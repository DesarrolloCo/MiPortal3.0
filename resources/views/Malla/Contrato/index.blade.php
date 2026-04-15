@extends('layouts.main')

@section('main')
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-file-document-box"></i> Historial de Contratos
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Empleado.index') }}">Empleados</a></li>
                            <li class="breadcrumb-item active">Contratos de {{ $empleado->EMP_NOMBRES }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        @can('sidebar_recursos_humanos')
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Contrato">
                            <i class="mdi mdi-plus-circle"></i> Nuevo Contrato
                        </button>
                        @endcan
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Mensajes de alerta -->
                @if(session('rgcmessage'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-check-circle"></i> Éxito!</strong> {{ session('rgcmessage') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('msjdelete'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-alert-circle"></i> Finalizado!</strong> {{ session('msjdelete') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('warmessage'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-alert"></i> Advertencia!</strong> {{ session('warmessage') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Información del Empleado -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-account-card-details text-primary"></i> Información del Empleado
                                </h4>

                                <div class="row mb-3">
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="text-muted mb-1"><i class="mdi mdi-account"></i> Nombre completo</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_NOMBRES }}</h6>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="text-muted mb-1"><i class="mdi mdi-card-account-details"></i> Documento</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_CEDULA }}</h6>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="text-muted mb-1"><i class="mdi mdi-phone"></i> Contacto</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_TELEFONO ?: 'No registrado' }}</h6>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="text-muted mb-1"><i class="mdi mdi-map-marker"></i> Dirección</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_DIRECCION ?: 'No registrada' }}</h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted mb-1"><i class="mdi mdi-barcode"></i> Código</label>
                                        <h6 class="mb-0"><span class="badge badge-secondary">{{ $empleado->EMP_CODE }}</span></h6>
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted mb-1"><i class="mdi mdi-gender-{{ strtolower($empleado->EMP_SEXO) == 'm' ? 'male' : 'female' }}"></i> Sexo</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_SEXO }}</h6>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <label class="text-muted mb-1"><i class="mdi mdi-cake-variant"></i> Fecha de nacimiento</label>
                                        <h6 class="mb-0">{{ $empleado->EMP_FECHA_NACIMIENTO ? \Carbon\Carbon::parse($empleado->EMP_FECHA_NACIMIENTO)->format('d/m/Y') : 'No registrada' }}</h6>
                                    </div>
                                    @if($empleado->cargo)
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <label class="text-muted mb-1"><i class="mdi mdi-briefcase"></i> Cargo Actual</label>
                                        <h6 class="mb-0"><span class="badge badge-primary">{{ $empleado->contratoActivo->cargo->CAR_NOMBRE ?? 'Sin contrato activo' }}</span></h6>
                                    </div>
                                    @endif
                                    @if($empleado->EMP_ACTIVO)
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="text-muted mb-1"><i class="mdi mdi-check-circle"></i> Estado</label>
                                        <h6 class="mb-0">
                                            <span class="badge badge-{{ $empleado->EMP_ACTIVO == 'SI' ? 'success' : 'danger' }}">
                                                {{ $empleado->EMP_ACTIVO == 'SI' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </h6>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Contratos -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">
                                    <i class="mdi mdi-file-document-box-multiple text-info"></i> Historial de Contratos
                                    <span class="badge badge-info ml-2">{{ $contratos->count() }} {{ $contratos->count() == 1 ? 'Contrato' : 'Contratos' }}</span>
                                </h4>

                                <!-- Tabla de contratos -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Estado</th>
                                                <th>Cargo</th>
                                                <th>Tipo Contrato</th>
                                                <th>Sueldo</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Duración</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($contratos as $con)
                                                <tr class="{{ $con->EMC_FINALIZADO == 'NO' ? 'table-active' : '' }}">
                                                    <!-- Estado del contrato -->
                                                    <td>
                                                        @if($con->EMC_FINALIZADO == 'NO')
                                                            <span class="badge badge-success">
                                                                <i class="mdi mdi-check-circle"></i> Activo
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                <i class="mdi mdi-close-circle"></i> Finalizado
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <!-- Cargo -->
                                                    <td>
                                                        <strong>{{ $con->cargo->CAR_NOMBRE ?? 'No especificado' }}</strong>
                                                    </td>

                                                    <!-- Tipo de contrato -->
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ $con->tipoContrato->TIC_NOMBRE ?? 'No especificado' }}
                                                        </span>
                                                    </td>

                                                    <!-- Sueldo -->
                                                    <td>
                                                        @if($con->EMC_SUELDO)
                                                            <strong class="text-success">
                                                                ${{ number_format($con->EMC_SUELDO, 0, ',', '.') }}
                                                            </strong>
                                                        @else
                                                            <span class="text-muted">No especificado</span>
                                                        @endif
                                                    </td>

                                                    <!-- Fecha Inicio -->
                                                    <td>
                                                        <i class="mdi mdi-calendar-start"></i>
                                                        {{ $con->EMC_FECHA_INI ? \Carbon\Carbon::parse($con->EMC_FECHA_INI)->format('d/m/Y') : 'No especificada' }}
                                                    </td>

                                                    <!-- Fecha Fin -->
                                                    <td>
                                                        @if($con->EMC_FECHA_FIN)
                                                            <i class="mdi mdi-calendar-end"></i>
                                                            {{ \Carbon\Carbon::parse($con->EMC_FECHA_FIN)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">
                                                                <i class="mdi mdi-calendar-clock"></i> Indefinido
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <!-- Duración -->
                                                    <td>
                                                        @if($con->EMC_FECHA_INI)
                                                            @php
                                                                $inicio = \Carbon\Carbon::parse($con->EMC_FECHA_INI);
                                                                $fin = $con->EMC_FECHA_FIN ? \Carbon\Carbon::parse($con->EMC_FECHA_FIN) : \Carbon\Carbon::now();
                                                                $duracion = $inicio->diff($fin);
                                                            @endphp
                                                            <small class="text-muted">
                                                                @if($duracion->y > 0)
                                                                    {{ $duracion->y }} {{ $duracion->y == 1 ? 'año' : 'años' }}
                                                                @endif
                                                                @if($duracion->m > 0)
                                                                    {{ $duracion->m }} {{ $duracion->m == 1 ? 'mes' : 'meses' }}
                                                                @endif
                                                            </small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>

                                                    <!-- Acciones -->
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @can('sidebar_recursos_humanos')
                                                            @if ($con->EMC_FINALIZADO == 'NO')
                                                                <button class="btn btn-warning btn-sm" title="Finalizar contrato"
                                                                    data-toggle="modal" data-target="#Finalizar_{{$con->EMC_ID}}">
                                                                    <i class="mdi mdi-close-circle"></i>
                                                                </button>

                                                                <a class="btn btn-primary btn-sm" href="{{ route('Funcione.index', $con->EMC_ID) }}"
                                                                   title="Gestionar funciones">
                                                                    <i class="mdi mdi-format-list-bulleted"></i>
                                                                </a>
                                                            @endif
                                                            @endcan

                                                            <button class="btn btn-danger btn-sm" title="Descargar PDF"
                                                                data-toggle="modal" data-target="#Pdf_{{$con->EMC_ID}}">
                                                                <i class="mdi mdi-file-pdf"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                @include('Malla.Contrato.pdf')
                                                @include('Malla.Contrato.finish')
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-5">
                                                        <i class="mdi mdi-file-document-box-outline" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No hay contratos registrados para este empleado</p>
                                                        @can('sidebar_recursos_humanos')
                                                        <button class="btn btn-success mt-2" data-toggle="modal" data-target="#Add_Contrato">
                                                            <i class="mdi mdi-plus-circle"></i> Crear Primer Contrato
                                                        </button>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                @include('Malla.Contrato.create')

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection
