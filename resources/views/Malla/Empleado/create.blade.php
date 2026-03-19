<!-- sample modal content -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="Add_Empleado" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Agregar empleado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Empleado.create') }}" method="POST" name="form-data">
                    @csrf
                    <input type="hidden" name="USER_ID" id="USER_ID" value="{{ Auth::user()->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cédula</label>
                                <input type="text" class="form-control" id="EMP_CEDULA" name="EMP_CEDULA">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Departamento de expedición</label>
                                <select name="DEP_ID" id="DEP_ID" class="form-control">
                                    <option class="form-control" disabled selected>-- Seleccione --</option>
                                    @foreach ($departamentos as $dep)
                                    <option value="{{ $dep->DEP_ID }}">{{ $dep->DEP_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Departamento de expedición</label>
                                <select name="MUN_ID" id="MUN_ID" class="form-control">
                                    <option class="form-control" disabled selected>-- Seleccione --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código</label>
                                <input type="text" class="form-control" id="EMP_CODE" name="EMP_CODE">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="EMP_NOMBRES" name="EMP_NOMBRES">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="EMP_DIRECCION" name="EMP_DIRECCION">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" id="EMP_TELEFONO" name="EMP_TELEFONO">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="EMP_SEXO" id="EMP_SEXO" class="form-control">
                                    <option class="form-control" disabled selected>-- Seleccione --</option>
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="EMP_FECHA_NACIMIENTO" name="EMP_FECHA_NACIMIENTO">
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de ingreso</label>
                                <input type="date" class="form-control" id="EMP_FECHA_INGRESO" name="EMP_FECHA_INGRESO">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Campaña</label>
                                <select name="CAM_ID" id="CAM_ID" class="form-control">
                                    <option class="form-control" disabled selected>-- Seleccione --</option>
                                    @foreach ($campanas as $cam)
                                        <option value="{{ $cam->CAM_ID }}">{{ $cam->CAM_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="email" id="EMP_EMAIL" name="EMP_EMAIL" class="form-control" >
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
