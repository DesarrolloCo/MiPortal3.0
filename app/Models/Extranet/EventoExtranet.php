<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\empleado;
use App\Models\departamento;

class EventoExtranet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventos_extranet';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'modalidad',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'link_virtual',
        'organizador_id',
        'departamento_id',
        'imagen_url',
        'cupo_maximo',
        'requiere_confirmacion',
        'estado',
        'color',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'requiere_confirmacion' => 'boolean',
        'cupo_maximo' => 'integer',
    ];

    // Relaciones
    public function organizador()
    {
        return $this->belongsTo(empleado::class, 'organizador_id', 'EMP_ID');
    }

    public function departamento()
    {
        return $this->belongsTo(departamento::class, 'departamento_id', 'DEP_ID');
    }

    public function asistentes()
    {
        return $this->hasMany(AsistenteEvento::class, 'evento_id');
    }

    public function confirmados()
    {
        return $this->hasMany(AsistenteEvento::class, 'evento_id')
            ->where('estado_confirmacion', 'confirmado');
    }

    public function galeria()
    {
        return $this->hasOne(Galeria::class, 'evento_id');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopePublicados($query)
    {
        return $query->where('estado', 'publicado');
    }

    public function scopeProximos($query, $dias = 7)
    {
        return $query->where('fecha_inicio', '>=', now())
            ->where('fecha_inicio', '<=', now()->addDays($dias))
            ->orderBy('fecha_inicio', 'asc');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Accessors
    public function getTipoIconoAttribute()
    {
        $iconos = [
            'reunion' => 'mdi-account-group',
            'capacitacion' => 'mdi-school',
            'celebracion' => 'mdi-party-popper',
            'conferencia' => 'mdi-presentation',
            'team_building' => 'mdi-account-multiple',
            'otro' => 'mdi-calendar',
        ];

        return $iconos[$this->tipo] ?? 'mdi-calendar';
    }

    public function getCupoDisponibleAttribute()
    {
        if (!$this->cupo_maximo) {
            return null;
        }

        $confirmados = $this->confirmados()->count();
        return $this->cupo_maximo - $confirmados;
    }

    public function getTieneCupoAttribute()
    {
        if (!$this->cupo_maximo) {
            return true;
        }

        return $this->cupo_disponible > 0;
    }

    public function getEsProximoAttribute()
    {
        return $this->fecha_inicio->isFuture() &&
               $this->fecha_inicio->diffInDays(now()) <= 7;
    }

    // Métodos
    public function publicar()
    {
        $this->update(['estado' => 'publicado']);
    }

    public function iniciar()
    {
        $this->update(['estado' => 'en_curso']);
    }

    public function finalizar()
    {
        $this->update(['estado' => 'finalizado']);
    }

    public function cancelar()
    {
        $this->update(['estado' => 'cancelado']);
    }
}
