<!-- Modal: Asignar Jornada -->
<div class="modal fade" id="modal_working_day" tabindex="-1" role="dialog" aria-labelledby="modalWorkingDayLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalWorkingDayLabel">
                    <i class="mdi mdi-calendar-range"></i> Asignar Jornada
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('Individual.working_day') }}" method="POST" id="form_working_day" autocomplete="off">
                @csrf
                <input type="hidden" value="{{ $list->EMP_ID }}" name="id_empleado">
                <input type="hidden" name="USER_ID" value="{{ Auth::user()->id }}">

                <div class="modal-body">
                    <!-- Alert Info -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        <strong>Información:</strong> Asigne una jornada laboral predefinida al empleado. La jornada incluye un rango de horas configurado previamente.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Campaign Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CLI_ID_jornada">
                                    <i class="mdi mdi-domain"></i> Cliente <span class="text-danger">*</span>
                                </label>
                                <select name="CLI_ID" id="CLI_ID_jornada" class="form-control" required>
                                    <option value="" selected disabled>-- Seleccione un cliente --</option>
                                    @foreach ($clientes as $cli)
                                        <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Seleccione el cliente para cargar las campañas</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CAM_ID_jornada">
                                    <i class="mdi mdi-folder-outline"></i> Campaña <span class="text-danger">*</span>
                                </label>
                                <select name="CAM_ID" id="CAM_ID_jornada" class="form-control" required disabled>
                                    <option value="">-- Primero seleccione un cliente --</option>
                                </select>
                                <small class="form-text text-muted">Campaña donde se asignará la jornada</small>
                            </div>
                        </div>
                    </div>

                    <!-- Shift Selection -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="JOR_ID">
                                    <i class="mdi mdi-clock-check"></i> Jornada <span class="text-danger">*</span>
                                </label>
                                <select name="JOR_ID" id="JOR_ID" class="form-control" required>
                                    <option value="">-- Seleccione una jornada --</option>
                                    @foreach ($jornadas as $jor)
                                        <option value="{{ $jor->JOR_ID }}"
                                                data-inicio="{{ $jor->horaInicio->HOR_INICIO ?? 'N/A' }}"
                                                data-final="{{ $jor->horaFinal->HOR_FINAL ?? 'N/A' }}">
                                            {{ $jor->JOR_NOMBRE }}
                                            @if($jor->horaInicio && $jor->horaFinal)
                                                ({{ $jor->horaInicio->HOR_INICIO }} - {{ $jor->horaFinal->HOR_FINAL }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Seleccione la jornada laboral a asignar</small>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FECHA_INICIAL_jornada">
                                    <i class="mdi mdi-calendar-start"></i> Fecha Inicial <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="FECHA_INICIAL" id="FECHA_INICIAL_jornada"
                                       required min="{{ date('Y-m-d') }}">
                                <small class="form-text text-muted">Fecha de inicio del período</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FECHA_FINAL_jornada">
                                    <i class="mdi mdi-calendar-end"></i> Fecha Final <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="FECHA_FINAL" id="FECHA_FINAL_jornada"
                                       required min="{{ date('Y-m-d') }}">
                                <small class="form-text text-muted">Fecha de fin del período</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="mdi mdi-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info" id="btn_submit_jornada">
                        <i class="mdi mdi-content-save"></i> Guardar Jornada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle client change to load campaigns for jornada modal
    $('#CLI_ID_jornada').on('change', function() {
        var cliId = $(this).val();
        var camSelect = $('#CAM_ID_jornada');

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
            camSelect.html('<option value="">-- Primero seleccione un cliente --</option>').prop('disabled', true);
        }
    });

    // Validate date range for jornada
    $('#FECHA_INICIAL_jornada, #FECHA_FINAL_jornada').on('change', function() {
        var fechaInicial = $('#FECHA_INICIAL_jornada').val();
        var fechaFinal = $('#FECHA_FINAL_jornada').val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            alert('La fecha inicial no puede ser mayor que la fecha final');
            $(this).val('');
        }
    });

    // Form submission with loading state
    $('#form_working_day').on('submit', function() {
        var btn = $('#btn_submit_jornada');
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Guardando...');
    });

    // Reset form when modal closes
    $('#modal_working_day').on('hidden.bs.modal', function() {
        $('#form_working_day')[0].reset();
        $('#CAM_ID_jornada').html('<option value="">-- Primero seleccione un cliente --</option>').prop('disabled', true);
        $('#btn_submit_jornada').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Guardar Jornada');
    });
});
</script>
