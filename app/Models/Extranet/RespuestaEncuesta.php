<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\empleado;

class RespuestaEncuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas_encuesta';

    protected $fillable = [
        'encuesta_id',
        'pregunta_id',
        'empleado_id',
        'respuesta',
    ];

    // Relaciones
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(PreguntaEncuesta::class, 'pregunta_id');
    }

    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleado_id', 'EMP_ID');
    }
}
