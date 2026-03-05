<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\jornada;
use App\Models\hora;

class JornadasController extends Controller
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
        $jornadas = jornada::where('JOR_ESTADO', '=', '1')->get();
        $horas = hora::where('HOR_ESTADO', '=', '1')->get();
        return view('Malla.Jornada.index', compact('jornadas', 'horas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //
        $request->validate([
            'JOR_NOMBRE' => 'required',
            'JOR_INICIO' => 'required',
            'JOR_FINAL' => 'required'
        ]);

        $datosjornadas = request()->except('_token');
        jornada::insert($datosjornadas);

        return redirect()->back()->with('rgcmessage', 'jornada cargada con exito!...');
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
        //
        $datosjornadas = request()->except(['_token','_method']);
        jornada::where('JOR_ID','=', $id)->update($datosjornadas);

        Session::flash('msjupdate', '¡La jornada se a actualizado correctamente!...');
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
        /* jornada::where('JOR_ID', $id)->delete(); */
        jornada::where('JOR_ID', $id)->update(['JOR_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'jornada borrada correctamente!...');
    }
}
