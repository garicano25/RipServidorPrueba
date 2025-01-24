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
            font-size: 9px;
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
            font-size: 9px;
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
            font-size: 9px;
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
        	font-size: 9px;
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
            font-size: 9px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_datos th{
            /*width: 16.6666%!important;*/
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
            /*width: 16.6666%!important;*/
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
            font-size: 9px;
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
            text-align: justify;
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
            font-size: 9px;
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
                    <td rowspan="3" width="20%"><img src="{{ Request::root().'/assets/images/logo_results.jpg' }}" height="46" width="130" alt=""></td>
                    <td rowspan="3" width="60%">
                        <h1>Orden de trabajo (OT)</h1>
                        @if( ($ordentrabajo['proyectoordentrabajo_cancelado'] + 0) == 1)
                            <h1 style="color: #F00;">CANCELADA</h1>
                        @endif
                    </td>
                    <td width="20%"><b>RIPFM-OC-001</b></td>
                </tr>
                <tr>
                    <td><b>Versión:</b> 0</td>
                </tr>
                <tr>
                    <td>
                        <script type="text/php">
                            if ( isset($pdf) )
                            {
                                $pdf->page_script('
                                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                                    $pdf->text(470, 80, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9);
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
                <th colspan="6">Datos del cliente a quien se dirige el informe</th>
            </tr>
            <tr>
                <th style="width: 20%!important;">No. cotización:</th>
                <td style="width: 15%!important;">{{ $proyecto->proyecto_cotizacion }}</td>
                <th style="width: 15%!important;">No. de OT:</th>
                <td style="width: 20%!important;"><b style="color: #273eff;">{{ $ordentrabajo['proyectoordentrabajo_folio'] }}</b></td>
                <th style="width: 15%!important;">Fecha:</th>
                <td style="width: 15%!important;">
                    @if($ordentrabajo['proyectoordentrabajo_autorizadofecha'])
                        {{ date('Y-m-d', strtotime($ordentrabajo['proyectoordentrabajo_autorizadofecha'])) }}
                    @else
                        <span style="color: #F00;">PENDIENTE AUTORIZACIÓN</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Razón social:</th>
                <td colspan="5">{{ $proyecto->proyecto_clienterazonsocial }}</td>
            </tr>
            <tr>
                <th>Nombre comercial:</th>
                <td colspan="2">{{ $proyecto->proyecto_clientenombrecomercial }}</td>
                <th>RFC:</th>
                <td colspan="2">{{ $proyecto->proyecto_clienterfc }}</td>
            </tr>
            <tr>
                <th>Giro de la empresa:</th>
                <td colspan="5" style="text-align: justify!important;">{{ $proyecto->proyecto_clientegiroempresa }}</td>
            </tr>
            <tr>
                <th>Dirección del servicio:</th>
                <td colspan="5">Instalación: <span style="text-decoration: underline;">{{ $proyecto->proyecto_clienteinstalacion }}</span>, {{ $proyecto->proyecto_clientedireccionservicio }}</td>
            </tr>
            <tr>
                <th>Persona a quien se dirige el informe:</th>
                <td colspan="5">{{ $proyecto->proyecto_clientepersonadirigido }}</td>
            </tr>
            <tr>
                <th>Contacto:</th>
                <td colspan="2">{{ $proyecto->proyecto_clientepersonacontacto }}</td>
                <th>Teléfono:</th>
                <td colspan="2">{{ $proyecto->proyecto_clientetelefonocontacto }}</td>
            </tr>
            <tr>
                <th>Celular:</th>
                <td colspan="2">{{ $proyecto->proyecto_clientecelularcontacto }}</td>
                <th>E-mail:</th>
                <td colspan="2">{{ $proyecto->proyecto_clientecorreocontacto }}</td>
            </tr>
            <tr>
                <th colspan="6">Descripción del servicio</th>
            </tr>
            <tr>
                <th>Necesidad u objetivo del servicio:</th>
                <td colspan="5" style="text-align: justify!important;">{{ $proyecto->proyecto_clienteobjetivoservicio }}</td>
            </tr>
        </tbody>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_listaservicios">
        <thead>
            <tr>
                <th style="width: 4%!important;">No.</th>
                <th style="width: 6%!important;">Cantidad</th>
                <th style="width: 30%!important;">Servicio</th>
                <th style="width: 25%!important;">Norma / Método</th>
                <th style="width: 7%!important;">Cantidad real</th>
                <th style="width: 7%!important;">Verificado</th>
            </tr>
        </thead>
        <tbody>
            @php $fila = 0; @endphp
            @foreach($servicios as $dato)
                @php 

                    $fila += 1;

                    // Obtener descripcion del analisis del agente
                    $descripcion = DB::select('SELECT
                                                    -- TABLA2.proveedor_id_recsensorial_id,
                                                    -- TABLA2.agente_id,
                                                    -- TABLA2.agente_nombre,
                                                    TABLA2.agente_descripcion
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                TABLA3.proveedor_id AS proveedor_id_recsensorial_id,
                                                                TABLA3.prueba_id AS agente_id,
                                                                TABLA3.agente_nombre AS agente_nombre,
                                                                TABLA3.agente_descripcion AS agente_descripcion
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        acreditacionalcance.proveedor_id,
                                                                        acreditacionalcance.prueba_id,
                                                                        (IF(IFNULL(acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionAlcance_agentetipo, ")"))) AS agente_nombre,
                                                                        IFNULL(acreditacionalcance.acreditacionAlcance_Descripcion, "") AS agente_descripcion
                                                                    FROM
                                                                        acreditacionalcance 
                                                                    WHERE
                                                                        acreditacionalcance.proveedor_id = "'.$dato->proyectoordentrabajodatos_proveedorid.'" 
                                                                        AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                                    ORDER BY
                                                                        acreditacionalcance.id DESC
                                                                ) AS TABLA3
                                                            WHERE
                                                                TABLA3.agente_nombre = "'.$dato->proyectoordentrabajodatos_agentenombre.'"
                                                            LIMIT 1
                                                        )
                                                        -- UNION ALL
                                                        -- (
                                                        --     SELECT
                                                        --         recsensorialagentescliente.recsensorial_id AS proveedor_id_recsensorial_id,
                                                        --         recsensorialagentescliente.agentescliente_agenteid AS agente_id,
                                                        --         recsensorialagentescliente.agentescliente_nombre AS agente_nombre,
                                                        --         recsensorialagentescliente.agentescliente_analisis AS agente_descripcion
                                                        --     FROM
                                                        --         recsensorialagentescliente
                                                        --     WHERE
                                                        --         recsensorialagentescliente.recsensorial_id = '.$proyecto->recsensorial_id.'
                                                        --         AND recsensorialagentescliente.agentescliente_nombre = "'.$dato->proyectoordentrabajodatos_agentenombre.'"
                                                        -- )
                                                    ) AS TABLA2
                                                LIMIT 1');
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $fila }}</td>
                    <td style="text-align: center;">{{ $dato->proyectoordentrabajodatos_agentepuntos }}</td>
                    <td>
                        <b>{{ $dato->proyectoordentrabajodatos_agentenombre }}</b>
                        @if(count($descripcion) > 0)
                            &nbsp;"{{ $descripcion[0]->agente_descripcion }}"
                        @endif
                    </td>
                    <td>{{ $dato->proyectoordentrabajodatos_agentenormas }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_observacion">
        <tbody>
            <tr>
                <th>Observaciones:</th>
            </tr>
            <tr>
                <td>{{ $ordentrabajo['proyectoordentrabajo_observacionot'] }}</td>
            </tr>
        </tbody>
    </table>

    @if( ($ordentrabajo['proyectoordentrabajo_autorizado'] + 0) == 1)
        <div id="firma">
            <div style="border: 1px #777777 solid; width: 220px; height: 46px; margin: 0px auto 4px auto">
                Documento firmado digitalmente por:<br>{{ $ordentrabajo['proyectoordentrabajo_autorizadonombre'] }}<br>{{ $ordentrabajo['proyectoordentrabajo_autorizadofecha'] }}
            </div>
            <hr style="margin: 0px;">
            <p style="font-weight: bold;">Elaborado por:</p>
        </div>
    @else
        <div id="firma">
            <div style="border: 0px #777777 solid; width: 220px; height: 20px; margin: 0px auto 4px auto"></div>
            <hr style="margin: 0px;">
            <p style="font-weight: bold;">Elaborado por:</p>
        </div>
    @endif

    {{-- CUERPO DE LA PAGINA --}}

    {{-- SALTO DE PAGINA --}}
	{{-- <div class="page-break"></div> --}}
    {{-- /SALTO DE PAGINA --}}
	{{-- <h2>DATOS PAGINA 2</h2> --}}
</body>
</html>
