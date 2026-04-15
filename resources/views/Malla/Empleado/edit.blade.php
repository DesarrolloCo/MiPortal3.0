<!-- sample modal content -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="Edit_Empleado{{ $list->EMP_ID }}" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Editar empleado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Empleado.update', $list->EMP_ID) }}" method="POST" name="form-data" id="form-edit-empleado-{{ $list->EMP_ID }}" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="USER_ID" id="USER_ID" value="{{ Auth::user()->id }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cédula <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EMP_CEDULA" name="EMP_CEDULA" value="{{ $list->EMP_CEDULA }}"
                                       required maxlength="20" pattern="[0-9]+"
                                       title="Ingrese solo números">
                                <small class="form-text text-muted">Solo números, máximo 20 dígitos</small>
                                <div class="invalid-feedback">Por favor ingrese una cédula válida (solo números)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Departamento de expedición <span class="text-danger">*</span></label>
                                <select name="DEP_ID" id="DEP_ID_EDIT" class="form-control" required>
                                    <option value="" disabled>-- Seleccione --</option>
                                    @foreach ($departamentos as $dep)
                                    <option value="{{ $dep->DEP_ID }}">{{ $dep->DEP_NOMBRE }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un departamento</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Municipio de expedición <span class="text-danger">*</span></label>
                                <select class="form-control" name="MUN_ID" id="MUN_ID_EDIT" required>
                                    @if($list->municipio)
                                        <option value="{{ $list->municipio->MUN_ID }}" selected>{{ $list->municipio->MUN_NOMBRE }}</option>
                                    @else
                                        <option value="" selected>-- Seleccione --</option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un municipio</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EMP_CODE" name="EMP_CODE" value="{{ $list->EMP_CODE }}"
                                       required maxlength="50">
                                <div class="invalid-feedback">Por favor ingrese el código del empleado</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EMP_NOMBRES" name="EMP_NOMBRES" value="{{ $list->EMP_NOMBRES }}"
                                       required maxlength="255" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                       title="Solo letras y espacios">
                                <div class="invalid-feedback">Por favor ingrese el nombre completo</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="EMP_DIRECCION" name="EMP_DIRECCION" value="{{ $list->EMP_DIRECCION }}" maxlength="255">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" id="EMP_TELEFONO" name="EMP_TELEFONO" value="{{ $list->EMP_TELEFONO }}"
                                       maxlength="20" pattern="[0-9\+\-\s\(\)]+"
                                       title="Ingrese un número de teléfono válido">
                                <small class="form-text text-muted">Ejemplo: 3001234567 o +57 300 123 4567</small>
                                <div class="invalid-feedback">Ingrese un teléfono válido</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sexo <span class="text-danger">*</span></label>
                                <select name="EMP_SEXO" id="EMP_SEXO" class="form-control" required>
                                    <option value="" disabled>-- Seleccione --</option>
                                    <option value="F" @if ($list->EMP_SEXO == 'F') selected @endif>Femenino</option>
                                    <option value="M" @if ($list->EMP_SEXO == 'M') selected @endif>Masculino</option>
                                </select>
                                <div class="invalid-feedback">Por favor seleccione el sexo</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="EMP_FECHA_NACIMIENTO" name="EMP_FECHA_NACIMIENTO" value="{{ $list->EMP_FECHA_NACIMIENTO }}"
                                       required max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                <small class="form-text text-muted">Debe ser mayor de 18 años</small>
                                <div class="invalid-feedback">Por favor ingrese una fecha de nacimiento válida</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de ingreso <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="EMP_FECHA_INGRESO" name="EMP_FECHA_INGRESO" value="{{ $list->EMP_FECHA_INGRESO }}"
                                       required max="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Por favor ingrese la fecha de ingreso</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cliente <span class="text-danger">*</span></label>
                                <select name="CLI_ID" id="CLI_ID" class="form-control" required>
                                    @if($list->cliente)
                                        <option value="{{ $list->cliente->CLI_ID }}" selected>{{ $list->cliente->CLI_NOMBRE }}</option>
                                    @endif
                                    <option value="" disabled>-- Seleccione --</option>
                                    @foreach ($clientes as $cli)
                                        <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un cliente</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo <span class="text-danger">*</span></label>
                                <input type="email" id="EMP_EMAIL" name="EMP_EMAIL" class="form-control" value="{{ $list->EMP_EMAIL }}"
                                       required maxlength="255"
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Ingrese un correo electrónico válido">
                                <small class="form-text text-muted">ejemplo@dominio.com</small>
                                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    // Validación de formulario con Bootstrap
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var form = document.getElementById('form-edit-empleado-{{ $list->EMP_ID }}');
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Scroll al primer campo con error
                        var firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstInvalid.focus();
                        }
                    }
                    form.classList.add('was-validated');
                }, false);
            }
        }, false);
    })();
</script>
