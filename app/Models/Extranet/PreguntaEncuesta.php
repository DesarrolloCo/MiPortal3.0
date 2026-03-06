<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaEncuesta extends Model
{
    use HasFactory;

    protected $table = 'preguntas_encuesta';

    protected $fillable = [
        'encuesta_id',
        'pregunta',
        'tipo_respuesta',
        'opciones',
        'escala_min',
        'escala_max',
        'obligatoria',
        'orden',
    ];

    protected $casts = [
        'opciones' => 'array',
        'escala_min' => 'integer',
        'escala_max' => 'integer',
        'obligatoria' => 'boolean',
        'orden' => 'integer',
    ];

    // Relaciones
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaEncuesta::class, 'pregunta_id');
    }
}
