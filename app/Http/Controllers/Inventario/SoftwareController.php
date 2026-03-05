<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\softwares;

class SoftwareController extends Controller
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
        $sql="SELECT SOF_ID, SOF_NOMBRE, SOF_VERSION FROM `softwares` WHERE SOF_ESTADO = 1";

        $softwares = DB::select($sql);

        return view('Inventario.Software.index', compact('softwares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosSoftware = request()->except('_token');
        /* dd($datosSoftware); */
        softwares::insert($datosSoftware);

        return redirect()->back()->with('rgcmessage', 'Software cargado con exito!...');
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
        $datosSoftware = request()->except(['_token','_method']);
        softwares::where('SOF_ID','=', $id)->update($datosSoftware);

        Session::flash('msjupdate', '¡El softwares se a actualizado correctamente!...');
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
        softwares::where('SOF_ID', $id)->update(['SOF_ESTADO' => '0']);
        return  redirect()->back()->with('msjdelete', 'Tipo borrado correctamente!...');
    }
}
