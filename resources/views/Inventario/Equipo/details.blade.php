@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Equipos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Equipo.index') }}">Equipos</a></li>
                            <li class="breadcrumb-item active">Detalles</li>

                        </ol>

                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <a href="{{ route('Equipo.index') }}" class="btn float-right waves-effect waves-light btn-secondary" title="Salir"><i class="mdi mdi-exit-to-app"></i></a>
                        <div class="dropdown float-right mr-2 hidden-sm-down">
                            <a href="{{ route('Equipo.cv',$equipos[0]->EQU_ID) }}" class="btn float-right waves-effect waves-light btn-success" title="Exportar hoja de vida"><i class="mdi mdi-file-excel"></i></a>
                        </div>
                        {{-- <button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Equipos"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- column -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 border-right"> <strong>Empleado asignado</strong>
                                            <br>
                                            @if(isset($equ_asignados[0]->EMP_NOMBRES))
                                            <p class="text-muted">{{ $equ_asignados[0]->EMP_NOMBRES }}</p>
                                            @else
                                                No hay ningún empleado asignado
                                            @endif
                                        </div>
                                        <div class="col-md-3 col-xs-6 border-right"> <strong>Nombre del equipo</strong>
                                            <br>
                                            <p class="text-muted">{{ $equipos[0]->EQU_NOMBRE }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 border-right"> <strong>Serial</strong>
                                            <br>
                                            <p class="text-muted">{{ $equipos[0]->EQU_SERIAL }}</p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Area</strong>
                                            <br>
                                            <p class="text-muted">{{ $equipos[0]->EQU_NOMBRE }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                </div>


                                    <div id="accordian-3">
                                        <div class="card">
                                            <a class="card-header" id="heading11">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                    <h5 class="mb-0">Datos del equipo</h5>
                                                </button>
                                            </a>
                                            <div id="collapse1" class="collapse show" aria-labelledby="heading11" data-parent="#accordian-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <fieldset disabled>
                                                                    <label for="disabledTextInput">Nombre del equipo</label>
                                                                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $equipos[0]->EQU_NOMBRE }}">
                                                                </fieldset>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <fieldset disabled>
                                                                    <label for="disabledTextInput">Serial</label>
                                                                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $equipos[0]->EQU_SERIAL }}">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <a class="card-header" id="heading22">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                    <h5 class="mb-0">Datos del empleado</h5>
                                                </button>
                                            </a>
                                            <div id="collapse2" class="collapse" aria-labelledby="heading22" data-parent="#accordian-3">
                                                <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <fieldset disabled>
                                                                <label for="disabledTextInput">Nombre del empleado</label>
                                                                @if(isset($equ_asignados[0]->EMP_NOMBRES))
                                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ $equ_asignados[0]->EMP_NOMBRES }}">
                                                                @else
                                                                <input type="text" id="disabledTextInput" class="form-control" value="No hay ningún empleado asignado">
                                                                @endif
                                                            </fieldset>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <fieldset disabled>
                                                                <label for="disabledTextInput">Identificacion</label>
                                                                @if(isset($equ_asignados[0]->EMP_CEDULA))
                                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ $equ_asignados[0]->EMP_CEDULA }}">
                                                                @else
                                                                <input type="text" id="disabledTextInput" class="form-control" value="No hay ningún empleado asignado">
                                                                @endif
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                                </div>
                                            </div>
                                        <div class="card">
                                            <a class="card-header" id="heading33">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                                    <h5 class="mb-0">Hardware</h5>
                                                </button>
                                            </a>
                                            <div id="collapse3" class="collapse" aria-labelledby="heading33" data-parent="#accordian-3">
                                                <div class="card-body">
                                                        <table class="table no-wrap display nowrap" id="table_hardware">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tipo</th>
                                                                    <th>Descripción</th>
                                                                    <th>Modelo</th>
                                                                    <th>Serial</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($har_asignados as $har_asignado)
                                                                <tr>
                                                                    <td>{{ $har_asignado->HAR_TIPO }}</td>
                                                                    <td>{{ $har_asignado->HAR_DESCRIPCION }}</td>
                                                                    <td>{{ $har_asignado->HAR_MODELO }}</td>
                                                                    <td>{{ $har_asignado->HAR_SERIAL }} </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-dark" rel="tooltip" data-toggle="modal" data-target="#Edit_Cambio{{ $har_asignado->HAS_ID }}">
                                                                            <i class="fas fa-exchange-alt"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                @include('Inventario.Equipo.change')
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <button class="float-right btn btn-sm btn-rounded btn-success" data-toggle="modal" data-target="#Add_Hardware">Agregar hardware</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <a class="card-header" id="heading44">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse3">
                                                    <h5 class="mb-0">Software</h5>
                                                </button>
                                            </a>
                                            <div id="collapse4" class="collapse" aria-labelledby="heading44" data-parent="#accordian-3">
                                                <div class="card-body">

                                                    <table class="table no-wrap display nowrap" id="table_software">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombres</th>
                                                                <th>Versión</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($sof_asignados as $sof_asignado)
                                                            <tr>
                                                                <td>{{ $sof_asignado->SOF_NOMBRE }}</td>
                                                                <td>{{ $sof_asignado->SOF_VERSION }}</td>
                                                                <td></td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button class="float-right btn btn-sm btn-rounded btn-success" data-toggle="modal" data-target="#Add_Software">Agregar software</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <a class="card-header" id="heading55">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse3">
                                                    <h5 class="mb-0">Mantenimiento</h5>
                                                </button>
                                            </a>
                                            <div id="collapse5" class="collapse" aria-labelledby="heading55" data-parent="#accordian-3">
                                                <div class="card-body">
                                                    <table class="table no-wrap display nowrap" id="table_mantenimiento_details">
                                                        <thead>
                                                            <tr>
                                                                <th>Tipo</th>
                                                                <th>Actividades</th>
                                                                <th>Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($man_asignados as $man_asignado)
                                                            <tr>
                                                                <td>{{ $man_asignado->MAS_TIPO }}</td>
                                                                <td>{{ $man_asignado->MAS_ACTIVIDAD }}</td>
                                                                <td>{{ $man_asignado->MAN_FECHA }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <a class="card-header" id="heading66">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse3">
                                                    <h5 class="mb-0">Cambios</h5>
                                                </button>
                                            </a>
                                            <div id="collapse6" class="collapse" aria-labelledby="heading66" data-parent="#accordian-3">
                                                <div class="card-body">
                                                    <table class="table no-wrap display nowrap" id="table_change">
                                                        <thead>
                                                            <tr>
                                                                <th>Tipo</th>
                                                                <th>Descripción</th>
                                                                <th>Modelo</th>
                                                                <th>Serial</th>
                                                                <th>Comentarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cambios as $cambio)
                                                            <tr>
                                                                <td>{{ $cambio->HAR_TIPO }}</td>
                                                                <td>{{ $cambio->HAR_DESCRIPCION }}</td>
                                                                <td>{{ $cambio->HAR_MODELO }}</td>
                                                                <td>{{ $cambio->HAR_SERIAL }} </td>
                                                                <td>{{ $cambio->HAS_COMENTARIOw }} </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <a class="card-header" id="heading66">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse3">
                                                    <h5 class="mb-0">Evidencias</h5>
                                                </button>
                                            </a>
                                            <div id="collapse7" class="collapse" aria-labelledby="heading77" data-parent="#accordian-3">
                                                <div class="card-body">
                                                    <table class="table no-wrap display nowrap" id="table_change">
                                                        <thead>
                                                            <tr>
                                                                <th>Empleado</th>
                                                                <th>Nombre</th>
                                                                <th>Evidencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($evidencias as $evidencia)
                                                            <tr>
                                                                <td>{{ $evidencia->EMP_NOMBRES }}</td>
                                                                <td>{{ $evidencia->EVI_NOMBRE }}</td>
                                                                <td><a href="{{ route('Equipo.evidencia', ['id' => $evidencia->EVI_ID]) }}" target="_blank">Previsualizar PDF</a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <!-- column -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                @include('Inventario.Equipo.software')
                @include('Inventario.Equipo.hardware')

@endsection
