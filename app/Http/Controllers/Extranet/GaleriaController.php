<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\Galeria;
use App\Models\Extranet\FotoGaleria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    public function index()
    {
        $galerias = Galeria::with('evento')
            ->withCount('fotos')
            ->orderBy('fecha', 'DESC')
            ->get();

        return view('extranet.galeria.index', compact('galerias'));
    }

    public function create()
    {
        return view('extranet.galeria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'evento_id' => 'nullable|exists:eventos_extranet,id',
        ]);

        $validated['autor_id'] = Auth::id();

        $galeria = Galeria::create($validated);

        return redirect()->route('extranet.galeria.show', $galeria->id)
            ->with('success', 'Álbum creado exitosamente.');
    }

    public function show($id)
    {
        $galeria = Galeria::with(['fotos' => function($query) {
            $query->orderBy('orden');
        }, 'evento'])->findOrFail($id);

        return view('extranet.galeria.show', compact('galeria'));
    }

    public function edit($id)
    {
        $galeria = Galeria::findOrFail($id);

        return view('extranet.galeria.edit', compact('galeria'));
    }

    public function update(Request $request, $id)
    {
        $galeria = Galeria::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'evento_id' => 'nullable|exists:eventos_extranet,id',
        ]);

        $galeria->update($validated);

        return redirect()->route('extranet.galeria.show', $galeria->id)
            ->with('success', 'Álbum actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $galeria = Galeria::findOrFail($id);

        // Eliminar todas las fotos
        foreach ($galeria->fotos as $foto) {
            if ($foto->archivo_url) {
                $path = str_replace('/storage/', '', $foto->archivo_url);
                Storage::disk('public')->delete($path);
            }
            $foto->delete();
        }

        $galeria->delete();

        return redirect()->route('extranet.galeria.index')
            ->with('success', 'Álbum eliminado exitosamente.');
    }

    public function uploadFotos($id)
    {
        $galeria = Galeria::findOrFail($id);

        return view('extranet.galeria.upload', compact('galeria'));
    }

    public function storeFotos(Request $request, $id)
    {
        $galeria = Galeria::findOrFail($id);

        $request->validate([
            'fotos.*' => 'required|image|max:5120', // 5MB max
        ]);

        if ($request->hasFile('fotos')) {
            $descripciones = $request->input('descripciones', []);
            $orden = FotoGaleria::where('galeria_id', $galeria->id)->max('orden') ?? 0;

            foreach ($request->file('fotos') as $index => $file) {
                $path = $file->store('galerias', 'public');

                FotoGaleria::create([
                    'galeria_id' => $galeria->id,
                    'archivo_url' => Storage::url($path),
                    'descripcion' => $descripciones[$index] ?? null,
                    'orden' => ++$orden,
                ]);
            }

            // Actualizar contador de fotos
            $galeria->update(['total_fotos' => $galeria->fotos()->count()]);
        }

        return redirect()->route('extranet.galeria.show', $galeria->id)
            ->with('success', 'Fotos subidas exitosamente.');
    }

    public function eliminarFoto($foto_id)
    {
        $foto = FotoGaleria::findOrFail($foto_id);
        $galeria_id = $foto->galeria_id;

        // Eliminar archivo del storage
        if ($foto->archivo_url) {
            $path = str_replace('/storage/', '', $foto->archivo_url);
            Storage::disk('public')->delete($path);
        }

        $foto->delete();

        // Actualizar contador
        $galeria = Galeria::find($galeria_id);
        $galeria->update(['total_fotos' => $galeria->fotos()->count()]);

        return redirect()->route('extranet.galeria.show', $galeria_id)
            ->with('success', 'Foto eliminada exitosamente.');
    }

    public function ordenarFotos(Request $request, $id)
    {
        // Implementar reordenamiento con drag & drop
    }

    public function likeFoto($foto_id)
    {
        $foto = FotoGaleria::findOrFail($foto_id);

        // Implementar sistema de likes (toggle)
        $foto->increment('likes');

        return response()->json([
            'success' => true,
            'likes' => $foto->likes
        ]);
    }
}
