<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToMallasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mallas', function (Blueprint $table) {
            // Índice compuesto para consultas de horarios por empleado y fecha
            // Usado en: obtenerHorariosEmpleado(), edit(), etc.
            $table->index(['EMP_ID', 'MAL_DIA'], 'idx_mallas_emp_dia');

            // Índice compuesto para consultas de horarios por campaña y fecha
            // Usado en: consultas grupales y reportes
            $table->index(['CAM_ID', 'MAL_DIA'], 'idx_mallas_cam_dia');

            // Índice compuesto para filtrar por fecha y estado
            // Usado en: consultas de horarios activos por fecha
            $table->index(['MAL_DIA', 'MAL_ESTADO'], 'idx_mallas_dia_estado');

            // Índice simple para filtrar por estado
            // Usado en: contar horarios activos/inactivos
            $table->index('MAL_ESTADO', 'idx_mallas_estado');

            // Índice para búsquedas por rango de fechas
            // Usado en: estadísticas y reportes por período
            $table->index('MAL_DIA', 'idx_mallas_dia');

            // Índice compuesto para validación de conflictos
            // Usado en: validarConflictos() - detectar solapamientos
            $table->index(['EMP_ID', 'MAL_DIA', 'MAL_INICIO', 'MAL_FINAL'], 'idx_mallas_conflictos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mallas', function (Blueprint $table) {
            // Eliminar índices en orden inverso
            $table->dropIndex('idx_mallas_conflictos');
            $table->dropIndex('idx_mallas_dia');
            $table->dropIndex('idx_mallas_estado');
            $table->dropIndex('idx_mallas_dia_estado');
            $table->dropIndex('idx_mallas_cam_dia');
            $table->dropIndex('idx_mallas_emp_dia');
        });
    }
}
