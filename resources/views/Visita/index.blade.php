@extends('layouts.main')

@section('main')
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Registro de Visitas</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Registro de Visitas</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Visita">
            <i class="mdi mdi-plus-circle"></i> Registrar Ingreso
        </button>
        <div class="dropdown float-right mr-2 hidden-sm-down">
            <button class="btn float-right hidden-sm-down btn-info" data-toggle="modal" data-target="#Generar_reportes">
                <i class="mdi mdi-file-excel"></i> Generar Reporte
            </button>
        </div>
    </div>
</div>

<!-- Badge de visitas activas -->
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong><i class="mdi mdi-account-multiple"></i> Visitas Activas:</strong>
            <span class="badge badge-pill badge-primary" style="font-size: 14px;">{{ $visitasActivas }}</span>
            personas actualmente en las instalaciones
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Filtros de búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('Visita.index') }}" class="form-inline">
                            <div class="form-group mr-2 mb-2">
                                <input type="text" class="form-control" name="buscar"
                                       placeholder="Buscar por nombre, cédula, empresa..."
                                       value="{{ request('buscar') }}" style="width: 300px;">
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <select name="estado" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="activos" {{ request('estado') == 'activos' ? 'selected' : '' }}>
                                        En Instalaciones
                                    </option>
                                    <option value="finalizados" {{ request('estado') == 'finalizados' ? 'selected' : '' }}>
                                        Finalizados
                                    </option>
                                </select>
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <input type="date" class="form-control" name="fecha_desde"
                                       value="{{ request('fecha_desde') }}" placeholder="Desde">
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <input type="date" class="form-control" name="fecha_hasta"
                                       value="{{ request('fecha_hasta') }}" placeholder="Hasta">
                            </div>

                            <button type="submit" class="btn btn-primary mr-2 mb-2">
                                <i class="mdi mdi-magnify"></i> Buscar
                            </button>

                            @if(request()->hasAny(['buscar', 'estado', 'fecha_desde', 'fecha_hasta']))
                                <a href="{{ route('Visita.index') }}" class="btn btn-secondary mb-2">
                                    <i class="mdi mdi-refresh"></i> Limpiar
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Mensajes de alerta -->
                @if(session('rgcmessage'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-check-circle"></i> Éxito!</strong> {{ session('rgcmessage') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('msjerror'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-alert-circle"></i> Error!</strong> {{ session('msjerror') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Tabla de registros -->
                <div class="table-responsive">
                    <table class="table table-hover no-wrap" id="table_visitas">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Fecha/Hora Ingreso</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Empresa</th>
                                <th>Motivo de Ingreso</th>
                                <th>Fecha/Hora Salida</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($registros as $registro)
                                <tr>
                                    <td>
                                        @if($registro->estaActiva())
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-account-check"></i> En Instalaciones
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="mdi mdi-check-circle"></i> Finalizado
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $registro->REG_NOMBRE }}</strong>
                                        @if($registro->REG_EQUIPO)
                                            <br><small class="text-muted">
                                                <i class="mdi mdi-laptop"></i> {{ $registro->REG_EQUIPO }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $registro->REG_TIPO_ID }}: {{ $registro->REG_CEDULA }}
                                    </td>
                                    <td>{{ $registro->REG_EMPRESA }}</td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($registro->REG_MOTIVO_INGRESO, 50) }}</span>
                                    </td>
                                    <td>
                                        @if($registro->REG_FECHA_HORA_SALIDA)
                                            {{ \Carbon\Carbon::parse($registro->REG_FECHA_HORA_SALIDA)->format('d/m/Y H:i') }}
                                            <br>
                                            <small class="text-success">
                                                <i class="mdi mdi-clock"></i>
                                                Duración: {{ \Carbon\Carbon::parse($registro->created_at)->diffForHumans(\Carbon\Carbon::parse($registro->REG_FECHA_HORA_SALIDA), true) }}
                                            </small>
                                        @else
                                            <span class="text-warning">
                                                <i class="mdi mdi-clock-alert"></i> En instalaciones
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($registro->estaActiva())
                                            <form action="{{ route('Visita.exit', $registro->REG_ID) }}" method="POST"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Confirmar salida de {{ $registro->REG_NOMBRE }}?')"
                                                        title="Registrar salida">
                                                    <i class="mdi mdi-exit-to-app"></i> Registrar Salida
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                <i class="mdi mdi-check"></i> Finalizado
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="mdi mdi-alert-circle" style="font-size: 48px;"></i>
                                        <p>No se encontraron registros</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Mostrando {{ $registros->firstItem() ?? 0 }} a {{ $registros->lastItem() ?? 0 }}
                            de {{ $registros->total() }} registros
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            {{ $registros->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Visita.create')
@include('Visita.reportes')

@endsection

@section('scripts')
<script>
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection
