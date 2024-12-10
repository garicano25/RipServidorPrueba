<?php

namespace App\Http\Controllers\reportes;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;

//Tablas revisiones
use App\modelos\reportes\reporterevisionesModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use App\modelos\recsensorial\catConclusionesModel;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;

class reportenom035Controller extends Controller
{
    //

       /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidovista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if ($proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Ruido primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 1) // Ruido
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();

            //=================================================== DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 1,
                    'agente_nombre' => 'si',
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
            //=================================================== DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR


            //CATEGORIAS POE
            //-------------------------------------


            $categorias = DB::select('SELECT
                                            reporteruidocategoria.proyecto_id, 
                                            reporteruidocategoria.registro_id, 
                                            reporteruidocategoria.id, 
                                            reporteruidocategoria.reporteruidocategoria_nombre, 
                                            reporteruidocategoria.reporteruidocategoria_total
                                        FROM
                                            reporteruidocategoria
                                        WHERE
                                            reporteruidocategoria.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reporteruidocategoria.reporteruidocategoria_nombre ASC');


            if (count($categorias) > 0) {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            } else {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //-------------------------------------


            $areas = DB::select('SELECT
                                    reporteruidoarea.proyecto_id, 
                                    reporteruidoarea.registro_id, 
                                    reporteruidoarea.id, 
                                    reporteruidoarea.reporteruidoarea_instalacion, 
                                    reporteruidoarea.reporteruidoarea_nombre, 
                                    reporteruidoarea.reporteruidoarea_numorden, 
                                    reporteruidoarea.reporteruidoarea_porcientooperacion
                                FROM
                                    reporteruidoarea
                                WHERE
                                    reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reporteruidoarea.reporteruidoarea_numorden ASC,
                                    reporteruidoarea.reporteruidoarea_nombre ASC');


            if (count($areas) > 0) {
                $areas_poe = 0; // NO TIENE POE GENERAL
            } else {
                $areas_poe = 1; // TIENE POE GENERAL
            }


            //-------------------------------------


            // $categorias_poe = 1; // TIENE POE GENERAL
            // $areas_poe = 1; // TIENE POE GENERAL


            //-------------------------------------


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.psico.reportenom035', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe', 'catConclusiones'));
        }
    }

}
