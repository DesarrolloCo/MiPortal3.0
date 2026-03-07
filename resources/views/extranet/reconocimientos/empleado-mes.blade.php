@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            @include('extranet.partials.flash-messages')

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.reconocimientos.index') }}">Reconocimientos</a></li>
                    <li class="breadcrumb-item active">Empleado del Mes</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="display-4">
                    <i class="mdi mdi-star text-warning"></i>
                    Empleado del Mes
                </h1>
                <p class="lead text-muted">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
            </div>

            @if($empleadoDelMes)
            <!-- Empleado del Mes Actual -->
            <div class="card border-warning shadow-lg mb-5">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <!-- Foto del empleado -->
                        <div class="col-md-4 text-center">
                            <div class="position-relative d-inline-block">
                                @if($empleadoDelMes->empleado->EMP_FOTO_URL)
                                <img src="{{ $empleadoDelMes->empleado->EMP_FOTO_URL }}"
                                     alt="{{ $empleadoDelMes->empleado->EMP_NOMBRES }}"
                                     class="rounded-circle border border-warning shadow"
                                     style="width: 200px; height: 200px; object-fit: cover; border-width: 5px !important;">
                                @else
                                <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center border border-warning shadow"
                                     style="width: 200px; height: 200px; font-size: 80px; border-width: 5px !important;">
                                    {{ substr($empleadoDelMes->empleado->EMP_NOMBRES, 0, 1) }}{{ substr($empleadoDelMes->empleado->EMP_APELLIDOS, 0, 1) }}
                                </div>
                                @endif

                                <!-- Insignia de estrella -->
                                <div class="position-absolute" style="bottom: 10px; right: 10px;">
                                    <div class="bg-warning rounded-circle p-2 shadow">
                                        <i class="mdi mdi-star text-white" style="font-size: 40px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del empleado -->
                        <div class="col-md-8">
                            <h2 class="mb-3">
                                {{ $empleadoDelMes->empleado->EMP_NOMBRES }} {{ $empleadoDelMes->empleado->EMP_APELLIDOS }}
                            </h2>

                            @if($empleadoDelMes->empleado->cargo)
                            <h5 class="text-muted mb-3">
                                <i class="mdi mdi-briefcase"></i>
                                {{ $empleadoDelMes->empleado->cargo->CAR_NOMBRE }}
                            </h5>
                            @endif

                            @if($empleadoDelMes->titulo)
                            <h4 class="text-primary mb-3">
                                <i class="mdi mdi-trophy"></i>
                                {{ $empleadoDelMes->titulo }}
                            </h4>
                            @endif

                            @if($empleadoDelMes->descripcion)
                            <div class="alert alert-light border-warning">
                                <p class="mb-0" style="font-size: 16px; line-height: 1.6;">
                                    {!! nl2br(e($empleadoDelMes->descripcion)) !!}
                                </p>
                            </div>
                            @endif

                            <div class="mt-4">
                                <small class="text-muted">
                                    <i class="mdi mdi-calendar"></i>
                                    Reconocido el {{ \Carbon\Carbon::parse($empleadoDelMes->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    @if($empleadoDelMes->imagen_url)
                    <div class="row mt-4">
                        <div class="col-12">
                            <img src="{{ $empleadoDelMes->imagen_url }}"
                                 alt="{{ $empleadoDelMes->titulo }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <!-- No hay empleado del mes -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-star-off mdi-72px text-muted mb-3"></i>
                    <h4>No hay Empleado del Mes designado</h4>
                    <p class="text-muted">Aún no se ha seleccionado un empleado del mes para {{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY') }}</p>

                    @can('crear-reconocimiento')
                    <a href="{{ route('extranet.reconocimientos.create') }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-plus"></i> Crear Reconocimiento
                    </a>
                    @endcan
                </div>
            </div>
            @endif

            <!-- Histórico de Empleados del Mes -->
            @if($historico->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="mdi mdi-history"></i> Empleados del Mes Anteriores
                    </h4>

                    <div class="row">
                        @foreach($historico as $reconocimiento)
                        @if($empleadoDelMes && $reconocimiento->id == $empleadoDelMes->id)
                            @continue
                        @endif

                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-body text-center">
                                    <!-- Foto -->
                                    @if($reconocimiento->empleado->EMP_FOTO_URL)
                                    <img src="{{ $reconocimiento->empleado->EMP_FOTO_URL }}"
                                         alt="{{ $reconocimiento->empleado->EMP_NOMBRES }}"
                                         class="rounded-circle mb-3"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 100px; height: 100px; font-size: 40px;">
                                        {{ substr($reconocimiento->empleado->EMP_NOMBRES, 0, 1) }}{{ substr($reconocimiento->empleado->EMP_APELLIDOS, 0, 1) }}
                                    </div>
                                    @endif

                                    <!-- Nombre -->
                                    <h6 class="mb-1">
                                        {{ $reconocimiento->empleado->EMP_NOMBRES }} {{ $reconocimiento->empleado->EMP_APELLIDOS }}
                                    </h6>

                                    <!-- Cargo -->
                                    @if($reconocimiento->empleado->cargo)
                                    <small class="text-muted d-block mb-2">
                                        {{ $reconocimiento->empleado->cargo->CAR_NOMBRE }}
                                    </small>
                                    @endif

                                    <!-- Fecha -->
                                    <div class="badge badge-info badge-pill">
                                        {{ \Carbon\Carbon::parse($reconocimiento->fecha)->locale('es')->isoFormat('MMMM YYYY') }}
                                    </div>

                                    <!-- Ver detalles -->
                                    <div class="mt-3">
                                        <a href="{{ route('extranet.reconocimientos.show', $reconocimiento->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="text-center mt-4">
                <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Volver a Reconocimientos
                </a>

                @can('crear-reconocimiento')
                <a href="{{ route('extranet.reconocimientos.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Nuevo Reconocimiento
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}
</style>
@endsection
