<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class unidad_negocio extends Model
{
    use HasFactory;
    protected $primaryKey = 'UNI_ID';

    public function campanas(): HasMany
    {
        return $this->hasMany(campana::class, 'UNI_ID', 'UNI_ID');
    }
}
