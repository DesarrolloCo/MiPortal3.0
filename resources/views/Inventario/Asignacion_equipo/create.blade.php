<!-- .modal for add task -->
<div class="modal fade" id="Add_Equ_asignado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title">
                    <i class="mdi mdi-clipboard-account"></i> Nueva Asignación de Equipo
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Nota:</strong> Al crear la asignación, se generará automáticamente un <strong>Acta de Entrega</strong> que podrá descargar.
                </div>

                <form action="{{ route('Asignacion_equipo.create') }}" method="POST" novalidate enctype="multipart/form-data" id="formAsignacion">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-account"></i> Empleado <span class="text-danger">*</span></label>
                                <input type="text" id="search-input-empleado" placeholder="Buscar empleado..." oninput="filterEmpleado()" class="form-control mb-2" />
                                <select name="EMP_ID" id="select-box-empleado" class="form-control" required>
                                    <option value="">-- Seleccione un empleado --</option>
                                    @foreach ($exc_emp as $list_emp)
                                    <option value="{{ $list_emp->EMP_ID }}">{{ $list_emp->EMP_NOMBRES }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Debe seleccionar un empleado</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-laptop"></i> Equipo <span class="text-danger">*</span></label>
                                <input type="text" id="search-input-equipo" placeholder="Buscar por serial..." oninput="filterEquipo()" class="form-control mb-2" />
                                <select name="EQU_ID" id="select-box-equipo" class="form-control" required>
                                    <option value="">-- Seleccione un equipo --</option>
                                    @foreach ($exc_equ as $list_equ)
                                    <option value="{{ $list_equ->EQU_ID }}">{{ $list_equ->EQU_NOMBRE }} - {{ $list_equ->EQU_SERIAL }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Debe seleccionar un equipo</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="mdi mdi-calendar"></i> Fecha de Entrega <span class="text-danger">*</span></label>
                                <input type="date" name="EAS_FECHA_ENTREGA" class="form-control" value="{{ date('Y-m-d') }}" required>
                                <small class="form-text text-muted">Fecha en la que se realiza la entrega del equipo</small>
                                <div class="invalid-feedback">Debe seleccionar una fecha</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Crear Asignación
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
    function filterEmpleado() {
        const input = document.getElementById("search-input-empleado").value.toLowerCase();
        const select = document.getElementById("select-box-empleado");
        const options = select.options;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            if (text.includes(input)) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }

    function filterEquipo() {
        const input = document.getElementById("search-input-equipo").value.toLowerCase();
        const select = document.getElementById("select-box-equipo");
        const options = select.options;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const text = option.text.toLowerCase();
            if (text.includes(input)) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }

    // Validación del formulario
    $(document).ready(function() {
        $('#formAsignacion').on('submit', function(e) {
            var form = this;

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(form).addClass('was-validated');
        });

        // Resetear validación al abrir el modal
        $('#Add_Equ_asignado').on('hidden.bs.modal', function() {
            $('#formAsignacion').removeClass('was-validated');
            $('#formAsignacion')[0].reset();
        });
    });
</script>

<style>
    /* Estilos para los filtros de búsqueda */
    #search-input-empleado,
    #search-input-equipo {
        border: 2px solid #e9ecef;
        border-radius: 5px;
    }

    #search-input-empleado:focus,
    #search-input-equipo:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    /* Mejorar visualización de selects */
    select.form-control {
        height: 150px;
        border: 2px solid #e9ecef;
        border-radius: 5px;
    }

    select.form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .modal-header.bg-success {
        background-color: #28a745 !important;
    }
</style>
