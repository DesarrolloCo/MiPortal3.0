<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class evi_asignado extends Model
{
    use HasFactory;

    protected $table = 'evi_asignados';

    protected $primaryKey = 'EVI_ID';

    protected $fillable = [
        'EAS_ID',
        'EVI_NOMBRE',
        'EVI_FECHA',
        'EVI_EVIDENCIA_PATH', // Nueva columna para ruta del archivo
        'EVI_ESTADO',
    ];

    protected $casts = [
        'EVI_FECHA' => 'date',
        'EVI_ESTADO' => 'integer',
    ];

    // Relaciones

    /**
     * Obtiene la asignación relacionada
     */
    public function asignacion()
    {
        return $this->belongsTo(equ_asignado::class, 'EAS_ID', 'EAS_ID');
    }

    // Métodos de ayuda

    /**
     * Obtiene la URL completa del archivo de evidencia
     */
    public function getEvidenciaUrlAttribute()
    {
        if ($this->EVI_EVIDENCIA_PATH) {
            return Storage::url($this->EVI_EVIDENCIA_PATH);
        }
        return null;
    }

    /**
     * Verifica si tiene evidencia almacenada
     */
    public function tieneEvidencia()
    {
        return !empty($this->EVI_EVIDENCIA_PATH) || !empty($this->EVI_EVIDENCIA);
    }

    /**
     * Verifica si la evidencia está en el nuevo formato (filesystem)
     */
    public function usaNuevoFormato()
    {
        return !empty($this->EVI_EVIDENCIA_PATH);
    }
}
