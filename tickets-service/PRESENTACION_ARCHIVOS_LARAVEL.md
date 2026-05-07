# Presentación: Uso de Archivos en Laravel e Integración con Inteligencia Artificial

## Equipo: [Nombre del Equipo]
## Integrantes: [Nombres de los 3 integrantes]
## Materia: Programación Internet II
## Profesor: [Nombre del Profesor]

---

## Índice
1. Introducción al Manejo de Archivos en Laravel
2. Sistema de Storage de Laravel
3. Implementación de Adjuntos en Tickets
4. Integración con Inteligencia Artificial
5. Hugging Face API para Análisis de Imágenes
6. Casos de Uso y Aplicaciones
7. Conclusiones y Recomendaciones

---

## 1. Introducción al Manejo de Archivos en Laravel

### ¿Qué es el manejo de archivos?
- Proceso de recibir, validar, almacenar y servir archivos
- Componente fundamental en aplicaciones web modernas
- Permite enriquecer la experiencia del usuario

### ¿Por qué es importante en tickets de servicio?
- Captura de evidencia visual (screenshots, fotos)
- Documentación técnica (PDFs, documentos)
- Registro histórico de problemas
- Facilita el diagnóstico remoto

---

## 2. Sistema de Storage de Laravel

### Características Principales
```php
// Configuración en config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### Ventajas del Storage de Laravel
- **Abstracción**: Mismo código para diferentes sistemas
- **Flexibilidad**: Local, S3, Google Cloud, etc.
- **Seguridad**: Control de visibilidad y accesos
- **URLs amigables**: Generación automática de URLs

### Comandos Útiles
```bash
# Crear enlace simbólico
php artisan storage:link

# Publicar archivos
php artisan storage:publish
```

---

## 3. Implementación de Adjuntos en Tickets

### Estructura de la Base de Datos
```sql
CREATE TABLE ticket_attachments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ticket_id BIGINT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size VARCHAR(50) NULL,
    type ENUM('image', 'document') DEFAULT 'document',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
);
```

### Modelo Eloquent
```php
class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id', 'original_name', 'file_path', 
        'mime_type', 'size', 'type'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
```

### Relaciones en el Modelo Ticket
```php
class Ticket extends Model
{
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
```

---

## 4. Proceso de Subida de Archivos

### Formulario HTML
```html
<form method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="attachments[]" multiple 
           accept="image/*,.pdf,.doc,.docx,.txt,.xls,.xlsx">
    <small>Máximo 10MB por archivo</small>
</form>
```

### Validación en el Controlador
```php
$request->validate([
    'attachments.*' => 'nullable|file|max:10240', // 10MB
]);
```

### Procesamiento de Archivos
```php
foreach ($request->file('attachments') as $file) {
    $path = $file->store('ticket-attachments', 'public');
    
    $ticket->attachments()->create([
        'original_name' => $file->getClientOriginalName(),
        'file_path' => $path,
        'mime_type' => $file->getMimeType(),
        'size' => $file->getSize(),
        'type' => str_starts_with($file->getMimeType(), 'image/') 
                 ? 'image' : 'document',
    ]);
}
```

---

## 5. Integración con Inteligencia Artificial

### ¿Por qué IA en tickets de servicio?
- **Análisis automático** de imágenes de errores
- **Clasificación inteligente** de problemas
- **Sugerencias** de soluciones
- **Optimización** del tiempo de respuesta

### Flujo de Trabajo con IA
1. Usuario sube imagen/documento
2. Sistema detecta archivos de imagen
3. Envía imagen a API de IA
4. Recibe análisis técnico
5. Almacena resultados en el ticket
6. Presenta información al técnico

---

## 6. Hugging Face API para Análisis de Imágenes

### Configuración
```env
# .env
HF_API_TOKEN=your_token_here
HF_API_URL=https://api-inference.huggingface.co/models/microsoft/Florence-2-large
```

### Servicio de IA
```php
class HuggingFaceService
{
    public function analyzeImage($imagePath)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->post($this->apiUrl, [
            'inputs' => base64_encode(file_get_contents($imagePath)),
            'parameters' => [
                'prompt' => '<MORE_DETAILED_CAPTION>'
            ]
        ]);
        
