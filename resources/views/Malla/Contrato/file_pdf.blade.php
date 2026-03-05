<!doctype html>
<html lang="es">

<head>
    <title>{{ $Cedula }} - {{ $Nombre }}</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
    <header>
        <!-- place navbar here -->
        <style>
            .background::before {
                content: "";
                position: absolute;
                background-image: url('img/fondo_contacta.jpg');
                background-size: 100% 100%;
                background-repeat: no-repeat;
                top: -50;
                left: -50;
                width: 120%;
                height: 112%;
                opacity: .4;
                z-index: -1;
            }

            .marging {
                padding: 0px 10vw;
            }

            .font-size-title {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                position: relative;
                left: 5px;
                bottom: -150px;
            }

            .font-size-subtitle {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                position: relative;
                left: 5px;
                bottom: -180px;
            }

            .font-size-body {
                font-size: 16px;
                text-align: justify;
                position: relative;
                left: 5px;
                bottom: -200px;
            }

            .font-size-footer {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                position: relative;
                left: 5px;
                bottom: -400px;
            }

            .font-size-footer2 {
                font-size: 16px;
                font-weight: bold;
                position: relative;
                left: 5px;
                bottom: -500px;
            }

            .firma {
                position:absolute;
                height:100px;
                width:200px;
                top: -40px;
                left : 180px;
                z-index: -1;
            }

            .font-style-new-romans {
                font-family: "Times New Roman";
            }
        </style>
    </header>
    <main class="font-style-new-romans lh-sm">
        @php
            setlocale(LC_TIME, 'es_ES');
        @endphp
        <div class="row background" style="">

            <div class="container">
                <p class="font-size-title">LA SUSCRITA DIRECTORA DE TALENTO HUMANO DE TECNOLOGÍAS Y SERVICIOS CONTACTA S.A.S BIC NIT: 900.499.095-6</p>
                <p class="font-size-subtitle">C E R T I F I C A:</p>
                <p class="font-size-body">@if ($Sexo == 'F') Que la señora @else Que el señor @endif <strong>{{ $Nombre }}</strong> titular de la cedula de ciudadanía número <strong>{{ $Cedula }}</strong> expedida en <strong>{{ $Municipio }} ({{ $Departamento }})</strong>, @if ($Fecha_fin == null) labora @else laboró @endif en esta empresa, mediante un contrato {{ $Tipo_contrato }} desde la fecha <strong>{{ $Fecha_inicio }}</strong> @if ($Fecha_fin != null) hasta la fecha <strong>{{ $Fecha_fin }}</strong>@endif, desempeñando el cargo de <strong>{{ $Cargo }}</strong>. @if ($Val_salario == 1) Devenga un salario básico mensual por la suma de ({{ $Salario }}) @endif.   </p>
                <p class="font-size-body">Se expide el presente, a los (<?php echo strftime('%d'); ?>) dias del mes de <?php echo strftime('%B'); ?> del <?php echo strftime('%Y'); ?>, en la ciudad de Barranquilla, a solicitud del interesado.</p>
                <p class="font-size-footer">@if ($firma_foto[0]->PAR_VALOR == null)
                    <img src="'img/sin_firma.jpg'" width="200px" height="100px">
                @else
                    <img class="firma" src="{{ $firma_foto[0]->PAR_VALOR }}">
                @endif
                @if ($firma_texto[0]->PAR_VALOR == null)
                    <p class="font-size-footer">Director General</p>
                @else
                    <p class="font-size-footer">{{ $firma_texto[0]->PAR_VALOR }}</p>
                @endif</p>
                <p class="font-size-footer2">{{ Auth::user()->name }}</p>
            </div>

        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
</body>

</html>
