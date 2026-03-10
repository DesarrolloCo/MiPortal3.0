<?php

namespace App\Http\Controllers\Extranet;

use App\Http\Controllers\Controller;
use App\Models\empleado;
use App\Models\departamento;
use App\Models\cargo;
use App\Models\Extranet\Reconocimiento;
use App\Models\Extranet\Proyecto;
use Illuminate\Http\Request;

class DirectorioController extends Controller
{
    public function index(Request $request)
    {
        $query = empleado::with(['cargo', 'departamento', 'campana'])
            ->where('EMP_ACTIVO', 1);

        // Filtro de búsqueda
        if ($request->has('buscar') && $request->buscar) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('EMP_NOMBRES', 'like', "%{$search}%")
                  ->orWhere('EMP_APELLIDOS', 'like', "%{$search}%")
                  ->orWhere('EMP_CEDULA', 'like', "%{$search}%")
                  ->orWhere('EMP_EMAIL', 'like', "%{$search}%");
            });
        }

        // Filtro por departamento
        if ($request->has('departamento') && $request->departamento) {
            $query->where('DEP_ID', $request->departamento);
        }

        // Filtro por cargo
        if ($request->has('cargo') && $request->cargo) {
            $query->where('CAR_ID', $request->cargo);
        }

        $empleados = $query->orderBy('EMP_NOMBRES')->paginate(12);

        // Obtener listas para filtros
        $departamentos = departamento::all();
        $cargos = cargo::all();

        return view('extranet.directorio.index', compact('empleados', 'departamentos', 'cargos'));
    }

    public function show($id)
    {
        $empleado = empleado::with(['cargo', 'departamento', 'campana', 'contratos'])
            ->findOrFail($id);

        // Obtener reconocimientos del empleado
        $reconocimientos = Reconocimiento::where('empleado_id', $id)
            ->orderBy('fecha', 'DESC')
            ->take(4)
            ->get();

        // Obtener proyectos donde está asignado
        $proyectos = Proyecto::where('responsable_id', $id)
            ->orWhereHas('tareas', function($query) use ($id) {
                $query->where('asignado_a', $id);
            })
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();

        return view('extranet.directorio.show', compact('empleado', 'reconocimientos', 'proyectos'));
    }

    public function organigrama()
    {
        // Implementar vista de organigrama
        $empleados = empleado::with(['cargo', 'departamento'])
            ->where('EMP_ACTIVO', 1)
            ->orderBy('DEP_ID')
            ->get();

        return view('extranet.directorio.organigrama', compact('empleados'));
    }

    public function buscar(Request $request)
    {
        $search = $request->input('q');

        $empleados = empleado::with(['cargo', 'departamento'])
            ->where('EMP_ACTIVO', 1)
            ->where(function($query) use ($search) {
                $query->where('EMP_NOMBRES', 'like', "%{$search}%")
                      ->orWhere('EMP_APELLIDOS', 'like', "%{$search}%")
                      ->orWhere('EMP_CEDULA', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($empleados);
    }

    public function exportarVCard($id)
    {
        $empleado = empleado::with(['cargo', 'departamento'])->findOrFail($id);

        // Generar vCard
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:{$empleado->nombre_completo}\n";
        $vcard .= "N:{$empleado->EMP_APELLIDOS};{$empleado->EMP_NOMBRES};;;\n";
        $vcard .= "ORG:MiPortal\n";
        $vcard .= "TITLE:{$empleado->cargo->CAR_DESCRIPCION}\n";
        $vcard .= "EMAIL:{$empleado->EMP_EMAIL}\n";
        $vcard .= "TEL:{$empleado->EMP_TELEFONO}\n";
        $vcard .= "END:VCARD\n";

        return response($vcard, 200)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="' . $empleado->nombre_completo . '.vcf"');
    }
}
