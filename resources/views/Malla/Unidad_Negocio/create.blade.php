<!-- .modal for add task -->
<div class="modal fade" id="Add_UniNegocios" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar unidad de negocio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Unidad_Negocio.create') }}" method="POST" name="form-data" novalidate>
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="USER_ID" name="USER_ID">
                    <div class="form-group">
                        <label>Codigo</label>
                        <input type="number" class="form-control" id="UNI_CODE" name="UNI_CODE" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="UNI_NOMBRE" name="UNI_NOMBRE" required>
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
