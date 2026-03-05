<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class malla extends Model
{
    use HasFactory;

    protected $table = 'mallas';
    protected $primaryKey = 'MAL_ID';

    protected $fillable = [
        'CAM_ID',
        'MAL_DIA',
        'MAL_INICIO',
        'MAL_FINAL',
        'EMP_ID',
        'USER_ID',
        'MAL_ESTADO'
    ];

    public function campana(): BelongsTo
    {
        return $this->belongsTo(campana::class, 'CAM_ID', 'CAM_ID');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(empleado::class, 'EMP_ID', 'EMP_ID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'USER_ID', 'id');
    }
}
