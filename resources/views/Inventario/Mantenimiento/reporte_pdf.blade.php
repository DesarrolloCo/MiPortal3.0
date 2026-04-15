<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Mantenimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #007bff;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .document-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 3px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .maintenance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .maintenance-table th {
            background-color: #e9ecef;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
            font-weight: bold;
        }

        .maintenance-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .maintenance-list {
            list-style: none;
            padding-left: 0;
        }

        .maintenance-list li {
            padding: 5px 0;
            padding-left: 20px;
            position: relative;
        }

        .maintenance-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
        }

        .activities-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 10px 0;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>REPORTE DE MANTENIMIENTO</h1>
        <p>Documento No. MAN-{{ str_pad($mantenimiento->MAN_ID, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Document Info -->
    <div class="document-info">
        <strong>Estado del Mantenimiento:</strong>
        @if($mantenimiento->MAN_STATUS == 1)
            <span class="status-badge status-pending">PENDIENTE</span>
        @elseif($mantenimiento->MAN_STATUS == 2)
            <span class="status-badge status-completed">COMPLETADO</span>
        @endif
    </div>

    <!-- Equipment Information -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EQUIPO</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Equipo:</div>
                <div class="info-value">{{ $mantenimiento->EQU_NOMBRE }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Serial:</div>
                <div class="info-value">{{ $mantenimiento->EQU_SERIAL }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo:</div>
                <div class="info-value">{{ $mantenimiento->EQU_TIPO }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Área:</div>
                <div class="info-value">{{ $mantenimiento->ARE_NOMBRE }}</div>
            </div>
        </div>
    </div>

    <!-- Maintenance Information -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL MANTENIMIENTO</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fecha Programada:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($mantenimiento->MAN_FECHA)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Proveedor:</div>
                <div class="info-value">{{ $mantenimiento->MAN_PROVEEDOR }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Técnico Responsable:</div>
                <div class="info-value">{{ $mantenimiento->TECNICO_NOMBRE }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cédula Técnico:</div>
                <div class="info-value">{{ $mantenimiento->TECNICO_CEDULA }}</div>
            </div>
        </div>
    </div>

    <!-- Activities Performed -->
    @if($man_asignados->count() > 0)
    <div class="section">
        <div class="section-title">ACTIVIDADES REALIZADAS</div>
        @foreach($man_asignados as $actividad)
        <table class="maintenance-table">
            <tr>
                <th style="width: 30%;">Tipo de Mantenimiento</th>
                <td>{{ $actividad->MAS_TIPO }}</td>
            </tr>
            <tr>
                <th>Descripción de Actividades</th>
                <td>
                    <div class="activities-box">{{ $actividad->MAS_ACTIVIDAD }}</div>
                </td>
            </tr>
        </table>
        @endforeach
    </div>
    @endif

    <!-- Maintenance Types Applied -->
    @if($tip_asignados->count() > 0)
    <div class="section">
        <div class="section-title">TIPOS DE MANTENIMIENTO APLICADOS</div>

        @php
            $fisicos = $tip_asignados->where('TIP_TIPO', 'Fisico');
            $logicos = $tip_asignados->where('TIP_TIPO', 'Logico');
        @endphp

        <table class="maintenance-table">
            @if($fisicos->count() > 0)
            <tr>
                <th style="width: 30%;">Mantenimiento Físico</th>
                <td>
                    <ul class="maintenance-list">
                        @foreach($fisicos as $fisico)
                        <li>{{ $fisico->TIP_NOMBRE }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif

            @if($logicos->count() > 0)
            <tr>
                <th style="width: 30%;">Mantenimiento Lógico</th>
                <td>
                    <ul class="maintenance-list">
                        @foreach($logicos as $logico)
                        <li>{{ $logico->TIP_NOMBRE }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Firma del Técnico<br>
                {{ $mantenimiento->TECNICO_NOMBRE }}<br>
                CC: {{ $mantenimiento->TECNICO_CEDULA }}
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Firma de Supervisión<br>
                Responsable de Área
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Este documento certifica las actividades de mantenimiento realizadas al equipo mencionado.</p>
        <p>Generado automáticamente por el Sistema de Gestión de Inventario - {{ config('app.name') }}</p>
    </div>
</body>
</html>
