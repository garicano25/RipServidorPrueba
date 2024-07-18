<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametrohieloModel;
use App\modelos\recsensorial\catparametrohielocaracteristicaModel;
use App\modelos\recsensorial\parametrohielocaracteristicaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametrohieloController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrohielovista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametrohielo', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrohielotabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            // lista de registros
            $tabla = DB::select('SELECT
                                    parametrohielo.id,
                                    parametrohielo.recsensorial_id,
                                    parametrohielo.recsensorialarea_id,
                                    recsensorialarea.recsensorialarea_nombre,
                                    parametrohielo.parametrohielo_ubicacion,
                                    parametrohielo.catparametrohielocaracteristica_id,
                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                    parametrohielo.parametrohielo_puntos 
                                FROM
                                    parametrohielo
                                    LEFT JOIN recsensorialarea ON parametrohielo.recsensorialarea_id = recsensorialarea.id
                                    LEFT JOIN catparametrohielocaracteristica ON parametrohielo.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                WHERE
                                    parametrohielo.recsensorial_id = '.$recsensorial_id.'
                                ORDER BY
                                    parametrohielo.id ASC');

            $numero_registro = 1;
            // lista caracteristicas por registros
            for ($i=0; $i < count($tabla); $i++)
            {
                $tabla[$i]->numero_registro = $numero_registro;
                $numero_registro += 1;

                $lista_texto = '';
                $lista = DB::select('SELECT
                                        parametrohielocaracteristica.catparametrohielocaracteristica_id AS id,
                                        catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                        catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica 
                                    FROM
                                        parametrohielocaracteristica
                                        LEFT JOIN catparametrohielocaracteristica ON parametrohielocaracteristica.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                    WHERE
                                        parametrohielocaracteristica.parametrohielo_id = '.$tabla[$i]->id.'
                                    ORDER BY
                                        id ASC');

                // formatear caracteristicas
                for ($x=0; $x < count($lista); $x++)
                {
                    $lista_texto .= "<li>".$lista[$x]->catparametrohielocaracteristica_caracteristica."</li>";
                }

                $tabla[$i]->lista_seleccionados = $lista;
                $tabla[$i]->lista_caracteristicas_texto = $lista_texto;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
                {
                    $tabla[$i]->accion_activa = 1;
                    $tabla[$i]->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $tabla[$i]->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $tabla[$i]->accion_activa = 0;
                    $tabla[$i]->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                    $tabla[$i]->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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
     * @param  int  $seleccionado_id
     * @return \Illuminate\Http\Response
     */
    public function parametrohieloselectcaracteristicas($seleccionado_id)
    {
        try
        {
            $opciones = '<option value=""></option>';

            $tabla = DB::select('SELECT
                                    MIN(catparametrohielocaracteristica.id) AS id,
                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo
                                FROM
                                    catparametrohielocaracteristica
                                GROUP BY
                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo
                                ORDER BY
                                    id ASC');

            // colocar numero de registro
            foreach ($tabla  as $key => $value) 
            {
                $opciones .= '<option value="'.$value->id.'">'.$value->catparametrohielocaracteristica_tipo.'</option>';
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  $caracteristica_tipo
     * @return \Illuminate\Http\Response
     */
    public function parametrohielocaracteristicas($caracteristica_tipo)
    {
        try
        {
            $tabla = catparametrohielocaracteristicaModel::where('catparametrohielocaracteristica_tipo', $caracteristica_tipo)
                    ->where('catparametrohielocaracteristica_activo', 1)
                    ->orderBy('id', 'asc')
                    ->get();

            // respuesta
            $dato['opciones'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['opciones'] = 0;
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
                // guardar
                $parametro = parametrohieloModel::create($request->all());
                $parametro->parametrohielocaracteristicasincronizacion()->sync($request->caracteristica);

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $parametro = parametrohieloModel::findOrFail($request['registro_id']);
                $parametro->update($request->all());
                $parametro->parametrohielocaracteristicasincronizacion()->sync($request->caracteristica);

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
    public function parametrohieloeliminar($registro_id)
    {
        try
        {
            $parametro = parametrohieloModel::where('id', $registro_id)->delete();
            $parametro = parametrohielocaracteristicaModel::where('parametrohielo_id', $registro_id)->delete();

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
