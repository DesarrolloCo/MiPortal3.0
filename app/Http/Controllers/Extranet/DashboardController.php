<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\Comunicado;
use App\Models\Extranet\Proyecto;
use App\Models\Extranet\EventoExtranet;
use App\Models\Extranet\PublicacionMuro;
use App\Models\Extranet\Reconocimiento;
use App\Models\Extranet\Encuesta;
use App\Models\Extranet\DocumentoExtranet;
use App\Models\Extranet\Galeria;
use App\Models\empleado;
use App\Models\emp_contrato;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal de Extranet
     */
    public function index()
    {
        // Widget 1: Cumpleaños del mes
        $cumpleanos = $this->getCumpleanosDelMes();

        // Widget 2: Aniversarios laborales del mes
        $aniversarios = $this->getAniversariosDelMes();

        // Widget 3: Nuevos empleados (últimos 30 días)
        $nuevosEmpleados = $this->getNuevosEmpleados();

        // Widget 4: Eventos próximos
        $eventosProximos = $this->getEventosProximos();

        // Widget 5: Proyectos activos
        $proyectosActivos = $this->getProyectosActivos();

        // Widget 6: Estadísticas generales
        $estadisticas = $this->getEstadisticasGenerales();

        // Publicaciones recientes del muro
        $publicaciones = PublicacionMuro::with(['autor', 'reacciones', 'comentarios'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Comunicados fijados
        $comunicadosFijados = Comunicado::publicados()
            ->fijados()
            ->vigentes()
            ->orderBy('prioridad', 'desc')
            ->take(3)
            ->get();

        // Encuestas pendientes de responder
        $encuestasPendientes = $this->getEncuestasPendientes();

        // Reconocimientos recientes
        $reconocimientosRecientes = $this->getReconocimientosRecientes();

        // Documentos destacados
        $documentosDestacados = $this->getDocumentosDestacados();

        // Galería reciente
        $galeriaReciente = $this->getGaleriaReciente();

        return view('extranet.dashboard', compact(
            'cumpleanos',
            'aniversarios',
            'nuevosEmpleados',
            'eventosProximos',
            'proyectosActivos',
            'estadisticas',
            'publicaciones',
            'comunicadosFijados',
            'encuestasPendientes',
            'reconocimientosRecientes',
            'documentosDestacados',
            'galeriaReciente'
        ));
    }

    /**
     * Widget 1: Obtener cumpleaños del mes actual
     */
    private function getCumpleanosDelMes()
    {
        $mesActual = Carbon::now()->month;

        return empleado::where('EMP_ACTIVO', 1)
            ->whereNotNull('EMP_FECHA_NACIMIENTO')
            ->whereRaw('MONTH(EMP_FECHA_NACIMIENTO) = ?', [$mesActual])
            ->with(['cargo', 'campana']) // Cargar relaciones para optimizar consultas
            ->orderByRaw('DAY(EMP_FECHA_NACIMIENTO) ASC')
            ->get()
            ->map(function ($empleado) {
                $fechaNacimiento = Carbon::parse($empleado->EMP_FECHA_NACIMIENTO);
                $edad = $fechaNacimiento->age;
                $diasRestantes = $this->getDiasHastaCumpleanos($fechaNacimiento);

                return [
                    'empleado' => $empleado,
                    'fecha' => $fechaNacimiento->locale('es')->isoFormat('DD [de] MMMM'),
                    'edad' => $edad,
                    'dias_restantes' => $diasRestantes,
                    'es_hoy' => $diasRestantes === 0,
                    'es_esta_semana' => $diasRestantes <= 7 && $diasRestantes >= 0,
                ];
            })
            ->sortBy(function ($item) {
                // Ordenar por días restantes: primero los de hoy, luego los más cercanos
                if ($item['es_hoy']) {
                    return 0;
                }
                return $item['dias_restantes'];
            })
            ->values(); // Resetear las claves del array
    }

    /**
     * Widget 2: Obtener aniversarios laborales del mes
     */
    private function getAniversariosDelMes()
    {
        $mesActual = Carbon::now()->month;

        return emp_contrato::with('empleado')
            ->whereHas('empleado', function ($query) {
                $query->where('EMP_ACTIVO', 1);
            })
            ->whereNotNull('EMC_FECHA_INI')
            ->whereRaw('MONTH(EMC_FECHA_INI) = ?', [$mesActual])
            ->orderByRaw('DAY(EMC_FECHA_INI) ASC')
            ->get()
            ->map(function ($contrato) {
                $fechaInicio = Carbon::parse($contrato->EMC_FECHA_INI);
                $anosServicio = $fechaInicio->diffInYears(Carbon::now());
                $diasRestantes = $this->getDiasHastaAniversario($fechaInicio);

                return [
                    'empleado' => $contrato->empleado,
                    'fecha' => $fechaInicio->format('d/m'),
                    'anos' => $anosServicio,
                    'dias_restantes' => $diasRestantes,
                    'es_hoy' => $diasRestantes === 0,
                    'es_esta_semana' => $diasRestantes <= 7 && $diasRestantes >= 0,
                ];
            })
            ->filter(function ($item) {
                return $item['anos'] > 0; // Solo mostrar si ya cumplió al menos 1 año
            });
    }

    /**
     * Widget 3: Obtener nuevos empleados (últimos 30 días)
     */
    private function getNuevosEmpleados()
    {
        $hace30Dias = Carbon::now()->subDays(30);

        return empleado::where('EMP_ACTIVO', 1)
            ->where('created_at', '>=', $hace30Dias)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($empleado) {
                return [
                    'empleado' => $empleado,
                    'dias' => Carbon::parse($empleado->created_at)->diffInDays(Carbon::now()),
                    'fecha_ingreso' => Carbon::parse($empleado->created_at)->format('d/m/Y'),
                ];
            });
    }

    /**
     * Widget 4: Obtener eventos próximos (próximos 30 días)
     */
    private function getEventosProximos()
    {
        $hoy = Carbon::now()->startOfDay();
        $enTreintaDias = Carbon::now()->addDays(30)->endOfDay();

        return EventoExtranet::where('estado', 'publicado')
            ->whereBetween('fecha_inicio', [$hoy, $enTreintaDias])
            ->with('organizador')
            ->orderBy('fecha_inicio', 'asc')
            ->take(5)
            ->get()
            ->map(function ($evento) {
                $fechaInicio = Carbon::parse($evento->fecha_inicio);

                return [
                    'evento' => $evento,
                    'dias_restantes' => $fechaInicio->diffInDays(Carbon::now()->startOfDay()),
                    'es_hoy' => $fechaInicio->isToday(),
                    'es_manana' => $fechaInicio->isTomorrow(),
                    'es_esta_semana' => $fechaInicio->isCurrentWeek(),
                ];
            });
    }

    /**
     * Widget 5: Obtener proyectos activos
     */
    private function getProyectosActivos()
    {
        return Proyecto::whereIn('estado', ['planificacion', 'en_progreso'])
            ->with(['responsable', 'tareas'])
            ->orderBy('prioridad', 'desc')
            ->orderBy('progreso', 'asc')
            ->take(5)
            ->get()
            ->map(function ($proyecto) {
                $totalTareas = $proyecto->tareas->count();
                $tareasCompletadas = $proyecto->tareas->where('estado', 'completada')->count();

                return [
                    'proyecto' => $proyecto,
                    'total_tareas' => $totalTareas,
                    'tareas_completadas' => $tareasCompletadas,
                    'tareas_pendientes' => $totalTareas - $tareasCompletadas,
                ];
            });
    }

    /**
     * Widget 6: Obtener estadísticas generales
     */
    private function getEstadisticasGenerales()
    {
        return [
            'total_empleados' => empleado::where('EMP_ACTIVO', 1)->count(),
            'comunicados_activos' => Comunicado::publicados()->vigentes()->count(),
            'eventos_proximos' => EventoExtranet::where('estado', 'publicado')
                ->where('fecha_inicio', '>=', Carbon::now())
                ->count(),
            'proyectos_activos' => Proyecto::whereIn('estado', ['planificacion', 'en_progreso'])->count(),
            'encuestas_activas' => Encuesta::where('estado', 'activa')
                ->where('fecha_inicio', '<=', Carbon::now())
                ->where(function ($q) {
                    $q->whereNull('fecha_fin')
                        ->orWhere('fecha_fin', '>=', Carbon::now());
                })
                ->count(),
            'reconocimientos_mes' => Reconocimiento::whereMonth('fecha', Carbon::now()->month)
                ->whereYear('fecha', Carbon::now()->year)
                ->count(),
        ];
    }

    /**
     * Calcular días hasta el próximo cumpleaños
     */
    private function getDiasHastaCumpleanos($fechaNacimiento)
    {
        $hoy = Carbon::now()->startOfDay();
        $cumpleanos = Carbon::createFromDate(
            $hoy->year,
            $fechaNacimiento->month,
            $fechaNacimiento->day
        )->startOfDay();

        if ($cumpleanos->lt($hoy)) {
            $cumpleanos->addYear();
        }

        return $hoy->diffInDays($cumpleanos);
    }

    /**
     * Calcular días hasta el próximo aniversario laboral
     */
    private function getDiasHastaAniversario($fechaInicio)
    {
        $hoy = Carbon::now()->startOfDay();
        $aniversario = Carbon::createFromDate(
            $hoy->year,
            $fechaInicio->month,
            $fechaInicio->day
        )->startOfDay();

        if ($aniversario->lt($hoy)) {
            $aniversario->addYear();
        }

        return $hoy->diffInDays($aniversario);
    }

    /**
     * Widget 7: Obtener encuestas pendientes de responder
     */
    private function getEncuestasPendientes()
    {
        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return collect([]);
        }

        // Obtener encuestas activas
        $encuestasActivas = Encuesta::where('estado', 'activa')
            ->where('fecha_inicio', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', Carbon::now());
            })
            ->with('autor')
            ->orderBy('fecha_inicio', 'DESC')
            ->get();

        // Filtrar las que el usuario no ha respondido
        $pendientes = $encuestasActivas->filter(function ($encuesta) use ($empleado) {
            if ($encuesta->anonima) {
                return true; // No podemos verificar si ya respondió
            }

            $yaRespondio = $encuesta->respuestas()
                ->where('empleado_id', $empleado->EMP_ID)
                ->exists();

            return !$yaRespondio;
        });

        return $pendientes->take(3);
    }

    /**
     * Widget 8: Obtener reconocimientos recientes
     */
    private function getReconocimientosRecientes()
    {
        return Reconocimiento::with(['empleado', 'otorgadoPor'])
            ->orderBy('fecha', 'DESC')
            ->take(5)
            ->get()
            ->map(function ($reconocimiento) {
                return [
                    'reconocimiento' => $reconocimiento,
                    'dias_atras' => Carbon::parse($reconocimiento->fecha)->diffInDays(Carbon::now()),
                    'es_reciente' => Carbon::parse($reconocimiento->fecha)->diffInDays(Carbon::now()) <= 7,
                ];
            });
    }

    /**
     * Widget 9: Obtener documentos destacados o recientes
     */
    private function getDocumentosDestacados()
    {
        // Primero intentar obtener destacados
        $destacados = DocumentoExtranet::with('autor')
            ->where('destacado', true)
            ->orderBy('created_at', 'DESC')
            ->take(4)
            ->get();

        // Si no hay suficientes destacados, complementar con recientes
        if ($destacados->count() < 4) {
            $faltantes = 4 - $destacados->count();
            $idsDestacados = $destacados->pluck('id')->toArray();

            $recientes = DocumentoExtranet::with('autor')
                ->whereNotIn('id', $idsDestacados)
                ->orderBy('created_at', 'DESC')
                ->take($faltantes)
                ->get();

            return $destacados->concat($recientes);
        }

        return $destacados;
    }

    /**
     * Widget 10: Obtener galería reciente
     */
    private function getGaleriaReciente()
    {
        return Galeria::with('evento')
            ->withCount('fotos')
            ->orderBy('created_at', 'DESC')
            ->first();
    }
}
