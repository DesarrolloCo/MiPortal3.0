<!-- .modal for add task -->
<div class="modal fade" id="Edit_Maintenance{{ $list_mantenimiento->MAN_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">
                    <i class="mdi mdi-clipboard-check"></i> Registrar Mantenimiento
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Información del equipo -->
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="mdi mdi-laptop"></i> Equipo:</strong> {{ $list_mantenimiento->EQU_NOMBRE }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="mdi mdi-calendar"></i> Fecha Programada:</strong>
                            {{ \Carbon\Carbon::parse($list_mantenimiento->MAN_FECHA)->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong><i class="mdi mdi-domain"></i> Proveedor:</strong> {{ $list_mantenimiento->MAN_PROVEEDOR }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="mdi mdi-account"></i> Técnico:</strong> {{ $list_mantenimiento->EMP_NOMBRES }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('Mantenimiento.maintenance') }}" method="POST" novalidate id="formMaintenance{{ $list_mantenimiento->MAN_ID }}">
                    @csrf
                    <input type="hidden" name="MAN_ID" value="{{ $list_mantenimiento->MAN_ID }}">
                    <input type="hidden" name="EQU_ID" value="{{ $list_mantenimiento->EQU_ID }}">
                    <input type="hidden" name="MAN_PROVEEDOR" value="{{ $list_mantenimiento->MAN_PROVEEDOR }}">
                    <input type="hidden" name="MAN_FECHA" value="{{ $list_mantenimiento->MAN_FECHA }}">
                    <input type="hidden" name="MAN_TECNICO" value="{{ $list_mantenimiento->MAN_TECNICO }}">

                    <div class="form-group">
                        <label for="MAS_TIPO{{ $list_mantenimiento->MAN_ID }}">
                            <i class="mdi mdi-checkbox-marked-circle"></i> Tipo de Mantenimiento <span class="text-danger">*</span>
                        </label>
                        <select name="MAS_TIPO" id="MAS_TIPO{{ $list_mantenimiento->MAN_ID }}" class="form-control" required>
                            <option value="">-- Seleccione el tipo --</option>
                            <option value="Preventivo">
                                <i class="mdi mdi-shield-check"></i> Preventivo - Mantenimiento programado regular
                            </option>
                            <option value="Correctivo">
                                <i class="mdi mdi-wrench"></i> Correctivo - Reparación de fallas
                            </option>
                            <option value="Proveedor">
                                <i class="mdi mdi-domain"></i> Proveedor - Servicio externo especializado
                            </option>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar el tipo de mantenimiento</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-cog"></i> Mantenimiento Físico</label>
                                <div class="maintenance-checkboxes">
                                    @foreach ($mantenimiento_fisicos as $mantenimiento_fisico)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   name="TIP_ID_FIS[]"
                                                   id="TIP_ID_FIS{{ $list_mantenimiento->MAN_ID }}_{{ $mantenimiento_fisico->TIP_ID }}"
                                                   value="{{ $mantenimiento_fisico->TIP_ID }}">
                                            <label class="custom-control-label"
                                                   for="TIP_ID_FIS{{ $list_mantenimiento->MAN_ID }}_{{ $mantenimiento_fisico->TIP_ID }}">
                                                {{ $mantenimiento_fisico->TIP_NOMBRE }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">Seleccione los tipos de mantenimiento físico realizados</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-monitor"></i> Mantenimiento Lógico</label>
                                <div class="maintenance-checkboxes">
                                    @foreach ($mantenimiento_logicos as $mantenimiento_logico)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   name="TIP_ID_LOG[]"
                                                   id="TIP_ID_LOG{{ $list_mantenimiento->MAN_ID }}_{{ $mantenimiento_logico->TIP_ID }}"
                                                   value="{{ $mantenimiento_logico->TIP_ID }}">
                                            <label class="custom-control-label"
                                                   for="TIP_ID_LOG{{ $list_mantenimiento->MAN_ID }}_{{ $mantenimiento_logico->TIP_ID }}">
                                                {{ $mantenimiento_logico->TIP_NOMBRE }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">Seleccione los tipos de mantenimiento lógico realizados</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="MAS_ACTIVIDAD{{ $list_mantenimiento->MAN_ID }}">
                            <i class="mdi mdi-text-box-multiple"></i> Actividades Realizadas <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control"
                                  rows="4"
                                  name="MAS_ACTIVIDAD"
                                  id="MAS_ACTIVIDAD{{ $list_mantenimiento->MAN_ID }}"
                                  placeholder="Describa detalladamente las actividades realizadas durante el mantenimiento..."
                                  required></textarea>
                        <small class="form-text text-muted">
                            Incluya: diagnóstico inicial, trabajos realizados, repuestos cambiados, observaciones finales
                        </small>
                        <div class="invalid-feedback">Debe describir las actividades realizadas</div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="mdi mdi-information"></i>
                        <strong>Importante:</strong> Al guardar, este mantenimiento se marcará como completado y se programará automáticamente el próximo mantenimiento en 6 meses.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Completar Mantenimiento
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
    .modal-header.bg-primary {
        background-color: #007bff !important;
    }

    .maintenance-checkboxes {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 10px;
        background-color: #f8f9fa;
    }

    .custom-control-label {
        cursor: pointer;
        user-select: none;
    }

    .custom-control-input:checked ~ .custom-control-label {
        font-weight: 600;
        color: #28a745;
    }
</style>

<script>
    $(document).ready(function() {
        // Validación del formulario de mantenimiento
        $('#formMaintenance{{ $list_mantenimiento->MAN_ID }}').on('submit', function(e) {
            var form = this;

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(form).addClass('was-validated');
        });

        // Resetear validación al cerrar el modal
        $('#Edit_Maintenance{{ $list_mantenimiento->MAN_ID }}').on('hidden.bs.modal', function() {
            $('#formMaintenance{{ $list_mantenimiento->MAN_ID }}').removeClass('was-validated');
            $('#formMaintenance{{ $list_mantenimiento->MAN_ID }}')[0].reset();
        });
    });
</script>
