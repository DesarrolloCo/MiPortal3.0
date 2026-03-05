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
                                                <th>Código de empleado</th>
                                                <th>Documento</th>
                                                <th>Nombre completo</th>
                                                <th>Campaña</th>
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
                                                    <td>{{ $list->CAM_NOMBRE }}</td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="marcador_{{$list->EMP_ID}}"
                                                                   onchange="estado_emp('{{ $list->EMP_ACTIVO == 'SI' ? 'NO' : 'SI' }}', {{ $list->EMP_ID }})"
                                                                   {{ $list->EMP_ACTIVO == 'SI' ? 'checked' : '' }}>
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
                            },
                            error: function (xhr) {
                                alert('Error al actualizar estado');
                                console.error(xhr.responseText);
                            }
                        });
                    }
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
