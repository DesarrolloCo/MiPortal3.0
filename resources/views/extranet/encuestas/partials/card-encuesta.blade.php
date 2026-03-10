<div class="card h-100 border-left-{{ $encuesta->estado == 'activa' ? 'primary' : ($encuesta->estado == 'cerrada' ? 'secondary' : 'warning') }}" style="border-left-width: 4px !important;">
    <div class="card-body">
        <!-- Estado -->
        <div class="mb-2">
            @if($encuesta->estado == 'activa')
                <span class="badge badge-success badge-pill">
                    <i class="mdi mdi-play-circle"></i> Activa
                </span>
            @elseif($encuesta->estado == 'cerrada')
                <span class="badge badge-secondary badge-pill">
                    <i class="mdi mdi-check-circle"></i> Cerrada
                </span>
            @else
                <span class="badge badge-warning badge-pill">
                    <i class="mdi mdi-file-document-outline"></i> Borrador
                </span>
            @endif

            @if($encuesta->anonima)
                <span class="badge badge-info badge-pill">
                    <i class="mdi mdi-incognito"></i> Anónima
                </span>
            @endif

            @if(isset($pendiente) && $pendiente)
                <span class="badge badge-danger badge-pill">
                    <i class="mdi mdi-alert"></i> Pendiente
                </span>
            @endif
        </div>

        <!-- Título -->
        <h5 class="card-title">{{ $encuesta->titulo }}</h5>

        <!-- Descripción -->
        @if($encuesta->descripcion)
        <p class="card-text text-muted">
            {{ Str::limit($encuesta->descripcion, 100) }}
        </p>
        @endif

        <!-- Metadata -->
        <div class="small text-muted mb-3">
            <div class="d-flex justify-content-between">
                <span>
                    <i class="mdi mdi-help-circle-outline"></i>
                    {{ $encuesta->preguntas->count() }} preguntas
                </span>
                <span>
                    <i class="mdi mdi-account-multiple"></i>
                    {{ $encuesta->total_respuestas }} respuestas
                </span>
            </div>

            @if($encuesta->fecha_fin)
            <div class="mt-2">
                <i class="mdi mdi-calendar-clock"></i>
                Cierra: {{ \Carbon\Carbon::parse($encuesta->fecha_fin)->locale('es')->diffForHumans() }}
            </div>
            @endif
        </div>

        <!-- Progress (si es activa y tiene respuestas) -->
        @if($encuesta->estado == 'activa' && $encuesta->total_respuestas > 0)
        <div class="mb-3">
            @php
            $empleadosActivos = \App\Models\empleado::where('EMP_ACTIVO', 1)->count();
            $porcentajeParticipacion = $empleadosActivos > 0 ? round(($encuesta->total_respuestas / $empleadosActivos) * 100, 1) : 0;
            @endphp
            <div class="d-flex justify-content-between mb-1">
                <small>Participación</small>
                <small class="font-weight-bold text-{{ $porcentajeParticipacion >= 70 ? 'success' : ($porcentajeParticipacion >= 40 ? 'warning' : 'danger') }}">
                    {{ $porcentajeParticipacion }}%
                </small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-{{ $porcentajeParticipacion >= 70 ? 'success' : ($porcentajeParticipacion >= 40 ? 'warning' : 'danger') }}"
                     role="progressbar"
                     style="width: {{ $porcentajeParticipacion }}%">
                </div>
            </div>
        </div>
        @endif

        <!-- Acciones -->
        <div class="d-flex justify-content-between align-items-center">
            @if($encuesta->estado == 'activa')
                @can('responder-encuesta')
                <a href="{{ route('extranet.encuestas.show', $encuesta->id) }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-play"></i> Responder
                </a>
                @endcan
            @elseif($encuesta->estado == 'cerrada')
                @can('ver-resultados-encuesta')
                <a href="{{ route('extranet.encuestas.resultados', $encuesta->id) }}" class="btn btn-info btn-sm">
                    <i class="mdi mdi-chart-bar"></i> Ver Resultados
                </a>
                @endcan
            @else
                @can('editar-encuesta')
                <a href="{{ route('extranet.encuestas.edit', $encuesta->id) }}" class="btn btn-warning btn-sm">
                    <i class="mdi mdi-pencil"></i> Editar
                </a>
                @endcan
            @endif

            <!-- Dropdown de opciones -->
            @canany(['editar-encuesta', 'eliminar-encuesta', 'ver-resultados-encuesta'])
            <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="mdi mdi-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('extranet.encuestas.show', $encuesta->id) }}">
                        <i class="mdi mdi-eye"></i> Ver
                    </a>

                    @can('ver-resultados-encuesta')
                    @if($encuesta->total_respuestas > 0)
                    <a class="dropdown-item" href="{{ route('extranet.encuestas.resultados', $encuesta->id) }}">
                        <i class="mdi mdi-chart-bar"></i> Resultados
                    </a>
                    @endif
                    @endcan

                    @can('editar-encuesta')
                    <a class="dropdown-item" href="{{ route('extranet.encuestas.edit', $encuesta->id) }}">
                        <i class="mdi mdi-pencil"></i> Editar
                    </a>
                    @endcan

                    @can('eliminar-encuesta')
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('extranet.encuestas.destroy', $encuesta->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar esta encuesta permanentemente?')">
                            <i class="mdi mdi-delete"></i> Eliminar
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endcanany
        </div>
    </div>

    @if($encuesta->estado == 'activa' && $encuesta->fecha_fin)
    @php
    $fechaFin = \Carbon\Carbon::parse($encuesta->fecha_fin);
    $diasRestantes = max(0, \Carbon\Carbon::now()->diffInDays($fechaFin, false));
    @endphp
    @if($diasRestantes <= 3 && $diasRestantes >= 0)
    <div class="card-footer bg-warning text-white small">
        <i class="mdi mdi-clock-alert"></i> ¡Cierra en {{ $diasRestantes }} {{ $diasRestantes == 1 ? 'día' : 'días' }}!
    </div>
    @endif
    @endif
</div>
