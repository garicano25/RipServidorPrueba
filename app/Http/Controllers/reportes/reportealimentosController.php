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
use App\modelos\reportes\reportealimentoscatalogoModel;
use App\modelos\reportes\reportealimentosModel;


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
                    'agente_id' => 11,
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

    public function datosproyectolimpiartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        $texto = str_replace($reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], 'REPORTE_FECHA_LARGA', $texto);

        if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace($proyecto->catsubdireccion->catsubdireccion_nombre, 'SUBDIRECCION_NOMBRE', $texto);
            $texto = str_replace($proyecto->catgerencia->catgerencia_nombre, 'GERENCIA_NOMBRE', $texto);
            $texto = str_replace($proyecto->catactivo->catactivo_nombre, 'ACTIVO_NOMBRE', $texto);
        } else {
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX TRI', $texto);
        }

        return $texto;
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        // $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. '.$recsensorial->recsensorial_codigopostal, $texto); 
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', '', $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], $texto);

        if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace('SUBDIRECCION_NOMBRE', $proyecto->catsubdireccion->catsubdireccion_nombre, $texto);
            $texto = str_replace('GERENCIA_NOMBRE', $proyecto->catgerencia->catgerencia_nombre, $texto);
            $texto = str_replace('ACTIVO_NOMBRE', $proyecto->catactivo->catactivo_nombre, $texto);
        } else {
            $texto = str_replace('SUBDIRECCION_NOMBRE', '', $texto);
            $texto = str_replace('GERENCIA_NOMBRE', '', $texto);
            $texto = str_replace('ACTIVO_NOMBRE', '', $texto);

            $texto = str_replace('PEMEX TRI', $recsensorial->recsensorial_empresa, $texto);
        }

        return $texto;
    }



    public function reportealimentosdatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportealimentoscatalogo = reportealimentoscatalogoModel::limit(1)->get();

            $reportealimentos  = reportealimentosModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reportealimentos_revision', 'DESC')
                ->limit(1)
                ->get();

            if (count($reportealimentos) == 0) {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reportealimentos = reportealimentosModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('proyecto_id', 'DESC')
                        ->orderBy('reportealimentos_revision', 'DESC')
                        // ->orderBy('updated_at', 'DESC')
                        ->limit(1)
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reportealimentos.id,
                                                reportealimentos.proyecto_id,
                                                reportealimentos.agente_id,
                                                reportealimentos.agente_nombre,
                                                reportealimentos.catactivo_id,
                                                reportealimentos.reportealimentos_revision,
                                                reportealimentos.reportealimentos_fecha,
                                                reportealimentos.reportealimentos_mes,
                                                reportealimentos.reportealimentos_instalacion,
                                                reportealimentos.reportealimentos_catregion_activo,
                                                reportealimentos.reportealimentos_catsubdireccion_activo,
                                                reportealimentos.reportealimentos_catgerencia_activo,
                                                reportealimentos.reportealimentos_catactivo_activo,
                                                reportealimentos.reportealimentos_introduccion,
                                                reportealimentos.reportealimentos_objetivogeneral,
                                                reportealimentos.reportealimentos_objetivoespecifico,
                                                reportealimentos.reportealimentos_metodologia_4_1,
                                                reportealimentos.reportealimentos_metodologia_4_2,
                                                reportealimentos.reportealimentos_metodologia_5_1,
                                                reportealimentos.reportealimentos_metodologia_5_2,
                                                reportealimentos.reportealimentos_ubicacioninstalacion,
                                                reportealimentos.reportealimentos_ubicacionfoto,
                                                reportealimentos.reportealimentos_procesoinstalacion,
                                                reportealimentos.reportealimentos_actividadprincipal,
                                                reportealimentos.reportealimentos_conclusion,
                                                reportealimentos.reportealimentos_responsable1,
                                                reportealimentos.reportealimentos_responsable1cargo,
                                                reportealimentos.reportealimentos_responsable1documento,
                                                reportealimentos.reportealimentos_responsable2,
                                                reportealimentos.reportealimentos_responsable2cargo,
                                                reportealimentos.reportealimentos_responsable2documento,
                                                reportealimentos.reportealimentos_concluido,
                                                reportealimentos.reportealimentos_concluidonombre,
                                                reportealimentos.reportealimentos_concluidofecha,
                                                reportealimentos.reportealimentos_cancelado,
                                                reportealimentos.reportealimentos_canceladonombre,
                                                reportealimentos.reportealimentos_canceladofecha,
                                                reportealimentos.reportealimentos_canceladoobservacion,
                                                reportealimentos.created_at,
                                                reportealimentos.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportealimentos ON proyecto.id = reportealimentos.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ? 
                                                AND reportealimentos.reportealimentos_instalacion <> "" 
                                            ORDER BY
                                                reportealimentos.updated_at DESC', [$recsensorial->cliente_id]);
                }


                $dato['reportealimentos_id'] = 0;


                if (count($reportealimentos) == 0) {
                    $reportealimentos = array(0, 0);
                    $dato['reportealimentos_id'] = -1;
                }
            } else {
                $dato['reportealimentos_id'] = $reportealimentos[0]->id;
            }


            $reportealimentos = $reportealimentos[0];


            //------------------------------


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 11) //Alimentos
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) > 0) {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reportealimentos_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reportealimentos_cancelado'] = $revision->reporterevisiones_cancelado;
            } else {
                $dato['reportealimentos_concluido'] = 0;
                $dato['reportealimentos_cancelado'] = 0;
            }


            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);


            // PORTADA
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_fecha != NULL && $reportealimentos->proyecto_id == $proyecto_id) {
                $reportefecha = $reportealimentos->reportealimentos_fecha;

                $dato['reportealimentos_portada_guardado'] = 1;
            } else {
                $reportefecha = $meses[$proyectofecha[1] + 0] . " del " . $proyectofecha[0];

                $dato['reportealimentos_portada_guardado'] = 0;
            }


            if ($dato['reportealimentos_id'] >= 0) {
                $dato['reportealimentos_portada'] = array(
                    'reportealimentos_catregion_activo' => $reportealimentos->reportealimentos_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reportealimentos_catsubdireccion_activo' => $reportealimentos->reportealimentos_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reportealimentos_catgerencia_activo' => $reportealimentos->reportealimentos_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reportealimentos_catactivo_activo' => $reportealimentos->reportealimentos_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reportealimentos_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reportealimentos_fecha' => $reportefecha,
                    'reportealimentos_mes' => $reportealimentos->reportealimentos_mes


                );
            } else {
                $dato['reportealimentos_portada'] = array(
                    'reportealimentos_catregion_activo' => 1,
                    'catregion_id' => $proyecto->catregion_id,
                    'reportealimentos_catsubdireccion_activo' => 1,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reportealimentos_catgerencia_activo' => 1,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reportealimentos_catactivo_activo' => 1,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reportealimentos_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reportealimentos_fecha' => $reportefecha,
                    'reportealimentos_mes' => ""

                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_introduccion != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_introduccion_guardado'] = 1;
                } else {
                    $dato['reportealimentos_introduccion_guardado'] = 0;
                }

                $introduccion = $reportealimentos->reportealimentos_introduccion;
            } else {
                $dato['reportealimentos_introduccion_guardado'] = 0;
                $introduccion = $reportealimentoscatalogo[0]->reportealimentoscatalogo_introduccion;
            }

            $dato['reportealimentos_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_objetivogeneral != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reportealimentos_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reportealimentos->reportealimentos_objetivogeneral;
            } else {
                $dato['reportealimentos_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportealimentoscatalogo[0]->reportealimentoscatalogo_objetivogeneral;
            }

            $dato['reportealimentos_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_objetivoespecifico != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reportealimentos_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reportealimentos->reportealimentos_objetivoespecifico;
            } else {
                $dato['reportealimentos_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportealimentoscatalogo[0]->reportealimentoscatalogo_objetivoespecifico;
            }

            $dato['reportealimentos_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_metodologia_4_1 != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reportealimentos_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reportealimentos->reportealimentos_metodologia_4_1;
            } else {
                $dato['reportealimentos_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportealimentoscatalogo[0]->reportealimentoscatalogo_metodologia_4_1;
            }

            $dato['reportealimentos_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_metodologia_4_2 != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_metodologia_4_2_guardado'] = 1;
                } else {
                    $dato['reportealimentos_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reportealimentos->reportealimentos_metodologia_4_2;
            } else {
                $dato['reportealimentos_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportealimentoscatalogo[0]->reportealimentoscatalogo_metodologia_4_2;
            }

            $dato['reportealimentos_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);


            // METODOLOGIA PUNTO 4.2.1
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_metodologia_5_1 != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_metodologia_5_1_guardado'] = 1;
                } else {
                    $dato['reportealimentos_metodologia_5_1_guardado'] = 0;
                }

                $metodologia_5_1 = $reportealimentos->reportealimentos_metodologia_5_1;
            } else {
                $dato['reportealimentos_metodologia_5_1_guardado'] = 0;
                $metodologia_5_1 = $reportealimentoscatalogo[0]->reportealimentoscatalogo_metodologia_5_1;
            }

            $dato['reportealimentos_metodologia_5_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_5_1);


            // METODOLOGIA PUNTO 4.2.2
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_metodologia_5_2 != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_metodologia_5_2_guardado'] = 1;
                } else {
                    $dato['reportealimentos_metodologia_5_2_guardado'] = 0;
                }

                $metodologia_5_2 = $reportealimentos->reportealimentos_metodologia_5_2;
            } else {
                $dato['reportealimentos_metodologia_5_2_guardado'] = 0;
                $metodologia_5_2 = $reportealimentoscatalogo[0]->reportealimentoscatalogo_metodologia_5_2;
            }

            $dato['reportealimentos_metodologia_5_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_5_2);

         
            // UBICACION
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_ubicacioninstalacion != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reportealimentos_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reportealimentos->reportealimentos_ubicacioninstalacion;
            } else {
                $dato['reportealimentos_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportealimentoscatalogo[0]->reportealimentoscatalogo_ubicacioninstalacion;
            }

            $ubicacionfoto = NULL;
            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_ubicacionfoto != NULL && $reportealimentos->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reportealimentos->reportealimentos_ubicacionfoto;
            }

            $dato['reportealimentos_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_procesoinstalacion != NULL && $reportealimentos->proyecto_id == $proyecto_id) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_procesoinstalacion_guardado'] = 1;
                } else {
                    $dato['reportealimentos_procesoinstalacion_guardado'] = 0;
                }

                $procesoinstalacion = $reportealimentos->reportealimentos_procesoinstalacion;
            } else {
                $dato['reportealimentos_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reportealimentos_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_actividadprincipal != NULL && $reportealimentos->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reportealimentos->reportealimentos_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reportealimentos_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // CONCLUSION
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_conclusion != NULL && $reportealimentos->proyecto_id == $proyecto_id) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_conclusion_guardado'] = 1;
                } else {
                    $dato['reportealimentos_conclusion_guardado'] = 0;
                }

                $conclusion = $reportealimentos->reportealimentos_conclusion;
            } else {
                $dato['reportealimentos_conclusion_guardado'] = 0;
                $conclusion = $reportealimentoscatalogo[0]->reportealimentoscatalogo_conclusion;
            }

            $dato['reportealimentos_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reportealimentos_id'] >= 0 && $reportealimentos->reportealimentos_responsable1 != NULL) {
                if ($reportealimentos->proyecto_id == $proyecto_id) {
                    $dato['reportealimentos_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reportealimentos_responsablesinforme_guardado'] = 0;
                }

                $dato['reportealimentos_responsablesinforme'] = array(
                    'reportealimentos_responsable1' => $reportealimentos->reportealimentos_responsable1,
                    'reportealimentos_responsable1cargo' => $reportealimentos->reportealimentos_responsable1cargo,
                    'reportealimentos_responsable1documento' => $reportealimentos->reportealimentos_responsable1documento,
                    'reportealimentos_responsable2' => $reportealimentos->reportealimentos_responsable2,
                    'reportealimentos_responsable2cargo' => $reportealimentos->reportealimentos_responsable2cargo,
                    'reportealimentos_responsable2documento' => $reportealimentos->reportealimentos_responsable2documento,
                    'proyecto_id' => $reportealimentos->proyecto_id,
                    'registro_id' => $reportealimentos->id,
                    'tipo1' => 1,
                    'tipo2' => 2
                );
            } else {
                $dato['reportealimentos_responsablesinforme_guardado'] = 0;
                $dato['reportealimentos_responsablesinforme'] = array(
                    'reportealimentos_responsable1' => $recsensorial->recsensorial_repfisicos1nombre,
                    'reportealimentos_responsable1cargo' => $recsensorial->recsensorial_repfisicos1cargo,
                    'reportealimentos_responsable1documento' => $recsensorial->recsensorial_repfisicos1doc,
                    'reportealimentos_responsable2' => $recsensorial->recsensorial_repfisicos2nombre,
                    'reportealimentos_responsable2cargo' => $recsensorial->recsensorial_repfisicos2cargo,
                    'reportealimentos_responsable2documento' => $recsensorial->recsensorial_repfisicos2doc,
                    'proyecto_id' => 0,
                    'registro_id' => 0,
                    'recsensorial_id' => $recsensorial->id,
                    'tipo1' => 3,
                    'tipo2' => 4
                );
                // }
            }


            // MEMORIA FOTOGRAFICA
            //===================================================


            $memoriafotografica = collect(DB::select('SELECT
                                                            proyectoevidenciafoto.proyecto_id,
                                                            proyectoevidenciafoto.agente_nombre,
                                                            IFNULL(COUNT(proyectoevidenciafoto.proyectoevidenciafoto_descripcion), 0) AS total
                                                        FROM
                                                            proyectoevidenciafoto
                                                        WHERE
                                                            proyectoevidenciafoto.proyecto_id = ' . $proyecto_id . '
                                                            AND proyectoevidenciafoto.agente_nombre = "' . $agente_nombre . '"
                                                        GROUP BY
                                                            proyectoevidenciafoto.proyecto_id,
                                                            proyectoevidenciafoto.agente_nombre
                                                        LIMIT 1'));

            if (count($memoriafotografica) > 0) {
                $dato['reportealimentos_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            } else {
                $dato['reportealimentos_memoriafotografica_guardado'] = 0;
            }

            //===================================================
            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);

        } catch (Exception $e) {

            $dato['datoscompletos'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    public function reportealimentosmapaubicacion($reportealimentos_id, $archivo_opcion)
    {
        $reportealimentos  = reportealimentosModel::findOrFail($reportealimentos_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reportealimentos->reportealimentos_ubicacionfoto);
        } else {
            return Storage::download($reportealimentos->reportealimentos_ubicacionfoto);
        }
    }


    public function reportealimentosresponsabledocumento($reportealimentos_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {

        if ($responsabledoc_tipo == 1) {

            $reportealimentos  = reportealimentosModel::findOrFail($reportealimentos_id);

            if ($responsabledoc_opcion == 0) {
                return Storage::response($reportealimentos->reportealimentos_responsable1documento);
            } else {
                return Storage::download($reportealimentos->reportealimentos_responsable1documento);
            }
        } else if ($responsabledoc_tipo == 2) {

            $reportealimentos  = reportealimentosModel::findOrFail($reportealimentos_id);

            if ($responsabledoc_opcion == 0) {
                return Storage::response($reportealimentos->reportealimentos_responsable2documento);
            } else {
                return Storage::download($reportealimentos->reportealimentos_responsable2documento);
            }
        } else if ($responsabledoc_tipo == 3) {

            $recsensorial  = recsensorialModel::findOrFail($reportealimentos_id);

            if ($responsabledoc_opcion == 0) {

                return Storage::response($recsensorial->recsensorial_repfisicos1doc);
            } else {

                return Storage::download($recsensorial->recsensorial_repfisicos1doc);
            }
        } else if ($responsabledoc_tipo == 4) {

            $recsensorial  = recsensorialModel::findOrFail($reportealimentos_id);

            if ($responsabledoc_opcion == 0) {

                return Storage::response($recsensorial->recsensorial_repfisicos2doc);
            } else {

                return Storage::download($recsensorial->recsensorial_repfisicos2doc);
            }
        }
    }


    public function reportealimentostabladefiniciones($proyecto_id, $agente_nombre, $reportealimentos_id)
    {
        try {
          
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 11)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            // Datos
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

            $where_definiciones = '';
            if (($recsensorial->recsensorial_tipocliente + 0) == 1) //1 = pemex, 0 = cliente
            {
                $where_definiciones = 'AND reportedefiniciones.catactivo_id = ' . $proyecto->catactivo_id;
            }

            $definiciones_catalogo = collect(DB::select('SELECT
                                                                TABLA.id,
                                                                TABLA.agente_id,
                                                                TABLA.agente_nombre,
                                                                TABLA.catactivo_id,
                                                                TABLA.concepto,
                                                                TABLA.descripcion,
                                                                TABLA.fuente
                                                            FROM
                                                                (
                                                                    (
                                                                        SELECT
                                                                            reportedefinicionescatalogo.id,
                                                                            reportedefinicionescatalogo.agente_id,
                                                                            reportedefinicionescatalogo.agente_nombre,
                                                                            -1 catactivo_id,
                                                                            reportedefinicionescatalogo.reportedefinicionescatalogo_concepto AS concepto,
                                                                            reportedefinicionescatalogo.reportedefinicionescatalogo_descripcion AS descripcion,
                                                                            reportedefinicionescatalogo.reportedefinicionescatalogo_fuente AS fuente
                                                                        FROM
                                                                            reportedefinicionescatalogo
                                                                        WHERE
                                                                            reportedefinicionescatalogo.agente_nombre LIKE "' . $agente_nombre . '"
                                                                            AND reportedefinicionescatalogo.reportedefinicionescatalogo_activo = 1
                                                                        ORDER BY
                                                                            reportedefinicionescatalogo.reportedefinicionescatalogo_concepto ASC
                                                                    )
                                                                    UNION ALL
                                                                    (
                                                                        SELECT
                                                                            reportedefiniciones.id,
                                                                            reportedefiniciones.agente_id,
                                                                            reportedefiniciones.agente_nombre,
                                                                            reportedefiniciones.catactivo_id,
                                                                            reportedefiniciones.reportedefiniciones_concepto AS concepto,
                                                                            reportedefiniciones.reportedefiniciones_descripcion AS descripcion,
                                                                            reportedefiniciones.reportedefiniciones_fuente AS fuente 
                                                                        FROM
                                                                            reportedefiniciones
                                                                        WHERE
                                                                            reportedefiniciones.agente_nombre LIKE "' . $agente_nombre . '"
                                                                            ' . $where_definiciones . ' 
                                                                        ORDER BY
                                                                            reportedefiniciones.agente_nombre ASC
                                                                    )
                                                                ) AS TABLA
                                                            ORDER BY
                                                                -- TABLA.catactivo_id ASC,
                                                                TABLA.concepto ASC'));

            foreach ($definiciones_catalogo as $key => $value) {
                if (($value->catactivo_id + 0) < 0) {
                    $value->descripcion_fuente = $value->descripcion . '<br><span style="color: #999999; font-style: italic;">Fuente: ' . $value->fuente . '</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle"><i class="fa fa-ban fa-1x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                } else {
                    $value->descripcion_fuente = $value->descripcion . '<br><span style="color: #999999; font-style: italic;">Fuente: ' . $value->fuente . '</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-eye fa-1x"></i></button>';
                    }
                }
            }

            // respuesta
            $dato['data'] = $definiciones_catalogo;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    public function reportealimentosdefinicioneliminar($definicion_id)
    {
        try {
            $definicion = reportedefinicionesModel::where('id', $definicion_id)->delete();

            // respuesta
            $dato["msj"] = 'Definición eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportealimentostablarevisiones($proyecto_id)
    {
        try {
            $revisiones = DB::select('SELECT
                                            reporterevisiones.proyecto_id,
                                            reporterevisiones.agente_id,
                                            reporterevisiones.agente_nombre,
                                            reporterevisiones.id,
                                            reporterevisiones.reporterevisiones_revision,
                                            reporterevisiones.reporterevisiones_concluido,
                                            reporterevisiones.reporterevisiones_concluidonombre,
                                            reporterevisiones.reporterevisiones_concluidofecha,
                                            reporterevisiones.reporterevisiones_cancelado,
                                            reporterevisiones.reporterevisiones_canceladonombre,
                                            reporterevisiones.reporterevisiones_canceladofecha,
                                            reporterevisiones.reporterevisiones_canceladoobservacion 
                                        FROM
                                            reporterevisiones
                                        WHERE
                                            reporterevisiones.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporterevisiones.agente_id = 4
                                        ORDER BY
                                            reporterevisiones.reporterevisiones_revision DESC');


            $checked_concluido = '';
            $checked_cancelado = '';
            $disabled_concluir = '';
            $disabled_cancelar = '';
            $dato['ultimaversion_cancelada'] = 0;
            $dato['ultimaversion_estado'] = 0;
            $dato['ultimarevision_id'] = 0;


            foreach ($revisiones as $key => $value) {
                if ($key == 0) {
                    $dato['ultimaversion_cancelada'] = $value->reporterevisiones_cancelado;


                    if ($value->reporterevisiones_concluido == 1 || $value->reporterevisiones_cancelado == 1) {
                        $dato['ultimaversion_estado'] = 1;
                    }


                    $value->ultima_revision = $value->id;
                    $dato['ultimarevision_id'] = $value->id;
                } else {
                    $value->ultima_revision = 0;
                }


                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($key + 0) == 0) {
                    $value->perfil_concluir = 1;
                    $disabled_concluir = '';
                } else {
                    $value->perfil_concluir = 0;
                    $disabled_concluir = 'disabled';
                }


                $checked_concluido = '';
                if (($value->reporterevisiones_concluido + 0) == 1) {
                    $checked_concluido = 'checked';
                }


                $value->checkbox_concluido = '<div class="switch" data-toggle="tooltip" title="Solo Coordinadores y Administradores">
                                                    <label>
                                                        <input type="checkbox" class="checkbox_concluido" ' . $checked_concluido . ' ' . $disabled_concluir . ' onclick="reporte_concluido(' . $value->id . ', ' . $value->perfil_concluir . ', this)">
                                                        <span class="lever switch-col-light-blue"></span>
                                                    </label>
                                                </div>';


                $value->nombre_concluido = $value->reporterevisiones_concluidonombre . '<br>' . $value->reporterevisiones_concluidofecha;


                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && ($key + 0) == 0) {
                    $value->perfil_cancelar = 1;
                    $disabled_cancelar = '';
                } else {
                    $value->perfil_cancelar = 0;
                    $disabled_cancelar = 'disabled';
                }


                $checked_cancelado = '';
                if (($value->reporterevisiones_cancelado + 0) == 1) {
                    $checked_cancelado = 'checked';
                }

                $value->checkbox_cancelado = '<div class="switch" data-toggle="tooltip" title="Solo Administradores">
                                                    <label>
                                                        <input type="checkbox" class="checkbox_cancelado" ' . $checked_cancelado . ' ' . $disabled_cancelar . ' onclick="reporte_cancelado(' . $value->id . ', ' . $value->perfil_cancelar . ', this)">
                                                        <span class="lever switch-col-red"></span>
                                                    </label>
                                                </div>';


                $value->nombre_cancelado = $value->reporterevisiones_canceladonombre . '<br>' . $value->reporterevisiones_canceladofecha;


                if (($value->reporterevisiones_concluido + 0) == 0 && ($value->reporterevisiones_cancelado + 0) == 0) {
                    $value->estado_texto = '<span class="text-info">Disponible para edición</span>';
                } else if (($value->reporterevisiones_cancelado + 0) == 1) {
                    $value->estado_texto = '<span class="text-danger">cancelado</span>: ' . $value->reporterevisiones_canceladoobservacion;
                } else {
                    $value->estado_texto = '<span class="text-info">Concluido</span>: No disponible para edición';
                }


                // Boton descarga informe WORD
                if (($value->reporterevisiones_concluido + 0) == 1 || ($value->reporterevisiones_cancelado + 0) == 1) {
                    $value->boton_descargar = '<button type="button" class="btn btn-success waves-effect btn-circle botondescarga" id="botondescarga_' . $key . '"><i class="fa fa-download fa-1x"></i></button>';
                } else {
                    $value->boton_descargar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="Para descargar esta revisión del informe, primero debe estar concluido ó cancelado."><i class="fa fa-ban fa-1x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $revisiones;
            $dato['total'] = count($revisiones);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['ultimaversion_cancelada'] = 0;
            $dato['ultimaversion_estado'] = 0;
            $dato['ultimarevision_id'] = 0;
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    public function reportealimentostablarecomendaciones($proyecto_id, $reportealimentos_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

            $tabla = collect(DB::select('SELECT
                                            TABLA.id,
                                            TABLA.agente_id,
                                            TABLA.agente_nombre,
                                            TABLA.recomendaciones_tipo,
                                            TABLA.recomendaciones_descripcion,
                                            TABLA.checked
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        CATALOGO.id,
                                                        CATALOGO.agente_id,
                                                        CATALOGO.agente_nombre,
                                                        CATALOGO.recomendaciones_tipo,
                                                        IF(CATALOGO.recomendaciones_descripcion != "", CATALOGO.recomendaciones_descripcion, CATALOGO.recomendacionescatalogo_descripcion) AS recomendaciones_descripcion,
                                                        IF(CATALOGO.recomendaciones_descripcion != "", "checked", "") AS checked
                                                    FROM
                                                        (
                                                            SELECT
                                                                reporterecomendacionescatalogo.id,
                                                                reporterecomendacionescatalogo.agente_id,
                                                                reporterecomendacionescatalogo.agente_nombre,
                                                                reporterecomendacionescatalogo.reporterecomendacionescatalogo_tipo AS recomendaciones_tipo,
                                                                reporterecomendacionescatalogo.reporterecomendacionescatalogo_descripcion AS recomendacionescatalogo_descripcion,
                                                                IFNULL((
                                                                        SELECT
                                                                            reporterecomendaciones.reporterecomendaciones_descripcion
                                                                        FROM
                                                                            reporterecomendaciones 
                                                                        WHERE
                                                                            reporterecomendaciones.proyecto_id = ' . $proyecto_id . '
                                                                            AND reporterecomendaciones.registro_id = ' . $reportealimentos_id . '
                                                                            AND reporterecomendaciones.reporterecomendacionescatalogo_id = reporterecomendacionescatalogo.id
                                                                        LIMIT 1 
                                                                ), NULL) AS recomendaciones_descripcion
                                                            FROM
                                                                reporterecomendacionescatalogo
                                                            WHERE
                                                                reporterecomendacionescatalogo.agente_nombre = "' . $agente_nombre . '"
                                                                AND reporterecomendacionescatalogo.reporterecomendacionescatalogo_activo = 1
                                                            ORDER BY
                                                                reporterecomendacionescatalogo.reporterecomendacionescatalogo_tipo DESC
                                                        ) AS CATALOGO
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        0 AS id,
                                                        reporterecomendaciones.agente_id,
                                                        reporterecomendaciones.agente_nombre,
                                                        reporterecomendaciones.reporterecomendaciones_tipo AS recomendaciones_tipo,
                                                        reporterecomendaciones.reporterecomendaciones_descripcion AS recomendaciones_descripcion,
                                                        "checked" AS checked
                                                    FROM
                                                        reporterecomendaciones
                                                    WHERE
                                                        reporterecomendaciones.proyecto_id = ' . $proyecto_id . '
                                                        AND reporterecomendaciones.agente_nombre = "' . $agente_nombre . '"
                                                        AND reporterecomendaciones.registro_id = ' . $reportealimentos_id . '
                                                        AND reporterecomendaciones.reporterecomendacionescatalogo_id = 0
                                                    ORDER BY
                                                        reporterecomendaciones.id ASC
                                                )
                                            ) AS TABLA'));

            $numero_registro = 0;
            $total = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if (($value->id + 0) > 0) {
                    $required_readonly = 'readonly';
                    if ($value->checked) {
                        $required_readonly = 'required';
                    }

                    $value->checkbox = '<div class="switch">
                                            <label>
                                                <input type="checkbox" class="recomendacion_checkbox" name="recomendacion_checkbox[]" value="' . $value->id . '" ' . $value->checked . ' onclick="activa_recomendacion(this);">
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>';

                    $value->descripcion = '<input type="hidden" class="form-control" name="recomendacion_tipo_' . $value->id . '" value="' . $value->recomendaciones_tipo . '" required>
                                            <label>' . $value->recomendaciones_tipo . '</label>
                                            <textarea  class="form-control" rows="5" id="recomendacion_descripcion_' . $value->id . '" name="recomendacion_descripcion_' . $value->id . '" ' . $required_readonly . '>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>';
                } else {
                    $value->checkbox = '<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>
                                        <button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-1x"></i></button>';

                    $preventiva = "";
                    $correctiva = "";
                    if ($value->recomendaciones_tipo == "Preventiva") {
                        $preventiva = "selected";
                    } else {
                        $correctiva = "selected";
                    }

                    $value->descripcion = '<div class="form-group">
                                                <label>Tipo</label>
                                                <select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>
                                                    <option value=""></option>
                                                    <option value="Preventiva" ' . $preventiva . '>Preventiva</option>
                                                    <option value="Correctiva" ' . $correctiva . '>Correctiva</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea  class="form-control" rows="5" name="recomendacionadicional_descripcion[]" required>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>
                                            </div>';
                }

                if ($value->checked) {
                    $total += 1;
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato['total'] = $total;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
    

    public function store(Request $request)
    {
        try {
            // TABLAS
            //============================================================

            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reportealimentos_id + 0) > 0) {
                $reportealimentos = reportealimentosModel::findOrFail($request->reportealimentos_id);


                $reportealimentos->update([
                    'reportealimentos_instalacion' => $request->reportealimentos_instalacion
                ]);


                $dato["reportealimentos_id"] = $reportealimentos->id;


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_id', $request->agente_id)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                if (count($revision) > 0) {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 70) // Valida disponibilidad de esta version
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reportealimentos AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reportealimentos = reportealimentosModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportealimentos_revision' => 0,
                    'reportealimentos_instalacion' => $request->reportealimentos_instalacion,
                    'reportealimentos_catregion_activo' => 1,
                    'reportealimentos_catsubdireccion_activo' => 1,
                    'reportealimentos_catgerencia_activo' => 1,
                    'reportealimentos_catactivo_activo' => 1,
                    'reportealimentos_concluido' => 0,
                    'reportealimentos_cancelado' => 0
                ]);


                //--------------------------------------
                //== VERIFICAR SI LO QUITO O LO DEJO PARA PODER CREAR LAS TABLAS

                // // Asignar categorias de este proyecto a este registro
                // DB::statement('UPDATE 
                //                     reportealimentoscategoria
                //                 SET 
                //                     registro_id = ' . $reportealimentos->id . '
                //                 WHERE 
                //                     proyecto_id = ' . $request->proyecto_id . '
                //                     AND IFNULL(registro_id, "") = "";');


                // // Asignar Areas de este proyecto a este registro
                // DB::statement('UPDATE 
                //                     reportealimentosarea
                //                 SET 
                //                     registro_id = ' . $reportealimentos->id . '
                //                 WHERE 
                //                     proyecto_id = ' . $request->proyecto_id . '
                //                     AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            // PORTADA
            if (($request->opcion + 0) == 0) {
                // REGION
                $catregion_activo = 0;
                if ($request->reportealimentos_catregion_activo != NULL) {
                    $catregion_activo = 1;
                }

                // SUBDIRECCION
                $catsubdireccion_activo = 0;
                if ($request->reportealimentos_catsubdireccion_activo != NULL) {
                    $catsubdireccion_activo = 1;
                }

                // GERENCIA
                $catgerencia_activo = 0;
                if ($request->reportealimentos_catgerencia_activo != NULL) {
                    $catgerencia_activo = 1;
                }

                // ACTIVO
                $catactivo_activo = 0;
                if ($request->reportealimentos_catactivo_activo != NULL) {
                    $catactivo_activo = 1;
                }

                $reportealimentos->update([
                    'reportealimentos_catregion_activo' => $catregion_activo,
                    'reportealimentos_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reportealimentos_catgerencia_activo' => $catgerencia_activo,
                    'reportealimentos_catactivo_activo' => $catactivo_activo,
                    'reportealimentos_instalacion' => $request->reportealimentos_instalacion,
                    'reportealimentos_fecha' => $request->reportealimentos_fecha,
                    'reportealimentos_mes' => $request->reportealimentos_mes
                ]);


                if (count($proyectoRecursos) == 0) {

                    $recusros = recursosPortadasInformesModel::create([
                        'PROYECTO_ID' => $request->proyecto_id,
                        'AGENTE_ID' => $request->agente_id,
                        'NORMA_ID' => 0,
                        'NIVEL1' => is_null($request->NIVEL1) ? null : $request->NIVEL1,
                        'NIVEL2' => is_null($request->NIVEL2) ? null : $request->NIVEL2,
                        'NIVEL3' => is_null($request->NIVEL3) ? null : $request->NIVEL3,
                        'NIVEL4' => is_null($request->NIVEL4) ? null : $request->NIVEL4,
                        'NIVEL5' => is_null($request->NIVEL5) ? null : $request->NIVEL5,
                        'OPCION_PORTADA1' => is_null($request->OPCION_PORTADA1) ? null : $request->OPCION_PORTADA1,
                        'OPCION_PORTADA2' => is_null($request->OPCION_PORTADA2) ? null : $request->OPCION_PORTADA2,
                        'OPCION_PORTADA3' => is_null($request->OPCION_PORTADA3) ? null : $request->OPCION_PORTADA3,
                        'OPCION_PORTADA4' => is_null($request->OPCION_PORTADA4) ? null : $request->OPCION_PORTADA4,
                        'OPCION_PORTADA5' => is_null($request->OPCION_PORTADA5) ? null : $request->OPCION_PORTADA5,
                        'OPCION_PORTADA6' => is_null($request->OPCION_PORTADA6) ? null : $request->OPCION_PORTADA6
                    ]);

                    if ($request->file('PORTADA')) {
                        // Eliminar IMG anterior
                        if (Storage::exists($recusros->RUTA_IMAGEN_PORTADA)) {
                            Storage::delete($recusros->RUTA_IMAGEN_PORTADA);
                        }

                        $extension = $request->file('PORTADA')->getClientOriginalExtension();
                        $imgGuardada = $request->file('PORTADA')->storeAs('reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reporteregistro_id . '/imagenPortada', 'PORTADA_IAMGEN.' . $extension);

                        $recusros->update(['RUTA_IMAGEN_PORTADA' => $imgGuardada]);
                    }
                } else {

                    foreach ($proyectoRecursos as $recurso) {
                        $recurso->update([
                            'NORMA_ID' => 0,
                            'NIVEL1' => is_null($request->NIVEL1) ? null : $request->NIVEL1,
                            'NIVEL2' => is_null($request->NIVEL2) ? null : $request->NIVEL2,
                            'NIVEL3' => is_null($request->NIVEL3) ? null : $request->NIVEL3,
                            'NIVEL4' => is_null($request->NIVEL4) ? null : $request->NIVEL4,
                            'NIVEL5' => is_null($request->NIVEL5) ? null : $request->NIVEL5,
                            'OPCION_PORTADA1' => is_null($request->OPCION_PORTADA1) ? null : $request->OPCION_PORTADA1,
                            'OPCION_PORTADA2' => is_null($request->OPCION_PORTADA2) ? null : $request->OPCION_PORTADA2,
                            'OPCION_PORTADA3' => is_null($request->OPCION_PORTADA3) ? null : $request->OPCION_PORTADA3,
                            'OPCION_PORTADA4' => is_null($request->OPCION_PORTADA4) ? null : $request->OPCION_PORTADA4,
                            'OPCION_PORTADA5' => is_null($request->OPCION_PORTADA5) ? null : $request->OPCION_PORTADA5,
                            'OPCION_PORTADA6' => is_null($request->OPCION_PORTADA6) ? null : $request->OPCION_PORTADA6
                        ]);
                    }

                    foreach ($proyectoRecursos as $recurso) {
                        if ($request->file('PORTADA')) {
                            // Eliminar IMG anterior
                            if (Storage::exists($recurso->RUTA_IMAGEN_PORTADA)) {
                                Storage::delete($recurso->RUTA_IMAGEN_PORTADA);
                            }

                            $extension = $request->file('PORTADA')->getClientOriginalExtension();
                            $imgGuardada = $request->file('PORTADA')->storeAs(
                                'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reporteregistro_id . '/imagenPortada',
                                'PORTADA_IMAGEN.' . $extension
                            );

                            $recurso->update(['RUTA_IMAGEN_PORTADA' => $imgGuardada]);
                        }
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INTRODUCCION
            if (($request->opcion + 0) == 1) {
                // dd($request->reportealimentos_introduccion);

                $reportealimentos->update([
                    'reportealimentos_introduccion' =>  $request->reportealimentos_introduccion,
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // DEFINICIONES
            if (($request->opcion + 0) == 2) {
                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }


                if (($request->reportedefiniciones_id + 0) == 0) //NUEVO
                {
                    DB::statement('ALTER TABLE reportedefiniciones AUTO_INCREMENT = 1;');

                    $definicion = reportedefinicionesModel::create([
                        'agente_id' => $request->agente_id,
                        'agente_nombre' => $request->agente_nombre,
                        'catactivo_id' => $request->catactivo_id,
                        'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto,
                        'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion,
                        'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else //EDITAR
                {
                    $definicion = reportedefinicionesModel::findOrFail($request->reportedefiniciones_id);

                    $definicion->update([
                        'catactivo_id' => $request->catactivo_id,
                        'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto,
                        'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion,
                        'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // OBJETIVO GENERAL
            if (($request->opcion + 0) == 3) {
                $reportealimentos->update([
                    'reportealimentos_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reportealimentos->update([
                    'reportealimentos_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reportealimentos->update([
                    'reportealimentos_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion + 0) == 6) {
                $reportealimentos->update([
                    'reportealimentos_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 5.1
            if (($request->opcion + 0) == 7) {
                $reportealimentos->update([
                    'reportealimentos_metodologia_5_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_metodologia_5_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 5.2
            if (($request->opcion + 0) == 8) {
                $reportealimentos->update([
                    'reportealimentos_metodologia_5_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_metodologia_5_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }



            // UBICACION
            if (($request->opcion + 0) == 11) {
                $reportealimentos->update([
                    'reportealimentos_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reportealimentosubicacionfoto')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportealimentos->id . '/ubicacionfoto/ubicacionfoto.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportealimentos->update([
                        'reportealimentos_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 12) {
                $reportealimentos->update([
                    'reportealimentos_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_procesoinstalacion),
                    'reportealimentos_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion + 0) == 13) {
                // dd($request->all());


                if (($request->categorias_poe + 0) == 1) {
                    $categoria = reportecategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportecategoria_id + 0) == 0) {
                        DB::statement('ALTER TABLE reportealimentoscategoria AUTO_INCREMENT = 1;');


                        // $request['registro_id'] = $reportealimentos->id;
                        // $request['recsensorialcategoria_id'] = 0;
                        // $categoria = reportealimentoscategoriaModel::create($request->all());

                        $categoria = reportealimentoscategoriaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportealimentos->id,
                            'recsensorialcategoria_id' => 0,
                            'reportealimentoscategoria_nombre' => $request->reportecategoria_nombre,
                            'reportealimentoscategoria_total' => $request->reportecategoria_total,
                            'reportealimentoscategoria_horas' => $request->reportecategoria_horas
                        ]);


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $categoria = reportealimentoscategoriaModel::findOrFail($request->reportecategoria_id);
                        // $categoria->update($request->all());

                        $categoria->update([
                            'registro_id' => $reportealimentos->id,
                            'reportealimentoscategoria_nombre' => $request->reportecategoria_nombre,
                            'reportealimentoscategoria_total' => $request->reportecategoria_total,
                            'reportealimentoscategoria_horas' => $request->reportecategoria_horas
                        ]);


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // AREAS
            if (($request->opcion + 0) == 14) {
                // dd($request->all());


                if (($request->areas_poe + 0) == 1) {
                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reportealimentosareacategoriaModel::where('reportealimentosarea_id', $request->reportearea_id)
                        ->where('reportealimentosareacategoria_poe', $reportealimentos->id)
                        ->delete();


                    if ($request->checkbox_reportecategoria_id) {
                        DB::statement('ALTER TABLE reportealimentosareacategoria AUTO_INCREMENT = 1;');


                        foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                            $areacategoria = reportealimentosareacategoriaModel::create([
                                'reportealimentosarea_id' => $area->id,
                                'reportealimentoscategoria_id' => $value,
                                'reportealimentosareacategoria_poe' => $reportealimentos->id,
                                'reportealimentosareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                'reportealimentosareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                'reportealimentosareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                'reportealimentosareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
                                'niveles_minimo' => $request['niveles_minimo_' . $value]

                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reportealimentosarea AUTO_INCREMENT = 1;');

                        // $request['registro_id'] = $reportealimentos->id;
                        // $request['recsensorialarea_id'] = 0;
                        // $area = reportealimentosareaModel::create($request->all());

                        $area = reportealimentosareaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportealimentos->id,
                            'recsensorialarea_id' => 0,
                            'reportealimentosarea_numorden' => $request->reportearea_orden,
                            'reportealimentosarea_nombre' => $request->reportearea_nombre,
                            'reportealimentosarea_instalacion' => $request->reportearea_instalacion,
                            'reportealimentosarea_puntos_ic' => $request->reportearea_puntos_ic,
                            'reportealimentosarea_puntos_pt' => $request->reportearea_puntos_pt,
                            'reportealimentosarea_sistemaalimentos' => $request->reportearea_sistemaalimentos,
                            'reportealimentosarea_luznatural' => $request->reportearea_luznatural,
                            'reportealimentosarea_alimentoslocalizada' => $request->reportearea_alimentoslocalizada,
                            'reportealimentosarea_porcientooperacion' => $request->reportealimentosarea_porcientooperacion,
                            'reportealimentosarea_colorsuperficie' => $request->reportearea_colorsuperficie,
                            'reportealimentosarea_tiposuperficie' => $request->reportearea_tiposuperficie,
                            'reportealimentosarea_largo' => $request->reportearea_largo,
                            'reportealimentosarea_ancho' => $request->reportearea_ancho,
                            'reportealimentosarea_alto' => $request->reportearea_alto,
                            'reportearea_criterio ' => $request->reportearea_criterio,
                            'reportearea_colortecho' => $request->reportearea_colortecho,
                            'reportearea_paredes' => $request->reportearea_paredes,
                            'reportearea_colorpiso' => $request->reportearea_colorpiso,
                            'reportearea_superficietecho' => $request->reportearea_superficietecho,
                            'reportearea_superficieparedes' => $request->reportearea_superficieparedes,
                            'reportearea_superficiepiso' => $request->reportearea_superficiepiso,
                            'reportearea_potenciaslamparas' => $request->reportearea_potenciaslamparas,
                            'reportearea_numlamparas' => $request->reportearea_numlamparas,
                            'reportearea_alturalamparas' => $request->reportearea_alturalamparas,
                            'reportearea_programamantenimiento' => $request->reportearea_programamantenimiento,
                            'reportearea_tipoalimentos' => $request->reportearea_tipoalimentos,
                            'reportearea_descripcion' => $request->reportearea_descripcion


                        ]);


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reportealimentosareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reportealimentosareacategoriaModel::create([
                                    'reportealimentosarea_id' => $area->id,
                                    'reportealimentoscategoria_id' => $value,
                                    'reportealimentosareacategoria_poe' => 0,
                                    'reportealimentosareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reportealimentosareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reportealimentosareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                    'reportealimentosareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
                                    'niveles_minimo' => $request['niveles_minimo_' . $value]


                                ]);
                            }
                        }

                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        // $request['registro_id'] = $reportealimentos->id;
                        $area = reportealimentosareaModel::findOrFail($request->reportearea_id);
                        // $area->update($request->all());
                        $area->update([
                            'registro_id' => $reportealimentos->id,
                            'reportealimentosarea_numorden' => $request->reportearea_orden,
                            'reportealimentosarea_nombre' => $request->reportearea_nombre,
                            'reportealimentosarea_instalacion' => $request->reportearea_instalacion,
                            'reportealimentosarea_puntos_ic' => $request->reportearea_puntos_ic,
                            'reportealimentosarea_puntos_pt' => $request->reportearea_puntos_pt,
                            'reportealimentosarea_sistemaalimentos' => $request->reportearea_sistemaalimentos,
                            'reportealimentosarea_luznatural' => $request->reportearea_luznatural,
                            'reportealimentosarea_alimentoslocalizada' => $request->reportearea_alimentoslocalizada,
                            'reportealimentosarea_porcientooperacion' => $request->reportealimentosarea_porcientooperacion,
                            'reportealimentosarea_colorsuperficie' => $request->reportearea_colorsuperficie,
                            'reportealimentosarea_tiposuperficie' => $request->reportearea_tiposuperficie,
                            'reportealimentosarea_largo' => $request->reportearea_largo,
                            'reportealimentosarea_ancho' => $request->reportearea_ancho,
                            'reportealimentosarea_alto' => $request->reportearea_alto,
                            'reportealimentosarea_criterio ' => $request->reportearea_criterio,
                            'reportealimentosarea_colortecho' => $request->reportearea_colortecho,
                            'reportealimentosarea_paredes' => $request->reportearea_paredes,
                            'reportealimentosarea_colorpiso' => $request->reportearea_colorpiso,
                            'reportealimentosarea_superficietecho' => $request->reportearea_superficietecho,
                            'reportealimentosarea_superficieparedes' => $request->reportearea_superficieparedes,
                            'reportealimentosarea_superficiepiso' => $request->reportearea_superficiepiso,
                            'reportealimentosarea_potenciaslamparas' => $request->reportearea_potenciaslamparas,
                            'reportealimentosarea_numlamparas' => $request->reportearea_numlamparas,
                            'reportealimentosarea_alturalamparas' => $request->reportearea_alturalamparas,
                            'reportealimentosarea_programamantenimiento' => $request->reportearea_programamantenimiento,
                            'reportealimentosarea_tipoalimentos' => $request->reportearea_tipoalimentos,
                            'reportealimentosarea_descripcion' => $request->reportearea_descripcion
                        ]);


                        $eliminar_categorias = reportealimentosareacategoriaModel::where('reportealimentosarea_id', $request->reportearea_id)
                            ->where('reportealimentosareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reportealimentosareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reportealimentosareacategoriaModel::create([
                                    'reportealimentosarea_id' => $area->id,
                                    'reportealimentoscategoria_id' => $value,
                                    'reportealimentosareacategoria_poe' => 0,
                                    'reportealimentosareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reportealimentosareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reportealimentosareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                    'reportealimentosareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
                                    'niveles_minimo' => $request['niveles_minimo_' . $value]

                                ]);
                            }
                        }

                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // CRITERIO SELECCION
            if (($request->opcion + 0) == 15) {
                $reportealimentos->update([
                    'reportealimentos_criterioseleccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_criterioseleccion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PUNTOS DE alimentos
            if (($request->opcion + 0) == 16) {
                if ($request->reportealimentospuntos_luxmed1menor != NULL) {
                    $request['reportealimentospuntos_luxmed1menor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed1menor'] = 0;
                }


                if ($request->reportealimentospuntos_luxmed2menor != NULL) {
                    $request['reportealimentospuntos_luxmed2menor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed2menor'] = 0;
                }


                if ($request->reportealimentospuntos_luxmed3menor != NULL) {
                    $request['reportealimentospuntos_luxmed3menor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed3menor'] = 0;
                }



                if ($request->reportealimentospuntos_luxmed1mayor != NULL) {
                    $request['reportealimentospuntos_luxmed1mayor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed1mayor'] = 0;
                }


                if ($request->reportealimentospuntos_luxmed2mayor != NULL) {
                    $request['reportealimentospuntos_luxmed2mayor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed2mayor'] = 0;
                }


                if ($request->reportealimentospuntos_luxmed3mayor != NULL) {
                    $request['reportealimentospuntos_luxmed3mayor'] = 1;
                } else {
                    $request['reportealimentospuntos_luxmed3mayor'] = 0;
                }


                $request['registro_id'] = $reportealimentos->id;
                if (($request->reportealimentospunto_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportealimentospuntos AUTO_INCREMENT = 1;');

                    $punto = reportealimentospuntosModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $punto = reportealimentospuntosModel::findOrFail($request->reportealimentospunto_id);
                    $punto->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // CONCLUSION
            if (($request->opcion + 0) == 20) {
                $reportealimentos->update([
                    'reportealimentos_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportealimentos_conclusion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion + 0) == 30) {
                if ($request->recomendacion_checkbox) {
                    $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                        ->where('catactivo_id', $request->catactivo_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reportealimentos->id)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportealimentos->id,
                            'catactivo_id' => $request->catactivo_id,
                            'reporterecomendacionescatalogo_id' => $value,
                            'reporterecomendaciones_tipo' => $request['recomendacion_tipo_' . $value],
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request['recomendacion_descripcion_' . $value])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                if ($request->recomendacionadicional_checkbox) {
                    if (!$request->recomendacion_checkbox) {
                        $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                            ->where('catactivo_id', $request->catactivo_id)
                            ->where('agente_nombre', $request->agente_nombre)
                            ->where('registro_id', $reportealimentos->id)
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportealimentos->id,
                            'catactivo_id' => $request->catactivo_id,
                            'reporterecomendacionescatalogo_id' => 0,
                            'reporterecomendaciones_tipo' => $request->recomendacionadicional_tipo[$key],
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->recomendacionadicional_descripcion[$key])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion + 0) == 40) {
                $reportealimentos->update([
                    'reportealimentos_responsable1' => $request->reportealimentos_responsable1,
                    'reportealimentos_responsable1cargo' => $request->reportealimentos_responsable1cargo,
                    'reportealimentos_responsable2' => $request->reportealimentos_responsable2,
                    'reportealimentos_responsable2cargo' => $request->reportealimentos_responsable2cargo
                ]);


                if ($request->reportealimentos_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportealimentos->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->reportealimentos_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reportealimentos->update([
                        'reportealimentos_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportealimentos_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reportealimentosresponsable1documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reportealimentos_responsable1documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportealimentos->id . '/responsables informe/responsable1_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportealimentos->update([
                        'reportealimentos_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reportealimentosresponsable2documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reportealimentos_responsable2documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportealimentos->id . '/responsables informe/responsable2_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportealimentos->update([
                        'reportealimentos_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 45) {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reportealimentos->id)
                    ->delete();

                if ($request->reportealimentos_checkboxplanocarpeta) {
                    DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reportealimentos_checkboxplanocarpeta as $key => $value) {
                        $anexo = reporteplanoscarpetasModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportealimentos->id,
                            'reporteplanoscarpetas_nombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $value)
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // EQUIPO UTILIZADO
            if (($request->opcion + 0) == 50) {
                // dd($request->all());

                if ($request->reportealimentos_equipoutilizadocheckbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reportealimentos->id)
                        ->delete();


                    DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');


                    foreach ($request->reportealimentos_equipoutilizadocheckbox as $key => $value) {
                        if ($request['equipoutilizado_checkboxcarta_' . $value]) {
                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        } else {
                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportealimentos->id,
                            'equipo_id' => $value,
                            'reporteequiposutilizados_cartacalibracion' => $request->reporteequiposutilizados_cartacalibracion
                        ]);
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion + 0) == 55) {
                $eliminar_informes = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reportealimentos->id)
                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                    ->delete();

                if ($request->reportealimentos_informeresultadocheckbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reportealimentos_informeresultadocheckbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportealimentos->id,
                            'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            ,
                            'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['reportealimentos_anexonombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['reportealimentos_anexoarchivo_' . $value]
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // ANEXOS 7 STPS y 8 EMA
            if (($request->opcion + 0) == 60) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reportealimentos->id)
                    ->where('reporteanexos_tipo', 2) // ANEXOS TIPO STPS Y EMA
                    ->delete();

                if ($request->reportealimentos_anexocheckbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reportealimentos_anexocheckbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportealimentos->id,
                            'reporteanexos_tipo' => 2  // ANEXOS TIPO STPS Y EMA
                            ,
                            'reporteanexos_anexonombre' => ($key + 1) . '.- ' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['reportealimentos_anexonombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['reportealimentos_anexoarchivo_' . $value]
                        ]);

                        $dato["total"] += 1;
                    }
                } else {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // REVISION INFORME, CANCELACION
            if (($request->opcion + 0) == 70) {
                $revision = reporterevisionesModel::findOrFail($request->reporterevisiones_id);


                $cancelado = 0;
                $canceladonombre = NULL;
                $canceladofecha = NULL;
                $canceladoobservacion = NULL;


                if ($revision->reporterevisiones_cancelado == 0) {
                    $cancelado = 1;
                    $canceladonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                    $canceladofecha = date('Y-m-d H:i:s');
                    $canceladoobservacion = $request->reporte_canceladoobservacion;
                }


                $revision->update([
                    'reporterevisiones_cancelado' => $cancelado,
                    'reporterevisiones_canceladonombre' => $canceladonombre,
                    'reporterevisiones_canceladofecha' => $canceladofecha,
                    'reporterevisiones_canceladoobservacion' => $canceladoobservacion
                ]);


                $dato["estado"] = 0;
                if ($revision->reporterevisiones_concluido == 1 || $cancelado == 1) {
                    $dato["estado"] = 1;
                }


                $dato["msj"] = 'Datos modificados correctamente';
            }


            // respuesta
            $dato["reportealimentos_id"] = $reportealimentos->id;
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    
}
