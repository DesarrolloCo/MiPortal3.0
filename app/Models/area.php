<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class area extends Model
{
    use HasFactory;

    protected $table = 'areas';
    protected $primaryKey = 'AREA_ID';

    public function campanas(): HasMany
    {
        return $this->hasMany(campana::class, 'AREA_ID');
    }
}
