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
            margin: 4.125cm 1.5cm 1.8cm 1.5cm;
            /*margin: 3.6cm 1.5cm 1.8cm 1.5cm;*/
            font-family: Helvetica, sans-serif;
            /*font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"!important;*/
            font-size: 10px;
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
            font-size: 10px;
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
            font-size: 10px;
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
            font-size: 10px;
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
            /*height: 20px;*/
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
            font-size: 10px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_datos th{
            /*width: 16.6666%!important;*/
            /*height: 20px;*/
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_datos td{
            /*width: 16.6666%!important;*/
            /*height: 20px;*/
            font-weight: normal;
            color: #333333;
            text-align: center;
            background: none;
            padding: 1px;
            vertical-align: middle;
            border: 1px #333333 solid;
        }

        /*----------------------------------*/

        #tabla_listaservicios{
            width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 10px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_listaservicios th{
            /*height: 20px;*/
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_listaservicios td{
            /*height: 20px;*/
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
            font-size: 10px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_observacion th{
            width: 100%!important;
            /*height: 20px;*/
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
            /*height: 20px;*/
            font-weight: normal;
            color: #333333;
            text-align: justify;
            background: none;
            padding: 1px 2px;
            vertical-align: middle;
            border: 1px #333333 solid;
        }

        #tabla_observacion td p{
            margin: 0px;
            padding: 0px;
            line-height: 16px;
        }

        /*----------------------------------*/

        #tabla_firma{
            width: 100%;
            padding: 0px;
            margin: 0px;
            font-family: inherit!important;
            font-size: 10px;
            color: #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabla_firma th{
            /*height: 14px;*/
            font-weight: bold!important;
            color: #000000;
            text-align: center;
            border: 1px #000000 solid;
            background: #E8E8E8;
            padding: 1px;
            vertical-align: middle;
        }

        #tabla_firma td{
            height: 50px;
            font-weight: normal;
            color: #333333;
            text-align: center;
            background: none;
            padding: 0px;
            vertical-align: middle;
            border: 1px #333333 solid;
        }

        #firma{
            width: 240px;
            margin:0px auto; 
            text-align: center;
            line-height: 14px;
            /*border: 1px #F00 solid;*/
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
                    <td rowspan="3" colspan="2" width="60%">
                        <h1>Orden de compra - PO</h1>
                        @if( ($ordencompra['proyectoordencompra_cancelado'] + 0) == 1)
                            <h1 style="color: #F00;">CANCELADA</h1>
                        @endif
                    </td>
                    <td width="20%" style="height: 20px;"><b>RIPFS-CP-003</b></td>
                </tr>
                <tr>
                    <td style="height: 20px;"><b>Versión:</b> 0</td>
                </tr>
                <tr>
                    <td style="height: 20px;">
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
                <tr>
                    <td colspan="4" style="border-left: none; border-right: none; height: 6px!important;"></td>
                </tr>
                <tr>
                    <td style="border-right: none!important;">&nbsp;</td>
                    <td style="border-left: none!important;">&nbsp;</td>
                    <td style="background: #E8E8E8; font-weight: bold!important;">No. Orden de compra - PO</td>
                    <td><b style="color: #273eff;">{{ $ordencompra['proyectoordencompra_folio'] }}</b></td>
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
                <th colspan="4">Emisor de la orden de compra</th>
            </tr>
            <tr>
                <td>{{ $proyecto->proyecto_razonsocial }}</td>
                <th colspan="2">Ciudad / País</th>
                <th>Fecha de emisión (aaaa/mm/dd)</th>
            </tr>
            <tr>
                <td>{{ $proyecto->proyecto_rfc }}</td>
                <td colspan="2" rowspan="2">{{ $proyecto->proyecto_ciudadpais }}</td>
                <td rowspan="2">
                    @if($ordencompra['proyectoordencompra_autorizadofecha'])
                        {{ date('Y-m-d', strtotime($ordencompra['proyectoordencompra_autorizadofecha'])) }}
                    @else
                        <span style="color: #F00;">PENDIENTE AUTORIZACIÓN</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th width="50%">Contacto</th>
            </tr>
            <tr>
                <td width="50%">{{ $proyecto->proyecto_contacto }}</td>
                <th width="15%">Teléfono</th>
                <td width="15%">{{ $proyecto->proyecto_contactotelefono }}</td>
                <th width="20%">No. de MR o Proyecto</th>
            </tr>
            <tr>
                <th>Dirección</th>
                <th>Celular</th>
                <td>{{ $proyecto->proyecto_contactocelular }}</td>
                <td>{{ $proyecto->proyecto_folio }}</td>
            </tr>
            <tr>
                <td>{{ $proyecto->proyecto_direccion }}</td>
                <th>E-mail</th>
                <td colspan="2">{{ $proyecto->proyecto_contactocorreo }}</td>
            </tr>
            <tr>
                <th colspan="4">Proveedor</th>
            </tr>
            <tr>
                <td>{{ $proveedor->proveedor_RazonSocial }}</td>
                <th colspan="2">Ciudad / País</th>
                <th>Fecha de entrega (aaaa/mm/dd)</th>
            </tr>
            <tr>
                <th>Contacto</th>
                <td colspan="2" rowspan="2">{{ $proveedor->proveedor_CiudadPais }}</td>
                <td rowspan="4">
                    @if($proyecto->proyecto_fechainicio)
                        {{ date('Y-m-d', strtotime('+40 day', strtotime($proyecto->proyecto_fechainicio))) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ $proveedor->proveedor_NombreContacto }}</td>
            </tr>
            <tr>
                <th width="50%">Dirección</th>
                <th width="15%">Teléfono</th>
                <td width="15%">{{ $proveedor->proveedor_TelefonoContacto }}</td>
            </tr>
            <tr>
                <td rowspan="2">{{ $proveedor->proveedor_DomicilioFiscal }}</td>
                <th>Celular</th>
                <td>{{ $proveedor->proveedor_CelularContacto }}</td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td colspan="2">{{ $proveedor->proveedor_CorreoContacto }}</td>
            </tr>
        </tbody>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_listaservicios">
        <thead>
            <tr>
                <th style="width: 5%!important;">No.</th>
                <th style="width: 45%!important;">Servicio</th>
                <th style="width: 10%!important;">Cantidad</th>
                <th style="width: 20%!important;">Precio Unitario</th>
                <th style="width: 20%!important;">Importe total</th>
            </tr>
        </thead>
        <tbody>            
            {{ $fila = 0, $totalorden = 0 }}
            @foreach($servicios as $servicio)
                @php  
                    $fila += 1;
                    $totalorden += $servicio->totalcantidadxunitario;

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
                                                                        acreditacionalcance.proveedor_id = "'.$servicio->proveedor_id.'" 
                                                                        AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                                    ORDER BY
                                                                        acreditacionalcance.id DESC
                                                                ) AS TABLA3
                                                            WHERE
                                                                TABLA3.agente_nombre = "'.$servicio->proyectoproveedores_agente.'"
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
                                                        --         AND recsensorialagentescliente.agentescliente_nombre = "'.$servicio->proyectoproveedores_agente.'"
                                                        -- )
                                                    ) AS TABLA2
                                                LIMIT 1');
                @endphp
                <tr>
                    <td>{{ $fila }}</td>
                    <td style="text-align: justify;">
                        <b>{{ $servicio->proyectoproveedores_agente }}</b>
                        @if(count($descripcion) > 0)
                            &nbsp;"{{ $descripcion[0]->agente_descripcion }}"
                        @endif
                    </td>
                    <td>{{ $servicio->proyectoproveedores_puntos }}</td>
                    <td>
                        @if($servicio->preciounitario == 0)
                            -
                        @else
                            $ {{ number_format($servicio->preciounitario, 2) }}
                        @endif
                    </td>
                    <td>
                        @if($servicio->preciounitario == 0)
                            -
                        @else
                            $ {{ number_format($servicio->preciototal, 2) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <th style="width: 60%!important;">&nbsp;</th>
                <th style="width: 20%!important;">Subtotal: </th>
                <td style="width: 20%!important;"><h3>$ {{ number_format($totalorden, 2) }}</h3></td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>IVA: </th>
                <td><h3>$ {{ number_format($totalorden*(0.16), 2) }}</h3></td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>Total: </th>
                <td><h3>$ {{ number_format($totalorden + $totalorden*(0.16), 2) }}</h3></td>
            </tr>
        </tfoot> --}}
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_listaservicios">
        <tfoot>
            <tr>
                <th style="width: 60%!important;">&nbsp;</th>
                <th style="width: 20%!important;">Subtotal: </th>
                <td style="width: 20%!important;"><h3>$ {{ number_format($totalorden, 2) }}</h3></td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>IVA: </th>
                <td><h3>$ {{ number_format($totalorden*(0.16), 2) }}</h3></td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <th>Total: </th>
                <td><h3>$ {{ number_format($totalorden + $totalorden*(0.16), 2) }}</h3></td>
            </tr>
        </tfoot>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_observacion">
        <tbody>
            <tr>
                <th>Observaciones o indicaciones especiales</th>
            </tr>
            <tr>
                <td>
                    {{-- <p style="text-align: center;"><b>De acuerdo con la nueva disposición fiscal del SAT para la facturación versión 3.3 considerar lo siguiente:</b></p>
                    <p>* Forma de pago: <b style="text-decoration: underline;">"99"por definir</b></p>
                    <p>* Posteriormente debe emitirse el complemento de pago: <b style="text-decoration: underline;">"03" transferencia electrónica de fondos.</b></p>
                    <p>* Método de pago: <b style="text-decoration: underline;">"PPD" pago en parcialidades o diferido</b></p> 
                    <p>* Uso de CFDI: <b style="text-decoration: underline;">G03 gastos en general</b></p>
                    <p>* Debe incluir el número de la Orden de compra-PO y el número de Recepción del bien y/o servicio-GR en la factura</p>
                    <p>* Debe anexar copia de la orden de compra y recepción del bien y/o servicio, además debe enviar la factura en archivo PDF y XML</p>
                    <p>* Recepción de facturas: <b style="text-decoration: underline;">martes y jueves en horario de 9:00 - 13:00 horas</b></p> --}}
                    * <b>Instalación y dirección del servicio:</b> <span style="text-decoration: underline;">{{ $proyecto->proyecto_clienteinstalacion }}</span>, {{ $proyecto->proyecto_clientedireccionservicio }}
                    @if($ordencompra['proyectoordencompra_observacionoc'])
                        <br><br>* {{ $ordencompra['proyectoordencompra_observacionoc'] }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" id="tabla_firma">
        <tbody>
            <tr>
                <th width="50%">Solicitado por:</th>
                <th width="50%">Aprobado por:</th>
            </tr>
            <tr>
                <td>
                    @if( ($ordencompra['proyectoordencompra_revisado'] + 0) == 1)
                        <div id="firma">
                            <div style="border: 1px #777777 solid; width: 220px; margin: 0px auto;">
                                Documento firmado digitalmente por:<br>{{ $ordencompra['proyectoordencompra_revisadonombre'] }}<br>{{ $ordencompra['proyectoordencompra_revisadofecha'] }}
                            </div>
                        </div>
                    @endif
                </td>
                <td>
                    @if( ($ordencompra['proyectoordencompra_autorizado'] + 0) == 1)
                        <div id="firma">
                            <div style="border: 1px #777777 solid; width: 220px; margin: 0px auto;">
                                Documento firmado digitalmente por:<br>{{ $ordencompra['proyectoordencompra_autorizadonombre'] }}<br>{{ $ordencompra['proyectoordencompra_autorizadofecha'] }}
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    {{-- /CUERPO DE LA PAGINA --}}

    {{-- SALTO DE PAGINA --}}
    {{-- <div class="page-break"></div> --}}
    {{-- /SALTO DE PAGINA --}}
    {{-- <h2>DATOS PAGINA 2</h2> --}}
</body>
</html>
