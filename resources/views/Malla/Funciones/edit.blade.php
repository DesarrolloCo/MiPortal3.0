<!-- .modal for add task -->
<div class="modal fade" id="EditFuncion{{ $list->EMF_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar contrato</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Funcione.update', $list->EMF_ID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- <input type="hidden" class="form-control" id="USER_ID" name="USER_ID"
                        value="{{ Auth::user()->id }}"required> --}}

                    <input type="hidden" name="EMC_ID" value="{{$emc_id}}">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="EMF_NOMBRE" name="EMF_NOMBRE" class="form-control" value="{{ $list->EMF_NOMBRE }}" required>
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
