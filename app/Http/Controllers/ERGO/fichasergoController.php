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


class fichasergoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getCategoriasErgo(Request $request)
    {
        try {
            $reco_id = $request->get('reco_id');

            $categorias = recoergocategoriasModel::where('RECO_ID', $reco_id)
                ->where('ACTIVO', 1)
                ->get(['ID_CATEGORIA_ERGO', 'NOMBRE_CATEGORIA_ERGO', 'CAT_DEPARTAMENTO']);

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


}
