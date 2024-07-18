<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametroserviciopersonalModel;
use DB;

class parametroserviciopersonalController extends Controller
{
    



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroserviciopersonalvista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

        return view('catalogos.recsensorial.parametroserviciopersonal', compact('recsensorial', 'recsensorial_id'));
    }









    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroserviciopersonaltabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $tabla = parametroserviciopersonalModel::where('recsensorial_id', $recsensorial_id)->orderBy('id', 'asc')->get();

            // Formatear filas
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // folios recsensorial
                switch ($value->parametroserviciopersonal_puntos+0)
                {
                    case (($value->parametroserviciopersonal_puntos+0) >= 50):
                        $value->tipo_instalacion = '<span style="min-width: 160px; border-radius: 5px; padding: 6px; text-align: center; background-color: #DF0101; color: #FFFFFF;">Extra grande</span>';
                        break;
                    case (($value->parametroserviciopersonal_puntos+0) >= 31):
                        $value->tipo_instalacion = '<span style="min-width: 160px; border-radius: 5px; padding: 6px; text-align: center; background-color: #FF8000; color: #FFFFFF;">Grande</span>';
                        break;
                    case (($value->parametroserviciopersonal_puntos+0) >= 11):
                        $value->tipo_instalacion = '<span style="min-width: 160px; border-radius: 5px; padding: 6px; text-align: center; background-color: #FFD700; color: #FFFFFF;">Mediana</span>';
                        break;
                    case (($value->parametroserviciopersonal_puntos+0) >= 5):
                        $value->tipo_instalacion = '<span style="min-width: 160px; border-radius: 5px; padding: 6px; text-align: center; background-color: #5FB404; color: #FFFFFF;">Chica</span>';
                        break;
                    case (($value->parametroserviciopersonal_puntos+0) >= 2):
                        $value->tipo_instalacion = '<span style="min-width: 160px; border-radius: 5px; padding: 6px; text-align: center; background-color: #0080FF; color: #FFFFFF;">Extra chica</span>';
                        break;
                    default:
                        $value->tipo_instalacion = 'N/A';
                        break;
                }

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
                DB::statement('ALTER TABLE parametroserviciopersonal AUTO_INCREMENT=1');
                $parametro = parametroserviciopersonalModel::create($request->all());

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                $parametro = parametroserviciopersonalModel::findOrFail($request['registro_id']);
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
    public function parametroserviciopersonaleliminar($registro_id)
    {
        try
        {
            $parametro = parametroserviciopersonalModel::where('id', $registro_id)->delete();

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
