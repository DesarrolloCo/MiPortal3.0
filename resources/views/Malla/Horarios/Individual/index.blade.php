@extends('layouts.main')

@section('main')
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-account-clock"></i> Horarios Individuales
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Horarios Individuales</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <div class="d-flex justify-content-end">
                            <span class="badge badge-info p-2">
                                <i class="mdi mdi-account-multiple"></i> {{ $empleados->total() }} Empleados
                            </span>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3">
                                    <i class="mdi mdi-filter-variant"></i> Filtros de Búsqueda
                                </h4>

                                <!-- Filtros de búsqueda -->
                                <form method="GET" action="{{ route('Individual.index') }}" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label class="text-muted mb-1">
                                                    <i class="mdi mdi-magnify"></i> Buscar
                                                </label>
                                                <input type="text" class="form-control" name="buscar"
                                                       placeholder="Nombre, cédula o código..."
                                                       value="{{ request('buscar') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-muted mb-1">
                                                    <i class="mdi mdi-folder-outline"></i> Campaña
                                                </label>
                                                <select name="campana" class="form-control">
                                                    <option value="">Todas las campañas</option>
                                                    @foreach($campanas as $cam)
                                                        <option value="{{ $cam->CAM_ID }}"
                                                            {{ request('campana') == $cam->CAM_ID ? 'selected' : '' }}>
                                                            {{ $cam->CAM_NOMBRE }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-muted mb-1">
                                                    <i class="mdi mdi-briefcase"></i> Cargo
                                                </label>
                                                <select name="cargo" class="form-control">
                                                    <option value="">Todos los cargos</option>
                                                    @foreach($cargos as $car)
                                                        <option value="{{ $car->CAR_ID }}"
                                                            {{ request('cargo') == $car->CAR_ID ? 'selected' : '' }}>
                                                            {{ $car->CAR_NOMBRE }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label class="text-muted mb-1">&nbsp;</label>
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary btn-block mr-1">
                                                    <i class="mdi mdi-magnify"></i> Buscar
                                                </button>
                                                @if(request()->hasAny(['buscar', 'campana', 'cargo']))
                                                    <a href="{{ route('Individual.index') }}"
                                                       class="btn btn-secondary"
                                                       title="Limpiar filtros">
                                                        <i class="mdi mdi-refresh"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Tabla de empleados -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="mdi mdi-account"></i> Empleado</th>
                                                <th><i class="mdi mdi-card-account-details"></i> Cédula</th>
                                                <th><i class="mdi mdi-barcode"></i> Código</th>
                                                <th><i class="mdi mdi-briefcase"></i> Cargo</th>
                                                <th><i class="mdi mdi-folder"></i> Campaña</th>
                                                <th class="text-center"><i class="mdi mdi-cog"></i> Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($empleados as $list)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $list->EMP_NOMBRES }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $list->EMP_CEDULA }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">{{ $list->EMP_CODE }}</span>
                                                    </td>
                                                    <td>
                                                        <small>{{ $list->cargo->CAR_NOMBRE ?? 'Sin cargo' }}</small>
                                                    </td>
                                                    <td>
                                                        @if($list->campana)
                                                            <span class="badge badge-primary">
                                                                {{ $list->campana->CAM_NOMBRE }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Sin campaña</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('Individual.employee_hours', $list->EMP_ID) }}"
                                                           class="btn btn-primary btn-sm"
                                                           title="Gestionar horarios">
                                                            <i class="mdi mdi-calendar-clock"></i> Ver Horarios
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-5">
                                                        <i class="mdi mdi-account-off" style="font-size: 48px;"></i>
                                                        <p class="mt-2">
                                                            @if(request()->hasAny(['buscar', 'campana', 'cargo']))
                                                                No se encontraron empleados con los filtros seleccionados
                                                            @else
                                                                No hay empleados activos registrados
                                                            @endif
                                                        </p>
                                                        @if(request()->hasAny(['buscar', 'campana', 'cargo']))
                                                            <a href="{{ route('Individual.index') }}" class="btn btn-secondary mt-2">
                                                                <i class="mdi mdi-refresh"></i> Limpiar Filtros
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                @if($empleados->hasPages())
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            Mostrando {{ $empleados->firstItem() ?? 0 }} a {{ $empleados->lastItem() ?? 0 }}
                                            de {{ $empleados->total() }} empleados
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-right">
                                            {{ $empleados->links() }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Limpiar input de búsqueda con ESC
        $('input[name="buscar"]').on('keydown', function(e) {
            if (e.key === 'Escape') {
                $(this).val('');
            }
        });

        // Auto-submit en cambio de selects
        $('select[name="campana"], select[name="cargo"]').on('change', function() {
            // Opcional: auto-submit del formulario al cambiar filtros
            // $(this).closest('form').submit();
        });

        // Highlight de fila al hover
        $('.table tbody tr').hover(
            function() {
                $(this).addClass('table-active');
            },
            function() {
                $(this).removeClass('table-active');
            }
        );
    });
</script>
@endsection
