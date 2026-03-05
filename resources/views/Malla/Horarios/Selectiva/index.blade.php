@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Malla de horarios masivos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Malla de horarios masivos</li>
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
                                <form class="mt-4" action="{{ route('Selectiva.create') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="checkJorOrHor" id="checkJorOrHor" value="0"> {{-- 0 = por jornada, 1 = por hora --}}

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Cliente</label>
                                                <select name="CLI_ID" id="CLI_ID" class="form-control">
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
                                                <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL" class="form-control" {{-- min=<?php $hoy = date('Y-m-d'); echo $hoy; ?> --}}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha final</label>
                                                <input type="date" name="FECHA_FINAL" id="FECHA_FINAL" class="form-control" {{-- min=<?php $hoy = date('Y-m-d'); echo $hoy; ?> --}}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="jornada">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jornada</label>
                                                <select name="JOR_ID" id="JOR_ID" class="form-control">
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach ($jornadas as $jor)
                                                        <option value="{{ $jor->JOR_ID }}">{{ $jor->JOR_NOMBRE }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="for_fecha" style="display: none;">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Hora inicial</label>
                                                <select name="HORA_INICIAL" id="HOR_ID1" class="form-control">
                                                    <option value=""></option>
                                                    @foreach ($horas as $hor)
                                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_INICIO }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Hora final</label>
                                                <select name="HORA_FINAL" id="HOR_ID2" class="form-control">
                                                    <option value=""></option>
                                                    @foreach ($horas as $hor)
                                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_FINAL }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="check_fecha" onclick="ojito()">
                                            <label class="custom-control-label" for="check_fecha">Activar formato por hora</label>
                                        </div>
                                    </div>

                                    <br>

                                    <button type="submit" class="btn btn-primary">Asignar</button>

                                    <!-- column -->
                                <div class="table-responsive">
                                    <table class="table no-wrap display responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th><label for="">Campaña</label></th>
                                                <th><label for="">Empleado</label></th>
                                                <th><input class="form-check-input" type="checkbox" id="seleccionar_todo"><label for="">All</label></th>
                                            </tr>
                                        </thead>
                                        <tbody name="tablaempleados" id="tablaempleados">

                                        </tbody>
                                    </table>
                                </div>
                                <!-- column -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row -->


                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                <script src="{{ asset('js/malla_selectiva.js') }}"></script>

@endsection