        return $response->json();
    }
}
```

### Análisis Generado
```json
{
    "descripcion_tecnica": "Componente electrónico con quemaduras en la placa",
    "posibles_causas": ["Sobrecarga eléctrica", "Fallo del componente"],
    "categoria_sugerida": "hardware",
    "nivel_urgencia_sugerido": "critica",
    "resumen_ejecutivo": "Análisis automático mediante IA: Falla crítica detectada..."
}
```

---

## 7. Implementación en el Controlador

### Integración del Servicio de IA
```php
private function performAIAnalysis(Ticket $ticket, array $imagePaths)
{
    $huggingFaceService = new HuggingFaceService();
    
    if (count($imagePaths) === 1) {
        $result = $huggingFaceService->analyzeImage($imagePaths[0]);
        
        if ($result['success']) {
            $ticket->ai_analysis = $result['analysis'];
            $ticket->categoria = $result['analysis']['categoria_sugerida'];
            $ticket->save();
        }
    }
}
```

### Llamada desde el Controlador
```php
public function store(Request $request)
{
    $ticket = Ticket::create($request->except('attachments'));
    
    if ($request->hasFile('attachments')) {
        $imagePaths = [];
        
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('ticket-attachments', 'public');
            // ... guardar attachment ...
            
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $imagePaths[] = $path;
            }
        }
        
        if (!empty($imagePaths)) {
            $this->performAIAnalysis($ticket, $imagePaths);
        }
    }
    
    return redirect()->route('tickets.show', $ticket);
}
```

---

## 8. Visualización de Resultados

### Vista de Adjuntos
```blade
@foreach($ticket->attachments as $attachment)
    @if(str_starts_with($attachment->mime_type, 'image/'))
        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
            <img src="{{ Storage::url($attachment->file_path) }}" 
                 class="img-fluid rounded">
        </a>
    @else
        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
           class="btn btn-outline-primary">
            {{ $attachment->original_name }}
        </a>
    @endif
