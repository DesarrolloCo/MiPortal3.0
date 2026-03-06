<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\empleado;
use App\Models\departamento;
use App\Models\campana;

class Proyecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'objetivo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_fin_real',
        'estado',
        'prioridad',
        'progreso',
        'presupuesto',
        'responsable_id',
        'departamento_id',
        'campana_id',
        'etiquetas',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_fin_real' => 'date',
        'progreso' => 'integer',
        'presupuesto' => 'decimal:2',
        'etiquetas' => 'array',
    ];

    // Relaciones
    public function responsable()
    {
        return $this->belongsTo(empleado::class, 'responsable_id', 'EMP_ID');
    }

    public function departamento()
    {
        return $this->belongsTo(departamento::class, 'departamento_id', 'DEP_ID');
    }

    public function campana()
    {
        return $this->belongsTo(campana::class, 'campana_id', 'CAM_ID');
    }

    public function tareas()
    {
        return $this->hasMany(TareaProyecto::class, 'proyecto_id');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['planificacion', 'en_progreso']);
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePorResponsable($query, $responsableId)
    {
        return $query->where('responsable_id', $responsableId);
    }

    public function scopePorDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    // Accessors
    public function getEstadoColorAttribute()
    {
        $colores = [
            'planificacion' => 'secondary',
            'en_progreso' => 'primary',
            'pausado' => 'warning',
            'completado' => 'success',
            'cancelado' => 'danger',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_fin) {
            return null;
        }

        return now()->diffInDays($this->fecha_fin, false);
    }

    public function getEsAtrasadoAttribute()
    {
        if ($this->estado === 'completado' || $this->estado === 'cancelado') {
            return false;
        }

        if (!$this->fecha_fin) {
            return false;
        }

        return now()->greaterThan($this->fecha_fin) && $this->progreso < 100;
    }

    // Métodos
    public function actualizarProgreso()
    {
        $totalTareas = $this->tareas()->count();

        if ($totalTareas === 0) {
            $this->update(['progreso' => 0]);
            return;
        }

        $tareasCompletadas = $this->tareas()->where('estado', 'completada')->count();
        $progreso = round(($tareasCompletadas / $totalTareas) * 100);

        $this->update(['progreso' => $progreso]);

        if ($progreso === 100 && $this->estado === 'en_progreso') {
            $this->completar();
        }
    }

    public function iniciar()
    {
        $this->update(['estado' => 'en_progreso']);
    }

    public function pausar()
    {
        $this->update(['estado' => 'pausado']);
    }

    public function completar()
    {
        $this->update([
            'estado' => 'completado',
            'fecha_fin_real' => now(),
            'progreso' => 100,
        ]);
    }

    public function cancelar()
    {
        $this->update(['estado' => 'cancelado']);
    }
}
