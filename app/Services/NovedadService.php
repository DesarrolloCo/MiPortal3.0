<?php

namespace App\Services;

use App\Models\novedade;
use App\Models\NovedadHorario;
use App\Models\malla;
use App\Models\Extranet\NotificacionExtranet;
use App\Models\tipos_novedade;
use App\Models\empleado;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NovedadService
{
    /**
     * Crear una novedad para uno o varios horarios asociados.
     *
     * @param  int|array<int>  $mallaIds
     */
    public function crearNovedad($mallaIds, array $datosNovedad, array $archivos = []): novedade
    {
        $mallaIds = is_array($mallaIds) ? $mallaIds : [$mallaIds];
        $mallaIds = array_unique(array_filter($mallaIds));

        // Crear la novedad
        $novedad = new novedade();
        $novedad->TIN_ID = $datosNovedad['tipo_id'];
        $novedad->EMP_ID = $datosNovedad['empleado_id'];

        $referencia = $datosNovedad['fecha_referencia'] ?? null;
        $fechaReferencia = $referencia ? Carbon::parse($referencia) : null;

        if (!$fechaReferencia && !empty($mallaIds)) {
            $fechaMasTemprana = malla::whereIn('MAL_ID', $mallaIds)
                ->min('MAL_DIA');

            if ($fechaMasTemprana) {
                $fechaReferencia = Carbon::parse($fechaMasTemprana);
            }
        }

        $novedad->NOV_FECHA = ($fechaReferencia ?: Carbon::now('America/Bogota'))->format('Y-m-d');

        // Otros datos
        $novedad->NOV_DESCRIPCION = $datosNovedad['descripcion'];
        $novedad->USER_ID = $datosNovedad['user_id'];
        $novedad->NOV_ESTADO = 1;
        $novedad->NOV_ESTADO_APROBACION = 'pendiente';

        // Agregar archivos si los hay
        if (!empty($archivos)) {
            $novedad->NOV_ARCHIVOS = json_encode($archivos);
        }

        $novedad->save();

        foreach ($mallaIds as $mallaId) {
            NovedadHorario::create([
                'nov_id' => $novedad->NOV_ID,
                'mal_id' => $mallaId,
            ]);
        }



        // Enviar notificaciones
        $this->enviarNotificacionesCreacion($novedad);

        return $novedad;
    }

    /**
     * Enviar notificaciones cuando se crea una novedad
     */
    private function enviarNotificacionesCreacion(novedade $novedad): void
    {
        try {
            // Notificación para el empleado
            $tipoNovedad = tipos_novedade::find($novedad->TIN_ID);
            $tipoNombre = $tipoNovedad ? $tipoNovedad->TIN_NOMBRE : 'Novedad';

            $notifDataEmpleado = [
                'empleado_id' => $novedad->EMP_ID,
                'tipo' => 'sistema',
                'titulo' => 'Novedad Registrada',
                'mensaje' => 'Tu novedad "' . $tipoNombre . '" ha sido registrada exitosamente y está pendiente de aprobación.',
                'datos_adicionales' => [
                    'novedad_id' => $novedad->NOV_ID,
                    'tipo_novedad' => $tipoNombre,
                    'estado' => 'pendiente'
                ]
            ];

            try {
                $notificacionEmpleado = NotificacionExtranet::crear($notifDataEmpleado);
                Log::info("Notificación creada para empleado en creación de novedad", [
                    'novedad_id' => $novedad->NOV_ID,
                    'notificacion_id' => $notificacionEmpleado->id,
                    'empleado_id' => $novedad->EMP_ID
                ]);
            } catch (\Exception $e) {
                Log::error("Error creando notificación para empleado", [
                    'novedad_id' => $novedad->NOV_ID,
                    'empleado_id' => $novedad->EMP_ID,
                    'error' => $e->getMessage()
                ]);
            }

            // Notificación para supervisores con permiso 'ver-novedades'
            $supervisores = User::permission('ver-novedades')->get();

            if ($supervisores->isNotEmpty()) {
                $empleado = empleado::find($novedad->EMP_ID);
                $empleadoNombre = $empleado ? $empleado->EMP_NOMBRES . ' ' . $empleado->EMP_APELLIDOS : 'Empleado';

                // Evitar notificaciones duplicadas
                $notificadosEmpleadosIds = [$novedad->EMP_ID]; // El empleado ya recibió notificación

                foreach ($supervisores as $supervisor) {
                    // Solo notificar si el supervisor tiene un empleado asociado y no ha sido notificado ya
                    if ($supervisor->empleados && !in_array($supervisor->empleados->EMP_ID, $notificadosEmpleadosIds)) {
                        $notifDataSupervisor = [
                            'empleado_id' => $supervisor->empleados->EMP_ID,
                            'tipo' => 'sistema',
                            'titulo' => 'Nueva Novedad Pendiente de Aprobación',
                            'mensaje' => 'Se ha registrado una nueva novedad para ' . $empleadoNombre . ' (' . $tipoNombre . ') que requiere aprobación.',
                            'datos_adicionales' => [
                                'novedad_id' => $novedad->NOV_ID,
                                'tipo_novedad' => $tipoNombre,
                                'empleado_id' => $novedad->EMP_ID,
                                'empleado_nombre' => $empleadoNombre,
                                'estado' => 'pendiente',
                                'fecha_registro' => now()->format('d/m/Y H:i'),
                                'action_url' => route('Novedades.index') // URL para ir directamente a gestionar novedades
                            ]
                        ];

                        NotificacionExtranet::crear($notifDataSupervisor);

                        $notificadosEmpleadosIds[] = $supervisor->empleados->EMP_ID;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error enviando notificaciones en creación de novedad", [
                'novedad_id' => $novedad->NOV_ID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Desactivar horarios asociados a una novedad
     */
    public function desactivarHorarios(array $horariosIds, int $estado = 0): int
    {
        return malla::whereIn('MAL_ID', $horariosIds)->update(['MAL_ESTADO' => $estado]);
    }

    /**
     * Eliminar una novedad y sus relaciones
     */
    public function eliminarNovedad(int $novedadId): bool
    {
        $novedad = novedade::findOrFail($novedadId);

        // Eliminar relaciones (se hace automáticamente por CASCADE)
        // NovedadHorario::where('nov_id', $novedadId)->delete();

        // Eliminar la novedad
        return $novedad->delete();
    }

    /**
     * Procesar archivos para novedades
     */
    public function procesarArchivos($archivos): array
    {
        $archivosGuardados = [];

        if ($archivos && is_array($archivos)) {
            foreach ($archivos as $index => $archivo) {
                if ($archivo->isValid()) {
                    // Generate unique filename
                    $extension = $archivo->getClientOriginalExtension();
                    $filename = time() . '_' . $index . '_' . uniqid() . '.' . $extension;
                    $path = 'novedad-files/' . $filename;

                    // Store file on disk
                    $archivo->storeAs('novedad-files', $filename, 'local');

                    $archivosGuardados[] = [
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'filename' => $filename,
                        'path' => $path,
                        'size' => $archivo->getSize(),
                        'tipo' => $archivo->getMimeType(),
                        'fecha_subida' => Carbon::now('America/Bogota')->toDateTimeString()
                    ];
                }
            }
        }

        return $archivosGuardados;
    }
}
