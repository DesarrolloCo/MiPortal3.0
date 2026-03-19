<?php

namespace App\Exports;

use App\Models\equ_asignado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AsignacionesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filtrarActivas;

    public function __construct($filtrarActivas = true)
    {
        $this->filtrarActivas = $filtrarActivas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = equ_asignado::with(['equipo', 'empleado'])
            ->orderBy('EAS_FECHA_ENTREGA', 'desc');

        if ($this->filtrarActivas) {
            $query->where('EAS_ESTADO', 1); // Solo asignaciones activas
        }

        return $query->get();
    }

    /**
     * @var equ_asignado $asignacion
     */
    public function map($asignacion): array
    {
        $estadoTexto = $asignacion->EAS_ESTADO == 1 ? 'Activa' : 'Devuelta';

        return [
            $asignacion->EAS_ID,
            $asignacion->equipo ? $asignacion->equipo->EQU_NOMBRE : 'N/A',
            $asignacion->equipo ? $asignacion->equipo->EQU_SERIAL : 'N/A',
            $asignacion->empleado ? $asignacion->empleado->EMP_NOMBRES : 'N/A',
            $asignacion->empleado ? $asignacion->empleado->EMP_CEDULA : 'N/A',
            $asignacion->EAS_FECHA_ENTREGA ? $asignacion->EAS_FECHA_ENTREGA->format('d/m/Y') : 'N/A',
            $estadoTexto,
            $asignacion->created_at ? $asignacion->created_at->format('d/m/Y H:i') : 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'ID Asignación',
            'Equipo',
            'Serial',
            'Empleado',
            'Cédula',
            'Fecha Entrega',
            'Estado',
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
                    'startColor' => ['rgb' => '70AD47'],
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
            'E' => 15,
            'F' => 15,
            'G' => 12,
            'H' => 18,
        ];
    }
}
