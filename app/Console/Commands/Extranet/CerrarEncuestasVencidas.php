<?php

namespace App\Console\Commands\Extranet;

use Illuminate\Console\Command;
use App\Models\Extranet\Encuesta;
use Carbon\Carbon;

class CerrarEncuestasVencidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extranet:cerrar-encuestas-vencidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra automáticamente las encuestas cuya fecha límite ha expirado';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Buscando encuestas vencidas...');

        // Buscar encuestas activas que ya pasaron su fecha límite
        $encuestasVencidas = Encuesta::where('estado', 'activa')
            ->whereNotNull('fecha_fin')
            ->where('fecha_fin', '<', Carbon::now())
            ->get();

        if ($encuestasVencidas->isEmpty()) {
            $this->info('✅ No hay encuestas vencidas para cerrar.');
            return Command::SUCCESS;
        }

        $contador = 0;

        foreach ($encuestasVencidas as $encuesta) {
            $encuesta->update(['estado' => 'cerrada']);
            $contador++;

            $this->line("   ✓ Encuesta cerrada: {$encuesta->titulo} (ID: {$encuesta->id})");
        }

        $this->info("✅ Se cerraron {$contador} encuesta(s) vencida(s) exitosamente.");

        return Command::SUCCESS;
    }
}
