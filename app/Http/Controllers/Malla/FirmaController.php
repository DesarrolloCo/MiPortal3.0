<?php

namespace App\Http\Controllers\Malla;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Auth;

use App\Models\parametro;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $firma_foto = DB::select('SELECT `PAR_VALOR` FROM `parametros` WHERE `PAR_CODE` = "FOTO_FIRMA_DIRECTOR_GENERAL"');
        $firma_texto = DB::select('SELECT `PAR_VALOR` FROM `parametros` WHERE `PAR_CODE` = "NOMBRE_DIRECTOR_GENERAL"');

        return view('Malla.Firma.index', compact('firma_foto','firma_texto'));
    }

    public function cambiar_firma(request $request){
        $validator = [
            'PAR_VALOR' => 'required|image|mimes:svg',
        ];

        $messages = [
            'PAR_VALOR.required' => 'El campo del formulario es obligatorio',
            'PAR_VALOR.image' => 'El Archivo debe ser una imagen',
            'PAR_VALOR.mimes' => 'La imagen debe ser de formato SVG',
        ];

        $this->validate($request, $validator, $messages);

        return DB::transaction(function () use ($request){

            parametro::where('PAR_CODE', 'FOTO_FIRMA_DIRECTOR_GENERAL')->update(['PAR_VALOR' =>  "storage/".$request->file('PAR_VALOR')->store('img','public')]);

            return redirect()->back()->with('rgcmessage', 'Firma cargada con exito!...');
        }, 5);

    }

    public function cambiar_nombre_firma(request $request){
        $validator = [
            'PAR_VALOR' => 'required',
        ];

        $messages = [
            'PAR_VALOR.required' => 'El campo del formulario es obligatorio',
        ];

        $this->validate($request, $validator, $messages);

        return DB::transaction(function () use ($request){

            parametro::where('PAR_CODE', 'NOMBRE_DIRECTOR_GENERAL')->update(['PAR_VALOR' =>  $request->PAR_VALOR]);

            return redirect()->back()->with('rgcmessage', 'NmbreFirma cargada con exito!...');
        }, 5);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
}
