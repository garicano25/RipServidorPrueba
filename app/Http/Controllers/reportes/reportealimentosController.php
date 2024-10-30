<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Storage;


// Modelos de uso
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reportecategoriaModel;
use App\modelos\reportes\reporteareaModel;
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\recursosPortadasInformesModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\recsensorial\catConclusionesModel;


class reportealimentosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('asignacionUser:INFORMES')->only('store');
    }

    public function reportealimentosvista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Alimentos primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION EN CASO DE QUE NO EXISTA
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 11) // Alimentos
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 22,
                    'agente_nombre' => 'Alimentos',
                    'reporterevisiones_revision' => 0,
                    'reporterevisiones_concluido' => 0,
                    'reporterevisiones_concluidonombre' => NULL,
                    'reporterevisiones_concluidofecha' => NULL,
                    'reporterevisiones_cancelado' => 0,
                    'reporterevisiones_canceladonombre' => NULL,
                    'reporterevisiones_canceladofecha' => NULL,
                    'reporterevisiones_canceladoobservacion' => NULL
                ]);
            }


            //CATEGORIAS POE
            //-------------------------------------


            $categorias = DB::select('SELECT
                                            reportebeicategoria.proyecto_id, 
                                            reportebeicategoria.registro_id, 
                                            reportebeicategoria.id, 
                                            reportebeicategoria.reportebeicategoria_nombre
                                        FROM
                                            reportebeicategoria
                                        WHERE
                                            reportebeicategoria.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportebeicategoria.reportebeicategoria_nombre ASC');


            if (count($categorias) > 0) {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            } else {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //-------------------------------------


            $areas = DB::select('SELECT
                                    reportebeiarea.proyecto_id, 
                                    reportebeiarea.registro_id, 
                                    reportebeiarea.id, 
                                    reportebeiarea.reportebeiarea_nombre
                                FROM
                                    reportebeiarea
                                WHERE
                                    reportebeiarea.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reportebeiarea.reportebeiarea_nombre ASC');


            //SI LA CONSULTA NO TIENE NADA
            if (count($areas) > 0) {
                $areas_poe = 0; // NO TIENE POE GENERAL
            } else //SI LA CONSULTA TRAE ALGO
            {
                $areas_poe = 1; // TIENE POE GENERAL
            }



            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();

            // Vista
            return view('reportes.parametros.reportealimentos', compact('proyecto', 'recsensorial', 'categorias_poe', 'areas_poe', 'catConclusiones'));
        }
    }

   
}
