{{--
    Componente modal específico para operaciones CRUD

    @param string $modalId - ID único del modal
    @param string $title - Título del modal
    @param string $action - URL de acción del formulario
    @param string $method - Método HTTP (POST, PUT, PATCH, DELETE)
    @param string $size - Tamaño del modal
    @param string $submitText - Texto del botón de envío
    @param string $submitClass - Clase CSS del botón de envío
    @param string $cancelText - Texto del botón de cancelar
    @param bool $novalidate - Desactivar validación HTML5
    @param string $enctype - Tipo de codificación del formulario
--}}

@props([
    'modalId' => 'crud-modal-' . uniqid(),
    'title' => 'Modal',
    'action' => '#',
    'method' => 'POST',
    'size' => '',
    'submitText' => 'Guardar',
    'submitClass' => 'btn-success',
    'cancelText' => 'Cerrar',
    'novalidate' => false,
    'enctype' => null,
    'centered' => false,
    'scrollable' => false
])

<x-modals.base-modal
    :modal-id="$modalId"
    :title="$title"
    :size="$size"
    :centered="$centered"
    :scrollable="$scrollable">

    <form action="{{ $action }}"
          method="POST"
          {{ $novalidate ? 'novalidate' : '' }}
          {{ $enctype ? 'enctype=' . $enctype : '' }}
          class="modal-form"
          data-modal-id="{{ $modalId }}">

        @csrf
        @if(strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <div class="modal-form-fields">
            {{ $slot }}
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $cancelText }}
            </button>
            <button type="submit" class="btn {{ $submitClass }}">
                <span class="btn-text">{{ $submitText }}</span>
                <span class="btn-loading d-none">
                    <i class="fas fa-spinner fa-spin"></i> Guardando...
                </span>
            </button>
        </div>
    </form>
</x-modals.base-modal>