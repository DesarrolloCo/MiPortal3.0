<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-party-popper text-info"></i> Aniversarios Laborales</h4>
        <h6 class="card-subtitle">Celebremos su trayectoria</h6>

        @if($aniversarios->count() > 0)
            <div class="table-responsive m-t-20">
                <table class="table stylish-table">
                    <tbody>
                        @foreach($aniversarios->take(5) as $item)
                        <tr>
                            <td style="width:50px;">
                                <div class="round round-{{ $item['es_hoy'] ? 'success' : ($item['es_esta_semana'] ? 'warning' : 'primary') }}">
                                    <i class="mdi mdi-briefcase"></i>
                                </div>
                            </td>
                            <td>
                                <h6>{{ $item['empleado']->EMP_NOMBRES }}</h6>
                                <small class="text-muted">
                                    {{ $item['anos'] }} {{ $item['anos'] == 1 ? 'año' : 'años' }} en la empresa
                                    @if($item['es_hoy'])
                                        <span class="badge badge-success">¡Hoy!</span>
                                    @elseif($item['dias_restantes'] <= 7)
                                        <span class="badge badge-warning">En {{ $item['dias_restantes'] }} días</span>
                                    @endif
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($aniversarios->count() > 5)
                    <div class="text-center">
                        <small class="text-muted">+ {{ $aniversarios->count() - 5 }} aniversarios más este mes</small>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-emoticon-neutral mdi-48px text-muted"></i>
                <p class="text-muted">No hay aniversarios este mes</p>
            </div>
        @endif
    </div>
</div>
