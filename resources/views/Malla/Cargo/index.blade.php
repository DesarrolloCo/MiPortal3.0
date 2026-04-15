@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Gestiones por cargos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gestiones por cargos</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                         @can('crear-cargo')
                         <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_Cargo" data-bs-target="#Add_Cargo"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                         @endcan
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
                                <!-- Filtro de búsqueda -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('Cargo.index') }}" class="form-inline">
                                            <div class="form-group mr-2 mb-2">
                                                <input type="text" class="form-control" name="buscar"
                                                       placeholder="Buscar por código o nombre..."
                                                       value="{{ request('buscar') }}" style="width: 300px;">
                                            </div>

                                            <button type="submit" class="btn btn-primary mr-2 mb-2">
                                                <i class="mdi mdi-magnify"></i> Buscar
                                            </button>

                                            @if(request()->has('buscar'))
                                                <a href="{{ route('Cargo.index') }}" class="btn btn-secondary mb-2">
                                                    <i class="mdi mdi-refresh"></i> Limpiar
                                                </a>
                                            @endif
                                        </form>
                                    </div>
                                </div>

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
                                        <strong><i class="mdi mdi-alert-circle"></i> Eliminado!</strong> {{ session('msjdelete') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('msjupdate'))
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <strong><i class="mdi mdi-check-circle"></i> Actualizado!</strong> {{ session('msjupdate') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- column -->
                                <div class="table-responsive">
                                    <table class="table table-hover no-wrap" id="table_cargos">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($cargos as $list)
                                                <tr>
                                                    <td><strong>{{ $list->CAR_CODE }}</strong></td>
                                                    <td>{{ $list->CAR_NOMBRE }}</td>
                                                     <td class="text-center">
                                                         @can('opciones-cargo')
                                                         <form action="{{ route('Cargo.delete', $list->CAR_ID) }}" method="POST"
                                                             style="display: inline-block;">
                                                             @csrf
                                                             @method('DELETE')

                                                             <button type="submit" class="btn btn-danger btn-sm" rel="tooltip"
                                                                 onclick="return confirm('¿Seguro que quiere eliminar este cargo?')"
                                                                 title="Eliminar Registro">
                                                                 <i class="fas fa-trash-alt"></i>
                                                             </button>
                                                         </form>

                                                         <button type="button" class="btn btn-primary btn-sm" rel="tooltip"
                                                             data-toggle="modal" data-bs-toggle="modal"
                                                             data-target="#EditCargo{{ $list->CAR_ID }}"
                                                             data-bs-target="#EditCargo{{ $list->CAR_ID }}"
                                                             title="Editar Cargo">
                                                             <i class="fas fa-edit"></i>
                                                         </button>
                                                         @endcan
                                                     </td>
                                                </tr>
                                                @include('Malla.Cargo.edit')
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">
                                                        <i class="mdi mdi-alert-circle" style="font-size: 48px;"></i>
                                                        <p>No se encontraron cargos</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- column -->

                                <!-- Paginación -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            Mostrando {{ $cargos->firstItem() ?? 0 }} a {{ $cargos->lastItem() ?? 0 }}
                                            de {{ $cargos->total() }} cargos
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="float-right">
                                            {{ $cargos->links() }}
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


                @include('Malla.Cargo.create')

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
    console.log("Cargo modal script loaded");

    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Handler for add buttons (dynamic based on found patterns)
    $("[data-target=\"#Add_Cargo\"]").click(function(e) {
        e.preventDefault();
        console.log("Add button clicked for Add_Cargo");
        $("#Add_Cargo").show().addClass("show");
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
