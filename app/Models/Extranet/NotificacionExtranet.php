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
        'datos_adicionales',
        'leida',
        'fecha_lectura',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'fecha_lectura' => 'datetime',
        'datos_adicionales' => 'array',
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
            'fecha_lectura' => now(),
        ]);
    }

    public function marcarComoNoLeida()
    {
        $this->update([
            'leida' => false,
            'fecha_lectura' => null,
        ]);
    }

    // Método estático para crear notificación
    public static function crear($datos)
    {
        return self::create([
            'empleado_id' => $datos['empleado_id'],
            'tipo' => $datos['tipo'] ?? 'sistema',
            'titulo' => $datos['titulo'],
            'mensaje' => $datos['mensaje'] ?? null,
            'datos_adicionales' => $datos['datos_adicionales'] ?? null,
            'leida' => false,
        ]);
    }
}
