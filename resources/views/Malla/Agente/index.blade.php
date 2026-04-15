@extends('layouts.main')

@section('main')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">
            <i class="mdi mdi-calendar-clock"></i> Mi Horario
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Mi Horario</li>
        </ol>
    </div>
    <div class="col-md-4 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            <small class="text-muted">
                <i class="mdi mdi-information-outline"></i>
                Vista semanal de tu horario laboral
            </small>
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
                @if(Auth::user()->empleados)
                <!-- Employee Header -->
                <div class="row mb-3 pb-3 border-bottom">
                    <!-- Hidden input for calendar JavaScript -->
                    <input type="hidden" name="id_empleado" value="{{ Auth::user()->empleados->EMP_ID }}" id="id_empleado">
                    <div class="col-md-6">
                        <h4 class="card-title mb-2">
                            <i class="mdi mdi-account-circle text-primary"></i>
                            {{ Auth::user()->empleados->EMP_NOMBRES }} {{ Auth::user()->empleados->EMP_APELLIDOS }}
                        </h4>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    <i class="mdi mdi-card-account-details"></i> Cédula:
                                </small>
                                <span class="badge badge-secondary">{{ Auth::user()->empleados->EMP_CEDULA }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    <i class="mdi mdi-briefcase"></i> Cargo:
                                </small>
                                <strong>{{ Auth::user()->empleados->cargo->CAR_NOMBRE ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <div class="calendar-info">
                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                <div class="mr-3 mb-1">
                                    <span class="badge badge-primary badge-sm">
                                        <i class="mdi mdi-circle"></i> Trabajo
                                    </span>
                                </div>
                                <div class="mr-3 mb-1">
                                    <span class="badge badge-success badge-sm">
                                        <i class="mdi mdi-circle"></i> Almuerzo
                                    </span>
                                </div>
                                <div class="mr-3 mb-1">
                                    <span class="badge badge-danger badge-sm">
                                        <i class="mdi mdi-circle"></i> Bloqueado
                                    </span>
                                </div>
                                <div class="mb-1">
                                    <small class="text-muted">
                                        <i class="mdi mdi-mouse-pointer"></i> Click para detalles
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="mdi mdi-calendar-month"></i> Calendario de Horarios
                        </h5>
                        <div id='calendario_agente' class="calendar-container"></div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="mdi mdi-account-alert text-warning" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Usuario sin empleado asociado</h4>
                    <p class="text-muted">No se puede mostrar el horario porque no tienes un perfil de empleado asignado.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

@endsection

@section('scripts')
<script src="{{ asset('js/calendar-agente.js') }}"></script>

<style>
    .calendar-container {
        min-height: 600px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .calendar-info .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .calendar-info .badge i {
        font-size: 0.6rem;
        margin-right: 0.25rem;
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

    .event-novedad {
        font-size: 9px;
        font-weight: 600;
        line-height: 1.1;
        color: #fff;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection
