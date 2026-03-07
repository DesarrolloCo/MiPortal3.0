@extends('layouts.main')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.proyectos.index') }}">Proyectos</a></li>
                    <li class="breadcrumb-item active">Editar: {{ $proyecto->nombre }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><i class="mdi mdi-pencil"></i> Editar Proyecto</h4>
                    <h6 class="card-subtitle mb-4">Modifique los campos necesarios</h6>

                    <form action="{{ route('extranet.proyectos.update', $proyecto->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nombre del Proyecto -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Proyecto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                           id="nombre" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required maxlength="255">
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                              id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Objetivo -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="objetivo">Objetivo del Proyecto</label>
                                    <textarea class="form-control @error('objetivo') is-invalid @enderror"
                                              id="objetivo" name="objetivo" rows="3">{{ old('objetivo', $proyecto->objetivo) }}</textarea>
                                    @error('objetivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Responsable -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="responsable_id">Responsable del Proyecto</label>
                                    <select class="form-control @error('responsable_id') is-invalid @enderror" id="responsable_id" name="responsable_id">
                                        <option value="">Sin asignar</option>
                                        @foreach(\App\Models\empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get() as $emp)
                                        <option value="{{ $emp->EMP_ID }}" {{ old('responsable_id', $proyecto->responsable_id) == $emp->EMP_ID ? 'selected' : '' }}>
                                            {{ $emp->EMP_NOMBRES }} {{ $emp->EMP_APELLIDOS }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('responsable_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="departamento_id">Departamento</label>
                                    <select class="form-control @error('departamento_id') is-invalid @enderror" id="departamento_id" name="departamento_id">
                                        <option value="">No aplica</option>
                                        @foreach(\App\Models\departamento::orderBy('DEP_NOMBRE')->get() as $dep)
                                        <option value="{{ $dep->DEP_ID }}" {{ old('departamento_id', $proyecto->departamento_id) == $dep->DEP_ID ? 'selected' : '' }}>
                                            {{ $dep->DEP_NOMBRE }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('departamento_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado y Prioridad -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                        <option value="planificacion" {{ old('estado', $proyecto->estado) == 'planificacion' ? 'selected' : '' }}>Planificación</option>
                                        <option value="en_progreso" {{ old('estado', $proyecto->estado) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                        <option value="pausado" {{ old('estado', $proyecto->estado) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                                        <option value="completado" {{ old('estado', $proyecto->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                        <option value="cancelado" {{ old('estado', $proyecto->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prioridad">Prioridad <span class="text-danger">*</span></label>
                                    <select class="form-control @error('prioridad') is-invalid @enderror" id="prioridad" name="prioridad" required>
                                        <option value="baja" {{ old('prioridad', $proyecto->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                                        <option value="media" {{ old('prioridad', $proyecto->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                                        <option value="alta" {{ old('prioridad', $proyecto->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="critica" {{ old('prioridad', $proyecto->prioridad) == 'critica' ? 'selected' : '' }}>Crítica</option>
                                    </select>
                                    @error('prioridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="progreso">Progreso (%)</label>
                                    <input type="number" class="form-control @error('progreso') is-invalid @enderror"
                                           id="progreso" name="progreso" value="{{ old('progreso', $proyecto->progreso) }}" min="0" max="100">
                                    <small class="form-text text-muted">Se actualiza automáticamente según tareas</small>
                                    @error('progreso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                           id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('Y-m-d')) }}" required>
                                    @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Fin Estimada</label>
                                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                           id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('Y-m-d') : '') }}">
                                    @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Presupuesto y Horas -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="presupuesto">Presupuesto (opcional)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" class="form-control @error('presupuesto') is-invalid @enderror"
                                               id="presupuesto" name="presupuesto" value="{{ old('presupuesto', $proyecto->presupuesto) }}" min="0" step="0.01">
                                    </div>
                                    @error('presupuesto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horas_estimadas">Horas Estimadas</label>
                                    <input type="number" class="form-control @error('horas_estimadas') is-invalid @enderror"
                                           id="horas_estimadas" name="horas_estimadas" value="{{ old('horas_estimadas', $proyecto->horas_estimadas) }}" min="0">
                                    @error('horas_estimadas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('extranet.proyectos.show', $proyecto->id) }}" class="btn btn-secondary">
                                <i class="mdi mdi-cancel"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Actualizar Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
