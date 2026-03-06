<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Comunicado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comunicados';

    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'prioridad',
        'fecha_inicio',
        'fecha_fin',
        'archivo_url',
        'imagen_url',
        'autor_id',
        'visible_para',
        'fijado',
        'estado',
        'vistas',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'visible_para' => 'array',
        'fijado' => 'boolean',
        'vistas' => 'integer',
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopePublicados($query)
    {
        return $query->where('estado', 'publicado');
    }

    public function scopeFijados($query)
    {
        return $query->where('fijado', true);
    }

    public function scopeVigentes($query)
    {
        return $query->where('fecha_inicio', '<=', now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', now());
            });
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    // Accessors
    public function getPrioridadColorAttribute()
    {
        $colores = [
            'baja' => 'success',
            'media' => 'info',
            'alta' => 'warning',
            'critica' => 'danger',
        ];

        return $colores[$this->prioridad] ?? 'secondary';
    }

    public function getTipoIconoAttribute()
    {
        $iconos = [
            'general' => 'mdi-information',
            'urgente' => 'mdi-alert',
            'rh' => 'mdi-account-group',
            'ti' => 'mdi-desktop-classic',
            'operaciones' => 'mdi-cogs',
            'admin' => 'mdi-shield-account',
        ];

        return $iconos[$this->tipo] ?? 'mdi-file-document';
    }

    public function getEsVigenteAttribute()
    {
        $inicio = $this->fecha_inicio;
        $fin = $this->fecha_fin;

        if (now() < $inicio) {
            return false;
        }

        if ($fin && now() > $fin) {
            return false;
        }

        return true;
    }

    // Métodos
    public function incrementarVistas()
    {
        $this->increment('vistas');
    }

    public function fijar()
    {
        $this->update(['fijado' => true]);
    }

    public function desfijar()
    {
        $this->update(['fijado' => false]);
    }

    public function publicar()
    {
        $this->update(['estado' => 'publicado']);
    }

    public function archivar()
    {
        $this->update(['estado' => 'archivado']);
    }
}
