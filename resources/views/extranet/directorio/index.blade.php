@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="mdi mdi-account-group"></i> Directorio de Empleados</h3>
            </div>

            <!-- Buscador y Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('extranet.directorio.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="buscar" class="form-control"
                                           placeholder="Buscar por nombre, cédula o email..."
                                           value="{{ request('buscar') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="departamento" class="form-control">
                                        <option value="">Todos los departamentos</option>
                                        @foreach($departamentos as $dep)
                                        <option value="{{ $dep->DEP_ID }}" {{ request('departamento') == $dep->DEP_ID ? 'selected' : '' }}>
                                            {{ $dep->DEP_DESCRIPCION }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="cargo" class="form-control">
                                        <option value="">Todos los cargos</option>
                                        @foreach($cargos as $cargo)
                                        <option value="{{ $cargo->CAR_ID }}" {{ request('cargo') == $cargo->CAR_ID ? 'selected' : '' }}>
                                            {{ $cargo->CAR_DESCRIPCION }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="mdi mdi-magnify"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(request()->hasAny(['buscar', 'departamento', 'cargo']))
                    <div class="mt-2">
                        <a href="{{ route('extranet.directorio.index') }}" class="btn btn-sm btn-secondary">
                            <i class="mdi mdi-close"></i> Limpiar filtros
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h4 class="mb-0">{{ $empleados->total() }}</h4>
                            <p class="mb-0">Empleados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h4 class="mb-0">{{ $departamentos->count() }}</h4>
                            <p class="mb-0">Departamentos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h4 class="mb-0">{{ $cargos->count() }}</h4>
                            <p class="mb-0">Cargos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h4 class="mb-0">{{ \App\Models\empleado::where('EMP_ACTIVO', 1)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30))->count() }}</h4>
                            <p class="mb-0">Nuevos (30 días)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Empleados -->
            <div class="row">
                @forelse($empleados as $empleado)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 empleado-card">
                        <div class="card-body text-center">
                            <!-- Foto -->
                            <div class="mb-3">
                                @if($empleado->EMP_FOTO_URL)
                                <img src="{{ $empleado->EMP_FOTO_URL }}" alt="{{ $empleado->nombre_completo }}"
                                     class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="mdi mdi-account mdi-48px text-white"></i>
                                </div>
                                @endif
                            </div>

                            <!-- Nombre -->
                            <h5 class="mb-1">{{ $empleado->nombre_completo }}</h5>

                            <!-- Cargo -->
                            @if($empleado->cargo)
                            <p class="text-muted mb-1">
                                <i class="mdi mdi-briefcase"></i> {{ $empleado->cargo->CAR_DESCRIPCION }}
                            </p>
                            @endif

                            <!-- Departamento -->
                            @if($empleado->departamento)
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-office-building"></i> {{ $empleado->departamento->DEP_DESCRIPCION }}
                            </p>
                            @endif

                            <!-- Contacto -->
                            <div class="mb-3">
                                @if($empleado->EMP_EMAIL)
                                <small class="d-block">
                                    <i class="mdi mdi-email"></i> {{ $empleado->EMP_EMAIL }}
                                </small>
                                @endif
                                @if($empleado->EMP_TELEFONO)
                                <small class="d-block">
                                    <i class="mdi mdi-phone"></i> {{ $empleado->EMP_TELEFONO }}
                                </small>
                                @endif
                            </div>

                            <!-- Botón Ver Perfil -->
                            <a href="{{ route('extranet.directorio.show', $empleado->EMP_ID) }}"
                               class="btn btn-sm btn-primary btn-block">
                                <i class="mdi mdi-account-card-details"></i> Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Sin Resultados -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-account-search mdi-72px text-muted"></i>
                            <h5 class="mt-3">No se encontraron empleados</h5>
                            <p class="text-muted">Intenta con otros filtros de búsqueda.</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $empleados->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.empleado-card {
    transition: all 0.3s ease;
}

.empleado-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}
</style>
@endsection
