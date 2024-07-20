<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\parametroruidosonometriaModel;
use App\modelos\recsensorial\parametroruidosonometriacategoriasModel;
use App\modelos\recsensorial\parametroruidodosimetriaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use App\modelos\recsensorial\parametroruidoequiposModel;
use App\modelos\catalogos\ProveedorModel;
use App\modelos\catalogos\EquipoModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class parametroruidoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidovista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();
        $proveedores = ProveedorModel::where('proveedor_Eliminado', 0)->orderBy('proveedor_RazonSocial', 'ASC')->get();
        $equipos = EquipoModel::where('equipo_Eliminado', 0)
            // ->whereDate('equipo_VigenciaCalibracion', '>', date('Y-m-d'))
            ->orderBy('equipo_Descripcion', 'ASC')
            ->orderBy('equipo_Marca', 'ASC')
            ->orderBy('equipo_Modelo', 'ASC')
            ->orderBy('equipo_VigenciaCalibracion', 'ASC')
            ->get();

        return view('catalogos.recsensorial.parametroruido', compact('recsensorial', 'recsensorialareas', 'recsensorial_id', 'proveedores', 'equipos'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidosonometriatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            $tabla = DB::select('SELECT
                                    parametroruidosonometria.id,
                                    parametroruidosonometria.recsensorial_id,
                                    parametroruidosonometria.recsensorialarea_id,
                                    IFNULL(recsensorialarea.recsensorialarea_nombre, "Sin dato") AS recsensorialarea_nombre,
                                    IFNULL((
                                        SELECT
                                            CONCAT("● ", REPLACE(GROUP_CONCAT(recsensorialcategoria.recsensorialcategoria_nombrecategoria), ",", "<br>● "))
                                        FROM
                                            parametroruidosonometriacategorias
                                            LEFT JOIN recsensorialcategoria ON parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                        WHERE
                                            parametroruidosonometriacategorias.recsensorialarea_id = parametroruidosonometria.recsensorialarea_id
                                    ), "-") AS categorias,
                                    parametroruidosonometria.parametroruidosonometria_puntos,
                                    parametroruidosonometria.parametroruidosonometria_medmax,
                                    parametroruidosonometria.parametroruidosonometria_medmin,
                                    parametroruidosonometria.parametroruidosonometria_med1,
                                    parametroruidosonometria.parametroruidosonometria_med2,
                                    parametroruidosonometria.parametroruidosonometria_med3,
                                    parametroruidosonometria.parametroruidosonometria_med4,
                                    parametroruidosonometria.parametroruidosonometria_med5,
                                    parametroruidosonometria.parametroruidosonometria_med6,
                                    parametroruidosonometria.parametroruidosonometria_med7,
                                    parametroruidosonometria.parametroruidosonometria_med8,
                                    parametroruidosonometria.parametroruidosonometria_med9,
                                    parametroruidosonometria.parametroruidosonometria_med10
                                FROM
                                    parametroruidosonometria
                                    LEFT JOIN recsensorialarea ON parametroruidosonometria.recsensorialarea_id = recsensorialarea.id
                                WHERE
                                    parametroruidosonometria.recsensorial_id = ' . $recsensorial_id . ' 
                                ORDER BY
                                    parametroruidosonometria.id ASC');


            $mediciones = array();

            foreach ($tabla as $key => $value) {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;


                if ($value->parametroruidosonometria_medmax) {
                    if (($value->parametroruidosonometria_medmax + 0) > 80 || ($value->parametroruidosonometria_medmin + 0) > 80) {
                        if ((($value->parametroruidosonometria_medmax + 0) - ($value->parametroruidosonometria_medmin + 0)) > 5) {
                            $value->resultado = 'Inestable<br>± ' . (($value->parametroruidosonometria_medmax + 0) - ($value->parametroruidosonometria_medmin + 0)) . ' dB';
                            // dd($value->parametroruidosonometria_medmax.' - '.$value->parametroruidosonometria_medmin.' - '.(($value->parametroruidosonometria_medmax+0) - ($value->parametroruidosonometria_medmin+0)).' - '.$value->resultado);
                        } else {
                            $value->resultado = 'Estable<br>± ' . (($value->parametroruidosonometria_medmax + 0) - ($value->parametroruidosonometria_medmin + 0)) . ' dB';
                            // dd($value->parametroruidosonometria_medmax.' - '.$value->parametroruidosonometria_medmin.' - '.(($value->parametroruidosonometria_medmax+0) - ($value->parametroruidosonometria_medmin+0)).' - '.$value->resultado);
                        }
                    } else {
                        $value->resultado = 'No se evalua<br>< 80 dB';
                        // dd($value->parametroruidosonometria_medmax.' - '.$value->parametroruidosonometria_medmin.' - '.(($value->parametroruidosonometria_medmax+0) - ($value->parametroruidosonometria_medmin+0)).' - '.$value->resultado);
                    }


                    $value->nsa = 'Max: ' . $value->parametroruidosonometria_medmax . 'dB<br>Min: ' . $value->parametroruidosonometria_medmin . 'dB';
                } else if ($value->parametroruidosonometria_med1) {
                    if (($value->parametroruidosonometria_med1 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med1 + 0);
                    }
                    if (($value->parametroruidosonometria_med2 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med2 + 0);
                    }
                    if (($value->parametroruidosonometria_med3 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med3 + 0);
                    }
                    if (($value->parametroruidosonometria_med4 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med4 + 0);
                    }
                    if (($value->parametroruidosonometria_med5 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med5 + 0);
                    }
                    if (($value->parametroruidosonometria_med6 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med6 + 0);
                    }
                    if (($value->parametroruidosonometria_med7 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med7 + 0);
                    }
                    if (($value->parametroruidosonometria_med8 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med8 + 0);
                    }
                    if (($value->parametroruidosonometria_med9 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med9 + 0);
                    }
                    if (($value->parametroruidosonometria_med10 + 0) > 0) {
                        $mediciones[] = ($value->parametroruidosonometria_med10 + 0);
                    }


                    if (max($mediciones) > 80 || min($mediciones) > 80) {
                        if ((max($mediciones) - min($mediciones)) > 5) {
                            $value->resultado = 'Inestable<br>± ' . (max($mediciones) - min($mediciones)) . ' dB';
                            // dd(min($mediciones).' - '.max($mediciones).' - '.(max($mediciones) - min($mediciones)).' - '.$value->resultado);
                        } else {
                            $value->resultado = 'Estable<br>± ' . (max($mediciones) - min($mediciones)) . ' dB';
                            // dd(min($mediciones).' - '.max($mediciones).' - '.(max($mediciones) - min($mediciones)).' - '.$value->resultado);
                        }
                    } else {
                        $value->resultado = 'No se evalua<br>< 80 dB';
                        // dd(min($mediciones).' - '.max($mediciones).' - '.(max($mediciones) - min($mediciones)).' - '.$value->resultado);
                    }


                    $value->nsa = 'Med1: ' . $value->parametroruidosonometria_med1 . 'dB<br>';
                    $value->nsa .= 'Med2: ' . $value->parametroruidosonometria_med2 . 'dB<br>';
                    $value->nsa .= 'Med3: ' . $value->parametroruidosonometria_med3 . 'dB<br>';
                    $value->nsa .= 'Med4: ' . $value->parametroruidosonometria_med4 . 'dB<br>';
                    $value->nsa .= 'Med5: ' . $value->parametroruidosonometria_med5 . 'dB<br>';
                    $value->nsa .= 'Med6: ' . $value->parametroruidosonometria_med6 . 'dB<br>';
                    $value->nsa .= 'Med7: ' . $value->parametroruidosonometria_med7 . 'dB<br>';
                    $value->nsa .= 'Med8: ' . $value->parametroruidosonometria_med8 . 'dB<br>';
                    $value->nsa .= 'Med9: ' . $value->parametroruidosonometria_med9 . 'dB<br>';
                    $value->nsa .= 'Med10: ' . $value->parametroruidosonometria_med10 . 'dB';
                } else {
                    $value->nsa = 'N/A';
                    $value->resultado = 'N/A';
                }


                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidodosimetriatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            $tabla = parametroruidodosimetriaModel::with(['recsensorialcategoria'])
                ->where('recsensorial_id', $recsensorial_id)
                ->orderBy('id', 'asc')
                ->get();

            // agrupar pruebas por area
            foreach ($tabla  as $key => $value) {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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

    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorialarea_id
     * @return \Illuminate\Http\Response
     */
    public function recsensoriallistacategoriasxarea($recsensorialarea_id)
    {
        try {
            $opciones = '<option value=""></option>';
            $listacategorias = DB::select('SELECT
                                                recsensorialareacategorias.recsensorialarea_id,
                                                recsensorialareacategorias.recsensorialcategoria_id,
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria  AS recsensorialcategoria_nombrecategoria,
                                                IFNULL((
                                                    SELECT
                                                        IF(parametroruidosonometriacategorias.recsensorialcategoria_id, "checked", "") 
                                                    FROM
                                                        parametroruidosonometriacategorias 
                                                    WHERE
                                                        parametroruidosonometriacategorias.recsensorialarea_id = recsensorialareacategorias.recsensorialarea_id AND parametroruidosonometriacategorias.recsensorialcategoria_id = recsensorialareacategorias.recsensorialcategoria_id 
                                                    LIMIT 1
                                                ), "") AS checked
                                            FROM
                                                recsensorialareacategorias
                                                LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id
                                            WHERE
                                                recsensorialareacategorias.recsensorialarea_id = ' . $recsensorialarea_id . '
                                            ORDER BY
                                                recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');

            // respuesta
            $dato['categorias'] = $listacategorias;
            $dato["msj"] = 'Información consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['categorias'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $registro_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidosonometriaeliminar($registro_id)
    {
        try {

            $parametro = parametroruidosonometriaModel::findOrFail($registro_id);
            $recsensorialarea_id = $parametro->recsensorialarea_id;
            $parametro = parametroruidosonometriaModel::where('id', $registro_id)->delete();

            // eliminar categorias
            $eliminar = parametroruidosonometriacategoriasModel::where('recsensorialarea_id', $recsensorialarea_id)->delete();

            // respuesta
            $dato['eliminado'] = $parametro;
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $registro_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidodosimetriaeliminar($registro_id)
    {
        try {
            $parametro = parametroruidodosimetriaModel::where('id', $registro_id)->delete();

            // respuesta
            $dato['eliminado'] = $parametro;
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
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
    public function parametroruidoequipotabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            $tabla = DB::select('SELECT
                                    parametroruidoequipos.recsensorial_id,
                                    parametroruidoequipos.id,
                                    parametroruidoequipos.proveedor_id,
                                    proveedor.proveedor_RazonSocial,
                                    parametroruidoequipos.equipo_id,
                                    equipo.equipo_Descripcion,
                                    equipo.equipo_Marca,
                                    equipo.equipo_Modelo,
                                    equipo.equipo_Serie,
                                    equipo.equipo_VigenciaCalibracion
                                    -- equipo.equipo_CertificadoPDF 
                                FROM
                                    parametroruidoequipos
                                    LEFT JOIN proveedor ON parametroruidoequipos.proveedor_id = proveedor.id
                                    LEFT JOIN equipo ON parametroruidoequipos.equipo_id = equipo.id
                                WHERE
                                    parametroruidoequipos.recsensorial_id = ' . $recsensorial_id . ' 
                                ORDER BY
                                    proveedor.proveedor_RazonSocial ASC,
                                    equipo.equipo_Descripcion ASC,
                                    equipo.equipo_Marca ASC,
                                    equipo.equipo_Modelo ASC,
                                    equipo.equipo_Serie ASC');


            $numero_registro = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;





                // Boton ELIMINAR
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar_equipo"><i class="fa fa-trash"></i></button>';
                } else {
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


    /**
     * Display the specified resource.
     *
     * @param  int  $equipo_id
     * @return \Illuminate\Http\Response
     */
    public function parametroruidoequipoeliminar($equipo_id)
    {
        try {
            parametroruidoequiposModel::where('id', $equipo_id)->delete();

            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (($request->opcion + 0) == 1) // PUNTOS DEL RECONCIMIENTO
            {
                if ($request['sonometria']) {
                    if ($request['registro_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE parametroruidosonometria AUTO_INCREMENT=1');

                        // guardar
                        $parametro = parametroruidosonometriaModel::create($request->all());

                        // categorias
                        foreach ($request->categoria as $key => $value) {
                            $categoria = parametroruidosonometriacategoriasModel::create([
                                'recsensorialarea_id' => $request['recsensorialarea_id'], 'recsensorialcategoria_id' => $value
                            ]);
                        }

                        // mensaje
                        $dato["msj"] = 'Información guardada correctamente';
                    } else //editar
                    {
                        if (!$request->parametroruidosonometria_med1) {
                            $request['parametroruidosonometria_med1'] = NULL;
                            $request['parametroruidosonometria_med2'] = NULL;
                            $request['parametroruidosonometria_med3'] = NULL;
                            $request['parametroruidosonometria_med4'] = NULL;
                            $request['parametroruidosonometria_med5'] = NULL;
                            $request['parametroruidosonometria_med6'] = NULL;
                            $request['parametroruidosonometria_med7'] = NULL;
                            $request['parametroruidosonometria_med8'] = NULL;
                            $request['parametroruidosonometria_med9'] = NULL;
                            $request['parametroruidosonometria_med10'] = NULL;
                        }


                        if (!$request->parametroruidosonometria_medmax) {
                            $request['parametroruidosonometria_medmax'] = NULL;
                            $request['parametroruidosonometria_medmin'] = NULL;
                        }

                        // modificar
                        $parametro = parametroruidosonometriaModel::findOrFail($request['registro_id']);
                        $parametro->update($request->all());

                        // eliminar categorias
                        $eliminar = parametroruidosonometriacategoriasModel::where('recsensorialarea_id', $request['recsensorialarea_id'])->delete();

                        // categorias
                        foreach ($request->categoria as $key => $value) {
                            $categoria = parametroruidosonometriacategoriasModel::create([
                                'recsensorialarea_id' => $request['recsensorialarea_id'], 'recsensorialcategoria_id' => $value
                            ]);
                        }

                        // mensaje
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                } else //Dosimetría
                {
                    if ($request['registro_id'] == 0) //nuevo
                    {
                        // AUTO_INCREMENT
                        DB::statement('ALTER TABLE parametroruidodosimetria AUTO_INCREMENT=1');

                        // guardar
                        $parametro = parametroruidodosimetriaModel::create($request->all());

                        // mensaje
                        $dato["msj"] = 'Información guardada correctamente';
                    } else //editar
                    {
                        // modificar
                        $parametro = parametroruidodosimetriaModel::findOrFail($request['registro_id']);
                        $parametro->update($request->all());

                        // mensaje
                        $dato["msj"] = 'Información modificada correctamente';
                    }
                }

                // respuesta
                $dato['parametro'] = $parametro;
            }


            if (($request->opcion + 0) == 2) // EQUIPOS DE MEDICIÓN
            {
                // dd($request->all());

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE parametroruidoequipos AUTO_INCREMENT=1');

                // guardar
                $equiporuido = parametroruidoequiposModel::create($request->all());

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
