@props([
    'icon' => 'alert-circle',
    'title' => 'No hay datos disponibles',
    'message' => '',
    'actionText' => null,
    'actionUrl' => null,
    'show' => true
])

<div
    {{ $attributes->merge(['class' => 'text-center py-5 empty-state']) }}
    style="{{ $show ? '' : 'display: none;' }}"
>
    <i class="mdi mdi-{{ $icon }} empty-state-icon"></i>

    <h5 class="text-muted mt-3">{{ $title }}</h5>

    @if($message)
        <p class="text-muted">{{ $message }}</p>
    @endif

    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary mt-2">
            <i class="mdi mdi-plus-circle"></i> {{ $actionText }}
        </a>
    @endif

    {{ $slot }}
</div>

<style>
.empty-state {
    padding: 3rem 1rem;
}

.empty-state-icon {
    font-size: 64px;
    color: #ccc;
}

.empty-state h5 {
    margin-top: 1rem;
    color: #6c757d;
    font-weight: 600;
}

.empty-state p {
    color: #adb5bd;
    margin-top: 0.5rem;
}
</style>
