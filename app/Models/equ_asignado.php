<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class equ_asignado extends Model
{
    use HasFactory, Auditable;

    protected $table = 'equ_asignados';

    protected $primaryKey = 'EAS_ID';

    protected $fillable = [
        'EQU_ID',
        'EMP_ID',
        'EAS_FECHA_ENTREGA',
        'EAS_EVIDENCIA',
        'EAS_ESTADO',
    ];

    protected $casts = [
        'EAS_FECHA_ENTREGA' => 'date',
        'EAS_ESTADO' => 'integer',
    ];

    // Relaciones

    /**
     * Obtiene el equipo asignado
     */
    public function equipo()
    {
        return $this->belongsTo(equipo::class, 'EQU_ID', 'EQU_ID');
    }

    /**
     * Obtiene el empleado al que se le asignó
     */
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'EMP_ID', 'EMP_ID');
    }

    /**
     * Obtiene las devoluciones de esta asignación
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class, 'EAS_ID', 'EAS_ID');
    }

    /**
     * Obtiene la devolución activa de esta asignación
     */
    public function devolucionActiva()
    {
        return $this->hasOne(Devolucion::class, 'EAS_ID', 'EAS_ID')
            ->where('DEV_ESTADO', 1);
    }

    // Scopes

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('EAS_ESTADO', 1);
    }

    /**
     * Scope para asignaciones devueltas
     */
    public function scopeDevueltas($query)
    {
        return $query->where('EAS_ESTADO', 2);
    }

    // Métodos de ayuda

    /**
     * Verifica si la asignación está activa
     */
    public function estaActiva()
    {
        return $this->EAS_ESTADO == 1;
    }

    /**
     * Verifica si la asignación fue devuelta
     */
    public function fueDevuelta()
    {
        return $this->EAS_ESTADO == 2;
    }
}
