<!-- .modal for add task -->
<div class="modal fade" id="Add_Visita" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar visita</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Visita.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de documento <span class="text-danger">*</span></label>
                                <select name="REG_TIPO_ID" id="REG_TIPO_ID" class="form-control" required>
                                    <option value="CC">CC - Cédula de Ciudadanía</option>
                                    <option value="TI">TI - Tarjeta de Identidad</option>
                                    <option value="CE">CE - Cédula de Extranjería</option>
                                    <option value="PA">PA - Pasaporte</option>
                                    <option value="OT">OT - Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número de identificación <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="REG_CEDULA" name="REG_CEDULA"
                                       required pattern="[0-9]+" maxlength="50"
                                       placeholder="Ej: 1234567890">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="REG_NOMBRE" name="REG_NOMBRE"
                                       required maxlength="255"
                                       placeholder="Nombre completo del visitante">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Empresa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="REG_EMPRESA" name="REG_EMPRESA"
                                       required maxlength="255"
                                       placeholder="Empresa que representa">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo de ingreso <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="REG_MOTIVO_INGRESO" name="REG_MOTIVO_INGRESO"
                                          required maxlength="500" rows="3"
                                          placeholder="Describa el motivo de la visita"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>¿Trae equipo de cómputo?</label>
                                <select name="REG_EQUIPO" id="REG_EQUIPO" class="form-control">
                                    <option value="NO">No trae equipo</option>
                                    <option value="Portátil">Portátil</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Disco Duro">Disco Duro</option>
                                    <option value="USB">USB</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Serial del equipo</label>
                                <input type="text" class="form-control" id="REG_SERIAL" name="REG_SERIAL"
                                       maxlength="255" placeholder="Serial (opcional)">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check"></i> Registrar Ingreso
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
