<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// plugins PDF
use Barryvdh\DomPDF\Facade as PDF;
use PDFMerger;

// Modelos
use App\modelos\proyecto\proyectoordencompraModel;
use App\modelos\proyecto\proyectoordencompradatosModel;
use App\modelos\proyecto\proyectoModel;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\ServicioModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoordencompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Financiero,Cadista,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
    */
    public function proyectoordencompratabla($proyecto_id)
    {
        try
        {
            $activo = 0;
            if (auth()->user()->hasRoles(['Superusuario', 'Financiero']))
            {
                $activo = 1;
            }


            //------------------------------------


            $ordenescompra = DB::select('SELECT
                                                proyectoordencompra.id,
                                                proyectoordencompra.proyecto_id,
                                                proyectoordencompra.proveedor_id,
                                                proyectoordencompra.servicio_id,
                                                proveedor.proveedor_NombreComercial,
                                                proyectoordencompra.proyectoordencompra_folio,
                                                proyectoordencompra.proyectoordencompra_revision,
                                                IFNULL(proyectoordencompra.proyectoordencompra_tipolista, 0) AS proyectoordencompra_tipolista,
                                                proyectoordencompra.proyectoordencompra_revisado,
                                                proyectoordencompra.proyectoordencompra_revisadonombre,
                                                proyectoordencompra.proyectoordencompra_revisadofecha,
                                                proyectoordencompra.proyectoordencompra_autorizado,
                                                proyectoordencompra.proyectoordencompra_autorizadonombre,
                                                proyectoordencompra.proyectoordencompra_autorizadofecha,
                                                proyectoordencompra.proyectoordencompra_observacionoc,
                                                proyectoordencompra.proyectoordencompra_cancelado,
                                                proyectoordencompra.proyectoordencompra_canceladonombre,
                                                proyectoordencompra.proyectoordencompra_canceladofecha,
                                                proyectoordencompra.proyectoordencompra_canceladoobservacion,
                                                proyectoordencompra.proyectoordencompra_observacionrevision,
                                                IFNULL(proyectoordencompra.proyectoordencompra_facturado, 0) AS proyectoordencompra_facturado,
                                                IFNULL(proyectoordencompra.proyectoordencompra_facturadonombre, "x") AS proyectoordencompra_facturadonombre,
                                                IFNULL(proyectoordencompra.proyectoordencompra_facturadofecha, "0000-00-00") AS proyectoordencompra_facturadofecha,
                                                IFNULL(proyectoordencompra.proyectoordencompra_facturadomonto, "0") AS proyectoordencompra_facturadomonto,
                                                IFNULL(proyectoordencompra.proyectoordencompra_facturadopdf, "x") AS proyectoordencompra_facturadopdf,
                                                proyectoordencompra.created_at,
                                                proyectoordencompra.updated_at
                                            FROM
                                                proyectoordencompra
                                                LEFT JOIN proveedor ON proyectoordencompra.proveedor_id = proveedor.id 
                                            WHERE
                                                proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                            ORDER BY
                                                proyectoordencompra.id DESC');


            $fila = ''; $revisado = ''; $autorizado = ''; $cancelado = ''; $facturado = ''; $estado = '';            
            
            $numero_registro = count($ordenescompra);
            
            foreach ($ordenescompra as $key => $value)
            {
                $value->numero_registro = $numero_registro;
                $numero_registro -= 1;


                $value->folio = '<b style="color: #000000;">'.$value->proyectoordencompra_folio.'</b><br>'.$value->created_at;

                
                $value->proveedor = $value->proveedor_NombreComercial;


                // Diseño Revisado
                if (($value->proyectoordencompra_revisado+0) == 1)
                {
                    $value->revisado = $value->proyectoordencompra_revisadonombre.'<br>'.$value->proyectoordencompra_revisadofecha;
                }
                else
                {
                    $value->revisado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }


                // Diseño Autorizado
                if (($value->proyectoordencompra_autorizado+0) == 1)
                {
                    $value->autorizado = $value->proyectoordencompra_autorizadonombre.'<br>'.$value->proyectoordencompra_autorizadofecha;
                }
                else
                {
                    $value->autorizado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }


                // Diseño facturado
                if (($value->proyectoordencompra_facturado+0) == 1)
                {
                    $value->facturado = $value->proyectoordencompra_facturadonombre.'<br>'.$value->proyectoordencompra_facturadofecha;
                }
                else
                {
                    $value->facturado = 'N/A';
                }


                // Diseño cancelado
                if (($value->proyectoordencompra_cancelado+0) == 1)
                {
                    $value->cancelado = $value->proyectoordencompra_canceladonombre.'<br>'.$value->proyectoordencompra_canceladofecha;
                }
                else
                {
                    $value->cancelado = 'N/A';
                }


                // Diseño estado
                if (($value->proyectoordencompra_cancelado+0) == 1)
                {
                    $value->estado = '<b class="text-danger">Cancelado</b>';
                }
                else if (($value->proyectoordencompra_facturado+0) == 1)
                {
                    $value->estado = '<b style="color: #8BC34A!important;">Facturado</b>';
                }
                else
                {
                    $value->estado = '<b class="text-info">Vigente</b>';
                }


                $value->activo = $activo;


                // BOTON
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle boton_mostrar" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';


                // // Dibujar filas tabla
                // $fila.='<tr>
                //             <td>'.$numero_registro.'</td>
                //             <td><b style="color: #000000;">'.$value->proyectoordencompra_folio.'</b><br>'.$value->created_at.'</td>
                //             <td>'.$value->proveedor_NombreComercial.'</td>
                //             <td>'.$revisado.'</td>                            
                //             <td>'.$autorizado.'</td>
                //             <td>'.$facturado.'</td>
                //             <td>'.$cancelado.'</td>
                //             <td>'.$estado.'</td>
                //             <td><button type="button" class="btn btn-info btn-circle" onclick="mostrar_oc('.$proyecto_id.', '.$value->proveedor_id.', '.$value->servicio_id.', '.$value->id.', \''.$value->proyectoordencompra_folio.'\', '.$value->proyectoordencompra_revision.', '.$value->proyectoordencompra_tipolista.', '.$value->proyectoordencompra_revisado.', \''.$value->proyectoordencompra_revisadonombre.'\', \''.$value->proyectoordencompra_revisadofecha.'\', '.$value->proyectoordencompra_autorizado.', \''.$value->proyectoordencompra_autorizadonombre.'\', \''.$value->proyectoordencompra_autorizadofecha.'\', '.$value->proyectoordencompra_cancelado.', \''.$value->proyectoordencompra_canceladonombre.'\', \''.$value->proyectoordencompra_canceladofecha.'\', '.$value->proyectoordencompra_facturado.', \''.$value->proyectoordencompra_facturadonombre.'\', \''.$value->proyectoordencompra_facturadofecha.'\', '.$value->proyectoordencompra_facturadomonto.', \''.$value->proyectoordencompra_facturadopdf.'\', '.$activo.');"><i class="fa fa-eye"></i></button></td>
                //         </tr>';
            }

            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            $dato['data'] = $ordenescompra;
            // $dato['total'] = count($ordenescompra);
            // $dato['fila'] = $fila;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['data'] = 0;
            // $dato['total'] = 0;
            // $dato['fila'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $ordencompra_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordencompradatos($ordencompra_id)
    {
        try
        {
            $oc = DB::select('SELECT
                                    proyectoordencompra.id,
                                    proyectoordencompra.proyecto_id,
                                    proyectoordencompra.proveedor_id,
                                    proyectoordencompra.proyectoordencompra_folio,
                                    proyectoordencompra.proyectoordencompra_revision,
                                    proyectoordencompra.proyectoordencompra_revisado,
                                    proyectoordencompra.proyectoordencompra_revisadonombre,
                                    proyectoordencompra.proyectoordencompra_revisadofecha,
                                    proyectoordencompra.proyectoordencompra_autorizado,
                                    proyectoordencompra.proyectoordencompra_autorizadonombre,
                                    proyectoordencompra.proyectoordencompra_autorizadofecha,
                                    proyectoordencompra.proyectoordencompra_observacionoc,
                                    proyectoordencompra.proyectoordencompra_cancelado,
                                    proyectoordencompra.proyectoordencompra_canceladonombre,
                                    proyectoordencompra.proyectoordencompra_canceladofecha,
                                    proyectoordencompra.proyectoordencompra_canceladoobservacion,
                                    proyectoordencompra.proyectoordencompra_observacionrevision,
                                    proyectoordencompra.proyectoordencompra_facturado,
                                    proyectoordencompra.proyectoordencompra_facturadonombre,
                                    proyectoordencompra.proyectoordencompra_facturadofecha,
                                    proyectoordencompra.proyectoordencompra_facturadomonto,
                                    proyectoordencompra.proyectoordencompra_facturadopdf,
                                    proyectoordencompra.created_at,
                                    proyectoordencompra.updated_at 
                                FROM
                                    proyectoordencompra
                                WHERE
                                    proyectoordencompra.id = '.$ordencompra_id);

            // Mensaje
            $dato["ordencompra"] = $oc;

            // respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Mensaje
            $dato["ordencompra"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordencompraproveedores($proyecto_id)
    {
        try
        {
            // Consulta proveedores asignados al proyecto
            DB::statement("SET lc_time_names = 'es_MX';");
            $proveedores = DB::select('SELECT
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id,
                                            COUNT(proyectoproveedores.catprueba_id) AS totalagentes_asignados,
                                            IFNULL(proveedor.proveedor_RazonSocial, "PROVEEDOR NO ENCONTRADO") AS proveedor_RazonSocial,
                                            IFNULL((
                                                SELECT
                                                    proyectoordencompra.proyectoordencompra_cancelado
                                                FROM
                                                    proyectoordencompra
                                                WHERE
                                                    proyectoordencompra.proyecto_id = proyectoproveedores.proyecto_id
                                                    AND proyectoordencompra.proveedor_id = proyectoproveedores.proveedor_id
                                                ORDER BY
                                                    proyectoordencompra.id DESC
                                                LIMIT 1
                                            ), 1) AS ultimaoc_cancelado,
                                            IFNULL((
                                                SELECT
                                                    proyectoordencompra.proyectoordencompra_facturado
                                                FROM
                                                    proyectoordencompra
                                                WHERE
                                                    proyectoordencompra.proyecto_id = proyectoproveedores.proyecto_id
                                                    AND proyectoordencompra.proveedor_id = proyectoproveedores.proveedor_id
                                                ORDER BY
                                                    proyectoordencompra.id DESC
                                                LIMIT 1
                                            ), 0) AS ultimaoc_facturado
                                        FROM
                                            proyectoproveedores
                                            LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id 
                                        WHERE
                                            proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                        GROUP BY
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id,
                                            proveedor.proveedor_RazonSocial
                                        ORDER BY
                                            proveedor.proveedor_RazonSocial ASC');


            // Consultar posible folio
            foreach ($proveedores as $key => $value)
            {
                // Folio propuesta
                $ordenescompranueva = DB::select('SELECT
                                                        TABLA.ordenescompra_totales,
                                                        TABLA.ordencompra_total,
                                                        TABLA.ordencompra_folio,
                                                        TABLA.ordencompra_version,
                                                        TABLA.ordencompra_cancelado,
                                                        (
                                                            IF(TABLA.ordencompra_total = 0,
                                                                (
                                                                    CASE
                                                                        WHEN (TABLA.ordenescompra_totales = 0) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 9) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 99) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-0", (TABLA.ordenescompra_totales + 1))
                                                                        ELSE CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-", (TABLA.ordenescompra_totales + 1))
                                                                    END
                                                                )
                                                            ,
                                                                CONCAT(TABLA.ordencompra_folio, "-Rev", TABLA.ordencompra_total)
                                                            )
                                                        ) AS ordencompra_folionuevo
                                                    FROM
                                                        (
                                                            SELECT
                                                                COUNT(proyectoordencompra.id) AS ordenescompra_totales,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(proyectoordencompra.id)
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$value->proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$value->proveedor_id.'
                                                                ), 0) AS ordencompra_total,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_folio
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$value->proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$value->proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id ASC
                                                                    LIMIT 1
                                                                ), "VACIO") AS ordencompra_folio,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_revision
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$value->proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$value->proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 0) AS ordencompra_version,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_cancelado
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$value->proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$value->proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 1) AS ordencompra_cancelado
                                                            FROM
                                                                proyectoordencompra
                                                            WHERE
                                                                DATE_FORMAT(proyectoordencompra.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")
                                                                AND proyectoordencompra.proyectoordencompra_revision = 0
                                                        ) AS TABLA');

                $value->folionuevo = $ordenescompranueva[0]->ordencompra_folionuevo;
                $value->ordencompra_total = $ordenescompranueva[0]->ordencompra_total;
            }

            // Verificar contenido
            $proveedresopciones = '<option value="">&nbsp;</option>';
            if(count($proveedores) > 0)
            {
                // Lista proveedores
                foreach ($proveedores as $key => $value)
                {
                    $proveedresopciones .= '<option value="'.$value->proveedor_id.'*'.$value->ultimaoc_cancelado.'*'.$value->ultimaoc_facturado.'*'.$value->folionuevo.'*'.$value->ordencompra_total.'">'.$value->proveedor_RazonSocial.'</option>';
                }


                // Mensaje
                $dato["ordencompra_totalproveedores"] = count($proveedores);
                $dato["ordencompra_listaproveedores"] = $proveedresopciones;
                $dato["msj"] = 'Lista de proveedores asignados';
            }
            else
            {
                // Mensaje
                $dato["ordencompra_totalproveedores"] = 0;
                $dato["ordencompra_listaproveedores"] = $proveedresopciones;
                $dato["msj"] = 'No hay proveedores asignados al proyecto';
            }

            // respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Mensaje
            $dato["ordencompra_totalproveedores"] = 0;
            $dato["ordencompra_listaproveedores"] = '<option value="">&nbsp;</option>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordencompraproveedorcotizacion($proveedor_id)
    {
        try
        {
            DB::statement("SET lc_time_names = 'es_MX';");

            $cotizaciones = ServicioModel::where('proveedor_id', $proveedor_id)
                                            ->where('servicio_Eliminado', 0)
                                            ->get();

            if (count($cotizaciones) > 0)
            {
                $dato["select_cotizacion_opciones"] = '<option value="">&nbsp;</option>';

                foreach ($cotizaciones as $key => $cotizacion)
                {
                    $dato["select_cotizacion_opciones"] .= '<option value="'.$cotizacion->id.'">'.$cotizacion->servicio_numerocotizacion.' - (Vigencia '.$cotizacion->servicio_VigenciaCotizacion.')</option>';
                }
            }
            else
            {
                $dato["select_cotizacion_opciones"] = '<option value="">No se encontró cotización para este proveedor</option>';
            }


            // respuesta
            $dato["msj"] = 'Consulta correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Mensaje
            $dato["select_cotizacion_opciones"] = '<option value="">&nbsp;</option>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proveedor_id
     * @param  int  $cotizacion_id
     * @param  int  $ordencompra_id
     * @return \Illuminate\Http\Response
    */
    public function proyectoordencompramostrar($proyecto_id, $proveedor_id, $cotizacion_id, $ordencompra_id)
    {
        try
        {
            // dd($proyecto_id.', '.$proveedor_id.', '.$ordencompra_id);

            $folio_ordencompra = '';

            if (($ordencompra_id + 0) > 0) //Orden de compra existente
            {
                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($proyecto_id);

                // Datos del proveedor
                $proveedor = ProveedorModel::findOrFail($proveedor_id);

                // Datos orden compra
                $ordencompra = proyectoordencompraModel::findOrFail($ordencompra_id);
                $folio_ordencompra = $ordencompra->proyectoordencompra_folio;                

                // Consultar los servicios de este proveedor
                $servicios = DB::select('SELECT
                                                TABLA.proyectoordencompra_id,
                                                TABLA.proyectoordencompradatos_proveedorid AS proveedor_id,
                                                TABLA.proyectoordencompradatos_agenteid AS catprueba_id,
                                                TABLA.proyectoordencompradatos_agentenombre AS proyectoproveedores_agente,
                                                TABLA.proyectoordencompradatos_agentepuntos AS proyectoproveedores_puntos,
                                                TABLA.proyectoordencompradatos_preciounitario AS preciounitario,
                                                TABLA.proyectoordencompradatos_importetotal AS preciototal,
                                                TABLA.proyectoordencompradatos_agentenormas,
                                                TABLA.proyectoordencompradatos_agenteobservacion AS proyectoproveedores_observacion
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            0 AS tipo,
                                                            proyectoordencompradatos.proyectoordencompra_id,
                                                            proyectoordencompradatos.proyectoordencompradatos_proveedorid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenombre,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentepuntos,
                                                            proyectoordencompradatos.proyectoordencompradatos_preciounitario,
                                                            proyectoordencompradatos.proyectoordencompradatos_importetotal,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenormas,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteobservacion 
                                                        FROM
                                                            proyectoordencompradatos
                                                        WHERE
                                                            proyectoordencompradatos.proyectoordencompra_id = '.$ordencompra_id.'
                                                            AND proyectoordencompradatos.proyectoordencompradatos_agenteid != 15
                                                            AND proyectoordencompradatos.proyectoordencompradatos_agenteid != 0
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            1 AS tipo,
                                                            proyectoordencompradatos.proyectoordencompra_id,
                                                            proyectoordencompradatos.proyectoordencompradatos_proveedorid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenombre,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentepuntos,
                                                            proyectoordencompradatos.proyectoordencompradatos_preciounitario,
                                                            proyectoordencompradatos.proyectoordencompradatos_importetotal,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenormas,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteobservacion 
                                                        FROM
                                                            proyectoordencompradatos
                                                        WHERE
                                                            proyectoordencompradatos.proyectoordencompra_id = '.$ordencompra_id.'
                                                            AND proyectoordencompradatos.proyectoordencompradatos_agenteid = 15
                                                            AND proyectoordencompradatos.proyectoordencompradatos_agenteid != 0
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 AS tipo,
                                                            proyectoordencompradatos.proyectoordencompra_id,
                                                            proyectoordencompradatos.proyectoordencompradatos_proveedorid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteid,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenombre,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentepuntos,
                                                            proyectoordencompradatos.proyectoordencompradatos_preciounitario,
                                                            proyectoordencompradatos.proyectoordencompradatos_importetotal,
                                                            proyectoordencompradatos.proyectoordencompradatos_agentenormas,
                                                            proyectoordencompradatos.proyectoordencompradatos_agenteobservacion 
                                                        FROM
                                                            proyectoordencompradatos
                                                        WHERE
                                                            proyectoordencompradatos.proyectoordencompra_id = '.$ordencompra_id.'
                                                            AND proyectoordencompradatos.proyectoordencompradatos_agenteid = 0
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.proyectoordencompradatos_agentenombre ASC');

                // Formatear Filas servicios
                foreach ($servicios as $key => $value)
                {
                    // si encontro el precio
                    if (($value->preciounitario + 0) > 0)
                    {
                        // SUMA TOTAL
                        $value->totalcantidadxunitario = round(($value->proyectoproveedores_puntos * $value->preciounitario), 2);

                        // PRECIOS POR AGENTE
                        $value->preciounitario = round($value->preciounitario, 2);
                        $value->preciototal = round(($value->proyectoproveedores_puntos * $value->preciounitario), 2);
                    }
                    else
                    {
                        // SUMA TOTAL
                        $value->totalcantidadxunitario = 0;

                        // PRECIOS POR AGENTE
                        $value->preciounitario = 0;
                        $value->preciototal = 0;
                    }
                }
            }
            else // Orden compra nueva [Vista previa]
            {
                // Obtener folio propuesta
                $ordenescompranueva = DB::select('SELECT
                                                        TABLA.ordenescompra_totales,
                                                        TABLA.ordencompra_total,
                                                        TABLA.ordencompra_folio,
                                                        TABLA.ordencompra_version,
                                                        TABLA.ordencompra_cancelado,
                                                        (
                                                            IF(TABLA.ordencompra_total = 0,
                                                                (
                                                                    CASE
                                                                        WHEN (TABLA.ordenescompra_totales = 0) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 9) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 99) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-0", (TABLA.ordenescompra_totales + 1))
                                                                        ELSE CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-", (TABLA.ordenescompra_totales + 1))
                                                                    END
                                                                )
                                                            ,
                                                                CONCAT(TABLA.ordencompra_folio, "-Rev", TABLA.ordencompra_total)
                                                            )
                                                        ) AS ordencompra_folionuevo
                                                    FROM
                                                        (
                                                            SELECT
                                                                COUNT(proyectoordencompra.id) AS ordenescompra_totales,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(proyectoordencompra.id)
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                ), 0) AS ordencompra_total,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_folio
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id ASC
                                                                    LIMIT 1
                                                                ), "VACIO") AS ordencompra_folio,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_revision
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 0) AS ordencompra_version,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_cancelado
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 1) AS ordencompra_cancelado
                                                            FROM
                                                                proyectoordencompra
                                                            WHERE
                                                                DATE_FORMAT(proyectoordencompra.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")
                                                                AND proyectoordencompra.proyectoordencompra_revision = 0
                                                        ) AS TABLA');

                // Folio
                $folio_ordencompra = $ordenescompranueva[0]->ordencompra_folionuevo;

                // Orden compra
                $ordencompra = array(
                     'proyecto_id' => $proyecto_id
                    ,'proveedor_id' => $proveedor_id
                    ,'servicio_id' => $cotizacion_id
                    ,'proyectoordencompra_folio' => $ordenescompranueva[0]->ordencompra_folionuevo
                    ,'proyectoordencompra_revision' => $ordenescompranueva[0]->ordencompra_total
                    ,'proyectoordencompra_revisado' => 0
                    ,'proyectoordencompra_revisadonombre' => NULL
                    ,'proyectoordencompra_revisadofecha' => NULL
                    ,'proyectoordencompra_autorizado' => 0
                    ,'proyectoordencompra_autorizadonombre' => NULL
                    ,'proyectoordencompra_autorizadofecha' => NULL
                    ,'proyectoordencompra_observacionoc' => NULL
                    ,'proyectoordencompra_observacionrevision' => NULL
                    ,'proyectoordencompra_cancelado' => 0
                    ,'created_at' => date('Y-m-d H:m:s')
                    ,'updated_at' => date('Y-m-d H:m:s')
                );

                // Consultar los servicios de este proveedor
                $servicios = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.proveedor_id,
                                            TABLA.catprueba_id,
                                            TABLA.proyectoproveedores_agente,
                                            TABLA.proyectoproveedores_puntos,
                                            TABLA.proyectoproveedores_observacion 
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        0 AS tipo,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectoproveedores.proyectoproveedores_puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion 
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                        AND proyectoproveedores.catprueba_id != 15
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        1 AS tipo,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectoproveedores.proyectoproveedores_puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion 
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                        AND proyectoproveedores.catprueba_id = 15
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.tipo ASC,
                                            TABLA.proyectoproveedores_agente ASC');


                // Formatear Filas servicios y consultar precios
                foreach ($servicios as $key => $value)
                {
                    $precio = DB::select('SELECT
                                                servicio.proveedor_id,
                                                servicio.id,
                                                servicio.servicio_numerocotizacion,
                                                servicio.servicio_FechaCotizacion,
                                                servicio.servicio_VigenciaCotizacion,
                                                servicio.servicio_Eliminado,
                                                servicioprecios.agente_id,
                                                servicioprecios.agente_nombre,
                                                servicioprecios.agente_preciounitario AS preciounitario
                                            FROM
                                                servicioprecios
                                                LEFT JOIN servicio ON servicioprecios.servicio_id = servicio.id
                                            WHERE
                                                servicio.id = '.$cotizacion_id.'
                                                -- AND servicio.proveedor_id = '.$value->proveedor_id.'
                                                -- AND servicio.servicio_Eliminado = 0
                                                AND servicioprecios.agente_nombre LIKE "'.$value->proyectoproveedores_agente.'"
                                            ORDER BY
                                                servicio.servicio_VigenciaCotizacion DESC
                                            LIMIT 1');

                    // si encontro el precio
                    if (count($precio) > 0)
                    {
                        // SUMA TOTAL
                        $value->totalcantidadxunitario = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);

                        // PRECIOS POR AGENTE
                        $value->preciounitario = round($precio[0]->preciounitario, 2);
                        $value->preciototal = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);
                    }
                    else
                    {
                        // SUMA TOTAL
                        $value->totalcantidadxunitario = 0;

                        // PRECIOS POR AGENTE
                        $value->preciounitario = '-';
                        $value->preciototal = '-';
                    }
                }

                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($proyecto_id);

                // Datos del proyecto
                $proveedor = ProveedorModel::findOrFail($proveedor_id);
            }

            // Respuesta vista PDF
            return \PDF::loadView('reportes.proyecto.reporteproyectoordencompra', compact('proyecto', 'proveedor', 'ordencompra', 'servicios'))->stream($folio_ordencompra.'.pdf');
        }
        catch(Exception $e)
        {
            // Respuesta
            return 'Error en la consulta: '.$e->getMessage();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proveedor_id
     * @param  int  $cotizacion_id
     * @param  int  $ordencompra_id
     * @param  int  $ordencompra_tipolista
     * @return \Illuminate\Http\Response
     */
    public function proyectoordencompraactualizar($proyecto_id, $proveedor_id, $cotizacion_id, $ordencompra_id, $ordencompra_tipolista)
    {
        try
        {
            // Datos del proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Datos del proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);


            if (($ordencompra_id+0) > 0)
            {
                // Datos orden compra
                $ordencompra = proyectoordencompraModel::findOrFail($ordencompra_id);
                $folio_ordencompra = $ordencompra->proyectoordencompra_folio;
            }
            else
            {
                // Obtener folio propuesta
                $ordenescompranueva = DB::select('SELECT
                                                        TABLA.ordenescompra_totales,
                                                        TABLA.ordencompra_total,
                                                        TABLA.ordencompra_folio,
                                                        TABLA.ordencompra_version,
                                                        TABLA.ordencompra_cancelado,
                                                        (
                                                            IF(TABLA.ordencompra_total = 0,
                                                                (
                                                                    CASE
                                                                        WHEN (TABLA.ordenescompra_totales = 0) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 9) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 99) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-0", (TABLA.ordenescompra_totales + 1))
                                                                        ELSE CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-", (TABLA.ordenescompra_totales + 1))
                                                                    END
                                                                )
                                                            ,
                                                                CONCAT(TABLA.ordencompra_folio, "-Rev", TABLA.ordencompra_total)
                                                            )
                                                        ) AS ordencompra_folionuevo
                                                    FROM
                                                        (
                                                            SELECT
                                                                COUNT(proyectoordencompra.id) AS ordenescompra_totales,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(proyectoordencompra.id)
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                ), 0) AS ordencompra_total,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_folio
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id ASC
                                                                    LIMIT 1
                                                                ), "VACIO") AS ordencompra_folio,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_revision
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 0) AS ordencompra_version,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_cancelado
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$proyecto_id.'
                                                                        AND proyectoordencompra.proveedor_id = '.$proveedor_id.'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 1) AS ordencompra_cancelado
                                                            FROM
                                                                proyectoordencompra
                                                            WHERE
                                                                DATE_FORMAT(proyectoordencompra.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")
                                                                AND proyectoordencompra.proyectoordencompra_revision = 0
                                                        ) AS TABLA');

                // Folio
                $folio_ordencompra = $ordenescompranueva[0]->ordencompra_folionuevo;

                // Orden compra
                $ordencompra = array(
                     'proyecto_id' => $proyecto_id
                    ,'proveedor_id' => $proveedor_id
                    ,'servicio_id' => $cotizacion_id
                    ,'proyectoordencompra_folio' => $ordenescompranueva[0]->ordencompra_folionuevo
                    ,'proyectoordencompra_revision' => $ordenescompranueva[0]->ordencompra_total
                    ,'proyectoordencompra_revisado' => 0
                    ,'proyectoordencompra_revisadonombre' => NULL
                    ,'proyectoordencompra_revisadofecha' => NULL
                    ,'proyectoordencompra_autorizado' => 0
                    ,'proyectoordencompra_autorizadonombre' => NULL
                    ,'proyectoordencompra_autorizadofecha' => NULL
                    ,'proyectoordencompra_observacionoc' => NULL
                    ,'proyectoordencompra_observacionrevision' => NULL
                    ,'proyectoordencompra_cancelado' => 0
                    ,'created_at' => date('Y-m-d H:m:s')
                    ,'updated_at' => date('Y-m-d H:m:s')
                );
            }


            if (($ordencompra_tipolista+0) == 0) // lista de proveedores
            {
                $servicios = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.proveedor_id,
                                                TABLA.catprueba_id,
                                                TABLA.proyectoproveedores_agente,
                                                TABLA.proyectoproveedores_puntos,
                                                TABLA.proyectoproveedores_observacion,
                                                (
                                                    SELECT
                                                        -- TABLA1.proveedor_id,
                                                        -- TABLA1.agente,
                                                        TABLA1.acreditacionAlcance_Norma
                                                        -- TABLA1.acreditacionAlcance_Descripcion
                                                    FROM
                                                        (
                                                            SELECT
                                                                acreditacionalcance.proveedor_id,
                                                                acreditacionalcance.id,
                                                                IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                                IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                                IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                                acreditacionalcance.updated_at
                                                            FROM
                                                                acreditacionalcance
                                                            WHERE
                                                                acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                        ) AS TABLA1
                                                    WHERE
                                                        TABLA1.proveedor_id = TABLA.proveedor_id 
                                                        AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                    ORDER BY
                                                        TABLA1.updated_at DESC
                                                    LIMIT 1
                                                ) AS normas 
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            0 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id != 15
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            1 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 15
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 0
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                        ORDER BY
                                                            proyectoproveedores.proyectoproveedores_agente ASC
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.proyectoproveedores_agente ASC');
            }
            else
            {
                $servicios = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.proveedor_id,
                                            TABLA.tipo,
                                            TABLA.catprueba_id,
                                            TABLA.proyectoproveedores_agente,
                                            TABLA.proyectopuntosreales_puntos AS proyectoproveedores_puntos,
                                            TABLA.proyectopuntosreales_observacion AS proyectoproveedores_observacion,
                                            (
                                                SELECT
                                                    -- TABLA1.proveedor_id,
                                                    -- TABLA1.agente,
                                                    TABLA1.acreditacionAlcance_Norma
                                                    -- TABLA1.acreditacionAlcance_Descripcion
                                                FROM
                                                    (
                                                        SELECT
                                                            acreditacionalcance.proveedor_id,
                                                            acreditacionalcance.id,
                                                            IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                            IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                            IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                            acreditacionalcance.updated_at
                                                        FROM
                                                            acreditacionalcance
                                                        WHERE
                                                            acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                    ) AS TABLA1
                                                WHERE
                                                    TABLA1.proveedor_id = TABLA.proveedor_id 
                                                    AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                ORDER BY
                                                    TABLA1.updated_at DESC
                                                LIMIT 1
                                            ) AS normas 
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        0 tipo,
                                                        proyectopuntosreales.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectopuntosreales.proyectopuntosreales_puntos,
                                                        proyectopuntosreales.proyectopuntosreales_observacion 
                                                    FROM
                                                        proyectopuntosreales
                                                        LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                    WHERE
                                                        proyectopuntosreales.proyecto_id = '.$proyecto_id.'  
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'  
                                                        AND proyectoproveedores.catprueba_id != 15
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        1 tipo,
                                                        proyectopuntosreales.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectopuntosreales.proyectopuntosreales_puntos,
                                                        proyectopuntosreales.proyectopuntosreales_observacion 
                                                    FROM
                                                        proyectopuntosreales
                                                        LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                    WHERE
                                                        proyectopuntosreales.proyecto_id = '.$proyecto_id.'  
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'  
                                                        AND proyectoproveedores.catprueba_id = 15
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        2 AS tipo,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectoproveedores.proyectoproveedores_puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion 
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                        AND proyectoproveedores.catprueba_id = 15
                                                        AND proyectoproveedores.proyectoproveedores_agente LIKE "%blanco%"
                                                        -- AND proyectoproveedores.proyectoproveedores_tipoadicional = 1
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        3 AS tipo,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectoproveedores.proyectoproveedores_puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion 
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proveedor_id = '.$proveedor_id.'
                                                        AND proyectoproveedores.catprueba_id = 0
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.tipo ASC,
                                            TABLA.proyectoproveedores_agente ASC');
            }


            // Formatear Filas servicios y consultar precios
            foreach ($servicios as $key => $value)
            {
                $precio = DB::select('SELECT
                                            servicio.proveedor_id,
                                            servicio.id,
                                            servicio.servicio_numerocotizacion,
                                            servicio.servicio_FechaCotizacion,
                                            servicio.servicio_VigenciaCotizacion,
                                            servicio.servicio_Eliminado,
                                            servicioprecios.agente_id,
                                            servicioprecios.agente_nombre,
                                            servicioprecios.agente_preciounitario AS preciounitario
                                        FROM
                                            servicioprecios
                                            LEFT JOIN servicio ON servicioprecios.servicio_id = servicio.id
                                        WHERE
                                            servicio.id = '.$cotizacion_id.'
                                            -- AND servicio.proveedor_id = '.$value->proveedor_id.'
                                            -- AND servicio.servicio_Eliminado = 0
                                            AND servicioprecios.agente_nombre LIKE "'.$value->proyectoproveedores_agente.'"
                                        ORDER BY
                                            servicio.servicio_VigenciaCotizacion DESC
                                        LIMIT 1');

                // si encontro el precio
                if (count($precio) > 0)
                {
                    // SUMA TOTAL
                    $value->totalcantidadxunitario = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);

                    // PRECIOS POR AGENTE
                    $value->preciounitario = round($precio[0]->preciounitario, 2);
                    $value->preciototal = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);
                }
                else
                {
                    // SUMA TOTAL
                    $value->totalcantidadxunitario = 0;

                    // PRECIOS POR AGENTE
                    $value->preciounitario = '-';
                    $value->preciototal = '-';
                }
            }

            // Mostrar vista PDF
            return \PDF::loadView('reportes.proyecto.reporteproyectoordencompra', compact('proyecto', 'proveedor', 'ordencompra', 'servicios'))->stream($folio_ordencompra.'.pdf');
        }
        catch(Exception $e)
        {
            // $dato["msj"] = 'Error '.$e->getMessage();
            // return response()->json($dato);
            return 'Error '.$e->getMessage();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $ordencompra_id
     * @return \Illuminate\Http\Response
    */
    public function proyectoordencomprafactura($ordencompra_id)
    {
        $ordencompra  = proyectoordencompraModel::findOrFail($ordencompra_id);
        return Storage::response($ordencompra->proyectoordencompra_facturadopdf);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if (($request['ordencompra_id']+0) == 0) //NUEVO
            {
                // Obtener folio nuevo
                $ordenescompranueva = DB::select('SELECT
                                                        TABLA.ordenescompra_totales,
                                                        TABLA.ordencompra_total,
                                                        TABLA.ordencompra_folio,
                                                        TABLA.ordencompra_version,
                                                        TABLA.ordencompra_cancelado,
                                                        (
                                                            IF(TABLA.ordencompra_total = 0,
                                                                (
                                                                    CASE
                                                                        WHEN (TABLA.ordenescompra_totales = 0) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 9) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-00", (TABLA.ordenescompra_totales + 1))
                                                                        WHEN ((TABLA.ordenescompra_totales + 1) < 99) THEN CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-0", (TABLA.ordenescompra_totales + 1))
                                                                        ELSE CONCAT("RIP-POEH-", DATE_FORMAT(CURDATE(), "%y"), "-", (TABLA.ordenescompra_totales + 1))
                                                                    END
                                                                )
                                                            ,
                                                                CONCAT(TABLA.ordencompra_folio, "-Rev", TABLA.ordencompra_total)
                                                            )
                                                        ) AS ordencompra_folionuevo
                                                    FROM
                                                        (
                                                            SELECT
                                                                COUNT(proyectoordencompra.id) AS ordenescompra_totales,
                                                                IFNULL((
                                                                    SELECT
                                                                        COUNT(proyectoordencompra.id)
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$request['proyecto_id'].'
                                                                        AND proyectoordencompra.proveedor_id = '.$request['proveedor_id'].'
                                                                ), 0) AS ordencompra_total,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_folio
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$request['proyecto_id'].'
                                                                        AND proyectoordencompra.proveedor_id = '.$request['proveedor_id'].'
                                                                    ORDER BY
                                                                        proyectoordencompra.id ASC
                                                                    LIMIT 1
                                                                ), "VACIO") AS ordencompra_folio,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_revision
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$request['proyecto_id'].'
                                                                        AND proyectoordencompra.proveedor_id = '.$request['proveedor_id'].'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 0) AS ordencompra_version,
                                                                IFNULL((
                                                                    SELECT
                                                                        proyectoordencompra.proyectoordencompra_cancelado
                                                                    FROM
                                                                        proyectoordencompra
                                                                    WHERE
                                                                        proyectoordencompra.proyecto_id = '.$request['proyecto_id'].'
                                                                        AND proyectoordencompra.proveedor_id = '.$request['proveedor_id'].'
                                                                    ORDER BY
                                                                        proyectoordencompra.id DESC
                                                                    LIMIT 1
                                                                ), 1) AS ordencompra_cancelado
                                                            FROM
                                                                proyectoordencompra
                                                            WHERE
                                                                DATE_FORMAT(proyectoordencompra.created_at, "%Y") = DATE_FORMAT(CURDATE(), "%Y")
                                                                AND proyectoordencompra.proyectoordencompra_revision = 0
                                                        ) AS TABLA');


                // Valida si viene revisado
                $revisado = 0; $revisadonombre = NULL; $revisadofecha = NULL;
                if ($request['checkbox_revisaoc'] != NULL)
                {
                    $revisado = 1;
                    $revisadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                    $revisadofecha = date('Y-m-d H:i:s');
                }


                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proyectoordencompra AUTO_INCREMENT=1');


                // Crear OT
                $ordencompra = proyectoordencompraModel::create([
                      'proyecto_id' => $request->proyecto_id
                    , 'proveedor_id' => $request->proveedor_id
                    , 'servicio_id' => $request->cotizacion_id
                    , 'proyectoordencompra_folio' => $ordenescompranueva[0]->ordencompra_folionuevo
                    , 'proyectoordencompra_revision' => $ordenescompranueva[0]->ordencompra_total
                    , 'proyectoordencompra_tipolista' => $request->proyectoordencompra_tipolista
                    , 'proyectoordencompra_revisado' => $revisado
                    , 'proyectoordencompra_revisadonombre' => $revisadonombre
                    , 'proyectoordencompra_revisadofecha' => $revisadofecha
                    , 'proyectoordencompra_autorizado' => 0
                    , 'proyectoordencompra_autorizadonombre' => NULL
                    , 'proyectoordencompra_autorizadofecha' => NULL
                    , 'proyectoordencompra_observacionoc' => $request->proyectoordencompra_observacionoc
                    , 'proyectoordencompra_cancelado' => 0
                    , 'proyectoordencompra_canceladonombre' => NULL
                    , 'proyectoordencompra_canceladofecha' => NULL
                    , 'proyectoordencompra_canceladoobservacion' => NULL
                    , 'proyectoordencompra_observacionrevision' => $request->proyectoordencompra_observacionrevision
                    , 'proyectoordencompra_facturado' => 0
                    , 'proyectoordencompra_facturadonombre' => NULL
                    , 'proyectoordencompra_facturadofecha' => NULL
                    , 'proyectoordencompra_facturadomonto' => 0
                    , 'proyectoordencompra_facturadopdf' => NULL
                ]);


                if (($request->proyectoordencompra_tipolista+0) == 0) // lista de proveedores
                {
                    $servicios = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.proveedor_id,
                                                TABLA.catprueba_id,
                                                TABLA.proyectoproveedores_agente,
                                                TABLA.proyectoproveedores_puntos,
                                                TABLA.proyectoproveedores_observacion,
                                                (
                                                    SELECT
                                                        -- TABLA1.proveedor_id,
                                                        -- TABLA1.agente,
                                                        TABLA1.acreditacionAlcance_Norma
                                                        -- TABLA1.acreditacionAlcance_Descripcion
                                                    FROM
                                                        (
                                                            SELECT
                                                                acreditacionalcance.proveedor_id,
                                                                acreditacionalcance.id,
                                                                IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                                IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                                IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                                acreditacionalcance.updated_at
                                                            FROM
                                                                acreditacionalcance
                                                            WHERE
                                                                acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                        ) AS TABLA1
                                                    WHERE
                                                        TABLA1.proveedor_id = TABLA.proveedor_id 
                                                        AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                    ORDER BY
                                                        TABLA1.updated_at DESC
                                                    LIMIT 1
                                                ) AS normas 
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            0 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id != 15
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            1 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 15
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 0
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                        ORDER BY
                                                            proyectoproveedores.proyectoproveedores_agente ASC
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.proyectoproveedores_agente ASC');
                }
                else
                {
                    $servicios = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.proveedor_id,
                                                TABLA.tipo,
                                                TABLA.catprueba_id,
                                                TABLA.proyectoproveedores_agente,
                                                TABLA.proyectopuntosreales_puntos AS proyectoproveedores_puntos,
                                                TABLA.proyectopuntosreales_observacion AS proyectoproveedores_observacion,
                                                (
                                                    SELECT
                                                        -- TABLA1.proveedor_id,
                                                        -- TABLA1.agente,
                                                        TABLA1.acreditacionAlcance_Norma
                                                        -- TABLA1.acreditacionAlcance_Descripcion
                                                    FROM
                                                        (
                                                            SELECT
                                                                acreditacionalcance.proveedor_id,
                                                                acreditacionalcance.id,
                                                                IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                                IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                                IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                                acreditacionalcance.updated_at
                                                            FROM
                                                                acreditacionalcance
                                                            WHERE
                                                                acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                        ) AS TABLA1
                                                    WHERE
                                                        TABLA1.proveedor_id = TABLA.proveedor_id 
                                                        AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                    ORDER BY
                                                        TABLA1.updated_at DESC
                                                    LIMIT 1
                                                ) AS normas 
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            0 tipo,
                                                            proyectopuntosreales.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectopuntosreales.proyectopuntosreales_puntos,
                                                            proyectopuntosreales.proyectopuntosreales_observacion 
                                                        FROM
                                                            proyectopuntosreales
                                                            LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                        WHERE
                                                            proyectopuntosreales.proyecto_id = '.$request->proyecto_id.'  
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'  
                                                            AND proyectoproveedores.catprueba_id != 15
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            1 tipo,
                                                            proyectopuntosreales.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectopuntosreales.proyectopuntosreales_puntos,
                                                            proyectopuntosreales.proyectopuntosreales_observacion 
                                                        FROM
                                                            proyectopuntosreales
                                                            LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                        WHERE
                                                            proyectopuntosreales.proyecto_id = '.$request->proyecto_id.'  
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'  
                                                            AND proyectoproveedores.catprueba_id = 15
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 15
                                                            AND proyectoproveedores.proyectoproveedores_agente LIKE "%blanco%"
                                                            -- AND proyectoproveedores.proyectoproveedores_tipoadicional = 1
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            3 AS tipo,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente,
                                                            proyectoproveedores.proyectoproveedores_puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion 
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                            AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 0
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                        ORDER BY
                                                            proyectoproveedores.proyectoproveedores_agente ASC
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.proyectoproveedores_agente ASC');
                }


                // Formatear Filas servicios
                foreach ($servicios as $key => $value)
                {
                    $precio = DB::select('SELECT
                                                servicio.proveedor_id,
                                                servicio.id,
                                                servicio.servicio_numerocotizacion,
                                                servicio.servicio_FechaCotizacion,
                                                servicio.servicio_VigenciaCotizacion,
                                                servicio.servicio_Eliminado,
                                                servicioprecios.agente_id,
                                                servicioprecios.agente_nombre,
                                                servicioprecios.agente_preciounitario AS preciounitario
                                            FROM
                                                servicioprecios
                                                LEFT JOIN servicio ON servicioprecios.servicio_id = servicio.id
                                            WHERE
                                                servicio.id = '.$request->cotizacion_id.'
                                                -- AND servicio.proveedor_id = '.$value->proveedor_id.'
                                                -- AND servicio.servicio_Eliminado = 0
                                                AND servicioprecios.agente_nombre LIKE "'.$value->proyectoproveedores_agente.'"
                                            ORDER BY
                                                servicio.servicio_VigenciaCotizacion DESC
                                            LIMIT 1');

                    $precionunitario = 0;
                    $preciototal = 0;

                    // si encontro el precio
                    if (count($precio) > 0)
                    {
                        $precionunitario = round($precio[0]->preciounitario, 2);
                        $preciototal = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);
                    }

                    // Guardar dato orden compra
                    $ordencompradatos = proyectoordencompradatosModel::create([
                         'proyectoordencompra_id' => $ordencompra->id
                        ,'proyectoordencompradatos_proveedorid' => $ordencompra->proveedor_id
                        ,'proyectoordencompradatos_agenteid' => $value->catprueba_id
                        ,'proyectoordencompradatos_agentenombre' => $value->proyectoproveedores_agente
                        ,'proyectoordencompradatos_agentepuntos' => $value->proyectoproveedores_puntos
                        ,'proyectoordencompradatos_preciounitario' => $precionunitario
                        ,'proyectoordencompradatos_importetotal' => $preciototal
                        ,'proyectoordencompradatos_agentenormas' => $value->normas
                        ,'proyectoordencompradatos_agenteobservacion' => $value->proyectoproveedores_observacion
                    ]);
                }
            }
            else
            {
                // Consulta OC
                $ordencompra = proyectoordencompraModel::findOrFail($request['ordencompra_id']);


                // Valida si viene REVISADO
                $revisado = 0; $revisadonombre = NULL; $revisadofecha = NULL;
                if (($ordencompra->proyectoordencompra_revisado + 0) == 1)
                {
                    if ($request['checkbox_revisaoc'] != NULL)
                    {
                        $revisado = 1;
                        $revisadonombre = $ordencompra->proyectoordencompra_revisadonombre;
                        $revisadofecha = $ordencompra->proyectoordencompra_revisadofecha;
                    }
                }
                else
                {
                    if ($request['checkbox_revisaoc'] != NULL)
                    {
                        $revisado = 1;
                        $revisadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $revisadofecha = date('Y-m-d H:i:s');
                    }
                }


                // Valida si viene AUTORIZADO
                $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                if (($ordencompra->proyectoordencompra_autorizado + 0) == 1)
                {
                    if ($request['checkbox_autorizaoc'] != NULL)
                    {
                        $autorizado = 1;
                        $autorizadonombre = $ordencompra->proyectoordencompra_autorizadonombre;
                        $autorizadofecha = $ordencompra->proyectoordencompra_autorizadofecha;
                    }
                }
                else
                {
                    if ($request['checkbox_autorizaoc'] != NULL)
                    {
                        $autorizado = 1;
                        $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $autorizadofecha = date('Y-m-d H:i:s');
                    }
                }


                // Valida si viene CANCELADO
                $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                if (($ordencompra->proyectoordencompra_cancelado + 0) == 1)
                {
                    if ($request['checkbox_cancelaoc'] != NULL)
                    {
                        $cancelado = 1;
                        $canceladonombre = $ordencompra->proyectoordencompra_canceladonombre;
                        $canceladofecha = $ordencompra->proyectoordencompra_canceladofecha;
                        $canceladoobservacion = $ordencompra->proyectoordencompra_canceladoobservacion;
                    }
                }
                else
                {
                    if ($request['checkbox_cancelaoc'] != NULL)
                    {
                        $cancelado = 1;
                        $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $canceladofecha = date('Y-m-d H:i:s');
                        $canceladoobservacion = $request['proyectoordencompra_canceladoobservacion'];
                    }
                }


                // Valida si viene FACTURADO
                $facturado = 0; $facturadonombre = NULL; $facturadofecha = NULL; $facturadomonto = NULL; $facturadopdf = NULL;
                if (($ordencompra->proyectoordencompra_facturado + 0) == 1)
                {
                    if ($request->file('facturadopdf'))
                    {
                        $facturado = 1;
                        $facturadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $facturadofecha = date('Y-m-d H:i:s');
                        $facturadomonto = $request['proyectoordencompra_facturadomonto'];

                        // PDF
                        $extension = $request->file('facturadopdf')->getClientOriginalExtension(); //Extención
                        $facturadopdf = $request->file('facturadopdf')->storeAs('proyecto/'.$request->proyecto_id.'/facturas', 'Factura - OC ('.$ordencompra->proyectoordencompra_folio.').'.$extension); //Guardar PDF
                    }
                    else if ($request['checkbox_facturaoc'] != NULL)
                    {
                        $facturado = 1;
                        $facturadonombre = $ordencompra->proyectoordencompra_facturadonombre;
                        $facturadofecha = $ordencompra->proyectoordencompra_facturadofecha;
                        $facturadomonto = $ordencompra->proyectoordencompra_facturadomonto;
                        $facturadopdf = $ordencompra->proyectoordencompra_facturadopdf;
                    }
                    else
                    {
                        if (auth()->user()->hasRoles(['Superusuario', 'Financiero']))
                        {
                            $facturado = 0;
                            $facturadonombre = NULL;
                            $facturadofecha = NULL;
                            $facturadomonto = NULL;
                            $facturadopdf = NULL;


                            // Eliminar factura PDF
                            if (Storage::exists($ordencompra->proyectoordencompra_facturadopdf))
                            {
                                Storage::delete($ordencompra->proyectoordencompra_facturadopdf);
                            }
                        }
                        else
                        {
                            $facturado = 1;
                            $facturadonombre = $ordencompra->proyectoordencompra_facturadonombre;
                            $facturadofecha = $ordencompra->proyectoordencompra_facturadofecha;
                            $facturadomonto = $ordencompra->proyectoordencompra_facturadomonto;
                            $facturadopdf = $ordencompra->proyectoordencompra_facturadopdf;
                        }
                    }
                }
                else
                {
                    if ($request->file('facturadopdf'))
                    {
                        $facturado = 1;
                        $facturadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $facturadofecha = date('Y-m-d H:i:s');
                        $facturadomonto = $request['proyectoordencompra_facturadomonto'];

                        // PDF
                        $extension = $request->file('facturadopdf')->getClientOriginalExtension(); //Extención
                        $facturadopdf = $request->file('facturadopdf')->storeAs('proyecto/'.$request->proyecto_id.'/facturas', 'Factura - OC ('.$ordencompra->proyectoordencompra_folio.').'.$extension); //Guardar PDF
                    }
                }


                // Modificar
                $ordencompra->update([
                      'servicio_id' => $request->cotizacion_id
                    , 'proyectoordencompra_tipolista' => $request->proyectoordencompra_tipolista
                    , 'proyectoordencompra_revisado' => $revisado
                    , 'proyectoordencompra_revisadonombre' => $revisadonombre
                    , 'proyectoordencompra_revisadofecha' => $revisadofecha
                    , 'proyectoordencompra_autorizado' => $autorizado
                    , 'proyectoordencompra_autorizadonombre' => $autorizadonombre
                    , 'proyectoordencompra_autorizadofecha' => $autorizadofecha
                    , 'proyectoordencompra_observacionoc' => $request['proyectoordencompra_observacionoc']
                    , 'proyectoordencompra_cancelado' => $cancelado
                    , 'proyectoordencompra_canceladonombre' => $canceladonombre
                    , 'proyectoordencompra_canceladofecha' => $canceladofecha
                    , 'proyectoordencompra_canceladoobservacion' => $canceladoobservacion
                    , 'proyectoordencompra_observacionrevision' => $request['proyectoordencompra_observacionrevision']
                    , 'proyectoordencompra_facturado' => $facturado
                    , 'proyectoordencompra_facturadonombre' => $facturadonombre
                    , 'proyectoordencompra_facturadofecha' => $facturadofecha
                    , 'proyectoordencompra_facturadomonto' => $facturadomonto
                    , 'proyectoordencompra_facturadopdf' => $facturadopdf
                ]);


                if (($request->ordencompra_actualizaoc+0) == 1) // Si preciono actualizar lista de parametros
                {
                    // Eliminar datos anteriores de la OT
                    $agenteseliminar = proyectoordencompradatosModel::where('proyectoordencompra_id', $request['ordencompra_id'])->delete();

                    
                    if (($request->proyectoordencompra_tipolista+0) == 0) // lista de proveedores
                    {
                        $servicios = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.proveedor_id,
                                                    TABLA.catprueba_id,
                                                    TABLA.proyectoproveedores_agente,
                                                    TABLA.proyectoproveedores_puntos,
                                                    TABLA.proyectoproveedores_observacion,
                                                    (
                                                        SELECT
                                                            -- TABLA1.proveedor_id,
                                                            -- TABLA1.agente,
                                                            TABLA1.acreditacionAlcance_Norma
                                                            -- TABLA1.acreditacionAlcance_Descripcion
                                                        FROM
                                                            (
                                                                SELECT
                                                                    acreditacionalcance.proveedor_id,
                                                                    acreditacionalcance.id,
                                                                    IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                                    IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                                    IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                                    acreditacionalcance.updated_at
                                                                FROM
                                                                    acreditacionalcance
                                                                WHERE
                                                                    acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                            ) AS TABLA1
                                                        WHERE
                                                            TABLA1.proveedor_id = TABLA.proveedor_id 
                                                            AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                        ORDER BY
                                                            TABLA1.updated_at DESC
                                                        LIMIT 1
                                                    ) AS normas 
                                                FROM
                                                    (
                                                        (
                                                            SELECT
                                                                0 AS tipo,
                                                                proyectoproveedores.proyecto_id,
                                                                proyectoproveedores.proveedor_id,
                                                                proyectoproveedores.catprueba_id,
                                                                proyectoproveedores.proyectoproveedores_agente,
                                                                proyectoproveedores.proyectoproveedores_puntos,
                                                                proyectoproveedores.proyectoproveedores_observacion 
                                                            FROM
                                                                proyectoproveedores
                                                            WHERE
                                                                proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                                AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                                AND proyectoproveedores.catprueba_id != 15
                                                                AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                1 AS tipo,
                                                                proyectoproveedores.proyecto_id,
                                                                proyectoproveedores.proveedor_id,
                                                                proyectoproveedores.catprueba_id,
                                                                proyectoproveedores.proyectoproveedores_agente,
                                                                proyectoproveedores.proyectoproveedores_puntos,
                                                                proyectoproveedores.proyectoproveedores_observacion 
                                                            FROM
                                                                proyectoproveedores
                                                            WHERE
                                                                proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                                AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                                AND proyectoproveedores.catprueba_id = 15
                                                                AND proyectoproveedores.proyectoproveedores_tipoadicional <= 1
                                                        )
                                                        UNION ALL
                                                        (
                                                            SELECT
                                                                2 AS tipo,
                                                                proyectoproveedores.proyecto_id,
                                                                proyectoproveedores.proveedor_id,
                                                                proyectoproveedores.catprueba_id,
                                                                proyectoproveedores.proyectoproveedores_agente,
                                                                proyectoproveedores.proyectoproveedores_puntos,
                                                                proyectoproveedores.proyectoproveedores_observacion 
                                                            FROM
                                                                proyectoproveedores
                                                            WHERE
                                                                proyectoproveedores.proyecto_id = '.$request->proyecto_id.'
                                                                AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.'
                                                                AND proyectoproveedores.catprueba_id = 0
                                                                AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                            ORDER BY
                                                                proyectoproveedores.proyectoproveedores_agente ASC
                                                        )
                                                    ) AS TABLA
                                                ORDER BY
                                                    TABLA.tipo ASC,
                                                    TABLA.proyectoproveedores_agente ASC');
                    }
                    else
                    {
                        $servicios = DB::select('SELECT
                                                        TABLA.proyecto_id,
                                                        TABLA.proveedor_id,
                                                        TABLA.tipo,
                                                        TABLA.catprueba_id,
                                                        TABLA.proyectoproveedores_agente,
                                                        TABLA.proyectopuntosreales_puntos AS proyectoproveedores_puntos,
                                                        TABLA.proyectopuntosreales_observacion AS proyectoproveedores_observacion,
                                                        (
                                                            SELECT
                                                                -- TABLA1.proveedor_id,
                                                                -- TABLA1.agente,
                                                                TABLA1.acreditacionAlcance_Norma
                                                                -- TABLA1.acreditacionAlcance_Descripcion
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        acreditacionalcance.proveedor_id,
                                                                        acreditacionalcance.id,
                                                                        IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")")) AS agente,
                                                                        IF(acreditacionalcance.acreditacionAlcance_Norma = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Norma) AS acreditacionAlcance_Norma,
                                                                        IF(acreditacionalcance.acreditacionAlcance_Descripcion = "N/A", NULL, acreditacionalcance.acreditacionAlcance_Descripcion) AS acreditacionAlcance_Descripcion,
                                                                        acreditacionalcance.updated_at
                                                                    FROM
                                                                        acreditacionalcance
                                                                    WHERE
                                                                        acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                                ) AS TABLA1
                                                            WHERE
                                                                TABLA1.proveedor_id = TABLA.proveedor_id 
                                                                AND TABLA1.agente = TABLA.proyectoproveedores_agente
                                                            ORDER BY
                                                                TABLA1.updated_at DESC
                                                            LIMIT 1
                                                        ) AS normas 
                                                    FROM
                                                        (
                                                            (
                                                                SELECT
                                                                    0 tipo,
                                                                    proyectopuntosreales.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proyectoproveedores.catprueba_id,
                                                                    proyectoproveedores.proyectoproveedores_agente,
                                                                    proyectopuntosreales.proyectopuntosreales_puntos,
                                                                    proyectopuntosreales.proyectopuntosreales_observacion 
                                                                FROM
                                                                    proyectopuntosreales
                                                                    LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                                WHERE
                                                                    proyectopuntosreales.proyecto_id = '.$request->proyecto_id.' 
                                                                    AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.' 
                                                                    AND proyectoproveedores.catprueba_id != 15
                                                            )
                                                            UNION ALL
                                                            (
                                                                SELECT
                                                                    1 tipo,
                                                                    proyectopuntosreales.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proyectoproveedores.catprueba_id,
                                                                    proyectoproveedores.proyectoproveedores_agente,
                                                                    proyectopuntosreales.proyectopuntosreales_puntos,
                                                                    proyectopuntosreales.proyectopuntosreales_observacion 
                                                                FROM
                                                                    proyectopuntosreales
                                                                    LEFT JOIN proyectoproveedores ON proyectopuntosreales.proyectoproveedores_id = proyectoproveedores.id 
                                                                WHERE
                                                                    proyectopuntosreales.proyecto_id = '.$request->proyecto_id.' 
                                                                    AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.' 
                                                                    AND proyectoproveedores.catprueba_id = 15
                                                            )
                                                            UNION ALL
                                                            (
                                                                SELECT
                                                                    2 AS tipo,
                                                                    proyectoproveedores.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proyectoproveedores.catprueba_id,
                                                                    proyectoproveedores.proyectoproveedores_agente,
                                                                    proyectoproveedores.proyectoproveedores_puntos,
                                                                    proyectoproveedores.proyectoproveedores_observacion 
                                                                FROM
                                                                    proyectoproveedores
                                                                WHERE
                                                                    proyectoproveedores.proyecto_id = '.$request->proyecto_id.' 
                                                                    AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.' 
                                                                    AND proyectoproveedores.catprueba_id = 15
                                                                    AND proyectoproveedores.proyectoproveedores_agente LIKE "%blanco%"
                                                                    -- AND proyectoproveedores.proyectoproveedores_tipoadicional = 1
                                                            )
                                                            UNION ALL
                                                            (
                                                                SELECT
                                                                    3 AS tipo,
                                                                    proyectoproveedores.proyecto_id,
                                                                    proyectoproveedores.proveedor_id,
                                                                    proyectoproveedores.catprueba_id,
                                                                    proyectoproveedores.proyectoproveedores_agente,
                                                                    proyectoproveedores.proyectoproveedores_puntos,
                                                                    proyectoproveedores.proyectoproveedores_observacion 
                                                                FROM
                                                                    proyectoproveedores
                                                                WHERE
                                                                    proyectoproveedores.proyecto_id = '.$request->proyecto_id.' 
                                                                    AND proyectoproveedores.proveedor_id = '.$request->proveedor_id.' 
                                                                    AND proyectoproveedores.catprueba_id = 0
                                                                    AND proyectoproveedores.proyectoproveedores_tipoadicional = 2
                                                                ORDER BY
                                                                    proyectoproveedores.proyectoproveedores_agente ASC
                                                            )
                                                        ) AS TABLA
                                                    ORDER BY
                                                        TABLA.tipo ASC,
                                                        TABLA.proyectoproveedores_agente ASC');
                    }


                    // Formatear Filas servicios
                    foreach ($servicios as $key => $value)
                    {
                        $precio = DB::select('SELECT
                                                    servicio.proveedor_id,
                                                    servicio.id,
                                                    servicio.servicio_numerocotizacion,
                                                    servicio.servicio_FechaCotizacion,
                                                    servicio.servicio_VigenciaCotizacion,
                                                    servicio.servicio_Eliminado,
                                                    servicioprecios.agente_id,
                                                    servicioprecios.agente_nombre,
                                                    servicioprecios.agente_preciounitario AS preciounitario
                                                FROM
                                                    servicioprecios
                                                    LEFT JOIN servicio ON servicioprecios.servicio_id = servicio.id
                                                WHERE
                                                    servicio.id = '.$request->cotizacion_id.'
                                                    -- AND servicio.proveedor_id = '.$value->proveedor_id.'
                                                    -- AND servicio.servicio_Eliminado = 0
                                                    AND servicioprecios.agente_nombre LIKE "'.$value->proyectoproveedores_agente.'"
                                                ORDER BY
                                                    servicio.servicio_VigenciaCotizacion DESC
                                                LIMIT 1');

                        $precionunitario = 0;
                        $preciototal = 0;

                        // si encontro el precio
                        if (count($precio) > 0)
                        {
                            $precionunitario = round($precio[0]->preciounitario, 2);
                            $preciototal = round(($value->proyectoproveedores_puntos * $precio[0]->preciounitario), 2);
                        }

                        // Guardar dato orden compra
                        $ordencompradatos = proyectoordencompradatosModel::create([
                             'proyectoordencompra_id' => $ordencompra->id
                            ,'proyectoordencompradatos_proveedorid' => $ordencompra->proveedor_id
                            ,'proyectoordencompradatos_agenteid' => $value->catprueba_id
                            ,'proyectoordencompradatos_agentenombre' => $value->proyectoproveedores_agente
                            ,'proyectoordencompradatos_agentepuntos' => $value->proyectoproveedores_puntos
                            ,'proyectoordencompradatos_preciounitario' => $precionunitario
                            ,'proyectoordencompradatos_importetotal' => $preciototal
                            ,'proyectoordencompradatos_agentenormas' => $value->normas
                            ,'proyectoordencompradatos_agenteobservacion' => $value->proyectoproveedores_observacion
                        ]);
                    }
                }
            }


            $dato["activo"] = 0;
            if (auth()->user()->hasRoles(['Superusuario', 'Financiero']))
            {
                $dato["activo"] = 1;
            }


            // respuesta
            $dato["ordencompra"] = $ordencompra;
            $dato["msj"] = 'Información guardada correctamente';
            return response()->json($dato);
        }
        catch(\Exception $e)
        {
            $dato["msj"] = 'Error: '.$e->getMessage();
            $dato['ordencompra'] = 0;
            return response()->json($dato);
        }
    }
}
