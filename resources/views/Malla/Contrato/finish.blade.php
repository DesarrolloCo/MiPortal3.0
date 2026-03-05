<!-- .modal for add task -->
<div class="modal fade" id="Finalizar_{{$con->EMC_ID}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fecha de finalizacion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Contrato.finish', $con->EMC_ID) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="EMC_FECHA_FINALIZADO" id="EMC_FECHA_FINALIZADO" class="form-control" required>
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
