<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;

class SelectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Cli_cam(request $request){

        $sql = "SELECT cam.CAM_ID, cam.CAM_NOMBRE
        FROM campanas AS cam
        INNER JOIN uni_clis AS unc ON unc.UNC_ID = cam.UNC_ID
        INNER JOIN clientes AS cli on cli.CLI_ID = unc.CLI_ID
        INNER JOIN unidad_negocios AS uni on uni.UNI_ID = unc.UNI_ID
        WHERE cam.CAM_ESTADO = 1
        AND unc.UNC_ESTADO = 1
        AND cli.CLI_ESTADO = 1
        AND uni.UNI_ESTADO = 1
        AND cli.CLI_ID = ".$request->CLI_ID;

        $campana = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "campana" => $campana
            )
        );

    }

    public function mallagrupal_select(request $request){

        $sql = "SELECT * FROM `empleados`
        WHERE EMP_ESTADO = 1
        AND `CAM_ID` = ".$request->CAM_ID;

        $data = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "data" => $data
            )
        );
    }

    public function dep_mun(request $request){

        $sql = "SELECT * FROM `municipios` WHERE `DEP_ID` = ".$request->DEP_ID;

        $data = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "data" => $data
            )
        );

    }
}
