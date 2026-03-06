<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emp_contrato extends Model
{
    use HasFactory;

    protected $table = 'emp_contratos';
    protected $primaryKey = 'EMC_ID';

    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(empleado::class, 'EMP_ID', 'EMP_ID');
    }
}
