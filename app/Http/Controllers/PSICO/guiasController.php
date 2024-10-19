<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;



class guiasController extends Controller
{



public function obtenerExplicaciones(Request $request) {
    $ids = $request->input('ids');  

    $explicaciones = DB::table('catalogoguiaspsico')
                    ->whereIn('PREGUNTA_ID', $ids) 
                    ->pluck('PREGUNTA_EXPLICACION', 'PREGUNTA_ID'); 

    return response()->json(['explicaciones' => $explicaciones]);
}


}
