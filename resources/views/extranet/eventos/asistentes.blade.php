@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.eventos.index') }}">Eventos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.eventos.show', $evento->id) }}">{{ $evento->titulo }}</a></li>
                    <li class="breadcrumb-item active">Asistentes</li>
                </ol>
            </nav>

            <!-- Información del evento -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="card-title mb-2">
                                <i class="mdi mdi-account-multiple"></i> Lista de Asistentes
                            </h4>
                            <h5 class="text-muted">{{ $evento->titulo }}</h5>
                            <p class="mb-0">
                                <i class="mdi mdi-calendar"></i>
                                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <a href="{{ route('extranet.eventos.show', $evento->id) }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver al Evento
                        </a>
                    </div>

                    <!-- Estadísticas de asistencia -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ $evento->asistentes->count() }}</h3>
                                    <small>Total Registrados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() }}</h3>
                                    <small>Confirmados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ $evento->asistentes->where('estado_confirmacion', 'pendiente')->count() }}</h3>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3 class="mb-0">{{ $evento->asistentes->where('asistio', true)->count() }}</h3>
                                    <small>Asistieron</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($evento->cupo_maximo)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        Cupo: {{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() }} / {{ $evento->cupo_maximo }}
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ ($evento->asistentes->where('estado_confirmacion', 'confirmado')->count() / $evento->cupo_maximo) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tabla de asistentes -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Listado Completo</h5>

                    @if($evento->asistentes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empleado</th>
                                    <th>Cargo</th>
                                    <th>Estado Confirmación</th>
                                    <th>Asistió</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evento->asistentes as $index => $asistente)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($asistente->empleado->EMP_FOTO_URL)
                                            <img src="{{ $asistente->empleado->EMP_FOTO_URL }}"
                                                 alt="{{ $asistente->empleado->EMP_NOMBRES }}"
                                                 class="rounded-circle mr-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mr-2"
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                {{ substr($asistente->empleado->EMP_NOMBRES, 0, 1) }}{{ substr($asistente->empleado->EMP_APELLIDOS, 0, 1) }}
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $asistente->empleado->EMP_NOMBRES }} {{ $asistente->empleado->EMP_APELLIDOS }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $asistente->empleado->EMP_EMAIL }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $asistente->empleado->cargo->CAR_NOMBRE ?? 'N/A' }}</td>
                                    <td>
                                        @if($asistente->estado_confirmacion == 'confirmado')
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check-circle"></i> Confirmado
                                            </span>
                                        @elseif($asistente->estado_confirmacion == 'rechazado')
                                            <span class="badge badge-danger">
                                                <i class="mdi mdi-close-circle"></i> Rechazado
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="mdi mdi-clock"></i> Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($asistente->asistio)
                                            <span class="badge badge-success badge-pill">
                                                <i class="mdi mdi-check-bold"></i> Sí
                                            </span>
                                        @else
                                            <span class="badge badge-secondary badge-pill">
                                                <i class="mdi mdi-minus"></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($asistente->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-sm btn-{{ $asistente->asistio ? 'success' : 'outline-success' }}"
                                                    onclick="marcarAsistencia({{ $asistente->empleado_id }}, true)"
                                                    title="Marcar como asistió">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-{{ !$asistente->asistio ? 'danger' : 'outline-danger' }}"
                                                    onclick="marcarAsistencia({{ $asistente->empleado_id }}, false)"
                                                    title="Marcar como no asistió">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-account-off mdi-48px text-muted"></i>
                        <p class="text-muted mt-3">No hay asistentes registrados para este evento</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Botones de exportación -->
            @if($evento->asistentes->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Exportar Lista</h5>
                    <p class="text-muted">Descarga la lista de asistentes en diferentes formatos</p>
                    <div class="btn-group">
                        <button class="btn btn-success" onclick="exportarExcel()">
                            <i class="mdi mdi-file-excel"></i> Exportar a Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportarPDF()">
                            <i class="mdi mdi-file-pdf"></i> Exportar a PDF
                        </button>
                        <button class="btn btn-info" onclick="window.print()">
                            <i class="mdi mdi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function marcarAsistencia(empleadoId, asistio) {
    if (!confirm(`¿Confirmar que el empleado ${asistio ? 'SÍ' : 'NO'} asistió al evento?`)) {
        return;
    }

    fetch(`{{ route('extranet.eventos.marcar-asistencia', $evento->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            empleado_id: empleadoId,
            asistio: asistio
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al actualizar asistencia');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar asistencia');
    });
}

function exportarExcel() {
    alert('Funcionalidad de exportación a Excel en desarrollo');
    // TODO: Implementar exportación a Excel
}

function exportarPDF() {
    alert('Funcionalidad de exportación a PDF en desarrollo');
    // TODO: Implementar exportación a PDF
}
</script>

<style>
@media print {
    .card-body .btn-group,
    .breadcrumb,
    nav,
    .btn {
        display: none !important;
    }
}
</style>
@endsection
