@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.comunicados.index') }}">Comunicados</a></li>
                    <li class="breadcrumb-item active">Nuevo Comunicado</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-bullhorn"></i> Nuevo Comunicado</h4>
                    <h6 class="card-subtitle mb-4">Complete el formulario para crear un nuevo comunicado</h6>

                    <form action="{{ route('extranet.comunicados.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="255">
                                    @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo y Prioridad -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                        <option value="general" {{ old('tipo') == 'general' ? 'selected' : '' }}>General</option>
                                        <option value="urgente" {{ old('tipo') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                        <option value="rh" {{ old('tipo') == 'rh' ? 'selected' : '' }}>RH</option>
                                        <option value="ti" {{ old('tipo') == 'ti' ? 'selected' : '' }}>TI</option>
                                        <option value="operaciones" {{ old('tipo') == 'operaciones' ? 'selected' : '' }}>Operaciones</option>
                                        <option value="admin" {{ old('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prioridad">Prioridad <span class="text-danger">*</span></label>
                                    <select class="form-control @error('prioridad') is-invalid @enderror" id="prioridad" name="prioridad" required>
                                        <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                                        <option value="media" {{ old('prioridad', 'media') == 'media' ? 'selected' : '' }}>Media</option>
                                        <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="critica" {{ old('prioridad') == 'critica' ? 'selected' : '' }}>Crítica</option>
                                    </select>
                                    @error('prioridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                        <option value="borrador" {{ old('estado', 'borrador') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                        <option value="publicado" {{ old('estado') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                    </select>
                                    @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                           id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                                    @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Fin (Opcional)</label>
                                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                           id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                                    @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contenido">Contenido <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('contenido') is-invalid @enderror"
                                              id="contenido" name="contenido" rows="10" required>{{ old('contenido') }}</textarea>
                                    @error('contenido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Archivos -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imagen">Imagen Destacada (Opcional)</label>
                                    <input type="file" class="form-control-file @error('imagen') is-invalid @enderror"
                                           id="imagen" name="imagen" accept="image/*">
                                    <small class="form-text text-muted">Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF</small>
                                    @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="archivo">Archivo Adjunto (Opcional)</label>
                                    <input type="file" class="form-control-file @error('archivo') is-invalid @enderror"
                                           id="archivo" name="archivo">
                                    <small class="form-text text-muted">Tamaño máximo: 10MB</small>
                                    @error('archivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fijado -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="fijado" name="fijado" value="1" {{ old('fijado') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="fijado">
                                            <i class="mdi mdi-pin"></i> Fijar este comunicado en el dashboard
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('extranet.comunicados.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-cancel"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Guardar Comunicado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
