<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->text('NOV_DESCRIPCION')->nullable()->comment('Descripción detallada de la novedad');
            $table->date('NOV_FECHA_INICIO')->nullable()->comment('Fecha de inicio de la novedad');
            $table->date('NOV_FECHA_FIN')->nullable()->comment('Fecha de fin de la novedad (si aplica)');
            $table->time('NOV_HORA_INICIO')->nullable()->comment('Hora de inicio de la novedad');
            $table->time('NOV_HORA_FIN')->nullable()->comment('Hora de fin de la novedad');
            $table->json('NOV_ARCHIVOS')->nullable()->comment('Array de archivos adjuntos como soporte');
            $table->enum('NOV_ESTADO_APROBACION', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente')->comment('Estado de aprobación');
            $table->text('NOV_OBSERVACIONES')->nullable()->comment('Observaciones de gestión humana');
            $table->unsignedInteger('NOV_APROBADO_POR')->nullable()->comment('ID del usuario que aprobó/rechazó');
            $table->timestamp('NOV_FECHA_APROBACION')->nullable()->comment('Fecha de aprobación/rechazo');

            $table->foreign('NOV_APROBADO_POR')->references('id')->on('users')->onDelete('set null');
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
            $table->dropForeign(['NOV_APROBADO_POR']);
            $table->dropColumn([
                'NOV_DESCRIPCION',
                'NOV_FECHA_INICIO',
                'NOV_FECHA_FIN',
                'NOV_HORA_INICIO',
                'NOV_HORA_FIN',
                'NOV_ARCHIVOS',
                'NOV_ESTADO_APROBACION',
                'NOV_OBSERVACIONES',
                'NOV_APROBADO_POR',
                'NOV_FECHA_APROBACION'
            ]);
        });
    }
}
