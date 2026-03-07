@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            @include('extranet.partials.flash-messages')

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.reconocimientos.index') }}">Reconocimientos</a></li>
                    <li class="breadcrumb-item active">{{ $reconocimiento->titulo }}</li>
                </ol>
            </nav>

            <!-- Reconocimiento -->
            <div class="card {{ $reconocimiento->destacado ? 'border-warning' : '' }}">
                @if($reconocimiento->imagen_url)
                <img class="card-img-top" src="{{ $reconocimiento->imagen_url }}" alt="{{ $reconocimiento->titulo }}" style="max-height: 500px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-3">
                        @if($reconocimiento->destacado)
                        <span class="badge badge-warning badge-lg"><i class="mdi mdi-star"></i> Destacado</span>
                        @endif
                        <span class="badge badge-{{ $reconocimiento->tipo == 'empleado_mes' ? 'success' : 'primary' }} badge-lg">
                            {{ ucfirst(str_replace('_', ' ', $reconocimiento->tipo)) }}
                        </span>
                    </div>

                    <!-- Título -->
                    <h2 class="card-title text-center mb-4">
                        <i class="mdi mdi-trophy-award text-warning"></i> {{ $reconocimiento->titulo }}
                    </h2>

                    <!-- Empleado reconocido -->
                    <div class="text-center mb-4">
                        @if($reconocimiento->empleado->EMP_FOTO_URL)
                        <img src="{{ $reconocimiento->empleado->EMP_FOTO_URL }}" alt="{{ $reconocimiento->empleado->EMP_NOMBRES }}"
                             class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #ffc107;">
                        @else
                        <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px; font-size: 60px; border: 5px solid #ffc107;">
                            {{ substr($reconocimiento->empleado->EMP_NOMBRES, 0, 1) }}
                        </div>
                        @endif

                        <h3 class="mb-1">{{ $reconocimiento->empleado->EMP_NOMBRES }} {{ $reconocimiento->empleado->EMP_APELLIDOS }}</h3>
                        <p class="text-muted mb-0">{{ $reconocimiento->empleado->cargo->CAR_NOMBRE ?? '' }}</p>
                        @if($reconocimiento->empleado->campana)
                        <p class="text-muted">{{ $reconocimiento->empleado->campana->CAM_NOMBRE ?? '' }}</p>
                        @endif
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p style="font-size: 1.1rem; line-height: 1.8;">
                            {{ $reconocimiento->descripcion }}
                        </p>
                    </div>

                    <!-- Metadata -->
                    <hr>
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <i class="mdi mdi-calendar text-primary mdi-24px"></i>
                            <p class="mb-0 mt-2"><strong>Fecha</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($reconocimiento->fecha)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <i class="mdi mdi-account text-primary mdi-24px"></i>
                            <p class="mb-0 mt-2"><strong>Otorgado por</strong></p>
                            <p class="text-muted">{{ $reconocimiento->otorgadoPor->name ?? 'Sistema' }}</p>
                        </div>
                        <div class="col-md-4">
                            <i class="mdi mdi-{{ $reconocimiento->publico ? 'earth' : 'lock' }} text-primary mdi-24px"></i>
                            <p class="mb-0 mt-2"><strong>Visibilidad</strong></p>
                            <p class="text-muted">{{ $reconocimiento->publico ? 'Público' : 'Privado' }}</p>
                        </div>
                    </div>

                    <!-- Compartir en redes (opcional) -->
                    @if($reconocimiento->publico && $reconocimiento->destacado)
                    <div class="text-center mb-4">
                        <p class="text-muted mb-2">Comparte este logro:</p>
                        <button class="btn btn-primary btn-sm" onclick="compartirReconocimiento()">
                            <i class="mdi mdi-share-variant"></i> Compartir
                        </button>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>

                        <div>
                            @can('editar-reconocimiento')
                            <a href="{{ route('extranet.reconocimientos.edit', $reconocimiento->id) }}" class="btn btn-info">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            @endcan

                            @can('eliminar-reconocimiento')
                            <form action="{{ route('extranet.reconocimientos.destroy', $reconocimiento->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este reconocimiento permanentemente?')">
                                    <i class="mdi mdi-delete"></i> Eliminar
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Otros reconocimientos del empleado -->
            @if($reconocimiento->empleado->reconocimientos->where('id', '!=', $reconocimiento->id)->count() > 0)
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Otros reconocimientos de {{ explode(' ', $reconocimiento->empleado->EMP_NOMBRES)[0] }}</h5>
                    <div class="list-group">
                        @foreach($reconocimiento->empleado->reconocimientos->where('id', '!=', $reconocimiento->id)->take(5) as $otro)
                        <a href="{{ route('extranet.reconocimientos.show', $otro->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="mdi mdi-trophy-award text-warning"></i> {{ $otro->titulo }}
                                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($otro->fecha)->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $otro->tipo)) }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function compartirReconocimiento() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $reconocimiento->titulo }}',
            text: 'Reconocimiento para {{ $reconocimiento->empleado->EMP_NOMBRES }}',
            url: window.location.href
        });
    } else {
        // Fallback: copiar al portapapeles
        navigator.clipboard.writeText(window.location.href);
        alert('Enlace copiado al portapapeles');
    }
}
</script>
@endsection
