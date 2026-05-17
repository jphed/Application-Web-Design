# Documentación del Sistema de Metas Personales

## Descripción del Proyecto

El Sistema de Metas Personales es una aplicación web desarrollada con Laravel 11 que permite a los usuarios gestionar sus objetivos personales de manera estructurada. El sistema facilita el seguimiento de metas a través de hitos, registros de progreso y categorización, proporcionando una interfaz intuitiva tanto para usuarios regulares como para administradores.

### Características Principales

- **Gestión de Metas**: Creación, edición, eliminación y seguimiento de metas personales
- **Sistema de Hitos**: Desglose de metas en milestones o hitos intermedios
- **Registro de Progreso**: Bitácora de avances con notas y porcentajes
- **Categorización**: Organización de metas por categorías (salud, educación, finanzas, personal, fitness, lectura, aprendizaje)
- **Estados de Meta**: active, paused, done
- **Sistema de Roles**: Roles de usuario y administrador con permisos diferenciados
- **API RESTful**: Endpoints para integración con aplicaciones externas
- **Autenticación**: Sistema de login con Laravel Sanctum para API

### Stack Tecnológico

- **Backend**: Laravel 11 (PHP 8.2+)
- **Base de Datos**: MySQL
- **Autenticación API**: Laravel Sanctum
- **Frontend**: Blade Templates + Tailwind CSS
- **ORM**: Eloquent ORM

---

## Modelo de Datos

### Diagrama Entidad-Relación

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     Users       │       │     Goals       │       │  Milestones     │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │───┐   │ id (PK)         │───┐   │ id (PK)         │
│ name            │   │   │ user_id (FK)    │   │   │ goal_id (FK)    │
│ email           │   └──►│ title           │   └──►│ title           │
│ password        │       │ description     │       │ due_date        │
│ role            │       │ category        │       │ completed       │
│ motivation_msg  │       │ deadline        │       │ order           │
│ email_verified  │       │ status          │       │ notes           │
│ remember_token  │       │ progress        │       │ created_at      │
│ created_at      │       │ created_at      │       │ updated_at      │
│ updated_at      │       │ updated_at      │       └─────────────────┘
└─────────────────┘       └─────────────────┘
                                    │
                                    │
                                    ▼
                           ┌─────────────────┐
                           │ ProgressLogs    │
                           ├─────────────────┤
                           │ id (PK)         │
                           │ goal_id (FK)    │
                           │ note            │
                           │ progress_value  │
                           │ logged_at       │
                           └─────────────────┘
