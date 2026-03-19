<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Usuario que realizó la acción
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Información del modelo auditado
            $table->string('auditable_type'); // Clase del modelo (equipo, equ_asignado, etc.)
            $table->integer('auditable_id');   // ID del registro

            // Tipo de evento (created, updated, deleted, restored)
            $table->string('event', 50);

            // Valores antiguos y nuevos (JSON)
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();

            // Información adicional
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->text('url')->nullable();

            $table->timestamps();

            // Índices para mejorar consultas
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
