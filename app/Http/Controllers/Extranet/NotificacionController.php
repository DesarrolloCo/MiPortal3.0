<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\Extranet\NotificacionExtranet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return redirect()->route('extranet.dashboard')
                ->with('error', 'No tienes un empleado asignado.');
        }

        $query = NotificacionExtranet::where('empleado_id', $empleado->EMP_ID);

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        $notificaciones = $query->orderBy('created_at', 'DESC')->paginate(20);

        return view('extranet.notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida($id)
    {
        $notificacion = NotificacionExtranet::findOrFail($id);

        // Verificar que la notificación pertenece al usuario
        $empleado = Auth::user()->empleados;
        if ($notificacion->empleado_id !== $empleado->EMP_ID) {
            abort(403);
        }

        $notificacion->update([
            'leida' => true,
            'fecha_lectura' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return redirect()->route('extranet.dashboard')
                ->with('error', 'No tienes un empleado asignado.');
        }

        NotificacionExtranet::where('empleado_id', $empleado->EMP_ID)
            ->where('leida', false)
            ->update([
                'leida' => true,
                'fecha_lectura' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function eliminar($id)
    {
        $notificacion = NotificacionExtranet::findOrFail($id);

        // Verificar que la notificación pertenece al usuario
        $empleado = Auth::user()->empleados;
        if ($notificacion->empleado_id !== $empleado->EMP_ID) {
            abort(403);
        }

        $notificacion->delete();

        return redirect()->back()
            ->with('success', 'Notificación eliminada.');
    }

    public function getNoLeidas()
    {
        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return response()->json(['count' => 0]);
        }

        $count = NotificacionExtranet::where('empleado_id', $empleado->EMP_ID)
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getRecientes()
    {
        $empleado = Auth::user()->empleados;

        if (!$empleado) {
            return response()->json(['notificaciones' => []]);
        }

        $notificaciones = NotificacionExtranet::where('empleado_id', $empleado->EMP_ID)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return response()->json(['notificaciones' => $notificaciones]);
    }
}
