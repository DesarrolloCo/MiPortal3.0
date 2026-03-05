<!-- Modal de Aprobación/Rechazo -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="approvalModalLabel">
                    <i class="mdi mdi-check-circle me-2"></i>
                    <span id="modal-title-text">Gestión de Novedades</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeApprovalModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="approval-section" style="display: none;">
                    <div class="alert alert-success border-left-success">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-check-circle-outline text-success me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-1 font-weight-bold">Aprobar Novedades</h6>
                                <p class="mb-0">¿Está seguro que desea aprobar <span id="approval-count">0</span> novedad(es)?</p>
                                <small class="text-muted">Esta acción marcará las novedades seleccionadas como aprobadas.</small>
                            </div>
                        </div>
                    </div>

                    <div class="selected-items mb-3">
                        <h6 class="font-weight-bold mb-2">Novedades seleccionadas:</h6>
                        <div id="approval-items-list" class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <form id="approval-form" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="aprobar">
                        <input type="hidden" name="novedades_ids" id="approval-ids">

                        <div class="form-group">
                            <label class="form-label">
                                <i class="mdi mdi-comment-text-outline me-1"></i>
                                Comentarios adicionales (opcional)
                            </label>
                            <textarea name="comentarios" class="form-control" rows="3"
                                placeholder="Ingrese comentarios adicionales sobre la aprobación..."></textarea>
                        </div>
                    </form>
                </div>

                <div id="rejection-section" style="display: none;">
                    <div class="alert alert-danger border-left-danger">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-close-circle-outline text-danger me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-1 font-weight-bold">Rechazar Novedades</h6>
                                <p class="mb-0">¿Está seguro que desea rechazar <span id="rejection-count">0</span> novedad(es)?</p>
                                <small class="text-muted">Esta acción marcará las novedades seleccionadas como rechazadas.</small>
                            </div>
                        </div>
                    </div>

                    <div class="selected-items mb-3">
                        <h6 class="font-weight-bold mb-2">Novedades seleccionadas:</h6>
                        <div id="rejection-items-list" class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <form id="rejection-form" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="rechazar">
                        <input type="hidden" name="novedades_ids" id="rejection-ids">

                        <div class="form-group">
                            <label class="form-label required">
                                <i class="mdi mdi-comment-alert-outline me-1"></i>
                                Motivo del rechazo <span class="text-danger">*</span>
                            </label>
                            <textarea name="observaciones" class="form-control" rows="4" required
                                placeholder="Debe especificar el motivo por el cual está rechazando estas novedades..."></textarea>
                            <small class="form-text text-muted">
                                El motivo del rechazo es obligatorio y será visible para quien registró la novedad.
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeApprovalModal()">
                    <i class="mdi mdi-close me-1"></i>
                    Cancelar
                </button>

                <button type="button" id="btn-confirm-approval" class="btn btn-success" style="display: none;">
                    <i class="mdi mdi-check me-1"></i>
                    Confirmar Aprobación
                </button>

                <button type="button" id="btn-confirm-rejection" class="btn btn-danger" style="display: none;">
                    <i class="mdi mdi-close me-1"></i>
                    Confirmar Rechazo
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.required::after {
    content: " *";
    color: #dc3545;
}

.selected-items .item {
    padding: 8px 12px;
    margin: 4px 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    display: flex;
    justify-content: between;
    align-items: center;
}

.selected-items .item .item-info {
    flex: 1;
}

.selected-items .item .badge {
    margin-left: 10px;
}

#approvalModal {
    z-index: 1055 !important;
}

#approvalModal .modal-dialog {
    max-width: 700px;
    z-index: 1056 !important;
}

#approvalModal .modal-backdrop {
    z-index: 1054 !important;
}

#approvalModal .alert {
    border: none;
    border-radius: 10px;
}

#approvalModal .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    #approvalModal .modal-dialog {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
}
</style>

