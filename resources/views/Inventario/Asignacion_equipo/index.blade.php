@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Asignacion de equipos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Asignacion de equipos</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Equ_asignado"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        <div class="btn-group float-right mr-2 hidden-sm-down" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-file-excel"></i> Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('Inventario.exportar.asignaciones') }}">
                                    <i class="mdi mdi-account-check"></i> Solo Activas
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
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Alertas de mensajes -->
                @if(session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Éxito!</strong> {{ session('success') }}
                            @if(session('devolucion_id'))
                                <br>
                                <a href="{{ route('Asignacion_equipo.acta_devolucion', session('devolucion_id')) }}"
                                   class="btn btn-sm btn-success mt-2">
                                    <i class="mdi mdi-file-pdf"></i> Descargar Acta de Devolución
                                </a>
                            @endif
                            @if(session('asignacion_id'))
                                <br>
                                <a href="{{ route('Asignacion_equipo.acta_entrega', session('asignacion_id')) }}"
                                   class="btn btn-sm btn-success mt-2">
                                    <i class="mdi mdi-file-pdf"></i> Descargar Acta de Entrega
                                </a>
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Advertencia!</strong> {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                               <!-- column -->

                               <div class="table-responsive">
                                <table class="table no-wrap display responsive nowrap" id="table_equipos">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Estado</th>
                                            <th>Empleado</th>
                                            <th>Equipo</th>
                                            <th>Serial</th>
                                            <th>Fecha Entrega</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equ_asignado as $row)
                                            <tr>
                                                <td>
                                                    @if ($row->EAS_ESTADO == '1')
                                                        <span class="badge badge-success px-2 py-1">
                                                            <i class="mdi mdi-check-circle"></i> Asignado
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary px-2 py-1">
                                                            <i class="mdi mdi-arrow-left-circle"></i> Devuelto
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{$row->EMP_NOMBRES}}</strong>
                                                </td>
                                                <td>{{$row->EQU_NOMBRE}}</td>
                                                <td><code>{{$row->EQU_SERIAL}}</code></td>
                                                <td>
                                                    <i class="mdi mdi-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($row->EAS_FECHA_ENTREGA)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <!-- Acta de Entrega -->
                                                        <a href="{{ route('Asignacion_equipo.acta_entrega', $row->EAS_ID) }}"
                                                           class="btn btn-sm btn-info"
                                                           rel="tooltip"
                                                           title="Descargar Acta de Entrega">
                                                            <i class="mdi mdi-file-document"></i>
                                                        </a>

                                                        <!-- Agregar Evidencia -->
                                                        <button type="button"
                                                                class="btn btn-sm btn-primary"
                                                                rel="tooltip"
                                                                data-toggle="modal"
                                                                data-target="#Edit_asg_equ{{ $row->EAS_ID }}"
                                                                title="Agregar evidencia">
                                                            <i class="mdi mdi-attachment"></i>
                                                        </button>

                                                        <!-- Registrar Devolución (solo si está asignado) -->
                                                        @if($row->EAS_ESTADO == '1')
                                                        <button type="button"
                                                                class="btn btn-sm btn-warning"
                                                                rel="tooltip"
                                                                data-toggle="modal"
                                                                data-target="#Devolver_equ{{ $row->EAS_ID }}"
                                                                title="Registrar devolución">
                                                            <i class="mdi mdi-keyboard-return"></i>
                                                        </button>
                                                        @else
                                                        <!-- Acta de Devolución (si está devuelto) -->
                                                        @if(isset($row->DEV_ID) && $row->DEV_ID)
                                                        <a href="{{ route('Asignacion_equipo.acta_devolucion', $row->DEV_ID) }}"
                                                           class="btn btn-sm btn-warning"
                                                           rel="tooltip"
                                                           title="Descargar Acta de Devolución">
                                                            <i class="mdi mdi-file-pdf"></i>
                                                        </a>
                                                        @else
                                                        <button class="btn btn-sm btn-secondary" disabled rel="tooltip" title="Devolución sin acta">
                                                            <i class="mdi mdi-file-alert"></i>
                                                        </button>
                                                        @endif
                                                        @endif

                                                        <!-- Eliminar Asignación -->
                                                        <form action="{{ route('Asignacion_equipo.delete', $row->EAS_ID) }}"
                                                              method="POST"
                                                              style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-danger"
                                                                    rel="tooltip"
                                                                    title="Terminar asignación"
                                                                    onclick="return confirm('¿Seguro que quiere remover esta asignación?')">
                                                                <i class="fas fa-cut"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('Inventario.Asignacion_equipo.edit')
                                            @include('Inventario.Asignacion_equipo.devolver')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
            <!-- column -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->


                @include('Inventario.Asignacion_equipo.create')

@endsection

@section('scripts')
<style>
    /* Mejoras visuales para la tabla de asignaciones */
    #table_equipos tbody tr {
        transition: background-color 0.2s ease;
    }

    #table_equipos tbody tr:hover {
        background-color: #f8f9fa;
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

    .btn-group .btn {
        margin: 0 2px;
    }

    /* Animación suave para los badges */
    .badge {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Mejorar visualización de alertas */
    .alert {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .alert .btn {
        margin: 0 5px;
    }

    /* Tooltip mejorado */
    [rel="tooltip"] {
        position: relative;
    }

    /* Estilo para cards */
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: none;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Mejorar visualización de thead */
    thead.bg-light th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #5a6268;
        border-bottom: 2px solid #dee2e6;
    }
</style>

<script>
    $(document).ready(function() {
        // Verificar si DataTable ya existe y destruirla antes de reinicializar
        if ($.fn.DataTable.isDataTable('#table_equipos')) {
            $('#table_equipos').DataTable().destroy();
        }

        // Inicializar DataTable con opciones mejoradas
        $('#table_equipos').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "pageLength": 25,
            "order": [[4, "desc"]], // Ordenar por fecha de entrega descendente
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Desactivar ordenamiento en columna de acciones
            ],
            "responsive": true,
            "autoWidth": false
        });

        // Inicializar tooltips de Bootstrap
        $('[rel="tooltip"]').tooltip();

        // Auto-cerrar alertas después de 10 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 10000);
    });
</script>
@endsection
