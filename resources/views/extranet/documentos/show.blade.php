@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active">{{ $documento->titulo }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h3>{{ $documento->titulo }}</h3>
                        @if($documento->destacado)
                        <span class="badge badge-warning badge-pill"><i class="mdi mdi-star"></i> Destacado</span>
                        @endif
                    </div>

                    <div class="text-muted mb-3">
                        <span class="badge badge-info">{{ ucfirst($documento->categoria) }}</span>
                        <span class="ml-2"><i class="mdi mdi-calendar"></i> {{ \Carbon\Carbon::parse($documento->created_at)->format('d/m/Y') }}</span>
                        <span class="ml-2"><i class="mdi mdi-download"></i> {{ $documento->descargas }} descargas</span>
                        <span class="ml-2"><i class="mdi mdi-tag"></i> v{{ $documento->version }}</span>
                    </div>

                    @if($documento->descripcion)
                    <div class="alert alert-light">{{ $documento->descripcion }}</div>
                    @endif

                    <div class="bg-light p-4 text-center">
                        <i class="mdi mdi-file-pdf mdi-72px text-danger"></i>
                        <p class="mt-2"><strong>{{ $documento->archivo_nombre }}</strong></p>
                        <p class="text-muted">{{ number_format($documento->archivo_tamano / 1024 / 1024, 2) }} MB</p>

                        <a href="{{ route('extranet.documentos.descargar', $documento->id) }}" class="btn btn-success btn-lg">
                            <i class="mdi mdi-download"></i> Descargar Documento
                        </a>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('extranet.documentos.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Volver
                        </a>
                        @can('editar-documento')
                        <a href="{{ route('extranet.documentos.edit', $documento->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Editar
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
