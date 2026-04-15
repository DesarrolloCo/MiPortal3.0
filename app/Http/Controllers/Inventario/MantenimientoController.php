<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\equipo;
use App\Models\area;
use App\Models\empleado;
use App\Models\mantenimiento;
use App\Models\tipo_mantenimiento;
use App\Models\tip_asignado;
use App\Models\man_asignado;
use App\Models\tecnico;

class MantenimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Usar Eloquent en lugar de SQL raw para mejor seguridad y mantenibilidad
        $tabla_mantenimiento = mantenimiento::select(
                'mantenimientos.MAN_ID',
                'mantenimientos.MAN_FECHA',
                'mantenimientos.MAN_PROVEEDOR',
                'mantenimientos.MAN_TECNICO',
                'mantenimientos.MAN_STATUS',
                'mantenimientos.EQU_ID',
                'empleados.EMP_NOMBRES',
                'areas.ARE_NOMBRE',
                'equipos.EQU_NOMBRE'
            )
            ->join('empleados', 'empleados.EMP_ID', '=', 'mantenimientos.MAN_TECNICO')
            ->join('equipos', 'equipos.EQU_ID', '=', 'mantenimientos.EQU_ID')
            ->join('areas', 'areas.ARE_ID', '=', 'equipos.ARE_ID')
            ->where('mantenimientos.MAN_ESTADO', 1)
            ->where('mantenimientos.MAN_STATUS', 1)
            ->orderBy('mantenimientos.MAN_FECHA', 'asc')
            ->get();

        $area = area::where('ARE_ESTADO', 1)->orderBy('ARE_NOMBRE')->get();

        // Usar Eloquent para obtener técnicos
        $tec_asignados = empleado::select('empleados.EMP_ID', 'empleados.EMP_NOMBRES')
            ->join('tecnicos', 'tecnicos.EMP_ID', '=', 'empleados.EMP_ID')
            ->where('tecnicos.TEC_ESTADO', 1)
            ->orderBy('empleados.EMP_NOMBRES')
            ->get();

        $mantenimiento_fisicos = tipo_mantenimiento::where('TIP_ESTADO', 1)
            ->where('TIP_TIPO', 'Fisico')
            ->orderBy('TIP_NOMBRE')
            ->get();

        $mantenimiento_logicos = tipo_mantenimiento::where('TIP_ESTADO', 1)
            ->where('TIP_TIPO', 'Logico')
            ->orderBy('TIP_NOMBRE')
            ->get();

        return view('Inventario.Mantenimiento.index', compact('area','tec_asignados','tabla_mantenimiento','mantenimiento_fisicos','mantenimiento_logicos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Validar datos de entrada
        $validated = $request->validate([
            'MAN_FECHA' => 'required|date|after_or_equal:today',
            'MAN_PROVEEDOR' => 'required|string|max:255',
            'MAN_TECNICO' => 'required|exists:empleados,EMP_ID',
            'EQU_IDS' => 'required|array|min:1',
            'EQU_IDS.*' => 'exists:equipos,EQU_ID'
        ], [
            'MAN_FECHA.required' => 'La fecha de mantenimiento es requerida',
            'MAN_FECHA.after_or_equal' => 'La fecha debe ser igual o posterior a hoy',
            'MAN_PROVEEDOR.required' => 'El proveedor es requerido',
            'MAN_TECNICO.required' => 'Debe seleccionar un técnico',
            'MAN_TECNICO.exists' => 'El técnico seleccionado no existe',
            'EQU_IDS.required' => 'Debe seleccionar al menos un equipo',
            'EQU_IDS.*.exists' => 'Uno de los equipos seleccionados no existe'
        ]);

        return DB::transaction(function () use ($validated) {
            $mantenimientosCreados = [];

            foreach($validated['EQU_IDS'] as $equipoId) {
                $mantenimiento = mantenimiento::create([
                    'EQU_ID' => $equipoId,
                    'MAN_PROVEEDOR' => $validated['MAN_PROVEEDOR'],
                    'MAN_FECHA' => $validated['MAN_FECHA'],
                    'MAN_TECNICO' => $validated['MAN_TECNICO'],
                    'MAN_ESTADO' => 1,
                    'MAN_STATUS' => 1 // 1 = Pendiente
                ]);

                $mantenimientosCreados[] = $mantenimiento;
            }

            // TODO: Implementar notificación MantenimientoProximoNotification aquí

            $cantidadEquipos = count($mantenimientosCreados);
            $mensaje = $cantidadEquipos === 1
                ? 'Plan de mantenimiento creado exitosamente'
                : "Planes de mantenimiento creados para {$cantidadEquipos} equipos";

            return redirect()->route('Mantenimiento.index')->with('success', $mensaje);
        });
    }

    public function maintenance(Request $request)
    {
        // Validar datos de entrada
        $validated = $request->validate([
            'MAN_ID' => 'required|exists:mantenimientos,MAN_ID',
            'EQU_ID' => 'required|exists:equipos,EQU_ID',
            'MAN_PROVEEDOR' => 'required|string|max:255',
            'MAN_FECHA' => 'required|date',
            'MAN_TECNICO' => 'required|exists:empleados,EMP_ID',
            'MAS_TIPO' => 'required|in:Preventivo,Correctivo,Proveedor',
            'MAS_ACTIVIDAD' => 'required|string',
            'TIP_ID_FIS' => 'nullable|array',
            'TIP_ID_FIS.*' => 'exists:tipo_mantenimientos,TIP_ID',
            'TIP_ID_LOG' => 'nullable|array',
            'TIP_ID_LOG.*' => 'exists:tipo_mantenimientos,TIP_ID'
        ], [
            'MAS_TIPO.required' => 'El tipo de mantenimiento es requerido',
            'MAS_TIPO.in' => 'El tipo de mantenimiento debe ser Preventivo, Correctivo o Proveedor',
            'MAS_ACTIVIDAD.required' => 'Debe describir las actividades realizadas'
        ]);

        return DB::transaction(function () use ($validated) {
            // Registrar actividad del mantenimiento
            $manAsignado = man_asignado::create([
                'MAN_ID' => $validated['MAN_ID'],
                'MAS_TIPO' => $validated['MAS_TIPO'],
                'MAS_ACTIVIDAD' => $validated['MAS_ACTIVIDAD']
            ]);

            // Registrar tipos de mantenimiento físico
            if (!empty($validated['TIP_ID_FIS'])) {
                foreach($validated['TIP_ID_FIS'] as $tipIdFis) {
                    tip_asignado::create([
                        'MAN_ID' => $validated['MAN_ID'],
                        'TIP_ID' => $tipIdFis
                    ]);
                }
            }

            // Registrar tipos de mantenimiento lógico
            if (!empty($validated['TIP_ID_LOG'])) {
                foreach($validated['TIP_ID_LOG'] as $tipIdLog) {
                    tip_asignado::create([
                        'MAN_ID' => $validated['MAN_ID'],
                        'TIP_ID' => $tipIdLog
                    ]);
                }
            }

            // Obtener fecha del próximo mantenimiento (6 meses después usando Eloquent)
            $mantenimientoActual = mantenimiento::where('EQU_ID', $validated['EQU_ID'])
                ->orderBy('MAN_FECHA', 'ASC')
                ->first();

            if ($mantenimientoActual) {
                $fechaProximo = \Carbon\Carbon::parse($mantenimientoActual->MAN_FECHA)
                    ->addMonths(6)
                    ->format('Y-m-d');

                // Marcar mantenimiento actual como completado usando Eloquent
                mantenimiento::where('MAN_ID', $validated['MAN_ID'])
                    ->update(['MAN_STATUS' => 2]); // 2 = Completado

                // Crear nuevo mantenimiento programado usando Eloquent
                $nuevoMantenimiento = mantenimiento::create([
                    'EQU_ID' => $validated['EQU_ID'],
                    'MAN_PROVEEDOR' => $validated['MAN_PROVEEDOR'],
                    'MAN_FECHA' => $fechaProximo,
                    'MAN_TECNICO' => $validated['MAN_TECNICO'],
                    'MAN_ESTADO' => 1,
                    'MAN_STATUS' => 1 // 1 = Pendiente
                ]);

                // TODO: Enviar notificación MantenimientoProximoNotification
                // cuando falten 7 días para el próximo mantenimiento
            }

            return redirect()->route('Mantenimiento.index')
                ->with('success', 'Mantenimiento registrado exitosamente. Se ha programado el próximo mantenimiento.')
                ->with('mantenimiento_id', $validated['MAN_ID']);
        });
    }

    public function details($id)
    {
        // Usar Eloquent para obtener detalles del mantenimiento
        $mantenimiento = mantenimiento::select(
                'mantenimientos.*',
                'equipos.EQU_NOMBRE',
                'equipos.EQU_SERIAL',
                'areas.ARE_NOMBRE',
                'empleados.EMP_NOMBRES as TECNICO_NOMBRE'
            )
            ->join('equipos', 'equipos.EQU_ID', '=', 'mantenimientos.EQU_ID')
            ->join('areas', 'areas.ARE_ID', '=', 'equipos.ARE_ID')
            ->join('empleados', 'empleados.EMP_ID', '=', 'mantenimientos.MAN_TECNICO')
            ->where('mantenimientos.MAN_ID', $id)
            ->firstOrFail();

        $man_asignados = man_asignado::where('MAN_ID', $id)->get();

        // Obtener tipos de mantenimiento asignados
        $tip_asignados = tip_asignado::select('tip_asignados.*', 'tipo_mantenimientos.TIP_NOMBRE', 'tipo_mantenimientos.TIP_TIPO')
            ->join('tipo_mantenimientos', 'tipo_mantenimientos.TIP_ID', '=', 'tip_asignados.TIP_ID')
            ->where('tip_asignados.MAN_ID', $id)
            ->get();

        return view('Inventario.Mantenimiento.details', compact('mantenimiento', 'man_asignados', 'tip_asignados'));
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

    /**
     * Generar reporte PDF de mantenimiento
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarReportePDF($id)
    {
        $mantenimiento = mantenimiento::select(
                'mantenimientos.*',
                'equipos.EQU_NOMBRE',
                'equipos.EQU_SERIAL',
                'equipos.EQU_TIPO',
                'areas.ARE_NOMBRE',
                'empleados.EMP_NOMBRES as TECNICO_NOMBRE',
                'empleados.EMP_CEDULA as TECNICO_CEDULA'
            )
            ->join('equipos', 'equipos.EQU_ID', '=', 'mantenimientos.EQU_ID')
            ->join('areas', 'areas.ARE_ID', '=', 'equipos.ARE_ID')
            ->join('empleados', 'empleados.EMP_ID', '=', 'mantenimientos.MAN_TECNICO')
            ->where('mantenimientos.MAN_ID', $id)
            ->firstOrFail();

        $man_asignados = man_asignado::where('MAN_ID', $id)->get();

        $tip_asignados = tip_asignado::select('tip_asignados.*', 'tipo_mantenimientos.TIP_NOMBRE', 'tipo_mantenimientos.TIP_TIPO')
            ->join('tipo_mantenimientos', 'tipo_mantenimientos.TIP_ID', '=', 'tip_asignados.TIP_ID')
            ->where('tip_asignados.MAN_ID', $id)
            ->get();

        $pdf = \PDF::loadView('Inventario.Mantenimiento.reporte_pdf', compact('mantenimiento', 'man_asignados', 'tip_asignados'));

        $nombreArchivo = 'Reporte_Mantenimiento_' . str_pad($id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Exportar mantenimientos a Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function exportarExcel()
    {
        $mantenimientos = mantenimiento::select(
                'mantenimientos.MAN_ID',
                'mantenimientos.MAN_FECHA',
                'mantenimientos.MAN_PROVEEDOR',
                'mantenimientos.MAN_STATUS',
                'empleados.EMP_NOMBRES as TECNICO',
                'areas.ARE_NOMBRE',
                'equipos.EQU_NOMBRE',
                'equipos.EQU_SERIAL'
            )
            ->join('empleados', 'empleados.EMP_ID', '=', 'mantenimientos.MAN_TECNICO')
            ->join('equipos', 'equipos.EQU_ID', '=', 'mantenimientos.EQU_ID')
            ->join('areas', 'areas.ARE_ID', '=', 'equipos.ARE_ID')
            ->where('mantenimientos.MAN_ESTADO', 1)
            ->orderBy('mantenimientos.MAN_FECHA', 'desc')
            ->get();

        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        $nombreArchivo = "Mantenimientos_{$fecha}.xlsx";

        return \Excel::download(new \App\Exports\MantenimientosExport($mantenimientos), $nombreArchivo);
    }
}
