<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\uni_cli;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\novedade;
use App\Models\tipos_novedade;

class GrupalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $sql = "SELECT HOR_ID,HOR_INICIO,HOR_FINAL FROM horas WHERE HOR_ESTADO = 1";

        $horas = DB::select($sql);
        $jornadas = jornada::where('JOR_ESTADO', '=', '1')->get();
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();

        return view('Malla.Horarios.Grupal.index', compact('horas','jornadas','clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //
        //TRAER TODOS LOS EMPLEADOS
        $sql = "SELECT * FROM empleados WHERE CAM_ID = ".$request->CAM_ID;
        $empleados = DB::select($sql);

        if($request->JOR_ID != null && $request->checkJorOrHor == 0){
            //TRAER EL REGISTRO DE LA HORA INICIAL DE LA JORNADA
            $sql2 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
            FROM jornadas AS jor
            INNER JOIN horas AS hor ON hor.HOR_ID = jor.JOR_INICIO
            WHERE jor.JOR_ID = ".$request->JOR_ID;

            $JORInicio = DB::select($sql2);

            //TRAER EL REGISTRO DE LA HORA FINAL DE LA JORNADA
            $sql3 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
            FROM jornadas AS jor
            INNER JOIN horas AS hor ON hor.HOR_ID = jor.JOR_FINAL
            WHERE jor.JOR_ID = ".$request->JOR_ID;

            $JORFinal = DB::select($sql3);

        }else if($request->HORA_INICIAL != null && $request->HORA_FINAL != null && $request->checkJorOrHor == 1){
            //TRAER EL REGISTRO DE LA HORA INICIAL
            $sql2 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
            FROM horas AS hor
            WHERE hor.HOR_ID = ".$request->HORA_INICIAL;

            $JORInicio = DB::select($sql2);

            //TRAER EL REGISTRO DE LA HORA FINAL
            $sql3 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
            FROM horas AS hor
            WHERE hor.HOR_ID = ".$request->HORA_FINAL;

            $JORFinal = DB::select($sql3);
        }else{
            return redirect()->back()->with('warmessage', 'Tiene que seleccionar por lo menos una jornada y si activa el formato por hora, una hora inicial y hora final!...');
        }

        //EXTRAEMOS LA FECHA INICIAL Y FINAL
        //REALIZAMOS UN AGENDAMIENTO POR CADA FECHA EMPEZANDO POR LA INICIAL HASTA LA FINAL
        $eventos = [];
        $FECHA_INICIAL = strtotime($request->FECHA_INICIAL);
        $FECHA_FINAL = strtotime($request->FECHA_FINAL);
        $DIA_INICIAL =  date("d", $FECHA_INICIAL);
        $DIA_FINAL =  date("d", $FECHA_FINAL);
        $FECHA = $request->FECHA_INICIAL;

        $MES_INICIAL =  date("m", $FECHA_INICIAL);
        $MES_FINAL =  date("m", $FECHA_FINAL);

        if(intval($MES_INICIAL) != intval($MES_FINAL)){
            return redirect()->back()->with('warmessage', 'Solo puede agendar en un mes a la vez!...');
        }


        if(intval($DIA_INICIAL) > intval($DIA_FINAL)){
            return redirect()->back()->with('warmessage', 'La fecha inicial es mayor que la final!...');
        }


        for ($i2 = intval($DIA_INICIAL); $i2 < intval($DIA_FINAL)+1; $i2++) {
            //BUSCAR CADA HORA Y CREAR LOS EVENTOS PARA INSERTAR EN LA TABLA EVENTO PARA CADA EMPLEADO
            for ($i = intval($JORInicio[0]->HOR_ID); $i < intval($JORFinal[0]->HOR_ID)+1; $i++) {

                $sql4 = "SELECT hor.HOR_ID, hor.HOR_INICIO, hor.HOR_FINAL
                FROM horas AS hor
                WHERE hor.HOR_ID = ".$i;

                $evento = DB::select($sql4);

                foreach ($empleados as $list){
                    $eventos[] = array(
                        "CAM_ID" => $request->CAM_ID,
                        "MAL_DIA" => $FECHA,
                        "MAL_INICIO" => $FECHA." ".$evento[0]->HOR_INICIO,
                        "MAL_FINAL" => $FECHA." ".$evento[0]->HOR_FINAL,
                        "EMP_ID" => $list->EMP_ID,
                        "USER_ID" => $request->USER_ID
                    );
                }

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

                $sqlvalidador = 'SELECT * FROM mallas WHERE EMP_ID = '.$eventos[$i]["EMP_ID"].' AND MAL_DIA = "'.$eventos[$i]["MAL_DIA"].'" AND MAL_INICIO = "'.$eventos[$i]["MAL_INICIO"].'" AND MAL_FINAL = "'.$eventos[$i]["MAL_FINAL"].'"';

                $validador = DB::select($sqlvalidador);

                if(count($validador) == 0){
                    malla::insert($malla);
                }else{
                    malla::where('MAL_ID','=', $validador[0]->MAL_ID)->update($malla[0]);
                }
                $malla = Array();
            }
            $FECHA = date("Y-m-d",strtotime($FECHA."+ 1 days"));
        }
        return redirect()->back()->with('rgcmessage', 'Malla grupal cargada con exito!...');
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
    public function destroy($id)
    {
        //
    }
}