<script>
// Función para mostrar notificaciones (compatible con toastr o alternativa)
function showNotification(message, type = 'info') {
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

// Función para cerrar modal manualmente
function closeApprovalModal() {
    console.log('Closing approval modal...');

    const modal = document.getElementById('approvalModal');

    // Remover modal
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }

    // Remover TODOS los backdrops posibles
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(function(backdrop) {
        if (backdrop && backdrop.parentNode) {
            backdrop.parentNode.removeChild(backdrop);
        }
    });

    // Restaurar el body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';

    // Limpiar formularios
    const approvalForm = document.getElementById('approval-form');
    const rejectionForm = document.getElementById('rejection-form');

    if (approvalForm) approvalForm.reset();
    if (rejectionForm) rejectionForm.reset();

    // Ocultar secciones
    const approvalSection = document.getElementById('approval-section');
    const rejectionSection = document.getElementById('rejection-section');
    const btnApproval = document.getElementById('btn-confirm-approval');
    const btnRejection = document.getElementById('btn-confirm-rejection');

    if (approvalSection) approvalSection.style.display = 'none';
    if (rejectionSection) rejectionSection.style.display = 'none';
    if (btnApproval) btnApproval.style.display = 'none';
    if (btnRejection) btnRejection.style.display = 'none';

    console.log('Modal closed successfully');
}

// Función para obtener novedades seleccionadas
function getSelectedNovedades() {
    const selected = [];
    $('.row_checkbox:checked').each(function() {
        const row = $(this).closest('tr');
        const id = $(this).val();
        const empleado = row.find('td:eq(2)').text().trim();
        const tipo = row.find('td:eq(4)').text().trim();
        const descripcion = row.find('td:eq(5)').text().trim();
        const estado = row.find('td:eq(7) .badge').text().trim();

        selected.push({
            id: id,
            empleado: empleado,
            tipo: tipo,
            descripcion: descripcion,
            estado: estado
        });
    });
    return selected;
}

// Función para mostrar lista de items
function showItemsList(items, containerId) {
    let html = '';
    items.forEach(function(item) {
        html += `
            <div class="item">
                <div class="item-info">
                    <strong>${item.empleado}</strong><br>
                    <small class="text-muted">${item.tipo} - ${item.descripcion}</small>
                </div>
                <span class="badge badge-${getStatusColor(item.estado)}">${item.estado}</span>
            </div>
        `;
    });
    $('#' + containerId).html(html);
}

function getStatusColor(estado) {
    switch(estado.toLowerCase()) {
        case 'pendiente': return 'warning';
        case 'aprobada': return 'success';
        case 'rechazada': return 'danger';
        default: return 'secondary';
    }
}

