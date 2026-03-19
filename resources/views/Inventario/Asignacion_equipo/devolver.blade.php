<!-- Modal para registrar devolución de equipo -->
<div class="modal fade" id="Devolver_equ{{ $row->EAS_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title text-white">
                    <i class="mdi mdi-keyboard-return"></i> Registrar Devolución de Equipo
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Asignacion_equipo.devolver', $row->EAS_ID) }}" method="POST" novalidate>
                    @csrf

                    <!-- Información de la asignación -->
                    <div class="alert alert-info">
                        <strong>Empleado:</strong> {{ $row->EMP_NOMBRES }}<br>
                        <strong>Equipo:</strong> {{ $row->EQU_NOMBRE }} - {{ $row->EQU_SERIAL }}<br>
                        <strong>Fecha de Entrega:</strong> {{ $row->EAS_FECHA_ENTREGA }}
                    </div>

                    <div class="row">
                        <!-- Fecha de devolución -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DEV_FECHA_DEVOLUCION">Fecha de Devolución <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="DEV_FECHA_DEVOLUCION" name="DEV_FECHA_DEVOLUCION"
                                       value="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback">Este campo es requerido</div>
                            </div>
                        </div>

                        <!-- Estado del equipo -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DEV_ESTADO_EQUIPO">Estado del Equipo <span class="text-danger">*</span></label>
                                <select class="form-control" id="DEV_ESTADO_EQUIPO" name="DEV_ESTADO_EQUIPO" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Bueno" selected>Bueno</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Malo">Malo</option>
                                </select>
                                <div class="invalid-feedback">Seleccione el estado del equipo</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Recibido por -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DEV_RECIBIDO_POR">Recibido Por (Empleado)</label>
                                <select class="form-control" id="DEV_RECIBIDO_POR" name="DEV_RECIBIDO_POR">
                                    <option value="">Seleccione un empleado...</option>
                                    @foreach($exc_emp as $empleado)
                                        <option value="{{ $empleado->EMP_ID }}">{{ $empleado->EMP_NOMBRES }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Empleado que recibe el equipo devuelto</small>
                            </div>
                        </div>
                    </div>

                    <!-- Verificaciones -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="d-block mb-2"><strong>Verificaciones:</strong></label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="DEV_HARDWARE_COMPLETO{{ $row->EAS_ID }}"
                                       name="DEV_HARDWARE_COMPLETO" value="1" checked>
                                <label class="custom-control-label" for="DEV_HARDWARE_COMPLETO{{ $row->EAS_ID }}">
                                    Hardware Completo
                                </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="DEV_SOFTWARE_COMPLETO{{ $row->EAS_ID }}"
                                       name="DEV_SOFTWARE_COMPLETO" value="1" checked>
                                <label class="custom-control-label" for="DEV_SOFTWARE_COMPLETO{{ $row->EAS_ID }}">
                                    Software Completo
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Observaciones -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DEV_OBSERVACIONES">Observaciones Generales</label>
                                <textarea class="form-control" id="DEV_OBSERVACIONES" name="DEV_OBSERVACIONES"
                                          rows="3" placeholder="Ingrese observaciones generales sobre la devolución..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Daños reportados -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DEV_DANOS_REPORTADOS">Daños Reportados</label>
                                <textarea class="form-control" id="DEV_DANOS_REPORTADOS" name="DEV_DANOS_REPORTADOS"
                                          rows="3" placeholder="Describa cualquier daño encontrado en el equipo..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Faltantes -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DEV_FALTANTES">Faltantes</label>
                                <textarea class="form-control" id="DEV_FALTANTES" name="DEV_FALTANTES"
                                          rows="3" placeholder="Liste los componentes o accesorios faltantes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="mdi mdi-keyboard-return"></i> Registrar Devolución
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
