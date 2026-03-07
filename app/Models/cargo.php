<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';
    protected $primaryKey = 'CAR_ID';
    public $timestamps = true;

    protected $fillable = [
        'CAR_NOMBRE',
        'CAR_CODE',
        'CAR_ESTADO',
        'USER_ID',
    ];

    /**
     * Relación con empleados
     */
    public function empleados()
    {
        return $this->hasMany(empleado::class, 'CAR_ID', 'CAR_ID');
    }
}
