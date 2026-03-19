<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class equipo extends Model
{
    use HasFactory, Auditable;

    protected $table = 'equipos';

    protected $primaryKey = 'EQU_ID';

    protected $fillable = [
        'ARE_ID',
        'EQU_SERIAL',
        'EQU_NOMBRE',
        'EQU_PRECIO',
        'EQU_TIPO',
        'EQU_OBSERVACIONES',
        'PRO_ID',
        'EQU_ESTADO',
        'EQU_STATUS',
    ];

    protected $casts = [
        'EQU_PRECIO' => 'float',
        'EQU_ESTADO' => 'integer',
        'EQU_STATUS' => 'integer',
    ];

    // Relaciones

    /**
     * Obtiene el área al que pertenece el equipo
     */
    public function area()
    {
        return $this->belongsTo(area::class, 'ARE_ID', 'ARE_ID');
    }

    /**
     * Obtiene el proveedor del equipo
     */
    public function proveedor()
    {
        return $this->belongsTo(proveedor::class, 'PRO_ID', 'PRO_ID');
    }

    /**
     * Obtiene todas las asignaciones del equipo
     */
    public function asignaciones()
    {
        return $this->hasMany(equ_asignado::class, 'EQU_ID', 'EQU_ID');
    }

    /**
     * Obtiene la asignación activa actual del equipo
     */
    public function asignacionActiva()
    {
        return $this->hasOne(equ_asignado::class, 'EQU_ID', 'EQU_ID')
            ->where('EAS_ESTADO', 1);
    }

    /**
     * Obtiene todos los mantenimientos del equipo
     */
    public function mantenimientos()
    {
        return $this->hasMany(mantenimiento::class, 'EQU_ID', 'EQU_ID');
    }

    // Scopes

    /**
     * Scope para equipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('EQU_ESTADO', 1);
    }

    /**
     * Scope para equipos disponibles (no asignados activamente)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('asignacionActiva');
    }

    // Métodos de ayuda

    /**
     * Verifica si el equipo está actualmente asignado
     */
    public function estaAsignado()
    {
        return $this->asignacionActiva()->exists();
    }

    /**
     * Verifica si el equipo está activo
     */
    public function estaActivo()
    {
        return $this->EQU_ESTADO == 1;
    }
}
