<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDevolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear tabla sin foreign keys primero
        // IMPORTANTE: Usar integer() sin unsigned para que coincida con las tablas existentes que usan int(11)
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->increments('DEV_ID');

            // Relación con la asignación (int(11) en equ_asignados)
            $table->integer('EAS_ID');

            // Datos de la devolución
            $table->date('DEV_FECHA_DEVOLUCION');
            $table->integer('DEV_RECIBIDO_POR')->nullable(); // FK a empleados.EMP_ID (int(11))

            // Estado del equipo al devolver
            $table->enum('DEV_ESTADO_EQUIPO', ['Bueno', 'Regular', 'Malo'])->default('Bueno');

            // Verificaciones
            $table->boolean('DEV_HARDWARE_COMPLETO')->default(true);
            $table->boolean('DEV_SOFTWARE_COMPLETO')->default(true);

            // Observaciones y daños
            $table->text('DEV_OBSERVACIONES')->nullable();
            $table->text('DEV_DANOS_REPORTADOS')->nullable();
            $table->text('DEV_FALTANTES')->nullable();

            // Auditoría (int(11) en users)
            $table->integer('DEV_USER_ID')->nullable(); // Usuario que registra la devolución

            // Ruta del PDF del acta
            $table->string('DEV_ACTA_PATH', 255)->nullable();

            // Estado del registro
            $table->integer('DEV_ESTADO')->default(1); // 1=activo, 0=anulado

            $table->timestamps();

            // Índices para foreign keys
            $table->index('EAS_ID');
            $table->index('DEV_RECIBIDO_POR');
            $table->index('DEV_USER_ID');
        });

        // Ajustar charset y collation para que coincida con las tablas existentes
        DB::statement('ALTER TABLE devoluciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin');

        // Agregar foreign keys después de ajustar el charset
        Schema::table('devoluciones', function (Blueprint $table) {
            $table->foreign('EAS_ID')->references('EAS_ID')->on('equ_asignados')->onDelete('restrict');
            $table->foreign('DEV_RECIBIDO_POR')->references('EMP_ID')->on('empleados')->onDelete('restrict');
            $table->foreign('DEV_USER_ID')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devoluciones');
    }
}
