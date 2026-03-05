<!-- .modal for add task -->
<div class="modal fade" id="modal_novedad" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Novedades</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Individual.time_status', $list->MAL_ID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="USER_ID" id="USER_ID" value="{{ Auth::user()->id }}" required>
                    <input type="hidden" name="MAL_ID" id="MAL_ID" value="{{ $list->MAL_ID }}" required>
                    <input type="hidden" name="MAL_DIA" id="MAL_DIA" value="{{ $MAL_DIA }}" required>
                    <input type="hidden" name="EMP_ID" id="EMP_ID" value="{{ $EMP_ID }}" required>
                    <input type="hidden" name="MAL_ESTADO" value="0" required>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select name="TIN_ID" id="TIN_ID" class="form-control">
                                    <option disabled selected>-- Seleccione --</option>
                                    @foreach ($tipos_novedades as $tip)
                                        <option value="{{ $tip->TIN_ID }}">{{ $tip->TIN_NOMBRE }}</option>
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
