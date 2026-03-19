<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvidenciaPathToEviAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evi_asignados', function (Blueprint $table) {
            // Agregar columna para ruta del archivo en filesystem
            $table->string('EVI_EVIDENCIA_PATH', 255)->nullable()->after('EVI_EVIDENCIA');

            // Agregar índice para mejorar consultas
            $table->index('EAS_ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evi_asignados', function (Blueprint $table) {
            $table->dropColumn('EVI_EVIDENCIA_PATH');
            $table->dropIndex(['EAS_ID']);
        });
    }
}
