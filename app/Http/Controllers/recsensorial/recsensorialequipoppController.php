<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialequipoppModel;
use DB;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class recsensorialequipoppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }


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
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialequipopptabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $sql = DB::select('SELECT
                                    recsensorialequipopp.recsensorial_id AS recsensorial_id,
                                    recsensorialequipopp.recsensorialcategoria_id AS categoria_id,
                                    IF(recsensorialequipopp.recsensorialcategoria_id = 0, "Todas las categorías", CONCAT(cd.catdepartamento_nombre, " (", recsensorialcategoria.recsensorialcategoria_nombrecategoria, ")")) AS categoria,
                                    IFNULL((
                                        SELECT
                                            CONCAT("● ", REPLACE(GROUP_CONCAT(CONCAT(catpartecuerpo.catpartecuerpo_nombre, " (", cpd.CLAVE_EPP, ")")), ",", "<br>● "))
                                        FROM
                                            recsensorialequipopp
                                            LEFT JOIN catpartecuerpo ON recsensorialequipopp.catpartecuerpo_id = catpartecuerpo.id 
                                            LEFT JOIN catpartescuerpo_descripcion cpd ON recsensorialequipopp.catpartescuerpo_descripcion_id = cpd.ID_PARTESCUERPO_DESCRIPCION AND cpd.ACTIVO = 1
                                        WHERE
                                            recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . ' AND recsensorialequipopp.recsensorialcategoria_id = categoria_id
                                    ), "NINGUNO") AS epp
                                FROM
                                    recsensorialequipopp
                                    LEFT JOIN recsensorialcategoria ON recsensorialequipopp.recsensorialcategoria_id = recsensorialcategoria.id
                                    LEFT JOIN catdepartamento cd on recsensorialcategoria.catdepartamento_id = cd.id 
                                    AND cd.catdepartamento_activo = 1
                                WHERE
                                    recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . '
                                GROUP BY
                                    recsensorialequipopp.recsensorial_id,
                                    recsensorialequipopp.recsensorialcategoria_id,
                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                    cd.catdepartamento_nombre
                                ORDER BY
                                    categoria ASC');

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($sql as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $sql;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recsensorialeppcatalogo()
    {
        try {
            $opciones = '<option value=""></option>';

            $sql = DB::select('SELECT
                                    catpartecuerpo.id,
                                    catpartecuerpo.catpartecuerpo_nombre,
                                    catpartecuerpo.catpartecuerpo_activo 
                                FROM
                                    catpartecuerpo
                                WHERE
                                    catpartecuerpo.catpartecuerpo_activo = 1
                                ORDER BY
                                    catpartecuerpo.id ASC');

            // colocar numero de registro
            foreach ($sql as $key => $value) {
                $opciones .= '<option value="' . $value->id . '">' . $value->catpartecuerpo_nombre . '</option>';
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function recsensorialClaveEpp($PARTECUERPO_ID)
    {
        try {
            $opciones = '<option value=""></option>';

            $sql = DB::select('SELECT
                                    c.ID_PARTESCUERPO_DESCRIPCION AS ID,
                                    c.CLAVE_EPP,
                                    c.TIPO_RIEGO
                                FROM
                                    catpartescuerpo_descripcion c
                                WHERE
                                    c.ACTIVO = 1
                                    AND c.PARTECUERPO_ID = ? ', [$PARTECUERPO_ID] );

            // colocar numero de registro
            foreach ($sql as $key => $value) {
                $opciones .= '<option value="' . $value->ID . '" data-descripcion ="'. $value->TIPO_RIEGO .'">' . $value->CLAVE_EPP . '</option>';
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $seleccionado_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialeppcategorias($recsensorial_id, $seleccionado_id)
    {
        try {
            $opciones = '<option value=""></option>';

            if ($seleccionado_id == 0) {
                $opciones .= '<option value="0" selected>TODAS LAS CATEGORÍAS</option>';
            } else {
                $opciones .= '<option value="0">TODAS LAS CATEGORÍAS</option>';
            }

            $sql = DB::select('SELECT
                                    rc.recsensorial_id,
                                    rc.id,
                                    rc.recsensorialcategoria_nombrecategoria,
                                    cd.catdepartamento_nombre
                                FROM
                                    recsensorialcategoria  rc
                                    LEFT JOIN catdepartamento  cd ON rc.catdepartamento_id = cd.id
                                WHERE
                                    rc.recsensorial_id = ' . $recsensorial_id . '
                                    AND cd.catdepartamento_activo = 1
                                ORDER BY
                                    rc.recsensorialcategoria_nombrecategoria ASC');

            // colocar numero de registro
            foreach ($sql as $key => $value) {
                if ($seleccionado_id == $value->id) {
                    $opciones .= '<option value="' . $value->id . '" selected>' . $value->catdepartamento_nombre . ' (' . $value->recsensorialcategoria_nombrecategoria . ')</option>';
                } else {
                    $opciones .= '<option value="' . $value->id . '">' . $value->catdepartamento_nombre . ' (' . $value->recsensorialcategoria_nombrecategoria . ')</option>';
                }
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialeppeditar($recsensorial_id, $categoria_id)
    {
        try {
            $tabla = '';
            $No = 1;

            $catcuerpo = DB::select('SELECT
                                        catpartecuerpo.id,
                                        catpartecuerpo.catpartecuerpo_nombre
                                    FROM
                                        catpartecuerpo
                                    ORDER BY
                                        catpartecuerpo.id ASC');


            $descricion = DB::select('SELECT cc.catpartecuerpo_nombre, cd.CLAVE_EPP, cd.TIPO_RIEGO, cd.ID_PARTESCUERPO_DESCRIPCION, cd.PARTECUERPO_ID
                                    FROM catpartescuerpo_descripcion cd
                                    LEFT JOIN catpartecuerpo cc on cd.PARTECUERPO_ID = cc.id');

            $sql = DB::select('SELECT
                                    recsensorialequipopp.recsensorial_id,
                                    recsensorialequipopp.recsensorialcategoria_id,
                                    recsensorialequipopp.id,
                                    recsensorialequipopp.catpartecuerpo_id,
                                    recsensorialequipopp.catpartescuerpo_descripcion_id,
                                    cpd.TIPO_RIEGO,
                                    cpd.ID_PARTESCUERPO_DESCRIPCION,
                                    cpd.CLAVE_EPP
                                FROM
                                    recsensorialequipopp
                                    LEFT JOIN catpartescuerpo_descripcion cpd ON recsensorialequipopp.catpartescuerpo_descripcion_id = cpd.ID_PARTESCUERPO_DESCRIPCION  
                                WHERE
                                    recsensorialequipopp.recsensorial_id = ' . $recsensorial_id . ' 
                                    AND recsensorialequipopp.recsensorialcategoria_id = ' . $categoria_id . '
                                ORDER BY
                                    recsensorialequipopp.id ASC');

            // colocar numero de registro
            foreach ($sql as $key => $value) {
                $tabla .= '<tr>';

                $opciones = '<option value="">&nbsp;</option>';
                $opciones2 = '<option value="">&nbsp;</option>';

                foreach ($catcuerpo  as $key => $value2) {
                    if ($value->catpartecuerpo_id == $value2->id) {
                        $opciones .= '<option value="' . $value2->id . '" selected>' . $value2->catpartecuerpo_nombre . '</option>';
                    } else {
                        $opciones .= '<option value="' . $value2->id . '">' . $value2->catpartecuerpo_nombre . '</option>';
                    }
                }


                foreach ($descricion  as $key => $value3) {
                    
                    if($value->catpartecuerpo_id == $value3->PARTECUERPO_ID) {
                        if ($value->ID_PARTESCUERPO_DESCRIPCION == $value3->ID_PARTESCUERPO_DESCRIPCION) {
                            $opciones2 .= '<option value="' . $value3->ID_PARTESCUERPO_DESCRIPCION . '" data-descripcion="'.$value3->TIPO_RIEGO.'" selected>' . $value3->CLAVE_EPP . '</option>';
                        } else {
                            $opciones2 .= '<option value="' . $value3->ID_PARTESCUERPO_DESCRIPCION . '"  data-descripcion="' . $value3->TIPO_RIEGO . '">' . $value3->CLAVE_EPP . '</option>';
                        }
                    }
                }


                
                $tabla .= '<td style="width:250px"><select class="custom-select form-control regionAnatomica" name="cuerpo[]" required>' . $opciones . '</select></td>';
                $tabla .= '<td style="width:400px"><select class="custom-select form-control claveyEpp" name="claveEpp[]" required>' . $opciones2 . '</select></td>';
                $tabla .= '<td><input type="text" class="form-control" name="tipoRiesgo[]" value="'.$value->TIPO_RIEGO.'"  style="height: auto;" required></td>';

                if ($No == 1) {
                    $tabla .= '<td><button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-trash"></i></button></td>';
                } else {
                    $tabla .= '<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>';
                }

                $tabla .= '</tr>';
                $No += 1;
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['tabla'] = "";
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialequipoppeliminar($recsensorial_id, $categoria_id)
    {
        try {
            // $equipopp = recsensorialequipoppModel::where('id', $equipopp_id)->delete();
            $eliminar = recsensorialequipoppModel::where('recsensorial_id', $recsensorial_id)
                ->where('recsensorialcategoria_id', $categoria_id)
                ->delete(); //eliminar componentes anteriores

            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
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
            if ($request['equipopp_id'] == 0) //nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialequipopp AUTO_INCREMENT=1');

                // EPP seleccionados
                foreach ($request->cuerpo as $key => $value) {
                    $sustancia = recsensorialequipoppModel::create([
                        'recsensorial_id' => $request['recsensorial_id'], 
                        'recsensorialcategoria_id' => $request['recsensorialcategoria_id'],
                        'catpartecuerpo_id' => $request->cuerpo[$key],
                        'catpartescuerpo_descripcion_id' => $request->claveEpp[$key]
                    ]);
                }

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            } else //editar
            {
                $eliminar = recsensorialequipoppModel::where('recsensorial_id', $request['recsensorial_id'])
                    ->where('recsensorialcategoria_id', $request['recsensorialcategoria_id'])
                    ->delete(); //eliminar componentes anteriores

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialequipopp AUTO_INCREMENT=1');

                // EPP seleccionados
                foreach ($request->cuerpo as $key => $value) {
                    $sustancia = recsensorialequipoppModel::create([
                        'recsensorial_id' => $request['recsensorial_id'],
                        'recsensorialcategoria_id' => $request['recsensorialcategoria_id'], 
                        'catpartecuerpo_id' => $request->cuerpo[$key],
                        'catpartescuerpo_descripcion_id' => $request->claveEpp[$key]
                    ]);
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
            }

            // respuesta
            $dato['recsensorial_id'] = $request['recsensorial_id'];
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
