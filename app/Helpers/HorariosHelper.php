<?php

namespace App\Helpers;

use App\Models\campana;
use App\Models\uni_cli;
use App\Models\jornada;
use App\Models\hora;
use App\Models\malla;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Helper class for Horarios (Schedules) module
 * Provides common functionality for schedule management
 */
class HorariosHelper
{
    /**
     * Get campaigns by client ID
     *
     * @param int $clientId
     * @return \Illuminate\Support\Collection
     */
    public static function getCampaignsByClient($clientId)
    {
        $uniclis = uni_cli::where('CLI_ID', $clientId)
            ->where('UNC_ESTADO', 1)
            ->pluck('UNC_ID');

        return campana::whereIn('UNC_ID', $uniclis)
            ->where('CAM_ESTADO', '1')
            ->select('CAM_ID', 'CAM_NOMBRE')
            ->orderBy('CAM_NOMBRE')
            ->get();
    }

    /**
     * Get shift (jornada) details by ID
     *
     * @param int $jornadaId
     * @return \App\Models\jornada|null
     */
    public static function getJornadaById($jornadaId)
    {
        return jornada::find($jornadaId);
    }

    /**
     * Get hour details by ID
     *
     * @param int $horaId
     * @return \App\Models\hora|null
     */
    public static function getHoraById($horaId)
    {
        return hora::find($horaId);
    }

