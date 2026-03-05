<!-- .modal for add task -->
<div class="modal fade" id="Add_Mantenimiento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar plan de mantenimiento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Mantenimiento.create') }}" method="POST"novalidate>
                    @csrf
                    <div class="form-group">
                        <label>Fecha de mantenimiento</label>
                        <input type="date" name="MAN_FECHA" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Proveedor</label>
                        <input type="text" name="MAN_PROVEEDOR" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <select name="ARE_ID" id="ARE_ID" class="form-control">
                            <option value="">-- Seleccione --</option>
                            @foreach ($area as $list_area)
                            <option value="{{ $list_area->ARE_ID }}">{{ $list_area->ARE_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Equipos</label>
                        <select name="EQU_IDS[]" id="EQU_ID" multiple="multiple" class="form-control">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tecnicos</label>
                        <select name="MAN_TECNICO" id="MAN_TECNICO" class="form-control">
                            <option value="">-- Seleccione --</option>
                            @foreach ($tec_asignados as $list_tecnicos)
                            <option value="{{ $list_tecnicos->EMP_ID }}">{{ $list_tecnicos->EMP_NOMBRES }}</option>
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


<script src="{{ asset('js/validacion.js') }}"></script>
