<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicoToReconocimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reconocimientos', function (Blueprint $table) {
            // Agregar columna 'publico' si no existe
            if (!Schema::hasColumn('reconocimientos', 'publico')) {
                $table->boolean('publico')->default(true)->after('fecha');
            }

            // Agregar columna 'destacado' si no existe
            if (!Schema::hasColumn('reconocimientos', 'destacado')) {
                $table->boolean('destacado')->default(false)->after('publico');
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
        Schema::table('reconocimientos', function (Blueprint $table) {
            // Eliminar columnas si existen
            if (Schema::hasColumn('reconocimientos', 'destacado')) {
                $table->dropColumn('destacado');
            }

            if (Schema::hasColumn('reconocimientos', 'publico')) {
                $table->dropColumn('publico');
            }
        });
    }
}
