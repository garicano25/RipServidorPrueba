<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\parametrocalidadaireModel;
use App\modelos\recsensorial\parametrocalidadairecaracteristicasModel;
use App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel;

use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialevidenciasModel;
use DB;

// plugins
use Illuminate\Support\Facades\Storage;
use Image;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class parametrocalidadaireController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrocalidadairevista($recsensorial_id)
    {
        // Reconocimiento
        $recsensorial = recsensorialModel::findOrFail($recsensorial_id);
        $recsensorialareas = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->orderBy('recsensorialarea_nombre', 'asc')->get();

        // consultar CATALOGO
        $airecaracteristicas = catparametrocalidadairecaracteristicaModel::where('catparametrocalidadairecaracteristica_activo', 1)->get();
        
        return view('catalogos.recsensorial.parametrocalidadaire', compact('recsensorial', 'recsensorialareas', 'recsensorial_id', 'airecaracteristicas'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function parametrocalidadairetabla($recsensorial_id)
    {
        try
        {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            // $tabla = recsensorialareaModel::all();
            $tabla = parametrocalidadaireModel::with(['recsensorialarea', 'caracteristicas'])
                    ->where('recsensorial_id', $recsensorial_id)
                    ->orderBy('id', 'asc')
                    ->get();

            // Formatear filas
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                
                // obtener caracteristicas
                $listacaracteristicas = $value->caracteristicas->pluck('catalogoairecaracteristicas.catparametrocalidadairecaracteristica_caracteristica');
                
                // formatear caracteristicas
                $lista = "";
                foreach ($listacaracteristicas as $key => $caracteristica) 
                {
                    $lista .= "<li>".$caracteristica."</li>";
                }
                $value->airecaracteristicas = $lista;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
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
            if ($request['registro_id']==0) //nuevo
            {
                // guardar
                $parametro = parametrocalidadaireModel::create($request->all());
                $parametro->airecaracteristicas()->sync($request->airecaracteristica);

                // mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }
            else //editar
            {
                // modificar
                $parametro = parametrocalidadaireModel::findOrFail($request['registro_id']);
                $parametro->update($request->all());
                $parametro->airecaracteristicas()->sync($request->airecaracteristica);

                // mensaje
                $dato["msj"] = 'Información modificada correctamente';
            }

            // respuesta
            $dato['parametro'] = $parametro;
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
    public function parametrocalidadaireeliminar($registro_id)
    {
        try
        {
            $parametro = parametrocalidadaireModel::where('id', $registro_id)->delete();
            $caracteristicas = parametrocalidadairecaracteristicasModel::where('parametrocalidadaire_id', $registro_id)->delete();

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
