<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\equipo;
use App\Models\area;
use App\Models\empleado;
use App\Models\mantenimiento;
use App\Models\tipo_mantenimiento;
use App\Models\tip_asignado;
use App\Models\man_asignado;
use App\Models\tecnico;

class MantenimientoController extends Controller
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
        $sql_mantenimiento = 'SELECT e.EMP_NOMBRES, a.ARE_NOMBRE, eq.EQU_NOMBRE, eq.EQU_ID, MAN_PROVEEDOR, MAN_ID, MAN_FECHA, MAN_TECNICO
        FROM `mantenimientos` AS m
        INNER JOIN empleados AS e ON e.EMP_ID = m.MAN_TECNICO
        INNER JOIN equipos AS eq ON eq.EQU_ID = m.EQU_ID
        INNER JOIN areas AS a ON a.ARE_ID = eq.ARE_ID
        WHERE MAN_ESTADO = 1 AND MAN_STATUS = 1';

        $area = area::where('ARE_ESTADO', '=','1')->get();

        $tecnicos = "SELECT e.EMP_NOMBRES, e.EMP_ID
        FROM `tecnicos` as t
        INNER JOIN empleados AS e ON e.EMP_ID = t.EMP_ID
        WHERE TEC_ESTADO = 1";
        $tec_asignados = DB::select($tecnicos);;

        $mantenimiento_fisicos = tipo_mantenimiento::where('TIP_ESTADO', '=','1')->where('TIP_TIPO', '=','Fisico')->get();

        $mantenimiento_logicos = tipo_mantenimiento::where('TIP_ESTADO', '=','1')->where('TIP_TIPO', '=','Logico')->get();

        $tabla_mantenimiento = DB::select($sql_mantenimiento);

        return view('Inventario.Mantenimiento.index', compact('area','tec_asignados','tabla_mantenimiento','mantenimiento_fisicos','mantenimiento_logicos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return DB::transaction(function () use ($request){
            /* dd($request->request); */
            foreach($request->EQU_IDS as $EQU_ID)
            {

            $mantenimientos = new mantenimiento();
            $mantenimientos->EQU_ID = $EQU_ID;
            $mantenimientos->MAN_PROVEEDOR = $request->MAN_PROVEEDOR;
            $mantenimientos->MAN_FECHA = $request->MAN_FECHA;
            $mantenimientos->MAN_FECHA = $request->MAN_FECHA;
            $mantenimientos->MAN_TECNICO = $request->MAN_TECNICO;
            $mantenimientos->save();

            }

            return redirect()->route('Mantenimiento.index')->with('rgcmessage', 'Equipos cargados con exito!...');

        }, 5);
    }

    public function maintenance(Request $request) {

        return DB::transaction(function () use ($request){


            $man_asignado = new man_asignado();
            $man_asignado->MAN_ID=$request->MAN_ID;
            $man_asignado->MAS_TIPO=$request->MAS_TIPO;
            $man_asignado->MAS_ACTIVIDAD=$request->MAS_ACTIVIDAD;
            $man_asignado->save();

            foreach($request->TIP_ID_FIS as $TIP_ID_FI)
            {
            $tip_asignado_FI = new tip_asignado();
            $tip_asignado_FI->MAN_ID = $request->MAN_ID;
            $tip_asignado_FI->TIP_ID = $TIP_ID_FI;
            $tip_asignado_FI->save();

            }
            foreach($request->TIP_ID_LOG as $TIP_ID_LO)
            {

            $tip_asignado_LO = new tip_asignado();
            $tip_asignado_LO->MAN_ID = $request->MAN_ID;
            $tip_asignado_LO->TIP_ID = $TIP_ID_LO;
            $tip_asignado_LO->save();

            }

            $FECHA="SELECT DATE_ADD(MAN_FECHA, INTERVAL 6 month) as FECHA FROM mantenimientos WHERE EQU_ID =".$request->EQU_ID." ORDER BY MAN_FECHA ASC LIMIT 1";
            $FECHA_NEW = DB::select($FECHA);/* dd($FECHA_NEW); */
            $sql_update="UPDATE `mantenimientos` SET `MAN_STATUS`= 2 WHERE MAN_ID =".$request->MAN_ID;
            $sql_add="INSERT INTO `mi`.`mantenimientos` (`EQU_ID`,`MAN_PROVEEDOR`,`MAN_FECHA`,`MAN_TECNICO`) VALUES ('".$request->EQU_ID."','".$request->MAN_PROVEEDOR."','".$FECHA_NEW[0]->FECHA."','".$request->MAN_TECNICO."')";

            $SQLUPDATE = DB::update($sql_update);

            /* dd($FECHA_NEW); */
            $SQLADD = DB::insert($sql_add);


            return redirect()->route('Mantenimiento.index')->with('rgcmessage', 'Mantenimiento cargado con exito!...');

        }, 5);

    }

    public function details($id)
    {
        $sql="SELECT MAS_TIPO, MAS_ACTIVIDAD FROM `man_asignados` WHERE MAN_ID=".$id;

        $man_asignados = DB::select($sql);

        return view('Inventario.Mantenimiento.details', compact('man_asignados'));
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
