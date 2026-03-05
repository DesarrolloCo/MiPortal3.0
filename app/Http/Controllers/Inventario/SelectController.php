<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function equipos_select(request $request){

        $sql = "SELECT * FROM `equipos`
        WHERE EQU_ESTADO = 1
        AND `ARE_ID` = ".$request->ARE_ID;

        $data = DB::select($sql);

        echo json_encode(
            array(
                "success" => true,
                "data" => $data
            )
        );
    }

}
