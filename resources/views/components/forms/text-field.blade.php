{{--
    Componente de campo de texto reutilizable

    @param string $name - Nombre del campo
    @param string $label - Etiqueta del campo
    @param string $type - Tipo de input (text, email, password, number, etc.)
    @param string $value - Valor por defecto
    @param string $placeholder - Placeholder del campo
    @param bool $required - Campo obligatorio
    @param int $maxlength - Longitud máxima
    @param int $minlength - Longitud mínima
    @param string $pattern - Patrón de validación
    @param string $helpText - Texto de ayuda
    @param bool $readonly - Campo de solo lectura
    @param bool $disabled - Campo deshabilitado
--}}

@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'maxlength' => null,
    'minlength' => null,
    'pattern' => null,
    'helpText' => null,
    'readonly' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $fieldId = $name . '_' . uniqid();
    $value = $value ?? old($name);
    $hasError = $errors->has($name);
@endphp

<div class="form-group">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input type="{{ $type }}"
           id="{{ $fieldId }}"
           name="{{ $name }}"
           class="form-control {{ $hasError ? 'is-invalid' : '' }} {{ $class }}"
           value="{{ $value }}"
           {{ $placeholder ? 'placeholder=' . $placeholder : '' }}
           {{ $required ? 'required' : '' }}
           {{ $readonly ? 'readonly' : '' }}
           {{ $disabled ? 'disabled' : '' }}
           {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
           {{ $minlength ? 'minlength=' . $minlength : '' }}
           {{ $pattern ? 'pattern=' . $pattern : '' }}
           {{ $attributes }}>

    @if($helpText)
        <small class="form-text text-muted">{{ $helpText }}</small>
    @endif

    @if($maxlength)
        <small class="form-text text-muted char-counter">
            <span class="current-length">{{ strlen($value) }}</span>/{{ $maxlength }} caracteres
        </small>
    @endif

    @if($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>