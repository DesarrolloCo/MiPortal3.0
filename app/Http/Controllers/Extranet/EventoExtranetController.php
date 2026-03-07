<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\EventoExtranet;
use App\Models\Extranet\AsistenteEvento;
use App\Models\Extranet\PublicacionMuro;
use App\Models\empleado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoExtranetController extends Controller
{
    /**
     * Mostrar listado de eventos
     */
    public function index(Request $request)
    {
        $query = EventoExtranet::with(['organizador', 'asistentes']);

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('modalidad')) {
            $query->where('modalidad', $request->modalidad);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto solo mostrar publicados
            $query->where('estado', 'publicado');
        }

        if ($request->filled('mes')) {
            $query->whereMonth('fecha_inicio', $request->mes);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        // Vista de calendario o lista
        $vista = $request->get('vista', 'lista');

        if ($vista === 'calendario') {
            // Obtener eventos para el calendario
            $eventos = $query->get();
            return view('extranet.eventos.calendario', compact('eventos'));
        } else {
            $eventos = $query->orderBy('fecha_inicio', 'asc')->paginate(12);
            return view('extranet.eventos.index', compact('eventos'));
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('extranet.eventos.create');
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:reunion,capacitacion,celebracion,conferencia,team_building,otro',
            'modalidad' => 'required|in:presencial,virtual,hibrido',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable|after:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'link_virtual' => 'nullable|url|max:500',
            'imagen' => 'nullable|image|max:5120',
            'cupo_maximo' => 'nullable|integer|min:1',
            'requiere_confirmacion' => 'boolean',
            'estado' => 'required|in:borrador,publicado,en_curso,finalizado,cancelado',
            'color' => 'nullable|string|max:7',
        ]);

        // Obtener empleado del usuario actual
        $user = Auth::user();
        $empleado = empleado::where('USER_ID', $user->id)->first();

        if (!$empleado) {
            return redirect()
                ->back()
                ->with('error', 'No se encontró el empleado asociado a este usuario');
        }

        $validated['organizador_id'] = $empleado->EMP_ID;

        // Combinar fecha y hora para fecha_inicio
        $validated['fecha_inicio'] = Carbon::parse($validated['fecha_inicio'] . ' ' . $validated['hora_inicio']);

        // Combinar fecha y hora para fecha_fin si existe
        if (!empty($validated['fecha_fin']) && !empty($validated['hora_fin'])) {
            $validated['fecha_fin'] = Carbon::parse($validated['fecha_fin'] . ' ' . $validated['hora_fin']);
        }

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('eventos/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        // Color por defecto según tipo
        if (empty($validated['color'])) {
            $validated['color'] = $this->getColorPorTipo($validated['tipo']);
        }

        $evento = EventoExtranet::create($validated);

        // Si se publica, crear publicación en el muro
        if ($evento->estado === 'publicado') {
            $this->crearPublicacionMuro($evento);
        }

        return redirect()
            ->route('extranet.eventos.show', $evento->id)
            ->with('success', 'Evento creado exitosamente');
    }

    /**
     * Mostrar detalle de evento
     */
    public function show($id)
    {
        $evento = EventoExtranet::with(['organizador', 'asistentes.empleado'])->findOrFail($id);

        // Obtener empleado del usuario actual para verificar si ya está registrado
        $user = Auth::user();
        $empleado = empleado::where('USER_ID', $user->id)->first();

        $miConfirmacion = null;
        if ($empleado) {
            $miConfirmacion = AsistenteEvento::where('evento_id', $id)
                ->where('empleado_id', $empleado->EMP_ID)
                ->first();
        }

        return view('extranet.eventos.show', compact('evento', 'miConfirmacion'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $evento = EventoExtranet::findOrFail($id);
        return view('extranet.eventos.edit', compact('evento'));
    }

    /**
     * Actualizar evento
     */
    public function update(Request $request, $id)
    {
        $evento = EventoExtranet::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:reunion,capacitacion,celebracion,conferencia,team_building,otro',
            'modalidad' => 'required|in:presencial,virtual,hibrido',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'hora_inicio' => 'required',
            'hora_fin' => 'nullable|after:hora_inicio',
            'lugar' => 'nullable|string|max:255',
            'link_virtual' => 'nullable|url|max:500',
            'imagen' => 'nullable|image|max:5120',
            'cupo_maximo' => 'nullable|integer|min:1',
            'requiere_confirmacion' => 'boolean',
            'estado' => 'required|in:borrador,publicado,en_curso,finalizado,cancelado',
            'color' => 'nullable|string|max:7',
        ]);

        // Combinar fecha y hora
        $validated['fecha_inicio'] = Carbon::parse($validated['fecha_inicio'] . ' ' . $validated['hora_inicio']);

        if (!empty($validated['fecha_fin']) && !empty($validated['hora_fin'])) {
            $validated['fecha_fin'] = Carbon::parse($validated['fecha_fin'] . ' ' . $validated['hora_fin']);
        }

        // Subir nueva imagen si existe
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($evento->imagen_url) {
                $oldPath = str_replace('/storage/', '', $evento->imagen_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('eventos/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        $estadoAnterior = $evento->estado;
        $evento->update($validated);

        // Si cambia a publicado, crear publicación en muro
        if ($estadoAnterior !== 'publicado' && $evento->estado === 'publicado') {
            $this->crearPublicacionMuro($evento);
        }

        return redirect()
            ->route('extranet.eventos.show', $evento->id)
            ->with('success', 'Evento actualizado exitosamente');
    }

    /**
     * Eliminar evento (soft delete)
     */
    public function destroy($id)
    {
        $evento = EventoExtranet::findOrFail($id);
        $evento->delete();

        return redirect()
            ->route('extranet.eventos.index')
            ->with('success', 'Evento eliminado exitosamente');
    }

    /**
     * Confirmar asistencia a evento
     */
    public function confirmarAsistencia($id)
    {
        $evento = EventoExtranet::findOrFail($id);

        // Obtener empleado del usuario actual
        $user = Auth::user();
        $empleado = empleado::where('USER_ID', $user->id)->first();

        if (!$empleado) {
            return redirect()
                ->back()
                ->with('error', 'No se encontró el empleado asociado a este usuario');
        }

        // Verificar si hay cupo
        if ($evento->cupo_maximo) {
            $confirmados = AsistenteEvento::where('evento_id', $id)
                ->where('estado_confirmacion', 'confirmado')
                ->count();

            if ($confirmados >= $evento->cupo_maximo) {
                return redirect()
                    ->back()
                    ->with('error', 'El evento ha alcanzado el cupo máximo');
            }
        }

        // Crear o actualizar asistencia
        AsistenteEvento::updateOrCreate(
            [
                'evento_id' => $id,
                'empleado_id' => $empleado->EMP_ID,
            ],
            [
                'estado_confirmacion' => 'confirmado',
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Asistencia confirmada exitosamente');
    }

    /**
     * Cancelar asistencia a evento
     */
    public function cancelarAsistencia($id)
    {
        $user = Auth::user();
        $empleado = empleado::where('USER_ID', $user->id)->first();

        if (!$empleado) {
            return redirect()
                ->back()
                ->with('error', 'No se encontró el empleado asociado a este usuario');
        }

        AsistenteEvento::where('evento_id', $id)
            ->where('empleado_id', $empleado->EMP_ID)
            ->update(['estado_confirmacion' => 'rechazado']);

        return redirect()
            ->back()
            ->with('success', 'Asistencia cancelada');
    }

    /**
     * Lista de asistentes (solo para organizador)
     */
    public function listaAsistentes($id)
    {
        $evento = EventoExtranet::with(['asistentes.empleado'])->findOrFail($id);

        return view('extranet.eventos.asistentes', compact('evento'));
    }

    /**
     * Marcar asistencia real (solo para organizador durante/después del evento)
     */
    public function marcarAsistencia(Request $request, $id)
    {
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,EMP_ID',
            'asistio' => 'required|boolean',
        ]);

        AsistenteEvento::where('evento_id', $id)
            ->where('empleado_id', $validated['empleado_id'])
            ->update(['asistio' => $validated['asistio']]);

        return response()->json(['success' => true]);
    }

    /**
     * Obtener eventos para el calendario (AJAX)
     */
    public function getEventosCalendario(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $eventos = EventoExtranet::where('estado', 'publicado')
            ->whereBetween('fecha_inicio', [$start, $end])
            ->get()
            ->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'title' => $evento->titulo,
                    'start' => $evento->fecha_inicio->format('Y-m-d H:i:s'),
                    'end' => $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d H:i:s') : null,
                    'color' => $evento->color,
                    'url' => route('extranet.eventos.show', $evento->id),
                ];
            });

        return response()->json($eventos);
    }

    /**
     * Crear publicación en el muro cuando se publica un evento
     */
    private function crearPublicacionMuro($evento)
    {
        // Verificar que no exista ya una publicación para este evento
        $existePublicacion = PublicacionMuro::where('tipo', 'evento')
            ->where('referencia_id', $evento->id)
            ->exists();

        if (!$existePublicacion) {
            $contenido = strip_tags($evento->descripcion);
            $contenido .= "\n\nFecha: " . $evento->fecha_inicio->format('d/m/Y H:i');

            if ($evento->modalidad === 'presencial') {
                $contenido .= "\nLugar: " . $evento->lugar;
            } elseif ($evento->modalidad === 'virtual') {
                $contenido .= "\nModalidad: Virtual";
            } else {
                $contenido .= "\nModalidad: Híbrido";
            }

            PublicacionMuro::create([
                'tipo' => 'evento',
                'referencia_id' => $evento->id,
                'titulo' => $evento->titulo,
                'contenido' => $contenido,
                'imagen_url' => $evento->imagen_url,
                'autor_id' => Auth::id(),
                'destacado' => false,
                'comentarios_habilitados' => true,
            ]);
        }
    }

    /**
     * Obtener color por defecto según tipo de evento
     */
    private function getColorPorTipo($tipo)
    {
        $colores = [
            'reunion' => '#007bff',
            'capacitacion' => '#28a745',
            'celebracion' => '#ff69b4',
            'conferencia' => '#6f42c1',
            'team_building' => '#17a2b8',
            'otro' => '#6c757d',
        ];

        return $colores[$tipo] ?? '#007bff';
    }
}
