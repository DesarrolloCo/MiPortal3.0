@extends('layouts.main')


@section('main')
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Mi Visita</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Mi Visita</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Visita"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Generar_reportes"><i class="mdi mdi-file-excel"></i> Generar reporte</button>
                        </div>

                        {{-- <button class="right-side-toggle waves-effect waves-light btn-info btn-circle btn-sm float-right ml-2"><i class="ti-settings text-white"></i></button>
                        <button class="btn float-right hidden-sm-down btn-success"><i class="mdi mdi-plus-circle"></i> Create</button>
                        <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- column -->
                                <div class="table-responsive">
                                    <table class="table no-wrap display responsive nowrap" id="table_equipos">
                                        <thead>
                                            <tr>
                                                <th>Fecha de ingreso</th>
                                                <th>Nombre</th>
                                                <th>Empresa</th>
                                                <th>Motivo de ingreso</th>
                                                <th>Fecha de salida</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($registros as $list)
                                                <tr>
                                                    <td>{{ $list->created_at }}</td>
                                                    <td>{{ $list->REG_NOMBRE }}</td>
                                                    <td>{{ $list->REG_EMPRESA }}</td>
                                                    <td>{{ $list->REG_MOTIVO_INGRESO }}</td>
                                                    <td>{{ $list->REG_FECHA_HORA_SALIDA }}</td>
                                                    <td>
                                                        <form action="{{ route('Visita.exit', $list->REG_ID) }}" method="POST"
                                                            style="display: inline-block; ">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-danger" rel="tooltip">
                                                                <i class="mdi mdi-exit-to-app" title="Registrar salida"></i>
                                                            </button>

                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- column -->


                            </div>
                        </div>
                    </div>
                </div>
                @include('Visita.create')
                @include('Visita.reportes');
@endsection