    /**
     * Create schedule entries for date range
     *
     * @param array $data - Must include: EMP_ID, CAM_ID, fecha_inicial, fecha_final, USER_ID
     * @param bool $useJornada - true for jornada mode, false for custom hours
     * @param int|null $jornadaId - Required if useJornada is true
     * @param string|null $horaInicio - Required if useJornada is false
     * @param string|null $horaFinal - Required if useJornada is false
     * @return array - ['success' => bool, 'created' => int, 'message' => string]
     */
    public static function createScheduleRange($data, $useJornada, $jornadaId = null, $horaInicio = null, $horaFinal = null)
    {
        try {
            $created = 0;
            $fechaInicial = Carbon::parse($data['fecha_inicial']);
            $fechaFinal = Carbon::parse($data['fecha_final']);

            // Get time range based on mode
            if ($useJornada) {
                $jornada = self::getJornadaById($jornadaId);
                if (!$jornada) {
                    return ['success' => false, 'created' => 0, 'message' => 'Jornada no encontrada'];
                }
                $inicio = $jornada->JOR_INICIO;
                $final = $jornada->JOR_FINAL;
            } else {
                $inicio = $horaInicio;
                $final = $horaFinal;
            }

            // Create entries for each day in range
            while ($fechaInicial <= $fechaFinal) {
                $exists = malla::where('EMP_ID', $data['EMP_ID'])
                    ->where('MAL_DIA', $fechaInicial->format('Y-m-d'))
                    ->where('MAL_INICIO', $inicio)
                    ->where('MAL_FINAL', $final)
                    ->where('MAL_ESTADO', 1)
                    ->exists();

                if (!$exists) {
                    malla::create([
                        'EMP_ID' => $data['EMP_ID'],
                        'CAM_ID' => $data['CAM_ID'],
                        'MAL_DIA' => $fechaInicial->format('Y-m-d'),
                        'MAL_INICIO' => $inicio,
                        'MAL_FINAL' => $final,
                        'MAL_ESTADO' => 1,
                        'USER_ID' => $data['USER_ID']
                    ]);
                    $created++;
                }

                $fechaInicial->addDay();
            }

            return [
                'success' => true,
                'created' => $created,
                'message' => "Se crearon {$created} horario(s) exitosamente"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'created' => 0,
                'message' => 'Error al crear horarios: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get employee schedules for date range
     *
     * @param int $empId
     * @param string $fechaInicial
     * @param string $fechaFinal
     * @return \Illuminate\Support\Collection
     */
    public static function getEmployeeSchedules($empId, $fechaInicial, $fechaFinal)
    {
        return malla::join('campanas', 'mallas.CAM_ID', '=', 'campanas.CAM_ID')
            ->join('uni_clis', 'campanas.UNC_ID', '=', 'uni_clis.UNC_ID')
            ->join('clientes', 'uni_clis.CLI_ID', '=', 'clientes.CLI_ID')
            ->where('mallas.EMP_ID', $empId)
            ->whereBetween('mallas.MAL_DIA', [$fechaInicial, $fechaFinal])
            ->select(
                'mallas.*',
                'campanas.CAM_NOMBRE',
                'clientes.CLI_NOMBRE'
            )
            ->orderBy('mallas.MAL_DIA')
            ->orderBy('mallas.MAL_INICIO')
            ->get();
    }

    /**
     * Deactivate schedule by ID
     *
     * @param int $mallaId
     * @param int $userId
     * @return bool
     */
    public static function deactivateSchedule($mallaId, $userId)
    {
        return malla::where('MAL_ID', $mallaId)
            ->update([
                'MAL_ESTADO' => 0,
                'USER_ID' => $userId,
                'updated_at' => now()
            ]) > 0;
    }

    /**
     * Activate schedule by ID
     *
     * @param int $mallaId
     * @param int $userId
     * @return bool
     */
    public static function activateSchedule($mallaId, $userId)
    {
        return malla::where('MAL_ID', $mallaId)
            ->update([
                'MAL_ESTADO' => 1,
                'USER_ID' => $userId,
                'updated_at' => now()
            ]) > 0;
    }

    /**
     * Delete schedule by ID (soft delete by setting MAL_ESTADO = 0)
     *
     * @param int $mallaId
     * @param int $userId
     * @return bool
     */
    public static function deleteSchedule($mallaId, $userId)
    {
        return self::deactivateSchedule($mallaId, $userId);
    }

    /**
     * Validate schedule doesn't overlap with existing schedules
     *
     * @param int $empId
     * @param string $fecha
     * @param string $horaInicio
     * @param string $horaFinal
     * @param int|null $excludeMallaId - ID to exclude from check (for updates)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateScheduleOverlap($empId, $fecha, $horaInicio, $horaFinal, $excludeMallaId = null)
    {
        $query = malla::where('EMP_ID', $empId)
            ->where('MAL_DIA', $fecha)
            ->where('MAL_ESTADO', 1)
            ->where(function($q) use ($horaInicio, $horaFinal) {
                $q->whereBetween('MAL_INICIO', [$horaInicio, $horaFinal])
                  ->orWhereBetween('MAL_FINAL', [$horaInicio, $horaFinal])
                  ->orWhere(function($q2) use ($horaInicio, $horaFinal) {
                      $q2->where('MAL_INICIO', '<=', $horaInicio)
                         ->where('MAL_FINAL', '>=', $horaFinal);
                  });
            });

        if ($excludeMallaId) {
            $query->where('MAL_ID', '!=', $excludeMallaId);
        }

        $overlapping = $query->exists();

        return [
            'valid' => !$overlapping,
            'message' => $overlapping ? 'Ya existe un horario en ese rango de tiempo' : 'Horario válido'
        ];
    }

    /**
     * Format schedule time for display
     *
     * @param string $time - Time in HH:mm:ss format
     * @return string - Time in HH:mm format
     */
    public static function formatTime($time)
    {
        return Carbon::parse($time)->format('H:i');
    }

    /**
     * Format date for display
     *
     * @param string $date - Date in Y-m-d format
     * @param string $format - Desired format (default: d/m/Y)
     * @return string
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Get schedule statistics for employee
     *
     * @param int $empId
     * @param string|null $fechaInicial
     * @param string|null $fechaFinal
     * @return array
     */
    public static function getEmployeeScheduleStats($empId, $fechaInicial = null, $fechaFinal = null)
    {
        $query = malla::where('EMP_ID', $empId);

        if ($fechaInicial && $fechaFinal) {
            $query->whereBetween('MAL_DIA', [$fechaInicial, $fechaFinal]);
        }

        $total = $query->count();
        $activos = $query->where('MAL_ESTADO', 1)->count();
        $inactivos = $total - $activos;

        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos
        ];
    }
}
