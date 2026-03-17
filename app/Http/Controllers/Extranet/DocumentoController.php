<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\DocumentoExtranet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentoExtranet::with('autor');

        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $documentos = $query->orderBy('created_at', 'DESC')->paginate(12);

        // Obtener documentos destacados
        $destacados = DocumentoExtranet::with('autor')
            ->where('destacado', true)
            ->orderBy('created_at', 'DESC')
            ->take(3)
            ->get();

        return view('extranet.documentos.index', compact('documentos', 'destacados'));
    }

    public function create()
    {
        return view('extranet.documentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:politicas,manuales,formatos,reglamentos,procedimientos,capacitacion,otro',
            'archivo' => 'required|file|max:10240', // 10MB max
        ]);

        $validated['autor_id'] = Auth::id();

        // Guardar archivo
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->store('documentos', 'public');
            $validated['archivo_url'] = Storage::url($path);
            $validated['archivo_nombre'] = $file->getClientOriginalName();
            $validated['archivo_tipo'] = $file->getClientMimeType();
            $validated['archivo_tamano'] = $file->getSize();
        }

        DocumentoExtranet::create($validated);

        return redirect()->route('extranet.documentos.index')
            ->with('success', 'Documento subido exitosamente.');
    }

    public function show($id)
    {
        $documento = DocumentoExtranet::with('autor')->findOrFail($id);

        return view('extranet.documentos.show', compact('documento'));
    }

    public function edit($id)
    {
        $documento = DocumentoExtranet::findOrFail($id);

        return view('extranet.documentos.edit', compact('documento'));
    }

    public function update(Request $request, $id)
    {
        $documento = DocumentoExtranet::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'required|in:politicas,manuales,formatos,reglamentos,procedimientos,capacitacion,otro',
        ]);

        $documento->update($validated);

        return redirect()->route('extranet.documentos.index')
            ->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $documento = DocumentoExtranet::findOrFail($id);

        // Eliminar archivo del storage
        if ($documento->archivo_url) {
            $path = str_replace('/storage/', '', $documento->archivo_url);
            Storage::disk('public')->delete($path);
        }

        $documento->delete();

        return redirect()->route('extranet.documentos.index')
            ->with('success', 'Documento eliminado exitosamente.');
    }

    public function descargar($id)
    {
        $documento = DocumentoExtranet::findOrFail($id);

        // Incrementar contador de descargas
        $documento->increment('descargas');

        $path = str_replace('/storage/', '', $documento->archivo_url);
        return Storage::disk('public')->download($path, $documento->archivo_nombre);
    }

    public function nuevaVersion(Request $request, $id)
    {
        // Implementar gestión de versiones
    }

    public function buscar(Request $request)
    {
        // Implementar búsqueda avanzada
    }
}