```

### Descripción de Tablas

#### Users
- **id**: Identificador único del usuario
- **name**: Nombre completo del usuario
- **email**: Correo electrónico (único)
- **password**: Contraseña hasheada
- **role**: Rol del usuario ('admin' o 'user')
- **motivation_msg**: Mensaje de motivación personal
- **email_verified_at**: Timestamp de verificación de email
- **remember_token**: Token para sesión persistente
- **created_at/updated_at**: Timestamps de registro

#### Goals
- **id**: Identificador único de la meta
- **user_id**: Foreign key a Users (cascade on delete)
- **title**: Título de la meta
- **description**: Descripción detallada (opcional)
- **category**: Categoría de la meta
- **deadline**: Fecha límite (opcional)
- **status**: Estado ('active', 'paused', 'done')
- **progress**: Porcentaje de progreso (0-100)
- **created_at/updated_at**: Timestamps de registro

#### Milestones
- **id**: Identificador único del hito
- **goal_id**: Foreign key a Goals (cascade on delete)
- **title**: Título del hito
- **due_date**: Fecha límite del hito (opcional)
- **completed**: Estado de completado (boolean)
- **order**: Orden de visualización
- **notes**: Notas adicionales (opcional)
- **created_at/updated_at**: Timestamps de registro

#### ProgressLogs
- **id**: Identificador único del registro
- **goal_id**: Foreign key a Goals (cascade on delete)
- **note**: Nota del progreso
- **progress_value**: Valor de progreso (0-100)
- **logged_at**: Timestamp del registro

### Relaciones

- **User → Goals**: One-to-Many (Un usuario tiene muchas metas)
- **Goal → Milestones**: One-to-Many (Una meta tiene muchos hitos)
- **Goal → ProgressLogs**: One-to-Many (Una meta tiene muchos registros de progreso)
- **Goal → User**: Many-to-One (Una meta pertenece a un usuario)
- **Milestone → Goal**: Many-to-One (Un hito pertenece a una meta)
- **ProgressLog → Goal**: Many-to-One (Un registro pertenece a una meta)

---

## Explicación del CRUD

### CRUD de Metas (Goals)

#### Controlador: `GoalController`

**Index (Listar)**
```php
public function index(Request $request)
```
- **Ruta**: GET `/goals` (web) o GET `/api/goals` (API)
- **Descripción**: Lista todas las metas del usuario autenticado
- **Filtros**: Por status y category
- **Paginación**: 10 elementos por página (web)
- **Autorización**: Usuarios ven sus metas, admin ve todas

**Create (Crear)**
```php
public function create()
```
- **Ruta**: GET `/goals/create` (web)
- **Descripción**: Muestra formulario de creación
- **Datos requeridos**: title, description, category, deadline, status, progress

**Store (Guardar)**
```php
public function store(StoreGoalRequest $request)
```
- **Ruta**: POST `/goals` (web) o POST `/api/goals` (API)
- **Descripción**: Crea una nueva meta
- **Validación**: StoreGoalRequest
- **Autorización**: Solo usuarios autenticados

**Show (Ver)**
```php
public function show(Goal $goal)
```
- **Ruta**: GET `/goals/{goal}` (web) o GET `/api/goals/{goal}` (API)
- **Descripción**: Muestra detalle de una meta con hitos y registros
- **Carga**: milestones, progressLogs, user
- **Autorización**: Dueño de la meta o admin

**Edit (Editar)**
```php
public function edit(Goal $goal)
```
- **Ruta**: GET `/goals/{goal}/edit` (web)
- **Descripción**: Muestra formulario de edición
- **Autorización**: Dueño de la meta o admin

**Update (Actualizar)**
```php
public function update(UpdateGoalRequest $request, Goal $goal)
```
- **Ruta**: PUT/PATCH `/goals/{goal}` (web) o PUT `/api/goals/{goal}` (API)
- **Descripción**: Actualiza una meta existente
- **Validación**: UpdateGoalRequest
- **Autorización**: Dueño de la meta o admin

**Destroy (Eliminar)**
```php
public function destroy(Goal $goal)
```
- **Ruta**: DELETE `/goals/{goal}` (web) o DELETE `/api/goals/{goal}` (API)
- **Descripción**: Elimina una meta (cascade con hitos y registros)
- **Autorización**: Dueño de la meta o admin

### CRUD de Hitos (Milestones)

#### Controlador: `MilestoneController`

**Store (Crear)**
```php
public function store(StoreMilestoneRequest $request, Goal $goal)
```
- **Ruta**: POST `/goals/{goal}/milestones` (web)
- **Descripción**: Crea un nuevo hito para una meta
- **Auto-orden**: Calcula orden automáticamente
- **Autorización**: Dueño de la meta o admin

**Update (Actualizar)**
```php
public function update(StoreMilestoneRequest $request, Goal $goal, Milestone $milestone)
```
- **Ruta**: PUT/PATCH `/goals/{goal}/milestones/{milestone}` (web)
- **Descripción**: Actualiza un hito existente
- **Validación**: Verifica que el hito pertenezca a la meta
- **Autorización**: Dueño de la meta o admin

**Toggle (Alternar estado)**
```php
public function toggle(Goal $goal, Milestone $milestone)
```
- **Ruta**: POST `/goals/{goal}/milestones/{milestone}/toggle` (web)
- **Descripción**: Alterna el estado completed del hito
- **Autorización**: Dueño de la meta o admin

**Destroy (Eliminar)**
```php
public function destroy(Goal $goal, Milestone $milestone)
```
- **Ruta**: DELETE `/goals/{goal}/milestones/{milestone}` (web)
- **Descripción**: Elimina un hito
- **Validación**: Verifica que el hito pertenezca a la meta
- **Autorización**: Dueño de la meta o admin

### CRUD de Registros de Progreso (ProgressLogs)

#### Controlador: `ProgressLogController`

**Store (Crear)**
```php
public function store(StoreProgressLogRequest $request, Goal $goal)
```
- **Ruta**: POST `/goals/{goal}/progress-logs` (web) o POST `/api/goals/{goal}/logs` (API)
- **Descripción**: Crea un registro de progreso
- **Auto-actualización**: Actualiza el progreso de la meta
- **Autorización**: Dueño de la meta o admin

**Destroy (Eliminar)**
```php
public function destroy(Goal $goal, ProgressLog $progressLog)
```
- **Ruta**: DELETE `/goals/{goal}/progress-logs/{progressLog}` (web)
- **Descripción**: Elimina un registro de progreso
- **Validación**: Verifica que el registro pertenezca a la meta
- **Autorización**: Dueño de la meta o admin

---

## Explicación del Uso de la API

### Autenticación

La API utiliza **Laravel Sanctum** para la autenticación mediante tokens.

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@metas.com",
  "password": "admin123"
}
```

