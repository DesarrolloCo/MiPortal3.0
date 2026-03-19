@extends('layouts.main')

@section('main')
<!-- Breadcrumb -->
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Código QR - {{ $equipo->EQU_NOMBRE }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Equipo.index') }}">Equipos</a></li>
            <li class="breadcrumb-item active">Código QR</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center text-right">
        <a href="{{ route('Equipo.qr.descargar', $equipo->EQU_ID) }}" class="btn btn-success">
            <i class="mdi mdi-download"></i> Descargar QR
        </a>
        <a href="{{ route('Equipo.details', $equipo->EQU_ID) }}" class="btn btn-info">
            <i class="mdi mdi-eye"></i> Ver Detalles
        </a>
    </div>
</div>

<!-- Contenido -->
<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">Código QR del Equipo</h4>
                <p class="card-subtitle mb-4">Escanea este código para acceder rápidamente a la información del equipo</p>

                <!-- Información del Equipo -->
                <div class="mb-4">
                    <h5 class="mb-3">{{ $equipo->EQU_NOMBRE }}</h5>
                    <p class="mb-1"><strong>Serial:</strong> {{ $equipo->EQU_SERIAL ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Marca:</strong> {{ $equipo->EQU_MARCA ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Modelo:</strong> {{ $equipo->EQU_MODELO ?? 'N/A' }}</p>
                </div>

                <!-- Código QR -->
                <div class="qr-container mb-4" style="display: inline-block; padding: 20px; background: white; border: 2px solid #ddd; border-radius: 8px;">
                    {!! $qrCode !!}
                </div>

                <!-- Instrucciones -->
                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Instrucciones:</strong><br>
                    Escanea este código QR con tu dispositivo móvil para acceder a la ficha técnica completa del equipo.
                </div>

                <!-- Botones de Acción -->
                <div class="btn-group mt-3" role="group">
                    <a href="{{ route('Equipo.qr.descargar', $equipo->EQU_ID) }}" class="btn btn-success">
                        <i class="mdi mdi-download"></i> Descargar SVG
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="mdi mdi-printer"></i> Imprimir
                    </button>
                    <a href="{{ route('Equipo.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .page-titles, .btn-group, .alert, .card-subtitle {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
