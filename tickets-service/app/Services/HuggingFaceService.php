<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    private $apiToken;
    private $apiUrl;

    public function __construct()
    {
        $this->apiToken = env('HF_API_TOKEN');
        $this->apiUrl = env('HF_API_URL', 'https://api-inference.huggingface.co/models/microsoft/Florence-2-large');
    }

    /**
     * Analiza una imagen y genera una descripción técnica detallada
     */
    public function analyzeImage($imagePath)
    {
        try {
            if (!$this->apiToken || $this->apiToken === 'your_hugging_face_token_here' || $this->apiToken === 'hf_demo_token_for_testing_purposes') {
                // Modo demostración - analizar contenido real de la imagen
                return $this->analyzeImageContent($imagePath);
            }

            // Obtener la imagen desde storage
            $fullPath = storage_path('app/public/' . $imagePath);
            if (!file_exists($fullPath)) {
                return [
                    'success' => false,
                    'error' => 'Archivo no encontrado',
                    'description' => 'No se pudo encontrar el archivo para analizar'
                ];
            }

            $imageData = file_get_contents($fullPath);

            // Realizar la petición a Hugging Face
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'inputs' => base64_encode($imageData),
                'parameters' => [
                    'prompt' => '<MORE_DETAILED_CAPTION>'
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Extraer la descripción del resultado
                $description = $this->extractDescription($result);
                
                // Generar análisis adicional
                $analysis = $this->generateTechnicalAnalysis($description, $imagePath);
                
                return [
                    'success' => true,
                    'description' => $description,
                    'analysis' => $analysis,
                    'raw_response' => $result
                ];
            } else {
                Log::error('Hugging Face API Error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => 'Error en la API de Hugging Face',
                    'description' => 'No se pudo procesar la imagen con IA'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Hugging Face Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error en el servicio de IA',
                'description' => 'Error interno al procesar la imagen'
            ];
        }
    }

    /**
     * Extrae la descripción del resultado de la API
     */
    private function extractDescription($result)
    {
        if (isset($result[0]['generated_text'])) {
            return $result[0]['generated_text'];
        }
        
        if (isset($result['generated_text'])) {
            return $result['generated_text'];
        }
        
        return 'No se pudo generar una descripción detallada';
    }

    /**
     * Genera un análisis técnico basado en la descripción
     */
    private function generateTechnicalAnalysis($description, $imagePath)
    {
        $analysis = [
            'descripcion_tecnica' => $description,
            'posibles_causas' => $this->suggestPossibleCauses($description),
            'categoria_sugerida' => $this->suggestCategory($description),
            'nivel_urgencia_sugerido' => $this->suggestUrgency($description),
            'resumen_ejecutivo' => $this->generateExecutiveSummary($description)
        ];

        return $analysis;
    }

    /**
     * Sugiere posibles causas basadas en la descripción
     */
    private function suggestPossibleCauses($description)
    {
        $causes = [];
        $description = strtolower($description);

        if (strpos($description, 'error') !== false || strpos($description, 'fail') !== false) {
            $causes[] = 'Error de software o configuración';
        }
        
        if (strpos($description, 'cable') !== false || strpos($description, 'connection') !== false) {
            $causes[] = 'Problema de conexión física';
        }
        
        if (strpos($description, 'screen') !== false || strpos($description, 'display') !== false) {
            $causes[] = 'Fallo en el hardware de visualización';
        }
        
        if (strpos($description, 'burn') !== false || strpos($description, 'damage') !== false) {
            $causes[] = 'Daño físico del componente';
        }
        
        if (strpos($description, 'power') !== false || strpos($description, 'electric') !== false) {
            $causes[] = 'Problema eléctrico o de fuente de poder';
        }

        if (empty($causes)) {
            $causes[] = 'Requiere análisis técnico manual';
        }

        return $causes;
    }

    /**
     * Sugiere una categoría basada en la descripción
     */
    private function suggestCategory($description)
    {
        $description = strtolower($description);

        if (strpos($description, 'screen') !== false || strpos($description, 'monitor') !== false) {
            return 'hardware';
        }
        
        if (strpos($description, 'software') !== false || strpos($description, 'application') !== false) {
            return 'software';
        }
        
        if (strpos($description, 'network') !== false || strpos($description, 'connection') !== false) {
            return 'comunicaciones';
        }
        
        if (strpos($description, 'email') !== false || strpos($description, 'mail') !== false) {
            return 'email';
        }
        
        if (strpos($description, 'platform') !== false || strpos($description, 'system') !== false) {
            return 'plataformas';
        }

        return 'otro';
    }

    /**
     * Sugiere un nivel de urgencia basado en la descripción
     */
    private function suggestUrgency($description)
    {
        $description = strtolower($description);

        if (strpos($description, 'critical') !== false || strpos($description, 'severe') !== false || strpos($description, 'burn') !== false) {
            return 'critica';
        }
        
        if (strpos($description, 'error') !== false || strpos($description, 'fail') !== false) {
            return 'alta';
        }
        
        if (strpos($description, 'warning') !== false || strpos($description, 'slow') !== false) {
            return 'media';
        }

        return 'baja';
    }

    /**
     * Genera un resumen ejecutivo
     */
    private function generateExecutiveSummary($description)
    {
        return "Análisis automático mediante IA: " . substr($description, 0, 200) . "...";
    }

    /**
     * Analiza múltiples imágenes y combina los resultados
     */
    public function analyzeMultipleImages($imagePaths)
    {
        $results = [];
        $combinedAnalysis = [
            'descriptions' => [],
            'posibles_causas' => [],
            'categoria_sugerida' => null,
            'nivel_urgencia_sugerido' => 'baja'
        ];

        foreach ($imagePaths as $imagePath) {
            $result = $this->analyzeImage($imagePath);
            $results[] = $result;

            if ($result['success']) {
                $combinedAnalysis['descriptions'][] = $result['description'];
                $combinedAnalysis['posibles_causas'] = array_merge(
                    $combinedAnalysis['posibles_causas'],
                    $result['analysis']['posibles_causas']
                );
            }
        }

        // Eliminar causas duplicadas
        $combinedAnalysis['posibles_causas'] = array_unique($combinedAnalysis['posibles_causas']);

        // Determinar la categoría y urgencia más comunes
        if (!empty($results)) {
            $categories = [];
            $urgencies = [];

            foreach ($results as $result) {
                if ($result['success']) {
                    $categories[] = $result['analysis']['categoria_sugerida'];
                    $urgencies[] = $result['analysis']['nivel_urgencia_sugerido'];
                }
            }

            if (!empty($categories)) {
                $combinedAnalysis['categoria_sugerida'] = $this->getMostCommon($categories);
            }

            if (!empty($urgencies)) {
                $combinedAnalysis['nivel_urgencia_sugerido'] = $this->getHighestUrgency($urgencies);
            }
        }

        $combinedAnalysis['resumen_ejecutivo'] = $this->generateCombinedSummary($combinedAnalysis['descriptions']);

        return [
            'success' => true,
            'individual_results' => $results,
            'combined_analysis' => $combinedAnalysis
        ];
    }

    /**
     * Obtiene el elemento más común de un array
     */
    private function getMostCommon($array)
    {
        $counts = array_count_values($array);
        arsort($counts);
        return key($counts);
    }

    /**
     * Obtiene el nivel de urgencia más alto
     */
    private function getHighestUrgency($urgencies)
    {
        $urgencyLevels = [
            'baja' => 1,
            'media' => 2,
            'alta' => 3,
            'critica' => 4
        ];

        $highest = 0;
        $result = 'baja';

        foreach ($urgencies as $urgency) {
            if (isset($urgencyLevels[$urgency]) && $urgencyLevels[$urgency] > $highest) {
                $highest = $urgencyLevels[$urgency];
                $result = $urgency;
            }
        }

        return $result;
    }

    /**
     * Genera un resumen combinado
     */
    private function generateCombinedSummary($descriptions)
    {
        if (empty($descriptions)) {
            return 'No se pudo generar análisis de las imágenes';
        }

        $combinedText = implode(' ', $descriptions);
        return "Análisis combinado de múltiples imágenes: " . substr($combinedText, 0, 300) . "...";
    }

    /**
     * Analiza el contenido real de una imagen usando funciones básicas de PHP
     */
    private function analyzeImageContent($imagePath)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            if (!file_exists($fullPath)) {
                return [
                    'success' => false,
                    'error' => 'Archivo no encontrado',
                    'description' => 'No se pudo encontrar el archivo para analizar'
                ];
            }

            // Verificar si GD está disponible
            if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
                // Usar sistema de respaldo basado en metadatos del archivo
                return $this->analyzeImageMetadata($imagePath, $fullPath);
            }

            // Obtener información básica de la imagen
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                return [
                    'success' => false,
                    'error' => 'No es una imagen válida',
                    'description' => 'El archivo no es una imagen válida'
                ];
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $type = $imageInfo[2];
            $mime = $imageInfo['mime'];
            
            // Crear recurso de imagen para análisis
            $imageResource = null;
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $imageResource = imagecreatefromjpeg($fullPath);
                    break;
                case IMAGETYPE_PNG:
                    $imageResource = imagecreatefrompng($fullPath);
                    break;
                case IMAGETYPE_GIF:
                    $imageResource = imagecreatefromgif($fullPath);
                    break;
                default:
                    return [
                        'success' => false,
                        'error' => 'Formato no soportado',
                        'description' => 'Formato de imagen no soportado para análisis'
                    ];
            }

            if (!$imageResource) {
                return [
                    'success' => false,
                    'error' => 'Error al procesar imagen',
                    'description' => 'No se pudo procesar la imagen'
                ];
            }

            // Análisis del contenido visual
            $analysis = $this->performVisualAnalysis($imageResource, $width, $height, $imagePath);
            
            // Liberar memoria
            imagedestroy($imageResource);

            return [
                'success' => true,
                'description' => $analysis['descripcion_tecnica'],
                'analysis' => $analysis,
                'demo_mode' => true
            ];

        } catch (\Exception $e) {
            Log::error('Error en análisis de contenido de imagen: ' . $e->getMessage());
            // Usar sistema de respaldo en caso de error
            return $this->analyzeImageMetadata($imagePath, $fullPath ?? '');
        }
    }

    /**
     * Analiza imagen basándose en metadatos cuando GD no está disponible
     */
    private function analyzeImageMetadata($imagePath, $fullPath)
    {
        try {
            $fileName = basename($imagePath);
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
            
            // Obtener información básica sin GD
            $imageInfo = @getimagesize($fullPath);
            $width = $imageInfo[0] ?? 0;
            $height = $imageInfo[1] ?? 0;
            $type = $imageInfo[2] ?? 0;
            $mime = $imageInfo['mime'] ?? 'unknown';
            
            // Análisis basado en metadatos y nombre de archivo
            $analysis = $this->generateAnalysisFromMetadata($fileName, $fileSize, $width, $height, $mime, $type);
            
            return [
                'success' => true,
                'description' => $analysis['descripcion_tecnica'],
                'analysis' => $analysis,
                'demo_mode' => true,
                'metadata_mode' => true
            ];
            
        } catch (\Exception $e) {
            Log::error('Error en análisis de metadatos: ' . $e->getMessage());
            // Último respaldo: análisis basado solo en nombre
            return $this->generateDemoAnalysis($imagePath);
        }
    }

    /**
     * Realiza análisis visual del contenido de la imagen
     */
    private function performVisualAnalysis($imageResource, $width, $height, $imagePath)
    {
        // Análisis básico de colores
        $totalPixels = $width * $height;
        $colorAnalysis = $this->analyzeColors($imageResource, $totalPixels);
        
        // Análisis de brillo y contraste
        $brightness = $this->calculateBrightness($imageResource, $totalPixels);
        
        // Análisis de dominancia de colores
        $dominantColors = $this->getDominantColors($imageResource);
        
        // Determinar tipo de contenido basado en características visuales
        $contentType = $this->determineContentType($colorAnalysis, $brightness, $dominantColors, $width, $height);
        
        // Generar análisis basado en el contenido detectado
        $analysis = $this->generateAnalysisFromContent($contentType, $colorAnalysis, $brightness, $dominantColors);
        
        return $analysis;
    }

    /**
     * Analiza la distribución de colores en la imagen
     */
    private function analyzeColors($imageResource, $totalPixels)
    {
        $width = imagesx($imageResource);
        $height = imagesy($imageResource);
        
        $redTotal = 0;
        $greenTotal = 0;
        $blueTotal = 0;
        $darkPixels = 0;
        $brightPixels = 0;
        
        // Muestreo de pixels (analizar cada 10º pixel para rendimiento)
        $step = max(1, min($width, $height) / 100);
        
        for ($x = 0; $x < $width; $x += $step) {
            for ($y = 0; $y < $height; $y += $step) {
                $rgb = imagecolorat($imageResource, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $redTotal += $r;
                $greenTotal += $g;
                $blueTotal += $b;
                
                $brightness = ($r + $g + $b) / 3;
                if ($brightness < 50) $darkPixels++;
                elseif ($brightness > 200) $brightPixels++;
            }
        }
        
        $sampledPixels = ($width / $step) * ($height / $step);
        
        return [
            'avg_red' => $redTotal / $sampledPixels,
            'avg_green' => $greenTotal / $sampledPixels,
            'avg_blue' => $blueTotal / $sampledPixels,
            'dark_ratio' => $darkPixels / $sampledPixels,
            'bright_ratio' => $brightPixels / $sampledPixels
        ];
    }

    /**
     * Calcula el brillo promedio de la imagen
     */
    private function calculateBrightness($imageResource, $totalPixels)
    {
        $width = imagesx($imageResource);
        $height = imagesy($imageResource);
        
        $totalBrightness = 0;
        $step = max(1, min($width, $height) / 50);
        $sampledPixels = 0;
        
        for ($x = 0; $x < $width; $x += $step) {
            for ($y = 0; $y < $height; $y += $step) {
                $rgb = imagecolorat($imageResource, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $totalBrightness += ($r + $g + $b) / 3;
                $sampledPixels++;
            }
        }
        
        return $sampledPixels > 0 ? $totalBrightness / $sampledPixels : 128;
    }

    /**
     * Obtiene los colores dominantes de la imagen
     */
    private function getDominantColors($imageResource)
    {
        $width = imagesx($imageResource);
        $height = imagesy($imageResource);
        
        $colorCounts = [];
        $step = max(1, min($width, $height) / 30);
        
        for ($x = 0; $x < $width; $x += $step) {
            for ($y = 0; $y < $height; $y += $step) {
                $rgb = imagecolorat($imageResource, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Agrupar colores similares
                $r = round($r / 32) * 32;
                $g = round($g / 32) * 32;
                $b = round($b / 32) * 32;
                
                $key = $r . ',' . $g . ',' . $b;
                $colorCounts[$key] = ($colorCounts[$key] ?? 0) + 1;
            }
        }
        
        arsort($colorCounts);
        return array_slice($colorCounts, 0, 3, true);
    }

    /**
     * Determina el tipo de contenido basado en características visuales
     */
    private function determineContentType($colorAnalysis, $brightness, $dominantColors, $width, $height)
    {
        $aspectRatio = $width / $height;
        
        // Analizar características para determinar contenido
        if ($colorAnalysis['dark_ratio'] > 0.7) {
            return 'dark_screen';
        } elseif ($brightness > 180 && $colorAnalysis['bright_ratio'] > 0.6) {
            return 'bright_screen';
        } elseif ($colorAnalysis['dark_ratio'] > 0.4 && $brightness < 100) {
            return 'error_message';
        } elseif (count($dominantColors) > 0) {
            $mainColor = explode(',', array_key_first($dominantColors));
            if (count($mainColor) >= 3) {
                $r = (int)$mainColor[0];
                $g = (int)$mainColor[1];
                $b = (int)$mainColor[2];
                
                if ($r > 200 && $g < 100 && $b < 100) {
                    return 'error_indicator';
                } elseif ($g > 150 && $r < 100 && $b < 100) {
                    return 'success_indicator';
                } elseif ($r > 200 && $g > 200 && $b < 100) {
                    return 'warning_indicator';
                }
            }
        }
        
        if ($aspectRatio > 1.5) {
            return 'wide_screenshot';
        } elseif ($aspectRatio < 0.8) {
            return 'vertical_content';
        }
        
        return 'general_image';
    }

    /**
     * Genera análisis basado en metadatos de la imagen
     */
    private function generateAnalysisFromMetadata($fileName, $fileSize, $width, $height, $mime, $type)
    {
        $fileName = strtolower($fileName);
        
        // Análisis basado en dimensiones y tamaño
        $aspectRatio = $width > 0 && $height > 0 ? $width / $height : 1;
        $megapixels = ($width * $height) / 1000000;
        $sizeMB = $fileSize / (1024 * 1024);
        
        // Determinar tipo de imagen por metadatos
        $contentType = $this->determineContentTypeFromMetadata($fileName, $aspectRatio, $megapixels, $sizeMB, $mime);
        
        // Generar análisis específico
        $analyses = [
            'screenshot_error' => [
                'descripcion_tecnica' => 'Captura de pantalla detectada con posibles indicadores de error. El análisis se basa en metadatos del archivo.',
                'posibles_causas' => ['Error de software visible', 'Problema de configuración', 'Fallo de aplicación'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'alta',
                'resumen_ejecutivo' => 'Análisis por metadatos: Captura de pantalla con posible error detectado.'
            ],
            'screenshot_normal' => [
                'descripcion_tecnica' => 'Captura de pantalla estándar detectada. El análisis se basa en las características del archivo.',
                'posibles_causas' => ['Documentación de interfaz', 'Referencia visual', 'Captura de funcionamiento'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'baja',
                'resumen_ejecutivo' => 'Análisis por metadatos: Captura de pantalla estándar detectada.'
            ],
            'photo_hardware' => [
                'descripcion_tecnica' => 'Fotografía de componente de hardware detectada. El análisis se basa en las propiedades de la imagen.',
                'posibles_causas' => ['Fallo físico del equipo', 'Desgaste de componentes', 'Daño visible'],
                'categoria_sugerida' => 'hardware',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis por metadatos: Fotografía de hardware detectada.'
            ],
            'document_scan' => [
                'descripcion_tecnica' => 'Documento escaneado o captura de texto detectada. El análisis se basa en metadatos del archivo.',
                'posibles_causas' => ['Documentación técnica', 'Manual de usuario', 'Referencia escrita'],
                'categoria_sugerida' => 'otro',
                'nivel_urgencia_sugerido' => 'baja',
                'resumen_ejecutivo' => 'Análisis por metadatos: Documento escaneado detectado.'
            ],
            'mobile_capture' => [
                'descripcion_tecnica' => 'Captura desde dispositivo móvil detectada. El análisis se basa en las dimensiones y orientación.',
                'posibles_causas' => ['Problema reportado vía móvil', 'Captura rápida', 'Referencia móvil'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis por metadatos: Captura móvil detectada.'
            ],
            'general_image' => [
                'descripcion_tecnica' => 'Imagen general detectada. El análisis se limita a metadatos debido a restricciones del sistema.',
                'posibles_causas' => ['Documentación general', 'Referencia visual', 'Captura no específica'],
                'categoria_sugerida' => 'otro',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis por metadatos: Imagen general detectada, requiere revisión manual.'
            ]
        ];
        
        return $analyses[$contentType] ?? $analyses['general_image'];
    }

    /**
     * Determina tipo de contenido basado en metadatos
     */
    private function determineContentTypeFromMetadata($fileName, $aspectRatio, $megapixels, $sizeMB, $mime)
    {
        // Análisis por nombre de archivo
        if (strpos($fileName, 'error') !== false || strpos($fileName, 'fail') !== false) {
            return 'screenshot_error';
        } elseif (strpos($fileName, 'screen') !== false || strpos($fileName, 'capture') !== false) {
            return 'screenshot_normal';
        } elseif (strpos($fileName, 'photo') !== false || strpos($fileName, 'img') !== false) {
            return 'photo_hardware';
        } elseif (strpos($fileName, 'doc') !== false || strpos($fileName, 'scan') !== false) {
            return 'document_scan';
        }
        
        // Análisis por dimensiones
        if ($aspectRatio < 0.8) {
            return 'mobile_capture'; // Vertical típico de móvil
        } elseif ($aspectRatio > 1.5) {
            return 'screenshot_normal'; // Panorámico típico de desktop
        } elseif ($megapixels > 2) {
            return 'photo_hardware'; // Alta resolución típica de foto
        } elseif ($sizeMB > 1) {
            return 'photo_hardware'; // Archivo grande típico de foto
        }
        
        // Análisis por MIME type
        if ($mime === 'image/jpeg' && $sizeMB > 0.5) {
            return 'photo_hardware';
        } elseif ($mime === 'image/png') {
            return 'screenshot_normal'; // PNG común en screenshots
        }
        
        return 'general_image';
    }

    /**
     * Genera análisis basado en el contenido detectado
     */
    private function generateAnalysisFromContent($contentType, $colorAnalysis, $brightness, $dominantColors)
    {
        $analyses = [
            'dark_screen' => [
                'descripcion_tecnica' => 'Imagen con pantalla oscura que indica posible fallo de visualización o sistema apagado. La baja luminosidad sugiere problemas de energía o display.',
                'posibles_causas' => ['Fallo en pantalla', 'Problema de energía', 'Sistema en modo de bajo consumo'],
                'categoria_sugerida' => 'hardware',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis visual: Pantalla oscura detectada, posible problema de hardware o energía.'
            ],
            'bright_screen' => [
                'descripcion_tecnica' => 'Captura de pantalla con alta luminosidad mostrando interfaz de sistema activa. Los colores brillantes indican funcionamiento normal del display.',
                'posibles_causas' => ['Funcionamiento normal', 'Configuración de brillo elevada', 'Interfaz activa'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'baja',
                'resumen_ejecutivo' => 'Análisis visual: Pantalla brillante detectada, sistema aparentemente funcional.'
            ],
            'error_message' => [
                'descripcion_tecnica' => 'Captura de pantalla mostrando mensaje de error con fondo oscuro. Los colores bajos y contraste alto sugieren ventana de error del sistema.',
                'posibles_causas' => ['Error de software', 'Excepción no controlada', 'Conflicto de configuración'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'alta',
                'resumen_ejecutivo' => 'Análisis visual: Mensaje de error detectado, requiere intervención técnica inmediata.'
            ],
            'error_indicator' => [
                'descripcion_tecnica' => 'Imagen con predominancia de colores rojos que indican alertas o errores críticos del sistema. Los tonos rojos sugieren fallos graves.',
                'posibles_causas' => ['Error crítico del sistema', 'Fallo de hardware', 'Problema de seguridad'],
                'categoria_sugerida' => 'hardware',
                'nivel_urgencia_sugerido' => 'critica',
                'resumen_ejecutivo' => 'Análisis visual: Indicadores de error crítico detectados, urgencia máxima requerida.'
            ],
            'success_indicator' => [
                'descripcion_tecnica' => 'Imagen con elementos verdes que indican estado operativo correcto. Los colores verdes sugieren funcionamiento normal del sistema.',
                'posibles_causas' => ['Operación normal', 'Proceso completado exitosamente', 'Sistema estable'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'baja',
                'resumen_ejecutivo' => 'Análisis visual: Indicadores de estado positivo detectados, sistema funcional.'
            ],
            'warning_indicator' => [
                'descripcion_tecnica' => 'Imagen con elementos amarillos que indican advertencias o alertas moderadas. Los tonos amarillos sugieren precaución.',
                'posibles_causas' => ['Advertencia del sistema', 'Configuración requerida', 'Mantenimiento necesario'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis visual: Indicadores de advertencia detectados, se recomienda atención.'
            ],
            'wide_screenshot' => [
                'descripcion_tecnica' => 'Captura de pantalla panorámica mostrando interfaz extendida del sistema. El formato amplio sugiere aplicación o escritorio completo.',
                'posibles_causas' => ['Captura de aplicación', 'Interfaz de escritorio', 'Vista extendida del sistema'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'baja',
                'resumen_ejecutivo' => 'Análisis visual: Captura de pantalla panorámica detectada, documentación de interfaz.'
            ],
            'vertical_content' => [
                'descripcion_tecnica' => 'Imagen con formato vertical que sugiere contenido móvil o documento específico. La orientación vertical indica posible captura de teléfono.',
                'posibles_causas' => ['Captura móvil', 'Documento vertical', 'Interfaz de aplicación móvil'],
                'categoria_sugerida' => 'software',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis visual: Contenido vertical detectado, posible origen móvil.'
            ],
            'general_image' => [
                'descripcion_tecnica' => 'Imagen general sin características específicas definidas. El contenido visual sugiere documentación o captura estándar.',
                'posibles_causas' => ['Documentación general', 'Captura de pantalla estándar', 'Referencia visual'],
                'categoria_sugerida' => 'otro',
                'nivel_urgencia_sugerido' => 'media',
                'resumen_ejecutivo' => 'Análisis visual: Imagen general detectada, requiere revisión manual detallada.'
            ]
        ];
        
        return $analyses[$contentType] ?? $analyses['general_image'];
    }

    /**
     * Crea análisis simulado basado en el nombre del archivo (método de respaldo)
     */
    private function createMockAnalysis($fileName)
    {
        $fileName = strtolower($fileName);
        
        // Análisis base para demostración
        $baseAnalysis = [
            'descripcion_tecnica' => 'Imagen analizada que muestra componentes técnicos con posibles anomalías. Se detectan patrones que sugieren necesidad de intervención técnica.',
            'posibles_causas' => ['Desgaste normal del equipo', 'Configuración incorrecta', 'Falta de mantenimiento preventivo'],
            'categoria_sugerida' => 'hardware',
            'nivel_urgencia_sugerido' => 'media',
            'resumen_ejecutivo' => 'Análisis automático mediante IA: La imagen muestra indicios de problema técnico que requiere atención especializada.'
        ];

        // Personalizar análisis según el nombre del archivo
        if (strpos($fileName, 'error') !== false || strpos($fileName, 'fail') !== false) {
            $baseAnalysis['descripcion_tecnica'] = 'Captura de pantalla mostrando mensaje de error crítico del sistema. Se observa código de error y stack trace que indica fallo en la aplicación.';
            $baseAnalysis['posibles_causas'] = ['Error de software', 'Configuración incorrecta', 'Incompatibilidad de versiones'];
            $baseAnalysis['categoria_sugerida'] = 'software';
            $baseAnalysis['nivel_urgencia_sugerido'] = 'alta';
        } elseif (strpos($fileName, 'screen') !== false || strpos($fileName, 'monitor') !== false) {
            $baseAnalysis['descripcion_tecnica'] = 'Imagen de pantalla de computadora mostrando interfaz del sistema con posibles anomalías visuales. Se detectan elementos fuera de lo normal.';
            $baseAnalysis['posibles_causas'] = ['Problema de drivers', 'Resolución incorrecta', 'Fallo en tarjeta gráfica'];
            $baseAnalysis['categoria_sugerida'] = 'hardware';
            $baseAnalysis['nivel_urgencia_sugerido'] = 'media';
        } elseif (strpos($fileName, 'cable') !== false || strpos($fileName, 'connection') !== false) {
            $baseAnalysis['descripcion_tecnica'] = 'Imagen de conexiones de red o cables que muestran posible mala conexión o daño físico. Se observan signos de desgaste.';
            $baseAnalysis['posibles_causas'] = ['Cable dañado', 'Conexión suelta', 'Problema de configuración de red'];
            $baseAnalysis['categoria_sugerida'] = 'comunicaciones';
            $baseAnalysis['nivel_urgencia_sugerido'] = 'media';
        } elseif (strpos($fileName, 'burn') !== false || strpos($fileName, 'damage') !== false) {
            $baseAnalysis['descripcion_tecnica'] = 'Imagen mostrando daño físico evidente en componente electrónico. Se observan marcas de quemadura o deterioro.';
            $baseAnalysis['posibles_causas'] = ['Sobrecarga eléctrica', 'Sobrecalentamiento', 'Falla del componente'];
            $baseAnalysis['categoria_sugerida'] = 'hardware';
            $baseAnalysis['nivel_urgencia_sugerido'] = 'critica';
        }

        return $baseAnalysis;
    }
}
