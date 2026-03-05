<!-- .modal for add task -->
<div class="modal fade" id="Add_Contrato" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar contrato</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Contrato.create', $empleado[0]->EMP_ID) }}" method="POST">
                    @csrf

                    <input type="hidden" class="form-control" id="USER_CREATED" name="USER_CREATED"
                        value="{{ Auth::user()->id }}"required>
                    <input type="hidden" class="form-control" id="EMP_ID" name="EMP_ID"
                        value="{{ $empleado[0]->EMP_ID }}"required>

                    <div class="form-group">
                        <label>Cargo</label>
                        <select name="CAR_ID" id="CAR_ID" class="form-control" aria-label="Default select example" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach ($cargos as $car)
                                <option value="{{ $car->CAR_ID }}">{{ $car->CAR_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de contrato</label>
                        <select name="TIC_ID" id="TIC_ID" class="form-control" aria-label="Default select example" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach ($tipos_contratos as $tic)
                                <option value="{{ $tic->TIC_ID }}">{{ $tic->TIC_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sueldo</label>
                        <input type="number" step="any" name="EMC_SUELDO" id="EMC_SUELDO" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de inicio</label>
                                <input type="date" name="EMC_FECHA_INI" id="EMC_FECHA_INI" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de finalizacion</label>
                                <input type="date" ame="EMC_FECHA_FIN" id="EMC_FECHA_FIN" class="form-control" >
                            </div>
                        </div>
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
