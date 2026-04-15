<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Services\NovedadService;
use App\Services\HorarioService;
use App\Http\Requests\HorarioIndividualRequest;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\NovedadHorario;
use App\Models\tipos_novedade;
use App\Models\hora;

class IndividualController extends Controller
{
    public function __construct(
        protected NovedadService $novedadService,
        protected HorarioService $horarioService
    ) {
        $this->middleware('auth');
    }

    /**
     * Listado de empleados activos con paginación y filtros
     */
    public function index(Request $request)
    {
        // Iniciar query
        $query = empleado::where('EMP_ESTADO', 1)
            ->where('EMP_ACTIVO', 'SI');

        // Filtro por búsqueda (nombre o cédula)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('EMP_NOMBRES', 'like', "%{$buscar}%")
                  ->orWhere('EMP_CEDULA', 'like', "%{$buscar}%")
                  ->orWhere('EMP_CODE', 'like', "%{$buscar}%");
            });
        }

        // Filtro por campaña
        if ($request->filled('campana')) {
            $query->where('CAM_ID', $request->campana);
        }

        // Filtro por cargo
        if ($request->filled('cargo')) {
            $query->where('CAR_ID', $request->cargo);
        }

        // Ordenar y paginar
        $empleados = $query->with(['cargo', 'campana'])
            ->orderBy('EMP_NOMBRES')
            ->paginate(15)
            ->appends($request->all());

        // Obtener campañas y cargos para filtros
        $campanas = campana::where('CAM_ESTADO', '=', '1')
            ->orderBy('CAM_NOMBRE')
            ->get(['CAM_ID', 'CAM_NOMBRE']);

        $cargos = \App\Models\cargo::where('CAR_ESTADO', '=', '1')
            ->orderBy('CAR_NOMBRE')
            ->get(['CAR_ID', 'CAR_NOMBRE']);

        return view('Malla.Horarios.Individual.index', compact('empleados', 'campanas', 'cargos'));
    }

    public function redirect_to_edit(Request $request)
    {
        $empId = $request->get('EMP_ID');
        $fecha = $request->get('FECHA');

        return view('Malla.Horarios.Individual.redirect', compact('empId', 'fecha'));
    }

    /**
     * Editar horarios de un empleado
     */
    public function edit(Request $request)
    {
        $empleado = empleado::where('EMP_ID', $request->EMP_ID)->firstOrFail();
        $EMP_ID = $empleado->EMP_ID;

        // Verificar el tipo de fecha seleccionada
        $dateType = $request->DATE_TYPE ?? 'single';
        $isDateRange = $dateType === 'range';

        // Validar y obtener fechas
        if ($isDateRange) {
            $fechaInicial = $request->FECHA_INICIAL;
            $fechaFinal = $request->FECHA_FINAL;

            if (!$fechaInicial || !$fechaFinal) {
                Session::flash('warmessage', 'Debe especificar tanto la fecha inicial como la final para el rango');
                return redirect()->back();
            }

            if ($fechaInicial > $fechaFinal) {
                Session::flash('warmessage', 'La fecha inicial no puede ser mayor que la fecha final');
                return redirect()->back();
            }

            $MAL_DIA = $fechaInicial . ' - ' . $fechaFinal;
        } else {
            $fechaInicial = $fechaFinal = $MAL_DIA = $request->FECHA;

            if (!$MAL_DIA) {
                Session::flash('warmessage', 'Debe especificar una fecha');
                return redirect()->back();
            }
        }

        // Obtener horarios usando el servicio
        $emp_horario = $this->horarioService->obtenerHorariosEmpleado(
            $request->EMP_ID,
            $fechaInicial,
            $isDateRange ? $fechaFinal : null
        );

        // Validar resultados
        if ($emp_horario->isEmpty()) {
            $message = $isDateRange
                ? 'El empleado no tiene horarios asignados en el rango de fechas seleccionado'
                : 'El empleado no tiene horario asignado en esta fecha';

            Session::flash('warmessage', $message);
            return redirect()->back();
        }

        // Transformar para mantener compatibilidad con vista
        $emp_horario = $emp_horario->map(function ($item) {
            return (object) [
                'MAL_ID' => $item->MAL_ID,
                'CLI_ID' => $item->campana->unidadNegocioCliente->cliente->CLI_ID ?? null,
                'CLI_NOMBRE' => $item->campana->unidadNegocioCliente->cliente->CLI_NOMBRE ?? 'N/A',
                'CAM_ID' => $item->CAM_ID,
                'CAM_NOMBRE' => $item->campana->CAM_NOMBRE ?? 'N/A',
                'EMP_ID' => $item->EMP_ID,
                'MAL_INICIO' => $item->MAL_INICIO,
                'MAL_FINAL' => $item->MAL_FINAL,
                'MAL_ESTADO' => $item->MAL_ESTADO,
                'MAL_DIA' => $item->MAL_DIA
            ];
        });

        // Datos para la vista
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();
        $tipos_novedades = tipos_novedade::where('TIN_ESTADO', '=', '1')
            ->where('TIN_TIPO', '=', '0')
            ->get();
        $empleados = empleado::where('EMP_ESTADO', '=', '1')->get();

        return view('Malla.Horarios.Individual.editar_horario', compact(
            'tipos_novedades',
            'emp_horario',
            'clientes',
            'empleado',
            'empleados',
            'MAL_DIA',
            'EMP_ID',
            'isDateRange',
            'fechaInicial',
            'fechaFinal'
        ));
    }

    /**
     * Cambiar estado de horario individual (con novedad)
     */
    public function time_status(Request $request, $id)
    {
        // Validación de datos del formulario completo
        $request->validate([
            'TIN_ID' => 'required|exists:tipos_novedades,TIN_ID',
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'NOV_DESCRIPCION' => 'required|string',
            'archivos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        // Procesar archivos
        $archivos = $this->novedadService->procesarArchivos($request->file('archivos'));

        // Preparar datos para la novedad
        $datosNovedad = [
            'tipo_id' => $request->TIN_ID,
            'empleado_id' => $request->EMP_ID,
            'descripcion' => $request->NOV_DESCRIPCION,
            'user_id' => $request->USER_ID,
            'fecha_referencia' => $request->MAL_DIA
        ];

        // Crear novedad individual usando el servicio
        $this->novedadService->crearNovedad([$id], $datosNovedad, $archivos);

        // Desactivar el horario
        $this->novedadService->desactivarHorarios([$id], $request->MAL_ESTADO);

        // Mensaje de éxito
        Session::flash('rgcmessage', 'Horario desactivado y novedad registrada correctamente');

        return redirect()->route('Individual.employee_hours', ['id' => $request->EMP_ID]);
    }

    /**
     * Cambiar estado de múltiples horarios (con novedad)
     */
    public function time_status_multiple(Request $request)
    {
        // Validación de datos del formulario completo
        $request->validate([
            'TIN_ID' => 'required|exists:tipos_novedades,TIN_ID',
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'NOV_DESCRIPCION' => 'required|string',
            'archivos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'MAL_IDS' => 'required|string'
        ]);

        // Procesar los IDs de horarios seleccionados
        $horariosIds = explode(',', $request->MAL_IDS);
        $horariosIds = array_filter($horariosIds);

        if (empty($horariosIds)) {
            return back()->withErrors(['MAL_IDS' => 'Debe seleccionar al menos un horario'])->withInput();
        }

        // Obtener horarios para referencia
        $horariosSeleccionados = malla::whereIn('MAL_ID', $horariosIds)
            ->get(['MAL_ID', 'MAL_DIA']);

        // Procesar archivos usando el servicio
        $archivos = $this->novedadService->procesarArchivos($request->file('archivos'));

        // Preparar datos para la novedad
        $datosNovedad = [
            'tipo_id' => $request->TIN_ID,
            'empleado_id' => $request->EMP_ID,
            'descripcion' => $request->NOV_DESCRIPCION,
            'user_id' => $request->USER_ID,
            'fecha_referencia' => $horariosSeleccionados->min('MAL_DIA')
        ];

        // Crear novedades usando el servicio
        $this->novedadService->crearNovedad($horariosIds, $datosNovedad, $archivos);

        // Desactivar todos los horarios
        $this->novedadService->desactivarHorarios($horariosIds, 0);

        return redirect()->route('Individual.employee_hours', ['id' => $request->EMP_ID]);
    }

    /**
     * Reactivar horario
     */
    public function delete_time_status(Request $request, $id)
    {
        $estado = (int) $request->MAL_ESTADO;

        DB::transaction(function () use ($id, $estado) {
            malla::where('MAL_ID', $id)->update(['MAL_ESTADO' => $estado]);

            if ($estado === 0) {
                $novedadHorario = NovedadHorario::where('mal_id', $id)->first();

                if (!$novedadHorario) {
                    throw new \Exception('No se encontró la relación de novedad para el horario proporcionado.');
                }

                $novId = $novedadHorario->nov_id;
                $novedadHorario->delete();
                $novedadHorarios = NovedadHorario::where('nov_id', $novId)->get();

                if ($novedadHorarios->isEmpty()) {
                    $this->novedadService->eliminarNovedad($novId);
                }
            }
        });

        return redirect()->with('rgcmessage', 'Horario reactivado correctamente');
    }

    /**
     * Vista para asignar horarios a un empleado
     */
    public function employee($id)
    {
        $campanas = campana::where('CAM_ESTADO', '=', '1')->get();
        $uni_negocios = unidad_negocio::where('UNI_ESTADO', '=', '1')->get();
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();
        $jornadas = jornada::activas()->get();

        // Usar Eloquent en lugar de SQL raw
        $empleado = empleado::with('cargo')
            ->where('EMP_ID', $id)
            ->firstOrFail();

        $horas = hora::activas()->orderBy('HOR_ID')->get();

        // Obtener uni_clis usando Eloquent
        $uni_clis = DB::table('uni_clis AS unc')
            ->join('clientes AS cli', 'cli.CLI_ID', '=', 'unc.CLI_ID')
            ->join('unidad_negocios AS uni', 'uni.UNI_ID', '=', 'unc.UNI_ID')
            ->where('unc.UNC_ESTADO', 1)
            ->where('cli.CLI_ESTADO', 1)
            ->where('uni.UNI_ESTADO', 1)
            ->select('unc.UNC_ID', 'uni.UNI_ID', 'uni.UNI_NOMBRE', 'cli.CLI_ID', 'cli.CLI_NOMBRE')
            ->get();

        // Convertir empleado a array para compatibilidad con vista
        $empleado = [(object) [
            'EMP_ID' => $empleado->EMP_ID,
            'EMP_NOMBRES' => $empleado->EMP_NOMBRES,
            'EMP_CEDULA' => $empleado->EMP_CEDULA,
            'CAR_NOMBRE' => $empleado->cargo->CAR_NOMBRE ?? 'N/A'
        ]];

        return view('Malla.Horarios.Individual.employee_hours', compact(
            'horas',
            'empleado',
            'campanas',
            'uni_negocios',
            'clientes',
            'uni_clis',
            'jornadas'
        ));
    }

    /**
     * Crear horarios por hora (individual)
     */
    public function hour(HorarioIndividualRequest $request)
    {
        $resultado = $this->horarioService->generarHorarios(
            [$request->id_empleado],
            $request->CAM_ID,
            $request->FECHA_INICIAL,
            $request->FECHA_FINAL,
            null, // Sin jornada
            $request->HORA_INICIAL,
            $request->HORA_FINAL,
            $request->USER_ID
        );

        if (!$resultado['exito']) {
            return redirect()->back()->with('warmessage', $resultado['mensaje']);
        }

        return redirect()->back()->with('rgcmessage', 'Horarios individuales creados exitosamente');
    }

    /**
     * Crear horarios por jornada (individual)
     */
    public function working_day(HorarioIndividualRequest $request)
    {
        $resultado = $this->horarioService->generarHorarios(
            [$request->id_empleado],
            $request->CAM_ID,
            $request->FECHA_INICIAL,
            $request->FECHA_FINAL,
            $request->JOR_ID,
            null, // Sin hora inicial
            null, // Sin hora final
            $request->USER_ID
        );

        if (!$resultado['exito']) {
            return redirect()->back()->with('warmessage', $resultado['mensaje']);
        }

        return redirect()->back()->with('rgcmessage', 'Jornada individual creada exitosamente');
    }
}
