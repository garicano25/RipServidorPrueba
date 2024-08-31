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
use App\modelos\reportes\reportequimicoscatalogoModel;
use App\modelos\reportes\reportequimicosModel;
use App\modelos\reportes\reportequimicosproyectoModel;
//-----------------------
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportequimicoscategoriaModel;
use App\modelos\reportes\reportequimicosareaModel;
use App\modelos\reportes\reportequimicosareacategoriaModel;
use App\modelos\reportes\reportequimicoseppModel;
use App\modelos\reportes\reportequimicosevaluacionModel;
use App\modelos\reportes\reportequimicosevaluacionparametroModel;
use App\modelos\reportes\reportequimicosmetodomuestreoModel;
use App\modelos\reportes\reportequimicosconclusionModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reportequimicosparametroscatalogoModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\catreportequimicospartidasModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\reportes\reportequimicosgruposModel;


use App\modelos\reportes\recursosPortadasInformesModel;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reportequimicosController extends Controller
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
    public function reportequimicosvista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Químicos primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15) // Químicos
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (count($revision) == 0) {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $proyecto_id,
                    'agente_id' => 15,
                    'agente_nombre' => 'Químicos',
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


            // COPIAR QUIMICOS DEL PROYECTO
            //===================================================


            $total_quimicos = DB::select('SELECT
                                                COUNT(reportequimicosproyecto.id) AS total 
                                            FROM
                                                reportequimicosproyecto
                                            WHERE
                                                reportequimicosproyecto.proyecto_id = ' . $proyecto_id);

            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================
            if (($total_quimicos[0]->total + 0) == 0) {

                $quimicos = DB::select('SELECT
                                            proyectoproveedores.id,
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id,
                                            proyectoproveedores.proyectoproveedores_tipoadicional,
                                            proyectoproveedores.catprueba_id,
                                            proyectoproveedores.proyectoproveedores_agente,
                                            proyectoproveedores.proyectoproveedores_puntos,
                                            proyectoproveedores.proyectoproveedores_observacion 
                                        FROM
                                            proyectoproveedores 
                                        WHERE
                                            proyectoproveedores.proyecto_id = ? 
                                            AND proyectoproveedores.catprueba_id = 15
                                            AND proyectoproveedores.proyectoproveedores_agente NOT LIKE "%blanco%"
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_agente ASC', [$proyecto_id]);


                DB::statement('ALTER TABLE reportequimicosproyecto AUTO_INCREMENT = 1;');


                foreach ($quimicos as $key => $value) {
                    $quimico = reportequimicosproyectoModel::create([
                        'proyecto_id' => $proyecto_id,
                        'reportequimicosproyecto_parametro' => $value->proyectoproveedores_agente,
                        'cantidad' => $value->proyectoproveedores_puntos
                    ]);
                }
            } else {

                $faltantes = DB::select('CALL sp_validar_parametros_faltantes_g(?,?)', [$proyecto_id, $proyecto->recsensorial_id]);
            }

            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================








            //CATEGORIAS POE
            //===================================================


            $categorias = DB::select('SELECT
                                            reportequimicoscategoria.proyecto_id, 
                                            reportequimicoscategoria.registro_id, 
                                            reportequimicoscategoria.id, 
                                            reportequimicoscategoria.reportequimicoscategoria_nombre, 
                                            reportequimicoscategoria.reportequimicoscategoria_total
                                        FROM
                                            reportequimicoscategoria
                                        WHERE
                                            reportequimicoscategoria.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportequimicoscategoria.reportequimicoscategoria_nombre ASC');


            if (count($categorias) > 0) {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            } else {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //===================================================


            $areas = DB::select('SELECT
                                    reportequimicosarea.proyecto_id, 
                                    reportequimicosarea.registro_id, 
                                    reportequimicosarea.id, 
                                    reportequimicosarea.reportequimicosarea_instalacion, 
                                    reportequimicosarea.reportequimicosarea_nombre, 
                                    reportequimicosarea.reportequimicosarea_numorden, 
                                    reportequimicosarea.reportequimicosarea_porcientooperacion
                                FROM
                                    reportequimicosarea
                                WHERE
                                    reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                ORDER BY
                                    reportequimicosarea.reportequimicosarea_numorden ASC,
                                    reportequimicosarea.reportequimicosarea_nombre ASC');


            if (count($areas) > 0) {
                $areas_poe = 0; // NO TIENE POE GENERAL
            } else {
                $areas_poe = 1; // TIENE POE GENERAL
            }


            //-------------------------------------


            // $categorias_poe = 1; // TIENE POE GENERAL
            // $areas_poe = 1; // TIENE POE GENERAL


            //===================================================


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();

            // Vista
            return view('reportes.parametros.reportequimicos', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe'));
        }
    }


    public function datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace($quimicos_nombre, 'QUIMICOS_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        // $texto = str_replace('C.P. '.$recsensorial->recsensorial_codigopostal, 'INSTALACION_CODIGOPOSTAL', $texto);
        // $texto = str_replace($recsensorial->recsensorial_coordenadas, 'INSTALACION_COORDENADAS', $texto);
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


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace('QUIMICOS_NOMBRE', $quimicos_nombre, $texto);
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


    public function quimicosnombre($proyecto_id, $reporteregistro_id)
    {
        $quimicos = DB::select('SELECT
                                    reportequimicosgrupos.proyecto_id,
                                    reportequimicosgrupos.registro_id,
                                    reportequimicosgrupos.proveedor_id,
                                    proveedor.proveedor_NombreComercial,
                                    reportequimicosgrupos.catreportequimicospartidas_id,
                                    catreportequimicospartidas.catreportequimicospartidas_numero,
                                    catreportequimicospartidas.catreportequimicospartidas_descripcion,
                                    reportequimicosgrupos.reportequimicosproyecto_id,
                                    reportequimicosproyecto.reportequimicosproyecto_parametro 
                                FROM
                                    reportequimicosgrupos
                                    LEFT JOIN proveedor ON reportequimicosgrupos.proveedor_id = proveedor.id
                                    LEFT JOIN catreportequimicospartidas ON reportequimicosgrupos.catreportequimicospartidas_id = catreportequimicospartidas.id
                                    LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                WHERE
                                    reportequimicosgrupos.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportequimicosgrupos.registro_id = ' . $reporteregistro_id . ' 
                                ORDER BY
                                    reportequimicosgrupos.catreportequimicospartidas_id ASC,
                                    reportequimicosproyecto.reportequimicosproyecto_parametro ASC');


        if (count($quimicos) == 0) {
            $quimicos = DB::select('SELECT
                                        reportequimicosproyecto.id,
                                        reportequimicosproyecto.proyecto_id,
                                        reportequimicosproyecto.registro_id,
                                        reportequimicosproyecto.reportequimicosproyecto_parametro 
                                    FROM
                                        reportequimicosproyecto 
                                    WHERE
                                        reportequimicosproyecto.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosproyecto.registro_id = ' . $reporteregistro_id . '
                                    ORDER BY
                                        reportequimicosproyecto.reportequimicosproyecto_parametro ASC');


            if (count($quimicos) == 0) {
                $quimicos = DB::select('SELECT
                                            reportequimicosproyecto.id,
                                            reportequimicosproyecto.proyecto_id,
                                            reportequimicosproyecto.registro_id,
                                            reportequimicosproyecto.reportequimicosproyecto_parametro 
                                        FROM
                                            reportequimicosproyecto 
                                        WHERE
                                            reportequimicosproyecto.proyecto_id = ' . $proyecto_id . '
                                        ORDER BY
                                            reportequimicosproyecto.reportequimicosproyecto_parametro ASC');
            }
        }


        $quimicos_nombre = '';
        foreach ($quimicos as $key => $value) {
            if (($key + 0) > 0) {
                $quimicos_nombre .= ', ' . $value->reportequimicosproyecto_parametro;
            } else {
                $quimicos_nombre .= $value->reportequimicosproyecto_parametro;
            }
        }

        return $quimicos_nombre;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosdatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reportequimicoscatalogoModel::limit(1)->get();
            $reporte  = reportequimicosModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reportequimicos_revision', 'DESC')
                ->limit(1)
                ->get();


            if (count($reporte) > 0) {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = $reporte->id;
            } else {
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reportequimicosModel::where('catactivo_id', $proyecto->catactivo_id)
                        ->orderBy('proyecto_id', 'DESC')
                        ->orderBy('reportequimicos_revision', 'DESC')
                        ->limit(1)
                        ->get();
                } else {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reportequimicos.id,
                                                reportequimicos.proyecto_id,
                                                reportequimicos.agente_id,
                                                reportequimicos.agente_nombre,
                                                reportequimicos.catactivo_id,
                                                reportequimicos.reportequimicos_revision,
                                                reportequimicos.reportequimicos_fecha,
                                                reportequimicos.reporte_mes,

                                                reportequimicos.reportequimicos_instalacion,
                                                reportequimicos.reportequimicos_catregion_activo,
                                                reportequimicos.reportequimicos_catsubdireccion_activo,
                                                reportequimicos.reportequimicos_catgerencia_activo,
                                                reportequimicos.reportequimicos_catactivo_activo,
                                                reportequimicos.reportequimicos_introduccion,
                                                reportequimicos.reportequimicos_objetivogeneral,
                                                reportequimicos.reportequimicos_objetivoespecifico,
                                                reportequimicos.reportequimicos_metodologia_4_1,
                                                reportequimicos.reportequimicos_metodologia_4_2,
                                                reportequimicos.reportequimicos_ubicacioninstalacion,
                                                reportequimicos.reportequimicos_ubicacionfoto,
                                                reportequimicos.reportequimicos_procesoinstalacion,
                                                reportequimicos.reportequimicos_actividadprincipal,
                                                reportequimicos.reportequimicos_conclusion,
                                                reportequimicos.reportequimicos_responsable1,
                                                reportequimicos.reportequimicos_responsable1cargo,
                                                reportequimicos.reportequimicos_responsable1documento,
                                                reportequimicos.reportequimicos_responsable2,
                                                reportequimicos.reportequimicos_responsable2cargo,
                                                reportequimicos.reportequimicos_responsable2documento,
                                                reportequimicos.reportequimicos_concluido,
                                                reportequimicos.reportequimicos_concluidonombre,
                                                reportequimicos.reportequimicos_concluidofecha,
                                                reportequimicos.reportequimicos_cancelado,
                                                reportequimicos.reportequimicos_canceladonombre,
                                                reportequimicos.reportequimicos_canceladofecha,
                                                reportequimicos.reportequimicos_canceladoobservacion,
                                                reportequimicos.created_at,
                                                reportequimicos.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportequimicos ON proyecto.id = reportequimicos.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = ' . $recsensorial->cliente_id . '  
                                                AND reportequimicos.reportequimicos_instalacion <> "" 
                                            ORDER BY
                                                reportequimicos.updated_at DESC');
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
                ->where('agente_id', 15) //Quimicos
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


            // QUIMICOS LISTA
            //===================================================


            $quimicos_nombre = $this->quimicosnombre($proyecto_id, $dato['reporteregistro_id']);


            // PORTADA
            //===================================================

            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente + 0);

            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_fecha != NULL && $reporte->proyecto_id == $proyecto_id) {
                $reportefecha = $reporte->reportequimicos_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                    'reporte_catregion_activo' => $reporte->reportequimicos_catregion_activo,
                    'catregion_id' => $proyecto->catregion_id,
                    'reporte_catsubdireccion_activo' => $reporte->reportequimicos_catsubdireccion_activo,
                    'catsubdireccion_id' => $proyecto->catsubdireccion_id,
                    'reporte_catgerencia_activo' => $reporte->reportequimicos_catgerencia_activo,
                    'catgerencia_id' => $proyecto->catgerencia_id,
                    'reporte_catactivo_activo' => $reporte->reportequimicos_catactivo_activo,
                    'catactivo_id' => $proyecto->catactivo_id,
                    'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion,
                    'reporte_fecha' => $reportefecha,
                    'reporte_mes' => $reporte->reporte_mes

                );
            } else {
                $reportefecha = $meses[($proyectofecha[1] + 0)] . " del " . $proyectofecha[0];
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


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_introduccion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_introduccion_guardado'] = 1;
                } else {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reportequimicos_introduccion;
            } else {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo[0]->reportequimicoscatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_objetivogeneral != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                } else {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reportequimicos_objetivogeneral;
            } else {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo[0]->reportequimicoscatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_objetivoespecifico != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                } else {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reportequimicos_objetivoespecifico;
            } else {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo[0]->reportequimicoscatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_metodologia_4_1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reportequimicos_metodologia_4_1;
            } else {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo[0]->reportequimicoscatalogo_metodologia_4_1;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_metodologia_4_2 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_metodologia_4_2_guardado'] = 1;
                } else {
                    $dato['reporte_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reporte->reportequimicos_metodologia_4_2;
            } else {
                $dato['reporte_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportecatalogo[0]->reportequimicoscatalogo_metodologia_4_2;
            }

            $dato['reporte_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $metodologia_4_2);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_ubicacioninstalacion != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                } else {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reportequimicos_ubicacioninstalacion;
            } else {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo[0]->reportequimicoscatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_ubicacionfoto != NULL && $reporte->proyecto_id == $proyecto_id) {
                $ubicacionfoto = $reporte->reportequimicos_ubicacionfoto;
            }

            $dato['reporte_ubicacioninstalacion'] = array(
                'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $ubicacion),
                'ubicacionfoto' => $ubicacionfoto
            );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_procesoinstalacion != NULL && $reporte->proyecto_id == $proyecto_id) {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reportequimicos_procesoinstalacion;
            } else {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_actividadprincipal != NULL && $reporte->proyecto_id == $proyecto_id) {
                $procesoinstalacion = $reporte->reportequimicos_actividadprincipal;
            } else {
                $procesoinstalacion = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $procesoinstalacion);


            // CONCLUSION
            //===================================================
            $idConclusion = DB::select('SELECT * FROM reportequimicosconclusion WHERE proyecto_id = ? LIMIT 1', [$proyecto_id]);

            if (count($idConclusion) > 0) {

                $dato['reporte_conclusion_guardado'] = 1;
                $dato['reporte_conclusion_id'] = $idConclusion[0]->id;
                $conclusion = $idConclusion[0]->reportequimicosconclusion_conclusion;
                $dato['reporte_conclusion'] = $conclusion;
            } else {

                $dato['reporte_conclusion_guardado'] = 0;
                $dato['reporte_conclusion_id'] = 0;
                $conclusion = $reportecatalogo[0]->reportequimicoscatalogo_conclusion;
                $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $conclusion);
            }



            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportequimicos_responsable1 != NULL) {
                if ($reporte->proyecto_id == $proyecto_id) {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                } else {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                    'responsable1' => $reporte->reportequimicos_responsable1,
                    'responsable1cargo' => $reporte->reportequimicos_responsable1cargo,
                    'responsable1documento' => $reporte->reportequimicos_responsable1documento,
                    'responsable2' => $reporte->reportequimicos_responsable2,
                    'responsable2cargo' => $reporte->reportequimicos_responsable2cargo,
                    'responsable2documento' => $reporte->reportequimicos_responsable2documento,
                    'proyecto_id' => $reporte->proyecto_id,
                    'registro_id' => $reporte->id
                );
            } else {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reportequimicosModel::where('reportequimicos_responsable1', '!=', '')
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->get();

                if (count($reportehistorial) > 0 && $reportehistorial[0]->reportequimicos_responsable1 != NULL) {
                    $dato['reporte_responsablesinforme'] = array(
                        'responsable1' => $reportehistorial[0]->reportequimicos_responsable1,
                        'responsable1cargo' => $reportehistorial[0]->reportequimicos_responsable1cargo,
                        'responsable1documento' => $reportehistorial[0]->reportequimicos_responsable1documento,
                        'responsable2' => $reportehistorial[0]->reportequimicos_responsable2,
                        'responsable2cargo' => $reportehistorial[0]->reportequimicos_responsable2cargo,
                        'responsable2documento' => $reportehistorial[0]->reportequimicos_responsable2documento,
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
                                                    IFNULL(COUNT(proyectoevidenciafoto.proyectoevidenciafoto_archivo), 0) AS total
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
     * @param  int $reporteregistro_id
     * @return \Illuminate\Http\Response
     */
    public function reportequimicostabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            //==========================================


            $quimicos = DB::select('SELECT
                                        reportequimicosproyecto.id,
                                        reportequimicosproyecto.proyecto_id,
                                        reportequimicosproyecto.registro_id,
                                        reportequimicosproyecto.reportequimicosproyecto_parametro 
                                    FROM
                                        reportequimicosproyecto 
                                    WHERE
                                        reportequimicosproyecto.proyecto_id = ' . $proyecto_id . ' 
                                        -- AND reportequimicosproyecto.registro_id = ' . $reporteregistro_id . ' 
                                    ORDER BY
                                        reportequimicosproyecto.reportequimicosproyecto_parametro ASC');


            // if (count($quimicos) == 0)
            // {
            //     $quimicos = DB::select('SELECT
            //                                 reportequimicosproyecto.id,
            //                                 reportequimicosproyecto.proyecto_id,
            //                                 reportequimicosproyecto.registro_id,
            //                                 reportequimicosproyecto.reportequimicosproyecto_parametro 
            //                             FROM
            //                                 reportequimicosproyecto 
            //                             WHERE
            //                                 reportequimicosproyecto.proyecto_id = '.$proyecto_id.' 
            //                             ORDER BY
            //                                 reportequimicosproyecto.reportequimicosproyecto_parametro ASC');
            // }


            $numero_registro = 0; // $quimicos_lista = array();
            foreach ($quimicos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                // if ($edicion == 1)
                // {
                //     $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                // }
                // else
                // {
                //     $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                // }


                // $quimicos_lista[] = $value->reportequimicosproyecto_parametro;
            }

            // respuesta
            $dato['data'] = $quimicos;
            // $dato['quimicos_lista'] = $quimicos_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            // $dato['quimicos_lista'] = 0;
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteregistro_id     
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosgrupostabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            $proveedores = DB::select('SELECT
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
                                            AND proyectoproveedores.catprueba_id = 15
                                        GROUP BY
                                            proyectoproveedores.proyecto_id,
                                            proyectoproveedores.proveedor_id,
                                            proveedor.proveedor_NombreComercial
                                        ORDER BY
                                            proveedor.proveedor_NombreComercial ASC');


            $dato['proveedores_opciones'] = '<option value=""></option>';
            foreach ($proveedores as $key => $value) {
                $dato['proveedores_opciones'] .= '<option value="' . $value->proveedor_id . '">' . $value->proveedor_NombreComercial . '</option>';
            }


            //============================================


            $proyecto = proyectoModel::with(['recsensorial'])->findOrFail($proyecto_id);


            // $partidas = DB::select('SELECT
            //                             catreportequimicospartidas.id,
            //                             catreportequimicospartidas.catreportequimicospartidas_numero,
            //                             catreportequimicospartidas.catreportequimicospartidas_descripcion,
            //                             catreportequimicospartidas.catreportequimicospartidas_activo 
            //                         FROM
            //                             catreportequimicospartidas
            //                         WHERE
            //                             catreportequimicospartidas.catreportequimicospartidas_activo = 1
            //                         ORDER BY
            //                             catreportequimicospartidas.id ASC');


            // $dato['catpartidasquimicos'] = '<option value=""></option>';
            // foreach ($partidas as $key => $value)
            // {
            //     $dato['catpartidasquimicos'] .= '<option value="'.$value->id.'">'.$value->catreportequimicospartidas_numero.'.- '.$value->catreportequimicospartidas_descripcion.'</option>';
            // }


            $partidas = DB::select("SELECT
                                cc.CLIENTE_ID, 
                                clientepartidas.id, 
                                clientepartidas.catprueba_id, 
                                clientepartidas.clientepartidas_tipo, 
                                clientepartidas.clientepartidas_nombre, 
                                clientepartidas.clientepartidas_descripcion
                                FROM
                                contratos_partidas  clientepartidas
                                LEFT JOIN contratos_clientes cc ON cc.ID_CONTRATO = clientepartidas.CONTRATO_ID
                                WHERE
                                cc.CLIENTE_ID = " . $proyecto->recsensorial->cliente_id . "
                                AND clientepartidas.clientepartidas_tipo = 2 -- 1 = RECONOCIMIENTO, 2 = INFORME
                                AND clientepartidas.catprueba_id = 15 -- QUIMICOS
                                ORDER BY
                                clientepartidas.id ASC,
                                clientepartidas.clientepartidas_descripcion ASC");

            $dato['catpartidasquimicos'] = '<option value=""></option>';

            // Verificamos si la consulta devolvió algún resultado
            if (count($partidas) > 0) {
                foreach ($partidas as $key => $value) {
                    $dato['catpartidasquimicos'] .= '<option value="' . $value->id . '">' . $value->clientepartidas_descripcion . '</option>';
                }
            } else {
                // Si no hay partidas, agregamos una opción indicando que no hay contratos
                $dato['catpartidasquimicos'] = '<option value="">Reconocimiento sin contrato</option>';
            }


            //============================================


            $parametros = DB::select('SELECT
                                            reportequimicosproyecto.id,
                                            reportequimicosproyecto.proyecto_id,
                                            reportequimicosproyecto.registro_id,
                                            reportequimicosproyecto.reportequimicosproyecto_parametro ,
                                            reportequimicosproyecto.cantidad 
                                        FROM
                                            reportequimicosproyecto 
                                        WHERE
                                            reportequimicosproyecto.proyecto_id = ' . $proyecto_id . ' 
                                            -- AND reportequimicosproyecto.registro_id = ' . $reporteregistro_id . '
                                        ORDER BY
                                            reportequimicosproyecto.reportequimicosproyecto_parametro ASC');



            $dato['parametros_checkbox'] = '';
            foreach ($parametros as $key => $value) {
                $dato['parametros_checkbox'] .= '<div class="col-6">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="hidden" class="checkbox_parametro" id="parametro_' . $value->id . '" name="parametro[]" value="' . $value->id . '">
                                                            <input type="checkbox" class="checkbox_parametro" value="' . $value->id . '" checked disabled>
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;">' . $value->reportequimicosproyecto_parametro . ' [ Cantidad: ' . $value->cantidad . '] </label>
                                                </div>';
            }


            //============================================


            $grupos = DB::select('SELECT
                                        reportequimicosgrupos.proyecto_id,
                                        reportequimicosgrupos.registro_id,
                                        reportequimicosgrupos.proveedor_id,
                                        proveedor.proveedor_NombreComercial,
                                        reportequimicosgrupos.catreportequimicospartidas_id,
                                        reportequimicosproyecto.cantidad,
                                        reportequimicosgrupos.reportequimicosproyecto_id,
                                        reportequimicosproyecto.reportequimicosproyecto_parametro,
                                        (
                                            CASE
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Benceno%" THEN 1
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Tolueno%" THEN 2
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Xileno%" THEN 3
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Hexano%" THEN 1
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Ciclohexano%" THEN 2
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Metano%" THEN 1
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Etano%" THEN 2
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Propano%" THEN 3
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Butano%" THEN 4
                                                WHEN reportequimicosproyecto.reportequimicosproyecto_parametro LIKE "Pentano%" THEN 5
                                                ELSE 0
                                            END
                                        ) AS orden 
                                    FROM
                                        reportequimicosgrupos
                                        LEFT JOIN proveedor ON reportequimicosgrupos.proveedor_id = proveedor.id
                                        -- LEFT JOIN clientepartidas ON reportequimicosgrupos.catreportequimicospartidas_id = clientepartidas.id
                                        LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                    WHERE
                                        reportequimicosgrupos.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosgrupos.registro_id = ' . $reporteregistro_id . '  
                                    ORDER BY
                                        orden ASC,
                                        reportequimicosproyecto.reportequimicosproyecto_parametro ASC');


            $quimicos_lista = array();
            $partida = 'XXXXX';
            $dato['catpartidasquimicos_utilizadas'] = '<option value=""></option>';
            foreach ($grupos as $key => $value) {
                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if (($edicion + 0) == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }


                $quimicos_lista[] = $value->reportequimicosproyecto_parametro;


                // if ($partida != $value->clientepartidas_descripcion)
                // {
                //     $partida = $value->clientepartidas_descripcion;

                //     $dato['catpartidasquimicos_utilizadas'] .= '<option value="'.$value->catreportequimicospartidas_id.'">'.$value->clientepartidas_descripcion.'</option>';
                // }
            }


            // respuesta
            $dato['data'] = $grupos;
            $dato["total"] = count($grupos);
            $dato['quimicos_lista'] = $quimicos_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato['quimicos_lista'] = 0;
            $dato['proveedores_opciones'] = '<option value="">Error al consultar proveedores</option>';
            $dato['catpartidasquimicos'] = '<option value="">Error al consultar partidas</option>';
            $dato['catpartidasquimicos_utilizadas'] = '<option value="">Error al consultar partidas</option>';
            $dato['parametros_opciones'] = '<option value="">Error al consultar parametros</option>';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $partida_id
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosgrupoeliminar($proyecto_id, $reporteregistro_id, $partida_id)
    {
        try {
            $eliminar_grupo = reportequimicosgruposModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
                ->where('catreportequimicospartidas_id', $partida_id)
                ->delete();

            // respuesta
            $dato["msj"] = 'Grupo de químicos eliminado correctamente';
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
    public function reportequimicostabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();


            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporterevisiones_concluido == 1 || $reporte[0]->reporterevisiones_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }



            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
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
    public function reportequimicosdefinicioneliminar($definicion_id)
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
    public function reportequimicosmapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reportequimicosModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0) {
            return Storage::response($reporte->reportequimicos_ubicacionfoto);
        } else {
            return Storage::download($reporte->reportequimicos_ubicacionfoto);
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
    public function reportequimicoscategorias($proyecto_id, $reporteregistro_id, $areas_poe)
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


                    $value->reportequimicoscategoria_nombre = $value->reportecategoria_nombre;
                    $value->reportequimicoscategoria_total = $value->reportecategoria_total;


                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }


                $total_singuardar = 1;
            } else {
                // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();


                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                    ->where('agente_id', 15)
                    ->orderBy('reporterevisiones_revision', 'DESC')
                    ->get();


                $edicion = 1;
                if (count($revision) > 0) {
                    if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                        $edicion = 0;
                    }
                }


                //==========================================


                $categorias = reportequimicoscategoriaModel::where('proyecto_id', $proyecto_id)
                    ->where('registro_id', $reporteregistro_id)
                    ->orderBy('reportequimicoscategoria_nombre', 'ASC')
                    ->get();

                if (count($categorias) == 0) {
                    $categorias = reportequimicoscategoriaModel::where('proyecto_id', $proyecto_id)
                        ->orderBy('reportequimicoscategoria_nombre', 'ASC')
                        ->get();
                }


                foreach ($categorias as $key => $value) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
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
    public function reportequimicoscategoriaeliminar($categoria_id)
    {
        try {
            $categoria = reportequimicoscategoriaModel::where('id', $categoria_id)->delete();

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
    public function reportequimicosareas($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $total_singuardar = 0;
            $instalacion = 'XXX';
            $area = 'XXX';
            $area2 = 'XXX';
            $selectareasoption = '<option value=""></option>';
            $tabla_5_5 = '';
            $tabla_5_6 = '';
            $tabla_6_1 = '';


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                            reportearea.proyecto_id,
                                            reportearea.id,
                                            reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                                            reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                            reportearea.reportearea_orden AS reportequimicosarea_numorden,
                                            reportearea.reportearea_porcientooperacion,
                                            IF( IFNULL( reportearea.reportearea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportearea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                            reportearea.reportequimicosarea_porcientooperacion,
                                            reportearea.reportearea_caracteristica AS reportequimicosarea_caracteristica,
                                            reportearea.reportearea_maquinaria AS reportequimicosarea_maquinaria,
                                            reportearea.reportearea_contaminante AS reportequimicosarea_contaminante,
                                            reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
                                            reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
                                            reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                            IFNULL((
                                                SELECT
                                                    IF(reportequimicosareacategoria.reportequimicoscategoria_id, "activo", "") AS checked
                                                FROM
                                                    reportequimicosareacategoria
                                                WHERE
                                                    reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
                                                    AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                                                    AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . '
                                                LIMIT 1
                                            ), "") AS activo,
                                            reporteareacategoria.reporteareacategoria_total AS reportequimicosareacategoria_total,
                                            reporteareacategoria.reporteareacategoria_geh AS reportequimicosareacategoria_geh,
                                            reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades  
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
                    if ($area != $value->reportequimicosarea_nombre) {
                        $area = $value->reportequimicosarea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reportequimicosarea_porcientooperacion > 0) {
                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>' . $value->reportequimicosarea_instalacion . '</td>
                                                <td>' . $value->reportequimicosarea_nombre . '</td>
                                                <td>' . $value->reportequimicosarea_porcientooperacion . ' %</td>
                                            </tr>';
                        }
                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    if ($value->activo) {
                        $value->reportecategoria_nombre_texto = '<span class="text-danger">' . $value->reportequimicoscategoria_nombre . '</span>';
                    } else {
                        $value->reportecategoria_nombre_texto = $value->reportequimicoscategoria_nombre;
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';


                    if ($value->reportequimicosarea_porcientooperacion === NULL) {
                        $total_singuardar += 1;
                    }


                    if ($value->reportequimicosarea_porcientooperacion > 0) {
                        if ($value->activo) {
                            //TABLA 5.5.- Actividades del personal expuesto
                            //==================================================


                            $tabla_5_5 .= '<tr>
                                                <td>' . $value->reportequimicosarea_instalacion . '</td>
                                                <td>' . $value->reportequimicosarea_nombre . '</td>
                                                <td>' . $value->reportequimicoscategoria_nombre . '</td>
                                                <td class="justificado">' . $value->reportequimicosareacategoria_actividades . '</td>
                                            </tr>';


                            //TABLA 5.6.- Fuentes generadoras
                            //==================================================


                            $tabla_5_6 .= '<tr>
                                                <td>' . $value->reportequimicosarea_instalacion . '</td>
                                                <td>' . $value->reportequimicosarea_nombre . '</td>
                                                <td>' . $value->reportequimicosarea_maquinaria . '</td>
                                                <td class="justificado">' . $value->reportequimicosarea_contaminante . '</td>
                                                <td>' . $value->reportequimicoscategoria_nombre . '</td>';

                            if (($value->reportequimicosarea_caracteristica + 0) == 0) {
                                $tabla_5_6 .= '<td>No</td>
                                                                    <td>Si</td>';
                            } else {
                                $tabla_5_6 .= '<td>Si</td>
                                                                    <td>No</td>';
                            }

                            $tabla_5_6 .= '</tr>';
                        }


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reportequimicosarea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reportequimicosarea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }


                        if ($instalacion != $value->reportequimicosarea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reportequimicosarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportequimicosarea_nombre) {
                            $area2 = $value->reportequimicosarea_nombre;
                            $selectareasoption .= '<option value="' . $value->id . '">' . $area2 . '</option>';
                        }


                        if ($key == (count($areas) - 1)) {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            } else {
                // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();


                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                    ->where('agente_id', 15)
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
                                            COUNT(reportequimicosarea.id) AS total
                                        FROM
                                            reportequimicosarea
                                        WHERE
                                            reportequimicosarea.proyecto_id = ' . $proyecto_id . '
                                            AND reportequimicosarea.registro_id = ' . $reporteregistro_id);

                $where_condicion = '';
                if (($registros[0]->total + 0) > 0) {
                    $where_condicion = 'AND reportequimicosarea.registro_id = ' . $reporteregistro_id;
                }


                //==========================================


                $areas = DB::select('SELECT
                                        reportequimicosarea.id,
                                        reportequimicosarea.proyecto_id,
                                        reportequimicosarea.registro_id,
                                        reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                        reportequimicosarea.recsensorialarea_id,
                                        reportequimicosarea.reportequimicosarea_instalacion,
                                        reportequimicosarea.reportequimicosarea_nombre,
                                        reportequimicosarea.reportequimicosarea_numorden,
                                        reportequimicosarea.reportequimicosarea_porcientooperacion,
                                        reportequimicosarea.reportequimicosarea_maquinaria,
                                        reportequimicosarea.reportequimicosarea_contaminante,
                                        reportequimicosarea.reportequimicosarea_caracteristica,
                                        reportequimicosareacategoria.reportequimicoscategoria_id,
                                        reportequimicoscategoria.reportequimicoscategoria_nombre,
                                        reportequimicosareacategoria.reportequimicosareacategoria_total, 
                                        reportequimicosareacategoria.reportequimicosareacategoria_actividades 
                                    FROM
                                        reportequimicosarea
                                        LEFT OUTER JOIN reportequimicosareacategoria ON reportequimicosarea.id = reportequimicosareacategoria.reportequimicosarea_id
                                        LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id 
                                    WHERE
                                        reportequimicosarea.proyecto_id = ' . $proyecto_id . ' 
                                        ' . $where_condicion . ' 
                                        AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0
                                    ORDER BY
                                        reportequimicosarea.reportequimicosarea_numorden ASC,
                                        reportequimicosarea.reportequimicosarea_instalacion ASC,
                                        reportequimicosarea.reportequimicosarea_nombre ASC');


                // FORMATEAR FILAS
                foreach ($areas as $key => $value) {
                    if ($area != $value->reportequimicosarea_nombre) {
                        $area = $value->reportequimicosarea_nombre;
                        $value->area_nombre = $area;

                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;

                        if ($value->reportequimicosarea_porcientooperacion > 0) {
                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================


                            $tabla_6_1 .= '<tr>
                                                <td>' . $value->reportequimicosarea_instalacion . '</td>
                                                <td>' . $value->reportequimicosarea_nombre . '</td>
                                                <td>' . $value->reportequimicosarea_porcientooperacion . ' %</td>
                                            </tr>';
                        }
                    } else {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    $value->reportecategoria_nombre_texto = $value->reportequimicoscategoria_nombre;
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                    if ($edicion == 1) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }


                    if ($value->reportequimicosarea_porcientooperacion === NULL) {
                        $total_singuardar += 1;
                    }


                    if ($value->reportequimicosarea_porcientooperacion > 0) {
                        //TABLA 5.5.- Actividades del personal expuesto
                        //==================================================


                        $tabla_5_5 .= '<tr>
                                            <td>' . $value->reportequimicosarea_instalacion . '</td>
                                            <td>' . $value->reportequimicosarea_nombre . '</td>
                                            <td>' . $value->reportequimicoscategoria_nombre . '</td>
                                            <td class="justificado">' . $value->reportequimicosareacategoria_actividades . '</td>
                                        </tr>';


                        //TABLA 5.6.- Fuentes generadoras
                        //==================================================


                        $tabla_5_6 .= '<tr>
                                            <td>' . $value->reportequimicosarea_instalacion . '</td>
                                            <td>' . $value->reportequimicosarea_nombre . '</td>
                                            <td>' . $value->reportequimicosarea_maquinaria . '</td>
                                            <td class="justificado">' . $value->reportequimicosarea_contaminante . '</td>
                                            <td>' . $value->reportequimicoscategoria_nombre . '</td>';

                        if (($value->reportequimicosarea_caracteristica + 0) == 0) {
                            $tabla_5_6 .= '<td>No</td>
                                                                <td>Si</td>';
                        } else {
                            $tabla_5_6 .= '<td>Si</td>
                                                                <td>No</td>';
                        }

                        $tabla_5_6 .= '</tr>';


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reportequimicosarea_instalacion && ($key + 0) == 0) {
                            $instalacion = $value->reportequimicosarea_instalacion;
                            $selectareasoption .= '<optgroup label="' . $instalacion . '">';
                        }

                        if ($instalacion != $value->reportequimicosarea_instalacion && ($key + 0) > 0) {
                            $instalacion = $value->reportequimicosarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="' . $instalacion . '">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportequimicosarea_nombre) {
                            $area2 = $value->reportequimicosarea_nombre;
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
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_5_6"] = $tabla_5_6;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_5"] = '<tr><td colspan="4">Error al consultar los datos</td></tr>';
            $dato["tabla_5_6"] = '<tr><td colspan="7">Error al consultar los datos</td></tr>';
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
    public function reportequimicosareascategorias($proyecto_id, $reporteregistro_id, $area_id, $areas_poe)
    {
        try {
            // dd($proyecto_id, $reporteregistro_id, $area_id, $areas_poe);


            $numero_registro = 0;
            $areacategorias_lista = '';
            $readonly_required = '';


            if (($areas_poe + 0) == 1) {
                $areacategorias = DB::select('SELECT
                                                    reportecategoria.proyecto_id,
                                                    reporteareacategoria.reportearea_id,
                                                    reportecategoria.id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reportequimicosareacategoria.id, "checked", "") AS checked
                                                        FROM
                                                            reportequimicosareacategoria
                                                        WHERE
                                                            reportequimicosareacategoria.reportequimicosarea_id = reporteareacategoria.reportearea_id
                                                            AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                                                            AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . ' 
                                                    ), "") AS checked,
                                                    reportecategoria.reportecategoria_total,
                                                    reporteareacategoria.reporteareacategoria_total AS categoria_total,
                                                    reporteareacategoria.reporteareacategoria_geh,
                                                    reporteareacategoria.reporteareacategoria_actividades AS categoria_actividades 
                                                FROM
                                                    reporteareacategoria
                                                    LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportecategoria.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteareacategoria.reportearea_id = ' . $area_id . ' 
                                                ORDER BY
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC');


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
                                                    ' . $value->reportequimicoscategoria_nombre . '
                                                </td>
                                                <td with="120">
                                                    <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_total_' . $value->id . '" value="' . $value->categoria_total . '" readonly>
                                                </td>
                                                <td with="">
                                                    <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_actividades_' . $value->id . '" readonly>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            } else {
                $areacategorias = DB::select('SELECT
                                                    reportequimicoscategoria.id,
                                                    reportequimicoscategoria.proyecto_id,
                                                    reportequimicoscategoria.recsensorialcategoria_id,
                                                    reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                    reportequimicoscategoria.reportequimicoscategoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reportequimicosareacategoria.id, "checked", "") AS checked
                                                        FROM
                                                            reportequimicosareacategoria
                                                        WHERE
                                                            reportequimicosareacategoria.reportequimicosarea_id = ' . $area_id . '
                                                            AND reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                            AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0 
                                                    ), "") AS checked,
                                                    IFNULL((
                                                        SELECT
                                                            reportequimicosareacategoria.reportequimicosareacategoria_total
                                                        FROM
                                                            reportequimicosareacategoria
                                                        WHERE
                                                            reportequimicosareacategoria.reportequimicosarea_id = ' . $area_id . '
                                                            AND reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                            AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0 
                                                    ), "") AS categoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            reportequimicosareacategoria.reportequimicosareacategoria_actividades
                                                        FROM
                                                            reportequimicosareacategoria
                                                        WHERE
                                                            reportequimicosareacategoria.reportequimicosarea_id = ' . $area_id . '
                                                            AND reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                            AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0 
                                                    ), "") AS categoria_actividades
                                                FROM
                                                    reportequimicoscategoria
                                                WHERE
                                                    reportequimicoscategoria.proyecto_id = ' . $proyecto_id . '
                                                    AND reportequimicoscategoria.registro_id = ' . $reporteregistro_id . '
                                                ORDER BY
                                                    reportequimicoscategoria.reportequimicoscategoria_nombre ASC');


                foreach ($areacategorias as $key => $value) {
                    $numero_registro += 1;

                    if ($value->checked) {
                        $readonly_required = 'required';
                    } else {
                        $readonly_required = 'readonly';
                    }

                    $areacategorias_lista .= '<tr>
                                                <td with="60">
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_categoria_id[]" value="' . $value->id . '" ' . $value->checked . ' onchange="activa_areacategoria(this, ' . $numero_registro . ');"/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td with="240">
                                                    ' . $value->reportequimicoscategoria_nombre . '
                                                </td>
                                                <td with="120">
                                                    <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_total_' . $value->id . '" value="' . $value->categoria_total . '" ' . $readonly_required . '>
                                                </td>
                                                <td with="">
                                                    <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="areacategoria_actividades_' . $value->id . '" ' . $readonly_required . '>' . $value->categoria_actividades . '</textarea>
                                                </td>
                                            </tr>';
                }
            }


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areacategorias'] = '<tr><td colspan="2">Error al cargar las categorías</td></tr>';
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
    public function reportequimicosareaeliminar($area_id)
    {
        try {
            $area = reportequimicosareaModel::where('id', $area_id)->delete();
            $areacategorias = reportequimicosareacategoriaModel::where('reportequimicosarea_id', $area_id)
                ->where('reportequimicosareacategoria_poe', 0)
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
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosepptabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            $epp = reportequimicoseppModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
                ->get();

            $numero_registro = 0;
            foreach ($epp as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
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
    public function reportequimicoseppeliminar($epp_id)
    {
        try {
            $epp = reportequimicoseppModel::where('id', $epp_id)->delete();

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
     * @param  int $recsensorial_id     
     * @return \Illuminate\Http\Response
     */
    public function reportequimicossustanciasreconocimiento($recsensorial_id)
    {
        try {
            $sustancias = DB::select("CALL sp_ponderacion1_tabla8_1_b(?)", [$recsensorial_id]);


            // respuesta
            $dato['data'] = $sustancias;
            $dato["total"] = count($sustancias);
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
     * @param  int $area_id     
     * @param  int $reporteregistro_id     
     * @param  int $areas_poe     
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosevaluacionareacategorias($area_id, $reporteregistro_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                reportequimicosareacategoria.reportequimicosarea_id,
                                                reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                reportequimicosareacategoria.reportequimicoscategoria_id,
                                                reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                                reportecategoria.reportecategoria_orden 
                                            FROM
                                                reportequimicosareacategoria
                                                LEFT JOIN reportecategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportecategoria.id
                                            WHERE
                                                reportequimicosareacategoria.reportequimicosarea_id = ' . $area_id . ' 
                                                AND reportequimicosareacategoria.reportequimicosareacategoria_poe = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');
            } else {
                $categorias = DB::select('SELECT
                                                reportequimicosareacategoria.reportequimicosarea_id,
                                                reportequimicosareacategoria.reportequimicosareacategoria_poe,
                                                reportequimicosareacategoria.reportequimicoscategoria_id,
                                                reportequimicoscategoria.reportequimicoscategoria_nombre 
                                            FROM
                                                reportequimicosareacategoria
                                                LEFT JOIN reportequimicoscategoria ON reportequimicosareacategoria.reportequimicoscategoria_id = reportequimicoscategoria.id
                                            WHERE
                                                reportequimicosareacategoria.reportequimicosarea_id = ' . $area_id . ' 
                                                AND reportequimicosareacategoria.reportequimicosareacategoria_poe = 0 
                                            ORDER BY
                                                reportequimicoscategoria.reportequimicoscategoria_nombre ASC');
            }


            $categorias_opciones = '<option value=""></option>';
            foreach ($categorias as $key => $value) {
                $categorias_opciones .= '<option value="' . $value->reportequimicoscategoria_id . '">' . $value->reportequimicoscategoria_nombre . '</option>';
            }
            $categorias_opciones .= '<option value="0">N/A</option>';


            // respuesta
            $dato["categorias_opciones"] = $categorias_opciones;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["categorias_opciones"] = '<option value="">Error al consultar las categorías</option>';
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
    public function reportequimicosevaluaciontabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
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
                $evaluacion = DB::select('SELECT
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.reportequimicosarea_id,
                                                reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                IFNULL(reportecategoria.reportecategoria_nombre, "N/A") AS reportequimicoscategoria_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                                reportequimicosevaluacionparametro.id AS reportequimicosevaluacionparametro_id,
                                                reportequimicosevaluacionparametro.id AS reportequimicosevaluacion_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro,
                                                (
                                                    CASE
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Benceno%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Tolueno%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Xileno%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Hexano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Ciclohexano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Metano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Etano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Propano%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Butano%" THEN 4
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Pentano%" THEN 5
                                                        ELSE 0
                                                    END
                                                ) AS orden,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_metodo,
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS concentracion_texto,

                                                (REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", ""), ">", ""), " ", "") + 0) AS concentracion,
                                                
                                                (REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) AS concentracion,
                                                
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS valorlimiteTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS valorlimite,
                                                
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior, " ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS limitesuperiorTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior AS limitesuperior,
                                                
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_periodo,
                                               
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN reportearea ON reportequimicosevaluacion.reportequimicosarea_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportecategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC,
                                                orden ASC,
                                                reportequimicosevaluacionparametro.id ASC');
            } else {
                $evaluacion = DB::select('SELECT
                                                reportequimicosevaluacion.proyecto_id,
                                                reportequimicosevaluacion.registro_id,
                                                reportequimicosevaluacion.id,
                                                reportequimicosevaluacion.reportequimicosarea_id,
                                                reportequimicosarea.reportequimicosarea_nombre,
                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                IFNULL(reportequimicoscategoria.reportequimicoscategoria_nombre, "N/A") AS reportequimicoscategoria_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_nombre,
                                                reportequimicosevaluacion.reportequimicosevaluacion_ficha,
                                                reportequimicosevaluacion.reportequimicosevaluacion_geo,
                                                reportequimicosevaluacion.reportequimicosevaluacion_total,
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                                reportequimicosevaluacionparametro.id AS reportequimicosevaluacionparametro_id,
                                                reportequimicosevaluacionparametro.id AS reportequimicosevaluacion_id,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro,
                                                (
                                                    CASE
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Benceno%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Tolueno%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Xileno%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Hexano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Ciclohexano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Metano%" THEN 1
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Etano%" THEN 2
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Propano%" THEN 3
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Butano%" THEN 4
                                                        WHEN reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro LIKE "Pentano%" THEN 5
                                                        ELSE 0
                                                    END
                                                ) AS orden,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_metodo,
                                                 CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS concentracion_texto,
                                                (REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) AS concentracion,
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite," ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS valorlimiteTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS valorlimite,
                                                
                                                CONCAT(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior, " ", reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_unidad) AS limitesuperiorTexto,

                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_limitesuperior AS limitesuperior,
                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_periodo,
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "Dentro de norma", "Fuera de norma") AS resultado_texto,
                                                IF((REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "<", "reportequimicosevaluacionparametro_valorlimite - "), ">", "reportequimicosevaluacionparametro_valorlimite + ") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, "#00FF00", "#FF0000") AS resultado_color
                                            FROM
                                                reportequimicosevaluacion
                                                LEFT JOIN reportequimicosarea ON reportequimicosevaluacion.reportequimicosarea_id = reportequimicosarea.id
                                                LEFT JOIN reportequimicoscategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                            WHERE
                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                            ORDER BY
                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC,
                                                orden ASC,
                                                reportequimicosevaluacionparametro.id ASC');
            }


            foreach ($evaluacion as $key => $value) {
                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $evaluacion;
            $dato["total"] = count($evaluacion);
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
     * @param  int $puntoevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosevaluacioneliminar($puntoevaluacion_id)
    {
        try {
            $puntoner = reportequimicosevaluacionModel::where('id', $puntoevaluacion_id)->delete();

            $puntoner_parametros = reportequimicosevaluacionparametroModel::where('reportequimicosevaluacion_id', $puntoevaluacion_id)->delete();

            // respuesta
            $dato["msj"] = 'Punto de evaluación eliminado correctamente';
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
    public function reportequimicosmetodomuestreotabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            //==========================================


            $parametros = DB::select('SELECT
                                            TABLA2.proyecto_id,
                                            TABLA2.registro_id,
                                            TABLA2.parametro AS reportequimicosmetodomuestreo_parametro,
                                            TABLA2.puntos AS reportequimicosmetodomuestreo_puntos,
                                            TABLA2.metodo AS reportequimicosmetodomuestreo_metodo,
                                            TABLA2.tipo AS reportequimicosmetodomuestreo_tipo,
                                            TABLA2.flujos AS reportequimicosmetodomuestreo_flujo
                                        FROM
                                        (
                                            SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.parametro,
                                                IFNULL((
                                                    SELECT
                                                        reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_puntos
                                                    FROM
                                                        reportequimicosmetodomuestreo 
                                                    WHERE
                                                        reportequimicosmetodomuestreo.proyecto_id = TABLA.proyecto_id 
                                                        AND reportequimicosmetodomuestreo.registro_id = TABLA.registro_id
                                                        AND reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = TABLA.parametro
                                                    LIMIT 1
                                                ), 0) AS puntos,
                                                IFNULL((
                                                    SELECT
                                                        reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_metodo
                                                    FROM
                                                        reportequimicosmetodomuestreo 
                                                    WHERE
                                                        reportequimicosmetodomuestreo.proyecto_id = TABLA.proyecto_id 
                                                        AND reportequimicosmetodomuestreo.registro_id = TABLA.registro_id
                                                        AND reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = TABLA.parametro
                                                    LIMIT 1
                                                ), "") AS metodo,
                                                IFNULL((
                                                    SELECT
                                                        reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_tipo
                                                    FROM
                                                        reportequimicosmetodomuestreo 
                                                    WHERE
                                                        reportequimicosmetodomuestreo.proyecto_id = TABLA.proyecto_id 
                                                        AND reportequimicosmetodomuestreo.registro_id = TABLA.registro_id
                                                        AND reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = TABLA.parametro
                                                    LIMIT 1
                                                ), "") AS tipo,
                                                IFNULL((
                                                    SELECT
                                                        GROUP_CONCAT(reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_flujo)
                                                    FROM
                                                        reportequimicosmetodomuestreo 
                                                    WHERE
                                                        reportequimicosmetodomuestreo.proyecto_id = TABLA.proyecto_id 
                                                        AND reportequimicosmetodomuestreo.registro_id = TABLA.registro_id
                                                        AND reportequimicosmetodomuestreo.reportequimicosmetodomuestreo_parametro = TABLA.parametro     
                                                ), "") AS flujos
                                            FROM
                                                (
                                                    SELECT
                                                        reportequimicosevaluacion.proyecto_id,
                                                        reportequimicosevaluacion.registro_id,
                                                        reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro 
                                                    FROM
                                                        reportequimicosevaluacion
                                                        RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id 
                                                    WHERE
                                                        reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . '  
                                                        AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . '
                                                    GROUP BY
                                                        reportequimicosevaluacion.proyecto_id,
                                                        reportequimicosevaluacion.registro_id,  
                                                        reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro 
                                                    ORDER BY
                                                        MIN(reportequimicosevaluacion.reportequimicosevaluacion_punto) ASC
                                                ) AS TABLA
                                            ) AS TABLA2
                                        WHERE
                                            TABLA2.puntos > 0');


            foreach ($parametros as $key => $value) {
                $value->flujos = str_replace(',', '<br>', $value->reportequimicosmetodomuestreo_flujo);


                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $parametros;
            $dato["total"] = count($parametros);
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
     * @param  int $reporteregistro_id
     * @param  $parametro
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosmetodomuestreoeliminar($proyecto_id, $reporteregistro_id, $parametro)
    {
        try {
            $metodomuestreo_eliminar = reportequimicosmetodomuestreoModel::where('proyecto_id', $proyecto_id)
                ->where('registro_id', $reporteregistro_id)
                ->where('reportequimicosmetodomuestreo_parametro', $parametro)
                ->delete();

            // respuesta
            $dato["msj"] = 'Punto de evaluación eliminado correctamente';
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
    public function reportequimicosmatriztabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            if (($areas_poe + 0) == 1) {
                $puntos = DB::select('SELECT
                                            reportequimicosevaluacion.id,
                                            reportequimicosevaluacion.proyecto_id,
                                            reportequimicosevaluacion.registro_id,
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
                                            reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                                            reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                                            reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                            reportequimicosevaluacion.reportequimicosevaluacion_nombre,
                                            reportequimicosevaluacion.reportequimicosevaluacion_ficha,
                                            reportequimicosevaluacion.reportequimicosevaluacion_geo,
                                            reportequimicosevaluacion.reportequimicosevaluacion_total,
                                            reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(GROUP_CONCAT(CONCAT("<b>", reportequimicosevaluacionparametro_parametro, "</b>,(", reportequimicosevaluacionparametro_concentracion, " / ",    reportequimicosevaluacionparametro_valorlimite, ")")), ",", "<br>")
                                                FROM
                                                    reportequimicosevaluacionparametro
                                                WHERE
                                                    reportequimicosevaluacionparametro.reportequimicosevaluacion_id = reportequimicosevaluacion.id
                                            ), "-") AS parametros
                                        FROM
                                            reportequimicosevaluacion
                                            LEFT JOIN proyecto ON reportequimicosevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportearea ON reportequimicosevaluacion.reportequimicosarea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportecategoria.id
                                        WHERE
                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reportequimicosevaluacion.reportequimicosevaluacion_punto ASC');
            } else {
                $puntos = DB::select('SELECT
                                            reportequimicosevaluacion.id,
                                            reportequimicosevaluacion.proyecto_id,
                                            reportequimicosevaluacion.registro_id,
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
                                            reportequimicosarea.reportequimicosarea_instalacion,
                                            reportequimicosarea.reportequimicosarea_nombre,
                                            reportequimicoscategoria.reportequimicoscategoria_nombre,
                                            reportequimicosevaluacion.reportequimicosevaluacion_nombre,
                                            reportequimicosevaluacion.reportequimicosevaluacion_ficha,
                                            reportequimicosevaluacion.reportequimicosevaluacion_geo,
                                            reportequimicosevaluacion.reportequimicosevaluacion_total,
                                            reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(GROUP_CONCAT(CONCAT("<b>", reportequimicosevaluacionparametro_parametro, "</b>,(", reportequimicosevaluacionparametro_concentracion, " / ",    reportequimicosevaluacionparametro_valorlimite, ")")), ",", "<br>")
                                                FROM
                                                    reportequimicosevaluacionparametro
                                                WHERE
                                                    reportequimicosevaluacionparametro.reportequimicosevaluacion_id = reportequimicosevaluacion.id
                                            ), "-") AS parametros
                                        FROM
                                            reportequimicosevaluacion
                                            LEFT JOIN proyecto ON reportequimicosevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportequimicosarea ON reportequimicosevaluacion.reportequimicosarea_id = reportequimicosarea.id
                                            LEFT JOIN reportequimicoscategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportequimicoscategoria.id
                                        WHERE
                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reportequimicosevaluacion.reportequimicosevaluacion_punto ASC');
            }


            $numero_registro = 0;
            foreach ($puntos as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
            }


            // respuesta
            $dato["data"] = $puntos;
            $dato["total"] = count($puntos);
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
     * @param  int $partida_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosdashboard($proyecto_id, $reporteregistro_id, $partida_id, $areas_poe)
    {
        try {
            $where_condicion = '';

            if (($partida_id + 0) > 0) {
                $parametros = DB::select('SELECT
                                                reportequimicosgrupos.proyecto_id,
                                                reportequimicosgrupos.registro_id,
                                                reportequimicosgrupos.proveedor_id,
                                                proveedor.proveedor_NombreComercial,
                                                reportequimicosgrupos.catreportequimicospartidas_id,
                                                catreportequimicospartidas.catreportequimicospartidas_numero,
                                                catreportequimicospartidas.catreportequimicospartidas_descripcion,
                                                reportequimicosgrupos.reportequimicosproyecto_id,
                                                reportequimicosproyecto.reportequimicosproyecto_parametro 
                                            FROM
                                                reportequimicosgrupos
                                                LEFT JOIN proveedor ON reportequimicosgrupos.proveedor_id = proveedor.id
                                                LEFT JOIN catreportequimicospartidas ON reportequimicosgrupos.catreportequimicospartidas_id = catreportequimicospartidas.id
                                                LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                            WHERE
                                                reportequimicosgrupos.proyecto_id = ? 
                                                AND reportequimicosgrupos.registro_id = ? 
                                            ORDER BY
                                                catreportequimicospartidas.catreportequimicospartidas_numero ASC,
                                                reportequimicosproyecto.reportequimicosproyecto_parametro ASC
                                            LIMIT 1', [$proyecto_id, $reporteregistro_id]);


                foreach ($parametros as $key => $value) {
                    if (($key + 0) == 0) {
                        $where_condicion .= 'WHERE TABLA.parametro = "' . $value->reportequimicosproyecto_parametro . '" ';

                        $dato["dashboard_titulo"] = $value->catreportequimicospartidas_descripcion;
                    } else {
                        $where_condicion .= 'OR TABLA.parametro = "' . $value->reportequimicosproyecto_parametro . '" ';
                    }
                }
            } else {
                if (($reporteregistro_id + 0) > 0) {
                    $reporte = reportequimicosModel::findOrFail($reporteregistro_id);
                    $dato["dashboard_titulo"] = 'Evaluación de químicos en ' . $reporte->reportequimicos_instalacion;
                } else {
                    $dato["dashboard_titulo"] = 'Evaluación de químicos';
                }
            }


            //=====================================
            // AREAS AVALUADAS


            if (($areas_poe + 0) == 1) {
                $areas = DB::select('SELECT
                                            TABLA3.proyecto_id,
                                            TABLA3.registro_id,
                                            TABLA3.area_id,
                                            TABLA3.area_nombre,
                                            TABLA3.tipo_evaluacion,
                                            COUNT(TABLA3.area_id) AS total,
                                            IF(COUNT(TABLA3.area_id) > 1, CONCAT(COUNT(TABLA3.area_id), " Puntos"), CONCAT(COUNT(TABLA3.area_id), " Punto")) AS total_texto
                                        FROM
                                            (
                                                SELECT
                                                    TABLA2.proyecto_id,
                                                    TABLA2.registro_id,
                                                    TABLA2.area_id,
                                                    TABLA2.area_nombre,
                                                    TABLA2.tipo_evaluacion
                                                FROM
                                                    (
                                                        SELECT
                                                            TABLA.id,
                                                            TABLA.proyecto_id,
                                                            TABLA.registro_id,
                                                            TABLA.area_id,
                                                            TABLA.area_nombre,
                                                            TABLA.punto,
                                                            TABLA.tipo_evaluacion,
                                                            TABLA.parametro
                                                        FROM
                                                            (
                                                                SELECT
                                                                    TABLA0.id,
                                                                    TABLA0.proyecto_id,
                                                                    TABLA0.registro_id,
                                                                    TABLA0.area_id,
                                                                    TABLA0.area_nombre,
                                                                    TABLA0.punto,
                                                                    TABLA0.tipo_evaluacion,
                                                                    reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro
                                                                FROM
                                                                    (
                                                                        SELECT
                                                                            reportequimicosevaluacion.id,
                                                                            reportequimicosevaluacion.proyecto_id,
                                                                            reportequimicosevaluacion.registro_id,
                                                                            reportequimicosevaluacion.reportequimicosarea_id AS area_id,
                                                                            reportearea.reportearea_nombre AS area_nombre,
                                                                            reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                                            IF(reportequimicosevaluacion.reportequimicoscategoria_id > 0, 1, 0) AS tipo_evaluacion
                                                                        FROM
                                                                            reportequimicosevaluacion
                                                                            LEFT JOIN reportearea ON reportequimicosevaluacion.reportequimicosarea_id = reportearea.id
                                                                        WHERE
                                                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                                    ) AS TABLA0
                                                                    LEFT JOIN reportequimicosevaluacionparametro ON TABLA0.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                                            ) AS TABLA
                                                        ' . $where_condicion . ' 
                                                        -- WHERE
                                                            -- TABLA.parametro = "Ácido sulfhídrico"
                                                            -- TABLA.parametro = "Metano"
                                                            -- OR TABLA.parametro = "Propano"
                                                            -- OR TABLA.parametro = "Butano"
                                                            -- OR TABLA.parametro = "Pentano"
                                                            -- OR TABLA.parametro = "Etano"
                                                    ) AS TABLA2
                                                GROUP BY
                                                    TABLA2.proyecto_id,
                                                    TABLA2.registro_id,
                                                    TABLA2.area_id,
                                                    TABLA2.area_nombre,
                                                    TABLA2.tipo_evaluacion,
                                                    TABLA2.punto
                                            ) AS TABLA3
                                        GROUP BY
                                            TABLA3.proyecto_id,
                                            TABLA3.registro_id,
                                            TABLA3.area_id,
                                            TABLA3.area_nombre,
                                            TABLA3.tipo_evaluacion
                                        ORDER BY
                                            TABLA3.tipo_evaluacion ASC,
                                            TABLA3.area_nombre ASC');
            } else {
                $areas = DB::select('SELECT
                                            TABLA3.proyecto_id,
                                            TABLA3.registro_id,
                                            TABLA3.area_id,
                                            TABLA3.area_nombre,
                                            TABLA3.tipo_evaluacion,
                                            COUNT(TABLA3.area_id) AS total,
                                            IF(COUNT(TABLA3.area_id) > 1, CONCAT(COUNT(TABLA3.area_id), " Puntos"), CONCAT(COUNT(TABLA3.area_id), " Punto")) AS total_texto
                                        FROM
                                            (
                                                SELECT
                                                    TABLA2.proyecto_id,
                                                    TABLA2.registro_id,
                                                    TABLA2.area_id,
                                                    TABLA2.area_nombre,
                                                    TABLA2.tipo_evaluacion
                                                FROM
                                                    (
                                                        SELECT
                                                            TABLA.id,
                                                            TABLA.proyecto_id,
                                                            TABLA.registro_id,
                                                            TABLA.area_id,
                                                            TABLA.area_nombre,
                                                            TABLA.punto,
                                                            TABLA.tipo_evaluacion,
                                                            TABLA.parametro
                                                        FROM
                                                            (
                                                                SELECT
                                                                    TABLA0.id,
                                                                    TABLA0.proyecto_id,
                                                                    TABLA0.registro_id,
                                                                    TABLA0.area_id,
                                                                    TABLA0.area_nombre,
                                                                    TABLA0.punto,
                                                                    TABLA0.tipo_evaluacion,
                                                                    reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro
                                                                FROM
                                                                    (
                                                                        SELECT
                                                                            reportequimicosevaluacion.id,
                                                                            reportequimicosevaluacion.proyecto_id,
                                                                            reportequimicosevaluacion.registro_id,
                                                                            reportequimicosevaluacion.reportequimicosarea_id AS area_id,
                                                                            reportequimicosarea.reportequimicosarea_nombre AS area_nombre,
                                                                            reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                                            IF(reportequimicosevaluacion.reportequimicoscategoria_id > 0, 1, 0) AS tipo_evaluacion
                                                                        FROM
                                                                            reportequimicosevaluacion
                                                                            LEFT JOIN reportequimicosarea ON reportequimicosevaluacion.reportequimicosarea_id = reportequimicosarea.id
                                                                        WHERE
                                                                            reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                                            AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                                    ) AS TABLA0
                                                                    LEFT JOIN reportequimicosevaluacionparametro ON TABLA0.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                                            ) AS TABLA
                                                        ' . $where_condicion . ' 
                                                        -- WHERE
                                                            -- TABLA.parametro = "Ácido sulfhídrico"
                                                            -- TABLA.parametro = "Metano"
                                                            -- OR TABLA.parametro = "Propano"
                                                            -- OR TABLA.parametro = "Butano"
                                                            -- OR TABLA.parametro = "Pentano"
                                                            -- OR TABLA.parametro = "Etano"
                                                    ) AS TABLA2
                                                GROUP BY
                                                    TABLA2.proyecto_id,
                                                    TABLA2.registro_id,
                                                    TABLA2.area_id,
                                                    TABLA2.area_nombre,
                                                    TABLA2.tipo_evaluacion,
                                                    TABLA2.punto
                                            ) AS TABLA3
                                        GROUP BY
                                            TABLA3.proyecto_id,
                                            TABLA3.registro_id,
                                            TABLA3.area_id,
                                            TABLA3.area_nombre,
                                            TABLA3.tipo_evaluacion
                                        ORDER BY
                                            TABLA3.tipo_evaluacion ASC,
                                            TABLA3.area_nombre ASC');
            }


            $dashboard_areas = '';
            $tipo_evaluacion = 0;
            $dato["total_evaluacion"] = 0;
            $dato["total_evaluacionambiental"] = 0;
            $dato["total_evaluacionpersonal"] = 0;
            foreach ($areas as $key => $value) {
                if (($key + 0) > 0) {
                    if ($tipo_evaluacion != ($value->tipo_evaluacion + 0)) {
                        $tipo_evaluacion = ($value->tipo_evaluacion + 0);
                        $dashboard_areas .= '<br><br>';
                    }
                } else {
                    $tipo_evaluacion = ($value->tipo_evaluacion + 0);
                }


                $dashboard_areas .= '● <b>' . $value->area_nombre . '</b> (' . $value->total_texto . ')<br>';


                if (($value->tipo_evaluacion + 0) == 0) {
                    $dato["total_evaluacionambiental"] += ($value->total + 0);
                } else {
                    $dato["total_evaluacionpersonal"] += ($value->total + 0);
                }


                $dato["total_evaluacion"] += ($value->total + 0);
            }


            $dato["total_evaluacion"] = '<br>' . $dato["total_evaluacion"];


            //=====================================
            // CATEGORIAS AVALUADAS


            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                TABLA2.proyecto_id,
                                                TABLA2.registro_id,
                                                TABLA2.reportequimicoscategoria_id,
                                                TABLA2.reportequimicoscategoria_nombre
                                            FROM
                                                (
                                                    SELECT
                                                        TABLA.proyecto_id,
                                                        TABLA.registro_id,
                                                        TABLA.reportequimicoscategoria_id,
                                                        TABLA.reportequimicoscategoria_nombre
                                                    FROM
                                                        (
                                                            SELECT
                                                                reportequimicosevaluacion.proyecto_id,
                                                                reportequimicosevaluacion.registro_id,
                                                                reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                                reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro 
                                                            FROM
                                                                reportequimicosevaluacion
                                                                LEFT JOIN reportecategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportecategoria.id
                                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                                            WHERE
                                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                            ORDER BY
                                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC
                                                        ) AS TABLA
                                                    ' . $where_condicion . ' 
                                                    -- WHERE
                                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                                        -- TABLA.parametro = "Metano"
                                                        -- OR TABLA.parametro = "Propano"
                                                        -- OR TABLA.parametro = "Butano"
                                                        -- OR TABLA.parametro = "Pentano"
                                                        -- OR TABLA.parametro = "Etano"
                                                    GROUP BY
                                                        TABLA.proyecto_id,
                                                        TABLA.registro_id,
                                                        TABLA.reportequimicoscategoria_id,
                                                        TABLA.reportequimicoscategoria_nombre
                                                ) AS TABLA2
                                            WHERE
                                                TABLA2.reportequimicoscategoria_id > 0
                                            ORDER BY
                                                TABLA2.reportequimicoscategoria_nombre ASC');
            } else {
                $categorias = DB::select('SELECT
                                                TABLA2.proyecto_id,
                                                TABLA2.registro_id,
                                                TABLA2.reportequimicoscategoria_id,
                                                TABLA2.reportequimicoscategoria_nombre
                                            FROM
                                                (
                                                    SELECT
                                                        TABLA.proyecto_id,
                                                        TABLA.registro_id,
                                                        TABLA.reportequimicoscategoria_id,
                                                        TABLA.reportequimicoscategoria_nombre
                                                    FROM
                                                        (
                                                            SELECT
                                                                reportequimicosevaluacion.proyecto_id,
                                                                reportequimicosevaluacion.registro_id,
                                                                reportequimicosevaluacion.reportequimicosevaluacion_punto,
                                                                reportequimicosevaluacion.reportequimicoscategoria_id,
                                                                reportequimicoscategoria.reportequimicoscategoria_nombre,
                                                                reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro 
                                                            FROM
                                                                reportequimicosevaluacion
                                                                LEFT JOIN reportequimicoscategoria ON reportequimicosevaluacion.reportequimicoscategoria_id = reportequimicoscategoria.id
                                                                RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
                                                            WHERE
                                                                reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . ' 
                                                                AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                            ORDER BY
                                                                reportequimicosevaluacion.reportequimicosevaluacion_punto ASC
                                                        ) AS TABLA
                                                    ' . $where_condicion . ' 
                                                    -- WHERE
                                                        -- TABLA.parametro = "Ácido sulfhídrico"
                                                        -- TABLA.parametro = "Metano"
                                                        -- OR TABLA.parametro = "Propano"
                                                        -- OR TABLA.parametro = "Butano"
                                                        -- OR TABLA.parametro = "Pentano"
                                                        -- OR TABLA.parametro = "Etano"
                                                    GROUP BY
                                                        TABLA.proyecto_id,
                                                        TABLA.registro_id,
                                                        TABLA.reportequimicoscategoria_id,
                                                        TABLA.reportequimicoscategoria_nombre
                                                ) AS TABLA2
                                            WHERE
                                                TABLA2.reportequimicoscategoria_id > 0
                                            ORDER BY
                                                TABLA2.reportequimicoscategoria_nombre ASC');
            }


            $dashboard_categorias = '';
            foreach ($categorias as $key => $value) {
                $dashboard_categorias .= '● <b>' . $value->reportequimicoscategoria_nombre . '</b><br>';
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
                                                AND reporterecomendaciones.agente_nombre = "Químicos"');


            $dato['dashboard_recomendaciones_total'] = 0;
            if (count($recomendaciones) > 0) {
                $dato['dashboard_recomendaciones_total'] = $recomendaciones[0]->totalrecomendaciones;
            }
            $dato['dashboard_recomendaciones_total'] = '<br>' . $dato['dashboard_recomendaciones_total'];


            //=====================================
            // NIVEL DE CUMPLIMIENTO POR PARAMETRO


            $cumplimiento = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.registro_id,
                                            TABLA.parametro,
                                            (
                                                CASE
                                                    WHEN TABLA.parametro LIKE "Benceno%" THEN 1
                                                    WHEN TABLA.parametro LIKE "Tolueno%" THEN 2
                                                    WHEN TABLA.parametro LIKE "Xileno%" THEN 3
                                                    WHEN TABLA.parametro LIKE "Hexano%" THEN 1
                                                    WHEN TABLA.parametro LIKE "Ciclohexano%" THEN 2
                                                    WHEN TABLA.parametro LIKE "Metano%" THEN 1
                                                    WHEN TABLA.parametro LIKE "Etano%" THEN 2
                                                    WHEN TABLA.parametro LIKE "Propano%" THEN 3
                                                    WHEN TABLA.parametro LIKE "Butano%" THEN 4
                                                    WHEN TABLA.parametro LIKE "Pentano%" THEN 5
                                                    ELSE 0
                                                END
                                            ) AS orden,
                                            COUNT(resultado) AS total_evaluaciones,
                                            SUM(resultado) AS total_cumplimiento,
                                            ROUND((ROUND((SUM(resultado) / COUNT(resultado)), 2)*100), 0) AS cumplimiento,
                                            (
                                                CASE
                                                    WHEN ROUND((ROUND((SUM(resultado) / COUNT(resultado)), 2)*100), 0) >= 90 THEN "#8ee66b"
                                                    WHEN ROUND((ROUND((SUM(resultado) / COUNT(resultado)), 2)*100), 0) >= 50 THEN "#ffb22b"
                                                    ELSE "#fc4b6c"
                                                END
                                            ) AS color
                                        FROM
                                            (
                                                SELECT
                                                    reportequimicosevaluacion.proyecto_id AS proyecto_id,
                                                    reportequimicosevaluacion.registro_id AS registro_id,
                                                    reportequimicosevaluacion.reportequimicosevaluacion_punto AS punto,
                                                    reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro,
                                                    reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion,
                                                    (REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "")+0) AS concentracion,
                                                    reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite AS limite,
                                                    IF((REPLACE(REPLACE(REPLACE(reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_concentracion, "˂", ""), ">", ""), " ", "") + 0) <= reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_valorlimite, 1, 0) AS resultado
                                                FROM
                                                    reportequimicosevaluacion
                                                    RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id 
                                                WHERE
                                                    reportequimicosevaluacion.proyecto_id = ' . $proyecto_id . '  
                                                    AND reportequimicosevaluacion.registro_id = ' . $reporteregistro_id . ' 
                                                ORDER BY
                                                    reportequimicosevaluacion.reportequimicosevaluacion_punto ASC
                                            ) AS TABLA
                                        ' . $where_condicion . ' 
                                        -- WHERE
                                            -- TABLA.parametro = "Ácido sulfhídrico"
                                            -- TABLA.parametro = "Metano"
                                            -- OR TABLA.parametro = "Propano"
                                            -- OR TABLA.parametro = "Butano"
                                            -- OR TABLA.parametro = "Pentano"
                                            -- OR TABLA.parametro = "Etano"
                                        GROUP BY
                                            TABLA.proyecto_id,
                                            TABLA.registro_id,
                                            orden ASC,
                                            TABLA.parametro');


            $col = 'col-12';
            if (count($cumplimiento) > 7) {
                $col = 'col-6';
            }


            $parametros_cumplimiento = '';
            foreach ($cumplimiento as $key => $value) {
                $parametros_cumplimiento .= '<div class="' . $col . '" style="display: inline-block; text-align: left;">
                                                <h6 style="margin: 0px; font-size:0.8vw;">♦ ' . $value->parametro . ' <span class="pull-right">' . $value->cumplimiento . '%</span></h6>
                                                <div class="progress" style="margin-bottom: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: ' . $value->cumplimiento . '%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>';
            }


            // respuesta
            $dato['parametros_cumplimiento'] = $parametros_cumplimiento;
            $dato["dashboard_areas"] = $dashboard_areas;
            $dato["dashboard_categorias"] = $dashboard_categorias;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["dashboard_titulo"] = 'Error al consultar';
            $dato['parametros_cumplimiento'] = '';
            $dato["dashboard_areas"] = 'Error al consultar las áreas evaluadas';
            $dato["dashboard_categorias"] = 'Error al consultar las categorías evaluadas';
            $dato["total_evaluacion"] = '<br>0';
            $dato["total_evaluacionambiental"] = 0;
            $dato["total_evaluacionpersonal"] = 0;
            $dato['dashboard_recomendaciones_total'] = '<br>0';

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
    public function reportequimicosdashboardgraficas(Request $request)
    {
        try
        {
            // dd($request->all());


            if ($request->grafica_dashboard)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->grafica_dashboard); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);


                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/partidas/grafica_partida_'.$request->partida_id.'.jpg'; // GRAFICA


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
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosconclusionestabla($proyecto_id, $reporteregistro_id)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);
            $reportecatalogo = reportequimicoscatalogoModel::limit(1)->get();


            //===================================================


            // $reporte = reportequimicosModel::where('id', $reporteregistro_id)->get();


            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reportequimicos_concluido == 1 || $reporte[0]->reportequimicos_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }   


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            $edicion = 1;
            if (count($revision) > 0) {
                if ($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1) {
                    $edicion = 0;
                }
            }


            // QUIMICOS
            //===================================================


            $quimicos_nombre = $this->quimicosnombre($proyecto_id, $reporteregistro_id);


            //===================================================


            $tabla = DB::select('SELECT
                                    reportequimicosconclusion.proyecto_id,
                                    reportequimicosconclusion.registro_id,
                                    reportequimicosconclusion.id,
                                    reportequimicosconclusion.catreportequimicospartidas_id,
                                    catreportequimicospartidas.catreportequimicospartidas_numero,
                                    catreportequimicospartidas.catreportequimicospartidas_descripcion,
                                    reportequimicosconclusion.reportequimicosconclusion_conclusion 
                                FROM
                                    reportequimicosconclusion
                                    LEFT JOIN catreportequimicospartidas ON reportequimicosconclusion.catreportequimicospartidas_id = catreportequimicospartidas.id
                                WHERE
                                    reportequimicosconclusion.proyecto_id = ' . $proyecto_id . ' 
                                    AND reportequimicosconclusion.registro_id = ' . $reporteregistro_id . ' 
                                ORDER BY
                                    reportequimicosconclusion.catreportequimicospartidas_id ASC');



            $numero_registro = 0;
            $total = 0;
            foreach ($tabla as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->partida = $value->catreportequimicospartidas_numero . '.- ' . $value->catreportequimicospartidas_descripcion;


                $value->conclusion = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $value->reportequimicosconclusion_conclusion);


                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if ($edicion == 1) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $tabla;
            $dato['total'] = count($tabla);
            $dato['conclusion_catalogo'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $reportecatalogo[0]->reportequimicoscatalogo_conclusion);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato['conclusion_catalogo'] = 'Error al consultar la conclusión del catálogo';
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $conclusion_id
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosconclusioneliminar($conclusion_id)
    {
        try {
            $conclusion = reportequimicosconclusionModel::where('id', $conclusion_id)->delete();

            // respuesta
            $dato["msj"] = 'Conclusión eliminada correctamente';
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
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosrecomendacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try {
            // QUIMICOS
            //===================================================


            $quimicos_nombre = $this->quimicosnombre($proyecto_id, $reporteregistro_id);


            //PARTIDAS QUÍMICOS
            //===================================================


            $partidas = DB::select('SELECT
                                        reportequimicosgrupos.proyecto_id,
                                        reportequimicosgrupos.registro_id,
                                        -- reportequimicosgrupos.proveedor_id,
                                        -- proveedor.proveedor_NombreComercial,
                                        reportequimicosgrupos.catreportequimicospartidas_id,
                                        catreportequimicospartidas.catreportequimicospartidas_numero,
                                        catreportequimicospartidas.catreportequimicospartidas_descripcion
                                        -- ,reportequimicosgrupos.reportequimicosproyecto_id,
                                        -- reportequimicosproyecto.reportequimicosproyecto_parametro 
                                    FROM
                                        reportequimicosgrupos
                                        LEFT JOIN proveedor ON reportequimicosgrupos.proveedor_id = proveedor.id
                                        LEFT JOIN catreportequimicospartidas ON reportequimicosgrupos.catreportequimicospartidas_id = catreportequimicospartidas.id
                                        LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                    WHERE
                                        reportequimicosgrupos.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportequimicosgrupos.registro_id = ' . $reporteregistro_id . ' 
                                    GROUP BY
                                        reportequimicosgrupos.proyecto_id,
                                        reportequimicosgrupos.registro_id,
                                        reportequimicosgrupos.catreportequimicospartidas_id,
                                        catreportequimicospartidas.catreportequimicospartidas_numero,
                                        catreportequimicospartidas.catreportequimicospartidas_descripcion
                                    ORDER BY
                                        reportequimicosgrupos.catreportequimicospartidas_id ASC');


            //===================================================


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
                                                reporterecomendaciones.catalogo_id,
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
                                            <textarea  class="form-control" rows="5" id="recomendacion_descripcion_' . $value->id . '" name="recomendacion_descripcion_' . $value->id . '" ' . $required_readonly . '>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $value->recomendaciones_descripcion) . '</textarea>';
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



                    $partidas_opciones = '<option value=""></option>';
                    foreach ($partidas as $key2 => $partida) {
                        if (($value->catalogo_id + 0) == ($partida->catreportequimicospartidas_id + 0)) {
                            $partidas_opciones .= '<option value="' . $partida->catreportequimicospartidas_id . '" selected>' . $partida->catreportequimicospartidas_numero . '.- ' . $partida->catreportequimicospartidas_descripcion . '</option>';
                        } else {
                            $partidas_opciones .= '<option value="' . $partida->catreportequimicospartidas_id . '">' . $partida->catreportequimicospartidas_numero . '.- ' . $partida->catreportequimicospartidas_descripcion . '</option>';
                        }
                    }


                    $value->descripcion = '<div class="row">
                                                <div class="col-6">
                                                    <label>Tipo</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>
                                                        <option value=""></option>
                                                        <option value="Preventiva" ' . $preventiva . '>Preventiva</option>
                                                        <option value="Correctiva" ' . $correctiva . '>Correctiva</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label>Partida informe</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_quimicopartida[]" required>
                                                        ' . $partidas_opciones . '
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <br>
                                                    <label>Descripción</label>
                                                    <textarea  class="form-control" rows="5" name="recomendacionadicional_descripcion[]" required>' . $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $quimicos_nombre, $value->recomendaciones_descripcion) . '</textarea>
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
    public function reportequimicosresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reportequimicosModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1) {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportequimicos_responsable1documento);
            } else {
                return Storage::download($reporte->reportequimicos_responsable1documento);
            }
        } else {
            if ($responsabledoc_opcion == 0) {
                return Storage::response($reporte->reportequimicos_responsable2documento);
            } else {
                return Storage::download($reporte->reportequimicos_responsable2documento);
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
    public function reportequimicosplanostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try {
            $planos = DB::select('SELECT
                                        proyectoevidenciaplano.proyecto_id,
                                        proyectoevidenciaplano.agente_id,
                                        proyectoevidenciaplano.agente_nombre,
                                        proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                        proyectoevidenciaplano.catreportequimicospartidas_id,
                                        contratos_partidas.clientepartidas_descripcion,
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
                                            LIMIT 1
                                        ), "") AS checked
                                    FROM
                                        proyectoevidenciaplano
                                        LEFT JOIN contratos_partidas  ON proyectoevidenciaplano.catreportequimicospartidas_id = contratos_partidas.id 
                                    WHERE
                                        proyectoevidenciaplano.proyecto_id = ' . $proyecto_id . ' 
                                        AND proyectoevidenciaplano.agente_nombre = "' . $agente_nombre . '" 
                                        AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta != ""
                                    GROUP BY
                                        proyectoevidenciaplano.proyecto_id,
                                        proyectoevidenciaplano.agente_id,
                                        proyectoevidenciaplano.agente_nombre,
                                        proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                        proyectoevidenciaplano.catreportequimicospartidas_id,
                                        contratos_partidas.clientepartidas_descripcion
                                    ORDER BY
                                        proyectoevidenciaplano.catreportequimicospartidas_id ASC,
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


                $value->partida = $value->clientepartidas_descripcion;


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
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosevaluadostabla($proyecto_id, $reporteregistro_id)
    {
        try {
            // $parametros = DB::select('SELECT
            //                                 TABLA.proyecto_id,
            //                                 TABLA.registro_id,
            //                                 TABLA.parametro,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_cas,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_ebullicion,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_pesomolecular,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_estadofisico,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_viaingreso,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_gradoriesgo,
            //                                 reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_limiteexposicion
            //                             FROM
            //                                 (
            //                                     SELECT
            //                                         reportequimicosevaluacion.proyecto_id,
            //                                         reportequimicosevaluacion.registro_id,
            //                                         -- reportequimicosevaluacion.reportequimicosevaluacion_punto,
            //                                         reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro AS parametro
            //                                     FROM
            //                                         reportequimicosevaluacion
            //                                         RIGHT JOIN reportequimicosevaluacionparametro ON reportequimicosevaluacion.id = reportequimicosevaluacionparametro.reportequimicosevaluacion_id
            //                                     WHERE
            //                                         reportequimicosevaluacion.proyecto_id = '.$proyecto_id.' 
            //                                         AND reportequimicosevaluacion.registro_id = '.$reporteregistro_id.' 
            //                                         -- AND reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Ácido sulfhídrico"
            //                                         -- AND reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Metano"
            //                                         -- OR reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Propano"
            //                                         -- OR reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Butano"
            //                                         -- OR reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Pentano"
            //                                         -- OR reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro = "Etano"
            //                                     GROUP BY
            //                                         reportequimicosevaluacion.proyecto_id,
            //                                         reportequimicosevaluacion.registro_id,
            //                                         reportequimicosevaluacionparametro.reportequimicosevaluacionparametro_parametro
            //                                 ) AS TABLA
            //                                 LEFT JOIN reportequimicosparametroscatalogo ON TABLA.parametro = reportequimicosparametroscatalogo.reportequimicosparametroscatalogo_parametro
            //                             ORDER BY
            //                                 TABLA.parametro ASC');

            $parametros = DB::select('CALL sp_anexo3_11_3_informe_quimico_b(?)', [$proyecto_id]);


            $dato['tabla_reporte_11_3'] = '';
            foreach ($parametros as $key => $value) {
                $dato['tabla_reporte_11_3'] .= '<tr>
                                                    <td>' . $value->NOMBRE . '</td>
                                                    <td>' . $value->NUM_CAS . '</td>
                                                    <td>' . $value->PM . '</td>
                                                    <td>' . $value->INGRESO . '</td>
                                                    <td>' . $value->RIESGO . '</td>
                                                    <td>' . $value->VLE . '</td>
                                                </tr>';
            }

            // respuesta

            $dato["total"] = count($parametros);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['tabla_reporte_11_3'] = '<tr>
                                                <td colspan="8">Error al consultar los químicos</td>
                                            </tr>';
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
    public function reportequimicosequipoutilizadotabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                            AND proyectoproveedores.catprueba_id = 15 -- Quimicos ------------------------------
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
                                                AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '" 
                                                AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . ' 
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
                                                AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . ' 
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
                                                AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . ' 
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
                                        proveedor.proveedor_NombreComercial ASC,
                                        equipo.equipo_Descripcion ASC,
                                        equipo.equipo_Marca ASC,
                                        equipo.equipo_Modelo ASC,
                                        equipo.equipo_Serie ASC');


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
    public function reportequimicosanexosresultadostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportequimicosanexosacreditacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                        AND reporteanexos.registro_id = ' . $reporteregistro_id . ' 
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
                                                        AND proyectoproveedores.catprueba_id = 15
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
    public function reportequimicosrevisionestabla($proyecto_id)
    {
        try {
            // $revisiones = DB::select('SELECT
            //                                 reportequimicos.id,
            //                                 reportequimicos.proyecto_id,
            //                                 reportequimicos.agente_id,
            //                                 reportequimicos.agente_nombre,
            //                                 reportequimicos.reportequimicos_revision AS reporte_revision,
            //                                 reportequimicos.reportequimicos_concluido,
            //                                 reportequimicos.reportequimicos_concluidonombre,
            //                                 reportequimicos.reportequimicos_concluidofecha,
            //                                 reportequimicos.reportequimicos_cancelado,
            //                                 reportequimicos.reportequimicos_canceladonombre,
            //                                 reportequimicos.reportequimicos_canceladofecha,
            //                                 reportequimicos.reportequimicos_canceladoobservacion,
            //                                 reportequimicos.created_at,
            //                                 reportequimicos.updated_at 
            //                             FROM
            //                                 reportequimicos
            //                             WHERE
            //                                 reportequimicos.proyecto_id = '.$proyecto_id.' 
            //                             ORDER BY
            //                                 reportequimicos.reportequimicos_revision DESC');


            //----------------------------


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
                                            AND reporterevisiones.agente_id = 15
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
    public function reportequimicosrevisionconcluir($reporte_id)
    {
        try {
            // $revision  = reportequimicosModel::findOrFail($reporte_id);
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
    /*
    public function reportequimicosrevisionnueva(Request $request)
    {
        try
        {
            // dd($request->all());


            // OBTENER ULTIMA REVISION
            // -------------------------------------------------
            

            $revision = DB::select('SELECT
                                        reportequimicos.id,
                                        reportequimicos.proyecto_id,
                                        reportequimicos.reportequimicos_revision 
                                    FROM
                                        reportequimicos 
                                    WHERE
                                        reportequimicos.proyecto_id = '.$request->proyecto_id.' 
                                    ORDER BY
                                        reportequimicos.reportequimicos_revision DESC
                                    LIMIT 1');


            // CLONAR REGISTRO REPORTE
            // -------------------------------------------------


            $revisionfinal  = reportequimicosModel::findOrFail($revision[0]->id);

            DB::statement('ALTER TABLE reportequimicos AUTO_INCREMENT = 1;');

            // $revisionnueva = $revisionfinal->replicate();
            $revisionnueva = $revisionfinal->replicate()->fill([
                  'reportequimicos_revision' => ($revision[0]->reportequimicos_revision + 1)
                , 'reportequimicos_concluido' => 0
                , 'reportequimicos_concluidonombre' => NULL
                , 'reportequimicos_concluidofecha' => NULL
                , 'reportequimicos_cancelado' => 0
                , 'reportequimicos_canceladonombre' => NULL
                , 'reportequimicos_canceladofecha' => NULL
                , 'reportequimicos_canceladoobservacion' => NULL
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


            $categorias_historial = reportequimicoscategoriaModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('registro_id', $revision[0]->id)
                                                                ->get();


            $categorias_nuevosid = array();
            DB::statement('ALTER TABLE reportequimicoscategoria AUTO_INCREMENT = 1;');
            foreach ($categorias_historial as $key => $value)
            {                
                $categoria = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $categoria->save();

                $categorias_nuevosid['id_'.$value->id] = $categoria->id;
            }
            // dd($categorias_nuevosid);


            // CLONAR REGISTROS TABLA AREAS
            // -------------------------------------------------

            
            $areas_historial = reportequimicosareaModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();

            $areas_nuevosid = array();
            DB::statement('ALTER TABLE reportequimicosarea AUTO_INCREMENT = 1;');
            foreach ($areas_historial as $key => $value)
            {                
                $area = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $area->save();

                $areas_nuevosid['id_'.$value->id] = $area->id;
            }
            // dd($areas_nuevosid);


            // CLONAR REGISTROS TABLA AREAS CATEGORIAS
            // -------------------------------------------------


            $areacategorias = DB::select('SELECT
                                                reportequimicosarea.proyecto_id,
                                                reportequimicosarea.registro_id,
                                                reportequimicosareacategoria.id,
                                                reportequimicosareacategoria.reportequimicosarea_id,
                                                reportequimicosareacategoria.reportequimicoscategoria_id,
                                                reportequimicosareacategoria.reportequimicosareacategoria_total,
                                                reportequimicosareacategoria.reportequimicosareacategoria_actividades 
                                            FROM
                                                reportequimicosareacategoria
                                                LEFT JOIN reportequimicosarea ON reportequimicosareacategoria.reportequimicosarea_id = reportequimicosarea.id
                                            WHERE
                                                reportequimicosarea.proyecto_id = '.$request->proyecto_id.' 
                                                AND reportequimicosarea.registro_id = '.$revision[0]->id);

            DB::statement('ALTER TABLE reportequimicosareacategoria AUTO_INCREMENT = 1;');
            foreach ($areacategorias as $key => $value)
            {
                $registro = reportequimicosareacategoriaModel::create([
                      'reportequimicosarea_id' => $areas_nuevosid['id_'.$value->reportequimicosarea_id]
                    , 'reportequimicoscategoria_id' => $categorias_nuevosid['id_'.$value->reportequimicoscategoria_id]
                    , 'reportequimicosareacategoria_total' => $value->reportequimicosareacategoria_total
                    , 'reportequimicosareacategoria_actividades' => $value->reportequimicosareacategoria_actividades
                ]);
            }
            // dd($areacategorias);


            // CLONAR REGISTROS TABLA EQUIPO PROTECCION PERSONAL
            // -------------------------------------------------


            $equipo_epp = reportequimicoseppModel::where('proyecto_id', $request->proyecto_id)
                                                ->where('registro_id', $revision[0]->id)
                                                ->get();

            DB::statement('ALTER TABLE reportequimicosepp AUTO_INCREMENT = 1;');
            foreach ($equipo_epp as $key => $value)
            {
                $epp = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $epp->save();
            }
            // dd($equipo_epp);


            // CLONAR REGISTROS TABLA METODO DE MUESTREOS
            // -------------------------------------------------


            $metodos_muestreos = reportequimicosmetodomuestreoModel::where('proyecto_id', $request->proyecto_id)
                                                                    ->where('registro_id', $revision[0]->id)
                                                                    ->get();


            DB::statement('ALTER TABLE reportequimicosmetodomuestreo AUTO_INCREMENT = 1;');
            foreach ($metodos_muestreos as $key => $value)
            {
                $metodo = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $metodo->save();
            }
            // dd($metodos_muestreos);


            // CLONAR REGISTROS TABLA QUIMICOS PROYECTO
            // -------------------------------------------------


            $quimicos_proyecto = reportequimicosproyectoModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();


            $quimicos_nuevosid = array();
            DB::statement('ALTER TABLE reportequimicosproyecto AUTO_INCREMENT = 1;');
            foreach ($quimicos_proyecto as $key => $value)
            {
                $quimico = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $quimico->save();

                $quimicos_nuevosid['id_'.$value->id] = $quimico->id;
            }
            // dd($quimicos_proyecto);


            // CLONAR REGISTROS TABLA GRUPOS QUIMICOS
            // -------------------------------------------------


            $quimicos_grupos = reportequimicosgruposModel::where('proyecto_id', $request->proyecto_id)
                                                        ->where('registro_id', $revision[0]->id)
                                                        ->get();


            DB::statement('ALTER TABLE reportequimicosgrupos AUTO_INCREMENT = 1;');
            foreach ($quimicos_grupos as $key => $value)
            {
                $grupo = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                    , 'reportequimicosproyecto_id' => $quimicos_nuevosid['id_'.$value->reportequimicosproyecto_id]
                ]);

                $grupo->save();
            }
            // dd($quimicos_grupos);


            // CLONAR REGISTROS TABLA EVALUACION
            // -------------------------------------------------


            $evaluacion = reportequimicosevaluacionModel::where('proyecto_id', $request->proyecto_id)
                                                        ->where('registro_id', $revision[0]->id)
                                                        ->get();


            DB::statement('ALTER TABLE reportequimicosevaluacion AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE reportequimicosevaluacionparametro AUTO_INCREMENT = 1;');

            foreach ($evaluacion as $key => $value)
            {
                $categoria_id = 0;
                if (($value->reportequimicoscategoria_id+0) > 0)
                {
                    $categoria_id = $categorias_nuevosid['id_'.$value->reportequimicoscategoria_id];
                }

                $punto = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                    , 'reportequimicosarea_id' => $areas_nuevosid['id_'.$value->reportequimicosarea_id]
                    , 'reportequimicoscategoria_id' => $categoria_id
                ]);

                $punto->save();

                // CLONAR REGISTROS TABLA EVALUACION PARAMETROS
                $punto_parametros = reportequimicosevaluacionparametroModel::where('reportequimicosevaluacion_id', $value->id)->get();
                foreach ($punto_parametros as $key2 => $value2)
                {
                    $parametro = $value2->replicate()->fill([
                          'reportequimicosevaluacion_id' => $punto->id
                    ]);

                    $parametro->save();
                }
            }
            // dd($evaluacion);



            // CLONAR REGISTROS TABLA CONCLUSIONES
            // -------------------------------------------------


            $conclusiones = reportequimicosconclusionModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();


            DB::statement('ALTER TABLE reportequimicosconclusion AUTO_INCREMENT = 1;');
            foreach ($conclusiones as $key => $value)
            {
                $conclusion = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $conclusion->save();
            }
            // dd($conclusiones);


            // GUARDAR GRAFICAS DASHBOARD
            // -------------------------------------------------


            if ($request->dashboard_graficas)
            {
                // Convertir fotos a FILE y guardar
                for ($i=0; $i < count($request->dashboard_graficas); $i++)
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', str_replace("*", "", $request->dashboard_graficas[$i])); //Quitar los [*] de la cadena string
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/partidas/grafica_partida_'.$request->dashboard_partidas[$i].'.jpg'; // GRAFICA

                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
                }
            }
            // dd($destinoPath);

        
            // CLONAR CARPETA ARCHIVOS, PLANO UBICACION, RESPONSABLES DEL INFORME, GRAFICAS DASHBOARD
            // -------------------------------------------------


            $carpetaarchivos_historial = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id;
            $carpetaarchivos_destino = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revisionnueva->id;
            

            Storage::makeDirectory($carpetaarchivos_destino); //crear directorio
            File::copyDirectory(storage_path('app/'.$carpetaarchivos_historial), storage_path('app/'.$carpetaarchivos_destino)); //Copiar contenido directorio


            if (Storage::exists($carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'))
            {
                $revisionnueva->update([
                    'reportequimicos_ubicacionfoto' => $carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                    'reportequimicos_ubicacionfoto' => NULL
                ]);
            }


            if (Storage::exists($carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'))
            {
                $revisionnueva->update([
                      'reportequimicos_responsable1documento' => $carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'
                    , 'reportequimicos_responsable2documento' => $carpetaarchivos_destino.'/responsables informe/responsable2_doc.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                      'reportequimicos_responsable1documento' => NULL
                    , 'reportequimicos_responsable2documento' => NULL
                ]);
            }


            // -------------------------------------------------


            // respuesta
            $dato["reporteregistro_id"] = $revisionnueva->id;
            $dato["msj"] = 'Revisión creada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["reporteregistro_id"] = $request->reporteregistro_id;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
    */


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @param int $reporteregistro_id
     * @param int $reporterevisiones_id
     * @return \Illuminate\Http\Response
     */
    public function reportequimicospartidashistorial($proyecto_id, $reporteregistro_id, $reporterevisiones_id)
    {

        try {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                ->where('agente_id', 15)
                ->orderBy('reporterevisiones_revision', 'DESC')
                ->get();


            if (($revision[0]->id + 0) == ($reporterevisiones_id + 0)) {
                $partidas = DB::select('SELECT
                                            reportequimicosgrupos.proyecto_id,
                                            reportequimicosgrupos.registro_id,
                                            reportequimicosgrupos.proveedor_id,
                                            proveedor.proveedor_NombreComercial,
                                            reportequimicosgrupos.catreportequimicospartidas_id,
                                            clientepartidas.clientepartidas_descripcion
                                        FROM
                                            reportequimicosgrupos
                                            LEFT JOIN proveedor ON reportequimicosgrupos.proveedor_id = proveedor.id
                                            LEFT JOIN contratos_partidas AS clientepartidas ON reportequimicosgrupos.catreportequimicospartidas_id = clientepartidas.id
                                            LEFT JOIN reportequimicosproyecto ON reportequimicosgrupos.reportequimicosproyecto_id = reportequimicosproyecto.id 
                                        WHERE
                                            reportequimicosgrupos.proyecto_id = ' . $proyecto_id . '  
                                            AND reportequimicosgrupos.registro_id = ' . $reporteregistro_id . '  
                                        GROUP BY
                                            reportequimicosgrupos.proyecto_id,
                                            reportequimicosgrupos.registro_id,
                                            reportequimicosgrupos.proveedor_id,
                                            proveedor.proveedor_NombreComercial,
                                            reportequimicosgrupos.catreportequimicospartidas_id,
                                            clientepartidas.clientepartidas_descripcion
                                        ORDER BY
                                            clientepartidas.id ASC,
                                            clientepartidas.clientepartidas_descripcion ASC');
            } else {
                $partidas = DB::select('SELECT
                                            reporterevisiones.proyecto_id,
                                            reporterevisiones.agente_id,
                                            reporterevisiones.agente_nombre,
                                            reporterevisiones.id,
                                            reporterevisiones.reporterevisiones_revision,
                                            reporterevisiones.reporterevisiones_concluido,
                                            reporterevisiones.reporterevisiones_cancelado,
                                            reporterevisionesarchivo.reporterevisionesarchivo_tipo,
                                            clientepartidas.id AS catreportequimicospartidas_id,
                                            clientepartidas.clientepartidas_descripcion,
                                            reporterevisionesarchivo.reporterevisionesarchivo_archivo
                                        FROM
                                            reporterevisiones
                                            RIGHT JOIN reporterevisionesarchivo ON reporterevisiones.id = reporterevisionesarchivo.reporterevisiones_id
                                            LEFT JOIN clientepartidas ON reporterevisionesarchivo.reporterevisionesarchivo_tipo = clientepartidas.id
                                        WHERE
                                            reporterevisiones.id = ' . $reporterevisiones_id . ' 
                                        ORDER BY
                                            clientepartidas.id ASC,
                                            clientepartidas.clientepartidas_descripcion ASC');
            }


            $dato['partidas_opciones'] = '<option value=""></option>';


            if (count($partidas) > 0) {
                foreach ($partidas as $key => $value) {
                    $dato['partidas_opciones'] .= '<option value="' . $value->catreportequimicospartidas_id . '">' . $value->clientepartidas_descripcion . '</option>';
                }
            } else {
                $dato['partidas_opciones'] .= '<option value="0">Revisión sin partidas, Generar informe con todos los químicos evaluados</option>';
            }


            // respuesta
            $dato["msj"] = 'Partidas consultadas correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['partidas_opciones'] = '<option value="">Error al consultar partidas</option>';
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
                $reporte = reportequimicosModel::findOrFail($request->reporteregistro_id);


                $reporte->update([
                    'reportequimicos_instalacion' => $request->reporte_instalacion
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


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion + 0) != 22) // Valida disponibilidad de esta version
                {
                    // respuesta
                    $dato["msj"] = 'Informe de ' . $request->agente_nombre . ' NO disponible para edición';
                    return response()->json($dato);
                }
            } else {
                DB::statement('ALTER TABLE reportequimicos AUTO_INCREMENT = 1;');


                if (!$request->catactivo_id) {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }


                $reporte = reportequimicosModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'catactivo_id' => $request->catactivo_id,
                    'reportequimicos_revision' => 0,
                    'reportequimicos_instalacion' => $request->reporte_instalacion,
                    'reportequimicos_catregion_activo' => 1,
                    'reportequimicos_catsubdireccion_activo' => 1,
                    'reportequimicos_catgerencia_activo' => 1,
                    'reportequimicos_catactivo_activo' => 1,
                    'reportequimicos_concluido' => 0,
                    'reportequimicos_cancelado' => 0
                ]);


                //--------------------------------------


                // ASIGNAR QUIMICOS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportequimicosproyecto
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // ASIGNAR CATEGORIAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportequimicoscategoria
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');


                // ASIGNAR AREAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportequimicosarea
                                SET 
                                    registro_id = ' . $reporte->id . '
                                WHERE 
                                    proyecto_id = ' . $request->proyecto_id . '
                                    AND IFNULL(registro_id, "") = "";');
            }


            //============================================================


            $quimicos_nombre = $this->quimicosnombre($request->proyecto_id, $reporte->id);


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
                    'reportequimicos_catregion_activo' => $catregion_activo,
                    'reportequimicos_catsubdireccion_activo' => $catsubdireccion_activo,
                    'reportequimicos_catgerencia_activo' => $catgerencia_activo,
                    'reportequimicos_catactivo_activo' => $catactivo_activo,
                    'reportequimicos_instalacion' => $request->reporte_instalacion,
                    'reportequimicos_fecha' => $request->reporte_fecha,
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
                    'reportequimicos_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_introduccion)
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
                    'reportequimicos_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion + 0) == 4) {
                $reporte->update([
                    'reportequimicos_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion + 0) == 5) {
                $reporte->update([
                    'reportequimicos_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion + 0) == 6) {
                $reporte->update([
                    'reportequimicos_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion + 0) == 7) {
                $reporte->update([
                    'reportequimicos_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_ubicacioninstalacion)
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
                        'reportequimicos_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion + 0) == 8) {
                $reporte->update([
                    'reportequimicos_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_procesoinstalacion),
                    'reportequimicos_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->reporte_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion + 0) == 9) {
                if (($request->reportecategoria_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportequimicoscategoria AUTO_INCREMENT = 1;');

                    $request['recsensorialcategoria_id'] = 0;
                    $request['registro_id'] = $reporte->id;
                    $categoria = reportequimicoscategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reportequimicoscategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS
            if (($request->opcion + 0) == 10) {
                if (($request->areas_poe + 0) == 1) {
                    $request['reportequimicosarea_porcientooperacion'] = $request->reportequimicosarea_porcientooperacion;
                    $request['reportearea_caracteristica'] = $request->reportequimicosarea_caracteristica;
                    $request['reportearea_maquinaria'] = $request->reportequimicosarea_maquinaria;
                    $request['reportearea_contaminante'] = $request->reportequimicosarea_contaminante;

                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reportequimicosareacategoriaModel::where('reportequimicosarea_id', $request->reportearea_id)
                        ->where('reportequimicosareacategoria_poe', $request->reporteregistro_id)
                        ->delete();


                    if ($request->checkbox_categoria_id) {
                        DB::statement('ALTER TABLE reportequimicosareacategoria AUTO_INCREMENT = 1;');

                        foreach ($request->checkbox_categoria_id as $key => $value) {
                            $areacategoria = reportequimicosareacategoriaModel::create([
                                'reportequimicosarea_id' => $area->id,
                                'reportequimicosareacategoria_poe' => $request->reporteregistro_id,
                                'reportequimicoscategoria_id' => $value,
                                'reportequimicosareacategoria_total' => $request['areacategoria_total_' . $value],
                                'reportequimicosareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                } else {
                    if (($request->reportearea_id + 0) == 0) {
                        DB::statement('ALTER TABLE reportequimicosarea AUTO_INCREMENT = 1;');

                        $request['registro_id'] = $reporte->id;
                        $request['recsensorialarea_id'] = 0;
                        $area = reportequimicosareaModel::create($request->all());


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reportequimicosareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reportequimicosareacategoriaModel::create([
                                    'reportequimicosarea_id' => $area->id,
                                    'reportequimicosareacategoria_poe' => 0,
                                    'reportequimicoscategoria_id' => $value,
                                    'reportequimicosareacategoria_total' => $request['areacategoria_total_' . $value],
                                    'reportequimicosareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    } else {
                        $request['registro_id'] = $reporte->id;
                        $area = reportequimicosareaModel::findOrFail($request->reportearea_id);
                        $area->update($request->all());


                        $eliminar_categorias = reportequimicosareacategoriaModel::where('reportequimicosarea_id', $request->reportearea_id)
                            ->where('reportequimicosareacategoria_poe', 0)
                            ->delete();


                        if ($request->checkbox_categoria_id) {
                            DB::statement('ALTER TABLE reportequimicosareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) {
                                $areacategoria = reportequimicosareacategoriaModel::create([
                                    'reportequimicosarea_id' => $area->id,
                                    'reportequimicosareacategoria_poe' => 0,
                                    'reportequimicoscategoria_id' => $value,
                                    'reportequimicosareacategoria_total' => $request['areacategoria_total_' . $value],
                                    'reportequimicosareacategoria_actividades' => $request['areacategoria_actividades_' . $value]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // EQUIPO PROTECCION PERSONAL
            if (($request->opcion + 0) == 11) {
                if (($request->reporteepp_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportequimicosepp AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $categoria = reportequimicoseppModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reportequimicoseppModel::findOrFail($request->reporteepp_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // PUNTO DE EVALUACION
            if (($request->opcion + 0) == 12) {
                // dd($request->all());


                if (($request->puntoevaluacion_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportequimicosevaluacion AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $puntoevaluacion = reportequimicosevaluacionModel::create($request->all());

                    if ($request->reportequimicosevaluacionparametro_parametro) {
                        DB::statement('ALTER TABLE reportequimicosevaluacionparametro AUTO_INCREMENT = 1;');

                        foreach ($request->reportequimicosevaluacionparametro_parametro as $key => $value) {
                            $parametro = reportequimicosevaluacionparametroModel::create([
                                'reportequimicosevaluacion_id' => $puntoevaluacion->id,
                                'reportequimicosevaluacionparametro_parametro' => $value,
                                'reportequimicosevaluacionparametro_metodo' => $request['reportequimicosevaluacionparametro_metodo'][$key],
                                'reportequimicosevaluacionparametro_unidad' => $request['reportequimicosevaluacionparametro_unidad'][$key],
                                'reportequimicosevaluacionparametro_concentracion' => $request['reportequimicosevaluacionparametro_concentracion'][$key],
                                'reportequimicosevaluacionparametro_valorlimite' => $request['reportequimicosevaluacionparametro_valorlimite'][$key],
                                'reportequimicosevaluacionparametro_limitesuperior' => $request['reportequimicosevaluacionparametro_limitesuperior'][$key],
                                'reportequimicosevaluacionparametro_periodo' => $request['reportequimicosevaluacionparametro_periodo'][$key]
                            ]);
                        }
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['registro_id'] = $reporte->id;
                    $puntoevaluacion = reportequimicosevaluacionModel::findOrFail($request->puntoevaluacion_id);
                    $puntoevaluacion->update($request->all());

                    if ($request->reportequimicosevaluacionparametro_parametro) {
                        $eliminar_parametros = reportequimicosevaluacionparametroModel::where('reportequimicosevaluacion_id', $request->puntoevaluacion_id)->delete();

                        DB::statement('ALTER TABLE reportequimicosevaluacionparametro AUTO_INCREMENT = 1;');

                        foreach ($request->reportequimicosevaluacionparametro_parametro as $key => $value) {
                            $parametro = reportequimicosevaluacionparametroModel::create([
                                'reportequimicosevaluacion_id' => $puntoevaluacion->id,
                                'reportequimicosevaluacionparametro_parametro' => $value,
                                'reportequimicosevaluacionparametro_metodo' => $request['reportequimicosevaluacionparametro_metodo'][$key],
                                'reportequimicosevaluacionparametro_unidad' => $request['reportequimicosevaluacionparametro_unidad'][$key],
                                'reportequimicosevaluacionparametro_concentracion' => $request['reportequimicosevaluacionparametro_concentracion'][$key],
                                'reportequimicosevaluacionparametro_valorlimite' => $request['reportequimicosevaluacionparametro_valorlimite'][$key],
                                'reportequimicosevaluacionparametro_limitesuperior' => $request['reportequimicosevaluacionparametro_limitesuperior'][$key],
                                'reportequimicosevaluacionparametro_periodo' => $request['reportequimicosevaluacionparametro_periodo'][$key]
                            ]);
                        }
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // METODO DE MUESTREO
            if (($request->opcion + 0) == 13) {
                if ($request->reportequimicosmetodomuestreo_flujo) {
                    $eliminar_flujos = reportequimicosmetodomuestreoModel::where('proyecto_id', $request->proyecto_id)
                        ->where('registro_id', $reporte->id)
                        ->where('reportequimicosmetodomuestreo_parametro', $request->reportequimicosmetodomuestreo_parametro)
                        ->delete();

                    DB::statement('ALTER TABLE reportequimicosmetodomuestreo AUTO_INCREMENT = 1;');

                    foreach ($request->reportequimicosmetodomuestreo_flujo as $key => $value) {
                        $flujo = reportequimicosmetodomuestreoModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'reportequimicosmetodomuestreo_parametro' => $request->reportequimicosmetodomuestreo_parametro,
                            'reportequimicosmetodomuestreo_puntos' => $request->reportequimicosmetodomuestreo_puntos,
                            'reportequimicosmetodomuestreo_metodo' => $request->reportequimicosmetodomuestreo_metodo,
                            'reportequimicosmetodomuestreo_tipo' => $request->reportequimicosmetodomuestreo_tipo,
                            'reportequimicosmetodomuestreo_orden' => ($key + 1),
                            'reportequimicosmetodomuestreo_flujo' => $value
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardadas correctamente';
            }


            // CONCLUSIONES
            if (($request->opcion + 0) == 14) {
                if (($request->reporteconclusion_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportequimicosconclusion AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $request['reportequimicosconclusion_conclusion'] = $request->reportequimicosconclusion_conclusion;
                    $conclusion = reportequimicosconclusionModel::create($request->all());

                    // Mensaje
                    $dato["conclusion_id"] = $conclusion->id;
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {

                    $request['registro_id'] = $reporte->id;
                    $request['reportequimicosconclusion_conclusion'] = $request->reportequimicosconclusion_conclusion;
                    $conclusion = reportequimicosconclusionModel::findOrFail($request->reporteconclusion_id);
                    $conclusion->update($request->all());

                    // Mensaje
                    $dato["conclusion_id"] = $conclusion->id;
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // RECOMENDACIONES
            if (($request->opcion + 0) == 15) {
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
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request['recomendacion_descripcion_' . $value])
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
                            'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $quimicos_nombre, $request->recomendacionadicional_descripcion[$key]),
                            'catalogo_id' => $request->recomendacionadicional_quimicopartida[$key]
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion + 0) == 16) {
                $reporte->update([
                    'reportequimicos_responsable1' => $request->reporte_responsable1,
                    'reportequimicos_responsable1cargo' => $request->reporte_responsable1cargo,
                    'reportequimicos_responsable2' => $request->reporte_responsable2,
                    'reportequimicos_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial) {
                    $nuevo_destino = 'reportes/proyecto/' . $request->proyecto_id . '/' . $request->agente_nombre . '/' . $reporte->id . '/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/' . $request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/' . $nuevo_destino));

                    $reporte->update([
                        'reportequimicos_responsable1documento' => $nuevo_destino . 'responsable1_doc.jpg',
                        'reportequimicos_responsable2documento' => $nuevo_destino . 'responsable2_doc.jpg'
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
                        'reportequimicos_responsable1documento' => $destinoPath
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
                        'reportequimicos_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion + 0) == 17) {
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
            if (($request->opcion + 0) == 18) {
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
                            'registro_id' => $request->reporteregistro_id,
                            'equipo_id' => $value,
                            'reporteequiposutilizados_cartacalibracion' => $request->reporteequiposutilizados_cartacalibracion
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion + 0) == 19) {
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
            if (($request->opcion + 0) == 20) {
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


            // GRUPO QUIMICOS
            if (($request->opcion + 0) == 21) {
                if ($request->parametro) {
                    $eliminar_parametros = reportequimicosgruposModel::where('proyecto_id', $request->proyecto_id)
                        ->where('registro_id', $reporte->id)
                        // ->where('catreportequimicospartidas_id', $request->catreportequimicospartidas_id)
                        ->delete();


                    DB::statement('ALTER TABLE reportequimicosgrupos AUTO_INCREMENT = 1;');


                    foreach ($request->parametro as $key => $value) {
                        $parametro = reportequimicosgruposModel::create([
                            'proyecto_id' => $request->proyecto_id,
                            'registro_id' => $reporte->id,
                            'proveedor_id' => $request->proveedor_id,
                            'catreportequimicospartidas_id' => null,
                            'reportequimicosproyecto_id' => $value
                        ]);


                        reportequimicosproyectoModel::findOrFail($value)->update([
                            'registro_id' => $reporte->id
                        ]);
                    }
                }


                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // REVISION INFORME, CANCELACION
            if (($request->opcion + 0) == 22) {
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
