@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-clipboard-text"></i> Detalles del Mantenimiento
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Mantenimiento.index') }}">Mantenimientos</a></li>
                            <li class="breadcrumb-item active">Detalles</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <a href="{{ route('Mantenimiento.reporte', $mantenimiento->MAN_ID) }}"
                           class="btn btn-success float-right hidden-sm-down">
                            <i class="mdi mdi-file-pdf"></i> Descargar Reporte PDF
                        </a>
                        <a href="{{ route('Mantenimiento.index') }}"
                           class="btn btn-secondary float-right mr-2 hidden-sm-down">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Información del Equipo -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <i class="mdi mdi-laptop"></i> Información del Equipo
                                </h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-tag"></i> Equipo:</strong><br>
                                        <span class="text-muted">{{ $mantenimiento->EQU_NOMBRE }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-barcode"></i> Serial:</strong><br>
                                        <code>{{ $mantenimiento->EQU_SERIAL }}</code>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-office-building"></i> Área:</strong><br>
                                        <span class="badge badge-light border px-2 py-1">{{ $mantenimiento->ARE_NOMBRE }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-calendar"></i> Fecha Programada:</strong><br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($mantenimiento->MAN_FECHA)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-domain"></i> Proveedor:</strong><br>
                                        <span class="text-muted">{{ $mantenimiento->MAN_PROVEEDOR }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-account-wrench"></i> Técnico Responsable:</strong><br>
                                        <span class="text-muted">{{ $mantenimiento->TECNICO_NOMBRE }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="mdi mdi-information"></i> Estado:</strong><br>
                                        @if($mantenimiento->MAN_STATUS == 1)
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="mdi mdi-clock"></i> Pendiente
                                            </span>
                                        @elseif($mantenimiento->MAN_STATUS == 2)
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="mdi mdi-check-circle"></i> Completado
                                            </span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">
                                                <i class="mdi mdi-help-circle"></i> Desconocido
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividades Realizadas -->
                @if($man_asignados->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <i class="mdi mdi-clipboard-check"></i> Actividades Realizadas
                                </h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="20%">Tipo de Mantenimiento</th>
                                                <th>Actividades Realizadas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($man_asignados as $man_asignado)
                                            <tr>
                                                <td>
                                                    @if($man_asignado->MAS_TIPO == 'Preventivo')
                                                        <span class="badge badge-success px-2 py-1">
                                                            <i class="mdi mdi-shield-check"></i> {{ $man_asignado->MAS_TIPO }}
                                                        </span>
                                                    @elseif($man_asignado->MAS_TIPO == 'Correctivo')
                                                        <span class="badge badge-warning px-2 py-1">
                                                            <i class="mdi mdi-wrench"></i> {{ $man_asignado->MAS_TIPO }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-info px-2 py-1">
                                                            <i class="mdi mdi-domain"></i> {{ $man_asignado->MAS_TIPO }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div style="white-space: pre-wrap;">{{ $man_asignado->MAS_ACTIVIDAD }}</div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert"></i> <strong>Atención:</strong> No se han registrado actividades para este mantenimiento aún.
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tipos de Mantenimiento Aplicados -->
                @if($tip_asignados->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <i class="mdi mdi-format-list-checks"></i> Tipos de Mantenimiento Aplicados
                                </h4>
                                <hr>
                                <div class="row">
                                    @php
                                        $fisicos = $tip_asignados->where('TIP_TIPO', 'Fisico');
                                        $logicos = $tip_asignados->where('TIP_TIPO', 'Logico');
                                    @endphp

                                    @if($fisicos->count() > 0)
                                    <div class="col-md-6">
                                        <h5><i class="mdi mdi-cog"></i> Mantenimiento Físico</h5>
                                        <ul class="list-group">
                                            @foreach($fisicos as $fisico)
                                            <li class="list-group-item">
                                                <i class="mdi mdi-check text-success"></i> {{ $fisico->TIP_NOMBRE }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    @if($logicos->count() > 0)
                                    <div class="col-md-6">
                                        <h5><i class="mdi mdi-monitor"></i> Mantenimiento Lógico</h5>
                                        <ul class="list-group">
                                            @foreach($logicos as $logico)
                                            <li class="list-group-item">
                                                <i class="mdi mdi-check text-success"></i> {{ $logico->TIP_NOMBRE }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

@endsection

@section('scripts')
<style>
    /* Mejoras visuales */
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 20px;
    }

    .card-title {
        color: #2c3e50;
        font-weight: 600;
    }

    .list-group-item {
        border: 1px solid #e9ecef;
        padding: 10px 15px;
    }

    .badge {
        font-size: 0.85rem;
        font-weight: 600;
    }

    code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.9rem;
    }
</style>
@endsection
