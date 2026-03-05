@extends('layouts.main')

@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Gestionar tipos de novedades</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gestionar tipos de novedades</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_TiposNovedades" data-bs-target="#Add_TiposNovedades"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                                    <table class="table no-wrap display responsive nowrap" id="table_tipos_novedades" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th>Concepto SIIGO</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tiposNovedades as $list)
                                            <tr>
                                                <td>{{ $list->TIN_NOMBRE }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $list->TIN_TIPO == 1 ? 'success' : 'danger' }}">
                                                        {{ $list->tipo_texto }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $list->TIN_ESTADO == 1 ? 'success' : 'secondary' }}">
                                                        {{ $list->TIN_ESTADO == 1 ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($list->conceptoSiigo)
                                                        {{ $list->conceptoSiigo->CODIGO }} - {{ $list->conceptoSiigo->NOMBRE }}
                                                    @else
                                                        <span class="text-muted">Sin asignar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($list->TIN_ESTADO == 1)
                                                        <!-- Botón para desactivar (cuando está activo) -->
                                                        <form action="{{ route('TiposNovedades.delete', $list->TIN_ID) }}" method="POST"
                                                            style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-warning btn-sm" rel="tooltip"
                                                                onclick="return confirm('¿Seguro que quiere desactivar este tipo de novedad?')">
                                                                <i class="fas fa-ban" title="Desactivar Registro"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Botón para activar (cuando está inactivo) -->
                                                        <form action="{{ route('TiposNovedades.activate', $list->TIN_ID) }}" method="POST"
                                                            style="display: inline-block;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success btn-sm" rel="tooltip"
                                                                onclick="return confirm('¿Seguro que quiere activar este tipo de novedad?')">
                                                                <i class="fas fa-check" title="Activar Registro"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <button type="button" class="btn btn-primary btn-sm" rel="tooltip" data-toggle="modal" data-bs-toggle="modal" data-target="#EditTipoNovedad{{ $list->TIN_ID }}" data-bs-target="#EditTipoNovedad{{ $list->TIN_ID }}">
                                                        <i class="fas fa-edit" title="Editar Registro"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @include('Malla.Tipos_Novedades.edit')
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


                @include('Malla.Tipos_Novedades.create')

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
    console.log('Simplified modal script loaded');

    // Simple click handler for add button
    $('[data-target="#Add_TiposNovedades"]').click(function(e) {
        e.preventDefault();
        console.log('Add button clicked');

        // Simple show
        $('#Add_TiposNovedades').show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Add modal should be visible');
    });

    // Simple click handler for edit buttons
    $('[data-target^="#EditTipoNovedad"]').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        console.log('Edit button clicked, target:', target);

        // Hide any other open modals first
        $('.modal').hide().removeClass('show');
        $('.modal-backdrop').remove();

        // Show the specific edit modal
        $(target).show().addClass('show');

        // Add backdrop
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        console.log('Edit modal should be visible:', target);
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
    $('[id^="EditTipoNovedad"]').each(function() {
        console.log('Found edit modal:', this.id);
    });

    // DataTables initialization moved to layouts/tables.blade.php to match Novedades pattern

    // Character counter for TIN_NOMBRE fields
    $(document).on('input', 'input[name="TIN_NOMBRE"]', function() {
        var currentLength = $(this).val().length;
        var maxLength = parseInt($(this).attr('maxlength'));
        var remaining = maxLength - currentLength;

        // Find or create counter element
        var counter = $(this).parent().find('.char-counter');
        if (counter.length === 0) {
            $(this).after('<small class="char-counter text-muted"></small>');
            counter = $(this).parent().find('.char-counter');
        }

        // Update counter text and color
        counter.text(currentLength + '/' + maxLength + ' caracteres');
        if (remaining <= 5) {
            counter.removeClass('text-muted').addClass('text-warning');
        } else if (remaining <= 2) {
            counter.removeClass('text-muted text-warning').addClass('text-danger');
        } else {
            counter.removeClass('text-warning text-danger').addClass('text-muted');
        }
    });
});
</script>
@endsection