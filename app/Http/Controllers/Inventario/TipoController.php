<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\tipo_mantenimiento;

class TipoController extends Controller
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
        $tipo_mantenimientos = tipo_mantenimiento::where('TIP_ESTADO', '=','1')->get();

        return view('Inventario.Tipo.index', compact('tipo_mantenimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosTipo = request()->except('_token');
        /* dd($datosTipo); */
        tipo_mantenimiento::insert($datosTipo);

        return redirect()->route('Tipo.index')->with('rgcmessage', 'Tipo de mantenimiento cargado con exito!...');
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
        $datosTipo = request()->except(['_token','_method']);
        tipo_mantenimiento::where('TIP_ID','=', $id)->update($datosTipo);


        Session::flash('msjupdate', '¡El tipo se a actualizado correctamente!...');
        return redirect()->route('Tipo.index');
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
        tipo_mantenimiento::where('TIP_ID', $id)->update(['TIP_ESTADO' => '0']);
        return  redirect()->route('Tipo.index')->with('msjdelete', 'Tipo borrado correctamente!...');
    }
}
