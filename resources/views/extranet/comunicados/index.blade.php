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
                            <h4 class="card-title mb-0"><i class="mdi mdi-bullhorn"></i> Comunicados Internos</h4>
                            <h6 class="card-subtitle">Anuncios oficiales de la empresa</h6>
                        </div>
                        @can('crear-comunicado')
                        <a href="{{ route('extranet.comunicados.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nuevo Comunicado
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
                    <form method="GET" action="{{ route('extranet.comunicados.index') }}" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Buscar</label>
                                <input type="text" name="buscar" class="form-control" placeholder="Título o contenido..." value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="general" {{ request('tipo') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="urgente" {{ request('tipo') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                    <option value="rh" {{ request('tipo') == 'rh' ? 'selected' : '' }}>RH</option>
                                    <option value="ti" {{ request('tipo') == 'ti' ? 'selected' : '' }}>TI</option>
                                    <option value="operaciones" {{ request('tipo') == 'operaciones' ? 'selected' : '' }}>Operaciones</option>
                                    <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
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
                                <label>Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="">Publicados</option>
                                    <option value="borrador" {{ request('estado') == 'borrador' ? 'selected' : '' }}>Borradores</option>
                                    <option value="archivado" {{ request('estado') == 'archivado' ? 'selected' : '' }}>Archivados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info"><i class="mdi mdi-filter"></i> Filtrar</button>
                                    <a href="{{ route('extranet.comunicados.index') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Limpiar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de comunicados -->
    <div class="row">
        @forelse($comunicados as $comunicado)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                @if($comunicado->imagen_url)
                <img class="card-img-top img-responsive" src="{{ $comunicado->imagen_url }}" alt="{{ $comunicado->titulo }}" style="max-height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            @if($comunicado->fijado)
                            <span class="badge badge-warning"><i class="mdi mdi-pin"></i> Fijado</span>
                            @endif
                            <span class="badge badge-{{ $comunicado->prioridad == 'critica' ? 'danger' : ($comunicado->prioridad == 'alta' ? 'warning' : ($comunicado->prioridad == 'media' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($comunicado->prioridad) }}
                            </span>
                            <span class="badge badge-primary">{{ ucfirst($comunicado->tipo) }}</span>
                        </div>
                        @can('fijar-comunicado')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('extranet.comunicados.show', $comunicado->id) }}">
                                    <i class="mdi mdi-eye"></i> Ver
                                </a>
                                @can('editar-comunicado')
                                <a class="dropdown-item" href="{{ route('extranet.comunicados.edit', $comunicado->id) }}">
                                    <i class="mdi mdi-pencil"></i> Editar
                                </a>
                                @endcan
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('fijar-form-{{ $comunicado->id }}').submit();">
                                    <i class="mdi mdi-pin"></i> {{ $comunicado->fijado ? 'Desfijar' : 'Fijar' }}
                                </a>
                                <form id="fijar-form-{{ $comunicado->id }}" action="{{ route('extranet.comunicados.fijar', $comunicado->id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                @can('eliminar-comunicado')
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" onclick="if(confirm('¿Estás seguro de eliminar este comunicado?')) { event.preventDefault(); document.getElementById('delete-form-{{ $comunicado->id }}').submit(); }">
                                    <i class="mdi mdi-delete"></i> Eliminar
                                </a>
                                <form id="delete-form-{{ $comunicado->id }}" action="{{ route('extranet.comunicados.destroy', $comunicado->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </div>
                        </div>
                        @endcan
                    </div>

                    <h5 class="card-title">{{ $comunicado->titulo }}</h5>
                    <p class="card-text text-muted small">
                        <i class="mdi mdi-account"></i> {{ $comunicado->autor->name ?? 'Sistema' }}
                        <br>
                        <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($comunicado->fecha_inicio)->format('d/m/Y') }}
                        @if($comunicado->fecha_fin)
                        - {{ \Carbon\Carbon::parse($comunicado->fecha_fin)->format('d/m/Y') }}
                        @endif
                        <br>
                        <i class="mdi mdi-eye"></i> {{ $comunicado->vistas }} vistas
                    </p>

                    <p class="card-text">
                        {{ Str::limit(strip_tags($comunicado->contenido), 150) }}
                    </p>

                    <a href="{{ route('extranet.comunicados.show', $comunicado->id) }}" class="btn btn-primary btn-block">
                        <i class="mdi mdi-arrow-right"></i> Leer más
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-bullhorn-outline mdi-48px text-muted"></i>
                    <p class="text-muted mt-3">No se encontraron comunicados</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($comunicados->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $comunicados->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
