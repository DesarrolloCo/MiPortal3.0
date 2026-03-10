@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.encuestas.index') }}">Encuestas</a></li>
                    <li class="breadcrumb-item active">Resultados: {{ $encuesta->titulo }}</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3><i class="mdi mdi-chart-bar"></i> Resultados de la Encuesta</h3>
                            <h5 class="text-muted">{{ $encuesta->titulo }}</h5>
                            @if($encuesta->descripcion)
                            <p>{{ $encuesta->descripcion }}</p>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('extranet.encuestas.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Volver
                            </a>
                            @can('crear-encuesta')
                            <button class="btn btn-success" onclick="window.print()">
                                <i class="mdi mdi-printer"></i> Imprimir
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ $encuesta->total_respuestas }}</h2>
                            <small>Total Respuestas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            @php
                            $empleadosActivos = \App\Models\empleado::where('EMP_ACTIVO', 1)->count();
                            $participacion = $empleadosActivos > 0 ? round(($encuesta->total_respuestas / $empleadosActivos) * 100, 1) : 0;
                            @endphp
                            <h2 class="mb-0">{{ $participacion }}%</h2>
                            <small>Tasa de Participación</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ $encuesta->preguntas->count() }}</h2>
                            <small>Total Preguntas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0">
                                @if($encuesta->anonima)
                                <i class="mdi mdi-incognito"></i> Sí
                                @else
                                No
                                @endif
                            </h2>
                            <small>Anónima</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados por Pregunta -->
            @foreach($encuesta->preguntas->sortBy('orden') as $index => $pregunta)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $index + 1 }}. {{ $pregunta->pregunta }}</h5>
                    <small class="text-muted">
                        Tipo: {{ ucfirst(str_replace('_', ' ', $pregunta->tipo_respuesta)) }}
                        | Respuestas: {{ $pregunta->respuestas->count() }}
                    </small>
                </div>
                <div class="card-body">
                    @if(in_array($pregunta->tipo_respuesta, ['opcion_multiple', 'checkbox']))
                        <!-- Gráfico de Barras/Torta para Opciones Múltiples -->
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="chart-{{ $pregunta->id }}" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="col-md-4">
                                <h6>Resumen</h6>
                                @php
                                $opciones = json_decode($pregunta->opciones, true) ?? [];
                                $respuestas = $pregunta->respuestas->pluck('respuesta');
                                $contadores = [];
                                foreach($opciones as $opcion) {
                                    $contadores[$opcion] = 0;
                                }
                                foreach($respuestas as $respuesta) {
                                    if($pregunta->tipo_respuesta == 'checkbox') {
                                        $valores = json_decode($respuesta, true) ?? [$respuesta];
                                        foreach($valores as $valor) {
                                            if(isset($contadores[$valor])) {
                                                $contadores[$valor]++;
                                            }
                                        }
                                    } else {
                                        if(isset($contadores[$respuesta])) {
                                            $contadores[$respuesta]++;
                                        }
                                    }
                                }
                                @endphp
                                <ul class="list-unstyled">
                                    @foreach($contadores as $opcion => $count)
                                    <li class="mb-2">
                                        <strong>{{ $opcion }}:</strong> {{ $count }}
                                        ({{ $pregunta->respuestas->count() > 0 ? round(($count / $pregunta->respuestas->count()) * 100, 1) : 0 }}%)
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('chart-{{ $pregunta->id }}').getContext('2d');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: {!! json_encode(array_keys($contadores)) !!},
                                    datasets: [{
                                        label: 'Respuestas',
                                        data: {!! json_encode(array_values($contadores)) !!},
                                        backgroundColor: [
                                            'rgba(54, 162, 235, 0.8)',
                                            'rgba(255, 99, 132, 0.8)',
                                            'rgba(255, 206, 86, 0.8)',
                                            'rgba(75, 192, 192, 0.8)',
                                            'rgba(153, 102, 255, 0.8)',
                                            'rgba(255, 159, 64, 0.8)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        });
                        </script>

                    @elseif($pregunta->tipo_respuesta == 'escala')
                        <!-- Gráfico de Barras para Escala -->
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="chart-{{ $pregunta->id }}" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="col-md-4">
                                @php
                                $escalaContadores = [];
                                for($i = $pregunta->escala_min; $i <= $pregunta->escala_max; $i++) {
                                    $escalaContadores[$i] = 0;
                                }
                                foreach($pregunta->respuestas as $respuesta) {
                                    $valor = (int)$respuesta->respuesta;
                                    if(isset($escalaContadores[$valor])) {
                                        $escalaContadores[$valor]++;
                                    }
                                }
                                $promedio = $pregunta->respuestas->avg('respuesta');
                                @endphp
                                <h6>Estadísticas</h6>
                                <p><strong>Promedio:</strong> {{ number_format($promedio, 2) }}</p>
                                <p><strong>Total respuestas:</strong> {{ $pregunta->respuestas->count() }}</p>
                                <h6 class="mt-3">Distribución</h6>
                                <ul class="list-unstyled">
                                    @foreach($escalaContadores as $valor => $count)
                                    <li>{{ $valor }}: {{ $count }} ({{ $pregunta->respuestas->count() > 0 ? round(($count / $pregunta->respuestas->count()) * 100, 1) : 0 }}%)</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('chart-{{ $pregunta->id }}').getContext('2d');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: {!! json_encode(array_keys($escalaContadores)) !!},
                                    datasets: [{
                                        label: 'Frecuencia',
                                        data: {!! json_encode(array_values($escalaContadores)) !!},
                                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        });
                        </script>

                    @else
                        <!-- Respuestas de Texto -->
                        <div style="max-height: 400px; overflow-y: auto;">
                            @foreach($pregunta->respuestas->take(50) as $respuesta)
                            <div class="border-bottom pb-2 mb-2">
                                <p class="mb-0">{{ $respuesta->respuesta }}</p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($respuesta->created_at)->locale('es')->diffForHumans() }}
                                </small>
                            </div>
                            @endforeach
                            @if($pregunta->respuestas->count() > 50)
                            <p class="text-muted text-center">Y {{ $pregunta->respuestas->count() - 50 }} respuestas más...</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<style>
@media print {
    .btn, nav, .breadcrumb {
        display: none;
    }
}
</style>
@endsection
