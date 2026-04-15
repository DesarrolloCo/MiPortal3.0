<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

use App\Models\campana;
use App\Models\informe;
use App\Models\cliente;

use App\Models\User;

class InformeController extends Controller
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
        // Iniciar query con Eloquent y eager loading
        $query = informe::with(['campana', 'cliente'])
            ->where('INF_ESTADO', 1);

        // Filtrar por cliente si tiene el permiso específico
        if (Auth::user()->hasPermissionTo('cliente_informe')) {
            $query->where('CLI_ID', Auth::user()->id);
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('INF_NOMBRE', 'like', "%{$buscar}%")
                  ->orWhere('INF_URL', 'like', "%{$buscar}%")
                  ->orWhereHas('campana', function ($q2) use ($buscar) {
                      $q2->where('CAM_NOMBRE', 'like', "%{$buscar}%");
                  });
            });
        }

        // Filtro por proyecto/campaña
        if ($request->filled('proyecto')) {
            $query->where('CAM_ID', $request->proyecto);
        }

        // Filtro por cliente
        if ($request->filled('cliente')) {
            $query->where('CLI_ID', $request->cliente);
        }

        // Ordenar y paginar
        $informes = $query->orderBy('INF_ID', 'desc')
            ->paginate(15)
            ->appends($request->all());

        // Obtener campañas y clientes para los selects
        $campanas = campana::where('CAM_ESTADO', '=', '1')
            ->orderBy('CAM_NOMBRE', 'asc')
            ->get();

        $clientes = User::where('estado', '1')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Cliente mi reportes');
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('Reporte.Informe.index', compact('campanas', 'informes', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Validar datos
        $request->validate([
            'INF_NOMBRE' => 'required|string|max:255',
            'INF_URL' => 'required|string|max:500',
            'CAM_ID' => 'required|exists:campanas,CAM_ID',
            'CLI_ID' => 'nullable|exists:users,id'
        ]);

        // Crear informe con datos validados
        informe::create([
            'INF_NOMBRE' => $request->INF_NOMBRE,
            'INF_URL' => $request->INF_URL,
            'CAM_ID' => $request->CAM_ID,
            'CLI_ID' => $request->CLI_ID == 0 ? null : $request->CLI_ID,
            'INF_ESTADO' => 1
        ]);

        return redirect()->back()->with('rgcmessage', 'Informe creado exitosamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        // Validar datos
        $request->validate([
            'INF_NOMBRE' => 'required|string|max:255',
            'INF_URL' => 'required|string|max:500',
            'CAM_ID' => 'required|exists:campanas,CAM_ID',
            'CLI_ID' => 'nullable|exists:users,id'
        ]);

        // Actualizar informe
        informe::where('INF_ID', $id)->update([
            'INF_NOMBRE' => $request->INF_NOMBRE,
            'INF_URL' => $request->INF_URL,
            'CAM_ID' => $request->CAM_ID,
            'CLI_ID' => $request->CLI_ID == 0 ? null : $request->CLI_ID
        ]);

        Session::flash('msjupdate', 'Informe actualizado correctamente');
        return redirect()->back();
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
        /* cargo::where('CAR_ID', $id)->delete(); */
        informe::where('INF_ID', $id)->update(['INF_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Informe borrado correctamente!...');
    }

    public function reportes($id)
    {
        // Usar Eloquent para obtener el informe con su campaña
        $informe = informe::with('campana')
            ->where('INF_ESTADO', 1)
            ->where('INF_ID', $id)
            ->firstOrFail();

        return view('Reporte.Informe.informe', compact('informe'));
    }

    public function clientes($id)
    {
        //

        $sql2 = "SELECT us.name, rol.id
        FROM `model_has_roles` AS model
        INNER JOIN users AS us ON us.id = model.model_id
        INNER JOIN roles AS rol ON rol.id = model.role_id
        WHERE rol.id = 1;";

        $clientes = DB::select($sql2);

        return view('Reporte.Informe.create', compact('clientes'));
    }

}
