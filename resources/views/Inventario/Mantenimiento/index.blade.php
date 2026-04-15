@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Mantenimiento</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Mantenimiento</li>

                        </ol>

                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Mantenimiento">
                            <i class="mdi mdi-plus-circle"></i> Agregar
                        </button>
                        <a href="{{ route('Mantenimiento.exportar') }}" class="btn btn-info float-right mr-2 hidden-sm-down">
                            <i class="mdi mdi-file-excel"></i> Exportar Excel
                        </a>
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
                            @if(session('mantenimiento_id'))
                                <br>
                                <a href="{{ route('Mantenimiento.reporte', session('mantenimiento_id')) }}"
                                   class="btn btn-sm btn-success mt-2">
                                    <i class="mdi mdi-file-pdf"></i> Descargar Reporte PDF
                                </a>
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('rgcmessage'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Éxito!</strong> {{ session('rgcmessage') }}
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

                @if ($errors->any())
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                                                    <table class="table no-wrap display responsive nowrap" id="table_mantenimiento">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Estado</th>
                                                                <th>Área</th>
                                                                <th>Equipo</th>
                                                                <th>Proveedor</th>
                                                                <th>Fecha Programada</th>
                                                                <th>Técnico</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tabla_mantenimiento as $list_mantenimiento)
                                                            @php
                                                                $fechaMantenimiento = \Carbon\Carbon::parse($list_mantenimiento->MAN_FECHA);
                                                                $hoy = \Carbon\Carbon::now();
                                                                $diasRestantes = $hoy->diffInDays($fechaMantenimiento, false);
                                                                $esVencido = $diasRestantes < 0;
                                                                $esProximo = $diasRestantes >= 0 && $diasRestantes <= 7;
                                                            @endphp
                                                            <tr class="{{ $esVencido ? 'table-danger' : ($esProximo ? 'table-warning' : '') }}">
                                                                <td>
                                                                    @if($esVencido)
                                                                        <span class="badge badge-danger px-2 py-1">
                                                                            <i class="mdi mdi-alert-circle"></i> Vencido
                                                                        </span>
                                                                    @elseif($esProximo)
                                                                        <span class="badge badge-warning px-2 py-1">
                                                                            <i class="mdi mdi-clock-alert"></i> Próximo
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-success px-2 py-1">
                                                                            <i class="mdi mdi-check-circle"></i> Pendiente
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-light border px-2 py-1">
                                                                        <i class="mdi mdi-office-building"></i> {{ $list_mantenimiento->ARE_NOMBRE }}
                                                                    </span>
                                                                </td>
                                                                <td><strong>{{ $list_mantenimiento->EQU_NOMBRE }}</strong></td>
                                                                <td>{{ $list_mantenimiento->MAN_PROVEEDOR }}</td>
                                                                <td>
                                                                    <i class="mdi mdi-calendar"></i>
                                                                    {{ $fechaMantenimiento->format('d/m/Y') }}
                                                                    @if($esVencido)
                                                                        <br>
                                                                        <small class="text-danger">
                                                                            <i class="mdi mdi-alert"></i> {{ abs($diasRestantes) }} días vencido
                                                                        </small>
                                                                    @elseif($esProximo)
                                                                        <br>
                                                                        <small class="text-warning">
                                                                            <i class="mdi mdi-clock"></i> En {{ $diasRestantes }} días
                                                                        </small>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <i class="mdi mdi-account"></i> {{ $list_mantenimiento->EMP_NOMBRES }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group" role="group">
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-primary"
                                                                                rel="tooltip"
                                                                                title="Registrar mantenimiento"
                                                                                data-toggle="modal"
                                                                                data-target="#Edit_Maintenance{{ $list_mantenimiento->MAN_ID }}">
                                                                            <i class="mdi mdi-clipboard-check"></i>
                                                                        </button>

                                                                        <a href="{{ route('Mantenimiento.details',$list_mantenimiento->MAN_ID) }}"
                                                                           class="btn btn-sm btn-info"
                                                                           rel="tooltip"
                                                                           title="Ver detalles">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>

                                                                        <a href="{{ route('Mantenimiento.reporte', $list_mantenimiento->MAN_ID) }}"
                                                                           class="btn btn-sm btn-success"
                                                                           rel="tooltip"
                                                                           title="Descargar reporte PDF">
                                                                            <i class="mdi mdi-file-pdf"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @include('Inventario.Mantenimiento.maintenance')
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


                @include('Inventario.Mantenimiento.create')

@endsection

@section('scripts')
<style>
    /* Mejoras visuales para la tabla de mantenimientos */
    #table_mantenimiento tbody tr {
        transition: background-color 0.2s ease;
    }

    #table_mantenimiento tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.85rem;
        font-weight: 600;
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

    /* Highlight para filas vencidas y próximas */
    .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Verificar si DataTable ya existe y destruirla antes de reinicializar
        if ($.fn.DataTable.isDataTable('#table_mantenimiento')) {
            $('#table_mantenimiento').DataTable().destroy();
        }

        // Inicializar DataTable con opciones mejoradas
        $('#table_mantenimiento').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
            },
            "pageLength": 25,
            "order": [[4, "asc"]], // Ordenar por fecha programada ascendente
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Desactivar ordenamiento en columna de acciones
            ],
            "responsive": true,
            "autoWidth": false
        });

        // Inicializar tooltips
        $('[rel="tooltip"]').tooltip();

        // Auto-cerrar alertas después de 10 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 10000);
    });
</script>
@endsection
