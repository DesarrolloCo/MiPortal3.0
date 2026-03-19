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
                                                                <th>Nombre</th>
                                                                <th>Nombre</th>
                                                                <th>Serial</th>
                                                                <th>Área</th>
                                                                <th>Precio</th>
                                                                <th>Tipo</th>
                                                                <th>Estado</th>
                                                                <th>Observaciones</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($equipos as $list)
                                                                <tr>
                                                                    
                                                                    <td>{{ $list->NOMBRE }}</td>
                                                                    <td>{{ $list->EQU_NOMBRE }}</td>
                                                                    <td>{{ $list->EQU_SERIAL }}</td>
                                                                    <td>{{ $list->AREAS }}</td>
                                                                    <td>{{ $list->EQU_PRECIO }}</td>
                                                                    <td>{{ $list->EQU_TIPO }}</td>
                                                                    <td>{{ $list->TIE_NOMBRE }}</td>
                                                                    <td>{{ $list->EQU_OBSERVACIONES }}</td>
                                                                    <td>
                                                                        <a href="{{ route('Equipo.details',$list->EQU_ID) }}" class="btn btn-success" title="Ver detalles"><i class="fas fa-eye"></i></a>

                                                                    <a href="{{ route('Equipo.qr.mostrar', $list->EQU_ID) }}" class="btn btn-warning" title="Ver código QR"><i class="mdi mdi-qrcode"></i></a>

                                                                    <a href="{{ route('Equipo.historial', $list->EQU_ID) }}" class="btn btn-info" title="Ver historial"><i class="mdi mdi-history"></i></a>

                                                                    <button type="button" class="btn btn-primary" rel="tooltip" title="Editar" data-toggle="modal" data-bs-toggle="modal" data-target="#Edit_Equipo{{ $list->EQU_ID }}" data-bs-target="#Edit_Equipo{{ $list->EQU_ID }}">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>

                                                                    <form action="{{ route('Equipo.delete', $list->EQU_ID) }}" method="POST"
                                                                        style="display: inline-block; ">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit" class="btn btn-danger" rel="tooltip"
                                                                            onclick="return confirm('Seguro que quiere eliminar este cargo?') ">
                                                                            <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                                        </button>

                                                                    </form>
                                                                    </td>
                                                                </tr>
                                                                @include('Inventario.Equipo.edit')
                                                            @endforeach
                                                            {{-- <tr>
                                                                <td><a href="javascript:void(0)">Order #26589</a></td>
                                                                <td>Herman Beck</td>
                                                                <td><span class="text-muted"><i class="far fa-clock"></i> Oct 16, 2019</span> </td>
                                                                <td>$45.00</td>
                                                                <td>
                                                                    <div class="label label-table label-success">Paid</div>
                                                                </td>
                                                                <td>EN</td>
                                                            </tr> --}}

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
