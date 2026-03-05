<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() /* PARA EJECUTAR COLOCAR EN CONSOLA: php artisan db:seed --class=RoleSeeder */
    {
        //

        $permisos = [

            /* PERMISOS PARA VER OPCIONES SIDEBAR */
            'sidebar_administrador',
                'ver-roles',

                'ver-usuarios',
                'crear-usuario',
                'opciones-usuario',

            'sidebar_recursos_humanos',

                'ver-cargo',
                'crear-cargo',
                'opciones-cargo',

                'ver-empleado',
                'crear-empleado',
                'importar-empleado',
                'opciones-empleado',

            'sidebar_contabilidad',

                'ver-uni_negocio',
                'crear-uni_negocio',
                'opciones-uni_negocio',

                'ver-cli',
                'crear-cli',
                'opciones-cli',

                'ver-uni_cli',
                'crear-uni_cli',
                'opciones-uni_cli',

            'sidebar_operaciones',

                'ver-jornada',
                'crear-jornada',
                'opciones-jornada',

                'ver-camp',
                'crear-camp',
                'opciones-camp',

            'sidebar_agente',

            'sidebar_supervisor',

            'sidebar_reportes',
                'report_operaciones',
                'report_finanzas',

            'sidebar_mi_visita',
            'sidebar_mi_inventario',

        ];


        /* $roles = [

            'Administrador',
            'Supervisor',
            'Contadores',
            'Agente'

        ];


        foreach($roles as $rol) {
            Role::create(['name'=>$rol]);
        } */

        foreach($permisos as $permiso) {
            Permission::create(['name'=>$permiso]);
        }
    }
}
