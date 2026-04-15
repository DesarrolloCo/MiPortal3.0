@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            <i class="mdi mdi-laptop"></i> Detalles del Equipo
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Equipo.index') }}">Equipos</a></li>
                            <li class="breadcrumb-item active">{{ $equipos[0]->EQU_SERIAL }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <a href="{{ route('Equipo.index') }}" class="btn float-right waves-effect waves-light btn-secondary" title="Volver"><i class="mdi mdi-arrow-left"></i> Volver</a>
                        <a href="{{ route('Equipo.cv',$equipos[0]->EQU_ID) }}" class="btn float-right mr-2 waves-effect waves-light btn-success" title="Exportar hoja de vida"><i class="mdi mdi-file-excel"></i> Hoja de Vida</a>
                        <a href="{{ route('Equipo.qr.mostrar', $equipos[0]->EQU_ID) }}" class="btn float-right mr-2 waves-effect waves-light btn-warning" title="Ver QR"><i class="mdi mdi-qrcode"></i></a>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Tarjeta de Resumen del Equipo -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 border-right">
                                        <strong class="text-muted"><i class="mdi mdi-account"></i> Empleado Asignado</strong>
                                        <br>
                                        @if(isset($equ_asignados[0]->EMP_NOMBRES))
                                            <h5 class="mt-2 mb-0">
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="mdi mdi-account-check"></i> {{ $equ_asignados[0]->EMP_NOMBRES }}
                                                </span>
                                            </h5>
                                        @else
                                            <p class="text-muted mt-2">
                                                <span class="badge badge-secondary px-3 py-2">
                                                    <i class="mdi mdi-package"></i> No asignado
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-sm-6 border-right">
                                        <strong class="text-muted"><i class="mdi mdi-laptop"></i> Nombre del Equipo</strong>
                                        <br>
                                        <h5 class="mt-2 mb-0">{{ $equipos[0]->EQU_NOMBRE }}</h5>
                                    </div>
                                    <div class="col-md-3 col-sm-6 border-right">
                                        <strong class="text-muted"><i class="mdi mdi-barcode"></i> Serial</strong>
                                        <br>
                                        <h5 class="mt-2 mb-0"><code class="text-dark">{{ $equipos[0]->EQU_SERIAL }}</code></h5>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <strong class="text-muted"><i class="mdi mdi-office-building"></i> Área</strong>
                                        <br>
                                        <h5 class="mt-2 mb-0">
                                            <span class="badge badge-light border px-3 py-2">
                                                {{ $equipos[0]->EQU_NOMBRE }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acordeón de Información Detallada -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="accordian-3">
                                    <!-- Datos del Equipo -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading11">
                                            <button class="btn btn-link text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-laptop text-primary"></i> Datos del Equipo
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse1" class="collapse show" aria-labelledby="heading11" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="mdi mdi-tag"></i> Nombre del Equipo</label>
                                                            <input type="text" class="form-control" value="{{ $equipos[0]->EQU_NOMBRE }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="mdi mdi-barcode"></i> Serial</label>
                                                            <input type="text" class="form-control" value="{{ $equipos[0]->EQU_SERIAL }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Datos del Empleado -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading22">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-account text-success"></i> Datos del Empleado
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse2" class="collapse" aria-labelledby="heading22" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="mdi mdi-account"></i> Nombre del Empleado</label>
                                                            @if(isset($equ_asignados[0]->EMP_NOMBRES))
                                                            <input type="text" class="form-control" value="{{ $equ_asignados[0]->EMP_NOMBRES }}" readonly>
                                                            @else
                                                            <input type="text" class="form-control text-muted" value="No hay ningún empleado asignado" readonly>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="mdi mdi-card-account-details"></i> Identificación</label>
                                                            @if(isset($equ_asignados[0]->EMP_CEDULA))
                                                            <input type="text" class="form-control" value="{{ $equ_asignados[0]->EMP_CEDULA }}" readonly>
                                                            @else
                                                            <input type="text" class="form-control text-muted" value="No hay ningún empleado asignado" readonly>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hardware -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading33">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-memory text-info"></i> Hardware
                                                    <span class="badge badge-info ml-2">{{ count($har_asignados) }}</span>
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse3" class="collapse" aria-labelledby="heading33" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-details">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="mdi mdi-format-list-bulleted"></i> Tipo</th>
                                                                <th><i class="mdi mdi-text"></i> Descripción</th>
                                                                <th><i class="mdi mdi-cog"></i> Modelo</th>
                                                                <th><i class="mdi mdi-barcode"></i> Serial</th>
                                                                <th class="text-center"><i class="mdi mdi-settings"></i> Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($har_asignados as $har_asignado)
                                                            <tr>
                                                                <td><span class="badge badge-primary">{{ $har_asignado->HAR_TIPO }}</span></td>
                                                                <td>{{ $har_asignado->HAR_DESCRIPCION }}</td>
                                                                <td>{{ $har_asignado->HAR_MODELO }}</td>
                                                                <td><code>{{ $har_asignado->HAR_SERIAL }}</code></td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-warning" rel="tooltip" title="Registrar cambio" data-toggle="modal" data-target="#Edit_Cambio{{ $har_asignado->HAS_ID }}">
                                                                        <i class="fas fa-exchange-alt"></i> Cambiar
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @include('Inventario.Equipo.change')
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">
                                                                    <i class="mdi mdi-information"></i> No hay hardware asignado
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button class="btn btn-success btn-sm mt-3" data-toggle="modal" data-target="#Add_Hardware">
                                                    <i class="mdi mdi-plus-circle"></i> Agregar Hardware
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Software -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading44">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-application text-primary"></i> Software
                                                    <span class="badge badge-primary ml-2">{{ count($sof_asignados) }}</span>
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse4" class="collapse" aria-labelledby="heading44" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-details">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="mdi mdi-application"></i> Nombre</th>
                                                                <th><i class="mdi mdi-tag"></i> Versión</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($sof_asignados as $sof_asignado)
                                                            <tr>
                                                                <td><i class="mdi mdi-checkbox-marked-circle text-success"></i> {{ $sof_asignado->SOF_NOMBRE }}</td>
                                                                <td><span class="badge badge-light border">v{{ $sof_asignado->SOF_VERSION }}</span></td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="2" class="text-center text-muted">
                                                                    <i class="mdi mdi-information"></i> No hay software asignado
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button class="btn btn-success btn-sm mt-3" data-toggle="modal" data-target="#Add_Software">
                                                    <i class="mdi mdi-plus-circle"></i> Agregar Software
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mantenimiento -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading55">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-wrench text-warning"></i> Mantenimiento
                                                    <span class="badge badge-warning ml-2">{{ count($man_asignados) }}</span>
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse5" class="collapse" aria-labelledby="heading55" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-details">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="mdi mdi-format-list-bulleted"></i> Tipo</th>
                                                                <th><i class="mdi mdi-text"></i> Actividades</th>
                                                                <th><i class="mdi mdi-calendar"></i> Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($man_asignados as $man_asignado)
                                                            <tr>
                                                                <td><span class="badge badge-warning">{{ $man_asignado->MAS_TIPO }}</span></td>
                                                                <td>{{ $man_asignado->MAS_ACTIVIDAD }}</td>
                                                                <td><i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($man_asignado->MAN_FECHA)->format('d/m/Y') }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">
                                                                    <i class="mdi mdi-information"></i> No hay registros de mantenimiento
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cambios -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading66">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-swap-horizontal text-secondary"></i> Historial de Cambios
                                                    <span class="badge badge-secondary ml-2">{{ count($cambios) }}</span>
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse6" class="collapse" aria-labelledby="heading66" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-details">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="mdi mdi-format-list-bulleted"></i> Tipo</th>
                                                                <th><i class="mdi mdi-text"></i> Descripción</th>
                                                                <th><i class="mdi mdi-cog"></i> Modelo</th>
                                                                <th><i class="mdi mdi-barcode"></i> Serial</th>
                                                                <th><i class="mdi mdi-comment"></i> Comentarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($cambios as $cambio)
                                                            <tr>
                                                                <td><span class="badge badge-dark">{{ $cambio->HAR_TIPO }}</span></td>
                                                                <td>{{ $cambio->HAR_DESCRIPCION }}</td>
                                                                <td>{{ $cambio->HAR_MODELO }}</td>
                                                                <td><code>{{ $cambio->HAR_SERIAL }}</code></td>
                                                                <td>{{ $cambio->HAS_COMENTARIO ?? 'Sin comentarios' }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">
                                                                    <i class="mdi mdi-information"></i> No hay cambios registrados
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Evidencias -->
                                    <div class="card border">
                                        <a class="card-header bg-light" id="heading77">
                                            <button class="btn btn-link collapsed text-dark w-100 text-left" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-file-document text-info"></i> Evidencias
                                                    <span class="badge badge-info ml-2">{{ count($evidencias) }}</span>
                                                    <i class="mdi mdi-chevron-down float-right"></i>
                                                </h5>
                                            </button>
                                        </a>
                                        <div id="collapse7" class="collapse" aria-labelledby="heading77" data-parent="#accordian-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-details">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="mdi mdi-account"></i> Empleado</th>
                                                                <th><i class="mdi mdi-text"></i> Nombre</th>
                                                                <th class="text-center"><i class="mdi mdi-file-pdf"></i> Evidencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($evidencias as $evidencia)
                                                            <tr>
                                                                <td>{{ $evidencia->EMP_NOMBRES }}</td>
                                                                <td>{{ $evidencia->EVI_NOMBRE }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ route('Equipo.evidencia', ['id' => $evidencia->EVI_ID]) }}" target="_blank" class="btn btn-sm btn-danger">
                                                                        <i class="mdi mdi-file-pdf"></i> Ver PDF
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">
                                                                    <i class="mdi mdi-information"></i> No hay evidencias cargadas
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
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                @include('Inventario.Equipo.software')
                @include('Inventario.Equipo.hardware')

@endsection

@section('scripts')
<style>
    /* Mejoras visuales para la vista de detalles */
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }

    .card-header button {
        text-decoration: none !important;
    }

    .card-header button:hover {
        text-decoration: none !important;
    }

    .border-right {
        border-right: 1px solid #dee2e6 !important;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.8rem;
    }

    code {
        background-color: #f4f4f4;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 1rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    thead.bg-light th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #5a6268;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    /* Animación para los acordeones */
    .collapse {
        transition: all 0.3s ease;
    }

    .mdi-chevron-down {
        transition: transform 0.3s ease;
    }

    button[aria-expanded="true"] .mdi-chevron-down {
        transform: rotate(180deg);
    }

    /* Botones mejorados */
    .btn-sm {
        padding: 0.375rem 0.75rem;
    }

    @media (max-width: 768px) {
        .border-right {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Prevenir inicialización de DataTables en tablas de detalles
        $.fn.dataTable.ext.errMode = 'none';

        // Destruir cualquier inicialización previa de DataTables en tablas de detalles
        $('.table-details').each(function() {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().destroy();
            }
        });

        // Inicializar tooltips
        $('[rel="tooltip"]').tooltip();

        // Animar el scroll suave al abrir acordeones
        $('[data-toggle="collapse"]').on('click', function() {
            var target = $(this).attr('data-target');
            setTimeout(function() {
                if ($(target).hasClass('show')) {
                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 100
                    }, 500);
                }
            }, 350);
        });
    });
</script>
@endsection
