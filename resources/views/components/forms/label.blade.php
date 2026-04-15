@props([
    'for' => null,
    'icon' => null,
    'required' => false,
    'text' => null
])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'form-label']) }}
>
    @if($icon)
        <i class="mdi mdi-{{ $icon }}"></i>
    @endif

    {{ $text ?? $slot }}

    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

<style>
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 5px;
    color: #6c757d;
}
</style>
