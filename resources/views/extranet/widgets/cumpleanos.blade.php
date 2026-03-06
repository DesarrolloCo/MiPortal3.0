<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-cake text-warning"></i> Cumpleaños del Mes</h4>
        <h6 class="card-subtitle">Celebremos juntos</h6>

        @if($cumpleanos->count() > 0)
            <div class="table-responsive m-t-20">
                <table class="table stylish-table">
                    <tbody>
                        @foreach($cumpleanos->take(5) as $item)
                        <tr>
                            <td style="width:50px;">
                                <div class="round round-{{ $item['es_hoy'] ? 'success' : ($item['es_esta_semana'] ? 'warning' : 'info') }}">
                                    <i class="mdi mdi-cake-variant"></i>
                                </div>
                            </td>
                            <td>
                                <h6>{{ $item['empleado']->EMP_NOMBRES }}</h6>
                                <small class="text-muted">
                                    {{ $item['fecha'] }} ({{ $item['edad'] }} años)
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
                @if($cumpleanos->count() > 5)
                    <div class="text-center">
                        <small class="text-muted">+ {{ $cumpleanos->count() - 5 }} cumpleaños más este mes</small>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-emoticon-sad mdi-48px text-muted"></i>
                <p class="text-muted">No hay cumpleaños este mes</p>
            </div>
        @endif
    </div>
</div>
