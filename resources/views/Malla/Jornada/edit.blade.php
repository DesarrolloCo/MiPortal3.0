<!-- .modal for add task -->
<div class="modal fade" id="EditJornada{{ $list->JOR_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar joranda</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  action="{{ route('Jornada.update', $list->JOR_ID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @php
                    use App\Models\hora;

                    $j_inicio = hora::select('HOR_INICIO')->where('HOR_ID', $list->JOR_INICIO)->get();
                    $j_final = hora::select('HOR_FINAL')->where('HOR_ID', $list->JOR_FINAL)->get();

                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora inicial</label>
                                <input type="text" class="form-control" name="JOR_INICIO" id="JOR_INICIO" value="{{$j_inicio[0]->HOR_INICIO}}" required disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora final</label>
                                <input type="text" class="form-control" id="JOR_FINAL" name="JOR_FINAL" value="{{$j_final[0]->HOR_FINAL}}" required disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="JOR_NOMBRE" name="JOR_NOMBRE" value="{{$list->JOR_NOMBRE}}">
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
