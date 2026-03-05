<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.wrappixel.com/demos/admin-templates/monster-admin/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Feb 2019 11:35:37 GMT -->

<!-- Mirrored from www.wrappixel.com/demos/admin-templates/monster-admin/main/layout-fix-header-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Feb 2019 11:36:58 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('../assets/images/favicon.png') }}">
    <title>MI</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('../assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('../assets/plugins/dropify/dist/css/dropify.min.css') }}">
    <link href="{{ asset('../assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <!-- Calendar CSS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.1/locales-all.global.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    @yield('iframe_responsive')
    
    <!-- ====================== NOTIFICACION ====================== -->
    <!-- toast CSS -->
    <link href="{{ asset('../assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <!-- toast Multiselect -->
    <link href="{{ asset('../assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../assets/plugins/switchery/dist/switchery.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('../assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('../assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('../assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('../assets/plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />

    <!-- Estilos personalizados de las vistas -->
    @yield('styles')

    <!-- ====================== NOTIFICACION ====================== -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar card-no-border">
    
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        @include('layouts.navbar')

        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                @include('Malla.Horarios.Consultar.fecha')
                @yield('main')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            {{-- <footer class="footer  text-center">
                Deco <i class="mdi mdi-heart" style="color: red;"></i> © {{ date('Y') }} All Rights Reserved.
            </footer> --}}
            <footer class="footer  text-center">
                Contacta © {{ date('Y') }} All Rights Reserved.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script data-cfasync="false" src="{{ asset('../../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script><script src="{{ asset('../assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('../assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <!-- ====================== TABLE ====================== -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <!-- ====================== NOTIFICACION ====================== -->
    <script src="{{ asset('../assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <!-- ====================== UPLOAD ====================== -->
    <script src="{{ asset('../assets/plugins/dropify/dist/js/dropify.min.js')  }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('../assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('../assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':button[type=submit]').prop('disabled', true);
            });
        });
        
        jQuery(document).ready(function() {
        // For select 2
        $(".select2").select2();
        $('.selectpicker').selectpicker();
       
        $(".ajax").select2({
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            //templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
    </script>
    
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="{{ asset('../assets/plugins/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('../assets/plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('../assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('../assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}" type="text/javascript"></script>
    <script src="{{ asset('../assets/plugins/dff/dff.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('../assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    
    {{-- NOTIFICACIONES --}}
    @include('layouts.msj')
    @include('layouts.tables')

    {{-- SOLUCIÓN GLOBAL PARA SCROLL Y MODALES --}}
    <style>
        /* IMPORTANTE: Corrección global para scroll bloqueado */
        body {
            overflow-y: auto !important;
            overflow-x: auto !important;
        }

        body.modal-open {
            overflow: hidden !important;
        }

        /* Asegurar que el contenido principal siempre tenga scroll */
        .page-wrapper, .container-fluid {
            overflow-y: auto !important;
        }

        /* Botón de emergencia para desbloquear scroll */
        #global-unlock-scroll-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 11px;
            cursor: pointer;
            display: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        #global-unlock-scroll-btn:hover {
            background: #c82333;
        }

        /* Estilos mejorados para modales */
        .modal {
            overflow-y: auto !important;
        }

        .modal-dialog {
            margin: 1.75rem auto;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            console.log('🔧 Sistema global de corrección de scroll y modales cargado');

            // Función global para desbloquear scroll
            window.globalUnlockScroll = function() {
                console.log('🔓 Desbloqueando scroll globalmente...');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css({
                    'overflow': '',
                    'overflow-y': 'auto',
                    'overflow-x': 'auto',
                    'padding-right': ''
                });
                $('.modal').hide().removeClass('show');
                $('#global-unlock-scroll-btn').hide();
                console.log('✅ Scroll desbloqueado');
            };

            // Función para detectar problemas de scroll
            function detectScrollIssues() {
                const bodyOverflow = $('body').css('overflow');
                const bodyOverflowY = $('body').css('overflow-y');
                const hasModalOpen = $('body').hasClass('modal-open');
                const hasVisibleModal = $('.modal.show:visible').length > 0;
                const scrollHeight = $(document).height();
                const windowHeight = $(window).height();

                // Si hay contenido que debería hacer scroll pero el scroll está bloqueado
                const shouldHaveScroll = scrollHeight > windowHeight;
                const scrollBlocked = (bodyOverflow === 'hidden' || bodyOverflowY === 'hidden') && !hasVisibleModal;

                if (shouldHaveScroll && (scrollBlocked || hasModalOpen && !hasVisibleModal)) {
                    console.warn('⚠️ Problema de scroll detectado');
                    $('#global-unlock-scroll-btn').show();
                    return true;
                } else {
                    $('#global-unlock-scroll-btn').hide();
                    return false;
                }
            }

            // Verificación inicial del scroll
            setTimeout(function() {
                detectScrollIssues();
                // Forzar el scroll como disponible inicialmente
                if (!$('body').hasClass('modal-open')) {
                    $('body').css({
                        'overflow-y': 'auto',
                        'overflow-x': 'auto'
                    });
                }
            }, 1000);

            // Verificar cada 3 segundos
            setInterval(detectScrollIssues, 3000);

            // Limpiar estado al salir de la página
            $(window).on('beforeunload unload pagehide', function() {
                window.globalUnlockScroll();
            });

            // Limpiar con ESC
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    if ($('.modal.show').length === 0) {
                        window.globalUnlockScroll();
                    }
                }
            });

            // Mejorar el manejo de modales existentes
            $(document).on('click', '[data-toggle="modal"], [data-bs-toggle="modal"]', function(e) {
                const targetId = $(this).data('target') || $(this).data('bs-target');
                if (targetId) {
                    const modal = $(targetId);
                    if (modal.length) {
                        e.preventDefault();

                        // Limpiar otros modales primero
                        $('.modal').hide().removeClass('show');
                        $('.modal-backdrop').remove();

                        // Mostrar el modal
                        setTimeout(() => {
                            modal.show().addClass('show');
                            $('body').addClass('modal-open');
                            if ($('.modal-backdrop').length === 0) {
                                $('body').append('<div class="modal-backdrop fade show"></div>');
                            }
                        }, 50);
                    }
                }
            });

            // Mejorar el cierre de modales
            $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .modal-backdrop', function(e) {
                if (e.target === this || $(this).is('[data-dismiss="modal"], [data-bs-dismiss="modal"]')) {
                    $('.modal').hide().removeClass('show');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('overflow', '');
                }
            });

            // Prevenir que el contenido del modal cierre el modal
            $(document).on('click', '.modal-content', function(e) {
                e.stopPropagation();
            });

            console.log('✅ Sistema de corrección global inicializado');
        });
    </script>

    <!-- Botón de emergencia global -->
    <button id="global-unlock-scroll-btn" onclick="window.globalUnlockScroll()" title="Desbloquear scroll">
        🔓 Scroll
    </button>

    {{-- SCRIPTS PERSONALIZADOS DE LAS VISTAS --}}
    @yield('scripts')

    <!--Sidebar State Management - Debe ir al final -->
    <script src="{{ asset('js/sidebar-state.js') }}"></script>
</body>


<!-- Mirrored from www.wrappixel.com/demos/admin-templates/monster-admin/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Feb 2019 11:35:42 GMT -->

<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               MI PORTAL                                 * *-->
<!-- * *                TI - Sergio Bravo, Denilson Castro                       * *-->
<!-- * *            DE - Jose Lugo, Jaison Neira, Jcoob Charris                  * *-->
<!-- * *   Hecho con amor, dedicacion y mucho compromiso desde el area de TI&DE  * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->

</html>
