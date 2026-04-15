@props([
    'clientes' => [],
    'clientFieldId' => 'CLI_ID',
    'campaignFieldId' => 'CAM_ID',
    'required' => false
])

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $clientFieldId }}">
                <i class="mdi mdi-domain"></i> Cliente
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <select
                name="CLI_ID"
                id="{{ $clientFieldId }}"
                class="form-control client-select"
                {{ $required ? 'required' : '' }}
            >
                <option value="">-- Seleccione --</option>
                @foreach ($clientes as $cli)
                    <option value="{{ $cli->CLI_ID }}">{{ $cli->CLI_NOMBRE }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $campaignFieldId }}">
                <i class="mdi mdi-folder"></i> Campaña
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <select
                name="CAM_ID"
                id="{{ $campaignFieldId }}"
                class="form-control campaign-select"
                {{ $required ? 'required' : '' }}
                disabled
            >
                <option value="">-- Primero seleccione un cliente --</option>
            </select>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#{{ $clientFieldId }}').on('change', function() {
        var cliId = $(this).val();
        var camSelect = $('#{{ $campaignFieldId }}');

        if (cliId) {
            camSelect.prop('disabled', true).html('<option value="">Cargando campañas...</option>');

            $.ajax({
                url: '/get-campanas/' + cliId,
                type: 'GET',
                success: function(data) {
                    camSelect.html('<option value="">-- Seleccione una campaña --</option>');
                    $.each(data, function(key, value) {
                        camSelect.append('<option value="' + value.CAM_ID + '">' + value.CAM_NOMBRE + '</option>');
                    });
                    camSelect.prop('disabled', false);
                },
                error: function() {
                    camSelect.html('<option value="">Error al cargar campañas</option>');
                    alert('Error al cargar las campañas. Por favor intente nuevamente.');
                }
            });
        } else {
            camSelect.prop('disabled', true).html('<option value="">-- Primero seleccione un cliente --</option>');
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
