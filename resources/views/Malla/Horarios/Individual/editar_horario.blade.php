@extends('layouts.main')


@section('main')

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">
                            Horario individual
                            @if(isset($empleado) && count($empleado) > 0)
                                - {{ $empleado[0]->EMP_NOMBRES }} {{ $empleado[0]->EMP_APELLIDOS ?? '' }}
                            @endif
                        </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('Individual.index') }}">Horarios Individual</a></li>
                            <li class="breadcrumb-item active">
                                @if(isset($empleado) && count($empleado) > 0)
                                    {{ $empleado[0]->EMP_NOMBRES }}
                                @else
                                    Horario individual
                                @endif
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        {{--<button class="btn float-right hidden-sm-down btn-success" data-toggle="modal" data-target="#Add_Cargo"><i class="mdi mdi-plus-circle"></i> Agregar</button>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i class="mdi mdi-clock-outline text-primary me-2"></i>
                                    @if(isset($isDateRange) && $isDateRange)
                                        Horarios del Rango de Fechas
                                        <small class="text-muted d-block">{{ $fechaInicial }} - {{ $fechaFinal }}</small>
                                    @else
                                        Horarios del Día: {{ $MAL_DIA }}
                                    @endif
                                </h4>
                                @if(count($emp_horario) > 0)
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-toggle="modal" data-target="#modal_novedad_completa"
                                        onclick="prepararNovedadGeneral('{{ $EMP_ID }}', '{{ $MAL_DIA }}')">
                                        <i class="fas fa-calendar-times me-1"></i> Desactivar Horarios
                                    </button>
                                @endif
                            </div>
                            <div class="card-body">

                                <!-- column -->
                                <div class="table-responsive">
                                    <table class="table no-wrap display responsive nowrap" id="table_equipos">
                                        <thead>
                                            <tr>
                                                @if(isset($isDateRange) && $isDateRange)
                                                    <th>Fecha</th>
                                                @endif
                                                <th>Cliente</th>
                                                <th>Campaña</th>
                                                <th>Hora inicial</th>
                                                <th>Hora final</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($emp_horario as $list)
                                                <tr>
                                                    @if(isset($isDateRange) && $isDateRange)
                                                        <td>{{ date('d/m/Y', strtotime($list->MAL_DIA)) }}</td>
                                                    @endif
                                                    <td>{{ $list->CLI_NOMBRE }}</td>
                                                    <td>{{ $list->CAM_NOMBRE }}</td>
                                                    <td>{{ date('H:i', strtotime($list->MAL_INICIO)) }}</td>
                                                    <td>{{ date('H:i', strtotime($list->MAL_FINAL)) }}</td>

                                                    <td>
                                                        @if ($list->MAL_ESTADO == 1)

                                                            @can('delete-malla')
                                                                <form action="{{ route('Malla.delete', $list->MAL_ID) }}" method="POST"
                                                                    style="display: inline-block; ">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="number" class="form-control" id="USER_ID" name="USER_ID"
                                                                        value="{{ Auth::user()->id }}"required pattern="[0-9]+"
                                                                        style="display: none;">
                                                                    <input type="text" value="{{ $list->MAL_ID }}" name="MAL_ID" id="MAL_ID"
                                                                        style="display: none">
                                                                    <input type="text" value="{{ isset($isDateRange) && $isDateRange ? $list->MAL_DIA : $MAL_DIA }}" name="MAL_DIA" id="MAL_DIA"
                                                                        style="display: none">
                                                                    <input type="text" value="{{ $EMP_ID }}" name="EMP_ID" id="EMP_ID"
                                                                        style="display: none">
                                                                    <input type="text" value="1" name="MAL_ESTADO" style="display: none">
                                                                    <button type="submit" class="btn btn-danger" rel="tooltip">
                                                                        <i class="fas fa-trash-alt" title="Eliminar Registro"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        @else
                                                            <form action="{{ route('Individual.delete_time_status', $list->MAL_ID) }}"
                                                                method="POST" style="display: inline-block; ">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="number" class="form-control" id="USER_ID" name="USER_ID"
                                                                    value="{{ Auth::user()->id }}"required pattern="[0-9]+"
                                                                    style="display: none;">
                                                                <input type="text" value="{{ $list->MAL_ID }}" name="MAL_ID" id="MAL_ID"
                                                                    style="display: none">
                                                                <input type="text" value="{{ isset($isDateRange) && $isDateRange ? $list->MAL_DIA : $MAL_DIA }}" name="MAL_DIA" id="MAL_DIA"
                                                                    style="display: none">
                                                                <input type="text" value="{{ $EMP_ID }}" name="EMP_ID" id="EMP_ID"
                                                                    style="display: none">
                                                                <input type="text" value="1" name="MAL_ESTADO" style="display: none">
                                                                <button type="submit" class="btn btn-success" rel="tooltip">
                                                                    <i class="far fa-calendar-plus"></i> Activar
                                                                </button>
                                                            </form>
                                                        @endif


                                                        {{-- <button type="button" class="btn btn-primary" style="color:white" onclick="llenarmodal({{ $list->MAL_ID }})" rel="tooltip" data-toggle="modal" data-target="#edit_campana">
                                                            <i class="fas fa-edit"></i>
                                                        </button> --}}

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
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->

                <!-- Modal para registrar novedad completa -->
                @include('Malla.Horarios.Individual.modal-novedad')

@endsection

@section('styles')
<style>
/* Estilos para la información del empleado */
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.me-3 {
    margin-right: 1rem !important;
}

.card.mb-3 {
    margin-bottom: 1rem !important;
}

.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

.text-right {
    text-align: right !important;
}

.badge-info {
    background-color: #17a2b8;
    font-size: 0.85em;
    padding: 0.4em 0.6em;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .col-md-3.text-right {
        text-align: left !important;
        margin-top: 1rem;
    }

    .d-flex.align-items-center {
        flex-direction: column;
        text-align: center;
    }

    .avatar-circle.me-3 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem !important;
    }
}
</style>
@endsection
