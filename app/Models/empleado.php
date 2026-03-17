<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';        // Asegura que se use la tabla correcta
    protected $primaryKey = 'EMP_ID';      // Laravel sabrá que esta es la clave primaria

    public function users(){
        return $this->belongsTo(user::class, 'id');
    }

    public function campana(){
        return $this->belongsTo(campana::class, 'CAM_ID', 'CAM_ID');
    }

    public function cargo(){
        return $this->belongsTo(cargo::class, 'CAR_ID', 'CAR_ID');
    }

    public function departamento(){
        return $this->belongsTo(departamento::class, 'DEP_ID', 'DEP_ID');
    }

    public function contratos(){
        return $this->hasMany(emp_contrato::class, 'EMP_ID', 'EMP_ID');
    }

    public function reconocimientos(){
        return $this->hasMany(\App\Models\Extranet\Reconocimiento::class, 'empleado_id', 'EMP_ID');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute(){
        return trim($this->EMP_NOMBRES . ' ' . $this->EMP_APELLIDOS);
    }

    public function estado($estado, $emp_id){
        $sql = "UPDATE `empleados` SET `EMP_ACTIVO`= '".$estado."' WHERE `EMP_ID` = ".$emp_id;
        DB::update($sql);
        return "bien";
    }
}
