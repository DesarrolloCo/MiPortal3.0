{{--
    Componente base para modales reutilizables

    @param string $modalId - ID único del modal
    @param string $title - Título del modal
    @param string $size - Tamaño del modal (modal-sm, modal-lg, modal-xl, o vacío para estándar)
    @param bool $centered - Centrar el modal verticalmente (default: false)
    @param bool $scrollable - Permitir scroll en el contenido del modal (default: false)
    @param string $backdrop - Comportamiento del backdrop (true, false, static) (default: true)
    @param bool $keyboard - Permitir cerrar con ESC (default: true)
--}}

@props([
    'modalId' => 'modal-' . uniqid(),
    'title' => 'Modal',
    'size' => '',
    'centered' => false,
    'scrollable' => false,
    'backdrop' => 'true',
    'keyboard' => true,
    'fade' => true
])

<div class="modal {{ $fade ? 'fade' : '' }}"
     id="{{ $modalId }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="{{ $modalId }}Label"
     aria-hidden="true"
     data-backdrop="{{ $backdrop }}"
     data-keyboard="{{ $keyboard ? 'true' : 'false' }}">
    <div class="modal-dialog {{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}"
         role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>