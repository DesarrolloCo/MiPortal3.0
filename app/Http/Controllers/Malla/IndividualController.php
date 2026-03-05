<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\NovedadService;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\NovedadHorario;
use App\Models\tipos_novedade;

class IndividualController extends Controller
{
    public function __construct(
        protected NovedadService $novedadService
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $sql = "SELECT EMP_ID, EMP_NOMBRES, EMP_CEDULA FROM empleados WHERE EMP_ESTADO = 1 AND EMP_ACTIVO = 'SI'";

        $empleados = DB::select($sql);

        return view('Malla.Horarios.Individual.index', compact('empleados'));
    }

    public function redirect_to_edit(Request $request)
    {
        $empId = $request->get('EMP_ID');
        $fecha = $request->get('FECHA');

        return view('Malla.Horarios.Individual.redirect', compact('empId', 'fecha'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request)
    {
        $empleado = empleado::where('EMP_ID', $request->EMP_ID)->get();
        $EMP_ID = $empleado[0]->EMP_ID;

        // Verificar el tipo de fecha seleccionada
        $dateType = $request->DATE_TYPE ?? 'single';
        $isDateRange = $dateType === 'range';

        // Validar y obtener fechas
        if ($isDateRange) {
            $fechaInicial = $request->FECHA_INICIAL;
            $fechaFinal = $request->FECHA_FINAL;

            if (!$fechaInicial || !$fechaFinal) {
                Session::flash('warmessage', '¡Debe especificar tanto la fecha inicial como la final para el rango!...');
                return redirect()->back();
            }

            if ($fechaInicial > $fechaFinal) {
                Session::flash('warmessage', '¡La fecha inicial no puede ser mayor que la fecha final!...');
                return redirect()->back();
            }

            $MAL_DIA = $fechaInicial . ' - ' . $fechaFinal;
        } else {
            $fechaInicial = $fechaFinal = $MAL_DIA = $request->FECHA;

            if (!$MAL_DIA) {
                Session::flash('warmessage', '¡Debe especificar una fecha!...');
                return redirect()->back();
            }
        }

        // Consulta unificada
        $query = malla::with([
            'campana.unidadNegocioCliente.cliente',
            'campana.unidadNegocioCliente.unidadNegocio'
        ])->where('EMP_ID', $request->EMP_ID);

        // Aplicar filtro de fecha según el tipo

        if ($isDateRange) {
            $query->whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal])
                ->orderBy('MAL_DIA');
        } else {
            $query->where('MAL_DIA', $MAL_DIA);
        }

        $emp_horario = $query->orderBy('MAL_INICIO')
            ->get()
            ->map(function ($item) {
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

        // Validar resultados
        if ($emp_horario->isEmpty()) {
            $message = $isDateRange
                ? '¡El empleado no tiene horarios asignados en el rango de fechas seleccionado!...'
                : '¡El empleado no tiene horario asignado en esta fecha!...';

            Session::flash('warmessage', $message);
            return redirect()->back();
        }

        // Datos para la vista
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();
        $tipos_novedades = tipos_novedade::where('TIN_ESTADO', '=', '1')->where('TIN_TIPO', '=', '0')->get();
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

    public function time_status(request $request, $id)
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
        Session::flash('rgcmessage', 'Horario desactivado y novedad registrada correctamente!...');

        return redirect()->route('Individual.employee_hours', ['id' => $request->EMP_ID]);
    }

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
        $horariosIds = array_filter($horariosIds); // Eliminar valores vacíos

        if (empty($horariosIds)) {
            return back()->withErrors(['MAL_IDS' => 'Debe seleccionar al menos un horario'])->withInput();
        }

        // Obtener horarios para referencia
        $horariosSeleccionados = malla::whereIn('MAL_ID', $horariosIds)->get(['MAL_ID', 'MAL_DIA']);

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

        return redirect()->with('rgcmessage', 'Horario reactivado correctamente!...');
    }

    public function employee($id)
    {
        $campanas = campana::where('CAM_ESTADO', '=', '1')->get();
        $uni_negocios = unidad_negocio::where('UNI_ESTADO', '=', '1')->get();
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();
        $jornadas = jornada::where('JOR_ESTADO', '=', '1')->get();

        $sql1 = "SELECT unc.UNC_ID, uni.UNI_ID, uni.UNI_NOMBRE, cli.CLI_ID, cli.CLI_NOMBRE
        FROM uni_clis AS unc
        INNER JOIN clientes AS cli ON cli.CLI_ID = unc.CLI_ID
        INNER JOIN unidad_negocios AS uni ON uni.UNI_ID = unc.UNI_ID
        WHERE unc.UNC_ESTADO = 1
        AND cli.CLI_ESTADO = 1
        AND uni.UNI_ESTADO = 1";

        $uni_clis = DB::select($sql1);

        $sql = "SELECT e.EMP_ID, e.EMP_NOMBRES, e.EMP_CEDULA, c.CAR_NOMBRE FROM empleados e
        INNER JOIN cargos c WHERE c.CAR_ID = e.CAR_ID AND e.EMP_ID =" . $id;

        $empleado = DB::select($sql);

        $sql = "SELECT HOR_ID,HOR_INICIO,HOR_FINAL FROM horas WHERE HOR_ESTADO = 1";

        $horas = DB::select($sql);

        return view('Malla.Horarios.Individual.employee_hours', compact('horas', 'empleado', 'campanas', 'uni_negocios', 'clientes', 'uni_clis', 'jornadas'));
    }

    public function hour(request $request)
    {

        //TRAER EL REGISTRO DE LA HORA INICIAL
        $sql2 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
        FROM horas AS hor
        WHERE hor.HOR_ID = " . $request->HORA_INICIAL;

        $JORInicio = DB::select($sql2);

        //TRAER EL REGISTRO DE LA HORA FINAL
        $sql3 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
        FROM horas AS hor
        WHERE hor.HOR_ID = " . $request->HORA_FINAL;

        $JORFinal = DB::select($sql3);

        //BUSCAR CADA HORA Y CREAR LOS EVENTOS PARA INSERTAR EN LA TABLA EVENTO
        $eventos = [];
        $FECHA_INICIAL = strtotime($request->FECHA_INICIAL);
        $FECHA_FINAL = strtotime($request->FECHA_FINAL);
        $DIA_INICIAL =  date("d", $FECHA_INICIAL);
        $DIA_FINAL =  date("d", $FECHA_FINAL);
        $FECHA = $request->FECHA_INICIAL;

        $MES_INICIAL =  date("m", $FECHA_INICIAL);
        $MES_FINAL =  date("m", $FECHA_FINAL);

        if (intval($MES_INICIAL) != intval($MES_FINAL)) {
            return redirect()->back()->with('warmessage', 'Solo puede agendar en un mes a la vez!...');
        }

        if (intval($DIA_INICIAL) > intval($DIA_FINAL)) {
            return redirect()->back()->with('warmessage', 'La fecha inicial es mayor que la final!...');
        }

        for ($i2 = intval($DIA_INICIAL); $i2 < intval($DIA_FINAL) + 1; $i2++) {
            //BUSCAR CADA HORA Y CREAR LOS EVENTOS PARA INSERTAR EN LA TABLA EVENTO PARA CADA EMPLEADO
            for ($i = intval($JORInicio[0]->HOR_ID); $i < intval($JORFinal[0]->HOR_ID) + 1; $i++) {

                $sql4 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
                FROM horas AS hor
                WHERE hor.HOR_ID = " . $i;

                $evento = DB::select($sql4);

                $eventos[] = array(
                    "CAM_ID" => $request->CAM_ID,
                    "MAL_DIA" => $FECHA,
                    "MAL_INICIO" => $FECHA . " " . $evento[0]->HOR_INICIO,
                    "MAL_FINAL" => $FECHA . " " . $evento[0]->HOR_FINAL,
                    "EMP_ID" => $request->id_empleado,
                    "USER_ID" => $request->USER_ID
                );
            }

            //VERIFICAR SI EXISTE EL REGISTRO, ACTUALIZAR SI EXISTE O INSERTAR SI NO EXISTE
            for ($i = 0; $i < count($eventos); $i++) {

                $malla[] = array(
                    "CAM_ID" => $eventos[$i]["CAM_ID"],
                    "MAL_DIA" => $eventos[$i]["MAL_DIA"],
                    "MAL_INICIO" => $eventos[$i]["MAL_INICIO"],
                    "MAL_FINAL" => $eventos[$i]["MAL_FINAL"],
                    "EMP_ID" => $eventos[$i]["EMP_ID"],
                    "USER_ID" => $eventos[$i]["USER_ID"]
                );

                $sqlvalidador = 'SELECT * FROM mallas WHERE EMP_ID = ' . $eventos[$i]["EMP_ID"] . ' AND MAL_DIA = "' . $eventos[$i]["MAL_DIA"] . '" AND MAL_INICIO = "' . $eventos[$i]["MAL_INICIO"] . '" AND MAL_FINAL = "' . $eventos[$i]["MAL_FINAL"] . '"';

                $validador = DB::select($sqlvalidador);

                if (count($validador) == 0) {
                    malla::insert($malla);
                } else {
                    malla::where('MAL_ID', '=', $validador[0]->MAL_ID)->update($malla[0]);
                }
                $malla = array();
            }
            $FECHA = date("Y-m-d", strtotime($FECHA . "+ 1 days"));
        }
        /* return view('Supervisor.malla_individual.asignarmalla', compact('horas','campanas', 'MAL_DIA', 'EMP_ID')); */
        return redirect()->back()->with('rgcmessage', 'Hora individual cargada con exito!...');
    }

    public function working_day(request $request)
    {

        //TRAER EL REGISTRO DE LA HORA INICIAL DE LA JORNADA
        $sql2 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
        FROM jornadas AS jor
        INNER JOIN horas AS hor ON hor.HOR_ID = jor.JOR_INICIO
        WHERE jor.JOR_ID = " . $request->JOR_ID;

        $JORInicio = DB::select($sql2);

        //TRAER EL REGISTRO DE LA HORA FINAL DE LA JORNADA
        $sql3 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
        FROM jornadas AS jor
        INNER JOIN horas AS hor ON hor.HOR_ID = jor.JOR_FINAL
        WHERE jor.JOR_ID = " . $request->JOR_ID;

        $JORFinal = DB::select($sql3);

        //BUSCAR CADA HORA Y CREAR LOS EVENTOS PARA INSERTAR EN LA TABLA EVENTO
        $eventos = [];
        $FECHA_INICIAL = strtotime($request->FECHA_INICIAL);
        $FECHA_FINAL = strtotime($request->FECHA_FINAL);
        $DIA_INICIAL =  date("d", $FECHA_INICIAL);
        $DIA_FINAL =  date("d", $FECHA_FINAL);
        $FECHA = $request->FECHA_INICIAL;

        $MES_INICIAL =  date("m", $FECHA_INICIAL);
        $MES_FINAL =  date("m", $FECHA_FINAL);

        if (intval($MES_INICIAL) != intval($MES_FINAL)) {
            return redirect()->back()->with('warmessage', 'Solo puede agendar en un mes a la vez!...');
        }

        if (intval($DIA_INICIAL) > intval($DIA_FINAL)) {
            return redirect()->back()->with('warmessage', 'La fecha inicial es mayor que la final!...');
        }

        for ($i2 = intval($DIA_INICIAL); $i2 < intval($DIA_FINAL) + 1; $i2++) {
            //BUSCAR CADA HORA Y CREAR LOS EVENTOS PARA INSERTAR EN LA TABLA EVENTO PARA CADA EMPLEADO
            for ($i = intval($JORInicio[0]->HOR_ID); $i < intval($JORFinal[0]->HOR_ID) + 1; $i++) {

                $sql4 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
                FROM horas AS hor
                WHERE hor.HOR_ID = " . $i;

                $evento = DB::select($sql4);

                $eventos[] = array(
                    "CAM_ID" => $request->CAM_ID,
                    "MAL_DIA" => $FECHA,
                    "MAL_INICIO" => $FECHA . " " . $evento[0]->HOR_INICIO,
                    "MAL_FINAL" => $FECHA . " " . $evento[0]->HOR_FINAL,
                    "EMP_ID" => $request->id_empleado,
                    "USER_ID" => $request->USER_ID
                );
            }

            //VERIFICAR SI EXISTE EL REGISTRO, ACTUALIZAR SI EXISTE O INSERTAR SI NO EXISTE
            for ($i = 0; $i < count($eventos); $i++) {

                $malla[] = array(
                    "CAM_ID" => $eventos[$i]["CAM_ID"],
                    "MAL_DIA" => $eventos[$i]["MAL_DIA"],
                    "MAL_INICIO" => $eventos[$i]["MAL_INICIO"],
                    "MAL_FINAL" => $eventos[$i]["MAL_FINAL"],
                    "EMP_ID" => $eventos[$i]["EMP_ID"],
                    "USER_ID" => $eventos[$i]["USER_ID"]
                );

                $sqlvalidador = 'SELECT * FROM mallas WHERE EMP_ID = ' . $eventos[$i]["EMP_ID"] . ' AND MAL_DIA = "' . $eventos[$i]["MAL_DIA"] . '" AND MAL_INICIO = "' . $eventos[$i]["MAL_INICIO"] . '" AND MAL_FINAL = "' . $eventos[$i]["MAL_FINAL"] . '"';

                $validador = DB::select($sqlvalidador);

                if (count($validador) == 0) {
                    malla::insert($malla);
                } else {
                    malla::where('MAL_ID', '=', $validador[0]->MAL_ID)->update($malla[0]);
                }
                $malla = array();
            }
            $FECHA = date("Y-m-d", strtotime($FECHA . "+ 1 days"));
        }
        /* return view('Supervisor.malla_individual.asignarmalla', compact('horas','campanas', 'MAL_DIA', 'EMP_ID')); */
        return redirect()->back()->with('rgcmessage', 'Jornada individual cargada con exito!...');
    }
}
