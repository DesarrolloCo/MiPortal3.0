<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MainPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Asigna permisos principales del sistema a los roles existentes
     *
     * Ejecutar con: php artisan db:seed --class=MainPermissionsSeeder
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('');
        $this->command->info('🔐 Asignando permisos principales a roles...');
        $this->command->info('');

        // Permisos de sidebar
        $permisosSidebar = [
            'sidebar_administrador',
            'sidebar_recursos_humanos',
            'sidebar_contabilidad',
            'sidebar_operaciones',
            'sidebar_agente',
            'sidebar_supervisor',
            'sidebar_reportes',
            'sidebar_mi_inventario',
            'sidebar_mi_visita',
        ];

        // Permisos RRHH
        $permisosRRHH = [
            'ver-cargo', 'crear-cargo', 'opciones-cargo',
            'ver-empleado', 'crear-empleado', 'importar-empleado', 'opciones-empleado',
        ];

        // Permisos Contabilidad
        $permisosContabilidad = [
            'ver-uni_negocio', 'crear-uni_negocio', 'opciones-uni_negocio',
            'ver-cli', 'crear-cli', 'opciones-cli',
            'ver-uni_cli', 'crear-uni_cli', 'opciones-uni_cli',
        ];

        // Permisos Operaciones
        $permisosOperaciones = [
            'ver-jornada', 'crear-jornada', 'opciones-jornada',
            'ver-camp', 'crear-camp', 'opciones-camp',
        ];

        // Permisos Reportes
        $permisosReportes = [
            'report_operaciones', 'report_finanzas',
        ];

        // Asignar permisos por rol

        // 1. ADMINISTRADOR - Acceso completo
        $rolAdmin = Role::where('name', 'Administrador')->first();
        if ($rolAdmin) {
            $permisosAdmin = array_merge(
                $permisosSidebar,
                $permisosRRHH,
                $permisosContabilidad,
                $permisosOperaciones,
                $permisosReportes
            );
            $this->asignarPermisos($rolAdmin, $permisosAdmin);
        }

        // 2. RECURSOS HUMANOS - Solo RRHH
        $rolRRHH = Role::where('name', 'Recursos Humanos')->first();
        if ($rolRRHH) {
            $permisosRRHHFull = array_merge(
                ['sidebar_recursos_humanos'],
                $permisosRRHH
            );
            $this->asignarPermisos($rolRRHH, $permisosRRHHFull);
        }

        // 3. CONTABILIDAD - Solo Contabilidad
        $rolContabilidad = Role::where('name', 'Contadores')->first();
        if ($rolContabilidad) {
            $permisosContFull = array_merge(
                ['sidebar_contabilidad'],
                $permisosContabilidad
            );
            $this->asignarPermisos($rolContabilidad, $permisosContFull);
        }

        // 4. OPERACIONES - Solo Operaciones
        $rolOperaciones = Role::where('name', 'Supervisor')->first();
        if ($rolOperaciones) {
            $permisosOpFull = array_merge(
                ['sidebar_operaciones', 'sidebar_supervisor'],
                $permisosOperaciones
            );
            $this->asignarPermisos($rolOperaciones, $permisosOpFull);
        }

        // 5. AGENTE - Limitado
        $rolAgente = Role::where('name', 'Agente')->first();
        if ($rolAgente) {
            $permisosAgente = [
                'sidebar_agente',
                'sidebar_mi_inventario',
                'sidebar_mi_visita',
            ];
            $this->asignarPermisos($rolAgente, $permisosAgente);
        }

        $this->mostrarResumen();
    }

    private function asignarPermisos($rol, $nombresPermisos)
    {
        $permisos = Permission::whereIn('name', $nombresPermisos)->get();
        $rol->syncPermissions($permisos);

        $this->command->info("✓ {$rol->name}: {$permisos->count()} permisos asignados");
    }

    private function mostrarResumen()
    {
        $this->command->info('📊 Resumen de permisos principales por rol:');
        $this->command->info('');

        $roles = Role::with('permissions')->orderBy('name')->get();

        foreach ($roles as $rol) {
            $permisosPrincipales = $rol->permissions()
                ->where(function($q) {
                    $q->where('name', 'like', 'sidebar_%')
                      ->orWhere('name', 'like', 'ver-%')
                      ->orWhere('name', 'like', 'crear-%')
                      ->orWhere('name', 'like', 'opciones-%')
                      ->orWhere('name', 'like', 'importar-%')
                      ->orWhere('name', 'like', 'report_%');
                })
                ->count();

            if ($permisosPrincipales > 0) {
                $this->command->info("   {$rol->name}: {$permisosPrincipales} permisos principales");
            }
        }

        $this->command->info('');
    }
}