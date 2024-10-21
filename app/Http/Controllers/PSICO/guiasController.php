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


public function consultarDatosTrabajador(Request $request) {
    $idTrabajador = $request->input('id_trabajador');

    $trabajador = DB::select("
        SELECT RECPSICOTRABAJADOR_NOMBRE, RECPSICOTRABAJADOR_GENERO, RECPSICOTRABAJADOR_CORREO
        FROM recopsicotrabajadores
        WHERE ID_RECOPSICOTRABAJADOR = :idTrabajador
    ", ['idTrabajador' => $idTrabajador]);

    if (!empty($trabajador)) {
        return response()->json($trabajador[0]); 
    }

    return response()->json(['error' => 'No se encontraron datos para este trabajador'], 404);
}





}
