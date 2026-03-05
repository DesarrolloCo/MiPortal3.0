<?php

namespace App\Services;

use App\Models\novedade;
use App\Models\NovedadHorario;
use App\Models\malla;
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

        return $novedad;
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
            foreach ($archivos as $archivo) {
                if ($archivo->isValid()) {
                    $archivosGuardados[] = [
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'contenido_binario' => base64_encode(file_get_contents($archivo->getPathname())),
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
