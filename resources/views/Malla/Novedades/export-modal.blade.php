<!-- ===== MODAL DE EXPORTACIÓN - COMPONENTE CARGADO ===== -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="exportModalLabel">
                    <i class="mdi mdi-download me-2 text-white"></i>
                    Exportar Novedades a Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('Novedades.exportar') }}" id="exportForm">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-left-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        Seleccione los criterios para exportar las novedades. Los campos de fecha son opcionales.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="mdi mdi-format-list-checks me-1"></i>Estado de Aprobación
                                </label>
                                <select name="estado_aprobacion" class="form-control">
                                    <option value="" selected>📋 Todos los estados</option>
                                    <option value="pendiente">⏳ Pendientes</option>
                                    <option value="aprobada">✅ Aprobadas</option>
                                    <option value="rechazada">❌ Rechazadas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="mdi mdi-format-list-bulleted me-1"></i>Tipo de Novedad
                                </label>
                                <select name="tipo_novedad" class="form-control">
                                    <option value="">📄 Todos los tipos</option>
                                    @foreach($tiposNovedades as $tipo)
                                        <option value="{{ $tipo->TIN_ID }}">{{ $tipo->TIN_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="mdi mdi-calendar-start me-1"></i>Fecha de Inicio
                                </label>
                                <input type="date" name="fecha_inicio" class="form-control" id="fechaInicio">
                                <small class="form-text text-muted">Dejar vacío para incluir desde el inicio</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="mdi mdi-calendar-end me-1"></i>Fecha de Fin
                                </label>
                                <input type="date" name="fecha_fin" class="form-control" id="fechaFin">
                                <small class="form-text text-muted">Dejar vacío para incluir hasta hoy</small>
                            </div>
                        </div>
                    </div>

                    <div class="export-preview mt-4" id="exportPreview" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted">
                                    <i class="mdi mdi-information me-1"></i>
                                    <span id="previewText">Se exportarán todas las novedades</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success btn-sm px-4">
                        <i class="mdi mdi-microsoft-excel me-2"></i>Descargar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal fix - asegurar que aparezca correctamente */
#exportModal {
    z-index: 1050 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    outline: 0 !important;
}

#exportModal .modal-dialog {
    z-index: 1051 !important;
    margin: 30px auto !important;
    position: relative !important;
}

#exportModal.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Forzar que el modal sea siempre visible cuando tenga la clase show */
.modal.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Backdrop específico */
.modal-backdrop {
    z-index: 1040 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
}

/* ===== ESTILOS PARA MODAL DE EXPORTACIÓN ===== */
.modal-content.border-0 {
    border-radius: 1rem;
    overflow: hidden;
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    border-bottom: none;
}

.modal-body {
    background: #f8f9fa;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.form-control {
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.export-options {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
}

.export-preview .card {
    border: 1px dashed #007bff;
    background: #e3f2fd !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.alert.border-left-info {
    background-color: #d1ecf1;
    border-color: #b8daff;
    color: #0c5460;
}

/* Modal animations */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

/* Responsive modal */
@media (max-width: 768px) {
    .modal-dialog.modal-lg {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }

    .modal-body {
        padding: 1.5rem 1rem;
    }
}
</style>

<script>
$(document).ready(function() {

    // Configurar eventos del modal de exportación
    const exportForm = document.getElementById('exportForm');
    const fechaInicio = document.getElementById('fechaInicio');
    const fechaFin = document.getElementById('fechaFin');
    const exportPreview = document.getElementById('exportPreview');
    const previewText = document.getElementById('previewText');

    if (!exportForm || !fechaInicio || !fechaFin || !exportPreview || !previewText) {
        return;
    }

    // Validación de fechas
    fechaFin.addEventListener('change', function() {
        if (fechaInicio.value && fechaFin.value && fechaFin.value < fechaInicio.value) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('La fecha de fin no puede ser anterior a la fecha de inicio');
            }
            fechaFin.value = '';
        }
        updatePreview();
    });

    fechaInicio.addEventListener('change', function() {
        if (fechaInicio.value && fechaFin.value && fechaFin.value < fechaInicio.value) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('La fecha de fin no puede ser anterior a la fecha de inicio');
            }
            fechaFin.value = '';
        }
        updatePreview();
    });

    // Actualizar preview cuando cambien los filtros
    exportForm.addEventListener('change', updatePreview);

    function updatePreview() {
        const estado = exportForm.querySelector('[name="estado_aprobacion"]').value;
        const tipo = exportForm.querySelector('[name="tipo_novedad"]').value;
        const fechaIni = fechaInicio.value;
        const fechaFinVal = fechaFin.value;

        let preview = 'Se exportarán ';

        if (estado) {
            const estadoTextos = {
                'pendiente': 'novedades pendientes',
                'aprobada': 'novedades aprobadas',
                'rechazada': 'novedades rechazadas'
            };
            preview += estadoTextos[estado];
        } else {
            preview += 'todas las novedades';
        }

        if (tipo) {
            const tipoSelect = exportForm.querySelector('[name="tipo_novedad"]');
            const tipoText = tipoSelect.options[tipoSelect.selectedIndex].text;
            preview += ` del tipo "${tipoText}"`;
        }

        if (fechaIni && fechaFinVal) {
            preview += ` desde ${fechaIni} hasta ${fechaFinVal}`;
        } else if (fechaIni) {
            preview += ` desde ${fechaIni}`;
        } else if (fechaFinVal) {
            preview += ` hasta ${fechaFinVal}`;
        }

        previewText.textContent = preview;
        exportPreview.style.display = 'block';
    }

    // Establecer fecha de hoy como máximo
    const today = new Date().toISOString().split('T')[0];
    fechaInicio.setAttribute('max', today);
    fechaFin.setAttribute('max', today);

    // Validación y manejo de envío del formulario
    exportForm.addEventListener('submit', function(e) {
        const fechaIni = fechaInicio.value;
        const fechaFinVal = fechaFin.value;
        const submitButton = exportForm.querySelector('button[type="submit"]');

        if (fechaIni && fechaFinVal && fechaFinVal < fechaIni) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error('Por favor, corrija las fechas antes de exportar');
            }
            return false;
        }

        // Cambiar estado del botón a cargando
        const originalHtml = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Generando Excel...';
        submitButton.disabled = true;

        // Mostrar mensaje de descarga
        if (typeof toastr !== 'undefined') {
            toastr.info('Preparando archivo Excel... La descarga comenzará en breve', 'Exportando', {
                timeOut: 5000
            });
        }

        // Cerrar el modal después de un breve delay para permitir la descarga
        setTimeout(function() {
            // Restaurar botón
            submitButton.innerHTML = originalHtml;
            submitButton.disabled = false;

            $('#exportModal').modal('hide');

            // Mostrar mensaje de éxito
            if (typeof toastr !== 'undefined') {
                toastr.success('Excel exportado exitosamente', 'Exportación completada');
            }
        }, 1500);
    });

    // Reinicializar modal cuando se cierre
    $('#exportModal').on('hidden.bs.modal', function () {
        // Resetear formulario a valores por defecto
        exportForm.reset();

        // Restaurar valor por defecto del estado
        exportForm.querySelector('[name="estado_aprobacion"]').value = '';

        // Actualizar preview
        updatePreview();
    });

    // Inicializar preview
    updatePreview();
});
</script>
