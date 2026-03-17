<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            <i class="mdi mdi-file-document text-primary"></i> Documentos Destacados
        </h4>
        <h6 class="card-subtitle">Documentación importante de la empresa</h6>

        @if($documentosDestacados && $documentosDestacados->count() > 0)
            <div class="mt-3">
                @foreach($documentosDestacados as $documento)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <!-- Icono según tipo de archivo -->
                    <div class="mr-3">
                        @php
                            $extension = pathinfo($documento->archivo_nombre ?? '', PATHINFO_EXTENSION);
                            $iconClass = 'mdi-file-document';
                            $iconColor = 'text-primary';

                            if (in_array($extension, ['pdf'])) {
                                $iconClass = 'mdi-file-pdf';
                                $iconColor = 'text-danger';
                            } elseif (in_array($extension, ['doc', 'docx'])) {
                                $iconClass = 'mdi-file-word';
                                $iconColor = 'text-info';
                            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                $iconClass = 'mdi-file-excel';
                                $iconColor = 'text-success';
                            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                $iconClass = 'mdi-file-powerpoint';
                                $iconColor = 'text-warning';
                            }
                        @endphp
                        <i class="mdi {{ $iconClass }} mdi-36px {{ $iconColor }}"></i>
                    </div>

                    <!-- Información del documento -->
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            {{ $documento->titulo }}
                            @if($documento->destacado)
                            <span class="badge badge-primary badge-pill">Destacado</span>
                            @endif
                        </h6>
                        <div class="d-flex align-items-center text-muted small">
                            <span class="mr-3">
                                <i class="mdi mdi-tag"></i>
                                {{ ucfirst($documento->categoria) }}
                            </span>
                            @if($documento->version)
                            <span class="mr-3">
                                <i class="mdi mdi-file-code"></i>
                                v{{ $documento->version }}
                            </span>
                            @endif
                            <span>
                                <i class="mdi mdi-download"></i>
                                {{ $documento->descargas ?? 0 }} descargas
                            </span>
                        </div>
                    </div>

                    <!-- Botón descargar -->
                    <div class="ml-2">
                        <a href="{{ route('extranet.documentos.descargar', $documento->id) }}"
                           class="btn btn-sm btn-primary" title="Descargar">
                            <i class="mdi mdi-download"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('extranet.documentos.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-folder-multiple"></i> Ver todos los documentos
                </a>
            </div>
        @else
            <div class="text-center py-4">
                <i class="mdi mdi-folder-open-outline mdi-48px text-muted"></i>
                <p class="text-muted mt-2 mb-0">No hay documentos disponibles</p>
            </div>
        @endif
    </div>
</div>
