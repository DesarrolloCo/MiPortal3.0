@extends('layouts.main')

@section('main')

<!-- Bread crumb and right sidebar toggle -->
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Detalle de Novedad #{{ $novedad->NOV_ID }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Novedades.index') }}">Novedades</a></li>
            <li class="breadcrumb-item active">Detalle</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            @if($novedad->NOV_ESTADO_APROBACION === 'pendiente')
                <a href="{{ route('Novedades.edit', $novedad->NOV_ID) }}" class="btn btn-warning btn-sm mr-2">
                    <i class="mdi mdi-pencil"></i> Editar
                </a>
            @endif
            <a href="{{ route('Novedades.index') }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- Header Simple -->
<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="page-title">Novedad #{{ $novedad->NOV_ID }}</h2>
            <p class="page-subtitle">{{ $novedad->tipoNovedad->TIN_NOMBRE ?? 'N/A' }} - {{ $novedad->empleado->EMP_NOMBRES ?? 'N/A' }} {{ $novedad->empleado->EMP_APELLIDOS ?? '' }}</p>
        </div>
        <div class="col-md-4 text-right">
            <span class="badge badge-{{ $novedad->estado_color }} status-badge">
                <i class="mdi
                    @if($novedad->NOV_ESTADO_APROBACION === 'pendiente') mdi-clock-outline
                    @elseif($novedad->NOV_ESTADO_APROBACION === 'aprobada') mdi-check-circle
                    @else mdi-close-circle
                    @endif me-2
                "></i>
                {{ $novedad->estado_texto }}
            </span>
        </div>
    </div>
</div>

