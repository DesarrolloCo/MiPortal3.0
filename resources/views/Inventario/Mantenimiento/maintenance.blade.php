<!-- .modal for add task -->
<div class="modal fade" id="Edit_Maintenance{{ $list_mantenimiento->MAN_ID }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar asignacion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Mantenimiento.maintenance') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="MAN_ID" value="{{ $list_mantenimiento->MAN_ID }}">
                    <input type="hidden" name="EQU_ID" value="{{ $list_mantenimiento->EQU_ID }}">
                    <input type="hidden" name="MAN_PROVEEDOR" value="{{ $list_mantenimiento->MAN_PROVEEDOR }}">
                    <input type="hidden" name="MAN_FECHA" value="{{ $list_mantenimiento->MAN_FECHA }}">
                    <input type="hidden" name="MAN_TECNICO" value="{{ $list_mantenimiento->MAN_TECNICO }}">
                    <div class="form-group">
                        <label>Tipo de mantenimiento</label>
                        <select name="MAS_TIPO" id="MAS_TIPO" class="form-control">
                            <option value="" selected>-- Seleccione --</option>
                            <option value="Preventivo">Preventivo</option>
                            <option value="Correctivo">Correctivo</option>
                            <option value="Proveedor">Proveedor</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fisico</label>
                                @foreach ($mantenimiento_fisicos as $mantenimiento_fisico )
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="TIP_ID_FIS[]" id="TIP_ID_FIS" value="{{ $mantenimiento_fisico->TIP_ID }}">
                                        <label  for="">{{ $mantenimiento_fisico->TIP_NOMBRE }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Logico</label>
                                @foreach ($mantenimiento_logicos as $mantenimiento_logico )
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="TIP_ID_LOG[]" id="TIP_ID_LOG" value="{{ $mantenimiento_logico->TIP_ID }}">
                                        <label  for="">{{ $mantenimiento_logico->TIP_NOMBRE }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Actividades</label>

                        <textarea class="form-control" rows="3" name="MAS_ACTIVIDAD"></textarea>
                    </div>
                    {{-- <div class="form-group">
                        <label>Evidencia</label>
                        <input type="file" name="EAS_EVIDENCIA" class="form-control" >
                    </div> --}}
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
