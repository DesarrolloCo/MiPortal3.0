@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="mdi mdi-bell"></i> Centro de Notificaciones</h3>
                <div>
                    @if($notificaciones->where('leida', false)->count() > 0)
                    <form action="{{ route('extranet.notificaciones.marcar-todas-leidas') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-check-all"></i> Marcar todas como leídas
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Tabs de Filtros -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ !request('tipo') ? 'active' : '' }}"
                       href="{{ route('extranet.notificaciones.index') }}">
                        <i class="mdi mdi-all-inclusive"></i> Todas
                        @if($notificaciones->where('leida', false)->count() > 0)
                        <span class="badge badge-danger">{{ $notificaciones->where('leida', false)->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'comunicado' ? 'active' : '' }}"
                       href="{{ route('extranet.notificaciones.index', ['tipo' => 'comunicado']) }}">
                        <i class="mdi mdi-bullhorn"></i> Comunicados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'evento' ? 'active' : '' }}"
                       href="{{ route('extranet.notificaciones.index', ['tipo' => 'evento']) }}">
                        <i class="mdi mdi-calendar"></i> Eventos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'proyecto' ? 'active' : '' }}"
                       href="{{ route('extranet.notificaciones.index', ['tipo' => 'proyecto']) }}">
                        <i class="mdi mdi-clipboard-text"></i> Proyectos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'reconocimiento' ? 'active' : '' }}"
                       href="{{ route('extranet.notificaciones.index', ['tipo' => 'reconocimiento']) }}">
                        <i class="mdi mdi-trophy"></i> Reconocimientos
                    </a>
                </li>
            </ul>

            <!-- Lista de Notificaciones -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    @forelse($notificaciones as $notificacion)
                    <div class="card mb-2 notificacion-item {{ !$notificacion->leida ? 'bg-light border-primary' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <!-- Icono -->
                                <div class="mr-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px; background-color: {{ $notificacion->color ?? '#007bff' }};">
                                        <i class="mdi mdi-{{ $notificacion->icono ?? 'bell' }} mdi-24px text-white"></i>
                                    </div>
                                </div>

                                <!-- Contenido -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                @if($notificacion->importante)
                                                <span class="badge badge-danger">Importante</span>
                                                @endif
                                                @if(!$notificacion->leida)
                                                <span class="badge badge-primary">Nueva</span>
                                                @endif
                                                {{ $notificacion->titulo }}
                                            </h6>
                                            @if($notificacion->mensaje)
                                            <p class="mb-1 small">{{ $notificacion->mensaje }}</p>
                                            @endif
                                            <small class="text-muted">
                                                <i class="mdi mdi-clock-outline"></i>
                                                {{ \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() }}
                                            </small>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-toggle="dropdown">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(!$notificacion->leida)
                                                <form action="{{ route('extranet.notificaciones.marcar-leida', $notificacion->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="mdi mdi-check"></i> Marcar como leída
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('extranet.notificaciones.eliminar', $notificacion->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="mdi mdi-delete"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botón de Acción -->
                                    @if($notificacion->url)
                                    <a href="{{ $notificacion->url }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="mdi mdi-arrow-right"></i> Ver detalles
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Sin Notificaciones -->
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-bell-off mdi-72px text-muted"></i>
                            <h5 class="mt-3">No tienes notificaciones</h5>
                            <p class="text-muted">Cuando haya novedades, aparecerán aquí.</p>
                        </div>
                    </div>
                    @endforelse

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $notificaciones->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notificacion-item {
    transition: all 0.3s ease;
}

.notificacion-item:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
</style>
@endsection
