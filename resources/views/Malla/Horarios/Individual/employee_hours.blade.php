@extends('layouts.main')


@section('main')
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Horario</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Individual.index') }}">Horario individual</a></li>
                            <li class="breadcrumb-item active">Horario</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
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
                                <div class="card-header py-3 row" id="agente_info">
                                    @foreach ($empleado as $list)
                                        <div class="col-lg-4">
                                            <h6 class="card-title">Agente: {{ $list->EMP_NOMBRES }}</h6>
                                        </div>
                                            <div class="button-group">

                                                <button type="button" class="btn waves-effect waves-light" style="background-color: #f96806; color:white;" rel="tooltip" data-toggle="modal" data-target="#modal_hour">
                                                    Asignar horas
                                                </button>

                                                <button type="button" class="btn waves-effect waves-light" style="background-color: #f96806; color:white;" rel="tooltip" data-toggle="modal" data-target="#modal_working_day">
                                                    Asignar jornada
                                                </button>

                                                <button type="button" class="btn waves-effect waves-light" style="background-color: #f96806; color:white;" rel="tooltip" data-toggle="modal" data-target="#modal_edit">
                                                    Editar horario
                                                </button>
                                            </div>

                                            @include('Malla.Horarios.Individual.edit')
                                            @include('Malla.Horarios.Individual.working_day')
                                            @include('Malla.Horarios.Individual.hour')

                                    @endforeach
                                </div>
                                {{-- <input type="hidden" value="{{Auth::user()->empleados->EMP_ID}}" name="id_empleado" id="id_empleado"> --}}
                                <div id='calendario_supervisor'></div>
                            </div>
                        </div>
                    </div>
                </div>



                <script src="{{ asset('js/calendar-supervisor.js') }}"></script>

@endsection
