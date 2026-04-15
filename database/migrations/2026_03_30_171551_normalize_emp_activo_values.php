<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NormalizeEmpActivoValues extends Migration
{
    /**
     * Run the migrations.
     *
     * Normaliza los valores de EMP_ACTIVO de 'SI'/'NO' a 1/0
     *
     * @return void
     */
    public function up()
    {
        // Normalizar valores de EMP_ACTIVO de texto a numérico
        DB::statement("UPDATE empleados SET EMP_ACTIVO = 1 WHERE EMP_ACTIVO IN ('SI', 'si', 'S', 's', '1')");
        DB::statement("UPDATE empleados SET EMP_ACTIVO = 0 WHERE EMP_ACTIVO IN ('NO', 'no', 'N', 'n', '0') OR EMP_ACTIVO IS NULL");

        // Cambiar el tipo de columna a TINYINT si es necesario
        Schema::table('empleados', function (Blueprint $table) {
            // Esto asegura que la columna sea de tipo numérico
            $table->tinyInteger('EMP_ACTIVO')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir a valores de texto si es necesario
        DB::statement("UPDATE empleados SET EMP_ACTIVO = 'SI' WHERE EMP_ACTIVO = 1");
        DB::statement("UPDATE empleados SET EMP_ACTIVO = 'NO' WHERE EMP_ACTIVO = 0");

        // Cambiar el tipo de columna de vuelta a VARCHAR
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('EMP_ACTIVO', 10)->default('SI')->change();
        });
    }
}
