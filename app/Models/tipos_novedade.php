<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_novedade extends Model
{
    use HasFactory;

    protected $table = 'tipos_novedades';
    protected $primaryKey = 'TIN_ID';

    protected $fillable = [
        'TIN_NOMBRE',
        'TIN_TIPO',
        'TIN_ESTADO',
        'COD_SIIGO',
    ];

    public function novedades()
    {
        return $this->hasMany(novedade::class, 'TIN_ID', 'TIN_ID');
    }

    public function scopeActivos($query)
    {
        return $query->where('TIN_ESTADO', 1);
    }

    public function getTipoTextoAttribute()
    {
        return $this->TIN_TIPO == 1 ? 'Suma horas' : 'Resta horas';
    }

    public function conceptoSiigo()
    {
        return $this->belongsTo(ConceptoNominaSiigo::class, 'COD_SIIGO', 'CODIGO');
    }
}
