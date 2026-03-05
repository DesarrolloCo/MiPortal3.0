<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

use App\Models\campana;
use App\Models\informe;
use App\Models\cliente;

use App\Models\User;

class InformeController extends Controller
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
        $ids = Auth::user()->id;
        if(Auth::user()->hasPermissionTo('cliente_informe')){
            $sql = "SELECT inf.INF_ID, inf.INF_NOMBRE, inf.INF_URL, inf.CLI_ID, cam.CAM_NOMBRE, cam.CAM_ID, usu.id, usu.name
            FROM `informes` AS  inf
            INNER JOIN campanas AS cam ON cam.CAM_ID = inf.CAM_ID
            INNER JOIN users AS usu ON usu.id = inf.CLI_ID
            WHERE INF_ESTADO = 1 AND CLI_ID =". $ids;
        }else{
            $sql = "SELECT inf.INF_ID, inf.INF_NOMBRE, inf.INF_URL, inf.CLI_ID, cam.CAM_NOMBRE, cam.CAM_ID, usu.id, usu.name
            FROM `informes` AS  inf
            INNER JOIN campanas AS cam ON cam.CAM_ID = inf.CAM_ID
            LEFT JOIN users AS usu ON usu.id = inf.CLI_ID
            WHERE INF_ESTADO = 1;";
        }


        $campanas = campana::where('CAM_ESTADO', '=', '1')->get();
        $clientes = User::where('estado', '1')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'Cliente mi reportes');
        })
        ->get();

        /* $sql2 = "
        ;"; */


        $informe = DB::select($sql);
        /* $clientes = DB::select($sql2); */

        return view('Reporte.Informe.index', compact('campanas', 'informe', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosInforme = request()->except('_token');
        informe::insert($datosInforme);

        return redirect()->back()->with('rgcmessage', 'Informe cargado con exito!...');
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
        $datosInforme = request()->except(['_token','_method']);
        informe::where('INF_ID','=', $id)->update($datosInforme);

        Session::flash('msjupdate', '¡El informe se a actualizado correctamente!...');
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
        /* cargo::where('CAR_ID', $id)->delete(); */
        informe::where('INF_ID', $id)->update(['INF_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Informe borrado correctamente!...');
    }

    public function reportes($id)
    {
        //
        $sql = "SELECT inf.INF_ID, inf.INF_NOMBRE, inf.INF_URL, cam.CAM_NOMBRE, cam.CAM_ID
        FROM `informes` AS  inf
        INNER JOIN campanas AS cam ON cam.CAM_ID = inf.CAM_ID
        WHERE INF_ESTADO = 1 and inf.INF_ID = ".$id;

        $informe = DB::select($sql);
        return view('Reporte.Informe.informe', compact('informe'));
    }

    public function clientes($id)
    {
        //

        $sql2 = "SELECT us.name, rol.id
        FROM `model_has_roles` AS model
        INNER JOIN users AS us ON us.id = model.model_id
        INNER JOIN roles AS rol ON rol.id = model.role_id
        WHERE rol.id = 1;";

        $clientes = DB::select($sql2);

        return view('Reporte.Informe.create', compact('clientes'));
    }

}
