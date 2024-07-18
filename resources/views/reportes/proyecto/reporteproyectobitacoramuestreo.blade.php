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
            margin: 5.5cm 1.4cm 2cm 1.5cm;
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

        #tabla_encabezado td{
        	height: 20px;
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
            font-size: 11px;
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

        #tabla_lista{
            width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 10px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_lista th{
            height: 20px;
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_lista td{
            height: 20px;
            font-weight: normal;
            color: #333333;
            text-align: center;
            background: none;
            padding: 1px 2px;
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
                    <td rowspan="3" width="30%"><img src="{{ Request::root().'/assets/images/logo_results.jpg' }}" height="46" width="130" alt=""></td>
                    <td rowspan="3" width="60%"><h1>Bitácora de muestreo</h1></td>
                    <td width="30%"><b>Proyecto:</b> {{ $proyecto->proyecto_folio }}</td>
                </tr>
                <tr>
                    <td><b>OT:</b> {{ $ordentrabajo_folio }}</td>
                </tr>
                <tr>
                    <td>
                        <script type="text/php">
                            if ( isset($pdf) )
                            {
                                $pdf->page_script('
                                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                                    $pdf->text(463, 81, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);
                                ');
                            }
                        </script>
                    </td>
                </tr>
                <tr>
                    <td><b>Instalación</b></td>
                    <td colspan="2">{{ $proyecto->proyecto_clienteinstalacion }}</td>
                </tr>
                <tr>
                    <td><b>Dirección del servicio:</b></td>
                    <td colspan="2">{{ $proyecto->proyecto_clientedireccionservicio }}</td>
                </tr>
                <tr>
                    <td><b>No. orden de servicio:</b></td>
                    <td colspan="2">{{ $proyecto->proyecto_ordenservicio }}</td>
                </tr>
            </tbody>
        </table>
	</header>


	<footer>
		<hr>www.results-in-performance.com
	</footer>
    {{-- /ENCABEZADO Y PIE DE PÁGINA --}}


    {{-- CUERPO DE LA PAGINA --}}
    @php $contador = 0; @endphp
    @foreach($bitacora as $dia)
        @php $contador += 1; @endphp

        <table cellpadding="0" cellspacing="0" width="100%" id="tabla_lista">
            <thead>
                <tr>
                    <th colspan="4">Día {{ $contador }}: {{ $dia->proyectoevidenciabitacora_fecha }}</th>
                </tr>
                <tr>
                    <th width="25%" style="background: #F9F9F9;">Personal involucrado</th>
                    <th width="20%" style="background: #F9F9F9;">Parametro</th>
                    <th width="15%" style="background: #F9F9F9;">Avance (Pts. / Pers.)</th>
                    <th width="40%" style="background: #F9F9F9;">Observación</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $personal = DB::select('SELECT
                                                proyectoevidenciabitacorapersonal.proyectoevidenciabitacora_id,
                                                proyectoevidenciabitacorapersonal.signatario_id,
                                                -- proyectoevidenciabitacorapersonal.signatario_nombre,
                                                -- signatario.signatario_Nombre,
                                                IF(IFNULL(signatario.signatario_Nombre, "") = "", proyectoevidenciabitacorapersonal.signatario_nombre, signatario.signatario_Nombre) AS signatario_nombre,
                                                proyectoevidenciabitacorapersonal.signatario_observacion,
                                                proyectoevidenciabitacorapersonal.agente_id,
                                                proyectoevidenciabitacorapersonal.agente_nombre,
                                                proyectoevidenciabitacorapersonal.agente_puntos,
                                                proyectoevidenciabitacorapersonal.created_at,
                                                proyectoevidenciabitacorapersonal.updated_at
                                            FROM
                                                proyectoevidenciabitacorapersonal
                                                LEFT JOIN signatario ON proyectoevidenciabitacorapersonal.signatario_id = signatario.id
                                            WHERE
                                                proyectoevidenciabitacorapersonal.proyectoevidenciabitacora_id = '.$dia->id.' 
                                            ORDER BY
                                                proyectoevidenciabitacorapersonal.signatario_id DESC');
                @endphp

                @foreach($personal as $persona)
                    <tr>
                        <td>{{ $persona->signatario_nombre }}</td>
                        <td>{{ $persona->agente_nombre }}</td>
                        <td>{{ $persona->agente_puntos }}</td>
                        <td style="text-align: justify;">{{ $persona->signatario_observacion }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: justify;">{{ $dia->proyectoevidenciabitacora_observacion }}</td>
                </tr>
            </tfoot>
        </table><br>

    @endforeach

    {{-- <div id="firma">
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
    </div> --}}
    {{-- CUERPO DE LA PAGINA --}}

    {{-- SALTO DE PAGINA --}}
	{{-- <div class="page-break"></div> --}}
    {{-- /SALTO DE PAGINA --}}
	{{-- <h2>DATOS PAGINA 2</h2> --}}
</body>
</html>
