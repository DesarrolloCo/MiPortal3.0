<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            <i class="mdi mdi-newspaper text-success"></i> Muro Social
        </h4>
        <h6 class="card-subtitle">Últimas publicaciones del equipo</h6>

        @if($publicaciones && $publicaciones->count() > 0)
            <div class="mt-3">
                @foreach($publicaciones->take(3) as $publicacion)
                <div class="mb-3 pb-3 border-bottom">
                    <!-- Header de la publicación -->
                    <div class="d-flex align-items-center mb-2">
                        @if($publicacion->autor->empleados && $publicacion->autor->empleados->EMP_FOTO_URL)
                        <img src="{{ $publicacion->autor->empleados->EMP_FOTO_URL }}"
                             alt="{{ $publicacion->autor->name }}"
                             class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mr-2"
                             style="width: 40px; height: 40px;">
                            <i class="mdi mdi-account mdi-24px text-white"></i>
                        </div>
                        @endif

                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $publicacion->autor->name }}</h6>
                            <small class="text-muted">{{ $publicacion->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <p class="mb-2">{{ Str::limit($publicacion->contenido, 150) }}</p>

                    <!-- Imagen si existe -->
                    @if($publicacion->imagen_url)
                    <div class="mb-2">
                        <img src="{{ $publicacion->imagen_url }}" alt="Imagen"
                             class="img-fluid rounded" style="max-height: 200px; object-fit: cover; width: 100%;">
                    </div>
                    @endif

                    <!-- Estadísticas -->
                    <div class="d-flex align-items-center text-muted small">
                        <span class="mr-3">
                            <i class="mdi mdi-heart"></i>
                            {{ $publicacion->reacciones->count() }} reacciones
                        </span>
                        <span>
                            <i class="mdi mdi-comment"></i>
                            {{ $publicacion->comentarios->count() }} comentarios
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('extranet.muro.index') }}" class="btn btn-sm btn-outline-success">
                    <i class="mdi mdi-newspaper"></i> Ver todo el muro
                </a>
            </div>
        @else
            <div class="text-center py-4">
                <i class="mdi mdi-newspaper-variant-outline mdi-48px text-muted"></i>
                <p class="text-muted mt-2 mb-0">No hay publicaciones recientes</p>
                <a href="{{ route('extranet.muro.index') }}" class="btn btn-sm btn-success mt-2">
                    <i class="mdi mdi-plus"></i> Crear primera publicación
                </a>
            </div>
        @endif
    </div>
</div>
