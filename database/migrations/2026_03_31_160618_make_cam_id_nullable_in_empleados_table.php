<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeCamIdNullableInEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Hace que CAM_ID sea nullable ya que ahora se usa CLI_ID en su lugar
     *
     * @return void
     */
    public function up()
    {
        // Usar SQL directo para evitar problemas de compatibilidad con Doctrine DBAL
        DB::statement('ALTER TABLE empleados MODIFY CAM_ID INT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir CAM_ID a NOT NULL
        // ADVERTENCIA: Esto fallará si hay registros con CAM_ID NULL
        DB::statement('ALTER TABLE empleados MODIFY CAM_ID INT NOT NULL');
    }
}
