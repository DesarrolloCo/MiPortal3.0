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
        // Una encuesta está activa si su estado es 'activa' Y está dentro del rango de fechas
        $encuestasActivas = $encuestas->filter(function($encuesta) {
            return $encuesta->estado === 'activa'
                && now() >= $encuesta->fecha_inicio
                && (is_null($encuesta->fecha_fin) || now() <= $encuesta->fecha_fin);
        });

        $encuestasPendientes = $encuestas->where('estado', 'borrador');

        // Una encuesta está cerrada si su estado es 'cerrada' O si pasó su fecha límite
        $encuestasCerradas = $encuestas->filter(function($encuesta) {
            return $encuesta->estado === 'cerrada'
                || ($encuesta->estado === 'activa' && $encuesta->fecha_fin && now() > $encuesta->fecha_fin);
        });

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

        // SIEMPRE validar si ya respondió, independientemente si es anónima o no
        if ($empleado) {
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

        // Verificar que la encuesta esté activa y dentro del período válido
        if ($encuesta->estado !== 'activa') {
            return redirect()->route('extranet.encuestas.index')
                ->with('error', 'Esta encuesta ya no está disponible para responder.');
        }

        // Verificar que la encuesta esté dentro del período de respuesta
        if (now() < $encuesta->fecha_inicio) {
            return redirect()->route('extranet.encuestas.index')
                ->with('error', 'Esta encuesta aún no ha iniciado.');
        }

        if ($encuesta->fecha_fin && now() > $encuesta->fecha_fin) {
            return redirect()->route('extranet.encuestas.index')
                ->with('error', 'El período para responder esta encuesta ha finalizado.');
        }

        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return redirect()->route('extranet.encuestas.index')
                ->with('error', 'Debes tener un perfil de empleado para responder encuestas.');
        }

        // SIEMPRE verificar si ya respondió, independientemente si es anónima o no
        $yaRespondio = $encuesta->respuestas()
            ->where('empleado_id', $empleado->EMP_ID)
            ->exists();

        if ($yaRespondio) {
            return redirect()->route('extranet.encuestas.index')
                ->with('warning', 'Ya has respondido esta encuesta anteriormente. Solo puedes responder una vez.');
        }

        $respuestas = $request->input('respuestas', []);

        // Guardar respuestas
        // IMPORTANTE: Siempre guardamos el empleado_id para evitar respuestas duplicadas
        // El flag "anonima" solo afecta si se muestra el nombre en los resultados
        foreach ($respuestas as $pregunta_id => $respuesta) {
            // Para checkboxes, la respuesta es un array
            if (is_array($respuesta)) {
                $respuesta = json_encode($respuesta);
            }

            \App\Models\Extranet\RespuestaEncuesta::create([
                'encuesta_id' => $encuesta->id,
                'pregunta_id' => $pregunta_id,
                'empleado_id' => $empleado->EMP_ID, // Siempre guardamos el empleado_id
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
