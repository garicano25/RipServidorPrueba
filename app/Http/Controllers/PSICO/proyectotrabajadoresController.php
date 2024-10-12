<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\reconocimientopsico\recopsicotrabajadoresModel;
use App\modelos\reconocimientopsico\recopsicoproyectotrabajadoresModel;


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
     * @return \Illuminate\Http\Response
     */
    public function proyectotrabajadoreslista($RECPSICO_ID)
    {
        try {
            $numero_registros = 0;
            $filas = '';

                $trabajadores = recopsicotrabajadoresModel::where('RECPSICO_ID', 3)
                    ->select('ID_RECOPSICOTRABAJADOR', 'RECPSICOTRABAJADOR_NOMBRE', 'RECPSICOTRABAJADOR_MUESTRA', 'RECPSICOTRABAJADOR_SELECCIONADO', 'RECPSICOTRABAJADOR_OBSERVACION', 'RECPSICOTRABAJADOR_MODALIDAD')
                    ->get();

                $trabajadoresLista = DB::select('SELECT
                                                    ID_RECOPSICOTRABAJADOR,
                                                    RECPSICOTRABAJADOR_NOMBRE,
                                                    RECPSICOTRABAJADOR_MUESTRA,
                                                    RECPSICOTRABAJADOR_SELECCIONADO,
                                                    RECPSICOTRABAJADOR_OBSERVACION,
                                                    RECPSICOTRABAJADOR_MODALIDAD
                                                FROM
                                                    recopsicotrabajadores
                                                WHERE
                                                    RECPSICO_ID = ' . $RECPSICO_ID . ' AND
                                                    RECPSICOTRABAJADOR_MUESTRA = 1
                                                ');


                foreach ($trabajadoresLista as $key => $value) {
                    $lista = '';
                    $readonly_required = '';
                    $required_campo = '';
                    $checked = '';
                    $checked = 'checked';

                    $filas .= '<tr>
                                    <td>' . ($numero_registros + 1) . '</td>
                                    <td>
                                        <div class="switch" style="border: 0px #000 solid;">
                                            <label>
                                                <input type="checkbox" name="RECPSICOTRABAJADOR_SELECCIONADO[]" value="'.$numero_registros.'" checked="'.$checked.'" class="worker-checkbox" data-index="'.$numero_registros.'">
                                                <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="custom-select form-control" name="RECPSICOTRABAJADOR_MODALIDAD[]" id="RECPSICOTRABAJADOR_MODALIDAD[]>
                                            <option value="">Seleccione una modalidad</option>
                                            <option value="Online">Online</option>
                                            <option value="Presencial">Presencial</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="RECPSICOTRABAJADOR_NOMBRE" name="RECPSICOTRABAJADOR_NOMBRE[]" value="' . $value->RECPSICOTRABAJADOR_NOMBRE . '" readonly>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" name="RECPSICOTRABAJADOR_OBSERVACION' . $numero_registros . '" id="RECPSICOTRABAJADOR_OBSERVACION' . $numero_registros . '" value="' . $value->RECPSICOTRABAJADOR_OBSERVACION . '" disabled>
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
            $eliminar_trabajadores = recopsicoproyectotrabajadoresModel::where('proyecto_id', $request["proyecto_id"])->delete();

            // Guardar agentes
            DB::statement("ALTER TABLE proyectoproveedores AUTO_INCREMENT = 1;");

            if ($request->agente_activo) {
                foreach ($request->agente_activo as $key => $value) {
                    $guardar_fisicos = proyectotrabajadoresModel::create([
                        'proyecto_id' => $request["proyecto_id"],
                        'proveedor_id' => $request->proveedor_id[$value],
                        'proyectoproveedores_tipoadicional' => $request->agente_tipo[$value],
                        'catprueba_id' => $request->agente_id[$value],
                        'proyecto_agente' => $request->agente_nombre[$value],
                        'proyecto' => $request->agente_puntos[$value],
                        'proyectoproveedores_observacion' => $request->agente_obs[$value]
                    ]);
                }
            }

            // Agentes adicionales
            if ($request->agenteadicional_activo) {
                foreach ($request->agenteadicional_activo as $key => $value) {
                    // dd($request->agenteadicional_id);
                    $guardar_fisicos = proyectoproveedoresModel::create([
                        'proyecto_id' => $request["proyecto_id"],
                        'proveedor_id' => $request->proveedoradicional_id[$key],
                        'proyectoproveedores_tipoadicional' => $request->agenteadicional_tipo[$key],
                        'catprueba_id' => $request->agenteadicional_id[$key],
                        'proyectoproveedores_agente' => $request->agenteadicional_nombre[$key],
                        'proyectoproveedores_puntos' => $request->agenteadicional_puntos[$key],
                        'proyectoproveedores_observacion' => $request->agenteadicional_obs[$key]
                    ]);
                }
            }

            // respuesta
            $dato["msj"] = 'Datos guardados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // $dato['sustancia'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

}
