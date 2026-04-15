<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\HorarioService;
use App\Http\Requests\HorarioGrupalRequest;

use App\Models\cliente;
use App\Models\jornada;
use App\Models\hora;
use App\Models\empleado;

class GrupalController extends Controller
{
    public function __construct(
        protected HorarioService $horarioService
    ) {
        $this->middleware('auth');
    }

    /**
     * Vista principal de horarios grupales
     */
    public function index()
    {
        $horas = hora::activas()->orderBy('HOR_ID')->get();
        $jornadas = jornada::activas()->get();
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();

        return view('Malla.Horarios.Grupal.index', compact('horas', 'jornadas', 'clientes'));
    }

    /**
     * Crear horarios grupales para todos los empleados de una campaña
     */
    public function create(HorarioGrupalRequest $request)
    {
        // Obtener todos los empleados de la campaña
        $empleados = empleado::where('CAM_ID', $request->CAM_ID)
            ->where('EMP_ESTADO', 1)
            ->pluck('EMP_ID')
            ->toArray();

        if (empty($empleados)) {
            return redirect()->back()->with('warmessage', 'No hay empleados activos en la campaña seleccionada');
        }

        // Determinar si usa jornada o rango de horas
        $jornadaId = null;
        $horaInicial = null;
        $horaFinal = null;

        if ($request->checkJorOrHor == 0) {
            // Usar jornada
            $jornadaId = $request->JOR_ID;
        } else {
            // Usar rango de horas
            $horaInicial = $request->HORA_INICIAL;
            $horaFinal = $request->HORA_FINAL;
        }

        // Generar horarios usando el servicio
        $resultado = $this->horarioService->generarHorarios(
            $empleados,
            $request->CAM_ID,
            $request->FECHA_INICIAL,
            $request->FECHA_FINAL,
            $jornadaId,
            $horaInicial,
            $horaFinal,
            $request->USER_ID
        );

        if (!$resultado['exito']) {
            return redirect()->back()->with('warmessage', $resultado['mensaje']);
        }

        $mensaje = sprintf(
            'Horarios grupales creados: %d empleados × %d días × %d horas = %d registros',
            $resultado['estadisticas']['empleados'],
            $resultado['estadisticas']['dias'],
            $resultado['estadisticas']['horas_por_dia'],
            $resultado['estadisticas']['total_registros']
        );

        return redirect()->back()->with('rgcmessage', $mensaje);
    }
}
