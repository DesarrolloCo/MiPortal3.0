<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasColumn('novedades', 'MAL_ID')) {
            Schema::table('novedades', function (Blueprint $table) {
                $table->dropColumn('MAL_ID');
            });
        }

        foreach (['NOV_FECHA_INICIO', 'NOV_FECHA_FIN', 'NOV_HORA_INICIO', 'NOV_HORA_FIN'] as $column) {
            if (Schema::hasColumn('novedades', $column)) {
                Schema::table('novedades', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('novedades', 'MAL_ID')) {
            Schema::table('novedades', function (Blueprint $table) {
                $table->unsignedInteger('MAL_ID')->nullable()->after('NOV_ID');
                $table->foreign('MAL_ID')->references('MAL_ID')->on('mallas')->onDelete('set null');
            });
        }

        $columns = [
            'NOV_FECHA_INICIO' => fn (Blueprint $table) => $table->date('NOV_FECHA_INICIO')->nullable()->after('NOV_FECHA'),
            'NOV_FECHA_FIN' => fn (Blueprint $table) => $table->date('NOV_FECHA_FIN')->nullable()->after('NOV_FECHA_INICIO'),
            'NOV_HORA_INICIO' => fn (Blueprint $table) => $table->time('NOV_HORA_INICIO')->nullable()->after('NOV_FECHA_FIN'),
            'NOV_HORA_FIN' => fn (Blueprint $table) => $table->time('NOV_HORA_FIN')->nullable()->after('NOV_HORA_INICIO'),
        ];

        foreach ($columns as $column => $callback) {
            if (!Schema::hasColumn('novedades', $column)) {
                Schema::table('novedades', function (Blueprint $table) use ($callback) {
                    $callback($table);
                });
            }
        }
    }
};
