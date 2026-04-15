<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos';

    protected $primaryKey = 'MAN_ID';

    protected $fillable = [
        'EQU_ID',
        'MAN_PROVEEDOR',
        'MAN_FECHA',
        'MAN_TECNICO',
        'MAN_STATUS',
        'MAN_ESTADO',
    ];

    protected $casts = [
        'MAN_FECHA' => 'date',
        'MAN_TECNICO' => 'integer',
        'MAN_STATUS' => 'integer',
        'MAN_ESTADO' => 'integer',
    ];

    // Relaciones

    /**
     * Obtiene el equipo asociado al mantenimiento
     */
    public function equipo()
    {
        return $this->belongsTo(equipo::class, 'EQU_ID', 'EQU_ID');
    }

    // Scopes

    /**
     * Scope para mantenimientos activos (pendientes)
     */
    public function scopeActivos($query)
    {
        return $query->where('MAN_ESTADO', 1)
            ->where('MAN_STATUS', 1);
    }

    /**
     * Scope para mantenimientos completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('MAN_ESTADO', 1)
            ->where('MAN_STATUS', 2);
    }

    /**
     * Scope para mantenimientos próximos (en los próximos X días)
     */
    public function scopeProximos($query, $dias = 7)
    {
        return $query->activos()
            ->where('MAN_FECHA', '>=', Carbon::now())
            ->where('MAN_FECHA', '<=', Carbon::now()->addDays($dias))
            ->orderBy('MAN_FECHA');
    }

    /**
     * Scope para mantenimientos vencidos (fecha pasada y no realizados)
     */
    public function scopeVencidos($query)
    {
        return $query->activos()
            ->where('MAN_FECHA', '<', Carbon::now())
            ->orderBy('MAN_FECHA', 'desc');
    }

    // Métodos de ayuda

    /**
     * Verifica si el mantenimiento está próximo (en los próximos 7 días)
     */
    public function estaProximo()
    {
        if (!$this->MAN_FECHA || $this->MAN_STATUS == 2) {
            return false;
        }

        $diasRestantes = Carbon::now()->diffInDays($this->MAN_FECHA, false);
        return $diasRestantes >= 0 && $diasRestantes <= 7;
    }

    /**
     * Verifica si el mantenimiento está vencido
     */
    public function estaVencido()
    {
        if (!$this->MAN_FECHA || $this->MAN_STATUS == 2) {
            return false;
        }

        return $this->MAN_FECHA < Carbon::now();
    }

    /**
     * Obtiene los días restantes hasta el mantenimiento
     */
    public function diasRestantes()
    {
        if (!$this->MAN_FECHA || $this->MAN_STATUS == 2) {
            return null;
        }

        return Carbon::now()->diffInDays($this->MAN_FECHA, false);
    }

    /**
     * Obtiene la urgencia del mantenimiento (alta, media, baja)
     */
    public function getUrgenciaAttribute()
    {
        $dias = $this->diasRestantes();

        if ($dias === null) {
            return 'completado';
        }

        if ($dias < 0) {
            return 'vencido';
        }

        if ($dias <= 3) {
            return 'alta';
        }

        if ($dias <= 7) {
            return 'media';
        }

        return 'baja';
    }
}
