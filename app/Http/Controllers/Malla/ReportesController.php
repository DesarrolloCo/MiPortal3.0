<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cliente;
use App\Models\campana;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportesController extends Controller
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
        return view('Malla.Reporte.index');
    }

    public function operational()
    {
        //
        return view('Malla.Reporte.operativo');
    }

    public function financiero(){
        return view('Malla.Reporte.financiero');
    }

    public function fecha(){
        return view('Malla.Reporte.fecha');
    }

    public function campana(){

        $clientes = cliente::where('CLI_ESTADO', '=', '1')->get();

        return view('Malla.Reporte.campana', compact('clientes'));
    }


    public function details(request $request){

        $fecha = date('Y-m-d');
        $fecha_inicial = $request->FECHA_INICIAL;
        $fecha_final = $request->FECHA_FINAL;

        $sql = 'SELECT emp.EMP_CEDULA, emp.EMP_NOMBRES,DATE(mal.MAL_INICIO) AS fecha, DATE_FORMAT(mal.MAL_INICIO, "%H:%i:%S" ) AS hora_inicio,  DATE_FORMAT(mal.MAL_FINAL, "%H:%i:%S" ) AS hora_fin, uni.UNI_NOMBRE, uni.UNI_CODE, cli.CLI_NOMBRE, cli.CLI_CODE,
        cam.CAM_NOMBRE, cam.CAM_CODE
        FROM mallas AS mal
        INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        INNER JOIN clientes AS cli on cli.CLI_ID = cli.CLI_ID
        INNER JOIN unidad_negocios AS uni on uni.UNI_ID = uni.UNI_ID
        INNER JOIN uni_clis as uns on uns.UNC_ID = uns.UNC_ID
        WHERE mal.MAL_ESTADO = 1
        AND mal.created_at BETWEEN "' . $fecha_inicial . '" AND "' . $fecha_final . '"';

        $detalles = DB::select($sql);
        if (count($detalles) == 0) {
            Session::flash('reportewar', '¡No hay reportes registrados En esa cantidad de dias!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_visita.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila = 6;

        foreach ($detalles as $rows) {
            $sheet->setCellValue('A'.$fila, $rows->EMP_CEDULA);
            $sheet->setCellValue('B'.$fila, $rows->EMP_NOMBRES);
            $sheet->setCellValue('C'.$fila, $rows->fecha);
            $sheet->setCellValue('D'.$fila, $rows->hora_inicio);
            $sheet->setCellValue('E'.$fila, $rows->hora_fin);
            $sheet->setCellValue('F'.$fila, $rows->UNI_NOMBRE);
            $sheet->setCellValue('G'.$fila, $rows->UNI_CODE);
            $sheet->setCellValue('H'.$fila, $rows->CLI_NOMBRE);
            $sheet->setCellValue('I'.$fila, $rows->CLI_CODE);
            $sheet->setCellValue('J'.$fila, $rows->CAM_NOMBRE);
            $sheet->setCellValue('K'.$fila, $rows->CAM_CODE);
            $fila++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_Detallado.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    public function diary(){

        $fecha = date('Y-m-d');

        $sql = 'SELECT emp.EMP_CEDULA, emp.EMP_NOMBRES, cam.CAM_NOMBRE, COUNT(mal.MAL_ID) AS horas
        FROM mallas AS mal
        INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        WHERE mal.MAL_DIA = "'.$fecha.'" AND mal.MAL_ESTADO = 1
        GROUP BY cam.CAM_NOMBRE, emp.EMP_NOMBRES, emp.EMP_CEDULA';

        $reporte = DB::select($sql);

        if(count($reporte) == 0){
            Session::flash('reportewar', '¡No hay reportes registrados el dia de hoy!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_diary.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila_dos = 6;

        foreach ($reporte as $rows_dos) {
            $sheet->setCellValue('A'.$fila_dos, $rows_dos->EMP_CEDULA);
            $sheet->setCellValue('B'.$fila_dos, $rows_dos->EMP_NOMBRES);
            $sheet->setCellValue('C'.$fila_dos, $rows_dos->CAM_NOMBRE);
            $sheet->setCellValue('D'.$fila_dos, $rows_dos->horas);
            $fila_dos++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_General.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function financial(request $request){

        $fecha = date('Y-m-d');
        $fecha_inicial = $request->FECHA_INICIAL;
        $fecha_final = $request->FECHA_FINAL;

        $sql = 'SELECT emp.EMP_ID, emp.EMP_CEDULA, emp.EMP_NOMBRES, cli.CLI_NOMBRE, cli.CLI_CODE, uni.UNI_NOMBRE, uni.UNI_CODE, cam.CAM_NOMBRE, cam.CAM_CODE, COUNT(mal.MAL_ID) AS horas, emp.EMP_SUELDO
        FROM mallas AS mal
        INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        INNER JOIN uni_clis AS unc ON unc.UNC_ID = cam.UNC_ID
        INNER JOIN clientes AS cli on cli.CLI_ID = unc.CLI_ID
        INNER JOIN unidad_negocios AS uni on uni.UNI_ID = unc.UNI_ID
        WHERE mal.MAL_ESTADO = 1 AND mal.created_at BETWEEN "'.$fecha_inicial.'" AND "'.$fecha_final .'"
        GROUP BY emp.EMP_ID, emp.EMP_CEDULA, emp.EMP_NOMBRES, cli.CLI_NOMBRE, cli.CLI_CODE, uni.UNI_NOMBRE, uni.UNI_CODE, cam.CAM_NOMBRE, cam.CAM_CODE, emp.EMP_SUELDO';

        $reporte2 = DB::select($sql);
        if (count($reporte2) == 0) {
            Session::flash('reportewar', '¡No hay reportes registrados En esa cantidad de dias!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_financial.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila_tres = 6;

        foreach ($reporte2 as $rows_tres) {

            $sql2 = "SELECT
            COUNT(mal.MAL_ID) AS horas
            FROM mallas AS mal
            INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
            WHERE mal.MAL_ESTADO = 1
            AND emp.EMP_ID = ".$rows_tres->EMP_ID;

            $horas = DB::select($sql2);


            $horas_totales = $horas[0]->horas;
            $sueldo = $rows_tres->EMP_SUELDO;

            $precio_hora = $sueldo/$horas_totales;
            /* dd($precio_hora); */

            $sheet->setCellValue('A'.$fila_tres, $rows_tres->EMP_CEDULA);
            $sheet->setCellValue('B'.$fila_tres, $rows_tres->EMP_NOMBRES);
            $sheet->setCellValue('C'.$fila_tres, $rows_tres->UNI_NOMBRE);
            $sheet->setCellValue('D'.$fila_tres, $rows_tres->UNI_CODE);
            $sheet->setCellValue('E'.$fila_tres, $rows_tres->CLI_NOMBRE);
            $sheet->setCellValue('F'.$fila_tres, $rows_tres->CLI_CODE);
            $sheet->setCellValue('G'.$fila_tres, $rows_tres->horas);
            $sheet->setCellValue('H'.$fila_tres, $rows_tres->EMP_SUELDO);
            $sheet->setCellValue('I'.$fila_tres, Intval($rows_tres->horas)*$precio_hora);
            $fila_tres++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_CentrodeCosto.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function campaign(request $request){;

        $CAM_ID = $request->CAM_ID;
        $fecha_inicial = $request->FECHA_INICIAL;
        $fecha_final = $request->FECHA_FINAL;

        $sql = 'SELECT emp.EMP_CEDULA, emp.EMP_ID, emp.EMP_NOMBRES, cam.CAM_NOMBRE, mal.MAL_DIA, mal.CAM_ID, mal.EMP_ID,
        (SELECT mal2.MAL_INICIO FROM mallas AS mal2 WHERE mal.EMP_ID = mal2.EMP_ID ORDER BY mal2.MAL_ID ASC LIMIT 1) AS HORA_INICIAL,
        (SELECT mal2.MAL_FINAL FROM mallas AS mal2 WHERE mal.EMP_ID = mal2.EMP_ID ORDER BY mal2.MAL_ID DESC LIMIT 1) AS HORA_FINAL,
        COUNT(mal.MAL_ID) AS horas
        FROM mallas AS mal
        INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        WHERE mal.MAL_ESTADO = 1
        AND mal.created_at >= "'.$fecha_inicial.'"
        AND mal.created_at <= "'.$fecha_final.' 23:59:00"
        AND mal.CAM_ID = '.$CAM_ID.'
        GROUP BY mal.CAM_ID, cam.CAM_NOMBRE, emp.EMP_ID, emp.EMP_NOMBRES, emp.EMP_CEDULA, mal.MAL_DIA, mal.EMP_ID
        ORDER BY  mal.MAL_DIA, emp.EMP_NOMBRES ASC';

        $reporte = DB::select($sql);

        $campana = campana::where('CAM_ID', $CAM_ID)->get();

        if(count($reporte) == 0){
            Session::flash('warmessage', '¡La campana '.$campana[0]->CAM_NOMBRE.' no tuvo movimientos entre las fechas '.$fecha_inicial.' y '.$fecha_final.'!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_campaign.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila_cuatro = 6;

        foreach ($reporte as $rows_cuatro) {

            $sheet->setCellValue('A'.$fila_cuatro, $rows_cuatro->EMP_CEDULA);
            $sheet->setCellValue('B'.$fila_cuatro, $rows_cuatro->EMP_NOMBRES);
            $sheet->setCellValue('C'.$fila_cuatro, $rows_cuatro->CAM_NOMBRE);
            $sheet->setCellValue('D'.$fila_cuatro, $rows_cuatro->MAL_DIA);
            $sheet->setCellValue('E'.$fila_cuatro, $rows_cuatro->HORA_INICIAL);
            $sheet->setCellValue('F'.$fila_cuatro, $rows_cuatro->HORA_FINAL);
            $sheet->setCellValue('G'.$fila_cuatro, $rows_cuatro->horas);
            $fila_cuatro++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_campaña.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    public function date(request $request){

        $fecha = $request->FECHA;

        $sql = 'SELECT emp.EMP_CEDULA, emp.EMP_NOMBRES, cam.CAM_NOMBRE, COUNT(mal.MAL_ID) AS horas
        FROM mallas AS mal
        INNER JOIN empleados AS emp ON emp.EMP_ID = mal.EMP_ID
        INNER JOIN campanas AS cam ON cam.CAM_ID = mal.CAM_ID
        WHERE mal.MAL_DIA = "'.$fecha.'" AND mal.MAL_ESTADO = 1
        GROUP BY cam.CAM_NOMBRE, emp.EMP_NOMBRES, emp.EMP_CEDULA';

        $reporte = DB::select($sql);

        if(count($reporte) == 0){
            Session::flash('reportewar', '¡No hay reportes registrados en la fecha '.$fecha.'!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_date.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("HOJA DE VIDA");

        $fila_cinco = 6;

        foreach ($reporte as $rows_cinco) {

            $sheet->setCellValue('A'.$fila_cinco, $rows_cinco->EMP_CEDULA);
            $sheet->setCellValue('B'.$fila_cinco, $rows_cinco->EMP_NOMBRES);
            $sheet->setCellValue('C'.$fila_cinco, $rows_cinco->CAM_NOMBRE);
            $sheet->setCellValue('D'.$fila_cinco, $rows_cinco->horas);
            $fila_cinco++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_general.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
}
