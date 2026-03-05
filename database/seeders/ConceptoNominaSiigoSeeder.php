<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConceptoNominaSiigo;

class ConceptoNominaSiigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conceptos = [
            ['CODIGO' => '01', 'NOMBRE' => 'Salario básico', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '02', 'NOMBRE' => 'Subsidio de Transporte', 'concepto_dian' => 'TRANSPORTE'],
            ['CODIGO' => '03', 'NOMBRE' => 'Aux. Conectividad', 'concepto_dian' => 'TELETRABAJO'],
            ['CODIGO' => '04', 'NOMBRE' => 'AUX CONECTIVIDAD OPERATIVO', 'concepto_dian' => 'TELETRABAJO'],
            ['CODIGO' => '06', 'NOMBRE' => 'Auxilio Rodamiento', 'concepto_dian' => 'AUXILIO SALARIAL'],
            ['CODIGO' => '08', 'NOMBRE' => 'REEMBOLSO', 'concepto_dian' => 'OTROS CONCEPTOS NO SALARIAL'],
            ['CODIGO' => '10', 'NOMBRE' => 'Recargo Dominical D', 'concepto_dian' => 'HORA RECARGO NOCTURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '11', 'NOMBRE' => 'Horas Extras Diurnas 125%', 'concepto_dian' => 'HORA EXTRA DIURNA'],
            ['CODIGO' => '12', 'NOMBRE' => 'Horas Extras Nocturn 175%', 'concepto_dian' => 'HORA EXTRA NOCTURNA'],
            ['CODIGO' => '13', 'NOMBRE' => 'Hora Dominical', 'concepto_dian' => 'HORA EXTRA NOCTURNA DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '14', 'NOMBRE' => 'Horas ExtDiurnas Festivas', 'concepto_dian' => 'HORA RECARGO DIURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '15', 'NOMBRE' => 'Horas Extras Nocturn Fest', 'concepto_dian' => 'HORA EXTRA NOCTURNA DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '16', 'NOMBRE' => 'Recargo Nocturno Ordinari', 'concepto_dian' => 'HORA RECARGO NOCTURNO'],
            ['CODIGO' => '17', 'NOMBRE' => 'Recargo Nocturno + Festiv', 'concepto_dian' => 'HORA RECARGO DIURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '18', 'NOMBRE' => 'Dia Dominical', 'concepto_dian' => 'HORA EXTRA DIURNA DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '19', 'NOMBRE' => 'Recargo Diurno Festivo', 'concepto_dian' => 'HORA RECARGO DIURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '20', 'NOMBRE' => 'Incapacidad Grl - Empresa', 'concepto_dian' => 'INCAPACIDAD COMÚN'],
            ['CODIGO' => '21', 'NOMBRE' => 'Vacaciones Disfrutadas', 'concepto_dian' => 'VACACIONES COMUNES'],
            ['CODIGO' => '22', 'NOMBRE' => 'Incapacidad Laboral', 'concepto_dian' => 'INCAPACIDAD LABORAL'],
            ['CODIGO' => '24', 'NOMBRE' => 'Incapacidad Eps2', 'concepto_dian' => 'INCAPACIDAD COMÚN'],
            ['CODIGO' => '25', 'NOMBRE' => 'Descanso Laborado', 'concepto_dian' => 'OTROS CONCEPTOS SALARIAL'],
            ['CODIGO' => '27', 'NOMBRE' => 'Vacaciones Disfrutadas', 'concepto_dian' => 'VACACIONES COMUNES'],
            ['CODIGO' => '28', 'NOMBRE' => 'Licencia Maternidad', 'concepto_dian' => 'LICENCIA MATERNIDAD O PATERNIDAD'],
            ['CODIGO' => '33', 'NOMBRE' => 'DOMINICAL NOC', 'concepto_dian' => 'HORA RECARGO NOCTURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '36', 'NOMBRE' => 'RETROACTIVO RECARGOS', 'concepto_dian' => 'HORA RECARGO NOCTURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '37', 'NOMBRE' => 'LICENCIA DE MATERNIDAD', 'concepto_dian' => 'LICENCIA MATERNIDAD O PATERNIDAD'],
            ['CODIGO' => '50', 'NOMBRE' => 'Vacaciones Liq. Contrato', 'concepto_dian' => 'VACACIONES COMUNES'],
            ['CODIGO' => '60', 'NOMBRE' => 'Licencia Remunerada', 'concepto_dian' => 'LICENCIA REMUNERADA'],
            ['CODIGO' => '61', 'NOMBRE' => 'Licencia no Remunerada', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '63', 'NOMBRE' => 'No Laboró', 'concepto_dian' => 'LICENCIA NO REMUNERADA'],
            ['CODIGO' => '64', 'NOMBRE' => 'Suspensión', 'concepto_dian' => 'SANCIÓN PRIVADA'],
            ['CODIGO' => '65', 'NOMBRE' => 'Licencia de Luto', 'concepto_dian' => 'LICENCIA REMUNERADA'],
            ['CODIGO' => '66', 'NOMBRE' => 'Incapacidad Aprendiz', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '68', 'NOMBRE' => 'Reajuste Sueldo', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '90', 'NOMBRE' => 'BONIFICACIONES NO', 'concepto_dian' => 'BONIFICACIÓN NO SALARIAL'],
            ['CODIGO' => '94', 'NOMBRE' => 'Bonificacion Si', 'concepto_dian' => 'BONIFICACIÓN SALARIAL'],
            ['CODIGO' => '95', 'NOMBRE' => 'Bonificacion Aprendiz', 'concepto_dian' => 'BONIFICACIÓN NO SALARIAL'],
            ['CODIGO' => '200', 'NOMBRE' => 'Fondo de Salud', 'concepto_dian' => 'SALUD'],
            ['CODIGO' => '201', 'NOMBRE' => 'Fondo de Pension', 'concepto_dian' => 'PENSIÓN'],
            ['CODIGO' => '202', 'NOMBRE' => 'Fondo Solidarid Pensional', 'concepto_dian' => 'PENSIÓN'],
            ['CODIGO' => '204', 'NOMBRE' => 'Aporte Voluntario Pension', 'concepto_dian' => 'PENSIÓN VOLUNTARIA'],
            ['CODIGO' => '205', 'NOMBRE' => 'Aporte Vol Pension Emple', 'concepto_dian' => 'PENSIÓN VOLUNTARIA'],
            ['CODIGO' => '206', 'NOMBRE' => 'Ahorro Voluntario Pensión', 'concepto_dian' => 'PENSIÓN VOLUNTARIA'],
            ['CODIGO' => '210', 'NOMBRE' => 'Retencion en la Fuente', 'concepto_dian' => 'RETENCIÓN EN LA FUENTE'],
            ['CODIGO' => '211', 'NOMBRE' => 'Retencion Mínima', 'concepto_dian' => 'SIN ASIGNAR'],
            ['CODIGO' => '220', 'NOMBRE' => 'Anticipo Salario', 'concepto_dian' => 'ANTICIPOS'],
            ['CODIGO' => '221', 'NOMBRE' => 'Prestamos empleados', 'concepto_dian' => 'ANTICIPOS'],
            ['CODIGO' => '222', 'NOMBRE' => 'RETROACTIVO ANTICIPO', 'concepto_dian' => 'ANTICIPOS'],
            ['CODIGO' => '223', 'NOMBRE' => 'AUMENTO SALARIO', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '230', 'NOMBRE' => 'Ahorros', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '240', 'NOMBRE' => 'Embargos', 'concepto_dian' => 'EMBARGO FISCAL'],
            ['CODIGO' => '250', 'NOMBRE' => 'Descuentos Varios', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '260', 'NOMBRE' => 'Provision Cesantias', 'concepto_dian' => 'CESANTÍAS'],
            ['CODIGO' => '261', 'NOMBRE' => 'Provision Inter. Cesantia', 'concepto_dian' => 'CESANTÍAS'],
            ['CODIGO' => '262', 'NOMBRE' => 'Provision Primas', 'concepto_dian' => 'PRIMA'],
            ['CODIGO' => '263', 'NOMBRE' => 'Provision de Vacaciones', 'concepto_dian' => 'SIN ASIGNAR'],
            ['CODIGO' => '264', 'NOMBRE' => 'SEGURO EXEQUIAL', 'concepto_dian' => 'DEUDA'],
            ['CODIGO' => '265', 'NOMBRE' => 'Descuento Dominical', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '266', 'NOMBRE' => 'Nuevo Seguro Exequial', 'concepto_dian' => 'DEUDA'],
            ['CODIGO' => '267', 'NOMBRE' => 'Salario Aprendiz', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '268', 'NOMBRE' => 'Entrenamiento', 'concepto_dian' => 'OTROS CONCEPTOS NO SALARIAL'],
            ['CODIGO' => '269', 'NOMBRE' => 'Ajuste Primas Favor', 'concepto_dian' => 'PRIMA'],
            ['CODIGO' => '270', 'NOMBRE' => 'Ajuste Primas Contra', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '271', 'NOMBRE' => 'Retoactivo Domingo', 'concepto_dian' => 'HORA RECARGO DIURNO DOMINICAL Y FESTIVOS'],
            ['CODIGO' => '272', 'NOMBRE' => 'Retroactivo Dia', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '273', 'NOMBRE' => 'Dcto Horas', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '274', 'NOMBRE' => 'Descuento Diciembre', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '275', 'NOMBRE' => 'Auxilio Nocturno', 'concepto_dian' => 'HORA RECARGO NOCTURNO'],
            ['CODIGO' => '276', 'NOMBRE' => 'Salario', 'concepto_dian' => 'BÁSICO'],
            ['CODIGO' => '277', 'NOMBRE' => 'Retroactivo Aux Transport', 'concepto_dian' => 'TRANSPORTE'],
            ['CODIGO' => '300', 'NOMBRE' => 'cxc empleados', 'concepto_dian' => 'ANTICIPOS'],
            ['CODIGO' => '400', 'NOMBRE' => 'Licecia Paternidad', 'concepto_dian' => 'LICENCIA MATERNIDAD O PATERNIDAD'],
            ['CODIGO' => '500', 'NOMBRE' => 'LIBRANZA', 'concepto_dian' => 'ANTICIPOS'],
            ['CODIGO' => '600', 'NOMBRE' => 'RETENCION VIRNA 10%', 'concepto_dian' => 'RETENCIÓN EN LA FUENTE'],
            ['CODIGO' => '700', 'NOMBRE' => 'DESCUENTO AUX. TRANSPORTE', 'concepto_dian' => 'OTRAS DEDUCCIONES'],
            ['CODIGO' => '800', 'NOMBRE' => 'DESCUENTO DIAS VACACIONES', 'concepto_dian' => 'NINGUNO'],
            ['CODIGO' => '900', 'NOMBRE' => 'DESCUENTO DIAS', 'concepto_dian' => 'NINGUNO'],
        ];

        foreach ($conceptos as $concepto) {
            ConceptoNominaSiigo::create($concepto);
        }
    }
}
