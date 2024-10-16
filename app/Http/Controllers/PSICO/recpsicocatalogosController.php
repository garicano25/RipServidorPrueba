<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Storage;

class recpsicocatalogosController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('catalogos.psico.recpsicocatalogos');
    }

}
