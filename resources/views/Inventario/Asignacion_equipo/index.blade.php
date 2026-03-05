@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Asignacion de equipos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Asignacion de equipos</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Equ_asignado"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                                            <th>Nombre del empleados</th>
                                            <th>Nombre del equipo</th>
                                            <th>Serial</th>
                                            <th>Fecha de entrega</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equ_asignado as $row)
                                            <tr>
                                                <td>{{$row->EMP_NOMBRES}} @if ($row->EAS_ESTADO == '1') <i class="mdi mdi-checkbox-blank-circle text-success mr-2"></i>  @else <i class="mdi mdi-checkbox-blank-circle text-danger mr-2"></i> @endif</td>
                                                <td>{{$row->EQU_NOMBRE}}</td>
                                                <td>{{$row->EQU_SERIAL}}</td>
                                                <td>{{$row->EAS_FECHA_ENTREGA}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-target="#Edit_asg_equ{{ $row->EAS_ID }}">
                                                        <i class="mdi mdi-attachment"></i>
                                                    </button>
                                                    <form action="{{ route('Asignacion_equipo.delete', $row->EAS_ID) }}" method="POST"
                                                        style="display: inline-block; ">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger" rel="tooltip"
                                                            onclick="return confirm('Seguro que quiere remover esta asignacion?') ">
                                                            <i class="fas fa-cut" title="Terminacion de asignacion"></i>
                                                        </button>

                                                    </form>
                                                </td>
                                            </tr>
                                            @include('Inventario.Asignacion_equipo.edit')
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


                @include('Inventario.Asignacion_equipo.create')


@endsection
