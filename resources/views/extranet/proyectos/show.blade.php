@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('extranet.proyectos.index') }}">Proyectos</a></li>
            <li class="breadcrumb-item active">{{ $proyecto->nombre }}</li>
        </ol>
    </nav>

    <!-- Header del Proyecto -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="card-title mb-2">{{ $proyecto->nombre }}</h3>

                            <div class="mb-3">
                                <span class="badge badge-{{ $proyecto->prioridad == 'critica' ? 'danger' : ($proyecto->prioridad == 'alta' ? 'warning' : 'info') }}">
                                    {{ ucfirst($proyecto->prioridad) }}
                                </span>
                                <span class="badge badge-{{ $proyecto->estado == 'en_progreso' ? 'primary' : ($proyecto->estado == 'completado' ? 'success' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Responsable:</strong> {{ $proyecto->responsable->EMP_NOMBRES ?? 'Sin responsable' }}</p>
                                    <p class="mb-1"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</p>
                                    @if($proyecto->fecha_fin)
                                    <p class="mb-1"><strong>Fin estimado:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($proyecto->objetivo)
                                    <p class="mb-1"><strong>Objetivo:</strong> {{ $proyecto->objetivo }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Progreso -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Progreso del Proyecto</span>
                                    <span class="badge badge-{{ $proyecto->progreso < 30 ? 'danger' : ($proyecto->progreso < 70 ? 'warning' : 'success') }}">
                                        {{ $proyecto->progreso }}%
                                    </span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $proyecto->progreso < 30 ? 'danger' : ($proyecto->progreso < 70 ? 'warning' : 'success') }}"
                                         role="progressbar"
                                         style="width: {{ $proyecto->progreso }}%">
                                        {{ $proyecto->progreso }}%
                                    </div>
                                </div>
                                <small class="text-muted">Progreso calculado: {{ $progresoCalculado }}% (basado en tareas completadas)</small>
                            </div>
                        </div>

                        <div class="ml-3">
                            <a href="{{ route('extranet.proyectos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Volver
                            </a>
                            @can('editar-proyecto')
                            <a href="{{ route('extranet.proyectos.edit', $proyecto->id) }}" class="btn btn-info">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablero Kanban -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0"><i class="mdi mdi-view-column"></i> Tablero de Tareas</h4>
                        @can('gestionar-tareas')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNuevaTarea">
                            <i class="mdi mdi-plus"></i> Nueva Tarea
                        </button>
                        @endcan
                    </div>

                    <div class="row">
                        <!-- Columna: Pendiente -->
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-clock-outline"></i> Pendiente
                                        <span class="badge badge-light text-dark float-right">{{ $tareasPorEstado['pendiente']->count() }}</span>
                                    </h6>
                                </div>
                                <div class="card-body p-2" style="min-height: 400px;">
                                    @foreach($tareasPorEstado['pendiente'] as $tarea)
                                    <div class="card mb-2">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1">{{ $tarea->titulo }}</h6>
                                            <small class="text-muted d-block mb-2">{{ Str::limit($tarea->descripcion, 60) }}</small>

                                            @if($tarea->asignado_a)
                                            <small class="d-block mb-1">
                                                <i class="mdi mdi-account"></i> {{ $tarea->asignado->EMP_NOMBRES ?? '' }}
                                            </small>
                                            @endif

                                            @if($tarea->fecha_vencimiento)
                                            <small class="d-block {{ \Carbon\Carbon::parse($tarea->fecha_vencimiento)->isPast() ? 'text-danger' : '' }}">
                                                <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($tarea->fecha_vencimiento)->format('d/m/Y') }}
                                            </small>
                                            @endif

                                            <span class="badge badge-{{ $tarea->prioridad == 'critica' ? 'danger' : ($tarea->prioridad == 'alta' ? 'warning' : 'secondary') }} badge-sm">
                                                {{ ucfirst($tarea->prioridad) }}
                                            </span>

                                            @can('gestionar-tareas')
                                            <div class="mt-2">
                                                <button class="btn btn-xs btn-primary" onclick="moverTarea({{ $tarea->id }}, 'en_progreso')">
                                                    <i class="mdi mdi-arrow-right"></i>
                                                </button>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Columna: En Progreso -->
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-progress-clock"></i> En Progreso
                                        <span class="badge badge-light text-dark float-right">{{ $tareasPorEstado['en_progreso']->count() }}</span>
                                    </h6>
                                </div>
                                <div class="card-body p-2" style="min-height: 400px;">
                                    @foreach($tareasPorEstado['en_progreso'] as $tarea)
                                    <div class="card mb-2 border-primary">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1">{{ $tarea->titulo }}</h6>
                                            <small class="text-muted d-block mb-2">{{ Str::limit($tarea->descripcion, 60) }}</small>

                                            @if($tarea->asignado_a)
                                            <small class="d-block mb-1">
                                                <i class="mdi mdi-account"></i> {{ $tarea->asignado->EMP_NOMBRES ?? '' }}
                                            </small>
                                            @endif

                                            @if($tarea->fecha_vencimiento)
                                            <small class="d-block {{ \Carbon\Carbon::parse($tarea->fecha_vencimiento)->isPast() ? 'text-danger' : '' }}">
                                                <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($tarea->fecha_vencimiento)->format('d/m/Y') }}
                                            </small>
                                            @endif

                                            <span class="badge badge-{{ $tarea->prioridad == 'critica' ? 'danger' : ($tarea->prioridad == 'alta' ? 'warning' : 'secondary') }} badge-sm">
                                                {{ ucfirst($tarea->prioridad) }}
                                            </span>

                                            @can('gestionar-tareas')
                                            <div class="mt-2">
                                                <button class="btn btn-xs btn-secondary" onclick="moverTarea({{ $tarea->id }}, 'pendiente')">
                                                    <i class="mdi mdi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-xs btn-warning" onclick="moverTarea({{ $tarea->id }}, 'revision')">
                                                    <i class="mdi mdi-arrow-right"></i>
                                                </button>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Columna: En Revisión -->
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-eye-check"></i> En Revisión
                                        <span class="badge badge-light text-dark float-right">{{ $tareasPorEstado['revision']->count() }}</span>
                                    </h6>
                                </div>
                                <div class="card-body p-2" style="min-height: 400px;">
                                    @foreach($tareasPorEstado['revision'] as $tarea)
                                    <div class="card mb-2 border-warning">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1">{{ $tarea->titulo }}</h6>
                                            <small class="text-muted d-block mb-2">{{ Str::limit($tarea->descripcion, 60) }}</small>

                                            @if($tarea->asignado_a)
                                            <small class="d-block mb-1">
                                                <i class="mdi mdi-account"></i> {{ $tarea->asignado->EMP_NOMBRES ?? '' }}
                                            </small>
                                            @endif

                                            <span class="badge badge-{{ $tarea->prioridad == 'critica' ? 'danger' : ($tarea->prioridad == 'alta' ? 'warning' : 'secondary') }} badge-sm">
                                                {{ ucfirst($tarea->prioridad) }}
                                            </span>

                                            @can('gestionar-tareas')
                                            <div class="mt-2">
                                                <button class="btn btn-xs btn-primary" onclick="moverTarea({{ $tarea->id }}, 'en_progreso')">
                                                    <i class="mdi mdi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-xs btn-success" onclick="moverTarea({{ $tarea->id }}, 'completada')">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Columna: Completada -->
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-check-circle"></i> Completada
                                        <span class="badge badge-light text-dark float-right">{{ $tareasPorEstado['completada']->count() }}</span>
                                    </h6>
                                </div>
                                <div class="card-body p-2" style="min-height: 400px;">
                                    @foreach($tareasPorEstado['completada'] as $tarea)
                                    <div class="card mb-2 border-success">
                                        <div class="card-body p-2">
                                            <h6 class="mb-1 text-muted"><s>{{ $tarea->titulo }}</s></h6>

                                            @if($tarea->asignado_a)
                                            <small class="d-block mb-1">
                                                <i class="mdi mdi-account"></i> {{ $tarea->asignado->EMP_NOMBRES ?? '' }}
                                            </small>
                                            @endif

                                            @if($tarea->fecha_completada)
                                            <small class="d-block text-success">
                                                <i class="mdi mdi-check-circle"></i> {{ \Carbon\Carbon::parse($tarea->fecha_completada)->format('d/m/Y') }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Tarea -->
<div class="modal fade" id="modalNuevaTarea" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('extranet.proyectos.tareas.store', $proyecto->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Título *</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Asignado a</label>
                        <select class="form-control" name="asignado_a">
                            <option value="">Sin asignar</option>
                            @foreach($empleados as $emp)
                            <option value="{{ $emp->EMP_ID }}">{{ $emp->EMP_NOMBRES }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado *</label>
                                <select class="form-control" name="estado" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_progreso">En Progreso</option>
                                    <option value="revision">En Revisión</option>
                                    <option value="completada">Completada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prioridad *</label>
                                <select class="form-control" name="prioridad" required>
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" class="form-control" name="fecha_vencimiento">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function moverTarea(tareaId, nuevoEstado) {
    if (!confirm('¿Mover esta tarea a ' + nuevoEstado.replace('_', ' ') + '?')) {
        return;
    }

    fetch(`/extranet/tareas/${tareaId}/mover`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection
