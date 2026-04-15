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
        $this->authorize('ver-jornada');
        $jornadas = jornada::where('JOR_ESTADO', '=', '1')->where('USER_ID', auth()->id())->get();
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
        $this->authorize('crear-jornada');
        $request->validate([
            'JOR_NOMBRE' => 'required',
            'JOR_INICIO' => 'required|exists:horas,HOR_ID',
            'JOR_FINAL' => 'required|exists:horas,HOR_ID|gt:JOR_INICIO',
            'JOR_ALMUERZO_INICIO' => 'nullable|exists:horas,HOR_ID|gte:JOR_INICIO|lt:JOR_FINAL',
            'JOR_ALMUERZO_FIN' => 'nullable|exists:horas,HOR_ID|gt:JOR_ALMUERZO_INICIO|lte:JOR_FINAL'
        ], [
            'JOR_INICIO.exists' => 'La hora de inicio seleccionada no es válida.',
            'JOR_FINAL.exists' => 'La hora de fin seleccionada no es válida.',
            'JOR_FINAL.gt' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'JOR_ALMUERZO_INICIO.exists' => 'La hora de inicio de almuerzo seleccionada no es válida.',
            'JOR_ALMUERZO_INICIO.gte' => 'La hora de inicio de almuerzo debe estar dentro del horario laboral.',
            'JOR_ALMUERZO_INICIO.lt' => 'La hora de inicio de almuerzo debe ser anterior a la hora de fin.',
            'JOR_ALMUERZO_FIN.exists' => 'La hora de fin de almuerzo seleccionada no es válida.',
            'JOR_ALMUERZO_FIN.gt' => 'La hora de fin de almuerzo debe ser posterior a la hora de inicio.',
            'JOR_ALMUERZO_FIN.lte' => 'La hora de fin de almuerzo debe estar dentro del horario laboral.'
        ]);

        $datosjornadas = request()->except('_token');
        $datosjornadas['USER_ID'] = auth()->id();
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
        $this->authorize('opciones-jornada');

        $request->validate([
            'JOR_NOMBRE' => 'required',
            'JOR_INICIO' => 'required|exists:horas,HOR_ID',
            'JOR_FINAL' => 'required|exists:horas,HOR_ID|gt:JOR_INICIO',
            'JOR_ALMUERZO_INICIO' => 'nullable|exists:horas,HOR_ID|gte:JOR_INICIO|lt:JOR_FINAL',
            'JOR_ALMUERZO_FIN' => 'nullable|exists:horas,HOR_ID|gt:JOR_ALMUERZO_INICIO|lte:JOR_FINAL'
        ], [
            'JOR_INICIO.exists' => 'La hora de inicio seleccionada no es válida.',
            'JOR_FINAL.exists' => 'La hora de fin seleccionada no es válida.',
            'JOR_FINAL.gt' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'JOR_ALMUERZO_INICIO.exists' => 'La hora de inicio de almuerzo seleccionada no es válida.',
            'JOR_ALMUERZO_INICIO.gte' => 'La hora de inicio de almuerzo debe estar dentro del horario laboral.',
            'JOR_ALMUERZO_INICIO.lt' => 'La hora de inicio de almuerzo debe ser anterior a la hora de fin.',
            'JOR_ALMUERZO_FIN.exists' => 'La hora de fin de almuerzo seleccionada no es válida.',
            'JOR_ALMUERZO_FIN.gt' => 'La hora de fin de almuerzo debe ser posterior a la hora de inicio.',
            'JOR_ALMUERZO_FIN.lte' => 'La hora de fin de almuerzo debe estar dentro del horario laboral.'
        ]);

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
        $this->authorize('opciones-jornada');
        /* jornada::where('JOR_ID', $id)->delete(); */
        jornada::where('JOR_ID', $id)->update(['JOR_ESTADO' => '0']);
        return redirect()->back()->with('msjdelete', 'jornada borrada correctamente!...');
    }
}
