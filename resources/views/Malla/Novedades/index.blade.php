@extends('layouts.main')

@section('main')
    <!-- Bread crumb and right sidebar toggle -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor mb-0 mt-0">Gestión de Novedades de Nómina</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Novedades</li>
            </ol>
        </div>
        <div class="col-md-6 col-4 align-self-center">
            <div class="d-flex justify-content-end">
                <a href="{{ route('Novedades.create') }}" class="btn btn-success btn-sm mr-2">
                    <i class="mdi mdi-plus-circle"></i> Nueva Novedad
                </a>
                <a href="{{ route('Novedades.dashboard') }}" class="btn btn-info btn-sm mr-2">
                    <i class="mdi mdi-chart-pie"></i> Dashboard
                </a>
                <button class="btn btn-primary btn-sm" id="btnExportar" data-toggle="modal" data-target="#exportModal">
                    <i class="mdi mdi-download"></i> Exportar
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas Cards -->
    <div class="row mb-4">
        <!-- Pendientes -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card stats-card-warning h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stats-info">
                            <h6 class="stats-title text-white-50 mb-2">
                                Pendientes
                            </h6>
                            <h2 class="stats-number text-white mb-0 font-weight-bold">{{ $estadisticas['pendientes'] }}</h2>
                            <small class="stats-percentage text-white-50">
                                @if ($estadisticas['total'] > 0)
                                    {{ round(($estadisticas['pendientes'] / $estadisticas['total']) * 100, 1) }}% del total
                                @else
                                    0% del total
                                @endif
                            </small>
                        </div>
                        <div class="stats-icon">
                            <div class="icon-circle icon-warning">
                                <i class="mdi mdi-alert-circle text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-warning-light"
                            style="width: {{ $estadisticas['total'] > 0 ? ($estadisticas['pendientes'] / $estadisticas['total']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aprobadas -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card stats-card-success h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stats-info">
                            <h6 class="stats-title text-white-50 mb-2">
                                <i class="mdi mdi-check-circle-outline me-2"></i>Aprobadas
                            </h6>
                            <h2 class="stats-number text-white mb-0 font-weight-bold">{{ $estadisticas['aprobadas'] }}</h2>
                            <small class="stats-percentage text-white-50">
                                @if ($estadisticas['total'] > 0)
                                    {{ round(($estadisticas['aprobadas'] / $estadisticas['total']) * 100, 1) }}% del total
                                @else
                                    0% del total
                                @endif
                            </small>
                        </div>
                        <div class="stats-icon">
                            <div class="icon-circle icon-success">
                                <i class="mdi mdi-check-all text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-success-light"
                            style="width: {{ $estadisticas['total'] > 0 ? ($estadisticas['aprobadas'] / $estadisticas['total']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rechazadas -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card stats-card-danger h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stats-info">
                            <h6 class="stats-title text-white-50 mb-2">
                                <i class="mdi mdi-close-circle-outline me-2"></i>Rechazadas
                            </h6>
                            <h2 class="stats-number text-white mb-0 font-weight-bold">{{ $estadisticas['rechazadas'] }}</h2>
                            <small class="stats-percentage text-white-50">
                                @if ($estadisticas['total'] > 0)
                                    {{ round(($estadisticas['rechazadas'] / $estadisticas['total']) * 100, 1) }}% del total
                                @else
                                    0% del total
                                @endif
                            </small>
                        </div>
                        <div class="stats-icon">
                            <div class="icon-circle icon-danger">
                                <i class="mdi mdi-close text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-danger-light"
                            style="width: {{ $estadisticas['total'] > 0 ? ($estadisticas['rechazadas'] / $estadisticas['total']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
            <div class="card stats-card stats-card-info h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stats-info">
                            <h6 class="stats-title text-white-50 mb-2">
                                Total Novedades
                            </h6>
                            <h2 class="stats-number text-white mb-0 font-weight-bold">{{ $estadisticas['total'] }}</h2>
                            <small class="stats-percentage text-white-50">
                                Registros totales
                            </small>
                        </div>
                        <div class="stats-icon">
                            <div class="icon-circle">
                                <i class="mdi mdi-chart-bar text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-info-light" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('Novedades.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado_aprobacion" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente"
                                            {{ request('estado_aprobacion') == 'pendiente' ? 'selected' : '' }}>Pendientes
                                        </option>
                                        <option value="aprobada"
                                            {{ request('estado_aprobacion') == 'aprobada' ? 'selected' : '' }}>Aprobadas
                                        </option>
                                        <option value="rechazada"
                                            {{ request('estado_aprobacion') == 'rechazada' ? 'selected' : '' }}>Rechazadas
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Novedad</label>
                                    <select name="tipo_novedad" class="form-control">
                                        <option value="">Todos los tipos</option>
                                        @foreach ($tiposNovedades as $tipo)
                                            <option value="{{ $tipo->TIN_ID }}"
                                                {{ request('tipo_novedad') == $tipo->TIN_ID ? 'selected' : '' }}>
                                                {{ $tipo->TIN_NOMBRE }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control"
                                        value="{{ request('fecha_inicio') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control"
                                        value="{{ request('fecha_fin') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                    <a href="{{ route('Novedades.index') }}" class="btn btn-secondary">Limpiar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Novedades -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Lista de Novedades</h4>
                    <div class="bulk-actions" style="display: none;">
                        <button type="button" class="btn btn-success btn-sm" onclick="showApprovalModal()">
                            <i class="mdi mdi-check-all"></i> Aprobar Seleccionadas
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ml-2" onclick="showRejectionModal()">
                            <i class="mdi mdi-close-thick"></i> Rechazar Seleccionadas
                        </button>
                        <span class="ml-3 text-muted">
                            <span id="selected-count">0</span> novedades seleccionadas
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display nowrap" id="table_novedades" style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select_all">
                                    </th>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Identificación</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Fecha registro</th>
                                    <th>Estado</th>
                                    <th>Registrado por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($novedades as $novedad)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row_checkbox" value="{{ $novedad->NOV_ID }}">
                                        </td>
                                        <td>{{ $novedad->NOV_ID }}</td>
                                        <td>
                                            {{ $novedad->empleado->EMP_NOMBRES ?? 'N/A' }}
                                            {{ $novedad->empleado->EMP_APELLIDOS ?? '' }}
                                        </td>
                                        <td>{{ $novedad->empleado->EMP_CEDULA ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $novedad->tipoNovedad->TIN_NOMBRE ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($novedad->NOV_DESCRIPCION, 50) }}</td>
                                        <td>{{ optional($novedad->NOV_FECHA)->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $novedad->estado_color }}">
                                                {{ $novedad->estado_texto }}
                                            </span>
                                        </td>
                                        <td>{{ $novedad->usuario->name ?? 'N/A' }}</td>
                                         <td>
                                             <a href="{{ route('Novedades.show', $novedad->NOV_ID) }}"
                                                 class="btn btn-info btn-sm" rel="tooltip" title="Ver">
                                                 <i class="mdi mdi-eye"></i>
                                             </a>

                                             @if ($novedad->NOV_ESTADO_APROBACION === 'pendiente')
                                                 <form method="POST"
                                                     action="{{ route('Novedades.aprobar', $novedad->NOV_ID) }}"
                                                     style="display: inline-block;">
                                                     @csrf
                                                     <button type="submit" class="btn btn-success btn-sm" rel="tooltip"
                                                         title="Aprobar novedad">
                                                         <i class="mdi mdi-check"></i>
                                                     </button>
                                                 </form>

                                                 <button type="button" class="btn btn-danger btn-sm" rel="tooltip"
                                                     title="Rechazar novedad"
                                                     onclick="showRejectModal({{ $novedad->NOV_ID }}, '{{ addslashes($novedad->empleado->EMP_NOMBRES ?? 'N/A') }}')">
                                                     <i class="mdi mdi-close"></i>
                                                 </button>

                                                 <a href="{{ route('Novedades.edit', $novedad->NOV_ID) }}"
                                                     class="btn btn-warning btn-sm" rel="tooltip" title="Editar">
                                                     <i class="mdi mdi-pencil"></i>
                                                 </a>

                                                 <form method="POST"
                                                     action="{{ route('Novedades.destroy', $novedad->NOV_ID) }}"
                                                     style="display: inline-block;">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="btn btn-danger btn-sm" rel="tooltip"
                                                         title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                                         <i class="mdi mdi-delete"></i>
                                                     </button>
                                                 </form>
                                             @elseif ($novedad->NOV_ESTADO_APROBACION === 'rechazada')
                                                 <a href="{{ route('Novedades.edit', $novedad->NOV_ID) }}"
                                                     class="btn btn-warning btn-sm" rel="tooltip" title="Editar y reenviar">
                                                     <i class="mdi mdi-pencil"></i>
                                                 </a>
                                             @endif
                                         </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Exportación -->
    @include('Malla.Novedades.export-modal')

    <!-- Modal de Aprobación/Rechazo -->
    @include('Malla.Novedades.approval-modal')

    <!-- Botón de emergencia para desbloquear scroll -->
    <button id="unlock-scroll-btn" onclick="window.unlockScroll()" title="Desbloquear scroll">
        🔓 Desbloquear Scroll
    </button>
@endsection

@section('styles')
    <style>
        /* IMPORTANTE: Garantizar que el scroll nunca se bloquee permanentemente */
        body {
            overflow-x: auto !important;
        }

        body:not(.modal-open) {
            overflow-y: auto !important;
        }

        /* Fix para evitar bloqueo de scroll en modales */
        .modal-open {
            overflow: hidden;
        }

        /* Botón de emergencia para desbloquear scroll */
        #unlock-scroll-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            display: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        #unlock-scroll-btn:hover {
            background: #c82333;
        }

        /* Estilos para las tarjetas de estadísticas mejoradas */
        .stats-card {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stats-card-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
        }

        .stats-card-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stats-card-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .stats-card-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .stats-card .card-body {
            position: relative;
            z-index: 2;
        }

        .stats-title {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stats-percentage {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .stats-icon {
            opacity: 0.9;
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .icon-circle i {
            font-size: 1.5rem;
        }

        /* Estilos específicos para los círculos de iconos por tipo */
        .icon-warning {
            background: rgba(255, 193, 7, 0.3) !important;
            border: 1px solid rgba(255, 193, 7, 0.5) !important;
        }

        .icon-success {
            background: rgba(40, 167, 69, 0.3) !important;
            border: 1px solid rgba(40, 167, 69, 0.5) !important;
        }

        .icon-danger {
            background: rgba(220, 53, 69, 0.3) !important;
            border: 1px solid rgba(220, 53, 69, 0.5) !important;
        }

        .icon-info {
            background: rgba(23, 162, 184, 0.3) !important;
            border: 1px solid rgba(23, 162, 184, 0.5) !important;
        }

        .progress-sm {
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .bg-warning-light {
            background: rgba(255, 255, 255, 0.4) !important;
        }

        .bg-success-light {
            background: rgba(255, 255, 255, 0.4) !important;
        }

        .bg-danger-light {
            background: rgba(255, 255, 255, 0.4) !important;
        }

        .bg-info-light {
            background: rgba(255, 255, 255, 0.4) !important;
        }

        .progress-bar {
            border-radius: 2px;
            transition: width 0.6s ease;
        }

        /* Efecto de resplandor en hover */
        .stats-card:hover::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3));
            border-radius: 17px;
            z-index: -1;
            opacity: 0;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                opacity: 0.5;
            }

            to {
                opacity: 0.8;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-number {
                font-size: 2rem;
            }

            .icon-circle {
                width: 50px;
                height: 50px;
            }

            .icon-circle i {
                font-size: 1.25rem;
            }
        }

        /* Animation on load */
        .stats-card {
            animation: slideInUp 0.6s ease-out;
        }

        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mejorar el text-white-50 para mejor contraste */
        .text-white-50 {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* ===== ESTILOS SIMPLES PARA LA TABLA DE NOVEDADES ===== */

        /* Asegurar que todas las columnas sean visibles */
        #table_novedades {
            white-space: nowrap;
        }

        /* Mantener el contenido de las celdas sin cortar */
        #table_novedades td,
        #table_novedades th {
            white-space: nowrap;
            overflow: visible;
        }

        /* Mejorar la apariencia de los badges */
        #table_novedades .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        /* Espaciado en botones de acción */
        #table_novedades .btn {
            margin-right: 2px;
        }

        #table_novedades .btn:last-child {
            margin-right: 0;
        }

        /* ===== ESTILOS PARA MODAL DE EXPORTACIÓN ===== */

        .modal-content.border-0 {
            border-radius: 1rem;
            overflow: hidden;
        }

        .modal-header.bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
            border-bottom: none;
        }

        .modal-body {
            background: #f8f9fa;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .form-control-lg {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control-lg:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transform: translateY(-1px);
        }

        .export-options {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-check-label {
            font-weight: 500;
            color: #495057;
            cursor: pointer;
        }

        .export-preview .card {
            border: 1px dashed #007bff;
            background: #e3f2fd !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .alert.border-left-info {
            background-color: #d1ecf1;
            border-color: #b8daff;
            color: #0c5460;
        }

        /* Modal fix - asegurar que aparezca correctamente */
        #exportModal {
            z-index: 1050 !important;
        }

        #exportModal .modal-dialog {
            z-index: 1051 !important;
            margin: 30px auto !important;
        }

        #exportModal.show {
            display: block !important;
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Responsive modal */
        @media (max-width: 768px) {
            .modal-dialog.modal-lg {
                margin: 1rem;
                max-width: calc(100% - 2rem);
            }

            .modal-body {
                padding: 1.5rem 1rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Función para mostrar notificaciones
        function showNotification(message, type = 'info') {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else if (typeof $.toast !== 'undefined') {
                $.toast({
                    heading: type.toUpperCase(),
                    text: message,
                    position: 'top-right',
                    loaderBg: type === 'success' ? '#ff6849' : (type === 'error' ? '#bf441d' : '#01a9ac'),
                    icon: type,
                    hideAfter: 3000,
                    stack: 6
                });
            } else {
                alert(message);
            }
        }

        // Mensaje de éxito/error
        @if (session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif

        $(document).ready(function() {
            // Función para limpiar estado de modales
            function cleanupModalState() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
                console.log('Modal state cleaned up');
            }

            // Limpiar estado al cargar la página (por si quedó algo mal)
            cleanupModalState();

            const modal = $('#exportModal');
            const button = $('#btnExportar');

            button.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (modal.length === 0) {
                    alert('Error: Modal no encontrado.');
                    return;
                }

                try {
                    modal.modal('show');

                    setTimeout(function() {
                        if (!modal.hasClass('show') || modal.css('display') === 'none') {
                            cleanupModalState();

                            modal.removeClass('fade')
                                .addClass('show')
                                .css({
                                    'display': 'block !important',
                                    'z-index': '1050',
                                    'opacity': '1',
                                    'visibility': 'visible',
                                    'position': 'fixed',
                                    'top': '0',
                                    'left': '0',
                                    'width': '100%',
                                    'height': '100%'
                                })
                                .attr('aria-hidden', 'false')
                                .show();

                            modal.find('.modal-dialog').css({
                                'transform': 'scale(1)',
                                'opacity': '1',
                                'margin': '30px auto'
                            });

                            $('body').addClass('modal-open').css('overflow', 'hidden');
                            $('<div class="modal-backdrop fade show" style="z-index: 1040;"></div>')
                                .appendTo('body');

                            modal.find('[data-dismiss="modal"]').off('click.manualModal').on(
                                'click.manualModal',
                                function() {
                                    modal.hide().removeClass('show');
                                    cleanupModalState();
                                });

                            $('.modal-backdrop').off('click.manualModal').on('click.manualModal',
                                function() {
                                    modal.hide().removeClass('show');
                                    cleanupModalState();
                                });
                        }
                    }, 200);

                } catch (error) {
                    alert('Error al abrir modal: ' + error.message);
                    cleanupModalState();
                }
            });

            // Función para actualizar contador y mostrar/ocultar acciones masivas
            function updateBulkActions() {
                const checkedCount = $('.row_checkbox:checked').length;
                const totalCount = $('.row_checkbox').length;

                $('#selected-count').text(checkedCount);

                if (checkedCount > 0) {
                    $('.bulk-actions').show();
                } else {
                    $('.bulk-actions').hide();
                }

                // Actualizar el select all
                if (checkedCount === totalCount && totalCount > 0) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            }

            $('#select_all').on('click', function() {
                $('.row_checkbox').prop('checked', this.checked);
                updateBulkActions();
            });

            // Manejar clicks individuales
            $(document).on('change', '.row_checkbox', function() {
                updateBulkActions();
            });

            // Inicializar el estado
            updateBulkActions();

            // Limpiar estado al salir de la página o navegar
            $(window).on('beforeunload unload pagehide', function() {
                cleanupModalState();
            });

            // Limpiar estado cuando se presiona ESC
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    cleanupModalState();
                }
            });

            // Función de emergencia para liberar scroll (disponible globalmente)
            window.unlockScroll = function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
                $('.modal').hide().removeClass('show');
                $('#unlock-scroll-btn').hide();
                console.log('Scroll desbloqueado manualmente');
            };

        // Función para mostrar modal de rechazo
        window.showRejectModal = function(novedadId, empleadoNombre) {
            $('#rejectNovedadId').val(novedadId);
            $('#rejectEmpleadoNombre').text(empleadoNombre);
            // Actualizar el action del form
            $('#rejectModal form').attr('action', '{{ route("Novedades.rechazar", ":id") }}'.replace(':id', novedadId));
            $('#rejectModal').modal('show');
        };

        // Monitorear si el scroll está bloqueado y mostrar botón de emergencia
        function checkScrollStatus() {
                const bodyOverflow = $('body').css('overflow');
                const bodyOverflowY = $('body').css('overflow-y');
                const hasModalOpen = $('body').hasClass('modal-open');
                const hasVisibleModal = $('.modal.show:visible').length > 0;

                // Si el scroll está bloqueado pero no hay modales visibles, mostrar botón de emergencia
                if ((bodyOverflow === 'hidden' || bodyOverflowY === 'hidden' || hasModalOpen) && !hasVisibleModal) {
                    $('#unlock-scroll-btn').show();
                    console.warn('Scroll bloqueado detectado sin modal visible');
                } else {
                    $('#unlock-scroll-btn').hide();
                }
            }

            // Verificar estado del scroll cada 2 segundos
            setInterval(checkScrollStatus, 2000);
        });

        // Limpiar intervalos al salir
        $(window).on('beforeunload', function() {
            if (window.checkInterval) {
                clearInterval(window.checkInterval);
            }
        });
    </script>

    <!-- Modal de Rechazo de Novedad -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="mdi mdi-close-thick text-danger"></i> Rechazar Novedad
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    @csrf
                    <input type="hidden" name="novedad_id" id="rejectNovedadId">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-circle-outline"></i>
                            <strong>Advertencia:</strong> Está a punto de rechazar la novedad de <strong id="rejectEmpleadoNombre"></strong>.
                            El usuario podrá editarla y reenviarla para nueva evaluación.
                        </div>
                        <div class="form-group">
                            <label for="observaciones" class="form-label required">
                                <i class="mdi mdi-comment-text-outline"></i> Motivo del rechazo
                            </label>
                            <textarea name="observaciones" id="observaciones" class="form-control"
                                rows="4" placeholder="Explique detalladamente el motivo del rechazo..."
                                required maxlength="500"></textarea>
                            <div class="form-text">
                                Máximo 500 caracteres. Esta información será visible para el usuario que creó la novedad.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="mdi mdi-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="mdi mdi-close-thick"></i> Rechazar Novedad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