**Respuesta:**
```json
{
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@metas.com",
    "role": "admin"
  }
}
```

#### Uso del Token
Para las rutas protegidas, incluir el token en el header:
```http
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

#### Logout
```http
POST /api/logout
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Respuesta:**
```json
{
  "message": "Sesión API cerrada."
}
```

### Endpoints de Metas

#### Listar Metas
```http
GET /api/goals
Authorization: Bearer {token}
```

**Parámetros opcionales:**
- `status`: Filtrar por estado (active, paused, done)
- `category`: Filtrar por categoría

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "title": "Correr mi primera carrera de 10K",
      "description": "Prepararme para la Carrera Universitaria...",
      "category": "fitness",
      "deadline": "2026-06-15",
      "status": "active",
      "progress": 55,
      "created_at": "2026-01-15T10:00:00.000000Z",
      "updated_at": "2026-04-28T15:30:00.000000Z"
    }
  ]
}
```

#### Crear Meta
```http
POST /api/goals
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Aprender Python",
  "description": "Completar curso básico de Python",
  "category": "aprendizaje",
  "deadline": "2026-12-31",
  "status": "active",
  "progress": 0
}
```

**Respuesta (201):**
```json
{
  "data": {
    "id": 10,
    "user_id": 2,
    "title": "Aprender Python",
    "description": "Completar curso básico de Python",
    "category": "aprendizaje",
    "deadline": "2026-12-31",
    "status": "active",
    "progress": 0,
    "created_at": "2026-05-17T18:00:00.000000Z",
    "updated_at": "2026-05-17T18:00:00.000000Z"
  }
}
```

#### Ver Meta
```http
GET /api/goals/{goal}
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "data": {
    "id": 1,
    "user_id": 2,
    "title": "Correr mi primera carrera de 10K",
    "description": "Prepararme para la Carrera Universitaria...",
    "category": "fitness",
    "deadline": "2026-06-15",
    "status": "active",
    "progress": 55,
    "created_at": "2026-01-15T10:00:00.000000Z",
    "updated_at": "2026-04-28T15:30:00.000000Z",
    "milestones": [...],
    "progress_logs": [...],
    "user": {...}
  }
}
```

#### Actualizar Meta
```http
PUT /api/goals/{goal}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Correr mi primera carrera de 10K",
  "status": "active",
  "progress": 60
}
```

#### Eliminar Meta
```http
DELETE /api/goals/{goal}
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "message": "Meta eliminada."
}
```

### Endpoints de Registros de Progreso

#### Listar Registros
```http
GET /api/goals/{goal}/logs
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "goal_id": 1,
      "note": "Empecé con caminatas de 30 minutos...",
      "progress_value": 10,
      "logged_at": "2026-01-15T07:30:00.000000Z"
    }
  ]
}
```

#### Crear Registro
```http
POST /api/goals/{goal}/logs
Authorization: Bearer {token}
Content-Type: application/json

{
  "note": "Aumenté a 7 km el fin de semana",
  "progress_value": 55,
  "logged_at": "2026-03-28T19:15:00"
}
```

**Respuesta (201):**
```json
{
  "data": {
    "id": 3,
    "goal_id": 1,
    "note": "Aumenté a 7 km el fin de semana",
    "progress_value": 55,
    "logged_at": "2026-03-28T19:15:00.000000Z"
  }
}
```

---

## Uso de Roles

### Roles Disponibles

#### 1. Admin (Administrador)
- **Permisos**: Acceso total a todas las metas del sistema
- **Funcionalidades**:
  - Ver, editar y eliminar metas de cualquier usuario
  - Acceso a dashboard administrativo
  - Gestión global del sistema

#### 2. User (Usuario Regular)
- **Permisos**: Acceso limitado a sus propias metas
- **Funcionalidades**:
  - Crear, ver, editar y eliminar sus propias metas
  - Gestionar hitos y registros de progreso
  - Dashboard personal con sus metas

### Implementación de Autorización

#### Trait: `AuthorizesGoals`

El sistema utiliza un trait compartido para manejar la autorización:

```php
protected function authorizeGoal(Goal $goal): void
{
    $user = auth()->user();

    if (! $user->isAdmin() && $goal->user_id !== $user->id) {
        abort(403, 'No tienes permiso para acceder a esta meta.');
    }
}
```

#### Método Helper en User
```php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

