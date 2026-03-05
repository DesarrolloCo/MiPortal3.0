<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\tecnico;
use App\Models\empleado;

class TecnicoController extends Controller
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
        $tecnicos="SELECT e.EMP_NOMBRES, TEC_ID
        FROM `tecnicos` as t
        INNER JOIN empleados AS e ON e.EMP_ID = t.EMP_ID
        WHERE TEC_ESTADO = 1";
        $tec_asignados = DB::select($tecnicos);/* dd($FECHA_NEW); */
        $empleado = empleado::where('EMP_ESTADO', '=','1')->get();
        return view('Inventario.Tecnico.index', compact('tec_asignados','empleado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosTecnico = request()->except('_token');
        /* dd($datosTecnico); */
        tecnico::insert($datosTecnico);

        return redirect()->back()->with('rgcmessage', 'Tecnico cargado con exito!...');
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
        tecnico::where('TEC_ID', $id)->update(['TEC_ESTADO' => '0']);
        return  redirect()->route('Tecnico.index')->with('msjdelete', 'Tipo borrado correctamente!...');
    }
}
