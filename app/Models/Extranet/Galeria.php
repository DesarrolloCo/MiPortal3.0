<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galerias';

    protected $fillable = [
        'titulo',
        'descripcion',
        'evento_id',
        'fecha',
        'autor_id',
        'portada_url',
        'visible_para',
        'total_fotos',
    ];

    protected $casts = [
        'fecha' => 'date',
        'visible_para' => 'array',
        'total_fotos' => 'integer',
    ];

    // Relaciones
    public function evento()
    {
        return $this->belongsTo(EventoExtranet::class, 'evento_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoGaleria::class, 'galeria_id')->orderBy('orden');
    }

    // Métodos
    public function actualizarTotalFotos()
    {
        $this->update(['total_fotos' => $this->fotos()->count()]);
    }

    public function actualizarPortada($fotoUrl)
    {
        $this->update(['portada_url' => $fotoUrl]);
    }
}
