<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-calendar-check text-warning"></i> Eventos Próximos</h4>
        <h6 class="card-subtitle">Próximos 30 días</h6>

        @if($eventosProximos->count() > 0)
            <div class="m-t-20" style="max-height: 500px; overflow-y: auto;">
                @foreach($eventosProximos as $item)
                <div class="d-flex align-items-start border-bottom pb-3 pt-3 {{ $item['es_hoy'] ? 'bg-light-danger' : ($item['es_manana'] ? 'bg-light-warning' : '') }}">
                    <!-- Fecha circular -->
                    <div class="mr-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-{{ $item['es_hoy'] ? 'danger' : ($item['es_manana'] ? 'warning' : ($item['es_esta_semana'] ? 'info' : 'secondary')) }} text-white"
                             style="width: 60px; height: 60px; min-width: 60px;">
                            <div class="text-center">
                                <h5 class="mb-0 font-weight-bold" style="line-height: 1;">{{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->format('d') }}</h5>
                                <small style="font-size: 10px; line-height: 1;">{{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->locale('es')->isoFormat('MMM') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Información del evento -->
                    <div class="flex-grow-1">
                        <h6 class="mb-1 font-weight-bold">{{ $item['evento']->titulo }}</h6>

                        <div class="mb-2">
                            <small class="text-muted d-block">
                                <i class="mdi mdi-clock-outline"></i>
                                {{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->format('H:i') }}
                                @if($item['evento']->hora_fin)
                                    - {{ $item['evento']->hora_fin }}
                                @endif
                            </small>

                            <small class="text-muted d-block">
                                @if($item['evento']->modalidad == 'presencial')
                                    <i class="mdi mdi-map-marker text-success"></i> {{ Str::limit($item['evento']->lugar, 40) }}
                                @elseif($item['evento']->modalidad == 'virtual')
                                    <i class="mdi mdi-video text-primary"></i> Virtual
                                @else
                                    <i class="mdi mdi-vector-combine text-info"></i> Híbrido
                                @endif
                            </small>

                            @if($item['evento']->tipo)
                            <small class="text-muted d-block">
                                <i class="mdi mdi-tag-outline"></i> {{ ucfirst($item['evento']->tipo) }}
                            </small>
                            @endif
                        </div>

                        <!-- Badges -->
                        <div>
                            @if($item['es_hoy'])
                                <span class="badge badge-danger badge-pill">
                                    <i class="mdi mdi-alarm"></i> ¡Hoy!
                                </span>
                            @elseif($item['es_manana'])
                                <span class="badge badge-warning badge-pill">
                                    <i class="mdi mdi-calendar-today"></i> Mañana
                                </span>
                            @elseif($item['dias_restantes'] <= 7)
                                <span class="badge badge-info badge-pill">En {{ $item['dias_restantes'] }} días</span>
                            @else
                                <span class="badge badge-secondary badge-pill">{{ $item['dias_restantes'] }} días</span>
                            @endif

                            @if($item['evento']->requiere_confirmacion)
                                <span class="badge badge-outline-primary badge-pill">
                                    <i class="mdi mdi-account-check"></i> RSVP
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Link al evento -->
                    <div class="ml-2">
                        <a href="{{ route('extranet.eventos.show', $item['evento']->id) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="Ver detalles">
                            <i class="mdi mdi-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Resumen total -->
            <div class="text-center mt-3 pt-3 border-top">
                <small class="text-muted">
                    <strong>{{ $eventosProximos->count() }}</strong> eventos próximos
                </small>
                <br>
                <a href="{{ route('extranet.eventos.index') }}" class="btn btn-sm btn-link">
                    Ver todos los eventos <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-calendar-remove mdi-48px text-muted"></i>
                <p class="text-muted mt-2">No hay eventos próximos en los siguientes 30 días</p>
                @can('crear-evento')
                <a href="{{ route('extranet.eventos.create') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="mdi mdi-plus"></i> Crear evento
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
