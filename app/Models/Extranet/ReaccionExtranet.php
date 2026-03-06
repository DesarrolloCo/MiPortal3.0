<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ReaccionExtranet extends Model
{
    use HasFactory;

    protected $table = 'reacciones_extranet';

    protected $fillable = [
        'reaccionable_type',
        'reaccionable_id',
        'autor_id',
        'tipo',
    ];

    // Relaciones
    public function reaccionable()
    {
        return $this->morphTo();
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    // Scopes
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Accessors
    public function getTipoEmojiAttribute()
    {
        $emojis = [
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😠',
        ];

        return $emojis[$this->tipo] ?? '👍';
    }

    // Métodos estáticos
    public static function toggleReaccion($reaccionableType, $reaccionableId, $autorId, $tipo = 'like')
    {
        $reaccion = self::where('reaccionable_type', $reaccionableType)
            ->where('reaccionable_id', $reaccionableId)
            ->where('autor_id', $autorId)
            ->first();

        if ($reaccion) {
            if ($reaccion->tipo === $tipo) {
                // Si es la misma reacción, eliminar
                $reaccion->delete();
                return ['action' => 'removed', 'tipo' => null];
            } else {
                // Si es diferente, actualizar
                $reaccion->update(['tipo' => $tipo]);
                return ['action' => 'updated', 'tipo' => $tipo];
            }
        } else {
            // Crear nueva reacción
            self::create([
                'reaccionable_type' => $reaccionableType,
                'reaccionable_id' => $reaccionableId,
                'autor_id' => $autorId,
                'tipo' => $tipo,
            ]);
            return ['action' => 'created', 'tipo' => $tipo];
        }
    }
}
