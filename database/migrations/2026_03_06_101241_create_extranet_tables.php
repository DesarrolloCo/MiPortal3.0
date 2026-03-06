<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtranetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabla 1: Comunicados Internos
        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('contenido');
            $table->enum('tipo', ['general', 'urgente', 'rh', 'ti', 'operaciones', 'admin'])->default('general');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('archivo_url', 500)->nullable();
            $table->string('imagen_url', 500)->nullable();
            $table->integer('autor_id');
            $table->json('visible_para')->nullable()->comment('Array de roles o empleados');
            $table->boolean('fijado')->default(false);
            $table->enum('estado', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->unsignedInteger('vistas')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('estado');
            $table->index('tipo');
            $table->index('fecha_inicio');
            $table->index('fijado');
        });

        // Tabla 2: Proyectos
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->text('objetivo')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->enum('estado', ['planificacion', 'en_progreso', 'pausado', 'completado', 'cancelado'])->default('planificacion');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->unsignedTinyInteger('progreso')->default(0)->comment('0-100');
            $table->decimal('presupuesto', 15, 2)->nullable();
            $table->integer('responsable_id');
            $table->integer('departamento_id')->nullable();
            $table->integer('campana_id')->nullable();
            $table->json('etiquetas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('responsable_id')->references('EMP_ID')->on('empleados')->onDelete('cascade');
            $table->foreign('departamento_id')->references('DEP_ID')->on('departamentos')->onDelete('set null');
            $table->foreign('campana_id')->references('CAM_ID')->on('campanas')->onDelete('set null');
            $table->index('estado');
            $table->index('responsable_id');
        });

        // Tabla 3: Tareas de Proyecto
        Schema::create('tareas_proyecto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->integer('asignado_a')->nullable();
            $table->enum('estado', ['pendiente', 'en_progreso', 'revision', 'completada', 'cancelada'])->default('pendiente');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->date('fecha_vencimiento')->nullable();
            $table->dateTime('fecha_completada')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->json('dependencias')->nullable()->comment('IDs de tareas dependientes');
            $table->unsignedInteger('tiempo_estimado')->nullable()->comment('Horas');
            $table->unsignedInteger('tiempo_real')->nullable()->comment('Horas');
            $table->timestamps();

            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('asignado_a')->references('EMP_ID')->on('empleados')->onDelete('set null');
            $table->index('proyecto_id');
            $table->index('estado');
            $table->index('asignado_a');
        });

        // Tabla 4: Eventos Extranet
        Schema::create('eventos_extranet', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['reunion', 'capacitacion', 'celebracion', 'conferencia', 'team_building', 'otro'])->default('reunion');
            $table->enum('modalidad', ['presencial', 'virtual', 'hibrido'])->default('presencial');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('lugar', 255)->nullable();
            $table->string('link_virtual', 500)->nullable();
            $table->integer('organizador_id');
            $table->integer('departamento_id')->nullable();
            $table->string('imagen_url', 500)->nullable();
            $table->unsignedInteger('cupo_maximo')->nullable();
            $table->boolean('requiere_confirmacion')->default(false);
            $table->enum('estado', ['borrador', 'publicado', 'en_curso', 'finalizado', 'cancelado'])->default('borrador');
            $table->string('color', 7)->default('#007bff')->comment('Color hexadecimal');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organizador_id')->references('EMP_ID')->on('empleados')->onDelete('cascade');
            $table->foreign('departamento_id')->references('DEP_ID')->on('departamentos')->onDelete('set null');
            $table->index('fecha_inicio');
            $table->index('tipo');
            $table->index('estado');
        });

        // Tabla 5: Asistentes a Eventos
        Schema::create('asistentes_evento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->integer('empleado_id');
            $table->enum('estado_confirmacion', ['pendiente', 'confirmado', 'rechazado'])->default('pendiente');
            $table->boolean('asistio')->default(false);
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('eventos_extranet')->onDelete('cascade');
            $table->foreign('empleado_id')->references('EMP_ID')->on('empleados')->onDelete('cascade');
            $table->unique(['evento_id', 'empleado_id'], 'unique_asistente');
        });

        // Tabla 6: Galerías
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('evento_id')->nullable();
            $table->date('fecha');
            $table->integer('autor_id');
            $table->string('portada_url', 500)->nullable();
            $table->json('visible_para')->nullable();
            $table->unsignedInteger('total_fotos')->default(0);
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('eventos_extranet')->onDelete('set null');
            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('fecha');
        });

        // Tabla 7: Fotos de Galería
        Schema::create('fotos_galeria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('galeria_id');
            $table->string('archivo_url', 500);
            $table->string('descripcion', 500)->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->timestamps();

            $table->foreign('galeria_id')->references('id')->on('galerias')->onDelete('cascade');
            $table->index('galeria_id');
            $table->index('orden');
        });

        // Tabla 8: Reconocimientos
        Schema::create('reconocimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id');
            $table->enum('tipo', ['empleado_mes', 'aniversario', 'logro', 'excelencia', 'innovacion', 'trabajo_equipo', 'otro'])->default('logro');
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->integer('otorgado_por');
            $table->date('fecha');
            $table->string('imagen_url', 500)->nullable();
            $table->boolean('publico')->default(true);
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->foreign('empleado_id')->references('EMP_ID')->on('empleados')->onDelete('cascade');
            $table->foreign('otorgado_por')->references('id')->on('users')->onDelete('cascade');
            $table->index('empleado_id');
            $table->index('tipo');
            $table->index('fecha');
        });

        // Tabla 9: Encuestas
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->integer('autor_id');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->boolean('anonima')->default(true);
            $table->json('visible_para')->nullable();
            $table->enum('estado', ['borrador', 'activa', 'cerrada'])->default('borrador');
            $table->unsignedInteger('total_respuestas')->default(0);
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('estado');
            $table->index('fecha_inicio');
        });

        // Tabla 10: Preguntas de Encuesta
        Schema::create('preguntas_encuesta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encuesta_id');
            $table->text('pregunta');
            $table->enum('tipo_respuesta', ['texto_corto', 'texto_largo', 'opcion_multiple', 'checkbox', 'escala', 'fecha'])->default('texto_corto');
            $table->json('opciones')->nullable()->comment('Para opciones múltiples o checkbox');
            $table->integer('escala_min')->nullable();
            $table->integer('escala_max')->nullable();
            $table->boolean('obligatoria')->default(false);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index('encuesta_id');
            $table->index('orden');
        });

        // Tabla 11: Respuestas de Encuesta
        Schema::create('respuestas_encuesta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encuesta_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->integer('empleado_id')->nullable()->comment('NULL si es anónima');
            $table->text('respuesta');
            $table->timestamps();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('preguntas_encuesta')->onDelete('cascade');
            $table->foreign('empleado_id')->references('EMP_ID')->on('empleados')->onDelete('set null');
            $table->index('encuesta_id');
            $table->index('pregunta_id');
            $table->index('empleado_id');
        });

        // Tabla 12: Documentos Extranet
        Schema::create('documentos_extranet', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['politicas', 'manuales', 'formatos', 'reglamentos', 'procedimientos', 'capacitacion', 'otro'])->default('otro');
            $table->string('archivo_url', 500);
            $table->string('archivo_nombre', 255);
            $table->string('archivo_tipo', 100)->nullable();
            $table->unsignedInteger('archivo_tamano')->nullable()->comment('Bytes');
            $table->string('version', 20)->default('1.0');
            $table->integer('autor_id');
            $table->integer('departamento_id')->nullable();
            $table->json('visible_para')->nullable();
            $table->unsignedInteger('descargas')->default(0);
            $table->boolean('destacado')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('departamento_id')->references('DEP_ID')->on('departamentos')->onDelete('set null');
            $table->index('categoria');
            $table->index('departamento_id');
        });

        // Tabla 13: Publicaciones del Muro
        Schema::create('publicaciones_muro', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['comunicado', 'proyecto', 'evento', 'reconocimiento', 'cumpleanos', 'aniversario', 'nuevo_empleado', 'documento', 'encuesta', 'manual']);
            $table->unsignedBigInteger('referencia_id')->comment('ID del registro origen');
            $table->string('titulo', 255);
            $table->text('contenido')->nullable();
            $table->string('imagen_url', 500)->nullable();
            $table->integer('autor_id')->nullable();
            $table->boolean('destacado')->default(false);
            $table->boolean('comentarios_habilitados')->default(true);
            $table->unsignedInteger('total_comentarios')->default(0);
            $table->unsignedInteger('total_reacciones')->default(0);
            $table->unsignedInteger('vistas')->default(0);
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('set null');
            $table->index('tipo');
            $table->index(['created_at'], 'idx_created_at_desc');
            $table->index('destacado');
        });

        // Tabla 14: Comentarios Extranet
        Schema::create('comentarios_extranet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publicacion_id');
            $table->unsignedBigInteger('comentario_padre_id')->nullable()->comment('Para respuestas');
            $table->integer('autor_id');
            $table->text('contenido');
            $table->unsignedInteger('total_reacciones')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('publicacion_id')->references('id')->on('publicaciones_muro')->onDelete('cascade');
            $table->foreign('comentario_padre_id')->references('id')->on('comentarios_extranet')->onDelete('cascade');
            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('publicacion_id');
            $table->index('comentario_padre_id');
        });

        // Tabla 15: Reacciones Extranet
        Schema::create('reacciones_extranet', function (Blueprint $table) {
            $table->id();
            $table->string('reaccionable_type', 50)->comment('publicaciones_muro, comentarios_extranet');
            $table->unsignedBigInteger('reaccionable_id');
            $table->integer('autor_id');
            $table->enum('tipo', ['like', 'love', 'haha', 'wow', 'sad', 'angry'])->default('like');
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['reaccionable_type', 'reaccionable_id', 'autor_id'], 'unique_reaccion');
            $table->index(['reaccionable_type', 'reaccionable_id'], 'idx_reaccionable');
        });

        // Tabla 16: Notificaciones Extranet
        Schema::create('notificaciones_extranet', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id');
            $table->enum('tipo', ['comunicado', 'proyecto', 'evento', 'reconocimiento', 'comentario', 'reaccion', 'mencion', 'cumpleanos', 'aniversario', 'sistema']);
            $table->string('titulo', 255);
            $table->text('mensaje')->nullable();
            $table->string('referencia_tipo', 50)->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('icono', 50)->nullable();
            $table->string('color', 7)->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();
            $table->boolean('importante')->default(false);
            $table->timestamps();

            $table->foreign('empleado_id')->references('EMP_ID')->on('empleados')->onDelete('cascade');
            $table->index('empleado_id');
            $table->index('leida');
            $table->index(['created_at'], 'idx_notif_created_at_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificaciones_extranet');
        Schema::dropIfExists('reacciones_extranet');
        Schema::dropIfExists('comentarios_extranet');
        Schema::dropIfExists('publicaciones_muro');
        Schema::dropIfExists('documentos_extranet');
        Schema::dropIfExists('respuestas_encuesta');
        Schema::dropIfExists('preguntas_encuesta');
        Schema::dropIfExists('encuestas');
        Schema::dropIfExists('reconocimientos');
        Schema::dropIfExists('fotos_galeria');
        Schema::dropIfExists('galerias');
        Schema::dropIfExists('asistentes_evento');
        Schema::dropIfExists('eventos_extranet');
        Schema::dropIfExists('tareas_proyecto');
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('comunicados');
    }
}
