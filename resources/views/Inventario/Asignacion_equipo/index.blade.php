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
                        <div class="btn-group float-right mr-2 hidden-sm-down" role="group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-file-excel"></i> Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('Inventario.exportar.asignaciones') }}">
                                    <i class="mdi mdi-account-check"></i> Solo Activas
                                </a>
                                <a class="dropdown-item" href="{{ route('Inventario.exportar.todas_asignaciones') }}">
                                    <i class="mdi mdi-view-list"></i> Todas las Asignaciones
                                </a>
                                <a class="dropdown-item" href="{{ route('Inventario.exportar.devoluciones') }}">
                                    <i class="mdi mdi-keyboard-return"></i> Devoluciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- Alertas de mensajes -->
                @if(session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Éxito!</strong> {{ session('success') }}
                            @if(session('devolucion_id'))
                                <br>
                                <a href="{{ route('Asignacion_equipo.acta_devolucion', session('devolucion_id')) }}"
                                   class="btn btn-sm btn-success mt-2">
                                    <i class="mdi mdi-file-pdf"></i> Descargar Acta de Devolución
                                </a>
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Advertencia!</strong> {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

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
                                                    <button type="button" class="btn btn-primary" rel="tooltip" data-toggle="modal" data-target="#Edit_asg_equ{{ $row->EAS_ID }}" title="Agregar evidencia">
                                                        <i class="mdi mdi-attachment"></i>
                                                    </button>

                                                    @if($row->EAS_ESTADO == '1')
                                                    <button type="button" class="btn btn-warning" rel="tooltip" data-toggle="modal" data-target="#Devolver_equ{{ $row->EAS_ID }}" title="Registrar devolución">
                                                        <i class="mdi mdi-keyboard-return"></i>
                                                    </button>
                                                    @endif

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
                                            @include('Inventario.Asignacion_equipo.devolver')
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
