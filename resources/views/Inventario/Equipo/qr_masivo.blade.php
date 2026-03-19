<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Códigos QR - Inventario de Equipos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .qr-grid {
            display: table;
            width: 100%;
        }
        .qr-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            text-align: center;
            vertical-align: top;
            page-break-inside: avoid;
        }
        .qr-box {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #fafafa;
            min-height: 300px;
        }
        .qr-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        .qr-info {
            font-size: 11px;
            color: #666;
            margin: 8px 0;
            text-align: left;
        }
        .qr-info strong {
            color: #333;
        }
        .qr-code {
            margin: 15px 0;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            display: inline-block;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .page-break {
            page-break-after: always;
        }
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Códigos QR - Inventario de Equipos</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="qr-grid">
        @foreach($equiposConQR->chunk(2) as $chunkIndex => $chunk)
            @foreach($chunk as $index => $item)
                <div class="qr-item">
                    <div class="qr-box">
                        <h3>{{ $item['equipo']->EQU_NOMBRE }}</h3>

                        <div class="qr-info">
                            <strong>Serial:</strong> {{ $item['equipo']->EQU_SERIAL ?? 'N/A' }}
                        </div>
                        <div class="qr-info">
                            <strong>Marca:</strong> {{ $item['equipo']->EQU_MARCA ?? 'N/A' }}
                        </div>
                        <div class="qr-info">
                            <strong>Modelo:</strong> {{ $item['equipo']->EQU_MODELO ?? 'N/A' }}
                        </div>

                        <div class="qr-code">
                            {!! $item['qr'] !!}
                        </div>

                        <div class="qr-info" style="margin-top: 10px; text-align: center; font-size: 9px;">
                            ID: {{ $item['equipo']->EQU_ID }}
                        </div>
                    </div>
                </div>

                @if($index == 1 && !$loop->parent->last)
                    </div>
                    <div class="page-break"></div>
                    <div class="qr-grid">
                @endif
            @endforeach

            @if($chunk->count() == 1 && !$loop->last)
                <div class="qr-item"></div>
                </div>
                <div class="page-break"></div>
                <div class="qr-grid">
            @endif
        @endforeach
    </div>

    <div class="footer">
        Sistema de Inventario - {{ config('app.name') }} - Página <span class="pageNumber"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
