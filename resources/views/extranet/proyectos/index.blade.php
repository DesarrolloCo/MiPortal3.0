@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0"><i class="mdi mdi-clipboard-text-multiple"></i> Gestión de Proyectos</h4>
                            <h6 class="card-subtitle">Proyectos departamentales y corporativos</h6>
                        </div>
                        @can('crear-proyecto')
                        <a href="{{ route('extranet.proyectos.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nuevo Proyecto
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('extranet.proyectos.index') }}" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Buscar</label>
                                <input type="text" name="buscar" class="form-control" placeholder="Nombre o descripción..." value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="">Activos</option>
                                    <option value="planificacion" {{ request('estado') == 'planificacion' ? 'selected' : '' }}>Planificación</option>
                                    <option value="en_progreso" {{ request('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="pausado" {{ request('estado') == 'pausado' ? 'selected' : '' }}>Pausado</option>
                                    <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Prioridad</label>
                                <select name="prioridad" class="form-control">
                                    <option value="">Todas</option>
                                    <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                                    <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                                    <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                                    <option value="critica" {{ request('prioridad') == 'critica' ? 'selected' : '' }}>Crítica</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Responsable</label>
                                <select name="responsable_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach($empleados as $emp)
                                    <option value="{{ $emp->EMP_ID }}" {{ request('responsable_id') == $emp->EMP_ID ? 'selected' : '' }}>
                                        {{ $emp->EMP_NOMBRES }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info"><i class="mdi mdi-filter"></i> Filtrar</button>
                                    <a href="{{ route('extranet.proyectos.index') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Limpiar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de proyectos -->
    <div class="row">
        @forelse($proyectos as $proyecto)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-2">
                        <span class="badge badge-{{ $proyecto->prioridad == 'critica' ? 'danger' : ($proyecto->prioridad == 'alta' ? 'warning' : ($proyecto->prioridad == 'media' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($proyecto->prioridad) }}
                        </span>
                        <span class="badge badge-{{ $proyecto->estado == 'en_progreso' ? 'primary' : ($proyecto->estado == 'completado' ? 'success' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                        </span>
                    </div>

                    <h5 class="card-title">{{ $proyecto->nombre }}</h5>

                    <p class="card-text text-muted small">
                        <i class="mdi mdi-account"></i> {{ $proyecto->responsable->EMP_NOMBRES ?? 'Sin responsable' }}
                        <br>
                        <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}
                        @if($proyecto->fecha_fin)
                        - {{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') }}
                        @endif
                    </p>

                    <!-- Progreso -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Progreso</small>
                            <small><strong>{{ $proyecto->progreso }}%</strong></small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $proyecto->progreso < 30 ? 'danger' : ($proyecto->progreso < 70 ? 'warning' : 'success') }}"
                                 role="progressbar"
                                 style="width: {{ $proyecto->progreso }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Tareas -->
                    @php
                        $totalTareas = $proyecto->tareas->count();
                        $tareasCompletadas = $proyecto->tareas->where('estado', 'completada')->count();
                    @endphp
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="mdi mdi-checkbox-marked-circle-outline"></i> {{ $tareasCompletadas }} / {{ $totalTareas }} tareas completadas
                        </small>
                    </div>

                    <!-- Descripción -->
                    @if($proyecto->descripcion)
                    <p class="card-text">
                        {{ Str::limit($proyecto->descripcion, 100) }}
                    </p>
                    @endif

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('extranet.proyectos.show', $proyecto->id) }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-view-dashboard"></i> Ver Kanban
                        </a>

                        @can('editar-proyecto')
                        <div class="btn-group">
                            <a href="{{ route('extranet.proyectos.edit', $proyecto->id) }}" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            @can('eliminar-proyecto')
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Eliminar este proyecto?')) { document.getElementById('delete-form-{{ $proyecto->id }}').submit(); }">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            <form id="delete-form-{{ $proyecto->id }}" action="{{ route('extranet.proyectos.destroy', $proyecto->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endcan
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-clipboard-text-outline mdi-48px text-muted"></i>
                    <p class="text-muted mt-3">No se encontraron proyectos</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($proyectos->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $proyectos->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
