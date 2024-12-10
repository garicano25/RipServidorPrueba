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

class reporteMapaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('asignacionUser:INFORMES')->only('store');
    }

    public function reportemapaderiesgovista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Mapa de riesgo primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION EN CASO DE QUE NO EXISTA
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 17) // Mapa de riesgo
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 17,
                    'agente_nombre' => 'Mapa de riesgos',
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



            //=================== INSERTAMOS LOS PUNTOS EVALUADOS DE BEI ========================================== 

            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();

            // Vista
            return view('reportes.parametros.reportemapaderiesgo', compact('proyecto', 'recsensorial'));
        }
    }

}
