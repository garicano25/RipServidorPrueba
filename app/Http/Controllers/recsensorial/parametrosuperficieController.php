<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametrosuperficieModel;
use App\modelos\recsensorial\parametrosuperficiecaracteristicaModel;
use App\modelos\recsensorial\catparametrosuperficiecaracteristicaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametrosuperficieController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrosuperficievista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametrosuperficie', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrosuperficietabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            // $tabla = recsensorialareaModel::all();
            $tabla = parametrosuperficieModel::with(['recsensorialarea'])
                    ->where('recsensorial_id', $recsensorial_id)
                    ->orderBy('id', 'asc')
                    ->get();

            // agrupar pruebas por area
            foreach ($tabla  as $key => $value) 
            {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
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
     * @param  int  $seleccionado_id
     * @return \Illuminate\Http\Response
     */
    public function parametrosuperficiecaracteristicas($seleccionado_id)
    {
        try
        {
            $tabla = DB::select('SELECT
                                    catparametrosuperficiecaracteristica.id,
                                    catparametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_caracteristica,
                                    catparametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_activo,
                                    IFNULL((
                                        SELECT  
                                            IF(parametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_id, "checked", "")
                                        FROM
                                            parametrosuperficiecaracteristica
                                        WHERE
                                            parametrosuperficiecaracteristica.parametrosuperficie_id = '.$seleccionado_id.'
                                            AND parametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_id = catparametrosuperficiecaracteristica.id
                                    ), "") AS checked
                                FROM
                                    catparametrosuperficiecaracteristica
                                WHERE
                                    catparametrosuperficiecaracteristica.catparametrosuperficiecaracteristica_activo = 1');

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
                $parametro = parametrosuperficieModel::create($request->all());
                $parametro->parametrosuperficiecaracteristicasincronizacion()->sync($request->caracteristica);

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $parametro = parametrosuperficieModel::findOrFail($request['registro_id']);
                $parametro->update($request->all());
                $parametro->parametrosuperficiecaracteristicasincronizacion()->sync($request->caracteristica);

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
    public function parametrosuperficieeliminar($registro_id)
    {
        try
        {
            $parametro = parametrosuperficieModel::where('id', $registro_id)->delete();
            $parametro = parametrosuperficiecaracteristicaModel::where('parametrosuperficie_id', $registro_id)->delete();

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
