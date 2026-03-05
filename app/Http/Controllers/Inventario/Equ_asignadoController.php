<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\equipo;
use App\Models\equ_asignado;
use App\Models\empleado;
use App\Models\evi_asignado;
use App\Models\area;
use Illuminate\Support\Facades\Storage;
use PDF;

class Equ_asignadoController extends Controller
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

        $sql="SELECT e.EMP_NOMBRES, pc.EQU_ID,pc.EQU_NOMBRE, pc.EQU_SERIAL, EAS_ID, EAS_FECHA_ENTREGA, eq.EAS_ESTADO
        FROM equ_asignados AS eq
        INNER JOIN empleados AS e ON e.EMP_ID = eq.EMP_ID
        INNER JOIN equipos AS pc ON pc.EQU_ID = eq.EQU_ID
        WHERE eq.EAS_ESTADO = 1 OR EAS_ESTADO = 2";

        $sql2="SELECT EMP_ID, EMP_NOMBRES FROM `empleados` WHERE EMP_ESTADO = 1 AND EMP_ACTIVO = 'SI';";

        $sql3="SELECT EQU_ID, EQU_NOMBRE, EQU_SERIAL FROM `equipos` WHERE EQU_ESTADO = 1;";

        $sql4="SELECT TIE_ID, TIE_NOMBRE FROM `tipos_estados` WHERE TIE_ESTADO = 1;";

        $total = equipo::count();

        $equipos = equipo::where('EQU_ESTADO', '=','1')->get();

        $empleado = empleado::where('EMP_ESTADO', '=','1')->get();

        $equ_asignado = DB::select($sql);
        $exc_emp = DB::select($sql2);
        $exc_equ = DB::select($sql3);
        $tipos_estados = DB::select($sql4);
        /* dd($equ_asignado); */
        return view('Inventario.Asignacion_equipo.index', compact('equ_asignado','empleado','equipos','exc_emp','exc_equ','tipos_estados'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosEquAsignado = request()->except('_token');

        equ_asignado::insert($datosEquAsignado);
        return redirect()->back()->with('rgcmessage', 'Asignacion cargada con exito!...');

    }

    public function evidencia(Request $request)
    {
    // Validación del formulario, si es necesario
    $request->validate([
        'EVI_EVIDENCIA' => 'required|mimes:pdf|max:10240', // Ajusta las reglas según tus necesidades
    ]);

    // Obtén el contenido del archivo PDF en binario
    $pdfBinaryData = file_get_contents($request->file('EVI_EVIDENCIA')->path());

    // Guardar en la base de datos
    DB::table('evi_asignados')->insert([
        'EAS_ID' => $request->EAS_ID,
        'EVI_NOMBRE' => $request->EVI_NOMBRE,
        'EVI_FECHA' => $request->EVI_FECHA,
        'EVI_EVIDENCIA' => $pdfBinaryData,
    ]);

    return redirect()->back()->with('success', 'PDF guardado exitosamente.');
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

        $datosEvidencias = request()->except('_token');

        /* dd($datosEvidencias); */

        if($request->hasFile('EVI_EVIDENCIA')){
            $datosEvidencias['EVI_EVIDENCIA']=$request->file('EVI_EVIDENCIA')->store('uploads','public');
        }

        evi_asignado::insert($datosEvidencias);
        /* return response()->json($datosEvidencias); */
        return redirect()->back()->with('rgcmessage', 'Asignacion cargada con exito!...');
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
        equ_asignado::where('EAS_ID', $id)->update(['EAS_ESTADO' => '2']);
        return redirect()->back()->with('msjdelete', 'Asignacion removida correctamente!...');
    }
}
