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

class reportenom0353Controller extends Controller
{
   
       /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom035vista3($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if ($proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Ruido primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
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
            return view('reportes.psico.reportenom035guia3', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe', 'catConclusiones'));
        }
    }

      /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportepsico3datosgenerales($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reporteruidocatalogoModel::limit(1)->get();
            $reporte  = reporteruidoModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reporteruido_revision', 'DESC')
                ->limit(1)
                ->get();


            if (count($reporte) > 0) {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = $reporte->id;
            } else {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reporteruidoModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('proyecto_id', 'DESC')
                        ->orderBy('reporteruido_revision', 'DESC')
                        ->limit(1)
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reporteruido.id,
                                                reporteruido.proyecto_id,
                                                reporteruido.agente_id,
                                                reporteruido.agente_nombre,
                                                reporteruido.catactivo_id,
                                                reporteruido.reporteruido_revision,
                                                reporteruido.reporteruido_fecha,
                                                reporteruido.reporte_mes,
                                                reporteruido.reporteruido_instalacion,
                                                reporteruido.reporteruido_catregion_activo,
                                                reporteruido.reporteruido_catsubdireccion_activo,
                                                reporteruido.reporteruido_catgerencia_activo,
                                                reporteruido.reporteruido_catactivo_activo,
                                                reporteruido.reporteruido_introduccion,
                                                reporteruido.reporteruido_objetivogeneral,
                                                reporteruido.reporteruido_objetivoespecifico,
                                                reporteruido.reporteruido_metodologia_4_1,
                                                reporteruido.reporteruido_metodologia_4_2,
                                                reporteruido.reporteruido_ubicacioninstalacion,
                                                reporteruido.reporteruido_ubicacionfoto,
                                                reporteruido.reporteruido_procesoinstalacion,
                                                reporteruido.reporteruido_actividadprincipal,
                                                reporteruido.reporteruido_metodoevaluacion,
                                                reporteruido.reporteruido_conclusion,
                                                reporteruido.reporteruido_responsable1,
                                                reporteruido.reporteruido_responsable1cargo,
                                                reporteruido.reporteruido_responsable1documento,
                                                reporteruido.reporteruido_responsable2,
                                                reporteruido.reporteruido_responsable2cargo,
                                                reporteruido.reporteruido_responsable2documento,
                                                reporteruido.reporteruido_concluido,
                                                reporteruido.reporteruido_concluidonombre,
                                                reporteruido.reporteruido_concluidofecha,
                                                reporteruido.reporteruido_cancelado,
                                                reporteruido.reporteruido_canceladonombre,
                                                reporteruido.reporteruido_canceladofecha,
                                                reporteruido.reporteruido_canceladoobservacion,
                                                reporteruido.reporteruido_lmpe,
                                                reporteruido.created_at,
                                                reporteruido.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reporteruido ON proyecto.id = reporteruido.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . ' 
                                                AND reporteruido.reporteruido_instalacion <> "" 
                                            ORDER BY
                                                reporteruido.updated_at DESC');
                }


                if (count($reporte) > 0) {
                    $reporte = $reporte[0];
                    $dato['reporteregistro_id'] = 0;
                } else {
                    $reporte = array(0, 0);
                    $dato['reporteregistro_id'] = -1;
                }
            }

            //------------------------------


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 1) //Ruido
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) > 0) {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reporte_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reporte_cancelado'] = $revision->reporterevisiones_cancelado;
            } else {
                $dato['reporte_concluido'] = 0;
                $dato['reporte_cancelado'] = 0;
            }


            // PORTADA
            //===================================================
            $dato['reporteruido_lmpe'] = $reporte->reporteruido_lmpe;

            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_fecha != NULL && $reporte->proyecto_id == $proyecto_id) {
                $reportefecha = $reporte->reporteruido_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => $reporte->reporteruido_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => $reporte->reporteruido_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => $reporte->reporteruido_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => $reporte->reporteruido_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => $reporte->reporte_mes
                );
            } else {
                $reportefecha = $meses[$proyectofecha[1] + 0] . " del " . $proyectofecha[0];
                $dato['reporte_portada_guardado'] = 0;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => 1,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => 1,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => 1,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => 1,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => ""



                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_introduccion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_introduccion_guardado'] = 1;
                } else {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reporteruido_introduccion;
            } else {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo[0]->reporteruidocatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_objetivogeneral != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reporteruido_objetivogeneral;
            } else {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo[0]->reporteruidocatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_objetivoespecifico != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reporteruido_objetivoespecifico;
            } else {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo[0]->reporteruidocatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_metodologia_4_1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reporteruido_metodologia_4_1;
            } else {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo[0]->reporteruidocatalogo_metodologia_4_1;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_metodologia_4_2 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_2_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reporte->reporteruido_metodologia_4_2;
            } else {
                $dato['reporte_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportecatalogo[0]->reporteruidocatalogo_metodologia_4_2;
            }

            $dato['reporte_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_ubicacioninstalacion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reporteruido_ubicacioninstalacion;
            } else {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo[0]->reporteruidocatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_ubicacionfoto != NULL && $reporte->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reporte->reporteruido_ubicacionfoto;
            }

            $dato['reporte_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_procesoinstalacion != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reporteruido_procesoinstalacion;
            } else {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_actividadprincipal != NULL && $reporte->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reporte->reporteruido_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // METODO DE EVALUACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_metodoevaluacion != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_metodoevaluacion_guardado'] = 1;
                $metodoevaluacion = $reporte->reporteruido_metodoevaluacion;
            } else {
                $dato['reporte_metodoevaluacion_guardado'] = 0;
                $metodoevaluacion = $reportecatalogo[0]->reporteruidocatalogo_metodoevaluacion;
            }

            $dato['reporte_metodoevaluacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodoevaluacion);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_conclusion != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reporteruido_conclusion;
            } else {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo[0]->reporteruidocatalogo_conclusion;
            }

            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteruido_responsable1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                    'responsable1' => $reporte->reporteruido_responsable1,
                    'responsable1cargo' => $reporte->reporteruido_responsable1cargo,
                    'responsable1documento' => $reporte->reporteruido_responsable1documento,
                    'responsable2' => $reporte->reporteruido_responsable2,
                    'responsable2cargo' => $reporte->reporteruido_responsable2cargo,
                    'responsable2documento' => $reporte->reporteruido_responsable2documento,
                    'proyecto_id' => $reporte->proyecto_id,
                    'registro_id' => $reporte->id
                );
            } else {
                $dato['reporte_responsablesinforme_guardado'] = 0;

                // $reportehistorial = reporteruidoModel::where('catactivo_id', $proyecto->catactivo_id)
                //                                         ->orderBy('proyecto_id', 'DESC')
                //                                         ->orderBy('reporteruido_revision', 'DESC')
                //                                         ->limit(1)
                //                                         ->get();

                $reportehistorial = reporteruidoModel::where('reporteruido_responsable1', '!=', '')
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->get();

                if (count($reportehistorial) > 0 && $reportehistorial[0]->reporteruido_responsable1 != NULL) {
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => $reportehistorial[0]->reporteruido_responsable1,
                        'responsable1cargo' => $reportehistorial[0]->reporteruido_responsable1cargo,
                        'responsable1documento' => $reportehistorial[0]->reporteruido_responsable1documento,
                        'responsable2' => $reportehistorial[0]->reporteruido_responsable2,
                        'responsable2cargo' => $reportehistorial[0]->reporteruido_responsable2cargo,
                        'responsable2documento' => $reportehistorial[0]->reporteruido_responsable2documento,
                        'proyecto_id' => $reportehistorial[0]->proyecto_id,
                        'registro_id' => $reportehistorial[0]->id
                    );
                } else {
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => NULL,
                        'responsable1cargo' => NULL,
                        'responsable1documento' => NULL,
                        'responsable2' => NULL,
                        'responsable2cargo' => NULL,
                        'responsable2documento' => NULL,
                        'proyecto_id' => 0,
                        'registro_id' => 0
                    );
                }
            }


            // MEMORIA FOTOGRAFICA
            //===================================================


            $memoriafotografica = DB::select('SELECT
                                                    -- proyectoevidenciafoto.id,
                                                    proyectoevidenciafoto.proyecto_id,
                                                    -- proyectoevidenciafoto.proveedor_id,
                                                    -- proyectoevidenciafoto.agente_id,
                                                    proyectoevidenciafoto.agente_nombre,
                                                    -- proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                                    IFNULL(COUNT(proyectoevidenciafoto.proyectoevidenciafoto_descripcion), 0) AS total
                                                    -- ,proyectoevidenciafoto.proyectoevidenciafoto_archivo,
                                                    -- proyectoevidenciafoto.proyectoevidenciafoto_descripcion 
                                                FROM
                                                    proyectoevidenciafoto
                                                WHERE
                                                    proyectoevidenciafoto.proyecto_id = ' . $proyecto_id . '
                                                    AND proyectoevidenciafoto.agente_nombre = "' . $agente_nombre . '"
                                                GROUP BY
                                                    proyectoevidenciafoto.proyecto_id,
                                                    proyectoevidenciafoto.agente_nombre
                                                LIMIT 1');

            if (count($memoriafotografica) > 0) {
                $dato['reporte_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            } else {
                $dato['reporte_memoriafotografica_guardado'] = 0;
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

}
