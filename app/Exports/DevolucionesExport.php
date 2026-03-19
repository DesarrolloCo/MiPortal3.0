<?php

namespace App\Exports;

use App\Models\Devolucion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DevolucionesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Devolucion::with(['asignacion.equipo', 'asignacion.empleado', 'recibidoPor'])
            ->where('DEV_ESTADO', 1)
            ->orderBy('DEV_FECHA_DEVOLUCION', 'desc')
            ->get();
    }

    /**
     * @var Devolucion $devolucion
     */
    public function map($devolucion): array
    {
        $equipo = $devolucion->asignacion ? $devolucion->asignacion->equipo : null;
        $empleado = $devolucion->asignacion ? $devolucion->asignacion->empleado : null;

        return [
            $devolucion->DEV_ID,
            $equipo ? $equipo->EQU_NOMBRE : 'N/A',
            $equipo ? $equipo->EQU_SERIAL : 'N/A',
            $empleado ? $empleado->EMP_NOMBRES : 'N/A',
            $devolucion->DEV_FECHA_DEVOLUCION ? $devolucion->DEV_FECHA_DEVOLUCION->format('d/m/Y') : 'N/A',
            $devolucion->DEV_ESTADO_EQUIPO ?? 'N/A',
            $devolucion->DEV_HARDWARE_COMPLETO ? 'Sí' : 'No',
            $devolucion->DEV_SOFTWARE_COMPLETO ? 'Sí' : 'No',
            $devolucion->recibidoPor ? $devolucion->recibidoPor->EMP_NOMBRES : 'N/A',
            $devolucion->DEV_OBSERVACIONES ?? '',
            $devolucion->DEV_DANOS_REPORTADOS ?? '',
            $devolucion->DEV_FALTANTES ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'ID Devolución',
            'Equipo',
            'Serial',
            'Empleado',
            'Fecha Devolución',
            'Estado Equipo',
            'Hardware Completo',
            'Software Completo',
            'Recibido Por',
            'Observaciones',
            'Daños Reportados',
            'Faltantes',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'ED7D31'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 30,
            'E' => 18,
            'F' => 15,
            'G' => 18,
            'H' => 18,
            'I' => 25,
            'J' => 35,
            'K' => 35,
            'L' => 35,
        ];
    }
}
