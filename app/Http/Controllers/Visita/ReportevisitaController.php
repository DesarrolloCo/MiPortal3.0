<?php

namespace App\Http\Controllers\Visita;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cliente;
use App\Models\campana;


class ReportevisitaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportes(request $request){

        $fecha = date('Y-m-d');
        $fecha_inicial = $request->FECHA_INICIAL;
        $fecha_final = $request->FECHA_FINAL;

        $sql = 'SELECT * FROM registros WHERE created_at BETWEEN "' . $fecha_inicial . '" AND "' . $fecha_final . '"';

        $detalles = DB::select($sql);
        if (count($detalles) == 0) {
            Session::flash('reportewar', '¡No hay reportes registrados En esa cantidad de dias!...');
            return redirect()->back();
        }

        $spreadsheet  = IOFactory::load('assets/template_visita.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("REGISTRO DE REPORTES");

        $fila = 5;

        foreach ($detalles as $rows) {
            $sheet->setCellValue('A'.$fila, $rows->REG_ID);
            $sheet->setCellValue('B'.$fila, $rows->created_at);
            $sheet->setCellValue('C'.$fila, $rows->REG_NOMBRE);
            $sheet->setCellValue('D'.$fila, $rows->REG_TIPO_ID);
            $sheet->setCellValue('E'.$fila, $rows->REG_CEDULA);
            $sheet->setCellValue('F'.$fila, $rows->REG_EMPRESA);
            $sheet->setCellValue('G'.$fila, $rows->REG_MOTIVO_INGRESO);
            $sheet->setCellValue('H'.$fila, $rows->REG_EQUIPO);
            $sheet->setCellValue('I'.$fila, $rows->REG_SERIAL);
            $sheet->setCellValue('J'.$fila, $rows->created_at);
            $sheet->setCellValue('K'.$fila, $rows->REG_FECHA_HORA_SALIDA);
            $fila++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="REPORTE_VISITA.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }
}
