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
use App\modelos\proyecto\proyectoordenservicioModel;
use App\modelos\proyecto\proyectoordenservicioadicionalModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class proyectoordenservicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordenserviciotabla($proyecto_id)
    {
        try {
            // $ordenservicios = proyectoordenservicioModel::all();
            // $ordenservicios = proyectoordenservicioModel::where('proyecto_id', $proyecto_id)
            //                                             ->where('proyectoordenservicio_eliminado', 0)
            //                                             ->orderBy('id', 'desc')
            //                                             ->get();


            $ordenservicios = DB::select('SELECT
                                                TABLA.id,
                                                TABLA.proyecto_id,
                                                (
                                                    SELECT
                                                        -- proyectoordenservicio.proyecto_id,
                                                        COUNT(proyectoordenservicio.id)
                                                    FROM
                                                        proyectoordenservicio
                                                    WHERE
                                                        proyectoordenservicio.proyecto_id = ' . $proyecto_id . ' 
                                                        AND proyectoordenservicio.proyectoordenservicio_eliminado = 0
                                                    LIMIT 1
                                                ) AS ordenesservicios_total,
                                                TABLA.proyectoordenservicio_id,
                                                TABLA.tipo_documento,
                                                TABLA.proyectoordenservicio_oficio,
                                                TABLA.proyectoordenservicio_numero,
                                                TABLA.proyectoordenservicio_cotizacion,
                                                TABLA.proyectoordenservicio_total,
                                                TABLA.proyectoordenservicio_contrato,
                                                TABLA.proyectoordenservicio_raf,
                                                TABLA.proyectoordenservicio_pedido,
                                                TABLA.proyectoordenservicio_observacion,
                                                TABLA.proyectoordenservicio_pdf,
                                                TABLA.proyectoordenservicio_validado,
                                                TABLA.proyectoordenservicio_fechavalidacion,
                                                TABLA.proyectoordenservicio_personavalidacion,
                                                TABLA.proyectoordenservicio_eliminado
                                            FROM
                                                (
                                                    (
                                                        SELECT
                                                            proyectoordenservicio.id,
                                                            proyectoordenservicio.proyecto_id,
                                                            proyectoordenservicio.id AS proyectoordenservicio_id,
                                                            1 AS tipo_documento,
                                                            proyectoordenservicio.proyectoordenservicio_oficio,
                                                            proyectoordenservicio.proyectoordenservicio_numero,
                                                            proyectoordenservicio.proyectoordenservicio_cotizacion,
                                                            proyectoordenservicio.proyectoordenservicio_total,
                                                            proyectoordenservicio.proyectoordenservicio_contrato,
                                                            proyectoordenservicio.proyectoordenservicio_raf,
                                                            proyectoordenservicio.proyectoordenservicio_pedido,
                                                            proyectoordenservicio.proyectoordenservicio_observacion,
                                                            proyectoordenservicio.proyectoordenservicio_pdf,
                                                            proyectoordenservicio.proyectoordenservicio_validado,
                                                            proyectoordenservicio.proyectoordenservicio_fechavalidacion,
                                                            proyectoordenservicio.proyectoordenservicio_personavalidacion,
                                                            proyectoordenservicio.proyectoordenservicio_eliminado 
                                                        FROM
                                                            proyectoordenservicio
                                                        WHERE
                                                            proyectoordenservicio.proyecto_id = ' . $proyecto_id . ' 
                                                            AND proyectoordenservicio.proyectoordenservicio_eliminado = 0
                                                    )
                                                    UNION ALL
                                                    (
                                                        SELECT
                                                            proyectoordenservicioadicional.id,
                                                            proyectoordenservicioadicional.proyecto_id,
                                                            proyectoordenservicioadicional.proyectoordenservicio_id,
                                                            2 AS tipo_documento,
                                                            proyectoordenservicioadicional.proyectoordenservicioadicional_nombre,
                                                            NULL AS proyectoordenservicio_numero,
                                                            NULL AS proyectoordenservicio_cotizacion,
                                                            NULL AS proyectoordenservicio_total,
                                                            NULL AS proyectoordenservicio_contrato,
                                                            NULL AS proyectoordenservicio_raf,
                                                            NULL AS proyectoordenservicio_pedido,
                                                            NULL AS proyectoordenservicio_observacion,
                                                            proyectoordenservicioadicional.proyectoordenservicioadicional_pdf, 
                                                            NULL AS proyectoordenservicio_validado,
                                                            NULL AS proyectoordenservicio_fechavalidacion,
                                                            NULL AS proyectoordenservicio_personavalidacion,
                                                            NULL AS proyectoordenservicio_eliminado
                                                        FROM
                                                            proyectoordenservicioadicional
                                                        WHERE
                                                            proyectoordenservicioadicional.proyecto_id = ' . $proyecto_id . ' 
                                                            AND proyectoordenservicioadicional.proyectoordenservicioadicional_eliminado = 0
                                                    )
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.proyectoordenservicio_id ASC,
                                                TABLA.tipo_documento ASC,
                                                TABLA.id ASC');


            $numero_registro = 1;
            $proyectoordenservicio_id = 0;
            $ordenservicios_opciones = '<option value="">&nbsp;</option>';
            foreach ($ordenservicios as $key => $value) {
                if ($proyectoordenservicio_id != $value->proyectoordenservicio_id) {
                    $value->numero_registro = $numero_registro;
                    $numero_registro += 1;


                    $ordenservicios_opciones .= '<option value="' . $value->id . '">Orden de servicio.- ' . $value->proyectoordenservicio_numero . '</option>';


                    $proyectoordenservicio_id = ($value->proyectoordenservicio_id + 0);
                } else {
                    $value->numero_registro = "";
                }


                if (($value->tipo_documento + 0) == 1) //OS
                {
                    // formatear fecha
                    // if ($value["proyectoordenservicio_fechavalidacion"])
                    // {
                    //     $value["proyectoordenservicio_fechavalidacion"] = Carbon::createFromFormat('Y-m-d', $value->proyectoordenservicio_fechavalidacion)->format('d-m-Y');
                    // }

                    $value->total = "$ " . number_format($value->proyectoordenservicio_total, 2);

                    // icono verificacion
                    if ($value->proyectoordenservicio_validado == 1) {
                        $value->valida_nombre_fecha = $value->proyectoordenservicio_personavalidacion . '<br>' . $value->proyectoordenservicio_fechavalidacion;
                        $value->verificacion = '<i class="fa fa-check text-success"></i>';
                    } else {
                        $value->valida_nombre_fecha = '-';
                        $value->verificacion = '<i class="fa fa-ban text-danger"></i>';
                    }
                }


                // boton eliminar ultimo resgistro
                if (($value->tipo_documento + 0) == 1 && ($value->ordenesservicios_total) == 1) {
                    $value->total_ordenservicios = 0;

                    $value->boton_eliminar = '<button type="button" class="btn btn-default btn-circle"><i class="fa fa-ban"></i></button>';
                } else {
                    $value->total_ordenservicios = 2;

                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }


                $value->opcion_eliminar = 0;
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto'])) {
                    $value->opcion_eliminar = 1;
                }
            }

            // respuesta
            $dato['ordenservicios_opciones'] = $ordenservicios_opciones;
            $dato['data'] = $ordenservicios;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $ordenservicio_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordenserviciopdf($ordenservicio_id)
    {
        $ordenservicio = proyectoordenservicioModel::findOrFail($ordenservicio_id);
        // return Storage::download($ordenservicio->proyectoordenservicio_pdf);
        return Storage::response($ordenservicio->proyectoordenservicio_pdf);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $ordenservicioadicional_id
     * @return \Illuminate\Http\Response
     */
    public function proyectoordenservicioadicionalpdf($ordenservicioadicional_id)
    {
        $ordenservicioadicional = proyectoordenservicioadicionalModel::findOrFail($ordenservicioadicional_id);
        // return Storage::download($ordenservicioadicional->proyectoordenservicioadicional_pdf);
        return Storage::response($ordenservicioadicional->proyectoordenservicioadicional_pdf);
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
            if (($request->opcion + 0) == 1) {
              
                //===============================================================0


                // checkbox validacion
                if ($request["proyectoordenservicio_validado"] != null) {
                    $request["proyectoordenservicio_validado"] = 1;

                    // formatear fecha
                    // $request['proyectoordenservicio_fechavalidacion'] = Carbon::createFromFormat('d-m-Y', $request['proyectoordenservicio_fechavalidacion'])->format('Y-m-d');
                } else {
                    $request["proyectoordenservicio_validado"] = 0;
                    $request["proyectoordenservicio_fechavalidacion"] = NULL;
                    $request["proyectoordenservicio_personavalidacion"] = NULL;
                }

                // GUARDAR
                if ($request['ordenservicio_id'] == 0) //nuevo
                {
                    // campo proyectoordenservicio_eliminado
                    $request["proyectoordenservicio_eliminado"] = 0;

                    // guardar
                    DB::statement('ALTER TABLE proyectoordenservicio AUTO_INCREMENT=1');
                    $ordenservicio = proyectoordenservicioModel::create($request->all());

                    // mensaje
                    $dato["msj"] = 'Informacion guardada correctamente';
                } else //editar
                {
                    // modificar
                    $ordenservicio = proyectoordenservicioModel::findOrFail($request['ordenservicio_id']);
                    $ordenservicio->update($request->all());

                    // mensaje
                    $dato["msj"] = 'Informacion modificada correctamente';
                }

                // si envia archivo
                if ($request->file('orderserviciopdf')) {
                    $extension = $request->file('orderserviciopdf')->getClientOriginalExtension();
                    $request['proyectoordenservicio_pdf'] = $request->file('orderserviciopdf')->storeAs('proyecto/' . $request['proyecto_id'] . '/ordenservicio', 'OS_' . $ordenservicio->id . '.' . $extension);
                    $ordenservicio->update($request->all());
                }

                // Mensaje
                $dato['ordenservicio'] = $ordenservicio;
            }


            if (($request->opcion + 0) == 2) {
                // guardar
                $request['proyectoordenservicioadicional_eliminado'] = 0;
                DB::statement('ALTER TABLE proyectoordenservicioadicional AUTO_INCREMENT=1');
                $ordenservicioadicional = proyectoordenservicioadicionalModel::create($request->all());


                // si envia archivo
                if ($request->file('proyectoordenservicioadicionalpdf')) {
                    $acentos = array(
                        'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
                        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
                        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
                        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
                        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
                    );


                    //Quitar acentos
                    $archivo_nombre = strtr($request->proyectoordenservicioadicional_nombre, $acentos);


                    $extension = $request->file('proyectoordenservicioadicionalpdf')->getClientOriginalExtension();
                    $proyectoordenservicioadicional_pdf = $request->file('proyectoordenservicioadicionalpdf')->storeAs('proyecto/' . $request['proyecto_id'] . '/ordenservicioadicional', str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $archivo_nombre) . '.' . $extension);


                    // Modificar
                    $ordenservicioadicional->update([
                        'proyectoordenservicioadicional_pdf' => $proyectoordenservicioadicional_pdf
                    ]);
                }


                // mensaje
                $dato['ordenservicio'] = proyectoordenservicioModel::findOrFail($request->proyectoordenservicio_id);
                $dato["msj"] = 'Informacion guardada correctamente';
            }


            // Respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['ordenservicio'] = 0;
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $ordenservicio_id
     * @param  int  $tipo_documento
     * @return \Illuminate\Http\Response
     */
    public function proyectoordenservicioeliminar($ordenservicio_id, $tipo_documento)
    {
        try {
            if (($tipo_documento) == 1) {
                //Orden servicio
                $ordenservicio = proyectoordenservicioModel::findOrFail($ordenservicio_id);
                $ordenservicio->update(['proyectoordenservicio_eliminado' => 1]);


                //Documentos adicionales
                $ordenservicioadicional = proyectoordenservicioadicionalModel::where('proyectoordenservicio_id', $ordenservicio_id);
                $ordenservicioadicional->update(['proyectoordenservicioadicional_eliminado' => 1]);
            } else {
                //Documentos adicionales
                $ordenservicioadicional = proyectoordenservicioadicionalModel::findOrFail($ordenservicio_id);
                $ordenservicioadicional->update(['proyectoordenservicioadicional_eliminado' => 1]);
            }


            // respuesta
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
