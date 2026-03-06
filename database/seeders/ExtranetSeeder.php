<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Extranet\Comunicado;
use App\Models\Extranet\Proyecto;
use App\Models\Extranet\TareaProyecto;
use App\Models\Extranet\EventoExtranet;
use App\Models\Extranet\Reconocimiento;
use App\Models\Extranet\Encuesta;
use App\Models\Extranet\PreguntaEncuesta;
use App\Models\Extranet\DocumentoExtranet;
use App\Models\Extranet\PublicacionMuro;
use App\Models\User;
use App\Models\empleado;
use Carbon\Carbon;

class ExtranetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener primer usuario y empleado para usar como datos de prueba
        $usuario = User::first();
        $empleado = empleado::where('EMP_ACTIVO', 1)->first();

        if (!$usuario || !$empleado) {
            $this->command->error('No se encontraron usuarios o empleados activos. Ejecuta primero los seeders principales.');
            return;
        }

        $this->command->info('Creando datos de prueba para Extranet...');

        // 1. COMUNICADOS DE PRUEBA
        $this->command->info('Creando comunicados...');

        $comunicado1 = Comunicado::create([
            'titulo' => 'Bienvenidos a la Nueva Extranet Corporativa',
            'contenido' => '<p>Nos complace anunciar el lanzamiento de nuestra nueva <strong>Extranet Corporativa</strong>, una plataforma diseñada para mejorar la comunicación y colaboración entre todos los miembros de la empresa.</p><p>A través de esta herramienta podrán:</p><ul><li>Mantenerse informados con comunicados oficiales</li><li>Seguir el progreso de proyectos departamentales</li><li>Participar en eventos corporativos</li><li>Acceder a documentos importantes</li><li>Y mucho más...</li></ul><p>¡Esperamos que disfruten de esta nueva experiencia!</p>',
            'tipo' => 'general',
            'prioridad' => 'alta',
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addDays(30),
            'autor_id' => $usuario->id,
            'fijado' => true,
            'estado' => 'publicado',
        ]);

        $comunicado2 = Comunicado::create([
            'titulo' => 'Actualización de Políticas de Teletrabajo',
            'contenido' => '<p>Se informa a todos los colaboradores que se han actualizado las <strong>políticas de teletrabajo</strong>.</p><p><strong>Principales cambios:</strong></p><ul><li>Hasta 3 días de teletrabajo por semana previa aprobación</li><li>Horario flexible entre 7:00 AM y 7:00 PM</li><li>Disponibilidad obligatoria en horario core (10:00 AM - 3:00 PM)</li></ul><p>Para más información, consulta el documento adjunto o contacta a Recursos Humanos.</p>',
            'tipo' => 'rh',
            'prioridad' => 'media',
            'fecha_inicio' => Carbon::now()->subDays(5),
            'fecha_fin' => Carbon::now()->addDays(25),
            'autor_id' => $usuario->id,
            'fijado' => false,
            'estado' => 'publicado',
        ]);

        $comunicado3 = Comunicado::create([
            'titulo' => 'Mantenimiento Programado de Sistemas - Sábado 15',
            'contenido' => '<p><strong>IMPORTANTE:</strong> El próximo sábado 15 de marzo se realizará un mantenimiento programado de nuestros sistemas.</p><p><strong>Horario:</strong> 2:00 AM - 6:00 AM</p><p><strong>Sistemas afectados:</strong></p><ul><li>MiPortal</li><li>Sistema de nómina</li><li>Correo electrónico</li></ul><p>Por favor, planifica tus actividades considerando esta interrupción temporal del servicio.</p>',
            'tipo' => 'ti',
            'prioridad' => 'critica',
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addDays(10),
            'autor_id' => $usuario->id,
            'fijado' => true,
            'estado' => 'publicado',
        ]);

        // 2. PROYECTOS DE PRUEBA
        $this->command->info('Creando proyectos...');

        $proyecto1 = Proyecto::create([
            'nombre' => 'Migración a MiPortal 3.0',
            'descripcion' => 'Proyecto de actualización de la plataforma MiPortal a la versión 3.0 con nuevas funcionalidades y mejoras de rendimiento.',
            'objetivo' => 'Modernizar la plataforma, mejorar la experiencia de usuario y agregar el módulo de Extranet',
            'fecha_inicio' => Carbon::now()->subDays(30),
            'fecha_fin' => Carbon::now()->addDays(60),
            'estado' => 'en_progreso',
            'prioridad' => 'alta',
            'progreso' => 45,
            'responsable_id' => $empleado->EMP_ID,
        ]);

        // Tareas del proyecto 1
        TareaProyecto::create([
            'proyecto_id' => $proyecto1->id,
            'titulo' => 'Diseñar nueva interfaz de usuario',
            'descripcion' => 'Crear wireframes y mockups de la nueva interfaz',
            'asignado_a' => $empleado->EMP_ID,
            'estado' => 'completada',
            'prioridad' => 'alta',
            'fecha_completada' => Carbon::now()->subDays(20),
        ]);

        TareaProyecto::create([
            'proyecto_id' => $proyecto1->id,
            'titulo' => 'Desarrollar módulo de Extranet',
            'descripcion' => 'Implementar funcionalidades del módulo de Extranet (comunicados, proyectos, eventos)',
            'asignado_a' => $empleado->EMP_ID,
            'estado' => 'en_progreso',
            'prioridad' => 'alta',
            'fecha_vencimiento' => Carbon::now()->addDays(15),
        ]);

        TareaProyecto::create([
            'proyecto_id' => $proyecto1->id,
            'titulo' => 'Pruebas de integración',
            'descripcion' => 'Ejecutar pruebas de integración con los módulos existentes',
            'asignado_a' => $empleado->EMP_ID,
            'estado' => 'pendiente',
            'prioridad' => 'media',
            'fecha_vencimiento' => Carbon::now()->addDays(30),
        ]);

        $proyecto2 = Proyecto::create([
            'nombre' => 'Renovación de Infraestructura TI',
            'descripcion' => 'Actualización de servidores y equipos de red de la empresa',
            'objetivo' => 'Mejorar el rendimiento y seguridad de la infraestructura tecnológica',
            'fecha_inicio' => Carbon::now()->subDays(60),
            'fecha_fin' => Carbon::now()->subDays(5),
            'fecha_fin_real' => Carbon::now()->subDays(5),
            'estado' => 'completado',
            'prioridad' => 'alta',
            'progreso' => 100,
            'responsable_id' => $empleado->EMP_ID,
        ]);

        // 3. EVENTOS DE PRUEBA
        $this->command->info('Creando eventos...');

        EventoExtranet::create([
            'titulo' => 'Capacitación: Uso de MiPortal 3.0',
            'descripcion' => 'Capacitación obligatoria sobre las nuevas funcionalidades de MiPortal 3.0',
            'tipo' => 'capacitacion',
            'modalidad' => 'presencial',
            'fecha_inicio' => Carbon::now()->addDays(7)->setTime(9, 0),
            'fecha_fin' => Carbon::now()->addDays(7)->setTime(11, 0),
            'hora_inicio' => '09:00:00',
            'hora_fin' => '11:00:00',
            'lugar' => 'Sala de Capacitaciones - Piso 3',
            'organizador_id' => $empleado->EMP_ID,
            'cupo_maximo' => 30,
            'requiere_confirmacion' => true,
            'estado' => 'publicado',
            'color' => '#28a745',
        ]);

        EventoExtranet::create([
            'titulo' => 'Team Building: Actividades Recreativas',
            'descripcion' => 'Jornada de integración y actividades recreativas para fortalecer el trabajo en equipo',
            'tipo' => 'team_building',
            'modalidad' => 'presencial',
            'fecha_inicio' => Carbon::now()->addDays(14)->setTime(14, 0),
            'fecha_fin' => Carbon::now()->addDays(14)->setTime(18, 0),
            'hora_inicio' => '14:00:00',
            'hora_fin' => '18:00:00',
            'lugar' => 'Parque Central',
            'organizador_id' => $empleado->EMP_ID,
            'requiere_confirmacion' => true,
            'estado' => 'publicado',
            'color' => '#17a2b8',
        ]);

        EventoExtranet::create([
            'titulo' => 'Conferencia: Tendencias Tecnológicas 2026',
            'descripcion' => 'Conferencia virtual sobre las últimas tendencias en tecnología y transformación digital',
            'tipo' => 'conferencia',
            'modalidad' => 'virtual',
            'fecha_inicio' => Carbon::now()->addDays(21)->setTime(15, 0),
            'fecha_fin' => Carbon::now()->addDays(21)->setTime(17, 0),
            'hora_inicio' => '15:00:00',
            'hora_fin' => '17:00:00',
            'link_virtual' => 'https://meet.google.com/abc-defg-hij',
            'organizador_id' => $empleado->EMP_ID,
            'requiere_confirmacion' => false,
            'estado' => 'publicado',
            'color' => '#6f42c1',
        ]);

        // 4. RECONOCIMIENTOS DE PRUEBA
        $this->command->info('Creando reconocimientos...');

        Reconocimiento::create([
            'empleado_id' => $empleado->EMP_ID,
            'tipo' => 'excelencia',
            'titulo' => 'Excelencia en Servicio al Cliente',
            'descripcion' => 'Por su dedicación excepcional y atención al detalle en la atención a nuestros clientes durante el último trimestre',
            'otorgado_por' => $usuario->id,
            'fecha' => Carbon::now()->subDays(3),
            'publico' => true,
            'destacado' => true,
        ]);

        // 5. ENCUESTAS DE PRUEBA
        $this->command->info('Creando encuestas...');

        $encuesta1 = Encuesta::create([
            'titulo' => 'Encuesta de Clima Organizacional 2026',
            'descripcion' => 'Tu opinión es muy importante para nosotros. Por favor responde esta breve encuesta sobre el ambiente laboral.',
            'autor_id' => $usuario->id,
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addDays(15),
            'anonima' => true,
            'estado' => 'activa',
        ]);

        PreguntaEncuesta::create([
            'encuesta_id' => $encuesta1->id,
            'pregunta' => '¿Cómo calificarías el ambiente de trabajo en tu área?',
            'tipo_respuesta' => 'escala',
            'escala_min' => 1,
            'escala_max' => 5,
            'obligatoria' => true,
            'orden' => 1,
        ]);

        PreguntaEncuesta::create([
            'encuesta_id' => $encuesta1->id,
            'pregunta' => '¿Cuál es tu nivel de satisfacción con las herramientas tecnológicas disponibles?',
            'tipo_respuesta' => 'opcion_multiple',
            'opciones' => json_encode(['Muy satisfecho', 'Satisfecho', 'Neutral', 'Insatisfecho', 'Muy insatisfecho']),
            'obligatoria' => true,
            'orden' => 2,
        ]);

        PreguntaEncuesta::create([
            'encuesta_id' => $encuesta1->id,
            'pregunta' => '¿Qué sugerencias tienes para mejorar el ambiente laboral?',
            'tipo_respuesta' => 'texto_largo',
            'obligatoria' => false,
            'orden' => 3,
        ]);

        // 6. DOCUMENTOS DE PRUEBA
        $this->command->info('Creando documentos...');

        DocumentoExtranet::create([
            'titulo' => 'Manual de Usuario - MiPortal 3.0',
            'descripcion' => 'Guía completa sobre el uso de las funcionalidades de MiPortal 3.0',
            'categoria' => 'manuales',
            'archivo_url' => '/storage/documentos/manual-miportal-3.0.pdf',
            'archivo_nombre' => 'manual-miportal-3.0.pdf',
            'archivo_tipo' => 'application/pdf',
            'archivo_tamano' => 2048000,
            'version' => '1.0',
            'autor_id' => $usuario->id,
            'destacado' => true,
        ]);

        DocumentoExtranet::create([
            'titulo' => 'Política de Teletrabajo 2026',
            'descripcion' => 'Normativa actualizada sobre el trabajo remoto',
            'categoria' => 'politicas',
            'archivo_url' => '/storage/documentos/politica-teletrabajo-2026.pdf',
            'archivo_nombre' => 'politica-teletrabajo-2026.pdf',
            'archivo_tipo' => 'application/pdf',
            'archivo_tamano' => 512000,
            'version' => '2.0',
            'autor_id' => $usuario->id,
            'destacado' => true,
        ]);

        DocumentoExtranet::create([
            'titulo' => 'Formato de Solicitud de Vacaciones',
            'descripcion' => 'Formato oficial para solicitud de días de vacaciones',
            'categoria' => 'formatos',
            'archivo_url' => '/storage/documentos/formato-vacaciones.docx',
            'archivo_nombre' => 'formato-vacaciones.docx',
            'archivo_tipo' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'archivo_tamano' => 128000,
            'version' => '1.0',
            'autor_id' => $usuario->id,
            'destacado' => false,
        ]);

        // 7. PUBLICACIONES EN EL MURO
        $this->command->info('Creando publicaciones del muro...');

        PublicacionMuro::create([
            'tipo' => 'comunicado',
            'referencia_id' => $comunicado1->id,
            'titulo' => $comunicado1->titulo,
            'contenido' => strip_tags($comunicado1->contenido),
            'autor_id' => $usuario->id,
            'destacado' => true,
            'comentarios_habilitados' => true,
        ]);

        PublicacionMuro::create([
            'tipo' => 'proyecto',
            'referencia_id' => $proyecto1->id,
            'titulo' => 'Nuevo proyecto: ' . $proyecto1->nombre,
            'contenido' => $proyecto1->descripcion,
            'autor_id' => $usuario->id,
            'destacado' => false,
            'comentarios_habilitados' => true,
        ]);

        PublicacionMuro::create([
            'tipo' => 'reconocimiento',
            'referencia_id' => 1,
            'titulo' => '¡Felicitaciones! Reconocimiento otorgado',
            'contenido' => 'Excelencia en Servicio al Cliente para ' . $empleado->EMP_NOMBRES . ' ' . $empleado->EMP_APELLIDOS,
            'autor_id' => $usuario->id,
            'destacado' => true,
            'comentarios_habilitados' => true,
        ]);

        $this->command->info('');
        $this->command->info('✅ Datos de prueba de Extranet creados exitosamente!');
        $this->command->info('');
        $this->command->info('📊 Resumen:');
        $this->command->info('   - ' . Comunicado::count() . ' comunicados');
        $this->command->info('   - ' . Proyecto::count() . ' proyectos');
        $this->command->info('   - ' . TareaProyecto::count() . ' tareas');
        $this->command->info('   - ' . EventoExtranet::count() . ' eventos');
        $this->command->info('   - ' . Reconocimiento::count() . ' reconocimientos');
        $this->command->info('   - ' . Encuesta::count() . ' encuestas');
        $this->command->info('   - ' . PreguntaEncuesta::count() . ' preguntas de encuesta');
        $this->command->info('   - ' . DocumentoExtranet::count() . ' documentos');
        $this->command->info('   - ' . PublicacionMuro::count() . ' publicaciones en el muro');
        $this->command->info('');
    }
}
