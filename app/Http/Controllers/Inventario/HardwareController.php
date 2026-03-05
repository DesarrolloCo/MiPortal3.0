<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\hardwares;

class HardwareController extends Controller
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
        $sql="SELECT HAR_ID, HAR_TIPO, HAR_DESCRIPCION, HAR_MODELO, HAR_SERIAL, HAR_OBSERVACION FROM `hardwares` WHERE HAR_ESTADO = 1";

        $hardwares = DB::select($sql);

        return view('Inventario.Hardware.index', compact('hardwares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosHardware = request()->except('_token');
        /* dd($datosHardware); */
        hardwares::insert($datosHardware);

        return redirect()->back()->with('rgcmessage', 'Hardware cargado con exito!...');
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
        $datosHardware = request()->except(['_token','_method']);
        hardwares::where('HAR_ID','=', $id)->update($datosHardware);

        Session::flash('msjupdate', '¡El hardware se a actualizado correctamente!...');
        return redirect()->back();
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
        hardwares::where('HAR_ID', $id)->update(['HAR_ESTADO' => '0']);
        return  redirect()->back()->with('msjdelete', 'Hardware borrado correctamente!...');
    }
}
