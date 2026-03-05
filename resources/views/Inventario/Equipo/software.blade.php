<!-- .modal for add task -->
<div class="modal fade" id="Add_Software" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar software</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Equipo.software') }}" method="POST"novalidate>
                    @csrf
                    <input type="hidden" name="EQU_ID" value="{{ $equipos[0]->EQU_ID }}">
                    <div class="col-md-12">
                        <label>Area</label>
                        <select name="SOF_ID" id="" class="form-control">
                            <option value="">-- Seleccione --</option>
                            @foreach ($softwares as $software)
                            <option value="{{$software->SOF_ID}}">{{$software->SOF_NOMBRE}} - {{$software->SOF_VERSION}}</option>
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
