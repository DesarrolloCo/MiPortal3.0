<?php

namespace App\Services;

use App\Models\malla;
use App\Models\jornada;
use App\Models\hora;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HorarioService
{
    /**
     * Generar horarios para empleados en un rango de fechas
     *
     * @param array $empleadosIds IDs de empleados
     * @param int $campaniaId ID de campaña
     * @param string $fechaInicial Fecha inicial
     * @param string $fechaFinal Fecha final
     * @param int|null $jornadaId ID de jornada
     * @param int|null $horaInicialId ID de hora inicial
     * @param int|null $horaFinalId ID de hora final
     * @param int $userId ID del usuario que crea
     * @return array Resultado con éxito y estadísticas
     */
    public function generarHorarios(
        array $empleadosIds,
        int $campaniaId,
        string $fechaInicial,
        string $fechaFinal,
        ?int $jornadaId = null,
        ?int $horaInicialId = null,
        ?int $horaFinalId = null,
        int $userId
    ): array {
        // Validar fechas
        $rangoFechas = $this->validarYProcesarFechas($fechaInicial, $fechaFinal);

        if (!$rangoFechas['valido']) {
            return [
                'exito' => false,
                'mensaje' => $rangoFechas['mensaje']
            ];
        }

        // Obtener horas según jornada o rango
        $horas = $this->obtenerHoras($jornadaId, $horaInicialId, $horaFinalId);

        if ($horas->isEmpty()) {
            return [
                'exito' => false,
                'mensaje' => 'No se encontraron horas válidas para el rango especificado'
            ];
        }

        // Generar eventos de horario
        $eventos = $this->generarEventos(
            $empleadosIds,
            $rangoFechas['fechas'],
            $horas,
            $campaniaId,
            $userId
        );

        // Guardar horarios usando upsert
        $insertados = $this->guardarHorarios($eventos);

        return [
            'exito' => true,
            'mensaje' => 'Horarios generados exitosamente',
            'estadisticas' => [
                'empleados' => count($empleadosIds),
                'dias' => count($rangoFechas['fechas']),
                'horas_por_dia' => $horas->count(),
                'total_registros' => $insertados
            ]
        ];
    }

    /**
     * Validar y procesar rango de fechas
     */
    private function validarYProcesarFechas(string $fechaInicial, string $fechaFinal): array
    {
        try {
            $inicio = Carbon::parse($fechaInicial);
            $fin = Carbon::parse($fechaFinal);

            // Validar que fecha inicial no sea mayor que final
            if ($inicio->gt($fin)) {
                return [
                    'valido' => false,
                    'mensaje' => 'La fecha inicial no puede ser mayor que la fecha final'
                ];
            }

            // Validar que estén en el mismo mes
            if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
                return [
                    'valido' => false,
                    'mensaje' => 'Solo puede programar horarios dentro del mismo mes'
                ];
            }

            // Generar array de fechas
            $fechas = [];
            $fechaActual = $inicio->copy();

            while ($fechaActual->lte($fin)) {
                $fechas[] = $fechaActual->format('Y-m-d');
                $fechaActual->addDay();
            }

            return [
                'valido' => true,
                'fechas' => $fechas,
                'inicio' => $inicio,
                'fin' => $fin
            ];
        } catch (\Exception $e) {
            return [
                'valido' => false,
                'mensaje' => 'Formato de fecha inválido'
            ];
        }
    }

    /**
     * Obtener horas desde jornada o rango directo
     */
    private function obtenerHoras(?int $jornadaId, ?int $horaInicialId, ?int $horaFinalId): Collection
    {
        // Usar caché para horas activas
        $horasActivas = Cache::remember('horas_activas', 3600, function() {
            return hora::activas()->orderBy('HOR_ID')->get();
        });

        if ($jornadaId) {
            // Obtener horas desde jornada
            $jornada = jornada::with(['horaInicio', 'horaFinal', 'horaAlmuerzoInicio', 'horaAlmuerzoFin'])
                ->where('JOR_ID', $jornadaId)
                ->where('JOR_ESTADO', 1)
                ->first();

            if (!$jornada) {
                return collect([]);
            }

            // Si la jornada tiene almuerzo definido, necesitamos manejar horas especiales
            if ($jornada->JOR_ALMUERZO_INICIO && $jornada->JOR_ALMUERZO_FIN) {
                return $this->procesarHorasConAlmuerzo($horasActivas, $jornada);
            } else {
                // Jornada normal sin almuerzo
                return $horasActivas->whereBetween('HOR_ID', [
                    $jornada->JOR_INICIO,
                    $jornada->JOR_FINAL
                ])->values();
            }
        } elseif ($horaInicialId && $horaFinalId) {
            // Obtener horas desde rango directo
            return $horasActivas->whereBetween('HOR_ID', [
                $horaInicialId,
                $horaFinalId
            ])->values();
        }

        return collect([]);
    }

    /**
     * Procesar horas de una jornada que incluye almuerzo
     */
    private function procesarHorasConAlmuerzo(Collection $horasActivas, jornada $jornada): Collection
    {
        $horasProcesadas = collect();

        // Horas de trabajo antes del almuerzo
        $horasTrabajoManana = $horasActivas->whereBetween('HOR_ID', [
            $jornada->JOR_INICIO,
            $jornada->JOR_ALMUERZO_INICIO - 1
        ])->values();

        // Marcar horas de trabajo normal
        $horasTrabajoManana->each(function($hora) {
            $hora->_tipo = 'trabajo';
        });
        $horasProcesadas = $horasProcesadas->merge($horasTrabajoManana);

        // Horas de almuerzo
        $horasAlmuerzo = $horasActivas->whereBetween('HOR_ID', [
            $jornada->JOR_ALMUERZO_INICIO,
            $jornada->JOR_ALMUERZO_FIN
        ])->values();

        // Marcar horas de almuerzo
        $horasAlmuerzo->each(function($hora) {
            $hora->_tipo = 'almuerzo';
        });
        $horasProcesadas = $horasProcesadas->merge($horasAlmuerzo);

        // Horas de trabajo después del almuerzo
        $horasTrabajoTarde = $horasActivas->whereBetween('HOR_ID', [
            $jornada->JOR_ALMUERZO_FIN + 1,
            $jornada->JOR_FINAL
        ])->values();

        // Marcar horas de trabajo normal
        $horasTrabajoTarde->each(function($hora) {
            $hora->_tipo = 'trabajo';
        });
        $horasProcesadas = $horasProcesadas->merge($horasTrabajoTarde);

        return $horasProcesadas;
    }

    /**
     * Generar array de eventos de horario
     */
    private function generarEventos(
        array $empleadosIds,
        array $fechas,
        Collection $horas,
        int $campaniaId,
        int $userId
    ): array {
        $eventos = [];

        foreach ($fechas as $fecha) {
            foreach ($horas as $hora) {
                foreach ($empleadosIds as $empleadoId) {
                    // Determinar el estado basado en el tipo de hora
                    $estado = isset($hora->_tipo) && $hora->_tipo === 'almuerzo' ? 2 : 1; // 2 = almuerzo, 1 = trabajo

                    $eventos[] = [
                        'CAM_ID' => $campaniaId,
                        'MAL_DIA' => $fecha,
                        'MAL_INICIO' => $fecha . ' ' . $hora->HOR_INICIO,
                        'MAL_FINAL' => $fecha . ' ' . $hora->HOR_FINAL,
                        'EMP_ID' => $empleadoId,
                        'USER_ID' => $userId,
                        'MAL_ESTADO' => $estado
                    ];
                }
            }
        }

        return $eventos;
    }

    /**
     * Guardar horarios usando upsert (insertar o actualizar)
     */
    private function guardarHorarios(array $eventos): int
    {
        if (empty($eventos)) {
            return 0;
        }

        return DB::transaction(function() use ($eventos) {
            // Usar upsert para insertar o actualizar en lote
            // Esto reduce cientos de queries a solo una
            malla::upsert(
                $eventos,
                ['EMP_ID', 'MAL_DIA', 'MAL_INICIO', 'MAL_FINAL'], // Claves únicas
                ['CAM_ID', 'USER_ID', 'MAL_ESTADO'] // Campos a actualizar si existe
            );

            return count($eventos);
        });
    }

    /**
     * Obtener horarios de un empleado en un rango de fechas
     */
    public function obtenerHorariosEmpleado(
        int $empleadoId,
        string $fechaInicial,
        ?string $fechaFinal = null
    ): Collection {
        $query = malla::with([
            'campana.unidadNegocioCliente.cliente',
            'campana.unidadNegocioCliente.unidadNegocio',
            'empleado'
        ])->where('EMP_ID', $empleadoId);

        if ($fechaFinal) {
            // Rango de fechas
            $query->whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal])
                ->orderBy('MAL_DIA')
                ->orderBy('MAL_INICIO');
        } else {
            // Fecha específica
            $query->where('MAL_DIA', $fechaInicial)
                ->orderBy('MAL_INICIO');
        }

        return $query->get();
    }

    /**
     * Validar conflictos de horarios
     */
    public function validarConflictos(
        int $empleadoId,
        string $fecha,
        string $horaInicio,
        string $horaFin
    ): array {
        $conflictos = malla::where('EMP_ID', $empleadoId)
            ->where('MAL_DIA', $fecha)
            ->where('MAL_ESTADO', 1)
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('MAL_INICIO', [$horaInicio, $horaFin])
                    ->orWhereBetween('MAL_FINAL', [$horaInicio, $horaFin])
                    ->orWhere(function($q) use ($horaInicio, $horaFin) {
                        $q->where('MAL_INICIO', '<=', $horaInicio)
                          ->where('MAL_FINAL', '>=', $horaFin);
                    });
            })
            ->get();

        return [
            'tiene_conflictos' => $conflictos->isNotEmpty(),
            'conflictos' => $conflictos
        ];
    }

    /**
     * Eliminar horarios de un empleado en un rango de fechas
     */
    public function eliminarHorarios(
        int $empleadoId,
        string $fechaInicial,
        ?string $fechaFinal = null
    ): int {
        $query = malla::where('EMP_ID', $empleadoId);

        if ($fechaFinal) {
            $query->whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal]);
        } else {
            $query->where('MAL_DIA', $fechaInicial);
        }

        return $query->delete();
    }

    /**
     * Obtener estadísticas de horarios
     */
    public function obtenerEstadisticas(string $fechaInicial, string $fechaFinal): array
    {
        $totalHorarios = malla::whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal])->count();
        $horariosActivos = malla::whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal])
            ->where('MAL_ESTADO', 1)
            ->count();
        $horariosInactivos = $totalHorarios - $horariosActivos;

        $empleadosConHorario = malla::whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal])
            ->distinct('EMP_ID')
            ->count('EMP_ID');

        return [
            'total_horarios' => $totalHorarios,
            'activos' => $horariosActivos,
            'inactivos' => $horariosInactivos,
            'empleados_con_horario' => $empleadosConHorario
        ];
    }
}
