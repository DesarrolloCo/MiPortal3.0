<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;

class CalendarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function calendario_Agente(Request $request){

        $eventos = DB::table('mallas as mal')
            ->join('campanas as cam', 'mal.CAM_ID', '=', 'cam.CAM_ID')
            ->leftJoin('novedad_horarios as nh', 'mal.MAL_ID', '=', 'nh.mal_id')
            ->leftJoin('novedades as nov', function($join) {
                $join->on('nh.nov_id', '=', 'nov.NOV_ID')
                     ->where('nov.NOV_ESTADO_APROBACION', '=', 'aprobada');
            })
            ->leftJoin('tipos_novedades as tn', 'nov.TIN_ID', '=', 'tn.TIN_ID')
            ->where('mal.EMP_ID', $request->id_empleado)
            ->select(
                'mal.MAL_ID',
                'cam.CAM_NOMBRE',
                'mal.MAL_DIA',
                'mal.MAL_INICIO',
                'mal.MAL_FINAL',
                'mal.MAL_ESTADO',
                DB::raw('GROUP_CONCAT(DISTINCT tn.TIN_NOMBRE SEPARATOR ", ") as tipos_novedad'),
                DB::raw('GROUP_CONCAT(DISTINCT LEFT(nov.NOV_DESCRIPCION, 30) SEPARATOR "; ") as descripcion_novedad')
            )
            ->groupBy('mal.MAL_ID', 'cam.CAM_NOMBRE', 'mal.MAL_DIA', 'mal.MAL_INICIO', 'mal.MAL_FINAL', 'mal.MAL_ESTADO')
            ->get()
            ->map(function($evento) {
                // Extraer solo la hora de los campos DATETIME
                // Formato en BD: "2026-04-04 07:00:00"
                $horaInicio = date('H:i', strtotime($evento->MAL_INICIO));
                $horaFinal = date('H:i', strtotime($evento->MAL_FINAL));

                // Para FullCalendar, necesitamos solo la hora en formato HH:mm:ss
                $timeInicio = date('H:i:s', strtotime($evento->MAL_INICIO));
                $timeFinal = date('H:i:s', strtotime($evento->MAL_FINAL));

                // Determinar color basado en el estado de la malla
                if ($evento->MAL_ESTADO == 0) {
                    // Malla inactiva (bloqueada por novedad) - roja
                    $color = '#dc3545';
                } elseif ($evento->MAL_ESTADO == 2) {
                    // Malla de almuerzo - verde claro
                    $color = '#28a745';
                } else {
                    // Malla activa de trabajo - azul
                    $color = '#007bff';
                }

                // Construir el título con información de campaña y tipo
                $titulo = $evento->CAM_NOMBRE . ' (' . $horaInicio . ' - ' . $horaFinal . ')';
                if ($evento->MAL_ESTADO == 0) {
                    $titulo .= ' (Bloqueado)';
                } elseif ($evento->MAL_ESTADO == 2) {
                    $titulo .= ' - ALMUERZO';
                }

                // Combinar fecha con horas para FullCalendar
                return [
                    'id' => $evento->MAL_ID,
                    'title' => $titulo,
                    'start' => $evento->MAL_DIA . 'T' . $timeInicio,
                    'end' => $evento->MAL_DIA . 'T' . $timeFinal,
                    'color' => $color,
                    'MAL_ESTADO' => $evento->MAL_ESTADO,
                    'CAM_NOMBRE' => $evento->CAM_NOMBRE,
                    'MAL_INICIO' => $horaInicio,
                    'MAL_FINAL' => $horaFinal
                ];
            });

        return response()->json([
            'success' => true,
            'evento' => $eventos
        ]);

    }

    public function calendario_Supervisor_Agente(Request $request){

        $eventos = DB::table('mallas as mal')
            ->join('campanas as cam', 'mal.CAM_ID', '=', 'cam.CAM_ID')
            ->leftJoin('novedad_horarios as nh', 'mal.MAL_ID', '=', 'nh.mal_id')
            ->leftJoin('novedades as nov', function($join) {
                $join->on('nh.nov_id', '=', 'nov.NOV_ID')
                     ->where('nov.NOV_ESTADO_APROBACION', '=', 'aprobada');
            })
            ->leftJoin('tipos_novedades as tn', 'nov.TIN_ID', '=', 'tn.TIN_ID')
            ->where('mal.EMP_ID', $request->id_empleado)
            ->select(
                'mal.MAL_ID',
                'cam.CAM_NOMBRE',
                'mal.MAL_DIA',
                'mal.MAL_INICIO',
                'mal.MAL_FINAL',
                'mal.MAL_ESTADO',
                DB::raw('GROUP_CONCAT(DISTINCT tn.TIN_NOMBRE SEPARATOR ", ") as tipos_novedad'),
                DB::raw('GROUP_CONCAT(DISTINCT LEFT(nov.NOV_DESCRIPCION, 30) SEPARATOR "; ") as descripcion_novedad')
            )
            ->groupBy('mal.MAL_ID', 'cam.CAM_NOMBRE', 'mal.MAL_DIA', 'mal.MAL_INICIO', 'mal.MAL_FINAL', 'mal.MAL_ESTADO')
            ->get()
            ->map(function($evento) {
                // Extraer solo la hora de los campos DATETIME
                // Formato en BD: "2026-04-04 07:00:00"
                $horaInicio = date('H:i', strtotime($evento->MAL_INICIO));
                $horaFinal = date('H:i', strtotime($evento->MAL_FINAL));

                // Para FullCalendar, necesitamos solo la hora en formato HH:mm:ss
                $timeInicio = date('H:i:s', strtotime($evento->MAL_INICIO));
                $timeFinal = date('H:i:s', strtotime($evento->MAL_FINAL));

                // Determinar color basado en el estado
                if ($evento->MAL_ESTADO == 0) {
                    // Malla inactiva (bloqueada por novedad) - roja
                    $color = '#dc3545';
                } elseif ($evento->MAL_ESTADO == 2) {
                    // Malla de almuerzo - verde claro
                    $color = '#28a745';
                } else {
                    // Malla activa de trabajo - azul
                    $color = '#007bff';
                }

                // Construir el título con información de campaña
                $titulo = $evento->CAM_NOMBRE . ' (' . $horaInicio . ' - ' . $horaFinal . ')';
                if ($evento->MAL_ESTADO == 0) {
                    $tituloBloqueo = !empty($evento->tipos_novedad) ? $evento->tipos_novedad : 'Novedad';
                    $titulo .= ' (Bloqueado: ' . $tituloBloqueo . ')';
                } elseif ($evento->MAL_ESTADO == 2) {
                    $titulo .= ' - ALMUERZO';
                }

                // Combinar fecha con horas para FullCalendar
                return [
                    'id' => $evento->MAL_ID,
                    'title' => $titulo,
                    'start' => $evento->MAL_DIA . 'T' . $timeInicio,
                    'end' => $evento->MAL_DIA . 'T' . $timeFinal,
                    'color' => $color,
                    'MAL_ESTADO' => $evento->MAL_ESTADO,
                    'CAM_NOMBRE' => $evento->CAM_NOMBRE,
                    'MAL_INICIO' => $horaInicio,
                    'MAL_FINAL' => $horaFinal,
                    'tipos_novedad' => $evento->tipos_novedad,
                    'descripcion_novedad' => $evento->descripcion_novedad
                ];
            });

        return response()->json([
            'success' => true,
            'evento' => $eventos
        ]);

    }

}
