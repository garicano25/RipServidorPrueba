<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialcategoriaModel;
use DB;

class recsensorialcategoriaController extends Controller
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





    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialcategoriatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $tabla = recsensorialcategoriaModel::with(['catdepartamento', 'catmovilfijo'])
                ->where('recsensorial_id', $recsensorial_id)
                ->orderBy('catdepartamento_id', 'ASC')
                ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                ->get();

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Informacion consultada correctamente';
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
     * @param  int  $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialcategoriaeliminar($categoria_id)
    {
        try {
            $categoria = recsensorialcategoriaModel::where('id', $categoria_id)->delete();

            // respuesta
            $dato['eliminado'] = $categoria;
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
            if ($request['categoria_id'] == 0) //nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialcategoria AUTO_INCREMENT=1');

                // guardar
                $categoria = recsensorialcategoriaModel::create($request->all());

                // mensaje
                $dato["msj"] = 'Informacion guardada correctamente';
            } else //editar
            {
                // modificar
                $categoria = recsensorialcategoriaModel::findOrFail($request['categoria_id']);
                $categoria->update($request->all());

                // mensaje
                $dato["msj"] = 'Informacion modificada correctamente';
            }

            // respuesta
            $dato['categoria'] = $categoria;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
