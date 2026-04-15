@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Equipos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Equipos</li>

                        </ol>

                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_Equipos" data-bs-target="#Add_Equipos"><i class="mdi mdi-plus-circle"></i> Agregar</button>

                        <div class="btn-group float-right mr-2 hidden-sm-down" role="group">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-qrcode"></i> Códigos QR
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('Equipo.qr.masivo') }}">
                                    <i class="mdi mdi-file-pdf"></i> Generar PDF con Todos los QR
                                </a>
                                <a class="dropdown-item" href="{{ route('Equipo.qr.escaner') }}">
                                    <i class="mdi mdi-camera"></i> Escanear QR
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('Inventario.exportar.equipos') }}" class="btn btn-info float-right mr-2 hidden-sm-down">
                            <i class="mdi mdi-file-excel"></i> Exportar Excel
                        </a>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Alertas de mensajes -->
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

                @if(session('msjupdate'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Actualizado!</strong> {{ session('msjupdate') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('msjdelete'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Eliminado!</strong> {{ session('msjdelete') }}
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
                                                    <table class="table no-wrap display responsive nowrap" id="table_equipos_custom">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>Asignado a</th>
                                                                <th>Nombre</th>
                                                                <th>Serial</th>
                                                                <th>Área</th>
                                                                <th>Precio</th>
                                                                <th>Tipo</th>
                                                                <th>Estado</th>
                                                                <th>Observaciones</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($equipos as $list)
                                                                <tr>
                                                                    <td>
                                                                        @if($list->NOMBRE)
                                                                            <span class="badge badge-success px-2 py-1">
                                                                                <i class="mdi mdi-account-check"></i> {{ $list->NOMBRE }}
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-secondary px-2 py-1">
                                                                                <i class="mdi mdi-package"></i> Disponible
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td><strong>{{ $list->EQU_NOMBRE }}</strong></td>
                                                                    <td><code>{{ $list->EQU_SERIAL }}</code></td>
                                                                    <td>
                                                                        <span class="badge badge-light border px-2 py-1">
                                                                            <i class="mdi mdi-office-building"></i> {{ $list->AREAS ?? 'N/A' }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-success font-weight-bold">
                                                                            ${{ number_format($list->EQU_PRECIO, 0, ',', '.') }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @if($list->EQU_TIPO == 'Propio')
                                                                            <span class="badge badge-primary px-2 py-1">
                                                                                <i class="mdi mdi-check-circle"></i> {{ $list->EQU_TIPO }}
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-warning px-2 py-1">
                                                                                <i class="mdi mdi-clock"></i> {{ $list->EQU_TIPO }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($list->TIE_NOMBRE == 'Operativo')
                                                                            <span class="badge badge-success px-2 py-1">
                                                                                <i class="mdi mdi-check-circle"></i> {{ $list->TIE_NOMBRE }}
                                                                            </span>
                                                                        @elseif($list->TIE_NOMBRE == 'En Mantenimiento')
                                                                            <span class="badge badge-warning px-2 py-1">
                                                                                <i class="mdi mdi-wrench"></i> {{ $list->TIE_NOMBRE }}
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-secondary px-2 py-1">
                                                                                <i class="mdi mdi-information"></i> {{ $list->TIE_NOMBRE }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="{{ $list->EQU_OBSERVACIONES }}">
                                                                            {{ $list->EQU_OBSERVACIONES ?? 'Sin observaciones' }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="btn-group" role="group">
                                                                            <a href="{{ route('Equipo.details',$list->EQU_ID) }}"
                                                                               class="btn btn-sm btn-success"
                                                                               rel="tooltip"
                                                                               title="Ver detalles">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>

                                                                            <a href="{{ route('Equipo.qr.mostrar', $list->EQU_ID) }}"
                                                                               class="btn btn-sm btn-warning"
                                                                               rel="tooltip"
                                                                               title="Ver código QR">
                                                                                <i class="mdi mdi-qrcode"></i>
                                                                            </a>

                                                                            <a href="{{ route('Equipo.historial', $list->EQU_ID) }}"
                                                                               class="btn btn-sm btn-info"
                                                                               rel="tooltip"
                                                                               title="Ver historial">
                                                                                <i class="mdi mdi-history"></i>
                                                                            </a>

                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-primary"
                                                                                    rel="tooltip"
                                                                                    title="Editar"
                                                                                    data-toggle="modal"
                                                                                    data-bs-toggle="modal"
                                                                                    data-target="#Edit_Equipo{{ $list->EQU_ID }}"
                                                                                    data-bs-target="#Edit_Equipo{{ $list->EQU_ID }}">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>

                                                                            <form action="{{ route('Equipo.delete', $list->EQU_ID) }}"
                                                                                  method="POST"
                                                                                  style="display: inline-block;">
                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button type="submit"
                                                                                        class="btn btn-sm btn-danger"
                                                                                        rel="tooltip"
                                                                                        title="Eliminar equipo"
                                                                                        onclick="return confirm('¿Seguro que quiere eliminar este equipo?')">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @include('Inventario.Equipo.edit')
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


                @include('Inventario.Equipo.create')

@endsection

@section('scripts')
<style>
    /* Mejoras visuales para la tabla de equipos */
    #table_equipos_custom tbody tr {
        transition: background-color 0.2s ease;
    }

    #table_equipos_custom tbody tr:hover {
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

    /* Text truncate helper */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

/* Modal Override Styles */
.modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1050 !important;
    display: none !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
    outline: 0 !important;
}

.modal.show {
    display: block !important;
}

.modal-dialog {
    position: relative !important;
    width: auto !important;
    margin: 0.5rem !important;
    pointer-events: none !important;
}

.modal-content {
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    pointer-events: auto !important;
    background-color: #fff !important;
    border: 1px solid rgba(0,0,0,.2) !important;
    border-radius: 0.3rem !important;
    outline: 0 !important;
}

.modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1040 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: #000 !important;
}

.modal-backdrop.show {
    opacity: 0.5 !important;
}

@media (min-width: 576px) {
    .modal-dialog {
        max-width: 500px !important;
        margin: 1.75rem auto !important;
    }
}
</style>

<script>
$(document).ready(function() {
    console.log('Equipos modal script loaded');

    // Verificar si DataTable ya existe y destruirla antes de reinicializar
    if ($.fn.DataTable.isDataTable('#table_equipos_custom')) {
        $('#table_equipos_custom').DataTable().destroy();
    }

    // Inicializar DataTable con opciones mejoradas
    $('#table_equipos_custom').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
        },
        "pageLength": 25,
        "order": [[1, "asc"]], // Ordenar por nombre equipo
        "columnDefs": [
            { "orderable": false, "targets": 8 } // Desactivar ordenamiento en columna de acciones
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

    // Simple click handler for add button
    $('[data-target="#Add_Equipos"]').click(function(e) {
        e.preventDefault();
        console.log('Add Equipos button clicked');

        // Simple show
        $('#Add_Equipos').show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Add Equipos modal should be visible');
    });

    // Simple click handler for edit buttons
    $('[data-target^="#Edit_Equipo"]').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        console.log('Edit Equipo button clicked, target:', target);

        // Hide any other open modals first
        $('.modal').hide().removeClass('show');
        $('.modal-backdrop').remove();

        // Show the specific edit modal
        $(target).show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Edit Equipo modal should be visible:', target);
    });

    // Close modal functionality
    $(document).on('click', '.modal .close, [data-dismiss="modal"]', function() {
        console.log('Close button clicked');
        $('.modal').hide().removeClass('show');
        $('.modal-backdrop').remove();
    });

    // Close modal on backdrop click
    $(document).on('click', '.modal-backdrop', function() {
        console.log('Backdrop clicked');
        $('.modal').hide().removeClass('show');
        $('.modal-backdrop').remove();
    });

    // Prevent modal content click from closing modal
    $(document).on('click', '.modal-content', function(e) {
        e.stopPropagation();
    });

    // Debug: Show all edit modal IDs found
    $('[id^="Edit_Equipo"]').each(function() {
        console.log('Found edit modal:', this.id);
    });
});
</script>
@endsection
