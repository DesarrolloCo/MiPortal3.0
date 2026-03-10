@php
    $empleado = Auth::check() ? Auth::user()->empleados : null;
@endphp
<aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        
                        <!-- EXTRANET -->
                        <li class="nav-small-cap">EXTRANET</li>
                        <li>
                            <a href="{{ route('extranet.dashboard') }}" aria-expanded="false">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        @can('sidebar_extranet')
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <i class="mdi mdi-web"></i>
                                <span class="hide-menu">Módulos Extranet</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                @can('ver-comunicados')
                                <li><a href="{{ route('extranet.comunicados.index') }}"><i class="mdi mdi-bullhorn"></i> Comunicados</a></li>
                                @endcan

                                @can('ver-proyectos')
                                <li><a href="{{ route('extranet.proyectos.index') }}"><i class="mdi mdi-clipboard-text"></i> Proyectos</a></li>
                                @endcan

                                @can('ver-eventos')
                                <li><a href="{{ route('extranet.eventos.index') }}"><i class="mdi mdi-calendar-multiple"></i> Eventos</a></li>
                                @endcan

                                @can('ver-reconocimientos')
                                <li><a href="{{ route('extranet.reconocimientos.index') }}"><i class="mdi mdi-trophy-award"></i> Reconocimientos</a></li>
                                @endcan

                                @can('ver-encuestas')
                                <li><a href="{{ route('extranet.encuestas.index') }}"><i class="mdi mdi-poll-box"></i> Encuestas</a></li>
                                @endcan

                                @can('ver-documentos')
                                <li><a href="{{ route('extranet.documentos.index') }}"><i class="mdi mdi-file-document-multiple"></i> Documentos</a></li>
                                @endcan

                                @can('ver-galeria')
                                <li><a href="{{ route('extranet.galeria.index') }}"><i class="mdi mdi-image-multiple"></i> Galería</a></li>
                                @endcan
                            </ul>
                        </li>
                        <li class="nav-devider"></li>
                        @endcan
                        @can('sidebar_administrador')
                        <li class="nav-small-cap">  GESTIÓN</li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu">Administración {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @can('ver-roles')
                                <li><a href="{{ route('Roles.index') }}">Gestionar roles</a></li>
                                @endcan
                                @can('ver-usuarios')
                                <li><a href="{{ route('Users.index') }}">Gestionar usuarios</a></li>
                                @endcan
                                @can('ver-firma-de-certificados')
                                <li><a href="{{ route('Firma.index') }}">Gestionar firma</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('sidebar_recursos_humanos')
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-account-convert"></i><span class="hide-menu">Recursos humanos {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @can('ver-cargo')
                                <li><a href="{{ route('Cargo.index') }}">Gestiones por cargos</a></li>
                                @endcan
                                @can('ver-empleado')
                                <li><a href="{{ route('Empleado.index') }}">Gestionar empleados</a></li>
                                @endcan
                                @can('ver-novedades')
                                <li><a href="{{ route('Novedades.index') }}">Gestionar novedades</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('sidebar_contabilidad')
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-calculator"></i><span class="hide-menu">Contabilidad {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @can('ver-uni_negocio')
                                <li><a href="{{ route('Unidad_Negocio.index') }}">Gestionar unidades de negocio</a></li>
                                @endcan
                                @can('ver-cli')
                                <li><a href="{{ route('Cliente.index') }}">Gestionar clientes</a></li>
                                @endcan
                                @can('ver-uni_cli')
                                <li><a href="{{ route('Uni_cli.index') }}">Relacionar Uni - Cli</a></li>
                                @endcan
                                @can('ver-tipos_novedades')
                                <li><a href="{{ route('TiposNovedades.index') }}">Gestionar tipos de novedades</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('sidebar_operaciones')
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> Operaciones {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @can('ver-jornada')
                                <li><a href="{{ route('Jornada.index') }}">Gestionar jornadas</a></li>
                                @endcan
                                @can('ver-camp')
                                <li><a href="{{ route('Campana.index') }}">Gestionar campañas</a></li>
                                @endcan
                                {{-- <li><a href="{{ route('Contrato.index') }}">Gestionar relación de campañas</a></li> --}}
                            </ul>
                        </li>
                        <li class="nav-devider"></li>
                        @endcan
                        @can('sidebar_agente')
                        <li>
                            <a href="{{ route('Agente.index') }}" aria-expanded="false"><i class="mdi mdi-calendar-today"></i><span class="hide-menu">Mi horario {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        @if(isset($empleado) && $empleado->EMP_ID)
                            <li>
                                <a href="{{ route('Contrato.index', $empleado->EMP_ID) }}" aria-expanded="false">
                                    <i class="mdi mdi-certificate"></i>
                                    <span class="hide-menu">Certificados</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="#" aria-expanded="false">
                                    <i class="mdi mdi-alert"></i>
                                    <span class="hide-menu text-warning">⚠️ Sin empleado asignado</span>
                                </a>
                            </li>

                        <li class="nav-devider"></li>
                        @endif
                        @endcan
                        @can('sidebar_supervisor')
                        <li class="nav-small-cap">  HORARIOS</li>
                        <li>
                            <a href="{{ route('Individual.index') }}" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu">Horario individual {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('Grupal.index') }}" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Horario grupal </span></a>
                        </li> --}}
                        <li>
                            <a href="{{ route('Selectiva.index') }}" aria-expanded="false"><i class="mdi mdi-calendar-range"></i><span class="hide-menu">Horario masivo {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        <li>
                            <a href="#"  rel="tooltip" data-toggle="modal" data-target="#look_for_date"><i class="mdi mdi-calendar-text"></i><span class="hide-menu">Consultar horario {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        @endcan
                        @can('sidebar_mi_inventario')
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">  MI INVENTARIO</li>
                        <li>
                            <a href="{{ route('Equipo.index') }}" aria-expanded="false"><i class="mdi mdi-cellphone-link"></i><span class="hide-menu">Equipos {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        <li>
                            <a href="{{ route('Asignacion_equipo.index') }}" aria-expanded="false"><i class="mdi mdi-account-plus"></i><span class="hide-menu">Asignacion de equipos {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        <li>
                            <a href="{{ route('Mantenimiento.index') }}" aria-expanded="false"><i class="mdi mdi-wrench"></i><span class="hide-menu">Mantenimientos {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-plus-circle-multiple-outline"></i><span class="hide-menu">Adicionales {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{ route('Tipo.index') }}">Tipos de mantenimientos</a></li>
                                <li><a href="{{ route('Hardware.index') }}">Hardwares</a></li>
                                <li><a href="{{ route('Software.index') }}">Softwares</a></li>
                                <li><a href="{{ route('Tecnico.index') }}">Técnicos</a></li>
                                <li><a href="{{ route('Area.index') }}">Áreas</a></li>
                                <li><a href="{{ route('Estado.index') }}">Estados</a></li>
                            </ul>
                        </li>
                        @endcan
                        @can('sidebar_mi_visita')
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">  MI VISITA</li>
                        <li>
                            <a href="{{ route('Visita.index') }}" aria-expanded="false"><i class="fas fa-handshake"></i><span class="hide-menu"> Ingesos {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        @endcan
                        @can('sidebar_mi_reportes')
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">  MI REPORTES</li>
                        <li>
                            <a href="{{ route(
                                'Informe.index') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Informes (Reports) {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                        </li>
                        @endcan
                        @can('sidebar_reportes')
                        <li class="nav-devider"></li>
                        <li class="nav-small-cap">  ANÁLISIS</li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-file-chart"></i><span class="hide-menu">Reportes {{-- <span class="label label-rounded label-success">5</span> --}}</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @can('report_operaciones')
                                <li><a href="{{ route('Reporte.operational') }}">Operativo</a></li>
                                <li><a href="{{ route('Reporte.diary') }}">General por hoy</a></li>
                                <li><a href="{{ route('Reporte.fecha') }}">General por fecha</a></li>
                                <li><a href="{{ route('Reporte.campana') }}">General por campaña</a></li>
                                @endcan
                                @can('report_finanzas')
                                <li><a href="{{ route('Reporte.financiero') }}">Financiero</a></li>
                                @endcan
                            </ul>
                        </li>
                        {{-- <li>
                            <a href="{{ route('Novedades.index') }}" aria-expanded="false"><i class="mdi mdi-message-alert"></i><span class="hide-menu">Novedades
                                <span class="label label-rounded label-success">5</span></span></a>
                        </li> --}}
                        @endcan

                        {{-- <li class="nav-small-cap">FORMS, TABLE &amp; WIDGETS</li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-file"></i><span class="hide-menu">Forms</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="form-basic.html">Basic Forms</a></li>
                                <li><a href="form-layout.html">Form Layouts</a></li>
                                <li><a href="form-addons.html">Form Addons</a></li>
                                <li><a href="form-material.html">Form Material</a></li>
                                <li><a href="form-float-input.html">Floating Lable</a></li>
                                <li><a href="form-pickers.html">Form Pickers</a></li>
                                <li><a href="form-upload.html">File Upload</a></li>
                                <li><a href="form-mask.html">Form Mask</a></li>
                                <li><a href="form-validation.html">Form Validation</a></li>
                                <li><a href="form-bootstrap-validation.html">Form Bootstrap Validation</a></li>
                                <li><a href="form-dropzone.html">File Dropzone</a></li>
                                <li><a href="form-icheck.html">Icheck control</a></li>
                                <li><a href="form-img-cropper.html">Image Cropper</a></li>
                                <li><a href="form-bootstrapwysihtml5.html">HTML5 Editor</a></li>
                                <li><a href="form-typehead.html">Form Typehead</a></li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                                <li><a href="form-xeditable.html">Xeditable Editor</a></li>
                                <li><a href="form-summernote.html">Summernote Editor</a></li>
                                <li><a href="form-tinymce.html">Tinymce Editor</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            {{-- <div class="sidebar-footer">
                <!-- item-->
                <a href="#" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <!-- item-->
                <a href="#" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
                <!-- item-->
                <a href="#" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
            </div> --}}
            <!-- End Bottom points-->
        </aside>
