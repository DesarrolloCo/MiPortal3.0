@extends('layouts.main')

@section('main')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">
            <i class="mdi mdi-calendar-clock"></i> Gestión de Horarios
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Individual.index') }}">Horarios Individuales</a></li>
            <li class="breadcrumb-item active">Gestionar Horario</li>
        </ol>
    </div>
    <div class="col-md-4 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            <a href="{{ route('Individual.index') }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Employee Information Card -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($empleado as $list)
                <!-- Employee Header -->
                <div class="row mb-3 pb-3 border-bottom" id="agente_info">
                    <!-- Hidden input for calendar JavaScript -->
                    <input type="hidden" name="id_empleado" value="{{ $list->EMP_ID }}">
                    <div class="col-md-6">
                        <h4 class="card-title mb-2">
                            <i class="mdi mdi-account-circle text-primary"></i>
                            {{ $list->EMP_NOMBRES }}
                        </h4>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    <i class="mdi mdi-card-account-details"></i> Cédula:
                                </small>
                                <span class="badge badge-secondary">{{ $list->EMP_CEDULA }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    <i class="mdi mdi-briefcase"></i> Cargo:
                                </small>
                                <strong>{{ $list->CAR_NOMBRE }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <div class="btn-group-vertical btn-group-sm d-inline-flex" role="group">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_hour">
                                <i class="mdi mdi-clock-outline"></i> Asignar Horas
                            </button>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_working_day">
                                <i class="mdi mdi-calendar-range"></i> Asignar Jornada
                            </button>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_edit">
                                <i class="mdi mdi-pencil"></i> Editar Horario
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="mdi mdi-calendar-month"></i> Calendario de Horarios
                        </h5>
                        <div id='calendario_supervisor' class="calendar-container"></div>
                    </div>
                </div>

                @include('Malla.Horarios.Individual.edit')
                @include('Malla.Horarios.Individual.working_day')
                @include('Malla.Horarios.Individual.hour')
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

@endsection

@section('scripts')
<script src="{{ asset('js/calendar-supervisor.js') }}"></script>

<style>
    .calendar-container {
        min-height: 600px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .btn-group-vertical .btn {
        margin-bottom: 5px;
        text-align: left;
    }

    .btn-group-vertical .btn i {
        width: 20px;
    }

    /* Estilos para eventos del calendario */
    .custom-event-content {
        padding: 2px 4px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .event-campaign {
        font-size: 11px;
        font-weight: 600;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-bottom: 1px;
    }

    .event-time {
        font-size: 9px;
        font-weight: 500;
        line-height: 1.1;
        opacity: 0.9;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Estilos específicos de FullCalendar para mejorar legibilidad */
    .fc-event {
        padding: 1px 2px !important;
        border-radius: 3px;
    }

    .fc-event-title {
        font-weight: normal !important;
    }

    .fc-timegrid-event-harness {
        margin-right: 2px !important;
    }

    /* Mejorar el aspecto del calendario */
    .fc .fc-timegrid-slot {
        height: 2em;
    }

    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: #dee2e6;
    }

    .fc .fc-button-primary {
        background-color: #5969ff;
        border-color: #5969ff;
    }

    .fc .fc-button-primary:hover {
        background-color: #4556d8;
        border-color: #4556d8;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #3d4fc7;
        border-color: #3d4fc7;
    }
</style>
@endsection
