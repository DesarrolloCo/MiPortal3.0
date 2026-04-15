<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MantenimientosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $mantenimientos;

    public function __construct($mantenimientos)
    {
        $this->mantenimientos = $mantenimientos;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->mantenimientos;
    }

    /**
     * @param $mantenimiento
     */
    public function map($mantenimiento): array
    {
        $fechaMantenimiento = \Carbon\Carbon::parse($mantenimiento->MAN_FECHA);
        $hoy = \Carbon\Carbon::now();
        $diasRestantes = $hoy->diffInDays($fechaMantenimiento, false);

        // Determinar estado
        if ($mantenimiento->MAN_STATUS == 2) {
            $estado = 'Completado';
        } elseif ($diasRestantes < 0) {
            $estado = 'Vencido (' . abs($diasRestantes) . ' días)';
        } elseif ($diasRestantes >= 0 && $diasRestantes <= 7) {
            $estado = 'Próximo (' . $diasRestantes . ' días)';
        } else {
            $estado = 'Pendiente';
        }

        return [
            str_pad($mantenimiento->MAN_ID, 6, '0', STR_PAD_LEFT),
            $mantenimiento->EQU_NOMBRE,
            $mantenimiento->EQU_SERIAL,
            $mantenimiento->ARE_NOMBRE,
            $fechaMantenimiento->format('d/m/Y'),
            $mantenimiento->MAN_PROVEEDOR,
            $mantenimiento->TECNICO,
            $estado,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Mantenimiento',
            'Equipo',
            'Serial',
            'Área',
            'Fecha Programada',
            'Proveedor',
            'Técnico Responsable',
            'Estado',
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
                    'startColor' => ['rgb' => '28a745'],
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
            'A' => 18,  // ID Mantenimiento
            'B' => 30,  // Equipo
            'C' => 20,  // Serial
            'D' => 20,  // Área
            'E' => 18,  // Fecha Programada
            'F' => 25,  // Proveedor
            'G' => 30,  // Técnico Responsable
            'H' => 25,  // Estado
        ];
    }
}
