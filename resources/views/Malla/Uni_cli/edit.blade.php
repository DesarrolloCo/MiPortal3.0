<!-- .modal for add task -->
<div class="modal fade" id="EditRelacion{{ $list->UNC_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar relación de unidad de negocio y cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Uni_cli.update', $list->UNC_ID) }}" method="POST" name="form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden"name="USER_ID" value="{{ Auth::user()->id }}">
                    <div class="form-group">
                        <label>Unidad de negocio</label>
                        <select name="UNI_ID" id="UNI_ID" class="form-control">
                            <option value="{{$list->UNI_ID}}" selected>{{$list->UNI_NOMBRE}}</option>
                            <option class="form-control" disabled>-- Seleccione --</option>
                            @foreach ($unidad_negocios as $uni)
                                <option value="{{ $uni->UNI_ID }}">{{ $uni->UNI_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cliente</label>
                        <select name="CLI_ID" id="CLI_ID" class="form-control">
                            <option value="{{$list->CLI_ID}}" selected>{{$list->CLI_NOMBRE}}</option>
                            <option class="form-control" disabled >-- Seleccione --</option>
                            @foreach ($clientes as $cli)
                                <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                            @endforeach
                        </select>
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
