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

    protected $fillable = [
        'USER_ID',
        'CAR_ID',
        'CAM_ID',  // Agregado para compatibilidad con importaciones
        'DEP_ID',  // Agregado para compatibilidad con importaciones
        'EMP_CODE',
        'EMP_CEDULA',
        'MUN_ID',
        'EMP_NOMBRES',
        'EMP_DIRECCION',
        'EMP_TELEFONO',
        'EMP_SEXO',
        'EMP_FECHA_NACIMIENTO',
        'EMP_FECHA_INGRESO',
        'EMP_FECHA_RETIRO',
        'EMP_SUELDO',
        'EMP_TIPO_CONTRATO',
        'CLI_ID',
        'EMP_EMAIL',
        'EMP_ESTADO',
        'EMP_ACTIVO'
    ];

    public function users(){
        return $this->belongsTo(user::class, 'id');
    }

    public function cliente(){
        return $this->belongsTo(cliente::class, 'CLI_ID', 'CLI_ID');
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

    public function municipio(){
        return $this->belongsTo(municipio::class, 'MUN_ID', 'MUN_ID');
    }

    public function contratos(){
        return $this->hasMany(emp_contrato::class, 'EMP_ID', 'EMP_ID');
    }

    public function reconocimientos(){
        return $this->hasMany(\App\Models\Extranet\Reconocimiento::class, 'empleado_id', 'EMP_ID');
    }

    public function contratoActivo()
    {
        return $this->hasOne(emp_contrato::class, 'EMP_ID', 'EMP_ID')
            ->where('EMC_FINALIZADO', 'NO');
    }

    // Relaciones de Inventario

    /**
     * Obtiene todas las asignaciones de equipos del empleado
     */
    public function asignacionesEquipos()
    {
        return $this->hasMany(equ_asignado::class, 'EMP_ID', 'EMP_ID');
    }

    /**
     * Obtiene las asignaciones activas de equipos del empleado
     */
    public function asignacionesActivas()
    {
        return $this->hasMany(equ_asignado::class, 'EMP_ID', 'EMP_ID')
            ->where('EAS_ESTADO', 1);
    }

    /**
     * Obtiene las devoluciones que este empleado ha recibido
     */
    public function devolucionesRecibidas()
    {
        return $this->hasMany(Devolucion::class, 'DEV_RECIBIDO_POR', 'EMP_ID');
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute(){
        return trim($this->EMP_NOMBRES . ' ' . $this->EMP_APELLIDOS);
    }

    /**
     * Método obsoleto - Usar cambiarEstado() en el controlador en su lugar
     * @deprecated Este método será removido en futuras versiones
     */
    public function estado($estado, $emp_id){
        // Validar que $estado sea un valor válido (0 o 1)
        if (!in_array($estado, [0, 1, '0', '1'])) {
            return false;
        }

        // Usar Eloquent con binding de parámetros (previene SQL injection)
        return self::where('EMP_ID', $emp_id)
            ->update(['EMP_ACTIVO' => $estado]);
    }
}
