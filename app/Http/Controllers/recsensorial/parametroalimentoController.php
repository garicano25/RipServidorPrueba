<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametroalimentoModel;
use App\modelos\recsensorial\parametroalimentocaracteristicaModel;
use App\modelos\recsensorial\catparametroalimentocaracteristicaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametroalimentoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroalimentovista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametroalimento', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroalimentotabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            // lista de registros
            $tabla = DB::select('SELECT
                                    parametroalimento.id,
                                    parametroalimento.recsensorial_id,
                                    parametroalimento.recsensorialarea_id,
                                    recsensorialarea.recsensorialarea_nombre,
                                    parametroalimento.parametroalimento_ubicacion,
                                    parametroalimento.parametroalimento_puntos 
                                FROM
                                    parametroalimento
                                    LEFT JOIN recsensorialarea ON parametroalimento.recsensorialarea_id = recsensorialarea.id
                                WHERE
                                    parametroalimento.recsensorial_id = '.$recsensorial_id.'
                                ORDER BY
                                    parametroalimento.id ASC');

            $numero_registro = 1;
            // lista caracteristicas por registros
            for ($i=0; $i < count($tabla); $i++)
            {
                $tabla[$i]->numero_registro = $numero_registro;
                $numero_registro += 1;

                $lista_texto = '';
                $lista = DB::select('SELECT
                                        parametroalimentocaracteristica.catparametroalimentocaracteristica_id AS id,
                                        catparametroalimentocaracteristica.catparametroalimentocaracteristica_caracteristica
                                    FROM
                                        parametroalimentocaracteristica
                                        LEFT JOIN catparametroalimentocaracteristica ON parametroalimentocaracteristica.catparametroalimentocaracteristica_id = catparametroalimentocaracteristica.id
                                    WHERE
                                        parametroalimentocaracteristica.parametroalimento_id = '.$tabla[$i]->id.'
                                    ORDER BY
                                        parametroalimentocaracteristica.catparametroalimentocaracteristica_id ASC');

                // formatear caracteristicas
                for ($x=0; $x < count($lista); $x++)
                {
                    $lista_texto .= "<li>".$lista[$x]->catparametroalimentocaracteristica_caracteristica."</li>";
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
    public function parametroalimentocaracteristicas($seleccionado_id)
    {
        try
        {
            $tabla = DB::select('SELECT
                                    catparametroalimentocaracteristica.id,
                                    catparametroalimentocaracteristica.catparametroalimentocaracteristica_caracteristica,
                                    catparametroalimentocaracteristica.catparametroalimentocaracteristica_activo,
                                    IFNULL((
                                        SELECT  
                                            IF(parametroalimentocaracteristica.catparametroalimentocaracteristica_id, "checked", "")
                                        FROM
                                            parametroalimentocaracteristica
                                        WHERE
                                            parametroalimentocaracteristica.parametroalimento_id = '.$seleccionado_id.'
                                            AND parametroalimentocaracteristica.catparametroalimentocaracteristica_id = catparametroalimentocaracteristica.id
                                    ), "") AS checked
                                FROM
                                    catparametroalimentocaracteristica
                                WHERE
                                    catparametroalimentocaracteristica.catparametroalimentocaracteristica_activo = 1');

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
                $parametro = parametroalimentoModel::create($request->all());
                $parametro->parametroalimentocaracteristicasincronizacion()->sync($request->caracteristica);

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $parametro = parametroalimentoModel::findOrFail($request['registro_id']);
                $parametro->update($request->all());
                $parametro->parametroalimentocaracteristicasincronizacion()->sync($request->caracteristica);

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
    public function parametroalimentoeliminar($registro_id)
    {
        try
        {
            $parametro = parametroalimentoModel::where('id', $registro_id)->delete();
            $parametro = parametroalimentocaracteristicaModel::where('parametroalimento_id', $registro_id)->delete();

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
