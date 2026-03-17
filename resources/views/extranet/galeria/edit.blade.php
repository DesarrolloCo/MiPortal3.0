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
                    <li class="breadcrumb-item"><a href="{{ route('extranet.galeria.show', $galeria->id) }}">{{ $galeria->titulo }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="mdi mdi-pencil"></i> Editar Álbum</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('extranet.galeria.update', $galeria->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título del Álbum <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo', $galeria->titulo) }}" required>
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
                                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $galeria->descripcion) }}</textarea>
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
                                           id="fecha" name="fecha" value="{{ old('fecha', $galeria->fecha->format('Y-m-d')) }}" required>
                                    @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Evento relacionado -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="evento_id">Evento Relacionado</label>
                                    <select class="form-control @error('evento_id') is-invalid @enderror"
                                            id="evento_id" name="evento_id">
                                        <option value="">Ninguno</option>
                                        @if(isset($eventos))
                                        @foreach($eventos as $evento)
                                        <option value="{{ $evento->id }}" {{ old('evento_id', $galeria->evento_id) == $evento->id ? 'selected' : '' }}>
                                            {{ $evento->titulo }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('evento_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información del álbum -->
                            <div class="col-md-12">
                                <div class="alert alert-light">
                                    <i class="mdi mdi-information"></i>
                                    <strong>Total de fotos:</strong> {{ $galeria->fotos->count() }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Actualizar Álbum
                            </button>
                            <a href="{{ route('extranet.galeria.show', $galeria->id) }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Cancelar
                            </a>
                            @can('eliminar-album')
                            <button type="button" class="btn btn-danger float-right" onclick="confirmarEliminar()">
                                <i class="mdi mdi-delete"></i> Eliminar Álbum
                            </button>
                            @endcan
                        </div>
                    </form>

                    @can('eliminar-album')
                    <form id="formEliminar" action="{{ route('extranet.galeria.destroy', $galeria->id) }}" method="POST" style="display: none;">
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
    if (confirm('¿Estás seguro de eliminar este álbum y todas sus fotos? Esta acción no se puede deshacer.')) {
        document.getElementById('formEliminar').submit();
    }
}
</script>
@endsection
