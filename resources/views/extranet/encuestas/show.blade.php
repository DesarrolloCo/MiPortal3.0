@extends('layouts.main')

@section('main')
<div class="container-fluid">
    @include('extranet.partials.flash-messages')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('extranet.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('extranet.encuestas.index') }}">Encuestas</a></li>
                    <li class="breadcrumb-item active">{{ $encuesta->titulo }}</li>
                </ol>
            </nav>

            @if($yaRespondio)
            <!-- Ya respondió -->
            <div class="card border-success">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-check-circle-outline mdi-72px text-success"></i>
                    <h4 class="mt-3">¡Gracias por tu participación!</h4>
                    <p class="text-muted">Ya has respondido esta encuesta</p>

                    @can('ver-resultados-encuesta')
                    <a href="{{ route('extranet.encuestas.resultados', $encuesta->id) }}" class="btn btn-primary mt-2">
                        <i class="mdi mdi-chart-bar"></i> Ver Resultados
                    </a>
                    @endcan

                    <a href="{{ route('extranet.encuestas.index') }}" class="btn btn-secondary mt-2">
                        <i class="mdi mdi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            @else
            <!-- Formulario de Encuesta -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $encuesta->titulo }}</h4>
                    @if($encuesta->anonima)
                    <small><i class="mdi mdi-incognito"></i> Esta encuesta es anónima - Tu identidad no será revelada en los resultados</small>
                    @endif
                    <small class="d-block mt-1"><i class="mdi mdi-information"></i> Solo puedes responder esta encuesta una vez</small>
                </div>
                <div class="card-body">
                    @if($encuesta->descripcion)
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        {{ $encuesta->descripcion }}
                    </div>
                    @endif

                    <form action="{{ route('extranet.encuestas.responder', $encuesta->id) }}" method="POST">
                        @csrf

                        @foreach($encuesta->preguntas->sortBy('orden') as $index => $pregunta)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>{{ $index + 1 }}. {{ $pregunta->pregunta }}</strong>
                                @if($pregunta->obligatoria)
                                <span class="text-danger">*</span>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($pregunta->tipo_respuesta == 'texto_corto')
                                <input type="text" class="form-control" name="respuestas[{{ $pregunta->id }}]" {{ $pregunta->obligatoria ? 'required' : '' }}>

                                @elseif($pregunta->tipo_respuesta == 'texto_largo')
                                <textarea class="form-control" name="respuestas[{{ $pregunta->id }}]" rows="4" {{ $pregunta->obligatoria ? 'required' : '' }}></textarea>

                                @elseif($pregunta->tipo_respuesta == 'opcion_multiple')
                                @php $opciones = json_decode($pregunta->opciones, true) ?? []; @endphp
                                @foreach($opciones as $opcion)
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="p{{ $pregunta->id }}_{{ $loop->index }}"
                                           name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion }}" {{ $pregunta->obligatoria ? 'required' : '' }}>
                                    <label class="custom-control-label" for="p{{ $pregunta->id }}_{{ $loop->index }}">{{ $opcion }}</label>
                                </div>
                                @endforeach

                                @elseif($pregunta->tipo_respuesta == 'checkbox')
                                @php $opciones = json_decode($pregunta->opciones, true) ?? []; @endphp
                                @foreach($opciones as $opcion)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="p{{ $pregunta->id }}_{{ $loop->index }}"
                                           name="respuestas[{{ $pregunta->id }}][]" value="{{ $opcion }}">
                                    <label class="custom-control-label" for="p{{ $pregunta->id }}_{{ $loop->index }}">{{ $opcion }}</label>
                                </div>
                                @endforeach

                                @elseif($pregunta->tipo_respuesta == 'escala')
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $pregunta->escala_min }}</span>
                                    @for($i = $pregunta->escala_min; $i <= $pregunta->escala_max; $i++)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="p{{ $pregunta->id }}_{{ $i }}"
                                               name="respuestas[{{ $pregunta->id }}]" value="{{ $i }}" {{ $pregunta->obligatoria ? 'required' : '' }}>
                                        <label class="custom-control-label" for="p{{ $pregunta->id }}_{{ $i }}">{{ $i }}</label>
                                    </div>
                                    @endfor
                                    <span>{{ $pregunta->escala_max }}</span>
                                </div>

                                @elseif($pregunta->tipo_respuesta == 'fecha')
                                <input type="date" class="form-control" name="respuestas[{{ $pregunta->id }}]" {{ $pregunta->obligatoria ? 'required' : '' }}>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-send"></i> Enviar Respuestas
                            </button>
                            <a href="{{ route('extranet.encuestas.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
