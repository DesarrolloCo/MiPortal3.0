@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.comunicados.index') }}">Comunicados</a></li>
                    <li class="breadcrumb-item active">{{ $comunicado->titulo }}</li>
                </ol>
            </nav>

            <!-- Comunicado -->
            <div class="card">
                @if($comunicado->imagen_url)
                <img class="card-img-top" src="{{ $comunicado->imagen_url }}" alt="{{ $comunicado->titulo }}" style="max-height: 400px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Badges -->
                    <div class="mb-3">
                        @if($comunicado->fijado)
                        <span class="badge badge-warning"><i class="mdi mdi-pin"></i> Fijado</span>
                        @endif
                        <span class="badge badge-{{ $comunicado->prioridad == 'critica' ? 'danger' : ($comunicado->prioridad == 'alta' ? 'warning' : ($comunicado->prioridad == 'media' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($comunicado->prioridad) }}
                        </span>
                        <span class="badge badge-primary">{{ ucfirst($comunicado->tipo) }}</span>
                        <span class="badge badge-{{ $comunicado->estado == 'publicado' ? 'success' : 'secondary' }}">
                            {{ ucfirst($comunicado->estado) }}
                        </span>
                    </div>

                    <!-- Título -->
                    <h2 class="card-title">{{ $comunicado->titulo }}</h2>

                    <!-- Metadata -->
                    <div class="text-muted mb-4">
                        <p class="mb-1">
                            <i class="mdi mdi-account"></i> Publicado por <strong>{{ $comunicado->autor->name ?? 'Sistema' }}</strong>
                        </p>
                        <p class="mb-1">
                            <i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($comunicado->created_at)->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-1">
                            <i class="mdi mdi-calendar-check"></i> Vigente desde {{ \Carbon\Carbon::parse($comunicado->fecha_inicio)->format('d/m/Y') }}
                            @if($comunicado->fecha_fin)
                            hasta {{ \Carbon\Carbon::parse($comunicado->fecha_fin)->format('d/m/Y') }}
                            @endif
                        </p>
                        <p class="mb-0">
                            <i class="mdi mdi-eye"></i> {{ $comunicado->vistas }} vistas
                        </p>
                    </div>

                    <!-- Contenido -->
                    <div class="comunicado-contenido">
                        {!! $comunicado->contenido !!}
                    </div>

                    <!-- Archivo adjunto -->
                    @if($comunicado->archivo_url)
                    <div class="alert alert-info mt-4">
                        <i class="mdi mdi-file-document"></i>
                        <strong>Archivo adjunto:</strong>
                        <a href="{{ $comunicado->archivo_url }}" target="_blank" class="alert-link">
                            Descargar archivo <i class="mdi mdi-download"></i>
                        </a>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('extranet.comunicados.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>

                        <div>
                            @can('editar-comunicado')
                            <a href="{{ route('extranet.comunicados.edit', $comunicado->id) }}" class="btn btn-info">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                            @endcan

                            @can('fijar-comunicado')
                            <form action="{{ route('extranet.comunicados.fijar', $comunicado->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="mdi mdi-pin"></i> {{ $comunicado->fijado ? 'Desfijar' : 'Fijar' }}
                                </button>
                            </form>
                            @endcan

                            @can('archivar-comunicado')
                            @if($comunicado->estado !== 'archivado')
                            <form action="{{ route('extranet.comunicados.archivar', $comunicado->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-dark" onclick="return confirm('¿Archivar este comunicado?')">
                                    <i class="mdi mdi-archive"></i> Archivar
                                </button>
                            </form>
                            @endif
                            @endcan

                            @can('eliminar-comunicado')
                            <form action="{{ route('extranet.comunicados.destroy', $comunicado->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este comunicado permanentemente?')">
                                    <i class="mdi mdi-delete"></i> Eliminar
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.comunicado-contenido {
    font-size: 1.1rem;
    line-height: 1.8;
}

.comunicado-contenido img {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
}

.comunicado-contenido p {
    margin-bottom: 1rem;
}

.comunicado-contenido ul, .comunicado-contenido ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.comunicado-contenido h1, .comunicado-contenido h2, .comunicado-contenido h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}
</style>
@endsection
