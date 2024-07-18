<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametroiluminacionModel;
use App\modelos\recsensorial\parametroiluminacioncategoriasModel;
use DB;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametroiluminacionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroiluminacionvista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametroiluminacion', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroiluminaciontabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            $tabla = DB::select('SELECT
                                    parametroiluminacion.recsensorial_id,
                                    parametroiluminacion.id,
                                    parametroiluminacion.recsensorialarea_id,
                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                    IFNULL((
                                        SELECT
                                            -- CONCAT("● ", REPLACE(GROUP_CONCAT(CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_funcioncategoria, ")")), ",", "<br>● "))
                                            CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<br>● "))
                                        FROM
                                            parametroiluminacioncategorias
                                            LEFT JOIN recsensorialcategoria ON parametroiluminacioncategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                        WHERE
                                            parametroiluminacioncategorias.recsensorialarea_id = parametroiluminacion.recsensorialarea_id
                                    ), "-") AS categorias,
                                    parametroiluminacion.parametroiluminacion_largo,
                                    parametroiluminacion.parametroiluminacion_ancho,
                                    parametroiluminacion.parametroiluminacion_alto,
                                    parametroiluminacion.parametroiluminacion_puntos
                                FROM
                                    parametroiluminacion
                                    LEFT JOIN recsensorialarea ON parametroiluminacion.recsensorialarea_id = recsensorialarea.id 
                                WHERE
                                    parametroiluminacion.recsensorial_id = '.$recsensorial_id.'
                                ORDER BY
                                    parametroiluminacion.id ASC');

            // formatear registros
            foreach ($tabla  as $key => $value) 
            {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                if ($value->parametroiluminacion_largo)
                {
                    $value->indicearea = 'La: '.$value->parametroiluminacion_largo.' mts<br>';
                    $value->indicearea .= 'An: '.$value->parametroiluminacion_ancho.' mts<br>';
                    $value->indicearea .= 'Al: '.$value->parametroiluminacion_alto.' mts';
                }
                else
                {
                    $value->indicearea = 'N/A';
                }

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
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
     * @param  int  $recsensorialarea_id
     * @return \Illuminate\Http\Response
     */
    public function iluminacionlistacategoriasxarea($recsensorialarea_id)
    {
        try
        {
            $opciones = '<option value=""></option>';
            $listacategorias = DB::select('SELECT
                                                recsensorialareacategorias.recsensorialarea_id,
                                                recsensorialareacategorias.recsensorialcategoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria AS recsensorialcategoria_nombrecategoria,
                                                IFNULL((
                                                    SELECT
                                                        IF(parametroiluminacioncategorias.recsensorialcategoria_id, "checked", "") 
                                                    FROM
                                                        parametroiluminacioncategorias
                                                    WHERE
                                                        parametroiluminacioncategorias.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id AND parametroiluminacioncategorias.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id
                                                    LIMIT 1
                                                ), "") AS checked
                                            FROM
                                                recsensorialareacategorias
                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                            WHERE
                                                recsensorialareacategorias.recsensorialarea_id = '.$recsensorialarea_id.'
                                            ORDER BY
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');

            // respuesta
            $dato['categorias'] = $listacategorias;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['categorias'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
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
            if ($request['registro_id']==0) //nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE parametroiluminacion AUTO_INCREMENT=1');

                // guardar
                $parametro = parametroiluminacionModel::create($request->all());

                // categorias
                foreach ($request->categoria as $key => $value) 
                {
                    $categoria = parametroiluminacioncategoriasModel::create([
                          'recsensorialarea_id' => $request['recsensorialarea_id']
                        , 'recsensorialcategoria_id' => $value
                    ]);
                }

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                if (!$request->parametroiluminacion_largo)
                {
                    $request['parametroiluminacion_largo'] = NULL;
                    $request['parametroiluminacion_ancho'] = NULL;
                    $request['parametroiluminacion_alto'] = NULL;
                }

                // modificar
                $parametro = parametroiluminacionModel::findOrFail($request['registro_id']);
                $parametro->update($request->all());

                // eliminar categorias
                $eliminar = parametroiluminacioncategoriasModel::where('recsensorialarea_id', $request['recsensorialarea_id'])->delete();

                // categorias
                foreach ($request->categoria as $key => $value) 
                {
                    $categoria = parametroiluminacioncategoriasModel::create([
                          'recsensorialarea_id' => $request['recsensorialarea_id']
                        , 'recsensorialcategoria_id' => $value
                    ]);
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
            }

            // respuesta
            $dato['parametro'] = $parametro;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $registro_id
     * @return \Illuminate\Http\Response
     */
    public function parametroiluminacioneliminar($registro_id)
    {
        try
        {
            $parametro = parametroiluminacionModel::findOrFail($registro_id);
            $recsensorialarea_id = $parametro->recsensorialarea_id;
            $parametro = parametroiluminacionModel::where('id', $registro_id)->delete();

            // eliminar categorias
            $eliminar = parametroiluminacioncategoriasModel::where('recsensorialarea_id', $recsensorialarea_id)->delete();

            // respuesta
            $dato['eliminado'] = $parametro;
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
