<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestaController extends Controller
{
    public function index()
    {
        $encuestas = Encuesta::with(['autor', 'preguntas'])
            ->orderBy('created_at', 'DESC')
            ->get();

        // Separar encuestas por estado para las pestañas
        $encuestasActivas = $encuestas->where('estado', 'activa');
        $encuestasPendientes = $encuestas->where('estado', 'borrador');
        $encuestasCerradas = $encuestas->where('estado', 'cerrada');
        $borradores = $encuestas->where('estado', 'borrador');

        return view('extranet.encuestas.index', compact('encuestas', 'encuestasActivas', 'encuestasPendientes', 'encuestasCerradas', 'borradores'));
    }

    public function create()
    {
        return view('extranet.encuestas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'anonima' => 'boolean',
            'estado' => 'required|in:borrador,activa,cerrada',
        ]);

        $validated['autor_id'] = Auth::id();
        $validated['anonima'] = $request->has('anonima') ? 1 : 0;

        $encuesta = Encuesta::create($validated);

        // Guardar preguntas
        if ($request->has('preguntas')) {
            $preguntas = $request->input('preguntas');

            foreach ($preguntas as $orden => $preguntaData) {
                \App\Models\Extranet\PreguntaEncuesta::create([
                    'encuesta_id' => $encuesta->id,
                    'pregunta' => $preguntaData['pregunta'],
                    'tipo_respuesta' => $preguntaData['tipo_respuesta'],
                    'obligatoria' => isset($preguntaData['obligatoria']) ? 1 : 0,
                    'opciones' => $preguntaData['opciones'] ?? null,
                    'escala_min' => $preguntaData['escala_min'] ?? null,
                    'escala_max' => $preguntaData['escala_max'] ?? null,
                    'orden' => $orden,
                ]);
            }
        }

        return redirect()->route('extranet.encuestas.index')
            ->with('success', 'Encuesta creada exitosamente con ' . ($request->has('preguntas') ? count($request->input('preguntas')) : 0) . ' preguntas.');
    }

    public function show($id)
    {
        $encuesta = Encuesta::with(['preguntas', 'respuestas'])->findOrFail($id);

        // Verificar si el usuario ya respondió esta encuesta
        $empleado = Auth::user()->empleados;
        $yaRespondio = false;

        if ($empleado && !$encuesta->anonima) {
            $yaRespondio = $encuesta->respuestas()
                ->where('empleado_id', $empleado->EMP_ID)
                ->exists();
        }

        return view('extranet.encuestas.show', compact('encuesta', 'yaRespondio'));
    }

    public function edit($id)
    {
        $encuesta = Encuesta::with('preguntas')->findOrFail($id);

        return view('extranet.encuestas.edit', compact('encuesta'));
    }

    public function update(Request $request, $id)
    {
        $encuesta = Encuesta::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'anonima' => 'boolean',
            'estado' => 'required|in:borrador,activa,cerrada',
        ]);

        $encuesta->update($validated);

        return redirect()->route('extranet.encuestas.index')
            ->with('success', 'Encuesta actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->delete();

        return redirect()->route('extranet.encuestas.index')
            ->with('success', 'Encuesta eliminada exitosamente.');
    }

    public function responder(Request $request, $id)
    {
        $encuesta = Encuesta::with('preguntas')->findOrFail($id);

        // Verificar que la encuesta esté activa
        if ($encuesta->estado !== 'activa') {
            return redirect()->route('extranet.encuestas.index')
                ->with('error', 'Esta encuesta ya no está disponible para responder.');
        }

        $empleado = Auth::user()->empleados;

        // Verificar si ya respondió (solo para encuestas no anónimas)
        if ($empleado && !$encuesta->anonima) {
            $yaRespondio = $encuesta->respuestas()
                ->where('empleado_id', $empleado->EMP_ID)
                ->exists();

            if ($yaRespondio) {
                return redirect()->route('extranet.encuestas.index')
                    ->with('warning', 'Ya has respondido esta encuesta anteriormente.');
            }
        }

        $respuestas = $request->input('respuestas', []);

        // Guardar respuestas
        foreach ($respuestas as $pregunta_id => $respuesta) {
            // Para checkboxes, la respuesta es un array
            if (is_array($respuesta)) {
                $respuesta = json_encode($respuesta);
            }

            \App\Models\Extranet\RespuestaEncuesta::create([
                'encuesta_id' => $encuesta->id,
                'pregunta_id' => $pregunta_id,
                'empleado_id' => $encuesta->anonima ? null : ($empleado ? $empleado->EMP_ID : null),
                'respuesta' => $respuesta,
            ]);
        }

        // Incrementar contador de respuestas
        $encuesta->incrementarRespuestas();

        return redirect()->route('extranet.encuestas.index')
            ->with('success', '¡Gracias por tu participación! Tu respuesta ha sido registrada exitosamente.');
    }

    public function resultados($id)
    {
        $encuesta = Encuesta::with(['preguntas.respuestas'])->findOrFail($id);

        return view('extranet.encuestas.resultados', compact('encuesta'));
    }

    public function exportarExcel($id)
    {
        // Implementar exportación a Excel
    }
}
