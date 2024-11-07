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
use App\modelos\proyecto\proyectosignatarioModel;
use App\modelos\proyecto\proyectosignatarioactualModel;
use App\modelos\proyecto\proyectosignatariohistorialModel;
use App\modelos\proyecto\proyectosignatariosobservacionModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectosignatarioController extends Controller
{





    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }


    public function proyectosignatariosinventario($proyecto_id)
    {
        try
        {
            $where_adicional = '';
            if (auth()->user()->hasRoles(['Externo']))
            {
                $where_adicional = 'AND proyectoproveedores.proveedor_id = '.auth()->user()->empleado_id;
            }

            $signatarios = DB::select('SELECT
                                            TABLAPROVEEDORES.proyecto_id,
                                            TABLAPROVEEDORES.proveedor_id,
                                            TABLAPROVEEDORES.proveedor_NombreComercial,
                                            -- signatario.*,
                                            signatario.id,
                                            signatario.signatario_EstadoActivo,
                                            IF(signatario.signatario_EstadoActivo = 1, "Si", "No") AS disponibilidad,
                                            signatario.signatario_Nombre,
                                            IFNULL((
                                                SELECT
                                                    IF(proyectosignatariosactual.signatario_id , "checked", "")
                                                FROM
                                                    proyectosignatariosactual
                                                WHERE
                                                    proyectosignatariosactual.proyecto_id = TABLAPROVEEDORES.proyecto_id AND proyectosignatariosactual.signatario_id = signatario.id
                                                LIMIT 1
                                            ), "") AS checked
                                        FROM
                                            (
                                                SELECT
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial 
                                                FROM
                                                    proyectoproveedores
                                                    LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id 
                                                WHERE
                                                    proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                                    '.$where_adicional.'
                                                GROUP BY
                                                    proyectoproveedores.proyecto_id,
                                                    proyectoproveedores.proveedor_id,
                                                    proveedor.proveedor_NombreComercial
                                                ORDER BY
                                                    proveedor.proveedor_NombreComercial ASC
                                            ) AS TABLAPROVEEDORES
                                            LEFT JOIN signatario ON TABLAPROVEEDORES.proveedor_id = signatario.proveedor_id
                                        WHERE
                                            -- signatario.signatario_EstadoActivo = 1 AND
                                            signatario.signatario_Eliminado = 0
                                        GROUP BY
                                            TABLAPROVEEDORES.proyecto_id,
                                            TABLAPROVEEDORES.proveedor_id,
                                            TABLAPROVEEDORES.proveedor_NombreComercial,
                                            signatario.id,
                                            signatario.signatario_EstadoActivo,
                                            signatario.signatario_Nombre
                                        ORDER BY
                                            TABLAPROVEEDORES.proveedor_NombreComercial ASC,
                                            signatario.signatario_EstadoActivo DESC,
                                            signatario.signatario_Nombre ASC');

            $numero_registro = 0;
            $listasignatarios = '';
            foreach ($signatarios as $key => $value)
            {
                // consulta acreditaciones por signatario
                $listaalcances = '';
                $alcances = DB::select('SELECT
                                            acreditacionalcance.acreditacionAlcance_agente
                                            -- GROUP_CONCAT(acreditacionalcance.acreditacionAlcance_agente) AS acreditacionAlcance_agente
                                        FROM
                                            signatarioacreditacion
                                            LEFT JOIN acreditacionalcance ON signatarioacreditacion.signatarioAcreditacion_Alcance = acreditacionalcance.id
                                        WHERE
                                            signatarioacreditacion.signatario_id = '.$value->id.'
                                            AND signatarioacreditacion.cat_signatariodisponibilidad_id = 1
                                            AND signatarioacreditacion.signatarioAcreditacion_Eliminado = 0
                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                        GROUP BY
                                            acreditacionalcance.acreditacionAlcance_agente
                                        ORDER BY
                                            acreditacionalcance.acreditacionAlcance_agente ASC');

                foreach ($alcances as $key1 => $value1)
                {
                    if (($key1 + 0) == 0)
                    {
                        $listaalcances .= '• '.$value1->acreditacionAlcance_agente;
                    }
                    else
                    {
                        $listaalcances .= ' • '.$value1->acreditacionAlcance_agente;
                    }
                }


                // consulta acreditaciones por signatario
                $listaacreditaciones = '';
                $acreditaciones = DB::select('SELECT
                                                    TABLA1.acreditacion
                                                FROM
                                                    (
                                                        SELECT
                                                            signatarioacreditacion.signatarioAcreditacion_Eliminado,
                                                            signatarioacreditacion.cat_signatariodisponibilidad_id,
                                                            acreditacionalcance.acreditacionAlcance_Eliminado,
                                                            acreditacion.acreditacion_Eliminado,
                                                            IFNULL(( 
                                                                CASE
                                                                    WHEN (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) > 90) THEN CONCAT(acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"))
                                                                    WHEN (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) > 30) THEN CONCAT("<b style=color:#ffae00;>", acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"), " (", DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()), " d)", "</b>")
                                                                    ELSE CONCAT("<b style=color:#F00;>", acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"), " (", DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()), " d)", "</b>")
                                                                END
                                                            ), "Acreditacion: N/A") AS acreditacion
                                                        FROM
                                                            signatarioacreditacion
                                                            INNER JOIN acreditacionalcance ON signatarioacreditacion.signatarioAcreditacion_Alcance = acreditacionalcance.id
                                                            LEFT JOIN acreditacion ON acreditacionalcance.acreditacion_id = acreditacion.id
                                                        WHERE
                                                            signatarioacreditacion.signatario_id = '.$value->id.'
                                                    ) AS TABLA1
                                                WHERE
                                                    TABLA1.signatarioAcreditacion_Eliminado = 0
                                                    AND TABLA1.cat_signatariodisponibilidad_id = 1
                                                    AND IF(TABLA1.acreditacion = "Acreditacion: N/A", TABLA1.acreditacionAlcance_Eliminado = 0, TABLA1.acreditacionAlcance_Eliminado = 0 AND TABLA1.acreditacion_Eliminado = 0)
                                                GROUP BY
                                                    TABLA1.acreditacion');

                foreach ($acreditaciones as $key2 => $value2)
                {
                    $listaacreditaciones .= '<li>'.$value2->acreditacion.'</li>';
                }

                // dibujar filas
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                $value->proveedor_NombreComercial;
                $value->disponibilidad;

                if (($value->signatario_EstadoActivo+0) == 1)
                {
                    $value->checkbox = '<div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" class="checkbox_proyectosignatarios" name="signatario[]" value="'.$value->proveedor_id.'-'.$value->id.'" '.$value->checked.'>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>';
                }
                else
                {
                    $value->checkbox = '<i class="fa fa-ban"></i>';
                }
                
                $value->signatario_Nombre = $value->signatario_Nombre;
                $value->signatario_alcances = '<div style="text-align: justify;">'.$listaalcances.'</div>';
                $value->signatario_acreditaciones = $listaacreditaciones;
            }

            // Respuesta
            $dato["data"] = $signatarios;
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



    public function proyectosignatariosinventarioPsico($proyecto_id)
    {
        try {
            $where_adicional = '';
            if (auth()->user()->hasRoles(['Externo'])) {
                $where_adicional = 'AND proyectoproveedores.proveedor_id = ' . auth()->user()->empleado_id;
            }

            $signatarios = DB::select('SELECT
                                        TABLAPROVEEDORES.proyecto_id,
                                        TABLAPROVEEDORES.proveedor_id,
                                        TABLAPROVEEDORES.proveedor_NombreComercial,-- signatario.*,
                                        signatario.id,
                                        signatario.signatario_EstadoActivo,
                                    IF
                                        ( signatario.signatario_EstadoActivo = 1, "Si", "No" ) AS disponibilidad,
                                        signatario.signatario_Nombre,
                                        IFNULL((
                                            SELECT
                                            IF
                                                ( proyectosignatariosactual.signatario_id, "checked", "" ) 
                                            FROM
                                                proyectosignatariosactual 
                                            WHERE
                                                proyectosignatariosactual.proyecto_id = TABLAPROVEEDORES.proyecto_id 
                                                AND proyectosignatariosactual.signatario_id = signatario.id 
                                                LIMIT 1 
                                                ),
                                            "" 
                                        ) AS checked 
                                    FROM
                                        (
                                        SELECT
                                            ? as proyecto_id,
                                            proyectoproveedores.id as proveedor_id,
                                            proyectoproveedores.proveedor_NombreComercial 
                                        FROM
                                            proveedor proyectoproveedores
                                        WHERE
                                            proyectoproveedores.id = 1
                                        GROUP BY
                                            proyecto_id,
                                            proveedor_id,
                                            proyectoproveedores.proveedor_NombreComercial 
                                        ORDER BY
                                            proyectoproveedores.proveedor_NombreComercial ASC 
                                        ) AS TABLAPROVEEDORES
                                        LEFT JOIN signatario ON TABLAPROVEEDORES.proveedor_id = signatario.proveedor_id 
                                        WHERE-- signatario.signatario_EstadoActivo = 1 AND
                                        signatario.signatario_Eliminado = 0 
                                    GROUP BY
                                        TABLAPROVEEDORES.proyecto_id,
                                        TABLAPROVEEDORES.proveedor_id,
                                        TABLAPROVEEDORES.proveedor_NombreComercial,
                                        signatario.id,
                                        signatario.signatario_EstadoActivo,
                                        signatario.signatario_Nombre 
                                    ORDER BY
                                        TABLAPROVEEDORES.proveedor_NombreComercial ASC,
                                        signatario.signatario_EstadoActivo DESC,
                                        signatario.signatario_Nombre ASC', [$proyecto_id]);

            $numero_registro = 0;
            $listasignatarios = '';
            foreach ($signatarios as $key => $value) {
                // consulta acreditaciones por signatario
                $listaalcances = '';
                $alcances = DB::select('SELECT
                                            acreditacionalcance.acreditacionAlcance_agente
                                            -- GROUP_CONCAT(acreditacionalcance.acreditacionAlcance_agente) AS acreditacionAlcance_agente
                                        FROM
                                            signatarioacreditacion
                                            LEFT JOIN acreditacionalcance ON signatarioacreditacion.signatarioAcreditacion_Alcance = acreditacionalcance.id
                                        WHERE
                                            signatarioacreditacion.signatario_id = ' . $value->id . '
                                            AND signatarioacreditacion.cat_signatariodisponibilidad_id = 1
                                            AND signatarioacreditacion.signatarioAcreditacion_Eliminado = 0
                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                        GROUP BY
                                            acreditacionalcance.acreditacionAlcance_agente
                                        ORDER BY
                                            acreditacionalcance.acreditacionAlcance_agente ASC');

                foreach ($alcances as $key1 => $value1) {
                    if (($key1 + 0) == 0) {
                        $listaalcances .= '• ' . $value1->acreditacionAlcance_agente;
                    } else {
                        $listaalcances .= ' • ' . $value1->acreditacionAlcance_agente;
                    }
                }


                // consulta acreditaciones por signatario
                $listaacreditaciones = '';
                $acreditaciones = DB::select('SELECT
                                                    TABLA1.acreditacion
                                                FROM
                                                    (
                                                        SELECT
                                                            signatarioacreditacion.signatarioAcreditacion_Eliminado,
                                                            signatarioacreditacion.cat_signatariodisponibilidad_id,
                                                            acreditacionalcance.acreditacionAlcance_Eliminado,
                                                            acreditacion.acreditacion_Eliminado,
                                                            IFNULL(( 
                                                                CASE
                                                                    WHEN (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) > 90) THEN CONCAT(acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"))
                                                                    WHEN (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) > 30) THEN CONCAT("<b style=color:#ffae00;>", acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"), " (", DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()), " d)", "</b>")
                                                                    ELSE CONCAT("<b style=color:#F00;>", acreditacion.acreditacion_Entidad, " / ", acreditacion.acreditacion_Numero, " / ", DATE_FORMAT(acreditacion.acreditacion_Vigencia, "%d-%m-%Y"), " (", DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()), " d)", "</b>")
                                                                END
                                                            ), "Acreditacion: N/A") AS acreditacion
                                                        FROM
                                                            signatarioacreditacion
                                                            INNER JOIN acreditacionalcance ON signatarioacreditacion.signatarioAcreditacion_Alcance = acreditacionalcance.id
                                                            LEFT JOIN acreditacion ON acreditacionalcance.acreditacion_id = acreditacion.id
                                                        WHERE
                                                            signatarioacreditacion.signatario_id = ' . $value->id . '
                                                    ) AS TABLA1
                                                WHERE
                                                    TABLA1.signatarioAcreditacion_Eliminado = 0
                                                    AND TABLA1.cat_signatariodisponibilidad_id = 1
                                                    AND IF(TABLA1.acreditacion = "Acreditacion: N/A", TABLA1.acreditacionAlcance_Eliminado = 0, TABLA1.acreditacionAlcance_Eliminado = 0 AND TABLA1.acreditacion_Eliminado = 0)
                                                GROUP BY
                                                    TABLA1.acreditacion');

                foreach ($acreditaciones as $key2 => $value2) {
                    $listaacreditaciones .= '<li>' . $value2->acreditacion . '</li>';
                }

                // dibujar filas
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                $value->proveedor_NombreComercial;
                $value->disponibilidad;

                if (($value->signatario_EstadoActivo + 0) == 1) {
                    $value->checkbox = '<div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" class="checkbox_proyectosignatarios" name="signatario[]" value="' . $value->proveedor_id . '-' . $value->id . '" ' . $value->checked . '>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>';
                } else {
                    $value->checkbox = '<i class="fa fa-ban"></i>';
                }

                $value->signatario_Nombre = $value->signatario_Nombre;
                $value->signatario_alcances = '<div style="text-align: justify;">' . $listaalcances . '</div>';
                $value->signatario_acreditaciones = $listaacreditaciones;
            }

            // Respuesta
            $dato["data"] = $signatarios;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // Respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
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
    public function proyectosignatarioslistas($proyecto_id)
    {
        try
        {
            $signatarioslistas = DB::select('SELECT
                                                proyectosignatarios.id,
                                                proyectosignatarios.proyecto_id,
                                                (
                                                    SELECT
                                                        proyecto.proyecto_folio
                                                    FROM
                                                        proyecto
                                                    WHERE
                                                        proyecto.id = proyectosignatarios.proyecto_id
                                                ) AS proyecto_folio,
                                                (
                                                    SELECT
                                                        proyectoordentrabajo.proyectoordentrabajo_folio
                                                    FROM
                                                        proyectoordentrabajo
                                                    WHERE
                                                        proyectoordentrabajo.proyecto_id = proyectosignatarios.proyecto_id
                                                    ORDER BY
                                                        proyectoordentrabajo.proyectoordentrabajo_revision ASC
                                                    LIMIT 1
                                                ) AS ordentrabajo_folio,
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
                                                proyectosignatarios.proyectosignatario_revision ASC');

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($signatarioslistas as $key => $value)
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Numero de revision
                if (($value->proyectosignatario_revision+0) > 0)
                {
                    $value->revision = '<b>Lista de signatarios Rev-'.$value->proyectosignatario_revision.'</b><br>'.$value->created_at;
                }
                else
                {
                    $value->revision = '<b>Lista de signatarios</b><br>'.$value->created_at;
                }

                // Diseño Autorizado
                if (($value->proyectosignatario_autorizado+0) == 1)
                {
                    $value->autorizado = $value->proyectosignatario_autorizadonombre.'<br>'.$value->proyectosignatario_autorizadofecha;
                }
                else
                {
                    $value->autorizado = '<b class="text-danger"><i class="fa fa-ban"></i> Pendiente</b>';
                }

                // Diseño estado
                if (($value->proyectosignatario_cancelado+0) == 1)
                {
                    $value->estado = '<b class="text-danger">Cancelado</b>';
                    $value->cancelado = $value->proyectosignatario_canceladonombre.'<br>'.$value->proyectosignatario_canceladofecha;
                }
                else
                {
                    $value->estado = '<b class="text-info">Vigente</b>';
                    $value->cancelado = 'N/A';
                }
            }

            
            // Respuesta
            $dato["data"] = $signatarioslistas;
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
    public function proyectosignatariosgenerarlistaestado($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RES-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $cancelado = 1;

            $signatarioslistastotal = DB::select('SELECT
                                                    proyectosignatarios.id,
                                                    proyectosignatarios.proyecto_id,
                                                    proyectosignatarios.proyectosignatario_revision,
                                                    proyectosignatarios.proyectosignatario_autorizadofecha,
                                                    proyectosignatarios.proyectosignatario_cancelado
                                                FROM
                                                    proyectosignatarios
                                                WHERE
                                                    proyectosignatarios.proyecto_id = '.$proyecto_id.'
                                                ORDER BY
                                                    proyectosignatarios.proyectosignatario_revision DESC
                                                LIMIT 1');

            if (count($signatarioslistastotal) > 0)
            {
                $no_revision = ($signatarioslistastotal[0]->proyectosignatario_revision + 1);
                $no_revision_texto = ' Rev-'.$no_revision;

                $cancelado = ($signatarioslistastotal[0]->proyectosignatario_cancelado + 0);
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
    public function proyectosignatariosconsultaractual($proyecto_id){
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RES-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Numero de revision
            $no_revision = 0;
            $no_revision_texto = '';
            $signatarioslistastotal = DB::select('SELECT
                                                    proyectosignatarios.id,
                                                    proyectosignatarios.proyecto_id,
                                                    proyectosignatarios.proyectosignatario_revision,
                                                    proyectosignatarios.proyectosignatario_autorizado,
                                                    proyectosignatarios.proyectosignatario_cancelado
                                                FROM
                                                    proyectosignatarios
                                                WHERE
                                                    proyectosignatarios.proyecto_id = '.$proyecto_id.'
                                                ORDER BY
                                                    proyectosignatarios.proyectosignatario_revision DESC
                                                LIMIT 1');

            if (count($signatarioslistastotal) > 0)
            {
                $no_revision = ($signatarioslistastotal[0]->proyectosignatario_revision + 1);
                $no_revision_texto = ' Rev-'.$no_revision;
            }

            // Datos de la lista nueva de signatarios
            $signatarioslista = array(
                                 'proyecto_id' => $proyecto_id
                                ,'proyectosignatario_revision' => $no_revision
                                ,'proyectosignatario_autorizado' => 0
                                ,'proyectosignatario_autorizadonombre' => NULL
                                ,'proyectosignatario_autorizadofecha' => NULL
                                ,'proyectosignatario_cancelado' => 0
                                ,'proyectosignatario_canceladonombre' => NULL
                                ,'proyectosignatario_canceladofecha' => NULL
                                ,'proyectosignatario_canceladoobservacion' => NULL
                                ,'created_at' => date('Y-m-d H:m:s')
                                ,'updated_at' => date('Y-m-d H:m:s')
                            );

            // Consulta signatarios historial
            DB::statement("SET lc_time_names = 'es_MX';");
            $signatarios = DB::select('SELECT
                                            proyectosignatariosactual.proyecto_id,
                                            proveedor.proveedor_RazonSocial,
                                            proveedor.proveedor_NombreComercial,
                                            proyectosignatariosactual.signatario_id,
                                            signatario.signatario_Nombre,
                                            signatario.signatario_Cargo,
                                            signatario.signatario_Rfc, 
                                            IFNULL((
                                                SELECT
                                                    -- signatariocurso.signatario_id,
                                                    -- signatariocurso.id,
                                                    -- signatariocurso.signatarioCurso_NombreCurso,
                                                    -- signatariocurso.signatarioCurso_FechaExpedicion,
                                                    -- signatariocurso.signatarioCurso_FechaVigencia,
                                                    signatariocurso.signatarioCurso_FolioCurso
                                                FROM
                                                    signatariocurso
                                                WHERE
                                                    signatariocurso.signatario_id = proyectosignatariosactual.signatario_id
                                                    AND signatariocurso.signatarioCurso_Eliminado = 0
                                                    AND signatariocurso.signatarioCurso_NombreCurso LIKE "Libreta%"
                                                LIMIT 1
                                            ), "-") AS libreta_mar
                                        FROM
                                            proyectosignatariosactual
                                            LEFT JOIN proveedor ON proyectosignatariosactual.proveedor_id = proveedor.id
                                            LEFT JOIN signatario ON proyectosignatariosactual.signatario_id = signatario.id
                                        WHERE
                                            proyectosignatariosactual.proyecto_id = '.$proyecto_id.'
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
          
            return \PDF::loadView('reportes.proyecto.reporteproyectolistasignatarios', compact('proyecto', 'signatarioslista', 'signatarios'))->stream($ot_folio.' Lista de signatarios'.$no_revision_texto.'.pdf');
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
     * @param  int  $proyectosignatarios_revision
     * @return \Illuminate\Http\Response
     */
    public function proyectosignatariosconsultarhistorial($proyecto_id, $proyectosignatarios_revision)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Obtener folio
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RES-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
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
                                            AND proyectosignatarios.proyectosignatario_revision = '.$proyectosignatarios_revision.'
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
            if (($proyectosignatarios_revision+0) > 0)
            {
                $documento_nombre = 'Lista de signatarios rev-'.$proyectosignatarios_revision;
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
                                            signatario.signatario_Cargo,
                                            signatario.signatario_Rfc, 
                                            IFNULL((
                                                SELECT
                                                    -- signatariocurso.signatario_id,
                                                    -- signatariocurso.id,
                                                    -- signatariocurso.signatarioCurso_NombreCurso,
                                                    -- signatariocurso.signatarioCurso_FechaExpedicion,
                                                    -- signatariocurso.signatarioCurso_FechaVigencia,
                                                    signatariocurso.signatarioCurso_FolioCurso
                                                FROM
                                                    signatariocurso
                                                WHERE
                                                    signatariocurso.signatario_id = proyectosignatarioshistorial.signatario_id
                                                    AND signatariocurso.signatarioCurso_Eliminado = 0
                                                    AND signatariocurso.signatarioCurso_NombreCurso LIKE "%Libreta%"
                                                LIMIT 1
                                            ), "-") AS libreta_mar
                                        FROM
                                            proyectosignatarioshistorial
                                            LEFT JOIN proveedor ON proyectosignatarioshistorial.proveedor_id = proveedor.id
                                            LEFT JOIN signatario ON proyectosignatarioshistorial.signatario_id = signatario.id
                                        WHERE
                                            proyectosignatarioshistorial.proyecto_id = '.$proyecto_id.'
                                            AND proyectosignatarioshistorial.proyectosignatario_revision = '.$proyectosignatarios_revision.'
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if (($request->opcion + 0) == 0) // ASIGNAR SIGNATARIOS AL PROYECTO
            {
                if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                {
                    if ($request->signatario)
                    {
                        // Eliminar lista de signatarios actuales
                        $eliminar_signatarios = proyectosignatarioactualModel::where('proyecto_id',  $request->proyecto_id)->delete();

                        foreach ($request->signatario as $key => $value) 
                        {
                            // Separar datos [proveedor_id - signatario_id]
                            $valor = explode("-", $value);

                            $guardar_signatarios = proyectosignatarioactualModel::create([
                                  'proyecto_id' => $request->proyecto_id
                                , 'proveedor_id' => $valor[0]
                                , 'signatario_id' => $valor[1]
                            ]);
                        }
                    }

                    // mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    // Disponibilidad para guardar lista
                    $disponibilidadsignatarios = 1;
                    $signatarioslistaestado = DB::select('SELECT
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
                                                                proyectosignatarios.proyecto_id = '.$request->proyecto_id.'
                                                            ORDER BY
                                                                proyectosignatarios.proyectosignatario_revision DESC
                                                            LIMIT 1');

                    if (count($signatarioslistaestado) > 0)
                    {
                        $disponibilidadsignatarios = ($signatarioslistaestado[0]->disponibilidadsignatarios + 0);
                    }

                    // Acccion segun estado
                    if ($disponibilidadsignatarios == 1)
                    {
                        if ($request->signatario)
                        {
                            // Eliminar lista de signatarios actuales
                            $eliminar_signatarios = proyectosignatarioactualModel::where('proyecto_id',  $request->proyecto_id)->where('proveedor_id',  auth()->user()->empleado_id)->delete();

                            foreach ($request->signatario as $key => $value) 
                            {
                                // Separar datos [proveedor_id - signatario_id]
                                $valor = explode("-", $value);

                                $guardar_signatarios = proyectosignatarioactualModel::create([
                                      'proyecto_id' => $request->proyecto_id
                                    , 'proveedor_id' => $valor[0]
                                    , 'signatario_id' => $valor[1]
                                ]);
                            }
                        }

                        // Guardar observacion
                        if ($request->proyectosignatariosobservacion)
                        {
                            // Eliminar historial
                            $eliminar_observacion = proyectosignatariosobservacionModel::where('proyecto_id', $request->proyecto_id)
                                                                                        ->where('proveedor_id', auth()->user()->empleado_id)
                                                                                        ->delete();

                            // Guardar
                            $guardar_observacion = proyectosignatariosobservacionModel::create([
                                  'proyecto_id' => $request->proyecto_id
                                , 'proveedor_id' => auth()->user()->empleado_id
                                , 'proyectosignatariosobservacion' => $request->proyectosignatariosobservacion
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
                    $dato["disponibilidadsignatarios"] = $disponibilidadsignatarios;
                }
            }
            else // LISTAS DE SIGNATARIOS CREAR / EDITAR
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


                if (($request->signatarioslista_id) == 0) // NUEVA LISTA
                {
                    // Numero de revision
                    $no_revision = 0;
                    $signatarioslistastotal = DB::select('SELECT
                                                            proyectosignatarios.id,
                                                            proyectosignatarios.proyecto_id,
                                                            proyectosignatarios.proyectosignatario_revision,
                                                            proyectosignatarios.proyectosignatario_autorizado,
                                                            proyectosignatarios.proyectosignatario_cancelado
                                                        FROM
                                                            proyectosignatarios
                                                        WHERE
                                                            proyectosignatarios.proyecto_id = '.$request->proyecto_id.'
                                                        ORDER BY
                                                            proyectosignatarios.proyectosignatario_revision DESC
                                                        LIMIT 1');

                    if (count($signatarioslistastotal) > 0)
                    {
                        $no_revision = ($signatarioslistastotal[0]->proyectosignatario_revision + 1);
                    }

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if ($request->checkbox_autorizals != NULL)
                    {
                        $autorizado = 1;
                        $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $autorizadofecha = date('Y-m-d H:i:s');
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if ($request->checkbox_cancelals != NULL)
                    {
                        $cancelado = 1;
                        $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                        $canceladofecha = date('Y-m-d H:i:s');
                        $canceladoobservacion = $request->proyectosignatario_canceladoobservacion;
                    }

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectosignatarios AUTO_INCREMENT = 1;');

                    // Crear lista
                    $proyectosignatariolista = proyectosignatarioModel::create([
                          'proyecto_id' => $request->proyecto_id
                        , 'proyectosignatario_revision' => $no_revision
                        , 'proyectosignatario_autorizado' => $autorizado
                        , 'proyectosignatario_autorizadonombre' => $autorizadonombre
                        , 'proyectosignatario_autorizadofecha' => $autorizadofecha
                        , 'proyectosignatario_cancelado' => $cancelado
                        , 'proyectosignatario_canceladonombre' => $canceladonombre
                        , 'proyectosignatario_canceladofecha' => $canceladofecha
                        , 'proyectosignatario_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Consultar lista de signatarios actual
                    $signatariosactual = DB::select('SELECT
                                                    proyectosignatariosactual.proyecto_id,
                                                    proyectosignatariosactual.proveedor_id,
                                                    proyectosignatariosactual.signatario_id 
                                                FROM
                                                    proyectosignatariosactual
                                                WHERE
                                                    proyectosignatariosactual.proyecto_id = '.$request->proyecto_id);

                    // AUTO_INCREMENT
                    DB::statement('ALTER TABLE proyectosignatarioshistorial AUTO_INCREMENT = 1;');

                    // Guardar lista de signatarios actual en historial
                    foreach ($signatariosactual as $key => $value)
                    {
                        $signatarioshistorial = proyectosignatariohistorialModel::create([
                              'proyecto_id' => $value->proyecto_id
                            , 'proveedor_id' => $value->proveedor_id
                            , 'proyectosignatario_revision' => $no_revision
                            , 'signatario_id' => $value->signatario_id
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else // EDITAR LISTA
                {
                    // Obtener lista de signatarios
                    $proyectosignatariolista = proyectosignatarioModel::findOrFail($request->signatarioslista_id);

                    // Valida si viene AUTORIZADO
                    $autorizado = 0; $autorizadonombre = NULL; $autorizadofecha = NULL;
                    if (($proyectosignatariolista->proyectosignatario_autorizado + 0) == 1)
                    {
                        // if ($request->checkbox_autorizals != NULL)
                        // {
                            $autorizado = 1;
                            $autorizadonombre = $proyectosignatariolista->proyectosignatario_autorizadonombre;
                            $autorizadofecha = $proyectosignatariolista->proyectosignatario_autorizadofecha;
                        // }
                    }
                    else
                    {
                        if ($request->checkbox_autorizals != NULL)
                        {
                            $autorizado = 1;
                            $autorizadonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $autorizadofecha = date('Y-m-d H:i:s');
                        }
                    }

                    // Valida si viene CANCELADO
                    $cancelado = 0; $canceladonombre = NULL; $canceladofecha = NULL; $canceladoobservacion = NULL;
                    if (($proyectosignatariolista->proyectosignatario_cancelado + 0) == 1)
                    {
                        if ($request->checkbox_cancelals != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = $proyectosignatariolista->proyectosignatario_canceladonombre;
                            $canceladofecha = $proyectosignatariolista->proyectosignatario_canceladofecha;
                            $canceladoobservacion = $proyectosignatariolista->proyectosignatario_canceladoobservacion;
                        }
                    }
                    else
                    {
                        if ($request->checkbox_cancelals != NULL)
                        {
                            $cancelado = 1;
                            $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                            $canceladofecha = date('Y-m-d H:i:s');
                            $canceladoobservacion = $request->proyectosignatario_canceladoobservacion;
                        }
                    }

                    // Modificar
                    $proyectosignatariolista->update([
                          // 'proyecto_id' => $request->XXXXXXXXX
                        // , 'proyectosignatario_revision' => $request->XXXXXXXXX
                          'proyectosignatario_autorizado' => $autorizado
                        , 'proyectosignatario_autorizadonombre' => $autorizadonombre
                        , 'proyectosignatario_autorizadofecha' => $autorizadofecha
                        , 'proyectosignatario_cancelado' => $cancelado
                        , 'proyectosignatario_canceladonombre' => $canceladonombre
                        , 'proyectosignatario_canceladofecha' => $canceladofecha
                        , 'proyectosignatario_canceladoobservacion' => $canceladoobservacion
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos actualizados correctamente';
                }

                // respuesta
                $dato["folios"] = $folios;
                $dato["proyectosignatariolista"] = $proyectosignatariolista;
            }

            // respuesta
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["folios"] = 0;
            $dato["proyectosignatariolista"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }

    




}
