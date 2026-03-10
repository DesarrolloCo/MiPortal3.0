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
        $encuestas = Encuesta::with('autor')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('extranet.encuestas.index', compact('encuestas'));
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

        $encuesta = Encuesta::create($validated);

        // Guardar preguntas (implementar lógica)

        return redirect()->route('extranet.encuestas.index')
            ->with('success', 'Encuesta creada exitosamente.');
    }

    public function show($id)
    {
        $encuesta = Encuesta::with(['preguntas', 'respuestas'])->findOrFail($id);

        return view('extranet.encuestas.show', compact('encuesta'));
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
        // Implementar lógica de guardar respuestas
        return redirect()->route('extranet.encuestas.show', $id)
            ->with('success', 'Respuesta guardada exitosamente.');
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
