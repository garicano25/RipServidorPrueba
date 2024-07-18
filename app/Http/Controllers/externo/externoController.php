<?php

namespace App\Http\Controllers\externo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoproveedoresfisicosModel;
use App\modelos\proyecto\proyectoproveedoresquimicosModel;
use App\modelos\proyecto\proyectosignatarioModel;
use App\modelos\proyecto\proyectosignatariosobservacionModel;
use App\modelos\proyecto\proyectoequipoModel;
use App\modelos\proyecto\proyectoequiposobservacionModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class externoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        $this->middleware('roles:Externo');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('catalogos.externo.externo');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function externotabla()
    {
        try
        {
            DB::statement("SET lc_time_names = 'es_MX';");
            $proyectos = DB::select('SELECT
                                        proyectoproveedores.proyecto_id AS PROYECTO,
                                        proyectoproveedores.proveedor_id AS PROVEEDOR,
                                        proyectoproveedores.proyecto_id,
                                        proyectoproveedores.proveedor_id,
                                        proyecto.proyecto_folio,
                                        proyecto.proyecto_clienterazonsocial,
                                        proyecto.proyecto_clienteinstalacion,
                                        proyecto.proyecto_clientedireccionservicio,
                                        proyecto.proyecto_fechainicio,
                                        proyecto.proyecto_fechafin,
                                        proyecto.proyecto_totaldias,
                                        -- IFNULL((
                                        --     SELECT
                                        --         CONCAT("<li>", REPLACE(GROUP_CONCAT(CONCAT(REPLACE(proyectoproveedores.proyectoproveedores_agente, ",", "&#8218;"), " [ ", proyectoproveedores.proyectoproveedores_puntos, " ]")), ",", "</li><li>"), "</li>") AS x 
                                        --     FROM
                                        --         proyectoproveedores 
                                        --     WHERE
                                        --         proyectoproveedores.proyecto_id = PROYECTO 
                                        --         AND proyectoproveedores.proveedor_id = PROVEEDOR
                                        --         AND proyectoproveedores.catprueba_id != 15
                                        --     LIMIT 1
                                        -- ), "No hay datos que mostrar") AS agentesfisicos,
                                        -- IFNULL((
                                        --     SELECT
                                        --         CONCAT("<li>", REPLACE(GROUP_CONCAT(CONCAT(REPLACE(proyectoproveedores.proyectoproveedores_agente, ",", "&#8218;"), " [ ", proyectoproveedores.proyectoproveedores_puntos, " ]")), ",", "</li><li>"), "</li>") AS x 
                                        --     FROM
                                        --         proyectoproveedores 
                                        --     WHERE
                                        --         proyectoproveedores.proyecto_id = PROYECTO 
                                        --         AND proyectoproveedores.proveedor_id = PROVEEDOR
                                        --         AND proyectoproveedores.catprueba_id = 15
                                        --     LIMIT 1
                                        -- ), "No hay datos que mostrar") AS agentesquimicos,
                                        IFNULL((
                                            SELECT
                                                (
                                                    CASE
                                                        WHEN proyectosignatario_cancelado = 1 THEN 1
                                                        WHEN proyectosignatario_autorizado = 1 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS disponibilidadsignatarios
                                            FROM
                                                proyectosignatarios
                                            WHERE
                                                proyectosignatarios.proyecto_id = PROYECTO
                                            ORDER BY
                                                proyectosignatarios.proyectosignatario_revision DESC
                                            LIMIT 1
                                        ), 1) AS disponibilidadsignatarios,
                                        IFNULL((
                                            SELECT
                                                IFNULL(proyectosignatariosobservacion.proyectosignatariosobservacion, "")
                                            FROM
                                                proyectosignatariosobservacion
                                            WHERE
                                                proyectosignatariosobservacion.proyecto_id = PROYECTO
                                                AND proyectosignatariosobservacion.proveedor_id = PROVEEDOR
                                        ), "") AS observacionsignatarios,
                                        IFNULL((
                                            SELECT
                                                (
                                                    CASE
                                                        WHEN proyectoequipo_cancelado = 1 THEN 1
                                                        WHEN proyectoequipo_autorizado = 1 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS disponibilidadequipos
                                            FROM
                                                proyectoequipos
                                            WHERE
                                                proyectoequipos.proyecto_id = PROYECTO
                                            ORDER BY
                                                proyectoequipos.proyectoequipo_revision DESC
                                            LIMIT 1
                                        ), 1) AS disponibilidadequipos,
                                        IFNULL((
                                            SELECT
                                                IFNULL(proyectoequiposobservacion.proyectoequiposobservacion, "")
                                            FROM
                                                proyectoequiposobservacion
                                            WHERE
                                                proyectoequiposobservacion.proyecto_id = PROYECTO
                                                AND proyectoequiposobservacion.proveedor_id = PROVEEDOR
                                        ), "") AS observacionequipos
                                    FROM
                                        proyectoproveedores
                                        LEFT JOIN proyecto ON proyectoproveedores.proyecto_id = proyecto.id
                                    WHERE
                                        proyecto.proyecto_concluido = 0
                                        AND proyectoproveedores.proveedor_id = '.auth()->user()->empleado_id.'
                                    GROUP BY
                                        proyectoproveedores.proyecto_id,
                                        proyectoproveedores.proveedor_id,
                                        proyecto.proyecto_folio,
                                        proyecto.proyecto_clienterazonsocial,
                                        proyecto.proyecto_clienteinstalacion,
                                        proyecto.proyecto_clientedireccionservicio,
                                        proyecto.proyecto_fechainicio,
                                        proyecto.proyecto_fechafin,
                                        proyecto.proyecto_totaldias
                                    ORDER BY
                                        proyectoproveedores.proyecto_id ASC');

            $numero_registro = 0;
            foreach ($proyectos as $key => $value)
            {
                $numero_registro += 1;
                
                $value->numero_registro = $numero_registro;
                $value->instalacion_y_direccion = '<span style="color: #999999;">'.$value->proyecto_clienteinstalacion.'</span><br>'.$value->proyecto_clientedireccionservicio;
                $value->inicio_y_fin = $value->proyecto_fechainicio."<br>".$value->proyecto_fechafin;
                $value->duracion = $value->proyecto_totaldias." días";


                //=========================================================


                $agentesfisicos = DB::select('SELECT
                                                    TABLA2.agente,
                                                    TABLA2.puntos,
                                                    TABLA2.descripcion
                                                    -- CONCAT("<li>", REPLACE(GROUP_CONCAT(CONCAT(REPLACE(CONCAT("<b>", TABLA2.agente, "</b>"), ",", "&#8218;"), " [ ", TABLA2.puntos, " ] (", REPLACE(TABLA2.descripcion, ",", "‚"),")")), ",", "</li><li>"), "</li>") AS x 
                                                FROM
                                                    (
                                                        SELECT
                                                            proyectoproveedores.proyectoproveedores_agente AS agente,
                                                            proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                            IFNULL((
                                                                SELECT
                                                                    -- TABLA.proveedor_id,
                                                                    -- TABLA.prueba_id,
                                                                    -- TABLA.agente,
                                                                    -- TABLA.norma,
                                                                    TABLA.descripcion
                                                                FROM
                                                                 (
                                                                        SELECT
                                                                            acreditacionalcance.proveedor_id, 
                                                                            acreditacionalcance.prueba_id,
                                                                            (
                                                                                IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")"))
                                                                            ) AS agente,
                                                                            acreditacionalcance.acreditacionAlcance_Norma AS norma, 
                                                                            acreditacionalcance.acreditacionAlcance_Descripcion AS descripcion
                                                                        FROM
                                                                            acreditacionalcance
                                                                        WHERE
                                                                            acreditacionalcance.proveedor_id = '.$value->proveedor_id.' 
                                                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                                    ) AS TABLA
                                                                WHERE
                                                                    TABLA.agente LIKE proyectoproveedores.proyectoproveedores_agente
                                                                LIMIT 1 
                                                            ), "N/A") AS descripcion
                                                        FROM
                                                            proyectoproveedores 
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$value->proyecto_id.' 
                                                            AND proyectoproveedores.proveedor_id = '.$value->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id != 15
                                                    ) AS TABLA2');

                
                $tabla1 = '';

            
                if (count($agentesfisicos) > 0)
                {
                    foreach ($agentesfisicos as $key2 => $value2)
                    {
                        $tabla1 .= '<tr>
                                        <td>'.$value2->agente.'</td>
                                        <td>'.$value2->puntos.'</td>
                                        <td>'.$value2->descripcion.'</td>
                                    </tr>';
                    }
                }
                else
                {
                    $tabla1 .= '<tr>
                                    <td colspan="3">No hay datos que mostrar</td>
                                </tr>';
                }


                $value->agentesfisicos = $tabla1;


                //=========================================================


                $agentesquimicos = DB::select('SELECT
                                                    TABLA2.agente,
                                                    TABLA2.puntos,
                                                    TABLA2.descripcion
                                                    -- CONCAT("<li>", REPLACE(GROUP_CONCAT(CONCAT(REPLACE(CONCAT("<b>", TABLA2.agente, "</b>"), ",", "&#8218;"), " [ ", TABLA2.puntos, " ] (", REPLACE(TABLA2.descripcion, ",", "‚"),")")), ",", "</li><li>"), "</li>") AS x 
                                                FROM
                                                    (
                                                        SELECT
                                                            proyectoproveedores.proyectoproveedores_agente AS agente,
                                                            proyectoproveedores.proyectoproveedores_puntos AS puntos,
                                                            IFNULL((
                                                                SELECT
                                                                    -- TABLA.proveedor_id,
                                                                    -- TABLA.prueba_id,
                                                                    -- TABLA.agente,
                                                                    -- TABLA.norma,
                                                                    TABLA.descripcion
                                                                FROM
                                                                 (
                                                                        SELECT
                                                                            acreditacionalcance.proveedor_id, 
                                                                            acreditacionalcance.prueba_id,
                                                                            (
                                                                                IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo, ")"))
                                                                            ) AS agente,
                                                                            acreditacionalcance.acreditacionAlcance_Norma AS norma, 
                                                                            acreditacionalcance.acreditacionAlcance_Descripcion AS descripcion
                                                                        FROM
                                                                            acreditacionalcance
                                                                        WHERE
                                                                            acreditacionalcance.proveedor_id = '.$value->proveedor_id.' 
                                                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                                    ) AS TABLA
                                                                WHERE
                                                                    TABLA.agente LIKE proyectoproveedores.proyectoproveedores_agente
                                                                LIMIT 1 
                                                            ), "") AS descripcion
                                                        FROM
                                                            proyectoproveedores 
                                                        WHERE
                                                            proyectoproveedores.proyecto_id = '.$value->proyecto_id.' 
                                                            AND proyectoproveedores.proveedor_id = '.$value->proveedor_id.'
                                                            AND proyectoproveedores.catprueba_id = 15
                                                    ) AS TABLA2');


                $tabla2 = '';

            
                if (count($agentesquimicos) > 0)
                {
                    foreach ($agentesquimicos as $key2 => $value2)
                    {
                        $tabla2 .= '<tr>
                                        <td>'.$value2->agente.'</td>
                                        <td>'.$value2->puntos.'</td>
                                        <td>'.$value2->descripcion.'</td>
                                    </tr>';
                    }
                }
                else
                {
                    $tabla2 .= '<tr>
                                    <td colspan="3">No hay datos que mostrar</td>
                                </tr>';
                }


                $value->agentesquimicos = $tabla2;
            }

            // respuesta
            $dato['data'] = $proyectos;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function externoimprimirlistasignatarios($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Lista de signatarios historial
            $datoslista = DB::select('SELECT
                                            proyectosignatarios.id,
                                            proyectosignatarios.proyecto_id,
                                            proyectosignatarios.proyectosignatario_revision,
                                            proyectosignatarios.proyectosignatario_autorizado,
                                            proyectosignatarios.proyectosignatario_autorizadonombre,
                                            proyectosignatarios.proyectosignatario_autorizadofecha,
                                            proyectosignatarios.proyectosignatario_cancelado,
                                            proyectosignatarios.proyectosignatario_canceladonombre,
                                            proyectosignatarios.proyectosignatario_canceladofecha,
                                            proyectosignatarios.proyectosignatario_canceladoobservacion,
                                            proyectosignatarios.created_at,
                                            proyectosignatarios.updated_at 
                                        FROM
                                            proyectosignatarios 
                                        WHERE
                                            proyectosignatarios.proyecto_id = '.$proyecto_id.' 
                                        ORDER BY
                                            proyectosignatarios.proyectosignatario_revision DESC
                                        LIMIT 1');

            // Datos de la lista nueva de signatarios
            $signatarioslista = array(
                                     'proyecto_id' => $proyecto_id
                                    ,'proyectosignatario_revision' => $datoslista[0]->proyectosignatario_revision
                                    ,'proyectosignatario_autorizado' => $datoslista[0]->proyectosignatario_autorizado
                                    ,'proyectosignatario_autorizadonombre' => $datoslista[0]->proyectosignatario_autorizadonombre
                                    ,'proyectosignatario_autorizadofecha' => $datoslista[0]->proyectosignatario_autorizadofecha
                                    ,'proyectosignatario_cancelado' => $datoslista[0]->proyectosignatario_cancelado
                                    ,'proyectosignatario_canceladonombre' => $datoslista[0]->proyectosignatario_canceladonombre
                                    ,'proyectosignatario_canceladofecha' => $datoslista[0]->proyectosignatario_canceladofecha
                                    ,'proyectosignatario_canceladoobservacion' => $datoslista[0]->proyectosignatario_canceladoobservacion
                                    ,'created_at' => $datoslista[0]->created_at
                                    ,'updated_at' => $datoslista[0]->updated_at
                                );

            // Numero de revision
            $documento_nombre = '';
            if (($datoslista[0]->proyectosignatario_revision+0) > 0)
            {
                $documento_nombre = 'Lista de signatarios rev-'.$datoslista[0]->proyectosignatario_revision;
            }
            else
            {
                $documento_nombre = 'Lista de signatarios';
            }

            // Consulta signatarios historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $signatarios = DB::select('SELECT
                                            proyectosignatarioshistorial.proyecto_id,
                                            proveedor.proveedor_RazonSocial,
                                            proveedor.proveedor_NombreComercial,
                                            proyectosignatarioshistorial.signatario_id,
                                            signatario.signatario_Nombre,
                                            signatario.signatario_Cargo 
                                        FROM
                                            proyectosignatarioshistorial
                                            LEFT JOIN proveedor ON proyectosignatarioshistorial.proveedor_id = proveedor.id
                                            LEFT JOIN signatario ON proyectosignatarioshistorial.signatario_id = signatario.id
                                        WHERE
                                            proyectosignatarioshistorial.proyecto_id = '.$proyecto_id.'
                                            AND proyectosignatarioshistorial.proyectosignatario_revision = '.$datoslista[0]->proyectosignatario_revision.'
                                            AND proyectosignatarioshistorial.proveedor_id = '.auth()->user()->empleado_id.'
                                        ORDER BY
                                            proveedor.proveedor_NombreComercial ASC,
                                            signatario.signatario_Nombre ASC');


            //===========================================

            // return view('reportes.proyecto.ordentrabajo', compact('proyecto', 'lista'));
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', $proyecto, $proveedores_fisicos)


            // Convierte pagina en PDF y lo descarga o muestra en navegador
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', compact('proyecto', 'proveedores_fisicos', 'proveedores_quimicos'))
                        // ->setPaper('letter', 'landscape') //portrait, landscapes
                        // ->save(storage_path('app/ORDEN_TARABAJO/').$proyecto->proyecto_folio.'.pdf')
                        // ->download('archivo.pdf')
                        // ->stream('archivo.pdf');

            return \PDF::loadView('reportes.proyecto.reporteproyectolistasignatarios', compact('proyecto', 'signatarioslista', 'signatarios'))->stream($ot_folio.' '.$documento_nombre.'.pdf');
            // return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            // $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function externoimprimirlistaequipos($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Lista de equipos historial
            $datoslista = DB::select('SELECT
                                            proyectoequipos.id,
                                            proyectoequipos.proyecto_id,
                                            proyectoequipos.proyectoequipo_revision,
                                            proyectoequipos.proyectoequipo_autorizado,
                                            proyectoequipos.proyectoequipo_autorizadonombre,
                                            proyectoequipos.proyectoequipo_autorizadofecha,
                                            proyectoequipos.proyectoequipo_cancelado,
                                            proyectoequipos.proyectoequipo_canceladonombre,
                                            proyectoequipos.proyectoequipo_canceladofecha,
                                            proyectoequipos.proyectoequipo_canceladoobservacion,
                                            proyectoequipos.created_at,
                                            proyectoequipos.updated_at 
                                        FROM
                                            proyectoequipos 
                                        WHERE
                                            proyectoequipos.proyecto_id = '.$proyecto_id.' 
                                        ORDER BY
                                            proyectoequipos.proyectoequipo_revision DESC
                                        LIMIT 1');

            // Datos de la lista nueva de equipos
            $equiposlista = array(
                                 'proyecto_id' => $proyecto_id
                                ,'proyectoequipo_revision' => $datoslista[0]->proyectoequipo_revision
                                ,'proyectoequipo_autorizado' => $datoslista[0]->proyectoequipo_autorizado
                                ,'proyectoequipo_autorizadonombre' => $datoslista[0]->proyectoequipo_autorizadonombre
                                ,'proyectoequipo_autorizadofecha' => $datoslista[0]->proyectoequipo_autorizadofecha
                                ,'proyectoequipo_cancelado' => $datoslista[0]->proyectoequipo_cancelado
                                ,'proyectoequipo_canceladonombre' => $datoslista[0]->proyectoequipo_canceladonombre
                                ,'proyectoequipo_canceladofecha' => $datoslista[0]->proyectoequipo_canceladofecha
                                ,'proyectoequipo_canceladoobservacion' => $datoslista[0]->proyectoequipo_canceladoobservacion
                                ,'created_at' => $datoslista[0]->created_at
                                ,'updated_at' => $datoslista[0]->updated_at
                            );

            // Numero de revision
            $documento_nombre = '';
            if (($datoslista[0]->proyectoequipo_revision+0) > 0)
            {
                $documento_nombre = 'Lista de equipos rev-'.$datoslista[0]->proyectoequipo_revision;
            }
            else
            {
                $documento_nombre = 'Lista de equipos';
            }

            // Consulta equipos historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $equipos = DB::select('SELECT
                                        proyectoequiposhistorial.proyecto_id,
                                        proyectoequiposhistorial.proyectoequipo_revision,
                                        proyectoequiposhistorial.proveedor_id,
                                        proyectoequiposhistorial.equipo_id,
                                        proveedor.proveedor_RazonSocial,
                                        proveedor.proveedor_NombreComercial,
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        IFNULL(equipo.equipo_FechaCalibracion, "N/A") AS equipo_FechaCalibracion,
                                        IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") AS equipo_VigenciaCalibracion 
                                    FROM
                                        proyectoequiposhistorial
                                        LEFT JOIN proveedor ON proyectoequiposhistorial.proveedor_id = proveedor.id
                                        LEFT JOIN equipo ON proyectoequiposhistorial.equipo_id = equipo.id
                                    WHERE
                                        proyectoequiposhistorial.proyecto_id = '.$proyecto_id.'
                                        AND proyectoequiposhistorial.proyectoequipo_revision = '.$datoslista[0]->proyectoequipo_revision.' 
                                        AND proyectoequiposhistorial.proveedor_id = '.auth()->user()->empleado_id.'
                                    ORDER BY
                                        proveedor.proveedor_NombreComercial ASC,
                                        equipo.equipo_Descripcion ASC');


            //===========================================

            // return view('reportes.proyecto.ordentrabajo', compact('proyecto', 'lista'));
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', $proyecto, $proveedores_fisicos)


            // Convierte pagina en PDF y lo descarga o muestra en navegador
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', compact('proyecto', 'proveedores_fisicos', 'proveedores_quimicos'))
                        // ->setPaper('letter', 'landscape') //portrait, landscapes
                        // ->save(storage_path('app/ORDEN_TARABAJO/').$proyecto->proyecto_folio.'.pdf')
                        // ->download('archivo.pdf')
                        // ->stream('archivo.pdf');

            return \PDF::loadView('reportes.proyecto.reporteproyectolistaequipos', compact('proyecto', 'equiposlista', 'equipos'))->stream($ot_folio.' '.$documento_nombre.'.pdf');
            // return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            // $dato['opciones'] = $opciones_select;
            return response()->json($dato);
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
            // respuesta
            $dato["msj"] = 'Datos guardados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
