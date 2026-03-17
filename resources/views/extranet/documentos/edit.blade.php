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
                    <li class="breadcrumb-item active">Editar Documento</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="mdi mdi-pencil"></i> Editar Documento</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('extranet.documentos.update', $documento->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título del Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo', $documento->titulo) }}" required>
                                    @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $documento->descripcion) }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Categoría -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría <span class="text-danger">*</span></label>
                                    <select class="form-control @error('categoria') is-invalid @enderror"
                                            id="categoria" name="categoria" required>
                                        <option value="politicas" {{ old('categoria', $documento->categoria) == 'politicas' ? 'selected' : '' }}>Políticas</option>
                                        <option value="manuales" {{ old('categoria', $documento->categoria) == 'manuales' ? 'selected' : '' }}>Manuales</option>
                                        <option value="formatos" {{ old('categoria', $documento->categoria) == 'formatos' ? 'selected' : '' }}>Formatos</option>
                                        <option value="reglamentos" {{ old('categoria', $documento->categoria) == 'reglamentos' ? 'selected' : '' }}>Reglamentos</option>
                                        <option value="procedimientos" {{ old('categoria', $documento->categoria) == 'procedimientos' ? 'selected' : '' }}>Procedimientos</option>
                                        <option value="capacitacion" {{ old('categoria', $documento->categoria) == 'capacitacion' ? 'selected' : '' }}>Capacitación</option>
                                        <option value="otro" {{ old('categoria', $documento->categoria) == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Versión -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="version">Versión</label>
                                    <input type="text" class="form-control @error('version') is-invalid @enderror"
                                           id="version" name="version" value="{{ old('version', $documento->version) }}">
                                    @error('version')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Archivo Actual -->
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="mdi mdi-file-pdf"></i>
                                    <strong>Archivo actual:</strong> {{ $documento->archivo_nombre }}
                                    ({{ number_format($documento->archivo_tamano / 1024 / 1024, 2) }} MB)
                                </div>
                            </div>

                            <!-- Destacado -->
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="destacado"
                                           name="destacado" value="1" {{ old('destacado', $documento->destacado) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="destacado">
                                        <i class="mdi mdi-star"></i> Marcar como destacado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Actualizar Documento
                            </button>
                            <a href="{{ route('extranet.documentos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Cancelar
                            </a>
                            @can('eliminar-documento')
                            <button type="button" class="btn btn-danger float-right" onclick="confirmarEliminar()">
                                <i class="mdi mdi-delete"></i> Eliminar Documento
                            </button>
                            @endcan
                        </div>
                    </form>

                    @can('eliminar-documento')
                    <form id="formEliminar" action="{{ route('extranet.documentos.destroy', $documento->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar() {
    if (confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
        document.getElementById('formEliminar').submit();
    }
}
</script>
@endsection
