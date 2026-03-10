@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.encuestas.index') }}">Encuestas</a></li>
                    <li class="breadcrumb-item active">Nueva Encuesta</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <i class="mdi mdi-poll-box-plus"></i> Crear Nueva Encuesta
                    </h4>
                    <h6 class="card-subtitle mb-4">Diseña una encuesta personalizada para tus colaboradores</h6>

                    <form action="{{ route('extranet.encuestas.store') }}" method="POST" id="formEncuesta">
                        @csrf

                        <!-- Información General -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="mdi mdi-information"></i> Información General</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Título -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="titulo">Título de la Encuesta <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                                   id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="255">
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
                                            <small class="form-text text-muted">Explica el objetivo de la encuesta</small>
                                            @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Fechas -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                                   id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', now()->format('Y-m-d\TH:i')) }}" required>
                                            @error('fecha_inicio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_fin">Fecha de Cierre</label>
                                            <input type="datetime-local" class="form-control @error('fecha_fin') is-invalid @enderror"
                                                   id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                                            <small class="form-text text-muted">Dejar vacío para encuesta sin fecha límite</small>
                                            @error('fecha_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Opciones -->
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="anonima" name="anonima" value="1" {{ old('anonima', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="anonima">
                                                <i class="mdi mdi-incognito"></i> Encuesta Anónima
                                            </label>
                                            <small class="form-text text-muted">Las respuestas no se vincularán a nombres de empleados</small>
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado">
                                                <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                <option value="activa" {{ old('estado') == 'activa' ? 'selected' : '' }}>Activa (publicar ahora)</option>
                                            </select>
                                            @error('estado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preguntas -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="mdi mdi-format-list-bulleted"></i> Preguntas</h5>
                                <button type="button" class="btn btn-light btn-sm" onclick="agregarPregunta()">
                                    <i class="mdi mdi-plus"></i> Agregar Pregunta
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="contenedorPreguntas">
                                    <!-- Las preguntas se agregarán dinámicamente aquí -->
                                </div>

                                <div id="mensajeSinPreguntas" class="text-center text-muted py-4">
                                    <i class="mdi mdi-help-circle-outline mdi-48px"></i>
                                    <p class="mt-2">Aún no has agregado preguntas</p>
                                    <button type="button" class="btn btn-success" onclick="agregarPregunta()">
                                        <i class="mdi mdi-plus"></i> Agregar Primera Pregunta
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Guardar Encuesta
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

<!-- Template de Pregunta -->
<template id="templatePregunta">
    <div class="pregunta-item card mb-3" data-pregunta-index="">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span class="numero-pregunta font-weight-bold">Pregunta #</span>
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarPregunta(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Texto de la Pregunta -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Pregunta <span class="text-danger">*</span></label>
                        <textarea class="form-control pregunta-texto" name="preguntas[INDEX][pregunta]" rows="2" required></textarea>
                    </div>
                </div>

                <!-- Tipo de Respuesta -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo de Respuesta <span class="text-danger">*</span></label>
                        <select class="form-control tipo-respuesta" name="preguntas[INDEX][tipo_respuesta]" onchange="cambiarTipoRespuesta(this)" required>
                            <option value="texto_corto">Texto Corto</option>
                            <option value="texto_largo">Texto Largo</option>
                            <option value="opcion_multiple">Opción Múltiple</option>
                            <option value="checkbox">Casillas de Verificación</option>
                            <option value="escala">Escala Numérica</option>
                            <option value="fecha">Fecha</option>
                        </select>
                    </div>
                </div>

                <!-- Obligatoria -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Opciones</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="preguntas[INDEX][obligatoria]" value="1" checked>
                            <label class="custom-control-label">Pregunta Obligatoria</label>
                        </div>
                    </div>
                </div>

                <!-- Opciones (para opción múltiple/checkbox) -->
                <div class="col-md-12 opciones-container" style="display: none;">
                    <div class="form-group">
                        <label>Opciones de Respuesta</label>
                        <div class="lista-opciones">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" placeholder="Opción 1">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" onclick="agregarOpcion(this)">
                                        <i class="mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="opciones-json" name="preguntas[INDEX][opciones]">
                    </div>
                </div>

                <!-- Escala (para tipo escala) -->
                <div class="col-md-12 escala-container" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor Mínimo</label>
                                <input type="number" class="form-control" name="preguntas[INDEX][escala_min]" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor Máximo</label>
                                <input type="number" class="form-control" name="preguntas[INDEX][escala_max]" value="5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let contadorPreguntas = 0;

function agregarPregunta() {
    contadorPreguntas++;

    const template = document.getElementById('templatePregunta');
    const clone = template.content.cloneNode(true);

    // Actualizar índice
    const preguntaDiv = clone.querySelector('.pregunta-item');
    preguntaDiv.dataset.preguntaIndex = contadorPreguntas;

    // Actualizar número de pregunta
    clone.querySelector('.numero-pregunta').textContent = `Pregunta #${contadorPreguntas}`;

    // Reemplazar INDEX en todos los names
    const inputs = clone.querySelectorAll('[name*="INDEX"]');
    inputs.forEach(input => {
        input.name = input.name.replace('INDEX', contadorPreguntas);
        input.id = input.name;
    });

    // Agregar al contenedor
    document.getElementById('contenedorPreguntas').appendChild(clone);

    // Ocultar mensaje
    document.getElementById('mensajeSinPreguntas').style.display = 'none';

    // Actualizar checkboxes con IDs únicos
    actualizarCheckboxIds();
}

function eliminarPregunta(btn) {
    if (confirm('¿Eliminar esta pregunta?')) {
        btn.closest('.pregunta-item').remove();
        renumerarPreguntas();

        // Mostrar mensaje si no hay preguntas
        if (document.querySelectorAll('.pregunta-item').length === 0) {
            document.getElementById('mensajeSinPreguntas').style.display = 'block';
        }
    }
}

function renumerarPreguntas() {
    const preguntas = document.querySelectorAll('.pregunta-item');
    preguntas.forEach((pregunta, index) => {
        pregunta.querySelector('.numero-pregunta').textContent = `Pregunta #${index + 1}`;
    });
}

function cambiarTipoRespuesta(select) {
    const card = select.closest('.card-body');
    const opcionesContainer = card.querySelector('.opciones-container');
    const escalaContainer = card.querySelector('.escala-container');

    // Ocultar todos
    opcionesContainer.style.display = 'none';
    escalaContainer.style.display = 'none';

    // Mostrar según tipo
    const tipo = select.value;
    if (tipo === 'opcion_multiple' || tipo === 'checkbox') {
        opcionesContainer.style.display = 'block';
    } else if (tipo === 'escala') {
        escalaContainer.style.display = 'block';
    }
}

function agregarOpcion(btn) {
    const listaOpciones = btn.closest('.lista-opciones');
    const numOpciones = listaOpciones.querySelectorAll('.input-group').length + 1;

    const nuevaOpcion = document.createElement('div');
    nuevaOpcion.className = 'input-group mb-2';
    nuevaOpcion.innerHTML = `
        <input type="text" class="form-control" placeholder="Opción ${numOpciones}">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" onclick="eliminarOpcion(this)">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    `;

    listaOpciones.appendChild(nuevaOpcion);
}

function eliminarOpcion(btn) {
    btn.closest('.input-group').remove();
}

function actualizarCheckboxIds() {
    const checkboxes = document.querySelectorAll('.pregunta-item input[type="checkbox"]');
    checkboxes.forEach((checkbox, index) => {
        const uniqueId = `checkbox_${Date.now()}_${index}`;
        checkbox.id = uniqueId;
        const label = checkbox.nextElementSibling;
        if (label && label.tagName === 'LABEL') {
            label.setAttribute('for', uniqueId);
        }
    });
}

// Antes de enviar el formulario, recopilar opciones
document.getElementById('formEncuesta').addEventListener('submit', function(e) {
    const preguntasItems = document.querySelectorAll('.pregunta-item');

    preguntasItems.forEach(item => {
        const tipoRespuesta = item.querySelector('.tipo-respuesta').value;

        if (tipoRespuesta === 'opcion_multiple' || tipoRespuesta === 'checkbox') {
            const opcionesInputs = item.querySelectorAll('.lista-opciones input[type="text"]');
            const opciones = Array.from(opcionesInputs)
                .map(input => input.value.trim())
                .filter(val => val !== '');

            const opcionesJson = item.querySelector('.opciones-json');
            opcionesJson.value = JSON.stringify(opciones);
        }
    });
});

// Agregar primera pregunta automáticamente
document.addEventListener('DOMContentLoaded', function() {
    // No agregar pregunta automáticamente, dejar que el usuario lo haga
});
</script>

<style>
.pregunta-item {
    transition: all 0.3s ease;
}

.pregunta-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.numero-pregunta {
    font-size: 1.1rem;
    color: #007bff;
}
</style>
@endsection
