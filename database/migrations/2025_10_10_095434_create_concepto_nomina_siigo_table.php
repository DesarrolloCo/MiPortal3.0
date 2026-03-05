<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptoNominaSiigoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_nomina_siigo', function (Blueprint $table) {
            $table->id();
            $table->string('CODIGO', 10)->unique()->collation('utf8mb4_unicode_ci');
            $table->string('NOMBRE');
            $table->string('CONCEPTO_DIAN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto_nomina_siigo');
    }
}
