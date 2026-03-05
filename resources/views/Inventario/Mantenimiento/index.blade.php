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
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Mantenimiento"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                                                    <table class="table no-wrap display responsive nowrap" id="table_mantenimiento">
                                                        <thead>
                                                            <tr>
                                                                <th>Area</th>
                                                                <th>Equipo</th>
                                                                <th>Proveedor</th>
                                                                <th>Fecha de mantenimiento</th>
                                                                <th>Tecnico</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tabla_mantenimiento as $list_mantenimiento)
                                                            <tr>
                                                                <td>{{ $list_mantenimiento->ARE_NOMBRE }}</td>
                                                                <td>{{ $list_mantenimiento->EQU_NOMBRE }}</td>
                                                                <td>{{ $list_mantenimiento->MAN_PROVEEDOR }}</td>
                                                                <td>{{ $list_mantenimiento->MAN_FECHA }}</td>
                                                                <td>{{ $list_mantenimiento->EMP_NOMBRES }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-target="#Edit_Maintenance{{ $list_mantenimiento->MAN_ID }}">
                                                                        <i class="ti-settings text-white"></i>
                                                                    </button>
                                                                    <a href="{{ route('Mantenimiento.details',$list_mantenimiento->MAN_ID) }}" class="btn waves-effect waves-light btn-info"><i class="fas fa-eye"></i></a>
                                                                </td>
                                                            </tr>
                                                            @include('Inventario.Mantenimiento.maintenance')
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


                @include('Inventario.Mantenimiento.create')

@endsection