<!-- Información Principal -->
<div class="row">
    <div class="col-lg-8">
        <!-- Información del Empleado -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="mdi mdi-account text-primary me-2"></i>
                    Información del Empleado
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Empleado:</label>
                            <p class="form-control-plaintext">{{ $novedad->empleado->EMP_NOMBRES ?? 'N/A' }} {{ $novedad->empleado->EMP_APELLIDOS ?? '' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identificación:</label>
                            <p class="form-control-plaintext">{{ $novedad->empleado->EMP_CEDULA ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Departamento:</label>
                            <p class="form-control-plaintext">{{ $novedad->empleado->departamento->DEP_NOMBRE ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cargo:</label>
                            <p class="form-control-plaintext">{{ $novedad->empleado->cargo->CAR_NOMBRE ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($novedad->horarios->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-clock-outline text-primary me-2"></i>
                        Horarios afectados
                    </h5>
                    <span class="badge badge-secondary">{{ $novedad->horarios->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Fecha</th>
                                    <th>Hora inicio</th>
                                    <th>Hora fin</th>
                                    <th>Estado del horario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($novedad->horarios->sortBy('MAL_INICIO') as $index => $horario)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $horario->MAL_DIA ? \Carbon\Carbon::parse($horario->MAL_DIA)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $horario->MAL_INICIO ? \Carbon\Carbon::parse($horario->MAL_INICIO)->format('H:i') : 'N/A' }}</td>
                                        <td>{{ $horario->MAL_FINAL ? \Carbon\Carbon::parse($horario->MAL_FINAL)->format('H:i') : 'N/A' }}</td>
                                        <td>
                                            @if($horario->MAL_ESTADO === 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detalles de la Novedad -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="mdi mdi-file-document text-primary me-2"></i>
                    Detalles de la Novedad
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipo de Novedad:</label>
                            <p class="form-control-plaintext">
                                <span class="badge badge-info">{{ $novedad->tipoNovedad->TIN_NOMBRE ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Duracion:</label>
                            <p class="form-control-plaintext">
                                @php
                                    $fechaInicio = $novedad->nov_fecha_inicio;
                                    $fechaFin = $novedad->nov_fecha_fin;
                                @endphp
                                @if($fechaInicio && $fechaFin)
                                    @php
                                        $dias = $fechaInicio->diffInDays($fechaFin) + 1;
                                    @endphp
                                    {{ $dias }} {{ $dias === 1 ? 'dia' : 'dias' }}
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Descripcion:</label>
                            <div class="description-box">
                                {{ $novedad->NOV_DESCRIPCION }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Inicio:</label>
                            <p class="form-control-plaintext">
                                {{ $fechaInicio ? $fechaInicio->format('d/m/Y') : 'N/A' }}
                                @if($novedad->nov_hora_inicio)
                                    <small class="text-muted">{{ $novedad->nov_hora_inicio->format('H:i') }}</small>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Fin:</label>
                            <p class="form-control-plaintext">
                                {{ $fechaFin ? $fechaFin->format('d/m/Y') : 'No aplica' }}
                                @if($novedad->nov_hora_fin)
                                    <small class="text-muted">{{ $novedad->nov_hora_fin->format('H:i') }}</small>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Archivos Adjuntos -->
        @php
            $archivos_array = $novedad->archivos_lista ?? [];
        @endphp

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="mdi mdi-paperclip text-primary me-2"></i>
                    Archivos Adjuntos
                    @if($archivos_array && count($archivos_array) > 0)
                        <span class="badge badge-secondary">{{ count($archivos_array) }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($archivos_array && count($archivos_array) > 0)
                    <div class="files-list">
                        @foreach($archivos_array as $index => $archivo)
                            @php
                                $extension = pathinfo($archivo['nombre_original'] ?? '', PATHINFO_EXTENSION);
                                $icon_class = 'mdi-file-document';
                                $file_color = 'text-secondary';

                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon_class = 'mdi-file-image';
                                    $file_color = 'text-success';
                                } elseif (in_array(strtolower($extension), ['pdf'])) {
                                    $icon_class = 'mdi-file-pdf-box';
                                    $file_color = 'text-danger';
                                } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                    $icon_class = 'mdi-file-word-box';
                                    $file_color = 'text-primary';
                                } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                    $icon_class = 'mdi-file-excel-box';
                                    $file_color = 'text-warning';
                                }
                            @endphp

                            <div class="file-row">
                                <div class="file-icon {{ $file_color }}">
                                    <i class="mdi {{ $icon_class }}"></i>
                                </div>

                                <div class="file-details">
                                    <div class="file-name">{{ $archivo['nombre_original'] ?? 'archivo_' . $index }}</div>
                                    <div class="file-meta">
                                        <span>{{ number_format(($archivo['size'] ?? 0) / 1024, 1) }} KB</span>
                                        @if(isset($archivo['fecha_subida']))
                                            <span>{{ date('d/m/Y', strtotime($archivo['fecha_subida'])) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="file-actions">
                                    <a href="{{ route('Novedades.verArchivo', [$novedad->NOV_ID, $index]) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Ver archivo">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="{{ route('Novedades.descargarArchivo', [$novedad->NOV_ID, $index]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Descargar archivo">
                                        <i class="mdi mdi-download"></i>
                                    </a>
                                    @if($novedad->NOV_ESTADO_APROBACION === 'pendiente')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="eliminarArchivo({{ $novedad->NOV_ID }}, {{ $index }})"
                                                title="Eliminar archivo">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="mdi mdi-file-outline"></i>
                        <p>No hay archivos adjuntos</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel de Estado y Acciones -->
    <div class="col-lg-4">
        <!-- Timeline de Estado -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="mdi mdi-timeline-clock text-primary me-2"></i>
                    Seguimiento
                </h5>
            </div>
            <div class="card-body">
                <div class="status-timeline">
                    @foreach($novedad->logs->sortBy('created_at') as $log)
                        <div class="status-step completed">
                            <div class="step-indicator">
                                <i class="mdi {{ $log->action_icon }}"></i>
                            </div>
                            <div class="step-content">
                                <h6>{{ $log->action_label }}</h6>
                                <p>{{ $log->user->name ?? 'N/A' }}</p>
                                <small>{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                @if($log->description)
                                    <div class="mt-2 text-muted small">{{ $log->description }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Estado Actual si no hay log correspondiente -->
                    @if($novedad->NOV_ESTADO_APROBACION === 'pendiente' && !$novedad->logs->where('action', 'approved')->count() && !$novedad->logs->where('action', 'rejected')->count())
                        <div class="status-step current">
                            <div class="step-indicator">
                                <i class="mdi mdi-clock-outline"></i>
                            </div>
                            <div class="step-content">
                                <h6>En Revisión</h6>
                                <p>Esperando aprobación</p>
                                <span class="badge badge-warning">Pendiente</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        @if($novedad->NOV_OBSERVACIONES)
        <div class="card mb-4 border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-message-text text-primary me-2"></i>
                    Observaciones
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-left-warning">
                    <i class="mdi mdi-alert-circle text-warning me-2"></i>
                    {{ $novedad->NOV_OBSERVACIONES }}
                </div>
            </div>
        </div>
        @endif

        <!-- Acciones para Gestión Humana -->
        @if($novedad->NOV_ESTADO_APROBACION === 'pendiente' && auth()->user()->hasRole('Gestión Humana'))
        <div class="card border-0 shadow">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-account-check text-primary me-2"></i>
                    Acciones de Gestión
                </h5>
            </div>
            <div class="card-body">
                <div class="action-buttons">
                    <button type="button" class="btn btn-success btn-lg btn-block mb-3" onclick="aprobarNovedad({{ $novedad->NOV_ID }})">
                        <i class="mdi mdi-check-circle me-2"></i> Aprobar Novedad
                    </button>
                    <button type="button" class="btn btn-danger btn-lg btn-block" onclick="abrirModalRechazo({{ $novedad->NOV_ID }})">
                        <i class="mdi mdi-close-circle me-2"></i> Rechazar Novedad
                    </button>
                </div>
                <hr>
                <div class="alert alert-info">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <small>Una vez procesada, la novedad no podrá ser modificada.</small>
                </div>
            </div>
        </div>
        @endif

        <!-- Información Adicional -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>
                    Información Adicional
                </h5>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">ID de Novedad:</span>
                        <span class="info-value">#{{ $novedad->NOV_ID }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Fecha de Creación:</span>
                        <span class="info-value">{{ $novedad->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if($novedad->updated_at && $novedad->updated_at != $novedad->created_at)
                    <div class="info-row">
                        <span class="info-label">Última Modificación:</span>
                        <span class="info-value">{{ $novedad->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">Registrado por:</span>
                        <span class="info-value">{{ $novedad->usuario->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div class="modal fade" id="modalRechazo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechazar Novedad</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="form-rechazo" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="observaciones" class="required">Motivo del Rechazo</label>
                        <textarea name="observaciones" id="observaciones" rows="4" class="form-control"
                            placeholder="Explique el motivo por el cual se rechaza esta novedad..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Novedad</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Aprobar novedad individual
    function aprobarNovedad(novedadId) {
        if (confirm('¿Está seguro de que desea aprobar esta novedad?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/Novedades/${novedadId}/aprobar`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Abrir modal de rechazo
    function abrirModalRechazo(novedadId) {
        document.getElementById('form-rechazo').action = `/Novedades/${novedadId}/rechazar`;
        document.getElementById('observaciones').value = '';
        $('#modalRechazo').modal('show');
    }

    // Eliminar archivo
    function eliminarArchivo(novedadId, indiceArchivo) {
        if (confirm('¿Está seguro de que desea eliminar este archivo?')) {
            fetch(`/Novedades/${novedadId}/archivo/${indiceArchivo}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.success);
                    location.reload();
                } else if (data.error) {
                    toastr.error(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error al eliminar el archivo');
            });
        }
    }

    // Mensajes de éxito/error
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>
@endsection

@section('styles')
<style>
/* ===== ESTILOS SIMPLES Y LIMPIOS ===== */

/* Header de página */
.page-header {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

.status-badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

/* Cards básicos */
.card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
}

.card-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
}

.card-body {
    padding: 1.25rem;
}

/* Form groups */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control-plaintext {
    padding: 0;
    margin-bottom: 0;
    color: #6c757d;
    background: transparent;
    border: none;
}

/* Description box */
.description-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    color: #495057;
    line-height: 1.5;
}

/* Status Timeline */
.status-timeline {
    position: relative;
}

.status-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 25px;
    bottom: 25px;
    width: 2px;
    background: #e9ecef;
}

.status-step {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.status-step:last-child {
    margin-bottom: 0;
}

.step-indicator {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}

.status-step.completed .step-indicator {
    background: #28a745;
}

.status-step.current .step-indicator {
    background: #ffc107;
    color: #495057;
}

.step-content {
    flex: 1;
    padding-top: 0.25rem;
}

.step-content h6 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: #495057;
    font-size: 1rem;
}

.step-content p {
    margin: 0 0 0.25rem 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.step-content small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Info List */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .info-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.info-row .info-value {
    color: #6c757d;
    font-size: 0.9rem;
    text-align: right;
}

/* Files List */
.files-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.file-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #fafafa;
    transition: all 0.2s ease;
}

.file-row:hover {
    background: #f5f5f5;
    border-color: #ddd;
}

.file-icon {
    font-size: 1.75rem;
    width: 40px;
    text-align: center;
    flex-shrink: 0;
}

.file-details {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.file-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.file-actions {
    display: flex;
    gap: 0.25rem;
    flex-shrink: 0;
}

.file-actions .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.empty-state p {
    margin: 0;
    font-size: 0.9rem;
}

/* Utility classes */
.me-1 { margin-right: 0.25rem !important; }
.me-2 { margin-right: 0.5rem !important; }
.me-3 { margin-right: 1rem !important; }
.text-primary { color: #007bff !important; }
</style>
@endsection




