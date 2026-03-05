@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Gestionar contrato de {{ $empleado[0]->EMP_NOMBRES }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Gestionar contrato de {{ $empleado[0]->EMP_NOMBRES }}</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        @can('sidebar_recursos_humanos')
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Contrato"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                        {{-- <div class="dropdown float-right mr-2 hidden-sm-down">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2019 </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2019</a> <a class="dropdown-item" href="#">March 2019</a> <a class="dropdown-item" href="#">April 2019</a> </div>
                        </div> --}}
                        @endcan
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

                                <div class="row">
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Nombre completo</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_NOMBRES }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Documento</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_CEDULA }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Contacto</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_TELEFONO }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>Direccion</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_DIRECCION }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Codigo</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_CODE }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Sexo</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_SEXO }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 border-right"> <strong>Fecha de nacimiento</strong>
                                        <br>
                                        <p class="text-muted">{{ $empleado[0]->EMP_FECHA_NACIMIENTO }}</p>
                                    </div>
                                </div>

                                <hr>

                                <!-- column -->
                                <div class="table-responsive">
                                    <table class="table no-wrap display responsive nowrap" id="table_equipos">
                                        <thead>
                                            <tr>
                                                <th>Cargo</th>
                                                <th>Tipo Contrato</th>
                                                <th>Sueldo</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contratos as $con)
                                                <tr>
                                                    <td>{{ $con->CAR_NOMBRE }}</td>
                                                    <td>{{ $con->TIC_NOMBRE }}</td>
                                                    <td>{{ $con->EMC_SUELDO }}</td>
                                                    <td>{{ $con->EMC_FECHA_INI }}</td>
                                                    <td>{{ $con->EMC_FECHA_FIN }}</td>
                                                    <td>
                                                        @can('sidebar_recursos_humanos')
                                                        @if ($con->EMC_FINALIZADO == 'NO')
                                                                <button class="btn btn-danger" title="Terminacion de contrato" data-toggle="modal" data-target="#Finalizar_{{$con->EMC_ID}}"><i class="fas fa-cut"></i></button>

                                                                <a class="btn btn-primary" href="{{ route('Funcione.index', $con->EMC_ID) }}" title="Certificado laboral"><i class="fas fa-people-carry"></i><span>Funciones</span></a>
                                                        @endif
                                                        @endcan

                                                        <button class="btn float-right hidden-sm-down btn-danger" data-toggle="modal" data-target="#Pdf_{{$con->EMC_ID}}"><i class="fas fa-file-pdf"></i></button>

                                                    </td>
                                                </tr>

                                                @include('Malla.Contrato.pdf')
                                                @include('Malla.Contrato.finish')
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


                @include('Malla.Contrato.create')


                @endsection
