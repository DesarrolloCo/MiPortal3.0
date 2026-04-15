<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class AgentesController extends Controller
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
        $user = auth()->user();

        // Verificar que el usuario tenga un empleado asociado (requerido para mostrar horario)
        if (!$user->empleados) {
            abort(403, 'Usuario no tiene empleado asociado.');
        }

        // Esta vista muestra "Mi horario" - debería ser accesible para cualquier usuario con empleado
        // No requiere permisos especiales ya que es información personal

        return view('Malla.Agente.index');
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

    public function perfil(){
        return view('main.Perfil.perfil');
   }

   public function perfil_update(Request $request, $id)
   {
       $this->validate($request, [
           'name' => 'required',
           'email' => 'required|email|unique:users,email,'.$id,
           'password' => 'same:confirm-password'
       ]);

       $input = $request->all();
       if(!empty($input['password'])){
           $input['password'] = Hash::make($input['password']);
       }else{
           $input = Arr::except($input,array('password'));
       }

       $user = User::find($id);
       $user->update($input);

       return redirect()->back()->with('rgcmessage', '¡Perfil actualizado correctamente!...');
   }
}
