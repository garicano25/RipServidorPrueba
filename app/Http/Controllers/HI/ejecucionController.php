<?php

namespace App\Http\Controllers\HI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class ejecucionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('catalogos.recsensorial.ejecucionrec');
    }


    public function tablaEjecucion()
    {

        $tabla = DB::select('SELECT p.id ID_PROYECTO,
                                    p.proyecto_folio FOLIO,
                                    p.proyecto_fechainicio AS FECHA_INICIO,
                                    p.proyecto_fechafin AS FECHA_FIN,
                                    IFNULL(p.recsensorial_id, 0) TIENE_RECONOCIMIENTO,
                                    IFNULL(r.recsensorial_foliofisico, "") FOLIO_FISICO,
                                    IFNULL(r.recsensorial_folioquimico , "" ) FOLIO_QUIMICO
                            FROM proyecto p
                            LEFT JOIN recsensorial r ON r.id = p.recsensorial_id 
                            LEFT JOIN serviciosProyecto s ON s.PROYECTO_ID = p.id
                            WHERE s.HI_EJECUCION = 1');


        $count = 0;
        foreach ($tabla as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            
            if ($value->TIENE_RECONOCIMIENTO  != 0) {

                if ($value->FOLIO_FISICO != "" && $value->FOLIO_QUIMICO != "") {
                    $value->RECONOCIMIENTO_VINCULADO = $value->FOLIO_FISICO . "<br>" . $value->FOLIO_QUIMICO;
                } else if ($value->FOLIO_FISICO != "") {
                    $value->RECONOCIMIENTO_VINCULADO = $value->FOLIO_FISICO;
                } else {
                    $value->RECONOCIMIENTO_VINCULADO = $value->FOLIO_QUIMICO;
                }

                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle mostrar" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            } else {

                $value->RECONOCIMIENTO_VINCULADO = 'Sin vincular <br><i class="fa fa-ban text-danger"></i>';
                $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px; border: 1px #999999 solid!important;" disabled><i class="fa fa-eye-slash fa-2x"></i></button>';
            }
        }

        $listado['data']  = $tabla;
        return response()->json($listado);
    }
}
