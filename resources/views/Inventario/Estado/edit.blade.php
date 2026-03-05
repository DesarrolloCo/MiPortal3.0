<div class="modal fade" id="Edit_estado{{ $estado->TIE_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar estado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Estado.update', $estado->TIE_ID) }}" method="POST"novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label>Nombre</label>
                        <input type="text" name="TIE_NOMBRE" class="form-control" value="{{ $estado->TIE_NOMBRE }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

