@extends('layouts.main')

@section('main')
    <!-- Bread crumb and right sidebar toggle -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor mb-0 mt-0">
                @if (isset($datosHorario))
                    Registrar Novedad - Desactivar Horario
                @else
                    Registrar Nueva Novedad
                @endif
            </h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('Novedades.index') }}">Novedades</a></li>
                <li class="breadcrumb-item active">Nueva Novedad</li>
            </ol>
        </div>
        <div class="col-md-6 col-4 align-self-center">
            @if (isset($datosHorario))
                <a href="{{ route('Individual.redirect', ['EMP_ID' => $datosHorario['emp_id'], 'FECHA' => $datosHorario['mal_dia']]) }}"
                    class="btn btn-secondary float-right">
                    <i class="mdi mdi-arrow-left"></i> Volver al Horario
                </a>
            @else
                <a href="{{ route('Novedades.index') }}" class="btn btn-secondary float-right">
                    <i class="mdi mdi-arrow-left"></i> Volver
                </a>
            @endif
        </div>
    </div>

    <!-- Formulario -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('Novedades.store') }}" enctype="multipart/form-data"
                        id="novedadForm">
                        @csrf

                        @if ($datosHorario)
                            <!-- Campos ocultos para la desactivación del horario -->
                            <input type="hidden" name="mal_id" value="{{ $datosHorario['mal_id'] }}">
                            <input type="hidden" name="mal_dia" value="{{ $datosHorario['mal_dia'] }}">
                            <input type="hidden" name="accion" value="{{ $datosHorario['accion'] }}">

                            <!-- Información del horario a desactivar -->
                            <div class="alert alert-warning mb-4">
                                <h5><i class="mdi mdi-calendar-remove text-warning me-2"></i>Desactivación de Horario</h5>
                                <p class="mb-2">Está registrando una novedad para <strong>desactivar</strong> el siguiente
                                    horario:</p>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Cliente:</strong> {{ $datosHorario['cliente'] }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Campaña:</strong> {{ $datosHorario['campana'] }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Horario:</strong> {{ $datosHorario['horario_inicio'] }} -
                                        {{ $datosHorario['horario_final'] }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Día:</strong> {{ $datosHorario['mal_dia'] }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Sección 1: Información Principal -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-3">
                                <h5 class="section-title">
                                    <i class="mdi mdi-account-circle text-primary me-2"></i>
                                    Información Principal
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <div class="row">
                                <!-- Empleado -->
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="EMP_ID" class="form-label required">
                                            <i class="mdi mdi-account me-1"></i>Empleado
                                        </label>
                                        <select name="EMP_ID" id="EMP_ID"
                                            class="form-control form-control-lg @error('EMP_ID') is-invalid @enderror"
                                            required>
                                            <option value="">-- Seleccione el empleado --</option>
                                            @foreach ($empleados as $empleado)
                                                <option value="{{ $empleado->EMP_ID }}"
                                                    {{ old('EMP_ID', isset($datosHorario) ? $datosHorario['emp_id'] : null) == $empleado->EMP_ID ? 'selected' : '' }}>
                                                    {{ $empleado->EMP_NOMBRES }} {{ $empleado->EMP_APELLIDOS ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('EMP_ID')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tipo de Novedad -->
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="TIN_ID" class="form-label required">
                                            <i class="mdi mdi-format-list-bulleted-type me-1"></i>Tipo de Novedad
                                        </label>
                                        <select name="TIN_ID" id="TIN_ID"
                                            class="form-control form-control-lg @error('TIN_ID') is-invalid @enderror"
                                            required>
                                            <option value="">-- Seleccione el tipo --</option>
                                            @foreach ($tiposNovedades as $tipo)
                                                <option value="{{ $tipo->TIN_ID }}"
                                                    {{ old('TIN_ID') == $tipo->TIN_ID ? 'selected' : '' }}>
                                                    {{ $tipo->TIN_NOMBRE }} ({{ $tipo->tipo_texto }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('TIN_ID')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="col-lg-8 col-md-8">
                                    <div class="form-group">
                                        <label for="NOV_DESCRIPCION" class="form-label required">
                                            <i class="mdi mdi-text-long me-1"></i>Descripción de la Novedad
                                        </label>
                                        <textarea name="NOV_DESCRIPCION" id="NOV_DESCRIPCION" rows="4"
                                            class="form-control form-control-lg @error('NOV_DESCRIPCION') is-invalid @enderror"
                                            placeholder="Describa detalladamente la novedad..." required>{{ old('NOV_DESCRIPCION') }}</textarea>
                                        <div class="form-text">
                                            <i class="mdi mdi-information-outline"></i>
                                            Proporcione todos los detalles relevantes sobre la novedad
                                        </div>
                                        @error('NOV_DESCRIPCION')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Fecha de la Novedad -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="NOV_FECHA" class="form-label" id="nov-fecha-label">
                                            <i class="mdi mdi-calendar me-1"></i>Fecha de la Novedad
                                        </label>
                                        <input type="date" name="NOV_FECHA" id="NOV_FECHA"
                                            class="form-control form-control-lg @error('NOV_FECHA') is-invalid @enderror"
                                            value="{{ old('NOV_FECHA', \Carbon\Carbon::now('America/Bogota')->toDateString()) }}">
                                        <div class="form-text">
                                            <i class="mdi mdi-information-outline"></i>
                                            Fecha en que ocurre la novedad
                                        </div>
                                        @error('NOV_FECHA')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Fecha de referencia -->
                        @if (empty($datosHorario))
                            <div class="form-section mb-4" id="horarios-section">
                                <div class="section-header mb-3">
                                    <h5 class="section-title">
                                        <i class="mdi mdi-calendar-multiple-check text-primary me-2"></i>
                                        Horarios del empleado
                                    </h5>
                                    <hr class="section-divider">
                                </div>

                                @error('horarios')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div id="horarios-message" class="alert alert-info">
                                    Selecciona un empleado y, opcionalmente, ajusta la fecha para filtrar los horarios
                                    disponibles.
                                </div>

                                <!-- Mensaje cuando no hay horarios disponibles -->
                                <div id="no-horarios-message" class="alert alert-warning d-none">
                                    <i class="mdi mdi-information-outline me-2"></i>
                                    <strong>El empleado seleccionado no tiene horarios asignados en el período consultado.</strong><br>
                                    Puedes registrar la novedad definiendo manualmente el período de tiempo afectado.
                                </div>

                                <!-- Campos para definir horario manual cuando no hay malla -->
                                <div id="manual-schedule-section" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hora_inicio_manual" class="form-label required">
                                                    <i class="mdi mdi-clock-start me-1"></i>Hora Inicio
                                                </label>
                                                <input type="time" name="hora_inicio_manual" id="hora_inicio_manual"
                                                       class="form-control form-control-lg @error('hora_inicio_manual') is-invalid @enderror"
                                                       value="{{ old('hora_inicio_manual') }}">
                                                @error('hora_inicio_manual')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hora_fin_manual" class="form-label required">
                                                    <i class="mdi mdi-clock-end me-1"></i>Hora Fin
                                                </label>
                                                <input type="time" name="hora_fin_manual" id="hora_fin_manual"
                                                       class="form-control form-control-lg @error('hora_fin_manual') is-invalid @enderror"
                                                       value="{{ old('hora_fin_manual') }}">
                                                @error('hora_fin_manual')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information-outline me-2"></i>
                                        <strong>Nota:</strong> Al registrar esta novedad, se creará automáticamente un bloqueo de horario
                                        para el período especificado, el cual aparecerá en el calendario del empleado como un evento bloqueado.
                                    </div>
                                </div>

                                <div class="row align-items-end mb-3">
                                    <div class="col-lg-3 col-md-6">
                                        <label for="schedule-date-start" class="form-label">
                                            <i class="mdi mdi-calendar-start me-1"></i>Fecha desde
                                        </label>
                                        <input type="date" id="schedule-date-start" class="form-control form-control-lg"
                                            value="{{ old('NOV_FECHA_INICIO', \Carbon\Carbon::now('America/Bogota')->toDateString()) }}">
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <label for="schedule-date-end" class="form-label">
                                            <i class="mdi mdi-calendar-end me-1"></i>Fecha hasta
                                        </label>
                                        <input type="date" id="schedule-date-end" class="form-control form-control-lg"
                                            value="{{ old('NOV_FECHA_FIN', \Carbon\Carbon::now('America/Bogota')->toDateString()) }}">
                                    </div>
                                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                                        <button type="button" class="btn btn-outline-primary w-100" id="schedule-date-apply">
                                            <i class="mdi mdi-magnify me-1"></i> Filtrar rango
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                                        <div class="schedule-actions">
                                            <button type="button" class="btn btn-outline-success btn-sm action-btn" id="select-all-schedules">
                                                <i class="mdi mdi-checkbox-multiple-marked-outline me-1"></i>
                                                <span>Seleccionar todo</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm action-btn" id="clear-all-schedules">
                                                <i class="mdi mdi-close-box-outline me-1"></i>
                                                <span>Limpiar</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive d-none" id="horarios-table-wrapper">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 60px;">Seleccionar</th>
                                            <th>Fecha</th>
                                            <th>Hora inicio</th>
                                            <th>Hora fin</th>
                                            <th>Cliente</th>
                                            <th>Campaña</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="horarios-body">
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Sección 3: Documentos -->
                        <div class="form-section mb-4 mt-4">
                            <div class="section-header mb-3">
                                <h5 class="section-title">
                                    <i class="mdi mdi-paperclip text-primary me-2"></i>
                                    Documentos de Soporte
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <div class="form-group">
                                <label for="archivos" class="form-label">
                                    <i class="mdi mdi-file-plus me-1"></i>Archivos Adjuntos
                                    <span class="badge badge-secondary ms-1">Opcional</span>
                                </label>

                                <div class="upload-area">
                                    <div class="upload-content">
                                        <i class="mdi mdi-cloud-upload upload-icon"></i>
                                        <h6 class="upload-title">Seleccionar Archivos</h6>
                                        <p class="upload-subtitle">PDF, Imágenes, Word • Máximo 5MB por archivo</p>
                                        <input type="file" name="archivos[]" id="archivos"
                                            class="upload-input @error('archivos.*') is-invalid @enderror"
                                            multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    </div>
                                </div>

                                @error('archivos.*')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror

                                <div id="file-preview" class="file-preview mt-3"></div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="form-actions">
                            <hr class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                @if (isset($datosHorario))
                                    <a href="{{ route('Individual.redirect', ['EMP_ID' => $datosHorario['emp_id'], 'FECHA' => $datosHorario['mal_dia']]) }}"
                                        class="btn btn-outline-secondary btn-lg">
                                        <i class="mdi mdi-arrow-left me-2"></i>Cancelar
                                    </a>
                                @else
                                    <a href="{{ route('Novedades.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="mdi mdi-arrow-left me-2"></i>Cancelar
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="mdi mdi-content-save me-2"></i>Guardar Novedad
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* ===== ESTILOS PARA EL FORMULARIO DE NUEVA NOVEDAD ===== */

        /* Card principal */
        .card.shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            border-radius: 1rem;
            overflow: hidden;
        }

        /* Header con gradiente */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            position: relative;
        }

        .bg-gradient-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.2;
        }

        .card-header .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card-header .icon-circle i {
            font-size: 1.5rem;
        }

        /* Secciones del formulario */
        .form-section {
            position: relative;
        }

        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .section-divider {
            margin: 0.5rem 0 1rem 0;
            border-top: 2px solid #e9ecef;
            opacity: 1;
        }

        /* Labels mejorados */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label.required::after {
            content: '*';
            color: #dc3545;
            margin-left: 0.25rem;
            font-weight: bold;
        }

        .form-label i {
            color: #6c757d;
        }

        /* Form controls mejorados */
        .form-control,
        .form-control-lg {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-control-lg:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transform: translateY(-1px);
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        /* Badges para campos opcionales */
        .badge.badge-secondary {
            background-color: #6c757d;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Form text mejorado */
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-text i {
            margin-right: 0.5rem;
        }

        /* Upload area */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
            position: relative;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .upload-content {
            pointer-events: none;
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
            display: block;
        }

        .upload-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .upload-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        .upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* File preview */
        .file-preview {
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        /* Estilos para la lista de archivos */
        #file-list .alert {
            margin-top: 1rem;
            border: 1px solid #bee5eb;
            background-color: #d1ecf1;
        }

        #file-list ul {
            list-style: none;
            padding-left: 0;
        }

        #file-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        #file-list li:last-child {
            border-bottom: none;
        }

        /* Botones de acción */
        .form-actions {
            margin-top: 2rem;
        }

        .btn-lg {
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }

        /* Botones de acción para horarios */
        .schedule-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-width: 1.5px;
            padding: 0.5rem 0.75rem;
            min-width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .action-btn i {
            font-size: 0.875rem;
        }

        .action-btn span {
            font-size: 0.875rem;
        }

        .btn-outline-success.action-btn:hover {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-outline-secondary.action-btn:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Estilos para tabla de horarios */
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .thead-light th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Animaciones */
        .form-section {
            animation: slideInUp 0.6s ease-out;
        }

        .form-section:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-section:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                text-align: center;
            }

            .card-header .icon-circle {
                margin-bottom: 1rem;
                margin-right: 0 !important;
            }

        .btn-lg {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .schedule-actions {
            justify-content: center;
            flex-direction: column;
        }

        .action-btn {
            min-width: auto;
        }

        .d-flex.justify-content-between {
            flex-direction: column-reverse;
        }
        }

        /* Estados de validación */
        .is-invalid {
            border-color: #dc3545 !important;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Mejoras en las clases de Bootstrap 5 que no existen en Bootstrap 4 */
        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .me-3 {
            margin-right: 1rem !important;
        }

        .ms-1 {
            margin-left: 0.25rem !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .bg-opacity-20 {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        const horariosSection = document.getElementById('horarios-section');
        if (horariosSection) {
            const empleadosSelect = document.getElementById('EMP_ID');
            const fechaInicioInput = document.getElementById('schedule-date-start');
            const fechaFinInput = document.getElementById('schedule-date-end');
            const aplicarFiltroBtn = document.getElementById('schedule-date-apply');
            const selectAllBtn = document.getElementById('select-all-schedules');
            const clearAllBtn = document.getElementById('clear-all-schedules');
            const horariosMessage = document.getElementById('horarios-message');
            const noHorariosMessage = document.getElementById('no-horarios-message');
            const manualScheduleSection = document.getElementById('manual-schedule-section');
            const horariosWrapper = document.getElementById('horarios-table-wrapper');
            const horariosBody = document.getElementById('horarios-body');
            const horariosEndpointTemplate = "{{ route('Novedades.horariosEmpleado', ['empleado' => '__ID__']) }}";
            let isLoadingHorarios = false;
            const selectedHorarios = new Set(@json(array_map('strval', old('horarios', []))));

            function snapshotCurrentSelection() {
                const marcados = document.querySelectorAll('input[name=\"horarios[]\"]:checked');
                if (marcados.length > 0) {
                    selectedHorarios.clear();
                    marcados.forEach((checkbox) => selectedHorarios.add(checkbox.value));
                }
            }

            async function loadHorarios() {
                if (isLoadingHorarios) {
                    return;
                }

                const empleadoId = empleadosSelect.value;
                const fechaInicio = fechaInicioInput?.value;
                const fechaFin = fechaFinInput?.value;

                snapshotCurrentSelection();

                horariosBody.innerHTML = '';
                horariosWrapper.classList.add('d-none');
                noHorariosMessage.classList.add('d-none');
                manualScheduleSection.classList.add('d-none');
                horariosMessage.classList.remove('alert-danger', 'alert-success');
                horariosMessage.classList.add('alert-info');

                if (!empleadoId) {
                    horariosMessage.textContent = 'Selecciona un empleado para ver sus horarios disponibles.';
                    return;
                }

                if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                    horariosMessage.textContent = 'La fecha inicial no puede ser mayor que la fecha final.';
                    horariosMessage.classList.remove('alert-info');
                    horariosMessage.classList.add('alert-danger');
                    return;
                }

                horariosMessage.textContent = 'Cargando horarios disponibles...';
                isLoadingHorarios = true;

                try {
                    const endpoint = horariosEndpointTemplate.replace('__ID__', encodeURIComponent(empleadoId));
                    const params = new URLSearchParams();
                    if (fechaInicio) {
                        params.append('fecha_inicio', fechaInicio);
                    }
                    if (fechaFin) {
                        params.append('fecha_fin', fechaFin);
                    }
                    const url = `${endpoint}${params.toString() ? `?${params.toString()}` : ''}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('No se pudo obtener la información de horarios');
                    }

                    const payload = await response.json();
                    const horarios = Array.isArray(payload.data) ? payload.data : [];
                    renderHorarios(horarios);
                } catch (error) {
                    console.error(error);
                    horariosMessage.textContent = 'No fue posible cargar los horarios del empleado.';
                    horariosMessage.classList.remove('alert-info');
                    horariosMessage.classList.add('alert-danger');
                } finally {
                    isLoadingHorarios = false;
                }
            }

            function renderHorarios(horarios) {
                if (!horarios.length) {
                    horariosMessage.textContent = 'No se encontraron horarios para la combinación seleccionada.';
                    horariosWrapper.classList.add('d-none');

                    // Mostrar mensaje y campos manuales para creación de novedad sin horarios
                    noHorariosMessage.classList.remove('d-none');
                    manualScheduleSection.classList.remove('d-none');
                    return;
                }

                // Ocultar mensaje y campos manuales si hay horarios disponibles
                noHorariosMessage.classList.add('d-none');
                manualScheduleSection.classList.add('d-none');

                horariosMessage.textContent = 'Selecciona los horarios que deseas asociar a la novedad.';
                horariosMessage.classList.remove('alert-danger', 'alert-success');
                horariosMessage.classList.add('alert-info');
                horariosWrapper.classList.remove('d-none');

                const idsPresentes = new Set();
                horarios.forEach((horario) => {
                    idsPresentes.add(String(horario.id ?? horario.MAL_ID));
                });
                Array.from(selectedHorarios).forEach((id) => {
                    if (!idsPresentes.has(id)) {
                        selectedHorarios.delete(id);
                    }
                });

                horariosBody.innerHTML = horarios.map((horario) => {
                    const id = String(horario.id ?? horario.MAL_ID);
                    const checked = selectedHorarios.has(id) ? 'checked' : '';
                    const estado = horario.estado ?? horario.MAL_ESTADO ?? 0;
                    const estadoTexto = estado === 1 ? 'Activo' : 'Inactivo';
                    const estadoBadge = estado === 1 ? 'success' : 'danger';
                    const fecha = horario.fecha_formateada ?? horario.fecha ?? horario.MAL_DIA ?? 'N/A';
                    const inicio = horario.hora_inicio_formateada ?? horario.hora_inicio ?? horario.MAL_INICIO ??
                        'N/A';
                    const fin = horario.hora_fin_formateada ?? horario.hora_fin ?? horario.MAL_FINAL ?? 'N/A';
                    const cliente = horario.cliente ?? 'N/A';
                    const campana = horario.campana ?? 'N/A';

                    return `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input" name="horarios[]" value="${id}" ${checked}>
                        </td>
                        <td>${fecha}</td>
                        <td>${inicio}</td>
                        <td>${fin}</td>
                        <td>${cliente}</td>
                        <td>${campana}</td>
                        <td class="text-center">
                            <span class="badge badge-${estadoBadge}">${estadoTexto}</span>
                        </td>
                    </tr>
                `;
                }).join('');
            }

            horariosBody?.addEventListener('change', (event) => {
                if (event.target && event.target.matches('input[name="horarios[]"]')) {
                    if (event.target.checked) {
                        selectedHorarios.add(event.target.value);
                    } else {
                        selectedHorarios.delete(event.target.value);
                    }
                }
            });

            empleadosSelect?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            aplicarFiltroBtn?.addEventListener('click', () => {
                loadHorarios();
            });

            fechaInicioInput?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            fechaFinInput?.addEventListener('change', () => {
                if (!isLoadingHorarios) {
                    loadHorarios();
                }
            });

            selectAllBtn?.addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('#horarios-body input[name=\"horarios[]\"]');
                if (!checkboxes.length) {
                    horariosMessage.textContent = 'No hay horarios cargados para seleccionar.';
                    horariosMessage.classList.remove('alert-danger');
                    horariosMessage.classList.add('alert-info');
                    return;
                }

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = true;
                    selectedHorarios.add(checkbox.value);
                });

                horariosMessage.textContent = 'Todos los horarios visibles fueron seleccionados.';
                horariosMessage.classList.remove('alert-danger');
                horariosMessage.classList.add('alert-success');
            });

            clearAllBtn?.addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('#horarios-body input[name=\"horarios[]\"]');
                if (!checkboxes.length) {
                    horariosMessage.textContent = 'No hay horarios cargados para limpiar.';
                    horariosMessage.classList.remove('alert-danger');
                    horariosMessage.classList.add('alert-info');
                    return;
                }

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                selectedHorarios.clear();

                horariosMessage.textContent = 'Se limpiaron las selecciones de horarios.';
                horariosMessage.classList.remove('alert-danger');
                horariosMessage.classList.add('alert-info');
            });

            if (empleadosSelect?.value) {
                loadHorarios();
            }

            // Agregar validación para campos manuales
            const horaInicioManual = document.getElementById('hora_inicio_manual');
            const horaFinManual = document.getElementById('hora_fin_manual');
            const novFecha = document.getElementById('NOV_FECHA');

            // Hacer campos requeridos cuando la sección manual está visible
            function updateManualFieldsRequired() {
                const isVisible = !manualScheduleSection.classList.contains('d-none');
                if (horaInicioManual) horaInicioManual.required = isVisible;
                if (horaFinManual) horaFinManual.required = isVisible;
                if (novFecha) novFecha.required = isVisible;

                // Actualizar la etiqueta del campo fecha
                const fechaLabel = document.getElementById('nov-fecha-label');
                if (fechaLabel) {
                    if (isVisible) {
                        fechaLabel.classList.add('required');
                    } else {
                        fechaLabel.classList.remove('required');
                    }
                }
            }

            // Observar cambios en la visibilidad de la sección manual
            const observer = new MutationObserver(updateManualFieldsRequired);
            if (manualScheduleSection) {
                observer.observe(manualScheduleSection, { attributes: true, attributeFilter: ['class'] });
            }
        }

        // Preview de archivos
        document.getElementById('archivos').addEventListener('change', function() {
            const files = Array.from(this.files);
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            files.forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert(`El archivo ${file.name} no es un tipo válido`);
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert(`El archivo ${file.name} es demasiado grande (máximo 5MB)`);
                    this.value = '';
                    return;
                }
            });
        });
    </script>
@endsection


