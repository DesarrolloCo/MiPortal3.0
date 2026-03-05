<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\tipos_estados;


class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sql="SELECT TIE_ID, TIE_NOMBRE FROM `tipos_estados` WHERE TIE_ESTADO = 1";

        $estados = DB::select($sql);

        return view('Inventario.Estado.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosEstado = request()->except('_token');
        /* dd($datosEstado); */
        tipos_estados::insert($datosEstado);

        return redirect()->back()->with('rgcmessage', 'Estado cargado con exito!...');
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
        $datosEstado = request()->except(['_token','_method']);
        tipos_estados::where('TIE_ID','=', $id)->update($datosEstado);

        Session::flash('msjupdate', '¡El estado se a actualizado correctamente!...');
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
        tipos_estados::where('TIE_ID', $id)->update(['TIE_ESTADO' => '0']);
        return  redirect()->back()->with('msjdelete', 'Estado    borrado correctamente!...');
    }
}
