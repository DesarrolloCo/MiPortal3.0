<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovedadLog extends Model
{
    use HasFactory;

    protected $table = 'novedad_logs';

    protected $fillable = [
        'nov_id',
        'action',
        'user_id',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function novedad(): BelongsTo
    {
        return $this->belongsTo(novedade::class, 'nov_id', 'NOV_ID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getActionLabelAttribute(): string
    {
        return [
            'arrived' => 'Llegó',
            'forwarded' => 'Reenviada',
            'rejected' => 'Rechazada',
            'approved' => 'Aprobada',
        ][$this->action] ?? ucfirst($this->action);
    }

    public function getActionIconAttribute(): string
    {
        return [
            'arrived' => 'mdi-plus',
            'forwarded' => 'mdi-arrow-right',
            'rejected' => 'mdi-close-circle',
            'approved' => 'mdi-check-circle',
        ][$this->action] ?? 'mdi-circle';
    }

    public function getActionColorAttribute(): string
    {
        return [
            'arrived' => 'success',
            'forwarded' => 'info',
            'rejected' => 'danger',
            'approved' => 'success',
        ][$this->action] ?? 'secondary';
    }
}
