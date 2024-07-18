<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametrovibracionModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametrovibracionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrovibracionvista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        return view('catalogos.recsensorial.parametrovibracion', compact('recsensorial', 'recsensorialareas', 'recsensorial_id'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrovibraciontabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $numero_registro = 1;
            // $tabla = recsensorialareaModel::all();
            $tabla = parametrovibracionModel::with(['recsensorialarea', 'recsensorialcategoria'])
                    ->where('recsensorial_id', $recsensorial_id)
                    ->orderBy('id', 'asc')
                    ->get();

            // agrupar pruebas por area
            foreach ($tabla  as $key => $value) 
            {
                $value->numero_registro = $numero_registro;
                $numero_registro += 1;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
                {
                    $value->accion_activa = 1;
                    $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                }
                else
                {
                    $value->accion_activa = 0;
                    $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
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
            if (($request->opcion+0) == 1) // PUNTOS DEL RECONCIMIENTO
            {
                if ($request['registro_id']==0) //nuevo
                {
                    // guardar
                    $parametro = parametrovibracionModel::create($request->all());

                    // mensaje
                    $dato["msj"] = 'Información guardada correctamente';
                }
                else //editar
                {
                    // modificar
                    $parametro = parametrovibracionModel::findOrFail($request['registro_id']);
                    $parametro->update($request->all());

                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }

                // respuesta
                $dato['parametro'] = $parametro;
            }


            if (($request->opcion+0) == 2) // FOTOS EVIDENCIA
            {
                // dd($request->all());

                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialevidencias AUTO_INCREMENT = 1;');


                if ($request->file('inputevidenciafoto'))
                {
                    $tipo = 'Foto';
                    if (($request->recsensorialevidencias_tipo+0) == 2) // Tipo de archivo enviado
                    {
                        $tipo = 'Plano';
                    }

                    $request['cat_prueba_id'] = $request->parametro_id;

                    if (!$request->recsensorialarea_id)
                    {
                        $request['recsensorialarea_id'] = 0; // Si es plano debe poner el Area_id = 0
                    }

                    $foto = recsensorialevidenciasModel::create($request->all());

                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->foto_base64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // // Ruta destino archivo
                    $destinoPath = 'recsensorial/'.$request->recsensorial_id.'/evidencias_campo/'.$request->parametro_nombre.'/'.$tipo.'_'.$foto->id.'.jpg';
                    
                    // GUardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    // Actualizar ruta foto
                    $foto->update([
                        'recsensorialevidencias_foto' => $destinoPath
                    ]);

                    // Mensaje
                    $dato["msj"] = $tipo.' guardado correctamente';
                }
                else
                {
                    // Mensaje
                    $dato["msj"] = 'No se realizó ninguna acción';
                }
            }


            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $registro_id
     * @return \Illuminate\Http\Response
     */
    public function parametrovibracioneliminar($registro_id)
    {
        try
        {
            $parametro = parametrovibracionModel::where('id', $registro_id)->delete();

            // respuesta
            $dato['eliminado'] = $parametro;
            $dato["msj"] = 'Información eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
