@extends('layouts.main')

@section('main')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-8 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">
            <i class="mdi mdi-pencil-box-outline"></i> Editar Horarios
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Individual.index') }}">Horarios Individuales</a></li>
            <li class="breadcrumb-item active">Editar Horario</li>
        </ol>
    </div>
    <div class="col-md-4 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            <a href="{{ route('Individual.employee_hours', ['id' => $EMP_ID]) }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Employee Info Card -->
<!-- ============================================================== -->
@if(isset($empleado) && $empleado)
<div class="row">
    <div class="col-12">
        <div class="card bg-light border-primary">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">
                            <i class="mdi mdi-account-circle text-primary"></i>
                            {{ $empleado->EMP_NOMBRES }} {{ $empleado->EMP_APELLIDOS ?? '' }}
                        </h5>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="mdi mdi-card-account-details"></i> Cédula:
                                </small>
                                <span class="badge badge-secondary">{{ $empleado->EMP_CEDULA }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="mdi mdi-briefcase"></i> Cargo:
                                </small>
                                <strong>{{ $empleado->cargo->CAR_NOMBRE ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right mt-2 mt-md-0">
                        <div class="d-inline-block">
                            @if(isset($isDateRange) && $isDateRange)
                                <span class="badge badge-primary p-2">
                                    <i class="mdi mdi-calendar-range"></i> Rango: {{ \Carbon\Carbon::parse($fechaInicial)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFinal)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="badge badge-info p-2">
                                    <i class="mdi mdi-calendar-today"></i> Fecha: {{ \Carbon\Carbon::parse($MAL_DIA)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                        <div class="d-inline-block ml-2">
                            <span class="badge badge-success p-2">
                                <i class="mdi mdi-clock-outline"></i> {{ count($emp_horario) }} horario(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- ============================================================== -->
<!-- End Employee Info Card -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h4 class="card-title mb-0">
                    <i class="mdi mdi-clock-outline text-primary"></i>
                    Horarios Asignados
                </h4>
                @if(count($emp_horario) > 0)
                    <button type="button" class="btn btn-danger btn-sm"
                        data-toggle="modal" data-target="#modal_novedad_completa"
                        onclick="prepararNovedadGeneral('{{ $EMP_ID }}', '{{ $MAL_DIA }}')">
                        <i class="mdi mdi-calendar-remove"></i> Desactivar Horarios
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if(count($emp_horario) > 0)
                    <!-- Tabla de horarios -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table_horarios">
                            <thead class="thead-light">
                                <tr>
                                    @if(isset($isDateRange) && $isDateRange)
                                        <th><i class="mdi mdi-calendar"></i> Fecha</th>
                                    @endif
                                    <th><i class="mdi mdi-domain"></i> Cliente</th>
                                    <th><i class="mdi mdi-folder"></i> Campaña</th>
                                    <th><i class="mdi mdi-clock-start"></i> Hora Inicial</th>
                                    <th><i class="mdi mdi-clock-end"></i> Hora Final</th>
                                    <th><i class="mdi mdi-check-circle"></i> Estado</th>
                                    <th class="text-center"><i class="mdi mdi-cog"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($emp_horario as $list)
                                    <tr class="{{ $list->MAL_ESTADO == 1 ? '' : 'table-secondary' }}">
                                        @if(isset($isDateRange) && $isDateRange)
                                            <td>
                                                <span class="badge badge-light">
                                                    {{ \Carbon\Carbon::parse($list->MAL_DIA)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                        @endif
                                        <td>{{ $list->CLI_NOMBRE }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $list->CAM_NOMBRE }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($list->MAL_INICIO)->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($list->MAL_FINAL)->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            @if ($list->MAL_ESTADO == 1)
                                                <span class="badge badge-success">
                                                    <i class="mdi mdi-check-circle"></i> Activo
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="mdi mdi-close-circle"></i> Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($list->MAL_ESTADO == 1)
                                                @can('delete-malla')
                                                    <form action="{{ route('Malla.delete', $list->MAL_ID) }}" method="POST"
                                                        class="d-inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="USER_ID" value="{{ Auth::user()->id }}">
                                                        <input type="hidden" name="MAL_ID" value="{{ $list->MAL_ID }}">
                                                        <input type="hidden" name="MAL_DIA" value="{{ isset($isDateRange) && $isDateRange ? $list->MAL_DIA : $MAL_DIA }}">
                                                        <input type="hidden" name="EMP_ID" value="{{ $EMP_ID }}">
                                                        <input type="hidden" name="MAL_ESTADO" value="1">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Horario">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @else
                                                <form action="{{ route('Individual.delete_time_status', $list->MAL_ID) }}"
                                                    method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="USER_ID" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="MAL_ID" value="{{ $list->MAL_ID }}">
                                                    <input type="hidden" name="MAL_DIA" value="{{ isset($isDateRange) && $isDateRange ? $list->MAL_DIA : $MAL_DIA }}">
                                                    <input type="hidden" name="EMP_ID" value="{{ $EMP_ID }}">
                                                    <input type="hidden" name="MAL_ESTADO" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Reactivar Horario">
                                                        <i class="mdi mdi-calendar-check"></i> Reactivar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar-remove" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="text-muted mt-3">No hay horarios asignados</h5>
                        <p class="text-muted">
                            @if(isset($isDateRange) && $isDateRange)
                                No se encontraron horarios en el rango de fechas seleccionado
                            @else
                                No se encontraron horarios para la fecha seleccionada
                            @endif
                        </p>
                        <a href="{{ route('Individual.employee_hours', ['id' => $EMP_ID]) }}" class="btn btn-primary mt-2">
                            <i class="mdi mdi-plus-circle"></i> Asignar Horarios
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

<!-- Modal para registrar novedad completa -->
@include('Malla.Horarios.Individual.modal-novedad')

@endsection

@section('styles')
<style>
/* Card styling */
.card.bg-light {
    background-color: #f8f9fa !important;
}

.card.border-primary {
    border-left: 4px solid #007bff !important;
}

/* Table improvements */
.table thead.thead-light {
    background-color: #f8f9fa;
}

.table thead th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f1f3f5 !important;
    transform: scale(1.01);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.table tbody tr.table-secondary {
    opacity: 0.7;
}

/* Badge improvements */
.badge {
    font-weight: 500;
}

.badge-light {
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
}

/* Button improvements */
.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .table thead th {
        font-size: 0.8rem;
    }

    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .badge {
        font-size: 0.75rem;
    }
}

/* Loading state for delete forms */
.delete-form.loading button {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Confirm before deleting
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        if (confirm('¿Está seguro que desea eliminar este horario?')) {
            form.addClass('loading');
            form.find('button').html('<i class="mdi mdi-loading mdi-spin"></i>');
            form.off('submit').submit();
        }
    });

    // Initialize DataTable if available
    if ($.fn.DataTable && $('#table_horarios tbody tr').length > 10) {
        $('#table_horarios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 25,
            "order": [[0, "desc"]]
        });
    }
});
</script>
@endsection
