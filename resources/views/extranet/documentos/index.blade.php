@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0"><i class="mdi mdi-file-document-multiple"></i> Repositorio de Documentos</h4>
                            <h6 class="card-subtitle">Políticas, manuales y documentos corporativos</h6>
                        </div>
                        <div>
                            @can('subir-documento')
                            <a href="{{ route('extranet.documentos.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-upload"></i> Subir Documento
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Sidebar de Categorías -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="mdi mdi-filter-variant"></i> Categorías</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('extranet.documentos.index') }}" class="list-group-item list-group-item-action {{ !request('categoria') ? 'active' : '' }}">
                        <i class="mdi mdi-all-inclusive"></i> Todos ({{ $documentos->total() }})
                    </a>
                    @foreach(['politicas', 'manuales', 'formatos', 'reglamentos', 'procedimientos', 'capacitacion', 'otro'] as $cat)
                    <a href="{{ route('extranet.documentos.index', ['categoria' => $cat]) }}"
                       class="list-group-item list-group-item-action {{ request('categoria') == $cat ? 'active' : '' }}">
                        <i class="mdi mdi-folder"></i> {{ ucfirst($cat) }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Documentos Destacados -->
            @if($destacados->count() > 0)
            <div class="card mt-3">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="mdi mdi-star"></i> Destacados</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($destacados->take(5) as $doc)
                    <a href="{{ route('extranet.documentos.show', $doc->id) }}" class="list-group-item list-group-item-action">
                        <small><i class="mdi mdi-file-pdf"></i> {{ Str::limit($doc->titulo, 30) }}</small>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Listado de Documentos -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <!-- Buscador -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="buscar" placeholder="Buscar documentos..." value="{{ request('buscar') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="mdi mdi-magnify"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($documentos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Categoría</th>
                                    <th>Versión</th>
                                    <th>Descargas</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentos as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="mdi mdi-file-pdf mdi-24px text-danger mr-2"></i>
                                            <div>
                                                <strong>{{ $doc->titulo }}</strong>
                                                @if($doc->destacado)
                                                <i class="mdi mdi-star text-warning"></i>
                                                @endif
                                                @if($doc->descripcion)
                                                <br><small class="text-muted">{{ Str::limit($doc->descripcion, 60) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-info">{{ ucfirst($doc->categoria) }}</span></td>
                                    <td>{{ $doc->version }}</td>
                                    <td>{{ $doc->descargas }}</td>
                                    <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('extranet.documentos.descargar', $doc->id) }}" class="btn btn-sm btn-success" title="Descargar">
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                        <a href="{{ route('extranet.documentos.show', $doc->id) }}" class="btn btn-sm btn-info" title="Ver">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @can('editar-documento')
                                        <a href="{{ route('extranet.documentos.edit', $doc->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $documentos->links() }}
                    @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-file-document-outline mdi-72px text-muted"></i>
                        <h5 class="mt-3">No se encontraron documentos</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
