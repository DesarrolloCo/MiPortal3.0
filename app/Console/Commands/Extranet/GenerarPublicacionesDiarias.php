<?php

namespace App\Console\Commands\Extranet;

use Illuminate\Console\Command;
use App\Models\empleado;
use App\Models\emp_contrato;
use App\Models\Extranet\PublicacionMuro;
use Carbon\Carbon;

class GenerarPublicacionesDiarias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extranet:publicaciones-diarias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera publicaciones automáticas diarias en el muro de Extranet (cumpleaños, aniversarios, nuevos empleados)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando generación de publicaciones diarias...');

        $publicacionesCreadas = 0;

        // 1. Publicar cumpleaños del día
        $publicacionesCreadas += $this->publicarCumpleanosDelDia();

        // 2. Publicar aniversarios laborales del día
        $publicacionesCreadas += $this->publicarAniversariosDelDia();

        // 3. Publicar nuevos empleados de ayer
        $publicacionesCreadas += $this->publicarNuevosEmpleados();

        $this->info("✓ Proceso completado: {$publicacionesCreadas} publicaciones creadas.");

        return 0;
    }

    /**
     * Publicar cumpleaños del día actual
     */
    private function publicarCumpleanosDelDia()
    {
        $hoy = Carbon::now();
        $publicaciones = 0;

        $empleados = empleado::where('EMP_ACTIVO', 1)
            ->whereNotNull('EMP_FECHA_NACIMIENTO')
            ->whereRaw('MONTH(EMP_FECHA_NACIMIENTO) = ?', [$hoy->month])
            ->whereRaw('DAY(EMP_FECHA_NACIMIENTO) = ?', [$hoy->day])
            ->get();

        foreach ($empleados as $empleado) {
            // Verificar que no exista ya una publicación de cumpleaños para este empleado hoy
            $existe = PublicacionMuro::where('tipo', 'cumpleanos')
                ->where('referencia_id', $empleado->EMP_ID)
                ->whereDate('created_at', $hoy->toDateString())
                ->exists();

            if (!$existe) {
                $fechaNacimiento = Carbon::parse($empleado->EMP_FECHA_NACIMIENTO);
                $edad = $fechaNacimiento->age;

                PublicacionMuro::create([
                    'tipo' => 'cumpleanos',
                    'referencia_id' => $empleado->EMP_ID,
                    'titulo' => '¡Feliz Cumpleaños ' . explode(' ', $empleado->EMP_NOMBRES)[0] . '!',
                    'contenido' => "Hoy celebramos el cumpleaños de {$empleado->EMP_NOMBRES}. ¡Muchas felicidades en tu día! 🎂🎉",
                    'autor_id' => null, // Sistema
                    'destacado' => true,
                    'comentarios_habilitados' => true,
                ]);

                $publicaciones++;
                $this->line("  ✓ Publicación de cumpleaños creada para: {$empleado->EMP_NOMBRES}");
            }
        }

        return $publicaciones;
    }

    /**
     * Publicar aniversarios laborales del día actual
     */
    private function publicarAniversariosDelDia()
    {
        $hoy = Carbon::now();
        $publicaciones = 0;

        $contratos = emp_contrato::with('empleado')
            ->whereHas('empleado', function ($query) {
                $query->where('EMP_ACTIVO', 1);
            })
            ->whereNotNull('EMC_FECHA_INI')
            ->whereRaw('MONTH(EMC_FECHA_INI) = ?', [$hoy->month])
            ->whereRaw('DAY(EMC_FECHA_INI) = ?', [$hoy->day])
            ->whereRaw('YEAR(EMC_FECHA_INI) < ?', [$hoy->year]) // Debe tener al menos 1 año
            ->get();

        foreach ($contratos as $contrato) {
            $empleado = $contrato->empleado;

            // Verificar que no exista ya una publicación de aniversario para este empleado hoy
            $existe = PublicacionMuro::where('tipo', 'aniversario')
                ->where('referencia_id', $empleado->EMP_ID)
                ->whereDate('created_at', $hoy->toDateString())
                ->exists();

            if (!$existe) {
                $fechaInicio = Carbon::parse($contrato->EMC_FECHA_INI);
                $anosServicio = $fechaInicio->diffInYears($hoy);

                PublicacionMuro::create([
                    'tipo' => 'aniversario',
                    'referencia_id' => $empleado->EMP_ID,
                    'titulo' => "¡{$anosServicio} años en nuestra empresa!",
                    'contenido' => "Hoy celebramos {$anosServicio} " . ($anosServicio == 1 ? 'año' : 'años') . " de {$empleado->EMP_NOMBRES} en nuestra empresa. ¡Gracias por tu dedicación y compromiso! 🎊👏",
                    'autor_id' => null, // Sistema
                    'destacado' => true,
                    'comentarios_habilitados' => true,
                ]);

                $publicaciones++;
                $this->line("  ✓ Publicación de aniversario creada para: {$empleado->EMP_NOMBRES} ({$anosServicio} años)");
            }
        }

        return $publicaciones;
    }

    /**
     * Publicar empleados que ingresaron ayer
     */
    private function publicarNuevosEmpleados()
    {
        $ayer = Carbon::yesterday();
        $publicaciones = 0;

        $empleados = empleado::where('EMP_ACTIVO', 1)
            ->whereDate('created_at', $ayer->toDateString())
            ->get();

        foreach ($empleados as $empleado) {
            // Verificar que no exista ya una publicación de bienvenida para este empleado
            $existe = PublicacionMuro::where('tipo', 'nuevo_empleado')
                ->where('referencia_id', $empleado->EMP_ID)
                ->exists();

            if (!$existe) {
                PublicacionMuro::create([
                    'tipo' => 'nuevo_empleado',
                    'referencia_id' => $empleado->EMP_ID,
                    'titulo' => "¡Bienvenido/a al equipo!",
                    'contenido' => "Damos la bienvenida a {$empleado->EMP_NOMBRES}, quien se une a nuestro equipo. ¡Esperamos que tengas una excelente experiencia con nosotros! 🤝✨",
                    'autor_id' => null, // Sistema
                    'destacado' => true,
                    'comentarios_habilitados' => true,
                ]);

                $publicaciones++;
                $this->line("  ✓ Publicación de bienvenida creada para: {$empleado->EMP_NOMBRES}");
            }
        }

        return $publicaciones;
    }
}
