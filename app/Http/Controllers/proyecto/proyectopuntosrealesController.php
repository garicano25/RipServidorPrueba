<?php

namespace App\Http\Controllers\proyecto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectopuntosrealesModel;

class proyectopuntosrealesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
    }
   

    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
    */
    public function proyectopuntosrealeslista($proyecto_id)
    {
        try
        {
            $where_adicional = '';
            if (auth()->user()->hasRoles(['Externo']))
            {
                $where_adicional = 'AND proveedor.id = '.auth()->user()->empleado_id;
            }


            $sql = DB::select('SELECT
                                    proyectoproveedores.proyecto_id,
                                    proyectoproveedores.id,
                                    proveedor.id AS proveedor_id,
                                    proveedor.proveedor_NombreComercial,
                                    proyectoproveedores.proyectoproveedores_tipoadicional,
                                    proyectoproveedores.catprueba_id,
                                    proyectoproveedores.proyectoproveedores_agente,
                                    proyectoproveedores.proyectoproveedores_puntos,
                                    proyectopuntosreales.proyectopuntosreales_puntos,
                                    proyectopuntosreales.proyectopuntosreales_observacion 
                                FROM
                                    proyectoproveedores
                                    LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                    LEFT JOIN proyectopuntosreales ON proyectoproveedores.id = proyectopuntosreales.proyectoproveedores_id 
                                WHERE
                                    proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                    '.$where_adicional.' 
                                    AND proyectoproveedores.catprueba_id > 0 
                                    AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%" 
                                ORDER BY
                                    proveedor.proveedor_NombreComercial ASC,
                                    proyectoproveedores.proyectoproveedores_agente ASC');

            $filas = '';
            $total = 0;
            $required = '';
            if (count($sql) > 0)
            {
                foreach ($sql as $key => $value)
                {
                    if ($value->proyectopuntosreales_puntos)
                    {
                        $total += 1;
                    }


                    $required = 'readonly';
                    if (($value->proyectoproveedores_puntos+0) != ($value->proyectopuntosreales_puntos+0))
                    {
                        $required = 'required';
                    }


                    $filas.='<tr>
                                <td>
                                    <input type="hidden" class="form-control" name="puntoreal_id[]" value="'.$value->id.'">
                                    <span style="font-size: 13px; color: #777777;">'.$value->proyectoproveedores_agente.'</span><br>
                                    <small>'.$value->proveedor_NombreComercial.'</small>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 18px;">'.$value->proyectoproveedores_puntos.'</span>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="puntoreal[]" value="'.$value->proyectopuntosreales_puntos.'" onchange="verifica_puntosreales('.$value->proyectoproveedores_puntos.', this.value, '.$key.');" required>
                                </td>
                                <td>';
                                    
                                    if ($value->proyectopuntosreales_observacion)
                                    {
                                        $filas.='<span class="mytooltip tooltip-effect-1">
                                                    <input type="text" class="form-control" name="puntorealobservacion[]" value="'.$value->proyectopuntosreales_observacion.'" id="puntorealobservacion_'.$key.'" '.$required.'>
                                                    <span class="tooltip-content clearfix">
                                                        <span class="tooltip-text" style="padding: 12px; text-align: center;">'.$value->proyectopuntosreales_observacion.'</span>
                                                    </span>
                                                </span>';
                                    }
                                    else
                                    {
                                        $filas.='<input type="text" class="form-control" name="puntorealobservacion[]" value="'.$value->proyectopuntosreales_observacion.'" id="puntorealobservacion_'.$key.'" '.$required.'>';
                                    }

                        $filas.='</td>
                            </tr>';
                }
            }
            else
            {
                $filas = '<tr><td colspan="4" style="text-align: center;">NO hay datos que mostrar</td></tr>';
            }


            // Estado
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $dato["puntosreales_estado"] = ($proyecto->proyecto_puntosrealesactivo+0);


            // respuesta
            $dato['filas'] = $filas;
            $dato['puntosreales_total'] = $total;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['filas'] = '<tr><td colspan="4" style="text-align: center;">Error al cargar los agentes</td></tr>';
            $dato["puntosreales_estado"] = 0;
            $dato['puntosreales_total'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectopuntosrealesreporte($proyecto_id)
    {
        try
        {
            // Proyecto
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            // Generar folio OT
            $proyecto_folio = explode("-", $proyecto->proyecto_folio);
            $ot_folio = 'RIP-OTEH-'.$proyecto_folio[1].'-'.$proyecto_folio[2];
            $proyecto->folio_ot = $ot_folio;

            // Lista de puntos reales por agente
            $puntosrealeslista = collect(DB::select('SELECT
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.id,
                                                        proveedor.proveedor_NombreComercial,
                                                        proyectoproveedores.proyectoproveedores_tipoadicional,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        proyectoproveedores.proyectoproveedores_puntos,
                                                        proyectopuntosreales.proyectopuntosreales_puntos,
                                                        proyectopuntosreales.proyectopuntosreales_observacion 
                                                    FROM
                                                        proyectoproveedores
                                                        LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                                        LEFT JOIN proyectopuntosreales ON proyectoproveedores.id = proyectopuntosreales.proyectoproveedores_id 
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                                        AND proyectoproveedores.catprueba_id > 0 
                                                        AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                                    ORDER BY
                                                        proveedor.proveedor_NombreComercial ASC,
                                                        proyectoproveedores.proyectoproveedores_agente ASC'));

            //===========================================


            // return view('reportes.proyecto.ordentrabajo', compact('proyecto', 'lista'));
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', $proyecto, $proveedores_fisicos)


            // Convierte pagina en PDF y lo descarga o muestra en navegador
            // return \PDF::loadView('reportes.proyecto.ordentrabajo', compact('proyecto', 'proveedores_fisicos', 'proveedores_quimicos'))
                        // ->setPaper('letter', 'landscape') //portrait, landscapes
                        // ->save(storage_path('app/ORDEN_TARABAJO/').$proyecto->proyecto_folio.'.pdf')
                        // ->download('archivo.pdf')
                        // ->stream('archivo.pdf');


            return \PDF::loadView('reportes.proyecto.reporteproyectopuntosreales', compact('proyecto', 'puntosrealeslista'))->stream('Puntos reales '.$proyecto->proyecto_folio.'.pdf');
            // return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            // $dato['opciones'] = $opciones_select;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectopuntosrealesactivo($proyecto_id)
    {
        try
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            if (($proyecto->proyecto_puntosrealesactivo+0) == 0)
            {
                $proyecto->update([
                    'proyecto_puntosrealesactivo' => 1
                ]);


                // Mensaje
                $dato["puntosreales_estado"] = 1;
                $dato["msj"] = 'Puntos reales desbloqueado para edición';
            }
            else
            {
                $proyecto->update([
                    'proyecto_puntosrealesactivo' => 0
                ]);

                // Mensaje
                $dato["puntosreales_estado"] = 0;
                $dato["msj"] = 'Puntos reales bloqueado para edición';
            }

            // Respuesta
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
            if ($request->puntoreal_id)
            {
                $eliminar = proyectopuntosrealesModel::where('proyecto_id', $request->proyecto_id)->delete();

                DB::statement("ALTER TABLE proyectopuntosreales AUTO_INCREMENT = 1;");

                foreach ($request->puntoreal_id as $key => $value) 
                {
                    $puntoreal = proyectopuntosrealesModel::create([
                          'proyecto_id' => $request->proyecto_id
                        , 'proyectoproveedores_id' => $value
                        , 'proyectopuntosreales_puntos' => $request->puntoreal[$key]
                        , 'proyectopuntosreales_observacion' => $request->puntorealobservacion[$key]
                    ]);
                }

                // respuesta
                $dato["puntosreales_total"] = count($request->puntoreal_id);
                $dato["msj"] = 'Datos guardados correctamente';
            }
            else
            {
                // respuesta
                $dato["puntosreales_total"] = 0;
                $dato["msj"] = 'No se encontraron datos que guardar';
            }

            return response()->json($dato);
        }
        catch(\Exception $e)
        {
            // respuesta
            $dato["puntosreales_total"] = 0;
            $dato["msj"] = 'Error: '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
