<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovedadHorario extends Model
{
    use HasFactory;

    protected $table = 'novedad_horarios';

    protected $fillable = [
        'nov_id',
        'mal_id'
    ];

    /**
     * Relación con novedad
     */
    public function novedad(): BelongsTo
    {
        return $this->belongsTo(novedade::class, 'nov_id', 'NOV_ID');
    }

    /**
     * Relación con horario (malla)
     */
    public function horario(): BelongsTo
    {
        return $this->belongsTo(malla::class, 'mal_id', 'MAL_ID');
    }
}