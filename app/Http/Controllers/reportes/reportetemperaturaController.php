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
use App\modelos\reportes\reportetemperaturacatalogoModel;
use App\modelos\reportes\reportetemperaturaModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportetemperaturaareacategoriaModel;
use App\modelos\reportes\reportetemperaturaareamaquinariaModel;
use App\modelos\reportes\reportetemperaturaevaluacionModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\recsensorial\catConclusionesModel;
use App\modelos\reportes\recursosPortadasInformesModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reportetemperaturaController extends Controller
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
    public function reportetemperaturavista($proyecto_id)
    {
        $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Temperatura primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 3) // Temperatura
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================

            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 3,
                    'agente_nombre' => 'Temperatura',
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
                                            AND proyectoproveedores.catprueba_id = 3
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            //DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            // $proveedor_id = $proveedor[0]->proveedor_id;

            $proveedor_id = 0; //BORAR DESPUES DE SUBIR AL SERVIDOR



            //===================================================


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);


            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.parametros.reportetemperatura', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'proveedor_id', 'catConclusiones'));
        }
    }


    public function datosproyectolimpiartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace($proyecto->catsubdireccion->catsubdireccion_nombre, 'SUBDIRECCION_NOMBRE', $texto);
            $texto = str_replace($proyecto->catgerencia->catgerencia_nombre, 'GERENCIA_NOMBRE', $texto);
            $texto = str_replace($proyecto->catactivo->catactivo_nombre, 'ACTIVO_NOMBRE', $texto);
        } else {
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Exploración y Producción', $texto);
            $texto = str_replace($recsensorial->recsensorial_empresa, 'Pemex Exploración y Producción', $texto);
        }

        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        $texto = str_replace($reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], 'REPORTE_FECHA_LARGA', $texto);

        return $texto;
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

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
            $texto = str_replace('Pemex Exploración y Producción', $recsensorial->recsensorial_empresa, $texto);
        }

        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. ' . $recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], $texto);

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
    public function reportetemperaturadatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reportetemperaturacatalogoModel::findOrFail(1);
            $reporte = reportetemperaturaModel::where('proyecto_id', $proyecto_id)->get();


            if (count($reporte) > 0) {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = ($reporte->id + 0);
            } else {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reportetemperaturaModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('updated_at', 'DESC')
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reportetemperatura.id,
                                                reportetemperatura.proyecto_id,
                                                reportetemperatura.catactivo_id,
                                                reportetemperatura.reportetemperatura_fecha,
                                                reportetemperatura.reporte_mes,

                                                reportetemperatura.reportetemperatura_instalacion,
                                                reportetemperatura.reportetemperatura_catregion_activo,
                                                reportetemperatura.reportetemperatura_catsubdireccion_activo,
                                                reportetemperatura.reportetemperatura_catgerencia_activo,
                                                reportetemperatura.reportetemperatura_catactivo_activo,
                                                reportetemperatura.reportetemperatura_introduccion,
                                                reportetemperatura.reportetemperatura_objetivogeneral,
                                                reportetemperatura.reportetemperatura_objetivoespecifico,
                                                reportetemperatura.reportetemperatura_metodologia_4_1,
                                                reportetemperatura.reportetemperatura_ubicacioninstalacion,
                                                reportetemperatura.reportetemperatura_ubicacionfoto,
                                                reportetemperatura.reportetemperatura_procesoinstalacion,
                                                reportetemperatura.reportetemperatura_actividadprincipal,
                                                reportetemperatura.reportetemperatura_conclusion,
                                                reportetemperatura.reportetemperatura_responsable1,
                                                reportetemperatura.reportetemperatura_responsable1cargo,
                                                reportetemperatura.reportetemperatura_responsable1documento,
                                                reportetemperatura.reportetemperatura_responsable2,
                                                reportetemperatura.reportetemperatura_responsable2cargo,
                                                reportetemperatura.reportetemperatura_responsable2documento,
                                                reportetemperatura.created_at,
                                                reportetemperatura.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportetemperatura ON proyecto.id = reportetemperatura.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . ' 
                                                AND reportetemperatura.reportetemperatura_instalacion <> "" 
                                            ORDER BY
                                                reportetemperatura.updated_at DESC');
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
                ->where('agente_id', 3) //Temperatura
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


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportetemperatura_fecha != NULL) {
                $reportefecha = $reporte->reportetemperatura_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => $reporte->reportetemperatura_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => $reporte->reportetemperatura_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => $reporte->reportetemperatura_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => $reporte->reportetemperatura_catactivo_activo,
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
                    'reporte_mes' => ""

                );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_introduccion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_introduccion_guardado'] = 1;
                } else {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reportetemperatura_introduccion;
            } else {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo->reportetemperaturacatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_objetivogeneral != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reportetemperatura_objetivogeneral;
            } else {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo->reportetemperaturacatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_objetivoespecifico != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reportetemperatura_objetivoespecifico;
            } else {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo->reportetemperaturacatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_metodologia_4_1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reportetemperatura_metodologia_4_1;
            } else {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo->reportetemperaturacatalogo_metodologia_4_1;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_ubicacioninstalacion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reportetemperatura_ubicacioninstalacion;
            } else {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo->reportetemperaturacatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] > 0 && $reporte->reportetemperatura_ubicacionfoto != NULL) {
                $ubicacionfoto = $reporte->reportetemperatura_ubicacionfoto;
            }


            $dato['reporte_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportetemperatura_procesoinstalacion != NULL) {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reportetemperatura_procesoinstalacion;
            } else {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }


            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportetemperatura_actividadprincipal != NULL) {
                $actividadprincipal = $reporte->reportetemperatura_actividadprincipal;
            } else {
                $actividadprincipal = $recsensorial->recsensorial_actividadprincipal;
            }


            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $actividadprincipal);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reportetemperatura_conclusion != NULL) {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reportetemperatura_conclusion;
            } else {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo->reportetemperaturacatalogo_conclusion;
            }


            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportetemperatura_responsable1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                    'responsable1' => $reporte->reportetemperatura_responsable1,
                    'responsable1cargo' => $reporte->reportetemperatura_responsable1cargo,
                    'responsable1documento' => $reporte->reportetemperatura_responsable1documento,
                    'responsable2' => $reporte->reportetemperatura_responsable2,
                    'responsable2cargo' => $reporte->reportetemperatura_responsable2cargo,
                    'responsable2documento' => $reporte->reportetemperatura_responsable2documento,
                    'proyecto_id' => $reporte->proyecto_id,
                    'registro_id' => $reporte->id
                );
            } else {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reportetemperaturaModel::where('reportetemperatura_responsable1', '!=', '')
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->get();


                if (count($reportehistorial) > 0 && $reportehistorial[0]->reportetemperatura_responsable1 != NULL) {
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => $reportehistorial[0]->reportetemperatura_responsable1,
                        'responsable1cargo' => $reportehistorial[0]->reportetemperatura_responsable1cargo,
                        'responsable1documento' => $reportehistorial[0]->reportetemperatura_responsable1documento,
                        'responsable2' => $reportehistorial[0]->reportetemperatura_responsable2,
                        'responsable2cargo' => $reportehistorial[0]->reportetemperatura_responsable2cargo,
                        'responsable2documento' => $reportehistorial[0]->reportetemperatura_responsable2documento,
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
    public function reportetemperaturatabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 3)
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
    public function reportetemperaturadefinicioneliminar($definicion_id)
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
    public function reportetemperaturamapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reportetemperaturaModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporte->reportetemperatura_ubicacionfoto);
        } else {
            return Storage::download($reporte->reportetemperatura_ubicacionfoto);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportetemperaturaareas($proyecto_id)
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
                                     reportearea.reportearea_instalacion,
                                     reportearea.reportearea_nombre,
                                     reportearea.reportearea_orden,
                                     reportearea.reportearea_porcientooperacion,
                                     reportearea.reportetemperaturaarea_porcientooperacion,
                                     reportearea.reportearea_caracteristicaarea,
                                     reportearea.reportearea_tipoventilacion,
                                     reporteareacategoria.reportecategoria_id,
                                     reportecategoria.reportecategoria_orden,
                                     reportecategoria.reportecategoria_nombre,
                                     IFNULL((
                                        SELECT
                                            IF(reporteareacategoria.reportecategoria_id, "activo", "") AS checked
                                        FROM
                                            reportetemperaturaareacategoria
                                        WHERE
                                            reportetemperaturaareacategoria.reportearea_id = reportearea.id
                                            AND reportetemperaturaareacategoria.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                        LIMIT 1
                                     ), "") AS activo,
                                     reporteareacategoria.reporteareacategoria_total,
                                     reporteareacategoria.reporteareacategoria_geh,
                                     reporteareacategoria.reporteareacategoria_actividades,
                                     IFNULL((
                                        SELECT
                                            -- reportetemperaturaevaluacion.proyecto_id,
                                            -- reportetemperaturaevaluacion.reportearea_id,
                                            -- reportetemperaturaevaluacion.reportecategoria_id,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_puesto 
                                        FROM
                                            reportetemperaturaevaluacion
                                        WHERE
                                            reportetemperaturaevaluacion.proyecto_id = reportearea.proyecto_id
                                            AND reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                            AND reportetemperaturaevaluacion.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                        ORDER BY
                                            reportetemperaturaevaluacion.reportearea_id ASC,
                                            reportetemperaturaevaluacion.reportecategoria_id ASC,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_puesto DESC
                                        LIMIT 1
                                     ), "") AS reportetemperaturaevaluacion_puesto,
                                     IFNULL((
                                        SELECT
                                            -- reportetemperaturaevaluacion.proyecto_id,
                                            -- reportetemperaturaevaluacion.reportearea_id,
                                            -- reportetemperaturaevaluacion.reportecategoria_id,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_tiempo 
                                        FROM
                                            reportetemperaturaevaluacion
                                        WHERE
                                            reportetemperaturaevaluacion.proyecto_id = reportearea.proyecto_id
                                            AND reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                            AND reportetemperaturaevaluacion.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                        ORDER BY
                                            reportetemperaturaevaluacion.reportearea_id ASC,
                                            reportetemperaturaevaluacion.reportecategoria_id ASC
                                        LIMIT 1
                                     ), "") AS reportetemperaturaevaluacion_tiempo,
                                     IFNULL((
                                        SELECT
                                            -- reportetemperaturaevaluacion.proyecto_id,
                                            -- reportetemperaturaevaluacion.reportearea_id,
                                            -- reportetemperaturaevaluacion.reportecategoria_id,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_ciclos 
                                        FROM
                                            reportetemperaturaevaluacion
                                        WHERE
                                            reportetemperaturaevaluacion.proyecto_id = reportearea.proyecto_id
                                            AND reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                            AND reportetemperaturaevaluacion.reportecategoria_id = reporteareacategoria.reportecategoria_id
                                        ORDER BY
                                            reportetemperaturaevaluacion.reportearea_id ASC,
                                            reportetemperaturaevaluacion.reportecategoria_id ASC
                                        LIMIT 1
                                     ), "") AS reportetemperaturaevaluacion_ciclos
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


                    if ($value->reportetemperaturaarea_porcientooperacion > 0) {
                        $numero_registro2 += 1;

                        //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                        //==================================================

                        $tabla_6_1 .= '<tr>
                                            <td>' . $numero_registro2 . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportetemperaturaarea_porcientooperacion . '%</td>
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


                if ($value->reportearea_caracteristicaarea === NULL) {
                    $total_singuardar += 1;
                }


                if ($value->reportetemperaturaarea_porcientooperacion > 0) {
                    if ($value->activo) {
                        //TABLA 5.4.- Actividades del personal expuesto
                        //==================================================


                        if ($area3 != $value->reportearea_nombre) {
                            $area3 = $value->reportearea_nombre;
                            $numero_registro3 += 1;
                        }


                        $tiempo_ciclos = '';
                        if ($value->reportetemperaturaevaluacion_tiempo) {
                            $tiempo_ciclos = $value->reportetemperaturaevaluacion_tiempo . ' min / ' . $value->reportetemperaturaevaluacion_ciclos . ' ciclos';
                        }


                        $tabla_5_4 .= '<tr>
                                            <td>' . $numero_registro3 . '</td>
                                            <td>' . $value->reportearea_instalacion . '</td>
                                            <td>' . $value->reportearea_nombre . '</td>
                                            <td>' . $value->reportecategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reporteareacategoria_actividades . '</td>
                                            <td>' . $tiempo_ciclos . '</td>
                                            <td>' . $value->reportetemperaturaevaluacion_puesto . '</td>
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


            //------------------------------------------------
            //TABLA 5.5.- Identificación de las áreas


            $areasmaquinaria = DB::select('SELECT
                                                reportearea.proyecto_id,
                                                reportetemperaturamaquinaria.reportearea_id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                reportearea.reportetemperaturaarea_porcientooperacion,
                                                IF(reportearea.reportearea_caracteristicaarea = "Abierta", "Sí", "No") AS Abierta,
                                                IF(reportearea.reportearea_caracteristicaarea = "Cerrada", "Sí", "No") AS Cerrada,
                                                IF(reportearea.reportearea_tipoventilacion = "Natural", "Sí", "No") AS Naturals,
                                                IF(reportearea.reportearea_tipoventilacion = "Artificial", "Sí", "No") AS Artificial,
                                                reportetemperaturamaquinaria.reportetemperaturamaquinaria_nombre,
                                                reportetemperaturamaquinaria.reportetemperaturamaquinaria_cantidad 
                                            FROM
                                                reportetemperaturamaquinaria
                                                LEFT JOIN reportearea ON reportetemperaturamaquinaria.reportearea_id = reportearea.id
                                            WHERE
                                                reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportearea.reportetemperaturaarea_porcientooperacion > 0
                                            ORDER BY
                                                reportearea.reportearea_orden ASC,
                                                reportearea.reportearea_nombre ASC,
                                                reportetemperaturamaquinaria.reportetemperaturamaquinaria_nombre ASC');


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
                                    <td>' . $value->reportetemperaturamaquinaria_nombre . '</td>
                                    <td>' . $value->reportetemperaturamaquinaria_cantidad . '</td>
                                    <td>' . $value->Abierta . '</td>
                                    <td>' . $value->Cerrada . '</td>
                                    <td>' . $value->Naturals . '</td>
                                    <td>' . $value->Artificial . '</td>
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
            $dato["tabla_5_4"] = '<tr><td colspan="7">Error al consultar los datos</td></tr>';
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
    public function reportetemperaturaareacategorias($proyecto_id, $area_id)
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
                                                        IF(reportetemperaturaareacategoria.reportecategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reportetemperaturaareacategoria
                                                    WHERE
                                                        reportetemperaturaareacategoria.reportearea_id = reporteareacategoria.reportearea_id
                                                        AND reportetemperaturaareacategoria.reportecategoria_id = reportecategoria.id
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
                                                reportearea.reportetemperaturaarea_porcientooperacion,
                                                reportetemperaturamaquinaria.reportetemperaturamaquinaria_nombre,
                                                reportetemperaturamaquinaria.reportetemperaturamaquinaria_cantidad 
                                            FROM
                                                reportetemperaturamaquinaria
                                                LEFT JOIN reportearea ON reportetemperaturamaquinaria.reportearea_id = reportearea.id
                                            WHERE
                                                reportearea.id = ' . $area_id);


            $areamaquinarias_lista = '';


            foreach ($areamaquinarias as $key => $value) {
                $areamaquinarias_lista .= '<tr>
                                                <td><input type="text" class="form-control" name="reportetemperaturamaquinaria_nombre[]" value="' . $value->reportetemperaturamaquinaria_nombre . '" required></td>
                                                <td><input type="number" min="1" class="form-control" name="reportetemperaturamaquinaria_cantidad[]" value="' . $value->reportetemperaturamaquinaria_cantidad . '" required></td>
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
    public function reportetemperaturaevaluaciontabla($proyecto_id)
    {
        try {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 3)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            $evaluacion = DB::select('SELECT
                                            reportetemperaturaevaluacion.proyecto_id,
                                            reportetemperaturaevaluacion.id,
                                            reportetemperaturaevaluacion.reportearea_id,
                                            reportearea.reportearea_instalacion,
                                            reportearea.reportearea_nombre,
                                            reportearea.reportearea_orden,
                                            reportetemperaturaevaluacion.reportecategoria_id,
                                            reportecategoria.reportecategoria_nombre,
                                            reportecategoria.reportecategoria_orden,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_ficha,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_puesto,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_tiempo,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_ciclos,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen,
                                            (
                                                CASE
                                                    WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen = 1 THEN "Ligero"
                                                    WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen = 2 THEN "Moderado"
                                                    ELSE "Pesado"
                                                END
                                            ) AS regimen_texto,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_porcentaje,
                                            (
                                                CASE
                                                    WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_porcentaje = 1 THEN "100 % de exposición"
                                                    WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_porcentaje = 2 THEN "75 % de exposición y 25 % de recuperación en cada hora"
                                                    WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_porcentaje = 3 THEN "50 % de exposición y 50 % de recuperación en cada hora"
                                                    ELSE "25 % de exposición y 75 % de recuperación en cada hora"
                                                END
                                            ) AS porcentaje_texto,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_I,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_II,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_III,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_I = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_I) AS reportetemperaturaevaluacion_I_texto,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_II = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_II) AS reportetemperaturaevaluacion_II_texto,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_III = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_III) AS reportetemperaturaevaluacion_III_texto,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE,
                                            (
                                                IF(
                                                    (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                    , "Fuera de norma"
                                                    , "Dentro de norma"
                                                )
                                            ) AS resultado 
                                        FROM
                                            reportetemperaturaevaluacion
                                            LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id 
                                        WHERE
                                            reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto ASC,
                                            reportearea.reportearea_orden ASC,
                                            reportearea.reportearea_nombre ASC,
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador ASC');


            $dato['tabla_reporte_7_1'] = NULL;
            $numero_registro = 0;
            foreach ($evaluacion as $key => $value) {
                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }


                // TABLA RESULTADOS 7.1 temperaturas elevadas
                //==========================================


                $dato['tabla_reporte_7_1'] .= '<tr>
                                                    <td>' . $value->reportetemperaturaevaluacion_punto . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_trabajador . '</td>
                                                    <td>' . $value->reportecategoria_nombre . '</td>
                                                    <td>' . $value->reportearea_nombre . '</td>
                                                    <td>' . $value->regimen_texto . '</td>
                                                    <td>' . $value->porcentaje_texto . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_I_texto . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_II_texto . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_III_texto . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_LMPE . '</td>
                                                    <td>' . $value->resultado . '</td>
                                                </tr>';
            }


            //==========================================


            $areas_condicion = DB::select('SELECT
                                                reportetemperaturaevaluacion.proyecto_id,
                                                reportetemperaturaevaluacion.id,
                                                reportetemperaturaevaluacion.reportearea_id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                (
                                                    SELECT
                                                        -- evaluacion.proyecto_id,
                                                        -- evaluacion.reportearea_id,
                                                        COUNT(evaluacion.reportetemperaturaevaluacion_punto) AS total
                                                    FROM
                                                        reportetemperaturaevaluacion AS evaluacion
                                                    WHERE
                                                        evaluacion.proyecto_id = reportetemperaturaevaluacion.proyecto_id
                                                        AND evaluacion.reportearea_id = reportetemperaturaevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS total_puntosarea,
                                                reportecategoria.reportecategoria_nombre,
                                                reportecategoria.reportecategoria_orden,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_puesto,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen,
                                                (
                                                    CASE
                                                        WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen = 1 THEN "Ligero"
                                                        WHEN reportetemperaturaevaluacion.reportetemperaturaevaluacion_regimen = 2 THEN "Moderado"
                                                        ELSE "Pesado"
                                                    END
                                                ) AS regimen_texto,
                                                reporteareacategoria.reporteareacategoria_actividades 
                                            FROM
                                                reportetemperaturaevaluacion
                                                LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                            WHERE
                                                reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportearea.reportearea_orden ASC,
                                                reportearea.reportearea_nombre ASC,
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');



            $dato['tabla_reporte_6_2_1'] = NULL;
            $dato['total_puntosarea'] = 0;
            $area = 'XXXXX';
            foreach ($areas_condicion as $key => $value) {
                if ($area != $value->reportearea_nombre) {
                    $area = $value->reportearea_nombre;
                    $dato['total_puntosarea'] += ($value->total_puntosarea + 0);
                }


                $dato['tabla_reporte_6_2_1'] .= '<tr>
                                                    <td>' . $value->total_puntosarea . '</td>
                                                    <td>' . $value->reportearea_instalacion . '</td>
                                                    <td>' . $value->reportearea_nombre . '</td>
                                                    <td>' . $value->reportecategoria_nombre . '</td>
                                                    <td>' . $value->reportetemperaturaevaluacion_puesto . '</td>
                                                    <td>' . $value->reporteareacategoria_actividades . '</td>
                                                    <td>' . $value->regimen_texto . '</td>
                                                </tr>';
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
            $dato["tabla_5_4"] = NULL;
            $dato['tabla_reporte_7_1'] = NULL;
            $dato['tabla_reporte_6_2_1'] = NULL;
            $dato['total_puntosarea'] = 0;
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
    public function reportetemperaturaevaluacioncategorias($reportearea_id, $reportecategoria_id)
    {
        try {
            $areacategorias = DB::select('SELECT
                                                reportecategoria.proyecto_id,
                                                reporteareacategoria.reportearea_id,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportetemperaturaareacategoria.reportecategoria_id AS activo 
                                            FROM
                                                reporteareacategoria
                                                LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                RIGHT JOIN reportetemperaturaareacategoria ON reporteareacategoria.reportearea_id = reportetemperaturaareacategoria.reportearea_id 
                                                AND reportecategoria.id = reportetemperaturaareacategoria.reportecategoria_id 
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
     * @param  int  $reportetemperaturaevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reportetemperaturaevaluacioneliminar($reportetemperaturaevaluacion_id)
    {
        try {
            $area = reportetemperaturaevaluacionModel::where('id', $reportetemperaturaevaluacion_id)->delete();

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
    public function reportetemperaturamatriztabla($proyecto_id)
    {
        try {
            $numero_registro = 0;
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                $perforacion = 0;
                if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1) {
                    $perforacion = 1;
                }


                if ((($proyecto->catregion_id + 0) == 1 || ($proyecto->catregion_id + 0) == 2) && $perforacion == 0) {
                    $matriz = DB::select('SELECT
                                                reportetemperaturaevaluacion.proyecto_id,
                                                reportetemperaturaevaluacion.id,
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
                                                reportetemperaturaevaluacion.reportearea_id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_orden,
                                                reportearea.reportearea_nombre,
                                                reportetemperaturaevaluacion.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_ficha,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                (
                                                    SELECT
                                                        -- reporteareacategoria.reportearea_id,
                                                        SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = reportetemperaturaevaluacion.reportearea_id
                                                    LIMIT 1
                                                ) AS personas_area,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_I = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_I) AS reportetemperaturaevaluacion_I,
                                                IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_II = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_II) AS reportetemperaturaevaluacion_II,
                                                IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_III = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_III) AS reportetemperaturaevaluacion_III,
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE,
                                                (
                                                    IF(
                                                        (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_II+0) AND (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_III+0)
                                                        , (reportetemperaturaevaluacion_I+0)
                                                        , IF(
                                                                (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_I+0) AND (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_III+0)
                                                                , (reportetemperaturaevaluacion_II+0)
                                                                , (reportetemperaturaevaluacion_III+0)
                                                            )
                                                    )
                                                ) AS resultado_critico,
                                                (
                                                    IF(
                                                        (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                        OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                        OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                        , "Fuera de norma"
                                                        , "Dentro de norma"
                                                    )
                                                ) AS resultado 
                                            FROM
                                                reportetemperaturaevaluacion
                                                LEFT JOIN proyecto ON reportetemperaturaevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                INNER JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                            WHERE
                                                reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto ASC,
                                                reportearea.reportearea_orden ASC,
                                                reportearea.reportearea_nombre ASC,
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');


                    $punto = 'X';
                    foreach ($matriz as $key => $value) {
                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        $value->resultado_critico_limite = $value->resultado_critico . ' / ' . $value->reportetemperaturaevaluacion_LMPE;
                    }


                    $dato["data"] = $matriz;
                    $dato["total"] = count($matriz);
                } else {
                    $categorias = DB::select('SELECT
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    reportetemperaturaevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre 
                                                FROM
                                                    reportetemperaturaevaluacion
                                                    LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                GROUP BY
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    reportetemperaturaevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre
                                                ORDER BY
                                                    reportecategoria.reportecategoria_orden ASC');


                    $dato["matriz"] =  null;
                    foreach ($categorias as $key => $value) {
                        $registro = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.id,
                                                    TABLA.catregion_nombre,
                                                    TABLA.catsubdireccion_nombre,
                                                    TABLA.catgerencia_nombre,
                                                    TABLA.catactivo_nombre,
                                                    TABLA.gerencia_activo,
                                                    TABLA.reportearea_instalacion,
                                                    TABLA.reportecategoria_id,
                                                    TABLA.reportecategoria_orden,
                                                    TABLA.reportecategoria_nombre,
                                                    TABLA.reportetemperaturaevaluacion_trabajador,
                                                    TABLA.reportetemperaturaevaluacion_ficha,
                                                    TABLA.resultado_critico,
                                                    TABLA.reportetemperaturaevaluacion_LMPE,
                                                    TABLA.resultado
                                                FROM
                                                    (
                                                        SELECT
                                                            reportetemperaturaevaluacion.proyecto_id,
                                                            reportetemperaturaevaluacion.id,
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
                                                            reportetemperaturaevaluacion.reportearea_id,
                                                            reportearea.reportearea_instalacion,
                                                            reportearea.reportearea_orden,
                                                            reportearea.reportearea_nombre,
                                                            reportetemperaturaevaluacion.reportecategoria_id,
                                                            reportecategoria.reportecategoria_orden,
                                                            reportecategoria.reportecategoria_nombre,
                                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador,
                                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_ficha,
                                                            reporteareacategoria.reporteareacategoria_geh,
                                                            (
                                                                SELECT
                                                                    -- reporteareacategoria.reportearea_id,
                                                                    SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                                FROM
                                                                    reporteareacategoria
                                                                WHERE
                                                                    reporteareacategoria.reportearea_id = reportetemperaturaevaluacion.reportearea_id
                                                                LIMIT 1
                                                            ) AS personas_area,
                                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_I = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_I) AS reportetemperaturaevaluacion_I,
                                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_II = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_II) AS reportetemperaturaevaluacion_II,
                                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_III = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_III) AS reportetemperaturaevaluacion_III,
                                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE,
                                                            (
                                                                IF(
                                                                    (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_II+0) AND (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_III+0)
                                                                    , (reportetemperaturaevaluacion_I+0)
                                                                    , IF(
                                                                            (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_I+0) AND (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_III+0)
                                                                            , (reportetemperaturaevaluacion_II+0)
                                                                            , (reportetemperaturaevaluacion_III+0)
                                                                        )
                                                                )
                                                            ) AS resultado_critico,
                                                            (
                                                                IF(
                                                                    (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                                    , "Fuera de norma"
                                                                    , "Dentro de norma"
                                                                )
                                                            ) AS resultado 
                                                        FROM
                                                            reportetemperaturaevaluacion
                                                            LEFT JOIN proyecto ON reportetemperaturaevaluacion.proyecto_id = proyecto.id
                                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                            LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                            LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                            INNER JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                            AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                                        WHERE
                                                            reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                            AND reportetemperaturaevaluacion.reportecategoria_id = ' . $value->reportecategoria_id . ' 
                                                    ) AS TABLA
                                                -- WHERE
                                                    -- TABLA.resultado = "Fuera de norma"
                                                ORDER BY
                                                    TABLA.resultado_critico DESC
                                                LIMIT 1');


                        if (count($registro) > 0) {
                            $numero_registro += 1;


                            $dato["matriz"] .= '<tr>
                                                    <td>' . $numero_registro . '</td>
                                                    <td>' . $registro[0]->catsubdireccion_nombre . '</td>
                                                    <td>' . $registro[0]->gerencia_activo . '</td>
                                                    <td>' . $registro[0]->reportearea_instalacion . '</td>
                                                    <td>' . $registro[0]->reportetemperaturaevaluacion_trabajador . '</td>
                                                    <td>' . $registro[0]->reportetemperaturaevaluacion_ficha . '</td>
                                                    <td>' . $registro[0]->reportecategoria_nombre . '</td>
                                                    <td>' . $registro[0]->resultado_critico . ' / ' . $registro[0]->reportetemperaturaevaluacion_LMPE . '</td>
                                                </tr>';
                        }
                    }


                    $dato["total"] = $numero_registro;
                }
            } else {
                $matriz = DB::select('SELECT
                                            reportetemperaturaevaluacion.proyecto_id,
                                            reportetemperaturaevaluacion.id,
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
                                            reportetemperaturaevaluacion.reportearea_id,
                                            reportearea.reportearea_instalacion,
                                            reportearea.reportearea_orden,
                                            reportearea.reportearea_nombre,
                                            reportetemperaturaevaluacion.reportecategoria_id,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_ficha,
                                            reporteareacategoria.reporteareacategoria_geh,
                                            (
                                                SELECT
                                                    -- reporteareacategoria.reportearea_id,
                                                    SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                FROM
                                                    reporteareacategoria
                                                WHERE
                                                    reporteareacategoria.reportearea_id = reportetemperaturaevaluacion.reportearea_id
                                                LIMIT 1
                                            ) AS personas_area,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_I = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_I) AS reportetemperaturaevaluacion_I,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_II = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_II) AS reportetemperaturaevaluacion_II,
                                            IF(reportetemperaturaevaluacion.reportetemperaturaevaluacion_III = 0, "N/A", reportetemperaturaevaluacion.reportetemperaturaevaluacion_III) AS reportetemperaturaevaluacion_III,
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE,
                                            (
                                                IF(
                                                    (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_II+0) AND (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_III+0)
                                                    , (reportetemperaturaevaluacion_I+0)
                                                    , IF(
                                                            (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_I+0) AND (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_III+0)
                                                            , (reportetemperaturaevaluacion_II+0)
                                                            , (reportetemperaturaevaluacion_III+0)
                                                        )
                                                )
                                            ) AS resultado_critico,
                                            (
                                                IF(
                                                    (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                    OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                    , "Fuera de norma"
                                                    , "Dentro de norma"
                                                )
                                            ) AS resultado 
                                        FROM
                                            reportetemperaturaevaluacion
                                            LEFT JOIN proyecto ON reportetemperaturaevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                            INNER JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                            AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                        WHERE
                                            reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto ASC,
                                            reportearea.reportearea_orden ASC,
                                            reportearea.reportearea_nombre ASC,
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC');


                $punto = 'X';
                foreach ($matriz as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $value->resultado_critico_limite = $value->resultado_critico . ' / ' . $value->reportetemperaturaevaluacion_LMPE;
                }


                $dato["data"] = $matriz;
                $dato["total"] = count($matriz);
            }


            // respuesta
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
     * @return \Illuminate\Http\Response
     */
    public function reportetemperaturadashboard($proyecto_id)
    {
        try {
            $dato["dashboard_puntos"] = ' 0';
            $dato["dashboard_cumplimiento"] = '0%';
            $dato["dashboard_recomendaciones"] = ' 0';
            $dato["dashboard_distribucionpuntos"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';
            $dato["dashboard_categoriasevaluadas"] = '<b style="font-weight: 600; color: #000000;">Sin resultados</b>';


            //=====================================
            // TOTAL PUNTOS Y CUMPLIMIENTO NORMATIVO


            $cumplimiento = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            COUNT(TABLA.reportetemperaturaevaluacion_punto) AS total_puntos,
                                            SUM(IF(TABLA.resultado = "Dentro de norma", 1, 0)) AS dentro_norma,
                                            SUM(IF(TABLA.resultado = "Fuera de norma", 1, 0)) AS fuera_norma,
                                            ROUND((ROUND((SUM(IF(TABLA.resultado = "Dentro de norma", 1, 0)) / COUNT(TABLA.reportetemperaturaevaluacion_punto)), 3) * 100), 0) AS cumplimiento_normativo
                                        FROM
                                            (
                                                SELECT
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    reportetemperaturaevaluacion.id,
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
                                                    reportetemperaturaevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_orden,
                                                    reportearea.reportearea_nombre,
                                                    reportetemperaturaevaluacion.reportecategoria_id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_trabajador,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_ficha,
                                                    reporteareacategoria.reporteareacategoria_geh,
                                                    (
                                                        SELECT
                                                            -- reporteareacategoria.reportearea_id,
                                                            SUM(reporteareacategoria.reporteareacategoria_total) AS total
                                                        FROM
                                                            reporteareacategoria
                                                        WHERE
                                                            reporteareacategoria.reportearea_id = reportetemperaturaevaluacion.reportearea_id
                                                        LIMIT 1
                                                    ) AS personas_area,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_I,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_II,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_III,
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE,
                                                    (
                                                        IF(
                                                            (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_II+0) AND (reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion_III+0)
                                                            , (reportetemperaturaevaluacion_I+0)
                                                            , IF(
                                                                (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_I+0) AND (reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion_III+0)
                                                                , (reportetemperaturaevaluacion_II+0)
                                                                , (reportetemperaturaevaluacion_III+0)
                                                            )
                                                        )
                                                    ) AS resultado_critico,
                                                    (
                                                        IF(
                                                            (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                            OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                            OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                            , "Fuera de norma"
                                                            , "Dentro de norma"
                                                        )
                                                    ) AS resultado 
                                                FROM
                                                    reportetemperaturaevaluacion
                                                    LEFT JOIN proyecto ON reportetemperaturaevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                    INNER JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                    AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                                WHERE
                                                    reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                ORDER BY
                                                    reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto ASC,
                                                    reportearea.reportearea_orden ASC,
                                                    reportearea.reportearea_nombre ASC,
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC
                                            ) AS TABLA
                                        GROUP BY
                                            TABLA.proyecto_id');


            if (count($cumplimiento) > 0) {
                $dato["dashboard_puntos"] = ' ' . $cumplimiento[0]->total_puntos;
                $dato["dashboard_cumplimiento"] = $cumplimiento[0]->cumplimiento_normativo . '%';
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
                                                AND reporterecomendaciones.agente_nombre LIKE "%Temperatura%"');


            if (count($recomendaciones) > 0) {
                $dato['dashboard_recomendaciones'] = $recomendaciones[0]->totalrecomendaciones;
            }


            //=====================================
            // DISTRIBUCION DE PUNTOS DE EVALUACION


            $total_instalaciones = DB::select('SELECT
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    -- reportetemperaturaevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion 
                                                FROM
                                                    reportetemperaturaevaluacion
                                                    LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                WHERE
                                                    reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                GROUP BY
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    -- reportetemperaturaevaluacion.reportearea_id,
                                                    reportearea.reportearea_instalacion ');



            $distribucion_puntos = DB::select('SELECT
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    reportetemperaturaevaluacion.reportearea_id,
                                                    -- reportearea.reportearea_orden,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_nombre,
                                                    COUNT(reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto) AS total_puntos
                                                FROM
                                                    reportetemperaturaevaluacion
                                                    LEFT JOIN reportearea ON reportetemperaturaevaluacion.reportearea_id = reportearea.id
                                                WHERE
                                                    reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                GROUP BY
                                                    reportetemperaturaevaluacion.proyecto_id,
                                                    reportetemperaturaevaluacion.reportearea_id,
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


                $dato["dashboard_distribucionpuntos"] .= '<div class="' . $col . '" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . ';">● <b style="color: #333333;">' . $value->total_puntos . ' puntos.</b> - ' . $value->reportearea_nombre . '</div>';


                if (($key + 1) == count($distribucion_puntos)) {
                    $dato["dashboard_distribucionpuntos"] .= '<div class="col-6" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . ';">&nbsp;</div>';
                }
            }


            //=====================================
            // CATEGORIAS EVALUADAS


            $categorias_evaluadas = DB::select('SELECT
                                                     TABLA.proyecto_id,
                                                     TABLA.reportecategoria_id,
                                                     TABLA.reportecategoria_orden,
                                                     TABLA.reportecategoria_nombre,
                                                     IF(SUM(TABLA.resultado_critico) > 0, "#FF0000", "inherit") AS resultado_critico
                                                    FROM
                                                        (
                                                            SELECT
                                                                reportetemperaturaevaluacion.proyecto_id,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                                reportetemperaturaevaluacion.reportecategoria_id,
                                                                reportecategoria.reportecategoria_orden,
                                                                reportecategoria.reportecategoria_nombre,
                                                                (
                                                                    IF(
                                                                        (reportetemperaturaevaluacion.reportetemperaturaevaluacion_I+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                                        OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_II+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0) 
                                                                        OR (reportetemperaturaevaluacion.reportetemperaturaevaluacion_III+0) > (reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE+0)
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                ) AS resultado_critico 
                                                            FROM
                                                                reportetemperaturaevaluacion
                                                                LEFT JOIN reportecategoria ON reportetemperaturaevaluacion.reportecategoria_id = reportecategoria.id
                                                            WHERE
                                                                reportetemperaturaevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                            GROUP BY
                                                                reportetemperaturaevaluacion.proyecto_id,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_punto,
                                                                reportetemperaturaevaluacion.reportecategoria_id,
                                                                reportecategoria.reportecategoria_orden,
                                                                reportecategoria.reportecategoria_nombre,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_I,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_II,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_III,
                                                                reportetemperaturaevaluacion.reportetemperaturaevaluacion_LMPE
                                                        ) AS TABLA
                                                    GROUP BY
                                                        TABLA.proyecto_id,
                                                        TABLA.reportecategoria_id,
                                                        TABLA.reportecategoria_orden,
                                                        TABLA.reportecategoria_nombre
                                                    ORDER BY
                                                        TABLA.reportecategoria_orden ASC,
                                                        TABLA.reportecategoria_nombre ASC');


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


                $dato["dashboard_categoriasevaluadas"] .= '<div class="' . $col . '" style="display: inline-block; padding: 0px 1px; font-size: ' . $size . '; text-align: ' . $align . '; color: ' . $value->resultado_critico . '">● ' . $value->reportecategoria_nombre . '</div>';


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
    public function reportetemperaturarecomendacionestabla($proyecto_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);


            $tabla = DB::select('SELECT DISTINCT
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
                                                                        -- reporterecomendaciones.proyecto_id = ' . $proyecto_id . '
                                                                        reporterecomendaciones.reporterecomendacionescatalogo_id = reporterecomendacionescatalogo.id
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
                                                    reporterecomendaciones.agente_nombre = "' . $agente_nombre . '"
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
    public function reportetemperaturaresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reportetemperaturaModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportetemperatura_responsable1documento);
            } else {
                return Storage::download($reporte->reportetemperatura_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportetemperatura_responsable2documento);
            } else {
                return Storage::download($reporte->reportetemperatura_responsable2documento);
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
    public function reportetemperaturaplanostabla($proyecto_id, $agente_nombre)
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
    public function reportetemperaturaequipoutilizadotabla($proyecto_id, $agente_nombre)
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
                                            AND proyectoproveedores.catprueba_id = 3 -- Temperatura ------------------------------
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');


            $where_condicion = '';
            if (count($proveedor) > 0) {
                // $where_condicion = ' AND proyectoequiposactual.proveedor_id = '.$proveedor[0]->proveedor_id;  // SE DESHABILITO PORQUE SE MOSTRABA SOLO EL PROVEEDOR ASIGNADO AL AGENTE (GAMATEK) Y Y EL EQUIPO QUE SE USO ES DE (INTERTEK-GAMATEK), AHORA SE MUESTRAN TODOS LOS EQUIPOS DE TODOS LOS PROVEEDORES
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
    public function reportetemperaturaanexosresultadostabla($proyecto_id, $agente_nombre)
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
                                            LIMIT 1
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
    public function reportetemperaturaanexosacreditacionestabla($proyecto_id, $agente_nombre)
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
                                                        AND proyectoproveedores.catprueba_id = 3 
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
    public function reportetemperaturarevisionestabla($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 3
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
    public function reportetemperaturarevisionconcluir($reporte_id)
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

            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteregistro_id + 0) > 0) {
                $reporte = reportetemperaturaModel::findOrFail($request->reporteregistro_id);

                $dato["reporteregistro_id"] = $reporte->id;

                $reporte->update([
                    'reportetemperatura_instalacion' => $request->reporte_instalacion
                ]);


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                    ->where('agente_id', $request->agente_id)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                if (count($revision) > 0) {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 17) // Valida disponibilidad de esta version (20 CANCELACION REVISION)
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reportetemperatura AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reportetemperaturaModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportetemperatura_instalacion' => $request->reporte_instalacion,
                    'reportetemperatura_catregion_activo' => 1,
                    'reportetemperatura_catsubdireccion_activo' => 1,
                    'reportetemperatura_catgerencia_activo' => 1,
                    'reportetemperatura_catactivo_activo' => 1,
                    'reportetemperatura_concluido' => 0,
                    'reportetemperatura_cancelado' => 0
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
                    'reportetemperatura_catregion_activo' => $catregion_activo,
                    'reportetemperatura_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reportetemperatura_catgerencia_activo' => $catgerencia_activo,
                    'reportetemperatura_catactivo_activo' => $catactivo_activo,
                    'reportetemperatura_instalacion' => $request->reporte_instalacion,
                    'reportetemperatura_fecha' => $request->reporte_fecha,
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
                    // 'reportetemperatura_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion)
                    'reportetemperatura_introduccion' => $request->reporte_introduccion,
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
                    'reportetemperatura_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporte->update([
                    'reportetemperatura_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporte->update([
                    'reportetemperatura_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion + 0) == 6) {
                $reporte->update([
                    'reportetemperatura_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
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
                        'reportetemperatura_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 7) {
                $reporte->update([
                    'reportetemperatura_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion),
                    'reportetemperatura_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }

            /*
            // CATEGORIAS
            if (($request->opcion+0) == 8)
            {
                if (($request->reportecategoria_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reporteairecategoria AUTO_INCREMENT = 1;');

                    $request['recsensorialcategoria_id'] = 0;
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteairecategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reporteairecategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }
            */

            // AREAS
            if (($request->opcion + 0) == 8) {
                // dd($request->all());


                $area = reporteareaModel::findOrFail($request->reportearea_id);
                $area->update($request->all());


                $eliminar_categorias = reportetemperaturaareacategoriaModel::where('reportearea_id', $request->reportearea_id)->delete();


                if ($request->checkbox_categoria_id) {
                    DB::statement('ALTER TABLE reportetemperaturaareacategoria AUTO_INCREMENT = 1;');

                    foreach ($request->checkbox_categoria_id as $key => $value) {
                        $areacategoria = reportetemperaturaareacategoriaModel::create([
                            'reportearea_id' => $area->id,
                            'reportecategoria_id' => $value
                        ]);
                    }
                }


                $eliminar_maquinaria = reportetemperaturaareamaquinariaModel::where('reportearea_id', $request->reportearea_id)->delete();


                if ($request->reportetemperaturamaquinaria_nombre) {
                    DB::statement('ALTER TABLE reportetemperaturamaquinaria AUTO_INCREMENT = 1;');

                    foreach ($request->reportetemperaturamaquinaria_nombre as $key => $value) {
                        $areamaquinaria = reportetemperaturaareamaquinariaModel::create([
                            'reportearea_id' => $area->id,
                            'reportetemperaturamaquinaria_nombre' => $value,
                            'reportetemperaturamaquinaria_cantidad' => $request['reportetemperaturamaquinaria_cantidad'][$key]
                        ]);
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos modificados correctamente';
            }


            // PUNTO DE EVALUACION
            if (($request->opcion + 0) == 9) {
                // dd($request->all());
                if (($request->reportetemperaturaevaluacion_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportetemperaturaevaluacion AUTO_INCREMENT = 1;');


                    $punto = reportetemperaturaevaluacionModel::create($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $punto = reportetemperaturaevaluacionModel::findOrFail($request->reportetemperaturaevaluacion_id);
                    $punto->update($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // CONCLUSION
            if (($request->opcion + 0) == 10) {
                $reporte->update([
                    'reportetemperatura_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
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
                    'reportetemperatura_responsable1' => $request->reporte_responsable1,
                    'reportetemperatura_responsable1cargo' => $request->reporte_responsable1cargo,
                    'reportetemperatura_responsable2' => $request->reporte_responsable2,
                    'reportetemperatura_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporte->update([
                        'reportetemperatura_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportetemperatura_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
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
                        'reportetemperatura_responsable1documento' => $destinoPath
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
                        'reportetemperatura_responsable2documento' => $destinoPath
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
                if ($request->equipoutilizado_checkbox) {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                        ->where('agente_nombre', $request->agente_nombre)
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
