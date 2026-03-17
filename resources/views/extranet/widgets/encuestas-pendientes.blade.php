<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            <i class="mdi mdi-poll-box text-info"></i> Encuestas Pendientes
        </h4>
        <h6 class="card-subtitle">Responde estas encuestas activas</h6>

        @if($encuestasPendientes && $encuestasPendientes->count() > 0)
            <div class="mt-3">
                @foreach($encuestasPendientes as $encuesta)
                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $encuesta->titulo }}</h6>
                        <p class="text-muted mb-2 small">
                            {{ Str::limit($encuesta->descripcion, 80) }}
                        </p>
                        <div class="d-flex align-items-center">
                            <small class="text-muted mr-3">
                                <i class="mdi mdi-calendar"></i>
                                @if($encuesta->fecha_fin)
                                    Vence: {{ \Carbon\Carbon::parse($encuesta->fecha_fin)->format('d/m/Y') }}
                                @else
                                    Sin fecha límite
                                @endif
                            </small>
                            <small class="text-muted">
                                <i class="mdi mdi-help-circle"></i>
                                {{ $encuesta->preguntas->count() }} preguntas
                            </small>
                        </div>
                    </div>
                    <div class="ml-2">
                        <a href="{{ route('extranet.encuestas.show', $encuesta->id) }}"
                           class="btn btn-sm btn-info">
                            <i class="mdi mdi-pencil"></i> Responder
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($encuestasPendientes->count() > 0)
            <div class="text-center mt-2">
                <a href="{{ route('extranet.encuestas.index') }}" class="btn btn-sm btn-outline-info">
                    Ver todas las encuestas
                </a>
            </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="mdi mdi-checkbox-marked-circle-outline mdi-48px text-success"></i>
                <p class="text-muted mt-2 mb-0">No tienes encuestas pendientes</p>
            </div>
        @endif
    </div>
</div>
