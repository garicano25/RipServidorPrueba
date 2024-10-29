<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use App\modelos\reconocimientopsico\proyectotrabajadoresModel;


class proyectotrabajadoresController extends Controller
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
     * @param  int  $RECPSICO_ID
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectotrabajadoreslista($RECPSICO_ID, $proyecto_id)
    {
        try {
            $numero_registros = 0;
            $filas = '';

                //si ya hay datos guardados en programa de trabajo
                $exists = DB::table('proyectotrabajadores')
                ->where('proyecto_id', $proyecto_id)
                ->exists();


            if ($exists) {
                $trabajadoresLista = DB::select('SELECT 
                                                TRABAJADOR_ID,
                                                TRABAJADOR_NOMBRE,
                                                TRABAJADOR_SELECCIONADO,
                                                TRABAJADOR_OBSERVACION,
                                                TRABAJADOR_MODALIDAD
                                            FROM 
                                                proyectotrabajadores
                                            WHERE 
                                                proyecto_id = ' . $proyecto_id . '

                                            UNION ALL

                                            SELECT
                                                ID_RECOPSICOTRABAJADOR AS TRABAJADOR_ID,
                                                RECPSICOTRABAJADOR_NOMBRE AS TRABAJADOR_NOMBRE,
                                                RECPSICOTRABAJADOR_SELECCIONADO AS TRABAJADOR_SELECCIONADO,
                                                RECPSICOTRABAJADOR_OBSERVACION AS TRABAJADOR_OBSERVACION,
                                                RECPSICOTRABAJADOR_MODALIDAD AS TRABAJADOR_MODALIDAD
                                            FROM 
                                                recopsicotrabajadores
                                            WHERE 
                                                RECPSICO_ID = ' . $RECPSICO_ID . ' 
                                                AND RECPSICOTRABAJADOR_MUESTRA = 1
                                                AND NOT EXISTS (
                                                    SELECT 1 
                                                    FROM proyectotrabajadores 
                                                    WHERE proyecto_id = ' . $proyecto_id . '
                                                )');
            } else {
                
                $trabajadoresLista = DB::select('SELECT
                                                    ID_RECOPSICOTRABAJADOR AS TRABAJADOR_ID,
                                                    RECPSICOTRABAJADOR_NOMBRE AS TRABAJADOR_NOMBRE,
                                                    RECPSICOTRABAJADOR_MUESTRA AS TRABAJADOR_SELECCIONADO,
                                                    RECPSICOTRABAJADOR_OBSERVACION AS TRABAJADOR_OBSERVACION,
                                                    RECPSICOTRABAJADOR_MODALIDAD AS TRABAJADOR_MODALIDAD
                                                FROM
                                                    recopsicotrabajadores
                                                WHERE
                                                    RECPSICO_ID = ' . $RECPSICO_ID . ' AND
                                                    RECPSICOTRABAJADOR_MUESTRA = 1
                                                ');
            }

                foreach ($trabajadoresLista as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';


                    $checked = 'checked';

                    if ($value->TRABAJADOR_SELECCIONADO) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }

                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . ' 
                                        <input type="hidden" id="TRABAJADOR_ID" name="TRABAJADOR_ID[]" value="' . $value->TRABAJADOR_ID . '">
                                    </td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="TRABAJADOR_SELECCIONADO[]" value="'.$numero_registros.'"  class="worker-checkbox" data-index="'.$numero_registros.'" '.$checked.'>
                                                <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="TRABAJADOR_MODALIDAD[]" id="TRABAJADOR_MODALIDAD">
                                            <option value="Online" ' . ($value->TRABAJADOR_MODALIDAD == "Online" ? "selected" : "") . '>Online</option>
                                            <option value="Presencial" ' . ($value->TRABAJADOR_MODALIDAD == "Presencial" ? "selected" : "") . '>Presencial</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="TRABAJADOR_NOMBRE" name="TRABAJADOR_NOMBRE[]" value="' . $value->TRABAJADOR_NOMBRE . '" readonly>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" name="TRABAJADOR_OBSERVACION[]" id="TRABAJADOR_OBSERVACION' . $numero_registros . '" value="' . $value->TRABAJADOR_OBSERVACION . '" disabled>
                                    </td>
                                </tr>';

                    $numero_registros += 1;
                }
    
            $dato['numero_registros'] = $numero_registros;
            $dato['filas'] = $filas;
            $dato["msj"] = 'Datos consultados correctamente';
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

    public function proyectotrabajadoresadicionales(Request $request)
    {
        $term = $request->input('term');
    
        $trabajadores = recopsicotrabajadoresModel::where('RECPSICOTRABAJADOR_NOMBRE', 'LIKE', '%' . $term . '%')
            ->where('RECPSICO_ID', 3)
            ->where('RECPSICOTRABAJADOR_MUESTRA', 0)
            ->select('ID_RECOPSICOTRABAJADOR', 'RECPSICOTRABAJADOR_NOMBRE') 
            ->get();
       
        return response()->json($trabajadores);
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
             $eliminar_trabajadores = proyectotrabajadoresModel::where('proyecto_id', $request["proyecto_id"])->delete();
     
             $selectedWorkers = $request->input('TRABAJADOR_SELECCIONADO', []);
     
             // Obtener todos los IDs de trabajadores
             $allWorkerIds = $request->TRABAJADOR_ID;
             $contadorComentario = 0;
     
             foreach ($allWorkerIds as $key => $trabajadorId) {
                 $isSelected = in_array($key, $selectedWorkers);
     
                 $trabajadorData = [
                     'proyecto_id' => $request->proyecto_id,
                     'TRABAJADOR_ID' => $trabajadorId,
                     'TRABAJADOR_NOMBRE' => $request->TRABAJADOR_NOMBRE[$key],
                     'TRABAJADOR_SELECCIONADO' => $isSelected ? 1 : 0,
                 ];
     
                 if ($isSelected) {
                     $trabajadorData['TRABAJADOR_MODALIDAD'] = $request->TRABAJADOR_MODALIDAD[$key] ?? '';
                 } else {
                    if($contadorComentario>0){
                        $trabajadorData['TRABAJADOR_OBSERVACION'] = $request->TRABAJADOR_OBSERVACION[$contadorComentario] ?? '';
                        $contadorComentario++;
                    }else{
                        $trabajadorData['TRABAJADOR_OBSERVACION'] = $request->TRABAJADOR_OBSERVACION[$contadorComentario] ?? '';
                        $contadorComentario++;
                    }
                     
                 }
     
                 $guardar_trabajadormodalidad = proyectotrabajadoresModel::create($trabajadorData);
             }
     
             // respuesta
             $dato["msj"] = 'Datos guardados correctamente';
             return response()->json($dato);
         } catch (Exception $e) {
             $dato["msj"] = 'Error ' . $e->getMessage();
             return response()->json($dato);
         }
     }
            //$eliminar_trabajadores = proyectotrabajadoresModel::where('proyecto_id', $request["proyecto_id"])->delete();


       // Agentes adicionales
            // if ($request->agenteadicional_activo) {
            //     foreach ($request->agenteadicional_activo as $key => $value) {
            //         // dd($request->agenteadicional_id);
            //         $guardar_fisicos = proyectoproveedoresModel::create([
            //             'proyecto_id' => $request["proyecto_id"],
            //             'proveedor_id' => $request->proveedoradicional_id[$key],
            //             'proyectoproveedores_tipoadicional' => $request->agenteadicional_tipo[$key],
            //             'catprueba_id' => $request->agenteadicional_id[$key],
            //             'proyectoproveedores_agente' => $request->agenteadicional_nombre[$key],
            //             'proyectoproveedores_puntos' => $request->agenteadicional_puntos[$key],
            //             'proyectoproveedores_observacion' => $request->agenteadicional_obs[$key]
            //         ]);
            //     }
            // }

}
