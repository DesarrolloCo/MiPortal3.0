<!-- .modal for add task -->
<div class="modal fade" id="Add_Software" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar software</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Software.create') }}" method="POST"novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label for="SOF_NOMBRE" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="SOF_NOMBRE" name="SOF_NOMBRE" required>
                        <div class="invalid-feedback">Completa los datos</div>
                    </div>
                    <div class="col-md-12">
                        <label for="SOF_VERSION" class="form-label">Versión</label>
                        <input type="text" class="form-control" id="SOF_VERSION" name="SOF_VERSION" required>
                        <div class="invalid-feedback">Completa los datos</div>
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
