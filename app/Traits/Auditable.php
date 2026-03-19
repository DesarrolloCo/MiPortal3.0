<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot el trait Auditable
     */
    public static function bootAuditable()
    {
        // Evento al crear
        static::created(function ($model) {
            $model->auditEvent('created');
        });

        // Evento al actualizar
        static::updated(function ($model) {
            $model->auditEvent('updated');
        });

        // Evento al eliminar
        static::deleted(function ($model) {
            $model->auditEvent('deleted');
        });

        // Evento al restaurar (soft deletes)
        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                $model->auditEvent('restored');
            });
        }
    }

    /**
     * Registra un evento de auditoría
     *
     * @param string $event
     * @return void
     */
    protected function auditEvent($event)
    {
        // Omitir auditoría si el modelo tiene la propiedad $disableAuditing = true
        if (property_exists($this, 'disableAuditing') && $this->disableAuditing) {
            return;
        }

        // Preparar valores antiguos y nuevos
        $oldValues = null;
        $newValues = null;

        if ($event === 'created') {
            $newValues = $this->getAuditableAttributes();
        } elseif ($event === 'updated') {
            $oldValues = $this->getOriginal();
            $newValues = $this->getChanges();

            // Filtrar solo los campos que cambiaron
            $oldValues = array_intersect_key($oldValues, $newValues);

            // Si no hay cambios reales, no auditar
            if (empty($newValues)) {
                return;
            }
        } elseif ($event === 'deleted') {
            $oldValues = $this->getAuditableAttributes();
        } elseif ($event === 'restored') {
            $newValues = $this->getAuditableAttributes();
        }

        // Crear registro de auditoría
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'event' => $event,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);
    }

    /**
     * Obtiene los atributos auditables del modelo
     *
     * @return array
     */
    protected function getAuditableAttributes()
    {
        // Si el modelo define $auditableFields, solo auditar esos campos
        if (property_exists($this, 'auditableFields')) {
            return array_intersect_key(
                $this->getAttributes(),
                array_flip($this->auditableFields)
            );
        }

        // Excluir campos sensibles por defecto
        $excludedFields = [
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        // Si el modelo define $nonAuditableFields, agregarlos a la exclusión
        if (property_exists($this, 'nonAuditableFields')) {
            $excludedFields = array_merge($excludedFields, $this->nonAuditableFields);
        }

        return array_diff_key(
            $this->getAttributes(),
            array_flip($excludedFields)
        );
    }

    /**
     * Relación polimórfica con audit logs
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Obtiene el último log de auditoría
     */
    public function getLastAuditLog()
    {
        return $this->auditLogs()->latest()->first();
    }
}
