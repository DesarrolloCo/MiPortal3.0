<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToHorariosRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Índices para tabla empleados
        Schema::table('empleados', function (Blueprint $table) {
            // Índice compuesto para búsqueda de empleados activos por campaña
            // Usado en: GrupalController para obtener empleados de campaña
            if (!$this->indexExists('empleados', 'idx_empleados_cam_estado')) {
                $table->index(['CAM_ID', 'EMP_ESTADO'], 'idx_empleados_cam_estado');
            }

            // Índice compuesto para búsqueda de empleados activos
            // Usado en: listados de empleados, validaciones
            if (!$this->indexExists('empleados', 'idx_empleados_estado_activo')) {
                $table->index(['EMP_ESTADO', 'EMP_ACTIVO'], 'idx_empleados_estado_activo');
            }

            // Índice para búsqueda por nombres
            // Usado en: búsquedas y filtros
            if (!$this->indexExists('empleados', 'idx_empleados_nombres')) {
                $table->index('EMP_NOMBRES', 'idx_empleados_nombres');
            }

            // Índice para búsqueda por cédula
            // Usado en: búsquedas y validaciones
            if (!$this->indexExists('empleados', 'idx_empleados_cedula')) {
                $table->index('EMP_CEDULA', 'idx_empleados_cedula');
            }
        });

        // Índices para tabla horas
        Schema::table('horas', function (Blueprint $table) {
            // Índice para filtrar horas activas
            // Usado en: obtener catálogo de horas disponibles
            if (!$this->indexExists('horas', 'idx_horas_estado')) {
                $table->index('HOR_ESTADO', 'idx_horas_estado');
            }

            // Índice para ordenar horas
            // Usado en: obtener horas en orden
            if (!$this->indexExists('horas', 'idx_horas_id_estado')) {
                $table->index(['HOR_ID', 'HOR_ESTADO'], 'idx_horas_id_estado');
            }
        });

        // Índices para tabla jornadas
        Schema::table('jornadas', function (Blueprint $table) {
            // Índice para filtrar jornadas activas
            // Usado en: obtener catálogo de jornadas disponibles
            if (!$this->indexExists('jornadas', 'idx_jornadas_estado')) {
                $table->index('JOR_ESTADO', 'idx_jornadas_estado');
            }

            // Índice compuesto para jornadas con horas
            // Usado en: consultas de jornadas con rangos de horas
            if (!$this->indexExists('jornadas', 'idx_jornadas_horas')) {
                $table->index(['JOR_INICIO', 'JOR_FINAL', 'JOR_ESTADO'], 'idx_jornadas_horas');
            }
        });

        // Índices para tabla campanas
        Schema::table('campanas', function (Blueprint $table) {
            // Índice para filtrar campañas activas
            // Usado en: listados de campañas disponibles
            if (!$this->indexExists('campanas', 'idx_campanas_estado')) {
                $table->index('CAM_ESTADO', 'idx_campanas_estado');
            }

            // Índice para búsqueda por nombre
            // Usado en: búsquedas y filtros
            if (!$this->indexExists('campanas', 'idx_campanas_nombre')) {
                $table->index('CAM_NOMBRE', 'idx_campanas_nombre');
            }
        });

        // Índices para tabla clientes
        Schema::table('clientes', function (Blueprint $table) {
            // Índice para filtrar clientes activos
            // Usado en: listados de clientes disponibles
            if (!$this->indexExists('clientes', 'idx_clientes_estado')) {
                $table->index('CLI_ESTADO', 'idx_clientes_estado');
            }
        });

        // Índices para tabla uni_clis
        Schema::table('uni_clis', function (Blueprint $table) {
            // Índice compuesto para consultas de unidad-cliente
            // Usado en: obtener relaciones activas de unidad-cliente
            if (!$this->indexExists('uni_clis', 'idx_uniclis_estado')) {
                $table->index(['CLI_ID', 'UNI_ID', 'UNC_ESTADO'], 'idx_uniclis_estado');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar índices de uni_clis
        Schema::table('uni_clis', function (Blueprint $table) {
            if ($this->indexExists('uni_clis', 'idx_uniclis_estado')) {
                $table->dropIndex('idx_uniclis_estado');
            }
        });

        // Eliminar índices de clientes
        Schema::table('clientes', function (Blueprint $table) {
            if ($this->indexExists('clientes', 'idx_clientes_estado')) {
                $table->dropIndex('idx_clientes_estado');
            }
        });

        // Eliminar índices de campanas
        Schema::table('campanas', function (Blueprint $table) {
            if ($this->indexExists('campanas', 'idx_campanas_nombre')) {
                $table->dropIndex('idx_campanas_nombre');
            }
            if ($this->indexExists('campanas', 'idx_campanas_estado')) {
                $table->dropIndex('idx_campanas_estado');
            }
        });

        // Eliminar índices de jornadas
        Schema::table('jornadas', function (Blueprint $table) {
            if ($this->indexExists('jornadas', 'idx_jornadas_horas')) {
                $table->dropIndex('idx_jornadas_horas');
            }
            if ($this->indexExists('jornadas', 'idx_jornadas_estado')) {
                $table->dropIndex('idx_jornadas_estado');
            }
        });

        // Eliminar índices de horas
        Schema::table('horas', function (Blueprint $table) {
            if ($this->indexExists('horas', 'idx_horas_id_estado')) {
                $table->dropIndex('idx_horas_id_estado');
            }
            if ($this->indexExists('horas', 'idx_horas_estado')) {
                $table->dropIndex('idx_horas_estado');
            }
        });

        // Eliminar índices de empleados
        Schema::table('empleados', function (Blueprint $table) {
            if ($this->indexExists('empleados', 'idx_empleados_cedula')) {
                $table->dropIndex('idx_empleados_cedula');
            }
            if ($this->indexExists('empleados', 'idx_empleados_nombres')) {
                $table->dropIndex('idx_empleados_nombres');
            }
            if ($this->indexExists('empleados', 'idx_empleados_estado_activo')) {
                $table->dropIndex('idx_empleados_estado_activo');
            }
            if ($this->indexExists('empleados', 'idx_empleados_cam_estado')) {
                $table->dropIndex('idx_empleados_cam_estado');
            }
        });
    }

    /**
     * Verifica si un índice existe en una tabla
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableIndexes($table);

        return array_key_exists($indexName, $indexes);
    }
}
