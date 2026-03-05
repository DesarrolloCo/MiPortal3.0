<!-- .modal for add task -->
<div class="modal fade" id="editModal{{ $User->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{-- {{ route('Uni_cli.update', $list->UNC_ID) }} --}}" method="POST" name="form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden"name="USER_ID" value="{{ Auth::user()->id }}">
                    <div class="form-group">
                       <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $User->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electronico</label>
                        <input type="text" name="email" id="email" class="form-control" value="{{ $User->email }}">
                    </div>
                    <div class="form-group">
                        <label for="roles">Rol</label>
                        {{-- <input type="roles" name="roles" id="roles" class="form-control" value="{{ $roles->name }}"> --}}
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
