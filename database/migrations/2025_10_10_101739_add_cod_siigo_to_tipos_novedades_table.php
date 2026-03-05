<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodSiigoToTiposNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_novedades', function (Blueprint $table) {
            $table->string('COD_SIIGO', 10)->nullable()->collation('utf8mb4_unicode_ci')->after('TIN_ESTADO');
            $table->foreign('COD_SIIGO')
                ->references('CODIGO')
                ->on('concepto_nomina_siigo')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_novedades', function (Blueprint $table) {
            $table->dropForeign(['COD_SIIGO']);
            $table->dropColumn('COD_SIIGO');
        });
    }
}
