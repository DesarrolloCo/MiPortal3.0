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

    public function index()
    {
        //
        $cargos = cargo::where('CAR_ESTADO', '=','1')->get();

        return view('Malla.Cargo.index', compact('cargos'));
    }

     public function create(request $request)
    {
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
        /* cargo::where('CAR_ID', $id)->delete(); */
        cargo::where('CAR_ID', $id)->update(['CAR_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'Cargo borrado correctamente!...');
    }

    public function update(request $request, $id)
    {

        $datosCargo = request()->except(['_token','_method']);
        cargo::where('CAR_ID','=', $id)->update($datosCargo);


        Session::flash('msjupdate', '¡El Cargo se a actualizado correctamente!...');
        return redirect()->back();
    }
}
