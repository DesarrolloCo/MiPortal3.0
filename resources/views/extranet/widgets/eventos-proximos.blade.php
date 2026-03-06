<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-calendar-check text-warning"></i> Eventos Próximos</h4>
        <h6 class="card-subtitle">Los próximos 30 días</h6>

        @if($eventosProximos->count() > 0)
            <div class="m-t-20">
                @foreach($eventosProximos as $item)
                <div class="d-flex m-b-20 p-b-20" style="border-bottom: 1px solid #f1f1f1;">
                    <div class="m-r-20">
                        <div class="round round-{{ $item['es_hoy'] ? 'danger' : ($item['es_manana'] ? 'warning' : 'info') }}" style="width: 60px; height: 60px; line-height: 60px;">
                            <h6 class="m-b-0 text-white">
                                {{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->format('d') }}<br>
                                <small>{{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->format('M') }}</small>
                            </h6>
                        </div>
                    </div>
                    <div>
                        <h6 class="m-b-5">{{ $item['evento']->titulo }}</h6>
                        <p class="text-muted m-b-0">
                            <i class="mdi mdi-clock"></i> {{ \Carbon\Carbon::parse($item['evento']->fecha_inicio)->format('H:i') }}
                            @if($item['evento']->modalidad == 'presencial')
                                <br><i class="mdi mdi-map-marker"></i> {{ $item['evento']->lugar }}
                            @elseif($item['evento']->modalidad == 'virtual')
                                <br><i class="mdi mdi-video"></i> Virtual
                            @else
                                <br><i class="mdi mdi-hybrid"></i> Híbrido
                            @endif
                        </p>
                        @if($item['es_hoy'])
                            <span class="badge badge-danger">¡Hoy!</span>
                        @elseif($item['es_manana'])
                            <span class="badge badge-warning">Mañana</span>
                        @else
                            <span class="badge badge-info">En {{ $item['dias_restantes'] }} días</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-calendar-remove mdi-48px text-muted"></i>
                <p class="text-muted">No hay eventos próximos</p>
            </div>
        @endif
    </div>
</div>
