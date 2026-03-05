@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Malla de horarios por campaña</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Malla de horarios por campaña</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        {{-- <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Cargo"><i class="mdi mdi-plus-circle"></i> Agregar</button>
                         <div class="dropdown float-right mr-2 hidden-sm-down">
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
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="mt-4" action="{{ route('Reporte.campaign') }}" method="GET">
                                    @csrf
                                    <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Cliente</label>
                                                <select ame="CLI_ID" id="CLI_ID" class="form-control">
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach ($clientes as $cli)
                                                        <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Campañas</label>
                                                <select name="CAM_ID" id="CAM_ID" class="form-control">
                                                    {{-- SELECT CAMPAÑA --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha inicial</label>
                                                <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha final</label>
                                                <input type="date" name="FECHA_FINAL" id="FECHA_FINAL" class="form-control" >
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Asignar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- column -->
                                <div class="table-responsive">
                                    <table class="table no-wrap display responsive nowrap" id="table_equipos">
                                        <thead>
                                            <tr>
                                                <th>Campaña</th>
                                                <th>Empleado</th>
                                            </tr>
                                        </thead>
                                        <tbody name="tablaempleados" id="tablaempleados">

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

                <script src="{{ asset('js/malla_grupal.js') }}"></script>

@endsection
