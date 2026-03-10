<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\PublicacionMuro;
use Illuminate\Http\Request;

class MuroController extends Controller
{
    public function index(Request $request)
    {
        $publicaciones = PublicacionMuro::with(['autor', 'comentarios.autor', 'reacciones'])
            ->orderBy('destacado', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('extranet.muro.partials.publicaciones', compact('publicaciones'))->render(),
                'hasMore' => $publicaciones->hasMorePages()
            ]);
        }

        return view('extranet.muro.index', compact('publicaciones'));
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 2);

        $publicaciones = PublicacionMuro::with(['autor', 'comentarios.autor', 'reacciones'])
            ->orderBy('destacado', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'html' => view('extranet.muro.partials.publicaciones', compact('publicaciones'))->render(),
            'hasMore' => $publicaciones->hasMorePages()
        ]);
    }

    public function destacar($id)
    {
        $publicacion = PublicacionMuro::findOrFail($id);
        $publicacion->update(['destacado' => !$publicacion->destacado]);

        return redirect()->back()
            ->with('success', $publicacion->destacado ? 'Publicación destacada.' : 'Publicación no destacada.');
    }

    public function ocultar($id)
    {
        $publicacion = PublicacionMuro::findOrFail($id);
        $publicacion->delete();

        return redirect()->back()
            ->with('success', 'Publicación ocultada exitosamente.');
    }
}
