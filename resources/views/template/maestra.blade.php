@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="es" style="background: #F9F9F9!important;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache"> --}}

    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />


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
    <link href="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if(request()->is('tablero'))
    <!-- chartist CSS -->
    {{-- <link href="/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet"> --}}
    {{-- <link href="/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet"> --}}
    {{-- <link href="/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet"> --}}
    {{-- <link href="/assets/plugins/css-chart/css-chart.css" rel="stylesheet"> --}}
    @endif

    @if(request()->is('cliente'))
    <!-- file upload -->
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet">
    </link>

    @endif

    @if(request()->is('proveedor'))
    <!-- file upload -->
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    <!-- form_wizard_steps -->
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet">
    </link>
    {{-- Select search filter --}}
    <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('recsensorial'))
    <!-- file upload -->
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    <!-- Clock picker plugins css -->
    <link href="/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- form_wizard_steps -->
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet">
    </link>
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style3.css" rel="stylesheet">
    </link>
    <!-- Popup CSS -->
    <link href="/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">

    {{-- Select2 search filter --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Select search filter --}}
    <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif


    @if(request()->is('reconocimientoPsicosocial'))
    <!-- file upload -->
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    <!-- Clock picker plugins css -->
    <link href="/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- form_wizard_steps -->
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet">
    </link>
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style3.css" rel="stylesheet">
    </link>
    <!-- Popup CSS -->
    <link href="/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">


    {{-- Select2 search filter --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Select search filter --}}
    <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('recsensorialquimicoscatalogos'))
    {{-- Select2 search filter --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    {{-- Select search filter --}}
    <link href="/assets/plugins/select-search/selectize.css" rel="stylesheet" type="text/css" />
    @endif

    @if(request()->is('recsensorialcatalogos'))
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    @endif



    @if(request()->is('banco-imagenes'))
    <!-- file upload -->
    <link rel="stylesheet" href="/assets/plugins/dropify/dist/css/dropify.min.css">
    </link>

    @endif

    {{-- @if(request()->is('proyectos')) --}}

    @if(request()->is('proyectos') || request()->is('ejecucion') || request()->is('informes') || request()->is('programa') || request()->is('programaPsicosocial') || request()->is('ejecucionPsicosocial') || request()->is('informesPsicosocial'))
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css' rel='stylesheet' />
    <!-- form_wizard_stps -->
    <link href="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_style.css" rel="stylesheet">
    </link>
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
</head>

<body class="fix-header fix-sidebar card-no-border logo-center">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
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
                        </a>

                    </div>



                    @if (preg_match('/\btablero\b/', request()->path()) || preg_match('/\bcliente\b/', request()->path()) || preg_match('/\bproveedor\b/', request()->path()) || preg_match('/\bclientecatalogo\b/', request()->path()) || preg_match('/\bproveedorcatalogos\b/', request()->path()) || preg_match('/\bbanco-imagenes\b/', request()->path()) || preg_match('/\busuario\b/', request()->path()) || preg_match('/\bbiblioteca\b/', request()->path()))
                    <div class="navbar-nav" style="left: 35%; position: absolute;">

                        <h1 style="color:#ffff;font-weight: bold;">Recursos Administrativos</h1>
                    </div>

                    @endif


                    @if (preg_match('/\bproyectos\b/', request()->path()))
                    <div class="navbar-nav" style="left: 35%; position: absolute;">

                        <h1 style="color:#ffff;font-weight: bold;">Planeación de proyectos</h1>
                    </div>

                    @endif

                    @if (preg_match('/\bprograma\b/', request()->path()) || preg_match('/\brecsensorial\b/', request()->path()) || preg_match('/\bejecucion\b/', request()->path()) || preg_match('/\binformes\b/', request()->path()) || preg_match('/\brecsensorialcatalogos\b/', request()->path()) || preg_match('/\brecsensorialquimicoscatalogos\b/', request()->path()))
                    <div class="navbar-nav" style="left: 38%; position: absolute;">

                        <h1 style="color:#ffff;font-weight: bold;">Higiene Industrial</h1>
                    </div>

                    @endif

                    @if (preg_match('/\bprogramaPsicosocial\b/', request()->path()) || preg_match('/\breconocimientoPsicosocial\b/', request()->path()) || preg_match('/\bejecucionPsicosocial\b/', request()->path()) || preg_match('/\binformesPsicosocial\b/', request()->path()) || preg_match('/\brecpsicocatalogos\b/', request()->path())|| preg_match('/\brecpsicocatalogosrec\b/', request()->path()))
                    <div class="navbar-nav" style="left: 38%; position: absolute;">

                        <h1 style="color:#ffff;font-weight: bold;">Factor de Riesgo Psicosocial</h1>
                    </div>

                    @endif






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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Proyecto','Compras','Almacén', 'Operativo HI','Psicólogo','Ergónomo']))
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
                                                @elseif(auth()->user()->hasRoles(['Coordinador']))
                                                <b href="#" class="btn btn-rounded btn-danger">Coordinador</b>
                                                @elseif(auth()->user()->hasRoles(['Compras']))
                                                <b href="#" class="btn btn-rounded btn-danger">Compras</b>
                                                @elseif(auth()->user()->hasRoles(['Almacén']))
                                                <b href="#" class="btn btn-rounded btn-danger">Almacén</b>
                                                @elseif(auth()->user()->hasRoles(['Operativo HI']))
                                                <b href="#" class="btn btn-rounded btn-danger">Operativo HI</b>
                                                @elseif(auth()->user()->hasRoles(['Ergónomo']))
                                                <b href="#" class="btn btn-rounded btn-danger">Ergónomo</b>
                                                @elseif(auth()->user()->hasRoles(['Psicólogo']))
                                                <b href="#" class="btn btn-rounded btn-danger">Psicólogo</b>
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
                        @if(!auth()->user()->hasRoles(['Externo']))
                        <li> {{-- class="active" --}}
                            <a class="has-arrow" href="/" aria-expanded="false">
                                <i class="fa fa-th"></i><span class="hide-menu">Módulos</span>
                            </a>
                        </li>
                        @endif

                        @if (preg_match('/\btablero\b/', request()->path()) || preg_match('/\bcliente\b/', request()->path()) || preg_match('/\bproveedor\b/', request()->path()) || preg_match('/\bclientecatalogo\b/', request()->path()) || preg_match('/\bproveedorcatalogos\b/', request()->path()) || preg_match('/\bbanco-imagenes\b/', request()->path()) || preg_match('/\busuario\b/', request()->path()) || preg_match('/\bbiblioteca\b/', request()->path()))

                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                        <li> {{-- class="active" --}}
                            <a class="has-arrow" href="/tablero" aria-expanded="false">
                                <i class="mdi mdi-gauge"></i><span class="hide-menu">Tablero</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                        <li>
                            <a class="has-arrow " href="{{route('cliente.index')}}" aria-expanded="false">
                                <i class="mdi mdi-briefcase"></i><span class="hide-menu">Clientes</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI']))
                        <li>
                            <a class="has-arrow " href="{{route('proveedor.index')}}" aria-expanded="false">
                                <i class="mdi mdi-contacts"></i><span class="hide-menu">Proveedores</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                        <li>
                            <a class="has-arrow " href="{{route('biblioteca.index')}}" aria-expanded="false">
                                <i class="fa fa-book"></i><span class="hide-menu">Centro de información</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén']))
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false">
                                <i class="mdi mdi-file-multiple"></i><span class="hide-menu">Catálogos</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                <li><a href="{{ route('clientecatalogo') }}">Catálago de Clientes</a></li>
                                @endif
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén']))
                                <li><a href="{{route('proveedorcatalogos.index')}}">Catálago de Proveedores</a></li>
                                @endif
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                <li><a href="{{ route('banco-imagenes') }}">Catálago de imagenes</a></li>
                                @endif

                            </ul>
                        </li>
                        @endif

                        <li>
                            <a class="has-arrow " href="{{route('usuario.index')}}" aria-expanded="false">
                                <i class="mdi mdi-account"></i>
                                <span class="hide-menu">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Financiero']))
                                    Usuarios
                                    @else
                                    Mi perfil
                                    @endif
                                </span>
                            </a>
                        </li>

                        @endif


                        @if (preg_match('/\bproyectos\b/', request()->path()))

                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                        <li>
                            <a class="has-arrow " href="{{route('proyectos.index')}}" aria-expanded="false">
                                <i class="mdi mdi-format-list-numbers"></i><span class="hide-menu">Proyectos</span>
                            </a>
                        </li>
                        @endif

                        <!-- @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proveedor', 'Reconocimiento']))
                        <li>
                            <a class="has-arrow " href="{{route('seguimiento.index')}}" aria-expanded="false">
                                <i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Programa</span>
                            </a>
                        </li>
                        @endif -->

                        @endif

                        @if (preg_match('/\bprograma\b/', request()->path()) || preg_match('/\brecsensorial\b/', request()->path()) || preg_match('/\bejecucion\b/', request()->path()) || preg_match('/\binformes\b/', request()->path()) || preg_match('/\brecsensorialcatalogos\b/', request()->path()) || preg_match('/\brecsensorialquimicoscatalogos\b/', request()->path()))



                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI']))
                        <li>
                            <a class="has-arrow " href="{{route('programa.index')}}" aria-expanded="false">
                                <i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Programa de trabajo</span>
                            </a>
                        </li>
                        @endif



                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI', 'Psicólogo','Ergónomo']))
                        <li>
                            <a class="has-arrow " href="{{route('recsensorial.index')}}" aria-expanded="false">
                                <i class="mdi mdi-access-point"></i><span class="hide-menu">Reconocimiento</span>
                            </a>
                        </li>
                        @endif


                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI']))
                        <li>
                            <a class="has-arrow " href="{{route('ejecucion.index')}}" aria-expanded="false">
                                <i class="fa fa-cogs"></i><span class="hide-menu">Ejecución </span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo','Ergónomo']))
                        <li>
                            <a class="has-arrow " href="{{route('informes.index')}}" aria-expanded="false">
                                <i class="fa fa-print"></i><span class="hide-menu">Informes y entregables</span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI']))
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false">
                                <i class="mdi mdi-file-multiple"></i><span class="hide-menu">Catálogos</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                <li><a href="{{route('recsensorialcatalogos.index')}}">Módulo Rec. sensorial</a></li>
                                @endif

                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI','Almacén','Compras']))
                                <li><a href="{{route('recsensorialquimicoscatalogos.index')}}">Módulo Rec. químicos</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @endif


                        @if (preg_match('/\bergonomia\b/', request()->path()))


                        @endif
                        <!-- 
                        @if (preg_match('/\bpsicosocial\b/', request()->path()))



                        @endif -->

                        @if (preg_match('/\bprogramaPsicosocial\b/', request()->path()) || preg_match('/\breconocimientoPsicosocial\b/', request()->path()) || preg_match('/\bejecucionPsicosocial\b/', request()->path()) || preg_match('/\binformesPsicosocial\b/', request()->path()) || preg_match('/\brecpsicocatalogos\b/', request()->path())|| preg_match('/\brecpsicocatalogosrec\b/', request()->path()))



                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Psicólogo']))
                        <li>
                            <a class="has-arrow " href="{{route('programaPsicosocial.index')}}" aria-expanded="false">
                                <i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Programa de trabajo</span>
                            </a>
                        </li>
                        @endif



                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Psicólogo']))
                        <li>
                            <a class="has-arrow " href="{{route('reconocimientoPsicosocial.index')}}" aria-expanded="false">
                                <i class="mdi mdi-access-point"></i><span class="hide-menu">Reconocimiento</span>
                            </a>
                        </li>
                        @endif


                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Psicólogo']))
                        <li>
                            <a class="has-arrow " href="{{route('ejecucionPsicosocial.index')}}" aria-expanded="false">
                                <i class="fa fa-cogs"></i><span class="hide-menu">Ejecución </span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                        <li>
                            <a class="has-arrow " href="{{route('informesPsicosocial.index')}}" aria-expanded="false">
                                <i class="fa fa-print"></i><span class="hide-menu">Informes </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false">
                                <i class="mdi mdi-file-multiple"></i><span class="hide-menu">Catálogos</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                <li><a href="{{route('recpsicocatalogos.index')}}">Banco de preguntas</a></li>
                                <li><a href="{{route('recpsicocatalogosrec.index')}}">Módulo Rec. Psicosocial</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @endif

                        @if(auth()->user()->hasRoles(['Externo']))
                        <li>
                            <a class="has-arrow " href="{{route('externo.index')}}" aria-expanded="false">
                                <i class="mdi mdi-format-list-numbers"></i><span class="hide-menu">Proyectos activos</span>
                            </a>
                        </li>
                        @endif





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
            <footer class="footer"> www.results-in-performance.com </footer>
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
        $('.mydatepicker').on('click', function() {
            $(this).datepicker('setDate', $(this).val()); // Mostrar fecha del input y marcar en el calendario
        });
    </script>
    <!-- Form AJAX  -->
    <script src="/js_sitio/jquery.form.js"></script>
    <script src="/js_sitio/maestra.js?v=2.0"></script>


    @if(request()->is('tablero'))
    <!-- chartist chart -->
    {{-- <script src="/assets/plugins/chartist-js/dist/chartist.min.js"></script> --}}
    {{-- <script src="/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script> --}}
    <!-- Vector map JavaScript -->
    {{-- <script src="/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script> --}}
    {{-- <script src="/assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js"></script> --}}
    {{-- <script src="/js/dashboard3.js"></script> --}}

    {{-- Amcharts --}}
    {{-- <link href="/assets/plugins/c3-master/c3.min.css" rel="stylesheet"> --}}
    <script src="/assets/plugins/amChart/amcharts/amcharts.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/serial.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/plugins/responsive/responsive.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/plugins/export/export.js" type="text/javascript"></script>
    <link href="/assets/plugins/amChart/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet" />
    <script src="/assets/plugins/amChart/amcharts/pie.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/themes/light.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/themes/black.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/themes/dark.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/themes/chalk.js" type="text/javascript"></script>
    <script src="/assets/plugins/amChart/amcharts/themes/patterns.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js_sitio/index.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    {{-- datatable --}}
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    @endif

    @if(request()->is('cliente'))
    <!-- jQuery file upload -->
    <script src="/js/jasny-bootstrap.js"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <!-- This is data table -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <!-- Form wizard -->
    <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script.js"></script>
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.15/index.global.min.js"></script>

    {{-- JS pagina --}}
    <script src="/js_sitio/cliente.js?v=9.0"></script>
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
    <script src="/js_sitio/proveedor.js?v=2.0"></script>
    {{-- <script src="/assets/plugins/ViewerJS/"></script> --}}
    @endif

    @if(request()->is('proveedorcatalogos'))
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/js_sitio/proveedor_catalogos.js?v=2.0"></script>
    @endif

    @if(request()->is('banco-imagenes'))
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script src="/js_sitio/catalagoPlantilla.js?v=2.0"></script>
    @endif

    @if(request()->is('biblioteca'))
    <script src="/js_sitio/biblioteca.js"></script>
    <script src="/js/jasny-bootstrap.js"></script>

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
    {{-- Select2 search filter --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Select search filter --}}
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
    <!-- Magnific popup JavaScript -->
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
    {{-- pagina --}}
    <script src="/js_sitio/reconocimiento_sensorial.js?v=17.0"></script>
    @endif

    @if(request()->is('reconocimientoPsicosocial'))
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
    {{-- Select2 search filter --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Select search filter --}}
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
    <!-- Magnific popup JavaScript -->
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
    {{-- pagina --}}
    <script src="/js_sitio/reconocimientoPsico.js?v=3.0"></script>
    @endif



    @if(request()->is('clientecatalogo'))
    {{-- datatable --}}
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <script src="/js_sitio/catalogoclientes.js?v=2.0"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    @endif




    @if(request()->is('recsensorialcatalogos'))
    {{-- datatable --}}

    <script src="/js/jasny-bootstrap.js"></script>

    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <script src="/js_sitio/recsensorial_catalogos.js?v=2.0"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>



    @endif

    @if(request()->is('recsensorialquimicoscatalogos'))
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/js_sitio/recsensorialquimicos_catalogos.js?v=10.0"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    {{-- Select search filter --}}
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
    {{-- Select2 search filter --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- jQuery file upload -->
    <script src="/js/jasny-bootstrap.js"></script>
    @endif


    @if(request()->is('ejecucion'))
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
    <script src="/js_sitio/ejecucionHI.js?v=4.0"></script>

    @endif




    @if(request()->is('programa'))
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
    <script src="/js_sitio/programaTrabajoHI.js?v=2.0"></script>
    @endif

    @if(request()->is('programaPsicosocial'))

    <!-- Form wizard -->
    <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script2.js"></script>
    <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script3.js"></script>
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
    <script src="/js_sitio/programaTrabajoPsico.js?v=5.0"></script>

    @endif


    @if(request()->is('ejecucionPsicosocial'))
    <!-- Form wizard -->
    <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script.js"></script>
    <script src="/assets/plugins/moment/moment.js"></script>
    <script src="/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
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
    <script src="/js_sitio/ejecucionPsico.js?v=3.0"></script>

    @endif

    @if(request()->is('informesPsicosocial'))

    <!-- Form wizard -->
    <script src="/assets/plugins/form_wizard_steps_bootstrap/form_wizard_script.js"></script>
    {{-- datatable --}}
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <!-- jQuery file upload -->
    <script src="/js/jasny-bootstrap.js"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/custom.min.js"></script>
    {{-- pdfobject --}}
    <script src="/assets/plugins/pdfobject/pdfobject.js"></script>
    <!-- Magnific popup JavaScript -->
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
    <script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
    {{-- Select search filter --}}
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>
    {{-- pagina --}}
    <script src="/js_sitio/informesPsico.js?v=2.0"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endif



    @if(request()->is('recpsicocatalogos'))
    {{-- datatable --}}

    <script src="/js/jasny-bootstrap.js"></script>

    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <script src="/js_sitio/recpsico_catalogos.js?v=1.0"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>



    @endif

    @if(request()->is('recpsicocatalogosrec'))
    
    <script src="/js/jasny-bootstrap.js"></script>

    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/dataTables.rowsGroup.js"></script>
    <script src="/js_sitio/psicoInformes_catalogos.js?v=1.0"></script>
    <script src="/assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script src="/assets/plugins/select-search/selectize.js" type="text/javascript"></script>



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
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.15/index.global.min.js"></script>
    {{-- pagina --}}
    <script src="/js_sitio/proyecto.js?v=8.0"></script>
    @endif


    @if(request()->is('informes'))

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
    <script src="/js_sitio/informesHI.js?v=5.0"></script>
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
    <script src="/js_sitio/usuario.js?v=2.0"></script>
    <script src="/js_sitio/usuarioperfil.js?v=2.0"></script>
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
    <script src="/js_sitio/externo.js?v=2.0"></script>
    @endif

    <style>
        .contacto {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            margin-bottom: 10px;
        }
    </style>

    @if(request()->is('seguimiento*'))
    <script src="/assets/plugins/moment/moment.js"></script>
    {{-- <script src="/assets/plugins/moment/src/locale/es.js"></script> --}}
    <script src="/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
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
    <script src="/js_sitio/seguimiento/seguimientoproyecto.js?v=2.0"></script>
    <!--<script src="/assets/extra-libs/prism/prism.js"></script>-->
    @endif
</body>

</html>