<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
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
    <style>
        @page {
            margin: 0cm 0cm;
            /*font-family: "Chiller"!important;*/
        }

		@font-face {
			font-family: 'Helvetica';
			font-weight: normal;
			font-style: normal;
			font-variant: normal;
			src: url("font url");
		}
 
        body {
            margin: 3.6cm 1.5cm 2cm 1.5cm;
            font-family: Helvetica, sans-serif;
            /*font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"!important;*/
            font-size: 12px;
            line-height: 12px;
            color: #000000;
        }

        /*Encabezado de la pagina*/
		header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.5cm;
            /*background-color: #DDDDDD;*/
            text-align: center;
            line-height: 18px;
            padding: 1.5cm 1.5cm 0cm 1.5cm;
            font-size: 12px;
        }
 
 		/*Pie de pagina*/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: .3cm;
            /*background-color: #DDDDDD;*/
            text-align: center;
            line-height: 18px;
            padding: 0cm 1.5cm 1.5cm 1.5cm;
            font-size: 12px;
        }

        footer hr{
        	margin: 0px;
        	padding: 0px;
        	color: #F5F5F5;
        }

        /*DIV Salto de pagina*/
        .page-break {
		    page-break-after: always;
		}

		/*----------------------------------*/

        #tabla_encabezado{
        	width: 100%;
        	padding: 0px;
        	margin: 0px;
        	font-family: inherit!important;
        	font-size: 12px;
        	color: #000000;
        	text-align: center;
        	vertical-align: middle;
        }

        /*#tabla_encabezado #logo{
            background-image: url("/assets/images/logo_results.jpg");
            background-color: #FFF;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 2px 12px;
        }*/

        #tabla_encabezado td{
        	height: 30px;
        	border: 1px #000000 solid;
        	/*border-spacing: 1px;*/
        	/*border-radius: 4px;*/
        	vertical-align: middle;
        	color: #000000;
        	padding: 1px;
        }

        /*----------------------------------*/
        
        hr{
        	width: 100%;
        	padding: 0px;
        	margin: 12px 0px;
        }

        h1,h2,h3{
        	line-height: 16px;
        	padding: 0px;
        	margin: 0px;
        }

        h1{
        	font-size: 18px;
        	font-weight: 900px;
        }

        h2{
        	font-size: 14px;
        	font-weight: 600px;
        }

        /*----------------------------------*/

        #tabla_datos{
        	width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 12px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_datos th{
            width: 16.6666%!important;
        	height: 20px;
        	font-weight: bold!important;
        	color: #000000;
        	text-align: center;
        	border: 1px #000000 solid;
        	background: #E8E8E8;
        	padding: 1px 2px;
        	vertical-align: middle;
        }

        #tabla_datos td{
            width: 16.6666%!important;
        	height: 20px;
        	font-weight: normal;
        	color: #333333;
        	text-align: left;
        	background: none;
        	padding: 1px 2px;
        	vertical-align: middle;
        	border: 1px #333333 solid;
        }

        /*----------------------------------*/

        #tabla_listaservicios{
            width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 12px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_listaservicios th{
            height: 20px;
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_listaservicios td{
            height: 20px;
            font-weight: normal;
            color: #333333;
            text-align: center;
            background: none;
            padding: 1px;
            vertical-align: middle;
            border: 1px #333333 solid;
        }

        /*----------------------------------*/

        #tabla_observacion{
            width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 12px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_observacion th{
            width: 100%!important;
            height: 20px;
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_observacion td{
            width: 100%!important;
            height: 20px;
            font-weight: normal;
            color: #333333;
            text-align: justify;
            background: none;
            padding: 1px 2px;
            vertical-align: middle;
            border: 1px #333333 solid;
        }

        /*----------------------------------*/

         #firma{
            width: 240px;
            margin: 10px auto 0px auto; 
            text-align: center;
            line-height: 14px;
            /*border: 1px #F00 solid;*/
        }

        #firma p{
            width: 200px;
            margin: 0px auto; 
            text-align: center;
            line-height: 14px;
        }

    </style>
