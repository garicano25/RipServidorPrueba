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

use App\modelos\reconocimientoergo\recoergofichastecnicasModel;

class fichasergoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */



    public function Tablarecofichasergo(Request $request)
    {
        try {

            $ergo = $request->get('ergoid');

            $tabla = recoergofichastecnicasModel::select(
                'recoergo_fichastecnicas.*',
                'recoergocategorias.NOMBRE_CATEGORIA_ERGO as NOMBRE_CATEGORIA'
            )
                ->leftJoin(
                    'recoergocategorias',
                    'recoergo_fichastecnicas.CATEGORIA_ID_FICHA',
                    '=',
                    'recoergocategorias.ID_CATEGORIA_ERGO'
                )
                ->where('recoergo_fichastecnicas.RECO_ID', $ergo)
                ->get();

            foreach ($tabla as $value) {

                if ($value->ACTIVO == 0) {

                    $value->BTN_EDITAR = '
                    <button type="button" class="btn btn-primary btn-custom rounded-pill EDITAR">
                        <i class="bi bi-eye"></i>
                    </button>';

                    $value->BTN_VISUALIZAR = '
                    <button type="button" class="btn btn-primary btn-custom rounded-pill VISUALIZAR">
                        <i class="bi bi-eye"></i>
                    </button>';
                } else {

                    $value->BTN_EDITAR = '
                    <button type="button" class="btn btn-warning btn-circle editar">
                        <i class="fa fa-pencil"></i>
                    </button>';
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

    public function getCategoriasErgo(Request $request)
    {
        try {
            $reco_id = $request->get('reco_id');

            $categorias = recoergocategoriasModel::where('RECO_ID', $reco_id)
                ->where('ACTIVO', 1)
                ->get(['ID_CATEGORIA_ERGO', 'NOMBRE_CATEGORIA_ERGO', 'CAT_DEPARTAMENTO', 'CATEGORIA_AREAS_ID']);

            return response()->json([
                'data' => $categorias
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }



    public function store(Request $request)
    {
        try {
            switch (intval($request->api)) {
                case 1:


                    $camposArray = [
                        'CAT_AREAS_FICHA',
                    ];

                    foreach ($camposArray as $campo) {
                        if (!isset($request[$campo]) || empty($request[$campo])) {
                            $request[$campo] = null;
                        }
                    }


                    if ($request->ID_FICHAS_TECNICAS == 0) {
                        DB::statement('ALTER TABLE recoergo_fichastecnicas AUTO_INCREMENT=1;');
                        $fichas = recoergofichastecnicasModel::create($request->all());
                    } else {

                        if (isset($request->ELIMINAR)) {
                            if ($request->ELIMINAR == 1) {
                                $fichas = recoergofichastecnicasModel::where('ID_FICHAS_TECNICAS', $request['ID_FICHAS_TECNICAS'])->update(['ACTIVO' => 0]);
                                $response['code'] = 1;
                                $response['fichas'] = 'Desactivada';
                            } else {
                                $fichas = recoergofichastecnicasModel::where('ID_FICHAS_TECNICAS', $request['ID_FICHAS_TECNICAS'])->update(['ACTIVO' => 1]);
                                $response['code'] = 1;
                                $response['fichas'] = 'Activada';
                            }
                        } else {
                            $fichas = recoergofichastecnicasModel::find($request->ID_FICHAS_TECNICAS);
                            $fichas->update($request->all());
                            $response['code'] = 1;
                            $response['fichas'] = 'Actualizada';
                        }
                        return response()->json($response);
                    }
                    $response['code']  = 1;
                    $response['fichas']  = $fichas;
                    return response()->json($response);
                    break;
                default:
                    $response['code']  = 1;
                    $response['msj']  = 'Api no encontrada';
                    return response()->json($response);
            }
        } catch (Exception $e) {
            return response()->json('Error al guardar ');
        }
    }


    }
