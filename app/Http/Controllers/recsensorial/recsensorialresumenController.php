<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

// Modelos
use App\modelos\recsensorial\recsensorialModel;

class recsensorialresumenController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialresumentabla($recsensorial_id)
    {
        try {
            $sql = DB::select('CALL sp_obtener_volumetria_fisico_proyecto_b(?)', [$recsensorial_id]);


            // dibujar filas tabla
            $tabla = '';
            foreach ($sql  as $key => $value) {
                $tabla .= '<tr>';
                // $tabla .= '<td><span class="round" style="background-color: '.$value->tipoinstalacion_color.'"><i style="font-style: normal;">'.$value->tipoinstalacion_abreviacion.'</i></span></td>';
                $tabla .= '<td><h6>' . $value->catPrueba_Nombre . '</h6><small class="text-muted">Registros: ' . $value->totalregistros . '</small></td>';
                // $tabla .= '<td><b>'.$value->totalpuntos.'</b></td>';
                $tabla .= '<td><span class="round" style="background-color: ' . $value->tipoinstalacion_color . ';"><i style="font-weight: normal; font-size: 15px!important;">' . $value->totalpuntos . '</i></span></td>';
                $tabla .= '</tr>';
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialquimicosresumentabla($recsensorial_id)
    {
        try {
            $tot_registros = 0;

            $sql = DB::select('CALL sp_obtener_puntos_finales_b(?)', [$recsensorial_id]);

            // dibujar filas tabla
            $tabla = '';
            foreach ($sql as $key => $value) {
                $tot_registros += 1;

                $tabla .= '<tr>';
                $tabla .= '<td><h6>' . $value->PRODUCTO_COMPONENTE . '</h6><small class="text-muted">Registros: ' . $value->TOTAL_REGISTROS . '</small></td>';
                $tabla .= '<td><span class="round" style="background-color: #5FB404;"><i style="font-weight: normal; font-size: 15px!important;">' . $value->TOTAL_MUESTREO . '</i></span></td>';

                $tabla .= '</tr>';
            }


            // si no hay registros
            if ($tot_registros == 0) {
                $tabla .= '<tr><td colspan="4">Los puntos de muestreo aún no han sido confirmados</td></tr>';
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function recsensorialquimicosresumentabla_cliente($recsensorial_id)
    {
        try {
            $tot_registros = 0;



            $sql = DB::select(' SELECT 
                                    sus.SUSTANCIA_QUIMICA PRODUCTO_COMPONENTE,
                                    SUM(cliente.PUNTOS) TOTAL_MUESTREO,
                                    COUNT(cliente.ID_TABLA_INFORME_CLIENTE) AS TOTAL_REGISTROS
                                FROM recsensorial_tablaClientes_informes cliente
                                LEFT JOIN catsustancias_quimicas sus ON sus.ID_SUSTANCIA_QUIMICA = cliente.SUSTANCIA_ID
                                WHERE cliente.RECONOCIMIENTO_ID = ?
                                ORDER BY PRODUCTO_COMPONENTE', [$recsensorial_id]);

            // dibujar filas tabla
            $tabla = '';
            foreach ($sql as $key => $value) {
                $tot_registros += 1;

                $tabla .= '<tr>';
                $tabla .= '<td><h6>' . $value->PRODUCTO_COMPONENTE . '</h6><small class="text-muted">Registros: ' . $value->TOTAL_REGISTROS . '</small></td>';
                $tabla .= '<td><span class="round" style="background-color: #5FB404;"><i style="font-weight: normal; font-size: 15px!important;">' . $value->TOTAL_MUESTREO . '</i></span></td>';

                $tabla .= '</tr>';
            }


            // si no hay registros
            if ($tot_registros == 0) {
                $tabla .= '<tr><td colspan="4">No hay sustancias químicas adicionales</td></tr>';
            }

            // respuesta
            $dato['tabla'] = $tabla;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
