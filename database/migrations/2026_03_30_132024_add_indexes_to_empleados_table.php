<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            // Índice en EMP_CEDULA para búsquedas rápidas de empleados por cédula
            // Usado frecuentemente en validaciones y búsquedas
            $table->index('EMP_CEDULA', 'idx_empleados_cedula');

            // Índice en EMP_CODE para búsquedas por código de empleado
            $table->index('EMP_CODE', 'idx_empleados_code');

            // Índice en EMP_EMAIL para validaciones de unicidad y búsquedas
            $table->index('EMP_EMAIL', 'idx_empleados_email');

            // Índice en EMP_ESTADO para filtrar empleados activos/inactivos
            // Usado en casi todas las consultas
            $table->index('EMP_ESTADO', 'idx_empleados_estado');

            // Índice en EMP_ACTIVO para filtrar empleados activos
            $table->index('EMP_ACTIVO', 'idx_empleados_activo');

            // Índice en USER_ID para joins con tabla users
            $table->index('USER_ID', 'idx_empleados_user_id');

            // Índice en CAR_ID para joins con tabla cargos
            $table->index('CAR_ID', 'idx_empleados_car_id');

            // Índice en CLI_ID para joins con tabla clientes
            $table->index('CLI_ID', 'idx_empleados_cli_id');

            // Índice en MUN_ID para joins con tabla municipios
            $table->index('MUN_ID', 'idx_empleados_mun_id');

            // Índice compuesto para consulta principal del index()
            // Optimiza: WHERE EMP_ESTADO = 1 ORDER BY EMP_NOMBRES
            $table->index(['EMP_ESTADO', 'EMP_NOMBRES'], 'idx_empleados_estado_nombres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            // Eliminar índices en orden inverso
            $table->dropIndex('idx_empleados_estado_nombres');
            $table->dropIndex('idx_empleados_mun_id');
            $table->dropIndex('idx_empleados_cli_id');
            $table->dropIndex('idx_empleados_car_id');
            $table->dropIndex('idx_empleados_user_id');
            $table->dropIndex('idx_empleados_activo');
            $table->dropIndex('idx_empleados_estado');
            $table->dropIndex('idx_empleados_email');
            $table->dropIndex('idx_empleados_code');
            $table->dropIndex('idx_empleados_cedula');
        });
    }
}
