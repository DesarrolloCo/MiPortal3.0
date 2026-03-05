<!-- .modal for add task -->
<div class="modal fade" id="Edit_Cambio{{ $har_asignado->HAS_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" method="POST" action="{{ route('Equipo.change', $har_asignado->HAS_ID) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label>Detalles del cambio</label>
                        <textarea name="HAS_COMENTARIO" id="" cols="30" rows="3" class="form-control">{{ $har_asignado->HAS_COMENTARIO }}</textarea>
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
