<!-- Modal: Asignar Horas -->
<div class="modal fade" id="modal_hour" tabindex="-1" role="dialog" aria-labelledby="modalHourLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalHourLabel">
                    <i class="mdi mdi-clock-outline"></i> Asignar Horas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('Individual.hour') }}" method="POST" id="form_hour" autocomplete="off">
                @csrf
                <input type="hidden" value="{{ $list->EMP_ID }}" name="id_empleado">
                <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">

                <div class="modal-body">
                    <!-- Alert Info -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        <strong>Información:</strong> Asigne un rango de horas específico para el empleado en el período seleccionado.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Campaign Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CLI_ID_hour">
                                    <i class="mdi mdi-domain"></i> Cliente <span class="text-danger">*</span>
                                </label>
                                <select name="CLI_ID" id="CLI_ID_hour" class="form-control" required>
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
                                <label for="CAM_ID_hour">
                                    <i class="mdi mdi-folder-outline"></i> Campaña <span class="text-danger">*</span>
                                </label>
                                <select name="CAM_ID" id="CAM_ID_hour" class="form-control" required disabled>
                                    <option value="">-- Primero seleccione un cliente --</option>
                                </select>
                                <small class="form-text text-muted">Campaña donde se asignarán las horas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Hour Range -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="HORA_INICIAL_hour">
                                    <i class="mdi mdi-clock-start"></i> Hora Inicial <span class="text-danger">*</span>
                                </label>
                                <select name="HORA_INICIAL" id="HORA_INICIAL_hour" class="form-control" required>
                                    <option value="">-- Seleccione hora inicial --</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_INICIO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="HORA_FINAL_hour">
                                    <i class="mdi mdi-clock-end"></i> Hora Final <span class="text-danger">*</span>
                                </label>
                                <select name="HORA_FINAL" id="HORA_FINAL_hour" class="form-control" required>
                                    <option value="">-- Seleccione hora final --</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_FINAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FECHA_INICIAL_hour">
                                    <i class="mdi mdi-calendar-start"></i> Fecha Inicial <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="FECHA_INICIAL" id="FECHA_INICIAL_hour" required>
                                <small class="form-text text-muted">Fecha de inicio del período</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FECHA_FINAL_hour">
                                    <i class="mdi mdi-calendar-end"></i> Fecha Final <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" name="FECHA_FINAL" id="FECHA_FINAL_hour" required>
                                <small class="form-text text-muted">Fecha de fin del período</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="mdi mdi-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn_submit_hour">
                        <i class="mdi mdi-content-save"></i> Guardar Horarios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle client change to load campaigns for hour modal
    $('#CLI_ID_hour').on('change', function() {
        var cliId = $(this).val();
        var camSelect = $('#CAM_ID_hour');

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

    // Validate hour range
    $('#HORA_INICIAL_hour, #HORA_FINAL_hour').on('change', function() {
        var horaInicial = parseInt($('#HORA_INICIAL_hour').val());
        var horaFinal = parseInt($('#HORA_FINAL_hour').val());

        if (horaInicial && horaFinal && horaInicial >= horaFinal) {
            alert('La hora inicial debe ser menor que la hora final');
            $(this).val('');
        }
    });

    // Validate date range
    $('#FECHA_INICIAL_hour, #FECHA_FINAL_hour').on('change', function() {
        var fechaInicial = $('#FECHA_INICIAL_hour').val();
        var fechaFinal = $('#FECHA_FINAL_hour').val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            alert('La fecha inicial no puede ser mayor que la fecha final');
            $(this).val('');
        }
    });

    // Form submission with loading state
    $('#form_hour').on('submit', function() {
        var btn = $('#btn_submit_hour');
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Guardando...');
    });

    // Reset form when modal closes
    $('#modal_hour').on('hidden.bs.modal', function() {
        $('#form_hour')[0].reset();
        $('#CAM_ID_hour').html('<option value="">-- Primero seleccione un cliente --</option>').prop('disabled', true);
        $('#btn_submit_hour').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Guardar Horarios');
    });
});
</script>
