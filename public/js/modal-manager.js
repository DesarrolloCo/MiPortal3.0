/**
 * Modal Manager - Sistema centralizado de gestión de modales
 * MiPortal 2.0 - Laravel Project
 *
 * Proporciona funcionalidad unificada para todos los modales del proyecto
 */

class ModalManager {
    constructor() {
        this.activeModals = new Set();
        this.initialize();
        console.log('ModalManager initialized');
    }

    /**
     * Inicializar el gestor de modales
     */
    initialize() {
        this.bindEvents();
        this.setupFormHandlers();
        this.setupValidation();
        this.setupCharCounters();
    }

    /**
     * Vincular eventos globales
     */
    bindEvents() {
        const self = this;

        // Evento para botones que abren modales
        $(document).on('click', '[data-toggle="modal"], [data-bs-toggle="modal"]', function(e) {
            e.preventDefault();

            const target = $(this).data('target') || $(this).data('bs-target');
            if (target) {
                self.showModal(target);
            }
        });

        // Evento para cerrar modales
        $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function(e) {
            e.preventDefault();
            const modal = $(this).closest('.modal');
            self.hideModal(modal.attr('id'));
        });

        // Cerrar modal al hacer clic en el backdrop
        $(document).on('click', '.modal-backdrop, .modal', function(e) {
            if (e.target === this) {
                const modalId = $(this).hasClass('modal') ? $(this).attr('id') : null;
                if (modalId) {
                    self.hideModal(modalId);
                } else {
                    // Cerrar el modal activo si se hace clic en backdrop
                    const activeModal = $('.modal.show').last();
                    if (activeModal.length) {
                        self.hideModal(activeModal.attr('id'));
                    }
                }
            }
        });

        // Prevenir que el contenido del modal cierre el modal
        $(document).on('click', '.modal-content', function(e) {
            e.stopPropagation();
        });

