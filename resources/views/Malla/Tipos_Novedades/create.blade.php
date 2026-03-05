<!-- Modal para agregar tipo de novedad -->
<div class="modal" id="Add_TiposNovedades" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar tipo de novedad</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('TiposNovedades.create') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nombre <small class="text-muted">(máximo 25 caracteres)</small></label>
                        <input type="text" class="form-control" name="TIN_NOMBRE" maxlength="25" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select class="form-control" name="TIN_TIPO" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="1">Suma horas</option>
                            <option value="0">Resta horas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Concepto SIIGO</label>
                        <select class="form-control" name="COD_SIIGO">
                            <option value="">Seleccione un concepto SIIGO</option>
                            @foreach($conceptosSiigo as $concepto)
                                <option value="{{ $concepto->CODIGO }}">{{ $concepto->CODIGO }} - {{ $concepto->NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>