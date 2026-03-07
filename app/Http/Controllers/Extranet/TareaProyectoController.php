<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\TareaProyecto;
use App\Models\Extranet\Proyecto;
use Carbon\Carbon;

class TareaProyectoController extends Controller
{
    /**
     * Crear nueva tarea en un proyecto
     */
    public function store(Request $request, $proyectoId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'asignado_a' => 'nullable|exists:empleados,EMP_ID',
            'estado' => 'required|in:pendiente,en_progreso,revision,completada,cancelada',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $validated['proyecto_id'] = $proyectoId;

        $tarea = TareaProyecto::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tarea' => $tarea->load('asignado'),
                'message' => 'Tarea creada exitosamente',
            ]);
        }

        return redirect()
            ->route('extranet.proyectos.show', $proyectoId)
            ->with('success', 'Tarea creada exitosamente');
    }

    /**
     * Actualizar tarea existente
     */
    public function update(Request $request, $tareaId)
    {
        $tarea = TareaProyecto::findOrFail($tareaId);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'asignado_a' => 'nullable|exists:empleados,EMP_ID',
            'estado' => 'required|in:pendiente,en_progreso,revision,completada,cancelada',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        // Si se completa la tarea, registrar fecha
        if ($validated['estado'] === 'completada' && $tarea->estado !== 'completada') {
            $validated['fecha_completada'] = Carbon::now();
        }

        $tarea->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tarea' => $tarea->load('asignado'),
                'message' => 'Tarea actualizada exitosamente',
            ]);
        }

        return redirect()
            ->route('extranet.proyectos.show', $tarea->proyecto_id)
            ->with('success', 'Tarea actualizada exitosamente');
    }

    /**
     * Mover tarea a otro estado (para drag & drop Kanban)
     */
    public function moverEstado(Request $request, $tareaId)
    {
        $tarea = TareaProyecto::findOrFail($tareaId);

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_progreso,revision,completada,cancelada',
            'orden' => 'nullable|integer',
        ]);

        // Si se completa la tarea, registrar fecha
        if ($validated['estado'] === 'completada' && $tarea->estado !== 'completada') {
            $validated['fecha_completada'] = Carbon::now();
        }

        $tarea->update($validated);

        return response()->json([
            'success' => true,
            'tarea' => $tarea,
            'message' => 'Tarea movida exitosamente',
        ]);
    }

    /**
     * Eliminar tarea
     */
    public function destroy($tareaId)
    {
        $tarea = TareaProyecto::findOrFail($tareaId);
        $proyectoId = $tarea->proyecto_id;
        $tarea->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada exitosamente',
            ]);
        }

        return redirect()
            ->route('extranet.proyectos.show', $proyectoId)
            ->with('success', 'Tarea eliminada exitosamente');
    }

    /**
     * Obtener detalle de tarea (AJAX)
     */
    public function show($tareaId)
    {
        $tarea = TareaProyecto::with(['asignado', 'proyecto'])->findOrFail($tareaId);

        return response()->json([
            'success' => true,
            'tarea' => $tarea,
        ]);
    }
}
