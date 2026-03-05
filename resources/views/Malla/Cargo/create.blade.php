<!-- .modal for add task -->
<div class="modal fade" id="Add_Cargo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar cargo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Cargo.create') }}" method="POST" name="form-data" novalidate>
                    @csrf
                    <input type="number" class="form-control" id="USER_ID" name="USER_ID"
                        value="{{ Auth::user()->id }}"required pattern="[0-9]+" style="display: none;">
                    <div class="form-group">
                        <label>Codigo</label>
                        <input type="number" class="form-control" id="CAR_CODE" name="CAR_CODE" required pattern="[0-9]+">
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="CAR_NOMBRE" name="CAR_NOMBRE" required>
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
