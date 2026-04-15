<?php

namespace App\Http\Controllers\Visita;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\registro;

class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Iniciar query con Eloquent (previene SQL injection)
        $query = registro::where('REG_ESTADO', 1)
            ->with('usuario'); // Eager loading

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('REG_NOMBRE', 'like', "%{$buscar}%")
                  ->orWhere('REG_CEDULA', 'like', "%{$buscar}%")
                  ->orWhere('REG_EMPRESA', 'like', "%{$buscar}%")
                  ->orWhere('REG_MOTIVO_INGRESO', 'like', "%{$buscar}%");
            });
        }

        // Filtro por estado (activos/finalizados)
        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->whereNull('REG_FECHA_HORA_SALIDA');
            } elseif ($request->estado === 'finalizados') {
                $query->whereNotNull('REG_FECHA_HORA_SALIDA');
            }
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Ordenar por más recientes primero
        $registros = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends($request->all()); // Mantener parámetros en paginación

        // Contar visitas activas
        $visitasActivas = registro::where('REG_ESTADO', 1)
            ->whereNull('REG_FECHA_HORA_SALIDA')
            ->count();

        return view('Visita.index', compact('registros', 'visitasActivas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Este método debería mostrar el formulario, no guardar
        // La lógica de guardado debería estar en store()
        return view('Visita.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar datos de entrada
        $validated = $request->validate([
            'REG_NOMBRE' => 'required|string|max:255',
            'REG_TIPO_ID' => 'required|string|max:50',
            'REG_CEDULA' => 'required|string|max:50',
            'REG_EMPRESA' => 'required|string|max:255',
            'REG_MOTIVO_INGRESO' => 'required|string|max:500',
            'REG_EQUIPO' => 'nullable|string|max:255',
            'REG_SERIAL' => 'nullable|string|max:255',
        ], [
            'REG_NOMBRE.required' => 'El nombre es obligatorio',
            'REG_TIPO_ID.required' => 'El tipo de identificación es obligatorio',
            'REG_CEDULA.required' => 'La cédula es obligatoria',
            'REG_EMPRESA.required' => 'La empresa es obligatoria',
            'REG_MOTIVO_INGRESO.required' => 'El motivo de ingreso es obligatorio',
        ]);

        // Agregar datos adicionales
        $validated['USER_ID'] = Auth::id();
        $validated['REG_ESTADO'] = 1;

        // Crear registro usando Eloquent (previene SQL injection)
        registro::create($validated);

        return redirect()->back()->with('rgcmessage', 'Visita registrada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Registrar salida de una visita
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exit(Request $request, $id)
    {
        // Buscar registro usando Eloquent (previene SQL injection)
        $registro = registro::find($id);

        if (!$registro) {
            return redirect()->back()->with('msjerror', 'Registro no encontrado');
        }

        // Verificar que no tenga salida registrada
        if ($registro->REG_FECHA_HORA_SALIDA) {
            return redirect()->back()->with('msjerror', 'Esta visita ya tiene salida registrada');
        }

        // Registrar fecha y hora de salida usando Eloquent
        $registro->update([
            'REG_FECHA_HORA_SALIDA' => now()
        ]);

        return redirect()->back()->with('rgcmessage', 'Salida registrada con éxito');
    }
}
