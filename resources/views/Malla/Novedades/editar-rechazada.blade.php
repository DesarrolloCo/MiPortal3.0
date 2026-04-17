@extends('layouts.main')

@section('main')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">
            <i class="mdi mdi-pencil"></i> Editar Novedad Rechazada
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Novedades.index') }}">Novedades</a></li>
            <li class="breadcrumb-item active">Editar Rechazada</li>
        </ol>
    </div>
    <div class="col-md-4 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            <a href="{{ route('Novedades.index') }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Información de la novedad rechazada -->
                <div class="alert alert-warning mb-4">
                    <h5><i class="mdi mdi-alert-circle"></i> Novedad Rechazada</h5>
                    <p class="mb-2">Esta novedad fue rechazada por: <strong>{{ $novedad->aprobadoPor->name ?? 'Usuario desconocido' }}</strong></p>
                    @if($novedad->NOV_OBSERVACIONES)
                    <p class="mb-0"><strong>Observaciones:</strong> {{ $novedad->NOV_OBSERVACIONES }}</p>
                    @endif
                    <p class="mb-0 text-muted">Fecha de rechazo: {{ \Carbon\Carbon::parse($novedad->NOV_FECHA_APROBACION)->format('d/m/Y H:i') }}</p>
                </div>

                <form action="{{ route('Novedades.update', $novedad->NOV_ID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Tipo de Novedad -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TIN_ID" class="form-label required">
                                    <i class="mdi mdi-format-list-bulleted-type"></i> Tipo de Novedad
                                </label>
                                <select name="TIN_ID" id="TIN_ID" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($tiposNovedades as $tipo)
                                    <option value="{{ $tipo->TIN_ID }}" {{ $novedad->TIN_ID == $tipo->TIN_ID ? 'selected' : '' }}>
                                        {{ $tipo->TIN_NOMBRE }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Empleado -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EMP_ID" class="form-label required">
                                    <i class="mdi mdi-account"></i> Empleado
                                </label>
                                <select name="EMP_ID" id="EMP_ID" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->EMP_ID }}" {{ $novedad->EMP_ID == $empleado->EMP_ID ? 'selected' : '' }}>
                                        {{ $empleado->EMP_NOMBRES }} {{ $empleado->EMP_APELLIDOS }} - {{ $empleado->EMP_CEDULA }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="NOV_DESCRIPCION" class="form-label required">
                            <i class="mdi mdi-text"></i> Descripción
                        </label>
                        <textarea name="NOV_DESCRIPCION" id="NOV_DESCRIPCION" class="form-control" rows="4" required
                                  placeholder="Describe detalladamente la novedad...">{{ $novedad->NOV_DESCRIPCION }}</textarea>
                    </div>

                    <!-- Fecha -->
                    <div class="form-group">
                        <label for="NOV_FECHA" class="form-label">
                            <i class="mdi mdi-calendar"></i> Fecha
                        </label>
                        <input type="date" name="NOV_FECHA" id="NOV_FECHA" class="form-control"
                               value="{{ \Carbon\Carbon::parse($novedad->NOV_FECHA)->format('Y-m-d') }}">
                        <small class="form-text text-muted">Fecha de referencia para la novedad</small>
                    </div>

                    <!-- Horarios asociados -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="mdi mdi-clock-outline"></i> Horarios Asociados
                        </label>
                        <small class="form-text text-muted mb-3">
                            Selecciona los horarios que deseas asociar con esta novedad. Los horarios marcados estarán asociados.
                        </small>

                        <!-- Current associated schedules -->
                        @if($novedad->horarios && $novedad->horarios->count() > 0)
                        <div class="mb-3">
                            <h6>Horarios actualmente asociados:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">
                                                <input type="checkbox" id="select-all-current" class="form-check-input">
                                            </th>
                                            <th>Fecha</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Campaña</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($novedad->horarios as $horario)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="horarios[]" value="{{ $horario->MAL_ID }}"
                                                       class="form-check-input schedule-checkbox" checked>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($horario->MAL_DIA)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($horario->MAL_INICIO)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($horario->MAL_FINAL)->format('H:i') }}</td>
                                            <td>{{ $horario->campana->CAM_NOMBRE ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Option to load more schedules -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="load-more-schedules">
                                <i class="mdi mdi-plus"></i> Cargar más horarios disponibles
                            </button>
                            <small class="form-text text-muted">
                                Haz clic para ver horarios adicionales del empleado que puedes asociar.
                            </small>
                        </div>

                        <!-- Container for additional schedules -->
                        <div id="additional-schedules" class="d-none">
                            <h6>Horarios adicionales disponibles:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">
                                                <input type="checkbox" id="select-all-additional" class="form-check-input">
                                            </th>
                                            <th>Fecha</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Campaña</th>
                                        </tr>
                                    </thead>
                                    <tbody id="additional-schedules-body">
                                        <!-- Additional schedules will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-2">
                            <small class="form-text text-muted">
                                <strong>Nota:</strong> Solo puedes seleccionar horarios activos del empleado seleccionado.
                                Los horarios ya asociados estarán marcados por defecto.
                            </small>
                        </div>
                    </div>

                    <!-- Archivos existentes -->
                    @if($novedad->NOV_ARCHIVOS)
                    @php
                        $archivos = json_decode($novedad->NOV_ARCHIVOS, true);
                    @endphp
                    @if(is_array($archivos) && count($archivos) > 0)
                    <div class="form-group">
                        <label class="form-label">
                            <i class="mdi mdi-file-multiple"></i> Archivos Adjuntos
                        </label>
                        <div class="row">
                            @foreach($archivos as $index => $archivo)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-file-document-outline text-primary mr-2"></i>
                                            <div class="flex-grow-1">
                                                <small class="d-block text-truncate" title="{{ $archivo['nombre_original'] }}">
                                                    {{ $archivo['nombre_original'] }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ number_format($archivo['size'] / 1024, 1) }} KB
                                                </small>
                                            </div>
                                            <div class="ml-2">
                                                <a href="{{ route('Novedades.verArchivo', [$novedad->NOV_ID, $index]) }}"
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('Novedades.descargarArchivo', [$novedad->NOV_ID, $index]) }}"
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Nuevos archivos -->
                    <div class="form-group">
                        <label for="archivos" class="form-label">
                            <i class="mdi mdi-file-plus"></i> Agregar Nuevos Archivos
                        </label>
                        <input type="file" name="archivos[]" id="archivos" class="form-control" multiple
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="form-text text-muted">
                            Puedes agregar archivos adicionales. Tipos permitidos: PDF, JPG, PNG, DOC, DOCX. Máximo 5MB cada uno.
                        </small>
                    </div>

                    <!-- Estado -->
                    <input type="hidden" name="cambiar_estado" value="1">
                    <input type="hidden" name="nuevo_estado" value="pendiente">

                    <!-- Botones -->
                    <div class="form-group text-right">
                        <a href="{{ route('Novedades.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-close"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save"></i> Guardar y Reenviar para Aprobación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación del formulario
    $('form').on('submit', function(e) {
        const descripcion = $('#NOV_DESCRIPCION').val().trim();
        const tinId = $('#TIN_ID').val();
        const empId = $('#EMP_ID').val();

        if (!tinId) {
            e.preventDefault();
            alert('Por favor seleccione el tipo de novedad');
            $('#TIN_ID').focus();
            return false;
        }

        if (!empId) {
            e.preventDefault();
            alert('Por favor seleccione el empleado');
            $('#EMP_ID').focus();
            return false;
        }

        if (!descripcion) {
            e.preventDefault();
            alert('Por favor ingrese una descripción');
            $('#NOV_DESCRIPCION').focus();
            return false;
        }
    });

    // Schedule management functionality
    $('#select-all-current').on('change', function() {
        $('.schedule-checkbox').prop('checked', $(this).is(':checked'));
    });

    $('#load-more-schedules').on('click', function() {
        const empleadoId = $('#EMP_ID').val();

        if (!empleadoId) {
            alert('Por favor seleccione un empleado primero');
            return;
        }

        $(this).prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Cargando...');

        $.ajax({
            url: '/Novedades/empleado/' + empleadoId + '/horarios-disponibles',
            method: 'GET',
            data: {
                novedad_id: {{ $novedad->NOV_ID }},
                fecha_inicio: '{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}',
                fecha_fin: '{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}'
            },
            success: function(response) {
                const tbody = $('#additional-schedules-body');
                tbody.empty();

                if (response.horarios.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">No hay horarios adicionales disponibles</td></tr>');
                } else {
                    response.horarios.forEach(function(horario) {
                        const row = '<tr>' +
                            '<td class="text-center">' +
                                '<input type="checkbox" name="horarios[]" value="' + horario.MAL_ID + '" class="form-check-input additional-schedule-checkbox">' +
                            '</td>' +
                            '<td>' + horario.fecha + '</td>' +
                            '<td>' + horario.hora_inicio + '</td>' +
                            '<td>' + horario.hora_fin + '</td>' +
                            '<td>' + horario.campana + '</td>' +
                        '</tr>';
                        tbody.append(row);
                    });
                }

                $('#additional-schedules').removeClass('d-none');
            },
            error: function(xhr) {
                alert('Error al cargar los horarios disponibles');
                console.error(xhr);
            },
            complete: function() {
                $('#load-more-schedules').prop('disabled', false).html('<i class="mdi mdi-plus"></i> Cargar más horarios disponibles');
            }
        });
    });

    $('#select-all-additional').on('change', function() {
        $('.additional-schedule-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Update employee selection to reload schedules
    $('#EMP_ID').on('change', function() {
        // Hide additional schedules when employee changes
        $('#additional-schedules').addClass('d-none');
        $('#additional-schedules-body').empty();
    });
});
</script>
@endsection