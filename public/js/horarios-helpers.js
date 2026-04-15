/**
 * Horarios Helpers - Funciones reutilizables para el módulo de Horarios
 */

/**
 * Carga campañas dinámicamente basado en el cliente seleccionado
 * @param {string} clientSelectId - ID del select de clientes
 * @param {string} campaignSelectId - ID del select de campañas
 */
function loadCampaignsByClient(clientSelectId, campaignSelectId) {
    $('#' + clientSelectId).on('change', function() {
        var cliId = $(this).val();
        var camSelect = $('#' + campaignSelectId);

        if (cliId) {
            camSelect.prop('disabled', true).html('<option value="">Cargando campañas...</option>');

            $.ajax({
                url: '/get-campanas/' + cliId,
                type: 'GET',
                success: function(data) {
                    camSelect.html('<option value="">-- Seleccione una campaña --</option>');
                    $.each(data, function(key, value) {
                        camSelect.append('<option value="' + value.CAM_ID + '">' + value.CAM_NOMBRE + '</option>');
                    });
                    camSelect.prop('disabled', false);
                },
                error: function() {
                    camSelect.html('<option value="">Error al cargar campañas</option>');
                    showAlert('Error al cargar las campañas. Por favor intente nuevamente.', 'error');
                }
            });
        } else {
            camSelect.prop('disabled', true).html('<option value="">-- Primero seleccione un cliente --</option>');
        }
    });
}

/**
 * Valida que la fecha inicial sea menor o igual a la fecha final
 * @param {string} startDateId - ID del input de fecha inicial
 * @param {string} endDateId - ID del input de fecha final
 */
function validateDateRange(startDateId, endDateId) {
    $('#' + startDateId + ', #' + endDateId).on('change', function() {
        var fechaInicial = $('#' + startDateId).val();
        var fechaFinal = $('#' + endDateId).val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            showAlert('La fecha inicial debe ser menor o igual a la fecha final', 'warning');
            $(this).val('');
            return false;
        }
        return true;
    });
}

/**
 * Valida que la hora inicial sea menor que la hora final
 * @param {string} startTimeId - ID del select de hora inicial
 * @param {string} endTimeId - ID del select de hora final
 */
function validateTimeRange(startTimeId, endTimeId) {
    $('#' + startTimeId + ', #' + endTimeId).on('change', function() {
        var horaInicial = parseInt($('#' + startTimeId).val());
        var horaFinal = parseInt($('#' + endTimeId).val());

        if (horaInicial && horaFinal && horaInicial >= horaFinal) {
            showAlert('La hora inicial debe ser menor que la hora final', 'warning');
            $(this).val('');
            return false;
        }
        return true;
    });
}

/**
 * Muestra un mensaje de alerta al usuario
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de alerta (success, error, warning, info)
 */
function showAlert(message, type = 'info') {
    // Si existe SweetAlert2, usarlo
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info',
            title: type === 'error' ? 'Error' : type === 'success' ? 'Éxito' : type === 'warning' ? 'Advertencia' : 'Información',
            text: message,
            confirmButtonText: 'OK'
        });
    } else {
        // Fallback a alert nativo
        alert(message);
    }
}

/**
 * Alterna la visibilidad de secciones de jornada y hora
 * @param {boolean} showHourFormat - true para mostrar formato por hora, false para jornada
 * @param {string} jornadaContainerId - ID del contenedor de jornada
 * @param {string} horaContainerId - ID del contenedor de hora
 * @param {string} checkboxId - ID del checkbox de toggle
 */
function toggleJornadaHora(showHourFormat, jornadaContainerId = 'jornada', horaContainerId = 'for_fecha', checkboxId = 'checkJorOrHor') {
    if (showHourFormat) {
        $('#' + jornadaContainerId).hide();
        $('#' + horaContainerId).show();
        $('#' + checkboxId).val('1');
        // Limpiar jornada
        $('#JOR_ID').val('');
    } else {
        $('#' + jornadaContainerId).show();
        $('#' + horaContainerId).hide();
        $('#' + checkboxId).val('0');
        // Limpiar horas
        $('#HORA_INICIAL, #HOR_ID1').val('');
        $('#HORA_FINAL, #HOR_ID2').val('');
    }
}

/**
 * Configura el comportamiento de "seleccionar todos" para checkboxes
 * @param {string} selectAllId - ID del checkbox "seleccionar todos"
 * @param {string} checkboxName - Atributo name de los checkboxes individuales
 */
function setupSelectAll(selectAllId, checkboxName) {
    // Handle "select all" checkbox
    $('#' + selectAllId).on('change', function() {
        var isChecked = $(this).is(':checked');
        $('input[name="' + checkboxName + '"]').prop('checked', isChecked);
    });

    // Update "select all" if individual checkboxes change
    $(document).on('change', 'input[name="' + checkboxName + '"]', function() {
        var totalCheckboxes = $('input[name="' + checkboxName + '"]').length;
        var checkedCheckboxes = $('input[name="' + checkboxName + '"]:checked').length;
        $('#' + selectAllId).prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
    });
}

/**
 * Formatea una fecha en formato dd/mm/yyyy
 * @param {string} dateString - Fecha en formato yyyy-mm-dd
 * @returns {string} Fecha formateada
 */
function formatDate(dateString) {
    if (!dateString) return '';
    var date = new Date(dateString);
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    return day + '/' + month + '/' + year;
}

/**
 * Formatea una hora en formato HH:mm
 * @param {string} timeString - Hora en formato HH:mm:ss
 * @returns {string} Hora formateada
 */
function formatTime(timeString) {
    if (!timeString) return '';
    return timeString.substring(0, 5);
}

/**
 * Muestra u oculta un empty state
 * @param {string} emptyStateId - ID del contenedor del empty state
 * @param {boolean} show - true para mostrar, false para ocultar
 */
function toggleEmptyState(emptyStateId, show) {
    if (show) {
        $('#' + emptyStateId).show();
    } else {
        $('#' + emptyStateId).hide();
    }
}

/**
 * Deshabilita el botón de submit y muestra loading
 * @param {string} buttonId - ID del botón
 * @param {string} loadingText - Texto a mostrar durante la carga
 */
function setButtonLoading(buttonId, loadingText = 'Procesando...') {
    var btn = $('#' + buttonId);
    btn.data('original-text', btn.html());
    btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> ' + loadingText);
}

/**
 * Restaura el botón a su estado original
 * @param {string} buttonId - ID del botón
 */
function resetButton(buttonId) {
    var btn = $('#' + buttonId);
    var originalText = btn.data('original-text');
    if (originalText) {
        btn.prop('disabled', false).html(originalText);
    }
}

// Export para uso en módulos (si se usa)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        loadCampaignsByClient,
        validateDateRange,
        validateTimeRange,
        showAlert,
        toggleJornadaHora,
        setupSelectAll,
        formatDate,
        formatTime,
        toggleEmptyState,
        setButtonLoading,
        resetButton
    };
}
