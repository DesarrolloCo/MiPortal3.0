<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\equipo;
use App\Models\equ_asignado;
use App\Models\empleado;
use App\Models\evi_asignado;
use App\Models\area;
use App\Models\Devolucion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDevolucionRequest;
use App\Http\Requests\StoreAsignacionRequest;
use App\Notifications\EquipoAsignadoNotification;
use App\Notifications\EquipoDevueltoNotification;
use Illuminate\Support\Facades\Notification;
use PDF;

class Equ_asignadoController extends Controller
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

        $sql="SELECT e.EMP_NOMBRES, pc.EQU_ID,pc.EQU_NOMBRE, pc.EQU_SERIAL, EAS_ID, EAS_FECHA_ENTREGA, eq.EAS_ESTADO
        FROM equ_asignados AS eq
        INNER JOIN empleados AS e ON e.EMP_ID = eq.EMP_ID
        INNER JOIN equipos AS pc ON pc.EQU_ID = eq.EQU_ID
        WHERE eq.EAS_ESTADO = 1 OR EAS_ESTADO = 2";

        $sql2="SELECT EMP_ID, EMP_NOMBRES FROM `empleados` WHERE EMP_ESTADO = 1 AND EMP_ACTIVO = 'SI';";

        $sql3="SELECT EQU_ID, EQU_NOMBRE, EQU_SERIAL FROM `equipos` WHERE EQU_ESTADO = 1;";

        $sql4="SELECT TIE_ID, TIE_NOMBRE FROM `tipos_estados` WHERE TIE_ESTADO = 1;";

        $total = equipo::count();

        $equipos = equipo::where('EQU_ESTADO', '=','1')->get();

        $empleado = empleado::where('EMP_ESTADO', '=','1')->get();

        $equ_asignado = DB::select($sql);
        $exc_emp = DB::select($sql2);
        $exc_equ = DB::select($sql3);
        $tipos_estados = DB::select($sql4);
        /* dd($equ_asignado); */
        return view('Inventario.Asignacion_equipo.index', compact('equ_asignado','empleado','equipos','exc_emp','exc_equ','tipos_estados'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Validar que el equipo no esté ya asignado a otro empleado activamente
        $equipoYaAsignado = equ_asignado::where('EQU_ID', $request->EQU_ID)
            ->where('EAS_ESTADO', 1) // Estado activo
            ->exists();

        if ($equipoYaAsignado) {
            return redirect()->back()->with('error', 'Este equipo ya está asignado a otro empleado. Debe registrar la devolución antes de asignarlo nuevamente.');
        }

        // Validar datos básicos
        $request->validate([
            'EQU_ID' => 'required|exists:equipos,EQU_ID',
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'EAS_FECHA_ENTREGA' => 'nullable|date',
        ]);

        try {
            $datosEquAsignado = $request->except('_token');
            $datosEquAsignado['EAS_ESTADO'] = 1; // Estado activo por defecto

            $asignacion = equ_asignado::create($datosEquAsignado);

            // Obtener el empleado y enviar notificación
            $empleado = empleado::find($request->EMP_ID);
            if ($empleado && $empleado->users) {
                $empleado->users->notify(new EquipoAsignadoNotification($asignacion->EAS_ID));
            }

            return redirect()->back()->with('success', 'Asignación registrada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar la asignación: ' . $e->getMessage());
        }

    }

    /**
     * Almacenar evidencia de asignación (MEJORADO: usa filesystem en lugar de BLOB)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function evidencia(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'EAS_ID' => 'required|exists:equ_asignados,EAS_ID',
            'EVI_NOMBRE' => 'required|string|max:200',
            'EVI_FECHA' => 'required|date',
            'EVI_EVIDENCIA' => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            // Almacenar el archivo en el filesystem (storage/app/public/evidencias)
            $archivo = $request->file('EVI_EVIDENCIA');
            $nombreArchivo = time() . '_' . $request->EAS_ID . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('evidencias', $nombreArchivo, 'public');

            // Guardar en la base de datos usando el modelo
            evi_asignado::create([
                'EAS_ID' => $request->EAS_ID,
                'EVI_NOMBRE' => $request->EVI_NOMBRE,
                'EVI_FECHA' => $request->EVI_FECHA,
                'EVI_EVIDENCIA_PATH' => $rutaArchivo, // Nueva columna
                'EVI_ESTADO' => 1,
            ]);

            return redirect()->back()->with('success', 'Evidencia guardada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la evidencia: ' . $e->getMessage());
        }
    }



    /**
     * Store a newly created resource in storage.
     * NOTA: Este método mantiene compatibilidad con formularios antiguos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'EAS_ID' => 'required|exists:equ_asignados,EAS_ID',
            'EVI_NOMBRE' => 'required|string|max:200',
            'EVI_FECHA' => 'required|date',
            'EVI_EVIDENCIA' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $datosEvidencias = $request->except('_token', 'EVI_EVIDENCIA');
            $datosEvidencias['EVI_ESTADO'] = 1;

            // Si hay archivo, guardarlo en filesystem
            if ($request->hasFile('EVI_EVIDENCIA')) {
                $archivo = $request->file('EVI_EVIDENCIA');
                $nombreArchivo = time() . '_' . $request->EAS_ID . '_' . $archivo->getClientOriginalName();
                $rutaArchivo = $archivo->storeAs('evidencias', $nombreArchivo, 'public');
                $datosEvidencias['EVI_EVIDENCIA_PATH'] = $rutaArchivo;
            }

            evi_asignado::create($datosEvidencias);

            return redirect()->back()->with('success', 'Evidencia cargada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar la evidencia: ' . $e->getMessage());
        }
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
        equ_asignado::where('EAS_ID', $id)->update(['EAS_ESTADO' => '2']);
        return redirect()->back()->with('msjdelete', 'Asignacion removida correctamente!...');
    }

    /**
     * Procesar la devolución de un equipo asignado
     *
     * @param  \App\Http\Requests\StoreDevolucionRequest  $request
     * @param  int  $id  ID de la asignación
     * @return \Illuminate\Http\Response
     */
    public function devolver(StoreDevolucionRequest $request, $id)
    {
        // Validar que la asignación exista y esté activa
        $asignacion = equ_asignado::where('EAS_ID', $id)
            ->where('EAS_ESTADO', 1)
            ->first();

        if (!$asignacion) {
            return redirect()->back()->with('error', 'La asignación no existe o ya fue devuelta.');
        }

        // Validar que no exista ya una devolución para esta asignación
        $devolucionExistente = Devolucion::where('EAS_ID', $id)
            ->where('DEV_ESTADO', 1)
            ->exists();

        if ($devolucionExistente) {
            return redirect()->back()->with('warning', 'Este equipo ya tiene una devolución registrada.');
        }

        // Los datos ya están validados por StoreDevolucionRequest
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Crear el registro de devolución
            $devolucion = Devolucion::create([
                'EAS_ID' => $id,
                'DEV_FECHA_DEVOLUCION' => $validated['DEV_FECHA_DEVOLUCION'],
                'DEV_RECIBIDO_POR' => $validated['DEV_RECIBIDO_POR'] ?? null,
                'DEV_ESTADO_EQUIPO' => $validated['DEV_ESTADO_EQUIPO'],
                'DEV_HARDWARE_COMPLETO' => $request->has('DEV_HARDWARE_COMPLETO') ? 1 : 0,
                'DEV_SOFTWARE_COMPLETO' => $request->has('DEV_SOFTWARE_COMPLETO') ? 1 : 0,
                'DEV_OBSERVACIONES' => $validated['DEV_OBSERVACIONES'] ?? null,
                'DEV_DANOS_REPORTADOS' => $validated['DEV_DANOS_REPORTADOS'] ?? null,
                'DEV_FALTANTES' => $validated['DEV_FALTANTES'] ?? null,
                'DEV_USER_ID' => Auth::id(),
                'DEV_ESTADO' => 1, // Activo
            ]);

            // Actualizar el estado de la asignación a "devuelto" (2)
            equ_asignado::where('EAS_ID', $id)->update(['EAS_ESTADO' => 2]);

            DB::commit();

            // Enviar notificación al usuario que devolvió y al que recibió
            $empleadoDevuelve = $asignacion->empleado;
            if ($empleadoDevuelve && $empleadoDevuelve->users) {
                $empleadoDevuelve->users->notify(new EquipoDevueltoNotification($devolucion->DEV_ID));
            }

            // También notificar a administradores o personal de inventario si está configurado
            // Notification::send(User::role('admin')->get(), new EquipoDevueltoNotification($devolucion->DEV_ID));

            // Pasar el ID de la devolución para que se pueda descargar el PDF
            return redirect()->back()
                ->with('success', 'Devolución registrada exitosamente.')
                ->with('devolucion_id', $devolucion->DEV_ID);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF del acta de devolución
     *
     * @param  int  $id  ID de la devolución
     * @return \Illuminate\Http\Response
     */
    public function generarActaDevolucion($id)
    {
        // Cargar la devolución con todas sus relaciones
        $devolucion = Devolucion::with(['asignacion', 'recibidoPor', 'usuario'])->findOrFail($id);

        // Obtener información adicional de la asignación
        $sql = "SELECT e.EMP_NOMBRES, e.EMP_CEDULA, e.EMP_EMAIL, e.EMP_TELEFONO,
                eq.EQU_NOMBRE, eq.EQU_MARCA, eq.EQU_MODELO, eq.EQU_SERIAL, eq.EQU_DESCRIPCION,
                ea.EAS_FECHA_ENTREGA
                FROM equ_asignados AS ea
                INNER JOIN empleados AS e ON e.EMP_ID = ea.EMP_ID
                INNER JOIN equipos AS eq ON eq.EQU_ID = ea.EQU_ID
                WHERE ea.EAS_ID = ?";

        $datosAsignacion = DB::select($sql, [$devolucion->EAS_ID]);

        if (empty($datosAsignacion)) {
            return redirect()->back()->with('error', 'No se encontró información de la asignación.');
        }

        $datosAsignacion = $datosAsignacion[0];

        // Preparar datos para la vista del PDF
        $data = [
            'devolucion' => $devolucion,
            'empleado' => [
                'nombre' => $datosAsignacion->EMP_NOMBRES,
                'cedula' => $datosAsignacion->EMP_CEDULA,
                'email' => $datosAsignacion->EMP_EMAIL,
                'telefono' => $datosAsignacion->EMP_TELEFONO,
            ],
            'equipo' => [
                'nombre' => $datosAsignacion->EQU_NOMBRE,
                'marca' => $datosAsignacion->EQU_MARCA,
                'modelo' => $datosAsignacion->EQU_MODELO,
                'serial' => $datosAsignacion->EQU_SERIAL,
                'descripcion' => $datosAsignacion->EQU_DESCRIPCION,
            ],
            'fecha_entrega' => $datosAsignacion->EAS_FECHA_ENTREGA,
            'fecha_devolucion' => $devolucion->DEV_FECHA_DEVOLUCION,
            'recibido_por' => $devolucion->recibidoPor ? $devolucion->recibidoPor->EMP_NOMBRES : 'N/A',
            'registrado_por' => $devolucion->usuario ? $devolucion->usuario->name : 'Sistema',
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
        ];

        // Generar PDF
        $pdf = \PDF::loadView('Inventario.Asignacion_equipo.acta_devolucion_pdf', $data);

        // Retornar PDF para descarga
        return $pdf->download('Acta_Devolucion_' . $devolucion->DEV_ID . '.pdf');
    }
}
