<div class="modal fade" id="Edit_Hardware{{ $hardware->HAR_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar hardware</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Hardware.update', $hardware->HAR_ID) }}" method="POST"novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label>Tipo</label>
                        <select name="HAR_TIPO" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <option value="Dispositivo" @if ($hardware->HAR_TIPO == 'Dispositivo') selected @endif>Dispositivo</option>
                            <option value="Pieza" @if ($hardware->HAR_TIPO == 'Pieza') selected @endif>Pieza</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="HAR_DESCRIPCION" class="form-label">Descripcion</label>
                        <textarea class="form-control" name="HAR_DESCRIPCION" id="HAR_DESCRIPCION" cols="20" rows="2" >{{ $hardware->HAR_DESCRIPCION }}</textarea>
                        <div class="invalid-feedback">Completa los datos</div>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <label>Modelo</label>
                        <input type="text" name="HAR_MODELO" class="form-control" value="{{ $hardware->HAR_MODELO }}">
                    </div>
                    <div class="col-md-12">
                        <label>Serial</label>
                        <input type="text" name="HAR_SERIAL" class="form-control" value="{{ $hardware->HAR_SERIAL }}">
                    </div>
                    <div class="col-md-12">
                        <label for="HAR_OBSERVACION" class="form-label">Observaciones</label>
                        <textarea class="form-control" name="HAR_OBSERVACION" id="HAR_OBSERVACION" cols="20" rows="5" >{{ $hardware->HAR_OBSERVACION }}</textarea>
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
    </div>
</div>

