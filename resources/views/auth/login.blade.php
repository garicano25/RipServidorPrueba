<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title>Results In Performance</title>
    <!-- Bootstrap Core CSS -->
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
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
    <section id="wrapper">
        {{-- <div class="login-register" style="background-image:url(../assets/images/background/fondo-login.jpg);"> --}}
        <div class="login-register">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" name="form_login" id="form_login" enctype="multipart/form-data" method="post" action="{{ route('login') }}">
                        {!! csrf_field() !!}
                        {{-- {{ Auth::user()}} --}}
                        <h3 class="box-title m-b-20">Acceso</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" placeholder="Correo electrónico" name="email" value="" required>
                            </div>
                            <div class="text-danger" style="text-align: center;">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" placeholder="Contraseña" name="password" value="" required>
                            </div>
                            <div class="text-danger" style="text-align: center;">{{ $errors->first('password') }}</div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" id="boton_entrar">Entrar</button>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <img src="/assets/images/Colorancho.png" alt="" height="100" style="margin: 0px auto;">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    {{-- <script src="/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script> --}}
    {{-- plugins cambio imagen background --}}
    <script src="/assets/plugins/background-backstretch/jquery.backstretch.js"></script>
    <script type="text/javascript">
        $.backstretch(
            [
                "/assets/images/background/fondo-login-original.jpg",
                "/assets/images/background/fondo-login-verde.jpg",
                "/assets/images/background/fondo-login-azul.jpg",
                "/assets/images/background/fondo-login-celeste.jpg"
            ], {
                duration: 2500,
                fade: 2000
            }
        );

        // Load TABLA PROVEEDORES
        $(document).ready(function() {
            $("#boton_entrar").html("Entrar");
        });


        $("#boton_entrar").click(function() {
            var valida = this.form.checkValidity();
            if (valida) {
                $('#boton_entrar').html('Entrar <i class="fa fa-spin fa-spinner"></i>');
            }
        });
    </script>
</body>

</html>