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
use App\modelos\proyecto\proyectoordentrabajoModel;
use App\modelos\proyecto\proyectoordentrabajodatosModel;
use App\modelos\proyecto\proyectoModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoordentrabajoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('roles:Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Usuario,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Compras');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
    */
    public function proyectoordentrabajotabla($proyecto_id)
    {
        try
        {
            $ordenestrabajo = DB::select('SELECT
                                            proyectoordentrabajo.proyecto_id,
                                            proyectoordentrabajo.id,
                                            proyectoordentrabajo.proyectoordentrabajo_folio,
                                            proyectoordentrabajo.proyectoordentrabajo_revision,
                                            proyectoordentrabajo.proyectoordentrabajo_autorizado,
                                            proyectoordentrabajo.proyectoordentrabajo_autorizadonombre,
                                            proyectoordentrabajo.proyectoordentrabajo_autorizadofecha,
                                            proyectoordentrabajo.proyectoordentrabajo_observacionot,
                                            proyectoordentrabajo.proyectoordentrabajo_cancelado,
                                            proyectoordentrabajo.proyectoordentrabajo_canceladonombre,
                                            proyectoordentrabajo.proyectoordentrabajo_canceladofecha,
                                            proyectoordentrabajo.proyectoordentrabajo_canceladoobservacion,
                                            proyectoordentrabajo.proyectoordentrabajo_observacionrevision,
                                            proyectoordentrabajo.created_at,
                                            proyectoordentrabajo.updated_at  
                                        FROM
                                            proyectoordentrabajo
                                        WHERE
                                            proyectoordentrabajo.proyecto_id = '.$proyecto_id.'
                                        ORDER BY
                                            proyectoordentrabajo.id DESC');


            $fila = ''; $autorizado = ''; $cancelado = ''; $estado = '';

            $numero_registro = count($ordenestrabajo);
            
            foreach ($ordenestrabajo as $key => $value)
            {
                $value->numero_registro = $numero_registro;
                $numero_registro -= 1;


                $value->folio = '<b style="color: #000000;">'.$value->proyectoordentrabajo_folio.'</b><br>'.$value->created_at;


                // Diseño Autorizado
                if (($value->proyectoordentrabajo_autorizado+0) == 1)
                {
                    $value->autorizado = $value->proyectoordentrabajo_autorizadonombre.'<br>'.$value->proyectoordentrabajo_autorizadofecha;
                }
                else
                {
                    $value->autorizado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }


                // Diseño estado
                if (($value->proyectoordentrabajo_cancelado+0) == 1)
                {
                    $value->cancelado = $value->proyectoordentrabajo_canceladonombre.'<br>'.$value->proyectoordentrabajo_canceladofecha;
                    $value->estado = '<b class="text-danger">Cancelado</b>';
                }
                else
                {
                    $value->cancelado = 'N/A';
                    $value->estado = '<b class="text-info">Vigente</b>';
                }


                // BOTON
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle boton_mostrar" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';


                // // Dibujar filas tabla
                // $fila.='<tr>
                //             <td>'.$numero_registro.'</td>
                //             <td><b style="color: #000000;">'.$value->proyectoordentrabajo_folio.'</b><br>'.$value->created_at.'</td>
                //             <td>'.$autorizado.'</td>
                //             <td>'.$cancelado.'</td>
                //             <td>'.$estado.'</td>
                //             <td><button type="button" class="btn btn-info btn-circle" onclick="mostrar_ot('.$proyecto_id.', \''.$value->proyectoordentrabajo_folio.'\', '.$value->id.', '.$value->proyectoordentrabajo_revision.', '.$value->proyectoordentrabajo_autorizado.', \''.$value->proyectoordentrabajo_autorizadonombre.'\', \''.$value->proyectoordentrabajo_autorizadofecha.'\', null, '.$value->proyectoordentrabajo_cancelado.', \''.$value->proyectoordentrabajo_canceladonombre.'\', \''.$value->proyectoordentrabajo_canceladofecha.'\', null, null);"><i class="fa fa-eye"></i></button></td>
                //         </tr>';
            }

            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            $dato['data'] = $ordenestrabajo;
            // $dato['total'] = count($ordenestrabajo);
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
     * @param  int  $ordentrabajo_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordentrabajodatos($ordentrabajo_id)
    {
        try
        {
            $ot = DB::select('SELECT
                                    proyectoordentrabajo.id,
                                    proyectoordentrabajo.proyecto_id,
                                    proyectoordentrabajo.proyectoordentrabajo_folio,
                                    proyectoordentrabajo.proyectoordentrabajo_revision,
                                    proyectoordentrabajo.proyectoordentrabajo_autorizado,
                                    proyectoordentrabajo.proyectoordentrabajo_autorizadonombre,
                                    proyectoordentrabajo.proyectoordentrabajo_autorizadofecha,
                                    proyectoordentrabajo.proyectoordentrabajo_observacionot,
                                    proyectoordentrabajo.proyectoordentrabajo_cancelado,
                                    proyectoordentrabajo.proyectoordentrabajo_canceladonombre,
                                    proyectoordentrabajo.proyectoordentrabajo_canceladofecha,
                                    proyectoordentrabajo.proyectoordentrabajo_canceladoobservacion,
                                    proyectoordentrabajo.proyectoordentrabajo_observacionrevision,
                                    proyectoordentrabajo.created_at,
                                    proyectoordentrabajo.updated_at 
                                FROM
                                    proyectoordentrabajo
                                WHERE
                                    proyectoordentrabajo.id = '.$ordentrabajo_id);

            // Mensaje
            $dato["ordentrabajo"] = $ot;

            // respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Mensaje
            $dato["ordentrabajo"] = 0;
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
    public function proyectoordentrabajocrear($proyecto_id)
    {
        try
        {
            // Consulta cantidad de OT en este proyecto
            $totalordentrabajo = DB::select('SELECT
                                                    COUNT(proyectoordentrabajo.id) AS ordentrabajoversion,
                                                    IFNULL((
                                                        SELECT
                                                            TABLA.proyectoordentrabajo_cancelado
                                                        FROM
                                                            proyectoordentrabajo AS TABLA
                                                        WHERE
                                                            TABLA.id = MAX(proyectoordentrabajo.id)
                                                    ), 1) AS ultimaot_cancelado
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = '.$proyecto_id);

            // Valida si la ultima OT creada ha sido cancelada, para poder crear una nueva version
            if (($totalordentrabajo[0]->ultimaot_cancelado + 0) == 1)
            {
                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($proyecto_id);
                // $proyecto->fecha_hoy_corto = date('Y-m-d');
                // $proyecto->fecha_hoy_largo = date('Y-m-d H:i:s');

                // Obtener folio proyecto
                $proyecto_folio = explode("-", $proyecto->proyecto_folio);

                // Creamos folio OT
                if (($totalordentrabajo[0]->ordentrabajoversion + 0) == 0) // OT original - revision 0
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
                }
                else
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2].'-Rev'.$totalordentrabajo[0]->ordentrabajoversion;
                }

                // AUTO_INCREMENT
                // DB::statement('ALTER TABLE proyectoordentrabajo AUTO_INCREMENT=1');

                // Crear OT
                // $ordentrabajo = proyectoordentrabajoModel::create([
                //       'proyecto_id' => $proyecto_id
                //     , 'proyectoordentrabajo_folio' => $proyecto->folio_ot
                //     , 'proyectoordentrabajo_revision' => $totalordentrabajo[0]->ordentrabajoversion //Numero de revisión
                //     , 'proyectoordentrabajo_autorizado' => 0
                //     , 'proyectoordentrabajo_cancelado' => 0
                // ]);

                // Consulta los servicios de la OT [AGENTES A EVALUAR]
                DB::statement("SET lc_time_names = 'es_MX';");
                $agentes = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.proveedor_id,
                                            TABLA.tipo,
                                            TABLA.catprueba_id,
                                            TABLA.agente,
                                            TABLA.puntos,
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
                                                    AND TABLA1.agente = TABLA.agente
                                                ORDER BY
                                                    TABLA1.updated_at DESC
                                                LIMIT 1
                                            ) AS normas,
                                            TABLA.observacion
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        1 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id != 15
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        0 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id = 15
                                                        AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.tipo DESC,
                                            TABLA.agente ASC');

                // Guardar datos de esta orden de trabajo
                // $total_agentesguardados = 0;
                // foreach ($agentes as $key => $value) 
                // {
                //     $agentescliente = proyectoordentrabajodatosModel::create([
                //           'proyectoordentrabajo_id' => $ordentrabajo->id
                //         , 'proyectoordentrabajodatos_proveedorid' => $value->proveedor_id
                //         , 'proyectoordentrabajodatos_agenteid' => $value->catprueba_id
                //         , 'proyectoordentrabajodatos_agentenombre' => $value->agente
                //         , 'proyectoordentrabajodatos_agentepuntos' => $value->puntos
                //         , 'proyectoordentrabajodatos_agentenormas' => $value->normas
                //         , 'proyectoordentrabajodatos_agenteobservacion' => $value->observacion
                //     ]);

                //     $total_agentesguardados += 1;
                // }

                // Validar si hay servicios
                if (count($agentes) > 0)
                {
                    // Mensaje
                    $dato["ordentrabajo_id"] = 1;
                    $dato["ordentrabajo_folio"] = $proyecto->folio_ot;
                    $dato["proyectoordentrabajo_revision"] = $totalordentrabajo[0]->ordentrabajoversion;
                    $dato["msj"] = 'Vista previa de la nueva orden de trabajo';

                    // return \PDF::loadView('reportes.proyecto.reporteproyectoordentrabajo', compact('proyecto', 'ordentrabajo', 'servicios'))->stream($ordentrabajo->proyectoordentrabajo_folio.'.pdf');
                }
                else
                {
                    // Eliminar OT creada
                    // $eliminarot = proyectoordentrabajoModel::where('id', $ordentrabajo->id)->delete();

                    // Mensaje
                    $dato["ordentrabajo_id"] = 0;
                    $dato["msj"] = 'No se encontraron (Agentes / Factores de riesgo / Servicios)';
                }
            }
            else
            {
                // Mensaje
                $dato["ordentrabajo_id"] = 0;
                $dato["msj"] = 'Debe cancelar la Orden de trabajo mas reciente';
            }

            // Retornar respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Mensaje
            $dato["ordentrabajo_id"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $ordentrabajo_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordentrabajoactualizar($proyecto_id, $ordentrabajo_id)
    {
        try
        {
            // Datos del proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Datos del Orden trabajo
            $ordentrabajo = proyectoordentrabajoModel::findOrFail($ordentrabajo_id);
            $ordentrabajo->fecha_creacion_corto = date('Y-m-d', strtotime($ordentrabajo->created_at));
            $ordentrabajo->fecha_creacion_largo = $ordentrabajo->created_at;
            $proyecto->folio_ot = $ordentrabajo->proyectoordentrabajo_folio;

            // Consulta los servicios de la OT [AGENTES A EVALUAR]
            DB::statement("SET lc_time_names = 'es_MX';");
            $servicios = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        0 AS proyectoordentrabajo_id,
                                        TABLA.proveedor_id AS proyectoordentrabajodatos_proveedorid,
                                        TABLA.tipo,
                                        TABLA.catprueba_id AS proyectoordentrabajodatos_agenteid,
                                        TABLA.agente AS proyectoordentrabajodatos_agentenombre,
                                        TABLA.puntos AS proyectoordentrabajodatos_agentepuntos,
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
                                                AND TABLA1.agente = TABLA.agente
                                            ORDER BY
                                                TABLA1.updated_at DESC
                                            LIMIT 1
                                        ) AS proyectoordentrabajodatos_agentenormas,
                                        TABLA.observacion AS proyectoordentrabajodatos_agenteobservacion
                                    FROM
                                        (
                                            (
                                                SELECT
                                                    1 AS tipo,
                                                    proyectoproveedores.proveedor_id,
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.catprueba_id,
                                                    proyectoproveedores.proyectoproveedores_agente AS agente,
                                                    proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                    proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                    AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                    AND proyectoproveedores.catprueba_id != 15
                                                ORDER BY
                                                    proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                    proyectoproveedores.proyectoproveedores_agente ASC
                                            )
                                            UNION ALL
                                            (
                                                SELECT
                                                    0 AS tipo,
                                                    proyectoproveedores.proveedor_id,
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.catprueba_id,
                                                    proyectoproveedores.proyectoproveedores_agente AS agente,
                                                    proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                    proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                    AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                    AND proyectoproveedores.catprueba_id = 15
                                                    AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                ORDER BY
                                                    proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                    proyectoproveedores.proyectoproveedores_agente ASC
                                            )
                                        ) AS TABLA
                                    ORDER BY
                                        TABLA.tipo DESC,
                                        TABLA.agente ASC');

            // Mostrar PDF
            return \PDF::loadView('reportes.proyecto.reporteproyectoordentrabajo', compact('proyecto', 'ordentrabajo', 'servicios'))->stream($proyecto->folio_ot.'.pdf');
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
     * @param  int  $proyecto_id
     * @param  int  $ordentrabajo_id
     * @param  int  $ordentrabajo_descargar
     * @return \Illuminate\Http\Response
     */
    public function proyectoordentrabajoconsultar($proyecto_id, $ordentrabajo_id, $ordentrabajo_descargar)
    {
        try
        {
            if (($ordentrabajo_id+0) > 0) //Orden de trabajo [EXISTENTE]
            {
                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($proyecto_id);

                // Datos del Orden trabajo
                $ordentrabajo = proyectoordentrabajoModel::findOrFail($ordentrabajo_id);
                $ordentrabajo->fecha_creacion_corto = date('Y-m-d', strtotime($ordentrabajo->created_at));
                $ordentrabajo->fecha_creacion_largo = $ordentrabajo->created_at;
                $proyecto->folio_ot = $ordentrabajo->proyectoordentrabajo_folio;

                // Obtenemos la lista de agentes [servicios] para la OT
                $servicios = DB::select('SELECT
                                                TABLA.proyectoordentrabajo_id,
                                                TABLA.proyectoordentrabajodatos_proveedorid,
                                                TABLA.proyectoordentrabajodatos_agenteid,
                                                TABLA.proyectoordentrabajodatos_agentenombre,
                                                TABLA.proyectoordentrabajodatos_agentepuntos,
                                                TABLA.proyectoordentrabajodatos_agentenormas,
                                                TABLA.proyectoordentrabajodatos_agenteobservacion 
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            proyectoordentrabajodatos.proyectoordentrabajo_id,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_proveedorid,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentepuntos,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenormas,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agenteobservacion 
                                                        FROM
                                                            proyectoordentrabajodatos 
                                                        WHERE
                                                            proyectoordentrabajodatos.proyectoordentrabajo_id = '.$ordentrabajo_id.'
                                                            AND proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid != 15
                                                        ORDER BY
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre ASC
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            proyectoordentrabajodatos.proyectoordentrabajo_id,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_proveedorid,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentepuntos,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenormas,
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agenteobservacion 
                                                        FROM
                                                            proyectoordentrabajodatos 
                                                        WHERE
                                                            proyectoordentrabajodatos.proyectoordentrabajo_id = '.$ordentrabajo_id.'
                                                            AND proyectoordentrabajodatos.proyectoordentrabajodatos_agenteid = 15
                                                        ORDER BY
                                                            proyectoordentrabajodatos.proyectoordentrabajodatos_agentenombre ASC
                                                    )
                                                ) AS TABLA');
            }
            else //Orden de trabajo [VISTA PREVIA SIN SER CREADA AUN]
            {
                // Consulta cantidad de OT en este proyecto
                $totalordentrabajo = DB::select('SELECT
                                                        COUNT(proyectoordentrabajo.id) AS ordentrabajoversion,
                                                        IFNULL((
                                                            SELECT
                                                                TABLA.proyectoordentrabajo_cancelado
                                                            FROM
                                                                proyectoordentrabajo AS TABLA
                                                            WHERE
                                                                TABLA.id = MAX(proyectoordentrabajo.id)
                                                        ), 1) AS ultimaot_cancelado
                                                    FROM
                                                        proyectoordentrabajo
                                                    WHERE
                                                        proyectoordentrabajo.proyecto_id = '.$proyecto_id);

                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($proyecto_id);

                // Obtener folio proyecto
                $proyecto_folio = explode("-", $proyecto->proyecto_folio);

                // Creamos folio OT
                if (($totalordentrabajo[0]->ordentrabajoversion + 0) == 0) // OT original - revision 0
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
                }
                else
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2].'-Rev'.$totalordentrabajo[0]->ordentrabajoversion;
                }

                // Datos del Orden trabajo
                $ordentrabajo = array(
                                     'proyecto_id' => $proyecto_id
                                    ,'proyectoordentrabajo_folio' => $proyecto->folio_ot
                                    ,'proyectoordentrabajo_revision' => ($totalordentrabajo[0]->ordentrabajoversion + 0)
                                    ,'proyectoordentrabajo_autorizado' => 0
                                    ,'proyectoordentrabajo_autorizadonombre' => NULL
                                    ,'proyectoordentrabajo_autorizadofecha' => NULL
                                    ,'proyectoordentrabajo_observacionot' => NULL
                                    ,'proyectoordentrabajo_observacionrevision' => NULL
                                    ,'proyectoordentrabajo_cancelado' => 0
                                    ,'fecha_creacion_corto' => date('Y-m-d')
                                    ,'fecha_creacion_largo' => date('Y-m-d H:m:s')
                                );

                // Consulta los servicios de la OT [AGENTES A EVALUAR]
                DB::statement("SET lc_time_names = 'es_MX';");
                $servicios = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            0 AS proyectoordentrabajo_id,
                                            TABLA.proveedor_id AS proyectoordentrabajodatos_proveedorid,
                                            TABLA.tipo,
                                            TABLA.catprueba_id AS proyectoordentrabajodatos_agenteid,
                                            TABLA.agente AS proyectoordentrabajodatos_agentenombre,
                                            TABLA.puntos AS proyectoordentrabajodatos_agentepuntos,
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
                                                    AND TABLA1.agente = TABLA.agente
                                                ORDER BY
                                                    TABLA1.updated_at DESC
                                                LIMIT 1
                                            ) AS proyectoordentrabajodatos_agentenormas,
                                            TABLA.observacion AS proyectoordentrabajodatos_agenteobservacion
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        1 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id != 15
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        0 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id = 15
                                                        AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.tipo DESC,
                                            TABLA.agente ASC');
            }

            //===========================================

            // Convierte pagina en PDF y lo descarga o muestra en navegador
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', compact('proyecto', 'proveedores_fisicos', 'proveedores_quimicos'))
            //             ->setPaper('letter', 'landscape') //portrait, landscapes
            //             ->save(storage_path('app/ORDEN_TARABAJO/').$proyecto->proyecto_folio.'.pdf')
            //             ->download('archivo.pdf')
            //             ->stream('archivo.pdf');


            return \PDF::loadView('reportes.proyecto.reporteproyectoordentrabajo', compact('proyecto', 'ordentrabajo', 'servicios'))->stream($proyecto->folio_ot.'.pdf');


            // Mostrar / Descargar
            // if (($ordentrabajo_descargar+0) == 0)
            // {
            //     return \PDF::loadView('reportes.proyecto.reporteproyectoordentrabajo', compact('proyecto', 'ordentrabajo', 'servicios'))->stream($proyecto->folio_ot.'.pdf');
            // }
            // else
            // {
            //     return \PDF::loadView('reportes.proyecto.reporteproyectoordentrabajo', compact('proyecto', 'ordentrabajo', 'servicios'))->download($proyecto->folio_ot.'.pdf');
            // }

            // $dato["msj"] = 'Orden de trabajo creada correctamente';
            // return response()->json($dato);
        }
        catch(Exception $e)
        {
            // $dato["msj"] = 'Error '.$e->getMessage();
            // return response()->json($dato);
            return 'Error '.$e->getMessage();
        }
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
            if (($request['ordentrabajo_id']+0) == 0) //NUEVO
            {
                // Consulta cantidad de OT en este proyecto
                $totalordentrabajo = DB::select('SELECT
                                                    COUNT(proyectoordentrabajo.id) AS ordentrabajoversion,
                                                    IFNULL((
                                                        SELECT
                                                            TABLA.proyectoordentrabajo_cancelado
                                                        FROM
                                                            proyectoordentrabajo AS TABLA
                                                        WHERE
                                                            TABLA.id = MAX(proyectoordentrabajo.id)
                                                    ), 1) AS ultimaot_cancelado
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = '.$request['proyecto_id']);

                // Datos del proyecto
                $proyecto = proyectoModel::findOrFail($request['proyecto_id']);
                $proyecto->fecha_hoy_corto = date('Y-m-d');
                $proyecto->fecha_hoy_largo = date('Y-m-d H:i:s');

                // Obtener folio proyecto
                $proyecto_folio = explode("-", $proyecto->proyecto_folio);

                // Creamos folio OT
                if (($totalordentrabajo[0]->ordentrabajoversion + 0) == 0) // OT original - revision 0
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
                }
                else
                {
                    $proyecto->folio_ot = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2].'-Rev'.$totalordentrabajo[0]->ordentrabajoversion;
                }

                // Valida si viene autorizado
                $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                if ($request['checkbox_autorizaot'] != NULL)
                {
                    $autorizado = 1;
                    $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                    $autorizadofecha = date('Y-m-d H:i:s');
                }

                // Valida si viene cancelado
                $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                if ($request['checkbox_cancelaot'] != NULL)
                {
                    $cancelado = 1;
                    $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                    $canceladofecha = date('Y-m-d H:i:s');
                    $canceladoobservacion = $request['proyectoordentrabajo_canceladoobservacion'];
                }

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE proyectoordentrabajo AUTO_INCREMENT=1');

                // Crear OT
                $ordentrabajo = proyectoordentrabajoModel::create([
                      'proyecto_id' => $request['proyecto_id']
                    , 'proyectoordentrabajo_folio' => $proyecto->folio_ot
                    , 'proyectoordentrabajo_revision' => $totalordentrabajo[0]->ordentrabajoversion //Numero de revisión
                    , 'proyectoordentrabajo_autorizado' => $autorizado
                    , 'proyectoordentrabajo_autorizadonombre' => $autorizadonombre
                    , 'proyectoordentrabajo_autorizadofecha' => $autorizadofecha
                    , 'proyectoordentrabajo_observacionot' => $request['proyectoordentrabajo_observacionot']
                    , 'proyectoordentrabajo_cancelado' => $cancelado
                    , 'proyectoordentrabajo_canceladonombre' => $canceladonombre
                    , 'proyectoordentrabajo_canceladofecha' => $canceladofecha
                    , 'proyectoordentrabajo_canceladoobservacion' => $canceladoobservacion
                    , 'proyectoordentrabajo_observacionrevision' => $request['proyectoordentrabajo_observacionrevision']
                ]);

                // Copiar de los datos de la OT [AGENTES A EVALUAR]
                DB::statement("SET lc_time_names = 'es_MX';");
                $agentes = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.proveedor_id,
                                            TABLA.tipo,
                                            TABLA.catprueba_id,
                                            TABLA.agente,
                                            TABLA.puntos,
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
                                                    AND TABLA1.agente = TABLA.agente
                                                ORDER BY
                                                    TABLA1.updated_at DESC
                                                LIMIT 1
                                            ) AS normas,
                                            TABLA.observacion
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        1 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$request['proyecto_id'].'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id != 15
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        0 AS tipo,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente AS agente,
                                                        proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                        proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                    FROM
                                                        proyectoproveedores
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$request['proyecto_id'].'
                                                        AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                        AND proyectoproveedores.catprueba_id = 15
                                                        AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                    ORDER BY
                                                        proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            TABLA.tipo DESC,
                                            TABLA.agente ASC');

                // Guardar datos de esta orden de trabajo
                foreach ($agentes as $key => $value) 
                {
                    $agentescliente = proyectoordentrabajodatosModel::create([
                          'proyectoordentrabajo_id' => $ordentrabajo->id
                        , 'proyectoordentrabajodatos_proveedorid' => $value->proveedor_id
                        , 'proyectoordentrabajodatos_agenteid' => $value->catprueba_id
                        , 'proyectoordentrabajodatos_agentenombre' => $value->agente
                        , 'proyectoordentrabajodatos_agentepuntos' => $value->puntos
                        , 'proyectoordentrabajodatos_agentenormas' => $value->normas
                        , 'proyectoordentrabajodatos_agenteobservacion' => $value->observacion
                    ]);
                }
            }
            else
            {
                if (($request['ordentrabajo_actualizaot']+0) == 0) // Modificar solo datos generales [autorizado, cancelar, obs]
                {
                    // Consulta OT
                    $ordentrabajo = proyectoordentrabajoModel::findOrFail($request['ordentrabajo_id']);

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if (($ordentrabajo->proyectoordentrabajo_autorizado + 0) == 1)
                    {
                        if ($request['checkbox_autorizaot'] != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = $ordentrabajo->proyectoordentrabajo_autorizadonombre;
                            $autorizadofecha = $ordentrabajo->proyectoordentrabajo_autorizadofecha;
                        }
                    }
                    else
                    {
                        if ($request['checkbox_autorizaot'] != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $autorizadofecha = date('Y-m-d H:i:s');
                        }
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if (($ordentrabajo->proyectoordentrabajo_cancelado + 0) == 1)
                    {
                        if ($request['checkbox_cancelaot'] != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = $ordentrabajo->proyectoordentrabajo_canceladonombre;
                            $canceladofecha = $ordentrabajo->proyectoordentrabajo_canceladofecha;
                            $canceladoobservacion = $ordentrabajo->proyectoordentrabajo_canceladoobservacion;
                        }
                    }
                    else
                    {
                        if ($request['checkbox_cancelaot'] != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $canceladofecha = date('Y-m-d H:i:s');
                            $canceladoobservacion = $request['proyectoordentrabajo_canceladoobservacion'];
                        }
                    }

                    // Modificar
                    $ordentrabajo->update([
                          'proyectoordentrabajo_autorizado' => $autorizado
                        , 'proyectoordentrabajo_autorizadonombre' => $autorizadonombre
                        , 'proyectoordentrabajo_autorizadofecha' => $autorizadofecha
                        , 'proyectoordentrabajo_observacionot' => $request['proyectoordentrabajo_observacionot']
                        , 'proyectoordentrabajo_cancelado' => $cancelado
                        , 'proyectoordentrabajo_canceladonombre' => $canceladonombre
                        , 'proyectoordentrabajo_canceladofecha' => $canceladofecha
                        , 'proyectoordentrabajo_canceladoobservacion' => $canceladoobservacion
                        , 'proyectoordentrabajo_observacionrevision' => $request['proyectoordentrabajo_observacionrevision']
                    ]);
                }
                else //Actualizar TODO
                {
                    // Consulta OT
                    $ordentrabajo = proyectoordentrabajoModel::findOrFail($request['ordentrabajo_id']);

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if (($ordentrabajo->proyectoordentrabajo_autorizado + 0) == 1)
                    {
                        if ($request['checkbox_autorizaot'] != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = $ordentrabajo->proyectoordentrabajo_autorizadonombre;
                            $autorizadofecha = $ordentrabajo->proyectoordentrabajo_autorizadofecha;
                        }
                    }
                    else
                    {
                        if ($request['checkbox_autorizaot'] != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $autorizadofecha = date('Y-m-d H:i:s');
                        }
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if (($ordentrabajo->proyectoordentrabajo_cancelado + 0) == 1)
                    {
                        if ($request['checkbox_cancelaot'] != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = $ordentrabajo->proyectoordentrabajo_canceladonombre;
                            $canceladofecha = $ordentrabajo->proyectoordentrabajo_canceladofecha;
                            $canceladoobservacion = $ordentrabajo->proyectoordentrabajo_canceladoobservacion;
                        }
                    }
                    else
                    {
                        if ($request['checkbox_cancelaot'] != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $canceladofecha = date('Y-m-d H:i:s');
                            $canceladoobservacion = $request['proyectoordentrabajo_canceladoobservacion'];
                        }
                    }

                    // Modificar
                    $ordentrabajo->update([
                          'proyectoordentrabajo_autorizado' => $autorizado
                        , 'proyectoordentrabajo_autorizadonombre' => $autorizadonombre
                        , 'proyectoordentrabajo_autorizadofecha' => $autorizadofecha
                        , 'proyectoordentrabajo_observacionot' => $request['proyectoordentrabajo_observacionot']
                        , 'proyectoordentrabajo_cancelado' => $cancelado
                        , 'proyectoordentrabajo_canceladonombre' => $canceladonombre
                        , 'proyectoordentrabajo_canceladofecha' => $canceladofecha
                        , 'proyectoordentrabajo_canceladoobservacion' => $canceladoobservacion
                        , 'proyectoordentrabajo_observacionrevision' => $request['proyectoordentrabajo_observacionrevision']
                    ]);

                    // Eliminar datos anteriores de la OT
                    $agenteseliminar = proyectoordentrabajodatosModel::where('proyectoordentrabajo_id', $request['ordentrabajo_id'])->delete();

                    // Consultar y copiar los datos de la OT [AGENTES A EVALUAR ACTUALES]
                    DB::statement("SET lc_time_names = 'es_MX';");
                    $agentes = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.proveedor_id,
                                                TABLA.tipo,
                                                TABLA.catprueba_id,
                                                TABLA.agente,
                                                TABLA.puntos,
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
                                                        AND TABLA1.agente = TABLA.agente
                                                    ORDER BY
                                                        TABLA1.updated_at DESC
                                                    LIMIT 1
                                                ) AS normas,
                                                TABLA.observacion
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            1 AS tipo,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente AS agente,
                                                            proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request['proyecto_id'].'
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                            AND proyectoproveedores.catprueba_id != 15
                                                        ORDER BY
                                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                            proyectoproveedores.proyectoproveedores_agente ASC
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            0 AS tipo,
                                                            proyectoproveedores.proveedor_id,
                                                            proyectoproveedores.proyecto_id,
                                                            proyectoproveedores.catprueba_id,
                                                            proyectoproveedores.proyectoproveedores_agente AS agente,
                                                            proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                            proyectoproveedores.proyectoproveedores_observacion AS observacion
                                                        FROM
                                                            proyectoproveedores
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$request['proyecto_id'].'
                                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                            AND proyectoproveedores.catprueba_id = 15
                                                            AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                        ORDER BY
                                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                            proyectoproveedores.proyectoproveedores_agente ASC
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo DESC,
                                                TABLA.agente ASC');

                    // Guardar datos de esta orden de trabajo
                    foreach ($agentes as $key => $value) 
                    {
                        $agentescliente = proyectoordentrabajodatosModel::create([
                              'proyectoordentrabajo_id' => $ordentrabajo->id
                            , 'proyectoordentrabajodatos_proveedorid' => $value->proveedor_id
                            , 'proyectoordentrabajodatos_agenteid' => $value->catprueba_id
                            , 'proyectoordentrabajodatos_agentenombre' => $value->agente
                            , 'proyectoordentrabajodatos_agentepuntos' => $value->puntos
                            , 'proyectoordentrabajodatos_agentenormas' => $value->normas
                            , 'proyectoordentrabajodatos_agenteobservacion' => $value->observacion
                        ]);
                    }
                }
            }

            // respuesta
            $dato["ordentrabajo"] = $ordentrabajo;
            $dato["msj"] = 'Información guardada correctamente';
            return response()->json($dato);
        }
        catch(\Exception $e)
        {
            $dato["msj"] = 'Error: '.$e->getMessage();
            $dato['ordentrabajo'] = 0;
            return response()->json($dato);
        }
    }
}