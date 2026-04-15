@props([
    'startFieldId' => 'FECHA_INICIAL',
    'endFieldId' => 'FECHA_FINAL',
    'startFieldName' => 'FECHA_INICIAL',
    'endFieldName' => 'FECHA_FINAL',
    'required' => false,
    'minDate' => null
])

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $startFieldId }}">
                <i class="mdi mdi-calendar-start"></i> Fecha Inicial
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <input
                type="date"
                name="{{ $startFieldName }}"
                id="{{ $startFieldId }}"
                class="form-control date-start"
                {{ $required ? 'required' : '' }}
                @if($minDate) min="{{ $minDate }}" @endif
            >
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $endFieldId }}">
                <i class="mdi mdi-calendar-end"></i> Fecha Final
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <input
                type="date"
                name="{{ $endFieldName }}"
                id="{{ $endFieldId }}"
                class="form-control date-end"
                {{ $required ? 'required' : '' }}
                @if($minDate) min="{{ $minDate }}" @endif
            >
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#{{ $startFieldId }}, #{{ $endFieldId }}').on('change', function() {
        var fechaInicial = $('#{{ $startFieldId }}').val();
        var fechaFinal = $('#{{ $endFieldId }}').val();

        if (fechaInicial && fechaFinal && fechaInicial > fechaFinal) {
            alert('La fecha inicial debe ser menor o igual a la fecha final');
            $(this).val('');
        }
    });
});
</script>
@endpush

<style>
.form-group label {
    font-weight: 600;
    color: #495057;
}

.form-group label i {
    margin-right: 5px;
    color: #6c757d;
}
</style>
