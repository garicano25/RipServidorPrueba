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
use App\modelos\reportes\reportebeicatalogoModel;
use App\modelos\reportes\reportebeiModel;
use App\modelos\reportes\reportebeiareaModel;
use App\modelos\reportes\reportebeicategoriaModel;
use App\modelos\reportes\reportebeiareacategoriaModel;
use App\modelos\reportes\recursosPortadasInformesModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reportebeiepp;
use App\modelos\reportes\puntosBeiInformeModel;
use App\modelos\recsensorial\catConclusionesModel;


class reporteBeiController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('asignacionUser:INFORMES')->only('store');
    }

    public function reportebeivista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de BEI primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION EN CASO DE QUE NO EXISTA
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 22) // BEIS
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 22,
                    'agente_nombre' => 'BEI',
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


            //=================== INSERTAMOS LOS PUNTOS EVALUADOS DE BEI ========================================== 
            DB::select('CALL sp_insertar_actualizar_puntos_bei_g(?,?)', [$proyecto_id, $proyecto->recsensorial_id]);


            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();

            // Vista
            return view('reportes.parametros.reportebei', compact('proyecto', 'recsensorial', 'categorias_poe', 'areas_poe', 'catConclusiones'));
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
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Transformación Industrial', $texto);
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

            $texto = str_replace('PEMEX Transformación Industrial', $recsensorial->recsensorial_empresa, $texto);
        }

        return $texto;
    }

    public function reportebeidatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportebeicatalogo = reportebeicatalogoModel::limit(1)->get();

            $reportebei  = reportebeiModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reportebei_revision', 'DESC')
                ->limit(1)
                ->get();

            if (count($reportebei) == 0) {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reportebei = reportebeiModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('proyecto_id', 'DESC')
                        ->orderBy('reportebei_revision', 'DESC')
                        // ->orderBy('updated_at', 'DESC')
                        ->limit(1)
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reportebei.id,
                                                reportebei.proyecto_id,
                                                reportebei.agente_id,
                                                reportebei.agente_nombre,
                                                reportebei.catactivo_id,
                                                reportebei.reportebei_revision,
                                                reportebei.reportebei_fecha,
                                                reportebei.reportebei_mes,
                                                reportebei.reportebei_instalacion,
                                                reportebei.reportebei_catregion_activo,
                                                reportebei.reportebei_catsubdireccion_activo,
                                                reportebei.reportebei_catgerencia_activo,
                                                reportebei.reportebei_catactivo_activo,
                                                reportebei.reportebei_introduccion,
                                                reportebei.reportebei_objetivogeneral,
                                                reportebei.reportebei_objetivoespecifico,
                                                reportebei.reportebei_metodologia_4_1,
                                                reportebei.reportebei_metodologia_4_2,
                                                reportebei.reportebei_metodologia_4_2_1,
                                                reportebei.reportebei_metodologia_4_2_2,
                                                reportebei.reportebei_metodologia_4_2_3,
                                                reportebei.reportebei_metodologia_4_2_4,
                                                reportebei.reportebei_ubicacioninstalacion,
                                                reportebei.reportebei_ubicacionfoto,
                                                reportebei.reportebei_procesoinstalacion,
                                                reportebei.reportebei_actividadprincipal,
                                                reportebei.reportebei_criterioseleccion,
                                                reportebei.reportebei_conclusion,
                                                reportebei.reportebei_responsable1,
                                                reportebei.reportebei_responsable1cargo,
                                                reportebei.reportebei_responsable1documento,
                                                reportebei.reportebei_responsable2,
                                                reportebei.reportebei_responsable2cargo,
                                                reportebei.reportebei_responsable2documento,
                                                reportebei.reportebei_concluido,
                                                reportebei.reportebei_concluidonombre,
                                                reportebei.reportebei_concluidofecha,
                                                reportebei.reportebei_cancelado,
                                                reportebei.reportebei_canceladonombre,
                                                reportebei.reportebei_canceladofecha,
                                                reportebei.reportebei_canceladoobservacion,
                                                reportebei.created_at,
                                                reportebei.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportebei ON proyecto.id = reportebei.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . ' 
                                                AND reportebei.reportebei_instalacion <> "" 
                                            ORDER BY
                                                reportebei.updated_at DESC');
                }


                $dato['reportebei_id'] = 0;


                if (count($reportebei) == 0) {
                    $reportebei = array(0, 0);
                    $dato['reportebei_id'] = -1;
                }
            } else {
                $dato['reportebei_id'] = $reportebei[0]->id;
            }


            $reportebei = $reportebei[0];


            //------------------------------


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 4) //Iluminación
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) > 0) {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reportebei_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reportebei_cancelado'] = $revision->reporterevisiones_cancelado;
            } else {
                $dato['reportebei_concluido'] = 0;
                $dato['reportebei_cancelado'] = 0;
            }


            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);






            // PORTADA
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_fecha != NULL && $reportebei->proyecto_id == $proyecto_id) {
                $reportefecha = $reportebei->reportebei_fecha;

                $dato['reportebei_portada_guardado'] = 1;
            } else {
                $reportefecha = $meses[$proyectofecha[1] + 0] . " del " . $proyectofecha[0];

                $dato['reportebei_portada_guardado'] = 0;
            }


            if ($dato['reportebei_id'] >= 0) {
                $dato['reportebei_portada'] = array(
                    'reportebei_catregion_activo' => $reportebei->reportebei_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reportebei_catsubdireccion_activo' => $reportebei->reportebei_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reportebei_catgerencia_activo' => $reportebei->reportebei_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reportebei_catactivo_activo' => $reportebei->reportebei_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reportebei_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reportebei_fecha' => $reportefecha,
                    'reportebei_mes' => $reportebei->reportebei_mes


                );
            } else {
                $dato['reportebei_portada'] = array(
                    'reportebei_catregion_activo' => 1,
                    'catregion_id' => $proyecto->catregion_id,
                    'reportebei_catsubdireccion_activo' => 1,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reportebei_catgerencia_activo' => 1,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reportebei_catactivo_activo' => 1,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reportebei_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reportebei_fecha' => $reportefecha,
                    'reportebei_mes' => ""

                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_introduccion != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_introduccion_guardado'] = 1;
                } else {
                    $dato['reportebei_introduccion_guardado'] = 0;
                }

                $introduccion = $reportebei->reportebei_introduccion;
            } else {
                $dato['reportebei_introduccion_guardado'] = 0;
                $introduccion = $reportebeicatalogo[0]->reportebeicatalogo_introduccion;
            }

            $dato['reportebei_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_objetivogeneral != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reportebei_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reportebei->reportebei_objetivogeneral;
            } else {
                $dato['reportebei_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportebeicatalogo[0]->reportebeicatalogo_objetivogeneral;
            }

            $dato['reportebei_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_objetivoespecifico != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reportebei_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reportebei->reportebei_objetivoespecifico;
            } else {
                $dato['reportebei_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportebeicatalogo[0]->reportebeicatalogo_objetivoespecifico;
            }

            $dato['reportebei_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_1 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reportebei->reportebei_metodologia_4_1;
            } else {
                $dato['reportebei_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_1;
            }

            $dato['reportebei_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_2 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_2_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reportebei->reportebei_metodologia_4_2;
            } else {
                $dato['reportebei_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_2;
            }

            $dato['reportebei_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);


            // METODOLOGIA PUNTO 4.2.1
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_2_1 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_2_1_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_2_1_guardado'] = 0;
                }

                $metodologia_4_2_1 = $reportebei->reportebei_metodologia_4_2_1;
            } else {
                $dato['reportebei_metodologia_4_2_1_guardado'] = 0;
                $metodologia_4_2_1 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_2_1;
            }

            $dato['reportebei_metodologia_4_2_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_1);


            // METODOLOGIA PUNTO 4.2.2
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_2_2 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_2_2_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_2_2_guardado'] = 0;
                }

                $metodologia_4_2_2 = $reportebei->reportebei_metodologia_4_2_2;
            } else {
                $dato['reportebei_metodologia_4_2_2_guardado'] = 0;
                $metodologia_4_2_2 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_2_2;
            }

            $dato['reportebei_metodologia_4_2_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_2);


            // METODOLOGIA PUNTO 4.2.3
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_2_3 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_2_3_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_2_3_guardado'] = 0;
                }

                $metodologia_4_2_3 = $reportebei->reportebei_metodologia_4_2_3;
            } else {
                $dato['reportebei_metodologia_4_2_3_guardado'] = 0;
                $metodologia_4_2_3 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_2_3;
            }

            $dato['reportebei_metodologia_4_2_3'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_3);


            // METODOLOGIA PUNTO 4.2.4
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_metodologia_4_2_4 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_metodologia_4_2_4_guardado'] = 1;
                } else {
                    $dato['reportebei_metodologia_4_2_4_guardado'] = 0;
                }

                $metodologia_4_2_4 = $reportebei->reportebei_metodologia_4_2_4;
            } else {
                $dato['reportebei_metodologia_4_2_4_guardado'] = 0;
                $metodologia_4_2_4 = $reportebeicatalogo[0]->reportebeicatalogo_metodologia_4_2_4;
            }

            $dato['reportebei_metodologia_4_2_4'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_4);


            // UBICACION
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_ubicacioninstalacion != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reportebei_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reportebei->reportebei_ubicacioninstalacion;
            } else {
                $dato['reportebei_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportebeicatalogo[0]->reportebeicatalogo_ubicacioninstalacion;
            }

            $ubicacionfoto = NULL;
            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_ubicacionfoto != NULL && $reportebei->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reportebei->reportebei_ubicacionfoto;
            }

            $dato['reportebei_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_procesoinstalacion != NULL && $reportebei->proyecto_id == $proyecto_id) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_procesoinstalacion_guardado'] = 1;
                } else {
                    $dato['reportebei_procesoinstalacion_guardado'] = 0;
                }

                $procesoinstalacion = $reportebei->reportebei_procesoinstalacion;
            } else {
                $dato['reportebei_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reportebei_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_actividadprincipal != NULL && $reportebei->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reportebei->reportebei_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reportebei_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // CRITERIO DE SELECCION
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_criterioseleccion != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_criterioseleccion_guardado'] = 1;
                } else {
                    $dato['reportebei_criterioseleccion_guardado'] = 0;
                }

                $criterioseleccion = $reportebei->reportebei_criterioseleccion;
            } else {
                $dato['reportebei_criterioseleccion_guardado'] = 0;
                $criterioseleccion = $reportebeicatalogo[0]->reportebeicatalogo_criterioseleccion;
            }

            $dato['reportebei_criterioseleccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $criterioseleccion);


            // CONCLUSION
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_conclusion != NULL && $reportebei->proyecto_id == $proyecto_id) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_conclusion_guardado'] = 1;
                } else {
                    $dato['reportebei_conclusion_guardado'] = 0;
                }

                $conclusion = $reportebei->reportebei_conclusion;
            } else {
                $dato['reportebei_conclusion_guardado'] = 0;
                $conclusion = $reportebeicatalogo[0]->reportebeicatalogo_conclusion;
            }

            $dato['reportebei_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reportebei_id'] >= 0 && $reportebei->reportebei_responsable1 != NULL) {
                if ($reportebei->proyecto_id == $proyecto_id) {
                    $dato['reportebei_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reportebei_responsablesinforme_guardado'] = 0;
                }

                $dato['reportebei_responsablesinforme'] = array(
                    'reportebei_responsable1' => $reportebei->reportebei_responsable1,
                    'reportebei_responsable1cargo' => $reportebei->reportebei_responsable1cargo,
                    'reportebei_responsable1documento' => $reportebei->reportebei_responsable1documento,
                    'reportebei_responsable2' => $reportebei->reportebei_responsable2,
                    'reportebei_responsable2cargo' => $reportebei->reportebei_responsable2cargo,
                    'reportebei_responsable2documento' => $reportebei->reportebei_responsable2documento,
                    'proyecto_id' => $reportebei->proyecto_id,
                    'registro_id' => $reportebei->id,
                    'tipo1' => 1,
                    'tipo2' => 2
                );
            } else {
                $dato['reportebei_responsablesinforme_guardado'] = 0;

                $dato['reportebei_responsablesinforme'] = array(
                    'reportebei_responsable1' => $recsensorial->recsensorial_repfisicos1nombre,
                    'reportebei_responsable1cargo' => $recsensorial->recsensorial_repfisicos1cargo,
                    'reportebei_responsable1documento' => $recsensorial->recsensorial_repfisicos1doc,
                    'reportebei_responsable2' => $recsensorial->recsensorial_repfisicos2nombre,
                    'reportebei_responsable2cargo' => $recsensorial->recsensorial_repfisicos2cargo,
                    'reportebei_responsable2documento' => $recsensorial->recsensorial_repfisicos2doc,
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
                $dato['reportebei_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            } else {
                $dato['reportebei_memoriafotografica_guardado'] = 0;
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['datoscompletos'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeitabladefiniciones($proyecto_id, $agente_nombre, $reportebei_id)
    {
        try {
           
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 4)
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
                    // $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle"><i class="fa fa-trash fa-1x"></i></button>';

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
    
    public function reportebeidefinicioneliminar($definicion_id)
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


    public function reportebeimapaubicacion($reportebei_id, $archivo_opcion)
    {
        $reportebei  = reportebeiModel::findOrFail($reportebei_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reportebei->reportebei_ubicacionfoto);
        } else {
            return Storage::download($reportebei->reportebei_ubicacionfoto);
        }
    }

    public function reportebeiresponsabledocumento($reportebei_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {

        if ($responsabledoc_tipo == 1) {

            $reportebei  = reportebeiModel::findOrFail($reportebei_id);

            if ($responsabledoc_opcion == 0) {
                return Storage::response($reportebei->reportebei_responsable1documento);
            } else {
                return Storage::download($reportebei->reportebei_responsable1documento);
            }
        } else if ($responsabledoc_tipo == 2) {

            $reportebei  = reportebeiModel::findOrFail($reportebei_id);

            if ($responsabledoc_opcion == 0) {
                return Storage::response($reportebei->reportebei_responsable2documento);
            } else {
                return Storage::download($reportebei->reportebei_responsable2documento);
            }
        } else if ($responsabledoc_tipo == 3) {

            $recsensorial  = recsensorialModel::findOrFail($reportebei_id);

            if ($responsabledoc_opcion == 0) {

                return Storage::response($recsensorial->recsensorial_repfisicos1doc);
            } else {

                return Storage::download($recsensorial->recsensorial_repfisicos1doc);
            }
        } else if ($responsabledoc_tipo == 4) {

            $recsensorial  = recsensorialModel::findOrFail($reportebei_id);

            if ($responsabledoc_opcion == 0) {

                return Storage::response($recsensorial->recsensorial_repfisicos2doc);
            } else {

                return Storage::download($recsensorial->recsensorial_repfisicos2doc);
            }
        }
    }

    public function reportebeitablarevisiones($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 22
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

    public function reportebeiconcluirrevision($revision_id)
    {
        try {
            // $reportebei  = reportebeiModel::findOrFail($revision_id);
            $revision  = reporterevisionesModel::findOrFail($revision_id);


            $concluido = 0;
            $concluidonombre = NULL;
            $concluidofecha = NULL;


            if ($revision->reporterevisiones_concluido == 0) {
                $concluido = 1;
                $concluidonombre = auth()->user()->empleado->empleado_nombre . " " . auth()->user()->empleado->empleado_apellidopaterno . " " . auth()->user()->empleado->empleado_apellidomaterno;
                $concluidofecha = date('Y-m-d H:i:s');
            }


            $revision->update([
                'reporterevisiones_concluido' => $concluido,
                'reporterevisiones_concluidonombre' => $concluidonombre,
                'reporterevisiones_concluidofecha' => $concluidofecha
            ]);


            $dato["estado"] = 0;
            if ($concluido == 1 || $revision->reporterevisiones_cancelado == 1) {
                $dato["estado"] = 1;
            }


            $dato["msj"] = 'Datos modificados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["estado"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeicategorias($proyecto_id, $reportebei_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $total_singuardar = 0;


            if (($areas_poe + 0) == 1) {
                $categorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)
                    ->orderBy('reportecategoria_orden', 'ASC')
                    ->get();


                foreach ($categorias as $key => $value) {
                    // $numero_registro += 1;
                    // $value->numero_registro = $numero_registro;
                    $value->numero_registro = $value->reportecategoria_orden;


                    if (!$value->reportecategoria_horas) {
                        $total_singuardar += 1;

                        $value->categoria_horas = NULL;
                    } else {
                        $value->categoria_horas = $value->reportecategoria_horas . ' Hrs';
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }
            } else {
               
                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                    ->where('agente_id', 4)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                $edicion = 1;
                if (count($revision) > 0) {
                    if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                        $edicion = 0;
                    }
                }


                //==========================================


                $categorias = reportebeicategoriaModel::where('proyecto_id', $proyecto_id)
                    ->where('registro_id', $reportebei_id)
                    ->orderBy('reportebeicategoria_nombre', 'ASC')
                    ->get();


                foreach ($categorias as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $value->reportecategoria_nombre = $value->reportebeicategoria_nombre;
                    $value->reportecategoria_total = $value->reportebeicategoria_total;
                    $value->reportecategoria_horas = $value->reportebeicategoria_horas;
                    $value->categoria_horas = $value->reportebeicategoria_horas . ' Hrs';


                    if (!$value->reportebeicategoria_total) {
                        $total_singuardar += 1;
                        $value->categoria_horas = NULL;
                    } else {
                        $value->categoria_horas = $value->reportebeicategoria_horas . ' Hrs';
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                    }
                }
            }


            // respuesta
            $dato['data'] = $categorias;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total_singuardar"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeicategoriaeliminar($categoria_id)
    {
        try {
            $categoria = reportebeicategoriaModel::where('id', $categoria_id)->delete();

            // respuesta
            $dato["msj"] = 'Categoría eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeiareas($proyecto_id, $reportebei_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $numero_registro2 = 0;
            $total_singuardar = 0;
            $instalacion = 'XXX';
            $area = 'XXX';
            $area2 = 'XXX';
            $selectareasoption = '';
            $tabla_5_5 = NULL;
            $tabla_6_1 = NULL;
            $dato['total_ic'] = 0;
            $dato['total_pt'] = 0;


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        reportearea.aplica_bei,
                                        reportearea.reportearea_instalacion,
                                        reportearea.reportearea_nombre,
                                        reportearea.reportearea_orden,
                                        reportearea.reportebeiarea_porcientooperacion,
                                        IF( IFNULL( reportearea.reportebeiarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportebeiarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                        reportearea.reportearea_descripcion,
                                        reporteareacategoria.reportecategoria_id,
                                        reportecategoria.reportecategoria_orden,
                                        reportecategoria.reportecategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reportebeiareacategoria.reportebeicategoria_id, "checked", "") AS checked
                                            FROM
                                                reportebeiareacategoria
                                            WHERE
                                                reportebeiareacategoria.reportebeiarea_id = reportearea.id
                                                AND reportebeiareacategoria.reportebeicategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reportebeiareacategoria.reportebeiareacategoria_poe = ' . $reportebei_id . ' 
                                            LIMIT 1
                                        ), "") AS checked,
                                        reportecategoria.reportecategoria_horas,
                                        reporteareacategoria.reporteareacategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades

                                    FROM
                                        reportearea
                                        LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
                                        LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC,
                                        reportecategoria.reportecategoria_orden ASC,
                                        reportecategoria.reportecategoria_nombre ASC');


                foreach ($areas as $key => $value) {
                    if ($area != $value->reportearea_nombre) {
                        $area = $value->reportearea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;

                        if (($value->reportebeiarea_porcientooperacion + 0) > 0) {
                            $numero_registro2 += 1;


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportebeiarea_porcientooperacion . '%</td>
                                            </tr>';

                        }

                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    if ($value->checked) {
                        $value->reportecategoria_nombre_texto = '<span class="text-danger">' . $value->reportecategoria_nombre . '</span>';
                    } else {
                        $value->reportecategoria_nombre_texto = $value->reportecategoria_nombre;
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';


                    if ($value->aplica_bei === NULL) {
                        $total_singuardar += 1;
                    }


                    if (($value->reportebeiarea_porcientooperacion + 0) > 0) {

                        if ($value->checked) {
                            //TABLA 5.5.- Actividades del personal expuesto
                            //==================================================

                            $tabla_5_5 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td>' . $value->reportecategoria_horas . ' Hrs.</td>
                                            </tr>';
                        }

                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reportearea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reportearea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }


                        if ($instalacion != $value->reportearea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reportearea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportearea_nombre) {
                            $area2 = $value->reportearea_nombre;
                            $selectareasoption .= '<option value="' . $value->id . '">' . $area2 . '</option>';
                        }


                        if ($key == (count($areas) - 1)) {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            } else {
                


                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                    ->where('agente_id', 4)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                $edicion = 1;
                if (count($revision) > 0) {
                    if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                        $edicion = 0;
                    }
                }


                $areas = DB::select('SELECT
                                        reportebeiarea.proyecto_id,
                                        reportebeiarea.registro_id,
                                        reportebeiarea.id,
                                        reportebeiarea.reportebeiarea_instalacion AS reportearea_instalacion,
                                        reportebeiarea.reportebeiarea_nombre AS reportearea_nombre,
                                        reportebeiarea.reportebeiarea_numorden AS reportearea_orden,
                                        reportebeiarea.reportebeiarea_porcientooperacion,
                                        reportebeiarea.reportebeiarea_descripcion AS reportearea_descripcion,
                                        reportebeiareacategoria.reportebeicategoria_id AS reportecategoria_id,
                                        reportebeicategoria.reportebeicategoria_nombre AS reportecategoria_nombre,
                                        reportebeiareacategoria.reportebeiareacategoria_total AS reporteareacategoria_total,
                                        reportebeiareacategoria.reportebeiareacategoria_geo AS reporteareacategoria_geh,
                                        reportebeicategoria.reportebeicategoria_horas AS reportecategoria_horas
                                    FROM
                                        reportebeiarea
                                        INNER JOIN reportebeiareacategoria ON reportebeiarea.id = reportebeiareacategoria.reportebeiarea_id
                                        LEFT JOIN reportebeicategoria ON reportebeiareacategoria.reportebeicategoria_id = reportebeicategoria.id
                                    WHERE
                                        reportebeiarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportebeiarea.registro_id = ' . $reportebei_id . ' 
                                        AND reportebeiareacategoria.reportebeiareacategoria_poe = 0
                                    ORDER BY
                                        reportebeiarea.reportebeiarea_numorden ASC,
                                        reportebeiarea.reportebeiarea_nombre ASC,
                                        reportebeicategoria.reportebeicategoria_nombre ASC');


                foreach ($areas as $key => $value) {
                    if ($area != $value->reportearea_nombre) {
                        $area = $value->reportearea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;

                        if (($value->reportebeiarea_porcientooperacion + 0) > 0) {
                            $numero_registro2 += 1;


                           


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportebeiarea_porcientooperacion . '%</td>
                                            </tr>';


                           
                        }


                        
                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    $value->reportecategoria_nombre_texto = $value->reportecategoria_nombre;


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';


                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                    }


                    if ($value->reportebeiarea_porcientooperacion === NULL) {
                        $total_singuardar += 1;
                    }


                    if (($value->reportebeiarea_porcientooperacion + 0) > 0) {

                        //TABLA 5.5.- Actividades del personal expuesto
                        //==================================================

                        $tabla_5_5 .= '<tr>
                                            <td>' . $numero_registro . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportecategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                            <td>' . $value->reportecategoria_horas . ' Hrs.</td>
                                        </tr>';


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reportearea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reportearea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }


                        if ($instalacion != $value->reportearea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reportearea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportearea_nombre) {
                            $area2 = $value->reportearea_nombre;
                            $selectareasoption .= '<option value="' . $value->id . '">' . $area2 . '</option>';
                        }


                        if ($key == (count($areas) - 1)) {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            }


            // respuesta
            $dato['data'] = $areas;
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_6_1"] = $tabla_6_1;

            $dato["total_singuardar"] = $total_singuardar;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["tabla_5_5"] = NULL;
            $dato["tabla_6_1"] = NULL;

            $dato["total_singuardar"] = $total_singuardar;
            $dato["selectareasoption"] = '';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    public function reportebeiareascategorias($proyecto_id, $reportebei_id, $area_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $areacategorias_lista = '';


            if (($areas_poe + 0) == 1) {
                $areacategorias = DB::select('SELECT
                                                reportecategoria.proyecto_id,
                                                reporteareacategoria.reportearea_id,
                                                reportecategoria.id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reportebeiareacategoria.reportebeicategoria_id, "checked", "")
                                                    FROM
                                                        reportebeiareacategoria
                                                    WHERE
                                                        reportebeiareacategoria.reportebeiarea_id = reporteareacategoria.reportearea_id
                                                        AND reportebeiareacategoria.reportebeicategoria_id = reportecategoria.id
                                                        AND reportebeiareacategoria.reportebeiareacategoria_poe = ' . $reportebei_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reporteareacategoria.reporteareacategoria_total AS categoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS categoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades AS categoria_actividades
                                                
                                            FROM
                                                reporteareacategoria
                                                INNER JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteareacategoria.reportearea_id = ' . $area_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');



                $readonly_required = '';
                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;


                    if ($value->checked) {
                        $readonly_required = 'required';
                    } else {
                        $readonly_required = 'readonly';
                    }


                    $areacategorias_lista .= '<tr>
                                                <td width="60">
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_reportecategoria_id[]" value="' . $value->id . '" ' . $value->checked . ' onchange="activa_areacategoria(this, ' . $numero_registro . ');"/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="180">
                                                    ' . $value->reportecategoria_nombre . '
                                                </td>
                                                <td width="80">
                                                    <input type="number" class="form-control" name="reporteareacategoria_total_' . $value->id . '" value="' . $value->categoria_total . '" readonly>
                                                </td>
                                                <td width="80">
                                                    <input type="number" class="form-control" name="reporteareacategoria_geh_' . $value->id . '" value="' . $value->categoria_geh . '" readonly>
                                                </td>
                                                <td width="180">
                                                    <textarea rows="2" class="form-control" name="reporteareacategoria_actividades_' . $value->id . '" readonly>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            } else {
                $areacategorias = DB::select('SELECT
                                                    reportebeicategoria.id,
                                                    reportebeicategoria.proyecto_id,
                                                    reportebeicategoria.recsensorialcategoria_id,
                                                    reportebeicategoria.reportebeicategoria_nombre,
                                                    reportebeicategoria.reportebeicategoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reportebeiareacategoria.reportebeicategoria_id, "checked", "") AS checked
                                                        FROM
                                                            reportebeiareacategoria
                                                        WHERE
                                                            reportebeiareacategoria.reportebeiarea_id = ' . $area_id . '
                                                            AND reportebeiareacategoria.reportebeicategoria_id = reportebeicategoria.id
                                                            AND reportebeiareacategoria.reportebeiareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS checked,
                                                    IFNULL((
                                                        SELECT
                                                            reportebeiareacategoria.reportebeiareacategoria_total
                                                        FROM
                                                            reportebeiareacategoria
                                                        WHERE
                                                            reportebeiareacategoria.reportebeiarea_id = ' . $area_id . '
                                                            AND reportebeiareacategoria.reportebeicategoria_id = reportebeicategoria.id
                                                            AND reportebeiareacategoria.reportebeiareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            reportebeiareacategoria.reportebeiareacategoria_geo
                                                        FROM
                                                            reportebeiareacategoria
                                                        WHERE
                                                            reportebeiareacategoria.reportebeiarea_id = ' . $area_id . '
                                                            AND reportebeiareacategoria.reportebeicategoria_id = reportebeicategoria.id
                                                            AND reportebeiareacategoria.reportebeiareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_geh,
                                                    IFNULL((
                                                        SELECT
                                                            reportebeiareacategoria.reportebeiareacategoria_actividades
                                                        FROM
                                                            reportebeiareacategoria
                                                        WHERE
                                                            reportebeiareacategoria.reportebeiarea_id = ' . $area_id . '
                                                            AND reportebeiareacategoria.reportebeicategoria_id = reportebeicategoria.id
                                                            AND reportebeiareacategoria.reportebeiareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_actividades
                                                FROM
                                                    reportebeicategoria
                                                WHERE
                                                    reportebeicategoria.proyecto_id = ' . $proyecto_id . '
                                                    AND reportebeicategoria.registro_id = ' . $reportebei_id . '
                                                ORDER BY
                                                    reportebeicategoria.reportebeicategoria_nombre ASC');


                $readonly_required = '';
                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;


                    if ($value->checked) {
                        $readonly_required = 'required';
                    } else {
                        $readonly_required = 'readonly';
                    }


                    if (!$value->categoria_total) {
                        $value->categoria_total = $value->reportebeicategoria_total;
                    }


                    $areacategorias_lista .= '<tr>
                                                <td width="60">
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_reportecategoria_id[]" value="' . $value->id . '" ' . $value->checked . ' onchange="activa_areacategoria(this, ' . $numero_registro . ');"/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td width="180">
                                                    ' . $value->reportebeicategoria_nombre . '
                                                </td>
                                                <td width="80">
                                                    <input type="number" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_total_' . $value->id . '" value="' . $value->categoria_total . '" ' . $readonly_required . '>
                                                </td>
                                                <td width="80">
                                                    <input type="number" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_geh_' . $value->id . '" value="' . $value->categoria_geh . '" ' . $readonly_required . ' >
                                                </td>
                                                <td width="180">
                                                    <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_actividades_' . $value->id . '" ' . $readonly_required . '>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            }


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areacategorias'] = '<tr><td colspan="6">Error al cargar las categorías</td></tr>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeitablarecomendaciones($proyecto_id, $reportebei_id, $agente_nombre)
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
                                                                            AND reporterecomendaciones.registro_id = ' . $reportebei_id . '
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
                                                        AND reporterecomendaciones.registro_id = ' . $reportebei_id . '
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



    public function reportebeitablainformeresultados($proyecto_id, $reportebei_id, $agente_nombre)
    {
        try {
            $informes = collect(DB::select('SELECT
                                                proyectoevidenciadocumento.proyecto_id,
                                                proyectoevidenciadocumento.proveedor_id,
                                                proyectoevidenciadocumento.agente_id,
                                                proyectoevidenciadocumento.agente_nombre,
                                                proyectoevidenciadocumento.id,
                                                proyectoevidenciadocumento.proyectoevidenciadocumento_nombre,
                                                proyectoevidenciadocumento.proyectoevidenciadocumento_extension, 
                                                proyectoevidenciadocumento.proyectoevidenciadocumento_archivo,
                                                proyectoevidenciadocumento.created_at,
                                                IFNULL((
                                                    SELECT  
                                                        IF(IFNULL(reporteanexos.reporteanexos_rutaanexo, "") = "", "", "checked")
                                                    FROM
                                                        reporteanexos
                                                    WHERE
                                                        reporteanexos.proyecto_id = proyectoevidenciadocumento.proyecto_id
                                                        AND reporteanexos.agente_nombre = proyectoevidenciadocumento.agente_nombre
                                                        AND reporteanexos.registro_id = ' . $reportebei_id . '
                                                        AND reporteanexos.reporteanexos_tipo = 1
                                                        AND reporteanexos.reporteanexos_rutaanexo = proyectoevidenciadocumento.proyectoevidenciadocumento_archivo
                                                ), "") AS checked 
                                            FROM
                                                proyectoevidenciadocumento
                                            WHERE
                                                proyectoevidenciadocumento.proyecto_id = ' . $proyecto_id . '
                                                AND proyectoevidenciadocumento.agente_nombre = "' . $agente_nombre . '"'));

            $total_activos = 0;
            $numero_registro = 0;
            foreach ($informes as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // $value->checked = NULL;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="reportebei_anexonombre_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_nombre . '">
                                            <input type="hidden" class="form-control" name="reportebei_anexoarchivo_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_archivo . '">
                                            <input type="checkbox" class="reportebei_informeresultadocheckbox" name="reportebei_informeresultadocheckbox[]" value="' . $value->id . '" ' . $value->checked . '>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';

                if ($value->proyectoevidenciadocumento_extension == '.pdf' || $value->proyectoevidenciadocumento_extension == '.PDF') {
                    $value->documento = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar PDF"><i class="fa fa-file-pdf-o fa-1x"></i></button>';
                } else {
                    $value->documento = '<button type="button" class="btn btn-success waves-effect btn-circle" data-toggle="tooltip" title="Descargar archivo"><i class="fa fa-download fa-1x"></i></button>';
                }

                // VERIFICAR SI HAY DOCUMENTOS SELECCIONADOS
                if ($value->checked) {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $informes;
            $dato["total"] = $total_activos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    public function reportebeitablaequipoutilizado($proyecto_id, $reportebei_id, $agente_nombre)
    {
        try {
            $proveedor = DB::select('SELECT
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id,
                                            proyectoproveedores.proyectoproveedores_tipoadicional,
                                            proyectoproveedores.catprueba_id AS agente_id,
                                            proyectoproveedores.proyectoproveedores_agente
                                        FROM
                                            proyectoproveedores
                                        WHERE
                                            proyectoproveedores.proyecto_id = ' . $proyecto_id . ' 
                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                            AND proyectoproveedores.catprueba_id = 22 -- BEI ------------------------------
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');


            $where_condicion = '';
            


            $equipos = DB::select('SELECT DISTINCT
                                        proyectoequiposactual.proyecto_id,
                                        proyectoequiposactual.proveedor_id,
                                        proveedor.proveedor_NombreComercial,
                                        proyectoequiposactual.equipo_id,
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        IFNULL(equipo.equipo_FechaCalibracion, "N/A") AS equipo_FechaCalibracion,
                                        IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") AS equipo_VigenciaCalibracion,
                                        IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) AS vigencia_dias,
                                        IF(equipo.equipo_VigenciaCalibracion, CONCAT(equipo.equipo_VigenciaCalibracion, " (", (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1)," d)"), "N/A") AS vigencia_texto,
                                        (
                                            CASE
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) = 0 THEN ""
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) >= 90 THEN ""
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) >= 30 THEN "text-warning"
                                                ELSE "text-danger"
                                            END
                                        ) AS vigencia_color,
                                        -- equipo.equipo_CertificadoPDF,
                                        -- equipo.equipo_cartaPDF,
                                        IFNULL((
                                            SELECT
                                                IF(IFNULL(reporteequiposutilizados.equipo_id, ""), "checked" , "")
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
                                                AND reporteequiposutilizados.registro_id = "' . $reportebei_id . '"
                                                AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                AND reporteequiposutilizados.equipo_id = proyectoequiposactual.equipo_id
                                            LIMIT 1
                                        ), NULL) AS checked,
                                        IFNULL((
                                            SELECT
                                                reporteequiposutilizados.reporteequiposutilizados_cartacalibracion
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
                                                AND reporteequiposutilizados.registro_id = "' . $reportebei_id . '"
                                                AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                AND reporteequiposutilizados.equipo_id = proyectoequiposactual.equipo_id
                                            LIMIT 1
                                        ), NULL) AS cartacalibracion,
                                        IFNULL((
                                            SELECT
                                                reporteequiposutilizados.id
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
                                                AND reporteequiposutilizados.registro_id = "' . $reportebei_id . '"
                                                AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                AND reporteequiposutilizados.equipo_id = proyectoequiposactual.equipo_id
                                            LIMIT 1
                                        ), NULL) AS id
                                    FROM
                                        proyectoequiposactual
                                        LEFT JOIN proveedor ON proyectoequiposactual.proveedor_id = proveedor.id
                                        LEFT JOIN equipo ON proyectoequiposactual.equipo_id = equipo.id
                                    WHERE
                                        proyectoequiposactual.proyecto_id = ' . $proyecto_id . ' 
                                        ' . $where_condicion . ' 
                                    ORDER BY
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie');



            $total_activos = 0;
            $numero_registro = 0;
            foreach ($equipos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="reportebei_equipoutilizadocheckbox" name="reportebei_equipoutilizadocheckbox[]" value="' . $value->equipo_id . '" ' . $value->checked . ' onchange="activa_checkboxcarta(this, ' . $value->equipo_id . ');";>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                $value->equipo = '<span class="' . $value->vigencia_color . '">' . $value->equipo_Descripcion . '</span><br><small class="' . $value->vigencia_color . '">' . $value->proveedor_NombreComercial . '</small>';


                $value->marca_modelo_serie = '<span class="' . $value->vigencia_color . '">' . $value->equipo_Marca . '<br>' . $value->equipo_Modelo . '<br>' . $value->equipo_Serie . '</span>';


                $value->vigencia = '<span class="' . $value->vigencia_color . '">' . $value->vigencia_texto . '</span>';
                //---------------------------


                // VERIFICAR SI HAY EQUIPOS SELECCIONADOS
                if ($value->checked) {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $equipos;
            $dato["total"] = $total_activos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeiepptabla($proyecto_id, $reportebei_id)
    {
        try {
            


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 1)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            $epp = reportebeiepp::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reportebei_id)
                ->get();

            $numero_registro = 0;
            foreach ($epp as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $epp;
            $dato["total"] = count($epp);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    
    public function reportebeieppeliminar($epp_id)
    {
        try {
            $epp = reportebeiepp::where('id', $epp_id)->delete();

            // respuesta
            $dato["msj"] = 'E.P.P. eliminado correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    public function reportebeitablapuntos($proyecto_id) {
        try {
          


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 4)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }

            
            $puntos = DB::select('SELECT p.ID_BEI_INFORME,
                                        CONCAT(p.EDAD_BEI," años") as EDAD_BEI_TEXTO,
                                        p.NUM_PUNTO_BEI,
                                        p.RECSENSORIAL_ID,
                                        p.EDAD_BEI,
                                        p.NOMBRE_BEI,
                                        p.GENERO_BEI,
                                        p.FICHA_BEI,
                                        p.ANTIGUEDAD_BEI,
                                        p.MUESTRA_BEI,
                                         IF(p.UNIDAD_MEDIDA_BEI <> "",p.UNIDAD_MEDIDA_BEI, b.UNIDAD_MEDIDA) as UNIDAD_MEDIDA,
                                        CONCAT(IF(p.RESULTADO_BEI <> "", p.RESULTADO_BEI, 0)," ", IF(p.UNIDAD_MEDIDA_BEI <> "", p.UNIDAD_MEDIDA_BEI , b.UNIDAD_MEDIDA)) AS RESULTADO_BEI_TEXTO,
                                        p.RESULTADO_BEI,
                                        CONCAT(IF(p.REFERENCIA_BEI <> "", p.REFERENCIA_BEI, b.VALOR_REFERENCIA)," ", IF(p.UNIDAD_MEDIDA_BEI <> "", p.UNIDAD_MEDIDA_BEI , b.UNIDAD_MEDIDA)) AS REFERENCIA_BEI_TEXTO,
                                        p.REFERENCIA_BEI,
                                        a.recsensorialarea_nombre as AREA,
                                        p.AREA_ID,
                                        c.recsensorialcategoria_nombrecategoria as CATEGORIA,
                                        p.CATEGORIA_ID,
                                        b.DETERMINANTE,
                                        (IF((p.RESULTADO_BEI = "" OR p.RESULTADO_BEI IS NULL),"Sin evaluar",
                                            IF(
                                                -- Verificar si el valor contiene solo letras o es N.D, N.A, N/A
                                                p.RESULTADO_BEI REGEXP "^[A-Za-z]+$|^N[./]?D$|^N[./]?A$", 
                                                -- Si contiene solo letras o las abreviaturas, retornamos "Dentro de norma"
                                                "ND",  
                                                -- Si contiene números, continuamos con la limpieza
                                                IF(
                                                    CONVERT(REPLACE(REPLACE(REPLACE(p.RESULTADO_BEI, ">" , ""), "<" ,""), " ", ""), DECIMAL(10,2)) >= 0,
                                                    -- Después de limpiar, verificamos si el valor es mayor o igual a 0.25
                                                    IF(
                                                                    (REPLACE(REPLACE(REPLACE(p.RESULTADO_BEI, ">" , ""), "<" ,""), " ", "") + 0) > p.REFERENCIA_BEI,
                                                                    "Fuera de norma",  -- Si es mayor, está fuera de norma
                                                                    "Dentro de norma"  -- Si es menor, está dentro de norma
                                                    ),
                                                    "Fuera de norma"  -- Si no es un número válido o es negativo, es fuera de norma
                                                )
                                            )
                                        )
                                    )  as NORMATIVIDAD
                                FROM puntosBeiInforme p
                                LEFT JOIN sustanciasEntidadBeis b ON b.ID_BEI = p.BEI_ID
                                LEFT JOIN recsensorialarea a ON a.id = p.AREA_ID
                                LEFT JOIN recsensorialcategoria c ON c.id = p.CATEGORIA_ID
                                WHERE p.PROYECTO_ID = ?', [$proyecto_id]);
            


            $numero_registro = 0;
            foreach ($puntos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

            }


            // respuesta
            $dato['data'] = $puntos;
            $dato['total'] = $numero_registro;
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



            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reportebei_id + 0) > 0) {
                $reportebei = reportebeiModel::findOrFail($request->reportebei_id);


                $reportebei->update([
                    'reportebei_instalacion' => $request->reportebei_instalacion
                ]);


                $dato["reportebei_id"] = $reportebei->id;


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
                DB::statement('ALTER TABLE reportebei AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reportebei = reportebeiModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportebei_revision' => 0,
                    'reportebei_instalacion' => $request->reportebei_instalacion,
                    'reportebei_catregion_activo' => 1,
                    'reportebei_catsubdireccion_activo' => 1,
                    'reportebei_catgerencia_activo' => 1,
                    'reportebei_catactivo_activo' => 1,
                    'reportebei_concluido' => 0,
                    'reportebei_cancelado' => 0
                ]);


                //--------------------------------------


                // Asignar categorias de este proyecto a este registro
                DB::statement('UPDATE 
                                    reportebeicategoria
                                SET 
                                    registro_id = ' . $reportebei->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // Asignar Areas de este proyecto a este registro
                DB::statement('UPDATE 
                                    reportebeiarea
                                SET 
                                    registro_id = ' . $reportebei->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            // PORTADA
            if (($request->opcion + 0) == 0) {
                // REGION
                $catregion_activo = 0;
                if ($request->reportebei_catregion_activo != NULL) {
                    $catregion_activo = 1;
                }

                // SUBDIRECCION
                $catsubdireccion_activo = 0;
                if ($request->reportebei_catsubdireccion_activo != NULL) {
                    $catsubdireccion_activo = 1;
                }

                // GERENCIA
                $catgerencia_activo = 0;
                if ($request->reportebei_catgerencia_activo != NULL) {
                    $catgerencia_activo = 1;
                }

                // ACTIVO
                $catactivo_activo = 0;
                if ($request->reportebei_catactivo_activo != NULL) {
                    $catactivo_activo = 1;
                }

                $reportebei->update([
                    'reportebei_catregion_activo' => $catregion_activo,
                    'reportebei_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reportebei_catgerencia_activo' => $catgerencia_activo,
                    'reportebei_catactivo_activo' => $catactivo_activo,
                    'reportebei_instalacion' => $request->reportebei_instalacion,
                    'reportebei_fecha' => $request->reportebei_fecha,
                    'reportebei_mes' => $request->reportebei_mes
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
                        $imgGuardada = $request->file('PORTADA')->storeAs('reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reportebei_id . '/imagenPortada', 'PORTADA_IAMGEN.' . $extension);

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
                                'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $request->reportebei_id . '/imagenPortada',
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
                // dd($request->reportebei_introduccion);

                $reportebei->update([
                    'reportebei_introduccion' =>  $request->reportebei_introduccion,
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
                $reportebei->update([
                    'reportebei_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reportebei->update([
                    'reportebei_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reportebei->update([
                    'reportebei_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion + 0) == 6) {
                $reportebei->update([
                    'reportebei_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


        

            // UBICACION
            if (($request->opcion + 0) == 11) {
                $reportebei->update([
                    'reportebei_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reportebeiubicacionfoto')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportebei->id . '/ubicacionfoto/ubicacionfoto.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportebei->update([
                        'reportebei_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 12) {
                $reportebei->update([
                    'reportebei_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_procesoinstalacion),
                    'reportebei_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reportebei_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            // CATEGORIAS
            if (($request->opcion + 0) == 13) {
               
                if (($request->categorias_poe + 0) == 1) {
                    $categoria = reportecategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportecategoria_id + 0) == 0) {
                        DB::statement('ALTER TABLE reportebeicategoria AUTO_INCREMENT = 1;');

                        $categoria = reportebeicategoriaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportebei->id,
                            'recsensorialcategoria_id' => 0,
                            'reportebeicategoria_nombre' => $request->reportecategoria_nombre,
                            'reportebeicategoria_total' => $request->reportecategoria_total,
                            'reportebeicategoria_horas' => $request->reportecategoria_horas
                        ]);


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $categoria = reportebeicategoriaModel::findOrFail($request->reportecategoria_id);

                        $categoria->update([
                            'registro_id' => $reportebei->id,
                            'reportebeicategoria_nombre' => $request->reportecategoria_nombre,
                            'reportebeicategoria_total' => $request->reportecategoria_total,
                            'reportebeicategoria_horas' => $request->reportecategoria_horas
                        ]);


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // AREAS
            if (($request->opcion + 0) == 14) {

                if (($request->areas_poe + 0) == 1) {
                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reportebeiareacategoriaModel::where('reportebeiarea_id', $request->reportearea_id)
                        ->where('reportebeiareacategoria_poe', $reportebei->id)
                        ->delete();


                    if ($request->checkbox_reportecategoria_id) {
                        DB::statement('ALTER TABLE reportebeiareacategoria AUTO_INCREMENT = 1;');


                        foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                            $areacategoria = reportebeiareacategoriaModel::create([
                                'reportebeiarea_id' => $area->id,
                                'reportebeicategoria_id' => $value,
                                'reportebeiareacategoria_poe' => $reportebei->id,
                                'reportebeiareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                'reportebeiareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                'reportebeiareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                

                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reportebeiarea AUTO_INCREMENT = 1;');


                        $area = reportebeiareaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportebei->id,
                            'recsensorialarea_id' => 0,
                            'reportebeiarea_numorden' => $request->reportearea_orden,
                            'reportebeiarea_nombre' => $request->reportearea_nombre,
                            'reportebeiarea_instalacion' => $request->reportearea_instalacion,
                            'reportebeiarea_porcientooperacion' => $request->reportebeiarea_porcientooperacion,
                            'reportearea_descripcion' => $request->reportearea_descripcion

                        ]);


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reportebeiareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reportebeiareacategoriaModel::create([
                                    'reportebeiarea_id' => $area->id,
                                    'reportebeicategoria_id' => $value,
                                    'reportebeiareacategoria_poe' => 0,
                                    'reportebeiareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reportebeiareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reportebeiareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],

                                ]);
                            }
                        }

                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        // $request['registro_id'] = $reportebei->id;
                        $area = reportebeiareaModel::findOrFail($request->reportearea_id);
                        // $area->update($request->all());
                        $area->update([
                            'registro_id' => $reportebei->id,
                            'reportebeiarea_numorden' => $request->reportearea_orden,
                            'reportebeiarea_nombre' => $request->reportearea_nombre,
                            'reportebeiarea_instalacion' => $request->reportearea_instalacion,
                            'reportebeiarea_porcientooperacion' => $request->reportebeiarea_porcientooperacion,
                            'reportebeiarea_descripcion' => $request->reportearea_descripcion
                        ]);


                        $eliminar_categorias = reportebeiareacategoriaModel::where('reportebeiarea_id', $request->reportearea_id)
                            ->where('reportebeiareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reportebeiareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reportebeiareacategoriaModel::create([
                                    'reportebeiarea_id' => $area->id,
                                    'reportebeicategoria_id' => $value,
                                    'reportebeiareacategoria_poe' => 0,
                                    'reportebeiareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reportebeiareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reportebeiareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],

                                ]);
                            }
                        }

                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }

            // EQUIPO PROTECCION PERSONAL
            if (($request->opcion + 0) == 15) {
                if (($request->reporteepp_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportebeiepp AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reportebei->id;
                    $categoria = reportebeiepp::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reportebei->id;
                    $categoria = reportebeiepp::findOrFail($request->reporteepp_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // CONCLUSION
            if (($request->opcion + 0) == 20) {
                $reportebei->update([
                    'reportebei_conclusion' => $request->reportebei_conclusion
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
                        ->where('registro_id', $reportebei->id)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportebei->id,
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
                            ->where('registro_id', $reportebei->id)
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reportebei->id,
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
                $reportebei->update([
                    'reportebei_responsable1' => $request->reportebei_responsable1,
                    'reportebei_responsable1cargo' => $request->reportebei_responsable1cargo,
                    'reportebei_responsable2' => $request->reportebei_responsable2,
                    'reportebei_responsable2cargo' => $request->reportebei_responsable2cargo
                ]);


                if ($request->reportebei_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportebei->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->reportebei_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reportebei->update([
                        'reportebei_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportebei_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reportebeiresponsable1documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reportebei_responsable1documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportebei->id . '/responsables informe/responsable1_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportebei->update([
                        'reportebei_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reportebeiresponsable2documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reportebei_responsable2documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reportebei->id . '/responsables informe/responsable2_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reportebei->update([
                        'reportebei_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            // EQUIPO UTILIZADO
            if (($request->opcion + 0) == 50) {
                // dd($request->all());

                if ($request->reportebei_equipoutilizadocheckbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reportebei->id)
                        ->delete();


                    foreach ($request->reportebei_equipoutilizadocheckbox as $key => $value) {
                        if ($request['equipoutilizado_checkboxcarta_' . $value]) {
                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        } else {
                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportebei->id,
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
                    ->where('registro_id', $reportebei->id)
                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                    ->delete();

                if ($request->reportebei_informeresultadocheckbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reportebei_informeresultadocheckbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reportebei->id,
                            'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            ,
                            'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['reportebei_anexonombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['reportebei_anexoarchivo_' . $value]
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


            // PUNTOS DE RESULTADOS                                                                                                                                                                              
            if (($request->opcion + 0) == 80) {
               
                    
                $categoria = puntosBeiInformeModel::findOrFail($request->ID_BEI_INFORME);
                $categoria->update($request->all());

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            
            }

            // respuesta
            $dato["reportebei_id"] = $reportebei->id;
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


}
