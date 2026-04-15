<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emp_contrato extends Model
{
    use HasFactory;

    protected $table = 'emp_contratos';
    protected $primaryKey = 'EMC_ID';

    protected $fillable = [
        'EMP_ID',
        'CAR_ID',
        'TIC_ID',
        'EMC_SUELDO',
        'USER_CREATED',
        'EMC_FECHA_INI',
        'EMC_FECHA_FIN',
        'EMC_FINALIZADO'
    ];

    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'EMP_ID', 'EMP_ID');
    }

    // Relación con cargo
    public function cargo()
    {
        return $this->belongsTo(cargo::class, 'CAR_ID', 'CAR_ID');
    }

    // Relación con tipo de contrato
    public function tipoContrato()
    {
        return $this->belongsTo(tipos_contrato::class, 'TIC_ID', 'TIC_ID');
    }
}
