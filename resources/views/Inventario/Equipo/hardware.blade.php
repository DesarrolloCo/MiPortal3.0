<!-- .modal for add task -->
<div class="modal fade" id="Add_Hardware" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar hardware</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Equipo.hardware') }}" method="POST"novalidate>
                    @csrf
                    <input type="hidden" name="EQU_ID" value="{{ $equipos[0]->EQU_ID }}">
                    <div class="col-md-12">
                        <label>Tipo</label>
                        <select name="HAR_ID" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            @foreach ($hardwares as $hardware)
                            <option value="{{$hardware->HAR_ID}}">{{$hardware->HAR_DESCRIPCION}} - {{$hardware->HAR_SERIAL}}</option>
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


























{{-- <div class="modal fade" id="Add_Hardware" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar tipo de mantenimiento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST"novalidate>
                    @csrf
                    <input type="hidden" name="EQU_ID" value="">
                    <div class="col-md-12">
                        <label>Tipo</label>
                        <select name="HAR_TIPO" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <option value="Dispositivo">Dispositivo</option>
                            <option value="Pieza">Pieza</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Modelo</label>
                        <input type="text" name="HAR_MODELO" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Serial</label>
                        <input type="text" name="HAR_SERIAL" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label for="HAR_DESCRIPCION" class="form-label">Descripcion</label>
                        <textarea class="form-control" name="HAR_DESCRIPCION" id="HAR_DESCRIPCION" cols="20" rows="2" ></textarea>
                        <div class="invalid-feedback">Completa los datos</div>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <label for="HAR_OBSERVACION" class="form-label">Observaciones</label>
                        <textarea class="form-control" name="HAR_OBSERVACION" id="HAR_OBSERVACION" cols="20" rows="5" ></textarea>
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
 --}}
