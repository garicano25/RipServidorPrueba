<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results In Performance</title>

    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <!-- =================== Style ============================ -->

    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <link href="/assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/modulos.css" rel="stylesheet">
    <!--Animación -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/loadingio/loading.css@v2.0.0/dist/loading.css​">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/loadingio/loading.css@v2.0.0/dist/loading.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/loadingio/transition.css@v2.0.0/dist/transition.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/loadingio/transition.css@v2.0.0/dist/transition.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/loadingio/ldcover/dist/index.min.css">
    <!--alerts CSS -->
    <link href="/assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">



</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Mitad 1  -->
            <div class="col-6" style="background-color: #3f6a98;">
                <!-- Titulo de Sistema -->
                <div class="row p-3 ml-4 mt-2 ">
                    <h5 style=" color: #ffffff;">SEHILAB <sub>©</sub></h5>
                </div>
                <!-- Recursos Administrativos -->
                <div class="row">
                    <div class="col-6">
                        <div class="ld ld-wander-h" style="animation-duration:5.0s;">
                            <img src=" /assets/images/modulos/Group 27.png" style="width: 250px;margin-top: 40px;margin-left: 30px;">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-end mr-5">


                            @if(!auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                            <a class="unauthorized" href="#">

                                @else
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                                <a class="cta" href="/tablero">

                                    @elseif(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                                    <a class="cta" href="{{route('cliente.index')}}">

                                        @elseif(auth()->user()->hasRoles(['Psicólogo','Ergónomo']))
                                        <a class="cta" href="{{route('cliente.index')}}">
                                            @endif
                                            @endif

                                            <div class="row circle-one">
                                                <div class="col-12 circle-two">
                                                    <img src="/assets/images/modulos/recursos.png" class="logos" alt="Modulos de Recursos Administrativos">
                                                </div>
                                                <span class="titulos" style="justify-content: center; margin-top:20px; text-align:center">Recursos Administrativos</span>
                                            </div>
                                        </a>
                        </div>
                    </div>
                </div>

                <!-- Higiene Industrial -->
                <div class="row">
                    <div class="col-3 mx-5" style="transform: rotate(-90deg); color: #ffff; font-size:15px">
                        <span>Results In Performance</span>
                    </div>
                    <div class="col-7">
                        <div class="d-flex justify-content-start mr-5">

                            @if(!auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                            <a class="unauthorized" href="#">

                                @else
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI']))
                                <a class="cta" href="{{route('programa.index')}}">

                                    @elseif(auth()->user()->hasRoles(['Psicólogo','Ergónomo']))
                                    <a class="cta" href="{{route('recsensorial.index')}}">

                                        @endif
                                        @endif
                                        <div class="row circle-one">
                                            <div class="col-12 circle-two">
                                                <img src="/assets/images/modulos/higiene.png" class="logos" alt="Modulos de Higiene Industrial">
                                            </div>
                                            <span class="titulos" style="justify-content: center; margin-top:20px">Higiene Industrial</span>

                                        </div>
                                    </a>
                        </div>
                    </div>
                </div>
                <!-- Factor psicosial -->
                <div class="row mb-4">
                    <div class="col-4" style="justify-content: start; display:flex; align-items:center">
                        <h1 class="ld ld-breath" style="animation-duration:3.0s; font-size: 180px; color: #6c92ba; margin:40px">S</h1>
                    </div>
                    <div class="col-8">
                        <div class="d-flex justify-content-end mr-5 mb-4">

                            @if(!auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                            <a class="unauthorized" href="#">

                                @else
                                @if(auth()->user()->hasRoles(['Compras','Almacén','Operativo HI', 'Coordinador', 'Ergónomo']))
                                <a class="unauthorized" href="#">

                                    @elseif(auth()->user()->hasRoles(['Psicólogo','Superusuario', 'Administrador']))
                                    <a class="cta" href="{{route('programaPsicosocial.index')}}">

                                        @endif
                                        @endif

                                        <div class="row circle-one">
                                            <div class="col-12 circle-two">
                                                <img src="/assets/images/modulos/psicosocial.png" class="logos" alt="Modulos de Factor Piscosocial">
                                            </div>
                                            <span class="titulos" style="justify-content: center; margin-top:20px">Factor Psicosocial</span>

                                        </div>
                                    </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mitad 2 -->
            <div class="col-6" style="background-color: #6c92ba;">
                <!-- Nombre del usuario -->
                <div class="row p-3 d-flex align-items-center w-100" style="justify-content:end">
                    <a href="#" id="user-menu-toggle">
                        <img src="{{ route('usuariofoto', auth()->user()->id) }}" class="img-fluid mb-1 user" alt="user">
                    </a>
                    <h5 id="typing-text" class="me-3 ml-3 mr-5" style="color: #ffffff;"> Bienvenido(a) {{ Auth::user()->name }}</h5>
                </div>
                <!-- Planeacion -->
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex justify-content-start ml-5">

                            @if(!auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                            <a class="unauthorized" href="#">

                                @else
                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                                <a class="cta" href="{{route('proyectos.index')}}">

                                    @elseif(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI','Psicólogo','Ergónomo']))
                                    <a class="cta" href="{{route('proyectos.index')}}">

                                        @endif
                                        @endif

                                        <div class="row circle-one">
                                            <div class="col-12 circle-two">
                                                <img src="/assets/images/modulos/planeacion.png" class="logos" alt="Modulos de Planeacion">
                                            </div>
                                            <span class="titulos" style="justify-content: center;  margin-top:20px">Planeación</span>
                                        </div>
                                    </a>
                        </div>
                    </div>
                    <div class="6">
                        <div class="ld ld-wander-h" style="animation-duration:5.0s;">
                            <img src="/assets/images/modulos/Group 28.png" style="width: 250px;margin-top: 40px;margin-left: 100px;">
                        </div>
                    </div>
                </div>

                <!-- Factor ergonomico -->
                <div class="d-flex justify-content-center ml-5">
                    <a id="btnErgo" class="cta disabled" href="#">
                        <div class="row circle-one">
                            <div class="col-12 circle-two">
                                <img src="/assets/images/modulos/ergonomia.png" class="logos" alt="Modulos de Factor Ergonomico">
                            </div>
                            <span class="titulos" style="justify-content: center; margin-top:20px">Factor Ergonómico</span>
                        </div>
                    </a>
                </div>
                <!-- Seguridad Industrial -->
                <div class="row mb-4">
                    <div class="col-8">
                        <div class="d-flex justify-content-start ml-5 mb-4">
                            <a id="btnSeguridad" class="cta disabled" href="#">
                                <div class="row circle-one">
                                    <div class="col-12 circle-two">
                                        <img src="/assets/images/modulos/seguridad.png" class="logos" alt="Modulos de Seguridad Industrial">
                                    </div>
                                    <span class="titulos" style="justify-content: center; margin-top:20px">Seguridad Industrial</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-4 ld ld-breath" style="animation-duration:3.0s; justify-content: end; display:flex; align-items:center">
                        <h1 style="font-size: 180px; color: #3f6a98; margin:40px">H</h1>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="box-logo ld ld-breath" style="animation-duration:2.0s">
                <img class="logo" src="/assets/images/modulos/logo.png" alt="Logo SEHILAB">
            </div>

            <div class="row justify-content-center p-1 " style="background-color: #adca69; width:101%">
                <span style="color: #3f6a98;">results-in-performance.com</span>
            </div>
        </div>
    </div>

    <!-- =================== Scripts ============================ -->
    <!-- Bootstrap -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- All JQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Animación -->
    <script src="https://cdn.jsdelivr.net/gh/loadingio/ldcover/dist/index.min.js"></script>
    <!-- Custom JS -->
    <script src="/js_sitio/modulos.js"></script>
    <!-- Sweet-Alert  -->
    <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>

</body>

</html>