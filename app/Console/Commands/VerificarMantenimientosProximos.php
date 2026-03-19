<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\mantenimiento;
use App\Models\User;
use App\Notifications\MantenimientoProximoNotification;
use Illuminate\Support\Facades\Notification;

class VerificarMantenimientosProximos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mantenimiento:verificar-proximos {--dias=7 : Días de anticipación para alertar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica mantenimientos próximos y vencidos, y envía notificaciones a los administradores';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dias = $this->option('dias');

        $this->info("Verificando mantenimientos próximos y vencidos...");

        // Obtener mantenimientos vencidos
        $mantenimientosVencidos = mantenimiento::vencidos()->get();

        $this->line("Mantenimientos vencidos encontrados: " . $mantenimientosVencidos->count());

        // Obtener mantenimientos próximos (en los próximos X días)
        $mantenimientosProximos = mantenimiento::proximos($dias)->get();

        $this->line("Mantenimientos próximos encontrados: " . $mantenimientosProximos->count());

        // Obtener usuarios administradores o personal de inventario para notificar
        // Ajustar según tu sistema de roles
        $usuariosNotificar = User::where('id_cargo', 1) // Ajustar según tu lógica de permisos
            ->orWhere('email', 'like', '%admin%')
            ->get();

        if ($usuariosNotificar->isEmpty()) {
            // Si no hay usuarios específicos, obtener todos los usuarios activos
            $usuariosNotificar = User::take(5)->get();
            $this->warn("No se encontraron administradores específicos. Usando primeros 5 usuarios.");
        }

        $notificacionesEnviadas = 0;

        // Procesar mantenimientos vencidos
        foreach ($mantenimientosVencidos as $mantenimiento) {
            if ($mantenimiento->equipo) {
                $this->line("  - Vencido: {$mantenimiento->equipo->EQU_NOMBRE} (Fecha: {$mantenimiento->MAN_FECHA_AGENDADA->format('d/m/Y')})");

                foreach ($usuariosNotificar as $usuario) {
                    try {
                        $usuario->notify(new MantenimientoProximoNotification($mantenimiento->MAN_ID));
                        $notificacionesEnviadas++;
                    } catch (\Exception $e) {
                        $this->error("Error al enviar notificación: " . $e->getMessage());
                    }
                }
            }
        }

        // Procesar mantenimientos próximos
        foreach ($mantenimientosProximos as $mantenimiento) {
            if ($mantenimiento->equipo) {
                $diasRestantes = $mantenimiento->diasRestantes();
                $this->line("  - Próximo: {$mantenimiento->equipo->EQU_NOMBRE} (en {$diasRestantes} días)");

                foreach ($usuariosNotificar as $usuario) {
                    try {
                        $usuario->notify(new MantenimientoProximoNotification($mantenimiento->MAN_ID));
                        $notificacionesEnviadas++;
                    } catch (\Exception $e) {
                        $this->error("Error al enviar notificación: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Proceso completado. Notificaciones enviadas: " . $notificacionesEnviadas);

        return 0;
    }
}
