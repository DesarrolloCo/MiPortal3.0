<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Relaciones

    /**
     * Obtiene el usuario que realizó la acción
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el modelo auditado (polimórfica)
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    // Scopes

    /**
     * Scope para filtrar por tipo de modelo
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('auditable_type', $modelType);
    }

    /**
     * Scope para filtrar por tipo de evento
     */
    public function scopeForEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Métodos de ayuda

    /**
     * Obtiene una descripción legible de la acción
     */
    public function getDescriptionAttribute()
    {
        $user = $this->user ? $this->user->name : 'Sistema';
        $action = $this->getActionName();
        $model = class_basename($this->auditable_type);

        return "{$user} {$action} {$model} #{$this->auditable_id}";
    }

    /**
     * Obtiene el nombre de la acción en español
     */
    private function getActionName()
    {
        $actions = [
            'created' => 'creó',
            'updated' => 'actualizó',
            'deleted' => 'eliminó',
            'restored' => 'restauró',
        ];

        return $actions[$this->event] ?? $this->event;
    }
}
