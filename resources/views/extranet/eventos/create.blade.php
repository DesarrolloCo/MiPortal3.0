@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.eventos.index') }}">Eventos</a></li>
                    <li class="breadcrumb-item active">Nuevo Evento</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-calendar-plus"></i> Nuevo Evento</h4>
                    <h6 class="card-subtitle mb-4">Complete el formulario para crear un nuevo evento</h6>

                    <form action="{{ route('extranet.eventos.store') }}" method="POST" enctype="multipart/form-data">
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

                            <!-- Tipo y Modalidad -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                        <option value="reunion" {{ old('tipo') == 'reunion' ? 'selected' : '' }}>Reunión</option>
                                        <option value="capacitacion" {{ old('tipo') == 'capacitacion' ? 'selected' : '' }}>Capacitación</option>
                                        <option value="celebracion" {{ old('tipo') == 'celebracion' ? 'selected' : '' }}>Celebración</option>
                                        <option value="conferencia" {{ old('tipo') == 'conferencia' ? 'selected' : '' }}>Conferencia</option>
                                        <option value="team_building" {{ old('tipo') == 'team_building' ? 'selected' : '' }}>Team Building</option>
                                        <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="modalidad">Modalidad <span class="text-danger">*</span></label>
                                    <select class="form-control @error('modalidad') is-invalid @enderror" id="modalidad" name="modalidad" required>
                                        <option value="presencial" {{ old('modalidad', 'presencial') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                                        <option value="virtual" {{ old('modalidad') == 'virtual' ? 'selected' : '' }}>Virtual</option>
                                        <option value="hibrido" {{ old('modalidad') == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                                    </select>
                                    @error('modalidad')
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

                            <!-- Fechas y Horas -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                           id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                                    @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hora_inicio">Hora de Inicio <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                           id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio', '09:00') }}" required>
                                    @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Fin</label>
                                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                           id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                                    @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="hora_fin">Hora de Fin</label>
                                    <input type="time" class="form-control @error('hora_fin') is-invalid @enderror"
                                           id="hora_fin" name="hora_fin" value="{{ old('hora_fin') }}">
                                    @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                              id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lugar (para presencial o híbrido) -->
                            <div class="col-md-6" id="campo_lugar">
                                <div class="form-group">
                                    <label for="lugar">Lugar</label>
                                    <input type="text" class="form-control @error('lugar') is-invalid @enderror"
                                           id="lugar" name="lugar" value="{{ old('lugar') }}" maxlength="255">
                                    @error('lugar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Link virtual (para virtual o híbrido) -->
                            <div class="col-md-6" id="campo_link" style="display: none;">
                                <div class="form-group">
                                    <label for="link_virtual">Link de Reunión Virtual</label>
                                    <input type="url" class="form-control @error('link_virtual') is-invalid @enderror"
                                           id="link_virtual" name="link_virtual" value="{{ old('link_virtual') }}" maxlength="500"
                                           placeholder="https://meet.google.com/abc-defg-hij">
                                    @error('link_virtual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cupo y confirmación -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cupo_maximo">Cupo Máximo</label>
                                    <input type="number" class="form-control @error('cupo_maximo') is-invalid @enderror"
                                           id="cupo_maximo" name="cupo_maximo" value="{{ old('cupo_maximo') }}" min="1">
                                    <small class="form-text text-muted">Dejar vacío si no hay límite de cupo</small>
                                    @error('cupo_maximo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="color">Color del Evento</label>
                                    <input type="color" class="form-control @error('color') is-invalid @enderror"
                                           id="color" name="color" value="{{ old('color', '#007bff') }}">
                                    @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Imagen -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="imagen">Imagen del Evento</label>
                                    <input type="file" class="form-control-file @error('imagen') is-invalid @enderror"
                                           id="imagen" name="imagen" accept="image/*">
                                    <small class="form-text text-muted">Tamaño máximo: 5MB. Formatos: JPG, PNG, GIF</small>
                                    @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Checkboxes -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="requiere_confirmacion" name="requiere_confirmacion" value="1" {{ old('requiere_confirmacion') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requiere_confirmacion">
                                            <i class="mdi mdi-account-check"></i> Requiere confirmación de asistencia
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('extranet.eventos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-cancel"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('modalidad').addEventListener('change', function() {
    const modalidad = this.value;
    const campoLugar = document.getElementById('campo_lugar');
    const campoLink = document.getElementById('campo_link');

    if (modalidad === 'presencial') {
        campoLugar.style.display = 'block';
        campoLink.style.display = 'none';
    } else if (modalidad === 'virtual') {
        campoLugar.style.display = 'none';
        campoLink.style.display = 'block';
    } else if (modalidad === 'hibrido') {
        campoLugar.style.display = 'block';
        campoLink.style.display = 'block';
    }
});

// Ejecutar al cargar la página
document.getElementById('modalidad').dispatchEvent(new Event('change'));
</script>
@endsection
