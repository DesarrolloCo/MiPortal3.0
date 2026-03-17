<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PublicacionMuro extends Model
{
    use HasFactory;

    protected $table = 'publicaciones_muro';

    protected $fillable = [
        'tipo',
        'referencia_id',
        'titulo',
        'contenido',
        'imagen_url',
        'autor_id',
        'destacado',
        'comentarios_habilitados',
        'total_comentarios',
        'total_reacciones',
        'vistas',
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'comentarios_habilitados' => 'boolean',
        'total_comentarios' => 'integer',
        'total_reacciones' => 'integer',
        'vistas' => 'integer',
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioExtranet::class, 'publicacion_id')
            ->whereNull('comentario_padre_id')
            ->orderBy('created_at', 'desc');
    }

    public function reacciones()
    {
        return $this->morphMany(ReaccionExtranet::class, 'reaccionable');
    }

    // Relación polimórfica inversa para obtener el objeto original
    public function referencia()
    {
        switch ($this->tipo) {
            case 'comunicado':
                return $this->belongsTo(Comunicado::class, 'referencia_id');
            case 'proyecto':
                return $this->belongsTo(Proyecto::class, 'referencia_id');
            case 'evento':
                return $this->belongsTo(EventoExtranet::class, 'referencia_id');
            case 'reconocimiento':
                return $this->belongsTo(Reconocimiento::class, 'referencia_id');
            case 'documento':
                return $this->belongsTo(DocumentoExtranet::class, 'referencia_id');
            case 'encuesta':
                return $this->belongsTo(Encuesta::class, 'referencia_id');
            default:
                return null;
        }
    }

    // Scopes
    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($dias))
            ->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getTipoIconoAttribute()
    {
        $iconos = [
            'comunicado' => 'mdi-bullhorn',
            'proyecto' => 'mdi-briefcase',
            'evento' => 'mdi-calendar',
            'reconocimiento' => 'mdi-trophy',
            'cumpleanos' => 'mdi-cake',
            'aniversario' => 'mdi-party-popper',
            'nuevo_empleado' => 'mdi-account-plus',
            'documento' => 'mdi-file',
            'encuesta' => 'mdi-poll',
            'manual' => 'mdi-help-circle',
        ];

        return $iconos[$this->tipo] ?? 'mdi-information';
    }

    public function getTipoColorAttribute()
    {
        $colores = [
            'comunicado' => 'info',
            'proyecto' => 'primary',
            'evento' => 'warning',
            'reconocimiento' => 'success',
            'cumpleanos' => 'pink',
            'aniversario' => 'purple',
            'nuevo_empleado' => 'teal',
            'documento' => 'secondary',
            'encuesta' => 'indigo',
            'manual' => 'cyan',
        ];

        return $colores[$this->tipo] ?? 'secondary';
    }

    // Métodos
    public function getUserReaccionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }

        $reaccion = $this->reacciones()
            ->where('autor_id', auth()->id())
            ->first();

        return $reaccion ? $reaccion->tipo : null;
    }

    public function incrementarVistas()
    {
        $this->increment('vistas');
    }

    public function destacar()
    {
        $this->update(['destacado' => true]);
    }

    public function quitarDestacado()
    {
        $this->update(['destacado' => false]);
    }

    public function habilitarComentarios()
    {
        $this->update(['comentarios_habilitados' => true]);
    }

    public function deshabilitarComentarios()
    {
        $this->update(['comentarios_habilitados' => false]);
    }

    public function actualizarTotales()
    {
        $this->update([
            'total_comentarios' => $this->comentarios()->count(),
            'total_reacciones' => $this->reacciones()->count(),
        ]);
    }
}
