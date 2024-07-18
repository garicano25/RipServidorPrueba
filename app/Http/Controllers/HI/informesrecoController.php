<?php

namespace App\Http\Controllers\HI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;


class informesrecoController extends Controller
{
    public function index(){
    
        $recsensorials = recsensorialModel::whereNotNull('recsensorial_foliofisico')
        ->orWhereNotNull('recsensorial_folioquimico')
        ->get();

        return view('catalogos.recsensorial.informesrec', compact('recsensorials'));
    }
}
