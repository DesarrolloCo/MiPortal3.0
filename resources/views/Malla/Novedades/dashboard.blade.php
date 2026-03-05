@extends('layouts.main')

@section('main')

<!-- Bread crumb and right sidebar toggle -->
<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor mb-0 mt-0">Dashboard - Novedades de Nómina</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Novedades.index') }}">Novedades</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <div class="d-flex justify-content-end">
            <a href="{{ route('Novedades.index') }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas Cards Principales Mejoradas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-warning" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-warning">
                                <i class="mdi mdi-clock text-white"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number text-warning" data-count="{{ $estadisticas['pendientes'] }}">0</div>
                            <div class="stat-label">Pendientes</div>
                            <div class="stat-sublabel">
                                @if($estadisticasAvanzadas['urgentes'] > 0)
                                    <span class="badge badge-warning-soft">
                                        <i class="mdi mdi-alert"></i> {{ $estadisticasAvanzadas['urgentes'] }} urgentes
                                    </span>
                                @else
                                    <span class="text-success small">
                                        <i class="mdi mdi-check"></i> Sin urgentes
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="circular-progress" data-percentage="{{ $estadisticas['total'] > 0 ? round(($estadisticas['pendientes'] / $estadisticas['total']) * 100, 1) : 0 }}">
                                <svg class="circular-chart" viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke="#ffc107" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-wave">
                    <svg viewBox="0 0 1000 200" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,100 C150,200 350,0 500,100 L500,00 L0,0 Z" style="fill: rgba(255, 193, 7, 0.1);"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-success" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-success">
                                <i class="mdi mdi-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number text-success" data-count="{{ $estadisticas['aprobadas'] }}">0</div>
                            <div class="stat-label">Aprobadas</div>
                            <div class="stat-sublabel">
                                <span class="text-muted small">
                                    {{ $estadisticasAvanzadas['ultimo_mes']['aprobadas'] }} este mes
                                </span>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="circular-progress" data-percentage="{{ $estadisticas['total'] > 0 ? round(($estadisticas['aprobadas'] / $estadisticas['total']) * 100, 1) : 0 }}">
                                <svg class="circular-chart" viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke="#28a745" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-wave">
                    <svg viewBox="0 0 1000 200" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,100 C150,200 350,0 500,100 L500,00 L0,0 Z" style="fill: rgba(40, 167, 69, 0.1);"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-danger" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-danger">
                                <i class="mdi mdi-close-circle text-white"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number text-danger" data-count="{{ $estadisticas['rechazadas'] }}">0</div>
                            <div class="stat-label">Rechazadas</div>
                            <div class="stat-sublabel">
                                <span class="text-muted small">
                                    {{ $estadisticasAvanzadas['ultimo_mes']['rechazadas'] }} este mes
                                </span>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="circular-progress" data-percentage="{{ $estadisticas['total'] > 0 ? round(($estadisticas['rechazadas'] / $estadisticas['total']) * 100, 1) : 0 }}">
                                <svg class="circular-chart" viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke="#dc3545" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-wave">
                    <svg viewBox="0 0 1000 200" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,100 C150,200 350,0 500,100 L500,00 L0,0 Z" style="fill: rgba(220, 53, 69, 0.1);"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info" data-aos="fade-up" data-aos-delay="400">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper">
                            <div class="stat-icon bg-info">
                                <i class="mdi mdi-chart-bar text-white"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number text-info" data-count="{{ $estadisticas['total'] }}">0</div>
                            <div class="stat-label">Total Registradas</div>
                            <div class="stat-sublabel">
                                @php
                                    $cambio = $estadisticasAvanzadas['comparacion_anual']['actual'] - $estadisticasAvanzadas['comparacion_anual']['anterior'];
                                @endphp
                                @if($cambio > 0)
                                    <span class="text-success small">
                                        <i class="mdi mdi-trending-up"></i> +{{ $cambio }} vs año anterior
                                    </span>
                                @elseif($cambio < 0)
                                    <span class="text-danger small">
                                        <i class="mdi mdi-trending-down"></i> {{ $cambio }} vs año anterior
                                    </span>
                                @else
                                    <span class="text-muted small">
                                        <i class="mdi mdi-trending-neutral"></i> Igual que año anterior
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="circular-progress" data-percentage="100">
                                <svg class="circular-chart" viewBox="0 0 36 36">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke="#17a2b8" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-wave">
                    <svg viewBox="0 0 1000 200" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,100 C150,200 350,0 500,100 L500,00 L0,0 Z" style="fill: rgba(23, 162, 184, 0.1);"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Métricas Adicionales de HR -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-muted">Tiempo Promedio Aprobación</h5>
                <div class="display-4 text-primary">
                    {{ number_format($estadisticasAvanzadas['tiempo_promedio_aprobacion'], 1) }}
                </div>
                <p class="text-muted">días</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-muted">Tasa de Aprobación</h5>
                <div class="display-4 text-success">
                    {{ $estadisticas['total'] > 0 ? number_format(($estadisticas['aprobadas'] / $estadisticas['total']) * 100, 1) : 0 }}%
                </div>
                <p class="text-muted">Del total procesado</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-muted">Eficiencia Mensual</h5>
                <div class="display-4 text-info">
                    @php
                        $totalMes = $estadisticasAvanzadas['ultimo_mes']['pendientes'] + $estadisticasAvanzadas['ultimo_mes']['aprobadas'] + $estadisticasAvanzadas['ultimo_mes']['rechazadas'];
                        $procesadasMes = $estadisticasAvanzadas['ultimo_mes']['aprobadas'] + $estadisticasAvanzadas['ultimo_mes']['rechazadas'];
                    @endphp
                    {{ $totalMes > 0 ? number_format(($procesadasMes / $totalMes) * 100, 1) : 0 }}%
                </div>
                <p class="text-muted">Novedades procesadas</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-muted">Atención Requerida</h5>
                <div class="display-4 {{ $estadisticasAvanzadas['urgentes'] > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $estadisticasAvanzadas['urgentes'] }}
                </div>
                <p class="text-muted">Pendientes +7 días</p>
            </div>
        </div>
    </div>
