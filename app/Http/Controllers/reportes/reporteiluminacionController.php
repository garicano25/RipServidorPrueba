<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use DateTime;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;

//Tablas POE
use App\modelos\reportes\reportecategoriaModel;
use App\modelos\reportes\reporteareaModel;
use App\modelos\reportes\reporteareacategoriaModel;

//Tablas revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;

// Tablas estrucura del reporte
use App\modelos\reportes\reporteiluminacioncatalogoModel;
use App\modelos\reportes\reporteiluminacionModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteiluminacioncategoriaModel;
use App\modelos\reportes\reporteiluminacionareaModel;
use App\modelos\reportes\reporteiluminacionareacategoriaModel;
use App\modelos\reportes\reporteiluminacionpuntosModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\recsensorial\cat_sistemailuminacionModel;


use App\modelos\reportes\recursosPortadasInformesModel;


//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class reporteiluminacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionvista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Iluminación primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 4) // Iluminacion
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 4,
                    'agente_nombre' => 'Iluminación',
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
                                            reporteiluminacioncategoria.proyecto_id, 
                                            reporteiluminacioncategoria.registro_id, 
                                            reporteiluminacioncategoria.id, 
                                            reporteiluminacioncategoria.reporteiluminacioncategoria_nombre
                                        FROM
                                            reporteiluminacioncategoria
                                        WHERE
                                            reporteiluminacioncategoria.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');


            if (count($categorias) > 0) {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            } else {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //-------------------------------------


            $areas = DB::select('SELECT
                                    reporteiluminacionarea.proyecto_id, 
                                    reporteiluminacionarea.registro_id, 
                                    reporteiluminacionarea.id, 
                                    reporteiluminacionarea.reporteiluminacionarea_nombre
                                FROM
                                    reporteiluminacionarea
                                WHERE
                                    reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reporteiluminacionarea.reporteiluminacionarea_nombre ASC');


            //SI LA CONSULTA NO TIENE NADA
            if (count($areas) > 0) {
                $areas_poe = 0; // NO TIENE POE GENERAL
            } else //SI LA CONSULTA TRAE ALGO
            {
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

            $sistemas = cat_sistemailuminacionModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.parametros.reporteiluminacion', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe', 'sistemas'));
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
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Exploración y Producción', $texto);
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

            $texto = str_replace('PEMEX Exploración y Producción', $recsensorial->recsensorial_empresa, $texto);
        }

        return $texto;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciondatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reporteiluminacioncatalogo = reporteiluminacioncatalogoModel::limit(1)->get();

            $reporteiluminacion  = reporteiluminacionModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reporteiluminacion_revision', 'DESC')
                ->limit(1)
                ->get();

            if (count($reporteiluminacion) == 0) {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporteiluminacion = reporteiluminacionModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('proyecto_id', 'DESC')
                        ->orderBy('reporteiluminacion_revision', 'DESC')
                        // ->orderBy('updated_at', 'DESC')
                        ->limit(1)
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reporteiluminacion.id,
                                                reporteiluminacion.proyecto_id,
                                                reporteiluminacion.agente_id,
                                                reporteiluminacion.agente_nombre,
                                                reporteiluminacion.catactivo_id,
                                                reporteiluminacion.reporteiluminacion_revision,
                                                reporteiluminacion.reporteiluminacion_fecha,
                                                reporteiluminacion.reporteiluminacion_mes,
                                                reporteiluminacion.reporteiluminacion_instalacion,
                                                reporteiluminacion.reporteiluminacion_catregion_activo,
                                                reporteiluminacion.reporteiluminacion_catsubdireccion_activo,
                                                reporteiluminacion.reporteiluminacion_catgerencia_activo,
                                                reporteiluminacion.reporteiluminacion_catactivo_activo,
                                                reporteiluminacion.reporteiluminacion_introduccion,
                                                reporteiluminacion.reporteiluminacion_objetivogeneral,
                                                reporteiluminacion.reporteiluminacion_objetivoespecifico,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_1,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_2,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_2_1,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_2_2,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_2_3,
                                                reporteiluminacion.reporteiluminacion_metodologia_4_2_4,
                                                reporteiluminacion.reporteiluminacion_ubicacioninstalacion,
                                                reporteiluminacion.reporteiluminacion_ubicacionfoto,
                                                reporteiluminacion.reporteiluminacion_procesoinstalacion,
                                                reporteiluminacion.reporteiluminacion_actividadprincipal,
                                                reporteiluminacion.reporteiluminacion_criterioseleccion,
                                                reporteiluminacion.reporteiluminacion_conclusion,
                                                reporteiluminacion.reporteiluminacion_responsable1,
                                                reporteiluminacion.reporteiluminacion_responsable1cargo,
                                                reporteiluminacion.reporteiluminacion_responsable1documento,
                                                reporteiluminacion.reporteiluminacion_responsable2,
                                                reporteiluminacion.reporteiluminacion_responsable2cargo,
                                                reporteiluminacion.reporteiluminacion_responsable2documento,
                                                reporteiluminacion.reporteiluminacion_concluido,
                                                reporteiluminacion.reporteiluminacion_concluidonombre,
                                                reporteiluminacion.reporteiluminacion_concluidofecha,
                                                reporteiluminacion.reporteiluminacion_cancelado,
                                                reporteiluminacion.reporteiluminacion_canceladonombre,
                                                reporteiluminacion.reporteiluminacion_canceladofecha,
                                                reporteiluminacion.reporteiluminacion_canceladoobservacion,
                                                reporteiluminacion.created_at,
                                                reporteiluminacion.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reporteiluminacion ON proyecto.id = reporteiluminacion.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . ' 
                                                AND reporteiluminacion.reporteiluminacion_instalacion <> "" 
                                            ORDER BY
                                                reporteiluminacion.updated_at DESC');
                }


                $dato['reporteiluminacion_id'] = 0;


                if (count($reporteiluminacion) == 0) {
                    $reporteiluminacion = array(0, 0);
                    $dato['reporteiluminacion_id'] = -1;
                }
            } else {
                $dato['reporteiluminacion_id'] = $reporteiluminacion[0]->id;
            }


            $reporteiluminacion = $reporteiluminacion[0];


            //------------------------------


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 4) //Iluminación
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) > 0) {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reporteiluminacion_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reporteiluminacion_cancelado'] = $revision->reporterevisiones_cancelado;
            } else {
                $dato['reporteiluminacion_concluido'] = 0;
                $dato['reporteiluminacion_cancelado'] = 0;
            }


            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);






            // PORTADA
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_fecha != NULL && $reporteiluminacion->proyecto_id == $proyecto_id) {
                $reportefecha = $reporteiluminacion->reporteiluminacion_fecha;

                $dato['reporteiluminacion_portada_guardado'] = 1;
            } else {
                $reportefecha = $meses[$proyectofecha[1] + 0] . " del " . $proyectofecha[0];

                $dato['reporteiluminacion_portada_guardado'] = 0;
            }


            if ($dato['reporteiluminacion_id'] >= 0) {
                $dato['reporteiluminacion_portada'] = array(
                    'reporteiluminacion_catregion_activo' => $reporteiluminacion->reporteiluminacion_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporteiluminacion_catsubdireccion_activo' => $reporteiluminacion->reporteiluminacion_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporteiluminacion_catgerencia_activo' => $reporteiluminacion->reporteiluminacion_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporteiluminacion_catactivo_activo' => $reporteiluminacion->reporteiluminacion_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporteiluminacion_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporteiluminacion_fecha' => $reportefecha,
                    'reporteiluminacion_mes' => $reporteiluminacion->reporteiluminacion_mes


                );
            } else {
                $dato['reporteiluminacion_portada'] = array(
                    'reporteiluminacion_catregion_activo' => 1,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporteiluminacion_catsubdireccion_activo' => 1,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporteiluminacion_catgerencia_activo' => 1,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporteiluminacion_catactivo_activo' => 1,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporteiluminacion_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporteiluminacion_fecha' => $reportefecha,
                    'reporteiluminacion_mes' => ""

                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_introduccion != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_introduccion_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_introduccion_guardado'] = 0;
                }

                $introduccion = $reporteiluminacion->reporteiluminacion_introduccion;
            } else {
                $dato['reporteiluminacion_introduccion_guardado'] = 0;
                $introduccion = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_introduccion;
            }

            $dato['reporteiluminacion_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_objetivogeneral != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporteiluminacion->reporteiluminacion_objetivogeneral;
            } else {
                $dato['reporteiluminacion_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_objetivogeneral;
            }

            $dato['reporteiluminacion_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_objetivoespecifico != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporteiluminacion->reporteiluminacion_objetivoespecifico;
            } else {
                $dato['reporteiluminacion_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_objetivoespecifico;
            }

            $dato['reporteiluminacion_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_1 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporteiluminacion->reporteiluminacion_metodologia_4_1;
            } else {
                $dato['reporteiluminacion_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_1;
            }

            $dato['reporteiluminacion_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_2 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_2_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reporteiluminacion->reporteiluminacion_metodologia_4_2;
            } else {
                $dato['reporteiluminacion_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_2;
            }

            $dato['reporteiluminacion_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);


            // METODOLOGIA PUNTO 4.2.1
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_2_1 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_2_1_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_2_1_guardado'] = 0;
                }

                $metodologia_4_2_1 = $reporteiluminacion->reporteiluminacion_metodologia_4_2_1;
            } else {
                $dato['reporteiluminacion_metodologia_4_2_1_guardado'] = 0;
                $metodologia_4_2_1 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_2_1;
            }

            $dato['reporteiluminacion_metodologia_4_2_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_1);


            // METODOLOGIA PUNTO 4.2.2
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_2_2 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_2_2_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_2_2_guardado'] = 0;
                }

                $metodologia_4_2_2 = $reporteiluminacion->reporteiluminacion_metodologia_4_2_2;
            } else {
                $dato['reporteiluminacion_metodologia_4_2_2_guardado'] = 0;
                $metodologia_4_2_2 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_2_2;
            }

            $dato['reporteiluminacion_metodologia_4_2_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_2);


            // METODOLOGIA PUNTO 4.2.3
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_2_3 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_2_3_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_2_3_guardado'] = 0;
                }

                $metodologia_4_2_3 = $reporteiluminacion->reporteiluminacion_metodologia_4_2_3;
            } else {
                $dato['reporteiluminacion_metodologia_4_2_3_guardado'] = 0;
                $metodologia_4_2_3 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_2_3;
            }

            $dato['reporteiluminacion_metodologia_4_2_3'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_3);


            // METODOLOGIA PUNTO 4.2.4
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_metodologia_4_2_4 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_metodologia_4_2_4_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_metodologia_4_2_4_guardado'] = 0;
                }

                $metodologia_4_2_4 = $reporteiluminacion->reporteiluminacion_metodologia_4_2_4;
            } else {
                $dato['reporteiluminacion_metodologia_4_2_4_guardado'] = 0;
                $metodologia_4_2_4 = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_metodologia_4_2_4;
            }

            $dato['reporteiluminacion_metodologia_4_2_4'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2_4);


            // UBICACION
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_ubicacioninstalacion != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporteiluminacion->reporteiluminacion_ubicacioninstalacion;
            } else {
                $dato['reporteiluminacion_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_ubicacioninstalacion;
            }

            $ubicacionfoto = NULL;
            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_ubicacionfoto != NULL && $reporteiluminacion->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reporteiluminacion->reporteiluminacion_ubicacionfoto;
            }

            $dato['reporteiluminacion_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_procesoinstalacion != NULL && $reporteiluminacion->proyecto_id == $proyecto_id) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_procesoinstalacion_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_procesoinstalacion_guardado'] = 0;
                }

                $procesoinstalacion = $reporteiluminacion->reporteiluminacion_procesoinstalacion;
            } else {
                $dato['reporteiluminacion_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reporteiluminacion_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_actividadprincipal != NULL && $reporteiluminacion->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reporteiluminacion->reporteiluminacion_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reporteiluminacion_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // CRITERIO DE SELECCION
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_criterioseleccion != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_criterioseleccion_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_criterioseleccion_guardado'] = 0;
                }

                $criterioseleccion = $reporteiluminacion->reporteiluminacion_criterioseleccion;
            } else {
                $dato['reporteiluminacion_criterioseleccion_guardado'] = 0;
                $criterioseleccion = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_criterioseleccion;
            }

            $dato['reporteiluminacion_criterioseleccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $criterioseleccion);


            // CONCLUSION
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_conclusion != NULL && $reporteiluminacion->proyecto_id == $proyecto_id) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_conclusion_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_conclusion_guardado'] = 0;
                }

                $conclusion = $reporteiluminacion->reporteiluminacion_conclusion;
            } else {
                $dato['reporteiluminacion_conclusion_guardado'] = 0;
                $conclusion = $reporteiluminacioncatalogo[0]->reporteiluminacioncatalogo_conclusion;
            }

            $dato['reporteiluminacion_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteiluminacion_id'] >= 0 && $reporteiluminacion->reporteiluminacion_responsable1 != NULL) {
                if ($reporteiluminacion->proyecto_id == $proyecto_id) {
                    $dato['reporteiluminacion_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporteiluminacion_responsablesinforme_guardado'] = 0;
                }

                $dato['reporteiluminacion_responsablesinforme'] = array(
                    'reporteiluminacion_responsable1' => $reporteiluminacion->reporteiluminacion_responsable1,
                    'reporteiluminacion_responsable1cargo' => $reporteiluminacion->reporteiluminacion_responsable1cargo,
                    'reporteiluminacion_responsable1documento' => $reporteiluminacion->reporteiluminacion_responsable1documento,
                    'reporteiluminacion_responsable2' => $reporteiluminacion->reporteiluminacion_responsable2,
                    'reporteiluminacion_responsable2cargo' => $reporteiluminacion->reporteiluminacion_responsable2cargo,
                    'reporteiluminacion_responsable2documento' => $reporteiluminacion->reporteiluminacion_responsable2documento,
                    'proyecto_id' => $reporteiluminacion->proyecto_id,
                    'registro_id' => $reporteiluminacion->id
                );
            } else {
                $dato['reporteiluminacion_responsablesinforme_guardado'] = 0;

                // $reporteiluminacionhistorial = reporteiluminacionModel::where('catactivo_id', $proyecto->catactivo_id)
                //                                                         ->orderBy('proyecto_id', 'DESC')
                //                                                         ->orderBy('reporteiluminacion_revision', 'DESC')
                //                                                         ->limit(1)
                //                                                         ->get();

                $reporteiluminacionhistorial = reporteiluminacionModel::where('reporteiluminacion_responsable1', '!=', '')
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->get();


                if (count($reporteiluminacionhistorial) > 0 && $reporteiluminacionhistorial[0]->reporteiluminacion_responsable1 != NULL) {
                    $dato['reporteiluminacion_responsablesinforme'] = array(
                        'reporteiluminacion_responsable1' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable1,
                        'reporteiluminacion_responsable1cargo' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable1cargo,
                        'reporteiluminacion_responsable1documento' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable1documento,
                        'reporteiluminacion_responsable2' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable2,
                        'reporteiluminacion_responsable2cargo' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable2cargo,
                        'reporteiluminacion_responsable2documento' => $reporteiluminacionhistorial[0]->reporteiluminacion_responsable2documento,
                        'proyecto_id' => $reporteiluminacionhistorial[0]->proyecto_id,
                        'registro_id' => $reporteiluminacionhistorial[0]->id
                    );
                } else {
                    $dato['reporteiluminacion_responsablesinforme'] = array(
                        'reporteiluminacion_responsable1' => NULL,
                        'reporteiluminacion_responsable1cargo' => NULL,
                        'reporteiluminacion_responsable1documento' => NULL,
                        'reporteiluminacion_responsable2' => NULL,
                        'reporteiluminacion_responsable2cargo' => NULL,
                        'reporteiluminacion_responsable2documento' => NULL,
                        'proyecto_id' => 0,
                        'registro_id' => 0
                    );
                }
            }


            // MEMORIA FOTOGRAFICA
            //===================================================


            $memoriafotografica = collect(DB::select('SELECT
                                                            -- proyectoevidenciafoto.id,
                                                            proyectoevidenciafoto.proyecto_id,
                                                            -- proyectoevidenciafoto.proveedor_id,
                                                            -- proyectoevidenciafoto.agente_id,
                                                            proyectoevidenciafoto.agente_nombre,
                                                            -- proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                                            IFNULL(COUNT(proyectoevidenciafoto.proyectoevidenciafoto_nopunto), 0) AS total
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
                                                        LIMIT 1'));

            if (count($memoriafotografica) > 0) {
                $dato['reporteiluminacion_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            } else {
                $dato['reporteiluminacion_memoriafotografica_guardado'] = 0;
            }


            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            // $total_categorias = collect(DB::select('SELECT
            //                                             COUNT(reporteiluminacioncategoria.id) AS TOTAL
            //                                         FROM
            //                                             reporteiluminacioncategoria
            //                                         WHERE
            //                                             reporteiluminacioncategoria.proyecto_id = '.$proyecto_id));

            // if (($total_categorias[0]->TOTAL + 0) == 0)
            // {
            //     $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
            //                                                         ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
            //                                                         ->get();

            //     DB::statement('ALTER TABLE reporteiluminacioncategoria AUTO_INCREMENT = 1;');

            //     foreach ($recsensorial_categorias as $key => $value)
            //     {
            //         $categoria = reporteiluminacioncategoriaModel::create([
            //               'proyecto_id' => $proyecto_id
            //             , 'recsensorialcategoria_id' => $value->id
            //             , 'reporteiluminacioncategoria_nombre' => $value->recsensorialcategoria_nombrecategoria
            //         ]);
            //     }
            // }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            // $total_areas = collect(DB::select('SELECT
            //                                         COUNT(reporteiluminacionarea.id) AS TOTAL
            //                                     FROM
            //                                         reporteiluminacionarea
            //                                     WHERE
            //                                         reporteiluminacionarea.proyecto_id = '.$proyecto_id));

            // if (($total_areas[0]->TOTAL + 0) == 0)
            // {
            //     $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
            //                                                 ->orderBy('recsensorialarea_nombre', 'ASC')
            //                                                 ->get();

            //     DB::statement('ALTER TABLE reporteiluminacionarea AUTO_INCREMENT = 1;');

            //     foreach ($recsensorial_areas as $key => $value)
            //     {
            //         $area = reporteiluminacionareaModel::create([
            //               'proyecto_id' => $proyecto_id
            //             , 'recsensorialarea_id' => $value->id
            //             , 'reporteiluminacionarea_nombre' => $value->recsensorialarea_nombre
            //             , 'reporteiluminacionarea_instalacion' => $proyecto->proyecto_clienteinstalacion
            //         ]);
            //     }
            // }


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


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  $agente_nombre
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontabladefiniciones($proyecto_id, $agente_nombre, $reporteiluminacion_id)
    {
        try {
            // $reporteiluminacion = reporteiluminacionModel::where('id', $reporteiluminacion_id)->get();

            // $edicion = 1;
            // if (count($reporteiluminacion) > 0)
            // {
            //     if($reporteiluminacion[0]->reporteiluminacion_concluido == 1 || $reporteiluminacion[0]->reporteiluminacion_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


    /**
     * Display the specified resource.
     *
     * @param  int  $definicion_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciondefinicioneliminar($definicion_id)
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


    /**
     * Display the specified resource.
     *
     * @param  int  $reporteiluminacion_id
     * @param  int  $archivo_opcion
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionmapaubicacion($reporteiluminacion_id, $archivo_opcion)
    {
        $reporteiluminacion  = reporteiluminacionModel::findOrFail($reporteiluminacion_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporteiluminacion->reporteiluminacion_ubicacionfoto);
        } else {
            return Storage::download($reporteiluminacion->reporteiluminacion_ubicacionfoto);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacioncategorias($proyecto_id, $reporteiluminacion_id, $areas_poe)
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
                // $reporteiluminacion = reporteiluminacionModel::where('id', $reporteiluminacion_id)->get();


                // $edicion = 1;
                // if (count($reporteiluminacion) > 0)
                // {
                //     if($reporteiluminacion[0]->reporteiluminacion_concluido == 1 || $reporteiluminacion[0]->reporteiluminacion_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


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


                $categorias = reporteiluminacioncategoriaModel::where('proyecto_id', $proyecto_id)
                    ->where('registro_id', $reporteiluminacion_id)
                    ->orderBy('reporteiluminacioncategoria_nombre', 'ASC')
                    ->get();


                foreach ($categorias as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $value->reportecategoria_nombre = $value->reporteiluminacioncategoria_nombre;
                    $value->reportecategoria_total = $value->reporteiluminacioncategoria_total;
                    $value->reportecategoria_horas = $value->reporteiluminacioncategoria_horas;
                    $value->categoria_horas = $value->reporteiluminacioncategoria_horas . ' Hrs';


                    if (!$value->reporteiluminacioncategoria_total) {
                        $total_singuardar += 1;
                        $value->categoria_horas = NULL;
                    } else {
                        $value->categoria_horas = $value->reporteiluminacioncategoria_horas . ' Hrs';
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


    /**
     * Display the specified resource.
     *
     * @param  int  $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacioncategoriaeliminar($categoria_id)
    {
        try {
            $categoria = reporteiluminacioncategoriaModel::where('id', $categoria_id)->delete();

            // respuesta
            $dato["msj"] = 'Categoría eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionareas($proyecto_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $numero_registro2 = 0;
            $total_singuardar = 0;
            $instalacion = 'XXX';
            $area = 'XXX';
            $area2 = 'XXX';
            $selectareasoption = '';
            $tabla_5_4 = NULL;
            $tabla_5_5 = NULL;
            $tabla_6_1 = NULL;
            $tabla_6_2_1 = NULL;
            $dato['total_ic'] = 0;
            $tabla_6_2_2 = NULL;
            $dato['total_pt'] = 0;


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        reportearea.reportearea_instalacion,
                                        reportearea.reportearea_nombre,
                                        reportearea.reportearea_orden,
                                        -- reportearea.reportearea_porcientooperacion,
                                        reportearea.reporteiluminacionarea_porcientooperacion,
                                        IF( IFNULL( reportearea.reporteiluminacionarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteiluminacionarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                        reportearea.reportearea_puntos_ic,
                                        reportearea.reportearea_puntos_pt,
                                        reportearea.reportearea_sistemailuminacion,
                                        reportearea.reportearea_luznatural,
                                        reportearea.reportearea_iluminacionlocalizada,
                                        reportearea.reportearea_colorsuperficie,
                                        reportearea.reportearea_tiposuperficie,

                                        reportearea.reportearea_criterio,
                                        reportearea.reportearea_colortecho,
                                        reportearea.reportearea_paredes,
                                        reportearea.reportearea_colorpiso,
                                        reportearea.reportearea_superficietecho,
                                        reportearea.reportearea_superficieparedes,
                                        reportearea.reportearea_superficiepiso,
                                        reportearea.reportearea_potenciaslamparas,
                                        reportearea.reportearea_numlamparas,
                                        reportearea.reportearea_alturalamparas,
                                        reportearea.reportearea_programamantenimiento,
                                        reportearea.reportearea_tipoiluminacion,
                                        reportearea.reportearea_descripcionilimunacion,




                                        IF(reportearea.reportearea_largo > 0, reportearea.reportearea_largo, 1) AS reportearea_largo,
                                        IF(reportearea.reportearea_ancho > 0, reportearea.reportearea_ancho, 1) AS reportearea_ancho,
                                        IF(reportearea.reportearea_alto > 0, reportearea.reportearea_alto, 1) AS reportearea_alto,
                                        reporteareacategoria.reportecategoria_id,
                                        reportecategoria.reportecategoria_orden,
                                        reportecategoria.reportecategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                            FROM
                                                reporteiluminacionareacategoria
                                            WHERE
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                            LIMIT 1
                                        ), "") AS checked,
                                        reportecategoria.reportecategoria_horas,
                                        reporteareacategoria.reporteareacategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades,

                                        -- reporteareacategoria.reporteareacategoria_tareavisual,
                                        IFNULL((
                                            SELECT
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                            FROM
                                                reporteiluminacionareacategoria
                                            WHERE
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                            LIMIT 1
                                        ), "") AS reporteareacategoria_tareavisual
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


                        if (($value->reporteiluminacionarea_porcientooperacion + 0) > 0) {
                            $numero_registro2 += 1;


                            //TABLA 5.5.- Descripción del área iluminada y su sistema de iluminación
                            //==================================================

                            $tabla_5_5 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportearea_colorpiso . '</td>
                                                <td>' . $value->reportearea_superficiepiso . '</td>
                                                <td>' . $value->reportearea_tipoiluminacion . '</td>
                                                <td>' . $value->reportearea_sistemailuminacion . '</td>
                                            </tr>';


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reporteiluminacionarea_porcientooperacion . '%</td>
                                            </tr>';



                            // TOTAL PUNTOS INDICE DE AREA (TABLA 6.2.1)
                            $dato['total_ic'] += ($value->reportearea_puntos_ic + 0);

                            // TOTAL PUNTOS PUESTO DE TRABAJO (TABLA 6.2.2)
                            $dato['total_pt'] += ($value->reportearea_puntos_pt + 0);
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


                    if ($value->reportearea_puntos_ic === NULL) {
                        $total_singuardar += 1;
                    }


                    if (($value->reporteiluminacionarea_porcientooperacion + 0) > 0) {
                        if ($value->checked) {
                            //TABLA 5.4.- Actividades del personal expuesto
                            //==================================================

                            $tabla_5_4 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td>' . $value->reportearea_tipoiluminacion . '</td>
                                                <td>' . $value->reportecategoria_horas . ' Hrs.</td>
                                            </tr>';
                        }


                        if (($value->reportearea_puntos_ic + 0) > 0 && $value->checked == 'checked') {
                            //TABLA 6.2.1.- Índice de área
                            //==================================================


                            $indicearea = round((($value->reportearea_largo * $value->reportearea_ancho) / ($value->reportearea_alto * ($value->reportearea_largo + $value->reportearea_ancho))), 1);
                            $zonasminimas = NULL;
                            $zonasmaximas = NULL;


                            switch ($indicearea) {
                                case ($indicearea >= 3):
                                    $zonasminimas = 25;
                                    $zonasmaximas = 30;
                                    break;
                                case ($indicearea >= 2):
                                    $zonasminimas = 16;
                                    $zonasmaximas = 20;
                                    break;
                                case ($indicearea >= 1):
                                    $zonasminimas = 9;
                                    $zonasmaximas = 12;
                                    break;
                                case ($indicearea >= 0):
                                    $zonasminimas = 4;
                                    $zonasmaximas = 6;
                                    break;
                                default:
                                    $zonasminimas = 'N/A';
                                    $zonasmaximas = 'N/A';
                                    break;
                            }


                            $tabla_6_2_1 .= '<tr>
                                                <td>' . $value->reportearea_puntos_ic . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td>' . $indicearea . '</td>
                                                <td>' . $zonasminimas . '</td>
                                                <td>' . $zonasmaximas . '</td>
                                            </tr>';
                        }


                        if (($value->reportearea_puntos_pt + 0) > 0 && $value->checked == 'checked') {
                            //TABLA 6.2.2.- Puesto de trabajo
                            //==================================================


                            $tabla_6_2_2 .= '<tr>
                                                <td>' . $value->reportearea_puntos_pt . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_tareavisual . '</td>
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
                // $reporteiluminacion = reporteiluminacionModel::where('id', $reporteiluminacion_id)->get();


                // $edicion = 1;
                // if (count($reporteiluminacion) > 0)
                // {
                //     if($reporteiluminacion[0]->reporteiluminacion_concluido == 1 || $reporteiluminacion[0]->reporteiluminacion_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


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


                // $areas = reporteiluminacionareaModel::where('proyecto_id', $proyecto_id)
                //                                     ->where('registro_id', $reporteiluminacion_id)
                //                                     ->orderBy('reporteiluminacionarea_numorden', 'ASC')
                //                                     ->get();


                // if (count($areas) == 0)
                // {
                //     $areas = reporteiluminacionareaModel::where('proyecto_id', $proyecto_id)
                //                                         ->orderBy('reporteiluminacionarea_instalacion', 'ASC')
                //                                         ->orderBy('reporteiluminacionarea_numorden', 'ASC')
                //                                         ->orderBy('reporteiluminacionarea_nombre', 'ASC')
                //                                         ->get();
                // }


                $areas = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.registro_id,
                                        reporteiluminacionarea.id,
                                        reporteiluminacionarea.reporteiluminacionarea_instalacion AS reportearea_instalacion,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre AS reportearea_nombre,
                                        reporteiluminacionarea.reporteiluminacionarea_numorden AS reportearea_orden,
                                        reporteiluminacionarea.reporteiluminacionarea_porcientooperacion,
                                        reporteiluminacionarea.reporteiluminacionarea_puntos_ic AS reportearea_puntos_ic,
                                        reporteiluminacionarea.reporteiluminacionarea_puntos_pt AS reportearea_puntos_pt,
                                        reporteiluminacionarea.reporteiluminacionarea_sistemailuminacion AS reportearea_sistemailuminacion,
                                        reporteiluminacionarea.reporteiluminacionarea_luznatural AS reportearea_luznatural,
                                        reporteiluminacionarea.reporteiluminacionarea_iluminacionlocalizada AS reportearea_iluminacionlocalizada,
                                        reporteiluminacionarea.reporteiluminacionarea_colorsuperficie AS reportearea_colorsuperficie,
                                        reporteiluminacionarea.reporteiluminacionarea_tiposuperficie AS reportearea_tiposuperficie,

                                        reporteiluminacionarea.reporteiluminacionarea_criterio AS reportearea_criterio,
                                        reporteiluminacionarea.reporteiluminacionarea_colortecho AS reportearea_colortecho,
                                        reporteiluminacionarea.reporteiluminacionarea_paredes AS reportearea_paredes,
                                        reporteiluminacionarea.reporteiluminacionarea_colorpiso AS reportearea_colorpiso,
                                        reporteiluminacionarea.reporteiluminacionarea_superficietecho AS reportearea_superficietecho,
                                        reporteiluminacionarea.reporteiluminacionarea_superficieparedes AS reportearea_superficieparedes,
                                        reporteiluminacionarea.reporteiluminacionarea_superficiepiso AS reportearea_superficiepiso,
                                        reporteiluminacionarea.reporteiluminacionarea_potenciaslamparas AS reportearea_potenciaslamparas,
                                        reporteiluminacionarea.reporteiluminacionarea_numlamparas AS reportearea_numlamparas,
                                        reporteiluminacionarea.reporteiluminacionarea_alturalamparas AS reportearea_alturalamparas,
                                        reporteiluminacionarea.reporteiluminacionarea_programamantenimiento AS reportearea_programamantenimiento,
                                        reporteiluminacionarea.reporteiluminacionarea_tipoiluminacion AS reportearea_tipoiluminacion,
                                        reporteiluminacionarea.reporteiluminacionarea_descripcionilimunaciona AS reportearea_descripcionilimunacion,




                                     

                                        IF(reporteiluminacionarea.reporteiluminacionarea_largo > 0, reporteiluminacionarea.reporteiluminacionarea_largo, 1) AS reportearea_largo,
                                        IF(reporteiluminacionarea.reporteiluminacionarea_ancho > 0, reporteiluminacionarea.reporteiluminacionarea_ancho, 1) AS reportearea_ancho,
                                        IF(reporteiluminacionarea.reporteiluminacionarea_alto > 0, reporteiluminacionarea.reporteiluminacionarea_alto, 1) AS reportearea_alto,
                                        reporteiluminacionareacategoria.reporteiluminacioncategoria_id AS reportecategoria_id,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre AS reportecategoria_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_total AS reporteareacategoria_total,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo AS reporteareacategoria_geh,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_horas AS reportecategoria_horas,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades AS reporteareacategoria_actividades,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual AS reporteareacategoria_tareavisual 
                                        reporteiluminacionareacategoria.niveles_minimo AS niveles_minimo 

                                    FROM
                                        reporteiluminacionarea
                                        INNER JOIN reporteiluminacionareacategoria ON reporteiluminacionarea.id = reporteiluminacionareacategoria.reporteiluminacionarea_id
                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');


                foreach ($areas as $key => $value) {
                    if ($area != $value->reportearea_nombre) {
                        $area = $value->reportearea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if (($value->reporteiluminacionarea_porcientooperacion + 0) > 0) {
                            $numero_registro2 += 1;


                            //TABLA 5.5.- Descripción del área iluminada y su sistema de iluminación
                            //==================================================

                            $tabla_5_5 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportearea_colorsuperficie . '</td>
                                                <td>' . $value->reportearea_tiposuperficie . '</td>
                                                <td>' . $value->reportearea_luznatural . '</td>
                                                <td>' . $value->reportearea_sistemailuminacion . '</td>
                                            </tr>';


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro2 . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reporteiluminacionarea_porcientooperacion . '%</td>
                                            </tr>';


                            // TOTAL PUNTOS INDICE DE AREA (TABLA 6.2.1)
                            $dato['total_ic'] += ($value->reportearea_puntos_ic + 0);


                            // TOTAL PUNTOS PUESTO DE TRABAJO (TABLA 6.2.2)
                            $dato['total_pt'] += ($value->reportearea_puntos_pt + 0);
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


                    if ($value->reporteiluminacionarea_porcientooperacion === NULL) {
                        $total_singuardar += 1;
                    }


                    if (($value->reporteiluminacionarea_porcientooperacion + 0) > 0) {

                        //TABLA 5.4.- Actividades del personal expuesto
                        //==================================================

                        $tabla_5_4 .= '<tr>
                                            <td>' . $numero_registro . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportecategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                            <td>' . $value->reportearea_luznatural . '</td>
                                            <td>' . $value->reportearea_iluminacionlocalizada . '</td>
                                            <td>' . $value->reportecategoria_horas . ' Hrs.</td>
                                        </tr>';


                        if (($value->reportearea_puntos_ic + 0) > 0) {
                            //TABLA 6.2.1.- Índice de área
                            //==================================================


                            $indicearea = round((($value->reportearea_largo * $value->reportearea_ancho) / ($value->reportearea_alto * ($value->reportearea_largo + $value->reportearea_ancho))), 1);
                            $zonasminimas = NULL;
                            $zonasmaximas = NULL;


                            switch ($indicearea) {
                                case ($indicearea >= 3):
                                    $zonasminimas = 25;
                                    $zonasmaximas = 30;
                                    break;
                                case ($indicearea >= 2):
                                    $zonasminimas = 16;
                                    $zonasmaximas = 20;
                                    break;
                                case ($indicearea >= 1):
                                    $zonasminimas = 9;
                                    $zonasmaximas = 12;
                                    break;
                                case ($indicearea >= 0):
                                    $zonasminimas = 4;
                                    $zonasmaximas = 6;
                                    break;
                                default:
                                    $zonasminimas = 'N/A';
                                    $zonasmaximas = 'N/A';
                                    break;
                            }


                            $tabla_6_2_1 .= '<tr>
                                                <td>' . $value->reportearea_puntos_ic . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td>' . $indicearea . '</td>
                                                <td>' . $zonasminimas . '</td>
                                                <td>' . $zonasmaximas . '</td>
                                            </tr>';
                        }


                        if (($value->reportearea_puntos_pt + 0) > 0) {
                            //TABLA 6.2.2.- Puesto de trabajo
                            //==================================================


                            $tabla_6_2_2 .= '<tr>
                                                <td>' . $value->reportearea_puntos_pt . '</td>
                                                <td>' . $value->reportearea_instalacion . '</td>
                                                <td>' . $value->reportearea_nombre . '</td>
                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                                <td class="justificado">' . $value->reporteareacategoria_tareavisual . '</td>
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
            }


            // respuesta
            $dato['data'] = $areas;
            $dato["tabla_5_4"] = $tabla_5_4;
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["tabla_6_2_1"] = $tabla_6_2_1;
            $dato["tabla_6_2_2"] = $tabla_6_2_2;

            $dato["total_singuardar"] = $total_singuardar;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["tabla_5_4"] = NULL;
            $dato["tabla_5_5"] = NULL;
            $dato["tabla_6_1"] = NULL;
            $dato["tabla_6_2_1"] = NULL;
            $dato["tabla_6_2_2"] = NULL;

            $dato['total_ic'] = 0;
            $dato['total_pt'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["selectareasoption"] = '';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $area_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionareascategorias($proyecto_id, $reporteiluminacion_id, $area_id, $areas_poe)
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
                                                        IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "")
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteareacategoria.reportearea_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reportecategoria.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reporteareacategoria.reporteareacategoria_total AS categoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS categoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades AS categoria_actividades,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteareacategoria.reportearea_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reportecategoria.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS categoria_tareavisual,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.niveles_minimo
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteareacategoria.reportearea_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reportecategoria.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS niveles
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
                                                <td width="80">
                                                <select class="custom-select form-control" onchange="cambiarTareaVisual(this.value, ' . $numero_registro . ')" id="select_niveles_' . $numero_registro . '" name="niveles_minimo_' . $value->id . '"  value="' . $value->niveles . '" >
                                                    <option value=""></option>
                                                    <option value="1">20</option>
                                                    <option value="2">50</option>
                                                    <option value="3">100</option>
                                                    <option value="4">200</option>
                                                    <option value="5">300</option>
                                                    <option value="6">500</option>
                                                    <option value="7">750</option>
                                                    <option value="8">1000</option>
                                                    <option value="9">2000</option>

                                                </select>
                                                </td>
                                                <td width="180">
                                                    <textarea rows="2" class="form-control" id="textarea_tareavisual_' . $numero_registro . '"  name="reporteareacategoria_tareavisual_' . $value->id . '" ' . $readonly_required . '>' . $value->categoria_tareavisual . '</textarea>
                                                </td>
                                            </tr>';
                }
            } else {
                $areacategorias = DB::select('SELECT
                                                    reporteiluminacioncategoria.id,
                                                    reporteiluminacioncategoria.proyecto_id,
                                                    reporteiluminacioncategoria.recsensorialcategoria_id,
                                                    reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                    reporteiluminacioncategoria.reporteiluminacioncategoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS checked,
                                                    IFNULL((
                                                        SELECT
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_total
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_geh,
                                                    IFNULL((
                                                        SELECT
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_actividades,
                                                    IFNULL((
                                                        SELECT
                                                            reporteiluminacionareacategoria.niveles_minimo
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS niveles,
                                                    IFNULL((
                                                        SELECT
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                        WHERE
                                                            reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . '
                                                            AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                        LIMIT 1
                                                    ), "") AS categoria_tareavisual
                                                FROM
                                                    reporteiluminacioncategoria
                                                WHERE
                                                    reporteiluminacioncategoria.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteiluminacioncategoria.registro_id = ' . $reporteiluminacion_id . '
                                                ORDER BY
                                                    reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');


                $readonly_required = '';
                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;


                    if ($value->checked) {
                        $readonly_required = 'required';
                    } else {
                        $readonly_required = 'readonly';
                    }


                    if (!$value->categoria_total) {
                        $value->categoria_total = $value->reporteiluminacioncategoria_total;
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
                                                    ' . $value->reporteiluminacioncategoria_nombre . '
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
                                                 <td width="80">
                                                <select class="custom-select form-control" id="select_niveles_' . $numero_registro . '" name="niveles_minimo_' . $value->id . '">
                                                    <option value=""></option>
                                                    <option value="1">20</option>
                                                    <option value="2">50</option>
                                                    <option value="3">100</option>
                                                    <option value="4">200</option>
                                                    <option value="5">300</option>
                                                    <option value="6">500</option>
                                                    <option value="7">750</option>
                                                    <option value="8">1000</option>
                                                    <option value="9">2000</option>

                                                </select>
                                                </td>
                                               <td width="180">
                                                    <textarea rows="2" class="form-control" id="textarea_tareavisual_' . $numero_registro . '" name="reporteareacategoria_tareavisual_' . $value->id . '" ' . $readonly_required . '>' . $value->categoria_tareavisual . '</textarea>
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


    /**
     * Display the specified resource.
     *
     * @param int $area_id
     * @param int $categoria_id
     * @param int $reporteiluminacion_id
     * @param int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionareascategoriasconsultar($area_id, $categoria_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            $categoriasoption = '<option value=""></option>';


            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                                reportecategoria.reportecategoria_nombre,
                                                reportecategoria.reportecategoria_orden 
                                            FROM
                                                reporteiluminacionareacategoria
                                                LEFT JOIN reportecategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reportecategoria.id
                                            WHERE
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . ' 
                                                AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC');


                foreach ($categorias as $key => $value) {
                    if ($categoria_id == $value->reporteiluminacioncategoria_id) {
                        $categoriasoption .= '<option value="' . $value->reporteiluminacioncategoria_id . '" selected>' . $value->reportecategoria_nombre . '</option>';
                    } else {
                        $categoriasoption .= '<option value="' . $value->reporteiluminacioncategoria_id . '">' . $value->reportecategoria_nombre . '</option>';
                    }
                }
            } else {
                $categorias = DB::select('SELECT
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                                reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre 
                                            FROM
                                                reporteiluminacionareacategoria
                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                            WHERE
                                                reporteiluminacionareacategoria.reporteiluminacionarea_id = ' . $area_id . ' 
                                                -- AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                            ORDER BY
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');


                foreach ($categorias as $key => $value) {
                    if ($categoria_id == $value->reporteiluminacioncategoria_id) {
                        $categoriasoption .= '<option value="' . $value->reporteiluminacioncategoria_id . '" selected>' . $value->reporteiluminacioncategoria_nombre . '</option>';
                    } else {
                        $categoriasoption .= '<option value="' . $value->reporteiluminacioncategoria_id . '">' . $value->reporteiluminacioncategoria_nombre . '</option>';
                    }
                }
            }


            // respuesta
            $dato['categoriasoption'] = $categoriasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['categoriasoption'] = '<option value=""></option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $area_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionareaeliminar($area_id)
    {
        try {
            $area = reporteiluminacionareaModel::where('id', $area_id)->delete();

            $areacategorias = reporteiluminacionareacategoriaModel::where('reporteiluminacionarea_id', $area_id)
                ->where('reporteiluminacionareacategoria_poe', 0)
                ->delete();

            // respuesta
            $dato["msj"] = 'Área eliminada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteiluminacion_id
     * @param int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablapuntos($proyecto_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            // $reporteiluminacion = reporteiluminacionModel::where('id', $reporteiluminacion_id)->get();


            // $edicion = 1;
            // if (count($reporteiluminacion) > 0)
            // {
            //     if($reporteiluminacion[0]->reporteiluminacion_concluido == 1 || $reporteiluminacion[0]->reporteiluminacion_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //------------------------------------------


            if (($areas_poe + 0) == 1) {
                $puntos = DB::select('SELECT
                                            reporteiluminacionpuntos.proyecto_id,
                                            reporteiluminacionpuntos.registro_id,
                                            reporteiluminacionpuntos.id,
                                            reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                            reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                            reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3 
                                        FROM
                                            reporteiluminacionpuntos
                                            LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                        WHERE
                                            reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                        ORDER BY
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC');
            } else {
                $puntos = DB::select('SELECT
                                            reporteiluminacionpuntos.id,
                                            reporteiluminacionpuntos.proyecto_id,
                                            reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                            reporteiluminacionarea.reporteiluminacionarea_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                            reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3 
                                        FROM
                                            reporteiluminacionpuntos
                                            LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                            LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                        WHERE
                                            reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                            AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                        ORDER BY
                                            reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC');
            }


            $numero_registro = 0;
            foreach ($puntos as $key => $value) {
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


    /**
     * Display the specified resource.
     *
     * @param  int  $punto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablapuntoseliminar($punto_id)
    {
        try {
            $punto = reporteiluminacionpuntosModel::where('id', $punto_id)->delete();

            // respuesta
            $dato["msj"] = 'Punto de iluminación eliminado correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablaresultados($proyecto_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $tabla = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacionpuntos_categoria_id,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_fechaeval,
                                        reporteiluminacionpuntos_horario1,
                                        reporteiluminacionpuntos_horario2,
                                        reporteiluminacionpuntos_horario3,
                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,        
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,

                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#00FF00", "#FF0000"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frp_resultado_color, 
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frpt_resultado_color,

                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                            END
                                        ) AS fr_resultado,
                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#00FF00", "#FF0000")
                                            END
                                        ) AS fr_resultado_color
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                )  AS total_frpt
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            } else {
                $tabla = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacionpuntos_categoria_id,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_fechaeval,
                                        reporteiluminacionpuntos_horario1,
                                        reporteiluminacionpuntos_horario2,
                                        reporteiluminacionpuntos_horario3,
                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,        
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,

                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#00FF00", "#FF0000"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frp_resultado_color, 
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frpt_resultado_color,

                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                            END
                                        ) AS fr_resultado,
                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#00FF00", "#FF0000")
                                            END
                                        ) AS fr_resultado_color
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                )  AS total_frpt
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            }


            $numero_registro = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
            }


            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablamatrizexposicion($proyecto_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            if (($areas_poe + 0) == 1) {
                $tabla = DB::select('SELECT
                                            id,
                                            proyecto_id,
                                            IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                            IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                            IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                            IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                            (
                                                CASE
                                                    WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) = "" THEN catactivo_nombre
                                                    ELSE catgerencia_nombre
                                                END
                                            ) AS gerencia_activo,
                                            proyecto_clienteinstalacion,
                                            reporteiluminacionarea_instalacion,
                                            reporteiluminacionpuntos_area_id,
                                            reporteiluminacionarea_nombre,
                                            reporteiluminacioncategoria_nombre,
                                            reporteiluminacionpuntos_nombre,
                                            reporteiluminacionpuntos_ficha,
                                            reporteiluminacionareacategoria_total,
                                            reporteiluminacionareacategoria_geo,
                                            reporteiluminacionpuntos_nopoe,
                                            reporteiluminacionpuntos_nopunto,
                                            reporteiluminacionpuntos_concepto,
                                            reporteiluminacionpuntos_fechaeval,
                                            reporteiluminacionpuntos_horario1,
                                            reporteiluminacionpuntos_horario2,
                                            reporteiluminacionpuntos_horario3,
                                            
                                            IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux, 
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,
                                            
                                            (
                                                CASE
                                                    WHEN reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed2 = 0, 1000000, reporteiluminacionpuntos_luxmed2)
                                                        AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                    THEN reporteiluminacionpuntos_luxmed1
                                                    WHEN
                                                        reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed1 = 0, 1000000, reporteiluminacionpuntos_luxmed1) 
                                                        AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                    THEN reporteiluminacionpuntos_luxmed2
                                                    ELSE reporteiluminacionpuntos_luxmed3
                                                END
                                            ) AS lux_resultado_critico,
                                            IF(lux = 1, IF(total_lux = 3, "Si cumple", "No cumple"), "N/A") AS lux_resultado,
                                            IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                            
                                            IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,       
                                            
                                            IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3
                                        FROM
                                            (
                                                SELECT
                                                    reporteiluminacionpuntos.id,
                                                    reporteiluminacionpuntos.proyecto_id,
                                                    catregion.catregion_nombre,
                                                    catsubdireccion.catsubdireccion_nombre,
                                                    catgerencia.catgerencia_nombre,
                                                    catactivo.catactivo_nombre,
                                                    proyecto.proyecto_clienteinstalacion,
                                                    reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                    reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                    reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                    reporteareacategoria.reporteareacategoria_total AS reporteiluminacionareacategoria_total,
                                                    reporteareacategoria.reporteareacategoria_geh AS reporteiluminacionareacategoria_geo,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS lux,
                                                    (
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                        +
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                        +
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                    ) AS total_lux,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS frp,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS frpt 
                                                FROM
                                                    reporteiluminacionpuntos
                                                    LEFT JOIN proyecto ON reporteiluminacionpuntos.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                                    LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                    AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                                WHERE
                                                    reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                                ORDER BY
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                            ) AS TABLA');
            } else {
                $tabla = DB::select('SELECT
                                            id,
                                            proyecto_id,
                                            IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                            IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                            IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                            IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                            (
                                                CASE
                                                    WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) = "" THEN catactivo_nombre
                                                    ELSE catgerencia_nombre
                                                END
                                            ) AS gerencia_activo,
                                            proyecto_clienteinstalacion,
                                            reporteiluminacionarea_instalacion,
                                            reporteiluminacionpuntos_area_id,
                                            reporteiluminacionarea_nombre,
                                            reporteiluminacioncategoria_nombre,
                                            reporteiluminacionpuntos_nombre,
                                            reporteiluminacionpuntos_ficha,
                                            reporteiluminacionareacategoria_total,
                                            reporteiluminacionareacategoria_geo,
                                            reporteiluminacionpuntos_nopoe,
                                            reporteiluminacionpuntos_nopunto,
                                            reporteiluminacionpuntos_concepto,
                                            reporteiluminacionpuntos_fechaeval,
                                            reporteiluminacionpuntos_horario1,
                                            reporteiluminacionpuntos_horario2,
                                            reporteiluminacionpuntos_horario3,
                                            
                                            IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux, 
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                            -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                            IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,
                                            
                                            (
                                                CASE
                                                    WHEN reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed2 = 0, 1000000, reporteiluminacionpuntos_luxmed2)
                                                        AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                    THEN reporteiluminacionpuntos_luxmed1
                                                    WHEN
                                                        reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed1 = 0, 1000000, reporteiluminacionpuntos_luxmed1) 
                                                        AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                    THEN reporteiluminacionpuntos_luxmed2
                                                    ELSE reporteiluminacionpuntos_luxmed3
                                                END
                                            ) AS lux_resultado_critico,
                                            IF(lux = 1, IF(total_lux = 3, "Si cumple", "No cumple"), "N/A") AS lux_resultado,
                                            IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                            
                                            IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                            IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,       
                                            
                                            IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                            IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3
                                        FROM
                                            (
                                                SELECT
                                                    reporteiluminacionpuntos.id,
                                                    reporteiluminacionpuntos.proyecto_id,
                                                    catregion.catregion_nombre,
                                                    catsubdireccion.catsubdireccion_nombre,
                                                    catgerencia.catgerencia_nombre,
                                                    catactivo.catactivo_nombre,
                                                    proyecto.proyecto_clienteinstalacion,
                                                    reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                    reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                    reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_total,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS lux,
                                                    (
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                        +
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                        +
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                ELSE 0
                                                            END
                                                        )
                                                    ) AS total_lux,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS frp,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3,
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                            ELSE 1
                                                        END
                                                    ) AS frpt   
                                                FROM
                                                    reporteiluminacionpuntos
                                                    LEFT JOIN proyecto ON reporteiluminacionpuntos.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                    LEFT JOIN reporteiluminacionareacategoria ON reporteiluminacionarea.id = reporteiluminacionareacategoria.reporteiluminacionarea_id 
                                                    AND reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacionareacategoria.reporteiluminacioncategoria_id
                                                    LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id 
                                                WHERE
                                                    reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                                    -- AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0 
                                                ORDER BY
                                                    reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                            ) AS TABLA');
            }



            if (($proyecto->catregion_id) == 1) //REGION NORTE
            {
                $numero_registro = 0;
                foreach ($tabla as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;
                }
            } else {
                $numero_registro = 0;
                foreach ($tabla as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $value->iluminacion_resultado = $value->lux_resultado_critico . ' / ' . $value->lux . ' / ' . $value->lux_resultado;
                }
            }


            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciondashboard($proyecto_id, $reporteiluminacion_id, $areas_poe)
    {
        try {
            $areas_cumplimiento = '';
            $categorias_criticas = '';
            $iluminacion_datos = NULL;
            $reflexion_datos = NULL;


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                        reporteiluminacionarea_nombre,
                                        
                                         -- COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL)) AS total_iluminacion,
                                         -- COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) AS total_iluminacion_dentronorma,
                                         -- COUNT(IF(lux_resultado = "Fuera de norma", lux_resultado, NULL)) AS total_iluminacion_fueranorma,
                                         -- CONCAT(IFNULL((
                                         --     ROUND((ROUND(COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) / COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL)), 3) * 100), 1)
                                         -- ), 0), " %") AS iluminacion_porcentaje_cumplimiento,
                                         
                                         -- COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL)) AS total_reflexion,
                                         -- COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL)) AS total_reflexion_dentronorma,
                                         -- COUNT(IF(fr_resultado = "Fuera de norma", fr_resultado, NULL)) AS total_reflexion_fueranorma,
                                         -- CONCAT(IFNULL((
                                         --     ROUND((ROUND(COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL)) / COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL)), 3) * 100), 1)
                                         -- ), 0), " %") AS reflexion_porcentaje_cumplimiento,
                                        
                                        (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))) AS total,
                                        (COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) AS total_dentronorma,
                                        (COUNT(IF(lux_resultado = "Fuera de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Fuera de norma", fr_resultado, NULL))) AS total_fueranorma,
                                        IFNULL((
                                            ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)
                                        ), 0) AS porcentaje_cumplimiento,
                                        (
                                            CASE
                                                WHEN IFNULL((ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)), 0) >= 90 THEN "#8ee66b"
                                                WHEN IFNULL((ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)), 0) >= 50 THEN "#ffb22b"
                                                ELSE "#fc4b6c"
                                            END
                                        ) AS color
                                    FROM
                                        (
                                            SELECT
                                                id,
                                                proyecto_id,
                                                reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos_area_id,
                                                reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos_categoria_id,
                                                reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos_horario3,
                                                --  reporteiluminacionpuntos_luxmed1mayor,
                                                --  reporteiluminacionpuntos_luxmed2mayor,
                                                --  reporteiluminacionpuntos_luxmed3mayor,
                                                IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,        
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                
                                                IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                
                                                IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                (
                                                    CASE
                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                    END
                                                ) AS fr_resultado,
                                                (
                                                    CASE
                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                    END
                                                ) AS fr_resultado_color
                                            FROM
                                                (
                                                    SELECT
                                                        reporteiluminacionpuntos.id,
                                                        reporteiluminacionpuntos.proyecto_id,
                                                        reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                        reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                        reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS lux,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        ) AS total_lux,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS frp,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        ) AS total_frp,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS frpt,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        )  AS total_frpt
                                                    FROM
                                                        reporteiluminacionpuntos
                                                        LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                        LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                                    WHERE
                                                        reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                        AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                    ORDER BY
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                ) AS TABLA
                                        ) AS RESULTADO
                                    GROUP BY
                                        reporteiluminacionarea_nombre');


                if (count($areas) > 11) {
                    $col = 'col-4';
                } else if (count($areas) > 6) {
                    $col = 'col-6';
                } else {
                    $col = 'col-12';
                }


                foreach ($areas as $key => $value) {
                    $areas_cumplimiento .= '<div class="' . $col . '" style="display: inline-block; text-align: left;">
                                                <h6 class="m-t-30" style="margin: 0px; font-size:0.6vw;">' . $value->reporteiluminacionarea_nombre . ' <span class="pull-right">' . $value->porcentaje_cumplimiento . '%</span></h6>
                                                <div class="progress" style="margin-bottom: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: ' . $value->porcentaje_cumplimiento . '%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>';
                }


                //-------------------------------------


                $categorias = collect(DB::select('SELECT
                                                        proyecto_id,
                                                        reporteiluminacioncategoria_nombre
                                                        -- lux_resultado,
                                                        -- fr_resultado
                                                    FROM
                                                        (
                                                            SELECT
                                                                id,
                                                                proyecto_id,
                                                                reporteiluminacionarea_instalacion,
                                                                -- reporteiluminacionpuntos_area_id,
                                                                reporteiluminacionarea_nombre,
                                                                -- reporteiluminacionpuntos_categoria_id,
                                                                reporteiluminacioncategoria_nombre,
                                                                -- reporteiluminacionpuntos_nombre,
                                                                -- reporteiluminacionpuntos_ficha,
                                                                -- reporteiluminacionpuntos_nopunto,
                                                                -- reporteiluminacionpuntos_concepto,
                                                                -- reporteiluminacionpuntos_nopoe,
                                                                -- reporteiluminacionpuntos_fechaeval,
                                                                -- reporteiluminacionpuntos_horario1,
                                                                -- reporteiluminacionpuntos_horario2,
                                                                -- reporteiluminacionpuntos_horario3,
                                                                --  reporteiluminacionpuntos_luxmed1mayor,
                                                                --  reporteiluminacionpuntos_luxmed2mayor,
                                                                --  reporteiluminacionpuntos_luxmed3mayor,
                                                                -- IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                                IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                                -- IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                                
                                                                -- IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                                -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                                -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                                
                                                                -- IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                                -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                                -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                                (
                                                                    CASE
                                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                                    END
                                                                ) AS fr_resultado
                                                                -- ,(
                                                                --     CASE
                                                                --         WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                                --         ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                                --     END
                                                                -- ) AS fr_resultado_color
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        reporteiluminacionpuntos.id,
                                                                        reporteiluminacionpuntos.proyecto_id,
                                                                        reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                                        reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                                        reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS lux,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frp,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frpt,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        )  AS total_frpt
                                                                    FROM
                                                                        reporteiluminacionpuntos
                                                                        LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                                        LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                                                    WHERE
                                                                        reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                                        AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                                    ORDER BY
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                                ) AS TABLA
                                                        ) AS RESULTADO
                                                    WHERE
                                                        lux_resultado = "Fuera de norma" OR fr_resultado = "Fuera de norma"
                                                    GROUP BY
                                                        proyecto_id,
                                                        reporteiluminacioncategoria_nombre
                                                        -- ,lux_resultado,
                                                        -- fr_resultado'));


                foreach ($categorias as $key => $value) {
                    $categorias_criticas .= '<span style="margin: 10px 0px; text-align: justify; font-size:0.6vw;">♦ ' . $value->reporteiluminacioncategoria_nombre . '</span><br>';
                }


                //-------------------------------------


                $resultados = collect(DB::select('CALL sp_reporteiluminaciondashboard_b (?,?)', [$proyecto_id, $reporteiluminacion_id]));

                if (count($resultados) > 0) {
                    $iluminacion_datos[] = array(
                        'titulo' => "Dentro de norma",
                        'total' => $resultados[0]->total_iluminacion_dentronorma
                    );

                    $iluminacion_datos[] = array(
                        'titulo' => "Fuera de norma",
                        'total' => $resultados[0]->total_iluminacion_fueranorma
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Dentro de norma",
                        'total' => $resultados[0]->total_reflexion_dentronorma
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Fuera de norma",
                        'total' => $resultados[0]->total_reflexion_fueranorma
                    );

                    $dato['datos'] = array(
                        'total_iluminacion' => $resultados[0]->total_iluminacion,
                        'total_iluminacion_dentronorma' => $resultados[0]->total_iluminacion_dentronorma,
                        'total_iluminacion_fueranorma' => $resultados[0]->total_iluminacion_fueranorma,
                        'total_reflexion' => $resultados[0]->total_reflexion,
                        'total_reflexion_dentronorma' => $resultados[0]->total_reflexion_dentronorma,
                        'total_reflexion_fueranorma' => $resultados[0]->total_reflexion_fueranorma,
                        'nivel_iluminacion' => $resultados[0]->nivel_iluminacion,
                        'recomendaciones_total' => $resultados[0]->recomendaciones_total
                    );
                } else {
                    $iluminacion_datos[] = array(
                        'titulo' => "Sin evaluar",
                        'total' => 100.1
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Sin evaluar",
                        'total' => 100.1
                    );

                    $dato['datos'] = array(
                        'total_iluminacion' => 0,
                        'total_iluminacion_dentronorma' => 0,
                        'total_iluminacion_fueranorma' => 0,
                        'total_reflexion' => 0,
                        'total_reflexion_dentronorma' => 0,
                        'total_reflexion_fueranorma' => 0,
                        'nivel_iluminacion' => 0,
                        'recomendaciones_total' => 0
                    );
                }
            } else {
                $areas = DB::select('SELECT
                                        reporteiluminacionarea_nombre,
                                        
                                         -- COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL)) AS total_iluminacion,
                                         -- COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) AS total_iluminacion_dentronorma,
                                         -- COUNT(IF(lux_resultado = "Fuera de norma", lux_resultado, NULL)) AS total_iluminacion_fueranorma,
                                         -- CONCAT(IFNULL((
                                         --     ROUND((ROUND(COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) / COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL)), 3) * 100), 1)
                                         -- ), 0), " %") AS iluminacion_porcentaje_cumplimiento,
                                         
                                         -- COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL)) AS total_reflexion,
                                         -- COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL)) AS total_reflexion_dentronorma,
                                         -- COUNT(IF(fr_resultado = "Fuera de norma", fr_resultado, NULL)) AS total_reflexion_fueranorma,
                                         -- CONCAT(IFNULL((
                                         --     ROUND((ROUND(COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL)) / COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL)), 3) * 100), 1)
                                         -- ), 0), " %") AS reflexion_porcentaje_cumplimiento,
                                        
                                        (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))) AS total,
                                        (COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) AS total_dentronorma,
                                        (COUNT(IF(lux_resultado = "Fuera de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Fuera de norma", fr_resultado, NULL))) AS total_fueranorma,
                                        IFNULL((
                                            ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)
                                        ), 0) AS porcentaje_cumplimiento,
                                        (
                                            CASE
                                                WHEN IFNULL((ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)), 0) >= 90 THEN "#8ee66b"
                                                WHEN IFNULL((ROUND((ROUND((COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) + COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL))) / (COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL))  + COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL))), 3) * 100), 1)), 0) >= 50 THEN "#ffb22b"
                                                ELSE "#fc4b6c"
                                            END
                                        ) AS color
                                    FROM
                                        (
                                            SELECT
                                                id,
                                                proyecto_id,
                                                reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos_area_id,
                                                reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos_categoria_id,
                                                reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos_horario3,
                                                --  reporteiluminacionpuntos_luxmed1mayor,
                                                --  reporteiluminacionpuntos_luxmed2mayor,
                                                --  reporteiluminacionpuntos_luxmed3mayor,
                                                IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,        
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                
                                                IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                
                                                IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                (
                                                    CASE
                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                    END
                                                ) AS fr_resultado,
                                                (
                                                    CASE
                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                    END
                                                ) AS fr_resultado_color
                                            FROM
                                                (
                                                    SELECT
                                                        reporteiluminacionpuntos.id,
                                                        reporteiluminacionpuntos.proyecto_id,
                                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS lux,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        ) AS total_lux,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS frp,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        ) AS total_frp,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                        (
                                                            CASE
                                                                WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                ELSE 1
                                                            END
                                                        ) AS frpt,
                                                        (
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                            +
                                                            (
                                                                CASE
                                                                    WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                    WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                    WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                    ELSE 0
                                                                END
                                                            )
                                                        )  AS total_frpt
                                                    FROM
                                                        reporteiluminacionpuntos
                                                        LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                                    WHERE
                                                        reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                        AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                    ORDER BY
                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                ) AS TABLA
                                        ) AS RESULTADO
                                    GROUP BY
                                        reporteiluminacionarea_nombre');



                if (count($areas) > 11) {
                    $col = 'col-4';
                } else if (count($areas) > 6) {
                    $col = 'col-6';
                } else {
                    $col = 'col-12';
                }


                foreach ($areas as $key => $value) {
                    $areas_cumplimiento .= '<div class="' . $col . '" style="display: inline-block; text-align: left;">
                                                <h6 class="m-t-30" style="margin: 0px; font-size:0.6vw;">' . $value->reporteiluminacionarea_nombre . ' <span class="pull-right">' . $value->porcentaje_cumplimiento . '%</span></h6>
                                                <div class="progress" style="margin-bottom: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: ' . $value->porcentaje_cumplimiento . '%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>';
                }


                //-------------------------------------


                $categorias = collect(DB::select('SELECT
                                                        proyecto_id,
                                                        reporteiluminacioncategoria_nombre
                                                        -- lux_resultado,
                                                        -- fr_resultado
                                                    FROM
                                                        (
                                                            SELECT
                                                                id,
                                                                proyecto_id,
                                                                reporteiluminacionarea_instalacion,
                                                                -- reporteiluminacionpuntos_area_id,
                                                                reporteiluminacionarea_nombre,
                                                                -- reporteiluminacionpuntos_categoria_id,
                                                                reporteiluminacioncategoria_nombre,
                                                                -- reporteiluminacionpuntos_nombre,
                                                                -- reporteiluminacionpuntos_ficha,
                                                                -- reporteiluminacionpuntos_nopunto,
                                                                -- reporteiluminacionpuntos_concepto,
                                                                -- reporteiluminacionpuntos_nopoe,
                                                                -- reporteiluminacionpuntos_fechaeval,
                                                                -- reporteiluminacionpuntos_horario1,
                                                                -- reporteiluminacionpuntos_horario2,
                                                                -- reporteiluminacionpuntos_horario3,
                                                                --  reporteiluminacionpuntos_luxmed1mayor,
                                                                --  reporteiluminacionpuntos_luxmed2mayor,
                                                                --  reporteiluminacionpuntos_luxmed3mayor,
                                                                -- IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                                -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                                IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                                -- IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                                
                                                                -- IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                                -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                                -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                                -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                                
                                                                -- IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                                -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                                -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                                -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                                (
                                                                    CASE
                                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                                    END
                                                                ) AS fr_resultado
                                                                -- ,(
                                                                --     CASE
                                                                --         WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                                --         ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                                --     END
                                                                -- ) AS fr_resultado_color
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        reporteiluminacionpuntos.id,
                                                                        reporteiluminacionpuntos.proyecto_id,
                                                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS lux,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frp,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frpt,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        )  AS total_frpt
                                                                    FROM
                                                                        reporteiluminacionpuntos
                                                                        LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                                                    WHERE
                                                                        reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                                        AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                                    ORDER BY
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                                ) AS TABLA
                                                        ) AS RESULTADO
                                                    WHERE
                                                        lux_resultado = "Fuera de norma" OR fr_resultado = "Fuera de norma"
                                                    GROUP BY
                                                        proyecto_id,
                                                        reporteiluminacioncategoria_nombre
                                                        -- ,lux_resultado,
                                                        -- fr_resultado'));


                foreach ($categorias as $key => $value) {
                    $categorias_criticas .= '<span style="margin: 10px 0px; text-align: justify; font-size:0.6vw;">♦ ' . $value->reporteiluminacioncategoria_nombre . '</span><br>';
                }


                //-------------------------------------


                $resultados = collect(DB::select('SELECT
                                                        RESULTADO.proyecto_id,
                                                        COUNT(IF(lux_resultado != "N/A", lux_resultado, NULL)) AS total_iluminacion,
                                                        COUNT(IF(lux_resultado = "Dentro de norma", lux_resultado, NULL)) AS total_iluminacion_dentronorma,
                                                        COUNT(IF(lux_resultado = "Fuera de norma", lux_resultado, NULL)) AS total_iluminacion_fueranorma,
                                                        COUNT(IF(fr_resultado != "N/A", fr_resultado, NULL)) AS total_reflexion,
                                                        COUNT(IF(fr_resultado = "Dentro de norma", fr_resultado, NULL)) AS total_reflexion_dentronorma,
                                                        COUNT(IF(fr_resultado = "Fuera de norma", fr_resultado, NULL)) AS total_reflexion_fueranorma,
                                                        
                                                        CONCAT(
                                                             (
                                                                CASE
                                                                    WHEN 
                                                                        MIN(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) < MIN(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) 
                                                                        AND MIN(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) < MIN(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL)) 
                                                                        THEN MIN(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL))
                                                                    WHEN 
                                                                        MIN(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) < MIN(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) 
                                                                        AND MIN(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) < MIN(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL)) 
                                                                        THEN MIN(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL))
                                                                    ELSE 
                                                                        MIN(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL))
                                                                END
                                                             ),
                                                             "-",
                                                             (
                                                                CASE
                                                                    WHEN 
                                                                        MAX(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) > MAX(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) 
                                                                        AND MAX(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) > MAX(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL)) 
                                                                        THEN MAX(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL))
                                                                    WHEN 
                                                                        MAX(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) > MAX(IF(reporteiluminacionpuntos_luxmed1 > 0, reporteiluminacionpuntos_luxmed1, NULL)) 
                                                                        AND MAX(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL)) > MAX(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL)) 
                                                                        THEN MAX(IF(reporteiluminacionpuntos_luxmed2 > 0, reporteiluminacionpuntos_luxmed2, NULL))
                                                                    ELSE 
                                                                        MAX(IF(reporteiluminacionpuntos_luxmed3 > 0, reporteiluminacionpuntos_luxmed3, NULL))
                                                                END
                                                             ),
                                                             " Luxes<br>Medición ",
                                                             (
                                                                 IF(SUM(IF(reporteiluminacionpuntos_horario1 != "N/A", 1, 0)) > 0, 1, 0) +
                                                                 IF(SUM(IF(reporteiluminacionpuntos_horario2 != "N/A", 1, 0)) > 0, 1, 0) +
                                                                 IF(SUM(IF(reporteiluminacionpuntos_horario3 != "N/A", 1, 0)) > 0, 1, 0)
                                                             )
                                                         ) AS nivel_iluminacion,

                                                        IFNULL((
                                                            SELECT
                                                                COUNT(reporterecomendaciones.id)
                                                            FROM
                                                                reporterecomendaciones
                                                            WHERE
                                                                reporterecomendaciones.proyecto_id = RESULTADO.proyecto_id
                                                                AND reporterecomendaciones.agente_nombre = "Iluminación"
                                                         ), 0) AS recomendaciones_total
                                                    FROM
                                                        (
                                                            SELECT
                                                                id,
                                                                proyecto_id,
                                                                reporteiluminacionarea_instalacion,
                                                                reporteiluminacionpuntos_area_id,
                                                                reporteiluminacionarea_nombre,
                                                                reporteiluminacionpuntos_categoria_id,
                                                                reporteiluminacioncategoria_nombre,
                                                                reporteiluminacionpuntos_nombre,
                                                                reporteiluminacionpuntos_ficha,
                                                                reporteiluminacionpuntos_nopunto,
                                                                reporteiluminacionpuntos_concepto,
                                                                reporteiluminacionpuntos_nopoe,
                                                                reporteiluminacionpuntos_fechaeval,
                                                                reporteiluminacionpuntos_horario1,
                                                                reporteiluminacionpuntos_horario2,
                                                                reporteiluminacionpuntos_horario3,
                                                                --  reporteiluminacionpuntos_luxmed1mayor,
                                                                --  reporteiluminacionpuntos_luxmed2mayor,
                                                                --  reporteiluminacionpuntos_luxmed3mayor,
                                                                IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                                                IF(reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux, reporteiluminacionpuntos_luxmed1, 0) AS reporteiluminacionpuntos_luxmed1,
                                                                IF(reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux, reporteiluminacionpuntos_luxmed2, 0) AS reporteiluminacionpuntos_luxmed2,
                                                                IF(reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux, reporteiluminacionpuntos_luxmed3, 0) AS reporteiluminacionpuntos_luxmed3,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                                IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                                IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                                IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                                
                                                                IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                                IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                                IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                                
                                                                IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                                IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                                IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                                (
                                                                    CASE
                                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                                    END
                                                                ) AS fr_resultado,
                                                                (
                                                                    CASE
                                                                        WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                                        ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                                    END
                                                                ) AS fr_resultado_color
                                                            FROM
                                                                (
                                                                    SELECT
                                                                        reporteiluminacionpuntos.id,
                                                                        reporteiluminacionpuntos.proyecto_id,
                                                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS lux,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_lux,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frp,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        ) AS total_frp,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                                        (
                                                                            CASE
                                                                                WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                                WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                                ELSE 1
                                                                            END
                                                                        ) AS frpt,
                                                                        (
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                            +
                                                                            (
                                                                                CASE
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                                    WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                                    ELSE 0
                                                                                END
                                                                            )
                                                                        )  AS total_frpt
                                                                    FROM
                                                                        reporteiluminacionpuntos
                                                                        LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                                                    WHERE
                                                                        reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                                        AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                                    ORDER BY
                                                                        reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                                ) AS TABLA
                                                        ) AS RESULTADO
                                                    GROUP BY
                                                        proyecto_id'));

                if (count($resultados) > 0) {
                    $iluminacion_datos[] = array(
                        'titulo' => "Dentro de norma",
                        'total' => $resultados[0]->total_iluminacion_dentronorma
                    );

                    $iluminacion_datos[] = array(
                        'titulo' => "Fuera de norma",
                        'total' => $resultados[0]->total_iluminacion_fueranorma
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Dentro de norma",
                        'total' => $resultados[0]->total_reflexion_dentronorma
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Fuera de norma",
                        'total' => $resultados[0]->total_reflexion_fueranorma
                    );

                    $dato['datos'] = array(
                        'total_iluminacion' => $resultados[0]->total_iluminacion,
                        'total_iluminacion_dentronorma' => $resultados[0]->total_iluminacion_dentronorma,
                        'total_iluminacion_fueranorma' => $resultados[0]->total_iluminacion_fueranorma,
                        'total_reflexion' => $resultados[0]->total_reflexion,
                        'total_reflexion_dentronorma' => $resultados[0]->total_reflexion_dentronorma,
                        'total_reflexion_fueranorma' => $resultados[0]->total_reflexion_fueranorma,
                        'nivel_iluminacion' => $resultados[0]->nivel_iluminacion,
                        'recomendaciones_total' => $resultados[0]->recomendaciones_total
                    );
                } else {
                    $iluminacion_datos[] = array(
                        'titulo' => "Sin evaluar",
                        'total' => 100.1
                    );

                    $reflexion_datos[] = array(
                        'titulo' => "Sin evaluar",
                        'total' => 100.1
                    );

                    $dato['datos'] = array(
                        'total_iluminacion' => 0,
                        'total_iluminacion_dentronorma' => 0,
                        'total_iluminacion_fueranorma' => 0,
                        'total_reflexion' => 0,
                        'total_reflexion_dentronorma' => 0,
                        'total_reflexion_fueranorma' => 0,
                        'nivel_iluminacion' => 0,
                        'recomendaciones_total' => 0
                    );
                }
            }


            // respuesta
            $dato['areas_cumplimiento'] = $areas_cumplimiento;
            $dato['categorias_criticas'] = $categorias_criticas;
            $dato['iluminacion_datos'] = $iluminacion_datos;
            $dato['reflexion_datos'] = $reflexion_datos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areas_cumplimiento'] = '';
            $dato['categorias_criticas'] = '';
            $dato['iluminacion_datos'] = 0;
            $dato['reflexion_datos'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*
    public function reporteiluminaciondashboardgraficas(Request $request)
    {
        // dd($request->all());
        try
        {
            $reporteiluminacion = reporteiluminacionModel::findOrFail($request->reporteiluminacion_id);


            if ($request->grafica1)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica1); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$reporteiluminacion->proyecto_id.'/'.$reporteiluminacion->agente_nombre.'/'.$reporteiluminacion->id.'/graficas/grafica_1.jpg'; // AREAS CRITICAS

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


            if ($request->grafica_iluminacion)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_iluminacion); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$reporteiluminacion->proyecto_id.'/'.$reporteiluminacion->agente_nombre.'/'.$reporteiluminacion->id.'/graficas/grafica_2.jpg'; // ILUMINACION

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


            if ($request->grafica_reflexion)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_reflexion); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$reporteiluminacion->proyecto_id.'/'.$reporteiluminacion->agente_nombre.'/'.$reporteiluminacion->id.'/graficas/grafica_3.jpg'; // REFLEXION

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


            // Mensaje
            $dato["msj"] = 'Imagen guardada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
    */


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteiluminacion_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablarecomendaciones($proyecto_id, $reporteiluminacion_id, $agente_nombre)
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
                                                                            AND reporterecomendaciones.registro_id = ' . $reporteiluminacion_id . '
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
                                                        AND reporterecomendaciones.registro_id = ' . $reporteiluminacion_id . '
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


    /**
     * Display the specified resource.
     *
     * @param int $reporteiluminacion_id
     * @param int $responsabledoc_tipo
     * @param int $responsabledoc_opcion
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionresponsabledocumento($reporteiluminacion_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporteiluminacion  = reporteiluminacionModel::findOrFail($reporteiluminacion_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporteiluminacion->reporteiluminacion_responsable1documento);
            } else {
                return Storage::download($reporteiluminacion->reporteiluminacion_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporteiluminacion->reporteiluminacion_responsable2documento);
            } else {
                return Storage::download($reporteiluminacion->reporteiluminacion_responsable2documento);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteiluminacion_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablaplanos($proyecto_id, $reporteiluminacion_id, $agente_nombre)
    {
        try {
            $planos = collect(DB::select('SELECT
                                                proyectoevidenciaplano.proyecto_id,
                                                proyectoevidenciaplano.agente_id,
                                                proyectoevidenciaplano.agente_nombre,
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                                COUNT(proyectoevidenciaplano.id) AS total_planos,
                                                IFNULL((
                                                    SELECT
                                                        IF(IFNULL(reporteplanoscarpetas.reporteplanoscarpetas_nombre, "") = "", "", "checked")
                                                    FROM
                                                        reporteplanoscarpetas
                                                    WHERE
                                                        reporteplanoscarpetas.proyecto_id = proyectoevidenciaplano.proyecto_id
                                                        AND reporteplanoscarpetas.agente_nombre = proyectoevidenciaplano.agente_nombre
                                                        AND reporteplanoscarpetas.registro_id = ' . $reporteiluminacion_id . '
                                                        AND reporteplanoscarpetas.reporteplanoscarpetas_nombre = proyectoevidenciaplano.proyectoevidenciaplano_carpeta
                                                ), "") AS checked
                                            FROM
                                                proyectoevidenciaplano
                                            WHERE
                                                proyectoevidenciaplano.proyecto_id = ' . $proyecto_id . '
                                                AND proyectoevidenciaplano.agente_nombre = "' . $agente_nombre . '"
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta != ""
                                            GROUP BY
                                                proyectoevidenciaplano.proyecto_id,
                                                proyectoevidenciaplano.agente_id,
                                                proyectoevidenciaplano.agente_nombre,
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC'));

            $total_activos = 0;
            $numero_registro = 0;
            foreach ($planos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="reporteiluminacion_checkboxplanocarpeta" name="reporteiluminacion_checkboxplanocarpeta[]" value="' . $value->proyectoevidenciaplano_carpeta . '" ' . $value->checked . '>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';

                // VERIFICAR SI HAY CARPETAS SELECCIONADAS
                if ($value->checked) {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $planos;
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


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteiluminacion_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablaequipoutilizado($proyecto_id, $reporteiluminacion_id, $agente_nombre)
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
                                            AND proyectoproveedores.catprueba_id = 4 -- Iluminación ------------------------------
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');


            $where_condicion = '';
            if (count($proveedor) > 0) {
                // $where_condicion = ' AND proyectoequiposactual.proveedor_id = '.$proveedor[0]->proveedor_id;
            }


            $equipos = DB::select('SELECT
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteiluminacion_id . '"
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteiluminacion_id . '"
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteiluminacion_id . '"
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
                                            <input type="checkbox" class="reporteiluminacion_equipoutilizadocheckbox" name="reporteiluminacion_equipoutilizadocheckbox[]" value="' . $value->equipo_id . '" ' . $value->checked . ' onchange="activa_checkboxcarta(this, ' . $value->equipo_id . ');";>
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


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteiluminacion_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablainformeresultados($proyecto_id, $reporteiluminacion_id, $agente_nombre)
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
                                                        AND reporteanexos.registro_id = ' . $reporteiluminacion_id . '
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
                                            <input type="hidden" class="form-control" name="reporteiluminacion_anexonombre_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_nombre . '">
                                            <input type="hidden" class="form-control" name="reporteiluminacion_anexoarchivo_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_archivo . '">
                                            <input type="checkbox" class="reporteiluminacion_informeresultadocheckbox" name="reporteiluminacion_informeresultadocheckbox[]" value="' . $value->id . '" ' . $value->checked . '>
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


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteiluminacion_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablaanexos($proyecto_id, $reporteiluminacion_id, $agente_nombre)
    {
        try {
            $acreditaciones = collect(DB::select('SELECT
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proyectoproveedores.catprueba_id,
                                                        proyectoproveedores.proyectoproveedores_agente,
                                                        acreditacion.id,
                                                        acreditacion.acreditacion_Entidad,
                                                        acreditacion.acreditacion_Numero,
                                                        cat_tipoacreditacion.catTipoAcreditacion_Nombre,
                                                        cat_area.catArea_Nombre,
                                                        acreditacion.acreditacion_Expedicion,
                                                        acreditacion.acreditacion_Vigencia,
                                                        IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) AS vigencia_dias,
                                                        IF(acreditacion.acreditacion_Vigencia, CONCAT(acreditacion.acreditacion_Vigencia, " (", (DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1)," d)"), "N/A") AS vigencia_texto,
                                                        (
                                                            CASE
                                                                WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) = 0 THEN ""
                                                                WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) >= 90 THEN ""
                                                                WHEN IFNULL(DATEDIFF(acreditacion.acreditacion_Vigencia, CURDATE()) + 1, 0) >= 30 THEN "text-warning"
                                                                ELSE "text-danger"
                                                            END
                                                        ) AS vigencia_color,
                                                        acreditacion.acreditacion_SoportePDF,
                                                        IFNULL((
                                                            SELECT  
                                                                IF(IFNULL(reporteanexos.reporteanexos_rutaanexo, "") = "", "", "checked")
                                                            FROM
                                                                reporteanexos
                                                            WHERE
                                                                reporteanexos.proyecto_id = proyectoproveedores.proyecto_id
                                                                AND reporteanexos.agente_nombre = proyectoproveedores.proyectoproveedores_agente
                                                                AND reporteanexos.registro_id = ' . $reporteiluminacion_id . '
                                                                AND reporteanexos.reporteanexos_tipo = 2
                                                                AND reporteanexos.reporteanexos_rutaanexo = acreditacion.acreditacion_SoportePDF
                                                            LIMIT 1
                                                        ), "") AS checked 
                                                    FROM
                                                        proyectoproveedores
                                                        INNER JOIN acreditacion ON proyectoproveedores.proveedor_id = acreditacion.proveedor_id
                                                        LEFT JOIN cat_tipoacreditacion ON acreditacion.acreditacion_Tipo = cat_tipoacreditacion.id
                                                        LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id 
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = ' . $proyecto_id . ' 
                                                        AND proyectoproveedores.proyectoproveedores_agente = "' . $agente_nombre . '" 
                                                        AND acreditacion.acreditacion_Eliminado = 0
                                                        AND IFNULL(acreditacion.acreditacion_SoportePDF, "") != ""'));

            $total_activos = 0;
            $numero_registro = 0;
            foreach ($acreditaciones as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="reporteiluminacion_anexonombre_' . $value->id . '" value="' . $value->acreditacion_Entidad . ' ' . $value->acreditacion_Numero . '">
                                            <input type="hidden" class="form-control" name="reporteiluminacion_anexoarchivo_' . $value->id . '" value="' . $value->acreditacion_SoportePDF . '">
                                            <input type="checkbox" class="reporteiluminacion_anexocheckbox" name="reporteiluminacion_anexocheckbox[]" value="' . $value->id . '" ' . $value->checked . '>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';

                $value->tipo = '<span class="' . $value->vigencia_color . '">' . $value->catTipoAcreditacion_Nombre . '</span>';
                $value->entidad = '<span class="' . $value->vigencia_color . '">' . $value->acreditacion_Entidad . '</span>';
                $value->numero = '<span class="' . $value->vigencia_color . '">' . $value->acreditacion_Numero . '</span>';
                $value->area = '<span class="' . $value->vigencia_color . '">' . $value->catArea_Nombre . '</span>';
                $value->vigencia = '<span class="' . $value->vigencia_color . '">' . $value->vigencia_texto . '</span>';
                $value->certificado = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar certificado"><i class="fa fa-file-pdf-o fa-1x"></i></button>';

                // VERIFICAR SI HAY ACREDITACIONES SELECCIONADOS
                if ($value->checked) {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $acreditaciones;
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


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminaciontablarevisiones($proyecto_id)
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*
    public function reporteiluminacionnuevarevision(Request $request)
    {
        try
        {
            // dd($request->all());

            // OBTENER ULTIMA REVISION
            // -------------------------------------------------


            $revision = DB::select('SELECT
                                        reporteiluminacion.id,
                                        reporteiluminacion.proyecto_id,
                                        reporteiluminacion.reporteiluminacion_revision 
                                    FROM
                                        reporteiluminacion
                                    WHERE
                                        reporteiluminacion.proyecto_id = '.$request->proyecto_id.'
                                    ORDER BY
                                        reporteiluminacion.reporteiluminacion_revision DESC
                                    LIMIT 1');


            // CLONAR REGISTRO ILUMINACION
            // -------------------------------------------------


            $revisionfinal  = reporteiluminacionModel::findOrFail($revision[0]->id);

            DB::statement('ALTER TABLE reporteiluminacion AUTO_INCREMENT = 1;');

            // $revisionnueva = $revisionfinal->replicate();
            $revisionnueva = $revisionfinal->replicate()->fill([
                  'reporteiluminacion_revision' => ($revision[0]->reporteiluminacion_revision + 1)
                , 'reporteiluminacion_concluido' => 0
                , 'reporteiluminacion_concluidonombre' => NULL
                , 'reporteiluminacion_concluidofecha' => NULL
                , 'reporteiluminacion_cancelado' => 0
                , 'reporteiluminacion_canceladonombre' => NULL
                , 'reporteiluminacion_canceladofecha' => NULL
                , 'reporteiluminacion_canceladoobservacion' => NULL
            ]);

            $revisionnueva->save();


            // CLONAR REGISTROS TABLA ANEXOS
            // -------------------------------------------------


            $anexos_historial = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_nombre', $request->agente_nombre)
                                                    ->where('registro_id', $revision[0]->id)
                                                    ->get();

            DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');
            foreach ($anexos_historial as $key => $value)
            {                
                $anexo = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $anexo->save();
            }
            

            // CLONAR REGISTROS TABLA EQUIPOS UTILIZADOS
            // -------------------------------------------------


            $equipos_historial = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('agente_nombre', $request->agente_nombre)
                                                                ->where('registro_id', $revision[0]->id)
                                                                ->get();

            DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');
            foreach ($equipos_historial as $key => $value)
            {                
                $equipo = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                ]);

                $equipo->save();
            }


            // CLONAR REGISTROS TABLA PLANOS CARPETAS
            // -------------------------------------------------


            $planoscarpetas_historial = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('agente_nombre', $request->agente_nombre)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();

            DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');
            foreach ($planoscarpetas_historial as $key => $value)
            {                
                $carpeta = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                ]);

                $carpeta->save();
            }


            // CLONAR REGISTROS TABLA RECOMENDACIONES
            // -------------------------------------------------


            $recomendaciones_historial = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                                                                    ->where('agente_nombre', $request->agente_nombre)
                                                                    ->where('registro_id', $revision[0]->id)
                                                                    ->get();

            DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');
            foreach ($recomendaciones_historial as $key => $value)
            {                
                $recomendacion = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                ]);

                $recomendacion->save();
            }


            // CLONAR REGISTROS TABLA CATEGORÍAS
            // -------------------------------------------------


            if (($request->areas_poe+0) == 0) // Que las categorias no pertenezcan al POE general
            {
                $categorias_historial = reporteiluminacioncategoriaModel::where('proyecto_id', $request->proyecto_id)
                                                                        ->where('registro_id', $revision[0]->id)
                                                                        ->get();


                $categorias_nuevosid = array();
                DB::statement('ALTER TABLE reporteiluminacioncategoria AUTO_INCREMENT = 1;');
                foreach ($categorias_historial as $key => $value)
                {                
                    $categoria = $value->replicate()->fill([
                          'registro_id' => $revisionnueva->id
                    ]);

                    $categoria->save();

                    $categorias_nuevosid['id_'.$value->id] = $categoria->id;
                }
                // dd($categorias_nuevosid);
            }


            // CLONAR REGISTROS TABLA AREAS
            // -------------------------------------------------


            if (($request->areas_poe+0) == 0) // Que las areas no pertenezcan al POE general
            {            
                $areas_historial = reporteiluminacionareaModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('registro_id', $revision[0]->id)
                                                                ->get();


                $areas_nuevosid = array();
                DB::statement('ALTER TABLE reporteiluminacionarea AUTO_INCREMENT = 1;');
                foreach ($areas_historial as $key => $value)
                {                
                    $area = $value->replicate()->fill([
                          'registro_id' => $revisionnueva->id
                    ]);

                    $area->save();

                    $areas_nuevosid['id_'.$value->id] = $area->id;
                }
                // dd($areas_nuevosid);
            }


            // CLONAR REGISTROS TABLA AREAS CATEGORIAS
            // -------------------------------------------------


            if (($request->areas_poe+0) == 0) // Que las areas no pertenezcan al POE general
            {
                $areacategorias = DB::select('SELECT
                                                    reporteiluminacioncategoria.proyecto_id,
                                                    reporteiluminacioncategoria.registro_id,
                                                    reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                                    reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_total,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual 
                                                FROM
                                                    reporteiluminacionareacategoria
                                                    RIGHT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                    LEFT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id
                                                WHERE
                                                    reporteiluminacioncategoria.proyecto_id = '.$request->proyecto_id.'
                                                    AND reporteiluminacioncategoria.registro_id = '.$revision[0]->id.' 
                                                    AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                                ');


                DB::statement('ALTER TABLE reporteiluminacionareacategoria AUTO_INCREMENT = 1;');
                foreach ($areacategorias as $key => $value)
                {
                    $registro = reporteiluminacionareacategoriaModel::create([
                          'reporteiluminacionarea_id' => $areas_nuevosid['id_'.$value->reporteiluminacionarea_id]
                        , 'reporteiluminacioncategoria_id' => $categorias_nuevosid['id_'.$value->reporteiluminacioncategoria_id]
                        , 'reporteiluminacionareacategoria_poe' => 0
                        , 'reporteiluminacionareacategoria_total' => $value->reporteiluminacionareacategoria_total
                        , 'reporteiluminacionareacategoria_geo' => $value->reporteiluminacionareacategoria_geo
                        , 'reporteiluminacionareacategoria_actividades' => $value->reporteiluminacionareacategoria_actividades
                        , 'reporteiluminacionareacategoria_tareavisual' => $value->reporteiluminacionareacategoria_tareavisual
                    ]);
                }
            }
            else
            {
                $areacategorias = DB::select('SELECT
                                                    reportecategoria.proyecto_id,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                    reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                                    reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_total,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades,
                                                    reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual 
                                                FROM
                                                    reporteiluminacionareacategoria
                                                    LEFT JOIN reportecategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reportecategoria.id
                                                WHERE
                                                    reportecategoria.proyecto_id = '.$request->proyecto_id.' 
                                                    AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = '.($revision[0]->id+0));


                foreach ($areacategorias as $key => $value)
                {
                    $registro = reporteiluminacionareacategoriaModel::create([
                          'reporteiluminacionarea_id' => $value->reporteiluminacionarea_id
                        , 'reporteiluminacioncategoria_id' => $value->reporteiluminacioncategoria_id
                        , 'reporteiluminacionareacategoria_poe' => $revisionnueva->id
                        , 'reporteiluminacionareacategoria_total' => $value->reporteiluminacionareacategoria_total
                        , 'reporteiluminacionareacategoria_geo' => $value->reporteiluminacionareacategoria_geo
                        , 'reporteiluminacionareacategoria_actividades' => $value->reporteiluminacionareacategoria_actividades
                        , 'reporteiluminacionareacategoria_tareavisual' => $value->reporteiluminacionareacategoria_tareavisual
                    ]);
                }
            }


            // CLONAR REGISTROS TABLA PUNTOS DE ILUMINACION
            // -------------------------------------------------


            $puntos_historial = reporteiluminacionpuntosModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('registro_id', $revision[0]->id)
                                                                ->get();


            DB::statement('ALTER TABLE reporteiluminacionpuntos AUTO_INCREMENT = 1;');                                                                


            if (($request->areas_poe+0) == 0) // Que las areas no pertenezcan al POE general
            {
                foreach ($puntos_historial as $key => $value)
                {
                    // $array_key = array_key_exists('id_'.$value->reporteiluminacionpuntos_area_id, $areas_nuevosid);
                    // dd($array_key);

                    if (array_key_exists('id_'.$value->reporteiluminacionpuntos_area_id, $areas_nuevosid) == 1 && array_key_exists('id_'.$value->reporteiluminacionpuntos_categoria_id, $categorias_nuevosid) == 1)
                    {
                        $punto = $value->replicate()->fill([
                              'registro_id' => $revisionnueva->id
                            , 'reporteiluminacionpuntos_area_id' => $areas_nuevosid['id_'.$value->reporteiluminacionpuntos_area_id]
                            , 'reporteiluminacionpuntos_categoria_id' => $categorias_nuevosid['id_'.$value->reporteiluminacionpuntos_categoria_id]
                        ]);
                    }
                    else
                    {
                        $punto = $value->replicate()->fill([
                            'registro_id' => $revisionnueva->id
                        ]);
                    }

                    $punto->save();
                }
            }
            else
            {
                foreach ($puntos_historial as $key => $value)
                {
                    $punto = $value->replicate()->fill([
                        'registro_id' => $revisionnueva->id
                    ]);

                    $punto->save();
                }
            }


            // GUARDAR GRAFICAS IMAGENES Y CLONAR CARPETA
            // -------------------------------------------------


            if ($request->grafica1)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica1); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/graficas/grafica_1.jpg'; // AREAS CRITICAS

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


            if ($request->grafica_iluminacion)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_iluminacion); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/graficas/grafica_2.jpg'; // ILUMINACION

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


            if ($request->grafica_reflexion)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_reflexion); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/graficas/grafica_3.jpg'; // REFLEXION

                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
            }


        
            // CLONAR CARPETA ARCHIVOS, PLANO UBICACION, RESPONSABLES DEL INFORME, GRAFICAS
            // -------------------------------------------------


            $carpetaarchivos_historial = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id;
            $carpetaarchivos_destino = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revisionnueva->id;
            
            Storage::makeDirectory($carpetaarchivos_destino); //crear directorio
            File::copyDirectory(storage_path('app/'.$carpetaarchivos_historial), storage_path('app/'.$carpetaarchivos_destino)); //Copiar contenido directorio


            if (Storage::exists($carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'))
            {
                $revisionnueva->update([
                    'reporteiluminacion_ubicacionfoto' => $carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                    'reporteiluminacion_ubicacionfoto' => NULL
                ]);
            }


            if (Storage::exists($carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'))
            {
                $revisionnueva->update([
                      'reporteiluminacion_responsable1documento' => $carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'
                    , 'reporteiluminacion_responsable2documento' => $carpetaarchivos_destino.'/responsables informe/responsable2_doc.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                      'reporteiluminacion_responsable1documento' => NULL
                    , 'reporteiluminacion_responsable2documento' => NULL
                ]);
            }


            // -------------------------------------------------


            // respuesta
            $dato["reporteiluminacion_id"] = $revisionnueva->id;
            $dato["msj"] = 'Revisión creada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["reporteiluminacion_id"] = $revision[0]->id;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
    */


    /**
     * Display the specified resource.
     *
     * @param  int $revision_id
     * @return \Illuminate\Http\Response
     */
    public function reporteiluminacionconcluirrevision($revision_id)
    {
        try {
            // $reporteiluminacion  = reporteiluminacionModel::findOrFail($revision_id);
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // TABLAS
            //============================================================


            // VALIDAMOS PRIMERO ESTA OPCION  PARA NO AFECTAR CON TODO EL PROCESO DE INSERCION QUE ESTA ABAJO
            // INSERTAR PUNTOS POR MEDIO DE UN EXCEL
            if ($request->opcion == 8000) {


                // Empezamos a guardar los puntos de iluminacion
                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelPuntos')) {

                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelPuntos');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar las 3 primeras filas ya que no contienen datos importantes
                        $data = array_slice($data, 4);


                        //Obtenemos todos los datos del Excel y los almacenamos datos sin imagenes
                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {


                                // Almacenar la fila limpia en el array
                                $datosGenerales[] = $row;
                            }
                        }

                        // return response()->json(['msj' => $datosGenerales, "code" => 500]);


                        //Puntos totales
                        $totalPuntos = count($datosGenerales);
                        $puntosInsertados = 0;

                        //================================= Funciones de limpieaza de datos =================================
                        function puntosLimpios($punto)
                        {

                            $punto = trim($punto);
                            if ($punto == ' ---' || $punto == '---' || $punto == ' --- ' || $punto == '--- ' || $punto == 'No aplica' || $punto == 'NA' || $punto == 'N/A' || $punto == 'NO APLICA' || $punto == '') {

                                $subcadena = 0;
                            } else {

                                // Tomar los primeros cuatro caracteres
                                $subcadena = substr($punto, 0, 4);

                                // Evaluar cada carácter y validar que el siguiente no sea un espacio en blanco o un caracter de ±
                                for ($i = 0; $i < strlen($subcadena); $i++) {
                                    if (isset($subcadena[$i + 1]) && ($subcadena[$i + 1] == ' '  || $subcadena[$i + 1] == '±')) {
                                        return substr($subcadena, 0, $i + 1);
                                    }
                                }

                                // Si no hay espacios en blanco en los caracteres evaluados, devolver los cuatro caracteres
                                return $subcadena;
                            }

                            return $subcadena;
                        }


                        //Validamos las fechas en diferentes formatos
                        function validarFecha($date, $formatosValidos = ['Y-m-d', 'd-m-Y', 'Y/m/d', 'd/m/Y'], $formatoFinal = 'Y-m-d')
                        {
                            foreach ($formatosValidos as $formato) {
                                $d = DateTime::createFromFormat($formato, $date);
                                if ($d && $d->format($formato) === $date) {
                                    return $d->format($formatoFinal);
                                }
                            }
                            return null;
                        }

                        //FORMATEAMO LAS FECHAS A UN VALOR ACEPTABLE EN LA BASE DE DATOOS
                        function formatearHora($hora)
                        {

                            if ($hora == 'N/A' || $hora == 'n/a' || $hora == 'NP') {
                                return null;
                            } else {

                                // Separar la hora y los minutos
                                list($horas, $minutos) = explode(':', $hora);

                                $horasFormateadas = str_pad($horas, 2, '0', STR_PAD_LEFT);
                                $minutosFormateados = str_pad($minutos, 2, '0', STR_PAD_LEFT);

                                return $horasFormateadas . ':' . $minutosFormateados;
                            }
                        }

                        //VALIDAMOS LOS PUNTOS DE REFLEXION DE PAREDES
                        function limpiarFRP($valor)
                        {
                            if (is_numeric($valor)) {
                                return intval($valor);
                            } else {
                                return 0;
                            }
                        }

                        //VALIDAMOS LOS PUNTOS DE REFLEXION DE PLANO
                        function limpiarFRPT($valor)
                        {
                            if (is_numeric($valor)) {
                                return intval($valor);
                            } else {
                                return 0;
                            }
                        }


                        //VALIDAMOS LOS NUMERO DE POE
                        function limpiarPOE($valor)
                        {
                            if (is_numeric($valor)) {
                                return intval($valor);
                            } else {
                                return 0;
                            }
                        }


                        //Reiniciamos el Autoincrements de la  tabla de puntos
                        DB::statement('ALTER TABLE reporteiluminacionpuntos AUTO_INCREMENT = 1;');

                        //Limpiamos, Validamos y Insertamos todos los datos del Excel
                        foreach ($datosGenerales as $rowData) {

                            reporteiluminacionpuntosModel::create([
                                'proyecto_id' => $request['proyecto_id'],
                                'registro_id' => $request['registro_id'],
                                'reporteiluminacionpuntos_nombre' => 'NP',
                                'reporteiluminacionpuntos_ficha' => 'NP',
                                'reporteiluminacionpuntos_nopoe' => is_null($rowData['H']) ? 0 : limpiarPOE($rowData['H']),
                                'reporteiluminacionpuntos_nopunto' => is_null($rowData['A']) ? null : intval($rowData['A']),
                                'reporteiluminacionpuntos_fechaeval' => is_null($rowData['B']) ? null : validarFecha($rowData['B']),
                                'reporteiluminacionpuntos_horario1' => is_null($rowData['C']) ? null : formatearHora($rowData['C']),
                                'reporteiluminacionpuntos_horario2' => is_null($rowData['D']) ? null :  formatearHora($rowData['D']),
                                'reporteiluminacionpuntos_horario3' => is_null($rowData['E']) ? null : formatearHora($rowData['E']),
                                'reporteiluminacionpuntos_lux' => is_null($rowData['J']) ? null : intval($rowData['J']),
                                'reporteiluminacionpuntos_luxmed1' => is_null($rowData['K']) ? 0 : puntosLimpios($rowData['K']),
                                'reporteiluminacionpuntos_luxmed2' => is_null($rowData['N']) ? 0 : puntosLimpios($rowData['N']),
                                'reporteiluminacionpuntos_luxmed3' => is_null($rowData['Q']) ? 0 : puntosLimpios($rowData['Q']),
                                'reporteiluminacionpuntos_luxmed1menor' => 0,
                                'reporteiluminacionpuntos_luxmed2menor' => 0,
                                'reporteiluminacionpuntos_luxmed3menor' => 0,
                                'reporteiluminacionpuntos_luxmed1mayor' => 0,
                                'reporteiluminacionpuntos_luxmed2mayor' => 0,
                                'reporteiluminacionpuntos_luxmed3mayor' => 0,
                                'reporteiluminacionpuntos_frp' => 60,
                                'reporteiluminacionpuntos_frpt' => 50,
                                'reporteiluminacionpuntos_frpmed1' => is_null($rowData['L']) ? null : limpiarFRP($rowData['L']),
                                'reporteiluminacionpuntos_frpmed2' => is_null($rowData['O']) ? null : limpiarFRP($rowData['O']),
                                'reporteiluminacionpuntos_frpmed3' => is_null($rowData['R']) ? null : limpiarFRP($rowData['R']),
                                'reporteiluminacionpuntos_frptmed1' => is_null($rowData['M']) ? null : limpiarFRPT($rowData['M']),
                                'reporteiluminacionpuntos_frptmed2' => is_null($rowData['P']) ? null : limpiarFRPT($rowData['P']),
                                'reporteiluminacionpuntos_frptmed3' => is_null($rowData['S']) ? null : limpiarFRPT($rowData['S']),

                            ]);

                            $puntosInsertados++;
                        }

                        //RETORNAMOS UN MENSAJE DE CUANTOS INSERTO 
                        return response()->json(['msj' => 'Total de puntos insertados : ' . $puntosInsertados . ' de ' . $totalPuntos, 'code' => 200]);
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los puntos, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }
            }


            /// INSERTAR POR MEDIO DE EXCEL LO DATOS DE LAS AREAS QUE FALTAN 


            // VALIDAMOS PRIMERO ESTA OPCION  PARA NO AFECTAR CON TODO EL PROCESO DE INSERCION QUE ESTA ABAJO
            // INSERTAR PUNTOS POR MEDIO DE UN EXCEL
            if ($request->opcion == 8001) {


                // Empezamos a guardar los puntos de iluminacion
                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelArea')) {


                        $excel = $request->file('excelArea');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar las 3 primeras filas ya que no contienen datos importantes
                        $data = array_slice($data, 4);


                        //Obtenemos todos los datos del Excel y los almacenamos datos sin imagenes
                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {


                                // Almacenar la fila limpia en el array
                                $datosGenerales[] = $row;
                            }
                        }

                        // return response()->json(['msj' => $datosGenerales, "code" => 500]);


                        //Puntos totales
                        $totalPuntos = count($datosGenerales);
                        $puntosInsertados = 0;
                        $areasNoEncontradas = [];
                        //================================= Funciones de limpieaza de datos =================================


                        function tipoIluminacion($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado === 'NATURAL') {
                                return "Natural";
                            } elseif ($usoModificado === 'ARTIFICIAL') {
                                return "Artificial";
                            } elseif ($usoModificado === 'NATURAL Y ARTIFICIAL') {
                                return "Natural y artificial";
                            } else {
                                return 0;
                            }
                        }

                        //Reiniciamos el Autoincrements de la  tabla de puntos
                        // DB::statement('ALTER TABLE reporteiluminacionpuntos AUTO_INCREMENT = 1;');



                        foreach ($datosGenerales as $rowData) {

                            // Verificar si 'reportearea_nombre' está presente en el conjunto de datos
                            if (isset($rowData['B'])) {


                                $area = reporteareaModel::where('reportearea_nombre', $rowData['B'])
                                    ->where('proyecto_id', $request['proyecto_id'])
                                    ->first();

                                if ($area) {

                                    $area->update([
                                        'reportearea_orden' => is_null($rowData['A']) ? null : intval($rowData['A']),
                                        'reportearea_nombre' => is_null($rowData['B']) ? null : $rowData['B'],
                                        'reportearea_largo' => is_null($rowData['C']) ? null : floatval($rowData['C']),
                                        'reportearea_ancho' => is_null($rowData['D']) ? null : floatval($rowData['D']),
                                        'reportearea_alto' => is_null($rowData['E']) ? null : floatval($rowData['E']),
                                        'reportearea_puntos_ic' => is_null($rowData['F']) ? null : intval($rowData['F']),
                                        'reportearea_puntos_pt' => is_null($rowData['G']) ? null : intval($rowData['G']),
                                        'reportearea_criterio' => is_null($rowData['H']) ? null : $rowData['H'],
                                        'reportearea_colortecho' => is_null($rowData['I']) ? null : $rowData['I'],
                                        'reportearea_paredes' => is_null($rowData['J']) ? null : $rowData['J'],
                                        'reportearea_colorpiso' => is_null($rowData['K']) ? null : $rowData['K'],
                                        'reportearea_superficietecho' => is_null($rowData['L']) ? null : $rowData['L'],
                                        'reportearea_superficieparedes' => is_null($rowData['M']) ? null : $rowData['M'],
                                        'reportearea_superficiepiso' => is_null($rowData['N']) ? null : $rowData['N'],
                                        'reportearea_sistemailuminacion' => is_null($rowData['O']) ? null : $rowData['O'], // Guardar el nombre directamente
                                        'reportearea_potenciaslamparas' => is_null($rowData['P']) ? null : $rowData['P'],
                                        'reportearea_numlamparas' => is_null($rowData['Q']) ? null : $rowData['Q'],
                                        'reportearea_alturalamparas' => is_null($rowData['R']) ? null : $rowData['R'],
                                        'reportearea_programamantenimiento' => is_null($rowData['S']) ? null : $rowData['S'],
                                        'reportearea_tipoiluminacion' => is_null($rowData['T']) ? null : tipoIluminacion($rowData['T']),
                                        'reportearea_descripcionilimunacion' => is_null($rowData['U']) ? null : $rowData['U'],
                                    ]);

                                    $puntosInsertados++;
                                } else {
                                    // Si el área no existe, agregar al arreglo de no encontradas
                                    $areasNoEncontradas[] = $rowData['B'];
                                }
                            }
                        }

                        $mensaje = 'Total de datos insertados: ' . $puntosInsertados . ' de ' . $totalPuntos;

                        if (!empty($areasNoEncontradas)) {
                            $mensaje .= '. Las siguientes áreas no se encontraron: ' . implode(', ', $areasNoEncontradas);
                        }

                        return response()->json(['msj' => $mensaje, 'code' => 200]);
                    } else {
                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {
                    return response()->json([
                        'msj' => 'Se produjo un error al intentar cargar los puntos, inténtelo de nuevo o comuníquese con el responsable. Error: ' . $e->getMessage(),
                        'code' => 500
                    ]);
                }
            }







            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteiluminacion_id + 0) > 0) {
                $reporteiluminacion = reporteiluminacionModel::findOrFail($request->reporteiluminacion_id);


                $reporteiluminacion->update([
                    'reporteiluminacion_instalacion' => $request->reporteiluminacion_instalacion
                ]);


                $dato["reporteiluminacion_id"] = $reporteiluminacion->id;


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
                DB::statement('ALTER TABLE reporteiluminacion AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporteiluminacion = reporteiluminacionModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reporteiluminacion_revision' => 0,
                    'reporteiluminacion_instalacion' => $request->reporteiluminacion_instalacion,
                    'reporteiluminacion_catregion_activo' => 1,
                    'reporteiluminacion_catsubdireccion_activo' => 1,
                    'reporteiluminacion_catgerencia_activo' => 1,
                    'reporteiluminacion_catactivo_activo' => 1,
                    'reporteiluminacion_concluido' => 0,
                    'reporteiluminacion_cancelado' => 0
                ]);


                //--------------------------------------


                // Asignar categorias de este proyecto a este registro
                DB::statement('UPDATE 
                                    reporteiluminacioncategoria
                                SET 
                                    registro_id = ' . $reporteiluminacion->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // Asignar Areas de este proyecto a este registro
                DB::statement('UPDATE 
                                    reporteiluminacionarea
                                SET 
                                    registro_id = ' . $reporteiluminacion->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            // PORTADA
            if (($request->opcion + 0) == 0) {
                // REGION
                $catregion_activo = 0;
                if ($request->reporteiluminacion_catregion_activo != NULL) {
                    $catregion_activo = 1;
                }

                // SUBDIRECCION
                $catsubdireccion_activo = 0;
                if ($request->reporteiluminacion_catsubdireccion_activo != NULL) {
                    $catsubdireccion_activo = 1;
                }

                // GERENCIA
                $catgerencia_activo = 0;
                if ($request->reporteiluminacion_catgerencia_activo != NULL) {
                    $catgerencia_activo = 1;
                }

                // ACTIVO
                $catactivo_activo = 0;
                if ($request->reporteiluminacion_catactivo_activo != NULL) {
                    $catactivo_activo = 1;
                }

                $reporteiluminacion->update([
                    'reporteiluminacion_catregion_activo' => $catregion_activo,
                    'reporteiluminacion_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reporteiluminacion_catgerencia_activo' => $catgerencia_activo,
                    'reporteiluminacion_catactivo_activo' => $catactivo_activo,
                    'reporteiluminacion_instalacion' => $request->reporteiluminacion_instalacion,
                    'reporteiluminacion_fecha' => $request->reporteiluminacion_fecha,
                    'reporteiluminacion_mes' => $request->reporteiluminacion_mes
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
                // dd($request->reporteiluminacion_introduccion);

                $reporteiluminacion->update([
                    'reporteiluminacion_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_introduccion)
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
                $reporteiluminacion->update([
                    'reporteiluminacion_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporteiluminacion->update([
                    'reporteiluminacion_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion + 0) == 6) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2.1
            if (($request->opcion + 0) == 7) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_2_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_2_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2.2
            if (($request->opcion + 0) == 8) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_2_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_2_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2.3
            if (($request->opcion + 0) == 9) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_2_3' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_2_3)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2.4
            if (($request->opcion + 0) == 10) {
                $reporteiluminacion->update([
                    'reporteiluminacion_metodologia_4_2_4' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_metodologia_4_2_4)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion + 0) == 11) {
                $reporteiluminacion->update([
                    'reporteiluminacion_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reporteiluminacionubicacionfoto')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporteiluminacion->id . '/ubicacionfoto/ubicacionfoto.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporteiluminacion->update([
                        'reporteiluminacion_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 12) {
                $reporteiluminacion->update([
                    'reporteiluminacion_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_procesoinstalacion),
                    'reporteiluminacion_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_actividadprincipal)
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
                        DB::statement('ALTER TABLE reporteiluminacioncategoria AUTO_INCREMENT = 1;');


                        // $request['registro_id'] = $reporteiluminacion->id;
                        // $request['recsensorialcategoria_id'] = 0;
                        // $categoria = reporteiluminacioncategoriaModel::create($request->all());

                        $categoria = reporteiluminacioncategoriaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporteiluminacion->id,
                            'recsensorialcategoria_id' => 0,
                            'reporteiluminacioncategoria_nombre' => $request->reportecategoria_nombre,
                            'reporteiluminacioncategoria_total' => $request->reportecategoria_total,
                            'reporteiluminacioncategoria_horas' => $request->reportecategoria_horas
                        ]);


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $categoria = reporteiluminacioncategoriaModel::findOrFail($request->reportecategoria_id);
                        // $categoria->update($request->all());

                        $categoria->update([
                            'registro_id' => $reporteiluminacion->id,
                            'reporteiluminacioncategoria_nombre' => $request->reportecategoria_nombre,
                            'reporteiluminacioncategoria_total' => $request->reportecategoria_total,
                            'reporteiluminacioncategoria_horas' => $request->reportecategoria_horas
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


                    $eliminar_categorias = reporteiluminacionareacategoriaModel::where('reporteiluminacionarea_id', $request->reportearea_id)
                        ->where('reporteiluminacionareacategoria_poe', $reporteiluminacion->id)
                        ->delete();


                    if ($request->checkbox_reportecategoria_id) {
                        DB::statement('ALTER TABLE reporteiluminacionareacategoria AUTO_INCREMENT = 1;');


                        foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                            $areacategoria = reporteiluminacionareacategoriaModel::create([
                                'reporteiluminacionarea_id' => $area->id,
                                'reporteiluminacioncategoria_id' => $value,
                                'reporteiluminacionareacategoria_poe' => $reporteiluminacion->id,
                                'reporteiluminacionareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                'reporteiluminacionareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                'reporteiluminacionareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                'reporteiluminacionareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
                                'niveles_minimo' => $request['niveles_minimo_' . $value]

                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reporteiluminacionarea AUTO_INCREMENT = 1;');

                        // $request['registro_id'] = $reporteiluminacion->id;
                        // $request['recsensorialarea_id'] = 0;
                        // $area = reporteiluminacionareaModel::create($request->all());

                        $area = reporteiluminacionareaModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporteiluminacion->id,
                            'recsensorialarea_id' => 0,
                            'reporteiluminacionarea_numorden' => $request->reportearea_orden,
                            'reporteiluminacionarea_nombre' => $request->reportearea_nombre,
                            'reporteiluminacionarea_instalacion' => $request->reportearea_instalacion,
                            'reporteiluminacionarea_puntos_ic' => $request->reportearea_puntos_ic,
                            'reporteiluminacionarea_puntos_pt' => $request->reportearea_puntos_pt,
                            'reporteiluminacionarea_sistemailuminacion' => $request->reportearea_sistemailuminacion,
                            'reporteiluminacionarea_luznatural' => $request->reportearea_luznatural,
                            'reporteiluminacionarea_iluminacionlocalizada' => $request->reportearea_iluminacionlocalizada,
                            'reporteiluminacionarea_porcientooperacion' => $request->reporteiluminacionarea_porcientooperacion,
                            'reporteiluminacionarea_colorsuperficie' => $request->reportearea_colorsuperficie,
                            'reporteiluminacionarea_tiposuperficie' => $request->reportearea_tiposuperficie,
                            'reporteiluminacionarea_largo' => $request->reportearea_largo,
                            'reporteiluminacionarea_ancho' => $request->reportearea_ancho,
                            'reporteiluminacionarea_alto' => $request->reportearea_alto,
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
                            'reportearea_tipoiluminacion' => $request->reportearea_tipoiluminacion,
                            'reportearea_descripcionilimunacion' => $request->reportearea_descripcionilimunacion


                        ]);


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reporteiluminacionareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reporteiluminacionareacategoriaModel::create([
                                    'reporteiluminacionarea_id' => $area->id,
                                    'reporteiluminacioncategoria_id' => $value,
                                    'reporteiluminacionareacategoria_poe' => 0,
                                    'reporteiluminacionareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reporteiluminacionareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reporteiluminacionareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                    'reporteiluminacionareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
                                    'niveles_minimo' => $request['niveles_minimo_' . $value]


                                ]);
                            }
                        }

                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        // $request['registro_id'] = $reporteiluminacion->id;
                        $area = reporteiluminacionareaModel::findOrFail($request->reportearea_id);
                        // $area->update($request->all());
                        $area->update([
                            'registro_id' => $reporteiluminacion->id,
                            'reporteiluminacionarea_numorden' => $request->reportearea_orden,
                            'reporteiluminacionarea_nombre' => $request->reportearea_nombre,
                            'reporteiluminacionarea_instalacion' => $request->reportearea_instalacion,
                            'reporteiluminacionarea_puntos_ic' => $request->reportearea_puntos_ic,
                            'reporteiluminacionarea_puntos_pt' => $request->reportearea_puntos_pt,
                            'reporteiluminacionarea_sistemailuminacion' => $request->reportearea_sistemailuminacion,
                            'reporteiluminacionarea_luznatural' => $request->reportearea_luznatural,
                            'reporteiluminacionarea_iluminacionlocalizada' => $request->reportearea_iluminacionlocalizada,
                            'reporteiluminacionarea_porcientooperacion' => $request->reporteiluminacionarea_porcientooperacion,
                            'reporteiluminacionarea_colorsuperficie' => $request->reportearea_colorsuperficie,
                            'reporteiluminacionarea_tiposuperficie' => $request->reportearea_tiposuperficie,
                            'reporteiluminacionarea_largo' => $request->reportearea_largo,
                            'reporteiluminacionarea_ancho' => $request->reportearea_ancho,
                            'reporteiluminacionarea_alto' => $request->reportearea_alto,
                            'reporteiluminacionarea_criterio ' => $request->reportearea_criterio,
                            'reporteiluminacionarea_colortecho' => $request->reportearea_colortecho,
                            'reporteiluminacionarea_paredes' => $request->reportearea_paredes,
                            'reporteiluminacionarea_colorpiso' => $request->reportearea_colorpiso,
                            'reporteiluminacionarea_superficietecho' => $request->reportearea_superficietecho,
                            'reporteiluminacionarea_superficieparedes' => $request->reportearea_superficieparedes,
                            'reporteiluminacionarea_superficiepiso' => $request->reportearea_superficiepiso,
                            'reporteiluminacionarea_potenciaslamparas' => $request->reportearea_potenciaslamparas,
                            'reporteiluminacionarea_numlamparas' => $request->reportearea_numlamparas,
                            'reporteiluminacionarea_alturalamparas' => $request->reportearea_alturalamparas,
                            'reporteiluminacionarea_programamantenimiento' => $request->reportearea_programamantenimiento,
                            'reporteiluminacionarea_tipoiluminacion' => $request->reportearea_tipoiluminacion,
                            'reporteiluminacionarea_descripcionilimunacion' => $request->reportearea_descripcionilimunacion
                        ]);


                        $eliminar_categorias = reporteiluminacionareacategoriaModel::where('reporteiluminacionarea_id', $request->reportearea_id)
                            ->where('reporteiluminacionareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_reportecategoria_id) {
                            DB::statement('ALTER TABLE reporteiluminacionareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                                $areacategoria = reporteiluminacionareacategoriaModel::create([
                                    'reporteiluminacionarea_id' => $area->id,
                                    'reporteiluminacioncategoria_id' => $value,
                                    'reporteiluminacionareacategoria_poe' => 0,
                                    'reporteiluminacionareacategoria_total' => $request['reporteareacategoria_total_' . $value],
                                    'reporteiluminacionareacategoria_geo' => $request['reporteareacategoria_geh_' . $value],
                                    'reporteiluminacionareacategoria_actividades' => $request['reporteareacategoria_actividades_' . $value],
                                    'reporteiluminacionareacategoria_tareavisual' => $request['reporteareacategoria_tareavisual_' . $value],
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
                $reporteiluminacion->update([
                    'reporteiluminacion_criterioseleccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_criterioseleccion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PUNTOS DE ILUMINACION
            if (($request->opcion + 0) == 16) {
                if ($request->reporteiluminacionpuntos_luxmed1menor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed1menor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed1menor'] = 0;
                }


                if ($request->reporteiluminacionpuntos_luxmed2menor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed2menor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed2menor'] = 0;
                }


                if ($request->reporteiluminacionpuntos_luxmed3menor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed3menor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed3menor'] = 0;
                }



                if ($request->reporteiluminacionpuntos_luxmed1mayor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed1mayor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed1mayor'] = 0;
                }


                if ($request->reporteiluminacionpuntos_luxmed2mayor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed2mayor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed2mayor'] = 0;
                }


                if ($request->reporteiluminacionpuntos_luxmed3mayor != NULL) {
                    $request['reporteiluminacionpuntos_luxmed3mayor'] = 1;
                } else {
                    $request['reporteiluminacionpuntos_luxmed3mayor'] = 0;
                }


                $request['registro_id'] = $reporteiluminacion->id;
                if (($request->reporteiluminacionpunto_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteiluminacionpuntos AUTO_INCREMENT = 1;');

                    $punto = reporteiluminacionpuntosModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $punto = reporteiluminacionpuntosModel::findOrFail($request->reporteiluminacionpunto_id);
                    $punto->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // CONCLUSION
            if (($request->opcion + 0) == 20) {
                $reporteiluminacion->update([
                    'reporteiluminacion_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporteiluminacion_conclusion)
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
                        ->where('registro_id', $reporteiluminacion->id)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporteiluminacion->id,
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
                            ->where('registro_id', $reporteiluminacion->id)
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporteiluminacion->id,
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
                $reporteiluminacion->update([
                    'reporteiluminacion_responsable1' => $request->reporteiluminacion_responsable1,
                    'reporteiluminacion_responsable1cargo' => $request->reporteiluminacion_responsable1cargo,
                    'reporteiluminacion_responsable2' => $request->reporteiluminacion_responsable2,
                    'reporteiluminacion_responsable2cargo' => $request->reporteiluminacion_responsable2cargo
                ]);


                if ($request->reporteiluminacion_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporteiluminacion->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->reporteiluminacion_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporteiluminacion->update([
                        'reporteiluminacion_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reporteiluminacion_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reporteiluminacionresponsable1documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporteiluminacion_responsable1documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporteiluminacion->id . '/responsables informe/responsable1_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporteiluminacion->update([
                        'reporteiluminacion_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reporteiluminacionresponsable2documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporteiluminacion_responsable2documento); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporteiluminacion->id . '/responsables informe/responsable2_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporteiluminacion->update([
                        'reporteiluminacion_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 45) {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporteiluminacion->id)
                    ->delete();

                if ($request->reporteiluminacion_checkboxplanocarpeta) {
                    DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reporteiluminacion_checkboxplanocarpeta as $key => $value) {
                        $anexo = reporteplanoscarpetasModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporteiluminacion->id,
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

                if ($request->reporteiluminacion_equipoutilizadocheckbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reporteiluminacion->id)
                        ->delete();


                    DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');


                    foreach ($request->reporteiluminacion_equipoutilizadocheckbox as $key => $value) {
                        if ($request['equipoutilizado_checkboxcarta_' . $value]) {
                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        } else {
                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporteiluminacion->id,
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
                    ->where('registro_id', $reporteiluminacion->id)
                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                    ->delete();

                if ($request->reporteiluminacion_informeresultadocheckbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reporteiluminacion_informeresultadocheckbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporteiluminacion->id,
                            'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            ,
                            'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['reporteiluminacion_anexonombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['reporteiluminacion_anexoarchivo_' . $value]
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
                    ->where('registro_id', $reporteiluminacion->id)
                    ->where('reporteanexos_tipo', 2) // ANEXOS TIPO STPS Y EMA
                    ->delete();

                if ($request->reporteiluminacion_anexocheckbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->reporteiluminacion_anexocheckbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporteiluminacion->id,
                            'reporteanexos_tipo' => 2  // ANEXOS TIPO STPS Y EMA
                            ,
                            'reporteanexos_anexonombre' => ($key + 1) . '.- ' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['reporteiluminacion_anexonombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['reporteiluminacion_anexoarchivo_' . $value]
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
            $dato["reporteiluminacion_id"] = $reporteiluminacion->id;
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
