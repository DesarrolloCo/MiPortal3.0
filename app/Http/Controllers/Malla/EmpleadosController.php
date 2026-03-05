<?php

namespace App\Http\Controllers\Malla;

use \Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\uni_cli;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\User;
use App\Models\cargo;
use App\Models\departamento;
use App\Models\municipio;
use App\Models\emp_contrato;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;


class EmpleadosController extends Controller
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
        $departamentos = departamento::where('DEP_ESTADO', '=', '1')->get();

        $sql = "SELECT cam.CAM_NOMBRE, car.CAR_NOMBRE, usu.name, emp.*
        FROM empleados AS emp
        LEFT JOIN users AS usu ON usu.id = emp.USER_ID
        LEFT JOIN cargos AS car ON car.CAR_ID = emp.CAR_ID
        LEFT JOIN campanas AS cam ON cam.CAM_ID = emp.CAM_ID
        WHERE emp.EMP_ESTADO = 1";

        $empleados = DB::select($sql);
        $cargos = cargo::where('CAR_ESTADO', '=', '1')->get();
        $campanas = campana::where('CAM_ESTADO', '=', '1')->get();

        return view('Malla.Empleado.index', compact('empleados', 'cargos', 'campanas', 'departamentos'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $estado = $request->input('estado');

        $empleado = \App\Models\empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        $empleado->EMP_ACTIVO = $estado;
        $empleado->save();

        return response()->json([
            'mensaje' => 'Estado actualizado correctamente',
            'estado' => $empleado->EMP_ACTIVO
        ]);
    }

    public function create(request $request)
    {
        $request->validate([
            'EMP_CODE' => 'required',
            'EMP_CEDULA' => 'required',
            'EMP_NOMBRES' => 'required',
            'EMP_DIRECCION' => '',
            'EMP_TELEFONO' => '',
            'MUN_ID' => 'required',
            'EMP_SEXO' => 'required',
            'EMP_FECHA_NACIMIENTO' => 'required',
            'EMP_FECHA_INGRESO' => 'required',
            'CAM_ID' => 'required',
            'EMP_EMAIL' => 'required',
        ]);

        return DB::transaction(function () use ($request){

            $user = new User();
            $user->name = $request->EMP_NOMBRES;
            $user->email = $request->EMP_EMAIL;
            $user->password = Hash::make($request->EMP_CEDULA);
            $user->save();

            $user->assignRole('Agente');

            $empleado = new empleado();
            $empleado->USER_ID = $user->id;
            $empleado->EMP_CODE = $request->EMP_CODE;
            $empleado->EMP_CEDULA = $request->EMP_CEDULA;
            $empleado->EMP_NOMBRES = $request->EMP_NOMBRES;
            $empleado->EMP_DIRECCION = $request->EMP_DIRECCION;
            $empleado->EMP_TELEFONO = $request->EMP_TELEFONO;
            $empleado->EMP_SEXO = $request->EMP_SEXO;
            $empleado->EMP_FECHA_NACIMIENTO = $request->EMP_FECHA_NACIMIENTO;
            $empleado->EMP_FECHA_INGRESO = $request->EMP_FECHA_INGRESO;
            $empleado->EMP_SUELDO = $request->EMP_SUELDO;
            $empleado->MUN_ID = $request->MUN_ID;
            $empleado->CAM_ID = $request->CAM_ID;
            $empleado->EMP_EMAIL = $request->EMP_EMAIL;
            $empleado->save();

            return redirect()->back()->with('rgcmessage', 'Empleado y usuario creados con exito!...');

        }, 5);

    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id){

            $empleado = empleado::where('EMP_ID', $id)->get();

            $user_id = $empleado[0]->USER_ID;

           /*  User::where('id', $user_id)->delete();
            empleado::where('EMP_ID', $id)->delete(); */

            User::where('id', $user_id)->update(['estado' => '0']);
            $user = User::find($user_id);
            DB::table('model_has_roles')->where('model_id',$user_id)->delete();

            $user->assignRole('Inactivo');
            empleado::where('EMP_ID', $id)->update(['EMP_ESTADO' => '0']);

            return redirect()->back()->with('msjdelete', 'Empleado y usuario borrados correctamente!...');

        },5);

    }

    public function update(request $request, $id)
    {

        $datosEmpleado = request()->except(['_token','_method']);
        empleado::where('EMP_ID','=', $id)->update($datosEmpleado);

        Session::flash('msjupdate', '¡El Empleado se a actualizado correctamente!...');
        return redirect()->back();

    }

    /**

    * @param Request $request

    * @return \Illuminate\Http\RedirectResponse

    * @throws \Illuminate\Validation\ValidationException

    * @throws \PhpOffice\PhpSpreadsheet\Exception

    */

   function importData(Request $request){

    DB::transaction(function () use($request) {


    $this->validate($request, [

        'file' => 'required|file|mimes:xls,xlsx'

    ]);

    $the_file = $request->file('file');

    try{

        $spreadsheet = IOFactory::load($the_file->getRealPath());

        $sheet        = $spreadsheet->getActiveSheet();

        $row_limit    = $sheet->getHighestDataRow();

        $column_limit = $sheet->getHighestDataColumn();

        $row_range    = range( 2, $row_limit );

        $column_range = range( 'O', $column_limit );

        $startcount = 2;




        $data = array();


        foreach ( $row_range as $row ) {

            $data[] = [
                'cargo' =>$sheet->getCell( 'A' . $row )->getValue(),
                'codigo' => $sheet->getCell( 'B' . $row )->getValue(),
                'cedula' => $sheet->getCell( 'C' . $row )->getValue(),
                'municipio_expedida' => $sheet->getCell( 'D' . $row )->getValue(),
                'nombres' => $sheet->getCell( 'E' . $row )->getValue(),
                'direccion' =>$sheet->getCell( 'F' . $row )->getValue(),
                'telefono' =>$sheet->getCell( 'G' . $row )->getValue(),
                'sexo' =>$sheet->getCell( 'H' . $row )->getValue(),
                'fecha_nacimiento' =>$sheet->getCell( 'I' . $row )->getValue(),
                'fecha_ingreso' =>$sheet->getCell( 'J' . $row )->getValue(),
                'fecha_retiro' =>$sheet->getCell( 'K' . $row )->getValue(),
                'sueldo' =>$sheet->getCell( 'L' . $row )->getValue(),
                'tipo_de_contrato' =>$sheet->getCell( 'M' . $row )->getValue(),
                'campana' =>$sheet->getCell( 'N' . $row )->getValue(),
                'email' =>$sheet->getCell( 'O' . $row )->getValue(),
            ];

            $startcount++;

        }

        for ($s=0; $s < count($data) ; $s++) {

            $count = empleado::where('EMP_CEDULA', $data[$s]['cedula'])->count();

            if($count == 0){
                if (!empty($data[$s]['nombres']) && !empty($data[$s]['email'])) {
                    $user = User::create([
                        'name'     => $data[$s]['nombres'],
                        'email'    => $data[$s]['email'],
                        'password' => Hash::make($data[$s]['cedula'])
                    ]);
                    $user->assignRole('Agente');
                } else {
                    // Omitir fila o registrar error
                    continue;
                }



                $user->assignRole('Agente');

                $cargo = cargo::where('CAR_CODE', $data[$s]['cargo'])->get();
                $campana = campana::where('CAM_CODE', $data[$s]['campana'])->get();

                $empleado = new empleado();
                $empleado->USER_ID = $user->id;
                $empleado->CAR_ID = $cargo[0]->CAR_ID;
                $empleado->EMP_CODE = $data[$s]['codigo'];
                $empleado->EMP_CEDULA = $data[$s]['cedula'];
                $empleado->MUN_ID = $data[$s]['municipio_expedida'];
                $empleado->EMP_NOMBRES = $data[$s]['nombres'];
                $empleado->EMP_DIRECCION = $data[$s]['direccion'];
                $empleado->EMP_TELEFONO = $data[$s]['telefono'];
                $empleado->EMP_SEXO = $data[$s]['sexo'];
                $empleado->EMP_FECHA_NACIMIENTO = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$s]['fecha_nacimiento'])->format('Y-m-d');
                $empleado->EMP_FECHA_INGRESO = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$s]['fecha_ingreso'])->format('Y-m-d');
                if($data[$s]['fecha_retiro'] != null){
                    $empleado->EMP_FECHA_RETIRO = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$s]['fecha_retiro'])->format('Y-m-d');
                }
                $empleado->EMP_SUELDO = $data[$s]['sueldo'];
                $empleado->EMP_TIPO_CONTRATO = $data[$s]['tipo_de_contrato'];
                $empleado->CAM_ID = $campana[0]->CAM_ID;
                $empleado->EMP_EMAIL = $data[$s]['email'];
                $empleado->save();

                $emp_id = $empleado->id;

            }else{
                $cargo = cargo::where('CAR_CODE', $data[$s]['cargo'])->get();
                $campana = campana::where('CAM_CODE', $data[$s]['campana'])->get();
                $empleado = Empleado::where('EMP_CEDULA', $data[$s]['cedula'])->get();

                $emp_id = $empleado[0]->EMP_ID;
            }

            $sql = "SELECT count(*) AS conteo FROM emp_contratos WHERE EMP_ID = ".$emp_id." AND EMC_FINALIZADO = 'NO'";

            $val_con = DB::select($sql);
            if($val_con[0]->conteo == 0){

                $contrato = new emp_contrato();
                $contrato->EMP_ID = $emp_id;
                $contrato->CAR_ID = $cargo[0]->CAR_ID;
                $contrato->TIC_ID = $data[$s]['tipo_de_contrato'];
                $contrato->EMC_SUELDO = $data[$s]['sueldo'];
                $contrato->USER_CREATED = $id = Auth::user()->id;
                $contrato->EMC_FECHA_INI = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$s]['fecha_ingreso'])->format('Y-m-d');
                if($data[$s]['fecha_retiro'] != null){
                    $contrato->EMC_FECHA_FIN = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$s]['fecha_retiro'])->format('Y-m-d');
                }
                $contrato->save();

            }

            /* dd($data[$s]['cargo']); */

        }

    } catch (Exception $e) {

        $error_code = $e->errorInfo[1];




        return back()->with('msjdelete', 'Empleado y usuario no se importaron correctamente!...');

    }

    return redirect()->back()->with('rgcmessage', 'Empleados y usuarios importados exitosamente');

});

}

}
