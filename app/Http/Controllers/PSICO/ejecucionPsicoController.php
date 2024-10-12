<?php

namespace App\Http\Controllers\PSICO;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ejecucionPsicoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('catalogos.psico.ejecucion_psicosocial');
    }

    public function tablaEjecucion()
    {

        $tabla = DB::select('SELECT p.id ID_PROYECTO,
                                    p.proyecto_folio FOLIO,
                                    p.proyecto_fechainicio AS FECHA_INICIO,
                                    p.proyecto_fechafin AS FECHA_FIN,
                                    IFNULL(p.reconocimiento_psico_id, 0) TIENE_RECONOCIMIENTO,
                                    p.proyecto_clienteinstalacion,
                                    p.proyecto_clientedireccionservicio
                            FROM proyecto p
                            LEFT JOIN reconocimientopsico r ON r.id = p.reconocimiento_psico_id  
                            LEFT JOIN serviciosProyecto s ON s.PROYECTO_ID = p.id
                            WHERE s.PSICO_EJECUCION = 1');


        $count = 0;
        foreach ($tabla as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            $value->instalacion_y_direccion = '<span style="color: #999999;">' . $value->proyecto_clienteinstalacion . '</span><br>' . $value->proyecto_clientedireccionservicio;

            if ($value->TIENE_RECONOCIMIENTO  != 0) {
                $value->boton_mostrar = '<button type="button" class="btn btn-info btn-circle mostrar" style="padding: 0px;"><i class="fa fa-eye fa-2x"></i></button>';
            } else {
                $value->RECONOCIMIENTO_VINCULADO = 'Sin vincular <br><i class="fa fa-ban text-danger"></i>';
                $value->boton_mostrar = '<button type="button" class="btn btn-secondary btn-circle" style="padding: 0px; border: 1px #999999 solid!important;" disabled><i class="fa fa-eye-slash fa-2x"></i></button>';
            }
        }

        $listado['data']  = $tabla;
        return response()->json($listado);
    }

    public function tablaTrabajadoresOnline()
    {

        $tablaOnline = DB::select('SELECT r.RECPSICOTRABAJADOR_NOMBRE NOMBRE
                            FROM recopsicotrabajadores r
                            WHERE r.RECPSICOTRABAJADOR_MUESTRA = 1');


        $count = 0;
        foreach ($tablaOnline as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            $value->ESTADOCORREO = 'Sin enviar';
            $value->FECHAINICIO = '2024-10-24';
            $value->FECHAFIN = '2024-10-24';
            $value->ESTADOCUESTIONARIO = 'Sin iniciar';
            $value->boton_enviarCorreo = '<button type="button" class="btn btn-warning btn-circle enviarcorreo" style="padding: 0px;"><i class="fa fa-paper-plane "></i></button>';
        }

        $online['data']  = $tablaOnline;
        return response()->json($online);
    }

    public function tablaTrabajadoresPresencial()
    {

        $tablaPresencial = DB::select('SELECT r.RECPSICOTRABAJADOR_NOMBRE NOMBRE
                            FROM recopsicotrabajadores r
                            WHERE r.RECPSICOTRABAJADOR_MUESTRA = 0');


        $count = 0;
        foreach ($tablaPresencial as $key => $value) {
            $count += 1;

            $value->COUNT = $count;
            $value->ESTADOCUESTIONARIO = 'Pendiente';
            $value->boton_cargarPresencial = '<button type="button" class="btn btn-success btn-circle enviarcorreo" style="padding: 0px;"><i class="fa fa-file-excel-o"></i></button>';
        }

        $online['data']  = $tablaPresencial;
        return response()->json($online);
    }
}
