<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hora extends Model
{
    use HasFactory;

    protected $table = 'horas';
    protected $primaryKey = 'HOR_ID';
    public $timestamps = false;

    protected $fillable = [
        'HOR_INICIO',
        'HOR_FINAL',
        'HOR_ESTADO'
    ];

    protected $casts = [
        'HOR_ESTADO' => 'integer'
    ];

    // Scope para horas activas
    public function scopeActivas($query)
    {
        return $query->where('HOR_ESTADO', 1);
    }

    // Scope para obtener rango de horas
    public function scopeEnRango($query, int $horaInicioId, int $horaFinalId)
    {
        return $query->whereBetween('HOR_ID', [$horaInicioId, $horaFinalId])
            ->orderBy('HOR_ID');
    }
}
