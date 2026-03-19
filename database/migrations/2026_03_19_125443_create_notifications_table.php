<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // Clase de notificación
            $table->morphs('notifiable'); // Usuario o modelo que recibe la notificación (ya crea índice)
            $table->text('data'); // Datos de la notificación (JSON)
            $table->timestamp('read_at')->nullable(); // Fecha de lectura
            $table->timestamps();

            // Índice adicional solo para read_at
            $table->index('read_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
