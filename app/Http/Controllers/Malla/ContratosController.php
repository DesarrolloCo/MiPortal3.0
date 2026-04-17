<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\empleado;
use App\Models\user;
use App\Models\cargo;
use App\Models\tipos_contrato;
use App\Models\emp_contrato;

class ContratosController extends Controller
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

    /**
     * Verificar si el usuario puede acceder a información de contratos
     */
    private function canAccessContratos()
    {
        $user = Auth::user();

        // Verificar permiso específico
        if ($user->can('ver-empleado')) {
            return true;
        }

        // Verificar roles con acceso automático
        if ($user->hasAnyRole(['Administrador', 'Supervisor', 'Contadores'])) {
            return true;
        }

        return false;
    }

    public function index($emp_id)
    {
        // Verificar permisos
        if (!$this->canAccessContratos()) {
            $user = Auth::user();
            Log::warning('Usuario sin permiso intentando acceder a contratos:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'requested_employee_id' => $emp_id
            ]);

            return redirect()->route('home')->with('error',
                'No tienes permisos para acceder al historial de contratos de empleados. ' .
                'Contacta al administrador del sistema para solicitar los permisos necesarios.'
            );
        }
        // Obtener empleado con relaciones
        $empleado = empleado::with(['cargo', 'cliente', 'municipio', 'contratoActivo.cargo'])
            ->where('EMP_ID', $emp_id)
            ->firstOrFail();

        // Obtener cargos y tipos de contratos activos
        $cargos = cargo::where('CAR_ESTADO', '=', '1')
            ->orderBy('CAR_NOMBRE', 'asc')
            ->get();

        $tipos_contratos = tipos_contrato::where('TIC_ESTADO', '=', '1')
            ->orderBy('TIC_NOMBRE', 'asc')
            ->get();

        // Usar Eloquent en lugar de SQL raw para mejor seguridad y mantenibilidad
        $contratos = emp_contrato::with(['cargo', 'tipoContrato'])
            ->where('EMC_ESTADO', 1)
            ->where('EMP_ID', $emp_id)
            ->orderBy('EMC_FECHA_INI', 'desc')
            ->get();


        return view('Malla.Contrato.index', compact('empleado', 'cargos', 'tipos_contratos', 'contratos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        if (!$this->canAccessContratos()) {
            return redirect()->route('home')->with('error', 'No tienes permisos para gestionar contratos de empleados.');
        }
        //
        $contrato = emp_contrato::where('EMP_ID', $request->EMP_ID)->where('EMC_FINALIZADO', 'NO')->get();

        empleado::where('EMP_ID', $request->EMP_ID)->update(['CAR_ID' => $request->CAR_ID]);
        empleado::where('EMP_ID', $request->EMP_ID)->update(['EMP_SUELDO' => $request->EMC_SUELDO]);
        empleado::where('EMP_ID', $request->EMP_ID)->update(['EMP_FECHA_INGRESO' => $request->EMC_FECHA_INI]);
        if ($request->EMC_FECHA_FIN != null) {
            empleado::where('EMP_ID', $request->EMP_ID)->update(['EMP_FECHA_RETIRO' => $request->EMC_FECHA_FIN]);
        }

        if(count($contrato) != null){
            return redirect()->back()->with('warmessage', 'Esta empleado tiene un contrato activo actualmente, finalizar para poder crear otro!..');
        }

        $datosContrato = request()->except('_token');
        emp_contrato::insert($datosContrato);

        return redirect()->back()->with('rgcmessage', 'Contrato cargado con exito!...');
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
    }

    public function finish(Request $request, $emc_id)
    {
        if (!$this->canAccessContratos()) {
            return redirect()->route('home')->with('error', 'No tienes permisos para gestionar contratos de empleados.');
        }
        /* emp_contrato::where('EMC_ID', $emc_id)->update(['EMC_FINALIZADO' => 'SI']); */

        $contrato = emp_contrato::where('EMC_ID', $emc_id)->get();

        $fecha =  $request->EMC_FECHA_FINALIZADO;
        /* dd($fecha); */

        $user_id = Auth::user()->id;

        if($contrato[0]->EMC_FECHA_FIN == null){

            emp_contrato::where('EMC_ID', $emc_id)->update(['EMC_FECHA_FIN' => $fecha]);

        }

        emp_contrato::where('EMC_ID', $emc_id)->update(['EMC_FINALIZADO' => 'SI']);
        emp_contrato::where('EMC_ID', $emc_id)->update(['USER_ID_FINALIZADO' => $user_id]);
        emp_contrato::where('EMC_ID', $emc_id)->update(['EMC_FECHA_FINALIZADO' => $fecha]);

        return redirect()->back()->with('msjdelete', 'Contrato finalizado correctamente!...');

    }

    public function pdf(request $request, $emc_id){

        if($request->check_salario == "1"){
            $salario = 1;
        }else{
            $salario = 0;
        }

        if($request->check_funciones == "1"){
            $funciones = 1;
        }else{
            $funciones = 0;
        }

        $contrato = emp_contrato::where('EMC_ID', $emc_id)->get();

        $empleado = empleado::where('EMP_ID', $contrato[0]->EMP_ID)->get();

        $tipos_contrato = tipos_contrato::where('TIC_ID', $contrato[0]->TIC_ID)->get();

        $cargo = cargo::where('CAR_ID', $contrato[0]->CAR_ID)->get();

        $sql = "SELECT mun.MUN_ID, mun.MUN_NOMBRE, dep.DEP_ID, dep.DEP_NOMBRE
        FROM `municipios` AS mun
        INNER JOIN departamentos AS dep ON dep.DEP_ID = mun.DEP_ID
        WHERE `MUN_ID` = ".$empleado[0]->MUN_ID;

        $lugar_ex = DB::select($sql);

        $funciones_cargo = DB::select('SELECT * FROM `car_funciones` WHERE CAF_ESTADO = 1 AND `CAR_ID` = '.$cargo[0]->CAR_ID);
        $funciones_contrato = DB::select('SELECT * FROM `emc_funciones` WHERE EMF_ESTADO = 1 AND `EMC_ID` = '.$emc_id);

        $firma_foto = DB::select('SELECT `PAR_VALOR` FROM `parametros` WHERE `PAR_CODE` = "FOTO_FIRMA_DIRECTOR_GENERAL"');
        $firma_texto = DB::select('SELECT `PAR_VALOR` FROM `parametros` WHERE `PAR_CODE` = "NOMBRE_DIRECTOR_GENERAL"');

        $data = [
            'Nombre' => $empleado[0]->EMP_NOMBRES,
            'Sexo' => $empleado[0]->EMP_SEXO,
            'Cedula' => $empleado[0]->EMP_CEDULA,
            'Departamento' => $lugar_ex[0]->DEP_NOMBRE,
            'Municipio' => $lugar_ex[0]->MUN_NOMBRE,
            'Tipo_contrato' => $tipos_contrato[0]->TIC_NOMBRE,
            'Salario' => $contrato[0]->EMC_SUELDO,
            'Fecha_inicio' => $contrato[0]->EMC_FECHA_INI,
            'Fecha_fin' => $contrato[0]->EMC_FECHA_FIN,
            'Cargo' => $cargo[0]->CAR_NOMBRE,
            'Funciones_cargo' => $funciones_cargo,
            'Funciones_contrato' => $funciones_contrato,
            'Val_salario' => $salario,
            'Val_funciones' => $funciones,
            'firma_foto' => $firma_foto,
            'firma_texto' => $firma_texto
        ];

        $pdf = \PDF::loadView('Malla.Contrato.file_pdf', $data);

        return $pdf->stream('archivo.pdf');
    }

}
