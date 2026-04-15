<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\HorarioService;
use App\Http\Requests\HorarioSelectivoRequest;

use App\Models\cliente;
use App\Models\jornada;
use App\Models\hora;
use App\Models\empleado;

class SelectivaController extends Controller
{
    public function __construct(
        protected HorarioService $horarioService
    ) {
        $this->middleware('auth');
    }

    /**
     * Vista principal de horarios selectivos
     */
    public function index()
    {
        $horas = hora::activas()->orderBy('HOR_ID')->get();
        $jornadas = jornada::activas()->get();
        $clientes = cliente::all();
        $empleados = empleado::where('EMP_ESTADO', 1)
            ->where('EMP_ACTIVO', 'SI')
            ->orderBy('EMP_NOMBRES')
            ->get(['EMP_ID', 'EMP_NOMBRES', 'EMP_CEDULA']);

        return view('Malla.Horarios.Selectiva.index', compact('horas', 'empleados', 'clientes', 'jornadas'));
    }

    /**
     * Crear horarios selectivos para empleados seleccionados
     */
    public function create(HorarioSelectivoRequest $request)
    {
        // Los IDs de empleados ya vienen validados por el Form Request
        $empleadosIds = $request->ids;

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
            $empleadosIds,
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
            'Horarios selectivos creados: %d empleados × %d días × %d horas = %d registros',
            $resultado['estadisticas']['empleados'],
            $resultado['estadisticas']['dias'],
            $resultado['estadisticas']['horas_por_dia'],
            $resultado['estadisticas']['total_registros']
        );

        return redirect()->back()->with('rgcmessage', $mensaje);
    }
}
