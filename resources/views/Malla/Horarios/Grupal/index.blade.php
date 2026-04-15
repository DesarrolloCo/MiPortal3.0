@extends('layouts.main')

@section('main')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">
            <i class="mdi mdi-account-multiple-outline"></i> Malla de Horarios Grupal
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Horarios Grupal</li>
        </ol>
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
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">
                    <i class="mdi mdi-calendar-clock text-primary"></i>
                    Asignación de Horarios Grupal
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('Grupal.create') }}" method="POST" id="form_grupal">
                    @csrf
                    <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">

                    <!-- Sección 1: Información de Campaña y Fechas -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="CLI_ID">
                                    <i class="mdi mdi-domain"></i> Cliente <span class="text-danger">*</span>
                                </label>
                                <select name="CLI_ID" id="CLI_ID" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($clientes as $cli)
                                        <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="CAM_ID">
                                    <i class="mdi mdi-folder"></i> Campaña <span class="text-danger">*</span>
                                </label>
                                <select name="CAM_ID" id="CAM_ID" class="form-control" required disabled>
                                    <option value="">-- Primero seleccione un cliente --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="FECHA_INICIAL">
                                    <i class="mdi mdi-calendar-start"></i> Fecha Inicial <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="FECHA_FINAL">
                                    <i class="mdi mdi-calendar-end"></i> Fecha Final <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="FECHA_FINAL" id="FECHA_FINAL" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Horario por Jornada -->
                    <div class="row" id="jornada">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="JOR_ID">
                                    <i class="mdi mdi-clock-outline"></i> Jornada
                                </label>
                                <select name="JOR_ID" id="JOR_ID" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($jornadas as $jor)
                                        <option value="{{ $jor->JOR_ID }}">{{ $jor->JOR_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Horario por Hora (oculto por defecto) -->
                    <div class="row" id="for_fecha" style="display: none;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="HORA_INICIAL">
                                    <i class="mdi mdi-clock-start"></i> Hora Inicial
                                </label>
                                <select name="HORA_INICIAL" id="HORA_INICIAL" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_INICIO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="HORA_FINAL">
                                    <i class="mdi mdi-clock-end"></i> Hora Final
                                </label>
                                <select name="HORA_FINAL" id="HORA_FINAL" class="form-control">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_FINAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Toggle para cambiar entre Jornada y Hora -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="check_fecha" onclick="ojito()">
                                <label class="custom-control-label" for="check_fecha">
                                    <i class="mdi mdi-swap-horizontal"></i> Activar formato por hora
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Asignar Horarios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de empleados de la campaña -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">
                    <i class="mdi mdi-account-multiple text-primary"></i>
                    Empleados de la Campaña
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="table_equipos">
                        <thead class="thead-light">
                            <tr>
                                <th><i class="mdi mdi-folder"></i> Campaña</th>
                                <th><i class="mdi mdi-account"></i> Empleado</th>
                            </tr>
                        </thead>
                        <tbody name="tablaempleados" id="tablaempleados">
                            <!-- Los empleados se cargarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="empty_state" class="text-center py-5" style="display: none;">
                    <i class="mdi mdi-account-off" style="font-size: 64px; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">No hay empleados disponibles</h5>
                    <p class="text-muted">Seleccione un cliente y campaña para ver los empleados</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

@endsection

@section('scripts')
<script src="{{ asset('js/malla_grupal.js') }}"></script>

<script>
$(document).ready(function() {
    // Handle client change to load campaigns
    $('#CLI_ID').on('change', function() {
        var cliId = $(this).val();
        var camSelect = $('#CAM_ID');

        if (cliId) {
            camSelect.prop('disabled', true).html('<option value="">Cargando campañas...</option>');

            $.ajax({
                url: '/get-campanas/' + cliId,
                type: 'GET',
                success: function(data) {
                    camSelect.html('<option value="">-- Seleccione una campaña --</option>');
                    $.each(data, function(key, value) {
                        camSelect.append('<option value="' + value.CAM_ID + '">' + value.CAM_NOMBRE + '</option>');
                    });
                    camSelect.prop('disabled', false);
                },
                error: function() {
                    camSelect.html('<option value="">Error al cargar campañas</option>');
                    alert('Error al cargar las campañas. Por favor intente nuevamente.');
                }
            });
        } else {
            camSelect.prop('disabled', true).html('<option value="">-- Primero seleccione un cliente --</option>');
        }
    });

    // Validate date range
    $('#FECHA_INICIAL, #FECHA_FINAL').on('change', function() {
        var fechaInicial = $('#FECHA_INICIAL').val();
        var fechaFinal = $('#FECHA_FINAL').val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            alert('La fecha inicial debe ser menor o igual a la fecha final');
            $(this).val('');
        }
    });

    // Form validation
    $('#form_grupal').on('submit', function(e) {
        var checkJorHor = $('#check_fecha').is(':checked');
        var isValid = true;
        var errorMessage = '';

        // Validate based on mode (jornada or hora)
        if (checkJorHor) {
            // Validar modo por hora
            var horaInicial = $('#HORA_INICIAL').val();
            var horaFinal = $('#HORA_FINAL').val();

            if (!horaInicial || !horaFinal) {
                isValid = false;
                errorMessage = 'Por favor seleccione la hora inicial y final';
            }
        } else {
            // Validar modo por jornada
            var jorId = $('#JOR_ID').val();

            if (!jorId) {
                isValid = false;
                errorMessage = 'Por favor seleccione una jornada';
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }
    });
});
</script>

<style>
.table thead.thead-light {
    background-color: #f8f9fa;
}

.table thead th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f1f3f5 !important;
    transform: scale(1.01);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.form-group label {
    font-weight: 600;
    color: #495057;
}

.form-group label i {
    margin-right: 5px;
    color: #6c757d;
}

.card-header {
    border-bottom: 2px solid #e9ecef;
}

.text-danger {
    font-weight: bold;
}
</style>
@endsection
