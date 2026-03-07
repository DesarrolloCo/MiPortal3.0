<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\Proyecto;
use App\Models\Extranet\TareaProyecto;
use App\Models\Extranet\PublicacionMuro;
use App\Models\empleado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProyectoController extends Controller
{
    /**
     * Mostrar listado de proyectos
     */
    public function index(Request $request)
    {
        $query = Proyecto::with(['responsable', 'tareas']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto mostrar solo activos
            $query->whereIn('estado', ['planificacion', 'en_progreso']);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('responsable_id')) {
            $query->where('responsable_id', $request->responsable_id);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        $proyectos = $query->orderBy('prioridad', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        // Obtener empleados para filtro
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();

        return view('extranet.proyectos.index', compact('proyectos', 'empleados'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();
        return view('extranet.proyectos.create', compact('empleados'));
    }

    /**
     * Guardar nuevo proyecto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'objetivo' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'estado' => 'required|in:planificacion,en_progreso,pausado,completado,cancelado',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'progreso' => 'nullable|integer|min:0|max:100',
            'responsable_id' => 'required|exists:empleados,EMP_ID',
        ]);

        $validated['progreso'] = $validated['progreso'] ?? 0;

        $proyecto = Proyecto::create($validated);

        // Crear publicación en el muro
        $this->crearPublicacionMuro($proyecto);

        return redirect()
            ->route('extranet.proyectos.show', $proyecto->id)
            ->with('success', 'Proyecto creado exitosamente');
    }

    /**
     * Mostrar detalle de proyecto con vista Kanban
     */
    public function show($id)
    {
        $proyecto = Proyecto::with(['responsable', 'tareas.asignado'])->findOrFail($id);

        // Agrupar tareas por estado para vista Kanban
        $tareasPorEstado = [
            'pendiente' => $proyecto->tareas->where('estado', 'pendiente'),
            'en_progreso' => $proyecto->tareas->where('estado', 'en_progreso'),
            'revision' => $proyecto->tareas->where('estado', 'revision'),
            'completada' => $proyecto->tareas->where('estado', 'completada'),
        ];

        // Calcular progreso automático basado en tareas
        $totalTareas = $proyecto->tareas->count();
        $tareasCompletadas = $proyecto->tareas->where('estado', 'completada')->count();
        $progresoCalculado = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0;

        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();

        return view('extranet.proyectos.show', compact('proyecto', 'tareasPorEstado', 'progresoCalculado', 'empleados'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();
        return view('extranet.proyectos.edit', compact('proyecto', 'empleados'));
    }

    /**
     * Actualizar proyecto
     */
    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'objetivo' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'fecha_fin_real' => 'nullable|date',
            'estado' => 'required|in:planificacion,en_progreso,pausado,completado,cancelado',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'progreso' => 'nullable|integer|min:0|max:100',
            'responsable_id' => 'required|exists:empleados,EMP_ID',
        ]);

        $proyecto->update($validated);

        return redirect()
            ->route('extranet.proyectos.show', $proyecto->id)
            ->with('success', 'Proyecto actualizado exitosamente');
    }

    /**
     * Eliminar proyecto (soft delete)
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return redirect()
            ->route('extranet.proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }

    /**
     * Actualizar progreso del proyecto (manual o automático)
     */
    public function actualizarProgreso(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if ($request->has('automatico') && $request->automatico) {
            // Calcular automáticamente según tareas
            $totalTareas = $proyecto->tareas->count();
            $tareasCompletadas = $proyecto->tareas->where('estado', 'completada')->count();
            $progreso = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0;
        } else {
            // Actualizar manualmente
            $validated = $request->validate([
                'progreso' => 'required|integer|min:0|max:100',
            ]);
            $progreso = $validated['progreso'];
        }

        $proyecto->update(['progreso' => $progreso]);

        return response()->json([
            'success' => true,
            'progreso' => $progreso,
        ]);
    }

    /**
     * Vista tablero Kanban (alternativa a show)
     */
    public function kanban($id)
    {
        $proyecto = Proyecto::with(['responsable', 'tareas.asignado'])->findOrFail($id);

        $tareasPorEstado = [
            'pendiente' => $proyecto->tareas->where('estado', 'pendiente'),
            'en_progreso' => $proyecto->tareas->where('estado', 'en_progreso'),
            'revision' => $proyecto->tareas->where('estado', 'revision'),
            'completada' => $proyecto->tareas->where('estado', 'completada'),
        ];

        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();

        return view('extranet.proyectos.kanban', compact('proyecto', 'tareasPorEstado', 'empleados'));
    }

    /**
     * Estadísticas del proyecto
     */
    public function estadisticas($id)
    {
        $proyecto = Proyecto::with('tareas')->findOrFail($id);

        $stats = [
            'total_tareas' => $proyecto->tareas->count(),
            'pendientes' => $proyecto->tareas->where('estado', 'pendiente')->count(),
            'en_progreso' => $proyecto->tareas->where('estado', 'en_progreso')->count(),
            'revision' => $proyecto->tareas->where('estado', 'revision')->count(),
            'completadas' => $proyecto->tareas->where('estado', 'completada')->count(),
            'canceladas' => $proyecto->tareas->where('estado', 'cancelada')->count(),
            'prioridad_alta' => $proyecto->tareas->whereIn('prioridad', ['alta', 'critica'])->count(),
            'atrasadas' => $proyecto->tareas->where('fecha_vencimiento', '<', Carbon::now())
                                           ->whereNotIn('estado', ['completada', 'cancelada'])
                                           ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Crear publicación en el muro cuando se crea un proyecto
     */
    private function crearPublicacionMuro($proyecto)
    {
        // Verificar que no exista ya una publicación para este proyecto
        $existePublicacion = PublicacionMuro::where('tipo', 'proyecto')
            ->where('referencia_id', $proyecto->id)
            ->exists();

        if (!$existePublicacion) {
            PublicacionMuro::create([
                'tipo' => 'proyecto',
                'referencia_id' => $proyecto->id,
                'titulo' => 'Nuevo proyecto: ' . $proyecto->nombre,
                'contenido' => $proyecto->descripcion . "\n\nObjetivo: " . $proyecto->objetivo,
                'autor_id' => Auth::id(),
                'destacado' => $proyecto->prioridad === 'critica',
                'comentarios_habilitados' => true,
            ]);
        }
    }
}
