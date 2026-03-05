<!-- .modal for add task -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar horario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Individual.edit') }}" method="POST">
                    @csrf
                    <input type="hidden" id="EMP_ID" name="EMP_ID" value="{{ $list->EMP_ID }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <input type="radio" name="DATE_TYPE" value="single" checked> Fecha específica
                                </label>
                                <label class="form-label ml-3">
                                    <input type="radio" name="DATE_TYPE" value="range"> Rango de fechas
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="single-date-section" class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Fecha a editar</label>
                                <input type="date" name="FECHA" id="FECHA_EDIT" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="date-range-section" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha inicial</label>
                                    <input type="date" name="FECHA_INICIAL" id="FECHA_INICIAL_EDIT" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha final</label>
                                    <input type="date" name="FECHA_FINAL" id="FECHA_FINAL_EDIT" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Editar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateTypeRadios = document.querySelectorAll('input[name="DATE_TYPE"]');
    const singleDateSection = document.getElementById('single-date-section');
    const dateRangeSection = document.getElementById('date-range-section');

    dateTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'single') {
                singleDateSection.style.display = 'block';
                dateRangeSection.style.display = 'none';
                // Solo limpiar si estamos cambiando desde range a single
                if (dateRangeSection.style.display !== 'none') {
                    document.getElementById('FECHA_INICIAL_EDIT').value = '';
                    document.getElementById('FECHA_FINAL_EDIT').value = '';
                }
            } else {
                singleDateSection.style.display = 'none';
                dateRangeSection.style.display = 'block';
                // Solo limpiar si estamos cambiando desde single a range
                if (singleDateSection.style.display !== 'none') {
                    document.getElementById('FECHA_EDIT').value = '';
                }
            }
        });
    });

    // Initialize form state - show correct section without clearing fields
    const initialRadio = document.querySelector('input[name="DATE_TYPE"]:checked');
    if (initialRadio) {
        if (initialRadio.value === 'single') {
            singleDateSection.style.display = 'block';
            dateRangeSection.style.display = 'none';
        } else {
            singleDateSection.style.display = 'none';
            dateRangeSection.style.display = 'block';
        }
    }
});

// Validation function
function validateForm() {
    const dateType = document.querySelector('input[name="DATE_TYPE"]:checked').value;

    // Debug: Verificar que los elementos existan
    const fechaInicialElement = document.getElementById('FECHA_INICIAL_EDIT');
    const fechaFinalElement = document.getElementById('FECHA_FINAL_EDIT');
    const fechaElement = document.getElementById('FECHA_EDIT');

    console.log('Elements Debug:', {
        fechaInicialElement: fechaInicialElement,
        fechaFinalElement: fechaFinalElement,
        fechaElement: fechaElement,
        fechaInicialExists: fechaInicialElement !== null,
        fechaFinalExists: fechaFinalElement !== null
    });

    const fechaInicial = fechaInicialElement ? fechaInicialElement.value : 'ELEMENT_NOT_FOUND';
    const fechaFinal = fechaFinalElement ? fechaFinalElement.value : 'ELEMENT_NOT_FOUND';
    const fecha = fechaElement ? fechaElement.value : 'ELEMENT_NOT_FOUND';

    console.log('Validation Debug:', {
        dateType: dateType,
        fechaInicial: fechaInicial,
        fechaFinal: fechaFinal,
        fecha: fecha,
        isRange: dateType === 'range'
    });

    if (dateType === 'single') {
        if (!fecha) {
            alert('Por favor seleccione una fecha');
            return false;
        }
    } else {
        if (!fechaInicial || !fechaFinal) {
            alert('Por favor seleccione tanto la fecha inicial como la final. Inicial: "' + fechaInicial + '", Final: "' + fechaFinal + '"');
            return false;
        }

        if (fechaInicial > fechaFinal) {
            alert('La fecha inicial no puede ser mayor que la fecha final');
            return false;
        }
    }

    return true;
}
</script>