</head>
<body>
    {{-- ENCABEZADO Y PIE DE PÁGINA --}}
	<header>
	    <table width="100%" cellspacing="0" cellpadding="0" id="tabla_encabezado">
            <tbody>
                <tr>
                    <td rowspan="2" width="20%"><img src="{{ Request::root().'/assets/images/logo_results.jpg' }}" height="46" width="130" alt=""></td>
                    <td rowspan="2" width="60%">
                        <h1>Lista de equipos</h1>

                        @if($equiposlista['proyectoequipo_revision'] > 0)
                            (Rev-{{ $equiposlista['proyectoequipo_revision'] }})
                        @endif

                        @if($equiposlista['proyectoequipo_cancelado'] > 0)
                            <h1 style="color: #F00;">(CANCELADO)</h1>
                        @endif
                    </td>
                    <td width="20%">
                        <b>Fecha:</b><br>{{ date('Y-m-d', strtotime($equiposlista['created_at'])) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <script type="text/php">
                            if ( isset($pdf) )
                            {
                                $pdf->page_script('
                                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                                    $pdf->text(473, 80, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);
                                ');
                            }
                        </script>
                    </td>
                </tr>
            </tbody>
        </table>
	</header>

	<footer>
		<hr>www.results-in-performance.com
	</footer>
    {{-- /ENCABEZADO Y PIE DE PÁGINA --}}
    {{-- CUERPO DE LA PAGINA --}}

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_datos">
        <tbody>
            <tr>
                <th style="width: 15%!important;">No. proyecto:</th>
                <td style="width: 35%!important;">{{ $proyecto->proyecto_folio }}</td>
                <th style="width: 15%!important;">No. orden trabajo:</th>
                <td style="width: 35%!important;">{{ $proyecto->folio_ot }}</td>
            </tr>
            <tr>
                <th>Instalación:</th>
                <td colspan="3">{{ $proyecto->proyecto_clienteinstalacion }}</td>
            </tr>
            <tr>
                <th>Dirección del servicio:</th>
                <td colspan="3">{{ $proyecto->proyecto_clientedireccionservicio }}</td>
            </tr>
        </tbody>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_listaservicios">
        <thead>
            <tr>
                <th colspan="8">Equipos asignados</th>
            </tr>
            <tr>
                <th style="width: 5%!important;">No.</th>
                <th>Proveedor</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Fecha de calibración</th>
                <th>Vigencia de calibración</th>
            </tr>
        </thead>
        <tbody>
            @php $fila = 0; @endphp
            @foreach($equipos as $equipo)
                @php $fila += 1; @endphp
                <tr>
                    <td>{{ $fila }}</td>
                    <td>{{ $equipo->proveedor_NombreComercial }}</td>
                    <td>{{ $equipo->equipo_Descripcion }}</td>
                    <td>{{ $equipo->equipo_Marca }}</td>
                    <td>{{ $equipo->equipo_Modelo }}</td>
                    <td>{{ $equipo->equipo_Serie }}</td>
                    <td>{{ $equipo->equipo_FechaCalibracion }}</td>
                    <td>{{ $equipo->equipo_VigenciaCalibracion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="firma">
        @if($equiposlista['proyectoequipo_autorizado'] > 0)
            <div style="border: 1px #555555 solid; width: 220px; margin: 0px auto 4px auto">
                Documento firmado digitalmente por:<br>
                {{ $equiposlista['proyectoequipo_autorizadonombre'] }}<br>
                {{ $equiposlista['proyectoequipo_autorizadofecha'] }}
            </div>
        @else
            <br>
        @endif
        <hr style="margin: 0px;">
        <p style="font-weight: bold;">Elaborado por:</p>
    </div>
    {{-- CUERPO DE LA PAGINA --}}

    {{-- SALTO DE PAGINA --}}
	{{-- <div class="page-break"></div> --}}
    {{-- /SALTO DE PAGINA --}}
	{{-- <h2>DATOS PAGINA 2</h2> --}}

</body>
</html>
