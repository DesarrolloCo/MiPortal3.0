@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            @include('extranet.partials.flash-messages')

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.reconocimientos.index') }}">Reconocimientos</a></li>
                    <li class="breadcrumb-item active">Estadísticas</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-chart-bar text-primary"></i>
                                Estadísticas de Reconocimientos
                            </h4>
                            <h6 class="card-subtitle">Análisis y métricas del año {{ $ano }}</h6>
                        </div>
                        <div>
                            <form method="GET" action="{{ route('extranet.reconocimientos.estadisticas') }}" class="form-inline">
                                <label for="ano" class="mr-2">Año:</label>
                                <select name="ano" id="ano" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                    @for($i = \Carbon\Carbon::now()->year; $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3 class="mb-0">{{ $stats['total_ano'] }}</h3>
                            <small>Total Reconocimientos {{ $ano }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h3 class="mb-0">{{ $stats['por_tipo']->count() }}</h3>
                            <small>Tipos de Reconocimiento</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h3 class="mb-0">{{ $stats['top_empleados']->count() }}</h3>
                            <small>Empleados Reconocidos</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h3 class="mb-0">{{ round($stats['total_ano'] / 12, 1) }}</h3>
                            <small>Promedio Mensual</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Reconocimientos por Tipo -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="mdi mdi-chart-pie"></i> Reconocimientos por Tipo
                            </h5>

                            @if($stats['por_tipo']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['por_tipo'] as $tipo)
                                        <tr>
                                            <td>
                                                @switch($tipo->tipo)
                                                    @case('excelencia')
                                                        <span class="badge badge-success">Excelencia</span>
                                                        @break
                                                    @case('innovacion')
                                                        <span class="badge badge-info">Innovación</span>
                                                        @break
                                                    @case('trabajo_equipo')
                                                        <span class="badge badge-primary">Trabajo en Equipo</span>
                                                        @break
                                                    @case('liderazgo')
                                                        <span class="badge badge-warning">Liderazgo</span>
                                                        @break
                                                    @case('empleado_mes')
                                                        <span class="badge badge-danger">Empleado del Mes</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ ucfirst($tipo->tipo) }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-right">
                                                <strong>{{ $tipo->total }}</strong>
                                            </td>
                                            <td class="text-right">
                                                {{ round(($tipo->total / $stats['total_ano']) * 100, 1) }}%
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted text-center py-3">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reconocimientos por Mes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="mdi mdi-chart-line"></i> Reconocimientos por Mes
                            </h5>

                            @if($stats['por_mes']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mes</th>
                                            <th class="text-right">Cantidad</th>
                                            <th style="width: 40%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $maxMes = $stats['por_mes']->max('total');
                                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                        @endphp
                                        @foreach($stats['por_mes'] as $mes)
                                        <tr>
                                            <td>{{ $meses[$mes->mes] }}</td>
                                            <td class="text-right"><strong>{{ $mes->total }}</strong></td>
                                            <td>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary"
                                                         style="width: {{ ($mes->total / $maxMes) * 100 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted text-center py-3">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Empleados Reconocidos -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="mdi mdi-trophy"></i> Top 10 Empleados Más Reconocidos
                            </h5>

                            @if($stats['top_empleados']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Posición</th>
                                            <th>Empleado</th>
                                            <th>Cargo</th>
                                            <th class="text-right">Reconocimientos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['top_empleados'] as $index => $item)
                                        <tr>
                                            <td>
                                                @if($index == 0)
                                                    <span class="badge badge-warning badge-pill">
                                                        <i class="mdi mdi-trophy"></i> #1
                                                    </span>
                                                @elseif($index == 1)
                                                    <span class="badge badge-secondary badge-pill">#2</span>
                                                @elseif($index == 2)
                                                    <span class="badge badge-info badge-pill">#3</span>
                                                @else
                                                    <span class="text-muted">#{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->empleado->EMP_FOTO_URL)
                                                    <img src="{{ $item->empleado->EMP_FOTO_URL }}"
                                                         alt="{{ $item->empleado->EMP_NOMBRES }}"
                                                         class="rounded-circle mr-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mr-2"
                                                         style="width: 40px; height: 40px; font-size: 16px;">
                                                        {{ substr($item->empleado->EMP_NOMBRES, 0, 1) }}{{ substr($item->empleado->EMP_APELLIDOS, 0, 1) }}
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->empleado->EMP_NOMBRES }} {{ $item->empleado->EMP_APELLIDOS }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->empleado->cargo)
                                                    {{ $item->empleado->cargo->CAR_NOMBRE }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <strong class="text-primary">{{ $item->total }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted text-center py-3">No hay datos disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-center mt-4">
                <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Volver a Reconocimientos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
