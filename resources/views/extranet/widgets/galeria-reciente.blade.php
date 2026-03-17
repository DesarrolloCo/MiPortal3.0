<div class="card">
    <div class="card-body p-0">
        @if($galeriaReciente)
            <!-- Portada del álbum -->
            <div class="position-relative" style="height: 250px; overflow: hidden;">
                @if($galeriaReciente->portada_url)
                <img src="{{ $galeriaReciente->portada_url }}" alt="{{ $galeriaReciente->titulo }}"
                     style="width: 100%; height: 100%; object-fit: cover;">
                @else
                <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                    <i class="mdi mdi-image-album mdi-72px text-white"></i>
                </div>
                @endif

                <!-- Overlay con información -->
                <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 20px;">
                    <h5 class="text-white mb-1">
                        <i class="mdi mdi-image-multiple"></i> {{ $galeriaReciente->titulo }}
                    </h5>
                    <div class="text-white-50 small">
                        <span class="mr-3">
                            <i class="mdi mdi-camera"></i> {{ $galeriaReciente->fotos_count ?? 0 }} fotos
                        </span>
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ \Carbon\Carbon::parse($galeriaReciente->fecha)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Cuerpo del card -->
            <div class="p-3">
                @if($galeriaReciente->descripcion)
                <p class="text-muted mb-2 small">
                    {{ Str::limit($galeriaReciente->descripcion, 100) }}
                </p>
                @endif

                @if($galeriaReciente->evento)
                <div class="mb-3">
                    <span class="badge badge-info">
                        <i class="mdi mdi-calendar-star"></i> {{ $galeriaReciente->evento->titulo }}
                    </span>
                </div>
                @endif

                <div class="text-center">
                    <a href="{{ route('extranet.galeria.show', $galeriaReciente->id) }}"
                       class="btn btn-sm btn-primary btn-block">
                        <i class="mdi mdi-eye"></i> Ver álbum completo
                    </a>
                    <a href="{{ route('extranet.galeria.index') }}"
                       class="btn btn-sm btn-outline-secondary btn-block mt-1">
                        <i class="mdi mdi-image-multiple"></i> Ver todas las galerías
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="mdi mdi-image-album-outline mdi-72px text-muted"></i>
                <h5 class="mt-3">No hay álbumes de fotos</h5>
                <p class="text-muted">Los álbumes de eventos aparecerán aquí</p>
                @can('crear-album')
                <a href="{{ route('extranet.galeria.create') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="mdi mdi-plus"></i> Crear primer álbum
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
