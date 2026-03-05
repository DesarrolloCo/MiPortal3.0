<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoNominaSiigo extends Model
{
    use HasFactory;

    protected $table = 'concepto_nomina_siigo';

    protected $fillable = [
        'CODIGO',
        'NOMBRE',
        'concepto_dian'
    ];

    public function tiposNovedades()
    {
        return $this->hasMany(tipos_novedade::class, 'COD_SIIGO', 'CODIGO');
    }
}
