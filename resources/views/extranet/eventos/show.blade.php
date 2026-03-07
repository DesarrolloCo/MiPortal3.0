@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.eventos.index') }}">Eventos</a></li>
                    <li class="breadcrumb-item active">{{ $evento->titulo }}</li>
                </ol>
            </nav>

            <!-- Mensajes de retroalimentación -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle"></i>
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <!-- Evento -->
            <div class="card" style="border-left: 5px solid {{ $evento->color }};">
                @if($evento->imagen_url)
                <img class="card-img-top" src="{{ $evento->imagen_url }}" alt="{{ $evento->titulo }}" style="max-height: 400px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-3">
                        <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $evento->tipo)) }}</span>
                        <span class="badge badge-{{ $evento->modalidad == 'presencial' ? 'success' : ($evento->modalidad == 'virtual' ? 'info' : 'warning') }}">
                            {{ ucfirst($evento->modalidad) }}
                        </span>
                        <span class="badge badge-{{ $evento->estado == 'publicado' ? 'success' : 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $evento->estado)) }}
                        </span>
                    </div>

                    <!-- Título -->
                    <h2 class="card-title">{{ $evento->titulo }}</h2>

                    <!-- Metadata -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="mdi mdi-calendar text-primary"></i>
                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                            </p>
                            <p class="mb-2">
                                <i class="mdi mdi-clock text-primary"></i>
                                <strong>Hora:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}
                                @if($evento->hora_fin)
                                - {{ $evento->hora_fin }}
                                @endif
                            </p>
                            @if($evento->modalidad === 'presencial' || $evento->modalidad === 'hibrido')
                            <p class="mb-2">
                                <i class="mdi mdi-map-marker text-primary"></i>
                                <strong>Lugar:</strong> {{ $evento->lugar }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="mdi mdi-account text-primary"></i>
                                <strong>Organiza:</strong> {{ $evento->organizador->EMP_NOMBRES ?? 'Sin organizador' }}
                            </p>
                            @if($evento->cupo_maximo)
                            <p class="mb-2">
                                <i class="mdi mdi-account-group text-primary"></i>
                                <strong>Cupo:</strong> {{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() }} / {{ $evento->cupo_maximo }}
                            </p>
                            @endif
                            @if($evento->modalidad === 'virtual' || $evento->modalidad === 'hibrido')
                            @if($evento->link_virtual)
                            <p class="mb-2">
                                <i class="mdi mdi-video text-primary"></i>
                                <strong>Link:</strong> <a href="{{ $evento->link_virtual }}" target="_blank">Unirse al evento</a>
                            </p>
                            @endif
                            @endif
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($evento->descripcion)
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p>{{ $evento->descripcion }}</p>
                    </div>
                    @endif

                    <!-- Sistema de confirmación de asistencia -->
                    @if($evento->requiere_confirmacion && $evento->estado === 'publicado')
                    <hr>
                    <div class="alert alert-{{ $miConfirmacion && $miConfirmacion->estado_confirmacion === 'confirmado' ? 'success' : 'info' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($miConfirmacion && $miConfirmacion->estado_confirmacion === 'confirmado')
                                <i class="mdi mdi-check-circle"></i>
                                <strong>¡Has confirmado tu asistencia!</strong>
                                @else
                                <i class="mdi mdi-information"></i>
                                <strong>Este evento requiere confirmación de asistencia</strong>
                                @endif
                            </div>
                            <div>
                                @if($miConfirmacion && $miConfirmacion->estado_confirmacion === 'confirmado')
                                <form action="{{ route('extranet.eventos.cancelar-asistencia', $evento->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Cancelar tu asistencia?')">
                                        <i class="mdi mdi-close"></i> Cancelar Asistencia
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('extranet.eventos.confirmar-asistencia', $evento->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Confirmar Asistencia
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Lista de asistentes (si hay confirmados) -->
                    @if($evento->requiere_confirmacion && $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() > 0)
                    <div class="mt-4">
                        <h5>Asistentes Confirmados ({{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() }})</h5>
                        <div class="row">
                            @foreach($evento->asistentes->where('estado_confirmacion', 'confirmado')->take(12) as $asistente)
                            <div class="col-md-3 col-6 text-center mb-3">
                                @if($asistente->empleado->EMP_FOTO_URL)
                                <img src="{{ $asistente->empleado->EMP_FOTO_URL }}" alt="{{ $asistente->empleado->EMP_NOMBRES }}"
                                     class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px; font-size: 24px;">
                                    {{ substr($asistente->empleado->EMP_NOMBRES, 0, 1) }}
                                </div>
                                @endif
                                <p class="mb-0 mt-2 small">{{ explode(' ', $asistente->empleado->EMP_NOMBRES)[0] }}</p>
                            </div>
                            @endforeach
                        </div>
                        @if($evento->asistentes->where('estado_confirmacion', 'confirmado')->count() > 12)
                        <p class="text-muted small">
                            Y {{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() - 12 }} personas más...
                        </p>
                        @endif
                    </div>
                    @endif

                    <!-- Acciones -->
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('extranet.eventos.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>

                        <div>
                            @can('editar-evento')
                            <a href="{{ route('extranet.eventos.edit', $evento->id) }}" class="btn btn-info">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            @endcan

                            @can('gestionar-asistentes')
                            @if($evento->requiere_confirmacion)
                            <a href="{{ route('extranet.eventos.lista-asistentes', $evento->id) }}" class="btn btn-primary">
                                <i class="mdi mdi-account-multiple"></i> Gestionar Asistentes
                            </a>
                            @endif
                            @endcan

                            @can('eliminar-evento')
                            <form action="{{ route('extranet.eventos.destroy', $evento->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este evento permanentemente?')">
                                    <i class="mdi mdi-delete"></i> Eliminar
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success') || session('error'))
<script>
    // Auto-cerrar alertas de éxito después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Scroll suave a la alerta
        const firstAlert = document.querySelector('.alert');
        if (firstAlert) {
            firstAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endif
@endsection
