<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinaryFileStorageToNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->longText('NOV_ARCHIVOS')->nullable()->change()->comment('Archivos almacenados en formato binario (base64)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->json('NOV_ARCHIVOS')->nullable()->change()->comment('Array de archivos adjuntos como soporte');
        });
    }
}
