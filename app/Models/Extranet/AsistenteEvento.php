<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\empleado;

class AsistenteEvento extends Model
{
    use HasFactory;

    protected $table = 'asistentes_evento';

    protected $fillable = [
        'evento_id',
        'empleado_id',
        'estado_confirmacion',
        'asistio',
    ];

    protected $casts = [
        'asistio' => 'boolean',
    ];

    // Relaciones
    public function evento()
    {
        return $this->belongsTo(EventoExtranet::class, 'evento_id');
    }

    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleado_id', 'EMP_ID');
    }

    // Scopes
    public function scopeConfirmados($query)
    {
        return $query->where('estado_confirmacion', 'confirmado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_confirmacion', 'pendiente');
    }

    public function scopeAsistieron($query)
    {
        return $query->where('asistio', true);
    }

    // Métodos
    public function confirmar()
    {
        $this->update(['estado_confirmacion' => 'confirmado']);
    }

    public function rechazar()
    {
        $this->update(['estado_confirmacion' => 'rechazado']);
    }

    public function marcarAsistencia()
    {
        $this->update(['asistio' => true]);
    }
}
