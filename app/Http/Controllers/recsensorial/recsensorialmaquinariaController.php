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

    public function recsensorialmaquinariatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            //Tabla de fuentes generadas
            $tabla = DB::select('SELECT a.recsensorialarea_nombre,
                                        m.id,
                                        m.recsensorial_id,
                                        m.recsensorialmaquinaria_afecta,
                                        m.recsensorialmaquinaria_cantidad,
                                        m.recsensorialmaquinaria_contenido,
                                        m.recsensorialmaquinaria_unidadMedida,
                                        m.recsensorialmaquinaria_descripcionfuente,
                                        m.recsensorialmaquinaria_nombrecomun,
                                        m.recsensorialarea_id,
                                        p.id AS PRODUCTO_ID,
                                        IFNULL(m.recsensorialmaquinaria_nombre, p.catsustancia_nombre) as NOMBRE_FUENTE
                            FROM recsensorialmaquinaria m
                            LEFT JOIN recsensorialarea a ON a.id = m.recsensorialarea_id
                            LEFT JOIN catsustancia p ON p.id = m.recsensorialmaquinaria_quimica
                            WHERE m.recsensorial_id = ?', [$recsensorial_id]);


            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->nombre_completo = $value->NOMBRE_FUENTE . '<br> <small class="text-muted">' . $value->recsensorialmaquinaria_descripcionfuente . '</small>';


                //Obtenemos el area afectada
                $areasAfectadas = DB::select('SELECT afectan.TIPO_ALCANCE ALCANCE, prueba.catPrueba_Nombre
                            FROM areasAfectadas_fuentesGeneradoras afectan 
                            LEFT JOIN cat_prueba prueba on prueba.id = afectan.PRUEBA_ID 
                            LEFT JOIN recsensorialmaquinaria maquinaria on maquinaria.id = afectan.FUENTE_GENERADORA_ID
                            WHERE maquinaria.id = ' . $value->id . ' ');

                $cadena = "";
                foreach ($areasAfectadas  as $key => $val) {
                    $cadena .= "<li>" . '[' . $val->ALCANCE . '] ' . $val->catPrueba_Nombre . "</li>";
                }

                $value->areasAfectan = $cadena;


                if ($value->recsensorialmaquinaria_unidadMedida == 7) {
                    $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' PZ';
                } else {

                    switch ($value->recsensorialmaquinaria_unidadMedida) {
                        case 1:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' mg';
                            break;
                        case 2:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' L';
                            break;
                        case 3:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' M³';
                            break;
                        case 4:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' gr';
                            break;
                        case 5:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' Kg';
                            break;
                        case 6:
                            $value->recsensorialmaquinaria_cantidad_formateada =  $value->recsensorialmaquinaria_cantidad . ' de ' . $value->recsensorialmaquinaria_contenido . ' T';
                            break;
                        default:
                            $value->recsensorialmaquinaria_cantidad_formateada =  'ND';

                            break;
                    }
                }


                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    function recsensorialmaquinariaAreasAfectan($FUENTE_GENERADOR_ID)
    {
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


    function validarComponentesMaquinaria($id)
    {
        try {
            $total =  DB::select('SELECT COUNT(*) TOTAL
                                FROM catHojasSeguridad_SustanciasQuimicas 
                                WHERE HOJA_SEGURIDAD_ID = ?', [$id]);

            // respuesta
            $dato['total'] = $total[0]->TOTAL;
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
        try {
            $maquina = recsensorialmaquinariaModel::where('id', $maquina_id)->delete();

            // respuesta
            $dato['eliminado'] = $maquina;
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
            if ($request['maquina_id'] == 0) //nuevo
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
                            'TIPO' => is_null($request->TipoAgente[$key]) ?  null : $request->TipoAgente[$key],

                        ]);
                    }
                }

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            } else //editar
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
                            'TIPO' => is_null($request->TipoAgente[$key]) ?  null : $request->TipoAgente[$key],

                        ]);
                    }
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
            }

            // respuesta
            $dato['maquina'] = $maquina;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
