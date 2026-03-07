<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ExtranetPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder asigna permisos del módulo Extranet a los roles existentes
     *
     * Ejecutar con: php artisan db:seed --class=ExtranetPermissionsSeeder
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('');
        $this->command->info('🔐 Asignando permisos de Extranet a roles...');
        $this->command->info('');

        // Definir permisos por categoría
        $permisosBasicos = [
            'sidebar_extranet',
            'ver-dashboard-extranet',
            'ver-comunicados',
            'ver-eventos',
            'ver-proyectos',
            'ver-reconocimientos',
            'ver-documentos',
            'ver-galeria',
            'ver-muro',
            'ver-directorio',
            'comentar',
            'reaccionar',
            'responder-encuesta',
        ];

        $permisosCreacion = [
            'crear-comunicado',
            'crear-evento',
            'crear-proyecto',
            'crear-reconocimiento',
            'subir-documento',
            'subir-fotos',
            'publicar-muro',
        ];

        $permisosEdicion = [
            'editar-comunicado',
            'editar-evento',
            'editar-proyecto',
            'editar-reconocimiento',
            'editar-documento',
            'editar-album',
        ];

        $permisosAdministrativos = [
            'eliminar-comunicado',
            'eliminar-evento',
            'eliminar-proyecto',
            'eliminar-reconocimiento',
            'eliminar-documento',
            'eliminar-album',
            'eliminar-fotos',
            'eliminar-comentarios',
            'fijar-comunicado',
            'archivar-comunicado',
            'gestionar-asistentes',
            'gestionar-tareas',
            'asignar-tareas',
            'crear-encuesta',
            'editar-encuesta',
            'eliminar-encuesta',
            'ver-resultados-encuesta',
            'gestionar-notificaciones',
            'crear-album',
        ];

        // ===== ASIGNACIÓN POR ROLES =====

        // 1. RECURSOS HUMANOS - Acceso completo a comunicados, eventos, reconocimientos
        $rolRRHH = Role::where('name', 'Recursos Humanos')->first();
        if ($rolRRHH) {
            $permisosRRHH = array_merge(
                $permisosBasicos,
                $permisosCreacion,
                $permisosEdicion,
                [
                    'eliminar-comunicado',
                    'eliminar-evento',
                    'eliminar-reconocimiento',
                    'fijar-comunicado',
                    'archivar-comunicado',
                    'gestionar-asistentes',
                    'crear-encuesta',
                    'editar-encuesta',
                    'ver-resultados-encuesta',
                    'gestionar-notificaciones',
                ]
            );

            $this->asignarPermisos($rolRRHH, $permisosRRHH);
        }

        // 2. DESARROLLADOR - Acceso completo (para gestión técnica)
        $rolDev = Role::where('name', 'Desarrollador')->first();
        if ($rolDev) {
            $permisosDev = array_merge(
                $permisosBasicos,
                $permisosCreacion,
                $permisosEdicion,
                $permisosAdministrativos
            );

            $this->asignarPermisos($rolDev, $permisosDev);
        }

        // 3. DIRECTOR FINANCIERO - Acceso a comunicados, proyectos y documentos
        $rolDirFinanciero = Role::where('name', 'Director Financiero')->first();
        if ($rolDirFinanciero) {
            $permisosDF = array_merge(
                $permisosBasicos,
                [
                    'crear-comunicado',
                    'editar-comunicado',
                    'crear-proyecto',
                    'editar-proyecto',
                    'gestionar-tareas',
                    'asignar-tareas',
                    'subir-documento',
                    'editar-documento',
                    'fijar-comunicado',
                ]
            );

            $this->asignarPermisos($rolDirFinanciero, $permisosDF);
        }

        // 4. DIRECTOR DE OPERACIONES - Similar a Director Financiero
        $rolDirOp = Role::where('name', 'Director de Operaciones')->first();
        if ($rolDirOp) {
            $permisosDO = array_merge(
                $permisosBasicos,
                [
                    'crear-comunicado',
                    'editar-comunicado',
                    'crear-proyecto',
                    'editar-proyecto',
                    'gestionar-tareas',
                    'asignar-tareas',
                    'subir-documento',
                    'editar-documento',
                    'fijar-comunicado',
                ]
            );

            $this->asignarPermisos($rolDirOp, $permisosDO);
        }

        // 5. COORDINADOR OP - Puede crear proyectos y gestionar tareas
        $rolCoord = Role::where('name', 'Coordinador OP')->first();
        if ($rolCoord) {
            $permisosCoord = array_merge(
                $permisosBasicos,
                [
                    'crear-proyecto',
                    'editar-proyecto',
                    'gestionar-tareas',
                    'asignar-tareas',
                    'subir-documento',
                    'subir-fotos',
                ]
            );

            $this->asignarPermisos($rolCoord, $permisosCoord);
        }

        // 6. SUPERVISOR OP - Similar a Coordinador
        $rolSupervisor = Role::where('name', 'Supervisor OP')->first();
        if ($rolSupervisor) {
            $permisosSup = array_merge(
                $permisosBasicos,
                [
                    'crear-proyecto',
                    'editar-proyecto',
                    'gestionar-tareas',
                    'asignar-tareas',
                    'subir-documento',
                ]
            );

            $this->asignarPermisos($rolSupervisor, $permisosSup);
        }

        // 7. COMERCIAL - Acceso de lectura y participación
        $rolComercial = Role::where('name', 'Comercial')->first();
        if ($rolComercial) {
            $this->asignarPermisos($rolComercial, $permisosBasicos);
        }

        // 8. AGENTE - Solo lectura básica
        $rolAgente = Role::where('name', 'Agente')->first();
        if ($rolAgente) {
            $permisosAgente = [
                'sidebar_extranet',
                'ver-dashboard-extranet',
                'ver-comunicados',
                'ver-eventos',
                'ver-reconocimientos',
                'ver-galeria',
                'ver-muro',
                'ver-directorio',
                'comentar',
                'reaccionar',
            ];

            $this->asignarPermisos($rolAgente, $permisosAgente);
        }

        $this->command->info('');
        $this->command->info('✅ Permisos de Extranet asignados exitosamente!');
        $this->command->info('');

        // Mostrar resumen
        $this->mostrarResumen();
    }

    /**
     * Asignar permisos a un rol
     */
    private function asignarPermisos($rol, $nombresPermisos)
    {
        $permisos = Permission::whereIn('name', $nombresPermisos)->get();
        $rol->syncPermissions($permisos);

        $this->command->info("✓ {$rol->name}: {$permisos->count()} permisos asignados");
    }

    /**
     * Mostrar resumen de permisos por rol
     */
    private function mostrarResumen()
    {
        $this->command->info('📊 Resumen de permisos por rol:');
        $this->command->info('');

        $roles = Role::with('permissions')->orderBy('name')->get();

        foreach ($roles as $rol) {
            $permisosExtranet = $rol->permissions()
                ->where(function($q) {
                    $q->where('name', 'like', '%comunicado%')
                      ->orWhere('name', 'like', '%evento%')
                      ->orWhere('name', 'like', '%proyecto%')
                      ->orWhere('name', 'like', '%reconocimiento%')
                      ->orWhere('name', 'like', '%encuesta%')
                      ->orWhere('name', 'like', '%documento%')
                      ->orWhere('name', 'like', '%galeria%')
                      ->orWhere('name', 'like', '%muro%')
                      ->orWhere('name', 'like', '%directorio%')
                      ->orWhere('name', 'like', '%extranet%')
                      ->orWhere('name', 'like', '%album%')
                      ->orWhere('name', 'like', '%fotos%')
                      ->orWhere('name', 'like', '%asistentes%')
                      ->orWhere('name', 'like', '%tareas%')
                      ->orWhere('name', 'like', '%comentar%')
                      ->orWhere('name', 'like', '%reaccionar%')
                      ->orWhere('name', 'like', '%publicar%');
                })
                ->count();

            if ($permisosExtranet > 0) {
                $this->command->info("   {$rol->name}: {$permisosExtranet} permisos de extranet");
            }
        }

        $this->command->info('');
    }
}
