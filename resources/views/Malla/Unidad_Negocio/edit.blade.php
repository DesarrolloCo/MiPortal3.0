<div class="modal fade" id="EditUnidadNegocio{{ $list->UNI_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar unidad de negocio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('Unidad_Negocio.update', $list->UNI_ID) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Codigo</label>
                        <input type="text" class="form-control" value="{{ $list->UNI_CODE }}" id="UNI_CODE" name="UNI_CODE" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="email" class="form-control" value="{{ $list->UNI_NOMBRE }}" id="UNI_NOMBRE" name="UNI_NOMBRE" required>
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
