<!-- .modal for add task -->
<div class="modal fade" id="Add_Equipos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title">
                    <i class="mdi mdi-laptop"></i> Agregar Nuevo Equipo
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Equipo.create') }}" method="POST" novalidate id="formEquipo">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-office-building"></i> Área <span class="text-danger">*</span></label>
                                <select name="ARE_ID" class="form-control" required>
                                    <option value="">-- Seleccione un área --</option>
                                    @foreach ($area as $list_area)
                                    <option value="{{ $list_area->ARE_ID }}">{{ $list_area->ARE_NOMBRE }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Debe seleccionar un área</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EQU_SERIAL"><i class="mdi mdi-barcode"></i> Serial <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EQU_SERIAL" name="EQU_SERIAL" required>
                                <small class="form-text text-muted">Número de serie único del equipo</small>
                                <div class="invalid-feedback">El serial es requerido</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="EQU_NOMBRE"><i class="mdi mdi-tag"></i> Nombre del Equipo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EQU_NOMBRE" name="EQU_NOMBRE" required>
                                <small class="form-text text-muted">Ejemplo: Laptop Dell Inspiron 15</small>
                                <div class="invalid-feedback">El nombre del equipo es requerido</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EQU_PRECIO"><i class="mdi mdi-cash"></i> Precio <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="EQU_PRECIO" name="EQU_PRECIO" required min="0" step="0.01">
                                </div>
                                <small class="form-text text-muted">Valor del equipo en pesos</small>
                                <div class="invalid-feedback">El precio es requerido</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EQU_TIPO"><i class="mdi mdi-checkbox-marked-circle"></i> Tipo de Equipo <span class="text-danger">*</span></label>
                                <select class="form-control" name="EQU_TIPO" id="EQU_TIPO" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="Propio">Propio</option>
                                    <option value="Alquilado">Alquilado</option>
                                </select>
                                <small class="form-text text-muted">Indica si es propiedad de la empresa o alquilado</small>
                                <div class="invalid-feedback">Debe seleccionar el tipo de equipo</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="EQU_OBSERVACIONES"><i class="mdi mdi-comment-text"></i> Observaciones</label>
                                <textarea class="form-control" name="EQU_OBSERVACIONES" id="EQU_OBSERVACIONES" rows="4" placeholder="Ingrese detalles adicionales, especificaciones técnicas, etc."></textarea>
                                <small class="form-text text-muted">Campo opcional para información adicional</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Guardar Equipo
                        </button>
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
    // Validación del formulario de equipos
    $(document).ready(function() {
        $('#formEquipo').on('submit', function(e) {
            var form = this;

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(form).addClass('was-validated');
        });

        // Resetear validación al abrir el modal
        $('#Add_Equipos').on('hidden.bs.modal', function() {
            $('#formEquipo').removeClass('was-validated');
            $('#formEquipo')[0].reset();
        });

        // Formatear precio con separadores de miles
        $('#EQU_PRECIO').on('blur', function() {
            var value = $(this).val();
            if (value) {
                var formatted = parseFloat(value).toFixed(2);
                $(this).val(formatted);
            }
        });
    });
</script>

<style>
    /* Estilos mejorados para el modal de crear equipo */
    .modal-header.bg-success {
        background-color: #28a745 !important;
    }

    #Add_Equipos .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    #Add_Equipos select.form-control {
        border: 2px solid #e9ecef;
        border-radius: 5px;
    }

    #Add_Equipos .input-group-text {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
</style>
