@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-poll-box text-primary"></i> Encuestas Corporativas
                            </h4>
                            <h6 class="card-subtitle">Participa y ayúdanos a mejorar</h6>
                        </div>
                        <div>
                            @can('crear-encuesta')
                            <a href="{{ route('extranet.encuestas.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Nueva Encuesta
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#activas" role="tab">
                        <i class="mdi mdi-play-circle"></i> Activas ({{ $encuestasActivas->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#pendientes" role="tab">
                        <i class="mdi mdi-clock-outline"></i> Pendientes de Responder
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cerradas" role="tab">
                        <i class="mdi mdi-check-circle"></i> Cerradas ({{ $encuestasCerradas->count() }})
                    </a>
                </li>
                @can('crear-encuesta')
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#borradores" role="tab">
                        <i class="mdi mdi-file-document-outline"></i> Borradores ({{ $borradores->count() }})
                    </a>
                </li>
                @endcan
            </ul>

            <div class="tab-content">
                <!-- Encuestas Activas -->
                <div class="tab-pane active p-4" id="activas" role="tabpanel">
                    <div class="row">
                        @forelse($encuestasActivas as $encuesta)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('extranet.encuestas.partials.card-encuesta', ['encuesta' => $encuesta])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-poll-box mdi-72px text-muted"></i>
                                <h5 class="mt-3">No hay encuestas activas</h5>
                                <p class="text-muted">Por el momento no hay encuestas disponibles para responder</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pendientes de Responder -->
                <div class="tab-pane p-4" id="pendientes" role="tabpanel">
                    <div class="row">
                        @forelse($encuestasPendientes as $encuesta)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('extranet.encuestas.partials.card-encuesta', ['encuesta' => $encuesta, 'pendiente' => true])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-check-all mdi-72px text-success"></i>
                                <h5 class="mt-3">¡Estás al día!</h5>
                                <p class="text-muted">Has respondido todas las encuestas activas</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Encuestas Cerradas -->
                <div class="tab-pane p-4" id="cerradas" role="tabpanel">
                    <div class="row">
                        @forelse($encuestasCerradas as $encuesta)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('extranet.encuestas.partials.card-encuesta', ['encuesta' => $encuesta])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-archive mdi-72px text-muted"></i>
                                <h5 class="mt-3">No hay encuestas cerradas</h5>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Borradores -->
                @can('crear-encuesta')
                <div class="tab-pane p-4" id="borradores" role="tabpanel">
                    <div class="row">
                        @forelse($borradores as $encuesta)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('extranet.encuestas.partials.card-encuesta', ['encuesta' => $encuesta])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-file-document-outline mdi-72px text-muted"></i>
                                <h5 class="mt-3">No hay borradores</h5>
                                <p class="text-muted">Crea una nueva encuesta para comenzar</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
