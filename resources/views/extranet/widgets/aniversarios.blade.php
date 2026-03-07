<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-party-popper text-info"></i> Aniversarios Laborales</h4>
        <h6 class="card-subtitle">Celebremos su trayectoria - {{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY') }}</h6>

        @if($aniversarios->count() > 0)
            <div class="m-t-20" style="max-height: 400px; overflow-y: auto;">
                @foreach($aniversarios->take(10) as $item)
                <div class="d-flex align-items-center border-bottom pb-3 pt-3 {{ $item['es_hoy'] ? 'bg-light-success' : '' }}">
                    <!-- Foto del empleado -->
                    <div class="mr-3">
                        @if($item['empleado']->EMP_FOTO_URL)
                        <img src="{{ $item['empleado']->EMP_FOTO_URL }}" alt="{{ $item['empleado']->EMP_NOMBRES }}"
                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-{{ $item['es_hoy'] ? 'success' : ($item['es_esta_semana'] ? 'warning' : 'primary') }} text-white d-inline-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px; font-size: 20px;">
                            {{ substr($item['empleado']->EMP_NOMBRES, 0, 1) }}{{ substr($item['empleado']->EMP_APELLIDOS, 0, 1) }}
                        </div>
                        @endif
                    </div>

                    <!-- Información del empleado -->
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $item['empleado']->EMP_NOMBRES }} {{ $item['empleado']->EMP_APELLIDOS }}</h6>
                        <small class="text-muted d-block">
                            @if($item['empleado']->cargo)
                                {{ $item['empleado']->cargo->CAR_NOMBRE }}
                            @endif
                        </small>
                        <small class="text-muted">
                            <i class="mdi mdi-briefcase"></i> {{ $item['fecha'] }}
                            <span class="text-primary font-weight-bold">
                                ({{ $item['anos'] }} {{ $item['anos'] == 1 ? 'año' : 'años' }})
                            </span>
                        </small>
                    </div>

                    <!-- Badge de estado -->
                    <div class="ml-2">
                        @if($item['es_hoy'])
                            <span class="badge badge-success badge-pill">
                                <i class="mdi mdi-party-popper"></i> ¡Hoy!
                            </span>
                        @elseif($item['dias_restantes'] == 1)
                            <span class="badge badge-warning badge-pill">Mañana</span>
                        @elseif($item['dias_restantes'] <= 7)
                            <span class="badge badge-warning badge-pill">{{ $item['dias_restantes'] }} días</span>
                        @else
                            <span class="badge badge-info badge-pill">{{ $item['dias_restantes'] }} días</span>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($aniversarios->count() > 10)
                    <div class="text-center pt-3">
                        <small class="text-muted">
                            <i class="mdi mdi-information"></i>
                            + {{ $aniversarios->count() - 10 }} aniversarios más este mes
                        </small>
                    </div>
                @endif
            </div>

            <!-- Resumen total -->
            <div class="text-center mt-3 pt-3 border-top">
                <small class="text-muted">
                    <strong>{{ $aniversarios->count() }}</strong> aniversarios en total este mes
                </small>
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-emoticon-neutral mdi-48px text-muted"></i>
                <p class="text-muted mt-2">No hay aniversarios este mes</p>
            </div>
        @endif
    </div>
</div>
