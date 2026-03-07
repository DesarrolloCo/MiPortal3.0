@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.reconocimientos.index') }}">Reconocimientos</a></li>
                    <li class="breadcrumb-item active">Nuevo Reconocimiento</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-trophy-award"></i> Crear Nuevo Reconocimiento</h4>
                    <h6 class="card-subtitle mb-4">Celebre los logros de sus colaboradores</h6>

                    <form action="{{ route('extranet.reconocimientos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Empleado -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="empleado_id">Empleado Reconocido <span class="text-danger">*</span></label>
                                    <select class="form-control @error('empleado_id') is-invalid @enderror" id="empleado_id" name="empleado_id" required>
                                        <option value="">Seleccione un empleado</option>
                                        @foreach(\App\Models\empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get() as $emp)
                                        <option value="{{ $emp->EMP_ID }}" {{ old('empleado_id') == $emp->EMP_ID ? 'selected' : '' }}>
                                            {{ $emp->EMP_NOMBRES }} {{ $emp->EMP_APELLIDOS }} - {{ $emp->cargo->CAR_NOMBRE ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('empleado_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Título -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo">Título del Reconocimiento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                           id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="255"
                                           placeholder="Ej: Excelencia en atención al cliente">
                                    @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo de Reconocimiento <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                        <option value="empleado_mes" {{ old('tipo') == 'empleado_mes' ? 'selected' : '' }}>Empleado del Mes</option>
                                        <option value="aniversario" {{ old('tipo') == 'aniversario' ? 'selected' : '' }}>Aniversario</option>
                                        <option value="logro" {{ old('tipo') == 'logro' ? 'selected' : '' }}>Logro Destacado</option>
                                        <option value="excelencia" {{ old('tipo', 'excelencia') == 'excelencia' ? 'selected' : '' }}>Excelencia</option>
                                        <option value="innovacion" {{ old('tipo') == 'innovacion' ? 'selected' : '' }}>Innovación</option>
                                        <option value="trabajo_equipo" {{ old('tipo') == 'trabajo_equipo' ? 'selected' : '' }}>Trabajo en Equipo</option>
                                        <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha">Fecha del Reconocimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha') is-invalid @enderror"
                                           id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                    @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                              id="descripcion" name="descripcion" rows="5" required
                                              placeholder="Describa el motivo del reconocimiento, el logro alcanzado o el comportamiento destacado...">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Imagen -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="imagen">Imagen del Reconocimiento</label>
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
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="publico" name="publico" value="1" {{ old('publico', 1) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="publico">
                                            <i class="mdi mdi-earth"></i> Reconocimiento público (visible para todos)
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="destacado" name="destacado" value="1" {{ old('destacado') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="destacado">
                                            <i class="mdi mdi-star"></i> Destacar este reconocimiento
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('extranet.reconocimientos.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-cancel"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Crear Reconocimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
