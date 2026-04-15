<!-- Modal: Editar Horario -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditLabel">
                    <i class="mdi mdi-pencil"></i> Editar Horario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('Individual.edit') }}" method="POST" id="form_edit" autocomplete="off">
                @csrf
                <input type="hidden" id="EMP_ID" name="EMP_ID" value="{{ $list->EMP_ID }}">

                <div class="modal-body">
                    <!-- Alert Info -->
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-outline"></i>
                        <strong>Atención:</strong> Edite los horarios existentes del empleado para una fecha específica o un rango de fechas.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Date Type Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <i class="mdi mdi-calendar"></i> Tipo de Búsqueda
                                </label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="date_type_single" name="DATE_TYPE" value="single"
                                               class="custom-control-input" checked>
                                        <label class="custom-control-label" for="date_type_single">
                                            <i class="mdi mdi-calendar-today"></i> Fecha Específica
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="date_type_range" name="DATE_TYPE" value="range"
                                               class="custom-control-input">
                                        <label class="custom-control-label" for="date_type_range">
                                            <i class="mdi mdi-calendar-range"></i> Rango de Fechas
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Date Section -->
                    <div id="single-date-section" class="date-section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FECHA_EDIT">
                                        <i class="mdi mdi-calendar"></i> Fecha a Editar <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="FECHA" id="FECHA_EDIT" class="form-control">
                                    <small class="form-text text-muted">Seleccione la fecha del horario a editar</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Section -->
                    <div id="date-range-section" class="date-section" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FECHA_INICIAL_EDIT">
                                        <i class="mdi mdi-calendar-start"></i> Fecha Inicial <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL_EDIT" class="form-control">
                                    <small class="form-text text-muted">Inicio del rango a editar</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FECHA_FINAL_EDIT">
                                        <i class="mdi mdi-calendar-end"></i> Fecha Final <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="FECHA_FINAL" id="FECHA_FINAL_EDIT" class="form-control">
                                    <small class="form-text text-muted">Fin del rango a editar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="mdi mdi-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning" id="btn_submit_edit">
                        <i class="mdi mdi-pencil"></i> Continuar a Edición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const dateTypeRadios = $('input[name="DATE_TYPE"]');
    const singleDateSection = $('#single-date-section');
    const dateRangeSection = $('#date-range-section');

    // Handle date type change
    dateTypeRadios.on('change', function() {
        if ($(this).val() === 'single') {
            singleDateSection.show();
            dateRangeSection.hide();
            // Clear range dates
            $('#FECHA_INICIAL_EDIT').val('');
            $('#FECHA_FINAL_EDIT').val('');
        } else {
            singleDateSection.hide();
            dateRangeSection.show();
            // Clear single date
            $('#FECHA_EDIT').val('');
        }
    });

    // Validate date range
    $('#FECHA_INICIAL_EDIT, #FECHA_FINAL_EDIT').on('change', function() {
        var fechaInicial = $('#FECHA_INICIAL_EDIT').val();
        var fechaFinal = $('#FECHA_FINAL_EDIT').val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            alert('La fecha inicial no puede ser mayor que la fecha final');
            $(this).val('');
        }
    });

    // Form validation on submit
    $('#form_edit').on('submit', function(e) {
        var dateType = $('input[name="DATE_TYPE"]:checked').val();
        var isValid = true;
        var errorMessage = '';

        if (dateType === 'single') {
            var fecha = $('#FECHA_EDIT').val();
            if (!fecha) {
                isValid = false;
                errorMessage = 'Por favor seleccione una fecha';
            }
        } else {
            var fechaInicial = $('#FECHA_INICIAL_EDIT').val();
            var fechaFinal = $('#FECHA_FINAL_EDIT').val();

            if (!fechaInicial || !fechaFinal) {
                isValid = false;
                errorMessage = 'Por favor seleccione tanto la fecha inicial como la final';
            } else if (fechaInicial > fechaFinal) {
                isValid = false;
                errorMessage = 'La fecha inicial no puede ser mayor que la fecha final';
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }

        // Show loading state
        var btn = $('#btn_submit_edit');
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Cargando...');
    });

    // Reset form when modal closes
    $('#modal_edit').on('hidden.bs.modal', function() {
        $('#form_edit')[0].reset();
        $('input[name="DATE_TYPE"][value="single"]').prop('checked', true);
        singleDateSection.show();
        dateRangeSection.hide();
        $('#btn_submit_edit').prop('disabled', false).html('<i class="mdi mdi-pencil"></i> Continuar a Edición');
    });

    // Initialize - ensure correct section is shown
    var initialDateType = $('input[name="DATE_TYPE"]:checked').val();
    if (initialDateType === 'single') {
        singleDateSection.show();
        dateRangeSection.hide();
    } else {
        singleDateSection.hide();
        dateRangeSection.show();
    }
});
</script>
