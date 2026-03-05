<?php

namespace App\Http\Controllers\Inventario;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\equipo;
use App\Models\area;
use App\Models\sof_asignado;
use App\Models\har_asignado;
use App\Models\tipos_estados;

class EquiposController extends Controller
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
        $total = equipo::count();

        $area = area::where('ARE_ESTADO', '=','1')->get();
        
        $sql = "SELECT (SELECT emp.EMP_NOMBRES FROM empleados AS emp WHERE emp.EMP_ID = equ_a.EMP_ID)AS NOMBRE, equ_a.EMP_ID, equ.ARE_ID, equ.EQU_ID, equ.EQU_STATUS, equ.EQU_NOMBRE, equ.EQU_TIPO, equ.EQU_SERIAL, (SELECT are.ARE_NOMBRE FROM areas AS are WHERE are.ARE_ID = equ.ARE_ID)AS AREAS, equ.EQU_PRECIO, equ.EQU_OBSERVACIONES, tip.TIE_NOMBRE, equ.EQU_STATUS, tip.TIE_NOMBRE FROM `equipos` AS equ LEFT JOIN equ_asignados AS equ_a ON equ_a.EQU_ID = equ.EQU_ID AND equ_a.EAS_ESTADO = 1 INNER JOIN tipos_estados AS tip ON tip.TIE_ID = equ.EQU_STATUS WHERE equ.EQU_ESTADO = 1";
        $estados = tipos_estados::where('TIE_ESTADO', '=','1')->get();
        $equipos = DB::select($sql);
        return view('Inventario.Equipo.index', compact('equipos','total', 'area', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $datosEquipo = request()->except('_token');
        equipo::insert($datosEquipo);

        return redirect()->route('Equipo.index')->with('rgcmessage', 'Equipo cargado con exito!...');
    }

    public function software()
    {
        //
        $datosSoftware = request()->except('_token');
        sof_asignado::insert($datosSoftware);

        return redirect()->back()->with('rgcmessage', 'Software cargado con exito!...');
    }

    public function hardware()
    {
        //
        $datosHardware = request()->except('_token');
        har_asignado::insert($datosHardware);

        return redirect()->back()->with('rgcmessage', 'Hardware cargado con exito!...');
    }

    public function cv($id)
    {
        $sql="SELECT e.EMP_NOMBRES, e.EMP_CEDULA, e.EMP_EMAIL, pc.EQU_ID, pc.EQU_NOMBRE, pc.EQU_SERIAL, EAS_FECHA_ENTREGA
        FROM equ_asignados AS eq
        INNER JOIN empleados AS e ON e.EMP_ID = eq.EMP_ID
        INNER JOIN equipos AS pc ON pc.EQU_ID = eq.EQU_ID
        WHERE eq.EAS_ESTADO = 1 AND eq.EQU_ID =".$id;

        $sql2 = 'SELECT h.HAR_TIPO, h.HAR_DESCRIPCION, m.MAR_NOMBRE, h.HAR_MODELO, h.HAR_SERIAL, h.HAR_OBSERVACION
        FROM equipos AS e
        INNER JOIN har_asignados AS hs ON hs.EQU_ID = e.EQU_ID
        INNER JOIN hardwares AS h ON h.HAR_ID = hs.HAR_ID
        INNER JOIN marcas AS m ON m.MAR_ID = h.MAR_ID
        WHERE e.EQU_ID ='.$id;

        $sql3 = 'SELECT s.SOF_NOMBRE, s.SOF_VERSION
        FROM equipos AS e
        INNER JOIN sof_asignados AS so ON so.EQU_ID =  e.EQU_ID
        INNER JOIN softwares AS s ON s.SOF_ID = so.SOF_ID
        WHERE e.EQU_ID ='.$id;

        $sql4 = 'SELECT ms.created_at, ms.MAS_TIPO, m.MAN_PROVEEDOR, ms.MAS_ACTIVIDAD, m.MAN_FECHA, ms.MAS_ESTADO
        FROM equipos AS e
        INNER JOIN mantenimientos AS m ON m.EQU_ID = e.EQU_ID
        INNER JOIN man_asignados AS ms ON ms.MAN_ID = m.MAN_ID
        WHERE e.EQU_ID ='.$id;


        $resultado = DB::select($sql);
        $resultado2 = DB::select($sql2);
        $resultado3 = DB::select($sql3);
        $resultado4 = DB::select($sql4);

        $spreadsheet  = IOFactory::load('assets/template.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila = 6;
        $fila2 = 8;
        $fila3 = 9;
        $fila4 = 9;
        $fila5 = 10;
        $fila6 = 10;

        foreach ($resultado as $rows) {
            $sheet->setCellValue('E'.$fila, $rows->EQU_NOMBRE);
            $sheet->setCellValue('M'.$fila, $rows->EAS_FECHA_ENTREGA);
            $sheet->setCellValue('E'.$fila2, $rows->EMP_NOMBRES);
            $sheet->setCellValue('E'.$fila3, $rows->EMP_NOMBRES);
            $sheet->setCellValue('M'.$fila4, $rows->EMP_EMAIL);
            $sheet->setCellValue('E'.$fila6, $rows->EMP_EMAIL);
        }

        $fila7 = 14;

        foreach ($resultado2 as $rows2) {
            $sheet->setCellValue('D'.$fila7, $rows2->HAR_TIPO);
            $sheet->setCellValue('E'.$fila7, $rows2->HAR_DESCRIPCION);
            $sheet->setCellValue('F'.$fila7, $rows2->MAR_NOMBRE);
            $sheet->setCellValue('G'.$fila7, $rows2->HAR_MODELO);
            $sheet->setCellValue('J'.$fila7, $rows2->HAR_SERIAL);
            $sheet->setCellValue('M'.$fila7, $rows2->HAR_OBSERVACION);
            $fila7++;
        }

        $fila8 = 27;

        foreach ($resultado3 as $rows3) {
            $sheet->setCellValue('D'.$fila8, $rows3->SOF_NOMBRE);
            $sheet->setCellValue('J'.$fila8, $rows3->SOF_VERSION);
            $fila8++;
        }

        $fila11 = 45;

        foreach ($resultado4 as $rows4) {
            $sheet->setCellValue('D'.$fila11, $rows4->created_at);
            $sheet->setCellValue('E'.$fila11, $rows4->MAS_TIPO);
            $sheet->setCellValue('I'.$fila11, $rows4->MAN_PROVEEDOR);
            $sheet->setCellValue('O'.$fila11, $rows4->MAN_FECHA);
            $sheet->setCellValue('P'.$fila11, $rows4->MAS_ACTIVIDAD);
            $fila11++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');


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

    public function details($id)
    {
        //
        $sql="SELECT e.EMP_NOMBRES, e.EMP_CEDULA, e.EMP_EMAIL, pc.EQU_ID, pc.EQU_NOMBRE, pc.EQU_SERIAL, EAS_FECHA_ENTREGA
        FROM equ_asignados AS eq
        INNER JOIN empleados AS e ON e.EMP_ID = eq.EMP_ID
        INNER JOIN equipos AS pc ON pc.EQU_ID = eq.EQU_ID
        WHERE eq.EAS_ESTADO = 1 AND eq.EQU_ID =".$id;

        $sql1="SELECT EQU_ID, EQU_NOMBRE, EQU_SERIAL FROM `equipos` WHERE EQU_ID=".$id;

        $sql2="SELECT h.HAR_TIPO, h.HAR_DESCRIPCION, h.HAR_MODELO, h.HAR_SERIAL, has.HAS_ID, has.HAS_COMENTARIO
        FROM `har_asignados` AS has
        INNER JOIN hardwares AS h ON h.HAR_ID = has.HAR_ID
        WHERE HAS_ESTADO = 1 AND HAS_STATUS = 1 AND has.EQU_ID =".$id;

        $sql3="SELECT s.SOF_NOMBRE, s.SOF_VERSION
        FROM `sof_asignados` AS sas
        INNER JOIN softwares AS s ON s.SOF_ID = sas.SOF_ID
        WHERE SAS_ESTADO = 1 AND sas.EQU_ID =".$id;

        $sql4="SELECT mas.MAS_TIPO, mas.MAS_ACTIVIDAD, man.MAN_FECHA
        FROM `mantenimientos` AS man
        INNER JOIN man_asignados AS mas ON mas.MAN_ID = man.MAN_ID
        WHERE MAN_ESTADO = 1 AND EQU_ID =".$id;

        $sql5="SELECT SOF_NOMBRE, SOF_VERSION, SOF_ID FROM `softwares` WHERE SOF_ESTADO = 1";

        $sql6="SELECT HAR_ID, HAR_DESCRIPCION, HAR_SERIAL FROM `hardwares` WHERE HAR_ESTADO = 1";

        $sql7="SELECT h.HAR_TIPO, h.HAR_DESCRIPCION, h.HAR_MODELO, h.HAR_SERIAL, has.HAS_ID, has.HAS_COMENTARIO
        FROM `har_asignados` AS has
        INNER JOIN hardwares AS h ON h.HAR_ID = has.HAR_ID
        WHERE HAS_ESTADO = 1 AND HAS_STATUS = 2 AND has.EQU_ID =".$id;

        $sql8="SELECT evi.EVI_ID, evi.EAS_ID, evi.EVI_NOMBRE, evi.EVI_EVIDENCIA, equ.EQU_NOMBRE, emp.EMP_NOMBRES
        FROM `evi_asignados` AS evi
        INNER JOIN equ_asignados AS equ_a ON equ_a.EAS_ID = evi.EAS_ID
        INNER JOIN equipos AS equ ON equ.EQU_ID = equ_a.EQU_ID
        INNER JOIN empleados AS emp ON emp.EMP_ID = equ_a.EMP_ID
        WHERE equ.EQU_ID =".$id;

        $equ_asignados = DB::select($sql);
        $equipos = DB::select($sql1);
        $har_asignados = DB::select($sql2);
        $sof_asignados = DB::select($sql3);
        $man_asignados = DB::select($sql4);
        $softwares = DB::select($sql5);
        $hardwares = DB::select($sql6);
        $cambios = DB::select($sql7);
        $evidencias = DB::select($sql8);
        /* dd($sql2); */
        return view('Inventario.Equipo.details', compact('equ_asignados','equipos','har_asignados','sof_asignados','man_asignados','softwares','hardwares','cambios','evidencias'));
    }

    public function previsualizarPDF($id)
    {
    $documento = DB::table('evi_asignados')->where('EVI_ID', $id)->first();

    if (!$documento) {
        abort(404); // O manejar el error de alguna otra manera
    }

    // Devolver una respuesta con el PDF
    return response($documento->EVI_EVIDENCIA)->header('Content-Type', 'application/pdf');
    }

    public function change(Request $request, $id)
    {
        //
        $sql_change="UPDATE `har_asignados` SET HAS_STATUS = 2, HAS_COMENTARIO = '".$request->HAS_COMENTARIO."' WHERE HAS_ID =".$id;
        $change = DB::update($sql_change);
        Session::flash('msjupdate', '¡El cambio se a actualizado correctamente!...');
        return  redirect()->route('Equipo.index');
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
        $datosEquipo = request()->except(['_token','_method']);
        equipo::where('EQU_ID','=', $id)->update($datosEquipo);


        Session::flash('msjupdate', '¡El equipo se a actualizado correctamente!...');
        return redirect()->route('Equipo.index');
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
        equipo::where('EQU_ID', $id)->update(['EQU_ESTADO' => '0']);
        return  redirect()->route('Equipo.index')->with('msjdelete', 'Equipo borrado correctamente!...');
    }
}