#### Query Builder con Roles
```php
protected function goalsQuery()
{
    $user = auth()->user();

    return $user->isAdmin()
        ? Goal::query()->with('user')
        : $user->goals();
}
```

### Ejemplos de Uso

**Usuario Admin:**
- Puede acceder a `/goals/1` aunque pertenezca al usuario 2
- Puede ver todas las metas en el index
- Puede modificar cualquier meta

**Usuario Regular:**
- Solo puede acceder a sus propias metas
- Si intenta acceder a `/goals/5` (que pertenece a otro usuario), recibe 403
- Solo ve sus metas en el index

---

## Manual de Usuario Básico

### Registro e Inicio de Sesión

1. **Crear Cuenta**: Los usuarios son creados por el administrador a través de seeders
2. **Iniciar Sesión**: Utilizar email y contraseña proporcionados
3. **Dashboard**: Al iniciar sesión, verás el dashboard con tus metas

### Gestión de Metas

#### Crear una Meta
1. Hacer clic en "Nueva Meta"
2. Completar el formulario:
   - **Título**: Nombre de la meta
   - **Descripción**: Detalles adicionales (opcional)
   - **Categoría**: Seleccionar de la lista
   - **Fecha límite**: Fecha objetivo (opcional)
   - **Estado**: active, paused o done
   - **Progreso**: Porcentaje inicial (0-100)
3. Hacer clic en "Guardar"

#### Ver Detalle de Meta
1. Hacer clic en el título de la meta
2. Ver información completa:
   - Detalles de la meta
   - Lista de hitos
   - Historial de progreso

#### Editar una Meta
1. Desde el detalle, hacer clic en "Editar"
2. Modificar los campos deseados
3. Hacer clic en "Actualizar"

#### Eliminar una Meta
1. Desde el detalle, hacer clic en "Eliminar"
2. Confirmar la acción

### Gestión de Hitos

#### Agregar un Hito
1. Desde el detalle de la meta, ir a la sección de hitos
2. Completar el formulario:
   - **Título**: Nombre del hito
   - **Fecha límite**: Fecha objetivo (opcional)
   - **Orden**: Número de orden (opcional, auto-calculado)
   - **Notas**: Detalles adicionales (opcional)
3. Hacer clic en "Agregar Hito"

#### Marcar Hito como Completado
1. Hacer clic en el checkbox del hito
2. El estado se actualiza automáticamente

#### Editar o Eliminar Hito
1. Hacer clic en los botones de editar/eliminar junto al hito
2. Confirmar la acción

### Registro de Progreso

#### Agregar Registro de Progreso
1. Desde el detalle de la meta, ir a la sección de progreso
2. Completar el formulario:
   - **Nota**: Descripción del avance
   - **Valor de progreso**: Porcentaje actual (0-100)
   - **Fecha**: Fecha del registro (opcional, usa actual)
3. Hacer clic en "Registrar Progreso"
4. El progreso de la meta se actualiza automáticamente

### Filtrado de Metas

- **Por Estado**: Usar el dropdown de estado en el index
- **Por Categoría**: Usar el dropdown de categoría en el index
- **Combinado**: Se pueden aplicar ambos filtros simultáneamente

---

## Problemas Encontrados y Solución

### 1. Autorización de Metas

**Problema**: Los usuarios regulares podían acceder a metas de otros usuarios mediante manipulación de URLs.

**Solución**: Implementación del trait `AuthorizesGoals` que verifica:
- Si el usuario es admin, permite acceso
- Si es usuario regular, verifica que la meta le pertenezca
- En caso contrario, retorna error 403

### 2. Actualización de Progreso

**Problema**: El progreso de la meta no se actualizaba automáticamente al agregar registros de progreso.

**Solución**: En el método `store` de `ProgressLogController`, se agregó:
```php
$goal->update(['progress' => $validated['progress_value']]);
```
Esto asegura que el progreso de la meta siempre refleje el último registro.

### 3. Orden de Hitos

**Problema**: Los hitos no tenían un orden definido, dificultando su visualización secuencial.

**Solución**: 
- Agregar campo `order` en la tabla milestones
- Auto-calcular el orden al crear: `$goal->milestones()->max('order') + 1`
- Ordenar por `order` en la relación: `orderBy('order')`

### 4. Validación de Relaciones

**Problema**: Posible manipulación de URLs para acceder a hitos o registros de progreso de metas ajenas.

