<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ComentarioExtranet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comentarios_extranet';

    protected $fillable = [
        'publicacion_id',
        'comentario_padre_id',
        'autor_id',
        'contenido',
        'total_reacciones',
    ];

    protected $casts = [
        'total_reacciones' => 'integer',
    ];

    // Relaciones
    public function publicacion()
    {
        return $this->belongsTo(PublicacionMuro::class, 'publicacion_id');
    }

    public function padre()
    {
        return $this->belongsTo(ComentarioExtranet::class, 'comentario_padre_id');
    }

    public function respuestas()
    {
        return $this->hasMany(ComentarioExtranet::class, 'comentario_padre_id')
            ->orderBy('created_at', 'asc');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function reacciones()
    {
        return $this->morphMany(ReaccionExtranet::class, 'reaccionable');
    }

    // Scopes
    public function scopePrincipales($query)
    {
        return $query->whereNull('comentario_padre_id');
    }

    // Métodos
    public function actualizarTotalReacciones()
    {
        $this->update(['total_reacciones' => $this->reacciones()->count()]);
    }
}
