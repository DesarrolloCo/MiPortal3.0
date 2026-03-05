<?php

namespace App\Http\Controllers\Malla;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\novedade;
use App\Models\tipos_novedade;
use App\Models\empleado;
use App\Models\malla;
use App\Models\NovedadHorario;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class NovedadesController extends Controller
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
        $query = novedade::with(['tipoNovedad', 'empleado', 'usuario', 'aprobadoPor', 'horarios']);

        if ($request->filled('estado_aprobacion')) {
            $query->where('NOV_ESTADO_APROBACION', $request->estado_aprobacion);
        }

        if ($request->filled('tipo_novedad')) {
            $query->where('TIN_ID', $request->tipo_novedad);
        }

        if ($request->filled('empleado')) {
            $query->whereHas('empleado', function ($q) use ($request) {
                $q->where('EMP_NOMBRES', 'LIKE', '%' . $request->empleado . '%');
            });
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('NOV_FECHA', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->whereBetween('MAL_DIA', [$request->fecha_inicio, $request->fecha_fin]);
                    });
            });
        }

        $novedades = $query->orderBy('created_at', 'desc')->get();

        $estadisticas = [
            'pendientes' => novedade::pendientes()->count(),
            'aprobadas' => novedade::aprobadas()->count(),
            'rechazadas' => novedade::rechazadas()->count(),
            'total' => novedade::count()
        ];

        $tiposNovedades = tipos_novedade::activos()->get();

        return view('Malla.Novedades.index', compact('novedades', 'estadisticas', 'tiposNovedades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $tiposNovedades = tipos_novedade::activos()->get();
        $empleados = empleado::where('EMP_ESTADO', 1)->orderBy('EMP_NOMBRES')->get();

        // Datos del horario si viene desde la desactivación
        $datosHorario = null;
        if ($request->has('accion') && $request->accion === 'desactivar_horario') {
            $datosHorario = [
                'mal_id' => $request->mal_id,
                'emp_id' => $request->emp_id,
                'mal_dia' => $request->mal_dia,
                'cliente' => $request->cliente,
                'campana' => $request->campana,
                'horario_inicio' => $request->horario_inicio,
                'horario_final' => $request->horario_final,
                'accion' => $request->accion
            ];
        }

        return view('Malla.Novedades.create', compact('tiposNovedades', 'empleados', 'datosHorario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TIN_ID' => 'required|exists:tipos_novedades,TIN_ID',
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'NOV_DESCRIPCION' => 'required|string',
            'NOV_FECHA' => 'nullable|date',
            'horarios' => 'nullable|array',
            'horarios.*' => 'integer|exists:mallas,MAL_ID',
            'archivos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $horariosSeleccionados = collect($request->input('horarios', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique();

        if ((!$request->has('accion') || $request->accion !== 'desactivar_horario') && $horariosSeleccionados->isEmpty()) {
            return back()->withErrors(['horarios' => 'Debe seleccionar al menos un horario'])->withInput();
        }

        $archivos = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $archivos[] = [
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'contenido_binario' => base64_encode(file_get_contents($archivo->getPathname())),
                    'size' => $archivo->getSize(),
                    'tipo' => $archivo->getMimeType(),
                    'fecha_subida' => Carbon::now('America/Bogota')->toDateTimeString()
                ];
            }
        }

        $fechaReferencia = $request->NOV_FECHA;

        if (!$fechaReferencia && $horariosSeleccionados->isNotEmpty()) {
            $fechaReferencia = malla::whereIn('MAL_ID', $horariosSeleccionados)->min('MAL_DIA');
        }

        if (!$fechaReferencia && $request->accion === 'desactivar_horario') {
            $fechaReferencia = $request->mal_dia;
        }

        $fechaReferencia = $fechaReferencia
            ? Carbon::parse($fechaReferencia, 'America/Bogota')->format('Y-m-d')
            : Carbon::now('America/Bogota')->toDateString();

        $novedadData = [
            'TIN_ID' => $request->TIN_ID,
            'EMP_ID' => $request->EMP_ID,
            'NOV_DESCRIPCION' => $request->NOV_DESCRIPCION,
            'NOV_FECHA' => $fechaReferencia,
            'NOV_ARCHIVOS' => json_encode($archivos),
            'USER_ID' => Auth::id(),
            'NOV_ESTADO' => 1,
            'NOV_ESTADO_APROBACION' => 'pendiente'
        ];

        $novedad = novedade::create($novedadData);

        if ($horariosSeleccionados->isNotEmpty()) {
            $novedad->horarios()->syncWithoutDetaching($horariosSeleccionados->toArray());
        }

        // Si es desactivación de horario, registrar la relación y desactivar la malla
        if ($request->has('accion') && $request->accion === 'desactivar_horario' && $request->filled('mal_id')) {
            NovedadHorario::create([
                'nov_id' => $novedad->NOV_ID,
                'mal_id' => $request->mal_id
            ]);

            malla::where('MAL_ID', $request->mal_id)->update(['MAL_ESTADO' => 0]);

            return redirect()->route('Individual.redirect', [
                'EMP_ID' => $request->EMP_ID,
                'FECHA' => $request->mal_dia
            ])->with('success', 'Novedad registrada y horario desactivado exitosamente');
        }

        return redirect()->route('Novedades.index')
            ->with('success', 'Novedad registrada exitosamente');
    }

    public function horariosEmpleado(Request $request, int $empleadoId): JsonResponse
    {
        $fecha = $request->query('fecha');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');

        $query = malla::with([
            'campana',
            'campana.unidadNegocioCliente.cliente'
        ])->where('EMP_ID', $empleadoId)
            ->where('MAL_ESTADO', 1);

        if ($fechaInicio && $fechaFin) {
            if ($fechaInicio > $fechaFin) {
                [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
            }
            $query->whereBetween('MAL_DIA', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->whereDate('MAL_DIA', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->whereDate('MAL_DIA', '<=', $fechaFin);
        } elseif ($fecha) {
            $query->whereDate('MAL_DIA', $fecha);
        }

        $horarios = $query->orderBy('MAL_DIA')
            ->orderBy('MAL_INICIO')
            ->get()
            ->map(function ($horario) {
                return [
                    'id' => $horario->MAL_ID,
                    'fecha' => $horario->MAL_DIA,
                    'fecha_formateada' => $horario->MAL_DIA ? Carbon::parse($horario->MAL_DIA)->format('d/m/Y') : null,
                    'hora_inicio' => $horario->MAL_INICIO,
                    'hora_inicio_formateada' => $horario->MAL_INICIO ? Carbon::parse($horario->MAL_INICIO)->format('H:i') : null,
                    'hora_fin' => $horario->MAL_FINAL,
                    'hora_fin_formateada' => $horario->MAL_FINAL ? Carbon::parse($horario->MAL_FINAL)->format('H:i') : null,
                    'cliente' => $horario->campana?->unidadNegocioCliente?->cliente?->CLI_NOMBRE ?? 'N/A',
                    'campana' => $horario->campana?->CAM_NOMBRE ?? 'N/A',
                    'estado' => $horario->MAL_ESTADO,
                ];
            })->values();

        return response()->json([
            'data' => $horarios
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $novedad = novedade::with(['tipoNovedad', 'empleado', 'usuario', 'aprobadoPor', 'horarios'])->findOrFail($id);
        return view('Malla.Novedades.show', compact('novedad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $novedad = novedade::findOrFail($id);
        $tiposNovedades = tipos_novedade::activos()->get();
        $empleados = empleado::where('EMP_ESTADO', 1)->orderBy('EMP_NOMBRES')->get();

        return view('Malla.Novedades.edit', compact('novedad', 'tiposNovedades', 'empleados'));
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
        $novedad = novedade::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'TIN_ID' => 'required|exists:tipos_novedades,TIN_ID',
            'EMP_ID' => 'required|exists:empleados,EMP_ID',
            'NOV_DESCRIPCION' => 'required|string',
            'NOV_FECHA' => 'nullable|date',
            'archivos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $archivos = $novedad->archivos_lista;

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $archivos[] = [
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'contenido_binario' => base64_encode(file_get_contents($archivo->getPathname())),
                    'size' => $archivo->getSize(),
                    'tipo' => $archivo->getMimeType(),
                    'fecha_subida' => now()->toDateTimeString()
                ];
            }
        }

        $novedad->update([
            'TIN_ID' => $request->TIN_ID,
            'EMP_ID' => $request->EMP_ID,
            'NOV_DESCRIPCION' => $request->NOV_DESCRIPCION,
            'NOV_FECHA' => $request->NOV_FECHA ?? $novedad->NOV_FECHA,
            'NOV_ARCHIVOS' => json_encode($archivos)
        ]);

        return redirect()->route('Novedades.show', $novedad->NOV_ID)
            ->with('success', 'Novedad actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $novedad = novedade::findOrFail($id);

        if ($novedad->NOV_ESTADO_APROBACION !== 'pendiente') {
            return back()->with('error', 'No se puede eliminar una novedad que ya ha sido procesada');
        }


        $novedad->delete();

        return redirect()->route('Novedades.index')
            ->with('success', 'Novedad eliminada exitosamente');
    }

    public function aprobar($id)
    {
        $novedad = novedade::findOrFail($id);

        $novedad->update([
            'NOV_ESTADO_APROBACION' => 'aprobada',
            'NOV_APROBADO_POR' => Auth::id(),
            'NOV_FECHA_APROBACION' => now()
        ]);

        return back()->with('success', 'Novedad aprobada exitosamente');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|max:500'
        ]);

        $novedad = novedade::findOrFail($id);

        $novedad->update([
            'NOV_ESTADO_APROBACION' => 'rechazada',
            'NOV_OBSERVACIONES' => $request->observaciones,
            'NOV_APROBADO_POR' => Auth::id(),
            'NOV_FECHA_APROBACION' => now()
        ]);

        return back()->with('success', 'Novedad rechazada exitosamente');
    }

    public function aprobarMasivo(Request $request)
    {
        $idsString = $request->input('novedades_ids', '');
        $ids = !empty($idsString) ? explode(',', $idsString) : [];

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron novedades');
        }

        $count = novedade::whereIn('NOV_ID', $ids)
            ->where('NOV_ESTADO_APROBACION', 'pendiente')
            ->update([
                'NOV_ESTADO_APROBACION' => 'aprobada',
                'NOV_APROBADO_POR' => Auth::id(),
                'NOV_FECHA_APROBACION' => now()
            ]);

        return back()->with('success', "Se aprobaron $count novedades exitosamente");
    }

    public function rechazarMasivo(Request $request)
    {
        $request->validate([
            'observaciones' => 'required|string|max:1000'
        ]);

        $idsString = $request->input('novedades_ids', '');
        $ids = !empty($idsString) ? explode(',', $idsString) : [];

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron novedades');
        }

        $count = novedade::whereIn('NOV_ID', $ids)
            ->where('NOV_ESTADO_APROBACION', 'pendiente')
            ->update([
                'NOV_ESTADO_APROBACION' => 'rechazada',
                'NOV_OBSERVACIONES' => $request->observaciones,
                'NOV_APROBADO_POR' => Auth::id(),
                'NOV_FECHA_APROBACION' => now()
            ]);

        return back()->with('success', "Se rechazaron $count novedades exitosamente");
    }

    public function gestionHumana()
    {
        $novedadesPendientes = novedade::with(['tipoNovedad', 'empleado', 'usuario', 'horarios'])
            ->pendientes()
            ->orderBy('created_at', 'asc')
            ->get();

        $estadisticas = [
            'pendientes' => novedade::pendientes()->count(),
            'aprobadas' => novedade::aprobadas()->count(),
            'rechazadas' => novedade::rechazadas()->count(),
            'total' => novedade::count()
        ];

        return view('Malla.Novedades.gestion', compact('novedadesPendientes', 'estadisticas'));
    }

    public function dashboard()
    {
        $estadisticas = [
            'pendientes' => novedade::pendientes()->count(),
            'aprobadas' => novedade::aprobadas()->count(),
            'rechazadas' => novedade::rechazadas()->count(),
            'total' => novedade::count()
        ];

        // Novedades por mes del año actual
        $novedadesPorMes = novedade::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Novedades por tipo con información del tipo
        $novedadesPorTipo = novedade::with('tipoNovedad')
            ->selectRaw('TIN_ID, COUNT(*) as total')
            ->groupBy('TIN_ID')
            ->get();

        // Estadísticas adicionales para HR
        $estadisticasAvanzadas = [
            // Novedades por estado en los últimos 30 días
            'ultimo_mes' => [
                'pendientes' => novedade::pendientes()->where('created_at', '>=', now()->subDays(30))->count(),
                'aprobadas' => novedade::aprobadas()->where('created_at', '>=', now()->subDays(30))->count(),
                'rechazadas' => novedade::rechazadas()->where('created_at', '>=', now()->subDays(30))->count(),
            ],

            // Tiempo promedio de aprobación (en días)
            'tiempo_promedio_aprobacion' => novedade::whereNotNull('NOV_FECHA_APROBACION')
                ->selectRaw('AVG(DATEDIFF(NOV_FECHA_APROBACION, created_at)) as promedio')
                ->value('promedio') ?? 0,

            // Top 5 empleados con más novedades
            'empleados_mas_novedades' => novedade::with('empleado')
                ->selectRaw('EMP_ID, COUNT(*) as total')
                ->groupBy('EMP_ID')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),

            // Novedades por día de la semana
            'por_dia_semana' => novedade::selectRaw('DAYOFWEEK(created_at) as dia, COUNT(*) as total')
                ->groupBy('dia')
                ->orderBy('dia')
                ->get(),

            // Estadísticas por mes con estado
            'por_mes_estado' => novedade::selectRaw('MONTH(created_at) as mes, NOV_ESTADO_APROBACION, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('mes', 'NOV_ESTADO_APROBACION')
                ->orderBy('mes')
                ->get(),

            // Novedades que requieren atención urgente (más de 7 días pendientes)
            'urgentes' => novedade::pendientes()
                ->where('created_at', '<=', now()->subDays(7))
                ->count(),

            // Comparación con el año anterior
            'comparacion_anual' => [
                'actual' => novedade::whereYear('created_at', date('Y'))->count(),
                'anterior' => novedade::whereYear('created_at', date('Y') - 1)->count(),
            ]
        ];

        return view('Malla.Novedades.dashboard', compact('estadisticas', 'novedadesPorMes', 'novedadesPorTipo', 'estadisticasAvanzadas'));
    }

    public function exportarExcel2(Request $request)
    {
        $query = novedade::with(['tipoNovedad', 'empleado', 'usuario', 'aprobadoPor', 'horarios']);

        if ($request->filled('estado_aprobacion') && $request->estado_aprobacion !== '') {
            $query->where('NOV_ESTADO_APROBACION', $request->estado_aprobacion);
        }
        // Si no se selecciona estado específico, exporta TODOS los estados

        if ($request->filled('tipo_novedad')) {
            $query->where('TIN_ID', $request->tipo_novedad);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('NOV_FECHA', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->whereBetween('MAL_DIA', [$request->fecha_inicio, $request->fecha_fin]);
                    });
            });
        } elseif ($request->filled('fecha_inicio')) {
            $query->where(function ($q) use ($request) {
                $q->where('NOV_FECHA', '>=', $request->fecha_inicio)
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->where('MAL_DIA', '>=', $request->fecha_inicio);
                    });
            });
        } elseif ($request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->where('NOV_FECHA', '<=', $request->fecha_fin)
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->where('MAL_DIA', '<=', $request->fecha_fin);
                    });
            });
        }

        $novedades = $query->orderBy('NOV_ID', 'desc')->get();

        // Debug: verificar cuántas novedades se obtuvieron
        if ($novedades->isEmpty()) {
            // Si no hay novedades, crear un Excel con mensaje informativo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Novedades Nómina');
            $sheet->setCellValue('A1', 'No se encontraron novedades con los filtros aplicados');
            $sheet->setCellValue('A2', 'Estado: ' . ($request->estado_aprobacion ?: 'Todos'));
            $sheet->setCellValue('A3', 'Tipo: ' . ($request->tipo_novedad ?: 'Todos'));
            $sheet->setCellValue('A4', 'Fecha inicio: ' . ($request->fecha_inicio ?: 'Sin filtro'));
            $sheet->setCellValue('A5', 'Fecha fin: ' . ($request->fecha_fin ?: 'Sin filtro'));

            $filename = 'novedades_nomina_vacio_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Novedades Nómina');

        // Configurar información del documento
        $spreadsheet->getProperties()
            ->setCreator('Mi Portal 2.0')
            ->setLastModifiedBy('Sistema de Novedades')
            ->setTitle('Reporte de Novedades de Nómina')
            ->setSubject('Exportación de Novedades')
            ->setDescription('Reporte detallado de novedades de nómina generado automáticamente');

        // Título del reporte
        $sheet->setCellValue('A1', 'REPORTE DE NOVEDADES DE NÓMINA');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Información de filtros aplicados
        $filtrosInfo = [];
        if ($request->filled('estado_aprobacion') && $request->estado_aprobacion !== '') {
            $filtrosInfo[] = 'Estado: ' . ucfirst($request->estado_aprobacion);
        } else {
            $filtrosInfo[] = 'Estado: Todos';
        }

        if ($request->filled('tipo_novedad')) {
            $tipoNombre = tipos_novedade::find($request->tipo_novedad)->TIN_NOMBRE ?? 'N/A';
            $filtrosInfo[] = 'Tipo: ' . $tipoNombre;
        } else {
            $filtrosInfo[] = 'Tipo: Todos';
        }

        if ($request->filled('fecha_inicio')) {
            $filtrosInfo[] = 'Desde: ' . $request->fecha_inicio;
        }
        if ($request->filled('fecha_fin')) {
            $filtrosInfo[] = 'Hasta: ' . $request->fecha_fin;
        }

        $sheet->setCellValue('A2', 'Filtros aplicados: ' . implode(' | ', $filtrosInfo));
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Generado el: ' . date('d/m/Y H:i') . ' | Total de registros: ' . $novedades->count());
        $sheet->mergeCells('A3:L3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers de las columnas
        $headers = [
            'A4' => 'ID',
            'B4' => 'Empleado',
            'C4' => 'Identificación',
            'D4' => 'Tipo Novedad',
            'E4' => 'Descripción',
            'F4' => 'Fecha Inicio',
            'G4' => 'Fecha Fin',
            'H4' => 'Hora Inicio',
            'I4' => 'Hora Fin',
            'J4' => 'Estado',
            'K4' => 'Aprobado Por',
            'L4' => 'Fecha Aprobación'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Estilo para los headers
        $sheet->getStyle('A4:L4')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A4:L4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE7E6E6');
        $sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:L4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $fila = 5; // Empezar en la fila 5 después del header
        foreach ($novedades as $novedad) {
            $sheet->setCellValue('A' . $fila, $novedad->NOV_ID);
            $sheet->setCellValue('B' . $fila, ($novedad->empleado->EMP_NOMBRES ?? '') . ' ' . ($novedad->empleado->EMP_APELLIDOS ?? ''));
            $sheet->setCellValue('C' . $fila, $novedad->empleado->EMP_CEDULA ?? 'N/A');
            $sheet->setCellValue('D' . $fila, $novedad->tipoNovedad->TIN_NOMBRE ?? 'N/A');
            $sheet->setCellValue('E' . $fila, $novedad->NOV_DESCRIPCION);
            $sheet->setCellValue('F' . $fila, optional($novedad->nov_fecha_inicio)->format('d/m/Y') ?? '');
            $sheet->setCellValue('G' . $fila, optional($novedad->nov_fecha_fin)->format('d/m/Y') ?? '');
            $sheet->setCellValue('H' . $fila, optional($novedad->nov_hora_inicio)->format('H:i') ?? '');
            $sheet->setCellValue('I' . $fila, optional($novedad->nov_hora_fin)->format('H:i') ?? '');

            // Estado con color
            $estado = ucfirst($novedad->NOV_ESTADO_APROBACION);
            $sheet->setCellValue('J' . $fila, $estado);

            // Aplicar color según el estado
            switch ($novedad->NOV_ESTADO_APROBACION) {
                case 'aprobada':
                    $sheet->getStyle('J' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD5F4E6');
                    $sheet->getStyle('J' . $fila)->getFont()->getColor()->setARGB('FF0F5132');
                    break;
                case 'rechazada':
                    $sheet->getStyle('J' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8D7DA');
                    $sheet->getStyle('J' . $fila)->getFont()->getColor()->setARGB('FF721C24');
                    break;
                case 'pendiente':
                    $sheet->getStyle('J' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF3CD');
                    $sheet->getStyle('J' . $fila)->getFont()->getColor()->setARGB('FF856404');
                    break;
            }

            $sheet->setCellValue('K' . $fila, $novedad->aprobadoPor->name ?? '');
            $sheet->setCellValue('L' . $fila, $novedad->NOV_FECHA_APROBACION ? $novedad->NOV_FECHA_APROBACION->format('d/m/Y H:i') : '');

            // Bordes para las filas de datos
            $sheet->getStyle('A' . $fila . ':L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alternar colores de fila
            if ($fila % 2 == 0) {
                $sheet->getStyle('A' . $fila . ':L' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');
            }

            $fila++;
        }

        // Ajustar ancho de columnas automáticamente
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Establecer algunos anchos específicos para columnas que pueden ser muy anchas
        $sheet->getColumnDimension('E')->setWidth(40); // Descripción
        $sheet->getColumnDimension('B')->setWidth(25); // Empleado

        // Congelar la primera fila de headers
        $sheet->freezePane('A5');

        $filename = 'novedades_nomina_' . $novedades->count() . '_registros_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function exportarExcel(Request $request)
    {
        $query = novedade::with(['tipoNovedad', 'empleado', 'usuario', 'aprobadoPor', 'horarios'])
            ->whereNotNull('EMP_ID');

        if ($request->filled('estado_aprobacion') && $request->estado_aprobacion !== '') {
            $query->where('NOV_ESTADO_APROBACION', $request->estado_aprobacion);
        }
        // Si no se selecciona estado específico, exporta TODOS los estados

        if ($request->filled('tipo_novedad')) {
            $query->where('TIN_ID', $request->tipo_novedad);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('NOV_FECHA', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->whereBetween('MAL_DIA', [$request->fecha_inicio, $request->fecha_fin]);
                    });
            });
        } elseif ($request->filled('fecha_inicio')) {
            $query->where(function ($q) use ($request) {
                $q->where('NOV_FECHA', '>=', $request->fecha_inicio)
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->where('MAL_DIA', '>=', $request->fecha_inicio);
                    });
            });
        } elseif ($request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->where('NOV_FECHA', '<=', $request->fecha_fin)
                    ->orWhereHas('horarios', function ($horariosQuery) use ($request) {
                        $horariosQuery->where('MAL_DIA', '<=', $request->fecha_fin);
                    });
            });
        }

        $novedades = $query->orderBy('NOV_ID', 'desc')->get();

        // Debug: verificar cuántas novedades se obtuvieron
        if ($novedades->isEmpty()) {
            // Si no hay novedades, crear un Excel con mensaje informativo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Novedades Nómina');
            $sheet->setCellValue('A1', 'No se encontraron novedades con los filtros aplicados');
            $sheet->setCellValue('A2', 'Estado: ' . ($request->estado_aprobacion ?: 'Todos'));
            $sheet->setCellValue('A3', 'Tipo: ' . ($request->tipo_novedad ?: 'Todos'));
            $sheet->setCellValue('A4', 'Fecha inicio: ' . ($request->fecha_inicio ?: 'Sin filtro'));
            $sheet->setCellValue('A5', 'Fecha fin: ' . ($request->fecha_fin ?: 'Sin filtro'));

            $filename = 'novedades_nomina_vacio_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Novedades Nómina');

        // Configurar información del documento
        $spreadsheet->getProperties()
            ->setCreator('Mi Portal 2.0')
            ->setLastModifiedBy('Sistema de Novedades')
            ->setTitle('Reporte de Novedades de Nómina')
            ->setSubject('Exportación de Novedades')
            ->setDescription('Reporte detallado de novedades de nómina generado automáticamente');

        $sheet->setCellValue('A1', 'TECNOLOGIAS Y SERVICIOS CONTACTA S.A.S BIC');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->setCellValue('A2', 'INTERFACE DE NOVEDADES DE NOMINA 00' . Carbon::now('America/Bogota')->format('Ymd'));
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A2')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->setCellValue('A3', '');
        $sheet->mergeCells('A3:L3');
        $sheet->getStyle('A3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A3')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->setCellValue('A4', '');
        $sheet->mergeCells('A4:L4');

        $sheet->getStyle('A1:L4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Headers de las columnas
        $headers = [
            'A5' => 'NIT',
            'B5' => 'SUCURSAL',
            'C5' => 'CONCEPTO',
            'D5' => 'CENTRO DE COSTO',
            'E5' => 'SUB CENTRO',
            'F5' => 'VARIABLE',
            'G5' => 'VALOR',
            'H5' => 'TIPO',
            'I5' => 'COMPROBANTE',
            'J5' => 'NUMERO',
            'K5' => 'SECUENCIA',
            'L5' => 'GRUPO'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Estilo para los headers
        $sheet->getStyle('A5:L5')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A5:L5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE7E6E6');
        $sheet->getStyle('A5:L5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:L5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $fila = 6; // Empezar en la fila 6 después del header
        foreach ($novedades as $novedad) {
            // Debug: verificar si la relación empleado existe y qué datos tiene
            if ($novedad->empleado) {
                $empleadoCedula = $novedad->empleado->EMP_CEDULA ?? 'SIN_CEDULA';
                // También podríamos usar EMP_CODE si está disponible
                // $empleadoCedula = $novedad->empleado->EMP_CODE ?? $novedad->empleado->EMP_CEDULA ?? '000000000-0';
            } else {
                $empleadoCedula = 'SIN_EMPLEADO';
            }
            $sheet->setCellValue('A' . $fila, $empleadoCedula);
            $sheet->setCellValue('B' . $fila, 0);
            $sheet->setCellValue('C' . $fila, $novedad->tipoNovedad->COD_SIIGO ?? '');
            $sheet->setCellValue('D' . $fila, $novedad->empleado->campana->unidadNegocioCliente->unidadNegocio->UNI_CODE ?? '');
            $sheet->setCellValue('E' . $fila, $novedad->empleado->campana->unidadNegocioCliente->cliente->CLI_CODE ?? '');
            $sheet->setCellValue('F' . $fila, '');
            $sheet->setCellValue('G' . $fila, '');
            $sheet->setCellValue('H' . $fila, '');
            $sheet->setCellValue('I' . $fila, '');
            $sheet->setCellValue('J' . $fila, 0);
            $sheet->setCellValue('K' . $fila, 0);
            $sheet->setCellValue('L' . $fila, 51);

            // Bordes para las filas de datos
            $sheet->getStyle('A' . $fila . ':L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alternar colores de fila
            if ($fila % 2 == 0) {
                $sheet->getStyle('A' . $fila . ':L' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');
            }

            $fila++;
        }

        // Ajustar ancho de columnas automáticamente
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Establecer algunos anchos específicos para columnas que pueden ser muy anchas
        $sheet->getColumnDimension('E')->setWidth(40); // Descripción
        $sheet->getColumnDimension('B')->setWidth(25); // Empleado

        // Congelar la primera fila de headers
        $sheet->freezePane('A5');

        $filename = 'novedades_nomina_' . $novedades->count() . '_registros_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function descargarArchivo($novedadId, $indiceArchivo)
    {
        $novedad = novedade::findOrFail($novedadId);
        $archivo = $novedad->obtenerArchivoBinario($indiceArchivo);

        if (!$archivo) {
            return back()->with('error', 'Archivo no encontrado');
        }

        return response($archivo['contenido'])
            ->header('Content-Type', $archivo['tipo'])
            ->header('Content-Disposition', 'attachment; filename="' . $archivo['nombre_original'] . '"');
    }

    public function eliminarArchivo($novedadId, $indiceArchivo)
    {
        $novedad = novedade::findOrFail($novedadId);

        if ($novedad->NOV_ESTADO_APROBACION !== 'pendiente') {
            return response()->json(['error' => 'No se puede eliminar archivos de una novedad procesada'], 403);
        }

        $novedad->eliminarArchivo($indiceArchivo);
        $novedad->save();

        return response()->json(['success' => 'Archivo eliminado exitosamente']);
    }

    public function verArchivo($novedadId, $indiceArchivo)
    {
        $novedad = novedade::findOrFail($novedadId);
        $archivo = $novedad->obtenerArchivoBinario($indiceArchivo);

        if (!$archivo) {
            return back()->with('error', 'Archivo no encontrado');
        }

        return response($archivo['contenido'])
            ->header('Content-Type', $archivo['tipo'])
            ->header('Content-Disposition', 'inline; filename="' . $archivo['nombre_original'] . '"');
    }
}
