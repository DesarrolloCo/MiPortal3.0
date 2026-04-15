<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cargo;

class CargosController extends Controller
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
        $this->authorize('ver-cargo');
        // Iniciar query con Eloquent
        $query = cargo::where('CAR_ESTADO', '=', '1');

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('CAR_NOMBRE', 'like', "%{$buscar}%")
                  ->orWhere('CAR_CODE', 'like', "%{$buscar}%");
            });
        }

        // Ordenar y paginar
        $cargos = $query->orderBy('CAR_NOMBRE', 'asc')
            ->paginate(15)
            ->appends($request->all());

        return view('Malla.Cargo.index', compact('cargos'));
    }

     public function create(request $request)
    {
        $this->authorize('crear-cargo');
        $request->validate([
            'CAR_CODE' => 'required',
            'CAR_NOMBRE' => 'required'
        ]);

        $datosCargo = request()->except('_token');
        cargo::insert($datosCargo);

        return redirect()->back()->with('rgcmessage', 'Cargo cargado con exito!...');
    }

    public function destroy($id)
    {
        $this->authorize('opciones-cargo');
        /* cargo::where('CAR_ID', $id)->delete(); */
        cargo::where('CAR_ID', $id)->update(['CAR_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Cargo borrado correctamente!...');
    }

    public function update(request $request, $id)
    {
        $this->authorize('opciones-cargo');
        $datosCargo = request()->except(['_token','_method']);
        cargo::where('CAR_ID','=', $id)->update($datosCargo);


        Session::flash('msjupdate', '¡El Cargo se a actualizado correctamente!...');
        return redirect()->back();
    }
}
