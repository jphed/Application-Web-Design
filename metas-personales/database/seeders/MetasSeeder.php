<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\Milestone;
use App\Models\ProgressLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MetasSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@metas.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'motivation_msg' => 'Liderar con el ejemplo: cada meta cuenta.',
            'email_verified_at' => now(),
        ]);

        $maria = User::create([
            'name' => 'María López',
            'email' => 'maria@metas.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'motivation_msg' => 'Un paso cada día me acerca a mis sueños.',
            'email_verified_at' => now(),
        ]);

        $carlos = User::create([
            'name' => 'Carlos Ruiz',
            'email' => 'carlos@metas.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'motivation_msg' => 'La constancia vence a la perfección.',
            'email_verified_at' => now(),
        ]);

        $goalsData = [
            // María — 6 metas
            [
                'user_id' => $maria->id,
                'title' => 'Correr mi primera carrera de 10K',
                'description' => 'Prepararme para la Carrera Universitaria de primavera con un plan de 12 semanas.',
                'category' => 'fitness',
                'deadline' => '2026-06-15',
                'status' => 'active',
                'progress' => 55,
                'milestones' => [
                    ['title' => 'Completar 5 km sin parar', 'due_date' => '2026-03-01', 'completed' => true, 'order' => 0, 'notes' => 'Logrado en el parque La Sauceda.'],
                    ['title' => 'Entrenar 8 km continuos', 'due_date' => '2026-04-10', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Hacer tres entrenamientos de intervalos', 'due_date' => '2026-05-01', 'completed' => false, 'order' => 2, 'notes' => 'Falta la tercera sesión.'],
                    ['title' => 'Simulacro de 10 km', 'due_date' => '2026-05-25', 'completed' => false, 'order' => 3, 'notes' => null],
                    ['title' => 'Inscribirme oficialmente en la carrera', 'due_date' => '2026-06-01', 'completed' => false, 'order' => 4, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Empecé con caminatas de 30 minutos, tres veces por semana.', 'progress_value' => 10, 'logged_at' => '2026-01-15 07:30:00'],
                    ['note' => 'Ya corro 5 km; las piernas aguantan mejor que el mes pasado.', 'progress_value' => 35, 'logged_at' => '2026-02-20 08:00:00'],
                    ['note' => 'Aumenté a 7 km el fin de semana. Me falta ritmo en la última parte.', 'progress_value' => 55, 'logged_at' => '2026-03-28 19:15:00'],
                ],
            ],
            [
                'user_id' => $maria->id,
                'title' => 'Leer 6 libros de no ficción en 2026',
                'description' => 'Priorizar hábitos, finanzas personales y productividad.',
                'category' => 'lectura',
                'deadline' => '2026-12-31',
                'status' => 'active',
                'progress' => 33,
                'milestones' => [
                    ['title' => 'Terminar "Hábitos atómicos"', 'due_date' => '2026-02-28', 'completed' => true, 'order' => 0, 'notes' => 'Resumen hecho en Notion.'],
                    ['title' => 'Terminar "Padre rico, padre pobre"', 'due_date' => '2026-05-30', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Leer "Deep Work"', 'due_date' => '2026-08-15', 'completed' => false, 'order' => 2, 'notes' => 'Voy en el capítulo 4.'],
                    ['title' => 'Elegir y comprar los 3 libros restantes', 'due_date' => '2026-09-01', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Leo 20 páginas cada noche antes de dormir.', 'progress_value' => 15, 'logged_at' => '2026-01-10 22:00:00'],
                    ['note' => 'Segundo libro terminado; noté ideas para mi meta de ahorro.', 'progress_value' => 33, 'logged_at' => '2026-04-05 21:30:00'],
                ],
            ],
            [
                'user_id' => $maria->id,
                'title' => 'Ahorrar $15,000 MXN de fondo de emergencia',
                'description' => 'Apartar $1,250 mensuales en una cuenta separada hasta llegar a la meta.',
                'category' => 'finanzas',
                'deadline' => '2026-12-31',
                'status' => 'active',
                'progress' => 40,
                'milestones' => [
                    ['title' => 'Abrir cuenta de ahorro dedicada', 'due_date' => '2026-01-31', 'completed' => true, 'order' => 0, 'notes' => 'Cuenta en Nu, sin tarjeta vinculada.'],
                    ['title' => 'Primer depósito de $1,250', 'due_date' => '2026-02-05', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Llegar a $7,500 (mitad)', 'due_date' => '2026-07-01', 'completed' => false, 'order' => 2, 'notes' => 'Voy en $6,000.'],
                    ['title' => 'Completar los $15,000', 'due_date' => '2026-12-15', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Configuré transferencia automática el día 1 de cada mes.', 'progress_value' => 8, 'logged_at' => '2026-01-05 10:00:00'],
                    ['note' => 'Llevo $6,000; evité gastos impulsivos en comida a domicilio.', 'progress_value' => 40, 'logged_at' => '2026-04-01 18:45:00'],
                    ['note' => 'Vendí ropa que no usaba y deposité $800 extra.', 'progress_value' => 45, 'logged_at' => '2026-04-20 12:00:00'],
                ],
            ],
            [
                'user_id' => $maria->id,
                'title' => 'Dominar tablas dinámicas en Excel',
                'description' => 'Curso en línea para mejorar reportes en mi práctica profesional.',
                'category' => 'educación',
                'deadline' => '2026-05-30',
                'status' => 'paused',
                'progress' => 25,
                'milestones' => [
                    ['title' => 'Inscribirme al curso de Coursera', 'due_date' => '2026-02-01', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Completar módulos 1 y 2', 'due_date' => '2026-03-15', 'completed' => true, 'order' => 1, 'notes' => 'Pausé por exámenes finales.'],
                    ['title' => 'Hacer proyecto final con datos reales', 'due_date' => '2026-05-20', 'completed' => false, 'order' => 2, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Terminé el módulo de filtros y ordenamiento.', 'progress_value' => 25, 'logged_at' => '2026-03-01 16:00:00'],
                ],
            ],
            [
                'user_id' => $maria->id,
                'title' => 'Reducir consumo de azúcar refinada',
                'description' => 'Máximo 25 g diarios; reemplazar refrescos y postres por alternativas sanas.',
                'category' => 'salud',
                'deadline' => '2026-08-31',
                'status' => 'active',
                'progress' => 60,
                'milestones' => [
                    ['title' => 'Eliminar refrescos de la semana', 'due_date' => '2026-02-15', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => '30 días sin postre después de comer', 'due_date' => '2026-04-01', 'completed' => true, 'order' => 1, 'notes' => 'Fallé solo 3 días.'],
                    ['title' => 'Preparar snacks saludables los domingos', 'due_date' => null, 'completed' => true, 'order' => 2, 'notes' => 'Fruta, yogur y nueces.'],
                    ['title' => 'Mantener el hábito 90 días seguidos', 'due_date' => '2026-08-31', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'La primera semana fue difícil por los antojos de tarde.', 'progress_value' => 20, 'logged_at' => '2026-02-10 20:00:00'],
                    ['note' => 'Ya no pido refresco en la cafetería; solo agua o té.', 'progress_value' => 60, 'logged_at' => '2026-04-12 14:30:00'],
                ],
            ],
            [
                'user_id' => $maria->id,
                'title' => 'Voluntariado en comedor comunitario',
                'description' => 'Ayudar dos sábados al mes durante un semestre.',
                'category' => 'personal',
                'deadline' => '2026-11-30',
                'status' => 'done',
                'progress' => 100,
                'milestones' => [
                    ['title' => 'Contactar a la asociación civil', 'due_date' => '2026-01-20', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Asistir a capacitación inicial', 'due_date' => '2026-02-05', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Completar 8 turnos de servicio', 'due_date' => '2026-11-15', 'completed' => true, 'order' => 2, 'notes' => 'Cumplí 9 turnos.'],
                ],
                'logs' => [
                    ['note' => 'Primer día sirviendo almuerzos; muy cansado pero gratificante.', 'progress_value' => 30, 'logged_at' => '2026-02-15 13:00:00'],
                    ['note' => 'Meta cumplida: cerré el ciclo con una reunión de despedida del equipo.', 'progress_value' => 100, 'logged_at' => '2026-04-25 11:00:00'],
                ],
            ],
            // Carlos — 6 metas
            [
                'user_id' => $carlos->id,
                'title' => 'Alcanzar nivel B1 de inglés',
                'description' => 'Estudiar 45 minutos diarios y practicar conversación con un compañero de intercambio.',
                'category' => 'aprendizaje',
                'deadline' => '2026-10-31',
                'status' => 'active',
                'progress' => 45,
                'milestones' => [
                    ['title' => 'Terminar unidad 8 del curso en Duolingo', 'due_date' => '2026-03-01', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Ver 10 episodios de serie en inglés con subtítulos', 'due_date' => '2026-05-01', 'completed' => true, 'order' => 1, 'notes' => 'Friends, temporada 1.'],
                    ['title' => 'Agendar 5 sesiones de conversación', 'due_date' => '2026-07-01', 'completed' => false, 'order' => 2, 'notes' => 'Llevo 2 sesiones.'],
                    ['title' => 'Presentar examen de práctica B1', 'due_date' => '2026-09-15', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Instalé Anki para vocabulario; 20 tarjetas nuevas al día.', 'progress_value' => 15, 'logged_at' => '2026-01-20 19:00:00'],
                    ['note' => 'Puedo mantener una conversación básica de 10 minutos sin traducir.', 'progress_value' => 45, 'logged_at' => '2026-04-08 20:30:00'],
                    ['note' => 'Me corrigieron tiempos verbales en la última sesión de intercambio.', 'progress_value' => 50, 'logged_at' => '2026-04-18 21:00:00'],
                ],
            ],
            [
                'user_id' => $carlos->id,
                'title' => 'Bajar 8 kilos de forma saludable',
                'description' => 'Combinar déficit calórico moderado con entrenamiento de fuerza 3 veces por semana.',
                'category' => 'salud',
                'deadline' => '2026-09-30',
                'status' => 'active',
                'progress' => 50,
                'milestones' => [
                    ['title' => 'Consulta con nutriólogo universitario', 'due_date' => '2026-02-01', 'completed' => true, 'order' => 0, 'notes' => 'Plan de 1,800 kcal.'],
                    ['title' => 'Perder los primeros 3 kg', 'due_date' => '2026-04-01', 'completed' => true, 'order' => 1, 'notes' => 'Peso inicial 82 kg.'],
                    ['title' => 'Perder 3 kg adicionales', 'due_date' => '2026-07-01', 'completed' => false, 'order' => 2, 'notes' => 'Voy en -4 kg total.'],
                    ['title' => 'Alcanzar meta de 74 kg', 'due_date' => '2026-09-30', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Empecé a llevar registro en MyFitnessPal.', 'progress_value' => 10, 'logged_at' => '2026-02-05 07:00:00'],
                    ['note' => 'Bajé 4 kg; la ropa me queda mejor. Sigo con gym lunes-miércoles-viernes.', 'progress_value' => 50, 'logged_at' => '2026-04-15 08:30:00'],
                ],
            ],
            [
                'user_id' => $carlos->id,
                'title' => 'Entregar borrador de tesis antes de julio',
                'description' => 'Capítulo por capítulo con reuniones quincenales con mi asesor.',
                'category' => 'educación',
                'deadline' => '2026-07-15',
                'status' => 'active',
                'progress' => 70,
                'milestones' => [
                    ['title' => 'Entregar marco teórico', 'due_date' => '2026-02-28', 'completed' => true, 'order' => 0, 'notes' => 'Aprobado con observaciones menores.'],
                    ['title' => 'Completar metodología y resultados', 'due_date' => '2026-04-30', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Redactar conclusiones y anexos', 'due_date' => '2026-06-15', 'completed' => false, 'order' => 2, 'notes' => 'Borrador al 60%.'],
                    ['title' => 'Revisión final con asesor', 'due_date' => '2026-07-01', 'completed' => false, 'order' => 3, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Avancé 15 páginas esta semana; bloqueé distracciones con Pomodoro.', 'progress_value' => 40, 'logged_at' => '2026-03-10 23:00:00'],
                    ['note' => 'Capítulo de resultados terminado; solo faltan conclusiones.', 'progress_value' => 70, 'logged_at' => '2026-04-22 22:15:00'],
                    ['note' => 'Reunión con asesor: pidió ampliar la discusión de limitaciones.', 'progress_value' => 72, 'logged_at' => '2026-04-28 17:00:00'],
                ],
            ],
            [
                'user_id' => $carlos->id,
                'title' => 'Meditar 10 minutos cada mañana por 30 días',
                'description' => 'Usar la app Headspace para manejar estrés del semestre.',
                'category' => 'personal',
                'deadline' => '2026-05-31',
                'status' => 'done',
                'progress' => 100,
                'milestones' => [
                    ['title' => 'Completar los primeros 7 días', 'due_date' => '2026-03-07', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Llegar al día 15 sin saltar', 'due_date' => '2026-03-22', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Finalizar los 30 días consecutivos', 'due_date' => '2026-04-10', 'completed' => true, 'order' => 2, 'notes' => 'Racha de 32 días.'],
                ],
                'logs' => [
                    ['note' => 'Día 1: me costó concentrarme más de 5 minutos.', 'progress_value' => 3, 'logged_at' => '2026-03-01 06:30:00'],
                    ['note' => 'Día 20: noto menos ansiedad antes de clases.', 'progress_value' => 65, 'logged_at' => '2026-03-25 06:45:00'],
                    ['note' => 'Reto completado; seguiré meditando 5 días a la semana.', 'progress_value' => 100, 'logged_at' => '2026-04-12 07:00:00'],
                ],
            ],
            [
                'user_id' => $carlos->id,
                'title' => 'Viaje a Guanajuato con amigos',
                'description' => 'Ahorrar y planear itinerario para 4 días en semana santa.',
                'category' => 'personal',
                'deadline' => '2026-04-15',
                'status' => 'done',
                'progress' => 100,
                'milestones' => [
                    ['title' => 'Definir presupuesto por persona', 'due_date' => '2026-01-31', 'completed' => true, 'order' => 0, 'notes' => '$3,500 MXN c/u.'],
                    ['title' => 'Reservar hospedaje en el centro', 'due_date' => '2026-02-15', 'completed' => true, 'order' => 1, 'notes' => 'Airbnb confirmado.'],
                    ['title' => 'Comprar boletos de autobús', 'due_date' => '2026-03-01', 'completed' => true, 'order' => 2, 'notes' => null],
                    ['title' => 'Hacer el viaje', 'due_date' => '2026-04-10', 'completed' => true, 'order' => 3, 'notes' => 'Excelente experiencia.'],
                ],
                'logs' => [
                    ['note' => 'Juntamos $14,000 entre cuatro; falta reservar hotel.', 'progress_value' => 50, 'logged_at' => '2026-02-01 18:00:00'],
                    ['note' => 'Viaje hecho: visitamos el museo de las momias y el pipila.', 'progress_value' => 100, 'logged_at' => '2026-04-14 21:00:00'],
                ],
            ],
            [
                'user_id' => $carlos->id,
                'title' => 'Preparar certificación AWS Cloud Practitioner',
                'description' => 'Estudiar con ruta oficial y simulacros de examen.',
                'category' => 'aprendizaje',
                'deadline' => '2026-08-31',
                'status' => 'active',
                'progress' => 20,
                'milestones' => [
                    ['title' => 'Completar curso introductorio de AWS Skill Builder', 'due_date' => '2026-05-01', 'completed' => false, 'order' => 0, 'notes' => '40% del curso.'],
                    ['title' => 'Hacer 3 exámenes de práctica', 'due_date' => '2026-07-01', 'completed' => false, 'order' => 1, 'notes' => null],
                    ['title' => 'Agendar examen en Pearson VUE', 'due_date' => '2026-08-15', 'completed' => false, 'order' => 2, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Repasé servicios core: EC2, S3, IAM.', 'progress_value' => 20, 'logged_at' => '2026-04-01 19:30:00'],
                ],
            ],
            // Admin — 2 metas de ejemplo
            [
                'user_id' => $admin->id,
                'title' => 'Documentar guía de uso del sistema para alumnos',
                'description' => 'Manual breve con capturas para el curso de Programación en Internet II.',
                'category' => 'educación',
                'deadline' => '2026-05-20',
                'status' => 'active',
                'progress' => 35,
                'milestones' => [
                    ['title' => 'Esquema de secciones', 'due_date' => '2026-04-01', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Capturas del CRUD de metas', 'due_date' => '2026-04-25', 'completed' => true, 'order' => 1, 'notes' => null],
                    ['title' => 'Sección de API con Postman', 'due_date' => '2026-05-10', 'completed' => false, 'order' => 2, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'Redacté introducción y requisitos del sistema.', 'progress_value' => 20, 'logged_at' => '2026-04-05 10:00:00'],
                    ['note' => 'Agregué ejemplos de login API y listado de metas.', 'progress_value' => 35, 'logged_at' => '2026-04-20 11:30:00'],
                ],
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Revisar avance de metas del grupo en abril',
                'description' => 'Seguimiento mensual como administrador del sistema de demostración.',
                'category' => 'personal',
                'deadline' => '2026-04-30',
                'status' => 'done',
                'progress' => 100,
                'milestones' => [
                    ['title' => 'Exportar listado de metas activas', 'due_date' => '2026-04-15', 'completed' => true, 'order' => 0, 'notes' => null],
                    ['title' => 'Enviar retroalimentación a usuarios demo', 'due_date' => '2026-04-25', 'completed' => true, 'order' => 1, 'notes' => null],
                ],
                'logs' => [
                    ['note' => 'María y Carlos llevan buen ritmo en metas de salud y estudio.', 'progress_value' => 100, 'logged_at' => '2026-04-28 09:00:00'],
                ],
            ],
        ];

        foreach ($goalsData as $data) {
            $milestones = $data['milestones'];
            $logs = $data['logs'];
            unset($data['milestones'], $data['logs']);

            $goal = Goal::create($data);

            foreach ($milestones as $milestone) {
                $goal->milestones()->create($milestone);
            }

            foreach ($logs as $log) {
                $goal->progressLogs()->create($log);
            }
        }
    }
}
