<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;

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
use App\modelos\reportes\reporteruidocatalogoModel;
use App\modelos\reportes\reporteruidoModel;

use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteruidocategoriaModel;
use App\modelos\reportes\reporteruidoareaModel;
use App\modelos\reportes\reporteruidoareacategoriaModel;
use App\modelos\reportes\reporteruidoareamaquinariaModel;
use App\modelos\reportes\reporteruidoequipoauditivoModel;
use App\modelos\reportes\reporteruidoequipoauditivoatenuacionModel;
use App\modelos\reportes\reporteruidoequipoauditivocategoriasModel;
use App\modelos\reportes\reporteruidoeppModel;
use App\modelos\reportes\reporteruidoareaevaluacionModel;
use App\modelos\reportes\reporteruidonivelsonoroModel;
use App\modelos\reportes\reporteruidopuntonerModel;
use App\modelos\reportes\reporteruidopuntonercategoriasModel;
use App\modelos\reportes\reporteruidopuntonerfrecuenciasModel;
use App\modelos\reportes\reporteruidodosisnerModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\recursosPortadasInformesModel;

//Recursos para abrir el Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reporteruidoController extends Controller
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
                    'agente_nombre' => 'Ruido',
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


            // Vista
            return view('reportes.parametros.reporteruido', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe'));
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
    public function reporteruidodatosgenerales($proyecto_id, $agente_id, $agente_nombre)
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
                                                LIMIT 1');

            if (count($memoriafotografica) > 0) {
                $dato['reporte_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            } else {
                $dato['reporte_memoriafotografica_guardado'] = 0;
            }


            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            // $total_categorias = collect(DB::select('SELECT
            //                                             COUNT(reporteruidocategoria.id) AS TOTAL
            //                                         FROM
            //                                             reporteruidocategoria
            //                                         WHERE
            //                                             reporteruidocategoria.proyecto_id = '.$proyecto_id));

            // if (($total_categorias[0]->TOTAL + 0) == 0)
            // {
            //     $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
            //                                                             ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
            //                                                             ->get();

            //     DB::statement('ALTER TABLE reporteruidocategoria AUTO_INCREMENT = 1;');

            //     foreach ($recsensorial_categorias as $key => $value)
            //     {
            //         $categoria = reporteruidocategoriaModel::create([
            //               'proyecto_id' => $proyecto_id
            //             , 'recsensorialcategoria_id' => $value->id
            //             , 'reporteruidocategoria_nombre' => $value->recsensorialcategoria_nombrecategoria
            //         ]);
            //     }
            // }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            // $total_areas = collect(DB::select('SELECT
            //                                         COUNT(reporteruidoarea.id) AS TOTAL
            //                                     FROM
            //                                         reporteruidoarea
            //                                     WHERE
            //                                         reporteruidoarea.proyecto_id = '.$proyecto_id));

            // if (($total_areas[0]->TOTAL + 0) == 0)
            // {
            //     $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
            //                                                 ->orderBy('recsensorialarea_nombre', 'ASC')
            //                                                 ->get();

            //     DB::statement('ALTER TABLE reporteruidoarea AUTO_INCREMENT = 1;');

            //     foreach ($recsensorial_areas as $key => $value)
            //     {
            //         $area = reporteruidoareaModel::create([
            //               'proyecto_id' => $proyecto_id
            //             , 'recsensorialarea_id' => $value->id
            //             , 'reporteruidoarea_nombre' => $value->recsensorialarea_nombre
            //             , 'reporteruidoarea_instalacion' => $proyecto->proyecto_clienteinstalacion
            //         ]);
            //     }
            // }


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
    public function reporteruidotabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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
    public function reporteruidodefinicioneliminar($definicion_id)
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
    public function reporteruidomapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reporteruidoModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporte->reporteruido_ubicacionfoto);
        } else {
            return Storage::download($reporte->reporteruido_ubicacionfoto);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidocategorias($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            $total_singuardar = 0;


            if (($areas_poe + 0) == 1) {
                $categorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)
                    ->orderBy('reportecategoria_orden', 'ASC')
                    ->get();


                foreach ($categorias as $key => $value) {
                    // $numero_registro += 1;
                    // $value->numero_registro = $numero_registro;
                    $value->numero_registro = $value->reportecategoria_orden;


                    $value->reporteruidocategoria_nombre = $value->reportecategoria_nombre;
                    $value->reporteruidocategoria_total = $value->reportecategoria_total;


                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }


                $total_singuardar = 1;
            } else {
                // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();


                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


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


                //==========================================


                $categorias = reporteruidocategoriaModel::where('proyecto_id', $proyecto_id)
                    ->where('registro_id', $reporteregistro_id)
                    ->orderBy('reporteruidocategoria_nombre', 'ASC')
                    ->get();


                if (count($categorias) == 0) {
                    $categorias = reporteruidocategoriaModel::where('proyecto_id', $proyecto_id)
                        ->orderBy('reporteruidocategoria_nombre', 'ASC')
                        ->get();
                }


                $numero_registro = 0;
                foreach ($categorias as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil fa-1x"></i></button>';


                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                    }


                    if (!$value->reporteruidocategoria_total) {
                        $total_singuardar += 1;
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
    public function reporteruidocategoriaeliminar($categoria_id)
    {
        try {
            $categoria = reporteruidocategoriaModel::where('id', $categoria_id)->delete();

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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoareas($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $total_singuardar = 0;
            $instalacion = 'XXX';
            $area = 'XXX';
            $area2 = 'XXX';
            $selectareasoption = '<option value=""></option>';
            $tabla_5_3 = '';
            $tabla_5_5 = '';
            $tabla_5_8_UNO = '';
            $tabla_5_8_DOS = '';
            $tabla_6_1 = '';


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                        reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                        reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                        reportearea.reportearea_porcientooperacion,
                                        IF( IFNULL( reportearea.reportearea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportearea_porcientooperacion, " %" ), NULL ) AS reporteruidoarea_porcientooperacion_texto,
                                        reportearea.reporteruidoarea_porcientooperacion,
                                        reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                                        reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                                        reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                                        reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                                        reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                                        reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                                        reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                                        reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                                        reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                                        reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                                        reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                                        reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                                        reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10,
                                        reporteareacategoria.reportecategoria_id AS reporteruidocategoria_id,
                                        reportecategoria.reportecategoria_orden AS reporteruidocategoria_orden,
                                        reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reporteruidoareacategoria.reporteruidocategoria_id, "activo", "") AS checked
                                            FROM
                                                reporteruidoareacategoria
                                            WHERE
                                                reporteruidoareacategoria.reporteruidoarea_id = reportearea.id
                                                AND reporteruidoareacategoria.reporteruidocategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
                                            LIMIT 1
                                        ), "") AS activo,
                                        reporteareacategoria.reporteareacategoria_total AS reporteruidoareacategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh AS reporteruidoareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades AS reporteruidoareacategoria_actividades  
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


                // FORMATEAR FILAS
                foreach ($areas as $key => $value) {
                    if ($area != $value->reporteruidoarea_nombre) {
                        $area = $value->reporteruidoarea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reporteruidoarea_porcientooperacion > 0) {
                            //TABLA 5.3.- Descripción de los procesos que generen ruido
                            //==================================================


                            $tabla_5_3 .= '<tr>
                                                <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                <td>' . $value->reporteruidoarea_nombre . '</td>
                                                <td class="justificado">' . $value->reporteruidoarea_proceso . '</td>
                                            </tr>';


                            //TABLA 5.8.- NSA instantáneo para identificar las áreas y fuentes emisoras
                            //==================================================


                            // $tabla_5_8_UNO .= '<tr>
                            //                         <td>'.$value->reporteruidoarea_instalacion.'</td>
                            //                         <td>'.$value->reporteruidoarea_nombre.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_1.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_2.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_3.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_4.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_5.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_6.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_7.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_8.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_9.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_10.'</td>
                            //                         <td>'.$value->reporteruidoarea_tiporuido.'</td>
                            //                         <td>'.$value->reporteruidoarea_evaluacion.'</td>
                            //                     </tr>';


                            $tabla_5_8_UNO .= '<tr>
                                                    <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                    <td>' . $value->reporteruidoarea_nombre . '</td>
                                                    <td>' . $value->reporteruidoarea_LNI_1 . '</td>
                                                    <td>' . $value->reporteruidoarea_LNI_2 . '</td>
                                                    <td>' . $value->reporteruidoarea_tiporuido . '</td>
                                                    <td>' . $value->reporteruidoarea_evaluacion . '</td>
                                                </tr>';


                            //TABLA 5.8.- Maquinaria en el área
                            //==================================================


                            $areamaquinaria = DB::select('SELECT
                                                                reporteruidoareamaquinaria.id,
                                                                reporteruidoareamaquinaria.reporteruidoarea_id,
                                                                reporteruidoareamaquinaria.reporteruidoareamaquinaria_nombre,
                                                                reporteruidoareamaquinaria.reporteruidoareamaquinaria_cantidad 
                                                            FROM
                                                                reporteruidoareamaquinaria
                                                            WHERE
                                                                reporteruidoareamaquinaria.reporteruidoarea_id = ' . $value->id . ' 
                                                                AND reporteruidoareamaquinaria.reporteruidoareamaquinaria_poe > 0
                                                            ORDER BY
                                                                reporteruidoareamaquinaria.id ASC');


                            foreach ($areamaquinaria as $key => $maquina) {
                                $tabla_5_8_DOS .= '<tr>
                                                        <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                        <td>' . $value->reporteruidoarea_nombre . '</td>
                                                        <td>' . $maquina->reporteruidoareamaquinaria_nombre . '</td>
                                                        <td>' . $maquina->reporteruidoareamaquinaria_cantidad . '</td>
                                                    </tr>';
                            }


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================


                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro . '</td>
                                                <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                <td>' . $value->reporteruidoarea_nombre . '</td>
                                                <td>' . $value->reporteruidoarea_porcientooperacion . ' %</td>
                                            </tr>';
                        }
                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    if ($value->activo) {
                        $value->reportecategoria_nombre_texto = '<span class="text-danger">' . $value->reporteruidocategoria_nombre . '</span>';
                    } else {
                        $value->reportecategoria_nombre_texto = $value->reporteruidocategoria_nombre;
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';


                    if ($value->reporteruidoarea_tiporuido === NULL) {
                        $total_singuardar += 1;
                    }


                    if ($value->reporteruidoarea_porcientooperacion > 0) {
                        //TABLA 5.5.- Actividades del personal expuesto
                        //==================================================

                        if ($value->activo) {
                            $tabla_5_5 .= '<tr>
                                                <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                <td>' . $value->reporteruidoarea_nombre . '</td>
                                                <td>' . $value->reporteruidocategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reporteruidoareacategoria_actividades . '</td>
                                            </tr>';
                        }


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reporteruidoarea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reporteruidoarea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }

                        if ($instalacion != $value->reporteruidoarea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reporteruidoarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reporteruidoarea_nombre) {
                            $area2 = $value->reporteruidoarea_nombre;
                            $selectareasoption .= '<option value="' . $value->id . '">' . $area2 . '</option>';
                        }


                        if ($key == (count($areas) - 1)) {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            } else {
                // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


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


                //==========================================


                $registros = DB::select('SELECT
                                            COUNT(reporteruidoarea.id) AS total
                                        FROM
                                            reporteruidoarea
                                        WHERE
                                            reporteruidoarea.proyecto_id = ' . $proyecto_id . '
                                            AND reporteruidoarea.registro_id = ' . $reporteregistro_id);


                $where_condicion = '';
                if (($registros[0]->total + 0) > 0) {
                    $where_condicion = 'AND reporteruidoarea.registro_id = ' . $reporteregistro_id;
                }


                //==========================================


                $areas = DB::select('SELECT
                                        reporteruidoarea.id,
                                        reporteruidoarea.proyecto_id,
                                        reporteruidoarea.registro_id,
                                        reporteruidoarea.recsensorialarea_id,
                                        reporteruidoarea.reporteruidoarea_instalacion,
                                        reporteruidoarea.reporteruidoarea_nombre,
                                        reporteruidoarea.reporteruidoarea_numorden,
                                        reporteruidoarea.reporteruidoarea_proceso,
                                        reporteruidoarea.reporteruidoarea_porcientooperacion,
                                        reporteruidoarea.reporteruidoarea_tiporuido,
                                        reporteruidoarea.reporteruidoarea_evaluacion,
                                        reporteruidoarea.reporteruidoarea_LNI_1,
                                        reporteruidoarea.reporteruidoarea_LNI_2,
                                        reporteruidoarea.reporteruidoarea_LNI_3,
                                        reporteruidoarea.reporteruidoarea_LNI_4,
                                        reporteruidoarea.reporteruidoarea_LNI_5,
                                        reporteruidoarea.reporteruidoarea_LNI_6,
                                        reporteruidoarea.reporteruidoarea_LNI_7,
                                        reporteruidoarea.reporteruidoarea_LNI_8,
                                        reporteruidoarea.reporteruidoarea_LNI_9,
                                        reporteruidoarea.reporteruidoarea_LNI_10,
                                        reporteruidoareacategoria.reporteruidocategoria_id,
                                        reporteruidocategoria.reporteruidocategoria_nombre,
                                        reporteruidoareacategoria.reporteruidoareacategoria_actividades 
                                    FROM
                                        reporteruidoarea
                                        LEFT OUTER JOIN reporteruidoareacategoria ON reporteruidoarea.id = reporteruidoareacategoria.reporteruidoarea_id
                                        LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id 
                                    WHERE
                                        reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                        ' . $where_condicion . ' 
                                        AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                    ORDER BY
                                        reporteruidoarea.reporteruidoarea_numorden ASC,
                                        reporteruidoarea.reporteruidoarea_nombre ASC');


                // FORMATEAR FILAS
                foreach ($areas as $key => $value) {
                    if ($area != $value->reporteruidoarea_nombre) {
                        $area = $value->reporteruidoarea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reporteruidoarea_porcientooperacion > 0) {
                            //TABLA 5.3.- Descripción de los procesos que generen ruido
                            //==================================================


                            $tabla_5_3 .= '<tr>
                                                <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                <td>' . $value->reporteruidoarea_nombre . '</td>
                                                <td class="justificado">' . $value->reporteruidoarea_proceso . '</td>
                                            </tr>';


                            //TABLA 5.8.- NSA instantáneo para identificar las áreas y fuentes emisoras
                            //==================================================


                            // $tabla_5_8_UNO .= '<tr>
                            //                         <td>'.$value->reporteruidoarea_instalacion.'</td>
                            //                         <td>'.$value->reporteruidoarea_nombre.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_1.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_2.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_3.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_4.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_5.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_6.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_7.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_8.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_9.'</td>
                            //                         <td>'.$value->reporteruidoarea_LNI_10.'</td>
                            //                         <td>'.$value->reporteruidoarea_tiporuido.'</td>
                            //                         <td>'.$value->reporteruidoarea_evaluacion.'</td>
                            //                     </tr>';


                            $tabla_5_8_UNO .= '<tr>
                                                    <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                    <td>' . $value->reporteruidoarea_nombre . '</td>
                                                    <td>' . $value->reporteruidoarea_LNI_1 . '</td>
                                                    <td>' . $value->reporteruidoarea_LNI_2 . '</td>
                                                    <td>' . $value->reporteruidoarea_tiporuido . '</td>
                                                    <td>' . $value->reporteruidoarea_evaluacion . '</td>
                                                </tr>';


                            //TABLA 5.8.- Maquinaria en el área
                            //==================================================


                            $areamaquinaria = DB::select('SELECT
                                                                reporteruidoareamaquinaria.id,
                                                                reporteruidoareamaquinaria.reporteruidoarea_id,
                                                                reporteruidoareamaquinaria.reporteruidoareamaquinaria_nombre,
                                                                reporteruidoareamaquinaria.reporteruidoareamaquinaria_cantidad 
                                                            FROM
                                                                reporteruidoareamaquinaria
                                                            WHERE
                                                                reporteruidoareamaquinaria.reporteruidoarea_id = ' . $value->id . ' 
                                                            ORDER BY
                                                                reporteruidoareamaquinaria.id ASC');

                            foreach ($areamaquinaria as $key => $maquina) {
                                $tabla_5_8_DOS .= '<tr>
                                                        <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                        <td>' . $value->reporteruidoarea_nombre . '</td>
                                                        <td>' . $maquina->reporteruidoareamaquinaria_nombre . '</td>
                                                        <td>' . $maquina->reporteruidoareamaquinaria_cantidad . '</td>
                                                    </tr>';
                            }


                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================


                            $tabla_6_1 .= '<tr>
                                                <td>' . $numero_registro . '</td>
                                                <td>' . $value->reporteruidoarea_instalacion . '</td>
                                                <td>' . $value->reporteruidoarea_nombre . '</td>
                                                <td>' . $value->reporteruidoarea_porcientooperacion . ' %</td>
                                            </tr>';
                        }
                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    $value->reportecategoria_nombre_texto = $value->reporteruidocategoria_nombre;

                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';


                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                    }


                    if ($value->reporteruidoarea_tiporuido === NULL) {
                        $total_singuardar += 1;
                    }


                    if ($value->reporteruidoarea_porcientooperacion > 0) {
                        //TABLA 5.5.- Actividades del personal expuesto
                        //==================================================


                        $tabla_5_5 .= '<tr>
                                            <td>' . $value->reporteruidoarea_instalacion . '</td>
                                            <td>' . $value->reporteruidoarea_nombre . '</td>
                                            <td>' . $value->reporteruidocategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reporteruidoareacategoria_actividades . '</td>
                                        </tr>';


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reporteruidoarea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reporteruidoarea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }

                        if ($instalacion != $value->reporteruidoarea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reporteruidoarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reporteruidoarea_nombre) {
                            $area2 = $value->reporteruidoarea_nombre;
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
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_3"] = $tabla_5_3;
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_5_8_UNO"] = $tabla_5_8_UNO;
            $dato["tabla_5_8_DOS"] = $tabla_5_8_DOS;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_3"] = '<tr><td colspan="2">Error al consultar los datos</td></tr>';
            $dato["tabla_5_5"] = '<tr><td colspan="3">Error al consultar los datos</td></tr>';
            $dato["tabla_5_8_UNO"] = '<tr><td colspan="13">Error al consultar los datos</td></tr>';
            $dato["tabla_5_8_DOS"] = '<tr><td colspan="3">Error al consultar los datos</td></tr>';
            $dato["tabla_6_1"] = '<tr><td colspan="3">Error al consultar los datos</td></tr>';
            $dato["selectareasoption"] = '<option value="">Error al consultar áreas</option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $area_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoareascategoriasmaquinaria($proyecto_id, $reporteregistro_id, $area_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $areacategorias = DB::select('SELECT
                                                    reportecategoria.proyecto_id,
                                                    reporteareacategoria.reportearea_id,
                                                    reportecategoria.id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteruidoareacategoria.reporteruidocategoria_id, "checked", "") AS checked
                                                        FROM
                                                            reporteruidoareacategoria
                                                        WHERE
                                                            reporteruidoareacategoria.reporteruidoarea_id = reporteareacategoria.reportearea_id
                                                            AND reporteruidoareacategoria.reporteruidocategoria_id = reportecategoria.id
                                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
                                                        LIMIT 1
                                                    ), "") AS checked,
                                                    reporteareacategoria.reporteareacategoria_total AS reporteruidoareacategoria_total,
                                                    reporteareacategoria.reporteareacategoria_geh AS reporteruidoareacategoria_geh,
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

                $numero_registro = 0;
                $areacategorias_lista = '';

                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;

                    $areacategorias_lista .= '<tr>
                                                <td>
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_categoria_id[]" value="' . $value->id . '" ' . $value->checked . '/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    ' . $value->reporteruidocategoria_nombre . '
                                                    <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_actividades_' . $value->id . '" readonly>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            } else {
                $areacategorias = DB::select('SELECT
                                                    reporteruidocategoria.id,
                                                    reporteruidocategoria.proyecto_id,
                                                    reporteruidocategoria.recsensorialcategoria_id,
                                                    reporteruidocategoria.reporteruidocategoria_nombre,
                                                    reporteruidocategoria.reporteruidocategoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteruidoareacategoria.id, "checked", "") AS checked
                                                        FROM
                                                            reporteruidoareacategoria
                                                        WHERE
                                                            reporteruidoareacategoria.reporteruidoarea_id = ' . $area_id . '
                                                            AND reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id 
                                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                                    ), "") AS checked,
                                                    IFNULL((
                                                        SELECT
                                                            reporteruidoareacategoria.reporteruidoareacategoria_actividades
                                                        FROM
                                                            reporteruidoareacategoria
                                                        WHERE
                                                            reporteruidoareacategoria.reporteruidoarea_id = ' . $area_id . '
                                                            AND reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id 
                                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                                    ), "") AS categoria_actividades
                                                FROM
                                                    reporteruidocategoria
                                                WHERE
                                                    reporteruidocategoria.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteruidocategoria.registro_id = ' . $reporteregistro_id . '
                                                ORDER BY
                                                    reporteruidocategoria.reporteruidocategoria_nombre ASC');


                $numero_registro = 0;
                $areacategorias_lista = '';
                $readonly_required = '';

                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;

                    if ($value->checked) {
                        $readonly_required = 'required';
                    } else {
                        $readonly_required = 'readonly';
                    }

                    $areacategorias_lista .= '<tr>
                                                <td>
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_categoria_id[]" value="' . $value->id . '" ' . $value->checked . ' onchange="activa_areacategoria(this, ' . $numero_registro . ');"/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    ' . $value->reporteruidocategoria_nombre . '
                                                    <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_actividades_' . $value->id . '" ' . $readonly_required . '>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            }



            //=====================================================


            if (($areas_poe + 0) == 1) {
                $areamaquinaria = DB::select('SELECT
                                                    reporteruidoareamaquinaria.id,
                                                    reporteruidoareamaquinaria.reporteruidoarea_id,
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_poe, 
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_nombre,
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_cantidad 
                                                FROM
                                                    reporteruidoareamaquinaria
                                                WHERE
                                                    reporteruidoareamaquinaria.reporteruidoarea_id = ' . $area_id . ' 
                                                    AND reporteruidoareamaquinaria.reporteruidoareamaquinaria_poe = ' . $reporteregistro_id . '
                                                ORDER BY
                                                    reporteruidoareamaquinaria.id ASC');


                $areamaquinaria_lista = '';
                foreach ($areamaquinaria as $key => $value) {
                    if (($key + 0) > 0) {
                        $areamaquinaria_lista .= '<tr>
                                                        <td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" value="' . $value->reporteruidoareamaquinaria_nombre . '" required></td>
                                                        <td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" value="' . $value->reporteruidoareamaquinaria_cantidad . '" required></td>
                                                        <td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>
                                                    </tr>';
                    } else {
                        $areamaquinaria_lista .= '<tr>
                                                        <td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" value="' . $value->reporteruidoareamaquinaria_nombre . '" required></td>
                                                        <td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" value="' . $value->reporteruidoareamaquinaria_cantidad . '" required></td>
                                                        <td><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>
                                                    </tr>';
                    }
                }
            } else {
                $areamaquinaria = DB::select('SELECT
                                                    reporteruidoareamaquinaria.id,
                                                    reporteruidoareamaquinaria.reporteruidoarea_id,
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_poe, 
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_nombre,
                                                    reporteruidoareamaquinaria.reporteruidoareamaquinaria_cantidad 
                                                FROM
                                                    reporteruidoareamaquinaria
                                                WHERE
                                                    reporteruidoareamaquinaria.reporteruidoarea_id = ' . $area_id . ' 
                                                    AND reporteruidoareamaquinaria.reporteruidoareamaquinaria_poe = 0
                                                ORDER BY
                                                    reporteruidoareamaquinaria.id ASC');


                $areamaquinaria_lista = '';
                foreach ($areamaquinaria as $key => $value) {
                    if (($key + 0) > 0) {
                        $areamaquinaria_lista .= '<tr>
                                                        <td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" value="' . $value->reporteruidoareamaquinaria_nombre . '" required></td>
                                                        <td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" value="' . $value->reporteruidoareamaquinaria_cantidad . '" required></td>
                                                        <td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>
                                                    </tr>';
                    } else {
                        $areamaquinaria_lista .= '<tr>
                                                        <td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" value="' . $value->reporteruidoareamaquinaria_nombre . '" required></td>
                                                        <td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" value="' . $value->reporteruidoareamaquinaria_cantidad . '" required></td>
                                                        <td><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>
                                                    </tr>';
                    }
                }
            }


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;
            $dato['areamaquinaria'] = $areamaquinaria_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areacategorias'] = '<tr><td colspan="2">Error al cargar las categorías</td></tr>';
            $dato['areamaquinaria'] = '<tr><td colspan="3">Error al cargar las fuentes generadoras</td></tr>';
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
    public function reporteruidoareaeliminar($area_id)
    {
        try {
            $area = reporteruidoareaModel::where('id', $area_id)->delete();
            $areacategorias = reporteruidoareacategoriaModel::where('reporteruidoarea_id', $area_id)
                ->where('reporteruidoareacategoria_poe', 0)
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
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoequipoauditivotabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            $equipoauditivo_lista = DB::select('SELECT
                                                    reporteruidoequipoauditivo.id,
                                                    reporteruidoequipoauditivo.proyecto_id,
                                                    reporteruidoequipoauditivo.registro_id,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR 
                                                FROM
                                                    reporteruidoequipoauditivo
                                                WHERE
                                                    reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidoequipoauditivo.registro_id = ' . $reporteregistro_id . '
                                                ORDER BY
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');


            $t = '';
            $equiposauditivos_datos = '<p class="justificado">No hay equipos auditivos agregados</p>';

            if (count($equipoauditivo_lista) > 0) {
                $numero_registro = 0;
                $equiposauditivos_datos = '';

                foreach ($equipoauditivo_lista as $key => $lista) {
                    $numero_registro += 1;

                    $atenuaciones = DB::select('SELECT
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivo_id,
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivoatenuacion_bandaNRR,
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivoatenuacion_bandaatenuacion 
                                                FROM
                                                    reporteruidoequipoauditivoatenuacion
                                                WHERE
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivo_id = ' . $lista->id . ' 
                                                ORDER BY
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivoatenuacion_bandaNRR ASC, 
                                                    reporteruidoequipoauditivoatenuacion.reporteruidoequipoauditivoatenuacion_bandaatenuacion ASC');

                    // DIBUJAR TABLAS
                    $t .= '<table class="table table-hover tabla_info_centrado" width="100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" width="200">Equipo</th>
                                        <th rowspan="2" width="70">NRR</th>
                                        <th colspan="' . count($atenuaciones) . '">Atenuación por bandas de octava</th>
                                        <th rowspan="2" width="60">Editar</th>
                                        <th rowspan="2" width="60">Eliminar</th>
                                    </tr>
                                    <tr>';
                    foreach ($atenuaciones as $key => $value) {
                        $t .= '<th >' . $value->reporteruidoequipoauditivoatenuacion_bandaNRR . '</th>';
                    }
                    $t .= '</tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>' . $lista->reporteruidoequipoauditivo_tipo . ',<br>Marca: ' . $lista->reporteruidoequipoauditivo_marca . ',<br>Modelo: ' . $lista->reporteruidoequipoauditivo_modelo . '</th>
                                        <td>' . $lista->reporteruidoequipoauditivo_NRR . ' dB</th>';
                    foreach ($atenuaciones as $key => $value) {
                        $t .= '<td>' . $value->reporteruidoequipoauditivoatenuacion_bandaatenuacion . '</td>';
                    }

                    $t .= '<td><button type="button" class="btn btn-warning waves-effect btn-circle" onclick="equipoauditivo_editar(' . $proyecto_id . ', ' . $reporteregistro_id . ', ' . $lista->id . ', \'' . $lista->reporteruidoequipoauditivo_tipo . '\', \'' . $lista->reporteruidoequipoauditivo_marca . '\', \'' . $lista->reporteruidoequipoauditivo_modelo . '\', \'' . $lista->reporteruidoequipoauditivo_NRR . '\');"><i class="fa fa-pencil fa-1x"></i></button></td>';

                    if ($edicion == 1) {
                        $t .= '<td><button type="button" class="btn btn-danger waves-effect btn-circle" onclick="equipoauditivo_eliminar(' . $lista->id . ', \'' . $lista->reporteruidoequipoauditivo_tipo . '\');"><i class="fa fa-trash fa-1x"></i></button></td>';
                    } else {
                        $t .= '<td><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>';
                    }

                    $t .= '</tr>
                                </tbody>
                            </table>';


                    // PUNTO 7.4.- Determinación del factor de reducción (R) del equipo de protección personal auditivo
                    //==================================================


                    // $equiposauditivos_datos .= '<b style="color: #000000;">Ejemplo '.($numero_registro).':</b><br><br>
                    //                             <p class="justificado">
                    //                                 Equipo de Protección Personal Auditivo (EPPA): '.$lista->reporteruidoequipoauditivo_tipo.'<br>
                    //                                 Marca: '.$lista->reporteruidoequipoauditivo_marca.', Modelo: '.$lista->reporteruidoequipoauditivo_modelo.'<br>
                    //                                 NRR: '.$lista->reporteruidoequipoauditivo_NRR.' dB
                    //                             </p><br>
                    //                             <p class="justificado">Se procede a determinar el Factor de Reducción del EPPA:</p><br>
                    //                             <div style="border: 0px #F00 solid; width: 312px; margin: 0px auto;">
                    //                                 <span style="position: absolute; margin: 0px 0px 0px 90px; font-size: 29px; line-height: 29px; font-weight: normal; color: #000000;">'.$lista->reporteruidoequipoauditivo_NRR.'</span>
                    //                                 <span style="position: absolute; margin: 16px 0px 0px 257px; font-size: 29px; line-height: 29px; font-weight: normal; color: #000000;">
                    //                                     '.round(((($lista->reporteruidoequipoauditivo_NRR+0) - 7) / 2), 1).'
                    //                                 </span>
                    //                                 <img src="/assets/images/reportes/reporteruido_figura_7.4_vacio.jpg" height="60">
                    //                             </div><br>';
                }
            } else {
                $t = '<table class="table table-hover tabla_info_centrado" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" width="200">Equipo</th>
                                                        <th rowspan="2" width="80">NRR</th>
                                                        <th>Atenuación por bandas de octava</th>
                                                    </tr>
                                                    <tr>
                                                        <th>0</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>No hay equipo que mostrar</th>
                                                        <td>0 dB</th>
                                                        <td>0</th>
                                                    </tr>
                                                </tbody>
                                            </table>';
            }


            //TABLA 5.6.- Equipo de Protección Personal Auditiva (EPPA)
            //==================================================


            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                reporteruidoequipoauditivo.proyecto_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre, 
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR,
                                                reporteruidoequipoauditivocategorias.reporteruidocategoria_id
                                            FROM
                                                reporteruidoequipoauditivo
                                                LEFT JOIN reporteruidoequipoauditivocategorias ON reporteruidoequipoauditivo.id = reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id
                                                RIGHT JOIN reportecategoria ON reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reportecategoria.id
                                            WHERE
                                                reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');
            } else {
                $where_condicion = '';
                if (($reporteregistro_id + 0) > 0) {
                    $where_condicion = 'AND reporteruidocategoria.registro_id = ' . $reporteregistro_id;
                }

                $categorias = DB::select('SELECT
                                                reporteruidoequipoauditivo.proyecto_id,
                                                reporteruidocategoria.reporteruidocategoria_nombre, 
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR,
                                                reporteruidoequipoauditivocategorias.reporteruidocategoria_id
                                            FROM
                                                reporteruidoequipoauditivo
                                                LEFT JOIN reporteruidoequipoauditivocategorias ON reporteruidoequipoauditivo.id = reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id
                                                RIGHT JOIN reporteruidocategoria ON reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reporteruidocategoria.id
                                            WHERE
                                                reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                                ' . $where_condicion . ' 
                                            ORDER BY
                                                reporteruidocategoria.reporteruidocategoria_nombre ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                                reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');
            }


            $tabla_5_6 = '';
            foreach ($categorias as $key => $value) {
                $tabla_5_6 .= '<tr>
                                    <td>' . $value->reporteruidocategoria_nombre . '</td>
                                    <td>' . $value->reporteruidoequipoauditivo_tipo . '</td>
                                    <td>' . $value->reporteruidoequipoauditivo_marca . '</td>
                                    <td>' . $value->reporteruidoequipoauditivo_modelo . '</td>
                                    <td>' . $value->reporteruidoequipoauditivo_NRR . ' dB</td>
                                </tr>';
            }

            // respuesta
            $dato["tabla_5_6"] = $tabla_5_6;
            $dato['equipoauditivo_lista'] = $equipoauditivo_lista;
            $dato['equiposauditivos_tablas'] = $t;
            // $dato['equiposauditivos_datos'] = $equiposauditivos_datos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["tabla_5_6"] = '<tr><td colspan="5">Error al consultar los datos</td></tr>';
            $dato['equipoauditivo_lista'] = 0;
            $dato['equiposauditivos_tablas'] = '<table class="table table-hover tabla_info_centrado" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" width="200">Equipo</th>
                                                            <th rowspan="2" width="80">NRR</th>
                                                            <th>Atenuación por bandas de octava</th>
                                                        </tr>
                                                        <tr>
                                                            <th>0</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3">Error al cargar los datos</th>
                                                        </tr>
                                                    </tbody>
                                                </table>';
            // $dato['equiposauditivos_datos'] = '<p class="justificado">Error al consultar los equipos auditivos</p>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $equipoauditivo_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoequipoauditivoatenuaciones($equipoauditivo_id)
    {
        try {
            $atenuaciones = reporteruidoequipoauditivoatenuacionModel::where('reporteruidoequipoauditivo_id', $equipoauditivo_id)->get();

            $filas = '';
            foreach ($atenuaciones as $key => $value) {
                if (($key + 0) == 0) {
                    $filas .= '<tr>
                                    <td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaNRR[]" value="' . $value->reporteruidoequipoauditivoatenuacion_bandaNRR . '" required></td>
                                    <td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaatenuacion[]" value="' . $value->reporteruidoequipoauditivoatenuacion_bandaatenuacion . '" required></td>
                                    <td width="10%"><button type="button" class="btn btn-default waves-effect btn-circle"><i class="fa fa-ban fa-1x"></i></button></td>
                                </tr>';
                } else {
                    $filas .= '<tr>
                                    <td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaNRR[]" value="' . $value->reporteruidoequipoauditivoatenuacion_bandaNRR . '" required></td>
                                    <td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaatenuacion[]" value="' . $value->reporteruidoequipoauditivoatenuacion_bandaatenuacion . '" required></td>
                                    <td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>
                                </tr>';
                }
            }

            // respuesta
            $dato['atenuaciones'] = $filas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['atenuaciones'] = '<tr>
                                        <td colspan="3">Error al consultar los datos</td>
                                    </tr>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $equipoauditivo_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoequipoauditivocategorias($proyecto_id, $reporteregistro_id, $equipoauditivo_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                reportecategoria.id,
                                                reportecategoria.proyecto_id,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id,
                                                        IF(reporteruidoequipoauditivocategorias.reporteruidocategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteruidoequipoauditivocategorias
                                                    WHERE
                                                        reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id = ' . $equipoauditivo_id . ' 
                                                        AND reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "")  AS checked
                                            FROM
                                                reportecategoria
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');


                $filas = '';
                foreach ($categorias as $key => $value) {
                    $filas .= '<div class="col-6">
                                    <div class="form-group">
                                        <div class="switch" style="float: left;">
                                            <label>
                                                <input type="checkbox" name="equipoauditivo_categoria[]" value="' . $value->id . '" ' . $value->checked . '>
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>
                                        <label class="demo-switch-title" style="float: left; font-size: 12px;">' . $value->reportecategoria_nombre . '</label>
                                    </div>
                                </div>';
                }
            } else {
                $where_condicion = '';
                if (($reporteregistro_id + 0) > 0) {
                    $where_condicion = 'AND reporteruidocategoria.registro_id = ' . $reporteregistro_id;
                }


                $categorias = DB::select('SELECT
                                                reporteruidocategoria.id,
                                                reporteruidocategoria.proyecto_id,
                                                reporteruidocategoria.registro_id,
                                                reporteruidocategoria.reporteruidocategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        -- reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id,
                                                        IF(reporteruidoequipoauditivocategorias.reporteruidocategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteruidoequipoauditivocategorias
                                                    WHERE
                                                        reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id = ' . $equipoauditivo_id . ' 
                                                        AND reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reporteruidocategoria.id
                                                    LIMIT 1
                                                ), "")  AS checked
                                            FROM
                                                reporteruidocategoria
                                            WHERE
                                                reporteruidocategoria.proyecto_id = ' . $proyecto_id . ' 
                                                ' . $where_condicion . ' 
                                            ORDER BY
                                                reporteruidocategoria.reporteruidocategoria_nombre ASC');


                $filas = '';
                foreach ($categorias as $key => $value) {
                    $filas .= '<div class="col-6">
                                    <div class="form-group">
                                        <div class="switch" style="float: left;">
                                            <label>
                                                <input type="checkbox" name="equipoauditivo_categoria[]" value="' . $value->id . '" ' . $value->checked . '>
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>
                                        <label class="demo-switch-title" style="float: left; font-size: 12px;">' . $value->reporteruidocategoria_nombre . '</label>
                                    </div>
                                </div>';
                }
            }

            // respuesta
            $dato['equipoauditivocategorias_lista'] = $filas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['equipoauditivocategorias_lista'] = 'Error al consultar las categorías';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $equipoauditivo_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoequipoauditivoeliminar($equipoauditivo_id)
    {
        try {
            $equipoauditivo = reporteruidoequipoauditivoModel::where('id', $equipoauditivo_id)->delete();
            $equipoauditivoatenuaciones = reporteruidoequipoauditivoatenuacionModel::where('reporteruidoequipoauditivo_id', $equipoauditivo_id)->delete();

            // respuesta
            $dato["msj"] = 'Equipo auditivo eliminado correctamente';
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
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoepptabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            $epp = reporteruidoeppModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
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


    /**
     * Display the specified resource.
     *
     * @param  int  $epp_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoeppeliminar($epp_id)
    {
        try {
            $epp = reporteruidoeppModel::where('id', $epp_id)->delete();

            // respuesta
            $dato["msj"] = 'E.P.P. eliminado correctamente';
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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoareaevaluaciontabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            if (($areas_poe + 0) == 1) {
                $areasevaluacion = DB::select('SELECT
                                                    reporteruidoareaevaluacion.id,
                                                    reporteruidoareaevaluacion.proyecto_id,
                                                    reporteruidoareaevaluacion.registro_id,
                                                    reporteruidoareaevaluacion.reporteruidoarea_id,
                                                    reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                                    reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                                    reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_noevaluaciones,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion1,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion2,
                                                    IF(reporteruidoareaevaluacion_nomedicion1 = reporteruidoareaevaluacion_nomedicion2, reporteruidoareaevaluacion_nomedicion1, CONCAT(reporteruidoareaevaluacion_nomedicion1, " al ", reporteruidoareaevaluacion_nomedicion2)) AS nomedicion,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_ubicacion 
                                                FROM
                                                    reporteruidoareaevaluacion
                                                    LEFT JOIN reportearea ON reporteruidoareaevaluacion.reporteruidoarea_id = reportearea.id
                                                WHERE
                                                    reporteruidoareaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidoareaevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reportearea.reporteruidoarea_porcientooperacion > 0
                                                ORDER BY
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion1 ASC,
                                                    reportearea.reportearea_orden ASC');
            } else {
                $areasevaluacion = DB::select('SELECT
                                                    reporteruidoareaevaluacion.id,
                                                    reporteruidoareaevaluacion.proyecto_id,
                                                    reporteruidoareaevaluacion.registro_id,
                                                    reporteruidoareaevaluacion.reporteruidoarea_id,
                                                    reporteruidoarea.reporteruidoarea_instalacion,
                                                    reporteruidoarea.reporteruidoarea_numorden,
                                                    reporteruidoarea.reporteruidoarea_nombre,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_noevaluaciones,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion1,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion2,
                                                    IF(reporteruidoareaevaluacion_nomedicion1 = reporteruidoareaevaluacion_nomedicion2, reporteruidoareaevaluacion_nomedicion1, CONCAT(reporteruidoareaevaluacion_nomedicion1, " al ", reporteruidoareaevaluacion_nomedicion2)) AS nomedicion,
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_ubicacion 
                                                FROM
                                                    reporteruidoareaevaluacion
                                                    LEFT JOIN reporteruidoarea ON reporteruidoareaevaluacion.reporteruidoarea_id = reporteruidoarea.id
                                                WHERE
                                                    reporteruidoareaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidoareaevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reporteruidoarea.reporteruidoarea_porcientooperacion > 0 
                                                ORDER BY
                                                    reporteruidoareaevaluacion.reporteruidoareaevaluacion_nomedicion1 ASC,
                                                    reporteruidoarea.reporteruidoarea_numorden ASC');
            }


            $numero_registro = 0;
            $dato['ubicaciones_opciones'] = '<option value=""></option>';


            foreach ($areasevaluacion as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';


                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }


                //----------------------------------------


                $dato['ubicaciones_opciones'] .= '<option value="' . $value->reporteruidoareaevaluacion_ubicacion . '">Punto [' . $value->nomedicion . '] ' . $value->reporteruidoareaevaluacion_ubicacion . '</option>';
            }


            //===========================================


            $dato["areaevaluacion_totalpuntos"] = 0;


            $puntosevaluacion = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                SUM(TABLA.reporteruidoareaevaluacion_noevaluaciones) AS total
                                            FROM
                                                (
                                                    SELECT
                                                        reporteruidoareaevaluacion.proyecto_id,
                                                        reporteruidoareaevaluacion.registro_id,
                                                        reporteruidoareaevaluacion.reporteruidoarea_id,
                                                        reporteruidoareaevaluacion.reporteruidoareaevaluacion_noevaluaciones
                                                    FROM
                                                        reporteruidoareaevaluacion
                                                    WHERE
                                                        reporteruidoareaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteruidoareaevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                    GROUP BY
                                                        reporteruidoareaevaluacion.proyecto_id,
                                                        reporteruidoareaevaluacion.registro_id,
                                                        reporteruidoareaevaluacion.reporteruidoarea_id,
                                                        reporteruidoareaevaluacion.reporteruidoareaevaluacion_noevaluaciones
                                                ) AS TABLA
                                            GROUP BY
                                                TABLA.proyecto_id,
                                                TABLA.registro_id');


            if (count($puntosevaluacion) > 0) {
                $dato["areaevaluacion_totalpuntos"] = ($puntosevaluacion[0]->total + 0);
            }


            // respuesta
            $dato['data'] = $areasevaluacion;
            $dato["total"] = count($areasevaluacion);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["areaevaluacion_totalpuntos"] = 0;
            $dato['ubicaciones_opciones'] = '<option value=""></option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $area_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoareaevaluacioneliminar($proyecto_id, $reporteregistro_id, $area_id)
    {
        try {
            $eliminar_areaevaluacion = reporteruidoareaevaluacionModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
                ->where('reporteruidoarea_id', $area_id)
                ->delete();

            // respuesta
            $dato["msj"] = 'Área de evaluación eliminada correctamente';
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
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidonivelsonorotabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            $nivelsonoro = collect(DB::select('SELECT
                                                    reporteruidonivelsonoro.id,
                                                    reporteruidonivelsonoro.proyecto_id,
                                                    reporteruidonivelsonoro.registro_id,
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_punto,
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_ubicacion,
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_promedio,
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_totalperiodos,
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_totalresultados,
                                                    IFNULL(reporteruidonivelsonoro.reporteruidonivelsonoro_periodo1, "N/A") AS reporteruidonivelsonoro_periodo1,
                                                    IFNULL(reporteruidonivelsonoro.reporteruidonivelsonoro_periodo2, "N/A") AS reporteruidonivelsonoro_periodo2,
                                                    IFNULL(reporteruidonivelsonoro.reporteruidonivelsonoro_periodo3, "N/A") AS reporteruidonivelsonoro_periodo3,
                                                    IFNULL(reporteruidonivelsonoro.reporteruidonivelsonoro_periodo4, "N/A") AS reporteruidonivelsonoro_periodo4,
                                                    IFNULL(reporteruidonivelsonoro.reporteruidonivelsonoro_periodo5, "N/A") AS reporteruidonivelsonoro_periodo5,
                                                    reporteruidonivelsonoro.created_at,
                                                    reporteruidonivelsonoro.updated_at 
                                                FROM
                                                    reporteruidonivelsonoro
                                                WHERE
                                                    reporteruidonivelsonoro.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidonivelsonoro.registro_id = ' . $reporteregistro_id . ' 
                                                ORDER BY
                                                    reporteruidonivelsonoro.reporteruidonivelsonoro_punto ASC,
                                                    reporteruidonivelsonoro.id ASC'));

            $nivelsonoro_tabla = '';

            if (count($nivelsonoro) > 0) {
                $nivelsonoro_tabla .= '<thead>
                                            <tr>
                                                <th width="100">No. Medición</th>
                                                <th>Ubicación</th>';

                // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 1)
                // {
                //     $nivelsonoro_tabla .= '<th>Periodo 1<br>(dB)</th>';
                // }

                // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 2)
                // {
                //     $nivelsonoro_tabla .= '<th>Periodo 2<br>(dB)</th>';
                // }

                // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 3)
                // {
                //     $nivelsonoro_tabla .= '<th>Periodo 3<br>(dB)</th>';
                // }

                // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 4)
                // {
                //     $nivelsonoro_tabla .= '<th>Periodo 4<br>(dB)</th>';
                // }

                // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 5)
                // {
                //     $nivelsonoro_tabla .= '<th>Periodo 5<br>(dB)</th>';
                // }

                $nivelsonoro_tabla .= '<th>Periodo 1<br>(dB)</th>';
                $nivelsonoro_tabla .= '<th>Periodo 2<br>(dB)</th>';
                $nivelsonoro_tabla .= '<th>Periodo 3<br>(dB)</th>';
                $nivelsonoro_tabla .= '<th>Periodo 4<br>(dB)</th>';
                $nivelsonoro_tabla .= '<th>Periodo 5<br>(dB)</th>';

                $nivelsonoro_tabla .= '<th width="100">NSCE<sub>A, Ti</sub><br>Promedio (dB)</th>
                                                <th width="60">Editar</th>
                                                <th width="60">Eliminar</th>
                                            </tr>
                                        </thead>';

                $nivelsonoro_tabla .= '<tbody>';
                foreach ($nivelsonoro as $key => $value) {
                    $nivelsonoro_tabla .= '<tr>';

                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_punto . '</td>';
                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_ubicacion . '</td>';

                    // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 1)
                    // {
                    //     $nivelsonoro_tabla .= '<td>'.$value->reporteruidonivelsonoro_periodo1.'</td>';
                    // }

                    // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 2)
                    // {
                    //     $nivelsonoro_tabla .= '<td>'.$value->reporteruidonivelsonoro_periodo2.'</td>';
                    // }

                    // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 3)
                    // {
                    //     $nivelsonoro_tabla .= '<td>'.$value->reporteruidonivelsonoro_periodo3.'</td>';
                    // }

                    // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 4)
                    // {
                    //     $nivelsonoro_tabla .= '<td>'.$value->reporteruidonivelsonoro_periodo4.'</td>';
                    // }

                    // if (($nivelsonoro[0]->reporteruidonivelsonoro_totalperiodos + 0) >= 5)
                    // {
                    //     $nivelsonoro_tabla .= '<td>'.$value->reporteruidonivelsonoro_periodo5.'</td>';
                    // }

                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_periodo1 . '</td>';
                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_periodo2 . '</td>';
                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_periodo3 . '</td>';
                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_periodo4 . '</td>';
                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_periodo5 . '</td>';


                    $nivelsonoro_tabla .= '<td>' . $value->reporteruidonivelsonoro_promedio . '</td>';
                    $nivelsonoro_tabla .= '<td><button type="button" class="btn btn-warning waves-effect btn-circle" onclick="nivelsonoro_editar(' . $proyecto_id . ', ' . $reporteregistro_id . ', ' . $value->reporteruidonivelsonoro_punto . ');"><i class="fa fa-pencil fa-1x"></i></button></td>';

                    if ($edicion == 1) {
                        $nivelsonoro_tabla .= '<td><button type="button" class="btn btn-danger waves-effect btn-circle" onclick="nivelsonoro_eliminar(' . $proyecto_id . ', ' . $reporteregistro_id . ', ' . $value->reporteruidonivelsonoro_punto . ');"><i class="fa fa-trash fa-1x"></i></button></td>';
                    } else {
                        $nivelsonoro_tabla .= '<td><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>';
                    }

                    $nivelsonoro_tabla .= '</tr>';
                }
                $nivelsonoro_tabla .= '</tbody>';
            } else {
                $nivelsonoro_tabla .= '<thead>
                                            <tr>
                                                <th width="100">No. Medición</th>
                                                <th>Ubicación</th>
                                                <th width="100">Periodo 1<br>(dB)</th>
                                                <th width="100">Periodo 2<br>(dB)</th>
                                                <th width="100">Periodo 3<br>(dB)</th>
                                                <th width="100">Periodo 4<br>(dB)</th>
                                                <th width="100">Periodo 5<br>(dB)</th>
                                                <th width="100">NSCE<sub>A, Ti</sub><br>Promedio</th>
                                            </tr>
                                        </thead>';

                $nivelsonoro_tabla .= '<tbody>
                                            <tr>
                                                <td colspan="8">No hay datos que mostrar</td>
                                            </tr>
                                        </tbody>';
            }

            // respuesta
            $dato['nivelsonoro_tabla'] = $nivelsonoro_tabla;
            $dato["total"] = count($nivelsonoro);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['nivelsonoro_tabla'] = '<thead>
                                                <tr>
                                                    <th width="100">No. Medición</th>
                                                    <th>Ubicación</th>
                                                    <th width="100">Periodo 1<br>(dB)</th>
                                                    <th width="100">Periodo 2<br>(dB)</th>
                                                    <th width="100">Periodo 3<br>(dB)</th>
                                                    <th width="100">Periodo 4<br>(dB)</th>
                                                    <th width="100">Periodo 5<br>(dB)</th>
                                                    <th width="100">NSCE<sub>A, Ti</sub><br>Promedio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="8">Error al consultar los datos</td>
                                                </tr>
                                            </tbody>';
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $nivelsonoro_punto
     * @return \Illuminate\Http\Response
     */
    public function reporteruidonivelsonoroconsultapunto($proyecto_id, $reporteregistro_id, $nivelsonoro_punto)
    {
        try {
            $nivelsonoro_punto = collect(DB::select('SELECT
                                                        reporteruidonivelsonoro.id,
                                                        reporteruidonivelsonoro.proyecto_id,
                                                        reporteruidonivelsonoro.registro_id,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_punto,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_ubicacion,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_promedio,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_totalperiodos,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_totalresultados,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_periodo1,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_periodo2,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_periodo3,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_periodo4,
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_periodo5,
                                                        reporteruidonivelsonoro.created_at,
                                                        reporteruidonivelsonoro.updated_at 
                                                    FROM
                                                        reporteruidonivelsonoro
                                                    WHERE
                                                        reporteruidonivelsonoro.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteruidonivelsonoro.registro_id = ' . $reporteregistro_id . ' 
                                                        AND reporteruidonivelsonoro.reporteruidonivelsonoro_punto = ' . $nivelsonoro_punto . ' 
                                                    ORDER BY
                                                        reporteruidonivelsonoro.reporteruidonivelsonoro_punto ASC,
                                                        reporteruidonivelsonoro.id ASC'));

            $nivelsonororesultados_tablafilas = '';
            foreach ($nivelsonoro_punto as $key => $value) {
                if ($key == 0) {
                    $dato['nivelsonororesultados'] = array(
                        $value->reporteruidonivelsonoro_punto,
                        $value->reporteruidonivelsonoro_ubicacion,
                        $value->reporteruidonivelsonoro_promedio,
                        $value->reporteruidonivelsonoro_totalperiodos,
                        $value->reporteruidonivelsonoro_totalresultados
                    );
                }

                if ($value->reporteruidonivelsonoro_totalperiodos >= 1) {
                    $periodo1_estado = 'required';
                } else {
                    $periodo1_estado = 'disabled';
                }
                if ($value->reporteruidonivelsonoro_totalperiodos >= 2) {
                    $periodo2_estado = 'required';
                } else {
                    $periodo2_estado = 'disabled';
                }
                if ($value->reporteruidonivelsonoro_totalperiodos >= 3) {
                    $periodo3_estado = 'required';
                } else {
                    $periodo3_estado = 'disabled';
                }
                if ($value->reporteruidonivelsonoro_totalperiodos >= 4) {
                    $periodo4_estado = 'required';
                } else {
                    $periodo4_estado = 'disabled';
                }
                if ($value->reporteruidonivelsonoro_totalperiodos >= 5) {
                    $periodo5_estado = 'required';
                } else {
                    $periodo5_estado = 'disabled';
                }

                $nivelsonororesultados_tablafilas .= '<tr>
                                                            <td width="60">' . ($key + 1) . '</td>
                                                            <td><input type="number" step="any" min="0" class="form-control nivel_sonoro_campo" name="reporteruidonivelsonoro_periodo1[]" value="' . $value->reporteruidonivelsonoro_periodo1 . '" ' . $periodo1_estado . '></td>
                                                            <td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo2[]" value="' . $value->reporteruidonivelsonoro_periodo2 . '" ' . $periodo2_estado . '></td>
                                                            <td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo3[]" value="' . $value->reporteruidonivelsonoro_periodo3 . '" ' . $periodo3_estado . '></td>
                                                            <td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo4[]" value="' . $value->reporteruidonivelsonoro_periodo4 . '" ' . $periodo4_estado . '></td>
                                                            <td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo5[]" value="' . $value->reporteruidonivelsonoro_periodo5 . '" ' . $periodo5_estado . '></td>
                                                        </tr>';
            }

            // respuesta
            $dato['nivelsonororesultados_tablafilas'] = $nivelsonororesultados_tablafilas;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['nivelsonororesultados'] = 0;
            $dato['nivelsonororesultados_tablafilas'] = '<tr>
                                                            <td colspan="6">Error al consultar los datos</td>
                                                        </tr>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $nivelsonoro_punto
     * @return \Illuminate\Http\Response
     */
    public function reporteruidonivelsonoroeliminar($proyecto_id, $reporteregistro_id, $nivelsonoro_punto)
    {
        try {
            $eliminar_nivelsonoro = reporteruidonivelsonoroModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
                ->where('reporteruidonivelsonoro_punto', $nivelsonoro_punto)
                ->delete();

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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidopuntonertabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            if (($areas_poe + 0) == 1) {
                $puntoner = DB::select('SELECT
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id,
                                            reporteruidopuntoner.id,
                                            reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                            reporteruidopuntoner.reporteruidoarea_id,
                                            reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                            reportearea.reportearea_orden AS reporteruidoarea_orden,
                                            reporteruidopuntoner.reporteruidopuntoner_punto,
                                            reporteruidopuntoner.reporteruidopuntoner_ubicacion,
                                            reporteruidopuntoner.reporteruidopuntoner_identificacion,
                                            reporteruidopuntoner.reporteruidopuntoner_tmpe,
                                            reporteruidopuntoner.reporteruidopuntoner_ner,
                                            reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                            reporteruidopuntoner.reporteruidopuntoner_RdB 
                                        FROM
                                            reporteruidopuntoner
                                            LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id
                                        WHERE
                                            reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidopuntoner.reporteruidopuntoner_punto ASC');
            } else {
                // $puntoner = reporteruidopuntonerModel::with(['reporteruidoarea'])
                //                                     ->where('proyecto_id', $proyecto_id)
                //                                     ->where('registro_id', $reporteregistro_id)
                //                                     ->orderBy('reporteruidopuntoner_punto', 'ASC')
                //                                     ->get();


                $puntoner = DB::select('SELECT
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id,
                                            reporteruidopuntoner.id,
                                            reporteruidoarea.reporteruidoarea_instalacion,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidoarea.reporteruidoarea_numorden,
                                            reporteruidopuntoner.reporteruidoarea_id,
                                            reporteruidopuntoner.reporteruidopuntoner_punto,
                                            reporteruidopuntoner.reporteruidopuntoner_ubicacion,
                                            reporteruidopuntoner.reporteruidopuntoner_identificacion,
                                            reporteruidopuntoner.reporteruidopuntoner_tmpe,
                                            reporteruidopuntoner.reporteruidopuntoner_ner,
                                            reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                            reporteruidopuntoner.reporteruidopuntoner_RdB 
                                        FROM
                                            reporteruidopuntoner
                                            LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id 
                                        WHERE
                                            reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidopuntoner.reporteruidopuntoner_punto ASC');
            }


            $numero_registro = 0;
            foreach ($puntoner as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if ($value->reporteruidopuntoner_ner <= $value->reporteruidopuntoner_lmpe) {
                    $value->resultadoner = 1;
                    $value->resultadoner_texto = 'Dentro de norma';
                    $value->resultadoner_color = '#00FF00';
                } else {
                    $value->resultadoner = 0;
                    $value->resultadoner_texto = 'Fuera de norma';
                    $value->resultadoner_color = '#FF0000';
                }

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $puntoner;
            $dato["total"] = count($puntoner);
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
     * @param int $reporteregistro_id
     * @param int $area_id
     * @param int $puntoner_id
     * @param int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidopuntonerareacategorias($proyecto_id, $reporteregistro_id, $area_id, $puntoner_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $areacategorias = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.reporteruidocategoria_id,
                                                    TABLA.reportecategoria_orden,
                                                    TABLA.reporteruidocategoria_nombre,
                                                    TABLA.checked
                                                FROM
                                                (
                                                    SELECT
                                                        reportecategoria.proyecto_id,
                                                        reporteareacategoria.reportearea_id,
                                                        reportecategoria.id AS reporteruidocategoria_id,
                                                        reportecategoria.reportecategoria_orden,
                                                        reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                                        IFNULL((
                                                            SELECT
                                                                IF(reporteruidoareacategoria.reporteruidocategoria_id, "checked", "")
                                                            FROM
                                                                reporteruidoareacategoria
                                                            WHERE
                                                                reporteruidoareacategoria.reporteruidoarea_id = reporteareacategoria.reportearea_id
                                                                AND reporteruidoareacategoria.reporteruidocategoria_id = reportecategoria.id
                                                                AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
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
                                                        reportecategoria.reportecategoria_nombre ASC
                                                ) AS TABLA
                                            WHERE
                                                TABLA.checked != ""');
            } else {
                $areacategorias = DB::select('SELECT
                                                    reporteruidoareacategoria.reporteruidoarea_id,
                                                    reporteruidoareacategoria.reporteruidocategoria_id,
                                                    reporteruidocategoria.reporteruidocategoria_nombre
                                                FROM
                                                    reporteruidoareacategoria
                                                    LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id
                                                WHERE
                                                    reporteruidoareacategoria.reporteruidoarea_id = ' . $area_id . ' 
                                                    AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                                ORDER BY
                                                    reporteruidocategoria.reporteruidocategoria_nombre ASC');
            }


            $selectareacategorias_opciones = '<option value=""></option>';
            if (count($areacategorias) > 0) {
                foreach ($areacategorias as $key => $value) {
                    $selectareacategorias_opciones .= '<option value="' . $value->reporteruidocategoria_id . '">' . $value->reporteruidocategoria_nombre . '</option>';
                }
            }


            //====================================================


            $categoriaspuntoner = DB::select('SELECT
                                                    reporteruidopuntonercategorias.reporteruidopuntoner_id,
                                                    reporteruidopuntonercategorias.reporteruidocategoria_id,
                                                    IFNULL(reporteruidopuntonercategorias.reporteruidopuntonercategorias_total, "") AS reporteruidopuntonercategorias_total,
                                                    IFNULL(reporteruidopuntonercategorias.reporteruidopuntonercategorias_geo, "") AS reporteruidopuntonercategorias_geo,
                                                    IFNULL(reporteruidopuntonercategorias.reporteruidopuntonercategorias_ficha, "") AS reporteruidopuntonercategorias_ficha,
                                                    IFNULL(reporteruidopuntonercategorias.reporteruidopuntonercategorias_nombre, "") AS reporteruidopuntonercategorias_nombre 
                                                FROM
                                                    reporteruidopuntonercategorias
                                                WHERE
                                                    reporteruidopuntonercategorias.reporteruidopuntoner_id = ' . $puntoner_id);


            $categorias_puntoner = '';
            if (count($categoriaspuntoner) > 0) {
                foreach ($categoriaspuntoner as $key => $value) {
                    $categorias_puntoner .= '<tr>
                                                <td width="319">
                                                    <select class="custom-select form-control" name="reporteruidocategoria_id[]" required>
                                                        <option value=""></option>';
                    foreach ($areacategorias as $key2 => $categoria) {
                        if (($value->reporteruidocategoria_id + 0) == ($categoria->reporteruidocategoria_id + 0)) {
                            $categorias_puntoner .= '<option value="' . $categoria->reporteruidocategoria_id . '" selected>' . $categoria->reporteruidocategoria_nombre . '</option>';
                        } else {
                            $categorias_puntoner .= '<option value="' . $categoria->reporteruidocategoria_id . '">' . $categoria->reporteruidocategoria_nombre . '</option>';
                        }
                    }
                    $categorias_puntoner .= '</select>
                                                </td>
                                                <td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_total[]" value="' . $value->reporteruidopuntonercategorias_total . '" required></td>
                                                <td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_geo[]" value="' . $value->reporteruidopuntonercategorias_geo . '" required></td>
                                                <td width="120"><input type="text" class="form-control" name="reporteruidopuntonercategorias_ficha[]" value="' . $value->reporteruidopuntonercategorias_ficha . '" required></td>
                                                <td><input type="text" class="form-control" name="reporteruidopuntonercategorias_nombre[]" value="' . $value->reporteruidopuntonercategorias_nombre . '" required></td>';
                    if (($key + 0) == 0) {
                        $categorias_puntoner .= '<td width="60"><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>';
                    } else {
                        $categorias_puntoner .= '<td width="60"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>';
                    }
                    $categorias_puntoner .= '</tr>';
                }
            } else {
                $categorias_puntoner .= '<tr>
                                            <td width="319">
                                                <select class="custom-select form-control" name="reporteruidocategoria_id[]" required>
                                                    <option value=""></option>';
                foreach ($areacategorias as $key2 => $categoria) {
                    $categorias_puntoner .= '<option value="' . $categoria->reporteruidocategoria_id . '">' . $categoria->reporteruidocategoria_nombre . '</option>';
                }
                $categorias_puntoner .= '</select>
                                            </td>
                                            <td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_total[]" value="" required></td>
                                            <td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_geo[]" value="" required></td>
                                            <td width="120"><input type="text" class="form-control" name="reporteruidopuntonercategorias_ficha[]" value="" required></td>
                                            <td><input type="text" class="form-control" name="reporteruidopuntonercategorias_nombre[]" value="" required></td>
                                            <td width="60"><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button></td>
                                        </tr>';
            }


            // respuesta
            $dato['categorias_puntoner'] = $categorias_puntoner;
            $dato['selectareacategorias_opciones'] = $selectareacategorias_opciones;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['categorias_puntoner'] = '<tr><td colspan="6">Error al consultar las categorías, intentelo de nuevo.</td></tr>';
            $dato['selectareacategorias_opciones'] = '<option value=""></option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $puntoner_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidopuntonereliminar($puntoner_id)
    {
        try {
            $puntoner = reporteruidopuntonerModel::where('id', $puntoner_id)->delete();

            $puntoner_categorias = reporteruidopuntonercategoriasModel::where('reporteruidopuntoner_id', $puntoner_id)->delete();

            $puntoner_frecuencias = reporteruidopuntonerfrecuenciasModel::where('reporteruidopuntoner_id', $puntoner_id)->delete();

            // respuesta
            $dato["msj"] = 'Punto de la determinación del NER eliminado correctamente';
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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidodosisnertabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            // $reporte = reporteruidoModel::where('id', $reporteregistro_id)->get();


            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteruido_concluido == 1 || $reporte[0]->reporteruido_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


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


            //==========================================


            if (($areas_poe + 0) == 1) {
                $dosisner = DB::select('SELECT
                                            reporteruidodosisner.proyecto_id,
                                            reporteruidodosisner.registro_id,
                                            reporteruidodosisner.id,
                                            reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                            reporteruidodosisner.reporteruidoarea_id,
                                            reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                            reporteruidodosisner.reporteruidocategoria_id,
                                            reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                            reporteruidodosisner.reporteruidodosisner_punto,
                                            reporteruidodosisner.reporteruidodosisner_dosis,
                                            reporteruidodosisner.reporteruidodosisner_ner,
                                            reporteruidodosisner.reporteruidodosisner_lmpe,
                                            reporteruidodosisner.reporteruidodosisner_tmpe 
                                        FROM
                                            reporteruidodosisner
                                            LEFT JOIN reportearea ON reporteruidodosisner.reporteruidoarea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reporteruidodosisner.reporteruidocategoria_id = reportecategoria.id
                                        WHERE
                                            reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidodosisner.reporteruidodosisner_punto ASC');
            } else {
                // $dosisner = reporteruidodosisnerModel::with(['reporteruidoarea', 'reporteruidocategoria'])
                //                                     ->where('proyecto_id', $proyecto_id)
                //                                     ->where('registro_id', $reporteregistro_id)
                //                                     ->orderBy('reporteruidodosisner_punto', 'ASC')
                //                                     ->get();


                $dosisner = DB::select('SELECT
                                            reporteruidodosisner.proyecto_id,
                                            reporteruidodosisner.registro_id,
                                            reporteruidodosisner.id,
                                            reporteruidoarea.reporteruidoarea_instalacion,
                                            reporteruidodosisner.reporteruidoarea_id,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidodosisner.reporteruidocategoria_id,
                                            reporteruidocategoria.reporteruidocategoria_nombre,
                                            reporteruidodosisner.reporteruidodosisner_punto,
                                            reporteruidodosisner.reporteruidodosisner_dosis,
                                            reporteruidodosisner.reporteruidodosisner_ner,
                                            reporteruidodosisner.reporteruidodosisner_lmpe,
                                            reporteruidodosisner.reporteruidodosisner_tmpe 
                                        FROM
                                            reporteruidodosisner
                                            LEFT JOIN reporteruidoarea ON reporteruidodosisner.reporteruidoarea_id = reporteruidoarea.id
                                            LEFT JOIN reporteruidocategoria ON reporteruidodosisner.reporteruidocategoria_id = reporteruidocategoria.id
                                        WHERE
                                            reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidodosisner.reporteruidodosisner_punto ASC');
            }


            $numero_registro = 0;
            foreach ($dosisner as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if ($value->reporteruidodosisner_ner <= $value->reporteruidodosisner_lmpe) {
                    $value->resultadoner = 1;
                    $value->resultadoner_texto = 'Dentro de norma';
                    $value->resultadoner_color = '#00FF00';
                } else {
                    $value->resultadoner = 0;
                    $value->resultadoner_texto = 'Fuera de norma';
                    $value->resultadoner_color = '#FF0000';
                }

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-1x"></i></button>';

                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-1x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $dosisner;
            $dato["total"] = count($dosisner);
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
     * @param int $reporteregistro_id
     * @param int $area_id
     * @param int $categoria_id
     * @param int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidodosisnerareacategorias($proyecto_id, $reporteregistro_id, $area_id, $categoria_id, $areas_poe)
    {
        try {
            // dd($proyecto_id.' - '.$reporteregistro_id.' - '.$area_id.' - '.$categoria_id.' - '.$areas_poe);


            $categoriasoption = '<option value=""></option>';


            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.reporteruidocategoria_id,
                                                TABLA.reportecategoria_orden,
                                                TABLA.reporteruidocategoria_nombre,
                                                TABLA.checked
                                            FROM
                                            (
                                                SELECT
                                                    reportecategoria.proyecto_id,
                                                    reporteareacategoria.reportearea_id,
                                                    reportecategoria.id AS reporteruidocategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteruidoareacategoria.reporteruidocategoria_id, "checked", "")
                                                        FROM
                                                            reporteruidoareacategoria
                                                        WHERE
                                                            reporteruidoareacategoria.reporteruidoarea_id = reporteareacategoria.reportearea_id
                                                            AND reporteruidoareacategoria.reporteruidocategoria_id = reportecategoria.id
                                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
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
                                                    reportecategoria.reportecategoria_nombre ASC
                                            ) AS TABLA
                                        WHERE
                                            TABLA.checked != ""');
            } else {
                $categorias = DB::select('SELECT
                                                reporteruidoareacategoria.reporteruidoarea_id,
                                                reporteruidoareacategoria.reporteruidocategoria_id,
                                                reporteruidocategoria.reporteruidocategoria_nombre 
                                            FROM
                                                reporteruidoareacategoria
                                                LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id
                                            WHERE
                                                reporteruidoareacategoria.reporteruidoarea_id = ' . $area_id . ' 
                                                AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                            ORDER BY
                                                reporteruidocategoria.reporteruidocategoria_nombre ASC');
            }


            foreach ($categorias as $key => $value) {
                if ($categoria_id == $value->reporteruidocategoria_id) {
                    $categoriasoption .= '<option value="' . $value->reporteruidocategoria_id . '" selected>' . $value->reporteruidocategoria_nombre . '</option>';
                } else {
                    $categoriasoption .= '<option value="' . $value->reporteruidocategoria_id . '">' . $value->reporteruidocategoria_nombre . '</option>';
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
     * @param  int $dosisner_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidodosisnereliminar($dosisner_id)
    {
        try {
            $dosisner = reporteruidodosisnerModel::where('id', $dosisner_id)->delete();

            // respuesta
            $dato["msj"] = 'Dosis de determinación del NER eliminado correctamente';
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
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    /*public function reporteruidonivelruidoefectivotabla($proyecto_id, $reporteregistro_id)
    {
        try
        {
            $puntos = DB::select('SELECT
                                        reporteruidopuntoner.id,
                                        reporteruidopuntoner.proyecto_id,
                                        reporteruidopuntoner.registro_id,
                                        reporteruidopuntoner.reporteruidopuntoner_punto,
                                        -- reporteruidopuntoner.reporteruidoarea_id,
                                        reporteruidoarea.reporteruidoarea_nombre,
                                        reporteruidopuntonercategorias.reporteruidocategoria_id,
                                        reporteruidocategoria.reporteruidocategoria_nombre,
                                        -- reporteruidopuntoner.reporteruidopuntoner_ubicacion,
                                        -- reporteruidopuntoner.reporteruidopuntoner_identificacion,
                                        -- reporteruidopuntoner.reporteruidopuntoner_tmpe,
                                        -- reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                        reporteruidopuntoner.reporteruidopuntoner_ner
                                    FROM
                                        reporteruidopuntoner
                                        LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id
                                        RIGHT JOIN reporteruidopuntonercategorias ON reporteruidopuntoner.id = reporteruidopuntonercategorias.reporteruidopuntoner_id
                                        LEFT JOIN reporteruidocategoria ON reporteruidopuntonercategorias.reporteruidocategoria_id = reporteruidocategoria.id
                                    WHERE
                                        reporteruidopuntoner.proyecto_id = '.$proyecto_id.'  
                                        AND reporteruidopuntoner.registro_id = '.$reporteregistro_id.' 
                                    ORDER BY
                                        reporteruidopuntoner.reporteruidopuntoner_punto ASC,
                                        reporteruidocategoria.reporteruidocategoria_nombre ASC');

            $t = '';

            if (count($puntos) > 0)
            {
                $equiposauditivos = DB::select('SELECT
                                                    reporteruidoequipoauditivo.id,
                                                    reporteruidoequipoauditivo.proyecto_id,
                                                    reporteruidoequipoauditivo.registro_id,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR 
                                                FROM
                                                    reporteruidoequipoauditivo
                                                WHERE
                                                    reporteruidoequipoauditivo.proyecto_id = '.$proyecto_id.' 
                                                    AND reporteruidoequipoauditivo.registro_id = '.$reporteregistro_id.'
                                                ORDER BY
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                                    reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');

                $t .= '<thead>
                            <tr>
                                <th width="70">No.<br>medición</th>
                                <th>Área</th>
                                <th>Puesto</th>
                                <th width="70">NER<br>dB(A)</th>';
                         
                                foreach ($equiposauditivos as $key => $equipo)
                                {
                                    $t .= '<th width="130">R dB (A)<br>'.$equipo->reporteruidoequipoauditivo_tipo.'<br>Marca: '.$equipo->reporteruidoequipoauditivo_marca.'<br>Modelo: '.$equipo->reporteruidoequipoauditivo_modelo.'</th>
                                            <th width="70">NRE<br>dB(A)</th>';
                                }

                    $t .= '</tr>
                        </thead>
                        <tbody>';

                        foreach ($puntos as $key => $punto)
                        {
                            $t .= '<tr>
                                        <td>'.$punto->reporteruidopuntoner_punto.'</td>
                                        <td>'.$punto->reporteruidoarea_nombre.'</td>
                                        <td>'.$punto->reporteruidocategoria_nombre.'</td>
                                        <td>'.$punto->reporteruidopuntoner_ner.'</td>';

                                        foreach ($equiposauditivos as $key => $equipo)
                                        {
                                            $equipocategoria = DB::select('SELECT  
                                                                                COUNT(reporteruidoequipoauditivocategorias.reporteruidocategoria_id) AS total
                                                                            FROM
                                                                                reporteruidoequipoauditivocategorias 
                                                                            WHERE
                                                                                reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id = '.$equipo->id.' 
                                                                                AND reporteruidoequipoauditivocategorias.reporteruidocategoria_id = '.$punto->reporteruidocategoria_id.' 
                                                                            LIMIT 1');

                                            if (($equipocategoria[0]->total + 0) > 0)
                                            {
                                                $t .= '<td>'.round(((($equipo->reporteruidoequipoauditivo_NRR+0) -7) / 2), 1).'</td>
                                                        <td>'.round(($punto->reporteruidopuntoner_ner+0) - ((($equipo->reporteruidoequipoauditivo_NRR+0) -7) / 2), 1).'</td>';
                                            }
                                            else
                                            {
                                                $t .= '<td>&nbsp;</td>
                                                        <td>&nbsp;</td>';
                                            }
                                        }

                            $t .= '</tr>';
                        }

                $t .= '</tbody>';
            }
            else
            {
                $t = '<thead>
                            <tr>
                                <th width="70">No.<br>medición</th>
                                <th>Área</th>
                                <th>Puesto</th>
                                <th width="70">NER<br>dB(A)</th>
                                <th width="70">NRE<br>dB(A)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">No hay datos que mostrar</th>
                            </tr>
                        </tbody>';
            }


            // respuesta
            $dato["tabla_7_5"] = $t;
            $dato["total"] = count($puntos);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['tabla_7_5'] = '<thead>
                                        <tr>
                                            <th width="70">No.<br>medición</th>
                                            <th>Área</th>
                                            <th>Puesto</th>
                                            <th width="70">NER<br>dB(A)</th>
                                            <th width="70">NRE<br>dB(A)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5">Error al consultar los datos</th>
                                        </tr>
                                    </tbody>';
            $dato["total"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }*/


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidobandasoctavatabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $puntos_bandasoctava = DB::select('SELECT
                                                        reporteruidopuntoner.id,
                                                        reporteruidopuntoner.proyecto_id,
                                                        reporteruidopuntoner.registro_id,
                                                        reporteruidopuntoner.reporteruidopuntoner_punto,
                                                        reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                                        reporteruidopuntoner.reporteruidopuntoner_ubicacion,
                                                        reporteruidopuntoner.reporteruidopuntoner_identificacion,
                                                        reporteruidopuntoner.reporteruidopuntoner_tmpe,
                                                        reporteruidopuntoner.reporteruidopuntoner_ner,
                                                        reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                                        IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, "") AS reporteruidopuntoner_RdB,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_orden,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_frecuencia,
                                                        IFNULL(reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_nivel, "") AS reporteruidopuntonerfrecuencias_nivel,
                                                        IFNULL(reporteruidopuntoner.reporteruidopuntoner_NRE, "") AS resultado
                                                        -- ROUND((reporteruidopuntoner.reporteruidopuntoner_ner - IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, 0)), 1) AS resultado
                                                    FROM
                                                        reporteruidopuntoner
                                                        LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id 
                                                        RIGHT JOIN reporteruidopuntonerfrecuencias ON reporteruidopuntoner.id = reporteruidopuntonerfrecuencias.reporteruidopuntoner_id
                                                    WHERE
                                                        reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                                        AND reportearea.created_at >= "2021-06-01"
                                                    ORDER BY
                                                        reporteruidopuntoner.reporteruidopuntoner_punto ASC,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_orden ASC');
            } else {
                $puntos_bandasoctava = DB::select('SELECT
                                                        reporteruidopuntoner.id,
                                                        reporteruidopuntoner.proyecto_id,
                                                        reporteruidopuntoner.registro_id,
                                                        reporteruidopuntoner.reporteruidopuntoner_punto,
                                                        reporteruidoarea.reporteruidoarea_nombre,
                                                        reporteruidopuntoner.reporteruidopuntoner_ubicacion,
                                                        reporteruidopuntoner.reporteruidopuntoner_identificacion,
                                                        reporteruidopuntoner.reporteruidopuntoner_tmpe,
                                                        reporteruidopuntoner.reporteruidopuntoner_ner,
                                                        reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                                        IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, "") AS reporteruidopuntoner_RdB,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_orden,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_frecuencia,
                                                        IFNULL(reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_nivel, "") AS reporteruidopuntonerfrecuencias_nivel,
                                                        IFNULL(reporteruidopuntoner.reporteruidopuntoner_NRE, "") AS resultado
                                                        --ROUND((reporteruidopuntoner.reporteruidopuntoner_ner - IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, 0)), 1) AS resultado
                                                    FROM
                                                        reporteruidopuntoner
                                                        LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id 
                                                        RIGHT JOIN reporteruidopuntonerfrecuencias ON reporteruidopuntoner.id = reporteruidopuntonerfrecuencias.reporteruidopuntoner_id
                                                    WHERE
                                                        reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                                        AND reporteruidoarea.created_at <= "2021-05-31"
                                                    ORDER BY
                                                        reporteruidopuntoner.reporteruidopuntoner_punto ASC,
                                                        reporteruidopuntonerfrecuencias.reporteruidopuntonerfrecuencias_orden ASC');
            }





            $total_singuardar = DB::select('SELECT
                                                COUNT(IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, 1)) total
                                            FROM
                                                reporteruidopuntoner
                                            WHERE
                                                reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . '
                                                AND IFNULL(reporteruidopuntoner.reporteruidopuntoner_RdB, "") = ""');


            $total = 1;
            if (($total_singuardar[0]->total + 0) > 0) {
                $total = 0;
            }


            // respuesta
            $dato["data"] = $puntos_bandasoctava;
            // $dato["total"] = $total; //DESCOMENTAR DESPUES DE CORREGIR TODO
            $dato["total"] = 1;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["data"] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidomatrizexposicion($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            $numero_registro = 0;


            if (($areas_poe + 0) == 1) {
                $puntos = DB::select('SELECT
                                            reporteruidopuntoner.id,
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id,
                                            -- catregion.catregion_nombre,
                                            -- catsubdireccion.catsubdireccion_nombre,
                                            -- catgerencia.catgerencia_nombre,
                                            -- catactivo.catactivo_nombre,
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
                                            proyecto.proyecto_clienteinstalacion,
                                            reporteruidopuntoner.reporteruidoarea_id,
                                            reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                            reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                            reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                            reporteruidopuntonercategorias.reporteruidocategoria_id,
                                            reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_total,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_geo,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_nombre,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_ficha,
                                            reporteruidopuntoner.reporteruidopuntoner_punto,
                                            reporteruidopuntoner.reporteruidopuntoner_ner,
                                            reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                            IFNULL((
                                                SELECT
                                                    --  reporteruidodosisner.id,
                                                    --  reporteruidodosisner.proyecto_id,
                                                    --  reporteruidodosisner.registro_id,
                                                    --  reporteruidodosisner.reporteruidoarea_id,
                                                    --  reporteruidodosisner.reporteruidocategoria_id,
                                                    --  reporteruidodosisner.reporteruidodosisner_punto,
                                                    --  reporteruidodosisner.reporteruidodosisner_dosis,
                                                        reporteruidodosisner.reporteruidodosisner_ner
                                                    --  reporteruidodosisner.reporteruidodosisner_lmpe,
                                                    --  reporteruidodosisner.reporteruidodosisner_tmpe 
                                                FROM
                                                    reporteruidodosisner
                                                WHERE
                                                    reporteruidodosisner.proyecto_id = reporteruidopuntoner.proyecto_id
                                                    AND reporteruidodosisner.registro_id = reporteruidopuntoner.registro_id
                                                    AND reporteruidodosisner.reporteruidoarea_id = reporteruidopuntoner.reporteruidoarea_id
                                                    AND reporteruidodosisner.reporteruidocategoria_id = reporteruidopuntonercategorias.reporteruidocategoria_id
                                                LIMIT 1
                                            ), "N/A") AS dosimentria 
                                        FROM
                                            reporteruidopuntoner
                                            LEFT JOIN proyecto ON reporteruidopuntoner.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id
                                            RIGHT OUTER JOIN reporteruidopuntonercategorias ON reporteruidopuntoner.id = reporteruidopuntonercategorias.reporteruidopuntoner_id
                                            LEFT JOIN reportecategoria ON reporteruidopuntonercategorias.reporteruidocategoria_id = reportecategoria.id 
                                        WHERE
                                            reporteruidopuntoner.proyecto_id = ' . $proyecto_id . '  
                                            AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . '  
                                        ORDER BY
                                            reporteruidopuntoner.reporteruidopuntoner_punto ASC,
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC');
            } else {
                $puntos = DB::select('SELECT
                                            reporteruidopuntoner.id,
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id,
                                            -- catregion.catregion_nombre,
                                            -- catsubdireccion.catsubdireccion_nombre,
                                            -- catgerencia.catgerencia_nombre,
                                            -- catactivo.catactivo_nombre,
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
                                            proyecto.proyecto_clienteinstalacion,
                                            reporteruidopuntoner.reporteruidoarea_id,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidoarea.reporteruidoarea_numorden,
                                            reporteruidoarea.reporteruidoarea_instalacion,
                                            reporteruidopuntonercategorias.reporteruidocategoria_id,
                                            reporteruidocategoria.reporteruidocategoria_nombre,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_total,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_geo,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_nombre,
                                            reporteruidopuntonercategorias.reporteruidopuntonercategorias_ficha,
                                            reporteruidopuntoner.reporteruidopuntoner_punto,
                                            reporteruidopuntoner.reporteruidopuntoner_ner,
                                            reporteruidopuntoner.reporteruidopuntoner_lmpe,
                                            IFNULL((
                                                SELECT
                                                    --  reporteruidodosisner.id,
                                                    --  reporteruidodosisner.proyecto_id,
                                                    --  reporteruidodosisner.registro_id,
                                                    --  reporteruidodosisner.reporteruidoarea_id,
                                                    --  reporteruidodosisner.reporteruidocategoria_id,
                                                    --  reporteruidodosisner.reporteruidodosisner_punto,
                                                    --  reporteruidodosisner.reporteruidodosisner_dosis,
                                                        reporteruidodosisner.reporteruidodosisner_ner
                                                    --  reporteruidodosisner.reporteruidodosisner_lmpe,
                                                    --  reporteruidodosisner.reporteruidodosisner_tmpe 
                                                FROM
                                                    reporteruidodosisner
                                                WHERE
                                                    reporteruidodosisner.proyecto_id = reporteruidopuntoner.proyecto_id
                                                    AND reporteruidodosisner.registro_id = reporteruidopuntoner.registro_id
                                                    AND reporteruidodosisner.reporteruidoarea_id = reporteruidopuntoner.reporteruidoarea_id
                                                    AND reporteruidodosisner.reporteruidocategoria_id = reporteruidopuntonercategorias.reporteruidocategoria_id
                                                LIMIT 1
                                            ), "N/A") AS dosimentria 
                                        FROM
                                            reporteruidopuntoner
                                            LEFT JOIN proyecto ON reporteruidopuntoner.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id
                                            RIGHT OUTER JOIN reporteruidopuntonercategorias ON reporteruidopuntoner.id = reporteruidopuntonercategorias.reporteruidopuntoner_id
                                            LEFT JOIN reporteruidocategoria ON reporteruidopuntonercategorias.reporteruidocategoria_id = reporteruidocategoria.id 
                                        WHERE
                                            reporteruidopuntoner.proyecto_id = ' . $proyecto_id . '  
                                            AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . '  
                                        ORDER BY
                                            reporteruidopuntoner.reporteruidopuntoner_punto ASC,
                                            reporteruidocategoria.reporteruidocategoria_nombre ASC');
            }


            // $proyecto = proyectoModel::findOrFail($proyecto_id);


            foreach ($puntos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->resultado = $value->reporteruidopuntoner_ner . ' / ' . $value->reporteruidopuntoner_lmpe;


                // if (($proyecto->catregion_id + 0) != 1) //REGION NORTE
                // {
                //     $value->resultado = $value->reporteruidopuntoner_ner.' / '.$value->reporteruidopuntoner_lmpe;
                // }
            }


            // respuesta
            $dato["data"] = $puntos;
            // $dato["total"] = count($puntos);
            $dato["total"] = 1;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["data"] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteruidodashboard($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                //=====================================
                // AREAS CRITICAS

                $areas = DB::select('CALL sp_areas_criticas_ruido_b(?,?, ?)', [$proyecto_id, $reporteregistro_id, 1]);

                // $areas = DB::select('SELECT
                //                         reporteruidopuntoner.proyecto_id AS proyecto,
                //                         reporteruidopuntoner.registro_id AS registro,
                //                         reporteruidopuntoner.reporteruidoarea_id AS area,
                //                         reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                //                         MIN(reporteruidopuntoner_punto) AS puntominimo,
                //                         (
                //                             SELECT
                //                                 REPLACE(GROUP_CONCAT(CONCAT("Punto ", reporteruidopuntoner_punto, " (", reporteruidopuntoner_ner, " dB)")), ",", ", ")
                //                             FROM
                //                                 reporteruidopuntoner
                //                             WHERE
                //                                 reporteruidopuntoner.proyecto_id = proyecto
                //                                 AND reporteruidopuntoner.registro_id = registro
                //                                 AND reporteruidopuntoner.reporteruidoarea_id = area
                //                                 AND reporteruidopuntoner.reporteruidopuntoner_ner > reporteruidopuntoner.reporteruidopuntoner_lmpe
                //                             ORDER BY
                //                                 reporteruidopuntoner.reporteruidopuntoner_punto ASC
                //                         ) AS puntoscriticos
                //                     FROM
                //                         reporteruidopuntoner
                //                         LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id
                //                     WHERE
                //                         reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                //                         AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                //                         AND reporteruidopuntoner.reporteruidopuntoner_ner > reporteruidopuntoner.reporteruidopuntoner_lmpe
                //                     GROUP BY
                //                         reporteruidopuntoner.proyecto_id,
                //                         reporteruidopuntoner.registro_id,
                //                         reporteruidopuntoner.reporteruidoarea_id,
                //                         reportearea.reportearea_nombre
                //                     ORDER BY
                //                         MIN(reporteruidopuntoner_punto) ASC');


                $dashboard_areas = '';
                if (count($areas) > 0) {
                    foreach ($areas as $key => $value) {
                        $dashboard_areas .= '● <b>' . $value->reporteruidoarea_nombre . '</b> ' . $value->puntoscriticos . '.<br>';
                    }
                } else {
                    // AREAS EVALUADAS
                    $areas = DB::select('SELECT
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id,
                                                reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                                reportearea.reportearea_orden AS reporteruidoarea_numorden
                                            FROM
                                                reporteruidopuntoner
                                                LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id
                                            WHERE
                                                reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                            GROUP BY
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden
                                            ORDER BY
                                                reportearea.reportearea_orden ASC');

                    if (count($areas) > 0) {
                        foreach ($areas as $key => $value) {
                            $dashboard_areas .= '● <b>' . $value->reporteruidoarea_nombre . '</b><br>';
                        }
                    } else {
                        $dashboard_areas = 'No se encontraron áreas evaluadas.';
                    }
                }


                //=====================================
                // CATEGORIAS CRITICAS


                $categorias = DB::select('CALL sp_categorias_criticas_ruido_b(?,?,?)', [$proyecto_id, $reporteregistro_id, 1]);


                $dashboard_categorias = '';
                if (count($categorias) > 0) {
                    foreach ($categorias as $key => $value) {
                        $dashboard_categorias .= '● <b>' . $value->reporteruidocategoria_nombre . '</b> ' . $value->puntoscriticos . '.<br>';
                    }
                } else {
                    // CATEGORIAS EVALUADAS
                    $categorias = DB::select('SELECT
                                                    reporteruidodosisner.proyecto_id,
                                                    reporteruidodosisner.registro_id,
                                                    reporteruidodosisner.reporteruidocategoria_id,
                                                    reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre 
                                                FROM
                                                    reporteruidodosisner
                                                    INNER JOIN reportecategoria ON reporteruidodosisner.reporteruidocategoria_id = reportecategoria.id
                                                WHERE
                                                    reporteruidodosisner.proyecto_id = ' . $proyecto_id . '  
                                                    AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                                GROUP BY
                                                    reporteruidodosisner.proyecto_id,
                                                    reporteruidodosisner.registro_id,
                                                    reporteruidodosisner.reporteruidocategoria_id,
                                                    reportecategoria.reportecategoria_nombre
                                                ORDER BY
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC');

                    if (count($categorias) > 0) {
                        foreach ($categorias as $key => $value) {
                            $dashboard_categorias .= '● <b>' . $value->reporteruidocategoria_nombre . '</b><br>';
                        }
                    } else {
                        $dashboard_categorias = 'No se encontraron categorías evaluadas.';
                    }
                }
            } else {
                //=====================================
                // AREAS CRITICAS

                $areas = DB::select('CALL sp_areas_criticas_ruido_b(?,?, ?)', [$proyecto_id, $reporteregistro_id, 2]);

                // $areas = DB::select('SELECT
                //                             reporteruidopuntoner.proyecto_id AS proyecto,
                //                             reporteruidopuntoner.registro_id AS registro,
                //                             reporteruidopuntoner.reporteruidoarea_id AS area,
                //                             reporteruidoarea.reporteruidoarea_nombre,
                //                             MIN(reporteruidopuntoner_punto) AS puntominimo,
                //                             (
                //                                 SELECT
                //                                     REPLACE(GROUP_CONCAT(CONCAT("Punto ", reporteruidopuntoner_punto, " (", reporteruidopuntoner_ner, " dB)")), ",", ", ")
                //                                 FROM
                //                                     reporteruidopuntoner
                //                                 WHERE
                //                                     reporteruidopuntoner.proyecto_id = proyecto
                //                                     AND reporteruidopuntoner.registro_id = registro
                //                                     AND reporteruidopuntoner.reporteruidoarea_id = area
                //                                     AND reporteruidopuntoner.reporteruidopuntoner_ner > reporteruidopuntoner.reporteruidopuntoner_lmpe
                //                                 ORDER BY
                //                                     reporteruidopuntoner.reporteruidopuntoner_punto ASC
                //                             ) AS puntoscriticos
                //                         FROM
                //                             reporteruidopuntoner
                //                             LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id
                //                         WHERE
                //                             reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                //                             AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                //                             AND reporteruidopuntoner.reporteruidopuntoner_ner > reporteruidopuntoner.reporteruidopuntoner_lmpe
                //                         GROUP BY
                //                             reporteruidopuntoner.proyecto_id,
                //                             reporteruidopuntoner.registro_id,
                //                             reporteruidopuntoner.reporteruidoarea_id,
                //                             reporteruidoarea.reporteruidoarea_nombre
                //                         ORDER BY
                //                             MIN(reporteruidopuntoner_punto) ASC');

                $dashboard_areas = '';
                if (count($areas) > 0) {
                    foreach ($areas as $key => $value) {
                        $dashboard_areas .= '● <b>' . $value->reporteruidoarea_nombre . '</b> ' . $value->puntoscriticos . '.<br>';
                    }
                } else {
                    // AREAS EVALUADAS
                    $areas = DB::select('SELECT
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id,
                                                reporteruidoarea.reporteruidoarea_nombre,
                                                reporteruidoarea.reporteruidoarea_numorden 
                                            FROM
                                                reporteruidopuntoner
                                                LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id
                                            WHERE
                                                reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                            GROUP BY
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id,
                                                reporteruidoarea.reporteruidoarea_nombre,
                                                reporteruidoarea.reporteruidoarea_numorden
                                            ORDER BY
                                                reporteruidoarea.reporteruidoarea_numorden ASC');

                    if (count($areas) > 0) {
                        foreach ($areas as $key => $value) {
                            $dashboard_areas .= '● <b>' . $value->reporteruidoarea_nombre . '</b><br>';
                        }
                    } else {
                        $dashboard_areas = 'No se encontraron áreas evaluadas.';
                    }
                }


                //=====================================
                // CATEGORIAS CRITICAS


                $categorias = DB::select('CALL sp_categorias_criticas_ruido_b(?,?,?)', [$proyecto_id, $reporteregistro_id, 2]);



                $dashboard_categorias = '';
                if (count($categorias) > 0) {
                    foreach ($categorias as $key => $value) {
                        $dashboard_categorias .= '● <b>' . $value->reporteruidocategoria_nombre . '</b> ' . $value->puntoscriticos . '.<br>';
                    }
                } else {
                    // CATEGORIAS EVALUADAS
                    $categorias = DB::select('SELECT
                                                    reporteruidodosisner.proyecto_id,
                                                    reporteruidodosisner.registro_id,
                                                    reporteruidodosisner.reporteruidocategoria_id,
                                                    reporteruidocategoria.reporteruidocategoria_nombre 
                                                FROM
                                                    reporteruidodosisner
                                                    INNER JOIN reporteruidocategoria ON reporteruidodosisner.reporteruidocategoria_id = reporteruidocategoria.id
                                                WHERE
                                                    reporteruidodosisner.proyecto_id = ' . $proyecto_id . '  
                                                    AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                                GROUP BY
                                                    reporteruidodosisner.proyecto_id,
                                                    reporteruidodosisner.registro_id,
                                                    reporteruidodosisner.reporteruidocategoria_id,
                                                    reporteruidocategoria.reporteruidocategoria_nombre
                                                ORDER BY
                                                    reporteruidocategoria.reporteruidocategoria_nombre ASC');

                    if (count($categorias) > 0) {
                        foreach ($categorias as $key => $value) {
                            $dashboard_categorias .= '● <b>' . $value->reporteruidocategoria_nombre . '</b><br>';
                        }
                    } else {
                        $dashboard_categorias = 'No se encontraron categorías evaluadas.';
                    }
                }
            }


            //=====================================
            // EQUIPOS AUDITIVOS


            $equipos = DB::select('SELECT
                                        reporteruidoequipoauditivo.proyecto_id,
                                        reporteruidoequipoauditivo.registro_id,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR 
                                    FROM
                                        reporteruidoequipoauditivo
                                    WHERE
                                        reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteruidoequipoauditivo.registro_id = ' . $reporteregistro_id . ' 
                                    ORDER BY
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                        reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');


            $dashboard_equipos = '';
            if (count($equipos) > 0) {
                foreach ($equipos as $key => $value) {
                    $dashboard_equipos .= '● <b>' . $value->reporteruidoequipoauditivo_tipo . '</b> Marca: ' . $value->reporteruidoequipoauditivo_marca . ', Modelo: ' . $value->reporteruidoequipoauditivo_modelo . ', NRR: ' . $value->reporteruidoequipoauditivo_NRR . ' dB.<br>';
                }
            } else {
                $dashboard_equipos = 'No se encontraron equipos auditivos.';
            }


            //=====================================
            // SONOMETRIAS RESULTADOS


            $sonometrias = DB::select('SELECT
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id,
                                            COUNT(reporteruidopuntoner.reporteruidopuntoner_punto) AS totalsonometrias,
                                            SUM(IF(reporteruidopuntoner.reporteruidopuntoner_ner <= reporteruidopuntoner.reporteruidopuntoner_lmpe, 1, 0)) AS dentronorma,
                                            SUM(IF(reporteruidopuntoner.reporteruidopuntoner_ner > reporteruidopuntoner.reporteruidopuntoner_lmpe, 1, 0)) AS fueranorma
                                        FROM
                                            reporteruidopuntoner
                                        WHERE
                                            reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                        GROUP BY
                                            reporteruidopuntoner.proyecto_id,
                                            reporteruidopuntoner.registro_id');


            $dashboard_total_evaluacion = '';
            if (count($sonometrias) > 0) {
                $dashboard_total_evaluacion = $sonometrias[0]->totalsonometrias . ' puntos<br>Sonometría<br><br>';
                $dato["dashboard_sonometria_total_dentronorma"] = $sonometrias[0]->dentronorma;
                $dato["dashboard_sonometria_total_fueranorma"] = $sonometrias[0]->fueranorma;
            } else {
                $dashboard_total_evaluacion = '0 puntos<br>Sonometría<br><br>';
                $dato["dashboard_sonometria_total_dentronorma"] = 0;
                $dato["dashboard_sonometria_total_fueranorma"] = 0;
            }



            //=====================================
            // DOSIMETRIAS RESULTADOS


            $dosimetria = DB::select('SELECT
                                            reporteruidodosisner.proyecto_id,
                                            reporteruidodosisner.registro_id,
                                            COUNT(reporteruidodosisner.reporteruidodosisner_punto) AS totaldosimetrias,
                                            SUM(IF(reporteruidodosisner.reporteruidodosisner_ner <= reporteruidodosisner.reporteruidodosisner_lmpe, 1, 0)) AS dentronorma,
                                            SUM(IF(reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe, 1, 0)) AS fueranorma
                                        FROM
                                            reporteruidodosisner
                                        WHERE
                                            reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidodosisner.registro_id= ' . $reporteregistro_id . ' 
                                        GROUP BY
                                            reporteruidodosisner.proyecto_id,
                                            reporteruidodosisner.registro_id');


            if (count($dosimetria) > 0) {
                $dashboard_total_evaluacion .= $dosimetria[0]->totaldosimetrias . ' puntos<br>Dosimetría';

                $serie_grafico[] = array(
                    'titulo' => "Dentro de norma",
                    'total' => $dosimetria[0]->dentronorma
                );

                $serie_grafico[] = array(
                    'titulo' => "Fuera de norma",
                    'total' => $dosimetria[0]->fueranorma
                );
            } else {
                $dashboard_total_evaluacion .= '0 puntos<br>Dosimetría';

                $serie_grafico[] = array(
                    'titulo' => "Sin evaluar",
                    'total' => 100
                );
            }


            //=====================================
            // RECOMENDACIONES


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
                                                AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
                                                AND reporterecomendaciones.agente_nombre = "Ruido"');



            $dato['dashboard_recomendaciones_total'] = 0;
            if (count($recomendaciones) > 0) {
                $dato['dashboard_recomendaciones_total'] = $recomendaciones[0]->totalrecomendaciones;
            }


            //=====================================


            // respuesta
            $dato["dashboard_areas"] = $dashboard_areas;
            $dato["dashboard_categorias"] = $dashboard_categorias;
            $dato["dashboard_equipos"] = $dashboard_equipos;
            $dato["dashboard_total_evaluacion"] = $dashboard_total_evaluacion;
            $dato['serie_grafico'] = $serie_grafico;

            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["dashboard_areas"] = 'Error al consultar las áreas evaluadas';
            $dato["dashboard_categorias"] = 'Error al consultar las categorías evaluadas';
            $dato["dashboard_equipos"] = 'Error al consultar los equipos auditivos';
            $dato["dashboard_total_evaluacion"] = 'Error al<br>consultar<br>total evaluación';
            $dato['serie_grafico'] = 0;
            $dato['dashboard_recomendaciones_total'] = 0;

            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * use App\modelos\reportes\recursosPortadasInformesModel;
 a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*
    public function reporteruidodashboardgraficas(Request $request)
    {
        try
        {
            // dd($request->all());

            $reporte = reporteruidoModel::findOrFail($request->reporteregistro_id);


            if ($request->grafica_dashboard)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_dashboard); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);

                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$reporte->proyecto_id.'/'.$reporte->agente_nombre.'/'.$reporte->id.'/graficas/grafica_1.jpg'; // AREAS CRITICAS

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
     * @param  int $reporteregistro_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteruidorecomendacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                                        AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . '
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
                                                    AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . '
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
     * @param int $reporteregistro_id
     * @param int $responsabledoc_tipo
     * @param int $responsabledoc_opcion
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reporteruidoModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reporteruido_responsable1documento);
            } else {
                return Storage::download($reporte->reporteruido_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reporteruido_responsable2documento);
            } else {
                return Storage::download($reporte->reporteruido_responsable2documento);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoplanostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                        AND reporteplanoscarpetas.registro_id = ' . $reporteregistro_id . '
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
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoequipoutilizadotabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                            AND proyectoproveedores.catprueba_id = 1 -- Ruido ------------------------------
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');


            $where_condicion = '';
            if (count($proveedor) > 0) {
                // $where_condicion = ' AND proyectoequiposactual.proveedor_id = '.$proveedor[0]->proveedor_id;
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteregistro_id . '"
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteregistro_id . '"
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
                                                AND reporteequiposutilizados.registro_id = "' . $reporteregistro_id . '"
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
                                            <input type="checkbox" class="equipoutilizado_checkbox" name="equipoutilizado_checkbox[]" value="' . $value->equipo_id . '" ' . $value->checked . ' onchange="activa_checkboxcarta(this, ' . $value->equipo_id . ');";>
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
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoanexosresultadostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try {
            $anexos = collect(DB::select('SELECT
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
                                                        AND reporteanexos.registro_id = ' . $reporteregistro_id . '
                                                        AND reporteanexos.reporteanexos_tipo = 1
                                                        AND reporteanexos.reporteanexos_rutaanexo = proyectoevidenciadocumento.proyectoevidenciadocumento_archivo
                                                    LIMIT 1
                                                ), "") AS checked 
                                            FROM
                                                proyectoevidenciadocumento
                                            WHERE
                                                proyectoevidenciadocumento.proyecto_id = ' . $proyecto_id . '
                                                AND proyectoevidenciadocumento.agente_nombre = "' . $agente_nombre . '"'));

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
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reporteruidoanexosacreditacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                                AND reporteanexos.registro_id = ' . $reporteregistro_id . '
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
                                            <input type="hidden" class="form-control" name="anexoacreditacion_nombre_' . $value->id . '" value="' . $value->acreditacion_Entidad . ' ' . $value->acreditacion_Numero . '">
                                            <input type="hidden" class="form-control" name="anexoacreditacion_archivo_' . $value->id . '" value="' . $value->acreditacion_SoportePDF . '">
                                            <input type="checkbox" class="anexoacreditacion_checkbox" name="anexoacreditacion_checkbox[]" value="' . $value->id . '" ' . $value->checked . '>
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
    public function reporteruidorevisionestabla($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 1
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
     * Display the specified resource.
     *
     * @param  int $reporte_id
     * @return \Illuminate\Http\Response
     */
    public function reporteruidorevisionconcluir($reporte_id)
    {
        try {
            // $reporte  = reporteruidoModel::findOrFail($reporte_id);
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


    public function guardarCampolmpe($proyecto_id, $reporte_id, $valor)
    {
        try {

            $reporte  = reporteruidoModel::where('id', $reporte_id)
                ->where('proyecto_id', $proyecto_id)
                ->update(['reporteruido_lmpe' => $valor]);


            $dato["msj"] = 'Datos guardados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
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

            //IMPORTACION DE DATOS POR MEDIO DE EXEL
            if ($request->opcion == 1000) {

                //VARIABLES GLOBALES
                $proyecto_id = $request['proyecto_id'];
                $registro_id = $request['registro_id'];

                try {

                    // Verificar si hay un archivo en la solicitud
                    if ($request->hasFile('excelPuntos')) {

                        // Obtenemos el Excel de los personales
                        $excel = $request->file('excelPuntos');

                        // Cargamos el archivo usando la libreria de PhpSpreadsheet
                        $spreadsheet = IOFactory::load($excel->getPathname());
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray(null, true, true, true);

                        // Eliminar los encabezados dependiendo el tipo de documento
                        if (intval($request['tipoArchivo']) == 1) {
                            $data = array_slice($data, 4);
                        } else if (intval($request['tipoArchivo']) == 2) {
                            $data = array_slice($data, 2);
                        } else if (intval($request['tipoArchivo']) == 3) {
                            $data = array_slice($data, 2);
                        }


                        $datosGenerales = [];
                        foreach ($data as $row) {
                            // Verificar si la fila no está completamente vacía
                            if (!empty(array_filter($row))) {

                                $datosGenerales[] = $row;
                            }
                        }

                        // return response()->json(['msj' => $datosGenerales, "code" => 500]);

                        // ========================================================== DATOS GENERALES ===============================================================
                        // =========================================================================================================================
                        //Puntos totales
                        $totalPuntos = count($datosGenerales);
                        $puntosInsertados = 0;


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

                        //BUCAMOS EL LMPE EN LA TABLA DE REPORTERUIDO
                        $lmpeQuery = DB::select('SELECT reporteruido_lmpe FROM reporteruido WHERE id = ? AND proyecto_id = ?', [$registro_id, $proyecto_id]);
                        $lmpe = $lmpeQuery[0]->reporteruido_lmpe;


                        //BUSCAMOS Y CREAMOS EL ARRAY DE LAS UBICACIONES
                        $ubicacionesQuery = DB::select('SELECT reporteruidoareaevaluacion_nomedicion1 AS num1,
                                                                reporteruidoareaevaluacion_nomedicion2 as num2,
                                                                 reporteruidoareaevaluacion_ubicacion as ubicacion
                                                        FROM reporteruidoareaevaluacion WHERE registro_id = ? AND proyecto_id = ?', [$registro_id, $proyecto_id]);

                        // Crear el arreglo de ubicaciones
                        $ubicaciones = [];
                        foreach ($ubicacionesQuery as $fila) {
                            $num1 = $fila->num1;
                            $num2 = $fila->num2;
                            $ubicacion = $fila->ubicacion;

                            for ($i = $num1; $i <= $num2; $i++) {

                                $ubicaciones[intval($i)] = $ubicacion;
                            }
                        }


                        // =========================================================================================================================
                        // =========================================================================================================================


                        // ====================================================== FUNCIONES ===================================================================
                        // =========================================================================================================================
                        function calculartmpe($valor)
                        {

                            $numero = floatval($valor);
                            $numeroRedondeado = intval(round($numero));

                            // Tabla de correspondencia
                            $tabla = [
                                91 => 6.35,
                                92 => 5.04,
                                93 => 4.00,
                                94 => 3.17,
                                95 => 2.52,
                                96 => 2.00,
                                97 => 1.59,
                                98 => 1.26,
                                99 => 1.00,
                                100 => 0.79,
                                101 => 0.63,
                                102 => 0.50,
                                103 => 0.40,
                                104 => 0.31,
                                105 => 0.25
                            ];

                            if (array_key_exists($numeroRedondeado, $tabla)) {
                                return $tabla[$numeroRedondeado];
                            } else {
                                return 'NA';
                            }
                        }



                        // =========================================================================================================================
                        // =========================================================================================================================


                        // ====================================================== INSERCION DE DATOS ===================================================================
                        // =========================================================================================================================

                        switch (intval($request['tipoArchivo'])) {
                            case 1: // Excel con el formato de puntos de la LMPE

                                DB::statement('ALTER TABLE reporteruidonivelsonoro AUTO_INCREMENT = 1;');

                                foreach ($datosGenerales as $rowData) {
                                    //Columna en donde empiezan los resultados
                                    $columnaInicial = 'E';
                                    $puntosId = [];


                                    //Variables unicas
                                    $numPunto = $rowData['A'];
                                    $promedio = is_numeric($rowData['B']) ? $rowData['B'] : null;
                                    $periodos = is_null($rowData['C']) ? 0 : intval($rowData['C']);
                                    $resultados = is_null($rowData['D']) ? 0 : intval($rowData['D']);
                                    $ubicacion = isset($ubicaciones[$rowData['A']]) ? $ubicaciones[$rowData['A']] : null;

                                    //Recorremos todos los resultados de manera dinamica conforme a los periodos y los resultados de cada punto
                                    for ($i = 1; $i <= $periodos; $i++) {

                                        //Cuando se inserta por primera vez creamos los registros para obtener sus IDs y despues poder actualizarlos
                                        if ($i == 1) {

                                            for ($j = 1; $j <= $resultados; $j++) {

                                                $punto = reporteruidonivelsonoroModel::create([
                                                    'proyecto_id' => $proyecto_id,
                                                    'registro_id' => $registro_id,
                                                    'reporteruidonivelsonoro_punto' => $numPunto,
                                                    'reporteruidonivelsonoro_promedio' => $promedio,
                                                    'reporteruidonivelsonoro_totalperiodos' => $periodos,
                                                    'reporteruidonivelsonoro_totalresultados' => $resultados,
                                                    'reporteruidonivelsonoro_ubicacion' => $ubicacion,
                                                    'reporteruidonivelsonoro_periodo1' => $rowData[$columnaInicial],
                                                ]);


                                                // Guardmos el ID de los insertados en el arreglo usando $j como clave para luego poder obtenerlos y actualizarlos
                                                $puntosId[$j] = $punto->id;


                                                $columnaInicial++;
                                            }

                                            //Una vez creado el areglo donde estan los puntos 
                                        } else {

                                            for ($j = 1; $j <= $resultados; $j++) {


                                                $punto = reporteruidonivelsonoroModel::where('id', $puntosId[$j])
                                                    ->update([
                                                        'proyecto_id' => $proyecto_id,
                                                        'registro_id' => $registro_id,
                                                        'reporteruidonivelsonoro_punto' => $numPunto,
                                                        'reporteruidonivelsonoro_promedio' => $promedio,
                                                        'reporteruidonivelsonoro_totalperiodos' => $periodos,
                                                        'reporteruidonivelsonoro_totalresultados' => $resultados,
                                                        'reporteruidonivelsonoro_ubicacion' => $ubicacion,
                                                        'reporteruidonivelsonoro_periodo' . $i => $rowData[$columnaInicial],
                                                    ]);


                                                $columnaInicial++;
                                            }
                                        }
                                    }


                                    $puntosInsertados++;
                                }

                                break;
                            case 2: // 7.2.- Tabla de resultados de la determinación del NER

                                DB::statement('ALTER TABLE reporteruidopuntoner AUTO_INCREMENT = 1;');


                                //Limpiamos, Validamos y Insertamos todos los datos del Excel
                                foreach ($datosGenerales as $rowData) {

                                    $punto = reporteruidopuntonerModel::create([
                                        'proyecto_id' => $proyecto_id,
                                        'registro_id' => $registro_id,
                                        'reporteruidoarea_id' => isset($IdAreas[$rowData['C']]) ? $IdAreas[$rowData['C']] : null,
                                        'reporteruidopuntoner_punto' => is_null($rowData['A']) ? null : $rowData['A'],
                                        'reporteruidopuntoner_identificacion' => is_null($rowData['D']) ? null : $rowData['D'],
                                        'reporteruidopuntoner_ner' => is_null($rowData['B']) ? null : $rowData['B'],
                                        'reporteruidopuntoner_RdB' => NULL,
                                        'reporteruidopuntoner_lmpe' => $lmpe,
                                        'reporteruidopuntoner_tmpe' => is_null($rowData['B']) ? 'NA' : calculartmpe($rowData['B']),
                                        'reporteruidopuntoner_ubicacion' => $ubicaciones[$rowData['A']],

                                    ]);



                                    $frecuencias_bandasoctava = array('31.5', '63', '125', '250', '500', '1K', '2K', '4K', '8K');
                                    foreach ($frecuencias_bandasoctava as $key => $value) {
                                        $frecuencia = reporteruidopuntonerfrecuenciasModel::create([
                                            'reporteruidopuntoner_id' => $punto->id,
                                            'reporteruidopuntonerfrecuencias_orden' => ($key + 1),
                                            'reporteruidopuntonerfrecuencias_frecuencia' => $value,
                                            'reporteruidopuntonerfrecuencias_nivel' => NULL
                                        ]);
                                    }

                                    $puntosInsertados++;
                                }

                                break;
                            case 3: // Excel con el formato de puntos de la LMPE, la fecha de evaluación y la fecha de entrega

                                DB::statement('ALTER TABLE reporteruidodosisner AUTO_INCREMENT = 1;');

                                //Limpiamos, Validamos y Insertamos todos los datos del Excel
                                foreach ($datosGenerales as $rowData) {


                                    $punto = reporteruidodosisnerModel::create([
                                        'proyecto_id' => $proyecto_id,
                                        'registro_id' => $registro_id,
                                        'reporteruidodosisner_punto' => is_null($rowData['A']) ? null : $rowData['A'],
                                        'reporteruidodosisner_dosis' => is_null($rowData['B']) ? null : $rowData['B'],
                                        'reporteruidodosisner_ner' => is_null($rowData['C']) ? null : $rowData['C'],
                                        'reporteruidoarea_id' => isset($IdAreas[$rowData['D']]) ? $IdAreas[$rowData['D']] : null,
                                        'reporteruidocategoria_id' => isset($IdCategorias[$rowData['E']]) ?  $IdCategorias[$rowData['E']] : null,
                                        'reporteruidodosisner_lmpe' => $lmpe,
                                        'reporteruidodosisner_tmpe' => is_null($rowData['C']) ? 'NA' : calculartmpe($rowData['C']),

                                    ]);


                                    $puntosInsertados++;
                                }

                                break;
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







            // TABLAS
            //============================================================

            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteregistro_id + 0) > 0) {
                $reporte = reporteruidoModel::findOrFail($request->reporteregistro_id);


                $reporte->update([
                    'reporteruido_instalacion' => $request->reporte_instalacion
                ]);


                $dato["reporteregistro_id"] = $reporte->id;


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_id', $request->agente_id)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                if (count($revision) > 0) {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 26) // Valida disponibilidad de esta version
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reporteruido AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reporteruidoModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reporteruido_revision' => 0,
                    'reporteruido_instalacion' => $request->reporte_instalacion,
                    'reporteruido_catregion_activo' => 1,
                    'reporteruido_catsubdireccion_activo' => 1,
                    'reporteruido_catgerencia_activo' => 1,
                    'reporteruido_catactivo_activo' => 1,
                    'reporteruido_concluido' => 0,
                    'reporteruido_cancelado' => 0
                ]);


                //--------------------------------------


                // ASIGNAR CATEGORIAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reporteruidocategoria
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // ASIGNAR AREAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reporteruidoarea
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            // PORTADA
            if (($request->opcion + 0) == 0) {


                $reporte->update([
                    'reporteruido_catregion_activo' => 0,
                    'reporteruido_catsubdireccion_activo' => 0,
                    'reporteruido_catgerencia_activo' => 0,
                    'reporteruido_catactivo_activo' => 0,
                    'reporteruido_instalacion' => $request->reporte_instalacion,
                    'reporteruido_fecha' => $request->reporte_fecha,
                    'reporte_mes' => $request->reporte_mes

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
                    'reporteruido_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion)
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
                    'reporteruido_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporte->update([
                    'reporteruido_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporte->update([
                    'reporteruido_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion + 0) == 6) {
                $reporte->update([
                    'reporteruido_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion + 0) == 7) {
                $reporte->update([
                    'reporteruido_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
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
                        'reporteruido_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 8) {
                $reporte->update([
                    'reporteruido_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion),
                    'reporteruido_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion + 0) == 9) {
                if (($request->reportecategoria_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteruidocategoria AUTO_INCREMENT = 1;');

                    $request['recsensorialcategoria_id'] = 0;
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidocategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidocategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS
            if (($request->opcion + 0) == 10) {
                // dd($request->all());


                if (($request->areas_poe + 0) == 1) {
                    $request['reportearea_proceso'] = $request->reporteruidoarea_proceso;
                    $request['reportearea_tiporuido'] = $request->reporteruidoarea_tiporuido;
                    $request['reportearea_evaluacion'] = $request->reporteruidoarea_evaluacion;
                    $request['reportearea_LNI_1'] = $request->reporteruidoarea_LNI_1;
                    $request['reportearea_LNI_2'] = $request->reporteruidoarea_LNI_2;
                    $request['reportearea_LNI_3'] = $request->reporteruidoarea_LNI_3;
                    $request['reportearea_LNI_4'] = $request->reporteruidoarea_LNI_4;
                    $request['reportearea_LNI_5'] = $request->reporteruidoarea_LNI_5;
                    $request['reportearea_LNI_6'] = $request->reporteruidoarea_LNI_6;
                    $request['reportearea_LNI_7'] = $request->reporteruidoarea_LNI_7;
                    $request['reportearea_LNI_8'] = $request->reporteruidoarea_LNI_8;
                    $request['reportearea_LNI_9'] = $request->reporteruidoarea_LNI_9;
                    $request['reportearea_LNI_10'] = $request->reporteruidoarea_LNI_10;

                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reporteruidoareacategoriaModel::where('reporteruidoarea_id', $request->reportearea_id)
                        ->where('reporteruidoareacategoria_poe', $request->reporteregistro_id)
                        ->delete();


                    if ($request->checkbox_categoria_id) {
                        DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');


                        foreach ($request->checkbox_categoria_id as $key => $value) {
                            $areacategoria = reporteruidoareacategoriaModel::create([
                                'reporteruidoarea_id' => $area->id,
                                'reporteruidocategoria_id' => $value,
                                'reporteruidoareacategoria_poe' => $request->reporteregistro_id,
                                'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                            ]);
                        }
                    }


                    $eliminar_maquinaria = reporteruidoareamaquinariaModel::where('reporteruidoarea_id', $request->reportearea_id)
                        ->where('reporteruidoareamaquinaria_poe', $request->reporteregistro_id)
                        ->delete();


                    if ($request->reporteruidoareamaquinaria_nombre) {
                        DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                        foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                            $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                'reporteruidoarea_id' => $area->id,
                                'reporteruidoareamaquinaria_poe' => $request->reporteregistro_id,
                                'reporteruidoareamaquinaria_nombre' => $value,
                                'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reporteruidoarea AUTO_INCREMENT = 1;');


                        $request['registro_id'] = $reporte->id;
                        $request['recsensorialarea_id'] = 0;
                        $area = reporteruidoareaModel::create($request->all());


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reporteruidoareacategoriaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidocategoria_id' => $value,
                                    'reporteruidoareacategoria_poe' => 0,
                                    'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }


                        if ($request->reporteruidoareamaquinaria_nombre) {
                            DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                            foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                                $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidoareamaquinaria_poe' => 0,
                                    'reporteruidoareamaquinaria_nombre' => $value,
                                    'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $request['registro_id'] = $reporte->id;
                        $area = reporteruidoareaModel::findOrFail($request->reportearea_id);
                        $area->update($request->all());


                        $eliminar_categorias = reporteruidoareacategoriaModel::where('reporteruidoarea_id', $request->reportearea_id)
                            ->where('reporteruidoareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reporteruidoareacategoria AUTO_INCREMENT = 1;');


                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reporteruidoareacategoriaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidocategoria_id' => $value,
                                    'reporteruidoareacategoria_poe' => 0,
                                    'reporteruidoareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }



                        $eliminar_maquinaria = reporteruidoareamaquinariaModel::where('reporteruidoarea_id', $request->reportearea_id)
                            ->where('reporteruidoareamaquinaria_poe', 0)
                            ->delete();


                        if ($request->reporteruidoareamaquinaria_nombre) {
                            DB::statement('ALTER TABLE reporteruidoareamaquinaria AUTO_INCREMENT = 1;');

                            foreach ($request->reporteruidoareamaquinaria_nombre as $key => $value) {
                                $areamaquinaria = reporteruidoareamaquinariaModel::create([
                                    'reporteruidoarea_id' => $area->id,
                                    'reporteruidoareamaquinaria_poe' => 0,
                                    'reporteruidoareamaquinaria_nombre' => $value,
                                    'reporteruidoareamaquinaria_cantidad' => $request['reporteruidoareamaquinaria_cantidad'][$key]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // EQUIPO AUDITIVO
            if (($request->opcion + 0) == 11) {
                if (($request->reporteequipoauditivo_id + 0) == 0) {
                    $request['registro_id'] = $reporte->id;
                    DB::statement('ALTER TABLE reporteruidoequipoauditivo AUTO_INCREMENT = 1;');
                    $equipoauditivo = reporteruidoequipoauditivoModel::create($request->all());

                    if ($request->reporteruidoequipoauditivoatenuacion_bandaNRR) {
                        DB::statement('ALTER TABLE reporteruidoequipoauditivoatenuacion AUTO_INCREMENT = 1;');

                        foreach ($request->reporteruidoequipoauditivoatenuacion_bandaNRR as $key => $value) {
                            $atenuacion = reporteruidoequipoauditivoatenuacionModel::create([
                                'reporteruidoequipoauditivo_id' => $equipoauditivo->id,
                                'reporteruidoequipoauditivoatenuacion_bandaNRR' => $value,
                                'reporteruidoequipoauditivoatenuacion_bandaatenuacion' => $request['reporteruidoequipoauditivoatenuacion_bandaatenuacion'][$key]
                            ]);
                        }
                    }

                    if ($request->equipoauditivo_categoria) {
                        DB::statement('ALTER TABLE reporteruidoequipoauditivocategorias AUTO_INCREMENT = 1;');

                        foreach ($request->equipoauditivo_categoria as $key => $value) {
                            $categoria = reporteruidoequipoauditivocategoriasModel::create([
                                'reporteruidoequipoauditivo_id' => $equipoauditivo->id,
                                'reporteruidocategoria_id' => $value
                            ]);
                        }
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $equipoauditivo = reporteruidoequipoauditivoModel::findOrFail($request->reporteequipoauditivo_id);
                    $equipoauditivo->update($request->all());

                    if ($request->reporteruidoequipoauditivoatenuacion_bandaNRR) {
                        $eliminar_atenuaciones = reporteruidoequipoauditivoatenuacionModel::where('reporteruidoequipoauditivo_id', $request->reporteequipoauditivo_id)->delete();

                        DB::statement('ALTER TABLE reporteruidoequipoauditivoatenuacion AUTO_INCREMENT = 1;');

                        foreach ($request->reporteruidoequipoauditivoatenuacion_bandaNRR as $key => $value) {
                            $atenuacion = reporteruidoequipoauditivoatenuacionModel::create([
                                'reporteruidoequipoauditivo_id' => $equipoauditivo->id,
                                'reporteruidoequipoauditivoatenuacion_bandaNRR' => $value,
                                'reporteruidoequipoauditivoatenuacion_bandaatenuacion' => $request['reporteruidoequipoauditivoatenuacion_bandaatenuacion'][$key]
                            ]);
                        }
                    }

                    if ($request->equipoauditivo_categoria) {
                        $eliminar_categorias = reporteruidoequipoauditivocategoriasModel::where('reporteruidoequipoauditivo_id', $request->reporteequipoauditivo_id)->delete();

                        DB::statement('ALTER TABLE reporteruidoequipoauditivocategorias AUTO_INCREMENT = 1;');

                        foreach ($request->equipoauditivo_categoria as $key => $value) {
                            $categoria = reporteruidoequipoauditivocategoriasModel::create([
                                'reporteruidoequipoauditivo_id' => $equipoauditivo->id,
                                'reporteruidocategoria_id' => $value
                            ]);
                        }
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // EQUIPO PROTECCION PERSONAL
            if (($request->opcion + 0) == 12) {
                if (($request->reporteepp_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteruidoepp AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidoeppModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteruidoeppModel::findOrFail($request->reporteepp_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS PUNTOS DE EVALUACION
            if (($request->opcion + 0) == 13) {
                $eliminar_areaevaluacion = reporteruidoareaevaluacionModel::where('proyecto_id', $request->proyecto_id)
                    ->where('registro_id', $reporte->id)
                    ->where('reporteruidoarea_id', $request->reporteruidoarea_id)
                    ->delete();

                if ($request->reporteruidoareaevaluacion_nomedicion1) {
                    DB::statement('ALTER TABLE reporteruidoareaevaluacion AUTO_INCREMENT = 1;');

                    foreach ($request->reporteruidoareaevaluacion_nomedicion1 as $key => $value) {
                        $areaevaluacion = reporteruidoareaevaluacionModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporteruidoarea_id' => $request->reporteruidoarea_id,
                            'reporteruidoareaevaluacion_noevaluaciones' => $request->reporteruidoareaevaluacion_noevaluaciones,
                            'reporteruidoareaevaluacion_nomedicion1' => $value,
                            'reporteruidoareaevaluacion_nomedicion2' => $request['reporteruidoareaevaluacion_nomedicion2'][$key],
                            'reporteruidoareaevaluacion_ubicacion' => $request['reporteruidoareaevaluacion_ubicacion'][$key]
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODO DE EVALUACION
            if (($request->opcion + 0) == 14) {
                $reporte->update([
                    'reporteruido_metodoevaluacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodoevaluacion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PUNTO DE MEDICION NIVEL SONORO
            if (($request->opcion + 0) == 15) {

                $eliminar_nivelsonoro = reporteruidonivelsonoroModel::where('proyecto_id', $request->proyecto_id)
                    ->where('registro_id', $reporte->id)
                    ->where('reporteruidonivelsonoro_punto', $request->reportenivelsonoro_punto)
                    ->delete();

                if ($request->reporteruidonivelsonoro_periodo1) {
                    DB::statement('ALTER TABLE reporteruidonivelsonoro AUTO_INCREMENT = 1;');

                    foreach ($request->reporteruidonivelsonoro_periodo1 as $key => $value) {
                        $nivelsonoro = reporteruidonivelsonoroModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reporteruidonivelsonoro_punto' => $request->reporteruidonivelsonoro_punto,
                            'reporteruidonivelsonoro_ubicacion' => $request->reporteruidonivelsonoro_ubicacion,
                            'reporteruidonivelsonoro_promedio' => $request->reporteruidonivelsonoro_promedio,
                            'reporteruidonivelsonoro_totalperiodos' => $request->reporteruidonivelsonoro_totalperiodos,
                            'reporteruidonivelsonoro_totalresultados' => $request->reporteruidonivelsonoro_totalresultados,
                            'reporteruidonivelsonoro_periodo1' => $value,
                            'reporteruidonivelsonoro_periodo2' => $request['reporteruidonivelsonoro_periodo2'][$key] ?? null,
                            'reporteruidonivelsonoro_periodo3' => $request['reporteruidonivelsonoro_periodo3'][$key] ?? null,
                            'reporteruidonivelsonoro_periodo4' => $request['reporteruidonivelsonoro_periodo4'][$key] ?? null,
                            'reporteruidonivelsonoro_periodo5' => $request['reporteruidonivelsonoro_periodo5'][$key] ?? null,
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }



            // PUNTO DE DETERMINACION DEL NER
            if (($request->opcion + 0) == 16) {
                if (($request->puntoner_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteruidopuntoner AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $puntoner = reporteruidopuntonerModel::create($request->all());

                    //====================== DESACTIVAMOS LA INSERCION DE LAS CATEGORIAS YA QUE NO ES NECSARIO PARA ESTE PUNTO
                    // if ($request->reporteruidocategoria_id) {
                    //     DB::statement('ALTER TABLE reporteruidopuntonercategorias AUTO_INCREMENT = 1;');

                    //     foreach ($request->reporteruidocategoria_id as $key => $value) {
                    //         $categoria = reporteruidopuntonercategoriasModel::create([
                    //             'reporteruidopuntoner_id' => $puntoner->id, 'reporteruidocategoria_id' => $value, 'reporteruidopuntonercategorias_total' => $request['reporteruidopuntonercategorias_total'][$key], 'reporteruidopuntonercategorias_geo' => $request['reporteruidopuntonercategorias_geo'][$key], 'reporteruidopuntonercategorias_ficha' => $request['reporteruidopuntonercategorias_ficha'][$key], 'reporteruidopuntonercategorias_nombre' => $request['reporteruidopuntonercategorias_nombre'][$key]
                    //         ]);
                    //     }
                    // }

                    $frecuencias_bandasoctava = array('31.5', '63', '125', '250', '500', '1K', '2K', '4K', '8K');
                    foreach ($frecuencias_bandasoctava as $key => $value) {
                        $frecuencia = reporteruidopuntonerfrecuenciasModel::create([
                            'reporteruidopuntoner_id' => $puntoner->id,
                            'reporteruidopuntonerfrecuencias_orden' => ($key + 1),
                            'reporteruidopuntonerfrecuencias_frecuencia' => $value,
                            'reporteruidopuntonerfrecuencias_nivel' => NULL
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $puntoner = reporteruidopuntonerModel::findOrFail($request->puntoner_id);
                    $puntoner->update($request->all());

                    //====================== DESACTIVAMOS LA INSERCION DE LAS CATEGORIAS YA QUE NO ES NECESARIO PARA ESTE PUNTO

                    // if ($request->reporteruidocategoria_id) {
                    //     $eliminar_categorias = reporteruidopuntonercategoriasModel::where('reporteruidopuntoner_id', $request->puntoner_id)->delete();

                    //     DB::statement('ALTER TABLE reporteruidopuntonercategorias AUTO_INCREMENT = 1;');

                    //     foreach ($request->reporteruidocategoria_id as $key => $value) {
                    //         $categoria = reporteruidopuntonercategoriasModel::create([
                    //             'reporteruidopuntoner_id' => $puntoner->id, 'reporteruidocategoria_id' => $value, 'reporteruidopuntonercategorias_total' => $request['reporteruidopuntonercategorias_total'][$key], 'reporteruidopuntonercategorias_geo' => $request['reporteruidopuntonercategorias_geo'][$key], 'reporteruidopuntonercategorias_ficha' => $request['reporteruidopuntonercategorias_ficha'][$key], 'reporteruidopuntonercategorias_nombre' => $request['reporteruidopuntonercategorias_nombre'][$key]
                    //         ]);
                    //     }
                    // }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // DOSIS DE DETERMINACION DEL NER
            if (($request->opcion + 0) == 17) {
                if (($request->dosisner_id + 0) == 0) {
                    DB::statement('ALTER TABLE reporteruidodosisner AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $dosisner = reporteruidodosisnerModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $dosisner = reporteruidodosisnerModel::findOrFail($request->dosisner_id);
                    $dosisner->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // RUIDO EFECTIVO CON MODELO POR BANDAS DE OCTAVA
            if (($request->opcion + 0) == 18) {
                $puntoner = reporteruidopuntonerModel::findOrFail($request->reporteruidopuntoner_id);

                $puntoner->update([
                    'reporteruidopuntoner_RdB' => $request->reporteruidobandaoctava_RdB,
                    'reporteruidopuntoner_NRE' => $request->reporteruidobandaoctava_NRE
                ]);

                foreach ($request->reporteruidopuntonerfrecuencias_frecuencia as $key => $value) {
                    DB::statement('UPDATE reporteruidopuntonerfrecuencias
                                    SET reporteruidopuntonerfrecuencias_nivel = ' . $request['reporteruidopuntonerfrecuencias_nivel'][$key] . ' 
                                    WHERE reporteruidopuntoner_id = ' . $request->reporteruidopuntoner_id . '
                                    AND reporteruidopuntonerfrecuencias_frecuencia = "' . $value . '";');
                }

                // Mensaje
                $dato["msj"] = 'Datos modificados correctamente';
            }


            // CONCLUSION
            if (($request->opcion + 0) == 19) {
                $reporte->update([
                    'reporteruido_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion + 0) == 20) {
                if ($request->recomendacion_checkbox) {
                    $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                        ->where('catactivo_id', $request->catactivo_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reporte->id)
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
                            ->where('registro_id', $reporte->id)
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
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion + 0) == 21) {
                $reporte->update([
                    'reporteruido_responsable1' => $request->reporte_responsable1,
                    'reporteruido_responsable1cargo' => $request->reporte_responsable1cargo,
                    'reporteruido_responsable2' => $request->reporte_responsable2,
                    'reporteruido_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporte->update([
                        'reporteruido_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reporteruido_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
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
                        'reporteruido_responsable1documento' => $destinoPath
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
                        'reporteruido_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 22) {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
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
            if (($request->opcion + 0) == 23) {
                if ($request->equipoutilizado_checkbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
                        ->where('registro_id', $reporte->id)
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
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion + 0) == 24) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
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
            if (($request->opcion + 0) == 25) {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_nombre', $request->agente_nombre)
                    ->where('registro_id', $reporte->id)
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
            if (($request->opcion + 0) == 26) {
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


            /*

            // GRAFICAS FOTOS BASE64
            if (($request->opcion+0) == 70)
            {
                dd($request);

                if ($request->grafica1)
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->grafica1); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/informes/graficapastel1_iluminacion_informe'.$reporteiluminacion->id.'.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
                }

                // Mensaje
                $dato["msj"] = 'Imagen guardada correctamente';
            }

            */


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
