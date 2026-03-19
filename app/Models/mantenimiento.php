<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimiento';

    protected $primaryKey = 'MAN_ID';

    protected $fillable = [
        'EQU_ID',
        'MAN_TIPO',
        'MAN_FECHA_AGENDADA',
        'MAN_FECHA_REALIZACION',
        'MAN_DESCRIPCION',
        'MAN_PROVEEDOR',
        'MAN_COSTO',
        'MAN_OBSERVACIONES',
        'MAN_ESTADO',
    ];

    protected $casts = [
        'MAN_FECHA_AGENDADA' => 'date',
        'MAN_FECHA_REALIZACION' => 'date',
        'MAN_COSTO' => 'decimal:2',
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
            ->whereNull('MAN_FECHA_REALIZACION');
    }

    /**
     * Scope para mantenimientos completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('MAN_ESTADO', 1)
            ->whereNotNull('MAN_FECHA_REALIZACION');
    }

    /**
     * Scope para mantenimientos próximos (en los próximos X días)
     */
    public function scopeProximos($query, $dias = 7)
    {
        return $query->activos()
            ->where('MAN_FECHA_AGENDADA', '>=', Carbon::now())
            ->where('MAN_FECHA_AGENDADA', '<=', Carbon::now()->addDays($dias))
            ->orderBy('MAN_FECHA_AGENDADA');
    }

    /**
     * Scope para mantenimientos vencidos (fecha pasada y no realizados)
     */
    public function scopeVencidos($query)
    {
        return $query->activos()
            ->where('MAN_FECHA_AGENDADA', '<', Carbon::now())
            ->orderBy('MAN_FECHA_AGENDADA', 'desc');
    }

    // Métodos de ayuda

    /**
     * Verifica si el mantenimiento está próximo (en los próximos 7 días)
     */
    public function estaProximo()
    {
        if (!$this->MAN_FECHA_AGENDADA || $this->MAN_FECHA_REALIZACION) {
            return false;
        }

        $diasRestantes = Carbon::now()->diffInDays($this->MAN_FECHA_AGENDADA, false);
        return $diasRestantes >= 0 && $diasRestantes <= 7;
    }

    /**
     * Verifica si el mantenimiento está vencido
     */
    public function estaVencido()
    {
        if (!$this->MAN_FECHA_AGENDADA || $this->MAN_FECHA_REALIZACION) {
            return false;
        }

        return $this->MAN_FECHA_AGENDADA < Carbon::now();
    }

    /**
     * Obtiene los días restantes hasta el mantenimiento
     */
    public function diasRestantes()
    {
        if (!$this->MAN_FECHA_AGENDADA || $this->MAN_FECHA_REALIZACION) {
            return null;
        }

        return Carbon::now()->diffInDays($this->MAN_FECHA_AGENDADA, false);
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
