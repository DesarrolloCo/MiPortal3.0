@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Áreas</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Áreas</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-bs-toggle="modal" data-target="#Add_Areas" data-bs-target="#Add_Areas"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                                            <th>Nombre del área</th>
                                            <th>Descripcion del área</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($areas as $area)
                                                <tr>
                                                    <td>{{ $area->ARE_NOMBRE }}</td>
                                                    <td>{{ $area->ARE_DESCRIPCION }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-bs-toggle="modal" data-target="#Edit_Area{{ $area->ARE_ID }}" data-bs-target="#Edit_Area{{ $area->ARE_ID }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('Area.delete', $area->ARE_ID) }}" method="POST"
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
                                                @include('Inventario.Area.modals.edit')
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

    @include('Inventario.Area.modals.create')

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-styles.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/modal-manager.js') }}"></script>
    <script>
        // Configuración específica para el módulo de Áreas (opcional)
        $(document).ready(function() {
            console.log('Módulo de Áreas cargado con sistema optimizado de modales');

            // Eventos específicos del módulo si son necesarios
            $('#Add_Areas').on('modal:shown', function(e, data) {
                console.log('Modal de agregar área mostrado');
            });

            $('[id^="Edit_Area"]').on('modal:shown', function(e, data) {
                console.log('Modal de editar área mostrado:', data.modalId);
            });
        });
    </script>
@endpush