</div>

<!-- Gráficas Principales -->
<div class="row">
    <!-- Gráfica de Estados (Dona) -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Estado de Novedades</h4>
                <p class="card-subtitle text-muted">Distribución actual por estado</p>
                <div class="text-center">
                    <canvas id="chartEstados" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica por Tipo de Novedad (Barras) -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Novedades por Tipo</h4>
                <p class="card-subtitle text-muted">Tipos más frecuentes</p>
                <div class="text-center">
                    <canvas id="chartTipos" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica por Día de la Semana -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Frecuencia Semanal</h4>
                <p class="card-subtitle text-muted">Novedades por día de la semana</p>
                <div class="text-center">
                    <canvas id="chartDiaSemana" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfica de Tendencia Mensual -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tendencia Mensual {{ date('Y') }}</h4>
                <p class="card-subtitle text-muted">Evolución de novedades por estado durante el año</p>
                <div class="text-center">
                    <canvas id="chartMensualEstados" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Empleados -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Top Empleados</h4>
                <p class="card-subtitle text-muted">Empleados con más novedades</p>
                <div class="list-group list-group-flush">
                    @foreach($estadisticasAvanzadas['empleados_mas_novedades'] as $index => $emp)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <i class="mdi mdi-account-circle text-primary mr-2"></i>
                                <span class="font-weight-medium">
                                    {{ $emp->empleado->EMP_NOMBRES ?? 'N/A' }}
                                </span>
                            </div>
                            <span class="badge badge-primary badge-pill">{{ $emp->total }}</span>
                        </div>
                    @endforeach
                    @if($estadisticasAvanzadas['empleados_mas_novedades']->isEmpty())
                        <div class="text-center text-muted py-3">
                            <i class="mdi mdi-information"></i> No hay datos disponibles
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de Acciones Rápidas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Panel de Acciones Rápidas</h4>
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('Novedades.index') }}?estado_aprobacion=pendiente" class="btn btn-outline-warning btn-block">
                            <i class="mdi mdi-clock"></i> Ver Pendientes
                            <span class="badge badge-warning ml-2">{{ $estadisticas['pendientes'] }}</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('Novedades.create') }}" class="btn btn-outline-primary btn-block">
                            <i class="mdi mdi-plus"></i> Nueva Novedad
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button onclick="exportarReporte()" class="btn btn-outline-info btn-block">
                            <i class="mdi mdi-download"></i> Exportar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* ===== ESTILOS MODERNOS PARA DASHBOARD ===== */

