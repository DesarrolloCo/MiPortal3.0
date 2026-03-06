<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuestas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'autor_id',
        'fecha_inicio',
        'fecha_fin',
        'anonima',
        'visible_para',
        'estado',
        'total_respuestas',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'anonima' => 'boolean',
        'visible_para' => 'array',
        'total_respuestas' => 'integer',
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function preguntas()
    {
        return $this->hasMany(PreguntaEncuesta::class, 'encuesta_id')->orderBy('orden');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaEncuesta::class, 'encuesta_id');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa')
            ->where('fecha_inicio', '<=', now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', now());
            });
    }

    public function scopeCerradas($query)
    {
        return $query->where('estado', 'cerrada');
    }

    // Accessors
    public function getEsActivaAttribute()
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        if (now() < $this->fecha_inicio) {
            return false;
        }

        if ($this->fecha_fin && now() > $this->fecha_fin) {
            return false;
        }

        return true;
    }

    // Métodos
    public function activar()
    {
        $this->update(['estado' => 'activa']);
    }

    public function cerrar()
    {
        $this->update(['estado' => 'cerrada']);
    }

    public function incrementarRespuestas()
    {
        $this->increment('total_respuestas');
    }
}
