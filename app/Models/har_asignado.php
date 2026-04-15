<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class har_asignado extends Model
{
    use HasFactory;

    protected $table = 'har_asignados';
    protected $primaryKey = 'HAS_ID';
    public $timestamps = false;

    protected $fillable = [
        'HAR_ID',
        'EQU_ID',
        'EMP_ID',
        'HAS_ESTADO',
        'HAS_STATUS',
        'HAS_COMENTARIO'
    ];
}
