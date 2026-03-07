<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extranet\Reconocimiento;
use App\Models\Extranet\PublicacionMuro;
use App\Models\empleado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReconocimientoController extends Controller
{
    /**
     * Mostrar muro de reconocimientos
     */
    public function index(Request $request)
    {
        $query = Reconocimiento::with(['empleado', 'otorgadoPor']);

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }

        if ($request->filled('mes')) {
            $query->whereMonth('fecha', $request->mes);
        }

        if ($request->filled('ano')) {
            $query->whereYear('fecha', $request->ano);
        } else {
            // Por defecto mostrar del año actual
            $query->whereYear('fecha', Carbon::now()->year);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        // Solo públicos por defecto
        $query->where('publico', true);

        $reconocimientos = $query->orderBy('destacado', 'desc')
                                 ->orderBy('fecha', 'desc')
                                 ->paginate(12);

        // Obtener empleados para filtro
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();

        return view('extranet.reconocimientos.index', compact('reconocimientos', 'empleados'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();
        return view('extranet.reconocimientos.create', compact('empleados'));
    }

    /**
     * Guardar nuevo reconocimiento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,EMP_ID',
            'tipo' => 'required|in:empleado_mes,aniversario,logro,excelencia,innovacion,trabajo_equipo,otro',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'imagen' => 'nullable|image|max:5120',
            'publico' => 'boolean',
            'destacado' => 'boolean',
        ]);

        $validated['otorgado_por'] = Auth::id();

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('reconocimientos/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        $reconocimiento = Reconocimiento::create($validated);

        // Crear publicación en el muro si es público
        if ($reconocimiento->publico) {
            $this->crearPublicacionMuro($reconocimiento);
        }

        return redirect()
            ->route('extranet.reconocimientos.show', $reconocimiento->id)
            ->with('success', 'Reconocimiento otorgado exitosamente');
    }

    /**
     * Mostrar detalle de reconocimiento
     */
    public function show($id)
    {
        $reconocimiento = Reconocimiento::with(['empleado', 'otorgadoPor'])->findOrFail($id);

        return view('extranet.reconocimientos.show', compact('reconocimiento'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $reconocimiento = Reconocimiento::findOrFail($id);
        $empleados = empleado::where('EMP_ACTIVO', 1)->orderBy('EMP_NOMBRES')->get();
        return view('extranet.reconocimientos.edit', compact('reconocimiento', 'empleados'));
    }

    /**
     * Actualizar reconocimiento
     */
    public function update(Request $request, $id)
    {
        $reconocimiento = Reconocimiento::findOrFail($id);

        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,EMP_ID',
            'tipo' => 'required|in:empleado_mes,aniversario,logro,excelencia,innovacion,trabajo_equipo,otro',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'imagen' => 'nullable|image|max:5120',
            'publico' => 'boolean',
            'destacado' => 'boolean',
        ]);

        // Subir nueva imagen si existe
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($reconocimiento->imagen_url) {
                $oldPath = str_replace('/storage/', '', $reconocimiento->imagen_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagen = $request->file('imagen');
            $imagenNombre = time() . '_' . $imagen->getClientOriginalName();
            $imagenPath = $imagen->storeAs('reconocimientos/imagenes', $imagenNombre, 'public');
            $validated['imagen_url'] = '/storage/' . $imagenPath;
        }

        $reconocimiento->update($validated);

        return redirect()
            ->route('extranet.reconocimientos.show', $reconocimiento->id)
            ->with('success', 'Reconocimiento actualizado exitosamente');
    }

    /**
     * Eliminar reconocimiento
     */
    public function destroy($id)
    {
        $reconocimiento = Reconocimiento::findOrFail($id);

        // Eliminar imagen si existe
        if ($reconocimiento->imagen_url) {
            $oldPath = str_replace('/storage/', '', $reconocimiento->imagen_url);
            Storage::disk('public')->delete($oldPath);
        }

        $reconocimiento->delete();

        return redirect()
            ->route('extranet.reconocimientos.index')
            ->with('success', 'Reconocimiento eliminado exitosamente');
    }

    /**
     * Vista especial de empleado del mes
     */
    public function empleadoDelMes()
    {
        $mesActual = Carbon::now();

        $empleadoDelMes = Reconocimiento::with(['empleado'])
            ->where('tipo', 'empleado_mes')
            ->whereMonth('fecha', $mesActual->month)
            ->whereYear('fecha', $mesActual->year)
            ->where('publico', true)
            ->first();

        // Empleados del mes histórico (últimos 6 meses)
        $historico = Reconocimiento::with(['empleado'])
            ->where('tipo', 'empleado_mes')
            ->where('fecha', '>=', Carbon::now()->subMonths(6))
            ->where('publico', true)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('extranet.reconocimientos.empleado-mes', compact('empleadoDelMes', 'historico'));
    }

    /**
     * Estadísticas de reconocimientos
     */
    public function estadisticas(Request $request)
    {
        $ano = $request->get('ano', Carbon::now()->year);

        $stats = [
            'total_ano' => Reconocimiento::whereYear('fecha', $ano)->count(),
            'por_tipo' => Reconocimiento::whereYear('fecha', $ano)
                ->select('tipo', DB::raw('count(*) as total'))
                ->groupBy('tipo')
                ->get(),
            'por_mes' => Reconocimiento::whereYear('fecha', $ano)
                ->select(DB::raw('MONTH(fecha) as mes'), DB::raw('count(*) as total'))
                ->groupBy('mes')
                ->get(),
            'top_empleados' => Reconocimiento::with('empleado')
                ->whereYear('fecha', $ano)
                ->select('empleado_id', DB::raw('count(*) as total'))
                ->groupBy('empleado_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get(),
        ];

        if ($request->ajax()) {
            return response()->json($stats);
        }

        return view('extranet.reconocimientos.estadisticas', compact('stats', 'ano'));
    }

    /**
     * Crear publicación en el muro cuando se otorga un reconocimiento
     */
    private function crearPublicacionMuro($reconocimiento)
    {
        // Verificar que no exista ya una publicación para este reconocimiento
        $existePublicacion = PublicacionMuro::where('tipo', 'reconocimiento')
            ->where('referencia_id', $reconocimiento->id)
            ->exists();

        if (!$existePublicacion) {
            $contenido = $reconocimiento->descripcion;

            if ($reconocimiento->tipo === 'empleado_mes') {
                $contenido = "¡Felicitaciones a " . $reconocimiento->empleado->EMP_NOMBRES . " por ser el Empleado del Mes!\n\n" . $contenido;
            }

            PublicacionMuro::create([
                'tipo' => 'reconocimiento',
                'referencia_id' => $reconocimiento->id,
                'titulo' => $reconocimiento->titulo,
                'contenido' => $contenido,
                'imagen_url' => $reconocimiento->imagen_url,
                'autor_id' => $reconocimiento->otorgado_por,
                'destacado' => $reconocimiento->destacado || $reconocimiento->tipo === 'empleado_mes',
                'comentarios_habilitados' => true,
            ]);
        }
    }
}
