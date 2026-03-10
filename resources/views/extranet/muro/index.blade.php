@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="mdi mdi-newspaper"></i> Muro Social</h3>
            </div>

            <!-- Feed de Publicaciones -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">

                    @forelse($publicaciones as $publicacion)
                    <!-- Card de Publicación -->
                    <div class="card mb-3 publicacion-card" data-id="{{ $publicacion->id }}">
                        <div class="card-body">
                            <!-- Header de Publicación -->
                            <div class="d-flex align-items-start mb-3">
                                <!-- Icono según tipo -->
                                <div class="mr-3">
                                    @if($publicacion->tipo == 'comunicado')
                                    <div class="bg-primary text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-bullhorn mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'proyecto')
                                    <div class="bg-success text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-clipboard-text mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'evento')
                                    <div class="bg-warning text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-calendar-star mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'reconocimiento')
                                    <div class="bg-warning text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-trophy-award mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'cumpleanos')
                                    <div class="bg-pink text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-cake-variant mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'aniversario')
                                    <div class="bg-purple text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-party-popper mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'nuevo_empleado')
                                    <div class="bg-info text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-account-plus mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'encuesta')
                                    <div class="bg-cyan text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-poll-box mdi-24px"></i>
                                    </div>
                                    @elseif($publicacion->tipo == 'documento')
                                    <div class="bg-secondary text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-file-document mdi-24px"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Info de Publicación -->
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        @if($publicacion->destacado)
                                        <i class="mdi mdi-pin text-warning"></i>
                                        @endif
                                        {{ $publicacion->titulo }}
                                    </h5>
                                    <div class="text-muted small">
                                        <span class="badge badge-{{ $publicacion->tipo == 'comunicado' ? 'primary' : ($publicacion->tipo == 'evento' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $publicacion->tipo)) }}
                                        </span>
                                        @if($publicacion->autor)
                                        • {{ $publicacion->autor->name }}
                                        @endif
                                        • {{ \Carbon\Carbon::parse($publicacion->created_at)->diffForHumans() }}
                                        • <i class="mdi mdi-eye"></i> {{ $publicacion->vistas }} vistas
                                    </div>
                                </div>
                            </div>

                            <!-- Contenido -->
                            @if($publicacion->contenido)
                            <div class="mb-3">
                                <p class="mb-0">{{ $publicacion->contenido }}</p>
                            </div>
                            @endif

                            <!-- Imagen (si existe) -->
                            @if($publicacion->imagen_url)
                            <div class="mb-3">
                                <img src="{{ $publicacion->imagen_url }}" alt="{{ $publicacion->titulo }}"
                                     class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                            </div>
                            @endif

                            <!-- Botón Ver Más (enlace a módulo origen) -->
                            @php
                            $verMasUrl = null;
                            if($publicacion->tipo == 'comunicado') $verMasUrl = route('extranet.comunicados.show', $publicacion->referencia_id);
                            elseif($publicacion->tipo == 'proyecto') $verMasUrl = route('extranet.proyectos.show', $publicacion->referencia_id);
                            elseif($publicacion->tipo == 'evento') $verMasUrl = route('extranet.eventos.show', $publicacion->referencia_id);
                            elseif($publicacion->tipo == 'reconocimiento') $verMasUrl = route('extranet.reconocimientos.show', $publicacion->referencia_id);
                            elseif($publicacion->tipo == 'encuesta') $verMasUrl = route('extranet.encuestas.show', $publicacion->referencia_id);
                            elseif($publicacion->tipo == 'documento') $verMasUrl = route('extranet.documentos.show', $publicacion->referencia_id);
                            @endphp

                            @if($verMasUrl)
                            <div class="mb-3">
                                <a href="{{ $verMasUrl }}" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-arrow-right"></i> Ver más
                                </a>
                            </div>
                            @endif

                            <hr>

                            <!-- Reacciones y Comentarios -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <!-- Botones de Reacción -->
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-light reaccion-btn" data-publicacion="{{ $publicacion->id }}" data-tipo="like">
                                        <i class="mdi mdi-thumb-up{{ $publicacion->userReaccion == 'like' ? ' text-primary' : '-outline' }}"></i>
                                        <span class="reaccion-count-like">{{ $publicacion->reacciones->where('tipo', 'like')->count() }}</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light reaccion-btn" data-publicacion="{{ $publicacion->id }}" data-tipo="love">
                                        <i class="mdi mdi-heart{{ $publicacion->userReaccion == 'love' ? ' text-danger' : '-outline' }}"></i>
                                        <span class="reaccion-count-love">{{ $publicacion->reacciones->where('tipo', 'love')->count() }}</span>
                                    </button>
                                </div>

                                <!-- Contador de Comentarios -->
                                <span class="text-muted">
                                    <i class="mdi mdi-comment-outline"></i> {{ $publicacion->total_comentarios }} comentarios
                                </span>
                            </div>

                            <!-- Comentarios -->
                            @if($publicacion->comentarios_habilitados)
                            <div class="comentarios-container">
                                <!-- Lista de Comentarios -->
                                @foreach($publicacion->comentarios->where('comentario_padre_id', null)->take(3) as $comentario)
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1">
                                        <div class="bg-light rounded p-2">
                                            <strong>{{ $comentario->autor->name }}</strong>
                                            <p class="mb-0 small">{{ $comentario->contenido }}</p>
                                        </div>
                                        <div class="small text-muted ml-2">
                                            {{ \Carbon\Carbon::parse($comentario->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($publicacion->total_comentarios > 3)
                                <div class="mb-2">
                                    <a href="#" class="small text-muted">Ver todos los comentarios ({{ $publicacion->total_comentarios }})</a>
                                </div>
                                @endif

                                <!-- Formulario de Comentario -->
                                <form action="{{ route('extranet.comentarios.store') }}" method="POST" class="comentario-form">
                                    @csrf
                                    <input type="hidden" name="publicacion_id" value="{{ $publicacion->id }}">
                                    <div class="input-group">
                                        <input type="text" name="contenido" class="form-control form-control-sm"
                                               placeholder="Escribe un comentario..." required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="mdi mdi-send"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <!-- Sin Publicaciones -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-newspaper-variant-outline mdi-72px text-muted"></i>
                            <h5 class="mt-3">No hay publicaciones aún</h5>
                            <p class="text-muted">El muro social se llenará automáticamente con noticias y actividades de la empresa.</p>
                        </div>
                    </div>
                    @endforelse

                    <!-- Paginación / Load More -->
                    @if($publicaciones->hasMorePages())
                    <div class="text-center my-4">
                        <button id="loadMoreBtn" class="btn btn-outline-primary" data-page="2">
                            <i class="mdi mdi-refresh"></i> Cargar más publicaciones
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle reacción (AJAX)
document.querySelectorAll('.reaccion-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const publicacionId = this.dataset.publicacion;
        const tipo = this.dataset.tipo;

        fetch(`/extranet/publicaciones/${publicacionId}/reaccionar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tipo: tipo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar contadores
                document.querySelector(`.reaccion-count-${tipo}`).textContent = data.count;

                // Actualizar icono
                const icon = this.querySelector('i');
                if (data.action == 'added') {
                    icon.classList.remove('mdi-thumb-up-outline', 'mdi-heart-outline');
                    icon.classList.add(tipo == 'like' ? 'mdi-thumb-up' : 'mdi-heart');
                    icon.classList.add(tipo == 'like' ? 'text-primary' : 'text-danger');
                } else {
                    icon.classList.remove('mdi-thumb-up', 'mdi-heart', 'text-primary', 'text-danger');
                    icon.classList.add(tipo == 'like' ? 'mdi-thumb-up-outline' : 'mdi-heart-outline');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Submit comentario (AJAX)
document.querySelectorAll('.comentario-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Recargar para mostrar el comentario
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Load more (AJAX infinite scroll)
const loadMoreBtn = document.getElementById('loadMoreBtn');
if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        const page = this.dataset.page;

        fetch(`/extranet/muro?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                // Agregar nuevas publicaciones
                document.querySelector('.col-lg-8.offset-lg-2').insertAdjacentHTML('beforeend', data.html);

                // Actualizar número de página
                if (data.hasMore) {
                    this.dataset.page = parseInt(page) + 1;
                } else {
                    this.remove();
                }
            }
        });
    });
}
</script>

<style>
.publicacion-card {
    transition: box-shadow 0.3s ease;
}

.publicacion-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.bg-pink {
    background-color: #ff69b4;
}

.bg-purple {
    background-color: #9c27b0;
}

.bg-cyan {
    background-color: #00bcd4;
}

.reaccion-btn {
    transition: all 0.2s ease;
}

.reaccion-btn:hover {
    transform: scale(1.1);
}
</style>
@endsection
