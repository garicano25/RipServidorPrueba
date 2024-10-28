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

// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

//Tablas POE
use App\modelos\reportes\reportecategoriaModel;
use App\modelos\reportes\reporteareaModel;
use App\modelos\reportes\reporteareacategoriaModel;

//Tablas revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;

// Modelos estructura reporte
use App\modelos\reportes\reportevibracioncatalogoModel;
use App\modelos\reportes\reportevibracionModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportevibracionareacategoriaModel;
use App\modelos\reportes\reportevibracionareamaquinariaModel;
use App\modelos\reportes\reportevibracionevaluacionModel;
use App\modelos\reportes\reportevibracionevaluaciondatosModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\recsensorial\catConclusionesModel;
use App\modelos\reportes\recursosPortadasInformesModel;


//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//variables del excel
$area = null;
$puntoEvaluacion = null;
$ubicacion = null;
$tipoEvaluacion = null;

$puntoMedicion = null;
$categoria = null;
$tecnico = null;

$nombre = null;
$ficha = null;

$criterioNormativo = null;
$tiempoExposicion = null;
$mediciones = null;

$limitesNOM = null;
$tiempoExposicionNOM = null;
$numeroMediciones = null;
$fechaMedicion = null;
//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reportevibracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
        // $this->middleware('roles:Superusuario,Administrador,Proyecto');
        $this->middleware('asignacionUser:INFORMES')->only('store');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionvista($proyecto_id)
    {
        $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Vibración primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 2) // Vibracion
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();

            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================

            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 2,
                    'agente_nombre' => 'Vibración',
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
            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================


            // PROVEEDOR
            //===================================================


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
                                            AND proyectoproveedores.catprueba_id = 2 -- Vibración
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            //DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            // $proveedor_id = $proveedor[0]->proveedor_id;

            $proveedor_id = 0; //BORRAR DEESPUES DE SUBIR AL SERVIDOR 



            //===================================================


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);


            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.parametros.reportevibracion', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'proveedor_id', 'catConclusiones'));
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
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. ' . $recsensorial->recsensorial_codigopostal, $texto);
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
    public function reportevibraciondatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reportevibracioncatalogoModel::findOrFail(1);
            $reporte = reportevibracionModel::where('proyecto_id', $proyecto_id)->get();


            if (count($reporte) > 0) {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = ($reporte->id + 0);
            } else {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reportevibracionModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('updated_at', 'DESC')
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                -- proyecto.id,
                                                reportevibracion.proyecto_id,
                                                reportevibracion.id,
                                                reportevibracion.catactivo_id,
                                                reportevibracion.reportevibracion_fecha,
                                                reportevibracion.reporte_mes,
                                                reportevibracion.reportevibracion_instalacion,
                                                reportevibracion.reportevibracion_catregion_activo,
                                                reportevibracion.reportevibracion_catsubdireccion_activo,
                                                reportevibracion.reportevibracion_catgerencia_activo,
                                                reportevibracion.reportevibracion_catactivo_activo,
                                                reportevibracion.reportevibracion_alcanceinforme,
                                                reportevibracion.reportevibracion_introduccion,
                                                reportevibracion.reportevibracion_objetivogeneral,
                                                reportevibracion.reportevibracion_objetivoespecifico,
                                                reportevibracion.reportevibracion_metodologia_4_1,
                                                reportevibracion.reportevibracion_ubicacioninstalacion,
                                                reportevibracion.reportevibracion_ubicacionfoto,
                                                reportevibracion.reportevibracion_procesoinstalacion,
                                                reportevibracion.reportevibracion_actividadprincipal,
                                                reportevibracion.reportevibracion_conclusion,
                                                reportevibracion.reportevibracion_responsable1,
                                                reportevibracion.reportevibracion_responsable1cargo,
                                                reportevibracion.reportevibracion_responsable1documento,
                                                reportevibracion.reportevibracion_responsable2,
                                                reportevibracion.reportevibracion_responsable2cargo,
                                                reportevibracion.reportevibracion_responsable2documento,
                                                reportevibracion.created_at,
                                                reportevibracion.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportevibracion ON proyecto.id = reportevibracion.proyecto_id
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . '
                                                AND reportevibracion.reportevibracion_instalacion != ""
                                            ORDER BY
                                                reportevibracion.updated_at DESC');
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
                ->where('agente_id', 2) //vibracion
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


            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportevibracion_fecha != NULL) {
                $reportefecha = $reporte->reportevibracion_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => $reporte->reportevibracion_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => $reporte->reportevibracion_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => $reporte->reportevibracion_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => $reporte->reportevibracion_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => $reporte->reporte_mes,
                    'reporte_alcanceinforme' => $reporte->reportevibracion_alcanceinforme
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
                    'reporte_mes' => "",
                    'reporte_alcanceinforme' => 0
                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_introduccion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_introduccion_guardado'] = 1;
                } else {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reportevibracion_introduccion;
            } else {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo->reportevibracioncatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_objetivogeneral != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reportevibracion_objetivogeneral;
            } else {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo->reportevibracioncatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_objetivoespecifico != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reportevibracion_objetivoespecifico;
            } else {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo->reportevibracioncatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_metodologia_4_1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reportevibracion_metodologia_4_1;
            } else {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo->reportevibracioncatalogo_metodologia_4_1;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_ubicacioninstalacion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reportevibracion_ubicacioninstalacion;
            } else {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo->reportevibracioncatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] > 0 && $reporte->reportevibracion_ubicacionfoto != NULL) {
                $ubicacionfoto = $reporte->reportevibracion_ubicacionfoto;
            }


            $dato['reporte_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportevibracion_procesoinstalacion != NULL) {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reportevibracion_procesoinstalacion;
            } else {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }


            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportevibracion_actividadprincipal != NULL) {
                $actividadprincipal = $reporte->reportevibracion_actividadprincipal;
            } else {
                $actividadprincipal = $recsensorial->recsensorial_actividadprincipal;
            }


            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $actividadprincipal);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportevibracion_conclusion != NULL) {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reportevibracion_conclusion;
            } else {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo->reportevibracioncatalogo_conclusion;
            }


            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportevibracion_responsable1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                    'responsable1' => $reporte->reportevibracion_responsable1,
                    'responsable1cargo' => $reporte->reportevibracion_responsable1cargo,
                    'responsable1documento' => $reporte->reportevibracion_responsable1documento,
                    'responsable2' => $reporte->reportevibracion_responsable2,
                    'responsable2cargo' => $reporte->reportevibracion_responsable2cargo,
                    'responsable2documento' => $reporte->reportevibracion_responsable2documento,
                    'proyecto_id' => $reporte->proyecto_id,
                    'registro_id' => $reporte->id
                );
            } else {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reportevibracionModel::where('reportevibracion_responsable1', '!=', '')
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->get();


                if (count($reportehistorial) > 0 && $reportehistorial[0]->reportevibracion_responsable1 != NULL) {
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => $reportehistorial[0]->reportevibracion_responsable1,
                        'responsable1cargo' => $reportehistorial[0]->reportevibracion_responsable1cargo,
                        'responsable1documento' => $reportehistorial[0]->reportevibracion_responsable1documento,
                        'responsable2' => $reportehistorial[0]->reportevibracion_responsable2,
                        'responsable2cargo' => $reportehistorial[0]->reportevibracion_responsable2cargo,
                        'responsable2documento' => $reportehistorial[0]->reportevibracion_responsable2documento,
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


            //===================================================


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
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
     * @param  $agente_nombre
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibraciontabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 2)
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
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                } else {
                    $value->descripcion_fuente = $value->descripcion . '<br><span style="color: #999999; font-style: italic;">Fuente: ' . $value->fuente . '</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                    // $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle"><i class="fa fa-trash fa-2x"></i></button>';

                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-eye fa-2x"></i></button>';
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
    public function reportevibraciondefinicioneliminar($definicion_id)
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
     * @param  int  $reporteregistro_id
     * @param  int  $archivo_opcion
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionmapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reportevibracionModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporte->reportevibracion_ubicacionfoto);
        } else {
            return Storage::download($reporte->reportevibracion_ubicacionfoto);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionareas($proyecto_id) 
    {
        try {
            $numero_registro = 0;
            $numero_registro2 = 0;
            $numero_registro3 = 0;
            $total_singuardar = 0;
            $instalacion = 'XXX';
            $area = 'XXX';
            $area2 = 'XXX';
            $area3 = 'XXX';
            $selectareasoption = '<option value=""></option>';
            $tabla_5_4 = '';
            $tabla_5_5 = '';
            $tabla_6_1 = '';


            $areas = DB::select('SELECT
                                     reportearea.proyecto_id,
                                     reportearea.id,
                                     reportearea.aplica_vibracion,
                                     reportearea.reportearea_instalacion,
                                     reportearea.reportearea_nombre,
                                     reportearea.reportearea_orden,
                                     reportearea.reportearea_porcientooperacion,
                                     reportearea.reportevibracionarea_porcientooperacion,
                                     reportearea.reportearea_tipoexposicion,
                                     reporteareacategoria.reportecategoria_id,
                                     reportecategoria.reportecategoria_orden,
                                     reportecategoria.reportecategoria_nombre,
                                     IFNULL((
                                         SELECT
                                             IF(reporteareacategoria.reportecategoria_id, "activo", "") AS checked
                                         FROM
                                             reportevibracionareacategoria
                                         WHERE
                                             reportevibracionareacategoria.reportearea_id = reportearea.id
                                             AND reportevibracionareacategoria.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                         LIMIT 1
                                     ), "") AS activo,
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


                    if ($value->reportevibracionarea_porcientooperacion > 0) {
                        $numero_registro2 += 1;

                        //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                        //==================================================

                        $tabla_6_1 .= '<tr>
                                            <td>' . $numero_registro2 . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportevibracionarea_porcientooperacion . '%</td>
                                        </tr>';
                    }
                } else {
                    $value->area_nombre = $area;
                    $value->numero_registro = $numero_registro;
                }


                if ($value->activo) {
                    $value->reportecategoria_nombre_texto = '<span class="text-danger">' . $value->reportecategoria_nombre . '</span>';
                } else {
                    $value->reportecategoria_nombre_texto = $value->reportecategoria_nombre;
                }


                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                // $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';


                if ($value->aplica_vibracion === NULL) {
                    $total_singuardar += 1;
                }


                if ($value->reportevibracionarea_porcientooperacion > 0) {
                    if ($value->activo) {
                        //TABLA 5.4.- Actividades del personal expuesto
                        //==================================================


                        if ($area3 != $value->reportearea_nombre) {
                            $area3 = $value->reportearea_nombre;
                            $numero_registro3 += 1;
                        }


                        $tabla_5_4 .= '<tr>
                                            <td>' . $numero_registro3 . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportecategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
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

                    if (($key + 0) == (count($areas) - 1)) {
                        $selectareasoption .= '</optgroup>';
                    }
                }
            }


            //------------------------------------------------
            //TABLA 5.5.- Identificación de las áreas


            $areasmaquinaria = DB::select('SELECT
                                                reportearea.proyecto_id,
                                                reportevibracionmaquinaria.reportearea_id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionmaquinaria.reportevibracionmaquinaria_nombre,
                                                reportevibracionmaquinaria.reportevibracionmaquinaria_cantidad 
                                            FROM
                                                reportevibracionmaquinaria
                                                LEFT JOIN reportearea ON reportevibracionmaquinaria.reportearea_id = reportearea.id
                                            WHERE
                                                reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportearea.reportevibracionarea_porcientooperacion > 0
                                            ORDER BY
                                                reportearea.reportearea_orden ASC,
                                                reportearea.reportearea_nombre ASC,
                                                reportevibracionmaquinaria.reportevibracionmaquinaria_nombre ASC');


            $numero_registro = 0;
            $area = 'XXX';
            foreach ($areasmaquinaria as $key => $value) {
                if ($area != $value->reportearea_nombre) {
                    $area = $value->reportearea_nombre;
                    $numero_registro += 1;
                }


                $tabla_5_5 .= '<tr>
                                    <td>' . $numero_registro . '</td>
                                    <td>' . $value->reportearea_instalacion . '</td>
                                    <td>' . $value->reportearea_nombre . '</td>
                                    <td>' . $value->reportevibracionmaquinaria_nombre . '</td>
                                    <td>' . $value->reportevibracionmaquinaria_cantidad . '</td>
                                    <td>' . $value->reportearea_tipoexposicion . '</td>
                                </tr>';
            }


            //------------------------------------------------


            // respuesta
            $dato['data'] = $areas;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_4"] = $tabla_5_4;
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_4"] = '<tr><td colspan="5">Error al consultar los datos</td></tr>';
            $dato["tabla_5_5"] = '<tr><td colspan="6">Error al consultar los datos</td></tr>';
            $dato["tabla_6_1"] = '<tr><td colspan="4">Error al consultar los datos</td></tr>';
            $dato["selectareasoption"] = '<option value="">Error al consultar áreas</option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $area_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionareacategorias($proyecto_id, $area_id)
    {
        try {
            $areacategorias = DB::select('SELECT
                                                reportecategoria.proyecto_id,
                                                reporteareacategoria.reportearea_id,
                                                reportecategoria.id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reportevibracionareacategoria.reportecategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reportevibracionareacategoria
                                                    WHERE
                                                        reportevibracionareacategoria.reportearea_id = reporteareacategoria.reportearea_id
                                                        AND reportevibracionareacategoria.reportecategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reporteareacategoria.reporteareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades
                                            FROM
                                                reporteareacategoria
                                                INNER JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteareacategoria.reportearea_id = ' . $area_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');


            $numero_registro = 0;
            $areacategorias_lista = '';


            foreach ($areacategorias as $key => $value) {
                $numero_registro += 1;


                $areacategorias_lista .= '<tr>
                                            <td with="60">
                                                <div class="switch" style="border: 0px #000 solid;">
                                                    <label>
                                                        <input type="checkbox" name="checkbox_categoria_id[]" value="' . $value->id . '" ' . $value->checked . '/>
                                                        <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td with="240">
                                                ' . $value->reportecategoria_nombre . '
                                            </td>
                                            <td with="100">
                                                <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_total_' . $value->id . '" value="' . $value->reporteareacategoria_total . '" readonly>
                                            </td>
                                            <td with="100">
                                                <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_geh_' . $value->id . '" value="' . $value->reporteareacategoria_geh . '" readonly>
                                            </td>
                                            <td with="">
                                                <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_actividades_' . $value->id . '" readonly>' . $value->reporteareacategoria_actividades . '</textarea>
                                            </td>
                                        </tr>';
            }


            //-----------------------------------------------------------


            $areamaquinarias = DB::select('SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                reportevibracionmaquinaria.reportevibracionmaquinaria_nombre,
                                                reportevibracionmaquinaria.reportevibracionmaquinaria_cantidad 
                                            FROM
                                                reportevibracionmaquinaria
                                                LEFT JOIN reportearea ON reportevibracionmaquinaria.reportearea_id = reportearea.id
                                            WHERE
                                                reportearea.id = ' . $area_id);


            $areamaquinarias_lista = '';


            foreach ($areamaquinarias as $key => $value) {
                $areamaquinarias_lista .= '<tr>
                                                <td><input type="text" class="form-control" name="reportevibracionmaquinaria_nombre[]" value="' . $value->reportevibracionmaquinaria_nombre . '" required></td>
                                                <td><input type="number" min="1" class="form-control" name="reportevibracionmaquinaria_cantidad[]" value="' . $value->reportevibracionmaquinaria_cantidad . '" required></td>
                                                <td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>
                                            </tr>';
            }


            //-----------------------------------------------------------


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;
            $dato['areamaqinarias'] = $areamaquinarias_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areacategorias'] = '<tr><td colspan="5">Error al cargar las categorías</td></tr>';
            $dato['areamaqinarias'] = '<tr><td colspan="3">Error al cargar las maquinarías</td></tr>';
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
    public function reportevibracionevaluaciontabla($proyecto_id)
    {
        try {
            // PUNTOS DE EVALACION POR AREA
            //==========================================


            $areas_evaluadas = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportearea.reportearea_tipoexposicion,
                                                IFNULL((
                                                    SELECT
                                                        -- TABLA.proyecto_id, 
                                                        -- TABLA.reportearea_id, 
                                                        COUNT(TABLA.reportevibracionevaluacion_punto) AS total
                                                    FROM
                                                        reportevibracionevaluacion AS TABLA
                                                    WHERE
                                                        TABLA.proyecto_id = reportevibracionevaluacion.proyecto_id
                                                        AND TABLA.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ), 0) AS total_puntosarea,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto 
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportearea.reportearea_orden ASC,
                                                reportearea.reportearea_nombre ASC,
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');


            $numero_registro = 0;
            $area = 'XXXXX';
            $dato['tabla_reporte_6_2'] = NULL;
            $dato['total_puntosmedicion'] = 0;
            foreach ($areas_evaluadas as $key => $value) {
                if ($area != $value->reportearea_nombre) {
                    $area = $value->reportearea_nombre;
                    $dato['total_puntosmedicion'] += ($value->total_puntosarea + 0);

                    $numero_registro += 1;
                }


                $dato['tabla_reporte_6_2'] .= '<tr>
                                                    <td>' . $numero_registro . '</td>
                                                    <td>' . $value->reportearea_instalacion . '</td>
                                                    <td>' . $value->reportearea_nombre . '</td>
                                                    <td>' . $value->reportecategoria_nombre . '</td>
                                                    <td>' . $value->reportearea_tipoexposicion . '</td>
                                                    <td>' . $value->total_puntosarea . '</td>
                                                </tr>';
            }


            // TABLA PUNTOS DE EVALUACION
            //==========================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 2)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //-----------------------------


            $evaluacion = DB::select('SELECT
                                            reportevibracionevaluacion.proyecto_id,
                                            reportevibracionevaluacion.id,
                                            reportevibracionevaluacion.reportearea_id,
                                            reportearea.reportearea_instalacion,
                                            reportearea.reportearea_orden,
                                            reportearea.reportearea_nombre,
                                            reportearea.reportevibracionarea_porcientooperacion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                            reportevibracionevaluacion.reportecategoria_id,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre,
                                            reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                            reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                            reportearea.reportearea_tipoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                            (
                                                CASE
                                                    WHEN (reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion+0) = 1 THEN "Límites por NOM-024-STPS-2001"
                                                    WHEN (reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion+0) = 2 THEN "Límites por interpolación"
                                                    ELSE "Método ISO"
                                                END
                                            ) AS tipoevaluacion_texto,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                            reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                            reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                            reportevibracionevaluacion.reportevibracionevaluacion_fecha 
                                        FROM
                                            reportevibracionevaluacion
                                            LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                        WHERE
                                            reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                            reportearea.reportearea_orden ASC,
                                            reportearea.reportearea_nombre ASC,
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC,
                                            reportevibracionevaluacion.reportevibracionevaluacion_nombre ASC');


            foreach ($evaluacion as $key => $value) {
                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // TABLA 7.1 RESULTADOS
            //==========================================


            $dato['tabla_reporte_7_1'] = NULL;


            if (count($evaluacion) > 0) {
                switch (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0)) {
                    case (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2):

                        //ENCABEADO TABLA
                        //======================================


                        if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                            $dato['tabla_reporte_7_1'] .= '<thead>
                                                                <tr>
                                                                    <th width="3%">No. de medición</th>
                                                                    <th width="7%">Área</th>
                                                                    <th width="7%">Punto de<br>evaluación</th>
                                                                    <th width="8%">Categoría</th>
                                                                    <th width="5%">Tiempo<br>exposición</th>
                                                                    <th width="10%">Frecuencia central de tercio de octava (H<sub>z</sub>)</th>
                                                                    <th width="10%">Medición de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="10%">Límite de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>) del tiempo de exposición</th>
                                                                    <th width="10%">Medición de aceleración transversal en (a<sub>x</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="10%">Medición de aceleración transversal (a<sub>y</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="10%">Límite de aceleración transversal en (ax, ay) (m/s<sup>2</sup>) del tiempo de exposición</th>
                                                                    <th width="10%">Cumplimiento normativo</th>
                                                                </tr>
                                                            </thead>';


                            $dato['tabla_reporte_7_1_rowsGroup'] = array(0, 1, 2, 3, 4, 11);
                            $dato['tabla_reporte_7_1_columnaresultado'] = 11;
                        } else {
                            $dato['tabla_reporte_7_1'] .= '<thead>
                                                                <tr>
                                                                    <th width="3%" rowspan="2">No. de medición</th>
                                                                    <th width="7%" rowspan="2">Área</th>
                                                                    <th width="7%" rowspan="2">Punto de<br>evaluación</th>
                                                                    <th width="8%" rowspan="2">Categoría</th>
                                                                    <th width="5%" rowspan="2">Tiempo<br>exposición</th>
                                                                    <th width="5.38%" rowspan="2">Frecuencia central de tercio de octava (H<sub>z</sub>)</th>
                                                                    <th width="" colspan="3">Medición de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="5.38%" rowspan="2">Límite de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>) del tiempo de exposición</th>
                                                                    <th width="" colspan="3">Medición de aceleración transversal en (a<sub>x</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="" colspan="3">Medición de aceleración transversal (a<sub>y</sub>) (m/s<sup>2</sup>)</th>
                                                                    <th width="5.38%" rowspan="2">Límite de aceleración transversal en (ax, ay) (m/s<sup>2</sup>) del tiempo de exposición</th>
                                                                    <th width="5.38%" rowspan="2">Cumplimiento normativo</th>
                                                                </tr>
                                                                <tr>
                                                                    <th width="5.38%">Med. 1</th>
                                                                    <th width="5.38%">Med. 2</th>
                                                                    <th width="5.38%">Med. 3</th>
                                                                    <th width="5.38%">Med. 1</th>
                                                                    <th width="5.38%">Med. 2</th>
                                                                    <th width="5.38%">Med. 3</th>
                                                                    <th width="5.38%">Med. 1</th>
                                                                    <th width="5.38%">Med. 2</th>
                                                                    <th width="5.38%">Med. 3</th>
                                                                </tr>
                                                            </thead>';


                            $dato['tabla_reporte_7_1_rowsGroup'] = array(0, 1, 2, 3, 4, 17);
                            $dato['tabla_reporte_7_1_columnaresultado'] = 17;
                        }


                        // DATOS DE LA TABLA
                        //======================================


                        $datos = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,

                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                        $dato['tabla_reporte_7_1'] .= '<tbody>';

                        if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                            foreach ($datos as $key => $value) {
                                $dato['tabla_reporte_7_1'] .= '<tr>
                                                                        <td>' . $value->reportevibracionevaluacion_punto . '</td>
                                                                        <td>' . $value->reportearea_nombre . '</td>
                                                                        <td>' . $value->reportevibracionevaluacion_puntoevaluacion . '</td>
                                                                        <td>' . $value->reportecategoria_nombre . '</td>
                                                                        <td>' . $value->reportevibracionevaluacion_tiempoexposicion . '</td>
                                                                        <td style="color: #555555;">' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                                        <td style="color: ' . $value->az1_color . ';">' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                                        <td style="color: #555555;">' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                                        <td style="color: ' . $value->ax1_color . ';">' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                                        <td style="color: ' . $value->ay1_color . ';">' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                                        <td style="color: #555555;">' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                                        <td>' . $value->resultado . '</td>
                                                                    </tr>';
                            }
                        } else {
                            foreach ($datos as $key => $value) {
                                $dato['tabla_reporte_7_1'] .= '<tr>
                                                                        <td>' . $value->reportevibracionevaluacion_punto . '</td>
                                                                        <td>' . $value->reportearea_nombre . '</td>
                                                                        <td>' . $value->reportevibracionevaluacion_puntoevaluacion . '</td>
                                                                        <td>' . $value->reportecategoria_nombre . '</td>
                                                                        <td>' . $value->reportevibracionevaluacion_tiempoexposicion . '</td>
                                                                        <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                                        <td style="color: ' . $value->az1_color . ';">' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                                        <td style="color: ' . $value->az2_color . ';">' . $value->reportevibracionevaluaciondatos_az2 . '</td>
                                                                        <td style="color: ' . $value->az3_color . ';">' . $value->reportevibracionevaluaciondatos_az3 . '</td>
                                                                        <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                                        <td style="color: ' . $value->ax1_color . ';">' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                                        <td style="color: ' . $value->ax2_color . ';">' . $value->reportevibracionevaluaciondatos_ax2 . '</td>
                                                                        <td style="color: ' . $value->ax3_color . ';">' . $value->reportevibracionevaluaciondatos_ax3 . '</td>
                                                                        <td style="color: ' . $value->ay1_color . ';">' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                                        <td style="color: ' . $value->ay2_color . ';">' . $value->reportevibracionevaluaciondatos_ay2 . '</td>
                                                                        <td style="color: ' . $value->ay3_color . ';">' . $value->reportevibracionevaluaciondatos_ay3 . '</td>
                                                                        <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                                        <td>' . $value->resultado . '</td>
                                                                    </tr>';
                            }
                        }

                        $dato['tabla_reporte_7_1'] .= '</tbody>';


                        break;
                    default:

                        //ENCABEADO TABLA
                        //======================================


                        $dato['tabla_reporte_7_1'] .= '<thead>
                                                            <tr>
                                                                <th width="60">No. de<br>medición</th>
                                                                <th width="80">Fecha de<br>medición</th>
                                                                <th width="150">Área</th>
                                                                <th width="">Categoría</th>
                                                                <th width="150">Identificación</th>
                                                                <th width="100">Promedio<br>(m/seg<sup>2</sup>)</th>
                                                                <th width="100">Valor Máximo<br>Permisible (m/seg<sup>2</sup>)</th>
                                                                <th width="100">Cumplimiento<br>normativo</th>
                                                            </tr>
                                                        </thead>';


                        $dato['tabla_reporte_7_1_rowsGroup'] = array(2, 0);
                        $dato['tabla_reporte_7_1_columnaresultado'] = 7;


                        // DATOS DE LA TABLA
                        //======================================


                        $datos = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                IFNULL((
                                                    IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                ), "N/A") AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


                        $dato['tabla_reporte_7_1'] .= '<tbody>';

                        foreach ($datos as $key => $value) {
                            $dato['tabla_reporte_7_1'] .= '<tr>
                                                                    <td>' . $value->reportevibracionevaluacion_punto . '</td>
                                                                    <td>' . $value->reportevibracionevaluacion_fecha . '</td>
                                                                    <td>' . $value->reportearea_nombre . '</td>
                                                                    <td>' . $value->reportecategoria_nombre . '</td>
                                                                    <td>' . $value->reportevibracionevaluacion_puntoevaluacion . '</td>
                                                                    <td>' . $value->reportevibracionevaluacion_promedio . '</td>
                                                                    <td>' . $value->reportevibracionevaluacion_valormaximo . '</td>
                                                                    <td>' . $value->resultado . '</td>
                                                                </tr>';
                        }

                        $dato['tabla_reporte_7_1'] .= '</tbody>';


                        break;
                }
            } else {
                $dato['tabla_reporte_7_1_rowsGroup'] = array(0);
                $dato['tabla_reporte_7_1_columnaresultado'] = 0;


                $dato['tabla_reporte_7_1'] .= '<thead>
                                                    <tr>
                                                        <th width="3%">No. de medición</th>
                                                        <th width="9%">Área</th>
                                                        <th width="9%">Punto de evaluación</th>
                                                        <th width="9%">Categoría</th>
                                                        <th width="10%">Frecuencia central de tercio de octava (H<sub>z</sub>)</th>
                                                        <th width="10%">Medición de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>)</th>
                                                        <th width="10%">Límite de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>) para X de exposición</th>
                                                        <th width="10%">Medición de aceleración transversal en (a<sub>x</sub>) (m/s<sup>2</sup>)</th>
                                                        <th width="10%">Medición de aceleración transversal (a<sub>y</sub>) (m/s<sup>2</sup>)</th>
                                                        <th width="10%">Límite de aceleración transversal en (ax, ay) (m/s<sup>2</sup>) para X de exposición</th>
                                                        <th width="10%">Cumplimiento normativo</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>';
            }


            //==========================================


            // respuesta
            $dato['data'] = $evaluacion;
            $dato["total"] = count($evaluacion);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato['tabla_reporte_6_2'] = NULL;
            $dato['total_puntosmedicion'] = 0;
            $dato['tabla_reporte_7_1'] = NULL;
            $dato['tabla_reporte_7_1_rowsGroup'] = array(0);
            $dato['tabla_reporte_7_1_columnaresultado'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reportearea_id
     * @param  int $reportecategoria_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionevaluacioncategorias($reportearea_id, $reportecategoria_id)
    {
        try {
            $areacategorias = DB::select('SELECT
                                                reportecategoria.proyecto_id,
                                                reporteareacategoria.reportearea_id,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionareacategoria.reportecategoria_id AS activo 
                                            FROM
                                                reporteareacategoria
                                                LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionareacategoria ON reporteareacategoria.reportearea_id = reportevibracionareacategoria.reportearea_id 
                                                AND reportecategoria.id = reportevibracionareacategoria.reportecategoria_id 
                                            WHERE
                                                reporteareacategoria.reportearea_id = ' . $reportearea_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_nombre ASC');


            $dato['select_areacategorias'] = '<option value=""></option>';
            foreach ($areacategorias as $key => $value) {
                if (($reportecategoria_id + 0) == ($value->reportecategoria_id + 0)) {
                    $dato['select_areacategorias'] .= '<option value="' . $value->reportecategoria_id . '" selected>' . $value->reportecategoria_nombre . '</option>';
                } else {
                    $dato['select_areacategorias'] .= '<option value="' . $value->reportecategoria_id . '">' . $value->reportecategoria_nombre . '</option>';
                }
            }


            //-----------------------------------------------------------


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['select_areacategorias'] = '<option value="">Error al consultar las categorías</option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reportevibracionevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionevaluaciondatos($reportevibracionevaluacion_id)
    {
        try {
            $datos = DB::select('SELECT
                                    reportevibracionevaluaciondatos.reportevibracionevaluacion_id,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3,
                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite 
                                FROM
                                    reportevibracionevaluaciondatos
                                WHERE
                                    reportevibracionevaluaciondatos.reportevibracionevaluacion_id = ' . $reportevibracionevaluacion_id . ' 
                                ORDER BY
                                    (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


            // respuesta
            $dato["datos"] = $datos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['datos'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $reportevibracionevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionevaluacioneliminar($reportevibracionevaluacion_id)
    {
        try {
            $punto = reportevibracionevaluacionModel::where('id', $reportevibracionevaluacion_id)->delete();
            $punto_datos = reportevibracionevaluaciondatosModel::where('reportevibracionevaluacion_id', $reportevibracionevaluacion_id)->delete();


            // respuesta
            $dato["msj"] = 'Punto de medición eliminado correctamente';
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
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionmatriztabla($proyecto_id)
    {
        try {
            $numero_registro = 0;
            $dato["tabla_matriz"] = null;
            $dato['tabla_matriz_rowsGroup'] = array(0);
            $proyecto = proyectoModel::with(['recsensorial'])->findOrFail($proyecto_id);


            $perforacion = 0;

            if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1) {
                    $perforacion = 1;
                }
            }


            // CONFIGURACION EVALUACION
            //======================================================


            $evaluacion = DB::select('SELECT
                                            reportevibracionevaluacion.proyecto_id,
                                            reportevibracionevaluacion.id,
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                            reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                            reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                            reportevibracionevaluacion.reportevibracionevaluacion_fecha 
                                        FROM
                                            reportevibracionevaluacion
                                        WHERE
                                            reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


            // $proyecto->catregion_id = 1;
            // $perforacion = 0;
            // $evaluacion[0]->reportevibracionevaluacion_tipoevaluacion = 1;
            // $evaluacion[0]->reportevibracionevaluacion_numeromediciones = 3;


            // ENCABEZADO MATRIZ
            //======================================================


            if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 1 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Gerencia o<br>activo</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Número de<br>personas<br>en el área</th>
                                                            <th rowspan="3" width="3%">Grupo de<br>exposición<br>homogénea</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="6" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="2">Aceleración longitudinal</th>
                                                            <th colspan="3">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med.<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    } else {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Gerencia o<br>activo</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Número de<br>personas<br>en el área</th>
                                                            <th rowspan="3" width="3%">Grupo de<br>exposición<br>homogénea</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="12" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="4">Aceleración longitudinal</th>
                                                            <th colspan="7">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med. 1<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    }
                } else {
                    $dato["tabla_matriz"] = '<thead>
                                                    <tr>
                                                        <th rowspan="2" width="1%">Contador</th>
                                                        <th rowspan="2" width="4%">Subdirección o<br>corporativo</th>
                                                        <th rowspan="2" width="4%">Gerencia o<br>activo</th>
                                                        <th rowspan="2" width="4%">Instalación</th>
                                                        <th rowspan="2" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                        <th rowspan="2" width="">Nombre</th>
                                                        <th rowspan="2" width="4%">Ficha</th>
                                                        <th rowspan="2" width="">Categoría</th>
                                                        <th rowspan="2" width="2%">Número de<br>personas<br>en el área</th>
                                                        <th rowspan="2" width="2%">Grupo de<br>exposición<br>homogénea</th>
                                                        <th colspan="2" width="">(Frecuencia / Medición de aceleración / LMPE)</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="4%">Extremidad superior</th>
                                                        <th width="4%">Cuerpo entero</th>
                                                    </tr>
                                            </thead>';
                }
            } else if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 2 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Gerencia o<br>activo</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="6" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="2">Aceleración longitudinal</th>
                                                            <th colspan="3">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med.<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    } else {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Gerencia o<br>activo</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="12" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="4">Aceleración longitudinal</th>
                                                            <th colspan="7">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med. 1<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    }
                } else {
                    $dato["tabla_matriz"] = '<thead>
                                                    <tr>
                                                        <th rowspan="2" width="1%">Contador</th>
                                                        <th rowspan="2" width="4%">Subdirección o<br>corporativo</th>
                                                        <th rowspan="2" width="4%">Gerencia o<br>activo</th>
                                                        <th rowspan="2" width="4%">Instalación</th>
                                                        <th rowspan="2" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                        <th rowspan="2" width="">Nombre</th>
                                                        <th rowspan="2" width="4%">Ficha</th>
                                                        <th rowspan="2" width="">Categoría</th>
                                                        <th colspan="2" width="">(Frecuencia / Medición de aceleración / LMPE)</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="4%">Extremidad superior</th>
                                                        <th width="4%">Cuerpo entero</th>
                                                    </tr>
                                            </thead>';
                }
            } else if (count($evaluacion) > 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Coordinación</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="6" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="2">Aceleración longitudinal</th>
                                                            <th colspan="3">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med.<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med.<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    } else {
                        $dato["tabla_matriz"] = '<thead>
                                                        <tr>
                                                            <th rowspan="3" width="1%">Contador</th>
                                                            <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                            <th rowspan="3" width="4%">Coordinación</th>
                                                            <th rowspan="3" width="4%">Instalación</th>
                                                            <th rowspan="3" width="">Nombre</th>
                                                            <th rowspan="3" width="3%">Ficha</th>
                                                            <th rowspan="3" width="">Categoría</th>
                                                            <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                            <th colspan="12" width="">Cuerpo entero</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                            <th colspan="4">Aceleración longitudinal</th>
                                                            <th colspan="7">Aceleración transversal</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="3%">Med. 1<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>x</sub>)</th>
                                                            <th width="3%">Med. 1<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 2<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Med. 3<br>(a<sub>y</sub>)</th>
                                                            <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                        </tr>
                                                </thead>';
                    }
                } else {
                    $dato["tabla_matriz"] = '<thead>
                                                    <tr>
                                                        <th rowspan="2" width="1%">Contador</th>
                                                        <th rowspan="2" width="4%">Subdirección o<br>corporativo</th>
                                                        <th rowspan="2" width="4%">Coordinación</th>
                                                        <th rowspan="2" width="4%">Instalación</th>
                                                        <th rowspan="2" width="">Nombre</th>
                                                        <th rowspan="2" width="4%">Ficha</th>
                                                        <th rowspan="2" width="">Categoría</th>
                                                        <th colspan="2" width="">(Frecuencia / Medición de aceleración / LMPE)</th>
                                                    </tr>
                                                    <tr>
                                                        <th width="4%">Extremidad superior</th>
                                                        <th width="4%">Cuerpo entero</th>
                                                    </tr>
                                            </thead>';
                }
            } else {
                $dato["tabla_matriz"] = '<thead>
                                                <tr>
                                                    <th rowspan="3" width="1%">Contador</th>
                                                    <th rowspan="3" width="4%">Subdirección o<br>corporativo</th>
                                                    <th rowspan="3" width="4%">Gerencia o<br>activo</th>
                                                    <th rowspan="3" width="4%">Instalación</th>
                                                    <th rowspan="3" width="4%">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                                    <th rowspan="3" width="">Nombre</th>
                                                    <th rowspan="3" width="3%">Ficha</th>
                                                    <th rowspan="3" width="">Categoría</th>
                                                    <th rowspan="3" width="3%">Número de<br>personas<br>en el área</th>
                                                    <th rowspan="3" width="3%">Grupo de<br>exposición<br>homogénea</th>
                                                    <th rowspan="3" width="3%">Extremidad superior (Frecuencia / Medición de aceleración / Límite)</th>
                                                    <th colspan="6" width="">Cuerpo entero</th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" width="2%">Frecuencia<br>(Hz)</th>
                                                    <th colspan="2">Aceleración longitudinal</th>
                                                    <th colspan="3">Aceleración transversal</th>
                                                </tr>
                                                <tr>
                                                    <th width="3%">Med.<br>(a<sub>z</sub>)</th>
                                                    <th width="3%">Límite<br>(a<sub>z</sub>)</th>
                                                    <th width="3%">Med.<br>(a<sub>x</sub>)</th>
                                                    <th width="3%">Med.<br>(a<sub>y</sub>)</th>
                                                    <th width="3%">Límite<br>(a<sub>x</sub>, a<sub>y</sub>)</th>
                                                </tr>
                                        </thead>';
            }


            // DATOS MATRIZ
            //======================================================


            $dato["tabla_matriz"] .= '<tbody>';

            if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 1 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                (
                                                    CASE
                                                        WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                        ELSE catgerencia_nombre
                                                    END
                                                ) AS gerencia_activo,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        $numero_registro = 0;
                        $punto = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;
                            }


                            $dato["tabla_matriz"] .=    '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $value->catsubdireccion_nombre . '</td>
                                                            <td>' . $value->gerencia_activo . '</td>
                                                            <td>' . $value->reportearea_instalacion . '</td>
                                                            <td>' . $value->reportearea_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                            <td>' . $value->reportecategoria_nombre . '</td>
                                                            <td>' . $value->personas_area . '</td>
                                                            <td>' . $value->geh . '</td>
                                                            <td>N/A</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                        </tr>';
                        }


                        $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 4, 8, 9, 0, 5, 6, 7, 10);
                    } else {
                        $numero_registro = 0;
                        $punto = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;
                            }


                            $dato["tabla_matriz"] .=    '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $value->catsubdireccion_nombre . '</td>
                                                            <td>' . $value->gerencia_activo . '</td>
                                                            <td>' . $value->reportearea_instalacion . '</td>
                                                            <td>' . $value->reportearea_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                            <td>' . $value->reportecategoria_nombre . '</td>
                                                            <td>' . $value->personas_area . '</td>
                                                            <td>' . $value->geh . '</td>
                                                            <td>N/A</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                        </tr>';
                        }


                        $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 4, 8, 9, 0, 5, 6, 7, 10);
                    }
                } else {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                (
                                                    CASE
                                                        WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                        ELSE catgerencia_nombre
                                                    END
                                                ) AS gerencia_activo,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                IFNULL((
                                                    IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                ), "N/A") AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


                    $numero_registro = 0;
                    $punto = 'X';
                    foreach ($matriz as $key => $value) {
                        $numero_registro += 1;


                        $dato["tabla_matriz"] .=    '<tr>
                                                        <td>' . $numero_registro . '</td>
                                                        <td>' . $value->catsubdireccion_nombre . '</td>
                                                        <td>' . $value->gerencia_activo . '</td>
                                                        <td>' . $value->reportearea_instalacion . '</td>
                                                        <td>' . $value->reportearea_nombre . '</td>
                                                        <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                        <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                        <td>' . $value->reportecategoria_nombre . '</td>
                                                        <td>' . $value->personas_area . '</td>
                                                        <td>' . $value->geh . '</td>
                                                        <td>N/A</td>
                                                        <td>' . $value->reportevibracionevaluacion_promedio . ' / ' . $value->reportevibracionevaluacion_valormaximo . '</td>
                                                    </tr>';
                    }


                    $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 5, 6, 7, 4, 8, 9, 0);
                }
            } else if (count($evaluacion) > 0 && ($proyecto->catregion_id + 0) == 2 && $perforacion == 0) {
                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                (
                                                    CASE
                                                        WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                        ELSE catgerencia_nombre
                                                    END
                                                ) AS gerencia_activo,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                
                                                IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                (
                                                    SELECT
                                                        -- DATOS.reportevibracionevaluacion_id,
                                                        -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                        -- SUM(
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        -- ) AS total_fueranorma,
                                                        IF(SUM(
                                                            IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                            IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                        ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                        -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                        -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                    FROM
                                                        reportevibracionevaluaciondatos AS DATOS
                                                    WHERE
                                                        DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                    GROUP BY
                                                        DATOS.reportevibracionevaluacion_id
                                                    LIMIT 1
                                                ) AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC');


                    if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                        $numero_registro = 0;
                        $punto = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;
                            }


                            $dato["tabla_matriz"] .=    '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $value->catsubdireccion_nombre . '</td>
                                                            <td>' . $value->gerencia_activo . '</td>
                                                            <td>' . $value->reportearea_instalacion . '</td>
                                                            <td>' . $value->reportearea_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                            <td>' . $value->reportecategoria_nombre . '</td>
                                                            <td>N/A</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                        </tr>';
                        }


                        $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 4, 0, 5, 6, 7);
                    } else {
                        $numero_registro = 0;
                        $punto = 'X';
                        foreach ($matriz as $key => $value) {
                            if ($punto != $value->reportevibracionevaluacion_punto) {
                                $punto = $value->reportevibracionevaluacion_punto;
                                $numero_registro += 1;
                            }


                            $dato["tabla_matriz"] .=    '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $value->catsubdireccion_nombre . '</td>
                                                            <td>' . $value->gerencia_activo . '</td>
                                                            <td>' . $value->reportearea_instalacion . '</td>
                                                            <td>' . $value->reportearea_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                            <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                            <td>' . $value->reportecategoria_nombre . '</td>
                                                            <td>N/A</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_az3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ax3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay2 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_ay3 . '</td>
                                                            <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                        </tr>';
                        }


                        $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 4, 0, 5, 6, 7, 8);
                    }
                } else {
                    $matriz = DB::select('SELECT
                                                reportevibracionevaluacion.proyecto_id,
                                                reportevibracionevaluacion.id,
                                                IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                (
                                                    CASE
                                                        WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                        ELSE catgerencia_nombre
                                                    END
                                                ) AS gerencia_activo,
                                                reportearea.reportearea_instalacion,
                                                reportevibracionevaluacion.reportearea_id,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                reportearea.reportevibracionarea_porcientooperacion,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportevibracionevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id, 
                                                        -- reporteareacategoria.reportecategoria_id, 
                                                        -- reporteareacategoria.reporteareacategoria_total, 
                                                        -- reporteareacategoria.reporteareacategoria_actividades,
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                    LIMIT 1
                                                ), 1) AS geh,
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                reportearea.reportearea_tipoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                IFNULL((
                                                    IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                ), "N/A") AS resultado
                                            FROM
                                                reportevibracionevaluacion
                                                LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                            WHERE
                                                reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


                    $numero_registro = 0;
                    $punto = 'X';
                    foreach ($matriz as $key => $value) {
                        $numero_registro += 1;


                        $dato["tabla_matriz"] .=    '<tr>
                                                        <td>' . $numero_registro . '</td>
                                                        <td>' . $value->catsubdireccion_nombre . '</td>
                                                        <td>' . $value->gerencia_activo . '</td>
                                                        <td>' . $value->reportearea_instalacion . '</td>
                                                        <td>' . $value->reportearea_nombre . '</td>
                                                        <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                        <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                        <td>' . $value->reportecategoria_nombre . '</td>
                                                        <td>N/A</td>
                                                        <td>' . $value->reportevibracionevaluacion_promedio . ' / ' . $value->reportevibracionevaluacion_valormaximo . '</td>
                                                    </tr>';
                    }


                    $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 5, 6, 7, 4, 0);
                }
            } else if (count($evaluacion) > 0) {
                $categorias_evaluadas = DB::select('SELECT
                                                        reportevibracionevaluacion.proyecto_id,
                                                        reportevibracionevaluacion.reportecategoria_id,
                                                        reportecategoria.reportecategoria_orden,
                                                        reportecategoria.reportecategoria_nombre,
                                                        COUNT( reportevibracionevaluacion.reportevibracionevaluacion_punto ) AS total_mediciones 
                                                    FROM
                                                        reportevibracionevaluacion
                                                        LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id 
                                                    WHERE
                                                        reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    GROUP BY
                                                        reportevibracionevaluacion.proyecto_id,
                                                        reportevibracionevaluacion.reportecategoria_id,
                                                        reportecategoria.reportecategoria_orden,
                                                        reportecategoria.reportecategoria_nombre
                                                    ORDER BY
                                                        reportecategoria.reportecategoria_orden ASC');


                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {

                    $numero_registro = 0;
                    foreach ($categorias_evaluadas as $key1 => $categoria) {
                        $matriz = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.id,
                                                    IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                    IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                    IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                    IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                    (
                                                        CASE
                                                            WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                            ELSE catgerencia_nombre
                                                        END
                                                    ) AS gerencia_activo,
                                                    reportearea.reportearea_instalacion,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                    reportearea.reportevibracionarea_porcientooperacion,
                                                    (
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id,
                                                            SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        LIMIT 1
                                                    ) AS personas_area,
                                                    reportevibracionevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                    IFNULL((
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id, 
                                                            -- reporteareacategoria.reportecategoria_id, 
                                                            -- reporteareacategoria.reporteareacategoria_total, 
                                                            -- reporteareacategoria.reporteareacategoria_actividades,
                                                            reporteareacategoria.reporteareacategoria_geh
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                            AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                        LIMIT 1
                                                    ), 1) AS geh,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                    reportearea.reportearea_tipoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1, "-") AS reportevibracionevaluaciondatos_az1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2, "-") AS reportevibracionevaluaciondatos_az2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3, "-") AS reportevibracionevaluaciondatos_az3,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1, "-") AS reportevibracionevaluaciondatos_ax1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2, "-") AS reportevibracionevaluaciondatos_ax2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3, "-") AS reportevibracionevaluaciondatos_ax3,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1, "-") AS reportevibracionevaluaciondatos_ay1,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2, "-") AS reportevibracionevaluaciondatos_ay2,
                                                    IFNULL(reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3, "-") AS reportevibracionevaluaciondatos_ay3,
                                                    reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite,

                                                    IF(reportevibracionevaluaciondatos_az1 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az1_color,
                                                    IF(reportevibracionevaluaciondatos_az2 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az2_color,
                                                    IF(reportevibracionevaluaciondatos_az3 > (reportevibracionevaluaciondatos_azlimite+0), "#FF0000", "inherit") AS az3_color,
                                                    
                                                    IF(reportevibracionevaluaciondatos_ax1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax1_color,
                                                    IF(reportevibracionevaluaciondatos_ax2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax2_color,
                                                    IF(reportevibracionevaluaciondatos_ax3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ax3_color,
                                                    
                                                    IF(reportevibracionevaluaciondatos_ay1 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay1_color,
                                                    IF(reportevibracionevaluaciondatos_ay2 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay2_color,
                                                    IF(reportevibracionevaluaciondatos_ay3 > (reportevibracionevaluaciondatos_axylimite+0), "#FF0000", "inherit") AS ay3_color,
                                                    (
                                                        SELECT
                                                            -- DATOS.reportevibracionevaluacion_id,
                                                            -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                            SUM(
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            ) AS total_fueranorma
                                                            -- IF(SUM(
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            -- ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                            -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                            -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                        FROM
                                                            reportevibracionevaluaciondatos AS DATOS
                                                        WHERE
                                                            DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                        GROUP BY
                                                            DATOS.reportevibracionevaluacion_id
                                                        LIMIT 1
                                                    ) AS mediciones_fueranorma,
                                                    (
                                                        SELECT
                                                            -- DATOS.reportevibracionevaluacion_id,
                                                            -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                            -- SUM(
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            -- ) AS total_fueranorma,
                                                            IF(SUM(
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            ) > 0, "Fuera de norma", "Dentro de norma") AS resultado
                                                            -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                            -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                        FROM
                                                            reportevibracionevaluaciondatos AS DATOS
                                                        WHERE
                                                            DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                        GROUP BY
                                                            DATOS.reportevibracionevaluacion_id
                                                        LIMIT 1
                                                    ) AS resultado
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                    RIGHT JOIN reportevibracionevaluaciondatos ON reportevibracionevaluacion.id = reportevibracionevaluaciondatos.reportevibracionevaluacion_id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reportevibracionevaluacion.reportecategoria_id = ' . $categoria->reportecategoria_id . ' 
                                                ORDER BY
                                                    mediciones_fueranorma DESC,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                    (reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_frecuencia+0) ASC
                                                LIMIT 20');


                        $numero_registro += 1;
                        foreach ($matriz as $key => $value) {
                            if (($evaluacion[0]->reportevibracionevaluacion_numeromediciones + 0) == 1) {
                                $dato["tabla_matriz"] .=    '<tr>
                                                                <td>' . $numero_registro . '</td>
                                                                <td>' . $value->catsubdireccion_nombre . '</td>
                                                                <td>' . $value->gerencia_activo . '</td>
                                                                <td>' . $value->reportearea_instalacion . '</td>
                                                                <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                                <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                                <td>N/A</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                            </tr>';
                            } else {
                                $dato["tabla_matriz"] .=    '<tr>
                                                                <td>' . $numero_registro . '</td>
                                                                <td>' . $value->catsubdireccion_nombre . '</td>
                                                                <td>' . $value->gerencia_activo . '</td>
                                                                <td>' . $value->reportearea_instalacion . '</td>
                                                                <td>' . $value->reportevibracionevaluacion_nombre . '</td>
                                                                <td>' . $value->reportevibracionevaluacion_ficha . '</td>
                                                                <td>' . $value->reportecategoria_nombre . '</td>
                                                                <td>N/A</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_frecuencia . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_az1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_az2 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_az3 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_azlimite . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ax1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ax2 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ax3 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ay1 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ay2 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_ay3 . '</td>
                                                                <td>' . $value->reportevibracionevaluaciondatos_axylimite . '</td>
                                                            </tr>';
                            }
                        }
                    }


                    $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 0, 4, 5, 6, 7);
                } else {
                    $numero_registro = 0;
                    foreach ($categorias_evaluadas as $key1 => $categoria) {
                        $matriz = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.id,
                                                    IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                                    IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                                    IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                                    IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                                    (
                                                        CASE
                                                            WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) != "" THEN catactivo_nombre
                                                            ELSE catgerencia_nombre
                                                        END
                                                    ) AS gerencia_activo,
                                                    reportearea.reportearea_instalacion,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                    reportearea.reportevibracionarea_porcientooperacion,
                                                    (
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id,
                                                            SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                        LIMIT 1
                                                    ) AS personas_area,
                                                    reportevibracionevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                    IFNULL((
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id, 
                                                            -- reporteareacategoria.reportecategoria_id, 
                                                            -- reporteareacategoria.reporteareacategoria_total, 
                                                            -- reporteareacategoria.reporteareacategoria_actividades,
                                                            reporteareacategoria.reporteareacategoria_geh
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportevibracionevaluacion.reportearea_id
                                                            AND reporteareacategoria.reportecategoria_id = reportevibracionevaluacion.reportecategoria_id
                                                        LIMIT 1
                                                    ), 1) AS geh,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                    reportearea.reportearea_tipoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                    IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_promedio, "-") AS reportevibracionevaluacion_promedio,
                                                    IFNULL(reportevibracionevaluacion.reportevibracionevaluacion_valormaximo, "-") AS reportevibracionevaluacion_valormaximo,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                    IFNULL((
                                                        IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), "Fuera de norma", "Dentro de norma")
                                                    ), "N/A") AS resultado
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN proyecto ON reportevibracionevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reportevibracionevaluacion.reportecategoria_id = ' . $categoria->reportecategoria_id . ' 
                                                ORDER BY
                                                    (reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) DESC
                                                LIMIT 1');


                        if (count($matriz) > 0) {
                            $numero_registro += 1;
                            $dato["tabla_matriz"] .=    '<tr>
                                                            <td>' . $numero_registro . '</td>
                                                            <td>' . $matriz[0]->catsubdireccion_nombre . '</td>
                                                            <td>' . $matriz[0]->gerencia_activo . '</td>
                                                            <td>' . $matriz[0]->reportearea_instalacion . '</td>
                                                            <td>' . $matriz[0]->reportevibracionevaluacion_nombre . '</td>
                                                            <td>' . $matriz[0]->reportevibracionevaluacion_ficha . '</td>
                                                            <td>' . $matriz[0]->reportecategoria_nombre . '</td>
                                                            <td>N/A</td>
                                                            <td>' . $matriz[0]->reportevibracionevaluacion_promedio . ' / ' . $matriz[0]->reportevibracionevaluacion_valormaximo . '</td>
                                                        </tr>';
                        }
                    }


                    $dato['tabla_matriz_rowsGroup'] = array(1, 2, 3, 4, 5, 6, 0, 7);
                }
            } else {
                $dato["tabla_matriz"] .= '';
            }

            $dato["tabla_matriz"] .= '</tbody>';


            //======================================================


            // respuesta
            $dato["total_registros"] = count($evaluacion);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["tabla_matriz"] = null;
            $dato["total_registros"] = 0;
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
    public function reportevibraciondashboard($proyecto_id)
    {
        try {
            $dato["dashboard_puntos"] = ' 0';
            $dato["dashboard_cumplimiento"] = '0%';
            $dato["dashboard_recomendaciones"] = ' 0';
            $dato["dashboard_distribucionpuntos"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';
            $dato["dashboard_categoriasevaluadas"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';


            // CONFIGURACION EVALUACION
            //======================================================


            $evaluacion = DB::select('SELECT
                                            reportevibracionevaluacion.proyecto_id,
                                            reportevibracionevaluacion.id,
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                            reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                            reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                            reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                            reportevibracionevaluacion.reportevibracionevaluacion_fecha 
                                        FROM
                                            reportevibracionevaluacion
                                        WHERE
                                            reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportevibracionevaluacion.reportevibracionevaluacion_punto ASC');


            //=====================================
            // TOTAL PUNTOS Y CUMPLIMIENTO NORMATIVO


            $cumplimiento = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            COUNT(TABLA.reportevibracionevaluacion_punto) AS total_puntos,
                                            ROUND((ROUND((SUM(TABLA.cumplimiento_evaluacionsimple) / COUNT(TABLA.reportevibracionevaluacion_punto)), 3) * 100), 0) AS cumplimiento_evaluacionsimple,
                                            ROUND((ROUND((SUM(TABLA.cumplimiento_evaluacioncompleta) / COUNT(TABLA.reportevibracionevaluacion_punto)), 3) * 100), 0) AS cumplimiento_evaluacioncompleta
                                        FROM
                                            (
                                                SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.id,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportearea.reportevibracionarea_porcientooperacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_puntoevaluacion,
                                                    reportevibracionevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_ficha,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                    reportearea.reportearea_tipoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tipoevaluacion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_tiempoexposicion,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_numeromediciones,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_fecha,
                                                    (
                                                        IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), 0, 1)
                                                    ) AS cumplimiento_evaluacionsimple,
                                                    (
                                                        SELECT
                                                            -- DATOS.reportevibracionevaluacion_id,
                                                            -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                            -- SUM(
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            -- ) AS total_fueranorma,
                                                            IF(SUM(
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                    IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                            ) > 0, 0, 1) AS resultado
                                                            -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                            -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                        FROM
                                                            reportevibracionevaluaciondatos AS DATOS
                                                        WHERE
                                                            DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                        GROUP BY
                                                            DATOS.reportevibracionevaluacion_id
                                                        LIMIT 1
                                                    ) AS cumplimiento_evaluacioncompleta
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                ORDER BY
                                                    reportevibracionevaluacion.reportevibracionevaluacion_punto ASC,
                                                    reportearea.reportearea_orden ASC,
                                                    reportearea.reportearea_nombre ASC,
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC,
                                                    reportevibracionevaluacion.reportevibracionevaluacion_nombre ASC
                                            ) AS TABLA
                                        GROUP BY
                                            TABLA.proyecto_id');


            if (count($cumplimiento) > 0) {
                $dato["dashboard_puntos"] = ' ' . $cumplimiento[0]->total_puntos;

                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $dato["dashboard_cumplimiento"] = $cumplimiento[0]->cumplimiento_evaluacioncompleta . '%';
                } else {
                    $dato["dashboard_cumplimiento"] = $cumplimiento[0]->cumplimiento_evaluacionsimple . '%';
                }
            }


            //=====================================
            // RECOMENDACIONES EMITIDAS


            $recomendaciones = DB::select('SELECT
                                                -- reporterecomendaciones.proyecto_id,
                                                -- reporterecomendaciones.registro_id,
                                                -- reporterecomendaciones.agente_id,
                                                -- reporterecomendaciones.agente_nombre,
                                                -- reporterecomendaciones.catactivo_id,
                                                -- reporterecomendaciones.reporterecomendacionescatalogo_id,
                                                -- reporterecomendaciones.reporterecomendaciones_tipo,
                                                -- reporterecomendaciones.reporterecomendaciones_descripcion,
                                                COUNT(reporterecomendaciones.id) AS totalrecomendaciones
                                            FROM
                                                reporterecomendaciones 
                                            WHERE
                                                reporterecomendaciones.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporterecomendaciones.agente_nombre LIKE "%Vibración%"');


            if (count($recomendaciones) > 0) {
                $dato['dashboard_recomendaciones'] = $recomendaciones[0]->totalrecomendaciones;
            }


            //=====================================
            // DISTRIBUCION DE PUNTOS DE EVALUACION


            $total_instalaciones = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    -- reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion 
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                GROUP BY
                                                    reportevibracionevaluacion.proyecto_id,
                                                    -- reportevibracionevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion');


            $distribucion_puntos = DB::select('SELECT
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    -- reportearea.reportearea_orden,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_nombre,
                                                    COUNT(reportevibracionevaluacion.reportevibracionevaluacion_punto) AS total_puntos
                                                FROM
                                                    reportevibracionevaluacion
                                                    LEFT JOIN reportearea ON reportevibracionevaluacion.reportearea_id = reportearea.id
                                                WHERE
                                                    reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                GROUP BY
                                                    reportevibracionevaluacion.proyecto_id,
                                                    reportevibracionevaluacion.reportearea_id,
                                                    -- reportearea.reportearea_orden,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_nombre
                                                ORDER BY
                                                    reportearea.reportearea_orden ASC,
                                                    reportearea.reportearea_nombre ASC');


            $col = 'col-12';
            $align = 'center';
            $size = '0.85vw!important';
            if ((count($distribucion_puntos) + count($total_instalaciones)) > 13) {
                $col = 'col-6';
                $align = 'left';
                $size = '0.7vw!important';
            }


            $instalacion = 'XXXXX';
            foreach ($distribucion_puntos as $key => $value) {
                if (($key + 0) == 0) {
                    $dato["dashboard_distribucionpuntos"] = '';
                }


                if (count($total_instalaciones) > 1 && $instalacion != $value->reportearea_instalacion) {
                    if (($key + 0) > 0) {
                        $dato["dashboard_distribucionpuntos"] .= '<div class="col-12" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: center; color: #0BACDB;">&nbsp;</div>';
                    }


                    $dato["dashboard_distribucionpuntos"] .= '<div class="col-12" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: center; color: #0BACDB;"><b>' . $value->reportearea_instalacion . '</b></div>';
                    $instalacion = $value->reportearea_instalacion;
                }


                $dato["dashboard_distribucionpuntos"] .= '<div class="' . $col . '" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . ';">● <b style="color: #333333;">' . $value->total_puntos . ' puntos</b> - ' . $value->reportearea_nombre . '</div>';


                if (($key + 1) == count($distribucion_puntos)) {
                    $dato["dashboard_distribucionpuntos"] .= '<div class="col-6" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . ';">&nbsp;</div>';
                }
            }


            //=====================================
            // CATEGORIAS EVALUADAS


            $categorias_evaluadas = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    reportecategoria_id,
                                                    reportecategoria_orden,
                                                    reportecategoria_nombre,
                                                    IF(SUM(incumplimiento_evaluacionsimple) > 0, "#FF0000", "inherit") AS color_evaluacionsimple,
                                                    IF(SUM(incumplimiento_evaluacioncompleta) > 0, "#FF0000", "inherit") AS color_evaluacioncompleta
                                                FROM
                                                    (
                                                        SELECT
                                                            reportevibracionevaluacion.proyecto_id,
                                                            reportevibracionevaluacion.reportecategoria_id,
                                                            reportecategoria.reportecategoria_orden,
                                                            reportecategoria.reportecategoria_nombre,
                                                            reportevibracionevaluacion.reportevibracionevaluacion_punto,
                                                            reportevibracionevaluacion.reportevibracionevaluacion_promedio,
                                                            reportevibracionevaluacion.reportevibracionevaluacion_valormaximo,
                                                            (
                                                                IF((reportevibracionevaluacion.reportevibracionevaluacion_promedio+0) > (reportevibracionevaluacion.reportevibracionevaluacion_valormaximo+0), 1, 0)
                                                            ) AS incumplimiento_evaluacionsimple,
                                                            (
                                                                SELECT
                                                                    -- DATOS.reportevibracionevaluacion_id,
                                                                    -- DATOS.reportevibracionevaluaciondatos_frecuencia AS frecuencia,
                                                                    -- SUM(
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                            -- IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                                    -- ) AS total_fueranorma,
                                                                    IF(SUM(
                                                                        IF(DATOS.reportevibracionevaluaciondatos_az1 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_az2 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_az3 > (DATOS.reportevibracionevaluaciondatos_azlimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ax1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ax2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ax3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ay1 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ay2 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0) +
                                                                        IF(DATOS.reportevibracionevaluaciondatos_ay3 > (DATOS.reportevibracionevaluaciondatos_axylimite+0), 1, 0)
                                                                    ) > 0, 1, 0) AS resultado
                                                                    -- DATOS.reportevibracionevaluaciondatos_azlimite AS azlimite,
                                                                    -- DATOS.reportevibracionevaluaciondatos_axylimite AS axylimite 
                                                                FROM
                                                                    reportevibracionevaluaciondatos AS DATOS
                                                                WHERE
                                                                    DATOS.reportevibracionevaluacion_id = reportevibracionevaluacion.id
                                                                GROUP BY
                                                                    DATOS.reportevibracionevaluacion_id
                                                                LIMIT 1
                                                            ) AS incumplimiento_evaluacioncompleta
                                                        FROM
                                                            reportevibracionevaluacion
                                                            LEFT JOIN reportecategoria ON reportevibracionevaluacion.reportecategoria_id = reportecategoria.id
                                                        WHERE
                                                            reportevibracionevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    ) AS TABLA
                                                GROUP BY
                                                    TABLA.proyecto_id,
                                                    reportecategoria_id,
                                                    reportecategoria_orden,
                                                    reportecategoria_nombre
                                                ORDER BY
                                                    reportecategoria_orden ASC,
                                                    reportecategoria_nombre ASC');


            $col = 'col-12';
            $align = 'center';
            $size = '0.85vw!important';
            if (count($categorias_evaluadas) > 15) {
                $col = 'col-6';
                $align = 'left';
                $size = '0.7vw!important';
            }


            foreach ($categorias_evaluadas as $key => $value) {
                if (($key + 0) == 0) {
                    $dato["dashboard_categoriasevaluadas"] = '';
                }


                if (($evaluacion[0]->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    $dato["dashboard_categoriasevaluadas"] .= '<div class="' . $col . '" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . '; color: ' . $value->color_evaluacioncompleta . '">● ' . $value->reportecategoria_nombre . '</div>';
                } else {
                    $dato["dashboard_categoriasevaluadas"] .= '<div class="' . $col . '" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . '; color: ' . $value->color_evaluacionsimple . '">● ' . $value->reportecategoria_nombre . '</div>';
                }


                if (($key + 1) == count($categorias_evaluadas)) {
                    $dato["dashboard_categoriasevaluadas"] .= '<div class="col-6" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . ';">&nbsp;</div>';
                }
            }


            //=====================================


            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["dashboard_puntos"] = ' 0';
            $dato["dashboard_cumplimiento"] = '0%';
            $dato["dashboard_recomendaciones"] = ' 0';
            $dato["dashboard_distribucionpuntos"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';
            $dato["dashboard_categoriasevaluadas"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionrecomendacionestabla($proyecto_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);


            $tabla = DB::select('SELECT
                                        TABLA.id,
                                        TABLA.agente_id,
                                        TABLA.agente_nombre,
                                        TABLA.recomendaciones_tipo,
                                        TABLA.recomendaciones_descripcion,
                                        TABLA.catalogo_id,
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
                                                    0 AS catalogo_id,
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
                                                    reporterecomendaciones.catalogo_id,
                                                    "checked" AS checked
                                                FROM
                                                    reporterecomendaciones
                                                WHERE
                                                    reporterecomendaciones.proyecto_id = ' . $proyecto_id . '
                                                    AND reporterecomendaciones.agente_nombre = "' . $agente_nombre . '"
                                                    AND reporterecomendaciones.reporterecomendacionescatalogo_id = 0
                                                ORDER BY
                                                    reporterecomendaciones.id ASC
                                            )
                                        ) AS TABLA');


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
                                        <button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-2x"></i></button>';

                    $preventiva = "";
                    $correctiva = "";
                    if ($value->recomendaciones_tipo == "Preventiva") {
                        $preventiva = "selected";
                    } else {
                        $correctiva = "selected";
                    }


                    $value->descripcion = '<div class="row">
                                                <div class="col-12">
                                                    <label>Tipo recomendación</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>
                                                        <option value=""></option>
                                                        <option value="Preventiva" ' . $preventiva . '>Preventiva</option>
                                                        <option value="Correctiva" ' . $correctiva . '>Correctiva</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <br>
                                                    <label>Descripción</label>
                                                    <textarea  class="form-control" rows="5" name="recomendacionadicional_descripcion[]" required>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion) . '</textarea>
                                                </div>
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
     * @param int $reporteregistro_id
     * @param int $responsabledoc_tipo
     * @param int $responsabledoc_opcion
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reportevibracionModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportevibracion_responsable1documento);
            } else {
                return Storage::download($reporte->reportevibracion_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportevibracion_responsable2documento);
            } else {
                return Storage::download($reporte->reportevibracion_responsable2documento);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionplanostabla($proyecto_id, $agente_nombre)
    {
        try {
            $planos = DB::select('SELECT
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
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%' . $agente_nombre . '%" 
                                                AND reporteplanoscarpetas.reporteplanoscarpetas_nombre = proyectoevidenciaplano.proyectoevidenciaplano_carpeta
                                            LIMIT 1
                                        ), "") AS checked
                                    FROM
                                        proyectoevidenciaplano
                                    WHERE
                                        proyectoevidenciaplano.proyecto_id = ' . $proyecto_id . ' 
                                        AND proyectoevidenciaplano.agente_nombre LIKE "%' . $agente_nombre . '%" 
                                    GROUP BY
                                        proyectoevidenciaplano.proyecto_id,
                                        proyectoevidenciaplano.agente_id,
                                        proyectoevidenciaplano.agente_nombre,
                                        proyectoevidenciaplano.proyectoevidenciaplano_carpeta
                                    ORDER BY
                                        proyectoevidenciaplano.agente_nombre ASC,
                                        proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC');


            $total_activos = 0;
            $numero_registro = 0;
            foreach ($planos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="planoscarpeta_checkbox" name="planoscarpeta_checkbox[]" value="' . $value->proyectoevidenciaplano_carpeta . '" ' . $value->checked . '>
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
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionequipoutilizadotabla($proyecto_id, $agente_nombre)
    {
        try {
            $proveedor = DB::select('SELECT
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id
                                            -- proyectoproveedores.proyectoproveedores_tipoadicional,
                                            -- proyectoproveedores.catprueba_id AS agente_id,
                                            -- proyectoproveedores.proyectoproveedores_agente
                                        FROM
                                            proyectoproveedores
                                        WHERE
                                            proyectoproveedores.proyecto_id = ' . $proyecto_id . ' 
                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                            AND proyectoproveedores.catprueba_id = 2 -- Vibración ------------------------------
                                        GROUP BY
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id');


            $where_condicion = '';
            if (count($proveedor) > 0) {
                $lista = '';


                foreach ($proveedor as $key => $value) {
                    if (($key + 0) == 0) {
                        $lista .= $value->proveedor_id;
                    } else {
                        $lista .= ', ' . $value->proveedor_id;
                    }
                }


                // $where_condicion = ' AND proyectoequiposactual.proveedor_id IN ('.$lista.')';
            }


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
                                        
                                        IFNULL((
                                            SELECT
                                                IF(IFNULL(reporteequiposutilizados.equipo_id, ""), "checked" , "")
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
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


            if (count($equipos) == 0) {
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
                                      
                                        IFNULL((
                                            SELECT
                                                IF(IFNULL(reporteequiposutilizados.equipo_id, ""), "checked" , "")
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
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
                                    ORDER BY
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie');
            }


            $total_activos = 0;
            $numero_registro = 0;
            foreach ($equipos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="equipoutilizado_checkbox" name="equipoutilizado_checkbox[]" value="' . $value->equipo_id . '" ' . $value->checked . ' onchange="activa_checkboxcarta(this, ' . $value->equipo_id . ');";>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                $value->equipo = '<span class="' . $value->vigencia_color . '">' . $value->equipo_Descripcion . '</span><br><small class="' . $value->vigencia_color . '">' . $value->proveedor_NombreComercial . '</small>';


                $value->marca_modelo_serie = '<span class="' . $value->vigencia_color . '">' . $value->equipo_Marca . '<br>' . $value->equipo_Modelo . '<br>' . $value->equipo_Serie . '</span>';


                $value->vigencia = '<span class="' . $value->vigencia_color . '">' . $value->vigencia_texto . '</span>';







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
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionanexosresultadostabla($proyecto_id, $agente_nombre)
    {
        try {
            $anexos = DB::select('SELECT
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
                                                AND reporteanexos.agente_nombre LIKE "%' . $agente_nombre . '%" 
                                                AND reporteanexos.reporteanexos_tipo = 1
                                                AND reporteanexos.reporteanexos_rutaanexo = proyectoevidenciadocumento.proyectoevidenciadocumento_archivo
                                        ), "") AS checked 
                                    FROM
                                        proyectoevidenciadocumento
                                    WHERE
                                        proyectoevidenciadocumento.proyecto_id = ' . $proyecto_id . ' 
                                        AND proyectoevidenciadocumento.agente_nombre LIKE "%' . $agente_nombre . '%"
                                    ORDER BY
                                        proyectoevidenciadocumento.agente_nombre ASC,
                                        proyectoevidenciadocumento.proyectoevidenciadocumento_nombre ASC');

            $total_activos = 0;
            $numero_registro = 0;
            foreach ($anexos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="anexoresultado_nombre_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_nombre . '">
                                            <input type="hidden" class="form-control" name="anexoresultado_archivo_' . $value->id . '" value="' . $value->proyectoevidenciadocumento_archivo . '">
                                            <input type="checkbox" class="anexoresultado_checkbox" name="anexoresultado_checkbox[]" value="' . $value->id . '" ' . $value->checked . '>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';

                if ($value->proyectoevidenciadocumento_extension == '.pdf' || $value->proyectoevidenciadocumento_extension == '.PDF') {
                    $value->documento = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar PDF"><i class="fa fa-file-pdf-o fa-2x"></i></button>';
                } else {
                    $value->documento = '<button type="button" class="btn btn-success waves-effect btn-circle" data-toggle="tooltip" title="Descargar archivo"><i class="fa fa-download fa-2x"></i></button>';
                }

                // VERIFICAR SI HAY DOCUMENTOS SELECCIONADOS
                if ($value->checked) {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $anexos;
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
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionanexosacreditacionestabla($proyecto_id, $agente_nombre)
    {
        try {
            $acreditaciones = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.proveedor_id,
                                                TABLA.proveedor_NombreComercial,
                                                acreditacion.id,
                                                acreditacion.acreditacion_Entidad,
                                                acreditacion.acreditacion_Numero,
                                                IF(acreditacion.acreditacion_Tipo = 1, "Acreditación", "Aprobación") AS acreditacion_Tipo,
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
                                                        reporteanexos.proyecto_id = TABLA.proyecto_id
                                                        AND reporteanexos.agente_nombre = "' . $agente_nombre . '" 
                                                        AND reporteanexos.reporteanexos_tipo = 2
                                                        AND reporteanexos.reporteanexos_rutaanexo = acreditacion.acreditacion_SoportePDF
                                                    LIMIT 1
                                                ), "") AS checked 
                                            FROM
                                                (
                                                    SELECT
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proveedor.proveedor_NombreComercial
                                                        -- proyectoproveedores.catprueba_id,
                                                        -- proyectoproveedores.proyectoproveedores_agente 
                                                    FROM
                                                        proyectoproveedores
                                                        LEFT JOIN proveedor ON proyectoproveedores.proveedor_id = proveedor.id
                                                    WHERE
                                                        proyectoproveedores.proyecto_id = ' . $proyecto_id . ' 
                                                        AND proyectoproveedores.catprueba_id = 2 
                                                    GROUP BY
                                                        proyectoproveedores.proyecto_id,
                                                        proyectoproveedores.proveedor_id,
                                                        proveedor.proveedor_NombreComercial
                                                ) AS TABLA
                                                LEFT JOIN acreditacion ON TABLA.proveedor_id = acreditacion.proveedor_id
                                                LEFT JOIN cat_area ON acreditacion.cat_area_id = cat_area.id
                                            ORDER BY
                                                TABLA.proveedor_NombreComercial ASC,
                                                acreditacion.acreditacion_Entidad ASC');


            $total_activos = 0;
            $numero_registro = 0;
            foreach ($acreditaciones as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="anexoacreditacion_nombre_' . $value->id . '" value="' . $value->acreditacion_Entidad . ' ' . $value->acreditacion_Numero . '">
                                            <input type="hidden" class="form-control" name="anexoacreditacion_archivo_' . $value->id . '" value="' . $value->acreditacion_SoportePDF . '">
                                            <input type="checkbox" class="anexoacreditacion_checkbox" name="anexoacreditacion_checkbox[]" value="' . $value->id . '" ' . $value->checked . '>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                $value->tipo = '<span class="' . $value->vigencia_color . '">' . $value->acreditacion_Tipo . '</span>';
                $value->entidad = '<span class="' . $value->vigencia_color . '">' . $value->acreditacion_Entidad . '</span>';
                $value->numero = '<span class="' . $value->vigencia_color . '">' . $value->acreditacion_Numero . '</span>';
                $value->area = '<span class="' . $value->vigencia_color . '">' . $value->vigencia_color . '</span>';
                $value->vigencia = '<span class="' . $value->vigencia_color . '">' . $value->vigencia_texto . '</span>';
                $value->certificado = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar certificado"><i class="fa fa-file-pdf-o fa-2x"></i></button>';

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
    public function reportevibracionrevisionestabla($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 2
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
                    $value->boton_descargar = '<button type="button" class="btn btn-success waves-effect btn-circle botondescarga" id="botondescarga_' . $key . '"><i class="fa fa-download fa-2x"></i></button>';
                } else {
                    $value->boton_descargar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="Para descargar esta revisión del informe, primero debe estar concluido ó cancelado."><i class="fa fa-ban fa-2x"></i></button>';
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
     * Display the specified resource.
     *
     * @param  int $reporte_id
     * @return \Illuminate\Http\Response
     */
    public function reportevibracionrevisionconcluir($reporte_id)
    {
        try {
            // $reporte  = reporteaireModel::findOrFail($reporte_id);
            $revision  = reporterevisionesModel::findOrFail($reporte_id);


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


            /// INSERTAR POR MEDIO DE EXCEL LO DATOS DEL RESULTADO  


            // INSERTAR PUNTOS POR MEDIO DE UN EXCEL
            if ($request->opcion == 1000) {



                $proyecto_id = $request['proyecto_id'];



                // Empezamos a guardar los puntos de iluminacion
                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelResultado')) {

                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelResultado');

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

                         return response()->json(['msj' => $datosGenerales, "code" => 500]);


                        //Puntos totales
                        $totalPuntos = count($datosGenerales);
                        $puntosInsertados = 0;

                        //================================= Funciones de limpieaza de datos =================================





                        //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS CATEGORIAS CON SU ID
                        $IdCategorias = [];
                        $caategorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)->get();
                        foreach ($caategorias as $cat) {
                            $clave = $cat->reportecategoria_nombre;
                            $IdCategorias[$clave] = $cat->id;
                        }


                        //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS AREAS CON SU ID
                        $IdAreas = [];
                        $areas = reporteareaModel::where('proyecto_id', $proyecto_id)->get();
                        foreach ($areas as $area) {
                            $clave = $area->reportearea_nombre;
                            $IdAreas[$clave] = $area->id;
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


                        function tipoevaluacion($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == 'NOM-024' || $usoModificado == 'NOM-024-STPS-2001'  || $usoModificado == 'LÍMITES POR NOM-024-STPS-2001' || $usoModificado == 'LIMITES POR NOM-024-STPS-2001') {
                                return 1;
                            } elseif ($usoModificado == 'LÍMITES POR INTERPOLACIÓN' || $usoModificado == 'INTERPOLACIÓN' ||  $usoModificado == 'LIMITES POR INTERPOLACION' || $usoModificado == 'INTERPOLACION') {
                                return 2;
                            } elseif ($usoModificado === 'MÉTODO ISO'  || $usoModificado == 'ISO' || $usoModificado == 'METODO ISO') {
                                return 3;
                            } else {
                                return 0;
                            }
                        }



                        function tiempoexposicion($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == '1 MINUTOS' || $usoModificado == '1 MIN' || $usoModificado == '1 MINUTO') {
                                return "1 min";
                            } elseif ($usoModificado == '16 MINUTOS' || $usoModificado == '16 MIN' || $usoModificado == '16 MINUTO') {
                                return "16 min";
                            } elseif ($usoModificado === '25 MINUTOS'  || $usoModificado == '25 MIN' || $usoModificado == '25 MINUTO') {
                                return "25 min";
                            } elseif ($usoModificado === '1 HORA'  || $usoModificado == '1 H' || $usoModificado == '1 HORAS') {
                                return "1 h";
                            } elseif ($usoModificado === '2.5 HORA'  || $usoModificado == '2.5 H' || $usoModificado == '2.5 HORAS') {
                                return "2.5 h";
                            } elseif ($usoModificado === '4 HORA'  || $usoModificado == '4 H' || $usoModificado == '4 HORAS') {
                                return "4 h";
                            } elseif ($usoModificado === '8 HORA'  || $usoModificado == '8 H' || $usoModificado == '8 HORAS') {
                                return "8 h";
                            } elseif ($usoModificado === '16 HORA'  || $usoModificado == '16 H' || $usoModificado == '16 HORAS') {
                                return "16 h";
                            } elseif ($usoModificado === '24 HORA'  || $usoModificado == '24 H' || $usoModificado == '24 HORAS') {
                                return "24 h";
                            } else {
                                return 0;
                            }
                        }


                        function medicioneje($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == '1') {
                                return 1;
                            } elseif ($usoModificado == '3') {
                                return 3;
                            } else {
                                return 0;
                            }
                        }


                        function obtenerLimites($tiempo_exposicion)
                        {
                            switch ($tiempo_exposicion) {
                                case '1 min':
                                    return [
                                        'az' => ['5.60', '5.00', '4.50', '4.00', '3.55', '3.15', '2.80', '2.80', '2.80', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.00', '18.0', '22.4', '28.0'],
                                        'axy' => ['2.0', '2.0', '2.0', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '16.0', '20.0', '25.0', '31.5', '40.0', '50.0', '63.0', '80.0']
                                    ];
                                case '16 min':
                                    return [
                                        'az' => ['4.25', '3.75', '3.35', '3.00', '2.65', '2.35', '2.12', '2.12', '2.12', '2.12', '2.65', '3.35', '4.25', '5.30', '6.70', '8.50', '10.6', '13.2', '17.0', '21.2'],
                                        'axy' => ['1.50', '1.50', '1.50', '1.50', '1.9', '2.36', '3.0', '3.75', '4.75', '6.0', '7.5', '9.5', '11.8', '15.0', '19.0', '23.6', '30.0', '37.5', '45.7', '60.0']
                                    ];
                                case '25 min':
                                    return [
                                        'az' => ['3.55', '3.15', '2.80', '2.50', '2.24', '2.00', '1.80', '1.80', '1.80', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.0', '18.0'],
                                        'axy' => ['1.25', '1.25', '1.25', '1.25', '1.6', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '15.0', '20.0', '25.0', '3.5', '40.0', '50.0']
                                    ];
                                case '1 h':
                                    return [
                                        'az' => ['2.36', '2.12', '1.90', '1.70', '1.50', '1.32', '1.18', '1.18', '1.18', '1.18', '1.50', '1.90', '2.36', '3.00', '3.75', '4.75', '6.00', '7.50', '9.50', '11.8'],
                                        'axy' => ['0.85', '0.85', '0.85', '0.85', '1.06', '1.32', '1.70', '2.12', '2.65', '3.35', '4.25', '5.30', '6.70', '8.5', '10.6', '13.2', '17.0', '21.2', '26.5', '33.5']
                                    ];
                                case '2.5 h':
                                    return [
                                        'az' => ['1.40', '1.26', '1.12', '1.00', '0.90', '0.80', '0.71', '0.71', '0.71', '0.71', '0.90', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10'],
                                        'axy' => ['0.50', '0.50', '0.50', '0.50', '0.63', '0.8', '1.0', '1.25', '1.6', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '16.0', '20.0']
                                    ];
                                case '4 h':
                                    return [
                                        'az' => ['1.06', '0.95', '0.85', '0.75', '0.67', '0.60', '0.53', '0.53', '0.53', '0.53', '0.67', '0.85', '1.06', '1.32', '1.70', '2.12', '2.65', '3.35', '4.25', '5.30'],
                                        'axy' => ['0.355', '0.355', '0.355', '0.355', '0.450', '0.560', '0.710', '0.900', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.0']
                                    ];
                                case '8 h':
                                    return [
                                        'az' => ['0.63', '0.56', '0.50', '0.45', '0.40', '0.355', '0.315', '0.315', '0.315', '0.315', '0.40', '0.50', '0.63', '0.80', '1.00', '1.25', '1.60', '2.0', '2.5', '3.15'],
                                        'axy' => ['0.224', '0.224', '0.224', '0.224', '0.280', '0.355', '0.450', '0.560', '0.710', '0.900', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00']
                                    ];
                                case '16 h':
                                    return [
                                        'az' => ['0.383', '0.338', '0.302', '0.270', '0.239', '0.212', '0.192', '0.192', '0.192', '0.192', '0.239', '0.302', '0.383', '0.477', '0.605', '0.765', '0.955', '1.19', '1.53', '1.91'],
                                        'axy' => ['0.135', '0.135', '0.135', '0.135', '0.171', '0.212', '0.270', '0.338', '0.428', '0.54', '0.675', '0.855', '1.06', '1.35', '1.71', '2.12', '2.70', '3.38', '4.28', '5.4']
                                    ];
                                case '24 h':
                                    return [
                                        'az' => ['0.280', '0.250', '0.224', '0.200', '0.180', '0.160', '0.140', '0.140', '0.140', '0.140', '0.180', '0.224', '0.280', '0.355', '0.450', '0.560', '0.710', '0.900', '1.120', '1.400'],
                                        'axy' => ['0.100', '0.100', '0.100', '0.100', '0.125', '0.160', '0.20', '0.250', '0.315', '0.40', '0.50', '0.63', '0.80', '1.00', '1.25', '1.60', '2.00', '2.50', '3.15', '4.00']
                                    ];
                                default:
                                    return [
                                        'az' => [],
                                        'axy' => []
                                    ];
                            }
                        }


                        $frecuencias = [
                            '1.00',
                            '1.25',
                            '1.60',
                            '2.00',
                            '2.50',
                            '3.15',
                            '4.00',
                            '5.00',
                            '6.30',
                            '8.00',
                            '10.00',
                            '12.50',
                            '16.00',
                            '20.00',
                            '25.00',
                            '31.50',
                            '40.00',
                            '50.00',
                            '63.00',
                            '80.00'
                        ];

                        $columnasX1 = ['K', 'T', 'AC', 'AL', 'AU', 'BD', 'BM', 'BV', 'CE', 'CN', 'CW', 'DF', 'DO', 'DX', 'EG', 'EP', 'EY', 'FH', 'FQ', 'FZ'];
                        $columnasX2 = ['N', 'W', 'AF', 'AO', 'AX', 'BG', 'BP', 'BY', 'CH', 'CQ', 'CZ', 'DI', 'DR', 'EA', 'EJ', 'ES', 'FB', 'FK', 'FT', 'GC'];
                        $columnasX3 = ['Q', 'Z', 'AI', 'AR', 'BA', 'BJ', 'BS', 'CB', 'CK', 'CT', 'DC', 'DL', 'DU', 'ED', 'EK', 'EV', 'FE', 'FN', 'FW', 'GF'];

                        $columnasY1 = ['L', 'U', 'AD', 'AM', 'AV', 'BE', 'BN', 'BW', 'CF', 'CO', 'CX', 'DG', 'DP', 'DY', 'EH', 'EQ', 'EZ', 'FI', 'FR', 'GA'];
                        $columnasY2 = ['O', 'X', 'AG', 'AP', 'AY', 'BH', 'BQ', 'BZ', 'CI', 'CR', 'DA', 'DJ', 'DS', 'EB', 'EK', 'ET', 'FC', 'FL', 'FU', 'GD'];
                        $columnasY3 = ['R', 'AA', 'AJ', 'AS', 'BB', 'BK', 'BT', 'CC', 'CL', 'CU', 'DD', 'DM', 'DV', 'EE', 'EN', 'EW', 'FF', 'FO', 'FX', 'GG'];

                        $columnasZ1 = ['M', 'V', 'AE', 'AN', 'AW', 'BF', 'BO', 'BX', 'CG', 'CP', 'CY', 'DH', 'DQ', 'DZ', 'EI', 'ER', 'FA', 'FJ', 'FS', 'GB'];
                        $columnasZ2 = ['P', 'Y', 'AH', 'AQ', 'AZ', 'BI', 'BR', 'CA', 'CJ', 'CS', 'DB', 'DK', 'DT', 'EC', 'EL', 'EU', 'FD', 'FM', 'FV', 'GE'];
                        $columnasZ3 = ['S', 'AB', 'AK', 'AT', 'BC', 'BL', 'BU', 'CD', 'CM', 'CV', 'DE', 'DN', 'DW', 'EF', 'EO', 'EX', 'FG', 'FP', 'FY', 'GH'];

                        // Reiniciamos el AUTO_INCREMENT de la tabla principal
                        // DB::statement('ALTER TABLE reportevibracionevaluacion AUTO_INCREMENT = 1;');

                        // Limpiamos, validamos e insertamos todos los datos del Excel
                        foreach ($datosGenerales as $rowData) {
                            // Inserción en la tabla principal
                            $punto = reportevibracionevaluacionModel::create([
                                'proyecto_id' => $request['proyecto_id'],
                                'reportearea_id' => isset($IdAreas[$rowData['A']]) ? $IdAreas[$rowData['A']] : null,
                                'reportevibracionevaluacion_puntoevaluacion' => is_null($rowData['B']) ? null : $rowData['B'],
                                'reportevibracionevaluacion_punto' => is_null($rowData['C']) ? null : $rowData['C'],
                                'reportecategoria_id' => isset($IdCategorias[$rowData['D']]) ? $IdCategorias[$rowData['D']] : null,
                                'reportevibracionevaluacion_nombre' => is_null($rowData['E']) ? null : $rowData['E'],
                                'reportevibracionevaluacion_ficha' => is_null($rowData['F']) ? null : $rowData['F'],
                                'reportevibracionevaluacion_tipoevaluacion' => is_null($rowData['G']) ? null : tipoevaluacion($rowData['G']),
                                'reportevibracionevaluacion_tiempoexposicion' => is_null($rowData['H']) ? null : tiempoexposicion($rowData['H']),
                                'reportevibracionevaluacion_numeromediciones' => is_null($rowData['I']) ? null : medicioneje($rowData['I']),
                                'reportevibracionevaluacion_fecha' => is_null($rowData['J']) ? null : validarFecha($rowData['J']),
                            ]);

                            // Obtener límites basados en el tiempo de exposición
                            $tiempoExposicion = tiempoexposicion($rowData['H']);
                            $limites = obtenerLimites($tiempoExposicion);
                            $limiteAz = $limites['az'];
                            $limiteAxy = $limites['axy'];

                            // Inserción en la tabla de detalles para cada frecuencia
                            foreach ($frecuencias as $key => $frecuencia) {
                                reportevibracionevaluaciondatosModel::create([
                                    'reportevibracionevaluacion_id' => $punto->id,
                                    'reportevibracionevaluaciondatos_frecuencia' => $frecuencia,
                                    'reportevibracionevaluaciondatos_azlimite' => isset($limiteAz[$key]) ? $limiteAz[$key] : null,
                                    'reportevibracionevaluaciondatos_axylimite' => isset($limiteAxy[$key]) ? $limiteAxy[$key] : null,

                                    // Valores de los ejes Z
                                    'reportevibracionevaluaciondatos_az1' => isset($rowData[$columnasZ1[$key]]) ? $rowData[$columnasZ1[$key]] : null,
                                    'reportevibracionevaluaciondatos_az2' => isset($rowData[$columnasZ2[$key]]) ? $rowData[$columnasZ2[$key]] : null,
                                    'reportevibracionevaluaciondatos_az3' => isset($rowData[$columnasZ3[$key]]) ? $rowData[$columnasZ3[$key]] : null,

                                    // Valores de los ejes X
                                    'reportevibracionevaluaciondatos_ax1' => isset($rowData[$columnasX1[$key]]) ? $rowData[$columnasX1[$key]] : null,
                                    'reportevibracionevaluaciondatos_ax2' => isset($rowData[$columnasX2[$key]]) ? $rowData[$columnasX2[$key]] : null,
                                    'reportevibracionevaluaciondatos_ax3' => isset($rowData[$columnasX3[$key]]) ? $rowData[$columnasX3[$key]] : null,

                                    // Valores de los ejes Y
                                    'reportevibracionevaluaciondatos_ay1' => isset($rowData[$columnasY1[$key]]) ? $rowData[$columnasY1[$key]] : null,
                                    'reportevibracionevaluaciondatos_ay2' => isset($rowData[$columnasY2[$key]]) ? $rowData[$columnasY2[$key]] : null,
                                    'reportevibracionevaluaciondatos_ay3' => isset($rowData[$columnasY3[$key]]) ? $rowData[$columnasY3[$key]] : null,
                                ]);
                            }

                            $puntosInsertados++;
                        }

                        //RETORNAMOS UN MENSAJE DE CUANTOS INSERTO 
                        return response()->json(['msj' => 'Total de puntos insertados : ' . $puntosInsertados . ' de ' . $totalPuntos, 'code' => 200]);
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los resultados, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }
            }

            if ($request->opcion == 1001) {



                $proyecto_id = $request['proyecto_id'];

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


                        function tipoevaluacion($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == 'NOM-024' || $usoModificado == 'NOM-024-STPS-2001'  || $usoModificado == 'LÍMITES POR NOM-024-STPS-2001' || $usoModificado == 'LIMITES POR NOM-024-STPS-2001') {
                                return 1;
                            } elseif ($usoModificado == 'LÍMITES POR INTERPOLACIÓN' || $usoModificado == 'INTERPOLACIÓN' ||  $usoModificado == 'LIMITES POR INTERPOLACION' || $usoModificado == 'INTERPOLACION') {
                                return 2;
                            } elseif ($usoModificado === 'MÉTODO ISO'  || $usoModificado == 'ISO' || $usoModificado == 'METODO ISO') {
                                return 3;
                            } else {
                                return 0;
                            }
                        }



                        function tiempoexposicion($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == '1 MINUTOS' || $usoModificado == '1 MIN' || $usoModificado == '1 MINUTO') {
                                return "1 min";
                            } elseif ($usoModificado == '16 MINUTOS' || $usoModificado == '16 MIN' || $usoModificado == '16 MINUTO') {
                                return "16 min";
                            } elseif ($usoModificado === '25 MINUTOS'  || $usoModificado == '25 MIN' || $usoModificado == '25 MINUTO') {
                                return "25 min";
                            } elseif ($usoModificado === '1 HORA'  || $usoModificado == '1 H' || $usoModificado == '1 HORAS') {
                                return "1 h";
                            } elseif ($usoModificado === '2.5 HORA'  || $usoModificado == '2.5 H' || $usoModificado == '2.5 HORAS') {
                                return "2.5 h";
                            } elseif ($usoModificado === '4 HORA'  || $usoModificado == '4 H' || $usoModificado == '4 HORAS') {
                                return "4 h";
                            } elseif ($usoModificado === '8 HORA'  || $usoModificado == '8 H' || $usoModificado == '8 HORAS') {
                                return "8 h";
                            } elseif ($usoModificado === '16 HORA'  || $usoModificado == '16 H' || $usoModificado == '16 HORAS') {
                                return "16 h";
                            } elseif ($usoModificado === '24 HORA'  || $usoModificado == '24 H' || $usoModificado == '24 HORAS') {
                                return "24 h";
                            } else {
                                return 0;
                            }
                        }


                        function medicioneje($uso)
                        {
                            $usoModificado = trim(mb_strtoupper($uso, 'UTF-8'));

                            $usoModificado = preg_replace('/\s+/', ' ', $usoModificado);

                            // Comprobar y retornar el valor correspondiente
                            if ($usoModificado == '1') {
                                return 1;
                            } elseif ($usoModificado == '3') {
                                return 3;
                            } else {
                                return 0;
                            }
                        }


                        function obtenerLimites($tiempo_exposicion)
                        {
                            switch ($tiempo_exposicion) {
                                case '1 min':
                                    return [
                                        'az' => ['5.60', '5.00', '4.50', '4.00', '3.55', '3.15', '2.80', '2.80', '2.80', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.00', '18.0', '22.4', '28.0'],
                                        'axy' => ['2.0', '2.0', '2.0', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '16.0', '20.0', '25.0', '31.5', '40.0', '50.0', '63.0', '80.0']
                                    ];
                                case '16 min':
                                    return [
                                        'az' => ['4.25', '3.75', '3.35', '3.00', '2.65', '2.35', '2.12', '2.12', '2.12', '2.12', '2.65', '3.35', '4.25', '5.30', '6.70', '8.50', '10.6', '13.2', '17.0', '21.2'],
                                        'axy' => ['1.50', '1.50', '1.50', '1.50', '1.9', '2.36', '3.0', '3.75', '4.75', '6.0', '7.5', '9.5', '11.8', '15.0', '19.0', '23.6', '30.0', '37.5', '45.7', '60.0']
                                    ];
                                case '25 min':
                                    return [
                                        'az' => ['3.55', '3.15', '2.80', '2.50', '2.24', '2.00', '1.80', '1.80', '1.80', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.0', '18.0'],
                                        'axy' => ['1.25', '1.25', '1.25', '1.25', '1.6', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '15.0', '20.0', '25.0', '3.5', '40.0', '50.0']
                                    ];
                                case '1 h':
                                    return [
                                        'az' => ['2.36', '2.12', '1.90', '1.70', '1.50', '1.32', '1.18', '1.18', '1.18', '1.18', '1.50', '1.90', '2.36', '3.00', '3.75', '4.75', '6.00', '7.50', '9.50', '11.8'],
                                        'axy' => ['0.85', '0.85', '0.85', '0.85', '1.06', '1.32', '1.70', '2.12', '2.65', '3.35', '4.25', '5.30', '6.70', '8.5', '10.6', '13.2', '17.0', '21.2', '26.5', '33.5']
                                    ];
                                case '2.5 h':
                                    return [
                                        'az' => ['1.40', '1.26', '1.12', '1.00', '0.90', '0.80', '0.71', '0.71', '0.71', '0.71', '0.90', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10'],
                                        'axy' => ['0.50', '0.50', '0.50', '0.50', '0.63', '0.8', '1.0', '1.25', '1.6', '2.0', '2.5', '3.15', '4.0', '5.0', '6.3', '8.0', '10.0', '12.5', '16.0', '20.0']
                                    ];
                                case '4 h':
                                    return [
                                        'az' => ['1.06', '0.95', '0.85', '0.75', '0.67', '0.60', '0.53', '0.53', '0.53', '0.53', '0.67', '0.85', '1.06', '1.32', '1.70', '2.12', '2.65', '3.35', '4.25', '5.30'],
                                        'axy' => ['0.355', '0.355', '0.355', '0.355', '0.450', '0.560', '0.710', '0.900', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00', '11.2', '14.0']
                                    ];
                                case '8 h':
                                    return [
                                        'az' => ['0.63', '0.56', '0.50', '0.45', '0.40', '0.355', '0.315', '0.315', '0.315', '0.315', '0.40', '0.50', '0.63', '0.80', '1.00', '1.25', '1.60', '2.0', '2.5', '3.15'],
                                        'axy' => ['0.224', '0.224', '0.224', '0.224', '0.280', '0.355', '0.450', '0.560', '0.710', '0.900', '1.12', '1.40', '1.80', '2.24', '2.80', '3.55', '4.50', '5.60', '7.10', '9.00']
                                    ];
                                case '16 h':
                                    return [
                                        'az' => ['0.383', '0.338', '0.302', '0.270', '0.239', '0.212', '0.192', '0.192', '0.192', '0.192', '0.239', '0.302', '0.383', '0.477', '0.605', '0.765', '0.955', '1.19', '1.53', '1.91'],
                                        'axy' => ['0.135', '0.135', '0.135', '0.135', '0.171', '0.212', '0.270', '0.338', '0.428', '0.54', '0.675', '0.855', '1.06', '1.35', '1.71', '2.12', '2.70', '3.38', '4.28', '5.4']
                                    ];
                                case '24 h':
                                    return [
                                        'az' => ['0.280', '0.250', '0.224', '0.200', '0.180', '0.160', '0.140', '0.140', '0.140', '0.140', '0.180', '0.224', '0.280', '0.355', '0.450', '0.560', '0.710', '0.900', '1.120', '1.400'],
                                        'axy' => ['0.100', '0.100', '0.100', '0.100', '0.125', '0.160', '0.20', '0.250', '0.315', '0.40', '0.50', '0.63', '0.80', '1.00', '1.25', '1.60', '2.00', '2.50', '3.15', '4.00']
                                    ];
                                default:
                                    return [
                                        'az' => [],
                                        'axy' => []
                                    ];
                            }
                        }


                // Empezamos a guardar los puntos de iluminacion
                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelResultado')) {

                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelResultado');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());

                        // Obtener la cantidad de hojas
                        $totalHojas = $spreadsheet->getSheetCount();

                        for ($hojaIndex = 0; $hojaIndex < $totalHojas; $hojaIndex++) {
                        //     // Selecciona la hoja actual
                        $sheet = $spreadsheet->getSheet($hojaIndex);
                        //obtener hoja activa
                        //$sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar las 3 primeras filas ya que no contienen datos importantes
                        //$data = array_slice($data, 4);

                        //Obtenemos todos los datos del Excel y los almacenamos datos sin imagenes
                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {
                                // Almacenar la fila limpia en el array
                                $datosGenerales[] = $row;
                            }
                        }

                         

                        //Puntos totales
                        $totalPuntos = 20;
                        $puntosInsertados = 0;

                        //================================= Funciones de limpieaza de datos =================================

                        //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS CATEGORIAS CON SU ID
                        $IdCategorias = [];
                        $caategorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)->get();
                        foreach ($caategorias as $cat) {
                            $clave = $cat->reportecategoria_nombre;
                            $IdCategorias[$clave] = $cat->id;
                        }

                        //BUCAMOS Y ARMAMOS EL ARRAY PARA OBTENER LAS AREAS CON SU ID
                        $IdAreas = [];
                        $areas = reporteareaModel::where('proyecto_id', $proyecto_id)->get();
                        foreach ($areas as $area) {
                            $clave = $area->reportearea_nombre;
                            $IdAreas[$clave] = $area->id;
                        }



                        $frecuencias = [
                            '1.00',
                            '1.25',
                            '1.60',
                            '2.00',
                            '2.50',
                            '3.15',
                            '4.00',
                            '5.00',
                            '6.30',
                            '8.00',
                            '10.00',
                            '12.50',
                            '16.00',
                            '20.00',
                            '25.00',
                            '31.50',
                            '40.00',
                            '50.00',
                            '63.00',
                            '80.00'
                        ];

                        $columnasX1 = 'B';
                        $columnasX2 = 'E';
                        $columnasX3 = 'H';
                        $columnasY1 = 'C';
                        $columnasY2 = 'F';
                        $columnasY3 = 'I';
                        $columnasZ1 = 'D';
                        $columnasZ2 = 'G';
                        $columnasZ3 = 'J';  

                        // Reiniciamos el AUTO_INCREMENT de la tabla principal
                        // DB::statement('ALTER TABLE reportevibracionevaluacion AUTO_INCREMENT = 1;');    
                        // Limpiamos, validamos e insertamos todos los datos del Excel
                            foreach ($datosGenerales as $index => $rowData) {
                                switch ($index) {
                                    case 0: // Primera fila: contiene datos del área
                                        $area = isset($rowData['B']) ? $rowData['B'] : null;
                                        $puntoEvaluacion = isset($rowData['G']) ? $rowData['G'] : null;
                                        break;
                            
                                    case 1: // Segunda fila: contiene datos del punto de medición
                                        $puntoMedicion = isset($rowData['C']) ? $rowData['C'] : null;
                                        $categoria = isset($rowData['F']) ? $rowData['F'] : null;
                                        break;
                            
                                    case 2: // Tercera fila: contiene nombre y ficha
                                        $nombre = isset($rowData['C']) ? $rowData['C'] : null;
                                        $ficha = isset($rowData['I']) ? $rowData['I'] : null;
                                        break;
                            
                                    case 4: // Quinta fila: contiene límites y fecha
                                        $tipoEvaluacion = isset($rowData['A']) ? $rowData['A'] : null;
                                        $tiempoExposicion = isset($rowData['E']) ? $rowData['E'] : null;
                                        $numeroMediciones = isset($rowData['G']) ? $rowData['G'] : null;
                                        $fechaMedicion = isset($rowData['I']) ? validarFecha($rowData['I']) : null;
                                        break;
                                }
                               

                            
                        }

                        $punto = reportevibracionevaluacionModel::create([
                            'proyecto_id' => $request['proyecto_id'],
                            'reportearea_id' => isset($IdAreas[$area]) ? $IdAreas[$area] : null,
                            'reportevibracionevaluacion_puntoevaluacion' => $puntoEvaluacion,
                            'reportevibracionevaluacion_punto' => $puntoMedicion,
                            'reportecategoria_id' => isset($IdCategorias[$categoria]) ? $IdCategorias[$categoria] : null,
                            'reportevibracionevaluacion_nombre' => $nombre,
                            'reportevibracionevaluacion_ficha' => $ficha,
                            'reportevibracionevaluacion_tipoevaluacion' => tipoevaluacion($tipoEvaluacion),
                            'reportevibracionevaluacion_tiempoexposicion' => tiempoexposicion($tiempoExposicion),
                            'reportevibracionevaluacion_numeromediciones' => medicioneje($numeroMediciones),
                            'reportevibracionevaluacion_fecha' => validarFecha($fechaMedicion),
                        ]);
                      // Obtener límites basados en el tiempo de exposición
                        $tiempoExposicion1 = tiempoexposicion($tiempoExposicion);
                        $limites = obtenerLimites($tiempoExposicion1);
                        $limiteAz = $limites['az'];
                        $limiteAxy = $limites['axy'];
                        
                        
                       
                        foreach ($frecuencias as $key => $frecuencia) {
                            // Asignar valores generales de la frecuencia
                            $reportData = [
                                'reportevibracionevaluacion_id' => $punto->id,
                                'reportevibracionevaluaciondatos_frecuencia' => $frecuencia,
                                'reportevibracionevaluaciondatos_azlimite' => isset($limiteAz[$key]) ? $limiteAz[$key] : null,
                                'reportevibracionevaluaciondatos_axylimite' => isset($limiteAxy[$key]) ? $limiteAxy[$key] : null,
                            ];
                        
                            // Procesar solo el índice correcto de $datosGenerales
                            $rowData = $datosGenerales[$key + 6] ?? null; // Ajusta $key + 6 si es el índice correspondiente
                        
                            if ($rowData) {
                                $reportData['reportevibracionevaluaciondatos_az1'] = isset($rowData[$columnasZ1]) ? $rowData[$columnasZ1] : null;
                                $reportData['reportevibracionevaluaciondatos_az2'] = isset($rowData[$columnasZ2]) ? $rowData[$columnasZ2] : null;
                                $reportData['reportevibracionevaluaciondatos_az3'] = isset($rowData[$columnasZ3]) ? $rowData[$columnasZ3] : null;
                                $reportData['reportevibracionevaluaciondatos_ax1'] = isset($rowData[$columnasX1]) ? $rowData[$columnasX1] : null;
                                $reportData['reportevibracionevaluaciondatos_ax2'] = isset($rowData[$columnasX2]) ? $rowData[$columnasX2] : null;
                                $reportData['reportevibracionevaluaciondatos_ax3'] = isset($rowData[$columnasX3]) ? $rowData[$columnasX3] : null;
                                $reportData['reportevibracionevaluaciondatos_ay1'] = isset($rowData[$columnasY1]) ? $rowData[$columnasY1] : null;
                                $reportData['reportevibracionevaluaciondatos_ay2'] = isset($rowData[$columnasY2]) ? $rowData[$columnasY2] : null;
                                $reportData['reportevibracionevaluaciondatos_ay3'] = isset($rowData[$columnasY3]) ? $rowData[$columnasY3] : null;
                        
                                // Crear el registro en la base de datos
                                reportevibracionevaluaciondatosModel::create($reportData);
                                $puntosInsertados++;
                            }
                        }
                        // return response()->json(['msj' => 'Total de puntos insertados : ' . $puntosInsertados . ' de ' . $totalPuntos, 'code' => 200]);
                       
                        }
                        return response()->json(['msj' => 'Se cargaron ' . $totalHojas . ' puntos y se insertaron ' . $puntosInsertados . '/' . $totalPuntos. ' registros por punto', 'code' => 200]);

                             
                    } else {

                        return response()->json(["msj" => 'No se ha subido ningún archivo Excel', "code" => 500]);
                    }
                } catch (Exception $e) {

                    return response()->json(['msj' => 'Se produjo un error al intentar cargar los resultados, inténtelo de nuevo o comuníquelo con el responsable ' . ' ---- ' . $e->getMessage(), 'code' => 500]);
                }
            }
            





            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();


            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteregistro_id + 0) > 0) {
                $reporte = reportevibracionModel::findOrFail($request->reporteregistro_id);

                $dato["reporteregistro_id"] = $reporte->id;

                $reporte->update([
                    'reportevibracion_instalacion' => $request->reporte_instalacion
                ]);


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_id', $request->agente_id)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                if (count($revision) > 0) {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 17) // Valida disponibilidad de esta version (17 CANCELACION REVISION)
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reportevibracion AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reportevibracionModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportevibracion_instalacion' => $request->reporte_instalacion,
                    'reportevibracion_catregion_activo' => 1,
                    'reportevibracion_catsubdireccion_activo' => 1,
                    'reportevibracion_catgerencia_activo' => 1,
                    'reportevibracion_catactivo_activo' => 1,
                    'reportevibracion_concluido' => 0,
                    'reportevibracion_cancelado' => 0
                ]);
            }


            //============================================================

            // PORTADA
            if (($request->opcion + 0) == 0) {
                // REGION
                $catregion_activo = 0;
                if ($request->reporte_catregion_activo != NULL) {
                    $catregion_activo = 1;
                }

                // SUBDIRECCION
                $catsubdireccion_activo = 0;
                if ($request->reporte_catsubdireccion_activo != NULL) {
                    $catsubdireccion_activo = 1;
                }

                // GERENCIA
                $catgerencia_activo = 0;
                if ($request->reporte_catgerencia_activo != NULL) {
                    $catgerencia_activo = 1;
                }

                // ACTIVO
                $catactivo_activo = 0;
                if ($request->reporte_catactivo_activo != NULL) {
                    $catactivo_activo = 1;
                }

                $reporte->update([
                    'reportevibracion_catregion_activo' => $catregion_activo,
                    'reportevibracion_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reportevibracion_catgerencia_activo' => $catgerencia_activo,
                    'reportevibracion_catactivo_activo' => $catactivo_activo,
                    'reportevibracion_instalacion' => $request->reporte_instalacion,
                    'reportevibracion_fecha' => $request->reporte_fecha,
                    'reporte_mes' => $request->reporte_mes,
                    'reportevibracion_alcanceinforme' => $request->reporte_alcanceinforme
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
                $reporte->update([
                    // 'reportevibracion_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion)
                    'reportevibracion_introduccion' => $request->reporte_introduccion,
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
                $reporte->update([
                    'reportevibracion_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporte->update([
                    'reportevibracion_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporte->update([
                    'reportevibracion_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion + 0) == 6) {
                $reporte->update([
                    'reportevibracion_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reporteubicacionfoto')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/ubicacionfoto/ubicacionfoto.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportevibracion_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 7) {
                $reporte->update([
                    'reportevibracion_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion),
                    'reportevibracion_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            // AREAS
            if (($request->opcion + 0) == 8) {
                // dd($request->all());


                $area = reporteareaModel::findOrFail($request->reportearea_id);
                $area->update($request->all());


                $eliminar_categorias = reportevibracionareacategoriaModel::where('reportearea_id', $request->reportearea_id)->delete();


                if ($request->checkbox_categoria_id) {
                    DB::statement('ALTER TABLE reportevibracionareacategoria AUTO_INCREMENT = 1;');

                    foreach ($request->checkbox_categoria_id as $key => $value) {
                        $areacategoria = reportevibracionareacategoriaModel::create([
                            'reportearea_id' => $area->id,
                            'reportecategoria_id' => $value
                        ]);
                    }
                }


                $eliminar_maquinaria = reportevibracionareamaquinariaModel::where('reportearea_id', $request->reportearea_id)->delete();


                if ($request->reportevibracionmaquinaria_nombre) {
                    DB::statement('ALTER TABLE reportevibracionmaquinaria AUTO_INCREMENT = 1;');

                    foreach ($request->reportevibracionmaquinaria_nombre as $key => $value) {
                        $areamaquinaria = reportevibracionareamaquinariaModel::create([
                            'reportearea_id' => $area->id,
                            'reportevibracionmaquinaria_nombre' => $value,
                            'reportevibracionmaquinaria_cantidad' => $request['reportevibracionmaquinaria_cantidad'][$key]
                        ]);
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos modificados correctamente';
            }


            // PUNTO DE EVALUACION
            if (($request->opcion + 0) == 9) {
                // dd($request->all());


                if (($request->reportevibracionevaluacion_tipoevaluacion + 0) == 1) {
                    $request['reportevibracionevaluacion_promedio'] = null;
                    $request['reportevibracionevaluacion_valormaximo'] = null;
                    // $request['reportevibracionevaluacion_fecha'] = null;
                }


                if (($request->reportevibracionevaluacion_tipoevaluacion + 0) == 2) {
                    $request['reportevibracionevaluacion_tiempoexposicion'] = $request->reportevibracionevaluacion_promedio;
                    $request['reportevibracionevaluacion_promedio'] = null;
                    $request['reportevibracionevaluacion_valormaximo'] = null;
                    // $request['reportevibracionevaluacion_fecha'] = null;
                }


                if (($request->reportevibracionevaluacion_tipoevaluacion + 0) == 3) {
                    $request['reportevibracionevaluacion_tiempoexposicion'] = null;
                    $request['reportevibracionevaluacion_numeromediciones'] = null;
                }


                //----------------------------------------


                if (($request->reportevibracionevaluacion_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportevibracionevaluacion AUTO_INCREMENT = 1;');
                    $punto = reportevibracionevaluacionModel::create($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {

                    $punto = reportevibracionevaluacionModel::findOrFail($request->reportevibracionevaluacion_id);
                    $punto->update($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }


                //----------------------------------------


                $eliminar_datos = reportevibracionevaluaciondatosModel::where('reportevibracionevaluacion_id', $punto->id)->delete();
                DB::statement('ALTER TABLE reportevibracionevaluaciondatos AUTO_INCREMENT = 1;');


                if (($request->reportevibracionevaluacion_tipoevaluacion + 0) <= 2) {
                    foreach ($request->reportevibracionevaluaciondatos_frecuencia as $key => $value) {
                        $datos = reportevibracionevaluaciondatosModel::create([
                            'reportevibracionevaluacion_id' => $punto->id,
                            'reportevibracionevaluaciondatos_frecuencia' => $value,
                            'reportevibracionevaluaciondatos_az1' => $request['reportevibracionevaluaciondatos_az1'][$key],
                            'reportevibracionevaluaciondatos_az2' => $request['reportevibracionevaluaciondatos_az2'][$key],
                            'reportevibracionevaluaciondatos_az3' => $request['reportevibracionevaluaciondatos_az3'][$key],
                            'reportevibracionevaluaciondatos_azlimite' => $request['reportevibracionevaluaciondatos_azlimite'][$key],
                            'reportevibracionevaluaciondatos_ax1' => $request['reportevibracionevaluaciondatos_ax1'][$key],
                            'reportevibracionevaluaciondatos_ax2' => $request['reportevibracionevaluaciondatos_ax2'][$key],
                            'reportevibracionevaluaciondatos_ax3' => $request['reportevibracionevaluaciondatos_ax3'][$key],
                            'reportevibracionevaluaciondatos_ay1' => $request['reportevibracionevaluaciondatos_ay1'][$key],
                            'reportevibracionevaluaciondatos_ay2' => $request['reportevibracionevaluaciondatos_ay2'][$key],
                            'reportevibracionevaluaciondatos_ay3' => $request['reportevibracionevaluaciondatos_ay3'][$key],
                            'reportevibracionevaluaciondatos_axylimite' => $request['reportevibracionevaluaciondatos_axylimite'][$key]
                        ]);
                    }
                }
            }


            // CONCLUSION
            if (($request->opcion + 0) == 10) {
                $reporte->update([
                    'reportevibracion_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion + 0) == 11) {
                if ($request->recomendacion_checkbox) {
                    $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                        ->where('catactivo_id', $request->catactivo_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->delete();

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
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
                            ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value) {
                        $recomendacion = reporterecomendacionesModel::create([
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'catactivo_id' => $request->catactivo_id,
                            'reporterecomendacionescatalogo_id' => 0,
                            'reporterecomendaciones_tipo' => $request->recomendacionadicional_tipo[$key],
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->recomendacionadicional_descripcion[$key])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                // total recomendaciones
                $recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->get();


                $dato["dashboard_recomendaciones"] = count($recomendaciones);
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion + 0) == 12) {
                $reporte->update([
                    'reportevibracion_responsable1' => $request->reporte_responsable1,
                    'reportevibracion_responsable1cargo' => $request->reporte_responsable1cargo,
                    'reportevibracion_responsable2' => $request->reporte_responsable2,
                    'reportevibracion_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporte->update([
                        'reportevibracion_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportevibracion_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reporteresponsable1documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable1_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/responsable1_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportevibracion_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reporteresponsable2documento')) {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable2_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/responsable2_doc.jpg';

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reportevibracion_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 13) {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->delete();


                if ($request->planoscarpeta_checkbox) {
                    DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->planoscarpeta_checkbox as $key => $value) {
                        $anexo = reporteplanoscarpetasModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
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
            if (($request->opcion + 0) == 14) {
                // dd($request->all());

                if ($request->equipoutilizado_checkbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $request->reporteregistro_id)
                        ->delete();


                    DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');


                    foreach ($request->equipoutilizado_checkbox as $key => $value) {
                        if ($request['equipoutilizado_checkboxcarta_' . $value]) {
                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        } else {
                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'equipo_id' => $value,
                            'reporteequiposutilizados_cartacalibracion' => $request->reporteequiposutilizados_cartacalibracion
                        ]);
                    }


                    // $files = Storage::disk('local')->files('reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/equipos utilizados cartas');

                    // foreach ($files as $file)
                    // {
                    //     $carta = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                    //                                             ->where('agente_nombre', $request->agente_nombre)
                    //                                             ->where('registro_id', $request->reporteregistro_id)
                    //                                             ->where('reporteequiposutilizados_cartacalibracion', $file)
                    //                                             ->get();

                    //     if(count($carta) == 0)
                    //     {
                    //         if (Storage::exists($file))
                    //         {
                    //             Storage::delete($file); // Eliminar carta que no esta en uso
                    //         }
                    //     }
                    // }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion + 0) == 15) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                    ->delete();


                if ($request->anexoresultado_checkbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');


                    $dato["total"] = 0;
                    foreach ($request->anexoresultado_checkbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            ,
                            'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoresultado_nombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['anexoresultado_archivo_' . $value]
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
            if (($request->opcion + 0) == 16) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('reporteanexos_tipo', 2) // ANEXOS TIPO STPS Y EMA
                    ->delete();


                if ($request->anexoacreditacion_checkbox) {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');


                    $dato["total"] = 0;
                    foreach ($request->anexoacreditacion_checkbox as $key => $value) {
                        $anexo = reporteanexosModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'agente_id' => $request->agente_id,
                            'agente_nombre' => $request->agente_nombre,
                            'registro_id' => $reporte->id,
                            'reporteanexos_tipo' => 2  // ANEXOS TIPO STPS Y EMA
                            ,
                            'reporteanexos_anexonombre' => ($key + 1) . '.- ' . str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoacreditacion_nombre_' . $value]),
                            'reporteanexos_rutaanexo' => $request['anexoacreditacion_archivo_' . $value]
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
            if (($request->opcion + 0) == 17) {
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
            $dato["reporteregistro_id"] = $reporte->id;
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
