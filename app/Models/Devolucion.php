<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\equ_asignado;
use App\Models\empleado;
use App\Models\User;
use App\Traits\Auditable;

class Devolucion extends Model
{
    use HasFactory, Auditable;

    protected $table = 'devoluciones';

    protected $primaryKey = 'DEV_ID';

    protected $fillable = [
        'EAS_ID',
        'DEV_FECHA_DEVOLUCION',
        'DEV_RECIBIDO_POR',
        'DEV_ESTADO_EQUIPO',
        'DEV_HARDWARE_COMPLETO',
        'DEV_SOFTWARE_COMPLETO',
        'DEV_OBSERVACIONES',
        'DEV_DANOS_REPORTADOS',
        'DEV_FALTANTES',
        'DEV_USER_ID',
        'DEV_ACTA_PATH',
        'DEV_ESTADO',
    ];

    protected $casts = [
        'DEV_FECHA_DEVOLUCION' => 'date',
        'DEV_HARDWARE_COMPLETO' => 'boolean',
        'DEV_SOFTWARE_COMPLETO' => 'boolean',
        'DEV_ESTADO' => 'integer',
    ];

    // Relaciones

    /**
     * Obtiene la asignación que se está devolviendo
     */
    public function asignacion()
    {
        return $this->belongsTo(equ_asignado::class, 'EAS_ID', 'EAS_ID');
    }

    /**
     * Obtiene el empleado que recibió el equipo devuelto
     */
    public function recibidoPor()
    {
        return $this->belongsTo(empleado::class, 'DEV_RECIBIDO_POR', 'EMP_ID');
    }

    /**
     * Obtiene el usuario que registró la devolución
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'DEV_USER_ID', 'id');
    }

    // Scopes

    /**
     * Scope para devoluciones activas (no anuladas)
     */
    public function scopeActivas($query)
    {
        return $query->where('DEV_ESTADO', 1);
    }

    /**
     * Scope para devoluciones anuladas
     */
    public function scopeAnuladas($query)
    {
        return $query->where('DEV_ESTADO', 0);
    }

    // Métodos de ayuda

    /**
     * Verifica si la devolución está activa
     */
    public function estaActiva()
    {
        return $this->DEV_ESTADO == 1;
    }

    /**
     * Anula una devolución
     */
    public function anular()
    {
        $this->update(['DEV_ESTADO' => 0]);
    }
}
