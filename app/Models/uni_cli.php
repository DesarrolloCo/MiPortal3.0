<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class uni_cli extends Model
{
    use HasFactory;
    protected $primaryKey = 'UNC_ID';

    public function unidadNegocio(): BelongsTo
    {
        return $this->belongsTo(unidad_negocio::class, 'UNI_ID', 'UNI_ID');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(cliente::class, 'CLI_ID', 'CLI_ID');
    }
}
