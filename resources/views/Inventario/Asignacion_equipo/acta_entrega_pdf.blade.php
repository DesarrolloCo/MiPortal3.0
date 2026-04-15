<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Entrega de Equipo</title>
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
            border-left: 4px solid #28a745;
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
        .responsibilities {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ffc107;
            margin-bottom: 20px;
        }
        .responsibilities h5 {
            margin-top: 0;
            color: #856404;
        }
        .responsibilities ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .responsibilities li {
            margin-bottom: 5px;
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
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ACTA DE ENTREGA DE EQUIPO</h1>
        <p>Documento No. ENT-{{ str_pad($asignacion->EAS_ID, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Fecha de Generación: {{ $fecha_generacion }}</p>
    </div>

    <!-- Información del Empleado -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EMPLEADO RECEPTOR</div>
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
            @if(isset($empleado['cargo']))
            <tr>
                <td>Cargo:</td>
                <td>{{ $empleado['cargo'] }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Información del Equipo -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL EQUIPO ENTREGADO</div>
        <table class="info-table">
            <tr>
                <td>Nombre del Equipo:</td>
                <td>{{ $equipo['nombre'] }}</td>
            </tr>
            <tr>
                <td>Serial:</td>
                <td><strong>{{ $equipo['serial'] }}</strong></td>
            </tr>
            <tr>
                <td>Tipo:</td>
                <td>{{ $equipo['tipo'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Observaciones:</td>
                <td>{{ $equipo['observaciones'] ?? 'N/A' }}</td>
            </tr>
            @if(isset($equipo['area']))
            <tr>
                <td>Área:</td>
                <td>{{ $equipo['area'] }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Información de la Entrega -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA ENTREGA</div>
        <table class="info-table">
            <tr>
                <td>Fecha de Entrega:</td>
                <td>{{ \Carbon\Carbon::parse($fecha_entrega)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Entregado Por:</td>
                <td>{{ $entregado_por }}</td>
            </tr>
        </table>
    </div>

    <!-- Responsabilidades -->
    <div class="responsibilities">
        <h5><strong>RESPONSABILIDADES DEL EMPLEADO:</strong></h5>
        <ul>
            <li>Hacer uso adecuado del equipo asignado y mantenerlo en buenas condiciones.</li>
            <li>No realizar modificaciones no autorizadas al hardware o software del equipo.</li>
            <li>Informar inmediatamente cualquier daño, pérdida o mal funcionamiento del equipo.</li>
            <li>No instalar software no autorizado que pueda comprometer la seguridad.</li>
            <li>Mantener la confidencialidad de las credenciales de acceso.</li>
            <li>Devolver el equipo en las condiciones en que fue entregado al finalizar el contrato o cuando se solicite.</li>
            <li>El empleado será responsable de cualquier daño causado por negligencia o mal uso del equipo.</li>
        </ul>
    </div>

    <!-- Declaración -->
    <div class="alert-info">
        <p style="margin: 0;">
            Yo, <strong>{{ $empleado['nombre'] }}</strong>, identificado(a) con cédula <strong>{{ $empleado['cedula'] }}</strong>,
            declaro haber recibido el equipo descrito anteriormente en perfecto estado de funcionamiento y me comprometo
            a cumplir con las responsabilidades descritas en este documento.
        </p>
    </div>

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
                Firma de quien Entrega<br>
                <strong>{{ $entregado_por }}</strong><br>
                Área de Inventario/TI
            </div>
        </div>

        <div style="clear: both;"></div>
    </div>

    <!-- Información del Documento -->
    <div class="document-info">
        <p>
            Este documento ha sido generado automáticamente por el sistema.<br>
            Documento ID: ENT-{{ str_pad($asignacion->EAS_ID, 6, '0', STR_PAD_LEFT) }} |
            Fecha: {{ $fecha_generacion }}
        </p>
    </div>
</body>
</html>
