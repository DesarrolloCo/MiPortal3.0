<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\unidad_negocio;
use App\Models\cliente;
use App\Models\uni_cli;


class UniclisController extends Controller
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
        $sql = "SELECT unicli.UNC_ID, unicli.UNI_ID, uni.UNI_NOMBRE, unicli.CLI_ID, ser.CLI_NOMBRE, unicli.UNC_ESTADO
        FROM uni_clis AS unicli
        INNER JOIN unidad_negocios AS uni ON uni.UNI_ID = unicli.UNI_ID
        INNER JOIN clientes AS ser ON ser.CLI_ID = unicli.CLI_ID
        WHERE unicli.UNC_ESTADO = 1";

        $uni_clis = DB::select($sql);
        $unidad_negocios = unidad_negocio::where('UNI_ESTADO', '=', '1')->get();
        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();

        return view('Malla.Uni_cli.index', compact('uni_clis', 'clientes', 'unidad_negocios'));
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
            'UNI_ID' => 'required',
            'CLI_ID' => 'required'
        ]);

        $datosuni_cli = request()->except('_token');
        uni_cli::insert($datosuni_cli);

        return redirect()->back()->with('rgcmessage', 'Relacion cargada con exito!...');
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
        $datosuni_cli = request()->except(['_token','_method']);
        uni_cli::where('UNC_ID','=', $id)->update($datosuni_cli);


        Session::flash('msjupdate', '¡La relacion se a actualizado correctamente!...');
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
        /* uni_cli::where('UNC_ID', $id)->delete(); */
        uni_cli::where('UNC_ID', $id)->update(['UNC_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Relacion borrada correctamente!...');
    }
}
