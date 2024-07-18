<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialmaquinariaModel;
use App\modelos\recsensorial\areasAfectadas_fuentesGeneradorasModel;
use DB;

class recsensorialmaquinariaController extends Controller
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
    public function recsensorialmaquinariatabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            
            // $tabla = recsensorialmaquinariaModel::all();
            $tabla = recsensorialmaquinariaModel::with(['recsensorialarea'])
                    ->where('recsensorial_id', $recsensorial_id)
                    ->orderBy('recsensorialarea_id', 'ASC')
                    ->get();


            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($tabla as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // switch ($value['recsensorialmaquinaria_afecta']+0)
                // {
                //     case 1:
                //         $value['riesgos_provocados'] = "Factores físicos";
                //         break;
                //     case 2:
                //         $value['riesgos_provocados'] = "Factores químicos";
                //         break;
                //     case 3:
                //         $value['riesgos_provocados'] = "Factores físicos y químicos";
                //         break;
                // }
                //Obtenemos el area afectada
                $areasAfectadas = DB::select('SELECT afectan.TIPO_ALCANCE ALCANCE, prueba.catPrueba_Nombre
                            FROM areasAfectadas_fuentesGeneradoras afectan 
                            LEFT JOIN cat_prueba prueba on prueba.id = afectan.PRUEBA_ID 
                            LEFT JOIN recsensorialmaquinaria maquinaria on maquinaria.id = afectan.FUENTE_GENERADORA_ID
                            WHERE maquinaria.id = ' . $value->id .' ');

                $cadena = "";
                foreach ($areasAfectadas  as $key => $val) {
                    $cadena .= "<li>" . '['. $val->ALCANCE .'] '. $val->catPrueba_Nombre . "</li>";


                }

                $value['areasAfectan'] = $cadena;

                
                if($value['recsensorialmaquinaria_unidadMedida'] == 'PZ'){
                    $value['recsensorialmaquinaria_cantidad_formateada'] =  $value['recsensorialmaquinaria_cantidad'] . ' PZ';
                }else{
                    $value['recsensorialmaquinaria_cantidad_formateada'] =  $value['recsensorialmaquinaria_cantidad'] . ' de '. $value['recsensorialmaquinaria_contenido'] .' '. $value['recsensorialmaquinaria_unidadMedida'];
                    
                }    



                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $value->accion_activa = 0;
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


    function recsensorialmaquinariaAreasAfectan($FUENTE_GENERADOR_ID){
        try {
            $maquina = areasAfectadas_fuentesGeneradorasModel::where('FUENTE_GENERADORA_ID', $FUENTE_GENERADOR_ID)->get();

            // respuesta
            $dato['info'] = $maquina;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }









    /**
     * Display the specified resource.
     *
     * @param  int  $maquina_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialmaquinariaeliminar($maquina_id)
    {
        try
        {
            $maquina = recsensorialmaquinariaModel::where('id', $maquina_id)->delete();

            // respuesta
            $dato['eliminado'] = $maquina;
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
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
            if ($request['maquina_id']==0) //nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialmaquinaria AUTO_INCREMENT=1');

                // guardar
                $maquina = recsensorialmaquinariaModel::create($request->all());


                if ($request->AreaTipoAfecta) {
                    foreach ($request->AreaTipoAfecta as $key => $value) {

                       
                    
                        $guardar_areas_afectan = areasAfectadas_fuentesGeneradorasModel::create([
                            'FUENTE_GENERADORA_ID' => $maquina->id, 
                            'PRUEBA_ID' => $request->AgenteFactor[$key],
                            'TIPO_ALCANCE' => $request->AreaTipoAfecta[$key],
                            'TIPO' => isset($request->TipoAgente[$key]) ? $request->TipoAgente[$key] : null,

                        ]);
                    }
                }

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $maquina = recsensorialmaquinariaModel::findOrFail($request['maquina_id']);
                $maquina->update($request->all());

                //Modificamos las areas que afectan de la Fuente Generadora
                $eliminar_partidas = areasAfectadas_fuentesGeneradorasModel::where('FUENTE_GENERADORA_ID', $request["maquina_id"])->delete();
                if ($request->AreaTipoAfecta) {
                    foreach ($request->AreaTipoAfecta as $key => $value) {

                        $guardar_areas_afectan = areasAfectadas_fuentesGeneradorasModel::create([
                                'FUENTE_GENERADORA_ID' => $maquina->id,
                                'PRUEBA_ID' => $request->AgenteFactor[$key],
                                'TIPO_ALCANCE' => $request->AreaTipoAfecta[$key],
                                'TIPO' => isset($request->TipoAgente[$key]) ? $request->TipoAgente[$key] : null,

                            ]);
                    }
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
            }

            // respuesta
            $dato['maquina'] = $maquina;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    
}
