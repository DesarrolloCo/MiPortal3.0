<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            <i class="mdi mdi-trophy text-warning"></i> Reconocimientos Recientes
        </h4>
        <h6 class="card-subtitle">Celebrando los logros del equipo</h6>

        @if($reconocimientosRecientes && $reconocimientosRecientes->count() > 0)
            <div class="mt-3">
                @foreach($reconocimientosRecientes as $item)
                @php
                    $reconocimiento = $item['reconocimiento'];
                @endphp
                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                    <!-- Foto del empleado -->
                    <div class="mr-3">
                        @if($reconocimiento->empleado->EMP_FOTO_URL)
                        <img src="{{ $reconocimiento->empleado->EMP_FOTO_URL }}"
                             alt="{{ $reconocimiento->empleado->nombre_completo }}"
                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="mdi mdi-account mdi-24px text-white"></i>
                        </div>
                        @endif
                    </div>

                    <!-- Información del reconocimiento -->
                    <div class="flex-grow-1">
                        <h6 class="mb-1">
                            <span class="badge badge-warning mr-1">
                                <i class="mdi mdi-trophy"></i> {{ $reconocimiento->tipo }}
                            </span>
                            {{ $reconocimiento->empleado->nombre_completo }}
                        </h6>
                        <p class="text-muted mb-2 small">
                            "{{ Str::limit($reconocimiento->descripcion, 100) }}"
                        </p>
                        <div class="d-flex align-items-center">
                            <small class="text-muted mr-3">
                                <i class="mdi mdi-account"></i>
                                Por: {{ $reconocimiento->otorgadoPor->name ?? 'Sistema' }}
                            </small>
                            <small class="text-muted">
                                <i class="mdi mdi-clock"></i>
                                @if($item['dias_atras'] == 0)
                                    Hoy
                                @elseif($item['dias_atras'] == 1)
                                    Ayer
                                @else
                                    Hace {{ $item['dias_atras'] }} días
                                @endif
                            </small>
                            @if($item['es_reciente'])
                            <span class="badge badge-success badge-pill ml-2">Nuevo</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-sm btn-outline-warning">
                    <i class="mdi mdi-trophy"></i> Ver todos los reconocimientos
                </a>
            </div>
        @else
            <div class="text-center py-4">
                <i class="mdi mdi-trophy-outline mdi-48px text-muted"></i>
                <p class="text-muted mt-2 mb-0">No hay reconocimientos recientes</p>
            </div>
        @endif
    </div>
</div>
