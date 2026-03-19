@extends('layouts.main')

@section('main')
<!-- Breadcrumb -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Historial del Equipo</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Equipo.index') }}">Equipos</a></li>
            <li class="breadcrumb-item active">Historial</li>
        </ol>
    </div>
    <div class="col-md-4 col-4 align-self-center text-right">
        <a href="{{ route('Equipo.details', $equipo->EQU_ID) }}" class="btn btn-info btn-sm">
            <i class="mdi mdi-eye"></i> Ver Detalles
        </a>
        <a href="{{ route('Equipo.index') }}" class="btn btn-secondary btn-sm">
            <i class="mdi mdi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<!-- Información del Equipo -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="card-title mb-3">{{ $equipo->EQU_NOMBRE }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Serial:</strong> {{ $equipo->EQU_SERIAL ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Marca:</strong> {{ $equipo->EQU_MARCA ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Modelo:</strong> {{ $equipo->EQU_MODELO ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tipo:</strong> {{ $equipo->EQU_TIPO ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Precio:</strong> ${{ number_format($equipo->EQU_PRECIO ?? 0, 2) }}</p>
                                <p class="mb-2">
                                    <strong>Estado Actual:</strong>
                                    @if($equipo->asignacionActiva)
                                        <span class="badge badge-success">Asignado a {{ $equipo->asignacionActiva->empleado->EMP_NOMBRES ?? 'N/A' }}</span>
                                    @else
                                        <span class="badge badge-warning">Disponible</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stats">
                            <div class="mb-3">
                                <h3 class="text-primary">{{ $asignaciones->count() }}</h3>
                                <small>Asignaciones</small>
                            </div>
                            <div class="mb-3">
                                <h3 class="text-info">{{ $mantenimientos->count() }}</h3>
                                <small>Mantenimientos</small>
                            </div>
                            <div>
                                <h3 class="text-secondary">{{ $evidencias->count() }}</h3>
                                <small>Evidencias</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline de Historial -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Timeline de Actividad</h4>
                <p class="card-subtitle mb-4">Historial completo de operaciones realizadas con este equipo</p>

                @if($timeline->isEmpty())
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        No hay registros en el historial de este equipo.
                    </div>
                @else
                    <div class="timeline">
                        @foreach($timeline as $index => $evento)
                            <div class="timeline-item">
                                <div class="timeline-badge bg-{{ $evento['color'] }}">
                                    <i class="mdi {{ $evento['icono'] }}"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h5 class="timeline-title">{{ $evento['titulo'] }}</h5>
                                        <p class="timeline-date">
                                            <small class="text-muted">
                                                <i class="mdi mdi-clock"></i>
                                                {{ $evento['fecha']->format('d/m/Y H:i') }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="timeline-body">
                                        <p class="mb-1">{{ $evento['descripcion'] }}</p>
                                        @if($evento['estado'])
                                            <span class="badge badge-{{ $evento['color'] }}">{{ $evento['estado'] }}</span>
                                        @endif

                                        @if($evento['tipo'] === 'devolucion' && isset($evento['data']))
                                            <div class="mt-2">
                                                @if($evento['data']->DEV_HARDWARE_COMPLETO)
                                                    <span class="badge badge-success"><i class="mdi mdi-check"></i> Hardware Completo</span>
                                                @else
                                                    <span class="badge badge-danger"><i class="mdi mdi-close"></i> Hardware Incompleto</span>
                                                @endif

                                                @if($evento['data']->DEV_SOFTWARE_COMPLETO)
                                                    <span class="badge badge-success"><i class="mdi mdi-check"></i> Software Completo</span>
                                                @else
                                                    <span class="badge badge-danger"><i class="mdi mdi-close"></i> Software Incompleto</span>
                                                @endif

                                                @if($evento['data']->DEV_FALTANTES)
                                                    <p class="mt-2 mb-0"><strong>Faltantes:</strong> {{ $evento['data']->DEV_FALTANTES }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        @if($evento['tipo'] === 'mantenimiento' && isset($evento['data']))
                                            <div class="mt-2">
                                                @if($evento['data']->MAN_PROVEEDOR)
                                                    <p class="mb-1"><strong>Proveedor:</strong> {{ $evento['data']->MAN_PROVEEDOR }}</p>
                                                @endif
                                                @if($evento['data']->MAN_COSTO)
                                                    <p class="mb-1"><strong>Costo:</strong> ${{ number_format($evento['data']->MAN_COSTO, 2) }}</p>
                                                @endif
                                                @if($evento['data']->MAN_FECHA_REALIZACION)
                                                    <p class="mb-0"><strong>Realizado:</strong> {{ $evento['data']->MAN_FECHA_REALIZACION->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 20px;
    height: 100%;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 30px;
}

.timeline-badge {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    z-index: 1;
}

.timeline-panel {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
}

.timeline-date {
    margin: 0;
}

.timeline-body {
    margin-top: 10px;
}

.stats h3 {
    margin: 0;
    line-height: 1.2;
}

.stats small {
    display: block;
    color: #6c757d;
}
</style>
@endsection
