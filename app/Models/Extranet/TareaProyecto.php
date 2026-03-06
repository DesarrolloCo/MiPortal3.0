<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\empleado;

class TareaProyecto extends Model
{
    use HasFactory;

    protected $table = 'tareas_proyecto';

    protected $fillable = [
        'proyecto_id',
        'titulo',
        'descripcion',
        'asignado_a',
        'estado',
        'prioridad',
        'fecha_vencimiento',
        'fecha_completada',
        'orden',
        'dependencias',
        'tiempo_estimado',
        'tiempo_real',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_completada' => 'datetime',
        'orden' => 'integer',
        'dependencias' => 'array',
        'tiempo_estimado' => 'integer',
        'tiempo_real' => 'integer',
    ];

    // Relaciones
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function asignado()
    {
        return $this->belongsTo(empleado::class, 'asignado_a', 'EMP_ID');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'en_progreso');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopePorAsignado($query, $asignadoId)
    {
        return $query->where('asignado_a', $asignadoId);
    }

    // Accessors
    public function getEstadoColorAttribute()
    {
        $colores = [
            'pendiente' => 'secondary',
            'en_progreso' => 'info',
            'revision' => 'warning',
            'completada' => 'success',
            'cancelada' => 'danger',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    public function getEsVencidaAttribute()
    {
        if ($this->estado === 'completada' || $this->estado === 'cancelada') {
            return false;
        }

        if (!$this->fecha_vencimiento) {
            return false;
        }

        return now()->greaterThan($this->fecha_vencimiento);
    }

    // Métodos
    public function iniciar()
    {
        $this->update(['estado' => 'en_progreso']);
    }

    public function completar()
    {
        $this->update([
            'estado' => 'completada',
            'fecha_completada' => now(),
        ]);

        // Actualizar progreso del proyecto
        $this->proyecto->actualizarProgreso();
    }

    public function enviarARevision()
    {
        $this->update(['estado' => 'revision']);
    }

    public function cancelar()
    {
        $this->update(['estado' => 'cancelada']);
    }
}
