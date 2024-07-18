<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoequipoModel;
use App\modelos\proyecto\proyectoequipoactualModel;
use App\modelos\proyecto\proyectoequipohistorialModel;
use App\modelos\proyecto\proyectoequiposobservacionModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoequipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoequiposinventario($proyecto_id)
    {
        try
        {
            $where_adicional = '';
            if (auth()->user()->hasRoles(['Externo']))
            {
                $where_adicional = 'AND proyectoproveedores.proveedor_id = '.auth()->user()->empleado_id;
            }

            $equipos = DB::select('SELECT
                                        proveedor.id AS proveedor_id,
                                        proveedor.proveedor_NombreComercial,
                                        equipo.id,
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        IFNULL((
                                            CASE
                                                WHEN (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) > 90) THEN DATE_FORMAT(equipo.equipo_VigenciaCalibracion, "%Y-%m-%d")
                                                WHEN (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) > 30) THEN CONCAT(DATE_FORMAT(equipo.equipo_VigenciaCalibracion, "%Y-%m-%d"), " (", DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()), " d)")
                                                ELSE CONCAT(DATE_FORMAT(equipo.equipo_VigenciaCalibracion, "%Y-%m-%d"), " (", DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()), " d)")
                                            END
                                        ), "N/A") AS equipo_VigenciaCalibracion,
                                        (
                                            CASE
                                                WHEN (IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") = "N/A") THEN "#99abb4"
                                                WHEN (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) > 90) THEN "#99abb4"
                                                WHEN (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) > 30) THEN "#ffae00"
                                                ELSE "#F00"
                                            END
                                        ) AS equipo_color,
                                        IFNULL((
                                            SELECT
                                                IF(proyectoequiposactual.equipo_id , "checked", "")
                                            FROM
                                                proyectoequiposactual
                                            WHERE
                                                proyectoequiposactual.proyecto_id = proyectoproveedores.proyecto_id AND proyectoequiposactual.equipo_id = equipo.id
                                            LIMIT 1
                                        ), "") AS checked,
                                        (
                                            CASE
                                                WHEN (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) > 0 || IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") = "N/A") THEN "Si"
                                                ELSE "No"
                                            END
                                        ) AS equipo_disponible
                                    FROM
                                        proyectoproveedores
                                        INNER JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                        INNER JOIN equipo ON proyectoproveedores.proveedor_id = equipo.proveedor_id
                                    WHERE
                                        proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                        '.$where_adicional.'
                                    GROUP BY
                                        proyectoproveedores.proyecto_id,
                                        proveedor.id,
                                        proveedor.proveedor_NombreComercial,
                                        equipo.id,
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        equipo.equipo_VigenciaCalibracion
                                    ORDER BY
                                        proveedor.proveedor_NombreComercial ASC,
                                        equipo_disponible DESC,
                                        equipo.equipo_Descripcion ASC');

            
            $numero_registro = 0;
            $listaequipos = '';
            foreach ($equipos as $key => $value)
            {
                // dibujar filas
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                $value->proveedor_NombreComercial;
                $value->equipo_disponible;

                if ($value->equipo_disponible === "Si")
                {
                    $value->checkbox = '<div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" class="checkbox_proyectoequipos" name="equipo[]" value="'.$value->proveedor_id.'-'.$value->id.'" '.$value->checked.'>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>';
                }
                else
                {
                    $value->checkbox = '<i class="fa fa-ban"></i>';
                }
                
                $value->equipo_Descripcion = '<span style="color: '.$value->equipo_color.';">'.$value->equipo_Descripcion.'</span>';
                $value->equipo_Marca = '<span style="color: '.$value->equipo_color.';">'.$value->equipo_Marca.'</span>';
                $value->equipo_Modelo = '<span style="color: '.$value->equipo_color.';">'.$value->equipo_Modelo.'</span>';
                $value->equipo_Serie = '<span style="color: '.$value->equipo_color.';">'.$value->equipo_Serie.'</span>';
                $value->equipo_VigenciaCalibracion = '<span style="color: '.$value->equipo_color.';">'.$value->equipo_VigenciaCalibracion.'</span>';
            }

            
            // Respuesta
            $dato["data"] = $equipos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Respuesta
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato["data"] = 0;
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoequiposlistas($proyecto_id)
    {
        try
        {
            $equiposlistas = DB::select('SELECT
                                            proyectoequipos.id,
                                            proyectoequipos.proyecto_id,
                                            (
                                                SELECT
                                                    proyecto.proyecto_folio
                                                FROM
                                                    proyecto
                                                WHERE
                                                    proyecto.id = proyectoequipos.proyecto_id
                                            ) AS proyecto_folio,
                                            (
                                                SELECT
                                                    proyectoordentrabajo.proyectoordentrabajo_folio
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = proyectoequipos.proyecto_id
                                                ORDER BY
                                                    proyectoordentrabajo.proyectoordentrabajo_revision ASC
                                                LIMIT 1
                                            ) AS ordentrabajo_folio,
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
                                            proyectoequipos.proyectoequipo_revision ASC');

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($equiposlistas as $key => $value)
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Numero de revision
                if (($value->proyectoequipo_revision+0) > 0)
                {
                    $value->revision = '<b>Lista de equipos Rev-'.$value->proyectoequipo_revision.'</b><br>'.$value->created_at;
                }
                else
                {
                    $value->revision = '<b>Lista de equipos</b><br>'.$value->created_at;
                }

                // Diseño Autorizado
                if (($value->proyectoequipo_autorizado+0) == 1)
                {
                    $value->autorizado = $value->proyectoequipo_autorizadonombre.'<br>'.$value->proyectoequipo_autorizadofecha;
                }
                else
                {
                    $value->autorizado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }

                // Diseño estado
                if (($value->proyectoequipo_cancelado+0) == 1)
                {
                    $value->estado = '<b class="text-danger">Cancelado</b>';
                    $value->cancelado = $value->proyectoequipo_canceladonombre.'<br>'.$value->proyectoequipo_canceladofecha;
                }
                else
                {
                    $value->estado = '<b class="text-info">Vigente</b>';
                    $value->cancelado = 'N/A';
                }
            }

            
            // Respuesta
            $dato["data"] = $equiposlistas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // Respuesta
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato["data"] = 0;
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoequiposgenerarlistaestado($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $cancelado = 1;

            $equiposlistastotal = DB::select('SELECT
                                                    proyectoequipos.id,
                                                    proyectoequipos.proyecto_id,
                                                    proyectoequipos.proyectoequipo_revision,
                                                    proyectoequipos.proyectoequipo_autorizadofecha,
                                                    proyectoequipos.proyectoequipo_cancelado
                                                FROM
                                                    proyectoequipos
                                                WHERE
                                                    proyectoequipos.proyecto_id = '.$proyecto_id.'
                                                ORDER BY
                                                    proyectoequipos.proyectoequipo_revision DESC
                                                LIMIT 1');

            if (count($equiposlistastotal) > 0)
            {
                $no_revision = ($equiposlistastotal[0]->proyectoequipo_revision + 1);
                $no_revision_texto = ' Rev-'.$no_revision;

                $cancelado = ($equiposlistastotal[0]->proyectoequipo_cancelado + 0);
            }

            // Respuesta
            $dato["lista_folioot"] = $ot_folio;
            $dato["lista_revision"] = $no_revision;
            $dato["no_revision_texto"] = $no_revision_texto;
            $dato["lista_cancelado"] = $cancelado;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["lista_folioot"] = 0;
            $dato["lista_revision"] = 0;
            $dato["no_revision_texto"] = 0;
            $dato["lista_cancelado"] = 0;
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
    public function proyectoequiposconsultaractual($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $equiposlistastotal = DB::select('SELECT
                                                    proyectoequipos.id,
                                                    proyectoequipos.proyecto_id,
                                                    proyectoequipos.proyectoequipo_revision,
                                                    proyectoequipos.proyectoequipo_autorizado,
                                                    proyectoequipos.proyectoequipo_cancelado
                                                FROM
                                                    proyectoequipos
                                                WHERE
                                                    proyectoequipos.proyecto_id = '.$proyecto_id.'
                                                ORDER BY
                                                    proyectoequipos.proyectoequipo_revision DESC
                                                LIMIT 1');

            if (count($equiposlistastotal) > 0)
            {
                $no_revision = ($equiposlistastotal[0]->proyectoequipo_revision + 1);
                $no_revision_texto = ' Rev-'.$no_revision;
            }

            // Datos de la lista nueva de equipos
            $equiposlista = array(
                                 'proyecto_id' => $proyecto_id
                                ,'proyectoequipo_revision' => $no_revision
                                ,'proyectoequipo_autorizado' => 0
                                ,'proyectoequipo_autorizadonombre' => NULL
                                ,'proyectoequipo_autorizadofecha' => NULL
                                ,'proyectoequipo_cancelado' => 0
                                ,'proyectoequipo_canceladonombre' => NULL
                                ,'proyectoequipo_canceladofecha' => NULL
                                ,'proyectoequipo_canceladoobservacion' => NULL
                                ,'created_at' => date('Y-m-d H:m:s')
                                ,'updated_at' => date('Y-m-d H:m:s')
                            );

            // Consulta equipos historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $equipos = DB::select('SELECT
                                        proyectoequiposactual.proyecto_id,
                                        proyectoequiposactual.proveedor_id,
                                        proveedor.proveedor_RazonSocial,
                                        proveedor.proveedor_NombreComercial,
                                        proyectoequiposactual.equipo_id,
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        IFNULL(equipo.equipo_FechaCalibracion, "N/A") AS equipo_FechaCalibracion,
                                        IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") AS equipo_VigenciaCalibracion
                                    FROM
                                        proyectoequiposactual
                                        LEFT JOIN proveedor ON proyectoequiposactual.proveedor_id = proveedor.id
                                        LEFT JOIN equipo ON proyectoequiposactual.equipo_id = equipo.id
                                    WHERE
                                        proyectoequiposactual.proyecto_id = '.$proyecto_id.'
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


            return \PDF::loadView('reportes.proyecto.reporteproyectolistaequipos', compact('proyecto', 'equiposlista', 'equipos'))->stream($ot_folio.' Lista de equipos'.$no_revision_texto.'.pdf');
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
     * @param  int  $proyectoequipos_revision
     * @return \Illuminate\Http\Response
     */
    public function proyectoequiposconsultarhistorial($proyecto_id, $proyectoequipos_revision)
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
                                            AND proyectoequipos.proyectoequipo_revision = '.$proyectoequipos_revision.'
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
            if (($proyectoequipos_revision+0) > 0)
            {
                $documento_nombre = 'Lista de equipos rev-'.$proyectoequipos_revision;
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
                                        AND proyectoequiposhistorial.proyectoequipo_revision = '.$proyectoequipos_revision.'
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
            if (($request->opcion + 0) == 0) // ASIGNAR EQUIPOS AL PROYECTO
            {
                if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                {
                    if ($request->equipo)
                    {
                        // Eliminar lista de equipos actuales
                        $eliminar_equipos = proyectoequipoactualModel::where('proyecto_id',  $request->proyecto_id)->delete();

                        foreach ($request->equipo as $key => $value) 
                        {
                            // Separar datos [proveedor_id - equipo_id]
                            $valor = explode("-", $value);

                            $guardar_equipos = proyectoequipoactualModel::create([
                                  'proyecto_id' => $request->proyecto_id
                                , 'proveedor_id' => $valor[0]
                                , 'equipo_id' => $valor[1]
                            ]);
                        }
                    }

                    // mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    // Disponibilidad para guardar lista
                    $disponibilidadequipos = 1;
                    $equiposlistaestado = DB::select('SELECT
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
                                                            proyectoequipos.proyecto_id = '.$request->proyecto_id.'
                                                        ORDER BY
                                                            proyectoequipos.proyectoequipo_revision DESC
                                                        LIMIT 1');

                    if (count($equiposlistaestado) > 0)
                    {
                        $disponibilidadequipos = ($equiposlistaestado[0]->disponibilidadequipos + 0);
                    }

                    // Acccion segun estado
                    if ($disponibilidadequipos == 1)
                    {
                        if ($request->equipo)
                        {
                            // Eliminar lista de equipos actuales
                            $eliminar_equipos = proyectoequipoactualModel::where('proyecto_id',  $request->proyecto_id)->where('proveedor_id',  auth()->user()->empleado_id)->delete();

                            foreach ($request->equipo as $key => $value) 
                            {
                                // Separar datos [proveedor_id - equipo_id]
                                $valor = explode("-", $value);

                                $guardar_equipos = proyectoequipoactualModel::create([
                                      'proyecto_id' => $request->proyecto_id
                                    , 'proveedor_id' => $valor[0]
                                    , 'equipo_id' => $valor[1]
                                ]);
                            }
                        }

                        // Guardar observacion
                        if ($request->proyectoequiposobservacion)
                        {
                            // Eliminar historial
                            $eliminar_observacion = proyectoequiposobservacionModel::where('proyecto_id', $request->proyecto_id)
                                                                                    ->where('proveedor_id', auth()->user()->empleado_id)
                                                                                    ->delete();

                            // Guardar
                            $guardar_observacion = proyectoequiposobservacionModel::create([
                                  'proyecto_id' => $request->proyecto_id
                                , 'proveedor_id' => auth()->user()->empleado_id
                                , 'proyectoequiposobservacion' => $request->proyectoequiposobservacion
                            ]);
                        }

                        // mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    }
                    else
                    {
                        // mensaje
                        $dato["msj"] = 'No se realizó ningun cambio, la lista ha sido bloqueada por el Administrador';
                    }

                    // Respuesta
                    $dato["disponibilidadequipos"] = $disponibilidadequipos;
                }
            }
            else // LISTAS DE EQUIPOS CREAR / EDITAR
            {
                // Datos Proyecto y ordentrabajo
                $folios = DB::select('SELECT
                                            proyecto.id,
                                            proyecto.proyecto_folio,
                                            IFNULL((
                                                SELECT
                                                    proyectoordentrabajo.proyectoordentrabajo_folio
                                                FROM
                                                    proyectoordentrabajo
                                                WHERE
                                                    proyectoordentrabajo.proyecto_id = proyecto.id
                                                ORDER BY
                                                    proyectoordentrabajo.proyectoordentrabajo_revision ASC
                                                LIMIT 1
                                            ), "OT-PENDIENTE") AS ordentrabajo_folio 
                                        FROM
                                            proyecto
                                        WHERE
                                            proyecto.id = '.$request->proyecto_id);


                if (($request->equiposlista_id) == 0) // NUEVA LISTA
                {
                    // Numero de revision
                    $no_revision = 0;
                    $equiposlistastotal = DB::select('SELECT
                                                            proyectoequipos.id,
                                                            proyectoequipos.proyecto_id,
                                                            proyectoequipos.proyectoequipo_revision,
                                                            proyectoequipos.proyectoequipo_autorizado,
                                                            proyectoequipos.proyectoequipo_cancelado
                                                        FROM
                                                            proyectoequipos
                                                        WHERE
                                                            proyectoequipos.proyecto_id = '.$request->proyecto_id.'
                                                        ORDER BY
                                                            proyectoequipos.proyectoequipo_revision DESC
                                                        LIMIT 1');

                    if (count($equiposlistastotal) > 0)
                    {
                        $no_revision = ($equiposlistastotal[0]->proyectoequipo_revision + 1);
                    }

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if ($request->checkbox_autorizale != NULL)
                    {
                        $autorizado = 1;
                        $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $autorizadofecha = date('Y-m-d H:i:s');
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if ($request->checkbox_cancelale != NULL)
                    {
                        $cancelado = 1;
                        $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $canceladofecha = date('Y-m-d H:i:s');
                        $canceladoobservacion = $request->proyectoequipo_canceladoobservacion;
                    }

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectoequipos AUTO_INCREMENT = 1;');

                    // Crear lista
                    $proyectoequipolista = proyectoequipoModel::create([
                          'proyecto_id' => $request->proyecto_id
                        , 'proyectoequipo_revision' => $no_revision
                        , 'proyectoequipo_autorizado' => $autorizado
                        , 'proyectoequipo_autorizadonombre' => $autorizadonombre
                        , 'proyectoequipo_autorizadofecha' => $autorizadofecha
                        , 'proyectoequipo_cancelado' => $cancelado
                        , 'proyectoequipo_canceladonombre' => $canceladonombre
                        , 'proyectoequipo_canceladofecha' => $canceladofecha
                        , 'proyectoequipo_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Consultar lista de equipos actual
                    $equiposactual = DB::select('SELECT
                                                    proyectoequiposactual.proyecto_id,
                                                    proyectoequiposactual.proveedor_id,
                                                    proyectoequiposactual.equipo_id 
                                                FROM
                                                    proyectoequiposactual
                                                WHERE
                                                    proyectoequiposactual.proyecto_id = '.$request->proyecto_id);

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectoequiposhistorial AUTO_INCREMENT = 1;');

                    // Guardar lista de equipos actual en historial
                    foreach ($equiposactual as $key => $value)
                    {
                        $equiposhistorial = proyectoequipohistorialModel::create([
                              'proyecto_id' => $value->proyecto_id
                            , 'proveedor_id' => $value->proveedor_id
                            , 'proyectoequipo_revision' => $no_revision
                            , 'equipo_id' => $value->equipo_id
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else // EDITAR LISTA
                {
                    // Obtener lista de equipos
                    $proyectoequipolista = proyectoequipoModel::findOrFail($request->equiposlista_id);

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if (($proyectoequipolista->proyectoequipo_autorizado + 0) == 1)
                    {
                        // if ($request->checkbox_autorizale != NULL)
                        // {
                            $autorizado = 1;
                            $autorizadonombre = $proyectoequipolista->proyectoequipo_autorizadonombre;
                            $autorizadofecha = $proyectoequipolista->proyectoequipo_autorizadofecha;
                        // }
                    }
                    else
                    {
                        if ($request->checkbox_autorizale != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $autorizadofecha = date('Y-m-d H:i:s');
                        }
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if (($proyectoequipolista->proyectoequipo_cancelado + 0) == 1)
                    {
                        if ($request->checkbox_cancelale != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = $proyectoequipolista->proyectoequipo_canceladonombre;
                            $canceladofecha = $proyectoequipolista->proyectoequipo_canceladofecha;
                            $canceladoobservacion = $proyectoequipolista->proyectoequipo_canceladoobservacion;
                        }
                    }
                    else
                    {
                        if ($request->checkbox_cancelale != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $canceladofecha = date('Y-m-d H:i:s');
                            $canceladoobservacion = $request->proyectoequipo_canceladoobservacion;
                        }
                    }

                    // Modificar
                    $proyectoequipolista->update([
                          // 'proyecto_id' => $request->XXXXXXXXX
                        // , 'proyectoequipo_revision' => $request->XXXXXXXXX
                          'proyectoequipo_autorizado' => $autorizado
                        , 'proyectoequipo_autorizadonombre' => $autorizadonombre
                        , 'proyectoequipo_autorizadofecha' => $autorizadofecha
                        , 'proyectoequipo_cancelado' => $cancelado
                        , 'proyectoequipo_canceladonombre' => $canceladonombre
                        , 'proyectoequipo_canceladofecha' => $canceladofecha
                        , 'proyectoequipo_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos actualizados correctamente';
                }

                // respuesta
                $dato["folios"] = $folios;
                $dato["proyectoequipolista"] = $proyectoequipolista;
            }

            // respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["folios"] = 0;
            $dato["proyectoequipolista"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