        // Manejar tecla ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                const activeModal = $('.modal.show').last();
                if (activeModal.length && activeModal.data('keyboard') !== false) {
                    self.hideModal(activeModal.attr('id'));
                }
            }
        });
    }

    /**
     * Mostrar un modal
     */
    showModal(modalId) {
        const modal = $(modalId);

        if (!modal.length) {
            console.error('Modal no encontrado:', modalId);
            return;
        }

        // Ocultar otros modales activos
        this.hideAllModals();

        // Mostrar el modal
        modal.show().addClass('show');
        this.activeModals.add(modalId);

        // Agregar backdrop si no existe
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop show"></div>');
        }

        // Agregar clase al body
        $('body').addClass('modal-open');

        // Enfocar el primer campo del formulario
        setTimeout(() => {
            modal.find('input, select, textarea').filter(':visible').first().focus();
        }, 150);

        // Disparar evento personalizado
        modal.trigger('modal:shown', { modalId });

        console.log('Modal mostrado:', modalId);
    }

    /**
     * Ocultar un modal específico
     */
    hideModal(modalId) {
        if (!modalId) return;

        const modal = $('#' + modalId);

        if (modal.length) {
            modal.hide().removeClass('show');
            this.activeModals.delete('#' + modalId);

            // Resetear formularios
            const form = modal.find('form');
            if (form.length) {
                this.resetForm(form);
            }

            // Disparar evento personalizado
            modal.trigger('modal:hidden', { modalId });

            console.log('Modal ocultado:', modalId);
        }

        // Si no hay más modales activos, limpiar
        if (this.activeModals.size === 0) {
            this.cleanupModals();
        }
    }

    /**
     * Ocultar todos los modales
     */
    hideAllModals() {
        $('.modal.show').each((index, element) => {
            const modalId = $(element).attr('id');
            this.hideModal(modalId);
        });
    }

    /**
     * Limpiar elementos de modales
     */
    cleanupModals() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '');
        this.activeModals.clear();
    }

    /**
     * Configurar manejadores de formularios
     */
    setupFormHandlers() {
        const self = this;

        $(document).on('submit', '.modal-form', function(e) {
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const modalId = form.data('modal-id');

            // Mostrar estado de carga
            self.setLoadingState(submitBtn, true);

            // Si es un formulario AJAX (opcional)
            if (form.data('ajax') === true) {
                e.preventDefault();
                self.submitFormAjax(form);
            }
        });
    }

    /**
     * Configurar validación de formularios
     */
    setupValidation() {
        $(document).on('input change', '.modal-form input, .modal-form select, .modal-form textarea', function() {
            const field = $(this);
            const form = field.closest('form');

            // Remover clases de error previas
            field.removeClass('is-invalid');
            field.siblings('.invalid-feedback').hide();

            // Validación HTML5
            if (this.checkValidity && !this.checkValidity()) {
                field.addClass('is-invalid');
            }
        });
    }

    /**
     * Configurar contadores de caracteres
     */
    setupCharCounters() {
        $(document).on('input', 'input[maxlength], textarea[maxlength]', function() {
            const field = $(this);
            const maxLength = parseInt(field.attr('maxlength'));
            const currentLength = field.val().length;
            const remaining = maxLength - currentLength;

            let counter = field.siblings('.char-counter');
            if (counter.length === 0) {
                counter = field.parent().find('.char-counter');
            }

            if (counter.length) {
                const currentSpan = counter.find('.current-length');
                if (currentSpan.length) {
                    currentSpan.text(currentLength);
                }

                // Cambiar color según caracteres restantes
                counter.removeClass('text-muted text-warning text-danger');
                if (remaining <= 5) {
                    counter.addClass('text-danger');
                } else if (remaining <= 15) {
                    counter.addClass('text-warning');
                } else {
                    counter.addClass('text-muted');
                }
            }
        });
    }

    /**
     * Establecer estado de carga en botón
     */
    setLoadingState(button, loading) {
        const btnText = button.find('.btn-text');
        const btnLoading = button.find('.btn-loading');

        if (loading) {
            button.prop('disabled', true);
            btnText.addClass('d-none');
            btnLoading.removeClass('d-none');
        } else {
            button.prop('disabled', false);
            btnText.removeClass('d-none');
            btnLoading.addClass('d-none');
        }
    }

    /**
     * Resetear formulario
     */
    resetForm(form) {
        form[0].reset();
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').hide();

        // Resetear contadores de caracteres
        form.find('.char-counter .current-length').text('0');

        // Resetear estado de botones
        const submitBtn = form.find('button[type="submit"]');
        this.setLoadingState(submitBtn, false);
    }

    /**
     * Enviar formulario vía AJAX (funcionalidad opcional)
     */
    submitFormAjax(form) {
        const self = this;
        const url = form.attr('action');
        const method = form.find('input[name="_method"]').val() || 'POST';
        const formData = new FormData(form[0]);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Ocultar modal
                const modalId = form.data('modal-id');
                self.hideModal(modalId);

                // Mostrar mensaje de éxito
                if (response.message) {
                    self.showNotification(response.message, 'success');
                }

                // Recargar página o actualizar contenido
                if (response.reload) {
                    location.reload();
                } else if (response.redirect) {
                    location.href = response.redirect;
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;

                if (response && response.errors) {
                    self.showValidationErrors(form, response.errors);
                } else {
                    self.showNotification('Error al procesar la solicitud', 'error');
                }
            },
            complete: function() {
                const submitBtn = form.find('button[type="submit"]');
                self.setLoadingState(submitBtn, false);
            }
        });
    }

    /**
     * Mostrar errores de validación
     */
    showValidationErrors(form, errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = form.find(`[name="${fieldName}"]`);
            const errorMessage = errors[fieldName][0];

            if (field.length) {
                field.addClass('is-invalid');

                let feedback = field.siblings('.invalid-feedback');
                if (feedback.length === 0) {
                    field.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                } else {
                    feedback.text(errorMessage).show();
                }
            }
        });
    }

    /**
     * Mostrar notificación
     */
    showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else if (typeof $.toast !== 'undefined') {
            $.toast({
                heading: type.toUpperCase(),
                text: message,
                position: 'top-right',
                loaderBg: type === 'success' ? '#ff6849' : (type === 'error' ? '#bf441d' : '#01a9ac'),
                icon: type,
                hideAfter: 3000,
                stack: 6
            });
        } else {
            alert(message);
        }
    }

    /**
     * API pública para mostrar modal
     */
    show(modalId) {
        this.showModal(modalId);
    }

    /**
     * API pública para ocultar modal
     */
    hide(modalId) {
        this.hideModal(modalId);
    }

    /**
     * API pública para obtener modales activos
     */
    getActiveModals() {
        return Array.from(this.activeModals);
    }
}

// Inicializar el gestor de modales cuando el DOM esté listo
$(document).ready(function() {
    window.modalManager = new ModalManager();
});

// Exponer funciones globales para compatibilidad
window.showModal = function(modalId) {
    if (window.modalManager) {
        window.modalManager.show(modalId);
    }
};

window.hideModal = function(modalId) {
    if (window.modalManager) {
        window.modalManager.hide(modalId);
    }
};