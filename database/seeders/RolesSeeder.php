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

            // PERMISOS MÓDULO EXTRANET
            'sidebar_extranet',

                // Dashboard
                'ver-dashboard-extranet',

                // Comunicados
                'ver-comunicados',
                'crear-comunicado',
                'editar-comunicado',
                'eliminar-comunicado',
                'fijar-comunicado',
                'archivar-comunicado',

                // Proyectos
                'ver-proyectos',
                'crear-proyecto',
                'editar-proyecto',
                'eliminar-proyecto',
                'gestionar-tareas',
                'asignar-tareas',

                // Eventos
                'ver-eventos',
                'crear-evento',
                'editar-evento',
                'eliminar-evento',
                'gestionar-asistentes',

                // Reconocimientos
                'ver-reconocimientos',
                'crear-reconocimiento',
                'editar-reconocimiento',
                'eliminar-reconocimiento',

                // Encuestas
                'ver-encuestas',
                'crear-encuesta',
                'editar-encuesta',
                'eliminar-encuesta',
                'ver-resultados-encuesta',
                'responder-encuesta',

                // Documentos
                'ver-documentos',
                'subir-documento',
                'editar-documento',
                'eliminar-documento',
                'gestionar-versiones',

                // Galería
                'ver-galeria',
                'crear-album',
                'editar-album',
                'eliminar-album',
                'subir-fotos',
                'eliminar-fotos',

                // Muro Social
                'ver-muro',
                'publicar-muro',
                'comentar',
                'reaccionar',
                'eliminar-comentarios',

                // Directorio
                'ver-directorio',

                // Notificaciones
                'gestionar-notificaciones',

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
