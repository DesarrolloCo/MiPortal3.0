<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedad_horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nov_id');
            $table->unsignedBigInteger('mal_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('nov_id')->references('NOV_ID')->on('novedades')->onDelete('cascade');
            $table->foreign('mal_id')->references('MAL_ID')->on('mallas')->onDelete('cascade');

            // Índice único para evitar duplicados
            $table->unique(['nov_id', 'mal_id'], 'unique_novedad_horario');

            // Índices para performance
            $table->index('nov_id');
            $table->index('mal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('novedad_horarios');
    }
}
