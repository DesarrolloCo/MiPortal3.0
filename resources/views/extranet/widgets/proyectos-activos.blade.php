<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-briefcase text-primary"></i> Proyectos Activos</h4>
        <h6 class="card-subtitle">En progreso y planificación</h6>

        @if($proyectosActivos->count() > 0)
            <div class="m-t-20">
                @foreach($proyectosActivos as $item)
                <div class="m-b-20 p-b-20" style="border-bottom: 1px solid #f1f1f1;">
                    <div class="d-flex justify-content-between align-items-center m-b-10">
                        <h6 class="m-b-0">{{ $item['proyecto']->nombre }}</h6>
                        <span class="badge badge-{{ $item['proyecto']->estado == 'en_progreso' ? 'primary' : 'info' }}">
                            {{ ucfirst(str_replace('_', ' ', $item['proyecto']->estado)) }}
                        </span>
                    </div>

                    <div class="progress m-b-10" style="height: 8px;">
                        <div class="progress-bar bg-{{ $item['proyecto']->progreso < 30 ? 'danger' : ($item['proyecto']->progreso < 70 ? 'warning' : 'success') }}"
                             role="progressbar"
                             style="width: {{ $item['proyecto']->progreso }}%;"
                             aria-valuenow="{{ $item['proyecto']->progreso }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="mdi mdi-checkbox-marked-circle"></i>
                            {{ $item['tareas_completadas'] }}/{{ $item['total_tareas'] }} tareas
                        </small>
                        <small class="text-muted">{{ $item['proyecto']->progreso }}% completado</small>
                    </div>

                    @if($item['proyecto']->responsable)
                        <small class="text-muted">
                            <i class="mdi mdi-account"></i> {{ $item['proyecto']->responsable->EMP_NOMBRES }}
                        </small>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-briefcase-outline mdi-48px text-muted"></i>
                <p class="text-muted">No hay proyectos activos</p>
            </div>
        @endif
    </div>
</div>
