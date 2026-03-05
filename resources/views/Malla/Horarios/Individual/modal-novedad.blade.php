<!-- Modal para registrar novedad completa -->
<div class="modal fade" id="modal_novedad_completa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">
                    <i class="mdi mdi-calendar-clock me-2"></i>
                    Registrar Novedad - Desactivar Horario
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNovedadCompleta" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Campos ocultos para la desactivación de horarios -->
                    <input type="hidden" name="USER_ID" value="{{ Auth::user()->id }}" required>
                    <input type="hidden" name="MAL_IDS" id="modal_MAL_IDS" required>
                    <input type="hidden" name="MAL_DIA" id="modal_MAL_DIA" required>
                    <input type="hidden" name="EMP_ID" id="modal_EMP_ID" required>
                    <input type="hidden" name="MAL_ESTADO" value="0" required>
                    <input type="hidden" name="accion" value="desactivar_multiples" required>

                    <!-- Información del empleado y fecha -->
                    <div class="alert alert-info mb-3">
                        <h6><i class="mdi mdi-information text-info"></i> Información General</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Empleado:</strong> <span id="info_empleado_nombre"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Día:</strong> <span id="info_dia"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Horarios:</strong> <span id="info_total_horarios"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de horarios -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <h5 class="section-title">
                                <i class="mdi mdi-clock-outline text-primary me-2"></i>
                                Seleccionar Horarios a Desactivar
                            </h5>
                            <hr class="section-divider">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label">
                                        <i class="mdi mdi-checkbox-multiple-marked me-1"></i>
                                        Horarios Disponibles
                                    </label>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="seleccionarTodos()">
                                            <i class="mdi mdi-check-all"></i> Seleccionar Todos
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm ml-2" onclick="deseleccionarTodos()">
                                            <i class="mdi mdi-close"></i> Deseleccionar Todos
                                        </button>
                                    </div>
                                </div>

                                <div id="lista_horarios" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    <!-- Los horarios se cargarán dinámicamente aquí -->
                                </div>

                                <div class="form-text mt-2">
                                    <i class="mdi mdi-information-outline"></i>
                                    Seleccione los horarios que desea desactivar. Todos los horarios seleccionados compartirán la misma novedad.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 1: Información Principal -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <h5 class="section-title">
                                <i class="mdi mdi-account-circle text-primary me-2"></i>
                                Información Principal
                            </h5>
                            <hr class="section-divider">
                        </div>

                        <!-- Tipo de Novedad - Fila completa -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="modal_TIN_ID" class="form-label required">
                                        <i class="mdi mdi-format-list-bulleted-type me-1"></i>Tipo de Novedad
                                    </label>
                                    <select name="TIN_ID" id="modal_TIN_ID" class="form-control form-control-lg" required>
                                        <option value="">-- Seleccione el tipo --</option>
                                        @if(isset($tipos_novedades))
                                            @foreach($tipos_novedades as $tipo)
                                                <option value="{{ $tipo->TIN_ID }}">
                                                    {{ $tipo->TIN_NOMBRE }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción - Fila completa -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="modal_NOV_DESCRIPCION" class="form-label required">
                                        <i class="mdi mdi-text-long me-1"></i>Descripción de la Novedad
                                    </label>
                                    <textarea name="NOV_DESCRIPCION" id="modal_NOV_DESCRIPCION" rows="3"
                                        class="form-control"
                                        placeholder="Describa el motivo de la desactivación..." required></textarea>
                                    <div class="form-text">
                                        <i class="mdi mdi-information-outline"></i>
                                        Explique el motivo por el cual se desactivan estos horarios
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Documentos -->
                    <div class="form-section mb-4">
                        <div class="section-header mb-3">
                            <h5 class="section-title">
                                <i class="mdi mdi-paperclip text-primary me-2"></i>
                                Documentos de Soporte
                            </h5>
                            <hr class="section-divider">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="modal_archivos" class="form-label">
                                        <i class="mdi mdi-upload me-1"></i>Archivos Adjuntos
                                        <span class="badge badge-secondary ms-1">Opcional</span>
                                    </label>
                                    <input type="file" name="archivos[]" id="modal_archivos"
                                        class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <div class="form-text">
                                        <i class="mdi mdi-information-outline text-info"></i>
                                        Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Tamaño máximo: 5MB por archivo.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger" onclick="submitNovedadCompleta()">
                    <i class="mdi mdi-content-save me-1"></i>Desactivar y Guardar Novedad
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para el modal */
.modal-xl {
    max-width: 1140px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.section-divider {
    margin: 0.5rem 0 1rem 0;
    border-top: 2px solid #e9ecef;
    opacity: 1;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-label.required::after {
    content: '*';
    color: #dc3545;
    margin-left: 0.25rem;
    font-weight: bold;
}

.form-label i {
    color: #6c757d;
}

.me-1 { margin-right: 0.25rem !important; }
.me-2 { margin-right: 0.5rem !important; }
.ms-1 { margin-left: 0.25rem !important; }

.badge.badge-secondary {
    background-color: #6c757d;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

.form-text i {
    margin-right: 0.5rem;
}

/* Asegurar que las columnas se mantengan en una fila */
.form-section .row {
    display: flex;
    flex-wrap: wrap;
}

.form-section .col-lg-4,
.form-section .col-lg-8 {
    display: flex;
    flex-direction: column;
}

.form-section .form-group {
    flex: 1;
    margin-bottom: 1rem;
}
</style>

<script>
// Variables globales
let horariosDisponibles = [];

// Función para preparar el modal con todos los horarios del empleado
function prepararNovedadGeneral(empId, malDia) {
    // Establecer valores en campos ocultos
    document.getElementById('modal_EMP_ID').value = empId;
    document.getElementById('modal_MAL_DIA').value = malDia;

    // Actualizar información general
    document.getElementById('info_dia').textContent = malDia;

    // No necesitamos establecer fechas ya que se obtienen de los horarios seleccionados

    // Buscar el nombre del empleado y mostrarlo en la información general
    @if(isset($empleados))
        const empleados = @json($empleados);
        const empleado = empleados.find(emp => emp.EMP_ID == empId);
        if (empleado) {
            document.getElementById('info_empleado_nombre').textContent = empleado.EMP_NOMBRES + ' ' + (empleado.EMP_APELLIDOS || '');
        }
    @endif

    // Cargar horarios disponibles
    cargarHorariosDisponibles(empId, malDia);

    // Limpiar formulario
    document.getElementById('modal_TIN_ID').value = '';
    document.getElementById('modal_NOV_DESCRIPCION').value = '';
    document.getElementById('modal_archivos').value = '';
}

// Función para cargar los horarios disponibles del empleado
function cargarHorariosDisponibles(empId, malDia) {
    // Obtener horarios desde la variable global de PHP
    @if(isset($emp_horario))
        horariosDisponibles = @json($emp_horario);
    @endif

    // Filtrar solo horarios activos
    const horariosActivos = horariosDisponibles.filter(h => h.MAL_ESTADO == 1);

    document.getElementById('info_total_horarios').textContent = horariosActivos.length + ' horario(s) activo(s)';

    // Generar HTML para la lista de horarios
    const listaHorarios = document.getElementById('lista_horarios');
    listaHorarios.innerHTML = '';

    if (horariosActivos.length === 0) {
        listaHorarios.innerHTML = '<div class="text-center text-muted py-3"><i class="mdi mdi-information"></i> No hay horarios activos para desactivar</div>';
        return;
    }

    horariosActivos.forEach(horario => {
        const horarioItem = document.createElement('div');
        horarioItem.className = 'border-bottom pb-2 mb-2';
        horarioItem.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="${horario.MAL_ID}" id="horario_${horario.MAL_ID}" name="horarios_seleccionados[]">
                <label class="form-check-label w-100" for="horario_${horario.MAL_ID}">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Cliente:</strong> ${horario.CLI_NOMBRE}
                        </div>
                        <div class="col-md-3">
                            <strong>Campaña:</strong> ${horario.CAM_NOMBRE}
                        </div>
                        <div class="col-md-3">
                            <strong>Inicio:</strong> ${horario.MAL_INICIO}
                        </div>
                        <div class="col-md-3">
                            <strong>Fin:</strong> ${horario.MAL_FINAL}
                        </div>
                    </div>
                </label>
            </div>
        `;
        listaHorarios.appendChild(horarioItem);
    });
}

// Función para seleccionar todos los horarios
function seleccionarTodos() {
    const checkboxes = document.querySelectorAll('input[name="horarios_seleccionados[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

// Función para deseleccionar todos los horarios
function deseleccionarTodos() {
    const checkboxes = document.querySelectorAll('input[name="horarios_seleccionados[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Función para enviar el formulario
function submitNovedadCompleta() {
    const form = document.getElementById('formNovedadCompleta');

    // Validaciones básicas
    const tinId = document.getElementById('modal_TIN_ID').value;
    const descripcion = document.getElementById('modal_NOV_DESCRIPCION').value.trim();

    if (!tinId) {
        alert('Por favor seleccione el tipo de novedad');
        return;
    }

    if (!descripcion) {
        alert('Por favor ingrese la descripción de la novedad');
        return;
    }

    // Validar que se hayan seleccionado horarios
    const checkboxes = document.querySelectorAll('input[name="horarios_seleccionados[]"]:checked');
    if (checkboxes.length === 0) {
        alert('Por favor seleccione al menos un horario para desactivar');
        return;
    }

    // Recopilar IDs de horarios seleccionados
    const horariosSeleccionados = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('modal_MAL_IDS').value = horariosSeleccionados.join(',');

    // Configurar la acción del formulario - usar una nueva ruta para múltiples horarios
    form.action = `{{ route('Individual.time_status_multiple') }}`;
    form.method = 'POST';

    // Enviar formulario
    form.submit();
}

// Las validaciones de fechas y horas ya no son necesarias
// porque usamos los datos de los horarios seleccionados
</script>