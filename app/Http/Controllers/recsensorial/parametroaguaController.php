<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametroaguaModel;
use App\modelos\recsensorial\catparametroaguacaracteristicaModel;
use App\modelos\recsensorial\parametroaguacaracteristicaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametroaguaController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroaguavista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametroagua', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroaguatabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            // lista de registros
            $tabla = DB::select('SELECT
                                    parametroagua.id,
                                    parametroagua.recsensorial_id,
                                    parametroagua.recsensorialarea_id,
                                    recsensorialarea.recsensorialarea_nombre,
                                    parametroagua.parametroagua_ubicacion,
                                    parametroagua.parametroagua_tipouso,
                                    parametroagua.catparametroaguacaracteristica_id,
                                    catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                    parametroagua.parametroagua_puntos 
                                FROM
                                    parametroagua
                                    INNER JOIN recsensorialarea ON parametroagua.recsensorialarea_id = recsensorialarea.id
                                    INNER JOIN catparametroaguacaracteristica ON parametroagua.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                WHERE
                                    parametroagua.recsensorial_id = '.$recsensorial_id.'
                                ORDER BY
                                    parametroagua.id ASC');

            $numero_registro = 1;
            // lista caracteristicas por registros
            for ($i=0; $i < count($tabla); $i++)
            {
                $tabla[$i]->numero_registro = $numero_registro;
                $numero_registro += 1;

                $lista_texto = '';
                $lista = DB::select('SELECT
                                        parametroaguacaracteristica.catparametroaguacaracteristica_id AS id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica 
                                    FROM
                                        parametroaguacaracteristica
                                        LEFT JOIN catparametroaguacaracteristica ON parametroaguacaracteristica.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                    WHERE
                                        parametroaguacaracteristica.parametroagua_id = '.$tabla[$i]->id.'
                                    ORDER BY
                                        id ASC');

                // formatear caracteristicas
                for ($x=0; $x < count($lista); $x++)
                {
                    $lista_texto .= "<li>".$lista[$x]->catparametroaguacaracteristica_caracteristica."</li>";
                }

                $tabla[$i]->lista_seleccionados = $lista;
                $tabla[$i]->lista_caracteristicas_texto = $lista_texto;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
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
    public function parametroaguaselectcaracteristicas($seleccionado_id)
    {
        try
        {
            $opciones = '<option value=""></option>';

            $tabla = DB::select('SELECT
                                    MIN(catparametroaguacaracteristica.id) AS id,
                                    catparametroaguacaracteristica.catparametroaguacaracteristica_tipo
                                FROM
                                    catparametroaguacaracteristica
                                GROUP BY
                                    catparametroaguacaracteristica.catparametroaguacaracteristica_tipo
                                ORDER BY
                                    id ASC');

            // colocar numero de registro
            foreach ($tabla  as $key => $value) 
            {
                $opciones .= '<option value="'.$value->id.'">'.$value->catparametroaguacaracteristica_tipo.'</option>';
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
     * @param  int  $caracteristica_tipo
     * @return \Illuminate\Http\Response
     */
    public function parametroaguacaracteristicas($caracteristica_tipo)
    {
        try
        {
            $tabla = catparametroaguacaracteristicaModel::where('catparametroaguacaracteristica_tipo', $caracteristica_tipo)
                    ->where('catparametroaguacaracteristica_activo', 1)
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
            if (($request->opcion+0) == 1) // PUNTOS DEL RECONCIMIENTO
            {
                if ($request['registro_id']==0) //nuevo
                {
                    // guardar
                    $parametro = parametroaguaModel::create($request->all());
                    $parametro->parametroaguacaracteristicasincronizacion()->sync($request->caracteristica);

                    // mensaje
                    $dato["msj"] = 'Información guardada correctamente';
                }
                else //editar
                {
                    // modificar
                    $parametro = parametroaguaModel::findOrFail($request['registro_id']);
                    $parametro->update($request->all());
                    $parametro->parametroaguacaracteristicasincronizacion()->sync($request->caracteristica);

                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }

                // respuesta
                $dato['parametro'] = $parametro;
            }

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
    public function parametroaguaeliminar($registro_id)
    {
        try
        {
            $parametro = parametroaguaModel::where('id', $registro_id)->delete();
            $parametro = parametroaguacaracteristicaModel::where('parametroagua_id', $registro_id)->delete();

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
