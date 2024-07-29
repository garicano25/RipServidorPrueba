<?php

namespace App\Http\Controllers\catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\ServicioModel;
use App\modelos\catalogos\ServiciopreciosModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class servicioController extends Controller
{




    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras');
    }






    //Desactivar cotización
    public function cotizacionproveedorBloqueo($cotizacion_id, $accion)
    {
        try {
            $cotizacion = ServicioModel::findOrFail($cotizacion_id);

            if ($accion == 1) {

                $cotizacion->ACTIVO_COTIZACIONPROVEEDOR = 0;
                $dato["msj"] = 'Cotización Desactivada';
            } else {

                $cotizacion->ACTIVO_COTIZACIONPROVEEDOR = 1;
                $dato["msj"] = 'Cotización Activada';
            }

            $cotizacion->save();
            $dato["cotizacion"] = $cotizacion;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    //Desactivar partida 

    public function partidaproveedorBloqueo($partida_id, $accion)
    {
        try {
            $partida = ServiciopreciosModel::findOrFail($partida_id);

            if ($accion == 1) {

                $partida->ACTIVO_PARTIDAPROVEEDOR  = 0;
                $dato["msj"] = 'Partida Desactivada';
            } else {

                $partida->ACTIVO_PARTIDAPROVEEDOR  = 1;
                $dato["msj"] = 'Partida Activada';
            }

            $partida->save();
            $dato["partida"] = $partida;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
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
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function tablaproveedorservicio($proveedor_id)
    {
        try {
            // Proveedor
            $proveedor = ProveedorModel::findOrFail($proveedor_id);

            //Consulta tabla equipo relacion con area y prueba            
            $servicios = ServicioModel::where('proveedor_id', $proveedor_id)
                ->where('servicio_Eliminado', 0)
                ->orderby('id', 'ASC')
                ->get();

            $numero_registro = 0;

            // Formatear filas
            foreach ($servicios  as $key => $value) {
                $numero_registro += 1;

                // Formato fecha
                // $value->servicio_FechaCotizacion = Carbon::createFromFormat('Y-m-d', $value->servicio_FechaCotizacion)->format('d-m-Y');
                // $value->servicio_VigenciaCotizacion = Carbon::createFromFormat('Y-m-d', $value->servicio_VigenciaCotizacion)->format('d-m-Y');

                // determinar los dias faltantes para vigencia
                $datetime1 = date_create(date('Y-m-d'));
                $datetime2 = date_create($value->servicio_VigenciaCotizacion);
                $interval = date_diff($datetime1, $datetime2);

                // Color registro segun vigencia
                switch (($interval->format('%R%a') + 0)) {
                    case (($interval->format('%R%a') + 0) <= 30):
                        $value->numero_registro = $numero_registro;
                        $value->cotizacion = '<b class="text-danger">' . $value->servicio_numerocotizacion . '</b>';
                        $value->fecha = '<b class="text-danger">' . $value->servicio_FechaCotizacion . '</b>';
                        $value->Vigencia_texto = '<b class="text-danger">' . $value->servicio_VigenciaCotizacion . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        break;
                    case (($interval->format('%R%a') + 0) <= 90):
                        $value->numero_registro = $numero_registro;
                        $value->cotizacion = '<b class="text-warning">' . $value->servicio_numerocotizacion . '</b>';
                        $value->fecha = '<b class="text-warning">' . $value->servicio_FechaCotizacion . '</b>';
                        $value->Vigencia_texto = '<b class="text-warning">' . $value->servicio_VigenciaCotizacion . ' (' . ($interval->format('%R%a') + 0) . ' d)</b>';
                        break;
                    default:
                        $value->numero_registro = $numero_registro;
                        $value->cotizacion = $value->servicio_numerocotizacion;
                        $value->fecha = $value->servicio_FechaCotizacion;
                        $value->Vigencia_texto = $value->servicio_VigenciaCotizacion;
                        break;
                }

                if ($value->ACTIVO_COTIZACIONPROVEEDOR == 1) {
                    // Botones para cotización activa
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                        $value->accion_activa = 1;
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                        $value->boton_desactivar = '<button type="button" class="btn btn-info btn-circle boton_desactivar" data-toggle="tooltip" data-placement="top" title="Desactivar cotización"><i class="fa fa-lock"></i></button>';
                    } else {
                        $value->accion_activa = 0;
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    }
                } else {
                    // Botones para cotización desactivada
                    if (auth()->user()->hasRoles(['Superusuario', 'Administrador'])) {
                        $value->accion_activa = 1;
                        $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                        $value->boton_desactivar = '<button type="button" class="btn btn-info btn-circle boton_activar" data-toggle="tooltip" data-placement="top" title="Activar cotización"><i class="fa fa-unlock"></i></button>';
                    } else {
                        $value->accion_activa = 0;
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    }
                }
            }

            // devolver datos
            $listado['data'] = $servicios;
            return response()->json($listado);
        } catch (exception $e) {
            $listado['data'] = 0;
            return response()->json($listado);
        }
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function serviciolistaalcances($proveedor_id)
    {
        try {
            $opciones = '<option value="">Buscar</option>';
            $alcances = DB::select('SELECT
                                        TABLA.tipo,
                                        TABLA.proveedor_id,
                                        TABLA.agente_id,
                                        TABLA.agente_nombre
                                    FROM
                                        (
                                            (
                                                SELECT
                                                    0 tipo,
                                                    acreditacionalcance.proveedor_id,
                                                    acreditacionalcance.prueba_id AS agente_id,
                                                    IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                                FROM
                                                    acreditacionalcance
                                                WHERE
                                                    acreditacionalcance.proveedor_id = ' . $proveedor_id . '
                                                    AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                    AND acreditacionalcance.prueba_id != 15
                                            )
                                            UNION ALL
                                            (
                                                SELECT
                                                    1 tipo,
                                                    acreditacionalcance.proveedor_id,
                                                    acreditacionalcance.prueba_id AS agente_id,
                                                    IF(IFNULL(acreditacionalcance.acreditacionAlcance_agentetipo, "") = "", acreditacionalcance.acreditacionAlcance_agente, CONCAT(acreditacionalcance.acreditacionAlcance_agente, " (", acreditacionalcance.acreditacionAlcance_agentetipo,")")) AS agente_nombre
                                                FROM
                                                    acreditacionalcance
                                                WHERE
                                                    acreditacionalcance.proveedor_id = ' . $proveedor_id . '
                                                    AND acreditacionalcance.acreditacionAlcance_Eliminado = 0
                                                    AND acreditacionalcance.prueba_id = 15
                                            )
                                        ) AS TABLA
                                    ORDER BY
                                        TABLA.tipo ASC,
                                        TABLA.agente_nombre ASC');

            foreach ($alcances as $key => $value) {
                $opciones .= '<option value="' . $value->agente_id . '~' . $value->agente_nombre . '">' . $value->agente_nombre . '</option>';
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $opciones = '<option value="">Error al consultar</option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $cotizacion_id
     * @return \Illuminate\Http\Response
     */
    public function serviciopartidasprecios($cotizacion_id)
    {
        try {
            $filas_partidas = '';
            $partidas = DB::select('SELECT
                            TABLA.tipo,
                            TABLA.agente_id,
                            TABLA.agente_nombre,
                            TABLA.agente_preciounitario,
                            TABLA.ACTIVO_PARTIDAPROVEEDOR
                        FROM
                            (
                                SELECT
                                    1 tipo,
                                    servicioprecios.agente_id,
                                    servicioprecios.agente_nombre,
                                    servicioprecios.agente_preciounitario,
                                    servicioprecios.ACTIVO_PARTIDAPROVEEDOR 
                                FROM
                                    servicioprecios
                                WHERE
                                    servicioprecios.servicio_id = ' . $cotizacion_id . '
                                    AND servicioprecios.agente_id > 0
                                UNION
                                SELECT
                                    2 tipo,
                                    servicioprecios.agente_id,
                                    servicioprecios.agente_nombre,
                                    servicioprecios.agente_preciounitario,
                                    servicioprecios.ACTIVO_PARTIDAPROVEEDOR 
                                FROM
                                    servicioprecios
                                WHERE
                                    servicioprecios.servicio_id = ' . $cotizacion_id . '
                                    AND servicioprecios.agente_id = 0
                            ) AS TABLA
                        ORDER BY
                            TABLA.tipo ASC,
                            TABLA.agente_nombre ASC');



            // Formatear filas
            foreach ($partidas as $key => $value) {
                $style = '';
                $readonly = '';
                if ($value->ACTIVO_PARTIDAPROVEEDOR == 0) {
                    // Si la partida está desactivada, aplicar los estilos CSS
                    $style = ' style="text-decoration: line-through; background-color: #ccc;"';
                    // Si la partida está desactivada, hacer los campos de entrada de solo lectura
                    $readonly = ' readonly';
                }

                $filas_partidas .= '<tr' . $style . '>
                                        <td style="width: 70px;">' . ($key + 1) . '</td>';

                if (($value->tipo + 0) == 1) {
                    $filas_partidas .= '
                        <td style="width: 480px; text-align: left;">
                            <b class="text-secondary">' . $value->agente_nombre . '</b>
                            <input type="text" class="form-control" value="' . $value->agente_id . '~' . $value->agente_nombre . '" name="partida_alcance[]">
                        </td>
                        <th></th>
                        <td style="width: 180px;">
                            <input type="number" step="any" class="form-control" value="' . $value->agente_preciounitario . '" name="precio_alcance[]" required' . $readonly . '>
                        </td>
                            <td style="width: 120px; text-align: right;"><input type="hidden" name="ACTIVO_PARTIDAPROVEEDOR_ALCANCE[]" value="' . $value->ACTIVO_PARTIDAPROVEEDOR . '" class="d-none"></input></td>';
                } else {
                    $filas_partidas .= '
                        <td style="width: 480px; text-align: left;">
                            <b class="text-info">' . $value->agente_nombre . '</b>
                            <input type="text" class="form-control" value="' . $value->agente_nombre . '" name="partida_adicional[]">
                        </td>
                        <th></th>
                        <td style="width: 180px;">
                            <input type="number" step="any" class="form-control" value="' . $value->agente_preciounitario . '" name="precio_adicional[]" required' . $readonly . '>
                            
                        </td>
                        <td style="width: 120px; text-align: right;"><input type="hidden" name="ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[]" value="' . $value->ACTIVO_PARTIDAPROVEEDOR . '" class="d-none"></input></td>';
                }

                // Botones de desactivar/activar y eliminar
                $filas_partidas .= '<td style="width: 140px;" class="acciones">';
                // Botón de desactivar o activar la partida
                if ($value->ACTIVO_PARTIDAPROVEEDOR == 1) {
                    $filas_partidas .= '<button type="button" class="btn btn-info btn-circle boton_desactivar" data-toggle="tooltip" data-placement="top" title="Desactivar partida" data-partida-id="' . $value->agente_id . '"><i class="fa fa-lock"></i></button>';
                } else {
                    // Botón para activar la partida
                    $filas_partidas .= '<button type="button" class="btn btn-info btn-circle boton_activar" data-toggle="tooltip" data-placement="top" title="Activar partida" data-partida-id="' . $value->agente_id . '"><i class="fa fa-unlock"></i></button>';
                }
                $filas_partidas .= '</td>';

                // Celda para el botón de eliminar
                $filas_partidas .= '<td style="width: 70px;" class="acciones">';
                // Botón de eliminar
                $filas_partidas .= '<button type="button" class="btn btn-danger btn-circle boton_eliminar" data-partida-id="' . $value->agente_id . '"><i class="fa fa-trash"></i></button>';
                $filas_partidas .= '</td>';
            }





            // Última fila vacía para el diseño
            $filas_partidas .= '<tr><td colspan="4" style="width: 800px; height: 160px;">&nbsp;</td></tr>';

            // Respuesta
            $dato['total_partidas'] = count($partidas);
            $dato['filas_partidas'] = $filas_partidas;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            // En caso de error
            $dato['total_partidas'] = 0;
            $dato['filas_partidas'] = '<tr><td colspan="4" style="width: 800px; height: 20px;">Error al consultar partidas</td></tr>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $servicio_id
     * @return \Illuminate\Http\Response
     */
    public function mostrarpdf($servicio_id)
    {
        $documento = ServicioModel::findOrFail($servicio_id);
        return Storage::response($documento->servicio_SoportePDF);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
        try {
            // formatear campos fechas antes de guardar
            // $request['servicio_FechaCotizacion'] = Carbon::createFromFormat('d-m-Y', $request['servicio_FechaCotizacion'])->format('Y-m-d');
            // $request['servicio_VigenciaCotizacion'] = Carbon::createFromFormat('d-m-Y', $request['servicio_VigenciaCotizacion'])->format('Y-m-d');

            if ($request['servicio_Eliminado'] == 0) //valida eliminacion
            {

                if ($request['servicio_id'] == 0) //nuevo
                {
                    DB::statement("ALTER TABLE servicio AUTO_INCREMENT = 1;");
                    $servicio = ServicioModel::create($request->all());

                    // guardar partidas alcances
                    if ($request->partida_alcance) {
                        foreach ($request->partida_alcance as $key => $value) {
                            $valor = explode("~", $value);

                            $guardar_partidas = ServiciopreciosModel::create([
                                'servicio_id' => $servicio->id, 'agente_id' => $valor[0],
                                'agente_nombre' => $valor[1],
                                'ACTIVO_PARTIDAPROVEEDOR' => $request->ACTIVO_PARTIDAPROVEEDOR_ALCANCE[$key],
                                'agente_preciounitario' => $request->precio_alcance[$key]
                            ]);
                        }
                    }

                    // guardar partidas adicionales
                    if ($request->partida_adicional) {
                        foreach ($request->partida_adicional as $key => $value) {
                            $guardar_partidas = ServiciopreciosModel::create([
                                'servicio_id' => $servicio->id,
                                'agente_id' => 0,
                                'agente_nombre' => $value,
                                'ACTIVO_PARTIDAPROVEEDOR' => $request->ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[$key],
                                'agente_preciounitario' => $request->precio_adicional[$key]
                            ]);
                        }
                    }



                    // Si envia archivo
                    if ($request->file('serviciopdf')) {
                        $extension = $request->file('serviciopdf')->getClientOriginalExtension();
                        $archivo_pdf = $request->file('serviciopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/servicios', $servicio->id . '.' . $extension);

                        // $servicio->update($request->all());
                        $servicio->update([
                            'servicio_SoportePDF' => $archivo_pdf
                        ]);
                    }

                    // mensaje
                    $dato["msj"] = 'Información guardada correctamente';
                    $dato["servicio"] = $servicio;
                } else //editar
                {
                    $servicio = ServicioModel::findOrFail($request['servicio_id']);
                    $servicio->update($request->all());

                    // eliminar partidas precios
                    $eliminar_partidas = ServiciopreciosModel::where('servicio_id', $request["servicio_id"])->delete();

                    // guardar partidas alcances
                    if ($request->partida_alcance) {
                        foreach ($request->partida_alcance as $key => $value) {
                            $valor = explode("~", $value);

                            $guardar_partidas = ServiciopreciosModel::create([
                                'servicio_id' => $servicio->id,
                                'agente_id' => $valor[0],
                                'agente_nombre' => $valor[1],
                                'ACTIVO_PARTIDAPROVEEDOR' => $request->ACTIVO_PARTIDAPROVEEDOR_ALCANCE[$key],
                                'agente_preciounitario' => $request->precio_alcance[$key]

                            ]);
                        }
                    }

                    // guardar partidas adicionales
                    if ($request->partida_adicional) {
                        foreach ($request->partida_adicional as $key => $value) {
                            $guardar_partidas = ServiciopreciosModel::create([
                                'servicio_id' => $servicio->id,
                                'agente_id' => 0,
                                'agente_nombre' => $value,
                                'ACTIVO_PARTIDAPROVEEDOR' => $request->ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[$key],
                                'agente_preciounitario' => $request->precio_adicional[$key]
                            ]);
                        }
                    }


                    // print_r($request->all());
                    // exit;


                    // Si envia archivo
                    if ($request->file('serviciopdf')) {
                        $extension = $request->file('serviciopdf')->getClientOriginalExtension();
                        $archivo_pdf = $request->file('serviciopdf')->storeAs('proveedores/' . $request['proveedor_id'] . '/servicios', $servicio->id . '.' . $extension);

                        $servicio->update([
                            'servicio_SoportePDF' => $archivo_pdf
                        ]);
                    }

                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                    $dato["servicio"] = $servicio;
                }
            } else //eliminar
            {
                $servicio = ServicioModel::findOrFail($request['servicio_id']);
                $servicio->update($request->all());

                // mensaje
                $dato["msj"] = 'Información eliminada correctamente';
                $dato["servicio"] = $servicio;
            }

            // respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato["servicio"] = 0;
            return response()->json($dato);
        }
    }
}
