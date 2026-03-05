<!-- .modal for add task -->
<div class="modal fade" id="Add_Tecnico" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar tecnico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Tecnico.create') }}" method="POST"novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label>Selecciona Tecnico</label>
                        <select name="EMP_ID" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            @foreach ($empleado as $list_emp)
                            <option value="{{ $list_emp->EMP_ID }}">{{ $list_emp->EMP_NOMBRES }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="TEC_ESTADO" class="form-label">Estado</label>
                        <select class="form-control" name="TEC_ESTADO" id="TEC_ESTADO">
                            <option value="">-- Seleccione --</option>
                            <option value="1">ACTIVO</option>
                            <option value="2">INACTIVO</option>
                            <option value="3">SUSPENDIDO</option>
                            <option value="4">RETIRADO</option>
                            <option value="5">ANULADO</option>
                        </select>
                        <div class="invalid-feedback">Completa los datos</div>
                        <br>
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
