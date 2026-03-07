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
                            <h4 class="card-title mb-0"><i class="mdi mdi-trophy-award"></i> Muro de Reconocimientos</h4>
                            <h6 class="card-subtitle">Celebrando el éxito de nuestro equipo</h6>
                        </div>
                        <div>
                            @can('crear-reconocimiento')
                            <a href="{{ route('extranet.reconocimientos.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Nuevo Reconocimiento
                            </a>
                            @endcan
                            <a href="{{ route('extranet.reconocimientos.empleado-mes') }}" class="btn btn-warning">
                                <i class="mdi mdi-star"></i> Empleado del Mes
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
                    <form method="GET" action="{{ route('extranet.reconocimientos.index') }}" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Buscar</label>
                                <input type="text" name="buscar" class="form-control" placeholder="Título o descripción..." value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="empleado_mes" {{ request('tipo') == 'empleado_mes' ? 'selected' : '' }}>Empleado del Mes</option>
                                    <option value="aniversario" {{ request('tipo') == 'aniversario' ? 'selected' : '' }}>Aniversario</option>
                                    <option value="logro" {{ request('tipo') == 'logro' ? 'selected' : '' }}>Logro</option>
                                    <option value="excelencia" {{ request('tipo') == 'excelencia' ? 'selected' : '' }}>Excelencia</option>
                                    <option value="innovacion" {{ request('tipo') == 'innovacion' ? 'selected' : '' }}>Innovación</option>
                                    <option value="trabajo_equipo" {{ request('tipo') == 'trabajo_equipo' ? 'selected' : '' }}>Trabajo en Equipo</option>
                                    <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Empleado</label>
                                <select name="empleado_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach($empleados as $emp)
                                    <option value="{{ $emp->EMP_ID }}" {{ request('empleado_id') == $emp->EMP_ID ? 'selected' : '' }}>
                                        {{ $emp->EMP_NOMBRES }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Año</label>
                                <select name="ano" class="form-control">
                                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ request('ano', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info"><i class="mdi mdi-filter"></i> Filtrar</button>
                                    <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-secondary"><i class="mdi mdi-refresh"></i> Limpiar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Muro de reconocimientos -->
    <div class="row">
        @forelse($reconocimientos as $reconocimiento)
        <div class="col-lg-4 col-md-6">
            <div class="card {{ $reconocimiento->destacado ? 'border-warning' : '' }}">
                @if($reconocimiento->imagen_url)
                <img class="card-img-top" src="{{ $reconocimiento->imagen_url }}" alt="{{ $reconocimiento->titulo }}" style="max-height: 200px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-2">
                        @if($reconocimiento->destacado)
                        <span class="badge badge-warning"><i class="mdi mdi-star"></i> Destacado</span>
                        @endif
                        <span class="badge badge-{{ $reconocimiento->tipo == 'empleado_mes' ? 'success' : 'primary' }}">
                            {{ ucfirst(str_replace('_', ' ', $reconocimiento->tipo)) }}
                        </span>
                    </div>

                    <!-- Empleado reconocido -->
                    <div class="d-flex align-items-center mb-3">
                        @if($reconocimiento->empleado->EMP_FOTO_URL)
                        <img src="{{ $reconocimiento->empleado->EMP_FOTO_URL }}" alt="{{ $reconocimiento->empleado->EMP_NOMBRES }}"
                             class="rounded-circle mr-2" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mr-2"
                             style="width: 50px; height: 50px; font-size: 20px;">
                            {{ substr($reconocimiento->empleado->EMP_NOMBRES, 0, 1) }}
                        </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $reconocimiento->empleado->EMP_NOMBRES }}</h6>
                            <small class="text-muted">{{ $reconocimiento->empleado->cargo->CAR_NOMBRE ?? '' }}</small>
                        </div>
                    </div>

                    <!-- Título y descripción -->
                    <h5 class="card-title">{{ $reconocimiento->titulo }}</h5>
                    <p class="card-text">{{ Str::limit($reconocimiento->descripcion, 120) }}</p>

                    <!-- Metadata -->
                    <div class="text-muted small mb-3">
                        <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($reconocimiento->fecha)->format('d/m/Y') }}
                        <br>
                        <i class="mdi mdi-account"></i> Otorgado por {{ $reconocimiento->otorgadoPor->name ?? 'Sistema' }}
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('extranet.reconocimientos.show', $reconocimiento->id) }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-eye"></i> Ver Detalle
                        </a>

                        @can('editar-reconocimiento')
                        <div class="btn-group">
                            <a href="{{ route('extranet.reconocimientos.edit', $reconocimiento->id) }}" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            @can('eliminar-reconocimiento')
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Eliminar este reconocimiento?')) { document.getElementById('delete-form-{{ $reconocimiento->id }}').submit(); }">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            <form id="delete-form-{{ $reconocimiento->id }}" action="{{ route('extranet.reconocimientos.destroy', $reconocimiento->id) }}" method="POST" style="display: none;">
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
                    <i class="mdi mdi-trophy-outline mdi-48px text-muted"></i>
                    <p class="text-muted mt-3">No se encontraron reconocimientos</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($reconocimientos->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $reconocimientos->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
