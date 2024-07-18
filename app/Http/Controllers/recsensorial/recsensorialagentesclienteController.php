<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

// Modelos
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialagentesclienteModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class recsensorialagentesclienteController extends Controller
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
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialagentesclientetabla($recsensorial_id)
    {
        try
        {
             $tabla = DB::select('SELECT
                                        recsensorialagentescliente.recsensorial_id,
                                        recsensorialagentescliente.agentescliente_agenteid,
                                        recsensorialagentescliente.agentescliente_nombre,
                                        IFNULL(recsensorialagentescliente.agentescliente_tipoanalisis, "") AS agentescliente_tipoanalisis,
                                        recsensorialagentescliente.agentescliente_puntos,
                                        IFNULL(recsensorialagentescliente.agentescliente_analisis, "") AS agentescliente_analisis,
                                        IFNULL(recsensorialagentescliente.agentescliente_observacion, "") AS agentescliente_observacion 
                                    FROM
                                        recsensorialagentescliente
                                    WHERE
                                        recsensorialagentescliente.recsensorial_id = '.$recsensorial_id.'
                                    ORDER BY
                                        recsensorialagentescliente.agentescliente_puntos ASC,
                                        recsensorialagentescliente.agentescliente_nombre ASC');


            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);


            // respuesta
            $dato['tabla'] = $tabla;
            $dato['total_agentescliente'] = count($tabla);
            $dato["recsensorial_bloqueado"] = ($recsensorial->recsensorial_bloqueado+0);
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['tabla'] = 0;
            $dato['total_agentescliente'] = 0;
            $dato["recsensorial_bloqueado"] = 0;
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
            // Eliminar datos si existen
            $agentesclienteeliminar = recsensorialagentesclienteModel::where('recsensorial_id', $request['recsensorial_id'])->delete();

            // AUTO_INCREMENT
            DB::statement('ALTER TABLE recsensorialagentescliente AUTO_INCREMENT=1');

            // EPP seleccionados
            foreach ($request->lista_agenteid as $key => $value) 
            {
                $agentescliente = recsensorialagentesclienteModel::create([
                      'recsensorial_id' => $request['recsensorial_id']
                    , 'agentescliente_agenteid' => $value
                    , 'agentescliente_nombre' => $request->lista_agentenombre[$key]
                    , 'agentescliente_tipoanalisis' => $request->lista_agentetipo[$key]
                    , 'agentescliente_puntos' => $request->lista_agentepuntos[$key]
                    , 'agentescliente_analisis' => $request->lista_agenteanalisis[$key]
                    , 'agentescliente_observacion' => $request->lista_agenteobservacion[$key]
                ]);
            }

            // respuesta
            $dato['msj'] = "Información guardada correctamente";
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
