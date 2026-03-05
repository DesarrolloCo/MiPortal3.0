<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\emc_funcione;

class FuncionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($emc_id)
    {
        //
        $funciones = emc_funcione::where('EMF_ESTADO', '=','1')->where('EMC_ID', '=', $emc_id)->get();

        return view('Malla.Funciones.index', compact('funciones', 'emc_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //

        $datosfuncione = request()->except('_token');
        emc_funcione::insert($datosfuncione);

        return redirect()->back()->with('rgcmessage', 'funcion cargada con exito!...');
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
        $datosfuncione = request()->except(['_token','_method']);
        emc_funcione::where('EMF_ID','=', $id)->update($datosfuncione);

        return redirect()->back()->with('warmessage', '¡El funcion se a actualizado correctamente!...');
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
         /* emc_funcione::where('CAR_ID', $id)->delete(); */
         emc_funcione::where('EMF_ID', $id)->update(['EMF_ESTADO' => '0']);
         return redirect()->back()->with('msjdelete', 'funcion borrada correctamente!...');
    }
}
