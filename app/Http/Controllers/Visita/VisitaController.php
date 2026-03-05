<?php

namespace App\Http\Controllers\Visita;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

use App\Models\registro;

class VisitaController extends Controller
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
        $sql = "SELECT `REG_ID`, `REG_ESTADO`, `USER_ID`, `REG_NOMBRE`, `REG_TIPO_ID`, `REG_CEDULA`, `REG_EMPRESA`, `REG_MOTIVO_INGRESO`, `REG_EQUIPO`, `REG_SERIAL`, `REG_FECHA_HORA_SALIDA`, `created_at`
        FROM `registros`
        WHERE REG_ESTADO = 1;";
        $registros = DB::select($sql);
        return view('Visita.index', compact('registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $datosregistro = request()->except('_token');
        registro::insert($datosregistro);

        return redirect()->back()->with('rgcmessage', 'Registro cargada con exito!...');
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

    public function exit(Request $request, $id)
    {
        //
        $fechasal = date('Y-m-d H:i:s');
        $sql = "UPDATE registros SET REG_FECHA_HORA_SALIDA = '$fechasal' WHERE REG_ID = '$id'";
        $registro_salida = DB::select($sql);
        /* dd($registro_salida); */
        return redirect()->back()->with('rgcmessage', 'Registro actualizado con exito!...');
    }
}
