<?php

namespace App\Http\Controllers\ERGO;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use Carbon\Carbon;
use DateTime;

use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;


use App\modelos\reconocimientoergo\recoergocategoriasModel;



class categoriasergoController extends Controller
{





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */





    public function Tablarecocategoriasergo(Request $request)
    {
        try {
            $ergo = $request->get('ergoid');

            $tabla = recoergocategoriasModel::where('RECO_ID', $ergo)->get();


            foreach ($tabla as $value) {
                if ($value->ACTIVO == 0) {
                    $value->BTN_EDITAR = '<button type="button" class="btn btn-primary btn-custom rounded-pill EDITAR" ><i class="bi bi-eye"></i></button>';
                    $value->BTN_VISUALIZAR = '<button type="button" class="btn btn-primary btn-custom rounded-pill VISUALIZAR"><i class="bi bi-eye"></i></button>';
                } else {

                    $value->BTN_EDITAR = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                   
                }
            }

            return response()->json([
                'data' => $tabla,
                'msj' => 'Información consultada correctamente'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msj' => 'Error ' . $e->getMessage(),
                'data' => 0
            ]);
        }
    }


    public function obtenerPTCategoria(Request $request)
    {
        $reco_id = $request->reco_id;

        $ultimo = DB::table('recoergocategorias')
            ->where('RECO_ID', $reco_id)
            ->orderBy('ID_CATEGORIA_ERGO', 'desc')
            ->first();

        if ($ultimo && $ultimo->PT_CATEGORIA) {

            preg_match('/\d+/', $ultimo->PT_CATEGORIA, $matches);
            $numero = isset($matches[0]) ? intval($matches[0]) + 1 : 1;
        } else {
            $numero = 1;
        }

        $pt = 'PT' . $numero;

        return response()->json([
            'pt' => $pt
        ]);
    }



    public function store(Request $request)
    {
        try {
            switch (intval($request->api)) {
                case 1:
                    if ($request->ID_CATEGORIA_ERGO == 0) {
                        DB::statement('ALTER TABLE recoergocategorias AUTO_INCREMENT=1;');
                        $categorias = recoergocategoriasModel::create($request->all());
                    } else {

                        if (isset($request->ELIMINAR)) {
                            if ($request->ELIMINAR == 1) {
                                $categorias = recoergocategoriasModel::where('ID_CATEGORIA_ERGO', $request['ID_CATEGORIA_ERGO'])->update(['ACTIVO' => 0]);
                                $response['code'] = 1;
                                $response['categorias'] = 'Desactivada';
                            } else {
                                $categorias = recoergocategoriasModel::where('ID_CATEGORIA_ERGO', $request['ID_CATEGORIA_ERGO'])->update(['ACTIVO' => 1]);
                                $response['code'] = 1;
                                $response['categorias'] = 'Activada';
                            }
                        } else {
                            $categorias = recoergocategoriasModel::find($request->ID_CATEGORIA_ERGO);
                            $categorias->update($request->all());
                            $response['code'] = 1;
                            $response['categorias'] = 'Actualizada';
                        }
                        return response()->json($response);
                    }
                    $response['code']  = 1;
                    $response['categorias']  = $categorias;
                    return response()->json($response);
                    break;
                default:
                    $response['code']  = 1;
                    $response['msj']  = 'Api no encontrada';
                    return response()->json($response);
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar el género');
        }
    }

}
