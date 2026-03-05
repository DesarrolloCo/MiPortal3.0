@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Gestionar unidades de negocio</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gestionar unidades de negocio</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_UniNegocios" data-bs-target="#Add_UniNegocios"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                                                <th>Nombre</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($unidad_negocio as $list)
                                            <tr>
                                                <td>{{ $list->UNI_NOMBRE }}</td>
                                                <td>

                                                    <form action="{{ route('Unidad_Negocio.delete', $list->UNI_ID) }}" method="POST"
                                                        style="display: inline-block; ">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger" rel="tooltip"
                                                            onclick="return confirm('Seguro que quiere eliminar esta unidad de negocio?') ">
                                                            <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                        </button>

                                                    </form>

                                                    <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-bs-toggle="modal" data-target="#EditUnidadNegocio{{ $list->UNI_ID }}" data-bs-target="#EditUnidadNegocio{{ $list->UNI_ID }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @include('Malla.Unidad_Negocio.edit')
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


                @include('Malla.Unidad_Negocio.create')

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
    console.log("Unidad_Negocio modal script loaded");

    // Handler for add buttons (dynamic based on found patterns)
    
    $("[data-target=\"#Add_UniNegocios\"]").click(function(e) {
        e.preventDefault();
        console.log("Add button clicked for Add_UniNegocios");
        $("#Add_UniNegocios").show().addClass("show");
        if ($(".modal-backdrop").length === 0) {
            $("body").append("<div class=\"modal-backdrop show\"></div>");
        }
    });

    // Handler for edit buttons (dynamic based on found patterns)
    

    // Close modal functionality
    $(document).on("click", ".modal .close, [data-dismiss=\"modal\"]", function() {
        console.log("Close button clicked");
        $(".modal").hide().removeClass("show");
        $(".modal-backdrop").remove();
    });

    // Close modal on backdrop click
    $(document).on("click", ".modal-backdrop", function() {
        console.log("Backdrop clicked");
        $(".modal").hide().removeClass("show");
        $(".modal-backdrop").remove();
    });

    // Prevent modal content click from closing modal
    $(document).on("click", ".modal-content", function(e) {
        e.stopPropagation();
    });

    // Debug: Show all modal IDs found
    $(".modal[id]").each(function() {
        console.log("Found modal:", this.id);
    });
});
</script>
@endsection
