@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0"><i class="mdi mdi-image-multiple"></i> Galería de Fotos</h4>
                            <h6 class="card-subtitle">Álbumes de eventos y actividades corporativas</h6>
                        </div>
                        <div>
                            @can('crear-album')
                            <a href="{{ route('extranet.galeria.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Crear Álbum
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($galerias->count() > 0)
    <div class="row mt-4">
        @foreach($galerias as $galeria)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 hover-shadow">
                <!-- Portada del álbum -->
                <div class="position-relative" style="height: 250px; overflow: hidden;">
                    @if($galeria->portada_url)
                    <img src="{{ $galeria->portada_url }}" alt="{{ $galeria->titulo }}"
                         class="card-img-top" style="height: 100%; object-fit: cover;">
                    @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                        <i class="mdi mdi-image-off mdi-72px text-white"></i>
                    </div>
                    @endif

                    <!-- Badge de cantidad de fotos -->
                    <div class="position-absolute" style="bottom: 10px; right: 10px;">
                        <span class="badge badge-dark badge-pill">
                            <i class="mdi mdi-image"></i> {{ $galeria->total_fotos }} fotos
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Título -->
                    <h5 class="card-title">{{ $galeria->titulo }}</h5>

                    <!-- Descripción -->
                    @if($galeria->descripcion)
                    <p class="card-text text-muted">
                        {{ Str::limit($galeria->descripcion, 100) }}
                    </p>
                    @endif

                    <!-- Metadata -->
                    <div class="small text-muted mb-3">
                        <div>
                            <i class="mdi mdi-calendar"></i>
                            {{ \Carbon\Carbon::parse($galeria->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                        </div>
                        @if($galeria->evento)
                        <div>
                            <i class="mdi mdi-calendar-star"></i>
                            Evento: {{ $galeria->evento->titulo }}
                        </div>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('extranet.galeria.show', $galeria->id) }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-eye"></i> Ver Álbum
                        </a>

                        @canany(['editar-album', 'eliminar-album'])
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('subir-fotos')
                                <a class="dropdown-item" href="{{ route('extranet.galeria.upload-fotos', $galeria->id) }}">
                                    <i class="mdi mdi-upload"></i> Subir Fotos
                                </a>
                                @endcan

                                @can('editar-album')
                                <a class="dropdown-item" href="{{ route('extranet.galeria.edit', $galeria->id) }}">
                                    <i class="mdi mdi-pencil"></i> Editar
                                </a>
                                @endcan

                                @can('eliminar-album')
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('extranet.galeria.destroy', $galeria->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"
                                            onclick="return confirm('¿Eliminar este álbum y todas sus fotos?')">
                                        <i class="mdi mdi-delete"></i> Eliminar
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        @endcanany
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="row">
        <div class="col-12">
            {{ $galerias->links() }}
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-image-album mdi-72px text-muted"></i>
                    <h5 class="mt-3">No hay álbumes de fotos</h5>
                    <p class="text-muted">Crea el primer álbum para comenzar</p>
                    @can('crear-album')
                    <a href="{{ route('extranet.galeria.create') }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-plus"></i> Crear Primer Álbum
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endif
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
