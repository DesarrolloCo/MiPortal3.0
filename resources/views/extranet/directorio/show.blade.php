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
                    <li class="breadcrumb-item"><a href="{{ route('extranet.directorio.index') }}">Directorio</a></li>
                    <li class="breadcrumb-item active">{{ $empleado->nombre_completo }}</li>
                </ol>
            </nav>

            <!-- Perfil del Empleado -->
            <div class="row">
                <!-- Columna Izquierda: Info Personal -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <!-- Foto -->
                            <div class="mb-3">
                                @if($empleado->EMP_FOTO_URL)
                                <img src="{{ $empleado->EMP_FOTO_URL }}" alt="{{ $empleado->nombre_completo }}"
                                     class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px;">
                                    <i class="mdi mdi-account mdi-72px text-white"></i>
                                </div>
                                @endif
                            </div>

                            <!-- Nombre -->
                            <h4 class="mb-1">{{ $empleado->nombre_completo }}</h4>

                            <!-- Estado -->
                            @if($empleado->EMP_ACTIVO)
                            <span class="badge badge-success mb-3">Activo</span>
                            @else
                            <span class="badge badge-secondary mb-3">Inactivo</span>
                            @endif

                            <hr>

                            <!-- Información de Contacto -->
                            <div class="text-left">
                                <h5 class="mb-3"><i class="mdi mdi-card-account-details"></i> Información de Contacto</h5>

                                <p class="mb-2">
                                    <i class="mdi mdi-card-account-details text-primary"></i>
                                    <strong>Cédula:</strong><br>
                                    {{ $empleado->EMP_CEDULA }}
                                </p>

                                @if($empleado->EMP_EMAIL)
                                <p class="mb-2">
                                    <i class="mdi mdi-email text-primary"></i>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:{{ $empleado->EMP_EMAIL }}">{{ $empleado->EMP_EMAIL }}</a>
                                </p>
                                @endif

                                @if($empleado->EMP_TELEFONO)
                                <p class="mb-2">
                                    <i class="mdi mdi-phone text-primary"></i>
                                    <strong>Teléfono:</strong><br>
                                    <a href="tel:{{ $empleado->EMP_TELEFONO }}">{{ $empleado->EMP_TELEFONO }}</a>
                                </p>
                                @endif

                                @if($empleado->EMP_DIRECCION)
                                <p class="mb-2">
                                    <i class="mdi mdi-map-marker text-primary"></i>
                                    <strong>Dirección:</strong><br>
                                    {{ $empleado->EMP_DIRECCION }}
                                </p>
                                @endif

                                @if($empleado->EMP_FECHA_NACIMIENTO)
                                <p class="mb-2">
                                    <i class="mdi mdi-cake-variant text-primary"></i>
                                    <strong>Fecha de Nacimiento:</strong><br>
                                    {{ \Carbon\Carbon::parse($empleado->EMP_FECHA_NACIMIENTO)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    <br><small class="text-muted">({{ \Carbon\Carbon::parse($empleado->EMP_FECHA_NACIMIENTO)->age }} años)</small>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Info Laboral -->
                <div class="col-md-8">
                    <!-- Información Laboral -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3"><i class="mdi mdi-briefcase"></i> Información Laboral</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    @if($empleado->cargo)
                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-briefcase text-primary"></i> Cargo:</strong><br>
                                        {{ $empleado->cargo->CAR_DESCRIPCION }}
                                    </p>
                                    @endif

                                    @if($empleado->departamento)
                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-office-building text-primary"></i> Departamento:</strong><br>
                                        {{ $empleado->departamento->DEP_DESCRIPCION }}
                                    </p>
                                    @endif

                                    @if($empleado->campana)
                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-bullseye text-primary"></i> Campaña:</strong><br>
                                        {{ $empleado->campana->CAM_NOMBRE }}
                                    </p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    @php
                                    $contratoActivo = $empleado->contratos->where('EMC_ESTADO', 1)->first();
                                    @endphp

                                    @if($contratoActivo)
                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-calendar-check text-primary"></i> Fecha de Ingreso:</strong><br>
                                        {{ \Carbon\Carbon::parse($contratoActivo->EMC_FECHA_INICIO)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    </p>

                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-clock-outline text-primary"></i> Antigüedad:</strong><br>
                                        {{ \Carbon\Carbon::parse($contratoActivo->EMC_FECHA_INICIO)->diffForHumans(null, true) }}
                                    </p>

                                    <p class="mb-2">
                                        <strong><i class="mdi mdi-file-document text-primary"></i> Tipo de Contrato:</strong><br>
                                        @if($contratoActivo->EMC_TIPO == 1) Indefinido
                                        @elseif($contratoActivo->EMC_TIPO == 2) Temporal
                                        @elseif($contratoActivo->EMC_TIPO == 3) Por Obra
                                        @else Otro
                                        @endif
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reconocimientos -->
                    @if($reconocimientos->count() > 0)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3"><i class="mdi mdi-trophy-award"></i> Reconocimientos</h5>

                            <div class="row">
                                @foreach($reconocimientos as $reconocimiento)
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3">
                                        @if($reconocimiento->imagen_url)
                                        <img src="{{ $reconocimiento->imagen_url }}" alt="{{ $reconocimiento->titulo }}"
                                             class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        @endif

                                        <h6 class="mb-1">
                                            <span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $reconocimiento->tipo)) }}</span>
                                        </h6>
                                        <h6>{{ $reconocimiento->titulo }}</h6>
                                        <p class="small mb-1">{{ $reconocimiento->descripcion }}</p>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($reconocimiento->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($reconocimientos->count() > 4)
                            <div class="text-center mt-3">
                                <a href="{{ route('extranet.reconocimientos.index', ['empleado' => $empleado->EMP_ID]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Ver todos los reconocimientos
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Proyectos Asignados -->
                    @if($proyectos->count() > 0)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3"><i class="mdi mdi-clipboard-text"></i> Proyectos Asignados</h5>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Proyecto</th>
                                            <th>Estado</th>
                                            <th>Progreso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proyectos as $proyecto)
                                        <tr>
                                            <td>
                                                <strong>{{ $proyecto->nombre }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($proyecto->descripcion, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{
                                                    $proyecto->estado == 'completado' ? 'success' :
                                                    ($proyecto->estado == 'en_progreso' ? 'primary' :
                                                    ($proyecto->estado == 'pausado' ? 'warning' : 'secondary'))
                                                }}">
                                                    {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" style="width: {{ $proyecto->progreso }}%">
                                                        {{ $proyecto->progreso }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('extranet.proyectos.show', $proyecto->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="card">
                        <div class="card-body text-center">
                            <a href="{{ route('extranet.directorio.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Volver al Directorio
                            </a>

                            @if($empleado->EMP_EMAIL)
                            <a href="mailto:{{ $empleado->EMP_EMAIL }}" class="btn btn-primary">
                                <i class="mdi mdi-email"></i> Enviar Email
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
