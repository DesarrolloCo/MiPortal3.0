{{-- Componente de Dropdown de Notificaciones --}}
{{-- Uso: @include('extranet.components.notification-dropdown') --}}

@php
$empleado = Auth::check() && Auth::user()->empleados ? Auth::user()->empleados : null;
$notificaciones = [];
$noLeidas = 0;

if ($empleado) {
    // Obtener notificaciones recientes (últimas 5)
    $notificaciones = \App\Models\Extranet\NotificacionExtranet::where('empleado_id', $empleado->EMP_ID)
        ->orderBy('created_at', 'DESC')
        ->take(5)
        ->get();

    // Contar no leídas
    $noLeidas = \App\Models\Extranet\NotificacionExtranet::where('empleado_id', $empleado->EMP_ID)
        ->where('leida', false)
        ->count();
}
@endphp

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" id="notificacionDropdown"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-bell font-24"></i>
        @if($noLeidas > 0)
        <span class="badge badge-danger notify-badge">{{ $noLeidas > 9 ? '9+' : $noLeidas }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" style="width: 350px;">
        <ul class="list-style-none">
            <li>
                <div class="drop-title bg-primary text-white">
                    <h4 class="m-b-0 m-t-5">{{ $noLeidas }} Nuevas</h4>
                    <span class="font-light">Notificaciones</span>
                </div>
            </li>
            <li>
                <div class="message-center notifications" style="max-height: 400px; overflow-y: auto;">
                    @forelse($notificaciones as $notificacion)
                    <!-- Notificación -->
                    <a href="{{ $notificacion->url ?? route('extranet.notificaciones.index') }}"
                       class="message-item d-flex align-items-center border-bottom px-3 py-2 {{ !$notificacion->leida ? 'bg-light' : '' }}">
                        <div class="btn btn-{{ $notificacion->tipo == 'comunicado' ? 'primary' : ($notificacion->tipo == 'evento' ? 'warning' : 'info') }} btn-circle">
                            <i class="mdi mdi-{{ $notificacion->icono ?? 'bell' }}"></i>
                        </div>
                        <div class="w-75 d-inline-block v-middle pl-2">
                            <h6 class="message-title mb-0 mt-1">{{ Str::limit($notificacion->titulo, 30) }}</h6>
                            @if($notificacion->mensaje)
                            <span class="font-12 text-nowrap d-block text-muted text-truncate">{{ Str::limit($notificacion->mensaje, 40) }}</span>
                            @endif
                            <span class="font-12 text-nowrap d-block text-muted">
                                {{ \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <!-- Sin Notificaciones -->
                    <div class="text-center py-4">
                        <i class="mdi mdi-bell-off mdi-48px text-muted"></i>
                        <p class="text-muted mb-0">No hay notificaciones</p>
                    </div>
                    @endforelse
                </div>
            </li>
            <li>
                <a class="nav-link pt-3 text-center text-dark" href="{{ route('extranet.notificaciones.index') }}">
                    <strong>Ver todas las notificaciones</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </div>
</li>

<style>
.notify-badge {
    position: absolute;
    right: -5px;
    top: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: bold;
}

.message-item {
    text-decoration: none;
    transition: background-color 0.2s ease;
}

.message-item:hover {
    background-color: #f8f9fa !important;
}

.btn-circle {
    width: 40px;
    height: 40px;
    padding: 8px 0px;
    border-radius: 50%;
    text-align: center;
    font-size: 14px;
    line-height: 1.42857;
}
</style>

{{-- Script para actualizar el contador en tiempo real (AJAX polling) --}}
<script>
// Actualizar contador de notificaciones cada 30 segundos
setInterval(function() {
    fetch('/extranet/notificaciones/no-leidas')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notify-badge');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    badge.style.display = 'inline-block';
                } else {
                    // Crear badge si no existe
                    const notifIcon = document.querySelector('#notificacionDropdown');
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge badge-danger notify-badge';
                    newBadge.textContent = data.count > 9 ? '9+' : data.count;
                    notifIcon.appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error actualizando notificaciones:', error));
}, 30000); // 30 segundos
</script>
