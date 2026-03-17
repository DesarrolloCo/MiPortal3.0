@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.galeria.index') }}">Galería</a></li>
                    <li class="breadcrumb-item active">Crear Álbum</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="mdi mdi-image-album-plus"></i> Crear Nuevo Álbum de Fotos</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('extranet.galeria.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título del Álbum <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                                    <small class="form-text text-muted">Ej: Día de la Familia 2024, Capacitación ISO</small>
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
                                    <small class="form-text text-muted">Breve descripción del evento o actividad</small>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha">Fecha del Evento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha') is-invalid @enderror"
                                           id="fecha" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                                    @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Evento relacionado (opcional) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="evento_id">Evento Relacionado (opcional)</label>
                                    <select class="form-control @error('evento_id') is-invalid @enderror"
                                            id="evento_id" name="evento_id">
                                        <option value="">Ninguno</option>
                                        @if(isset($eventos))
                                        @foreach($eventos as $evento)
                                        <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                                            {{ $evento->titulo }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">Vincula este álbum a un evento específico</small>
                                    @error('evento_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            <strong>Nota:</strong> Después de crear el álbum podrás subir las fotos desde la opción "Subir Fotos".
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Crear Álbum
                            </button>
                            <a href="{{ route('extranet.galeria.index') }}" class="btn btn-secondary">
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
