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


use App\modelos\reconocimientoergo\recoergoareasModel;

class areasergoController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */





    public function Tablarecoareasergo(Request $request)
    {
        try {
            $ergo = $request->get('ergoid');

            $tabla = recoergoareasModel::where('RECO_ID', $ergo)->get();


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




    public function store(Request $request)
    {
        try {
            switch (intval($request->api)) {
                case 1:
                    if ($request->ID_AREA_ERGO == 0) {
                        DB::statement('ALTER TABLE recoergoareas AUTO_INCREMENT=1;');
                        $areas = recoergoareasModel::create($request->all());
                    } else {

                        if (isset($request->ELIMINAR)) {
                            if ($request->ELIMINAR == 1) {
                                $areas = recoergoareasModel::where('ID_AREA_ERGO', $request['ID_AREA_ERGO'])->update(['ACTIVO' => 0]);
                                $response['code'] = 1;
                                $response['areas'] = 'Desactivada';
                            } else {
                                $areas = recoergoareasModel::where('ID_AREA_ERGO', $request['ID_AREA_ERGO'])->update(['ACTIVO' => 1]);
                                $response['code'] = 1;
                                $response['areas'] = 'Activada';
                            }
                        } else {
                            $areas = recoergoareasModel::find($request->ID_AREA_ERGO);
                            $areas->update($request->all());
                            $response['code'] = 1;
                            $response['areas'] = 'Actualizada';
                        }
                        return response()->json($response);
                    }
                    $response['code']  = 1;
                    $response['areas']  = $areas;
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
