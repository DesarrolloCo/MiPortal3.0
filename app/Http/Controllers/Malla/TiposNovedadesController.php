<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tipos_novedade;
use App\Models\ConceptoNominaSiigo;
use Illuminate\Support\Facades\Validator;

class TiposNovedadesController extends Controller
{
    public function index()
    {
        // Mostrar todos los tipos de novedades (activos e inactivos)
        $tiposNovedades = tipos_novedade::with('conceptoSiigo')->get();
        $conceptosSiigo = ConceptoNominaSiigo::all();
        return view('Malla.Tipos_Novedades.index', compact('tiposNovedades', 'conceptosSiigo'));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TIN_NOMBRE' => 'required|string|max:25',
            'TIN_TIPO' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crear siempre como activo (TIN_ESTADO = 1)
        $data = $request->all();
        $data['TIN_ESTADO'] = 1;

        tipos_novedade::create($data);
        return redirect()->route('TiposNovedades.index')->with('success', 'Tipo de novedad creado exitosamente.');
    }

    public function destroy($id)
    {
        $tipoNovedad = tipos_novedade::findOrFail($id);
        $tipoNovedad->update(['TIN_ESTADO' => 0]);
        return redirect()->route('TiposNovedades.index')->with('success', 'Tipo de novedad desactivado exitosamente.');
    }

    public function activate($id)
    {
        $tipoNovedad = tipos_novedade::findOrFail($id);
        $tipoNovedad->update(['TIN_ESTADO' => 1]);
        return redirect()->route('TiposNovedades.index')->with('success', 'Tipo de novedad activado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'TIN_NOMBRE' => 'required|string|max:25',
            'TIN_TIPO' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tipoNovedad = tipos_novedade::findOrFail($id);
        // Solo actualizar los campos del formulario, mantener TIN_ESTADO intacto
        $tipoNovedad->update($request->only(['TIN_NOMBRE', 'TIN_TIPO', 'COD_SIIGO']));
        return redirect()->route('TiposNovedades.index')->with('success', 'Tipo de novedad actualizado exitosamente.');
    }
}
