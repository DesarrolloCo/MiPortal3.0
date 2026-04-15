<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class informe extends Model
{
    use HasFactory;

    protected $table = 'informes';
    protected $primaryKey = 'INF_ID';
    public $timestamps = false;

    protected $fillable = [
        'INF_NOMBRE',
        'INF_URL',
        'CAM_ID',
        'CLI_ID',
        'INF_ESTADO'
    ];

    // Relación con campaña/proyecto
    public function campana(): BelongsTo
    {
        return $this->belongsTo(campana::class, 'CAM_ID', 'CAM_ID');
    }

    // Relación con cliente (usuario)
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'CLI_ID', 'id');
    }
}