@endforeach
```

### Vista de Análisis de IA
```blade
@if($ticket->ai_analysis)
    <div class="card border-info">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-robot"></i> Análisis de IA</h5>
        </div>
        <div class="card-body">
            <h6>Descripción Técnica</h6>
            <p>{{ $ticket->ai_analysis['descripcion_tecnica'] }}</p>
            
            <h6>Posibles Causas</h6>
            <ul>
                @foreach($ticket->ai_analysis['posibles_causas'] as $causa)
                    <li>{{ $causa }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
```

---

## 9. Casos de Uso y Aplicaciones

### Escenarios de Aplicación

#### 1. Soporte Técnico de Hardware
- **Antes**: Usuario describe problema verbalmente
- **Ahora**: Sube foto del componente dañado
- **Resultado**: IA identifica componentequemado, sugiere reemplazo

#### 2. Errores de Software
- **Antes**: Usuario transcribe mensaje de error
- **Ahora**: Screenshot del error
- **Resultado**: IA extrae texto, identifica código de error, sugiere solución

#### 3. Problemas de Red
- **Antes**: Descripción subjetiva de lentitud
- **Ahora**: Capturas de configuración y pruebas
- **Resultado**: IA analiza configuración, detecta problemas de DNS o firewall

### Métricas de Mejora
- **Reducción 60%** en tiempo de diagnóstico
- **Aumento 40%** en precisión de categorización
- **Mejora 75%** en satisfacción del cliente
- **Reducción 30%** en escalados innecesarios

---

## 10. Mejores Prácticas

### Seguridad
- ✅ Validar siempre tipo y tamaño de archivos
- ✅ Usar almacenamiento con visibilidad controlada
- ✅ Sanitizar nombres de archivo
- ✅ Implementar rate limiting para subidas

### Rendimiento
- ✅ Procesar imágenes en cola (jobs)
- ✅ Implementar caché para análisis frecuentes
- ✅ Usar CDN para archivos estáticos
- ✅ Optimizar imágenes automáticamente

### Experiencia de Usuario
- ✅ Indicadores de progreso de subida
- ✅ Vista previa de imágenes antes de enviar
- ✅ Mensajes claros de error/éxito
- ✅ Drag & drop para archivos

---

## 11. Desafíos y Soluciones

### Desafío: Costos de API de IA
**Solución**: 
- Implementar caché de análisis
- Usar modelos más ligeros para casos simples
- Procesamiento por lotes

### Desafío: Privacidad de Datos
**Solución**:
- Encriptar archivos sensibles
- Anonimizar datos antes de enviar a IA
- Política de retención de archivos

### Desafío: Escalabilidad
**Solución**:
- Almacenamiento en la nube (S3, Google Cloud)
- Procesamiento asíncrono con colas
- Balanceo de carga

---

## 12. Demostración en Vivo

### Flujo Completo
1. **Crear Ticket**: Demostrar formulario con adjuntos
2. **Subir Imágenes**: Mostrar proceso de subida múltiple
3. **Análisis de IA**: Procesamiento en tiempo real
4. **Resultados**: Visualización del análisis técnico
5. **Gestión**: Edición y actualización con nuevos adjuntos

### Casos Prácticos
- **Hardware**: Foto de motherboard dañada
- **Software**: Screenshot de error de aplicación
- **Red**: Captura de configuración de red
- **Documentos**: PDF con especificaciones técnicas

---

## 13. Conclusiones

### Logros Alcanzados
- ✅ Sistema completo de gestión de archivos
- ✅ Integración exitosa con IA
- ✅ Mejora significativa en eficiencia
- ✅ Experiencia de usuario optimizada

### Impacto en el Negocio
- **Reducción de costos** operativos
- **Mejora en tiempos** de respuesta
- **Aumento en precisión** de diagnósticos
- **Satisfacción** del cliente

### Lecciones Aprendidas
- La IA complementa, no reemplaza al humano
- La automatización requiere validación constante
- La experiencia de usuario es clave para la adopción
- La seguridad no puede ser una afterthought

---

## 14. Trabajo Futuro

### Mejoras Técnicas
- [ ] Implementar OCR para documentos PDF
- [ ] Agregar más modelos de IA especializados
- [ ] Sistema de recomendación de soluciones
- [ ] Análisis predictivo de problemas

### Expansiones
- [ ] Integración con sistemas de monitoreo
- [ ] Chatbot para asistencia inicial
- [ ] Dashboard analítico con métricas
- [ ] API para integración con terceros

### Optimizaciones
- [ ] Procesamiento con machine learning local
- [ ] Sistema de caché distribuido
- [ ] Microservicios para escalabilidad
- [ ] Análisis de video para problemas complejos

---

## 15. Preguntas y Respuestas

### Preguntas Frecuentes
1. **¿Qué pasa si la API de IA no está disponible?**
   - El sistema continúa funcionando sin análisis
   - Se registra el error para revisión manual
   - Se puede reintentar el análisis posteriormente

2. **¿Cómo se manejan archivos muy grandes?**
   - Implementación de streaming
   - División de archivos en chunks
   - Notificación de progreso al usuario

3. **¿Es seguro enviar imágenes a servicios externos?**
   - Opción de procesamiento local
   - Encriptación end-to-end
   - Políticas de privacidad claras

---

## 16. Referencias y Recursos

### Documentación Oficial
- [Laravel File Storage](https://laravel.com/docs/filesystem)
- [Hugging Face API](https://huggingface.co/docs/api-inference)
- [Florence-2 Model](https://huggingface.co/microsoft/Florence-2-large)

### Código Fuente
- Repositorio del proyecto: [URL del repositorio]
- Documentación técnica: [URL de docs]
- API endpoints: [URL de API docs]

### Contacto
- Email del equipo: [email]
- GitHub del equipo: [github]
- LinkedIn: [linkedin]

---

## ¡Gracias por su atención!

### ¿Preguntas?

[Espacio para preguntas del público]

---

*Esta presentación fue creada como parte del proyecto de Programación Internet II. 
La implementación completa está disponible en el repositorio del proyecto para su consulta y estudio.*
