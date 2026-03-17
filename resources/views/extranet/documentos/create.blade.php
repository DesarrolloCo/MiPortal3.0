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
                    <li class="breadcrumb-item active">Subir Documento</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="mdi mdi-upload"></i> Subir Nuevo Documento</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('extranet.documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título del Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo') }}" required>
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
                                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                    <small class="form-text text-muted">Breve descripción del contenido del documento</small>
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
                                        <option value="">Seleccionar...</option>
                                        <option value="politicas" {{ old('categoria') == 'politicas' ? 'selected' : '' }}>Políticas</option>
                                        <option value="manuales" {{ old('categoria') == 'manuales' ? 'selected' : '' }}>Manuales</option>
                                        <option value="formatos" {{ old('categoria') == 'formatos' ? 'selected' : '' }}>Formatos</option>
                                        <option value="reglamentos" {{ old('categoria') == 'reglamentos' ? 'selected' : '' }}>Reglamentos</option>
                                        <option value="procedimientos" {{ old('categoria') == 'procedimientos' ? 'selected' : '' }}>Procedimientos</option>
                                        <option value="capacitacion" {{ old('categoria') == 'capacitacion' ? 'selected' : '' }}>Capacitación</option>
                                        <option value="otro" {{ old('categoria') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                                           id="version" name="version" value="{{ old('version', '1.0') }}">
                                    <small class="form-text text-muted">Ej: 1.0, 2.1, etc.</small>
                                    @error('version')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Archivo -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="archivo">Archivo <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('archivo') is-invalid @enderror"
                                               id="archivo" name="archivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                                        <label class="custom-file-label" for="archivo">Seleccionar archivo...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Formatos permitidos: PDF, Word, Excel, PowerPoint. Tamaño máximo: 10 MB
                                    </small>
                                    @error('archivo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Destacado -->
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="destacado"
                                           name="destacado" value="1" {{ old('destacado') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="destacado">
                                        <i class="mdi mdi-star"></i> Marcar como destacado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-upload"></i> Subir Documento
                            </button>
                            <a href="{{ route('extranet.documentos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar nombre del archivo seleccionado
document.getElementById('archivo').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Seleccionar archivo...';
    const label = document.querySelector('.custom-file-label');
    label.textContent = fileName;
});
</script>
@endsection
