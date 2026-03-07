<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\Comunicado;
use App\Models\Extranet\PublicacionMuro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ComunicadoController extends Controller
{
    /**
     * Mostrar listado de comunicados
     */
    public function index(Request $request)
    {
        $query = Comunicado::with('autor');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto solo mostrar publicados y vigentes
            $query->publicados()->vigentes();
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('contenido', 'like', '%' . $request->buscar . '%');
            });
        }

        $comunicados = $query->orderBy('fijado', 'desc')
                            ->orderBy('prioridad', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        return view('extranet.comunicados.index', compact('comunicados'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('extranet.comunicados.create');
    }

    /**
     * Guardar nuevo comunicado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:general,urgente,rh,ti,operaciones,admin',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'archivo' => 'nullable|file|max:10240', // 10MB max
            'imagen' => 'nullable|image|max:5120', // 5MB max
            'fijado' => 'boolean',
            'estado' => 'required|in:borrador,publicado,archivado',
        ]);

        $validated['autor_id'] = Auth::id();

        // Subir archivo si existe
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $archivoNombre = time() . '_' . $archivo->getClientOriginalName();
            $archivoPath = $archivo->storeAs('comunicados/archivos', $archivoNombre, 'public');
            $validated['archivo_url'] = '/storage/' . $archivoPath;
        }

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('comunicados/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        $comunicado = Comunicado::create($validated);

        // Si se publica, crear publicación en el muro
        if ($comunicado->estado === 'publicado') {
            $this->crearPublicacionMuro($comunicado);
        }

        return redirect()
            ->route('extranet.comunicados.show', $comunicado->id)
            ->with('success', 'Comunicado creado exitosamente');
    }

    /**
     * Mostrar detalle de comunicado
     */
    public function show($id)
    {
        $comunicado = Comunicado::with('autor')->findOrFail($id);

        // Incrementar contador de vistas
        $comunicado->increment('vistas');

        return view('extranet.comunicados.show', compact('comunicado'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        return view('extranet.comunicados.edit', compact('comunicado'));
    }

    /**
     * Actualizar comunicado
     */
    public function update(Request $request, $id)
    {
        $comunicado = Comunicado::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:general,urgente,rh,ti,operaciones,admin',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'archivo' => 'nullable|file|max:10240',
            'imagen' => 'nullable|image|max:5120',
            'fijado' => 'boolean',
            'estado' => 'required|in:borrador,publicado,archivado',
        ]);

        // Subir nuevo archivo si existe
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($comunicado->archivo_url) {
                $oldPath = str_replace('/storage/', '', $comunicado->archivo_url);
                Storage::disk('public')->delete($oldPath);
            }

            $archivo = $request->file('archivo');
            $archivoNombre = time() . '_' . $archivo->getClientOriginalName();
            $archivoPath = $archivo->storeAs('comunicados/archivos', $archivoNombre, 'public');
            $validated['archivo_url'] = '/storage/' . $archivoPath;
        }

        // Subir nueva imagen si existe
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($comunicado->imagen_url) {
                $oldPath = str_replace('/storage/', '', $comunicado->imagen_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('comunicados/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        $estadoAnterior = $comunicado->estado;
        $comunicado->update($validated);

        // Si cambia de borrador a publicado, crear publicación en muro
        if ($estadoAnterior !== 'publicado' && $comunicado->estado === 'publicado') {
            $this->crearPublicacionMuro($comunicado);
        }

        return redirect()
            ->route('extranet.comunicados.show', $comunicado->id)
            ->with('success', 'Comunicado actualizado exitosamente');
    }

    /**
     * Eliminar comunicado (soft delete)
     */
    public function destroy($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        $comunicado->delete();

        return redirect()
            ->route('extranet.comunicados.index')
            ->with('success', 'Comunicado eliminado exitosamente');
    }

    /**
     * Fijar/desfijar comunicado
     */
    public function fijar($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        $comunicado->fijado = !$comunicado->fijado;
        $comunicado->save();

        $mensaje = $comunicado->fijado ? 'Comunicado fijado exitosamente' : 'Comunicado desfijado exitosamente';

        return redirect()
            ->back()
            ->with('success', $mensaje);
    }

    /**
     * Archivar comunicado
     */
    public function archivar($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        $comunicado->estado = 'archivado';
        $comunicado->save();

        return redirect()
            ->route('extranet.comunicados.index')
            ->with('success', 'Comunicado archivado exitosamente');
    }

    /**
     * Crear publicación en el muro cuando se publica un comunicado
     */
    private function crearPublicacionMuro($comunicado)
    {
        // Verificar que no exista ya una publicación para este comunicado
        $existePublicacion = PublicacionMuro::where('tipo', 'comunicado')
            ->where('referencia_id', $comunicado->id)
            ->exists();

        if (!$existePublicacion) {
            PublicacionMuro::create([
                'tipo' => 'comunicado',
                'referencia_id' => $comunicado->id,
                'titulo' => $comunicado->titulo,
                'contenido' => strip_tags(substr($comunicado->contenido, 0, 300)) . '...',
                'imagen_url' => $comunicado->imagen_url,
                'autor_id' => $comunicado->autor_id,
                'destacado' => $comunicado->fijado || $comunicado->prioridad === 'critica',
                'comentarios_habilitados' => true,
            ]);
        }
    }
}
