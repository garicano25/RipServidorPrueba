<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class informesrecoPsicoController extends Controller
{
    //
    public function index(){

        return view('catalogos.psico.informes_psicosocial');
    }
}
