<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hardwares extends Model
{
    use HasFactory;

    protected $table = 'hardwares';
    protected $primaryKey = 'HAR_ID';
    public $timestamps = false;

    protected $fillable = [
        'HAR_TIPO',
        'HAR_DESCRIPCION',
        'HAR_MODELO',
        'HAR_SERIAL',
        'HAR_OBSERVACION',
        'HAR_ESTADO'
    ];
}