**Solución**: Implementación de métodos de validación:
```php
protected function ensureMilestoneBelongsToGoal(Goal $goal, Milestone $milestone): void
{
    if ($milestone->goal_id !== $goal->id) {
        abort(404);
    }
}
```

### 5. Timestamps en ProgressLogs

**Problema**: La tabla `progress_logs` no necesitaba timestamps automáticos de Laravel.

**Solución**: Deshabilitar timestamps en el modelo:
```php
public $timestamps = false;
```
Usar campo personalizado `logged_at` para el registro temporal.

### 6. Categorías Dinámicas

**Problema**: Las categorías estaban hardcodeadas en múltiples lugares.

**Solución**: Crear método estático en el modelo Goal:
```php
public static function categories(): array
{
    return ['salud', 'educación', 'finanzas', 'personal', 'fitness', 'lectura', 'aprendizaje'];
}
```
Centralizar la definición y reutilizar en controladores y vistas.

### 7. Consultas Eficientes

**Problema**: Problema N+1 al cargar metas con relaciones.

**Solución**: Usar eager loading:
```php
$goal->load(['milestones', 'progressLogs', 'user']);
```
Reducir el número de consultas a la base de datos.

### 8. Cascade Delete

**Problema**: Al eliminar una meta, los hitos y registros de progreso quedaban huérfanos.

**Solución**: Configurar cascade on delete en migraciones:
```php
$table->foreignId('goal_id')->constrained()->cascadeOnDelete();
```
Automatizar la limpieza de registros relacionados.

---

## Conclusiones

### Logros del Proyecto

1. **Sistema Completo de Gestión de Metas**: Se implementó un sistema robusto que permite a los usuarios gestionar sus objetivos personales de manera efectiva, con funcionalidades de seguimiento, categorización y registro de progreso.

2. **Arquitectura Escalable**: La estructura del proyecto sigue las mejores prácticas de Laravel, con separación clara de responsabilidades (Models, Controllers, Requests, Views).

3. **API RESTful**: Se proporcionó una API completa para integración con aplicaciones externas, con autenticación mediante tokens y endpoints bien documentados.

4. **Sistema de Roles**: Implementación efectiva de roles (admin/user) con autorización granular que protege los datos de los usuarios.

5. **Experiencia de Usuario**: Interfaz intuitiva con filtrado, paginación y feedback visual que facilita el uso del sistema.

6. **Datos de Prueba**: Seeder completo con metas realistas que demuestran todas las funcionalidades del sistema.

### Tecnologías Aprendidas

- **Laravel 11**: Framework PHP moderno con características como typed properties, improved enums, y simplification de configuration.
- **Eloquent ORM**: Relaciones, eager loading, query scopes, y model events.
- **Laravel Sanctum**: Autenticación API basada en tokens.
- **Form Requests**: Validación centralizada y reutilizable.
- **Traits**: Reutilización de lógica de autorización.
- **Blade Templates**: Sistema de plantillas con componentes y layouts.
- **Migrations**: Gestión de versiones de base de datos con relaciones y cascade delete.

### Mejoras Futuras Posibles

1. **Notificaciones**: Sistema de notificaciones para recordatorios de deadlines y hitos.
2. **Gráficos y Estadísticas**: Dashboard con visualizaciones de progreso y métricas.
3. **Exportación**: Capacidad de exportar metas y progreso a PDF o Excel.
4. **Colaboración**: Permitir compartir metas con otros usuarios.
5. **Mobile App**: Aplicación móvil que consuma la API existente.
6. **Gamificación**: Sistema de puntos, badges y logros por completar metas.
7. **Integración con Calendarios**: Sincronización con Google Calendar o Outlook.
8. **Recomendaciones IA**: Sistema de sugerencias de metas basado en historial.

### Experiencia de Desarrollo

El desarrollo del Sistema de Metas Personales proporcionó una experiencia completa en el desarrollo de aplicaciones web con Laravel, desde el diseño del modelo de datos hasta la implementación de la API y la interfaz de usuario. El proyecto demostró la importancia de:

- **Planificación del Modelo de Datos**: Una buena estructura de base de datos es fundamental para el éxito del proyecto.
- **Seguridad**: La implementación correcta de autorización y validación es crucial para proteger los datos.
- **Escalabilidad**: Diseñar el código pensando en futuras mejoras y mantenimiento.
- **Documentación**: Mantener documentación clara y actualizada facilita el desarrollo y el uso del sistema.

El sistema está listo para ser utilizado en un entorno de producción y puede servir como base para proyectos más complejos de gestión de objetivos y seguimiento de progreso.
