{{-- @php
    $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $Roles->id)
    ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
    ->all();
@endphp --}}

<!-- .modal for add task -->
<div class="modal fade" id="Edit_Rol{{ $Roles->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 70% !important;">
        <div class="modal-content">
            <style>
                #Edit_Rol{{ $Roles->id }} .modal-body {
                    overflow-x: hidden;
                }
                #Edit_Rol{{ $Roles->id }} .form-control {
                    max-width: 100%;
                    width: 100%;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .card {
                    border: 1px solid #e9ecef;
                    margin-bottom: 0.5rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .card-header {
                    background-color: #f8f9fa;
                    padding: 0.5rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .btn-link {
                    color: #495057;
                    text-decoration: none;
                    font-weight: 500;
                    padding: 0.5rem;
                    width: 100%;
                    text-align: left;
                    white-space: normal;
                    word-wrap: break-word;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .btn-link:hover {
                    color: #007bff;
                    text-decoration: none;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .card-body {
                    max-height: 400px;
                    overflow-y: auto;
                    padding: 1rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .permission-group {
                    margin-bottom: 1rem;
                    padding-left: 1rem;
                    border-left: 3px solid #e9ecef;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .permission-parent {
                    font-weight: 600;
                    color: #495057;
                    margin-bottom: 0.5rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .permission-child {
                    margin-left: 1.5rem;
                    margin-bottom: 0.3rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .custom-checkbox {
                    margin-bottom: 0.5rem;
                }
                #permissionsAccordionEdit{{ $Roles->id }} .accordion {
                    width: 100%;
                }
            </style>
            <div class="modal-header">
                <h4 class="modal-title">Editar Rol</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::model($Roles, ['method' =>'PATCH','route' =>['Roles.update', $Roles->id]]) !!}
                    <div class="form-group mb-4">
                        {!! Form::text('name', $Rol->name, array('class' => 'form-control', 'placeholder' => 'Ej: Administrador, Supervisor, etc.')) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Permisos por Módulo</label>
                                <div class="accordion" id="permissionsAccordionEdit{{ $Roles->id }}">
                                    @php
                                        // Agrupar permisos por módulos del sidebar con estructura jerárquica
                                        $modulosPermisos = [
                                            'Extranet' => [
                                                'parent' => 'sidebar_extranet',
                                                'children' => [
                                                    'Dashboard' => ['ver-dashboard-extranet'],
                                                    'Comunicados' => ['ver-comunicados', 'crear-comunicado', 'editar-comunicado', 'eliminar-comunicado', 'fijar-comunicado', 'archivar-comunicado'],
                                                    'Proyectos' => ['ver-proyectos', 'crear-proyecto', 'editar-proyecto', 'eliminar-proyecto', 'gestionar-tareas', 'asignar-tareas'],
                                                    'Eventos' => ['ver-eventos', 'crear-evento', 'editar-evento', 'eliminar-evento', 'gestionar-asistentes'],
                                                    'Reconocimientos' => ['ver-reconocimientos', 'crear-reconocimiento', 'editar-reconocimiento', 'eliminar-reconocimiento'],
                                                    'Encuestas' => ['ver-encuestas', 'crear-encuesta', 'editar-encuesta', 'eliminar-encuesta', 'ver-resultados-encuesta', 'responder-encuesta'],
                                                    'Documentos' => ['ver-documentos', 'subir-documento', 'editar-documento', 'eliminar-documento', 'gestionar-versiones'],
                                                    'Galería' => ['ver-galeria', 'crear-album', 'editar-album', 'eliminar-album', 'subir-fotos', 'eliminar-fotos'],
                                                    'Muro Social' => ['ver-muro', 'publicar-muro', 'comentar', 'reaccionar', 'eliminar-comentarios'],
                                                    'Directorio' => ['ver-directorio'],
                                                    'Notificaciones' => ['gestionar-notificaciones'],
                                                ]
                                            ],
                                            'Administración' => [
                                                'parent' => 'sidebar_administrador',
                                                'children' => [
                                                    'Roles' => ['ver-roles'],
                                                    'Usuarios' => ['ver-usuarios', 'crear-usuario', 'opciones-usuario'],
                                                    'Firma de Certificados' => ['ver-firma-de-certificados'],
                                                ]
                                            ],
                                            'Recursos Humanos' => [
                                                'parent' => 'sidebar_recursos_humanos',
                                                'children' => [
                                                    'Cargos' => ['ver-cargo', 'crear-cargo', 'opciones-cargo'],
                                                    'Empleados' => ['ver-empleado', 'crear-empleado', 'importar-empleado', 'opciones-empleado'],
                                                    'Novedades' => ['ver-novedades', 'ver-tipos_novedades'],
                                                ]
                                            ],
                                            'Contabilidad' => [
                                                'parent' => 'sidebar_contabilidad',
                                                'children' => [
                                                    'Unidades de Negocio' => ['ver-uni_negocio', 'crear-uni_negocio', 'opciones-uni_negocio'],
                                                    'Clientes' => ['ver-cli', 'crear-cli', 'opciones-cli'],
                                                    'Relación Uni-Cli' => ['ver-uni_cli', 'crear-uni_cli', 'opciones-uni_cli'],
                                                ]
                                            ],
                                            'Operaciones' => [
                                                'parent' => 'sidebar_operaciones',
                                                'children' => [
                                                    'Jornadas' => ['ver-jornada', 'crear-jornada', 'opciones-jornada'],
                                                    'Campañas' => ['ver-camp', 'crear-camp', 'opciones-camp'],
                                                ]
                                            ],
                                            'Mi Horario' => [
                                                'parent' => 'sidebar_agente',
                                                'children' => []
                                            ],
                                            'Horarios (Supervisor)' => [
                                                'parent' => 'sidebar_supervisor',
                                                'children' => []
                                            ],
                                            'Mi Inventario' => [
                                                'parent' => 'sidebar_mi_inventario',
                                                'children' => []
                                            ],
                                            'Mi Visita' => [
                                                'parent' => 'sidebar_mi_visita',
                                                'children' => []
                                            ],
                                            'Mis Reportes' => [
                                                'parent' => 'sidebar_mi_reportes',
                                                'children' => []
                                            ],
                                            'Análisis y Reportes' => [
                                                'parent' => 'sidebar_reportes',
                                                'children' => [
                                                    'Reportes Operacionales' => ['report_operaciones'],
                                                    'Reportes Financieros' => ['report_finanzas'],
                                                ]
                                            ],
                                        ];

                                        $permisosNoAgrupados = [];
                                        $todosLosPermisosAgrupados = [];

                                        foreach ($modulosPermisos as $modulo => $config) {
                                            $todosLosPermisosAgrupados[] = $config['parent'];
                                            foreach ($config['children'] as $submodulo => $permisos) {
                                                $todosLosPermisosAgrupados = array_merge($todosLosPermisosAgrupados, $permisos);
                                            }
                                        }

                                        foreach ($Permissions as $permission) {
                                            if (!in_array($permission->name, $todosLosPermisosAgrupados)) {
                                                $permisosNoAgrupados[] = $permission;
                                            }
                                        }
                                    @endphp

                                    @foreach ($modulosPermisos as $modulo => $config)
                                        @php
                                            $parentPermission = $Permissions->firstWhere('name', $config['parent']);
                                            $hasChildren = count($config['children']) > 0;
                                        @endphp

                                        @if($parentPermission)
                                        <div class="card">
                                            <div class="card-header p-2" id="headingEdit{{ Str::slug($modulo) }}{{ $Roles->id }}">
                                                <h6 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseEdit{{ Str::slug($modulo) }}{{ $Roles->id }}" aria-expanded="false" aria-controls="collapseEdit{{ Str::slug($modulo) }}{{ $Roles->id }}">
                                                        <i class="mdi mdi-chevron-down"></i> {{ $modulo }}
                                                    </button>
                                                </h6>
                                            </div>
                                            <div id="collapseEdit{{ Str::slug($modulo) }}{{ $Roles->id }}" class="collapse" aria-labelledby="headingEdit{{ Str::slug($modulo) }}{{ $Roles->id }}" data-parent="#permissionsAccordionEdit{{ $Roles->id }}">
                                                <div class="card-body">
                                                    {{-- Permiso padre --}}
                                                    <div class="custom-control custom-checkbox permission-parent">
                                                        {!! Form::checkbox('permission[]', $parentPermission->id, in_array($parentPermission->id, $rolePermissions) ? true : false, array('class' => 'custom-control-input', 'id' => 'permission-edit-parent-'.$parentPermission->id.'-'.$Roles->id)) !!}
                                                        <label class="custom-control-label" for="permission-edit-parent-{{ $parentPermission->id }}-{{ $Roles->id }}">
                                                            <i class="mdi mdi-eye"></i> {{ $parentPermission->name }}
                                                        </label>
                                                    </div>

                                                    {{-- Permisos hijos --}}
                                                    @if($hasChildren)
                                                        @foreach ($config['children'] as $submodulo => $permisosHijos)
                                                            <div class="permission-group">
                                                                <small class="text-muted d-block mb-2"><strong>{{ $submodulo }}</strong></small>
                                                                @foreach ($permisosHijos as $permisoNombre)
                                                                    @php
                                                                        $childPermission = $Permissions->firstWhere('name', $permisoNombre);
                                                                    @endphp
                                                                    @if($childPermission)
                                                                        <div class="custom-control custom-checkbox permission-child">
                                                                            {!! Form::checkbox('permission[]', $childPermission->id, in_array($childPermission->id, $rolePermissions) ? true : false, array('class' => 'custom-control-input', 'id' => 'permission-edit-'.$childPermission->id.'-'.$Roles->id)) !!}
                                                                            <label class="custom-control-label" for="permission-edit-{{ $childPermission->id }}-{{ $Roles->id }}">{{ $childPermission->name }}</label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach

                                    @if(count($permisosNoAgrupados) > 0)
                                        <div class="card">
                                            <div class="card-header p-2" id="headingEditOtros{{ $Roles->id }}">
                                                <h6 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseEditOtros{{ $Roles->id }}" aria-expanded="false" aria-controls="collapseEditOtros{{ $Roles->id }}">
                                                        <i class="mdi mdi-chevron-down"></i> Otros Permisos
                                                    </button>
                                                </h6>
                                            </div>
                                            <div id="collapseEditOtros{{ $Roles->id }}" class="collapse" aria-labelledby="headingEditOtros{{ $Roles->id }}" data-parent="#permissionsAccordionEdit{{ $Roles->id }}">
                                                <div class="card-body">
                                                    @foreach ($permisosNoAgrupados as $permission)
                                                        <div class="custom-control custom-checkbox">
                                                            {!! Form::checkbox('permission[]', $permission->id, in_array($permission->id, $rolePermissions) ? true : false, array('class' => 'custom-control-input', 'id' => 'permission-edit-'.$permission->id.'-'.$Roles->id)) !!}
                                                            <label class="custom-control-label" for="permission-edit-{{ $permission->id }}-{{ $Roles->id }}">{{ $permission->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" >Guardar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
