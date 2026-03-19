@extends('layouts.main')

@section('main')
<!-- Breadcrumb -->
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Escáner de Códigos QR</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Equipo.index') }}">Equipos</a></li>
            <li class="breadcrumb-item active">Escáner QR</li>
        </ol>
    </div>
</div>

<!-- Contenido -->
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Escanear Código QR</h4>
                <p class="card-subtitle mb-4">Usa la cámara de tu dispositivo para escanear el código QR de un equipo</p>

                <!-- Selector de Cámara -->
                <div class="form-group">
                    <label for="cameraSelect">Seleccionar Cámara:</label>
                    <select id="cameraSelect" class="form-control"></select>
                </div>

                <!-- Video Preview -->
                <div class="text-center mb-4">
                    <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                </div>

                <!-- Resultado -->
                <div id="result" class="alert alert-info" style="display: none;">
                    <h5>Equipo Escaneado:</h5>
                    <p id="resultText"></p>
                    <button id="redirectBtn" class="btn btn-primary mt-2" style="display: none;">Ver Detalles del Equipo</button>
                </div>

                <!-- Instrucciones -->
                <div class="alert alert-light">
                    <strong><i class="mdi mdi-information"></i> Instrucciones:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Permite el acceso a la cámara cuando el navegador lo solicite</li>
                        <li>Apunta la cámara hacia el código QR del equipo</li>
                        <li>El sistema detectará automáticamente el código y te redirigirá</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir librería html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let currentCameraId = null;

    // Inicializar el escáner cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        initializeScanner();
    });

    function initializeScanner() {
        html5QrCode = new Html5Qrcode("reader");

        // Obtener lista de cámaras
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                const cameraSelect = document.getElementById('cameraSelect');
                cameraSelect.innerHTML = '';

                cameras.forEach((camera, index) => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.text = camera.label || `Cámara ${index + 1}`;
                    cameraSelect.appendChild(option);
                });

                // Seleccionar cámara trasera por defecto (si existe)
                const backCamera = cameras.find(camera =>
                    camera.label.toLowerCase().includes('back') ||
                    camera.label.toLowerCase().includes('rear') ||
                    camera.label.toLowerCase().includes('trasera')
                );

                currentCameraId = backCamera ? backCamera.id : cameras[0].id;
                cameraSelect.value = currentCameraId;

                // Iniciar escáner con cámara seleccionada
                startScanning(currentCameraId);

                // Cambiar cámara cuando se selecciona otra
                cameraSelect.addEventListener('change', function() {
                    stopScanning().then(() => {
                        startScanning(this.value);
                    });
                });
            } else {
                alert('No se encontraron cámaras en este dispositivo.');
            }
        }).catch(err => {
            console.error('Error al obtener cámaras:', err);
            alert('Error al acceder a las cámaras. Por favor, verifica los permisos del navegador.');
        });
    }

    function startScanning(cameraId) {
        currentCameraId = cameraId;

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            cameraId,
            config,
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error('Error al iniciar escáner:', err);
        });
    }

    function stopScanning() {
        if (html5QrCode && html5QrCode.isScanning) {
            return html5QrCode.stop();
        }
        return Promise.resolve();
    }

    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Código escaneado: ${decodedText}`);

        // Detener el escáner
        stopScanning();

        // Mostrar resultado
        document.getElementById('result').style.display = 'block';
        document.getElementById('resultText').textContent = 'Redirigiendo...';

        // Redirigir a la URL escaneada
        if (decodedText.startsWith('http')) {
            // Si es una URL completa, redirigir directamente
            window.location.href = decodedText;
        } else {
            // Si es solo un ID, construir la URL
            document.getElementById('resultText').textContent = `Equipo ID: ${decodedText}`;
            const detailsUrl = '{{ route("Equipo.details", ":id") }}'.replace(':id', decodedText);
            document.getElementById('redirectBtn').style.display = 'inline-block';
            document.getElementById('redirectBtn').onclick = function() {
                window.location.href = detailsUrl;
            };
        }
    }

    function onScanError(errorMessage) {
        // Ignorar errores de escaneo (son muy frecuentes mientras busca el código)
        // console.warn(`Error de escaneo: ${errorMessage}`);
    }

    // Detener escáner cuando se cierra la página
    window.addEventListener('beforeunload', function() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    });
</script>

<style>
    #reader {
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    #reader video {
        width: 100% !important;
        height: auto !important;
    }
</style>
@endsection
