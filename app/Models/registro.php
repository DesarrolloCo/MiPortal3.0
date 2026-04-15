<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class registro extends Model
{
    use HasFactory;

    protected $table = 'registros';
    protected $primaryKey = 'REG_ID';

    protected $fillable = [
        'USER_ID',
        'REG_NOMBRE',
        'REG_TIPO_ID',
        'REG_CEDULA',
        'REG_EMPRESA',
        'REG_MOTIVO_INGRESO',
        'REG_EQUIPO',
        'REG_SERIAL',
        'REG_FECHA_HORA_SALIDA',
        'REG_ESTADO'
    ];

    protected $casts = [
        'REG_FECHA_HORA_SALIDA' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con usuario que registró la visita
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'USER_ID', 'id');
    }

    /**
     * Scope para visitas activas (sin salida registrada)
     */
    public function scopeActivas($query)
    {
        return $query->where('REG_ESTADO', 1)
            ->whereNull('REG_FECHA_HORA_SALIDA');
    }

    /**
     * Scope para visitas finalizadas (con salida registrada)
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('REG_ESTADO', 1)
            ->whereNotNull('REG_FECHA_HORA_SALIDA');
    }

    /**
     * Verificar si la visita está activa (aún en instalaciones)
     */
    public function estaActiva()
    {
        return is_null($this->REG_FECHA_HORA_SALIDA);
    }
}
