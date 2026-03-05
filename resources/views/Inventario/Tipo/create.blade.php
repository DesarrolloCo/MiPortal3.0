<!-- .modal for add task -->
<div class="modal fade" id="Add_Tipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar tipo de mantenimiento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Tipo.create') }}" method="POST"novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label>Area</label>
                        <select name="TIP_TIPO" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <option value="Logico">Logico</option>
                            <option value="Fisico">Fisico</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="TIP_NOMBRE" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="TIP_NOMBRE" name="TIP_NOMBRE" required>
                        <div class="invalid-feedback">Completa los datos</div>
                    </div>
                    <div class="col-md-12">
                        <label for="TIP_DESCRIPCION" class="form-label">Descripción</label>
                        <textarea class="form-control" name="TIP_DESCRIPCION" id="TIP_DESCRIPCION" cols="20" rows="5" ></textarea>
                        <div class="invalid-feedback">Completa los datos</div>
                        <br>
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
