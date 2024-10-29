<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class seguimientoPsicoController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('seguimientoPsico.proyectos');
        // $proyectos = proyectoModel::with(['recsensorial','recsensorial.catregion','recsensorial.catactivo','recsensorial.catgerencia'])->get();
        // return response()->json($proyectos);
    }
}
