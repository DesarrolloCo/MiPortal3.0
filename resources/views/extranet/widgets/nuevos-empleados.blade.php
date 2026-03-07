<div class="card">
    <div class="card-body">
        <h4 class="card-title"><i class="mdi mdi-account-plus text-success"></i> Nuevos Empleados</h4>
        <h6 class="card-subtitle">Bienvenidos al equipo (últimos 30 días)</h6>

        @if($nuevosEmpleados->count() > 0)
            <div class="m-t-20" style="max-height: 400px; overflow-y: auto;">
                @foreach($nuevosEmpleados->take(10) as $item)
                <div class="d-flex align-items-center border-bottom pb-3 pt-3 {{ $item['dias'] <= 7 ? 'bg-light-success' : '' }}">
                    <!-- Foto del empleado -->
                    <div class="mr-3">
                        @if($item['empleado']->EMP_FOTO_URL)
                        <img src="{{ $item['empleado']->EMP_FOTO_URL }}" alt="{{ $item['empleado']->EMP_NOMBRES }}"
                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center"
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
                            <i class="mdi mdi-calendar-clock"></i> Ingresó: {{ $item['fecha_ingreso'] }}
                            <span class="text-success">
                                (hace {{ $item['dias'] }} {{ $item['dias'] == 1 ? 'día' : 'días' }})
                            </span>
                        </small>
                    </div>

                    <!-- Badge de estado -->
                    <div class="ml-2">
                        @if($item['dias'] == 0)
                            <span class="badge badge-success badge-pill">
                                <i class="mdi mdi-new-box"></i> ¡Hoy!
                            </span>
                        @elseif($item['dias'] <= 7)
                            <span class="badge badge-success badge-pill">
                                <i class="mdi mdi-star"></i> Nuevo
                            </span>
                        @else
                            <span class="badge badge-info badge-pill">Reciente</span>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($nuevosEmpleados->count() > 10)
                    <div class="text-center pt-3">
                        <small class="text-muted">
                            <i class="mdi mdi-information"></i>
                            + {{ $nuevosEmpleados->count() - 10 }} empleados más
                        </small>
                    </div>
                @endif
            </div>

            <!-- Resumen total -->
            <div class="text-center mt-3 pt-3 border-top">
                <small class="text-muted">
                    <strong>{{ $nuevosEmpleados->count() }}</strong> nuevos empleados en total
                </small>
            </div>
        @else
            <div class="text-center m-t-30 m-b-30">
                <i class="mdi mdi-account-group mdi-48px text-muted"></i>
                <p class="text-muted mt-2">No hay nuevos empleados en los últimos 30 días</p>
            </div>
        @endif
    </div>
</div>
