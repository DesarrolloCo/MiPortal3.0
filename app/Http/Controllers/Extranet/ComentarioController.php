<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\ComentarioExtranet;
use App\Models\Extranet\PublicacionMuro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'publicacion_id' => 'required|exists:publicaciones_muro,id',
            'contenido' => 'required|string|max:1000',
            'comentario_padre_id' => 'nullable|exists:comentarios_extranet,id',
        ]);

        $validated['autor_id'] = Auth::id();

        $comentario = ComentarioExtranet::create($validated);

        // Incrementar contador en publicación
        $publicacion = PublicacionMuro::find($request->publicacion_id);
        $publicacion->increment('total_comentarios');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comentario' => $comentario->load('autor')
            ]);
        }

        return redirect()->back()
            ->with('success', 'Comentario agregado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $comentario = ComentarioExtranet::findOrFail($id);

        // Verificar que el usuario sea el autor
        if ($comentario->autor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $comentario->update($validated);

        return redirect()->back()
            ->with('success', 'Comentario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $comentario = ComentarioExtranet::findOrFail($id);

        // Verificar que el usuario sea el autor
        if ($comentario->autor_id !== Auth::id()) {
            abort(403);
        }

        // Decrementar contador en publicación
        $publicacion = PublicacionMuro::find($comentario->publicacion_id);
        $publicacion->decrement('total_comentarios');

        $comentario->delete();

        return redirect()->back()
            ->with('success', 'Comentario eliminado exitosamente.');
    }

    public function responder(Request $request, $id)
    {
        $comentarioPadre = ComentarioExtranet::findOrFail($id);

        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $validated['publicacion_id'] = $comentarioPadre->publicacion_id;
        $validated['comentario_padre_id'] = $comentarioPadre->id;
        $validated['autor_id'] = Auth::id();

        ComentarioExtranet::create($validated);

        return redirect()->back()
            ->with('success', 'Respuesta agregada exitosamente.');
    }
}