// Botones de aprobación masiva
function showApprovalModal() {
    console.log('showApprovalModal called');

    const selectedItems = getSelectedNovedades();
    console.log('Selected items:', selectedItems);

    if (selectedItems.length === 0) {
        showNotification('Debe seleccionar al menos una novedad', 'warning');
        return;
    }

    // Filtrar solo pendientes
    const pendingItems = selectedItems.filter(item => item.estado.toLowerCase() === 'pendiente');
    console.log('Pending items:', pendingItems);

    if (pendingItems.length === 0) {
        showNotification('Solo se pueden aprobar novedades con estado pendiente', 'warning');
        return;
    }

    if (pendingItems.length !== selectedItems.length) {
        showNotification(`Solo ${pendingItems.length} de ${selectedItems.length} novedades seleccionadas pueden ser aprobadas (estado pendiente)`, 'info');
    }

    // Configurar modal para aprobación
    $('#modal-title-text').text('Aprobar Novedades');
    $('#approval-section').show();
    $('#rejection-section').hide();
    $('#btn-confirm-approval').show();
    $('#btn-confirm-rejection').hide();

    $('#approval-count').text(pendingItems.length);
    $('#approval-ids').val(pendingItems.map(item => item.id).join(','));
    showItemsList(pendingItems, 'approval-items-list');

    console.log('Modal content configured:');
    console.log('- Title:', $('#modal-title-text').text());
    console.log('- Approval section visible:', $('#approval-section').is(':visible'));
    console.log('- Rejection section visible:', $('#rejection-section').is(':visible'));
    console.log('- Items count:', pendingItems.length);

    console.log('Showing modal...');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap modal exists:', typeof $.fn.modal);

    // Método alternativo para mostrar el modal manualmente
    const modal = document.getElementById('approvalModal');

    // Método 1: Bootstrap nativo
    try {
        $('#approvalModal').modal('show');
        console.log('Method 1: Bootstrap modal called');
    } catch (e) {
        console.error('Method 1 failed:', e);
    }

    // Método 2: Mostrar manualmente
    setTimeout(function() {
        // Solo limpiar backdrops, NO el contenido del modal
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(function(backdrop) {
            if (backdrop && backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });

        // Luego mostrar el modal
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');

        // Crear backdrop solo si no existe
        let existingBackdrop = document.querySelector('.modal-backdrop');
        if (!existingBackdrop) {
            let backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.style.zIndex = '1040';
            backdrop.onclick = closeApprovalModal; // Cerrar al hacer clic en el backdrop
            document.body.appendChild(backdrop);
        }

        console.log('Method 2: Manual show executed - content should be visible');

        // Verificar que el contenido esté presente después de mostrar
        console.log('Post-show verification:');
        console.log('- Modal display:', modal.style.display);
        console.log('- Modal has show class:', modal.classList.contains('show'));
        console.log('- Approval section display:', $('#approval-section').css('display'));
        console.log('- Modal title text:', $('#modal-title-text').text());
    }, 200);
}

function showRejectionModal() {
    const selectedItems = getSelectedNovedades();

    if (selectedItems.length === 0) {
        showNotification('Debe seleccionar al menos una novedad', 'warning');
        return;
    }

    // Filtrar solo pendientes
    const pendingItems = selectedItems.filter(item => item.estado.toLowerCase() === 'pendiente');

    if (pendingItems.length === 0) {
        showNotification('Solo se pueden rechazar novedades con estado pendiente', 'warning');
        return;
    }

    if (pendingItems.length !== selectedItems.length) {
        showNotification(`Solo ${pendingItems.length} de ${selectedItems.length} novedades seleccionadas pueden ser rechazadas (estado pendiente)`, 'info');
    }

    // Configurar modal para rechazo
    $('#modal-title-text').text('Rechazar Novedades');
    $('#rejection-section').show();
    $('#approval-section').hide();
    $('#btn-confirm-rejection').show();
    $('#btn-confirm-approval').hide();

    $('#rejection-count').text(pendingItems.length);
    $('#rejection-ids').val(pendingItems.map(item => item.id).join(','));
    showItemsList(pendingItems, 'rejection-items-list');

    // Mostrar modal manualmente
    const modal = document.getElementById('approvalModal');

    setTimeout(function() {
        // Solo limpiar backdrops, NO el contenido del modal
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(function(backdrop) {
            if (backdrop && backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });

        // Luego mostrar el modal
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');

        // Crear backdrop solo si no existe
        let existingBackdrop = document.querySelector('.modal-backdrop');
        if (!existingBackdrop) {
            let backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.style.zIndex = '1040';
            backdrop.onclick = closeApprovalModal; // Cerrar al hacer clic en el backdrop
            document.body.appendChild(backdrop);
        }

        console.log('Rejection modal shown - content should be visible');
    }, 200);
}

$(document).ready(function() {
    // Confirmar aprobación
    $('#btn-confirm-approval').on('click', function() {
        const form = $('#approval-form');
        form.attr('action', '{{ route("Novedades.aprobar.masivo") }}');
        form.submit();
    });

    // Confirmar rechazo
    $('#btn-confirm-rejection').on('click', function() {
        const form = $('#rejection-form');
        const observaciones = form.find('textarea[name="observaciones"]').val();

        if (!observaciones.trim()) {
            showNotification('El motivo del rechazo es obligatorio', 'error');
            form.find('textarea[name="observaciones"]').focus();
            return;
        }

        form.attr('action', '{{ route("Novedades.rechazar.masivo") }}');
        form.submit();
    });

    // Cerrar modal con tecla ESC
    $(document).on('keyup', function(e) {
        if (e.keyCode === 27) { // ESC key
            const modal = $('#approvalModal');
            if (modal.hasClass('show') || modal.is(':visible')) {
                closeApprovalModal();
            }
        }
    });

    // Limpiar modal al cerrar (Bootstrap event)
    $('#approvalModal').on('hidden.bs.modal', function() {
        closeApprovalModal();
    });

    // También capturar el click en el backdrop desde jQuery
    $(document).on('click', '.modal-backdrop', function() {
        closeApprovalModal();
    });
});
</script>
