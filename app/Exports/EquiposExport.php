<?php

namespace App\Exports;

use App\Models\equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class EquiposExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return equipo::with(['asignacionActiva.empleado'])
            ->where('EQU_ESTADO', 1)
            ->orderBy('EQU_NOMBRE')
            ->get();
    }

    /**
     * @var equipo $equipo
     */
    public function map($equipo): array
    {
        $asignacion = $equipo->asignacionActiva;

        return [
            $equipo->EQU_ID,
            $equipo->EQU_NOMBRE,
            $equipo->EQU_MARCA ?? 'N/A',
            $equipo->EQU_MODELO ?? 'N/A',
            $equipo->EQU_SERIAL ?? 'N/A',
            $equipo->EQU_TIPO ?? 'N/A',
            $equipo->EQU_DESCRIPCION ?? 'N/A',
            $equipo->EQU_PRECIO ? '$' . number_format($equipo->EQU_PRECIO, 2) : 'N/A',
            $asignacion ? 'Asignado' : 'Disponible',
            $asignacion && $asignacion->empleado ? $asignacion->empleado->EMP_NOMBRES : 'N/A',
            $equipo->created_at ? $equipo->created_at->format('d/m/Y') : 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Marca',
            'Modelo',
            'Serial',
            'Tipo',
            'Descripción',
            'Precio',
            'Estado',
            'Asignado a',
            'Fecha Registro',
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
                    'startColor' => ['rgb' => '4472C4'],
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
            'A' => 8,
            'B' => 25,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 12,
            'G' => 35,
            'H' => 15,
            'I' => 12,
            'J' => 30,
            'K' => 15,
        ];
    }
}
