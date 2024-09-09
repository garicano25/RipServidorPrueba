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

//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
//----------------------------------------------------------

// Modelos estructura reporte
use App\modelos\reportes\reporteserviciopersonalcatalogoModel;
use App\modelos\reportes\reporteserviciopersonalModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteserviciopersonalcondicioninseguraModel;
use App\modelos\reportes\reporteserviciopersonalcondicioninseguracategoriasModel;
use App\modelos\reportes\reporteserviciopersonalevaluacionpydModel;
use App\modelos\reportes\reporteserviciopersonalevaluacioncatalogoModel;
use App\modelos\reportes\reporteserviciopersonalevaluacionModel;

use App\modelos\reportes\recursosPortadasInformesModel;


class reporteserviciopersonalController extends Controller
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
    public function reporteserviciopersonalvista($proyecto_id)
    {
        //Zona horaria local
        date_default_timezone_set('America/Mexico_City');
        setlocale(LC_ALL,"es_MX");

        $agente_id = 16;
        $agente_nombre = 'Infraestructura para Servicios al personal';

        $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);


        if (($proyecto->recsensorial->recsensorial_tipocliente+0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL))
        {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Infraestructura para Servicios al personal, primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        }
        else
        {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', $agente_id) // Servicio al personal
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();

            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================

            if(count($revision) == 0)
            {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                      'proyecto_id' => $proyecto_id
                    , 'agente_id' => $agente_id
                    , 'agente_nombre' =>$agente_nombre
                    , 'reporterevisiones_revision' => 0
                    , 'reporterevisiones_concluido' => 0
                    , 'reporterevisiones_concluidonombre' => NULL
                    , 'reporterevisiones_concluidofecha' => NULL
                    , 'reporterevisiones_cancelado' => 0
                    , 'reporterevisiones_canceladonombre' => NULL
                    , 'reporterevisiones_canceladofecha' => NULL
                    , 'reporterevisiones_canceladoobservacion' => NULL
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
                                            proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                            AND proyectoproveedores.catprueba_id = '.$agente_id.' -- Servicio al personal
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            //DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            // $proveedor_id = $proveedor[0]->proveedor_id;
            $proveedor_id = 0; //QUITAR DESPUES DE SUBIR AL SERVIDOR



            //===================================================


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);


            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();

            $areaspoe = reporteareaModel::where('proyecto_id', $proyecto_id)->orderBy('reportearea_nombre', 'ASC')->get();
            $categoriaspoe = reportecategoriaModel::where('proyecto_id', $proyecto_id)->orderBy('reportecategoria_nombre', 'ASC')->get();

            $evaluacion_catalogo = reporteserviciopersonalevaluacioncatalogoModel::get();

            // Vista
            return view('reportes.parametros.reporteserviciopersonal', compact('proyecto', 'recsensorial', 'areaspoe', 'categoriaspoe', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'proveedor_id', 'evaluacion_catalogo'));
        }
    }


    public function datosproyectolimpiartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace($recsensorial->cliente->cliente_RazonSocial, 'CLIENTE_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        $texto = str_replace($reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], 'REPORTE_FECHA_LARGA', $texto);

        if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace($proyecto->catsubdireccion->catsubdireccion_nombre, 'SUBDIRECCION_NOMBRE', $texto);
            $texto = str_replace($proyecto->catgerencia->catgerencia_nombre, 'GERENCIA_NOMBRE', $texto);
            $texto = str_replace($proyecto->catactivo->catactivo_nombre, 'ACTIVO_NOMBRE', $texto);
        }
        else
        {
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Exploración y Producción', $texto);
        }

        return $texto;
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace('CLIENTE_NOMBRE', $recsensorial->cliente->cliente_RazonSocial, $texto);
        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. '.$recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], $texto);

        if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace('SUBDIRECCION_NOMBRE', $proyecto->catsubdireccion->catsubdireccion_nombre, $texto);
            $texto = str_replace('GERENCIA_NOMBRE', $proyecto->catgerencia->catgerencia_nombre, $texto);
            $texto = str_replace('ACTIVO_NOMBRE', $proyecto->catactivo->catactivo_nombre, $texto);
        }
        else
        {
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
    public function reporteserviciopersonaldatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try
        {
            $agente_id = 16;
            $agente_nombre = 'Infraestructura para Servicios al personal';

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $reportecatalogo = reporteserviciopersonalcatalogoModel::findOrFail(1); // Pemex
            }
            else
            {
                $reportecatalogo = reporteserviciopersonalcatalogoModel::findOrFail(2); //Cliente
            }

            
            $reporte = reporteserviciopersonalModel::where('proyecto_id', $proyecto_id)->get();
                                        

            if (count($reporte) > 0)
            {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = ($reporte->id+0);
            }
            else
            {
                if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reporteserviciopersonalModel::where('catactivo_id', $proyecto->catactivo_id)
                                                            ->orderBy('updated_at', 'DESC')
                                                            ->get();
                }
                else
                {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reporteserviciopersonal.id,
                                                reporteserviciopersonal.proyecto_id,
                                                reporteserviciopersonal.catactivo_id,
                                                reporteserviciopersonal.reporteserviciopersonal_fecha,
                                                reporteserviciopersonal.reporte_mes,

                                                reporteserviciopersonal.reporteserviciopersonal_instalacion,
                                                reporteserviciopersonal.reporteserviciopersonal_catregion_activo,
                                                reporteserviciopersonal.reporteserviciopersonal_catsubdireccion_activo,
                                                reporteserviciopersonal.reporteserviciopersonal_catgerencia_activo,
                                                reporteserviciopersonal.reporteserviciopersonal_catactivo_activo,
                                                reporteserviciopersonal.reporteserviciopersonal_alcanceinforme,
                                                reporteserviciopersonal.reporteserviciopersonal_introduccion,
                                                reporteserviciopersonal.reporteserviciopersonal_objetivogeneral,
                                                reporteserviciopersonal.reporteserviciopersonal_objetivoespecifico,
                                                reporteserviciopersonal.reporteserviciopersonal_metodologia_4,
                                                reporteserviciopersonal.reporteserviciopersonal_ubicacioninstalacion,
                                                reporteserviciopersonal.reporteserviciopersonal_ubicacionfoto,
                                                reporteserviciopersonal.reporteserviciopersonal_procesoinstalacion,
                                                reporteserviciopersonal.reporteserviciopersonal_metodologia_8_3,
                                                reporteserviciopersonal.reporteserviciopersonal_metodologia_8_4,
                                                reporteserviciopersonal.reporteserviciopersonal_conclusion,
                                                reporteserviciopersonal.reporteserviciopersonal_recomendaciones,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable1,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable1cargo,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable1documento,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable2,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable2cargo,
                                                reporteserviciopersonal.reporteserviciopersonal_responsable2documento,
                                                reporteserviciopersonal.created_at,
                                                reporteserviciopersonal.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reporteserviciopersonal ON proyecto.id = reporteserviciopersonal.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = '.$recsensorial->cliente_id.' 
                                                AND reporteserviciopersonal.reporteserviciopersonal_instalacion <> "" 
                                            ORDER BY
                                                reporteserviciopersonal.updated_at DESC');
                }


                if (count($reporte) > 0)
                {
                    $reporte = $reporte[0];
                    $dato['reporteregistro_id'] = 0;
                }
                else
                {
                    $reporte = array(0, 0);
                    $dato['reporteregistro_id'] = -1;
                }
            }


            //------------------------------


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', $agente_id) //servicio al personal
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();


            if(count($revision) > 0)
            {
                $revision = reporterevisionesModel::findOrFail($revision[0]->id);


                $dato['reporte_concluido'] = $revision->reporterevisiones_concluido;
                $dato['reporte_cancelado'] = $revision->reporterevisiones_cancelado;
            }
            else
            {
                $dato['reporte_concluido'] = 0;
                $dato['reporte_cancelado'] = 0;
            }

            
            // PORTADA
            //===================================================


            $dato['recsensorial_tipocliente'] = ($recsensorial->recsensorial_tipocliente+0);


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_fecha != NULL)
            {
                $reportefecha = $reporte->reporteserviciopersonal_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                                                  'reporte_catregion_activo' => $reporte->reporteserviciopersonal_catregion_activo
                                                , 'catregion_id' => $proyecto->catregion_id
                                                , 'reporte_catsubdireccion_activo' => $reporte->reporteserviciopersonal_catsubdireccion_activo
                                                , 'catsubdireccion_id' => $proyecto->catsubdireccion_id
                                                , 'reporte_catgerencia_activo' => $reporte->reporteserviciopersonal_catgerencia_activo
                                                , 'catgerencia_id' => $proyecto->catgerencia_id
                                                , 'reporte_catactivo_activo' => $reporte->reporteserviciopersonal_catactivo_activo
                                                , 'catactivo_id' => $proyecto->catactivo_id
                                                , 'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion
                                                , 'reporte_fecha' => $reportefecha
                                                , 'reporte_alcanceinforme' => $reporte->reporteserviciopersonal_alcanceinforme
                                                , 'reporte_mes' => $reporte->reporte_mes

                                            );
            }
            else
            {
                $reportefecha = $meses[$proyectofecha[1] + 0]." del ".$proyectofecha[0];
                $dato['reporte_portada_guardado'] = 0;

                $dato['reporte_portada'] = array(
                                                  'reporte_catregion_activo' => 1
                                                , 'catregion_id' => $proyecto->catregion_id
                                                , 'reporte_catsubdireccion_activo' => 1
                                                , 'catsubdireccion_id' => $proyecto->catsubdireccion_id
                                                , 'reporte_catgerencia_activo' => 1
                                                , 'catgerencia_id' => $proyecto->catgerencia_id
                                                , 'reporte_catactivo_activo' => 1
                                                , 'catactivo_id' => $proyecto->catactivo_id
                                                , 'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion
                                                , 'reporte_fecha' => $reportefecha
                                                , 'reporte_alcanceinforme' => ''
                                                , 'reporte_mes' => ""
                                                

                                            );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_introduccion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_introduccion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reporteserviciopersonal_introduccion;
            }
            else
            {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo->reporteserviciopersonalcatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_objetivogeneral != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reporteserviciopersonal_objetivogeneral;
            }
            else
            {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo->reporteserviciopersonalcatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_objetivoespecifico != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reporteserviciopersonal_objetivoespecifico;
            }
            else
            {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo->reporteserviciopersonalcatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_metodologia_4 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reporteserviciopersonal_metodologia_4;
            }
            else
            {
                $dato['reporte_metodologia_4_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo->reporteserviciopersonalcatalogo_metodologia_4;
            }

            $dato['reporte_metodologia_4'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_ubicacioninstalacion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reporteserviciopersonal_ubicacioninstalacion;
            }
            else
            {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo->reporteserviciopersonalcatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_ubicacionfoto != NULL)
            {
                $ubicacionfoto = $reporte->reporteserviciopersonal_ubicacionfoto;
            }


            $dato['reporte_ubicacioninstalacion'] = array(
                                                          'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion)
                                                        , 'ubicacionfoto' => $ubicacionfoto
                                                    );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_procesoinstalacion != NULL)
            {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reporteserviciopersonal_procesoinstalacion;
            }
            else
            {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }


            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // PUNTO 8.3
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_metodologia_8_3 != NULL)
            {
                $dato['reporte_punto_8_3_guardado'] = 1;
                $punto_8_3 = $reporte->reporteserviciopersonal_metodologia_8_3;
            }
            else
            {
                $dato['reporte_punto_8_3_guardado'] = 0;
                $punto_8_3 = $reportecatalogo->reporteserviciopersonalcatalogo_metodologia_8_3;
            }


            $dato['reporte_punto_8_3'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $punto_8_3);


            // PUNTO 8.4
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_metodologia_8_4 != NULL)
            {
                $dato['reporte_punto_8_4_guardado'] = 1;
                $punto_8_4 = $reporte->reporteserviciopersonal_metodologia_8_4;
            }
            else
            {
                $dato['reporte_punto_8_4_guardado'] = 0;
                $punto_8_4 = $reportecatalogo->reporteserviciopersonalcatalogo_metodologia_8_4;
            }


            $dato['reporte_punto_8_4'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $punto_8_4);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_conclusion != NULL)
            {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reporteserviciopersonal_conclusion;
            }
            else
            {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo->reporteserviciopersonalcatalogo_conclusion;
            }


            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RECOMENDACIONES
            //===================================================


            if ($dato['reporteregistro_id'] > 0 && $reporte->reporteserviciopersonal_recomendaciones != NULL)
            {
                $dato['reporte_recomendaciones_guardado'] = 1;
                $conclusion = $reporte->reporteserviciopersonal_recomendaciones;
            }
            else
            {
                $dato['reporte_recomendaciones_guardado'] = 0;
                $conclusion = $reportecatalogo->reporteserviciopersonalcatalogo_recomendaciones;
            }


            $dato['reporte_recomendaciones'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteserviciopersonal_responsable1 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_responsablesinforme_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_responsablesinforme_guardado'] = 0;
                }

                $dato['reporte_responsablesinforme'] = array(
                                                              'responsable1' => $reporte->reporteserviciopersonal_responsable1
                                                            , 'responsable1cargo' => $reporte->reporteserviciopersonal_responsable1cargo
                                                            , 'responsable1documento' => $reporte->reporteserviciopersonal_responsable1documento
                                                            , 'responsable2' => $reporte->reporteserviciopersonal_responsable2
                                                            , 'responsable2cargo' => $reporte->reporteserviciopersonal_responsable2cargo
                                                            , 'responsable2documento' => $reporte->reporteserviciopersonal_responsable2documento
                                                            , 'proyecto_id' => $reporte->proyecto_id
                                                            , 'registro_id' => $reporte->id
                                                        );
            }
            else
            {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reporteserviciopersonalModel::where('reporteserviciopersonal_responsable1', '!=', '')
                                                                ->orderBy('updated_at', 'DESC')
                                                                ->limit(1)
                                                                ->get();


                if (count($reportehistorial) > 0 && $reportehistorial[0]->reporteserviciopersonal_responsable1 != NULL)
                {
                    $dato['reporte_responsablesinforme'] = array(
                                                                  'responsable1' => $reportehistorial[0]->reporteserviciopersonal_responsable1
                                                                , 'responsable1cargo' => $reportehistorial[0]->reporteserviciopersonal_responsable1cargo
                                                                , 'responsable1documento' => $reportehistorial[0]->reporteserviciopersonal_responsable1documento
                                                                , 'responsable2' => $reportehistorial[0]->reporteserviciopersonal_responsable2
                                                                , 'responsable2cargo' => $reportehistorial[0]->reporteserviciopersonal_responsable2cargo
                                                                , 'responsable2documento' => $reportehistorial[0]->reporteserviciopersonal_responsable2documento
                                                                , 'proyecto_id' => $reportehistorial[0]->proyecto_id
                                                                , 'registro_id' => $reportehistorial[0]->id
                                                            );
                }
                else
                {
                    $dato['reporte_responsablesinforme'] = array(
                                                                  'responsable1' => NULL
                                                                , 'responsable1cargo' => NULL
                                                                , 'responsable1documento' => NULL
                                                                , 'responsable2' => NULL
                                                                , 'responsable2cargo' => NULL
                                                                , 'responsable2documento' => NULL
                                                                , 'proyecto_id' => 0
                                                                , 'registro_id' => 0
                                                            );
                }
            }


            //===================================================


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteserviciopersonaltabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 16) // Servicio al personal
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();


            $edicion = 1;
            if(count($revision) > 0)
            {
                if($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1)
                {
                    $edicion = 0;
                }
            }


            //==========================================


            // Datos
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::findOrFail($proyecto->recsensorial_id);

            $where_definiciones = '';
            if (($recsensorial->recsensorial_tipocliente+0) == 1) //1 = pemex, 0 = cliente
            {
                $where_definiciones = 'AND reportedefiniciones.catactivo_id = '.$proyecto->catactivo_id;
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
                                                                            reportedefinicionescatalogo.agente_nombre LIKE "'.$agente_nombre.'"
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
                                                                            reportedefiniciones.agente_nombre LIKE "'.$agente_nombre.'"
                                                                            '.$where_definiciones.' 
                                                                        ORDER BY
                                                                            reportedefiniciones.agente_nombre ASC
                                                                    )
                                                                ) AS TABLA
                                                            ORDER BY
                                                                -- TABLA.catactivo_id ASC,
                                                                TABLA.concepto ASC'));

            foreach ($definiciones_catalogo as $key => $value)
            {
                if (($value->catactivo_id+0) < 0)
                {
                    $value->descripcion_fuente = $value->descripcion.'<br><span style="color: #999999; font-style: italic;">Fuente: '.$value->fuente.'</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
                else
                {
                    $value->descripcion_fuente = $value->descripcion.'<br><span style="color: #999999; font-style: italic;">Fuente: '.$value->fuente.'</span>';
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                    // $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle"><i class="fa fa-trash fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-eye fa-2x"></i></button>';
                    }
                }
            }


            // respuesta
            $dato['data'] = $definiciones_catalogo;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $definicion_id
     * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonaldefinicioneliminar($definicion_id)
    {
        try
        {
            $definicion = reportedefinicionesModel::where('id', $definicion_id)->delete();

            // respuesta
            $dato["msj"] = 'Definición eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteserviciopersonalmapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reporteserviciopersonalModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0)
        {
            return Storage::response($reporte->reporteserviciopersonal_ubicacionfoto);
        }
        else
        {
            return Storage::download($reporte->reporteserviciopersonal_ubicacionfoto, 'Ubicacion instalacion.jpg');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalevaluacionpyd($proyecto_id)
    {
        try
        {
            $evaluacion_pyd = reporteserviciopersonalevaluacionpydModel::where('proyecto_id', $proyecto_id)->get();


            if (count($evaluacion_pyd) > 0)
            {
                $dato['evaluacion_pyd'] = $evaluacion_pyd[0];


                $tipologia = '';

                if ($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_tipologia == 1)
                {
                    $tipologia = 'Industrias, almacenes y bodegas donde se manipule materiales y sustancias que ocasionen manifiesto desaseo';
                }
                else if ($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_tipologia == 2)
                {
                    $tipologia = 'Oficinas';
                }
                else
                {
                    $tipologia = 'Otras industrias, almacenes';
                }

                
                // ----------------------------------------------


                $proporcionalidad = $this->proporcionalidad(($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_tipologia + 0), ($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_personas + 0));

                // dd($proporcionalidad);

                $E_resultado = 'Si cumple'; $E_color = 'background: #00FF00; color: #000000;';
                $L_resultado = 'Si cumple'; $L_color = 'background: #00FF00; color: #000000;';
                $M_resultado = 'Si cumple'; $M_color = 'background: #00FF00; color: #000000;';
                $R_resultado = 'Si cumple'; $R_color = 'background: #00FF00; color: #000000;';

                if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_escusados+0) < ($proporcionalidad['E']+0))
                {
                    $E_resultado = 'No cumple';
                    $E_color = 'background: #FF0000; color: #FFFFFF;';
                }

                if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_lavabos+0) < ($proporcionalidad['L']+0))
                {
                    $L_resultado = 'No cumple';
                    $L_color = 'background: #FF0000; color: #FFFFFF;';
                }

                if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_mingitorios+0) < ($proporcionalidad['M']+0))
                {
                    $M_resultado = 'No cumple';
                    $M_color = 'background: #FF0000; color: #FFFFFF;';
                }

                if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_Regaderas+0) < ($proporcionalidad['R']+0))
                {
                    $R_resultado = 'No cumple';
                    $R_color = 'background: #FF0000; color: #FFFFFF;';
                }

                $dato['tablas_pyd'] = '<table class="table-hover tabla_info_centrado" width="100%">
                                        <thead>
                                            <tr>
                                                <th colspan="7">Tabla comparativa de la proporcionalidad</th>
                                            </tr>
                                            <tr>
                                                <th colspan="7">Proporcionalidad de acuerdo a magnitud de personas</th>
                                            </tr>
                                            <tr>
                                                <th width="100">Área / Instalación</th>
                                                <th width="100">Número de personas</th>
                                                <th width="">Tipología</th>
                                                <th width="100">Servicio sanitario</th>
                                                <th width="100">Número con el que cuentan</th>
                                                <th width="100">Número solicitado por reglamento</th>
                                                <th width="100">Cumplimiento</th>
                                            </tr>
                                            <tr>
                                                <td rowspan="4">'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_areainstalacion.'</td>
                                                <td rowspan="4">'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_personas.'</td>
                                                <td rowspan="4">'.$tipologia.'</td>
                                                <td>Escusados</td>
                                                <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_escusados.'</td>
                                                <td>'.$proporcionalidad['E'].'</td>
                                                <td style="font-weight: bold; '.$E_color.'">'.$E_resultado.'</td>
                                            </tr>
                                            <tr>
                                                <td>Lavabos</td>
                                                <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_lavabos.'</td>
                                                <td>'.$proporcionalidad['L'].'</td>
                                                <td style="font-weight: bold; '.$L_color.'">'.$L_resultado.'</td>
                                            </tr>
                                            <tr>
                                                <td>Mingitorios</td>
                                                <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_mingitorios.'</td>
                                                <td>'.$proporcionalidad['M'].'</td>
                                                <td style="font-weight: bold; '.$M_color.'">'.$M_resultado.'</td>
                                            </tr>
                                            <tr>
                                                <td>Regaderas</td>
                                                <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_Regaderas.'</td>
                                                <td>'.$proporcionalidad['R'].'</td>
                                                <td style="font-weight: bold; '.$R_color.'">'.$R_resultado.'</td>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <br>
                                    <p style="text-align: justify;"><b style="color: #000;">Referencia: </b>Metodología del cumplimiento de proporcionalidad y dimensional de acuerdo con lo establecido en el Reglamento de Construcción para el estado de Veracruz-Llave y Reglamento de Construcción del Municipio del Centro- Estado de Tabasco.</p>';
                                    

                                    if ($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_notap)
                                    {
                                        $dato['tablas_pyd'] .= '<p style="text-align: justify;"><b style="color: #000;">Nota: </b>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_notap.'</p>';
                                    }
                                    

                // ----------------------------------------------


                if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_m2 + 0) > 0)
                {
                    $dimencionalidad = $this->dimencionalidad(($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_m2 + 0));

                    // dd($dimencionalidad);

                    $E_resultado = 'Si cumple'; $E_color = 'background: #00FF00; color: #000000;';
                    $L_resultado = 'Si cumple'; $L_color = 'background: #00FF00; color: #000000;';
                    $M_resultado = 'Si cumple'; $M_color = 'background: #00FF00; color: #000000;';
                    $R_resultado = 'Si cumple'; $R_color = 'background: #00FF00; color: #000000;';

                    if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_escusados+0) < ($dimencionalidad['E']+0))
                    {
                        $E_resultado = 'No cumple';
                        $E_color = 'background: #FF0000; color: #FFFFFF;';
                    }

                    if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_lavabos+0) < ($dimencionalidad['L']+0))
                    {
                        $L_resultado = 'No cumple';
                        $L_color = 'background: #FF0000; color: #FFFFFF;';
                    }

                    if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_mingitorios+0) < ($dimencionalidad['M']+0))
                    {
                        $M_resultado = 'No cumple';
                        $M_color = 'background: #FF0000; color: #FFFFFF;';
                    }

                    if (($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_Regaderas+0) < ($dimencionalidad['R']+0))
                    {
                        $R_resultado = 'No cumple';
                        $R_color = 'background: #FF0000; color: #FFFFFF;';
                    }
                }
                else
                {
                    $dimencionalidad = array('E' => 'N/A', 'L' => 'N/A', 'R' => 'N/A', 'M' => 'N/A');

                    $E_resultado = 'No aplica'; $E_color = 'background: none; color: #000000;';
                    $L_resultado = 'No aplica'; $L_color = 'background: none; color: #000000;';
                    $M_resultado = 'No aplica'; $M_color = 'background: none; color: #000000;';
                    $R_resultado = 'No aplica'; $R_color = 'background: none; color: #000000;';
                }


                $dato['tablas_pyd'] .= '<table class="table-hover tabla_info_centrado" width="100%">
                                            <thead>
                                                <tr>
                                                    <th colspan="7">Tabla comparativa de la dimencionalidad</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="7">Dimensionalidad conforme a los M<sup>2</sup> de la instalación</th>
                                                </tr>
                                                <tr>
                                                    <th width="100">Área / Instalación</th>
                                                    <th width="100">M<sup>2</sup> de la instalación</th>
                                                    <th width="">Tipología</th>
                                                    <th width="100">Servicio sanitario</th>
                                                    <th width="100">Número con el que cuentan</th>
                                                    <th width="100">Número solicitado por reglamento</th>
                                                    <th width="100">Cumplimiento</th>
                                                </tr>
                                                <tr>
                                                    <td rowspan="4">'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_areainstalacion.'</td>
                                                    <td rowspan="4">'.number_format($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_m2, 2).'</td>
                                                    <td rowspan="4">'.$tipologia.'</td>
                                                    <td>Escusados</td>
                                                    <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_escusados.'</td>
                                                    <td>'.$dimencionalidad['E'].'</td>
                                                    <td style="font-weight: bold; '.$E_color.'">'.$E_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Lavabos</td>
                                                    <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_lavabos.'</td>
                                                    <td>'.$dimencionalidad['L'].'</td>
                                                    <td style="font-weight: bold; '.$L_color.'">'.$L_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Mingitorios</td>
                                                    <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_mingitorios.'</td>
                                                    <td>'.$dimencionalidad['M'].'</td>
                                                    <td style="font-weight: bold; '.$M_color.'">'.$M_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Regaderas</td>
                                                    <td>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_Regaderas.'</td>
                                                    <td>'.$dimencionalidad['R'].'</td>
                                                    <td style="font-weight: bold; '.$R_color.'">'.$R_resultado.'</td>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <br>
                                        <p style="text-align: justify;"><b style="color: #000;">Referencia: </b>Metodología del cumplimiento de proporcionalidad y dimensional de acuerdo con lo establecido en el Reglamento de Construcción para el estado de Veracruz-Llave y Reglamento de Construcción del Municipio del Centro- Estado de Tabasco.</p>';
                                        

                                        if ($evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_notad)
                                        {
                                            $dato['tablas_pyd'] .= '<p style="text-align: justify;"><b style="color: #000;">Nota: </b>'.$evaluacion_pyd[0]->reporteserviciopersonalevaluacionpyd_notad.'</p>';
                                        }
            }
            else
            {
                $dato['evaluacion_pyd'] = 0;
                $dato['tablas_pyd'] = 'Sin evaluar';
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['evaluacion_pyd'] = 0;
            $dato['tablas_pyd'] = 'Error';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    public function proporcionalidad($tipologia, $numpersonas)
    {
        $magnitud = 0;

        switch ($tipologia)
        {
            case 1:
                    if($numpersonas > 100)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud =  explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        // dd(round(($numpersonas / 100), 2), round(($numpersonas / 100), 0), $magnitud);

                        $resultado = array('E' => (2*$magnitud[0]), 'L' => (2*$magnitud[0]), 'R' => (2*$magnitud[0]), 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 75)
                    {
                        $resultado = array('E' => 5, 'L' => 4, 'R' => 4, 'M' => 4);
                    }
                    else if ($numpersonas > 50)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 3, 'M' => 3);
                    }
                    else if ($numpersonas > 25)
                    {
                        $resultado = array('E' => 3, 'L' => 3, 'R' => 2, 'M' => 3);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 2, 'R' => 2, 'M' => 2);
                    }
                break;
            case 2:
                    if($numpersonas > 200)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud = explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        $resultado = array('E' => (2*$magnitud[0]), 'L' => (1*$magnitud[0]), 'R' => 0, 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 100)
                    {
                        $resultado = array('E' => 3, 'L' => 2, 'R' => 0, 'M' => 3);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 2, 'R' => 0, 'M' => 2);
                    }
                break;
            default:
                    if($numpersonas > 100)
                    {
                        if (round(($numpersonas / 100), 2) > round(($numpersonas / 100), 0))
                        {
                            $magnitud = explode('.', (round(($numpersonas / 100), 0) + 1));
                        }
                        else
                        {
                            $magnitud = explode('.', round(($numpersonas / 100), 0));
                        }

                        // dd(round(($numpersonas / 100), 2), round(($numpersonas / 100), 0), $magnitud);

                        $resultado = array('E' => (3*$magnitud[0]), 'L' => (2*$magnitud[0]), 'R' => (2*$magnitud[0]), 'M' => (2*$magnitud[0]));
                    }
                    else if ($numpersonas > 75)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 2, 'M' => 2);
                    }
                    else if ($numpersonas > 50)
                    {
                        $resultado = array('E' => 4, 'L' => 3, 'R' => 2, 'M' => 2);
                    }
                    else if ($numpersonas > 25)
                    {
                        $resultado = array('E' => 3, 'L' => 2, 'R' => 2, 'M' => 2);
                    }
                    else
                    {
                        $resultado = array('E' => 2, 'L' => 1, 'R' => 1, 'M' => 2);
                    }
                break;
        }

        return $resultado;
    }


    public function dimencionalidad($metroscuadrados)
    {
        $magnitud = 0;
        $R_E = 0;
        $R_L = 0;
        $R_R = 0;
        $R_M = 0;

        if ($metroscuadrados >= 1000)
        {
            $magnitud = explode('.', round(($metroscuadrados / 1000), 2));

                        // Mujeres + Hombres
            $R_E = ((2*$magnitud[0]) + (1*$magnitud[0]));
            $R_L = ((1*$magnitud[0]) + (1*$magnitud[0]));
            $R_R = 0;
            $R_M = (0 + (2*$magnitud[0]));
        }
        else if ($metroscuadrados >= 900)
        {
            // Mujeres + Hombres
            $R_E = (3 + 2);
            $R_L = (3 + 2);
            $R_R = 0;
            $R_M = (0 + 2);
        }
        else if ($metroscuadrados >= 800)
        {
            // Mujeres + Hombres
            $R_E = (2 + 2);
            $R_L = (2 + 2);
            $R_R = 0;
            $R_M = (0 + 2);
        }
        // else if ($metroscuadrados >= 700)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (2 + 1);
        //     $R_L = (2 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }
        else if ($metroscuadrados >= 600)
        {
            // Mujeres + Hombres
            $R_E = (2 + 1);
            $R_L = (2 + 1);
            $R_R = 0;
            $R_M = (0 + 1);
        }
        else //if ($metroscuadrados >= 500)
        {
            // Mujeres + Hombres
            $R_E = (1 + 1);
            $R_L = (1 + 1);
            $R_R = 0;
            $R_M = (0 + 1);
        }
        // else if ($metroscuadrados >= 400)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (1 + 1);
        //     $R_L = (1 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }
        // else if ($metroscuadrados >= 300)
        // {
        //     // Mujeres + Hombres
        //     $R_E = (1 + 1);
        //     $R_L = (1 + 1);
        //     $R_R = 0;
        //     $R_M = (0 + 1);
        // }


        $resultado = array('E' => $R_E, 'L' => $R_L, 'R' => $R_R, 'M' => $R_M);

        return $resultado;
    }


    /**
         * Display the specified resource.
         *
         * @param int $proyecto_id
         * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalevaluaciontabla($proyecto_id)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 16) // Servicio al personal
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();


            $edicion = 1;
            if(count($revision) > 0)
            {
                if($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1)
                {
                    $edicion = 0;
                }
            }


            //==========================================

            
            $a = reporteserviciopersonalevaluacionModel::where('proyecto_id', $proyecto_id)->where('reporteserviciopersonalevaluacioncatalogo_id', '>', 0)->orderBy('reporteserviciopersonalevaluacion_punto', 'ASC')->get(); //Normas
            $b = reporteserviciopersonalevaluacionModel::where('proyecto_id', $proyecto_id)->where('reporteserviciopersonalevaluacioncatalogo_id', '=', 0)->orderBy('reporteserviciopersonalevaluacion_punto', 'ASC')->get(); //Procedimientos
            $tabla = $a->merge($b);


            $numero_registro = 0;
            foreach ($tabla as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->tipo = 'Norma';
                if (($value->reporteserviciopersonalevaluacioncatalogo_id+0) == 0)
                {
                    $value->tipo = 'Procedimiento';
                }

                
                $value->descripcion = '<b style="color: #999;">'.$value->reporteserviciopersonalevaluacion_titulo.'</b><br>'.substr($value->reporteserviciopersonalevaluacion_descripcion, 0, 230).'...';


                if ($edicion == 1)
                {
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                }
                else
                {
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $evaluacion_id
     * @param  int  $evaluacion_opcion
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalevaluacionevidencia($evaluacion_id, $evaluacion_opcion)
    {
        $evaluacion  = reporteserviciopersonalevaluacionModel::findOrFail($evaluacion_id);

        if ($evaluacion_opcion == 1)
        {
            return Storage::response($evaluacion->reporteserviciopersonalevaluacion_evidencia1, 'Evidencia_1.jpg');
            // return Storage::download($evaluacion->reporteserviciopersonalevaluacion_evidencia1, 'Evidencia_1.jpg');
        }
        else
        {
            return Storage::response($evaluacion->reporteserviciopersonalevaluacion_evidencia2, 'Evidencia_2.jpg');
            // return Storage::download($evaluacion->reporteserviciopersonalevaluacion_evidencia2, 'Evidencia_2.jpg');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $evaluacion_id
     * @param  int  $evidencia_opcion
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalevaluacionevidenciaeliminar($evaluacion_id, $evidencia_opcion)
    {
        try
        {
            $evaluacion  = reporteserviciopersonalevaluacionModel::findOrFail($evaluacion_id);


            if ($evidencia_opcion == 1)
            {
                if (Storage::exists($evaluacion->reporteserviciopersonalevaluacion_evidencia1))
                {
                    Storage::delete($evaluacion->reporteserviciopersonalevaluacion_evidencia1); // Eliminar file
                }

                $evaluacion->update([
                    'reporteserviciopersonalevaluacion_evidencia1' => NULL
                ]);
            }
            else
            {
                if (Storage::exists($evaluacion->reporteserviciopersonalevaluacion_evidencia2))
                {
                    Storage::delete($evaluacion->reporteserviciopersonalevaluacion_evidencia2); // Eliminar file
                }

                $evaluacion->update([
                    'reporteserviciopersonalevaluacion_evidencia2' => NULL
                ]);
            }


            // respuesta
            $dato["msj"] = 'Datos eliminados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $evaluacion_id
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalevaluacioneliminar($evaluacion_id)
    {
        try
        {
            $evaluacion  = reporteserviciopersonalevaluacionModel::findOrFail($evaluacion_id);


            if (Storage::exists($evaluacion->reporteserviciopersonalevaluacion_evidencia1))
            {
                Storage::delete($evaluacion->reporteserviciopersonalevaluacion_evidencia1); // Eliminar file
            }


            if (Storage::exists($evaluacion->reporteserviciopersonalevaluacion_evidencia2))
            {
                Storage::delete($evaluacion->reporteserviciopersonalevaluacion_evidencia2); // Eliminar file
            }


            reporteserviciopersonalevaluacionModel::findOrFail($evaluacion_id)->delete();


            // respuesta
            $dato["msj"] = 'Datos eliminados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteserviciopersonalresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reporteserviciopersonalModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1)
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reporteserviciopersonal_responsable1documento);
            }
            else
            {
                return Storage::download($reporte->reporteserviciopersonal_responsable1documento, 'Representante tecnico.jpg');
            }
        }
        else
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reporteserviciopersonal_responsable2documento);
            }
            else
            {
                return Storage::download($reporte->reporteserviciopersonal_responsable2documento, 'Representante general.jpg');
            }
        }
    }


    /**
         * Display the specified resource.
         *
         * @param int $proyecto_id
         * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalcondicionesinsegurastabla($proyecto_id)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 16) // Servicio al personal
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();


            $edicion = 1;
            if(count($revision) > 0)
            {
                if($revision[0]->reporterevisiones_concluido == 1 || $revision[0]->reporterevisiones_cancelado == 1)
                {
                    $edicion = 0;
                }
            }


            //==========================================


            $tabla = DB::select('SELECT
                                    TABLA.proyecto_id,
                                    TABLA.id,
                                    TABLA.reportearea_id,
                                    TABLA.reportearea_nombre,
                                    TABLA.reporteserviciopersonalcondicioninsegura_actividad,
                                    TABLA.reporteserviciopersonalcondicioninsegura_rutinario,
                                    TABLA.reporteserviciopersonalcondicioninsegura_descripcion,
                                    TABLA.reporteserviciopersonalcondicioninsegura_clasificacion,
                                    TABLA.reporteserviciopersonalcondicioninsegura_efectos,
                                    TABLA.reporteserviciopersonalcondicioninsegura_fuente,
                                    TABLA.reporteserviciopersonalcondicioninsegura_medio,
                                    TABLA.reporteserviciopersonalcondicioninsegura_probabilidad,
                                    TABLA.reporteserviciopersonalcondicioninsegura_exposicion,
                                    TABLA.reporteserviciopersonalcondicioninsegura_consecuencia,
                                    ROUND(resultado, 1) AS resultado,
                                    (
                                        CASE
                                            WHEN resultado >= 400 THEN "#FF0000"
                                            WHEN resultado >= 200 THEN "#FF6600"
                                            WHEN resultado >= 70 THEN "#FFFF00"
                                            WHEN resultado >= 20 THEN "#006600"
                                            ELSE "#92D050"
                                        END
                                    ) AS resultado_color,
                                    (
                                        CASE
                                            WHEN resultado >= 400 THEN "Paralización de la actividad de forma inmediata"
                                            WHEN resultado >= 200 THEN "Corrección inmediata"
                                            WHEN resultado >= 70 THEN "Corrección necesaria urgente"
                                            WHEN resultado >= 20 THEN "No es urgente, pero debe corregirse"
                                            ELSE "Puede omitirse la corrección"
                                        END
                                    ) AS resultado_texto,
                                    TABLA.reporteserviciopersonalcondicioninsegura_foto1,
                                    TABLA.reporteserviciopersonalcondicioninsegura_foto2,
                                    categorias
                                FROM
                                    (
                                        SELECT
                                            reporteserviciopersonalcondicioninsegura.proyecto_id,
                                            reporteserviciopersonalcondicioninsegura.id,
                                            reporteserviciopersonalcondicioninsegura.reportearea_id,
                                            reportearea.reportearea_nombre,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_actividad,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_rutinario,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_descripcion,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_clasificacion,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_efectos,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_fuente,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_medio,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_probabilidad,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_exposicion,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_consecuencia,
                                            (
                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_probabilidad *
                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_exposicion *
                                                reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_consecuencia
                                            ) AS resultado,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_foto1,
                                            reporteserviciopersonalcondicioninsegura.reporteserviciopersonalcondicioninsegura_foto2,
                                            IFNULL((
                                                SELECT
                                                    -- reporteserviciopersonalcondicioninseguracategorias.reporteserviciopersonalcondicioninsegura_id,
                                                    -- reporteserviciopersonalcondicioninseguracategorias.id,
                                                    -- reporteserviciopersonalcondicioninseguracategorias.reportecategoria_id,
                                                    -- reportecategoria.reportecategoria_orden,
                                                    -- reportecategoria.reportecategoria_nombre
                                                    CONCAT("● ", REPLACE(GROUP_CONCAT(reportecategoria.reportecategoria_nombre ORDER BY reportecategoria.reportecategoria_orden ASC, reportecategoria.reportecategoria_nombre ASC),",","<br>● ")) AS categorias
                                                FROM
                                                    reporteserviciopersonalcondicioninseguracategorias
                                                    LEFT JOIN reportecategoria ON reporteserviciopersonalcondicioninseguracategorias.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reporteserviciopersonalcondicioninseguracategorias.reporteserviciopersonalcondicioninsegura_id = reporteserviciopersonalcondicioninsegura.id
                                                ORDER BY
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC
                                            ), "N/A") AS categorias
                                        FROM
                                            reporteserviciopersonalcondicioninsegura
                                            LEFT JOIN reportearea ON reporteserviciopersonalcondicioninsegura.reportearea_id = reportearea.id 
                                        WHERE
                                            reporteserviciopersonalcondicioninsegura.proyecto_id = '.$proyecto_id.' 
                                        ORDER BY
                                            reporteserviciopersonalcondicioninsegura.id ASC
                                    ) AS TABLA');

            $numero_registro = 0;
            foreach ($tabla as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if ($edicion == 1)
                {
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                }
                else
                {
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
         * Display the specified resource.
         *
         * @param int $proyecto_id
         * @param int $condicioninsegura_id
         * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalcondicionesinsegurascategorias($proyecto_id, $condicioninsegura_id)
    {
        try
        {
            // dd($proyecto_id, $condicioninsegura_id);
            $sql = DB::select('SELECT
                                    reportecategoria.proyecto_id, 
                                    reportecategoria.id, 
                                    reportecategoria.reportecategoria_nombre,
                                    (
                                        SELECT
                                            IF(reportecategoria_id > 0, "checked", "")
                                        FROM
                                            reporteserviciopersonalcondicioninseguracategorias
                                        WHERE
                                            reporteserviciopersonalcondicioninsegura_id = '.$condicioninsegura_id.' 
                                            AND reportecategoria_id = reportecategoria.id
                                        LIMIT 1
                                    ) AS checked
                                FROM
                                    reportecategoria
                                WHERE
                                    reportecategoria.proyecto_id = '.$proyecto_id.' 
                                ORDER BY
                                    reportecategoria.reportecategoria_nombre ASC');

            // respuesta
            $dato['condicioninsegura_categorias'] = $sql;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['condicioninsegura_categorias'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $condicioninsegura_id
     * @param  int  $archivo_opcion
     * @return \Illuminate\Http\Response
    */
    public function reporteserviciopersonalcondicionesinsegurasfoto($condicioninsegura_id, $archivo_opcion)
    {
        $condicioninsegura  = reporteserviciopersonalcondicioninseguraModel::findOrFail($condicioninsegura_id);

        if ($archivo_opcion == 1)
        {
            // return Storage::response($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto1);
            return Storage::download($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto1, 'Evidencia 1.jpg');
        }
        else
        {
            // return Storage::response($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2);
            return Storage::download($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2, 'Evidencia 2.jpg');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $condicioninsegura_id
     * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalcondicionesinseguraseliminar($condicioninsegura_id)
    {
        try
        {
            // Buscar registro
            $condicioninsegura = reporteserviciopersonalcondicioninseguraModel::findOrFail($condicioninsegura_id);

            // Eliminar  fotos
            if (Storage::exists($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto1))
            {
                Storage::delete($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto1);
            }

            // Eliminar  fotos
            if (Storage::exists($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2))
            {
                Storage::delete($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2);
            }

            // reporteserviciopersonalcondicioninseguraModel::findOrFail($condicioninsegura_id)->delete();
            $condicioninsegura->delete();
            
            reporteserviciopersonalcondicioninseguracategoriasModel::where('reporteserviciopersonalcondicioninsegura_id', $condicioninsegura_id)->delete();

            // respuesta
            $dato["msj"] = 'Condición insegura eliminada correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteserviciopersonalanexosresultadostabla($proyecto_id, $agente_nombre)
    {
        try
        {
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
                                                AND reporteanexos.agente_nombre LIKE "%'.$agente_nombre.'%" 
                                                AND reporteanexos.reporteanexos_tipo = 1
                                                AND reporteanexos.reporteanexos_rutaanexo = proyectoevidenciadocumento.proyectoevidenciadocumento_archivo
                                        ), "") AS checked 
                                    FROM
                                        proyectoevidenciadocumento
                                    WHERE
                                        proyectoevidenciadocumento.proyecto_id = '.$proyecto_id.' 
                                        AND proyectoevidenciadocumento.agente_nombre LIKE "%'.$agente_nombre.'%"
                                    ORDER BY
                                        proyectoevidenciadocumento.agente_nombre ASC,
                                        proyectoevidenciadocumento.proyectoevidenciadocumento_nombre ASC');

            $total_activos = 0;
            $numero_registro = 0;
            foreach ($anexos as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="anexoresultado_nombre_'.$value->id.'" value="'.$value->proyectoevidenciadocumento_nombre.'">
                                            <input type="hidden" class="form-control" name="anexoresultado_archivo_'.$value->id.'" value="'.$value->proyectoevidenciadocumento_archivo.'">
                                            <input type="checkbox" class="anexoresultado_checkbox" name="anexoresultado_checkbox[]" value="'.$value->id.'" '.$value->checked.'>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';

                if ($value->proyectoevidenciadocumento_extension == '.pdf' || $value->proyectoevidenciadocumento_extension == '.PDF')
                {
                    $value->documento = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar PDF"><i class="fa fa-file-pdf-o fa-2x"></i></button>';
                }
                else
                {
                    $value->documento = '<button type="button" class="btn btn-success waves-effect btn-circle" data-toggle="tooltip" title="Descargar archivo"><i class="fa fa-download fa-2x"></i></button>';
                }

                // VERIFICAR SI HAY DOCUMENTOS SELECCIONADOS
                if ($value->checked)
                {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $anexos;
            $dato["total"] = $total_activos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteserviciopersonalplanostabla($proyecto_id, $agente_nombre)
    {
        try
        {
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
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%'.$agente_nombre.'%" 
                                                AND reporteplanoscarpetas.reporteplanoscarpetas_nombre = proyectoevidenciaplano.proyectoevidenciaplano_carpeta
                                            LIMIT 1
                                        ), "") AS checked
                                    FROM
                                        proyectoevidenciaplano
                                    WHERE
                                        proyectoevidenciaplano.proyecto_id = '.$proyecto_id.' 
                                        AND proyectoevidenciaplano.agente_nombre LIKE "%'.$agente_nombre.'%" 
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
            foreach ($planos as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="planoscarpeta_checkbox" name="planoscarpeta_checkbox[]" value="'.$value->proyectoevidenciaplano_carpeta.'" '.$value->checked.'>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                // VERIFICAR SI HAY CARPETAS SELECCIONADAS
                if ($value->checked)
                {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $planos;
            $dato["total"] = $total_activos;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalrevisionestabla($proyecto_id)
    {
        try
        {
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
                                            reporterevisiones.proyecto_id = '.$proyecto_id.' 
                                            AND reporterevisiones.agente_id = 16
                                        ORDER BY
                                            reporterevisiones.reporterevisiones_revision DESC');


            $checked_concluido = '';
            $checked_cancelado = '';
            $disabled_concluir = '';
            $disabled_cancelar = '';
            $dato['ultimaversion_cancelada'] = 0;
            $dato['ultimaversion_estado'] = 0;
            $dato['ultimarevision_id'] = 0;


            foreach ($revisiones as $key => $value)
            {
                if ($key == 0)
                {
                    $dato['ultimaversion_cancelada'] = $value->reporterevisiones_cancelado;

                    
                    if ($value->reporterevisiones_concluido == 1 || $value->reporterevisiones_cancelado == 1)
                    {
                        $dato['ultimaversion_estado'] = 1;
                    }


                    $value->ultima_revision = $value->id;
                    $dato['ultimarevision_id'] = $value->id;
                }
                else
                {
                    $value->ultima_revision = 0;
                }


                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']) && ($key+0) == 0)
                {
                    $value->perfil_concluir = 1;
                    $disabled_concluir = '';
                }
                else
                {
                    $value->perfil_concluir = 0;
                    $disabled_concluir = 'disabled';
                }


                $checked_concluido = '';
                if (($value->reporterevisiones_concluido + 0) == 1)
                {
                    $checked_concluido = 'checked';
                }


                $value->checkbox_concluido = '<div class="switch" data-toggle="tooltip" title="Solo Coordinadores y Administradores">
                                                    <label>
                                                        <input type="checkbox" class="checkbox_concluido" '.$checked_concluido.' '.$disabled_concluir.' onclick="reporte_concluido('.$value->id.', '.$value->perfil_concluir.', this)">
                                                        <span class="lever switch-col-light-blue"></span>
                                                    </label>
                                                </div>';


                $value->nombre_concluido = $value->reporterevisiones_concluidonombre.'<br>'.$value->reporterevisiones_concluidofecha;


                if (auth()->user()->hasRoles(['Superusuario', 'Administrador']) && ($key+0) == 0)
                {
                    $value->perfil_cancelar = 1;
                    $disabled_cancelar = '';
                }
                else
                {
                    $value->perfil_cancelar = 0;
                    $disabled_cancelar = 'disabled';
                }


                $checked_cancelado = '';
                if (($value->reporterevisiones_cancelado + 0) == 1)
                {
                    $checked_cancelado = 'checked';
                }

                $value->checkbox_cancelado = '<div class="switch" data-toggle="tooltip" title="Solo Administradores">
                                                    <label>
                                                        <input type="checkbox" class="checkbox_cancelado" '.$checked_cancelado.' '.$disabled_cancelar.' onclick="reporte_cancelado('.$value->id.', '.$value->perfil_cancelar.', this)">
                                                        <span class="lever switch-col-red"></span>
                                                    </label>
                                                </div>';


                $value->nombre_cancelado = $value->reporterevisiones_canceladonombre.'<br>'.$value->reporterevisiones_canceladofecha;


                if (($value->reporterevisiones_concluido + 0) == 0 && ($value->reporterevisiones_cancelado + 0) == 0)
                {
                    $value->estado_texto = '<span class="text-info">Disponible para edición</span>';
                }
                else if (($value->reporterevisiones_cancelado + 0) == 1)
                {
                    $value->estado_texto = '<span class="text-danger">cancelado</span>: '.$value->reporterevisiones_canceladoobservacion;
                }
                else
                {
                    $value->estado_texto = '<span class="text-info">Concluido</span>: No disponible para edición';
                }


                // Boton descarga informe WORD
                if (($value->reporterevisiones_concluido + 0) == 1 || ($value->reporterevisiones_cancelado + 0) == 1)
                {
                    $value->boton_descargar = '<button type="button" class="btn btn-success waves-effect btn-circle botondescarga" id="botondescarga_'.$key.'"><i class="fa fa-download fa-2x"></i></button>';
                }
                else
                {
                    $value->boton_descargar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="Para descargar esta revisión del informe, primero debe estar concluido ó cancelado."><i class="fa fa-ban fa-2x"></i></button>';
                }
            }


            // respuesta
            $dato['data'] = $revisiones;
            $dato['total'] = count($revisiones);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['ultimaversion_cancelada'] = 0;
            $dato['ultimaversion_estado'] = 0;
            $dato['ultimarevision_id'] = 0;
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reporte_id
     * @return \Illuminate\Http\Response
     */
    public function reporteserviciopersonalrevisionconcluir($reporte_id)
    {
        try
        {
            // $reporte  = reporteaireModel::findOrFail($reporte_id);
            $revision  = reporterevisionesModel::findOrFail($reporte_id);


            $concluido = 0;
            $concluidonombre = NULL;
            $concluidofecha = NULL;


            if ($revision->reporterevisiones_concluido == 0)
            {
                $concluido = 1;                
                $concluidonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                $concluidofecha = date('Y-m-d H:i:s');
            }


            $revision->update([
                  'reporterevisiones_concluido' => $concluido
                , 'reporterevisiones_concluidonombre' => $concluidonombre
                , 'reporterevisiones_concluidofecha' => $concluidofecha
            ]);


            $dato["estado"] = 0;
            if ($concluido == 1 || $revision->reporterevisiones_cancelado == 1)
            {
                $dato["estado"] = 1;
            }


            $dato["msj"] = 'Datos modificados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["estado"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
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
        try
        {
            // TABLAS
            //============================================================
            
            $proyectoRecursos = recursosPortadasInformesModel::where('PROYECTO_ID', $request->proyecto_id)->where('AGENTE_ID', $request->agente_id)->get();

            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($request->proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            
            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);


            if (($request->reporteregistro_id + 0) > 0)
            {
                $reporte = reporteserviciopersonalModel::findOrFail($request->reporteregistro_id);

                $dato["reporteregistro_id"] = $reporte->id;

                $reporte->update([
                    'reporteserviciopersonal_instalacion' => $request->reporte_instalacion
                ]);


                //--------------------------------


                $revision = reporterevisionesModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_id', $request->agente_id)
                                                    ->orderBy('reporterevisiones_revision', 'DESC')
                                                    ->get();


                if(count($revision) > 0)
                {
                    $revision = reporterevisionesModel::findOrFail($revision[0]->id);
                }


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion+0) != 18) // Valida disponibilidad de esta version (18 CANCELACION REVISION)
                {
                    // respuesta
                    $dato["msj"] = 'Informe de '.$request->agente_nombre.' NO disponible para edición';
                    return response()->json($dato);
                }
            }
            else
            {
                DB::statement('ALTER TABLE reporteserviciopersonal AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id)
                {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reporteserviciopersonalModel::create([
                      'proyecto_id' => $request->proyecto_id
                    , 'agente_id' => $request->agente_id
                    , 'agente_nombre' => $request->agente_nombre
                    , 'catactivo_id' => $request->catactivo_id
                    , 'reporteserviciopersonal_instalacion' => $request->reporte_instalacion
                    , 'reporteserviciopersonal_catregion_activo' => 1
                    , 'reporteserviciopersonal_catsubdireccion_activo' => 1
                    , 'reporteserviciopersonal_catgerencia_activo' => 1
                    , 'reporteserviciopersonal_catactivo_activo' => 1
                    , 'reporteserviciopersonal_concluido' => 0
                    , 'reporteserviciopersonal_cancelado' => 0
                ]);
            }


            //============================================================
            
            // PORTADA
            if (($request->opcion+0) == 0)
            {
                // REGION
                $catregion_activo = 0;
                if ($request->reporte_catregion_activo != NULL)
                {
                    $catregion_activo = 1;
                }

                // SUBDIRECCION
                $catsubdireccion_activo = 0;
                if ($request->reporte_catsubdireccion_activo != NULL)
                {
                    $catsubdireccion_activo = 1;
                }

                // GERENCIA
                $catgerencia_activo = 0;
                if ($request->reporte_catgerencia_activo != NULL)
                {
                    $catgerencia_activo = 1;
                }

                // ACTIVO
                $catactivo_activo = 0;
                if ($request->reporte_catactivo_activo != NULL)
                {
                    $catactivo_activo = 1;
                }

                $reporte->update([
                      'reporteserviciopersonal_catregion_activo' => $catregion_activo
                    , 'reporteserviciopersonal_catsubdireccion_activo' => $catsubdireccion_activo
                    , 'reporteserviciopersonal_catgerencia_activo' => $catgerencia_activo
                    , 'reporteserviciopersonal_catactivo_activo' => $catactivo_activo
                    , 'reporteserviciopersonal_instalacion' => $request->reporte_instalacion
                    , 'reporteserviciopersonal_fecha' => $request->reporte_fecha
                    , 'reporte_mes' => $request->reporte_mes

                    , 'reporteserviciopersonal_alcanceinforme' => $request->reporte_alcanceinforme
                ]);


                if(count($proyectoRecursos) == 0){

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
                }else{

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
            if (($request->opcion+0) == 1)
            {
                $reporte->update([
                    'reporteserviciopersonal_introduccion' => $request->reporte_introduccion,
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // DEFINICIONES
            if (($request->opcion+0) == 2)
            {
                if (!$request->catactivo_id)
                {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                if (($request->reportedefiniciones_id+0) == 0) //NUEVO
                {
                    DB::statement('ALTER TABLE reportedefiniciones AUTO_INCREMENT = 1;');

                    $definicion = reportedefinicionesModel::create([
                          'agente_id' => $request->agente_id
                        , 'agente_nombre' => $request->agente_nombre
                        , 'catactivo_id' => $request->catactivo_id
                        , 'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto
                        , 'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion
                        , 'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else //EDITAR
                {
                    $definicion = reportedefinicionesModel::findOrFail($request->reportedefiniciones_id);

                    $definicion->update([
                          'catactivo_id' => $request->catactivo_id
                        , 'reportedefiniciones_concepto' => $request->reportedefiniciones_concepto
                        , 'reportedefiniciones_descripcion' => $request->reportedefiniciones_descripcion
                        , 'reportedefiniciones_fuente' => $request->reportedefiniciones_fuente
                    ]);

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // OBJETIVO GENERAL
            if (($request->opcion+0) == 3)
            {
                $reporte->update([
                    'reporteserviciopersonal_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion+0) == 4)
            {
                $reporte->update([
                    'reporteserviciopersonal_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4
            if (($request->opcion+0) == 5)
            {
                $reporte->update([
                    'reporteserviciopersonal_metodologia_4' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion+0) == 6)
            {
                $reporte->update([
                    'reporteserviciopersonal_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
                ]);

                // si envia archivo
                if ($request->file('reporteubicacionfoto'))
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->ubicacionmapa); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/ubicacionfoto/ubicacionfoto.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reporteserviciopersonal_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion+0) == 7)
            {
                $reporte->update([
                    'reporteserviciopersonal_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // EVALUACION PROPORCIONALIDAD Y DIMENCIONALIDAD
            if (($request->opcion+0) == 8)
            {
                // dd($request->all());

                if (($request->reporteserviciopersonalevaluacionpyd_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reporteserviciopersonalevaluacionpyd AUTO_INCREMENT = 1;');
                    $evaluacion_pyd = reporteserviciopersonalevaluacionpydModel::create($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $evaluacion_pyd = reporteserviciopersonalevaluacionpydModel::findOrFail($request->reporteserviciopersonalevaluacionpyd_id);
                    $evaluacion_pyd->update($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // EVALUACION DE LAS INSTALACIONE Y SERVICIOS
            if (($request->opcion+0) == 9)
            {
                // dd($request->all());

                if (($request->reporteserviciopersonalevaluacion_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reporteserviciopersonalevaluacion AUTO_INCREMENT = 1;');
                    $evaluacion = reporteserviciopersonalevaluacionModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $evaluacion = reporteserviciopersonalevaluacionModel::findOrFail($request->reporteserviciopersonalevaluacion_id);
                    $evaluacion->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }


                if ($request->file('reportefileevidencia1'))
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_imgbase64_evidencia1); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/Evaluacion evidencia/'.$evaluacion->id.'/Evidencia1.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $evaluacion->update([
                        'reporteserviciopersonalevaluacion_evidencia1' => $destinoPath
                    ]);
                }


                if ($request->file('reportefileevidencia2'))
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_imgbase64_evidencia2); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/Evaluacion evidencia/'.$evaluacion->id.'/Evidencia2.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $evaluacion->update([
                        'reporteserviciopersonalevaluacion_evidencia2' => $destinoPath
                    ]);
                }
            }


            // PUNTO 8.3
            if (($request->opcion+0) == 10)
            {
                $reporte->update([
                    'reporteserviciopersonal_metodologia_8_3' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_punto_8_3)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PUNTO 8.4
            if (($request->opcion+0) == 11)
            {
                $reporte->update([
                    'reporteserviciopersonal_metodologia_8_4' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_punto_8_4)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CONCLUSION
            if (($request->opcion+0) == 12)
            {
                $reporte->update([
                    'reporteserviciopersonal_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion+0) == 13)
            {
                $reporte->update([
                    'reporteserviciopersonal_recomendaciones' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_recomendaciones)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion+0) == 14)
            {
                $reporte->update([
                      'reporteserviciopersonal_responsable1' => $request->reporte_responsable1
                    , 'reporteserviciopersonal_responsable1cargo' => $request->reporte_responsable1cargo
                    , 'reporteserviciopersonal_responsable2' => $request->reporte_responsable2
                    , 'reporteserviciopersonal_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial)
                {
                    $nuevo_destino = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/'.$request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/'.$nuevo_destino));

                    $reporte->update([
                          'reporteserviciopersonal_responsable1documento' => $nuevo_destino.'responsable1_doc.jpg'
                        , 'reporteserviciopersonal_responsable2documento' => $nuevo_destino.'responsable2_doc.jpg'
                    ]);
                }


                if ($request->file('reporteresponsable1documento'))
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable1_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/responsables informe/responsable1_doc.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reporteserviciopersonal_responsable1documento' => $destinoPath
                    ]);
                }


                if ($request->file('reporteresponsable2documento'))
                {
                    // Codificar imagen recibida como tipo base64
                    $imagen_recibida = explode(',', $request->reporte_responsable2_documentobase64); //Archivo foto tipo base64
                    $imagen_nueva = base64_decode($imagen_recibida[1]);

                    // Ruta destino archivo
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/responsables informe/responsable2_doc.jpg';
                    
                    // Guardar Foto
                    Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                    // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                    $reporte->update([
                        'reporteserviciopersonal_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CONDICION INSEGURA
            if (($request->opcion+0) == 15)
            {
                if (($request->condicioninsegura_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reporteserviciopersonalcondicioninsegura AUTO_INCREMENT = 1;');

                    $condicioninsegura = reporteserviciopersonalcondicioninseguraModel::create($request->all());

                    DB::statement('ALTER TABLE reporteserviciopersonalcondicioninseguracategorias AUTO_INCREMENT = 1;');

                    foreach ($request->checkbox_categoria_id as $key => $value)
                    {
                        reporteserviciopersonalcondicioninseguracategoriasModel::create([
                              'reporteserviciopersonalcondicioninsegura_id' => $condicioninsegura->id
                            , 'reportecategoria_id' => $value
                        ]);
                    }

                    if ($request->file('condicioninsegurafoto1'))
                    {
                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->condicioninsegurafoto1_base64); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // Ruta destino archivo
                        $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/condiciones inseguras/'.$condicioninsegura->id.'/Evidencia1.jpg';
                        
                        // Guardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto1' => $destinoPath
                        ]);
                    }

                    if ($request->file('condicioninsegurafoto2'))
                    {
                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->condicioninsegurafoto2_base64); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // Ruta destino archivo
                        $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/condiciones inseguras/'.$condicioninsegura->id.'/Evidencia2.jpg';
                        
                        // Guardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto2' => $destinoPath
                        ]);
                    }
                    else
                    {
                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto2' => NULL
                        ]);
                    }
                }
                else
                {
                    $condicioninsegura = reporteserviciopersonalcondicioninseguraModel::findOrFail($request->condicioninsegura_id);
                    $condicioninsegura->update($request->all());

                    reporteserviciopersonalcondicioninseguracategoriasModel::where('reporteserviciopersonalcondicioninsegura_id', $request->condicioninsegura_id)->delete();

                    DB::statement('ALTER TABLE reporteserviciopersonalcondicioninseguracategorias AUTO_INCREMENT = 1;');

                    foreach ($request->checkbox_categoria_id as $key => $value)
                    {
                        reporteserviciopersonalcondicioninseguracategoriasModel::create([
                              'reporteserviciopersonalcondicioninsegura_id' => $request->condicioninsegura_id
                            , 'reportecategoria_id' => $value
                        ]);
                    }

                    if ($request->file('condicioninsegurafoto1'))
                    {
                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->condicioninsegurafoto1_base64); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // Ruta destino archivo
                        $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/condiciones inseguras/'.$condicioninsegura->id.'/Evidencia1.jpg';
                        
                        // Guardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto1' => $destinoPath
                        ]);
                    }

                    if ($request->file('condicioninsegurafoto2'))
                    {
                        // Codificar imagen recibida como tipo base64
                        $imagen_recibida = explode(',', $request->condicioninsegurafoto2_base64); //Archivo foto tipo base64
                        $imagen_nueva = base64_decode($imagen_recibida[1]);

                        // Ruta destino archivo
                        $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/condiciones inseguras/'.$condicioninsegura->id.'/Evidencia2.jpg';
                        
                        // Guardar Foto
                        Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                        // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public

                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto2' => $destinoPath
                        ]);
                    }
                    /*else
                    {
                        // Eliminar FOTO anterior
                        if (Storage::exists($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2))
                        {
                            Storage::delete($condicioninsegura->reporteserviciopersonalcondicioninsegura_foto2);
                        }

                        $condicioninsegura->update([
                            'reporteserviciopersonalcondicioninsegura_foto2' => NULL
                        ]);
                    }*/
                }


                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion+0) == 16)
            {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_nombre', $request->agente_nombre)
                                                    ->where('reporteanexos_tipo', 1) // INFORMES DE RESULTADOS
                                                    ->delete();


                if ($request->anexoresultado_checkbox)
                {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');


                    $dato["total"] = 0;
                    foreach ($request->anexoresultado_checkbox as $key => $value)
                    {
                        $anexo = reporteanexosModel::create([
                              'proyecto_id' => $request->proyecto_id
                            , 'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'registro_id' => $reporte->id
                            , 'reporteanexos_tipo' => 1  // INFORMES DE RESULTADOS
                            , 'reporteanexos_anexonombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoresultado_nombre_'.$value])
                            , 'reporteanexos_rutaanexo' => $request['anexoresultado_archivo_'.$value]
                        ]);

                        $dato["total"] += 1;
                    }
                }
                else
                {
                    $dato["total"] = 0;
                }


                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // PLANOS
            if (($request->opcion+0) == 17)
            {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                                                                        ->where('agente_nombre', $request->agente_nombre)
                                                                        ->delete();


                if ($request->planoscarpeta_checkbox)
                {
                    DB::statement('ALTER TABLE reporteplanoscarpetas AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->planoscarpeta_checkbox as $key => $value)
                    {
                        $anexo = reporteplanoscarpetasModel::create([
                              'proyecto_id' => $request->proyecto_id
                            , 'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'registro_id' => $reporte->id
                            , 'reporteplanoscarpetas_nombre' => str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $value)
                        ]);

                        $dato["total"] += 1;
                    }
                }
                else
                {
                    $dato["total"] = 0;
                }

                // Mensaje
                $dato["msj"] = 'Información guardada correctamente';
            }


            // REVISION INFORME, CANCELACION
            if (($request->opcion+0) == 18)
            {
                $revision = reporterevisionesModel::findOrFail($request->reporterevisiones_id);


                $cancelado = 0;
                $canceladonombre = NULL;
                $canceladofecha = NULL;
                $canceladoobservacion = NULL;


                if ($revision->reporterevisiones_cancelado == 0)
                {
                    $cancelado = 1;                
                    $canceladonombre = auth()->user()->empleado->empleado_nombre." ".auth()->user()->empleado->empleado_apellidopaterno." ".auth()->user()->empleado->empleado_apellidomaterno;
                    $canceladofecha = date('Y-m-d H:i:s');
                    $canceladoobservacion = $request->reporte_canceladoobservacion;
                }


                $revision->update([
                      'reporterevisiones_cancelado' => $cancelado
                    , 'reporterevisiones_canceladonombre' => $canceladonombre
                    , 'reporterevisiones_canceladofecha' => $canceladofecha
                    , 'reporterevisiones_canceladoobservacion' => $canceladoobservacion
                ]);


                $dato["estado"] = 0;
                if ($revision->reporterevisiones_concluido == 1 || $cancelado == 1)
                {
                    $dato["estado"] = 1;
                }


                $dato["msj"] = 'Datos modificados correctamente';
            }


            // respuesta
            $dato["reporteregistro_id"] = $reporte->id;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // respuesta
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
