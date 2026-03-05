<!-- .modal for add task -->
<div class="modal fade" id="Add_Jornada" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar joranda</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{ route('Jornada.create') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora inicial</label>
                                <select name="JOR_INICIO" id="JOR_INICIO" class="form-control">
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_INICIO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora final</label>
                                <select name="JOR_FINAL" id="JOR_FINAL" class="form-control">
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}">{{ $hor->HOR_FINAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="JOR_NOMBRE" name="JOR_NOMBRE">
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
