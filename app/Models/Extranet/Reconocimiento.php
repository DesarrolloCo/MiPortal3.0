<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\empleado;
use App\Models\User;

class Reconocimiento extends Model
{
    use HasFactory;

    protected $table = 'reconocimientos';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'titulo',
        'descripcion',
        'otorgado_por',
        'fecha',
        'imagen_url',
        'publico',
        'destacado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'publico' => 'boolean',
        'destacado' => 'boolean',
    ];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'empleado_id', 'EMP_ID');
    }

    public function otorgadoPor()
    {
        return $this->belongsTo(User::class, 'otorgado_por');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopePublicos($query)
    {
        return $query->where('publico', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorEmpleado($query, $empleadoId)
    {
        return $query->where('empleado_id', $empleadoId);
    }

    public function scopeEmpleadoDelMes($query)
    {
        return $query->where('tipo', 'empleado_mes')
            ->whereYear('fecha', now()->year)
            ->whereMonth('fecha', now()->month);
    }

    // Accessors
    public function getTipoIconoAttribute()
    {
        $iconos = [
            'empleado_mes' => 'mdi-trophy',
            'aniversario' => 'mdi-cake-variant',
            'logro' => 'mdi-medal',
            'excelencia' => 'mdi-star',
            'innovacion' => 'mdi-lightbulb',
            'trabajo_equipo' => 'mdi-account-group',
            'otro' => 'mdi-certificate',
        ];

        return $iconos[$this->tipo] ?? 'mdi-certificate';
    }

    public function getTipoColorAttribute()
    {
        $colores = [
            'empleado_mes' => 'warning',
            'aniversario' => 'info',
            'logro' => 'success',
            'excelencia' => 'primary',
            'innovacion' => 'purple',
            'trabajo_equipo' => 'teal',
            'otro' => 'secondary',
        ];

        return $colores[$this->tipo] ?? 'secondary';
    }

    // Métodos
    public function destacar()
    {
        $this->update(['destacado' => true]);
    }

    public function quitarDestacado()
    {
        $this->update(['destacado' => false]);
    }
}
