<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\empleado;

class NotificacionExtranet extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_extranet';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'titulo',
        'mensaje',
        'referencia_tipo',
        'referencia_id',
        'url',
        'icono',
        'color',
        'leida',
        'leida_at',
        'importante',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'leida_at' => 'datetime',
        'importante' => 'boolean',
    ];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleado_id', 'EMP_ID');
    }

    // Scopes
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('leida', true);
    }

    public function scopeImportantes($query)
    {
        return $query->where('importante', true);
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

    // Métodos
    public function marcarComoLeida()
    {
        $this->update([
            'leida' => true,
            'leida_at' => now(),
        ]);
    }

    public function marcarComoNoLeida()
    {
        $this->update([
            'leida' => false,
            'leida_at' => null,
        ]);
    }

    // Método estático para crear notificación
    public static function crear($datos)
    {
        // Establecer valores por defecto según el tipo
        $iconos = [
            'comunicado' => 'bullhorn',
            'proyecto' => 'briefcase',
            'evento' => 'calendar',
            'reconocimiento' => 'trophy',
            'comentario' => 'comment',
            'reaccion' => 'heart',
            'mencion' => 'at',
            'cumpleanos' => 'cake',
            'aniversario' => 'party-popper',
            'sistema' => 'cog',
        ];

        $colores = [
            'comunicado' => '#17a2b8',
            'proyecto' => '#007bff',
            'evento' => '#ffc107',
            'reconocimiento' => '#28a745',
            'comentario' => '#6c757d',
            'reaccion' => '#dc3545',
            'mencion' => '#17a2b8',
            'cumpleanos' => '#e83e8c',
            'aniversario' => '#6f42c1',
            'sistema' => '#6c757d',
        ];

        $tipo = $datos['tipo'] ?? 'sistema';

        return self::create([
            'empleado_id' => $datos['empleado_id'],
            'tipo' => $tipo,
            'titulo' => $datos['titulo'],
            'mensaje' => $datos['mensaje'] ?? null,
            'referencia_tipo' => $datos['referencia_tipo'] ?? null,
            'referencia_id' => $datos['referencia_id'] ?? null,
            'url' => $datos['url'] ?? null,
            'icono' => $datos['icono'] ?? $iconos[$tipo] ?? 'bell',
            'color' => $datos['color'] ?? $colores[$tipo] ?? '#6c757d',
            'importante' => $datos['importante'] ?? false,
        ]);
    }
}
