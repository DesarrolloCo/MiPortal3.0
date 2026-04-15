<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeDepIdNullableInEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Hace que DEP_ID sea nullable ya que no siempre se asigna en la importación
     *
     * @return void
     */
    public function up()
    {
        // Usar SQL directo para evitar problemas de compatibilidad con Doctrine DBAL
        DB::statement('ALTER TABLE empleados MODIFY DEP_ID INT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir DEP_ID a NOT NULL
        // ADVERTENCIA: Esto fallará si hay registros con DEP_ID NULL
        DB::statement('ALTER TABLE empleados MODIFY DEP_ID INT NOT NULL');
    }
}
