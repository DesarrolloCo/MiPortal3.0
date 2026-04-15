<!-- .modal for add task -->
<div class="modal fade" id="Add_Mantenimiento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title">
                    <i class="mdi mdi-clipboard-plus"></i> Agregar Plan de Mantenimiento
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Nota:</strong> El sistema programará automáticamente el próximo mantenimiento 6 meses después de la fecha seleccionada.
                </div>

                <form action="{{ route('Mantenimiento.create') }}" method="POST" novalidate id="formMantenimiento">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MAN_FECHA">
                                    <i class="mdi mdi-calendar"></i> Fecha de Mantenimiento <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="MAN_FECHA"
                                       id="MAN_FECHA"
                                       class="form-control"
                                       required
                                       min="{{ date('Y-m-d') }}">
                                <small class="form-text text-muted">Fecha programada para el mantenimiento</small>
                                <div class="invalid-feedback">La fecha de mantenimiento es requerida</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MAN_PROVEEDOR">
                                    <i class="mdi mdi-domain"></i> Proveedor <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="MAN_PROVEEDOR"
                                       id="MAN_PROVEEDOR"
                                       class="form-control"
                                       required
                                       placeholder="Nombre del proveedor o empresa">
                                <small class="form-text text-muted">Proveedor encargado del mantenimiento</small>
                                <div class="invalid-feedback">El proveedor es requerido</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ARE_ID">
                                    <i class="mdi mdi-office-building"></i> Área <span class="text-danger">*</span>
                                </label>
                                <select name="ARE_ID" id="ARE_ID" class="form-control" required>
                                    <option value="">-- Seleccione un área --</option>
                                    @foreach ($area as $list_area)
                                    <option value="{{ $list_area->ARE_ID }}">{{ $list_area->ARE_NOMBRE }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Seleccione el área para filtrar equipos</small>
                                <div class="invalid-feedback">Debe seleccionar un área</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MAN_TECNICO">
                                    <i class="mdi mdi-account-wrench"></i> Técnico Responsable <span class="text-danger">*</span>
                                </label>
                                <select name="MAN_TECNICO" id="MAN_TECNICO" class="form-control" required>
                                    <option value="">-- Seleccione un técnico --</option>
                                    @foreach ($tec_asignados as $list_tecnicos)
                                    <option value="{{ $list_tecnicos->EMP_ID }}">{{ $list_tecnicos->EMP_NOMBRES }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Técnico encargado del mantenimiento</small>
                                <div class="invalid-feedback">Debe seleccionar un técnico</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="EQU_ID">
                                    <i class="mdi mdi-laptop"></i> Equipos <span class="text-danger">*</span>
                                </label>
                                <select name="EQU_IDS[]" id="EQU_ID" multiple="multiple" class="form-control" required style="width: 100%;">
                                    <!-- Se cargará dinámicamente al seleccionar área -->
                                </select>
                                <small class="form-text text-muted">Puede seleccionar múltiples equipos (mantenga Ctrl para selección múltiple)</small>
                                <div class="invalid-feedback">Debe seleccionar al menos un equipo</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Crear Plan de Mantenimiento
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

<style>
    /* Estilos mejorados para el modal de crear mantenimiento */
    .modal-header.bg-success {
        background-color: #28a745 !important;
    }

    #Add_Mantenimiento .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    #Add_Mantenimiento select.form-control {
        border: 2px solid #e9ecef;
        border-radius: 5px;
    }
</style>

<script src="{{ asset('js/validacion.js') }}"></script>

<script>
    $(document).ready(function() {
        // Validación del formulario
        $('#formMantenimiento').on('submit', function(e) {
            var form = this;

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(form).addClass('was-validated');
        });

        // Resetear validación al abrir el modal
        $('#Add_Mantenimiento').on('hidden.bs.modal', function() {
            $('#formMantenimiento').removeClass('was-validated');
            $('#formMantenimiento')[0].reset();
            $('#EQU_ID').empty();
        });

        // Cargar equipos cuando se selecciona un área
        $('#ARE_ID').on('change', function() {
            var areaId = $(this).val();
            var equipoSelect = $('#EQU_ID');

            equipoSelect.empty();

            if (areaId) {
                $.ajax({
                    url: '/api/equipos-por-area/' + areaId,
                    type: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(index, equipo) {
                                equipoSelect.append(
                                    $('<option></option>')
                                        .val(equipo.EQU_ID)
                                        .text(equipo.EQU_NOMBRE + ' - ' + equipo.EQU_SERIAL)
                                );
                            });
                        } else {
                            equipoSelect.append('<option value="">No hay equipos disponibles</option>');
                        }
                    },
                    error: function() {
                        alert('Error al cargar los equipos');
                    }
                });
            }
        });
    });
</script>