/* Tarjetas de estadísticas modernas */
.stat-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stat-card .card {
    border: none;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.stat-icon-wrapper {
    margin-right: 15px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.stat-icon i {
    font-size: 24px;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.stat-sublabel {
    font-size: 0.85rem;
}

.stat-progress {
    width: 50px;
    height: 50px;
}

/* Progreso circular */
.circular-progress {
    position: relative;
    width: 50px;
    height: 50px;
}

.circular-chart {
    display: block;
    margin: 0 auto;
    max-width: 50px;
    max-height: 50px;
}

.circle-bg {
    fill: none;
    stroke: #e9ecef;
    stroke-width: 3.8;
}

.circle {
    fill: none;
    stroke-width: 2.8;
    stroke-linecap: round;
    animation: progress 2s ease-in-out forwards;
    stroke-dasharray: 0 100;
}

@keyframes progress {
    to {
        stroke-dasharray: var(--percentage) 100;
    }
}

/* Ondas decorativas */
.card-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    z-index: 1;
    opacity: 0.3;
}

.card-wave svg {
    width: 100%;
    height: 100%;
}

/* Badge suave */
.badge-warning-soft {
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

/* Métricas adicionales mejoradas */
.row .col-lg-3 .card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border-radius: 15px;
}

.row .col-lg-3 .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

/* Gráficas mejoradas */
.card {
    border-radius: 15px;
}

.card-title {
    font-weight: 600;
    color: #495057;
}

.card-subtitle {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Panel de acciones rápidas */
.btn {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Animaciones de aparición */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Lista de empleados mejorada */
.list-group-item {
    border-radius: 10px !important;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* Breadcrumb mejorado */
.breadcrumb {
    background: none;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .stat-number {
        font-size: 2rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
    }

    .stat-icon i {
        font-size: 20px;
    }

    .stat-progress {
        width: 40px;
        height: 40px;
    }

    .circular-chart {
        max-width: 40px;
        max-height: 40px;
    }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Datos para las gráficas
    const estadisticas = @json($estadisticas);
    const novedadesPorMes = @json($novedadesPorMes);
    const novedadesPorTipo = @json($novedadesPorTipo);
    const estadisticasAvanzadas = @json($estadisticasAvanzadas);

    // Configuración de colores del tema
    const colores = {
        pendiente: '#ffc107',
        aprobada: '#28a745',
        rechazada: '#dc3545',
        primario: '#007bff',
        secundario: '#6c757d',
        info: '#17a2b8'
    };

    // Gráfica de Estados (Dona) - Mejorada
    const ctxEstados = document.getElementById('chartEstados').getContext('2d');
    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Aprobadas', 'Rechazadas'],
            datasets: [{
                data: [estadisticas.pendientes, estadisticas.aprobadas, estadisticas.rechazadas],
                backgroundColor: [colores.pendiente, colores.aprobada, colores.rechazada],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 5,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const porcentaje = ((context.parsed * 100) / total).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + porcentaje + '%)';
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Gráfica por Tipo de Novedad (Barras Horizontales)
    const ctxTipos = document.getElementById('chartTipos').getContext('2d');
    const tiposLabels = novedadesPorTipo.map(item =>
        item.tipo_novedad ? item.tipo_novedad.TIN_NOMBRE : 'Sin tipo'
    );
    const tiposData = novedadesPorTipo.map(item => item.total);

    new Chart(ctxTipos, {
        type: 'bar',
        data: {
            labels: tiposLabels,
            datasets: [{
                label: 'Cantidad',
                data: tiposData,
                backgroundColor: colores.primario,
                borderColor: '#0056b3',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfica por Día de la Semana
    const ctxDiaSemana = document.getElementById('chartDiaSemana').getContext('2d');
    const diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    const dataDiaSemana = Array(7).fill(0);

    estadisticasAvanzadas.por_dia_semana.forEach(item => {
        dataDiaSemana[item.dia - 1] = item.total;
    });

    new Chart(ctxDiaSemana, {
        type: 'radar',
        data: {
            labels: diasSemana,
            datasets: [{
                label: 'Novedades por Día',
                data: dataDiaSemana,
                backgroundColor: 'rgba(23, 162, 184, 0.2)',
                borderColor: colores.info,
                borderWidth: 2,
                pointBackgroundColor: colores.info,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfica Mensual por Estados (Barras Apiladas)
    const ctxMensualEstados = document.getElementById('chartMensualEstados').getContext('2d');
    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    // Procesar datos mensuales por estado
    const dataMensualPendientes = Array(12).fill(0);
    const dataMensualAprobadas = Array(12).fill(0);
    const dataMensualRechazadas = Array(12).fill(0);

    estadisticasAvanzadas.por_mes_estado.forEach(item => {
        const mesIndex = item.mes - 1;
        switch(item.NOV_ESTADO_APROBACION) {
            case 'pendiente':
                dataMensualPendientes[mesIndex] = item.total;
                break;
            case 'aprobada':
                dataMensualAprobadas[mesIndex] = item.total;
                break;
            case 'rechazada':
                dataMensualRechazadas[mesIndex] = item.total;
                break;
        }
    });

    new Chart(ctxMensualEstados, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Pendientes',
                data: dataMensualPendientes,
                backgroundColor: colores.pendiente,
                borderColor: '#e0a800',
                borderWidth: 1
            }, {
                label: 'Aprobadas',
                data: dataMensualAprobadas,
                backgroundColor: colores.aprobada,
                borderColor: '#1e7e34',
                borderWidth: 1
            }, {
                label: 'Rechazadas',
                data: dataMensualRechazadas,
                backgroundColor: colores.rechazada,
                borderColor: '#bd2130',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Función para exportar reportes
    function exportarReporte() {
        const fechaInicio = prompt('Fecha de inicio (YYYY-MM-DD):');
        const fechaFin = prompt('Fecha de fin (YYYY-MM-DD):');

        if (fechaInicio && fechaFin) {
            const url = `{{ route('Novedades.exportar') }}?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
            window.open(url, '_blank');
        }
    }

    // Inicializar AOS y animaciones mejoradas
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Animación de contadores principales
        const contadores = document.querySelectorAll('.stat-number[data-count]');

        const observerOptions = {
            threshold: 0.5,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        contadores.forEach(contador => {
            observer.observe(contador);
        });

        function animateCounter(element) {
            const target = parseInt(element.dataset.count);
            const duration = 2000; // 2 segundos
            const startTime = performance.now();
            const startValue = 0;

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function (ease-out-quart)
                const easedProgress = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.round(startValue + (target - startValue) * easedProgress);

                element.textContent = currentValue.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                }
            }

            requestAnimationFrame(updateCounter);
        }

        // Animación de progreso circular
        setTimeout(() => {
            document.querySelectorAll('.circular-progress').forEach(progress => {
                const percentage = progress.dataset.percentage;
                const circle = progress.querySelector('.circle');
                if (circle) {
                    circle.style.setProperty('--percentage', percentage);
                }
            });
        }, 500);

        // Animación de contadores en métricas adicionales
        const contadoresAdicionales = document.querySelectorAll('.display-4');
        contadoresAdicionales.forEach(contador => {
            const observer2 = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const text = contador.textContent;
                        const isPercentage = text.includes('%');
                        const isDays = text.includes('día') || contador.parentElement.textContent.includes('días');

                        let finalValue = parseFloat(text.replace(/[^\d.]/g, ''));

                        if (isNaN(finalValue)) return;

                        let currentValue = 0;
                        const duration = 1500;
                        const startTime = performance.now();

                        function updateValue(currentTime) {
                            const elapsed = currentTime - startTime;
                            const progress = Math.min(elapsed / duration, 1);
                            const easedProgress = 1 - Math.pow(1 - progress, 3);

                            currentValue = finalValue * easedProgress;

                            let displayValue = isDays ? currentValue.toFixed(1) : Math.round(currentValue);

                            if (isPercentage) {
                                contador.textContent = displayValue + '%';
                            } else if (isDays) {
                                contador.textContent = displayValue;
                            } else {
                                contador.textContent = displayValue;
                            }

                            if (progress < 1) {
                                requestAnimationFrame(updateValue);
                            }
                        }

                        requestAnimationFrame(updateValue);
                        observer2.unobserve(contador);
                    }
                });
            }, observerOptions);

            observer2.observe(contador);
        });

        // Animación de hover mejorada para las tarjetas
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Efecto de pulsación para botones importantes
        document.querySelectorAll('.btn-warning, .btn-danger').forEach(btn => {
            if (btn.textContent.includes('urgentes') || btn.textContent.includes('Gestión')) {
                btn.classList.add('pulse-animation');
            }
        });
    });

    // Funciones auxiliares mejoradas
    function exportarReporte() {
        // Crear y mostrar modal personalizado en lugar de prompt básico
        const modalHTML = `
            <div class="modal fade" id="exportModalCustom" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-download me-2"></i>Exportar Reporte
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Fecha de inicio:</label>
                                <input type="date" class="form-control" id="exportFechaInicio" max="${new Date().toISOString().split('T')[0]}">
                            </div>
                            <div class="form-group">
                                <label>Fecha de fin:</label>
                                <input type="date" class="form-control" id="exportFechaFin" max="${new Date().toISOString().split('T')[0]}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="ejecutarExportacion()">
                                <i class="mdi mdi-download me-1"></i>Descargar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Agregar modal al DOM si no existe
        if (!document.getElementById('exportModalCustom')) {
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        $('#exportModalCustom').modal('show');
    }

    function ejecutarExportacion() {
        const fechaInicio = document.getElementById('exportFechaInicio').value;
        const fechaFin = document.getElementById('exportFechaFin').value;

        if (fechaInicio && fechaFin) {
            if (fechaFin < fechaInicio) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('La fecha de fin no puede ser anterior a la fecha de inicio');
                }
                return;
            }

            const url = `{{ route('Novedades.exportar') }}?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
            window.open(url, '_blank');
            $('#exportModalCustom').modal('hide');

            if (typeof toastr !== 'undefined') {
                toastr.success('Descarga iniciada correctamente');
            }
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Por favor, seleccione ambas fechas');
            }
        }
    }
</script>
@endsection
