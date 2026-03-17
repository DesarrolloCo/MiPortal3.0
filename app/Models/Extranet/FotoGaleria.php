<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoGaleria extends Model
{
    use HasFactory;

    protected $table = 'fotos_galeria';

    protected $fillable = [
        'galeria_id',
        'archivo_url',
        'descripcion',
        'orden',
        'likes',
    ];

    protected $casts = [
        'orden' => 'integer',
        'likes' => 'integer',
    ];

    // Relaciones
    public function galeria()
    {
        return $this->belongsTo(Galeria::class, 'galeria_id');
    }

    // Accessors
    public function getUserHasLikedAttribute()
    {
        // Por ahora retornar false
        // TODO: Implementar tabla de likes por usuario cuando se requiera
        return false;
    }

    // Métodos
    public function incrementarLikes()
    {
        $this->increment('likes');
    }
}
