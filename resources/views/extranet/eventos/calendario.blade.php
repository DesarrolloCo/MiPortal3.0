@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-calendar-multiple"></i> Calendario de Eventos
                            </h4>
                            <h6 class="card-subtitle">Vista mensual de actividades y eventos</h6>
                        </div>
                        <div>
                            <a href="{{ route('extranet.eventos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-view-list"></i> Vista Lista
                            </a>
                            @can('crear-evento')
                            <a href="{{ route('extranet.eventos.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Nuevo Evento
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="row mt-4">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Leyenda y Próximos Eventos -->
        <div class="col-lg-3">
            <!-- Leyenda de tipos -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="mdi mdi-palette"></i> Tipos de Evento
                    </h5>
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #007bff; border-radius: 3px;" class="mr-2"></div>
                            <small>Reunión</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #28a745; border-radius: 3px;" class="mr-2"></div>
                            <small>Capacitación</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #ff69b4; border-radius: 3px;" class="mr-2"></div>
                            <small>Celebración</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #6f42c1; border-radius: 3px;" class="mr-2"></div>
                            <small>Conferencia</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #17a2b8; border-radius: 3px;" class="mr-2"></div>
                            <small>Team Building</small>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div style="width: 20px; height: 20px; background-color: #6c757d; border-radius: 3px;" class="mr-2"></div>
                            <small>Otro</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximos Eventos -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="mdi mdi-clock-outline"></i> Próximos Eventos
                    </h5>
                    <div class="mt-3" style="max-height: 400px; overflow-y: auto;">
                        @php
                        $proximosEventos = $eventos->where('fecha_inicio', '>=', \Carbon\Carbon::now())->sortBy('fecha_inicio')->take(5);
                        @endphp

                        @if($proximosEventos->count() > 0)
                            @foreach($proximosEventos as $evento)
                            <div class="border-bottom pb-2 mb-2">
                                <div style="width: 4px; height: 100%; background-color: {{ $evento->color }}; position: absolute; left: 0;"></div>
                                <div class="pl-3">
                                    <h6 class="mb-1">
                                        <a href="{{ route('extranet.eventos.show', $evento->id) }}" class="text-dark">
                                            {{ Str::limit($evento->titulo, 40) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted d-block">
                                        <i class="mdi mdi-calendar"></i>
                                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('DD MMM') }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="mdi mdi-clock-outline"></i>
                                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No hay eventos próximos</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<!-- Incluir FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        height: 'auto',
        events: function(info, successCallback, failureCallback) {
            // Cargar eventos desde la API
            fetch('{{ route('extranet.eventos.calendario-data') }}?start=' + info.startStr + '&end=' + info.endStr)
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error cargando eventos:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            // Redirigir al detalle del evento
            window.location.href = info.event.url;
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventDidMount: function(info) {
            // Agregar tooltip con información del evento
            info.el.title = info.event.title + '\n' +
                           'Hora: ' + info.event.startStr.split('T')[1]?.substring(0,5) || '';
        },
        // Estilo de los eventos
        eventClassNames: function(arg) {
            return ['fc-event-custom'];
        }
    });

    calendar.render();
});
</script>

<style>
/* Estilos personalizados para FullCalendar */
.fc-event-custom {
    cursor: pointer;
    border: none !important;
    padding: 2px 4px !important;
}

.fc-event-custom:hover {
    opacity: 0.8;
}

.fc .fc-button-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.fc .fc-button-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #0056b3;
    border-color: #0056b3;
}

.fc-daygrid-event {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Mejorar la apariencia del calendario */
.fc {
    font-family: inherit;
}

.fc-theme-standard td, .fc-theme-standard th {
    border-color: #dee2e6;
}

.fc-col-header-cell {
    background-color: #f8f9fa;
    font-weight: 600;
}

.fc-daygrid-day-number {
    padding: 4px;
}

.fc-day-today {
    background-color: #fff3cd !important;
}
</style>
@endsection
