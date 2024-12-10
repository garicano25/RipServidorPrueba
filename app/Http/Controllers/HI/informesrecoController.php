<?php

namespace App\Http\Controllers\HI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;


class informesrecoController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras,Psicólogo,Ergónomo');

        // $this->middleware('asignacionUser:RECSENSORIAL')->only('store');

    }

    
    public function index(){
    
        $recsensorials = recsensorialModel::whereNotNull('recsensorial_foliofisico')
        ->orWhereNotNull('recsensorial_folioquimico')
        ->get();

        return view('catalogos.recsensorial.informesrec', compact('recsensorials'));
    }
}
