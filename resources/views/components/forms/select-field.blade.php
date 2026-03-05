{{--
    Componente de campo select reutilizable

    @param string $name - Nombre del campo
    @param string $label - Etiqueta del campo
    @param array|collection $options - Opciones del select
    @param string $value - Valor seleccionado
    @param string $valueKey - Clave del valor en las opciones (default: id)
    @param string $textKey - Clave del texto en las opciones (default: name)
    @param string $placeholder - Texto del placeholder (opción vacía)
    @param bool $required - Campo obligatorio
    @param string $helpText - Texto de ayuda
    @param bool $multiple - Selección múltiple
    @param bool $disabled - Campo deshabilitado
--}}

@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'valueKey' => 'id',
    'textKey' => 'name',
    'placeholder' => '-- Seleccione --',
    'required' => false,
    'helpText' => null,
    'multiple' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $fieldId = $name . '_' . uniqid();
    $value = $value ?? old($name);
    $hasError = $errors->has($name);
    $selectName = $multiple ? $name . '[]' : $name;
@endphp

<div class="form-group">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <select id="{{ $fieldId }}"
            name="{{ $selectName }}"
            class="form-control {{ $hasError ? 'is-invalid' : '' }} {{ $class }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $multiple ? 'multiple' : '' }}
            {{ $attributes }}>

        @if(!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @forelse($options as $option)
            @php
                // Manejar tanto arrays asociativos como objetos/colecciones
                if (is_array($option)) {
                    $optionValue = $option[$valueKey] ?? $option['value'] ?? $option[0];
                    $optionText = $option[$textKey] ?? $option['text'] ?? $option['label'] ?? $option[1] ?? $optionValue;
                } elseif (is_object($option)) {
                    $optionValue = $option->{$valueKey} ?? $option->value ?? $option->id;
                    $optionText = $option->{$textKey} ?? $option->text ?? $option->label ?? $option->name ?? $optionValue;
                } else {
                    $optionValue = $option;
                    $optionText = $option;
                }

                // Determinar si está seleccionado
                $isSelected = false;
                if ($multiple && is_array($value)) {
                    $isSelected = in_array($optionValue, $value);
                } else {
                    $isSelected = $value == $optionValue;
                }
            @endphp

            <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionText }}
            </option>
        @empty
            @if(!$multiple)
                <option value="" disabled>No hay opciones disponibles</option>
            @endif
        @endforelse
    </select>

    @if($helpText)
        <small class="form-text text-muted">{{ $helpText }}</small>
    @endif

    @if($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>