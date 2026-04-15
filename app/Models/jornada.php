<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class jornada extends Model
{
    use HasFactory;

    protected $table = 'jornadas';
    protected $primaryKey = 'JOR_ID';
    public $timestamps = false;

    protected $fillable = [
        'JOR_NOMBRE',
        'JOR_INICIO',
        'JOR_FINAL',
        'JOR_ALMUERZO_INICIO',
        'JOR_ALMUERZO_FIN',
        'JOR_ESTADO',
        'USER_ID'
    ];

    protected $casts = [
        'JOR_ESTADO' => 'integer',
        'JOR_INICIO' => 'integer',
        'JOR_FINAL' => 'integer',
        'JOR_ALMUERZO_INICIO' => 'integer',
        'JOR_ALMUERZO_FIN' => 'integer'
    ];

    // Relación con hora de inicio
    public function horaInicio(): BelongsTo
    {
        return $this->belongsTo(hora::class, 'JOR_INICIO', 'HOR_ID');
    }

    // Relación con hora de fin
    public function horaFinal(): BelongsTo
    {
        return $this->belongsTo(hora::class, 'JOR_FINAL', 'HOR_ID');
    }

    // Relación con hora de inicio de almuerzo
    public function horaAlmuerzoInicio(): BelongsTo
    {
        return $this->belongsTo(hora::class, 'JOR_ALMUERZO_INICIO', 'HOR_ID');
    }

    // Relación con hora de fin de almuerzo
    public function horaAlmuerzoFin(): BelongsTo
    {
        return $this->belongsTo(hora::class, 'JOR_ALMUERZO_FIN', 'HOR_ID');
    }

    // Scope para jornadas activas
    public function scopeActivas($query)
    {
        return $query->where('JOR_ESTADO', 1);
    }

    // Obtener todas las horas de la jornada
    public function obtenerHoras(): Collection
    {
        return hora::whereBetween('HOR_ID', [$this->JOR_INICIO, $this->JOR_FINAL])
            ->orderBy('HOR_ID')
            ->get();
    }
}
