<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-briefcase text-primary"></i> Proyectos Activos</h4>
        <h6 class="card-subtitle">En progreso y planificación</h6>

        @if($proyectosActivos->count() > 0)
            <div class="m-t-20" style="max-height: 500px; overflow-y: auto;">
                @foreach($proyectosActivos as $item)
                <div class="border-bottom pb-3 pt-3">
                    <!-- Header del proyecto -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">
                                <a href="{{ route('extranet.proyectos.show', $item['proyecto']->id) }}" class="text-dark">
                                    {{ $item['proyecto']->nombre }}
                                </a>
                            </h6>
                            @if($item['proyecto']->responsable)
                            <small class="text-muted d-block">
                                <i class="mdi mdi-account-circle"></i>
                                {{ $item['proyecto']->responsable->EMP_NOMBRES }} {{ $item['proyecto']->responsable->EMP_APELLIDOS }}
                            </small>
                            @endif
                        </div>
                        <div class="ml-2">
                            <span class="badge badge-{{ $item['proyecto']->prioridad == 'critica' ? 'danger' : ($item['proyecto']->prioridad == 'alta' ? 'warning' : 'info') }} badge-pill">
                                {{ ucfirst($item['proyecto']->prioridad) }}
                            </span>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Progreso del proyecto</small>
                            <small class="font-weight-bold text-{{ $item['proyecto']->progreso < 30 ? 'danger' : ($item['proyecto']->progreso < 70 ? 'warning' : 'success') }}">
                                {{ $item['proyecto']->progreso }}%
                            </small>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-{{ $item['proyecto']->progreso < 30 ? 'danger' : ($item['proyecto']->progreso < 70 ? 'warning' : 'success') }}"
                                 role="progressbar"
                                 style="width: {{ $item['proyecto']->progreso }}%;">
                            </div>
                        </div>
                    </div>

                    <!-- Información de tareas y estado -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="mdi mdi-checkbox-marked-circle-outline text-success"></i>
                                <strong>{{ $item['tareas_completadas'] }}</strong>/{{ $item['total_tareas'] }} tareas
                            </small>
                            @if($item['tareas_pendientes'] > 0)
                            <small class="text-muted ml-2">
                                <i class="mdi mdi-clock-outline text-warning"></i>
                                {{ $item['tareas_pendientes'] }} pendientes
                            </small>
                            @endif
                        </div>
                        <div>
                            <span class="badge badge-{{ $item['proyecto']->estado == 'en_progreso' ? 'primary' : 'secondary' }} badge-pill">
                                {{ $item['proyecto']->estado == 'en_progreso' ? 'En Progreso' : 'Planificación' }}
                            </span>
                        </div>
                    </div>

                    <!-- Botón de acción -->
                    <div class="mt-2">
                        <a href="{{ route('extranet.proyectos.show', $item['proyecto']->id) }}"
                           class="btn btn-sm btn-outline-primary btn-block">
                            <i class="mdi mdi-view-dashboard"></i> Ver Kanban
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Resumen total -->
            <div class="text-center mt-3 pt-3 border-top">
                <small class="text-muted">
                    <strong>{{ $proyectosActivos->count() }}</strong> proyectos activos
                </small>
                <br>
                <a href="{{ route('extranet.proyectos.index') }}" class="btn btn-sm btn-link">
                    Ver todos los proyectos <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-briefcase-outline mdi-48px text-muted"></i>
                <p class="text-muted mt-2">No hay proyectos activos en este momento</p>
                @can('crear-proyecto')
                <a href="{{ route('extranet.proyectos.create') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="mdi mdi-plus"></i> Crear proyecto
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
