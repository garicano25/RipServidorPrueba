<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametroergonomiaModel;
use App\modelos\recsensorial\parametroergonomiaareaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametroergonomiaController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroergonomiavista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametroergonomia', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroergonomiatabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            // $tabla = recsensorialareaModel::all();
            $tabla = parametroergonomiaModel::with(['recsensorialcategoria', 'parametroergonomialistaareas', 'parametroergonomialistaareas.recsensorialarea'])
                    ->where('recsensorial_id', $recsensorial_id)
                    ->orderBy('id', 'asc')
                    ->get();

            // recorrer lista
            foreach ($tabla  as $key => $value) 
            {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                //==================================

                if ($value['parametroergonomia_movimientorepetitivo']==1) {
                    $value['movimientorepetitivo'] = '<i class="fa fa-check"></i>';
                }
                else{
                    $value['movimientorepetitivo'] = '<i class="fa fa-ban"></i>';
                }

                //==================================

                if ($value['parametroergonomia_posturamantenida']==1) {
                    $value['posturamantenida'] = '<i class="fa fa-check"></i>';
                }
                else{
                    $value['posturamantenida'] = '<i class="fa fa-ban"></i>';
                }

                //==================================

                if ($value['parametroergonomia_posturaforzada']==1) {
                    $value['posturaforzada'] = '<i class="fa fa-check"></i>';
                }
                else{
                    $value['posturaforzada'] = '<i class="fa fa-ban"></i>';
                }

                //==================================

                if ($value['parametroergonomia_fuerza']==1) {
                    $value['fuerza'] = '<i class="fa fa-check"></i>';
                }
                else{
                    $value['fuerza'] = '<i class="fa fa-ban"></i>';
                }

                //==================================

                if ($value['parametroergonomia_cargamanual']==1) {
                    $value['cargamanual'] = '<i class="fa fa-check"></i>';
                }
                else{
                    $value['cargamanual'] = '<i class="fa fa-ban"></i>';
                }

                //==================================


                // obtener las areas
                $value['areas'] = $value->parametroergonomialistaareas->pluck('recsensorialarea.recsensorialarea_nombre');
                
                // crear lista
                $cadena = "";
                foreach ($value['areas']  as $key => $area) 
                {
                    $cadena .= "<li>".$area."</li>";
                }
                $value['areas'] = $cadena;

                //==================================

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
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
     * @param  int  $recsensorial_id
     * @param  int  $parametroergonomia_id
     * @return \Illuminate\Http\Response
     */
    public function ergonomiaareas($recsensorial_id, $parametroergonomia_id)
    {
        try
        {
            $ergonomiaareas = DB::select('SELECT
                                                recsensorialarea.id,
                                                recsensorialarea.recsensorial_id,
                                                recsensorialarea.recsensorialarea_nombre,
                                                IF((
                                                    SELECT
                                                        parametroergonomiaarea.recsensorialarea_id 
                                                    FROM
                                                        parametroergonomiaarea
                                                    WHERE
                                                        parametroergonomiaarea.parametroergonomia_id = '.$parametroergonomia_id.' AND parametroergonomiaarea.recsensorialarea_id = recsensorialarea.id
                                                    GROUP BY
                                                        parametroergonomiaarea.recsensorialarea_id
                                                ), "checked", "") AS seleccionado
                                            FROM
                                                recsensorialarea
                                            WHERE
                                                recsensorialarea.recsensorial_id = '.$recsensorial_id.'
                                            ORDER BY
                                                recsensorialarea.recsensorialarea_nombre ASC');

            // respuesta
            $dato['ergonomiaareas'] = $ergonomiaareas;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['ergonomiaareas'] = 0;
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
                $parametro = parametroergonomiaModel::create($request->all());
                $parametro->parametroergonomiaarea()->sync($request->ergonomiaarea);

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $parametro = parametroergonomiaModel::findOrFail($request['registro_id']);
                $parametro->parametroergonomiaarea()->sync($request->ergonomiaarea);
                $parametro->update($request->all());

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
    public function parametroergonomiaeliminar($registro_id)
    {
        try
        {
            $parametro = parametroergonomiaModel::where('id', $registro_id)->delete();

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
