@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.encuestas.index') }}">Encuestas</a></li>
                    <li class="breadcrumb-item active">Editar Encuesta</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-pencil"></i> Editar Encuesta</h4>
                    <h6 class="card-subtitle mb-4">Modifica la información de la encuesta</h6>

                    <form action="{{ route('extranet.encuestas.update', $encuesta->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="titulo" name="titulo"
                                           value="{{ old('titulo', $encuesta->titulo) }}" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $encuesta->descripcion) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                           value="{{ old('fecha_inicio', $encuesta->fecha_inicio ? date('Y-m-d\TH:i', strtotime($encuesta->fecha_inicio)) : '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Cierre</label>
                                    <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin"
                                           value="{{ old('fecha_fin', $encuesta->fecha_fin ? date('Y-m-d\TH:i', strtotime($encuesta->fecha_fin)) : '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="anonima" name="anonima" value="1"
                                           {{ old('anonima', $encuesta->anonima) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="anonima">Encuesta Anónima</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select class="form-control" id="estado" name="estado">
                                        <option value="borrador" {{ old('estado', $encuesta->estado) == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                        <option value="activa" {{ old('estado', $encuesta->estado) == 'activa' ? 'selected' : '' }}>Activa</option>
                                        <option value="cerrada" {{ old('estado', $encuesta->estado) == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            <strong>Nota:</strong> No se pueden modificar las preguntas de una encuesta que ya tiene respuestas.
                            Actualmente tiene {{ $encuesta->total_respuestas }} respuestas.
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Actualizar Encuesta
                            </button>
                            <a href="{{ route('extranet.encuestas.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
