<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\cliente;
use App\Models\campana;
use App\Models\uni_cli;

class CampanasController extends Controller
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
        $sql = "SELECT cli.CLI_ID, cli.CLI_NOMBRE, uni.UNI_ID, uni.UNI_NOMBRE, unc.UNC_ID, cam.*
        FROM campanas AS cam
        INNER JOIN uni_clis AS unc ON unc.UNC_ID = cam.UNC_ID
        INNER JOIN unidad_negocios AS uni ON uni.UNI_ID = unc.UNI_ID
        INNER JOIN clientes AS cli ON cli.CLI_ID = unc.CLI_ID
        WHERE cam.CAM_ESTADO = 1
        AND unc.UNC_ESTADO = 1
        AND uni.UNI_ESTADO = 1
        AND cli.CLI_ESTADO = 1";

        $campanas = DB::select($sql);

        $sql_2 = "SELECT unc.UNC_ID, uni.UNI_ID, uni.UNI_NOMBRE, cli.CLI_ID, cli.CLI_NOMBRE
        FROM uni_clis AS unc
        INNER JOIN unidad_negocios AS uni ON uni.UNI_ID = unc.UNI_ID
        INNER JOIN clientes AS cli ON cli.CLI_ID = unc.CLI_ID
        WHERE unc.UNC_ESTADO = 1
        AND uni.UNI_ESTADO = 1
        AND cli.CLI_ESTADO = 1";

        $uni_clis = DB::select($sql_2);
        /* dd($campanas); */
        return view('Malla.Campana.index', compact('campanas', 'uni_clis'));
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
            'CAM_NOMBRE' => 'required',
            'CAM_CODE' => 'required'
        ]);

        $datoscampana = request()->except('_token');
        campana::insert($datoscampana);

        return redirect()->back()->with('rgcmessage', 'Campaña cargada con exito!...');
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
        $datoscampana = request()->except(['_token','_method']);
        campana::where('CAM_ID','=', $id)->update($datoscampana);


        Session::flash('msjupdate', '¡La campana se a actualizado correctamente!...');
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
        /* campana::where('CAM_ID', $id)->delete(); */
        campana::where('CAM_ID', $id)->update(['CAM_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Campaña borrada correctamente!...');
    }
}
