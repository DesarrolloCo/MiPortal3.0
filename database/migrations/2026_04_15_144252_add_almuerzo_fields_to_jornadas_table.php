<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlmuerzoFieldsToJornadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jornadas', function (Blueprint $table) {
            $table->integer('JOR_ALMUERZO_INICIO')->nullable()->after('JOR_FINAL');
            $table->integer('JOR_ALMUERZO_FIN')->nullable()->after('JOR_ALMUERZO_INICIO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jornadas', function (Blueprint $table) {
            $table->dropColumn(['JOR_ALMUERZO_INICIO', 'JOR_ALMUERZO_FIN']);
        });
    }
}
