<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\unidad_negocio;
use Illuminate\Support\Facades\Session;


class Unidad_negociosController extends Controller
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
        $unidad_negocio = unidad_negocio::where('UNI_ESTADO', '=', '1')->get();
        return view('Malla.Unidad_Negocio.index', compact('unidad_negocio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //
        $request->validate([
            'UNI_NOMBRE' => 'required',
            'UNI_CODE' => 'required'
        ]);

        $datosUniNegocio = request()->except('_token');
        unidad_negocio::insert($datosUniNegocio);

        return redirect()->back()->with('rgcmessage', '¡Unidad de Negocio cargada con exito!...');
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
        $datosUniNegocios = request()->except(['_token','_method']);
        unidad_negocio::where('UNI_ID','=', $id)->update($datosUniNegocios);


        Session::flash('msjupdate', '¡La Unidad de Negocio se a actualizado correctamente!...');
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
        /* unidad_negocio::where('UNI_ID', $id)->delete(); */
        unidad_negocio::where('UNI_ID', $id)->update(['UNI_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', '¡Unidad de Negicio borrada correctamente!...');
    }
}
