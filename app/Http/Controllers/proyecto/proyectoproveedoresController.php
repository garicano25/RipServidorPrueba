<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\proyecto\proyectoproveedoresModel;
// use App\modelos\proyecto\proyectoproveedoresquimicosModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoproveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proyectoproveedorestodos()
    {
        try {
            $proveedores = DB::select('SELECT
                                            proveedor.id,
                                            proveedor.proveedor_NombreComercial
                                        FROM
                                            proveedor
                                        WHERE
                                            proveedor.proveedor_Eliminado = 0');

            // lista proveedores
            $select_lista_proveedores = '<option value="">&nbsp;</option>';
            foreach ($proveedores as $key => $value) {
                $select_lista_proveedores .= '<option value="' . $value->id . '">' . $value->proveedor_NombreComercial . '</option>';
            }

            // respuesta
            $dato['select_lista_proveedores'] = $select_lista_proveedores;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['select_lista_proveedores'] = '<option value="0">Error al consultar proveedores</option>';
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoproveedoralcances($proveedor_id)
    {
        try {
            $alcances = DB::select('SELECT
                                        TABLA.tipo,
                                        TABLA.acreditacionalcance_id,
                                        TABLA.proveedor_id,
                                        TABLA.agente_id,
                                        TABLA.agente_nombre,
                                        TABLA.activo
                                    FROM
                                        (
                                            (
                                                SELECT
                                                     1 AS tipo,
                                                    acreditacionalcance.id AS acreditacionalcance_id,
                                                    acreditacionalcance.proveedor_id,
                                                    acreditacionalcance.prueba_id AS agente_id,
                                                    IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre, NULL AS activo 
                                                FROM
                                                    acreditacionalcance
                                                WHERE
                                                    acreditacionalcance.proveedor_id = ' . $proveedor_id . '
                                                    AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                            )
                                            UNION ALL
                                            (
                                                SELECT
                                                2 AS tipo,
                                                0 AS acreditacionalcance_id,
                                                servicio.proveedor_id,
                                                servicioprecios.agente_id,
                                                servicioprecios.agente_nombre,
                                                servicioprecios.ACTIVO_PARTIDAPROVEEDOR AS activo
                                            FROM
                                                    servicio
                                                    LEFT JOIN servicioprecios ON servicio.id = servicioprecios.servicio_id
                                                WHERE
                                                    servicio.proveedor_id = ' . $proveedor_id . '
                                                    AND servicioprecios.agente_id = 0
                                                    AND servicio.servicio_Eliminado = 0
                                               	AND servicio.ACTIVO_COTIZACIONPROVEEDOR = 1
                                                AND servicioprecios.ACTIVO_PARTIDAPROVEEDOR = 1
                                                
                                            GROUP BY
                                                servicio.proveedor_id,
                                                servicioprecios.agente_id,
                                                servicioprecios.agente_nombre,
                                                servicioprecios.ACTIVO_PARTIDAPROVEEDOR
                                            )
                                        ) AS TABLA
                                    ORDER BY
                                        TABLA.tipo ASC,
                                        TABLA.agente_nombre ASC');

            // lista alcances
            $select_lista_proveedoralcances = '<option value="">&nbsp;</option>';
            foreach ($alcances as $key => $value) {
                if ($value->tipo == 1) {
                    $select_lista_proveedoralcances .= '<option value="' . $value->agente_id . '">' . $value->agente_nombre . '</option>';
                } else {
                    $select_lista_proveedoralcances .= '<option value="' . $value->agente_id . '" class="text-info">' . $value->agente_nombre . '</option>';
                }
            }

            // respuesta
            $dato['select_lista_proveedoralcances'] = $select_lista_proveedoralcances;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['select_lista_proveedoralcances'] = '<option value="0">Error al consultar alcances</option>';
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $recsensorial_id
     * @param  int  $recsensorial_alcancefisico
     * @param  int  $recsensorial_alcancequimico
     * @return \Illuminate\Http\Response
     */
    public function proyectoproveedoreslista($proyecto_id, $recsensorial_id, $recsensorial_alcancefisico, $recsensorial_alcancequimico)
    {
        try {
            $numero_registros = 0;
            $filas = '';

            // Resumen de fisicos COMPLETO
            if (($recsensorial_alcancefisico + 0) == 1) {
                $fisicos = DB::select('CALL sp_resumen_fisico_completo_programaHI(?,?)', [$recsensorial_id, $proyecto_id]);

                foreach ($fisicos as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';
                    $checked = '';
                    $puntos = 0;

                    // select proveedores
                    $opciones = DB::select('SELECT
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                                -- ,IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                            FROM
                                                acreditacionalcance
                                                LEFT JOIN proveedor ON acreditacionalcance.proveedor_id = proveedor.id
                                            WHERE
                                                acreditacionalcance.prueba_id = ' . $value->catprueba_id . '
                                                AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                AND proveedor.proveedor_Eliminado = 0
                                            GROUP BY
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                            
                                            ');

                    foreach ($opciones as $key2 => $value2) {
                        if ($value->agente != null) {
                            if ($value->proveedor == $value2->proveedor_id) {
                                $lista .= '<option value="' . $value2->proveedor_id . '" selected>' . $value2->proveedor_NombreComercial . '</option>';
                            } else {
                                $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                            }
                        } else {
                            $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                        }
                    }

                    //Total puntos y Observacion
                    if ($value->agente) {
                        $checked = 'checked';
                        $puntos = $value->puntos;
                        $required_campo = 'required';

                        if ($value->totalpuntos == $value->puntos) {
                            $readonly_required = 'readonly';
                        } else {
                            $readonly_required = 'required';
                        }
                    } else {
                        // $puntos = $value->totalpuntos;
                        $puntos = '';

                        $readonly_required = 'readonly';
                    }

                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="agente_activo[]" value="' . $numero_registros . '" onchange="valida_requiere_agente_activo(this)" ' . $checked . '/>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="proveedor_id[]" id="select_proveedor_' . $numero_registros . '" ' . $required_campo . '>
                                            <option value="">&nbsp;</option>
                                            ' . $lista . '
                                        </select>
                                    </td>
                                    <td>' . $value->agente_nombre . '</td>
                                    <td><div class="round" style="background-color: #999999;"><i>' . $value->totalpuntos . '</i></div></td>
                                    <td>
                                        <input type="hidden" class="form-control" name="agente_tipo[]" value="0">
                                        <input type="hidden" class="form-control" name="agente_id[]" value="' . $value->catprueba_id . '">
                                        <input type="hidden" class="form-control" name="agente_nombre[]" value="' . $value->agente_nombre . '">
                                        <input type="number" class="form-control" name="agente_puntos[]" value="' . $puntos . '" id="puntos_agente_' . $numero_registros . '" onchange="requiere_obs(' . $numero_registros . ', ' . $value->totalpuntos . ', this.value);" ' . $required_campo . '>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="agente_obs[]" id="agente_obs_' . $numero_registros . '" value="' . $value->observacion . '" ' . $readonly_required . '>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
            }


            // Resumen de quimicos COMPLETO
            if (($recsensorial_alcancequimico + 0) == 1) {
                //MANDAMOS A LLAMAR NUESTRO SP QUE NOS TRAE TODA LA INFORMACION NECESARIA PARA COMPLETAR LA TABLA
                $quimicos = DB::select('CALL sp_obtener_puntos_proveedores_proyecto_b(?, ?)', [$recsensorial_id, $proyecto_id]);

                foreach ($quimicos as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';
                    $checked = '';
                    $puntos = 0;

                    // select proveedores
                    $opciones = DB::select('SELECT
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                                -- ,IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                            FROM
                                                acreditacionalcance
                                                LEFT JOIN proveedor ON acreditacionalcance.proveedor_id = proveedor.id
                                            WHERE
                                                acreditacionalcance.prueba_id = 15
                                                AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                AND proveedor.proveedor_Eliminado = 0
                                            GROUP BY
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                            ');

                    foreach ($opciones as $key2 => $value2) {
                        if ($value->agente != null) {
                            if ($value->proveedor == $value2->proveedor_id) {
                                $lista .= '<option value="' . $value2->proveedor_id . '" selected>' . $value2->proveedor_NombreComercial . '</option>';
                            } else {
                                $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                            }
                        } else {
                            $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                        }
                    }

                    //Total puntos y Observacion
                    if ($value->agente) {
                        $checked = 'checked';
                        $puntos = $value->puntos;
                        $required_campo = 'required';

                        if ($value->TOTAL_MUESTREOS == $value->puntos) {
                            $readonly_required = 'readonly';
                        } else {
                            $readonly_required = 'required';
                        }
                    } else {
                        // $puntos = $value->TOTAL_MUESTREOS;
                        $puntos = '';

                        $readonly_required = 'readonly';
                    }

                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="agente_activo[]" value="' . $numero_registros . '" onchange="valida_requiere_agente_activo(this)" ' . $checked . '/>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="proveedor_id[]" id="select_proveedor_' . $numero_registros . '" ' . $required_campo . '>
                                            <option value="">&nbsp;</option>
                                            ' . $lista . '
                                        </select>
                                    </td>
                                    <td>' . $value->componente . '</td>
                                    <td><div class="round" style="background-color: #999999;"><i>' . $value->TOTAL_MUESTREOS . '</i></div></td>
                                    <td>
                                        <input type="hidden" class="form-control" name="agente_tipo[]" value="0">
                                        <input type="hidden" class="form-control" name="agente_id[]" value="15">
                                        <input type="hidden" class="form-control" name="agente_nombre[]" value="' . $value->componente . '">
                                        <input type="number" class="form-control" name="agente_puntos[]" value="' . $puntos . '" id="puntos_agente_' . $numero_registros . '" onchange="requiere_obs(' . $numero_registros . ', ' . $value->TOTAL_MUESTREOS . ', this.value);" ' . $required_campo . '>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="agente_obs[]" id="agente_obs_' . $numero_registros . '" value="' . $value->observacion . '" ' . $readonly_required . '>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
            }


            // Resumen de fisicos puntos CLIENTE
            if (($recsensorial_alcancefisico + 0) == 2) {
                $fisicos = DB::select('SELECT
                                            recsensorialagentescliente.recsensorial_id,
                                            recsensorialagentescliente.agentescliente_agenteid AS catprueba_id,
                                            recsensorialagentescliente.agentescliente_nombre AS agente_nombre,
                                            recsensorialagentescliente.agentescliente_analisis AS agente_analisis,
                                            recsensorialagentescliente.agentescliente_puntos AS totalpuntos,
                                            (
                                                IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                    IF(recsensorialagentescliente.agentescliente_agenteid != 17,
                                                        IF(recsensorialagentescliente.agentescliente_agenteid != 16,
                                                            CASE
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                                ELSE "Extra chica"
                                                            END
                                                        ,
                                                            CASE
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 50 THEN "Extra grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 31 THEN "Grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 11 THEN "Mediana"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 5 THEN "Chica"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 2 THEN "Extra chica"
                                                                ELSE "N/A"
                                                            END
                                                        )
                                                    ,
                                                        "N/A"
                                                    )
                                                ,
                                                    IF(recsensorialagentescliente.agentescliente_agenteid != 17,
                                                        IF(recsensorialagentescliente.agentescliente_agenteid != 16,
                                                            CASE
                                                                -- WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                                ELSE "Extra chica"
                                                            END
                                                        ,
                                                            CASE
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 50 THEN "Extra grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 31 THEN "Grande"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 11 THEN "Mediana"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 5 THEN "Chica"
                                                                WHEN recsensorialagentescliente.agentescliente_puntos >= 2 THEN "Extra chica"
                                                                ELSE "N/A"
                                                            END
                                                        )
                                                    ,
                                                        "N/A"
                                                    )
                                                )
                                            ) AS tipoinstalacion,
                                            (
                                                SELECT
                                                    proyectoproveedores.catprueba_id
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = agente_nombre
                                                LIMIT 1
                                            ) AS agente,
                                            (
                                                SELECT
                                                    proyectoproveedores.proveedor_id
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = agente_nombre
                                                LIMIT 1
                                            ) AS proveedor,
                                            (
                                                SELECT
                                                    proyectoproveedores.proyectoproveedores_puntos 
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = agente_nombre
                                                LIMIT 1
                                            ) AS puntos,
                                            (
                                                SELECT
                                                    proyectoproveedores.proyectoproveedores_observacion 
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = agente_nombre
                                                LIMIT 1
                                            ) AS observacion
                                        FROM
                                            recsensorialagentescliente
                                        WHERE
                                            recsensorialagentescliente.recsensorial_id = ' . $recsensorial_id . '
                                            AND recsensorialagentescliente.agentescliente_agenteid != 15
                                        ORDER BY
                                            recsensorialagentescliente.agentescliente_puntos DESC');

                foreach ($fisicos as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';
                    $checked = '';
                    $puntos = 0;

                    // select proveedores
                    $opciones = DB::select('SELECT
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                                -- ,IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                            FROM
                                                acreditacionalcance
                                                LEFT JOIN proveedor ON acreditacionalcance.proveedor_id = proveedor.id
                                            WHERE
                                                acreditacionalcance.prueba_id = ' . $value->catprueba_id . '
                                                AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                AND proveedor.proveedor_Eliminado = 0
                                            GROUP BY
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                            ');

                    foreach ($opciones as $key2 => $value2) {
                        if ($value->agente != null) {
                            if ($value->proveedor == $value2->proveedor_id) {
                                $lista .= '<option value="' . $value2->proveedor_id . '" selected>' . $value2->proveedor_NombreComercial . '</option>';
                            } else {
                                $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                            }
                        } else {
                            $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                        }
                    }

                    //Total puntos y Observacion
                    if ($value->agente) {
                        $checked = 'checked';
                        $puntos = $value->puntos;
                        $required_campo = 'required';

                        if ($value->totalpuntos == $value->puntos) {
                            $readonly_required = 'readonly';
                        } else {
                            $readonly_required = 'required';
                        }
                    } else {
                        // $puntos = $value->totalpuntos;
                        $puntos = '';

                        $readonly_required = 'readonly';
                    }


                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="agente_activo[]" value="' . $numero_registros . '" onchange="valida_requiere_agente_activo(this)" ' . $checked . '/>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="proveedor_id[]" id="select_proveedor_' . $numero_registros . '" ' . $required_campo . '>
                                            <option value="">&nbsp;</option>
                                            ' . $lista . '
                                        </select>
                                    </td>
                                    <td>' . $value->agente_nombre . '</td>
                                    <td><div class="round" style="background-color: #999999;"><i>' . $value->totalpuntos . '</i></div></td>
                                    <td>
                                        <input type="hidden" class="form-control" name="agente_tipo[]" value="0">
                                        <input type="hidden" class="form-control" name="agente_id[]" value="' . $value->catprueba_id . '">
                                        <input type="hidden" class="form-control" name="agente_nombre[]" value="' . $value->agente_nombre . '">
                                        <input type="number" class="form-control" name="agente_puntos[]" value="' . $puntos . '" id="puntos_agente_' . $numero_registros . '" onchange="requiere_obs(' . $numero_registros . ', ' . $value->totalpuntos . ', this.value);" ' . $required_campo . '>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="agente_obs[]" id="agente_obs_' . $numero_registros . '" value="' . $value->observacion . '" ' . $readonly_required . '>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
            }


            // Resumen de quimicos puntos CLIENTE
            if (($recsensorial_alcancequimico + 0) == 2) {
                $quimicos = DB::select('SELECT
                                            recsensorialagentescliente.recsensorial_id,
                                            recsensorialagentescliente.agentescliente_agenteid AS catprueba_id,
                                            recsensorialagentescliente.agentescliente_nombre AS componente,
                                            recsensorialagentescliente.agentescliente_analisis AS agente_analisis,
                                            recsensorialagentescliente.agentescliente_puntos AS TOTAL_MUESTREOS,
                                            (
                                                IF((SELECT recsensorial_tipocliente FROM recsensorial WHERE recsensorial.id = ' . $recsensorial_id . ' LIMIT 1) = 1, -- Si el cliente es Pemex
                                                    CASE
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                        ELSE "Extra chica"
                                                    END
                                                ,
                                                    CASE
                                                        -- WHEN recsensorialagentescliente.agentescliente_puntos >= 151 THEN "Extra grande"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 81 THEN "Grande"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 41 THEN "Mediana"
                                                        WHEN recsensorialagentescliente.agentescliente_puntos >= 21 THEN "Chica"
                                                        ELSE "Extra chica"
                                                    END
                                                )
                                            ) AS tipoinstalacion,
                                            (
                                                SELECT
                                                    proyectoproveedores.proyectoproveedores_agente
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = componente
                                                LIMIT 1
                                            ) AS agente,
                                            (
                                                SELECT
                                                    proyectoproveedores.proveedor_id
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = componente
                                                LIMIT 1
                                            ) AS proveedor,
                                            (
                                                SELECT
                                                    proyectoproveedores.proyectoproveedores_puntos
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = componente
                                                LIMIT 1
                                            ) AS puntos,
                                            (
                                                SELECT
                                                    proyectoproveedores.proyectoproveedores_observacion
                                                FROM
                                                    proyectoproveedores
                                                WHERE
                                                    proyectoproveedores.proyecto_id = ' . $proyecto_id . ' AND proyectoproveedores.proyectoproveedores_agente = componente
                                                LIMIT 1
                                            ) AS observacion
                                        FROM
                                            recsensorialagentescliente
                                        WHERE
                                            recsensorialagentescliente.recsensorial_id = ' . $recsensorial_id . '
                                            AND recsensorialagentescliente.agentescliente_agenteid = 15
                                        ORDER BY
                                            recsensorialagentescliente.agentescliente_puntos DESC');

                foreach ($quimicos as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';
                    $checked = '';
                    $puntos = 0;

                    // select proveedores
                    $opciones = DB::select('SELECT
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                                -- ,IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                            FROM
                                                acreditacionalcance
                                                LEFT JOIN proveedor ON acreditacionalcance.proveedor_id = proveedor.id
                                            WHERE
                                                acreditacionalcance.prueba_id = 15
                                                AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                AND proveedor.proveedor_Eliminado = 0
                                            GROUP BY
                                                acreditacionalcance.proveedor_id
                                                ,proveedor.proveedor_NombreComercial
                                                -- ,acreditacionalcance.prueba_id
                                            ');

                    foreach ($opciones as $key2 => $value2) {
                        if ($value->agente != null) {
                            if ($value->proveedor == $value2->proveedor_id) {
                                $lista .= '<option value="' . $value2->proveedor_id . '" selected>' . $value2->proveedor_NombreComercial . '</option>';
                            } else {
                                $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                            }
                        } else {
                            $lista .= '<option value="' . $value2->proveedor_id . '">' . $value2->proveedor_NombreComercial . '</option>';
                        }
                    }

                    //Total puntos y Observacion
                    if ($value->agente) {
                        $checked = 'checked';
                        $puntos = $value->puntos;
                        $required_campo = 'required';

                        if ($value->TOTAL_MUESTREOS == $value->puntos) {
                            $readonly_required = 'readonly';
                        } else {
                            $readonly_required = 'required';
                        }
                    } else {
                        // $puntos = $value->TOTAL_MUESTREOS;
                        $puntos = '';

                        $readonly_required = 'readonly';
                    }


                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="agente_activo[]" value="' . $numero_registros . '" onchange="valida_requiere_agente_activo(this)" ' . $checked . '/>
                                                <span class="lever switch-col-light-blue" style="paddin: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="proveedor_id[]" id="select_proveedor_' . $numero_registros . '" ' . $required_campo . '>
                                            <option value="">&nbsp;</option>
                                            ' . $lista . '
                                        </select>
                                    </td>
                                    <td>' . $value->componente . '</td>
                                    <td><div class="round" style="background-color: #999999;"><i>' . $value->TOTAL_MUESTREOS . '</i></div></td>
                                    <td>
                                        <input type="hidden" class="form-control" name="agente_tipo[]" value="0">
                                        <input type="hidden" class="form-control" name="agente_id[]" value="15">
                                        <input type="hidden" class="form-control" name="agente_nombre[]" value="' . $value->componente . '">
                                        <input type="number" class="form-control" name="agente_puntos[]" value="' . $puntos . '" id="puntos_agente_' . $numero_registros . '" onchange="requiere_obs(' . $numero_registros . ', ' . $value->TOTAL_MUESTREOS . ', this.value);" ' . $required_campo . '>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="agente_obs[]" id="agente_obs_' . $numero_registros . '" value="' . $value->observacion . '" ' . $readonly_required . '>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
            }


            // Verifica si hay parametros adicionales
            $adicionales = DB::select('SELECT
                                            proyectoproveedores.proyecto_id,
                                            IFNULL(proyectoproveedores.proveedor_id, 0) AS proveedor_id,
                                            proyectoproveedores.proyectoproveedores_tipoadicional,
                                            proyectoproveedores.catprueba_id,
                                            proyectoproveedores.proyectoproveedores_agente,
                                            proyectoproveedores.proyectoproveedores_puntos,
                                            proyectoproveedores.proyectoproveedores_observacion 
                                        FROM
                                            proyectoproveedores
                                        WHERE
                                            proyectoproveedores.proyecto_id = ' . $proyecto_id . '
                                            AND proyectoproveedores.proyectoproveedores_tipoadicional > 0
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.proyectoproveedores_agente ASC');


            if (count($adicionales) > 0) {
                foreach ($adicionales as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $puntos = 0;

                    // select proveedores
                    $opciones = DB::select('SELECT
                                                    proveedor.id,
                                                    proveedor.proveedor_NombreComercial
                                                FROM
                                                    proveedor
                                                WHERE
                                                    proveedor.proveedor_Eliminado = 0');

                    foreach ($opciones as $key2 => $value2) {
                        if ($value->proveedor_id == $value2->id) {
                            $lista .= '<option value="' . $value2->id . '" selected>' . $value2->proveedor_NombreComercial . '</option>';
                        } else {
                            $lista .= '<option value="' . $value2->id . '">' . $value2->proveedor_NombreComercial . '</option>';
                        }
                    }

                    $alcances = DB::select('SELECT
                                                TABLA.tipo,
                                                TABLA.acreditacionalcance_id,
                                                TABLA.proveedor_id,
                                                TABLA.agente_id,
                                                TABLA.agente_nombre
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            1 tipo,
                                                            acreditacionalcance.id AS acreditacionalcance_id,
                                                            acreditacionalcance.proveedor_id,
                                                            acreditacionalcance.prueba_id AS agente_id,
                                                            IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                                        FROM
                                                            acreditacionalcance
                                                        WHERE
                                                            acreditacionalcance.proveedor_id = ' . $value->proveedor_id . '
                                                            AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            2 tipo,
                                                            0 AS acreditacionalcance_id,
                                                            servicio.proveedor_id,
                                                            servicioprecios.agente_id,
                                                            servicioprecios.agente_nombre
                                                        FROM
                                                            servicio
                                                            LEFT JOIN servicioprecios ON servicio.id = servicioprecios.servicio_id
                                                        WHERE
                                                            servicio.proveedor_id = ' . $value->proveedor_id . '
                                                            AND servicioprecios.agente_id = 0
                                                            AND servicio.servicio_Eliminado = 0
                                                        GROUP BY
                                                            servicio.proveedor_id,
                                                            servicioprecios.agente_id,
                                                            servicioprecios.agente_nombre
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.agente_nombre ASC');










                    // lista alcances
                    $select_lista_proveedoralcances = '';
                    $class_tipo = '';
                    foreach ($alcances as $key3 => $value3) {
                        if ($value3->tipo == 1) {
                            $class_tipo = '';
                        } else {
                            $class_tipo = 'class="text-info"';
                        }

                        if ($value->proyectoproveedores_agente == $value3->agente_nombre) {
                            $select_lista_proveedoralcances .= '<option value="' . $value3->agente_id . '" ' . $class_tipo . ' selected>' . $value3->agente_nombre . '</option>';
                        } else {
                            $select_lista_proveedoralcances .= '<option value="' . $value3->agente_id . '" ' . $class_tipo . '>' . $value3->agente_nombre . '</option>';
                        }
                    }

                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>
                                    <td>
                                        <input type="checkbox" name="agenteadicional_activo[]" checked/>
                                        <select class="custom-select form-control" id="adicional_' . $numero_registros . '_proveedor" name="proveedoradicional_id[]" onchange="mostrar_proveedoralcances(this, ' . $numero_registros . ');" required>
                                            <option value="">&nbsp;</option>
                                            ' . $lista . '
                                        </select>
                                    </td>
                                    <td colspan="3">
                                        <select class="custom-select form-control" id="adicional_' . $numero_registros . '_alcance" name="agenteadicional_id[]" onchange="llenarcampos_proveedoralcances(this, ' . $numero_registros . ');" required>
                                            <option value="">&nbsp;</option>
                                            ' . $select_lista_proveedoralcances . '
                                        </select>
                                        <input type="hidden" class="form-control" id="adicional_' . $numero_registros . '_tipo" name="agenteadicional_tipo[]" value="' . $value->proyectoproveedores_tipoadicional . '">
                                        <input type="hidden" class="form-control" id="adicional_' . $numero_registros . '_nombre" name="agenteadicional_nombre[]" value="' . $value->proyectoproveedores_agente . '">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="agenteadicional_puntos[]" value="' . $value->proyectoproveedores_puntos . '" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="agenteadicional_obs[]" value="' . $value->proyectoproveedores_observacion . '" required>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
            }


            // respuesta
            $dato['numero_registros'] = $numero_registros;
            $dato['filas'] = $filas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
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
        try {
            // Eliminar datos anteriores
            $eliminar_proveedores = proyectoproveedoresModel::where('proyecto_id', $request["proyecto_id"])->delete();

            // Guardar agentes
            DB::statement("ALTER TABLE proyectoproveedores AUTO_INCREMENT = 1;");

            if ($request->agente_activo) {
                foreach ($request->agente_activo as $key => $value) {
                    $guardar_fisicos = proyectoproveedoresModel::create([
                        'proyecto_id' => $request["proyecto_id"], 'proveedor_id' => $request->proveedor_id[$value], 'proyectoproveedores_tipoadicional' => $request->agente_tipo[$value], 'catprueba_id' => $request->agente_id[$value], 'proyectoproveedores_agente' => $request->agente_nombre[$value], 'proyectoproveedores_puntos' => $request->agente_puntos[$value], 'proyectoproveedores_observacion' => $request->agente_obs[$value]
                    ]);
                }
            }

            // Agentes adicionales
            if ($request->agenteadicional_activo) {
                foreach ($request->agenteadicional_activo as $key => $value) {
                    // dd($request->agenteadicional_id);
                    $guardar_fisicos = proyectoproveedoresModel::create([
                        'proyecto_id' => $request["proyecto_id"], 'proveedor_id' => $request->proveedoradicional_id[$key], 'proyectoproveedores_tipoadicional' => $request->agenteadicional_tipo[$key], 'catprueba_id' => $request->agenteadicional_id[$key], 'proyectoproveedores_agente' => $request->agenteadicional_nombre[$key], 'proyectoproveedores_puntos' => $request->agenteadicional_puntos[$key], 'proyectoproveedores_observacion' => $request->agenteadicional_obs[$key]
                    ]);
                }
            }

            // respuesta
            $dato["msj"] = 'Datos guardados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // $dato['sustancia'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
