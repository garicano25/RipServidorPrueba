<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9!important;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title>Results In Performance</title>
    <!-- Bootstrap Core CSS -->
    {{-- <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="/css/colors/color_RIP.css" id="theme" rel="stylesheet">
    <!--alerts CSS -->
    <link href="/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <!-- Date picker plugins css -->
    {{-- <link href="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" /> --}}
    <link href="/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if(request()->is('/*'))
        <!-- chartist CSS -->
        <link href="/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
        <link href="/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
        {{-- <link href="/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet"> --}}
        <link href="/assets/plugins/css-chart/css-chart.css" rel="stylesheet">
    @endif

    @if(request()->is('proveedor'))
        <!-- file upload -->
        <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
        <!-- form_wizard_steps -->
        <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet"></link>
        {{-- Select search filter --}}
        <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('recsensorial'))
        <!-- file upload -->
        <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
        <!-- Clock picker plugins css -->
        <link href="/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
        <!-- form_wizard_steps -->
        <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet"></link>
        <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style3.css" rel="stylesheet"></link>
        {{-- Select search filter --}}
        <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('recsensorialcatalogos'))
    @endif

    @if(request()->is('proyectos'))
        <!-- form_wizard_steps -->
        <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet"></link>
        <!-- file upload -->
        <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
        <!-- Clock picker plugins css -->
        <link href="/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
        <!-- Popup CSS -->
        <link href="/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">
        {{-- Select search filter --}}
        <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('usuario'))
        <!-- file upload -->
        <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    @endif

    @if(request()->is('externo'))
        {{-- datatable --}}
        {{-- <link href="/assets/plugins/datatables/jquery.dataTables.min2.css" rel="stylesheet"></link> --}}
        <!-- file upload -->
        <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
        <!-- Popup CSS -->
        <link href="/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">
    @endif
    <style type="text/css">
        .dtp-picker {
     padding: none!important; 
    text-align: center;
}
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border logo-center">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <!-- <header class="topbar" style="background: #94B732;"> -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light" style="border: 0px #F00 solid; min-width: 100%;">
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <!-- Logo icon -->
                            <b>
                                <img src="/assets/images/logo.png" alt="homepage" class="dark-logo" />
                                <img src="/assets/images/logo-light.png" class="light-logo" alt="homepage" />
                            </b>
                            <!--End Logo icon -->
                            <!-- Logo text -->
                            {{-- <span>
                                <img src="/assets/images/logo.png" alt="homepage" class="dark-logo" />
                                <img src="/assets/images/logo-light.png" class="light-logo" alt="homepage" />
                            </span> --}}
                         </a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item" style="border: 0px #F00 solid; position: absolute; left: 5px; top: 55px;">
                            <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu" style="color: #000000;"></i></a>
                        </li>
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor', 'Proyecto']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-message"></i>
                                <div class="notify" id="notificaciones_activas">
                                    {{-- <span class="heartbit"></span>
                                    <span class="point"></span> --}}
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox scale-up" style="width: 420px!important;">
                                <ul>
                                    <li>
                                        <div class="drop-title" id="notificaciones_titulo">Notificaciones de vigencias (0)</div>
                                    </li>
                                    <li>
                                        <div class="message-center" id="div_notificaciones"></div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="#">&nbsp;</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- <img src="/assets/images/users/1.jpg" alt="user" class="profile-pic" /> --}}
                                <img src="{{route('usuariofoto', auth()->user()->id)}}" alt="user" class="profile-pic" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user" style="border: 0px #F00 solid; width: 460px;">
                                    <li>
                                        <div class="dw-user-box">
                                            {{-- <div class="u-img"><img src="/assets/images/users/1.jpg" alt="user" class="user_photo"></div> --}}
                                            <div class="u-img"><img src="{{route('usuariofoto', auth()->user()->id)}}" class="img-fluid" alt="user"></div>
                                            <div class="u-text">
                                                <h4>{{auth()->user()->name}}</h4>
                                                <p class="text-muted">{{auth()->user()->email}}</p>
                                                @if(auth()->user()->hasRoles(['Superusuario']))
                                                    <b href="#" class="btn btn-rounded btn-danger">Superusuario</b>
                                                @elseif(auth()->user()->hasRoles(['Administrador']))
                                                    <b href="#" class="btn btn-rounded btn-danger">Administrador</b>
                                                @elseif(auth()->user()->hasRoles(['Externo']))
                                                    <b href="#" class="btn btn-rounded btn-info">Proveedor</b>
                                                @else
                                                    <b href="#" class="btn btn-rounded btn-info">Empleado</b>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{route('usuario.index')}}"><i class="ti-user"></i> Mi perfil</a></li>
                                    <li role="separator" class="divider"></li>
                                    {{-- <li><a href="{{ route('logout') }}" style="color: #F00!important;"><i class="fa fa-power-off"></i> Cerrar sesión</a></li> --}}
                                    <li><a href="#" id="boton_cerrarsesion" style="color: #F00!important;"><i class="fa fa-power-off"></i> Cerrar sesión</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" style="border: 0px #f00 solid; min-width: 100% !important;">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">WEB</li>
                        <li>   {{-- class="active" --}}
                            <a class="has-arrow" href="/" aria-expanded="false">
                                <i class="mdi mdi-gauge"></i><span class="hide-menu">Tablero</span>
                            </a>
                        </li>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor', 'Compras', 'Staff', 'CoordinadorHI']))
                        <li>
                            <a class="has-arrow " href="{{route('proveedor.index')}}" aria-expanded="false">
                                <i class="mdi mdi-contacts"></i><span class="hide-menu">Proveedores</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Reconocimiento', 'Psicólogo', 'Ergónomo', 'CoordinadorPsicosocial', 'CoordinadorErgonómico', 'CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM', 'CoordinadorHI']))
                        <li>
                            <a class="has-arrow " href="{{route('recsensorial.index')}}" aria-expanded="false">
                                <i class="mdi mdi-access-point"></i><span class="hide-menu">Rec. Sensorial</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Compras', 'Staff', 'Psicólogo', 'Ergónomo', 'CoordinadorPsicosocial', 'CoordinadorErgonómico', 'CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM', 'CoordinadorHI', 'ApoyoTecnico', 'Reportes']))
                        <li>
                            <a class="has-arrow " href="{{route('proyectos.index')}}" aria-expanded="false">
                                <i class="mdi mdi-format-list-numbers"></i><span class="hide-menu">Proyectos</span>
                            </a>
                        </li>
                        <li>
                            <a class="has-arrow " href="{{route('seguimiento.index')}}" aria-expanded="false">
                                <i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Programa</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Externo']))
                        <li>
                            <a class="has-arrow " href="{{route('externo.index')}}" aria-expanded="false">
                                <i class="mdi mdi-format-list-numbers"></i><span class="hide-menu">Proyectos activos</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor', 'Reconocimiento']))
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false">
                                <i class="mdi mdi-file-multiple"></i><span class="hide-menu">Catálogos</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor']))
                                    <li><a href="{{route('proveedorcatalogos.index')}}">Módulo Proveedores</a></li>
                                @endif
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Reconocimiento']))
                                    <li><a href="{{route('recsensorialcatalogos.index')}}">Módulo Rec. sensorial</a></li>
                                    <li><a href="{{route('recsensorialquimicoscatalogos.index')}}">Módulo Rec. químicos</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li>
                            <a class="has-arrow " href="{{route('usuario.index')}}" aria-expanded="false">
                                <i class="mdi mdi-account"></i>
                                <span class="hide-menu">
                                    @if(auth()->user()->hasRoles(['Superusuario']))
                                        Usuarios
                                    @else
                                        Mi perfil
                                    @endif
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        {{-- CONTENIDO --}}
        <!-- ============================================================== -->
        <!-- COLOR_FONDO_MODIFICADO -->{{-- #F9F9F9 --}}
        <div class="page-wrapper" style="background: #F9F9F9!important;"> 
            <div class="container-fluid" style="min-width: 100% !important;">
                <div id="contenido_pag">
                    @yield('contenido')
                </div>
            </div>
            <footer class="footer" style="z-index: 10000000"> www.results-in-performance.com </footer>
            </div>
        </div>
        {{-- /CONTENIDO --}}
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    {{-- <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script> --}}
    <script src="/assets/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--stickey kit -->
    <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="/js/custom.min.js"></script>
    <!-- Style switcher -->
    {{-- <script src="/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script> --}}
    <!-- Sweet-Alert  -->
    <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        // Inicializar campos datepicker
        jQuery('.mydatepicker').datepicker({
            format: 'yyyy-mm-dd', //'dd-mm-yyyy'
            weekStart: 1, //dia que inicia la semana, 1 = Lunes
            // startDate: new Date('11/17/2020'), // deshabilitar dias anteriores con fecha
            // startDate: '-3d', // deshabilitar dias anteriores del dia actual
            // endDate: '+3d', //deshabilitar dias despues del dia actual
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true, //Dia de hoy marcado en el calendario
            toggleActive: true,
            // setDate: new Date('11/17/2020'), // "2020/11/25", //Fecha marcada en el caledario
            forceParse: false, //mantiene la fecha del input si no se selecciona otra
            showOnFocus: true
        });

        // Si selecciona un campo tipo datepicker
        $('.mydatepicker').on('click', function()
        {
            $(this).datepicker('setDate', $(this).val());// Mostrar fecha del input y marcar en el calendario
        });
    </script>
    <!-- Form AJAX  -->
    <script src="/js_sitio/jquery.form.js"></script>
    <script src="/js_sitio/maestra.js"></script>
    
    @if(request()->is('/*'))
        <!-- chartist chart -->
        <script src="/assets/plugins/chartist-js/dist/chartist.min.js"></script>
        <script src="/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
        <!-- Vector map JavaScript -->
        <script src="/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="/assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js"></script>
        <script src="/js/dashboard3.js"></script>
    @endif

    @if(request()->is('proveedor'))
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        <!-- This is data table -->
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <!-- Form wizard -->
        <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script.js"></script>
        {{-- Select search filter --}}
        <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
        {{-- JS pagina --}}
        <script src="/js_sitio/proveedor.js"></script>
        {{-- <script src="/assets/plugins/ViewerJS/"></script> --}}
    @endif

    @if(request()->is('proveedorcatalogos'))
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/js_sitio/proveedor_catalogos.js"></script>
    @endif

    @if(request()->is('recsensorial'))
        <!-- form_wizard_steps -->
        <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script2.js"></script>
        <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script3.js"></script>
        {{-- datatable --}}
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="js/custom.min.js"></script>
        {{-- Select search filter --}}
        <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
        {{-- pagina --}}
        <script src="/js_sitio/reconocimiento_sensorial.js"></script>
    @endif

    @if(request()->is('recsensorialcatalogos'))
        {{-- datatable --}}
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <script src="/js_sitio/recsensorial_catalogos.js"></script>
    @endif

    @if(request()->is('recsensorialquimicoscatalogos'))
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/js_sitio/recsensorialquimicos_catalogos.js"></script>
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
    @endif

    @if(request()->is('proyectos'))
        <!-- Form wizard -->
        <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script.js"></script>
        {{-- datatable --}}
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        {{-- pdfobject --}}
        <script src="/assets/plugins/pdfobject/pdfobject.js"></script>
        <!-- Magnific popup JavaScript -->
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
        {{-- Select search filter --}}
        <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
        {{-- pagina --}}
        <script src="/js_sitio/proyecto.js"></script>
    @endif

    @if(request()->is('usuario'))
        {{-- datatable --}}
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="js/custom.min.js"></script>
        {{-- pagina --}}
        <script src="/js_sitio/usuario.js"></script>
        <script src="/js_sitio/usuarioperfil.js"></script>
    @endif

    @if(request()->is('externo'))
        {{-- datatable --}}
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        <!-- jQuery file upload -->
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        <!-- Magnific popup JavaScript -->
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
        {{-- pagina --}}
        <script src="/js_sitio/externo.js"></script>
    @endif
    @if(request()->is('seguimiento*'))
        <script src="/assets/plugins/moment/moment.js"></script>
        <script src="/assets/plugins/moment/src/locale/es.js"></script>
        <script src="/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        
        <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
        
        <script src="/js/jasny-bootstrap.js"></script>
        <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
        
        <script src="/assets/plugins/pdfobject/pdfobject.js"></script>
        
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
        <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
        
        <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
        
        <script src="/js_sitio/seguimiento/seguimientoproyecto.js"></script>
        <script src="/assets/extra-libs/prism/prism.js"></script>
    @endif

</body>
</html>
