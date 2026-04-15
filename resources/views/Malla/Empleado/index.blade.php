@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Empleados</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Empleados</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_Empleado" data-bs-target="#Add_Empleado"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        <button class="btn float-right mr-2 hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_ImpEmpleado" data-bs-target="#Add_ImpEmpleado"><i class="mdi mdi-file-excel"></i> Importar</button>
                        {{-- <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
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
                            <strong><i class="mdi mdi-check-circle"></i> Éxito!</strong> {{ session('rgcmessage') }}
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
                            <strong><i class="mdi mdi-information"></i> Actualizado!</strong> {{ session('msjupdate') }}
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
                            <strong><i class="mdi mdi-alert"></i> Atención!</strong> {{ session('msjdelete') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('msjerror'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-close-circle"></i> Error!</strong> {{ session('msjerror') }}
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
                            <strong><i class="mdi mdi-close-circle"></i> Error!</strong> {{ session('error') }}
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
                            <strong><i class="mdi mdi-alert-circle"></i> Errores de validación:</strong>
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

                <!-- Errores de importación detallados -->
                @if(session('import_errors'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">
                                    <i class="mdi mdi-alert-circle"></i> Errores de Importación ({{ count(session('import_errors')) }})
                                </h5>
                                <button type="button" class="btn btn-sm btn-warning" id="toggle-errors">
                                    <i class="mdi mdi-chevron-down"></i> Ver detalles
                                </button>
                            </div>
                            <div id="error-details" style="display: none;">
                                <hr>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <ul class="mb-0">
                                        @foreach(session('import_errors') as $error)
                                            <li class="mb-1">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
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
                                        <thead>
                                            <tr>
                                                <th>Codigo de empleado</th>
                                                <th>Documento</th>
                                                <th>Nombre completo</th>
                                                <th>Cliente</th>
                                                <th>Estado</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($empleados as $list)
                                                <tr>
                                                    <td>{{ $list->EMP_CODE }}</td>
                                                    <td>{{ $list->EMP_CEDULA }}</td>
                                                    <td>{{ $list->EMP_NOMBRES }}</td>
                                                    <td>{{ $list->cliente ? $list->cliente->CLI_NOMBRE : 'N/A' }}</td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="marcador_{{$list->EMP_ID}}"
                                                                   onchange="estado_emp({{ ($list->EMP_ACTIVO == 1 || $list->EMP_ACTIVO == 'SI') ? 0 : 1 }}, {{ $list->EMP_ID }})"
                                                                   {{ ($list->EMP_ACTIVO == 1 || $list->EMP_ACTIVO == 'SI') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="marcador_{{$list->EMP_ID}}"></label>
                                                        </div>
                                                    </td>

                                                    <td>

                                                        <form action="{{ route('Empleado.delete', $list->EMP_ID) }}" method="POST"
                                                           style="display: inline-block; ">
                                                           @csrf
                                                           @method('DELETE')

                                                           <button type="submit" class="btn btn-danger" rel="tooltip"
                                                               onclick="return confirm('Seguro que quiere eliminar este empleado?') ">
                                                               <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                           </button>

                                                       </form>

                                                       <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-bs-toggle="modal" data-target="#Edit_Empleado{{ $list->EMP_ID }}" data-bs-target="#Edit_Empleado{{ $list->EMP_ID }}">
                                                           <i class="fas fa-edit"></i>
                                                       </button>

                                                       <a type="button" class="btn btn-primary" href="{{ route('Contrato.index', $list->EMP_ID) }}"><i class="fas fa-file-alt"></i></a>

                                                   </td>
                                                </tr>
                                                @include('Malla.Empleado.edit')
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


                @include('Malla.Empleado.create')
                @include('Malla.Empleado.importar')


                <script src="{{ asset('js/select_municipio.js') }}"></script>

                <script>
                    function estado_emp(estado, emp_id) {
                        $.ajax({
                            url: '/empleados/' + emp_id + '/estado',
                            type: 'POST',
                            data: {
                                _method: 'PUT',
                                _token: '{{ csrf_token() }}',
                                estado: estado
                            },
                            success: function (response) {
                                console.log('Estado actualizado:', response);
                                // Mostrar mensaje de éxito sin recargar página
                                toastr.success('Estado actualizado correctamente', 'Éxito');
                            },
                            error: function (xhr) {
                                console.error('Error al actualizar estado:', xhr.responseText);
                                var errorMsg = xhr.responseJSON && xhr.responseJSON.error
                                    ? xhr.responseJSON.error
                                    : 'Error al actualizar estado';
                                toastr.error(errorMsg, 'Error');
                            }
                        });
                    }

                    // Inicialización optimizada de DataTables
                    $(document).ready(function() {
                        // Verificar si DataTables ya está inicializado
                        if ($.fn.DataTable.isDataTable('#table_equipos')) {
                            // Destruir instancia existente
                            $('#table_equipos').DataTable().destroy();
                        }

                        // Inicializar DataTables con configuración optimizada
                        $('#table_equipos').DataTable({
                            // Opciones de rendimiento
                            "deferRender": true,        // Renderizado diferido para tablas grandes
                            "processing": true,          // Mostrar indicador de procesamiento
                            "stateSave": true,          // Guardar estado (página actual, búsqueda, etc.)
                            "stateDuration": 3600,      // Duración del estado guardado (1 hora)

                            // Paginación optimizada
                            "pageLength": 25,           // Mostrar 25 registros por defecto
                            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

                            // Idioma español
                            "language": {
                                "decimal": "",
                                "emptyTable": "No hay datos disponibles en la tabla",
                                "info": "Mostrando _START_ a _END_ de _TOTAL_ empleados",
                                "infoEmpty": "Mostrando 0 a 0 de 0 empleados",
                                "infoFiltered": "(filtrado de _MAX_ empleados totales)",
                                "infoPostFix": "",
                                "thousands": ",",
                                "lengthMenu": "Mostrar _MENU_ empleados",
                                "loadingRecords": "Cargando...",
                                "processing": "Procesando...",
                                "search": "Buscar:",
                                "zeroRecords": "No se encontraron registros coincidentes",
                                "paginate": {
                                    "first": "Primero",
                                    "last": "Último",
                                    "next": "Siguiente",
                                    "previous": "Anterior"
                                },
                                "aria": {
                                    "sortAscending": ": activar para ordenar la columna ascendente",
                                    "sortDescending": ": activar para ordenar la columna descendente"
                                }
                            },

                            // Configuración de columnas
                            "columnDefs": [
                                { "orderable": false, "targets": [4, 5] },  // Deshabilitar orden en Estado y Opciones
                                { "searchable": false, "targets": [4, 5] }  // Deshabilitar búsqueda en Estado y Opciones
                            ],

                            // Ordenamiento por defecto (por Nombre)
                            "order": [[2, "asc"]],

                            // Optimización de búsqueda
                            "search": {
                                "smart": true,          // Búsqueda inteligente
                                "regex": false,         // Desactivar regex para mejor rendimiento
                                "caseInsensitive": true // Búsqueda sin distinguir mayúsculas
                            },

                            // Responsive
                            "responsive": true,
                            "autoWidth": false
                        });
                    });
                </script>

                <!-- Script para toggle de errores de importación -->
                <script>
                    $(document).ready(function() {
                        $('#toggle-errors').click(function() {
                            var errorDetails = $('#error-details');
                            var icon = $(this).find('i');

                            if (errorDetails.is(':visible')) {
                                errorDetails.slideUp();
                                icon.removeClass('mdi-chevron-up').addClass('mdi-chevron-down');
                                $(this).html('<i class="mdi mdi-chevron-down"></i> Ver detalles');
                            } else {
                                errorDetails.slideDown();
                                icon.removeClass('mdi-chevron-down').addClass('mdi-chevron-up');
                                $(this).html('<i class="mdi mdi-chevron-up"></i> Ocultar detalles');
                            }
                        });

                        // Auto-cerrar alertas después de 15 segundos (excepto errores de importación)
                        setTimeout(function() {
                            $('.alert:not(:has(#error-details))').fadeOut('slow');
                        }, 15000);
                    });
                </script>


@endsection

@section('scripts')
<style>
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
    console.log('Empleados modal script loaded');

    // Simple click handler for add button
    $('[data-target="#Add_Empleado"]').click(function(e) {
        e.preventDefault();
        console.log('Add Empleado button clicked');

        // Simple show
        $('#Add_Empleado').show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Add Empleado modal should be visible');
    });

    // Simple click handler for import button
    $('[data-target="#Add_ImpEmpleado"]').click(function(e) {
        e.preventDefault();
        console.log('Import Empleado button clicked');

        // Simple show
        $('#Add_ImpEmpleado').show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Import Empleado modal should be visible');
    });

    // Simple click handler for edit buttons
    $('[data-target^="#Edit_Empleado"]').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        console.log('Edit Empleado button clicked, target:', target);

        // Hide any other open modals first
        $('.modal').hide().removeClass('show');
        $('.modal-backdrop').remove();

        // Show the specific edit modal
        $(target).show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Edit Empleado modal should be visible:', target);
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
    $('[id^="Edit_Empleado"]').each(function() {
        console.log('Found edit modal:', this.id);
    });
});
</script>
@endsection
