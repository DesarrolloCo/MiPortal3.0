<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-account-plus text-success"></i> Nuevos Empleados</h4>
        <h6 class="card-subtitle">Bienvenidos al equipo</h6>

        @if($nuevosEmpleados->count() > 0)
            <div class="table-responsive m-t-20">
                <table class="table stylish-table">
                    <tbody>
                        @foreach($nuevosEmpleados->take(5) as $item)
                        <tr>
                            <td style="width:50px;">
                                <div class="round round-success">
                                    <i class="mdi mdi-account-circle"></i>
                                </div>
                            </td>
                            <td>
                                <h6>{{ $item['empleado']->EMP_NOMBRES }}</h6>
                                <small class="text-muted">
                                    Ingresó hace {{ $item['dias'] }} días
                                    @if($item['dias'] <= 7)
                                        <span class="badge badge-success">Nuevo</span>
                                    @endif
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($nuevosEmpleados->count() > 5)
                    <div class="text-center">
                        <small class="text-muted">+ {{ $nuevosEmpleados->count() - 5 }} empleados más</small>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-account-group mdi-48px text-muted"></i>
                <p class="text-muted">No hay nuevos empleados en los últimos 30 días</p>
            </div>
        @endif
    </div>
</div>
