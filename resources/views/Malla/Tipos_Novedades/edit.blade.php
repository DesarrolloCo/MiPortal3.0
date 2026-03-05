<!-- Modal para editar tipo de novedad -->
<div class="modal" id="EditTipoNovedad{{ $list->TIN_ID }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar tipo de novedad</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('TiposNovedades.update', $list->TIN_ID) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Nombre <small class="text-muted">(máximo 25 caracteres)</small></label>
                        <input type="text" class="form-control" value="{{ $list->TIN_NOMBRE }}" name="TIN_NOMBRE" maxlength="25" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select class="form-control" name="TIN_TIPO" required>
                            <option value="1" {{ $list->TIN_TIPO == 1 ? 'selected' : '' }}>Suma horas</option>
                            <option value="0" {{ $list->TIN_TIPO == 0 ? 'selected' : '' }}>Resta horas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Concepto SIIGO</label>
                        <select class="form-control" name="COD_SIIGO">
                            <option value="">Seleccione un concepto SIIGO</option>
                            @foreach($conceptosSiigo as $concepto)
                                <option value="{{ $concepto->CODIGO }}" {{ $list->COD_SIIGO == $concepto->CODIGO ? 'selected' : '' }}>
                                    {{ $concepto->CODIGO }} - {{ $concepto->NOMBRE }}
                                </option>
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