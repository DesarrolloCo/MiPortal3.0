<!-- .modal for add task -->
<div class="modal fade" id="EditJornada{{ $list->JOR_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar jornada</h4>
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
                                <select name="JOR_INICIO" id="JOR_INICIO" class="form-control" required>
                                    <option value="">Seleccionar hora</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}" {{ $list->JOR_INICIO == $hor->HOR_ID ? 'selected' : '' }}>{{ $hor->HOR_INICIO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora final</label>
                                <select name="JOR_FINAL" id="JOR_FINAL" class="form-control" required>
                                    <option value="">Seleccionar hora</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}" {{ $list->JOR_FINAL == $hor->HOR_ID ? 'selected' : '' }}>{{ $hor->HOR_FINAL }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Almuerzo -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora inicio almuerzo <small class="text-muted">(opcional)</small></label>
                                <select name="JOR_ALMUERZO_INICIO" id="JOR_ALMUERZO_INICIO" class="form-control">
                                    <option value="">Sin almuerzo</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}" {{ $list->JOR_ALMUERZO_INICIO == $hor->HOR_ID ? 'selected' : '' }}>{{ $hor->HOR_INICIO }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora fin almuerzo <small class="text-muted">(opcional)</small></label>
                                <select name="JOR_ALMUERZO_FIN" id="JOR_ALMUERZO_FIN" class="form-control">
                                    <option value="">Sin almuerzo</option>
                                    @foreach ($horas as $hor)
                                        <option value="{{ $hor->HOR_ID }}" {{ $list->JOR_ALMUERZO_FIN == $hor->HOR_ID ? 'selected' : '' }}>{{ $hor->HOR_FINAL }}</option>
                                    @endforeach
                                </select>
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
