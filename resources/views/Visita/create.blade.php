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
                <form action="{{ route('Visita.create') }}" method="POST">
                    @csrf
                    <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select name="REG_TIPO_ID" id="REG_TIPO_ID" class="form-control">
                                    <option value="CC">CC</option>
                                    <option value="TI">TI</option>
                                    <option value="OT">OT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Numero de indetificacion</label>
                                <input type="number" class="form-control" id="REG_CEDULA" name="REG_CEDULA" required pattern="[0-9]+">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input type="text" class="form-control" id="REG_NOMBRE" name="REG_NOMBRE" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Empresa</label>
                                <input type="text" class="form-control" id="REG_EMPRESA" name="REG_EMPRESA" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo de ingreso</label>
                                <input type="text" class="form-control" id="REG_MOTIVO_INGRESO" name="REG_MOTIVO_INGRESO" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipo de computo</label>
                                <select name="REG_EQUIPO" id="REG_EQUIPO" class="form-control">
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Serial</label>
                                <input type="text" class="form-control" id="REG_SERIAL" name="REG_SERIAL" required>
                            </div>
                        </div>
                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
