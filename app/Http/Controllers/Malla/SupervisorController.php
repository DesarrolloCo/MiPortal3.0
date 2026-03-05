<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\uni_cli;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\novedade;
use App\Models\tipos_novedade;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request, $id){
        //
        malla::where('MAL_ID', $id)->delete();

        $MAL_DIA = $request->MAL_DIA;
        $empleado = empleado::where('EMP_ID', $request->EMP_ID)->get();
        $EMP_ID = $empleado[0]->EMP_ID;

        $sql = 'SELECT mal.MAL_ID, cli.CLI_ID, cli.CLI_NOMBRE, mal.CAM_ID,
        cam.CAM_NOMBRE, mal.EMP_ID, mal.MAL_INICIO, mal.MAL_FINAL, mal.MAL_ESTADO
        FROM mallas AS mal
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        INNER JOIN uni_clis AS unc ON unc.UNC_ID = cam.UNC_ID
        INNER JOIN clientes AS cli on cli.CLI_ID = unc.CLI_ID
        INNER JOIN unidad_negocios AS uni on uni.UNI_ID = unc.UNI_ID
        WHERE mal.MAL_DIA = "'.$MAL_DIA.'"
        AND EMP_ID = '.$request->EMP_ID;
        $emp_horario = DB::select($sql);

        $tipos_novedades = tipos_novedade::where('TIN_ESTADO', '=', '1')->where('TIN_TIPO', '=', '0')->get();

        $clientes = cliente::all();

        return view('Malla.Horarios.Individual.editar_horario', compact('tipos_novedades','emp_horario','clientes','empleado', 'MAL_DIA','EMP_ID'));
    }

    public function look_for(request $request){

        $sql = "SELECT mal.MAL_DIA, emp.EMP_ID, emp.EMP_CEDULA, emp.EMP_NOMBRES
        FROM `empleados` AS emp
        INNER JOIN mallas AS mal ON mal.EMP_ID = emp.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        WHERE mal.MAL_INICIO > '".$request->FECHA_INICIAL."'
        AND mal.MAL_FINAL < '".$request->FECHA_FINAL."'
        AND cam.CAM_ID = ".$request->CAM_ID."
        GROUP BY emp.EMP_ID, emp.EMP_CEDULA, emp.EMP_NOMBRES, mal.MAL_DIA
        ORDER BY mal.MAL_DIA ASC";

        $empleados = DB::select($sql);

        return view('Malla.Horarios.Consultar.consulta', compact('empleados'));
    }
}
