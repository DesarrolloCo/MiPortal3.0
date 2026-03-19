@extends('layouts.main')

@section('main')
<!-- Breadcrumb -->
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Dashboard de Inventario</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard Inventario</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center text-right">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-file-excel"></i> Exportar
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('Inventario.exportar.equipos') }}">
                    <i class="mdi mdi-desktop-mac"></i> Equipos
                </a>
                <a class="dropdown-item" href="{{ route('Inventario.exportar.asignaciones') }}">
                    <i class="mdi mdi-account-check"></i> Asignaciones Activas
                </a>
                <a class="dropdown-item" href="{{ route('Inventario.exportar.todas_asignaciones') }}">
                    <i class="mdi mdi-view-list"></i> Todas las Asignaciones
                </a>
                <a class="dropdown-item" href="{{ route('Inventario.exportar.devoluciones') }}">
                    <i class="mdi mdi-keyboard-return"></i> Devoluciones
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alertas de Mantenimiento -->
@if($mantenimientosVencidos->count() > 0 || $mantenimientosUrgentes->count() > 0)
<div class="row">
    <div class="col-12">
        <!-- Mantenimientos Vencidos -->
        @if($mantenimientosVencidos->count() > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading"><i class="mdi mdi-alert-circle"></i> ¡Mantenimientos Vencidos!</h4>
            <p>Hay <strong>{{ $mantenimientosVencidos->count() }}</strong> mantenimiento(s) que debieron realizarse y aún están pendientes:</p>
            <hr>
            <ul class="mb-0">
                @foreach($mantenimientosVencidos as $mantenimiento)
                <li>
                    <strong>{{ $mantenimiento->equipo->EQU_NOMBRE }}</strong> ({{ $mantenimiento->equipo->EQU_SERIAL ?? 'N/A' }})
                    - Tipo: {{ $mantenimiento->MAN_TIPO }}
                    - Debía realizarse: {{ $mantenimiento->MAN_FECHA_AGENDADA->format('d/m/Y') }}
                    <span class="badge badge-danger">Vencido hace {{ abs($mantenimiento->diasRestantes()) }} días</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Mantenimientos Urgentes (próximos 3 días) -->
        @if($mantenimientosUrgentes->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading"><i class="mdi mdi-clock-alert"></i> Mantenimientos Urgentes</h4>
            <p>Hay <strong>{{ $mantenimientosUrgentes->count() }}</strong> mantenimiento(s) programado(s) para los próximos 3 días:</p>
            <hr>
            <ul class="mb-0">
                @foreach($mantenimientosUrgentes as $mantenimiento)
                <li>
                    <strong>{{ $mantenimiento->equipo->EQU_NOMBRE }}</strong> ({{ $mantenimiento->equipo->EQU_SERIAL ?? 'N/A' }})
                    - Tipo: {{ $mantenimiento->MAN_TIPO }}
                    - Fecha: {{ $mantenimiento->MAN_FECHA_AGENDADA->format('d/m/Y') }}
                    <span class="badge badge-warning">En {{ $mantenimiento->diasRestantes() }} días</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Tarjetas de Estadísticas Principales -->
<div class="row">
    <!-- Total Equipos -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <div class="m-r-20 align-self-center">
                        <span class="lstick m-r-20"></span>
                        <i class="mdi mdi-desktop-mac display-5 text-info"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="m-t-0">{{ $totalEquipos }}</h2>
                        <h6 class="text-muted m-b-0">Total Equipos</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipos Asignados -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <div class="m-r-20 align-self-center">
                        <i class="mdi mdi-account-check display-5 text-success"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="m-t-0">{{ $equiposAsignados }}</h2>
                        <h6 class="text-muted m-b-0">Equipos Asignados</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipos Disponibles -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <div class="m-r-20 align-self-center">
                        <i class="mdi mdi-check-circle display-5 text-warning"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="m-t-0">{{ $equiposDisponibles }}</h2>
                        <h6 class="text-muted m-b-0">Equipos Disponibles</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valor Total -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex no-block">
                    <div class="m-r-20 align-self-center">
                        <i class="mdi mdi-cash display-5 text-primary"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="m-t-0">${{ number_format($valorTotal, 0, ',', '.') }}</h2>
                        <h6 class="text-muted m-b-0">Valor Total</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Segunda Fila de Estadísticas -->
<div class="row">
    <!-- Equipos Propios vs Alquilados -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Equipos Propios</h5>
                <div class="d-flex align-items-center">
                    <h2 class="text-success m-b-0">{{ $equiposPropios }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Equipos Alquilados</h5>
                <div class="d-flex align-items-center">
                    <h2 class="text-danger m-b-0">{{ $equiposAlquilados }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de Devoluciones -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Estado de Equipos Devueltos</h5>
                <div class="row">
                    <div class="col-4 text-center">
                        <h3 class="text-success">{{ $devolucionesEsteBueno }}</h3>
                        <small>Bueno</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-warning">{{ $devolucionesEstadoRegular }}</h3>
                        <small>Regular</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-danger">{{ $devolucionesEstadoMalo }}</h3>
                        <small>Malo</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos y Tablas -->
<div class="row">
    <!-- Equipos por Área -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Equipos por Área (Top 5)</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Área</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equiposPorArea as $area)
                            <tr>
                                <td>{{ $area->area }}</td>
                                <td class="text-right"><strong>{{ $area->total }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Mantenimientos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Próximos Mantenimientos</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proximosMantenimientos as $mantenimiento)
                            <tr>
                                <td>{{ $mantenimiento->EQU_NOMBRE }}</td>
                                <td><span class="badge badge-info">{{ $mantenimiento->MAN_TIPO }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($mantenimiento->MAN_FECHA_AGENDADA)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay mantenimientos programados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Asignaciones y Devoluciones Recientes -->
<div class="row">
    <!-- Asignaciones Recientes -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Asignaciones Recientes</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Empleado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignacionesRecientes as $asignacion)
                            <tr>
                                <td>
                                    <strong>{{ $asignacion->EQU_NOMBRE }}</strong><br>
                                    <small class="text-muted">{{ $asignacion->EQU_SERIAL }}</small>
                                </td>
                                <td>{{ $asignacion->EMP_NOMBRES }}</td>
                                <td>{{ \Carbon\Carbon::parse($asignacion->EAS_FECHA_ENTREGA)->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Devoluciones Recientes -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Devoluciones Recientes</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Empleado</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devolucionesRecientes as $devolucion)
                            <tr>
                                <td>
                                    <strong>{{ $devolucion->EQU_NOMBRE }}</strong><br>
                                    <small class="text-muted">{{ $devolucion->EQU_SERIAL }}</small>
                                </td>
                                <td>{{ $devolucion->EMP_NOMBRES }}</td>
                                <td>
                                    @if($devolucion->DEV_ESTADO_EQUIPO == 'Bueno')
                                        <span class="badge badge-success">{{ $devolucion->DEV_ESTADO_EQUIPO }}</span>
                                    @elseif($devolucion->DEV_ESTADO_EQUIPO == 'Regular')
                                        <span class="badge badge-warning">{{ $devolucion->DEV_ESTADO_EQUIPO }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $devolucion->DEV_ESTADO_EQUIPO }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($devolucion->DEV_FECHA_DEVOLUCION)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay devoluciones registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
