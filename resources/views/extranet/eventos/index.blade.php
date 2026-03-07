@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0"><i class="mdi mdi-calendar-multiple"></i> Eventos Corporativos</h4>
                            <h6 class="card-subtitle">Calendario de actividades y eventos</h6>
                        </div>
                        <div>
                            @can('crear-evento')
                            <a href="{{ route('extranet.eventos.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Nuevo Evento
                            </a>
                            @endcan
                            <a href="{{ route('extranet.eventos.index', ['vista' => 'calendario']) }}" class="btn btn-info">
                                <i class="mdi mdi-calendar"></i> Ver Calendario
                            </a>
                        </div>
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
                    <form method="GET" action="{{ route('extranet.eventos.index') }}" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Buscar</label>
                                <input type="text" name="buscar" class="form-control" placeholder="Título..." value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="reunion" {{ request('tipo') == 'reunion' ? 'selected' : '' }}>Reunión</option>
                                    <option value="capacitacion" {{ request('tipo') == 'capacitacion' ? 'selected' : '' }}>Capacitación</option>
                                    <option value="celebracion" {{ request('tipo') == 'celebracion' ? 'selected' : '' }}>Celebración</option>
                                    <option value="conferencia" {{ request('tipo') == 'conferencia' ? 'selected' : '' }}>Conferencia</option>
                                    <option value="team_building" {{ request('tipo') == 'team_building' ? 'selected' : '' }}>Team Building</option>
                                    <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Modalidad</label>
                                <select name="modalidad" class="form-control">
                                    <option value="">Todas</option>
                                    <option value="presencial" {{ request('modalidad') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                                    <option value="virtual" {{ request('modalidad') == 'virtual' ? 'selected' : '' }}>Virtual</option>
                                    <option value="hibrido" {{ request('modalidad') == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Mes</label>
                                <select name="mes" class="form-control">
                                    <option value="">Todos</option>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('mes') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info"><i class="mdi mdi-filter"></i> Filtrar</button>
                                    <a href="{{ route('extranet.eventos.index') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Limpiar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de eventos -->
    <div class="row">
        @forelse($eventos as $evento)
        <div class="col-lg-4 col-md-6">
            <div class="card" style="border-left: 4px solid {{ $evento->color }};">
                @if($evento->imagen_url)
                <img class="card-img-top img-responsive" src="{{ $evento->imagen_url }}" alt="{{ $evento->titulo }}" style="max-height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-2">
                        <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $evento->tipo)) }}</span>
                        <span class="badge badge-{{ $evento->modalidad == 'presencial' ? 'success' : ($evento->modalidad == 'virtual' ? 'info' : 'warning') }}">
                            {{ ucfirst($evento->modalidad) }}
                        </span>
                        @if($evento->requiere_confirmacion)
                        <span class="badge badge-dark">
                            <i class="mdi mdi-account-check"></i> {{ $evento->asistentes->where('estado_confirmacion', 'confirmado')->count() }}
                            @if($evento->cupo_maximo)
                            / {{ $evento->cupo_maximo }}
                            @endif
                        </span>
                        @endif
                    </div>

                    <h5 class="card-title">{{ $evento->titulo }}</h5>

                    <!-- Fecha y hora -->
                    <p class="text-muted mb-2">
                        <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                        <br>
                        <i class="mdi mdi-clock"></i> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}
                        @if($evento->hora_fin)
                        - {{ $evento->hora_fin }}
                        @endif
                    </p>

                    <!-- Lugar/Link -->
                    @if($evento->modalidad === 'presencial' && $evento->lugar)
                    <p class="text-muted mb-2">
                        <i class="mdi mdi-map-marker"></i> {{ $evento->lugar }}
                    </p>
                    @elseif($evento->modalidad === 'virtual')
                    <p class="text-muted mb-2">
                        <i class="mdi mdi-video"></i> Evento virtual
                    </p>
                    @endif

                    <!-- Organizador -->
                    <p class="text-muted small mb-3">
                        <i class="mdi mdi-account"></i> Organiza: {{ $evento->organizador->EMP_NOMBRES ?? 'Sin organizador' }}
                    </p>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('extranet.eventos.show', $evento->id) }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-eye"></i> Ver Detalle
                        </a>

                        @can('editar-evento')
                        <div class="btn-group">
                            <a href="{{ route('extranet.eventos.edit', $evento->id) }}" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            @can('eliminar-evento')
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Eliminar este evento?')) { document.getElementById('delete-form-{{ $evento->id }}').submit(); }">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            <form id="delete-form-{{ $evento->id }}" action="{{ route('extranet.eventos.destroy', $evento->id) }}" method="POST" style="display: none;">
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
                    <i class="mdi mdi-calendar-remove mdi-48px text-muted"></i>
                    <p class="text-muted mt-3">No se encontraron eventos</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($eventos->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $eventos->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
