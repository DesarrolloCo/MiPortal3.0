<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class campana extends Model
{
    use HasFactory;

    protected $table = 'campanas';
    protected $primaryKey = 'CAM_ID';

    public function empleados(): HasMany
    {
        return $this->hasMany(empleado::class, 'CAM_ID');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'AREA_ID', 'AREA_ID');
    }

    public function unidadNegocioCliente(): BelongsTo
    {
        return $this->belongsTo(uni_cli::class, 'UNC_ID', 'UNC_ID');
    }
}
