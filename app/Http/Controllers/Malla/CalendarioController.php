<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;

class CalendarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function calendario_Agente(request $request){

        $sql = "SELECT mal.MAL_ID, cam.CAM_NOMBRE AS title, mal.MAL_INICIO AS start, mal.MAL_FINAL AS end
        FROM mallas AS mal
        INNER JOIN campanas cam
        WHERE mal.CAM_ID = cam.CAM_ID
        AND mal.MAL_ESTADO = 1
        AND mal.EMP_ID = ".$request->id_empleado;

        $eventos = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "evento" => $eventos
            )
        );

    }

    public function calendario_Supervisor_Agente(request $request){

        $sql = "SELECT mal.MAL_ID, cam.CAM_NOMBRE AS title, mal.MAL_INICIO AS start, mal.MAL_FINAL AS end, mal.MAL_ESTADO
        FROM mallas AS mal
        INNER JOIN campanas cam
        WHERE mal.CAM_ID = cam.CAM_ID
        AND mal.EMP_ID = ".$request->id_empleado;

        $eventos = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "evento" => $eventos
            )
        );

    }

}
