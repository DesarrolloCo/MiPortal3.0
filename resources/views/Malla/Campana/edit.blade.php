<!-- .modal for add task -->
<div class="modal fade" id="EditCampana{{ $list->CAM_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar campaña</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Campana.update', $list->CAM_ID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="USER_ID" name="USER_ID" value="{{ Auth::user()->id }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Codigo</label>
                                <select name="UNC_ID" id="UNC_ID" class="form-control">
                                    <option selected value="{{ $list->UNC_ID }}">{{ $list->UNI_NOMBRE }} - {{ $list->CLI_NOMBRE }}</option>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach ($uni_clis as $unc)
                                        <option value="{{ $unc->UNC_ID }}">{{ $unc->UNI_NOMBRE }} - {{ $unc->CLI_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Codigo</label>
                                <input type="number" class="form-control" id="CAM_CODE" name="CAM_CODE" value="{{ $list->CAM_CODE }}" required pattern="[0-9]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="CAM_NOMBRE" name="CAM_NOMBRE" value="{{ $list->CAM_NOMBRE }}" required >
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
