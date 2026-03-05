<!-- .modal for add task -->
<div class="modal fade" id="EditCampana{{ $list->INF_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Informe</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Informe.update', $list->INF_ID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proyecto</label>
                                <select name="CAM_ID" id="CAM_ID" class="form-control">
                                    <option class="form-control" disabled selected>-- Seleccione --</option>
                                    @foreach ($campanas as $cam)
                                        <option value="{{ $cam->CAM_ID }}"  @if ($cam->CAM_ID == $list->CAM_ID) selected @endif>{{ $cam->CAM_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de informe</label>
                                <input type="text" class="form-control" id="INF_NOMBRE" name="INF_NOMBRE" value="{{ $list->INF_NOMBRE }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>URL</label>
                                <input type="text" class="form-control" id="INF_URL" name="INF_URL" value="{{ $list->INF_URL }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select name="CLI_ID" id="CLI_ID" class="form-control">
                                    {{-- N/A siempre primero --}}
                                    <option value="0" @if ($list->CLI_ID == 0) selected @endif>N/A</option>
                                
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" @if ($cliente->id == $list->CLI_ID) selected @endif>
                                            {{ $cliente->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
