<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedad_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('nov_id');
            $table->enum('action', ['arrived', 'forwarded', 'rejected', 'approved']);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('nov_id')->references('NOV_ID')->on('novedades')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('novedad_logs');
    }
}
