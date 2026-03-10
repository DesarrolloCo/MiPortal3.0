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
                    <li class="breadcrumb-item"><a href="{{ route('extranet.galeria.index') }}">Galería</a></li>
                    <li class="breadcrumb-item active">{{ $galeria->titulo }}</li>
                </ol>
            </nav>

            <!-- Header del Álbum -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3><i class="mdi mdi-image-album"></i> {{ $galeria->titulo }}</h3>
                            @if($galeria->descripcion)
                            <p class="text-muted">{{ $galeria->descripcion }}</p>
                            @endif
                            <div class="text-muted">
                                <i class="mdi mdi-calendar"></i>
                                {{ \Carbon\Carbon::parse($galeria->fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                <span class="ml-3">
                                    <i class="mdi mdi-image"></i> {{ $galeria->fotos->count() }} fotos
                                </span>
                                @if($galeria->evento)
                                <span class="ml-3">
                                    <i class="mdi mdi-calendar-star"></i>
                                    <a href="{{ route('extranet.eventos.show', $galeria->evento->id) }}">
                                        {{ $galeria->evento->titulo }}
                                    </a>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('extranet.galeria.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Volver
                            </a>
                            @can('subir-fotos')
                            <a href="{{ route('extranet.galeria.upload-fotos', $galeria->id) }}" class="btn btn-primary">
                                <i class="mdi mdi-upload"></i> Subir Fotos
                            </a>
                            @endcan
                            @can('editar-album')
                            <a href="{{ route('extranet.galeria.edit', $galeria->id) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            @if($galeria->fotos->count() > 0)
            <!-- Grid de Fotos -->
            <div class="row">
                @foreach($galeria->fotos->sortBy('orden') as $foto)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 foto-card">
                        <a href="{{ $foto->archivo_url }}" data-lightbox="galeria-{{ $galeria->id }}"
                           data-title="{{ $foto->descripcion }}">
                            <div class="position-relative">
                                <img src="{{ $foto->archivo_url }}" alt="{{ $foto->descripcion }}"
                                     class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="photo-overlay">
                                    <i class="mdi mdi-magnify-plus mdi-36px text-white"></i>
                                </div>
                            </div>
                        </a>

                        @if($foto->descripcion || $foto->likes > 0 || auth()->user()->can('eliminar-fotos'))
                        <div class="card-body p-2">
                            @if($foto->descripcion)
                            <p class="small mb-1">{{ Str::limit($foto->descripcion, 50) }}</p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Likes -->
                                <button type="button" class="btn btn-sm btn-link p-0" onclick="likeFoto({{ $foto->id }})">
                                    <i class="mdi mdi-heart{{ $foto->userHasLiked ? '' : '-outline' }} text-danger"></i>
                                    <span class="like-count-{{ $foto->id }}">{{ $foto->likes }}</span>
                                </button>

                                <!-- Eliminar -->
                                @can('eliminar-fotos')
                                <form action="{{ route('extranet.galeria.eliminar-foto', $foto->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                            onclick="return confirm('¿Eliminar esta foto?')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Sin Fotos -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-image-off mdi-72px text-muted"></i>
                    <h5 class="mt-3">Este álbum aún no tiene fotos</h5>
                    @can('subir-fotos')
                    <a href="{{ route('extranet.galeria.upload-fotos', $galeria->id) }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-upload"></i> Subir Primeras Fotos
                    </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Lightbox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
// Configuración de Lightbox
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': 'Foto %1 de %2'
});

// Like a foto (AJAX)
function likeFoto(fotoId) {
    fetch(`/extranet/galeria/fotos/${fotoId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`.like-count-${fotoId}`).textContent = data.likes;
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<style>
.foto-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.foto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.foto-card:hover .photo-overlay {
    opacity: 1;
}
</style>
@endsection
