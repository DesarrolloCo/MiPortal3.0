<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\ReaccionExtranet;
use App\Models\Extranet\PublicacionMuro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReaccionController extends Controller
{
    public function toggle(Request $request, $id)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:like,love,haha,wow,sad,angry',
        ]);

        $publicacion = PublicacionMuro::findOrFail($id);

        // Buscar reacción existente del usuario
        $reaccion = ReaccionExtranet::where('reaccionable_type', 'publicaciones_muro')
            ->where('reaccionable_id', $id)
            ->where('autor_id', Auth::id())
            ->first();

        if ($reaccion) {
            if ($reaccion->tipo === $validated['tipo']) {
                // Eliminar reacción (toggle off)
                $reaccion->delete();
                $publicacion->decrement('total_reacciones');

                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'count' => $publicacion->fresh()->total_reacciones
                ]);
            } else {
                // Cambiar tipo de reacción
                $reaccion->update(['tipo' => $validated['tipo']]);

                return response()->json([
                    'success' => true,
                    'action' => 'updated',
                    'count' => $publicacion->total_reacciones
                ]);
            }
        } else {
            // Crear nueva reacción
            ReaccionExtranet::create([
                'reaccionable_type' => 'publicaciones_muro',
                'reaccionable_id' => $id,
                'autor_id' => Auth::id(),
                'tipo' => $validated['tipo'],
            ]);

            $publicacion->increment('total_reacciones');

            return response()->json([
                'success' => true,
                'action' => 'added',
                'count' => $publicacion->fresh()->total_reacciones
            ]);
        }
    }
}
