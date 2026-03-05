{{--
    Componente de campo textarea reutilizable

    @param string $name - Nombre del campo
    @param string $label - Etiqueta del campo
    @param string $value - Valor por defecto
    @param string $placeholder - Placeholder del campo
    @param bool $required - Campo obligatorio
    @param int $rows - Número de filas
    @param int $cols - Número de columnas
    @param int $maxlength - Longitud máxima
    @param int $minlength - Longitud mínima
    @param string $helpText - Texto de ayuda
    @param bool $readonly - Campo de solo lectura
    @param bool $disabled - Campo deshabilitado
    @param bool $resizable - Permitir redimensionar
--}}

@props([
    'name',
    'label',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'rows' => 3,
    'cols' => null,
    'maxlength' => null,
    'minlength' => null,
    'helpText' => null,
    'readonly' => false,
    'disabled' => false,
    'resizable' => true,
    'class' => ''
])

@php
    $fieldId = $name . '_' . uniqid();
    $value = $value ?? old($name);
    $hasError = $errors->has($name);
    $style = !$resizable ? 'resize: none;' : '';
@endphp

<div class="form-group">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <textarea id="{{ $fieldId }}"
              name="{{ $name }}"
              class="form-control {{ $hasError ? 'is-invalid' : '' }} {{ $class }}"
              rows="{{ $rows }}"
              {{ $cols ? 'cols=' . $cols : '' }}
              {{ $placeholder ? 'placeholder=' . $placeholder : '' }}
              {{ $required ? 'required' : '' }}
              {{ $readonly ? 'readonly' : '' }}
              {{ $disabled ? 'disabled' : '' }}
              {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
              {{ $minlength ? 'minlength=' . $minlength : '' }}
              {{ $style ? 'style=' . $style : '' }}
              {{ $attributes }}>{{ $value }}</textarea>

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