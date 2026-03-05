<!-- .modal for add task -->
<div class="modal fade" id="Edit_text" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar texto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{ route('Firma.change_text') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nombre de la firma</label>
                        <input type="text" class="form-control" id="PAR_VALOR" name="PAR_VALOR" value="{{ $firma_texto[0]->PAR_VALOR }}" required>
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
