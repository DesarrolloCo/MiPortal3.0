<!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="{{ asset('assets/images/logo-light-icon.png') }}" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                         <!-- dark Logo text -->
                         <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                         {{-- <label for="" alt="homepage" class="dark-logo font-weight-bold">MI - CONTACTA</label> --}}
                         <!-- Light Logo text -->
                         <img src="{{ asset('assets/images/logo-light-text.png') }}" class="light-logo" alt="homepage" /></span></a>
                         {{-- <label for="" alt="homepage" class="light-logo font-weight-bold">MI - CONTACTA</label> --}}
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left"></i></a> </li>

                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        {{-- <li class="nav-item hidden-sm-down">
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search for..."> <a class="srh-btn"><i class="ti-search"></i></a> </form>
                        </li> --}}

                        <!-- Notificaciones -->
                        @if(Auth::check() && Auth::user()->empleados)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" id="notificacionesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
                                <i class="mdi mdi-bell-outline" style="font-size: 20px;"></i>
                                <span class="badge badge-danger " id="notificaciones-count">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY" aria-labelledby="notificacionesDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-bell"></i> Notificaciones
                                        <span class="badge badge-primary ml-2" id="total-notificaciones">0</span>
                                    </h6>
                                    <a href="{{ route('extranet.notificaciones.index') }}" class="text-muted">
                                        <small>Ver todas</small>
                                    </a>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div id="notificaciones-list" class="px-2">
                                    <!-- Las notificaciones se cargarán dinámicamente -->
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="px-2 pb-2">
                                    <form action="{{ route('extranet.notificaciones.marcar-todas-leidas') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary btn-block">
                                            <i class="mdi mdi-check-all"></i> Marcar todas como leídas
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('assets/images/users/sin_foto.jpg') }}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img src="{{ asset('assets/images/users/sin_foto.jpg') }}" alt="user"></div>
                                            <div class="u-text">
                                                <h4>{{ Auth::check() ? Auth::user()->name : 'Usuario' }}</h4>
                                                <br>
                                                <a href="{{ route('Agente.perfil')}}" class="btn btn-rounded btn-danger btn-sm">Ver perfil</a></div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    {{-- <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li> --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off"></i> {{ __('Cerrar sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

        @if(Auth::check() && Auth::user()->empleados)
        <script>
        // Función para calcular tiempo transcurrido
        function calcularTiempoTranscurrido(fecha) {
            const ahora = new Date();
            const fechaNotif = new Date(fecha);
            const diffMs = ahora - fechaNotif;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHoras = Math.floor(diffMins / 60);
            const diffDias = Math.floor(diffHoras / 24);

            if (diffMins < 1) return 'menos de 1 minuto';
            if (diffMins < 60) return `${diffMins} minuto${diffMins !== 1 ? 's' : ''}`;
            if (diffHoras < 24) return `${diffHoras} hora${diffHoras !== 1 ? 's' : ''}`;
            return `${diffDias} día${diffDias !== 1 ? 's' : ''}`;
        }

        $(document).ready(function() {
            // Función para cargar notificaciones
            function cargarNotificaciones() {
                $.ajax({
                    url: '{{ route("extranet.notificaciones.no-leidas") }}',
                    type: 'GET',
                    success: function(response) {
                        const count = response.count || 0;

                        // Actualizar contador
                        if (count > 0) {
                            $('#notificaciones-count').text(count > 99 ? '99+' : count).show();
                        } else {
                            $('#notificaciones-count').hide();
                        }

                        $('#total-notificaciones').text(count);
                    },
                    error: function() {
                        console.log('Error al cargar contador de notificaciones');
                    }
                });
            }

            // Función para cargar lista de notificaciones recientes
            function cargarListaNotificaciones() {
                $.ajax({
                    url: '{{ route("extranet.notificaciones.recientes") }}',
                    type: 'GET',
                    success: function(response) {
                        let html = '';

                        const notificaciones = response.notificaciones;

                        if (notificaciones && notificaciones.length > 0) {
                            notificaciones.forEach(function(notif) {
                                const iconClass = 'mdi mdi-bell';
                                const colorClass = 'text-muted';

                                // Determinar la URL de destino
                                let targetUrl = '#';
                                if (notif.datos_adicionales && notif.datos_adicionales.action_url) {
                                    targetUrl = notif.datos_adicionales.action_url;
                                }
                                html += `
                                    <a href="${targetUrl}" class="dropdown-item d-flex align-items-start py-2 ${!notif.leida ? 'bg-light' : ''}" ${targetUrl !== '#' ? 'data-notificacion-id="' + notif.id + '"' : ''}>
                                        <div class="mr-3">
                                            <i class="${iconClass} ${colorClass}" style="font-size: 18px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="font-weight-medium text-truncate" style="max-width: 250px;">
                                                ${notif.titulo}
                                            </div>
                                            <small class="text-muted">
                                                Hace ${calcularTiempoTranscurrido(notif.created_at)}
                                            </small>
                                        </div>
                                        ${!notif.leida ? '<div class="ml-2"><span class="badge badge-primary badge-sm">Nueva</span></div>' : ''}
                                    </a>
                                `;
                            });
                        } else {
                            html = `
                                <div class="text-center py-3">
                                    <i class="mdi mdi-bell-off-outline text-muted" style="font-size: 24px;"></i>
                                    <p class="text-muted mb-0 mt-1">No hay notificaciones</p>
                                </div>
                            `;
                        }

                        $('#notificaciones-list').html(html);
                    },
                    error: function() {
                        $('#notificaciones-list').html(`
                            <div class="text-center py-3">
                                <i class="mdi mdi-alert-circle text-warning" style="font-size: 24px;"></i>
                                <p class="text-muted mb-0 mt-1">Error al cargar notificaciones</p>
                            </div>
                        `);
                    }
                });
            }

            // Cargar datos iniciales
            cargarNotificaciones();
            cargarListaNotificaciones();

            // Actualizar contador cada 30 segundos
            setInterval(cargarNotificaciones, 30000);

            // Marcar notificación como leída al hacer clic
            $(document).on('click', 'a[data-notificacion-id]', function(e) {
                const notificacionId = $(this).data('notificacion-id');
                if (notificacionId) {
                    $.ajax({
                        url: '{{ route("extranet.notificaciones.marcar-leida", ":id") }}'.replace(':id', notificacionId),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            // Recargar notificaciones después de marcar como leída
                            cargarNotificaciones();
                        }
                    });
                }
            });

            // Cargar lista completa cuando se abre el dropdown
            $('#notificacionesDropdown').on('shown.bs.dropdown', function() {
                cargarListaNotificaciones();
            });
        });
        </script>

        <style>
        /* Estilos para notificaciones en navbar */
        .badge-counter {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            line-height: 1;
            text-align: center;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa !important;
        }

        .dropdown-item .badge {
            font-size: 9px;
        }

        /* Animación para el contador */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .badge-counter.new-notification {
            animation: pulse 0.5s ease-in-out;
        }

        /* Estilos para el dropdown de notificaciones */
        .dropdown-menu-notifications {
            width: 350px !important;
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            border-bottom: 1px solid #f0f0f0;
            padding: 8px 12px;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item.unread {
            background-color: #f8f9ff;
        }

        .notification-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        </style>
        @endif
