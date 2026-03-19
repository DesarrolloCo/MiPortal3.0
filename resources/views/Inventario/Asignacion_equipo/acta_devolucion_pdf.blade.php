<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Devolución de Equipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #7f8c8d;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px;
            border-left: 4px solid #3498db;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 30%;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-bueno { background-color: #d4edda; color: #155724; }
        .status-regular { background-color: #fff3cd; color: #856404; }
        .status-malo { background-color: #f8d7da; color: #721c24; }
        .checkbox {
            font-size: 16px;
            color: #27ae60;
        }
        .checkbox-no {
            font-size: 16px;
            color: #e74c3c;
        }
        .observations {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            min-height: 50px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .signature {
            width: 45%;
            display: inline-block;
            text-align: center;
            margin-top: 60px;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .document-info {
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ACTA DE DEVOLUCIÓN DE EQUIPO</h1>
        <p>Documento No. DEV-{{ str_pad($devolucion->DEV_ID, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Fecha de Generación: {{ $fecha_generacion }}</p>
    </div>

    <!-- Información del Empleado -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EMPLEADO</div>
        <table class="info-table">
            <tr>
                <td>Nombre Completo:</td>
                <td>{{ $empleado['nombre'] }}</td>
            </tr>
            <tr>
                <td>Cédula:</td>
                <td>{{ $empleado['cedula'] }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $empleado['email'] }}</td>
            </tr>
            <tr>
                <td>Teléfono:</td>
                <td>{{ $empleado['telefono'] ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Información del Equipo -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EQUIPO</div>
        <table class="info-table">
            <tr>
                <td>Nombre del Equipo:</td>
                <td>{{ $equipo['nombre'] }}</td>
            </tr>
            <tr>
                <td>Marca:</td>
                <td>{{ $equipo['marca'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Modelo:</td>
                <td>{{ $equipo['modelo'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Serial:</td>
                <td><strong>{{ $equipo['serial'] }}</strong></td>
            </tr>
            <tr>
                <td>Descripción:</td>
                <td>{{ $equipo['descripcion'] ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Información de la Devolución -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA DEVOLUCIÓN</div>
        <table class="info-table">
            <tr>
                <td>Fecha de Entrega:</td>
                <td>{{ \Carbon\Carbon::parse($fecha_entrega)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Fecha de Devolución:</td>
                <td>{{ \Carbon\Carbon::parse($fecha_devolucion)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Estado del Equipo:</td>
                <td>
                    <span class="status-badge status-{{ strtolower($devolucion->DEV_ESTADO_EQUIPO) }}">
                        {{ $devolucion->DEV_ESTADO_EQUIPO }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Recibido Por:</td>
                <td>{{ $recibido_por }}</td>
            </tr>
            <tr>
                <td>Registrado Por:</td>
                <td>{{ $registrado_por }}</td>
            </tr>
        </table>
    </div>

    <!-- Verificaciones -->
    <div class="section">
        <div class="section-title">VERIFICACIÓN DE COMPONENTES</div>
        <table class="info-table">
            <tr>
                <td>Hardware Completo:</td>
                <td>
                    @if($devolucion->DEV_HARDWARE_COMPLETO)
                        <span class="checkbox">✓ SÍ</span>
                    @else
                        <span class="checkbox-no">✗ NO</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Software Completo:</td>
                <td>
                    @if($devolucion->DEV_SOFTWARE_COMPLETO)
                        <span class="checkbox">✓ SÍ</span>
                    @else
                        <span class="checkbox-no">✗ NO</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Observaciones -->
    @if($devolucion->DEV_OBSERVACIONES)
    <div class="section">
        <div class="section-title">OBSERVACIONES GENERALES</div>
        <div class="observations">
            {{ $devolucion->DEV_OBSERVACIONES }}
        </div>
    </div>
    @endif

    <!-- Daños Reportados -->
    @if($devolucion->DEV_DANOS_REPORTADOS)
    <div class="section">
        <div class="section-title">DAÑOS REPORTADOS</div>
        <div class="observations">
            {{ $devolucion->DEV_DANOS_REPORTADOS }}
        </div>
    </div>
    @endif

    <!-- Faltantes -->
    @if($devolucion->DEV_FALTANTES)
    <div class="section">
        <div class="section-title">COMPONENTES FALTANTES</div>
        <div class="observations">
            {{ $devolucion->DEV_FALTANTES }}
        </div>
    </div>
    @endif

    <!-- Firmas -->
    <div class="footer">
        <div class="signature" style="float: left;">
            <div class="signature-line">
                Firma del Empleado<br>
                <strong>{{ $empleado['nombre'] }}</strong><br>
                CC: {{ $empleado['cedula'] }}
            </div>
        </div>

        <div class="signature" style="float: right;">
            <div class="signature-line">
                Firma de quien Recibe<br>
                <strong>{{ $recibido_por }}</strong>
            </div>
        </div>

        <div style="clear: both;"></div>
    </div>

    <!-- Información del Documento -->
    <div class="document-info">
        <p>
            Este documento ha sido generado automáticamente por el sistema.<br>
            Documento ID: DEV-{{ str_pad($devolucion->DEV_ID, 6, '0', STR_PAD_LEFT) }} |
            Fecha: {{ $fecha_generacion }}
        </p>
    </div>
</body>
</html>
