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
use App\modelos\reportes\reportehielocatalogoModel;
use App\modelos\reportes\reportehieloModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportehielocategoriaModel;
use App\modelos\reportes\reportehieloareaModel;
//----------------------------------------------------------
use App\modelos\recsensorial\catparametrohielocaracteristicaModel;
use App\modelos\reportes\reportehieloevaluacionModel;
use App\modelos\reportes\reportehieloevaluacionparametrosModel;
use App\modelos\reportes\reportehieloevaluacioncategoriasModel;
use App\modelos\reportes\reportehielomaterialModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;

use App\modelos\reportes\recursosPortadasInformesModel;

//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reportehieloController extends Controller
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
    public function reportehielovista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente+0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL))
        {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de hielo primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        }
        else
        {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 10) // Hielo
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();


            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================

            if(count($revision) == 0)
            {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                      'proyecto_id' => $proyecto_id
                    , 'agente_id' => 10
                    , 'agente_nombre' => 'Hielo'
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




            //CATEGORIAS POE
            //-------------------------------------


            $categorias = DB::select('SELECT
                                            reportehielocategoria.proyecto_id, 
                                            reportehielocategoria.registro_id, 
                                            reportehielocategoria.id, 
                                            reportehielocategoria.reportehielocategoria_nombre, 
                                            reportehielocategoria.reportehielocategoria_total
                                        FROM
                                            reportehielocategoria
                                        WHERE
                                            reportehielocategoria.proyecto_id = '.$proyecto_id.' 
                                        ORDER BY
                                            reportehielocategoria.reportehielocategoria_nombre ASC');


            if (count($categorias) > 0)
            {
                $categorias_poe = 0; // NO TIENE POE GENERAL
            }
            else
            {
                $categorias_poe = 1; // TIENE POE GENERAL
            }


            // AREAS POE
            //-------------------------------------


            $areas = DB::select('SELECT
                                    reportehieloarea.proyecto_id, 
                                    reportehieloarea.registro_id, 
                                    reportehieloarea.id, 
                                    reportehieloarea.reportehieloarea_instalacion, 
                                    reportehieloarea.reportehieloarea_nombre, 
                                    reportehieloarea.reportehieloarea_numorden, 
                                    reportehieloarea.reportehieloarea_porcientooperacion
                                FROM
                                    reportehieloarea
                                WHERE
                                    reportehieloarea.proyecto_id = '.$proyecto_id.' 
                                ORDER BY
                                    reportehieloarea.reportehieloarea_numorden ASC,
                                    reportehieloarea.reportehieloarea_nombre ASC');


            if (count($areas) > 0)
            {
                $areas_poe = 0; // NO TIENE POE GENERAL
            }
            else
            {
                $areas_poe = 1; // TIENE POE GENERAL
            }


            //-------------------------------------


            // $categorias_poe = 1; // TIENE POE GENERAL
            // $areas_poe = 1; // TIENE POE GENERAL


            /*

            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            $total_categorias = DB::select('SELECT
                                                COUNT(reportehielocategoria.id) AS TOTAL
                                            FROM
                                                reportehielocategoria
                                            WHERE
                                                reportehielocategoria.proyecto_id = '.$proyecto_id);

            if (($total_categorias[0]->TOTAL + 0) == 0)
            {
                $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                                                                        ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                                                                        ->get();


                DB::statement('ALTER TABLE reportehielocategoria AUTO_INCREMENT = 1;');

                
                foreach ($recsensorial_categorias as $key => $value)
                {
                    $categoria = reportehielocategoriaModel::create([
                          'proyecto_id' => $proyecto_id
                        , 'recsensorialcategoria_id' => $value->id
                        , 'reportehielocategoria_nombre' => $value->recsensorialcategoria_nombrecategoria
                    ]);
                }
            }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            $total_areas = DB::select('SELECT
                                            COUNT(reportehieloarea.id) AS TOTAL
                                        FROM
                                            reportehieloarea
                                        WHERE
                                            reportehieloarea.proyecto_id = '.$proyecto_id);

            if (($total_areas[0]->TOTAL + 0) == 0)
            {
                $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                                                            ->orderBy('recsensorialarea_nombre', 'ASC')
                                                            ->get();


                DB::statement('ALTER TABLE reportehieloarea AUTO_INCREMENT = 1;');

                
                foreach ($recsensorial_areas as $key => $value)
                {
                    $area = reportehieloareaModel::create([
                          'proyecto_id' => $proyecto_id
                        , 'recsensorialarea_id' => $value->id
                        , 'reportehieloarea_nombre' => $value->recsensorialarea_nombre
                        , 'reportehieloarea_instalacion' => $proyecto->proyecto_clienteinstalacion
                    ]);
                }
            }

            */


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
                                            AND proyectoproveedores.catprueba_id = 10
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            // DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            // $proveedor_id = $proveedor[0]->proveedor_id;

            $proveedor_id = 0; //BORRAR DESPUES DE SUBIR AL SERVIDOR



            //===================================================


            $recsensorial = recsensorialModel::with(['catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();

            // Vista
            return view('reportes.parametros.reportehielo', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'proveedor_id', 'categorias_poe', 'areas_poe'));
        }
    }


    public function datosproyectolimpiartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);
        
        if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
        {
            $texto = str_replace($proyecto->catsubdireccion->catsubdireccion_nombre, 'SUBDIRECCION_NOMBRE', $texto);
            $texto = str_replace($proyecto->catgerencia->catgerencia_nombre, 'GERENCIA_NOMBRE', $texto);
            $texto = str_replace($proyecto->catactivo->catactivo_nombre, 'ACTIVO_NOMBRE', $texto);
        }
        else
        {
            $texto = str_replace($recsensorial->recsensorial_empresa, 'PEMEX Exploración y Producción', $texto);
            $texto = str_replace($recsensorial->recsensorial_empresa, 'Pemex Exploración y Producción', $texto);
        }

        $texto = str_replace($proyecto->proyecto_clienteinstalacion, 'INSTALACION_NOMBRE', $texto);
        $texto = str_replace($proyecto->proyecto_clientedireccionservicio, 'INSTALACION_DIRECCION', $texto);
        $texto = str_replace($reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], 'REPORTE_FECHA_LARGA', $texto);

        return $texto;
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

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
            $texto = str_replace('Pemex Exploración y Producción', $recsensorial->recsensorial_empresa, $texto);
        }

        $texto = str_replace('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace('INSTALACION_DIRECCION', $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace('INSTALACION_CODIGOPOSTAL', 'C.P. '.$recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace('INSTALACION_COORDENADAS', $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace('REPORTE_FECHA_LARGA', $reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], $texto);

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
    public function reportehielodatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try
        {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
                
            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reportehielocatalogoModel::limit(1)->get();
            $reporte  = reportehieloModel::where('proyecto_id', $proyecto_id)
                                        ->orderBy('reportehielo_revision', 'DESC')
                                        ->limit(1)
                                        ->get();
                                        

            if (count($reporte) > 0)
            {
                $reporte = $reporte[0];
                $dato['reporteregistro_id'] = $reporte->id;
            }
            else
            {
                if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = cliente
                {
                    $reporte = reportehieloModel::where('catactivo_id', $proyecto->catactivo_id)
                                                ->orderBy('proyecto_id', 'DESC')
                                                ->orderBy('reportehielo_revision', 'DESC')
                                                ->limit(1)
                                                ->get();
                }
                else
                {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reportehielo.id,
                                                reportehielo.proyecto_id,
                                                reportehielo.agente_id,
                                                reportehielo.agente_nombre,
                                                reportehielo.catactivo_id,
                                                reportehielo.reportehielo_revision,
                                                reportehielo.reportehielo_fecha,
                                                reportehielo.reporte_mes,

                                                reportehielo.reportehielo_instalacion,
                                                reportehielo.reportehielo_catregion_activo,
                                                reportehielo.reportehielo_catsubdireccion_activo,
                                                reportehielo.reportehielo_catgerencia_activo,
                                                reportehielo.reportehielo_catactivo_activo,
                                                reportehielo.reportehielo_introduccion,
                                                reportehielo.reportehielo_introduccion2,
                                                reportehielo.reportehielo_objetivogeneral,
                                                reportehielo.reportehielo_objetivoespecifico,
                                                reportehielo.reportehielo_objetivoespecifico2,
                                                reportehielo.reportehielo_metodologia_4_1,
                                                reportehielo.reportehielo_metodologia_4_12,
                                                reportehielo.reportehielo_metodologia_4_2,
                                                reportehielo.reportehielo_metodologia_4_22,
                                                reportehielo.reportehielo_metodologia_4_3,
                                                reportehielo.reportehielo_metodologia_4_32,
                                                reportehielo.reportehielo_ubicacioninstalacion,
                                                reportehielo.reportehielo_ubicacionfoto,
                                                reportehielo.reportehielo_procesoinstalacion,
                                                reportehielo.reportehielo_procesoelaboracion,
                                                reportehielo.reportehielo_conclusion,
                                                reportehielo.reportehielo_conclusion2,
                                                reportehielo.reportehielo_responsable1,
                                                reportehielo.reportehielo_responsable1cargo,
                                                reportehielo.reportehielo_responsable1documento,
                                                reportehielo.reportehielo_responsable2,
                                                reportehielo.reportehielo_responsable2cargo,
                                                reportehielo.reportehielo_responsable2documento,
                                                reportehielo.reportehielo_concluido,
                                                reportehielo.reportehielo_concluidonombre,
                                                reportehielo.reportehielo_concluidofecha,
                                                reportehielo.reportehielo_cancelado,
                                                reportehielo.reportehielo_canceladonombre,
                                                reportehielo.reportehielo_canceladofecha,
                                                reportehielo.reportehielo_canceladoobservacion,
                                                reportehielo.created_at,
                                                reportehielo.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reportehielo ON proyecto.id = reportehielo.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = '.$recsensorial->cliente_id.' 
                                                AND reportehielo.reportehielo_instalacion <> "" 
                                            ORDER BY
                                                reportehielo.updated_at DESC');
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
                                                ->where('agente_id', 10) //Hielo
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


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_fecha != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $reportefecha = $reporte->reportehielo_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                                                  'reporte_catregion_activo' => $reporte->reportehielo_catregion_activo
                                                , 'catregion_id' => $proyecto->catregion_id
                                                , 'reporte_catsubdireccion_activo' => $reporte->reportehielo_catsubdireccion_activo
                                                , 'catsubdireccion_id' => $proyecto->catsubdireccion_id
                                                , 'reporte_catgerencia_activo' => $reporte->reportehielo_catgerencia_activo
                                                , 'catgerencia_id' => $proyecto->catgerencia_id
                                                , 'reporte_catactivo_activo' => $reporte->reportehielo_catactivo_activo
                                                , 'catactivo_id' => $proyecto->catactivo_id
                                                , 'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion
                                                , 'reporte_fecha' => $reportefecha
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
                                                , 'reporte_mes' => ""
                                                

                                            );
            }


            // INTRODUCCION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_introduccion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_introduccion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reportehielo_introduccion;
                $introduccion2 = $reporte->reportehielo_introduccion2;
            }
            else
            {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo[0]->reportehielocatalogo_introduccion;
                $introduccion2 = $reportecatalogo[0]->reportehielocatalogo_introduccion2;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);
            $dato['reporte_introduccion2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion2);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_objetivogeneral != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reportehielo_objetivogeneral;
            }
            else
            {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo[0]->reportehielocatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_objetivoespecifico != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reportehielo_objetivoespecifico;
                $objetivoespecifico2 = $reporte->reportehielo_objetivoespecifico2;
            }
            else
            {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo[0]->reportehielocatalogo_objetivoespecifico;
                $objetivoespecifico2 = $reportecatalogo[0]->reportehielocatalogo_objetivoespecifico2;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);
            $dato['reporte_objetivoespecifico2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico2);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_metodologia_4_1 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reportehielo_metodologia_4_1;
                $metodologia_4_12 = $reporte->reportehielo_metodologia_4_12;
            }
            else
            {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_1;
                $metodologia_4_12 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_12;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);
            $dato['reporte_metodologia_4_12'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_12);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_metodologia_4_2 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_2_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reporte->reportehielo_metodologia_4_2;
                $metodologia_4_22 = $reporte->reportehielo_metodologia_4_22;
            }
            else
            {
                $dato['reporte_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_2;
                $metodologia_4_22 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_22;
            }

            $dato['reporte_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);
            $dato['reporte_metodologia_4_22'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_22);


            // METODOLOGIA PUNTO 4.3
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_metodologia_4_3 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_3_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_3_guardado'] = 0;
                }

                $metodologia_4_3 = $reporte->reportehielo_metodologia_4_3;
                $metodologia_4_32 = $reporte->reportehielo_metodologia_4_32;
            }
            else
            {
                $dato['reporte_metodologia_4_3_guardado'] = 0;
                $metodologia_4_3 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_3;
                $metodologia_4_32 = $reportecatalogo[0]->reportehielocatalogo_metodologia_4_32;
            }

            $dato['reporte_metodologia_4_3'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_3);
            $dato['reporte_metodologia_4_32'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_32);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_ubicacioninstalacion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reportehielo_ubicacioninstalacion;
            }
            else
            {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo[0]->reportehielocatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_ubicacionfoto != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $ubicacionfoto = $reporte->reportehielo_ubicacionfoto;
            }

            $dato['reporte_ubicacioninstalacion'] = array(
                                                          'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion)
                                                        , 'ubicacionfoto' => $ubicacionfoto
                                                    );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_procesoinstalacion != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reportehielo_procesoinstalacion;
            }
            else
            {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // PROCESO ELBAORACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_procesoelaboracion != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $dato['reporte_procesoelaboracion_guardado'] = 1;
                $procesoelaboracion = $reporte->reportehielo_procesoelaboracion;
            }
            else
            {
                $dato['reporte_procesoelaboracion_guardado'] = 0;
                $procesoelaboracion = $reportecatalogo[0]->reportehielocatalogo_procesoelaboracion;
            }

            $dato['reporte_procesoelaboracion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoelaboracion);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_conclusion != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reportehielo_conclusion;
                $conclusion2 = $reporte->reportehielo_conclusion2;
            }
            else
            {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo[0]->reportehielocatalogo_conclusion;
                $conclusion2 = $reportecatalogo[0]->reportehielocatalogo_conclusion2;
            }

            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);
            $dato['reporte_conclusion2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion2);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reportehielo_responsable1 != NULL)
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
                                                              'responsable1' => $reporte->reportehielo_responsable1
                                                            , 'responsable1cargo' => $reporte->reportehielo_responsable1cargo
                                                            , 'responsable1documento' => $reporte->reportehielo_responsable1documento
                                                            , 'responsable2' => $reporte->reportehielo_responsable2
                                                            , 'responsable2cargo' => $reporte->reportehielo_responsable2cargo
                                                            , 'responsable2documento' => $reporte->reportehielo_responsable2documento
                                                            , 'proyecto_id' => $reporte->proyecto_id
                                                            , 'registro_id' => $reporte->id
                                                        );
            }
            else
            {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reportehieloModel::where('reportehielo_responsable1', '!=', '')
                                                    ->orderBy('updated_at', 'DESC')
                                                    ->limit(1)
                                                    ->get();

                if (count($reportehistorial) > 0 && $reportehistorial[0]->reportehielo_responsable1 != NULL)
                {
                    $dato['reporte_responsablesinforme'] = array(
                                                                  'responsable1' => $reportehistorial[0]->reportehielo_responsable1
                                                                , 'responsable1cargo' => $reportehistorial[0]->reportehielo_responsable1cargo
                                                                , 'responsable1documento' => $reportehistorial[0]->reportehielo_responsable1documento
                                                                , 'responsable2' => $reportehistorial[0]->reportehielo_responsable2
                                                                , 'responsable2cargo' => $reportehistorial[0]->reportehielo_responsable2cargo
                                                                , 'responsable2documento' => $reportehistorial[0]->reportehielo_responsable2documento
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


            // MEMORIA FOTOGRAFICA
            //===================================================


            $memoriafotografica = DB::select('SELECT
                                                    proyectoevidenciafoto.proyecto_id,
                                                    proyectoevidenciafoto.agente_id,
                                                    proyectoevidenciafoto.agente_nombre,
                                                    COUNT(proyectoevidenciafoto.proyectoevidenciafoto_nopunto) AS total 
                                                FROM
                                                    proyectoevidenciafoto
                                                WHERE
                                                    proyectoevidenciafoto.proyecto_id = '.$proyecto_id.' 
                                                    AND proyectoevidenciafoto.agente_nombre LIKE "%'.$agente_nombre.'%" 
                                                GROUP BY
                                                    proyectoevidenciafoto.proyecto_id,
                                                    proyectoevidenciafoto.agente_id,
                                                    proyectoevidenciafoto.agente_nombre');


            $dato['reporte_memoriafotografica_lista'] = '';
            if (count($memoriafotografica) > 0)
            {
                $dato['reporte_memoriafotografica_guardado'] = 1;

                foreach ($memoriafotografica as $key => $value)
                {
                    $dato['reporte_memoriafotografica_lista'] .= '● '.$value->total.' fotos de '.$value->agente_nombre.'<br>';
                }
            }
            else
            {                
                $dato['reporte_memoriafotografica_guardado'] = 0;
                $dato['reporte_memoriafotografica_lista'] = '● 0 fotos de Hielo (Fisicoquímico)<br>● 0 fotos de Hielo (Microbiológico)';
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
    public function reportehielotabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 10)
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
                                                                            reportedefinicionescatalogo.agente_nombre LIKE "%'.$agente_nombre.'%"
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
                                                                            reportedefiniciones.agente_nombre LIKE "%'.$agente_nombre.'%"
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
    public function reportehielodefinicioneliminar($definicion_id)
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
    public function reportehielomapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reportehieloModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0)
        {
            return Storage::response($reporte->reportehielo_ubicacionfoto);
        }
        else
        {
            return Storage::download($reporte->reportehielo_ubicacionfoto);
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
    public function reportehielocategorias($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $total_singuardar = 0;


            if (($areas_poe+0) == 1)
            {
                $categorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)
                                                    ->orderBy('reportecategoria_orden', 'ASC')
                                                    ->get();


                foreach ($categorias as $key => $value) 
                {
                    // $numero_registro += 1;
                    // $value->numero_registro = $numero_registro;
                    $value->numero_registro = $value->reportecategoria_orden;


                    $value->reportehielocategoria_nombre = $value->reportecategoria_nombre;
                    $value->reportehielocategoria_total = $value->reportecategoria_total;


                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }
            else
            {
                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                    ->where('agente_id', 10)
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


                $categorias = reportehielocategoriaModel::where('proyecto_id', $proyecto_id)
                                                        ->where('registro_id', $reporteregistro_id)
                                                        ->orderBy('reportehielocategoria_nombre', 'ASC')
                                                        ->get();

                if (count($categorias) == 0)
                {
                    $categorias = reportehielocategoriaModel::where('proyecto_id', $proyecto_id)
                                                            ->orderBy('reportehielocategoria_nombre', 'ASC')
                                                            ->get();
                }


                $numero_registro = 0; $total_singuardar = 0;
                foreach ($categorias as $key => $value) 
                {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;
                    
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }

                    if (!$value->reportehielocategoria_total)
                    {
                        $total_singuardar += 1;
                    }
                }
            }






                


            // respuesta
            $dato['data'] = $categorias;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total_singuardar"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $categoria_id
     * @return \Illuminate\Http\Response
     */
    public function reportehielocategoriaeliminar($categoria_id)
    {
        try
        {
            $categoria = reportehielocategoriaModel::where('id', $categoria_id)->delete();

            // respuesta
            $dato["msj"] = 'Categoría eliminada correctamente';
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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportehieloareas($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $numero_registro = 0; $numero_registro2 = 0; $total_singuardar = 0; $instalacion = 'XXX'; $area = 'XXX'; $area2 = 'XXX'; $selectareasoption = '<option value=""></option>'; $tabla_6_1 = '';


            if (($areas_poe+0) == 1)
            {
                $areas = DB::select('SELECT
                                         reportearea.proyecto_id,
                                         reportearea.id,
                                         reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                         reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                         reportearea.reportearea_orden AS reportehieloarea_numorden,
                                         reportearea.reportearea_porcientooperacion,
                                         reportearea.reportehieloarea_porcientooperacion
                                     FROM
                                         reportearea
                                     WHERE
                                         reportearea.proyecto_id = '.$proyecto_id.' 
                                     ORDER BY
                                         reportearea.reportearea_orden ASC,
                                         reportearea.reportearea_nombre ASC');


                // FORMATEAR FILAS
                foreach ($areas as $key => $value) 
                {
                    if ($area != $value->reportehieloarea_nombre)
                    {
                        $area = $value->reportehieloarea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reportehieloarea_porcientooperacion > 0)
                        {
                            $numero_registro2 += 1;

                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>'.$numero_registro2.'</td>
                                                <td>'.$value->reportehieloarea_instalacion.'</td>
                                                <td>'.$value->reportehieloarea_nombre.'</td>
                                                <td>'.$value->reportehieloarea_porcientooperacion.' %</td>
                                            </tr>';
                        }
                    }
                    else
                    {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }

                    
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';


                    if ($value->reportehieloarea_porcientooperacion === NULL)
                    {
                        $total_singuardar += 1;
                    }


                    if ($value->reportehieloarea_porcientooperacion > 0)
                    {
                        if ($instalacion != $value->reportehieloarea_instalacion && ($key + 0) == 0)
                        {
                            $instalacion = $value->reportehieloarea_instalacion;
                            $selectareasoption .= '<optgroup label="'.$instalacion.'">';
                        }
                        
                        if ($instalacion != $value->reportehieloarea_instalacion && ($key + 0) > 0)
                        {
                            $instalacion = $value->reportehieloarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="'.$instalacion.'">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportehieloarea_nombre)
                        {
                            $area2 = $value->reportehieloarea_nombre;
                            $selectareasoption .= '<option value="'.$value->id.'">'.$area2.'</option>';
                        }


                        if ($key == (count($areas) - 1))
                        {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            }
            else
            {
                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                    ->where('agente_id', 10)
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


                $areas = reportehieloareaModel::where('proyecto_id', $proyecto_id)
                                            ->where('registro_id', $reporteregistro_id)
                                            ->orderBy('reportehieloarea_numorden', 'ASC')
                                            ->orderBy('reportehieloarea_nombre', 'ASC')
                                            ->get();

                if (count($areas) == 0)
                {
                    $areas = reportehieloareaModel::where('proyecto_id', $proyecto_id)
                                                ->orderBy('reportehieloarea_nombre', 'ASC')
                                                ->get();
                }


                // FORMATEAR FILAS
                foreach ($areas as $key => $value) 
                {
                    if ($area != $value->reportehieloarea_nombre)
                    {
                        $area = $value->reportehieloarea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reportehieloarea_porcientooperacion > 0)
                        {
                            $numero_registro2 += 1;

                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>'.$numero_registro2.'</td>
                                                <td>'.$value->reportehieloarea_instalacion.'</td>
                                                <td>'.$value->reportehieloarea_nombre.'</td>
                                                <td>'.$value->reportehieloarea_porcientooperacion.' %</td>
                                            </tr>';
                        }
                    }
                    else
                    {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }

                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }

                    if ($value->reportehieloarea_porcientooperacion === NULL)
                    {
                        $total_singuardar += 1;
                    }


                    if ($value->reportehieloarea_porcientooperacion > 0)
                    {
                        if ($instalacion != $value->reportehieloarea_instalacion && ($key + 0) == 0)
                        {
                            $instalacion = $value->reportehieloarea_instalacion;
                            $selectareasoption .= '<optgroup label="'.$instalacion.'">';
                        }
                        
                        if ($instalacion != $value->reportehieloarea_instalacion && ($key + 0) > 0)
                        {
                            $instalacion = $value->reportehieloarea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="'.$instalacion.'">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reportehieloarea_nombre)
                        {
                            $area2 = $value->reportehieloarea_nombre;
                            $selectareasoption .= '<option value="'.$value->id.'">'.$area2.'</option>';
                        }


                        if ($key == (count($areas) - 1))
                        {
                            $selectareasoption .= '</optgroup>';
                        }
                    }
                }
            }


            // respuesta
            $dato['data'] = $areas;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_6_1"] = '<tr><td colspan="4">Error al consultar los datos</td></tr>';
            $dato["selectareasoption"] = '<option value="">Error al consultar áreas</option>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $area_id
     * @return \Illuminate\Http\Response
     */
    public function reportehieloareaeliminar($area_id)
    {
        try
        {
            $area = reportehieloareaModel::where('id', $area_id)->delete();            

            // respuesta
            $dato["msj"] = 'Área eliminada correctamente';
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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportehieloevaluaciontabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 10)
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


            if (($areas_poe+0) == 1)
            {
                $evaluacion = DB::select('SELECT
                                                reportehieloevaluacion.id,
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.reportehieloarea_id,
                                                reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                                reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                                reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                reportehieloevaluacion.reportehieloevaluacion_fecha,
                                                reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                reportehieloevaluacion.reportehieloevaluacion_tipouso,
                                                reportehieloevaluacion.reportehieloevaluacion_descripcionuso,
                                                reportehieloevaluacion.reportehieloevaluacion_condiciones,
                                                reportehieloevaluacion.reportehieloevaluacion_totalpersonas,
                                                reportehieloevaluacion.reportehieloevaluacion_geo,
                                                reportehieloevaluacion.reportehieloevaluacion_foliomuestra,
                                                reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_unidadmedida,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible,                                            
                                                REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo, "<", "˂"), ">", "˃"), "&", "Ց") AS reportehieloevaluacionparametros_metodo,
                                                REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց") AS reportehieloevaluacionparametros_obtenida,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                                RIGHT JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion DESC,
                                                reportehieloevaluacionparametros.catparametrohielocaracteristica_id ASC');


                $numero_registro = 0; $dato['analisis_resultados'] = NULL;
                foreach ($evaluacion as $key => $value) 
                {
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }


                    // TABLA ANALISIS DE LOS RESULTADOS
                    //==========================================


                    $dato['analisis_resultados'] .= '<tr>
                                                        <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_tipo.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_caracteristica.'</td>
                                                        <td>'.$value->reportehieloevaluacion_ubicacion.' / '.$value->reportehieloevaluacion_suministro.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_obtenida.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_concentracionpermisible.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_resultado.'</td>
                                                    </tr>';
                }


                //==========================================


                $evaluacion_datos = DB::select('SELECT
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                                    reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                                    reportearea.reportearea_orden AS reportehieloarea_numorden,
                                                    reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                    reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                    reportehieloevaluacion.reportehieloevaluacion_tipouso,
                                                    reportehieloevaluacion.reportehieloevaluacion_descripcionuso,
                                                    reportehieloevaluacion.reportehieloevaluacion_condiciones,
                                                    reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                                FROM
                                                    reportehieloevaluacion
                                                    LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                                WHERE
                                                    reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                    AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                ORDER BY
                                                    reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                    reportearea.reportearea_orden ASC');


                $dato['manejo_hielo'] = NULL; $dato['dispensadores_hielo'] = NULL;
                foreach ($evaluacion_datos as $key => $value)
                {
                    $dato['manejo_hielo'] .= '<tr>
                                                <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                <td>'.$value->reportehieloarea_instalacion.'</td>
                                                <td>'.$value->reportehieloarea_nombre.'</td>
                                                <td>'.$value->reportehieloevaluacion_tipouso.'</td>
                                                <td>'.$value->reportehieloevaluacion_descripcionuso.'</td>
                                            </tr>';


                    $dato['dispensadores_hielo'] .= '<tr>
                                                        <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                        <td>'.$value->reportehieloarea_instalacion.'</td>
                                                        <td>'.$value->reportehieloarea_nombre.'</td>
                                                        <td>'.$value->reportehieloevaluacion_ubicacion.'</td>
                                                        <td>'.$value->reportehieloevaluacion_suministro.'</td>
                                                        <td>'.$value->reportehieloevaluacion_condiciones.'</td>
                                                    </tr>';
                }


                if (count($evaluacion_datos) > 0)
                {
                    $dato['dispensadores_hielo'] .= '<tr>
                                                        <td><b>'.count($evaluacion_datos).'</b></td>
                                                        <td>Total</td>
                                                        <td>columna 3</td>
                                                        <td>columna 4</td>
                                                        <td>columna 5</td>
                                                        <td>columna 6</td>
                                                    </tr>';
                }


                //==========================================


                $metodo_evaluacion = DB::select('SELECT
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                                    reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                                    reportearea.reportearea_orden AS reportehieloarea_numorden,
                                                    -- reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible 
                                                FROM
                                                    reportehieloevaluacionparametros
                                                    LEFT JOIN reportehieloevaluacion ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                                    LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                WHERE
                                                    reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                    AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                                GROUP BY
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion,
                                                    reportearea.reportearea_nombre,
                                                    reportearea.reportearea_orden,
                                                    -- reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible
                                                ORDER BY
                                                    reportearea.reportearea_orden ASC,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id ASC');


                $dato['metodo_evaluacion'] = NULL;
                foreach ($metodo_evaluacion as $key => $value)
                {
                    $dato['metodo_evaluacion'] .= '<tr>
                                                        <td>'.$value->reportehieloarea_instalacion.'</td>
                                                        <td>'.$value->reportehieloarea_nombre.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_caracteristica.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_metodo.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_concentracionpermisible.'</td>
                                                    </tr>';
                }
            }
            else
            {
                $evaluacion = DB::select('SELECT
                                                reportehieloevaluacion.id,
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.reportehieloarea_id,
                                                reportehieloarea.reportehieloarea_instalacion,
                                                reportehieloarea.reportehieloarea_nombre,
                                                reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                reportehieloevaluacion.reportehieloevaluacion_fecha,
                                                reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                reportehieloevaluacion.reportehieloevaluacion_tipouso,
                                                reportehieloevaluacion.reportehieloevaluacion_descripcionuso,
                                                reportehieloevaluacion.reportehieloevaluacion_condiciones,
                                                reportehieloevaluacion.reportehieloevaluacion_totalpersonas,
                                                reportehieloevaluacion.reportehieloevaluacion_geo,
                                                reportehieloevaluacion.reportehieloevaluacion_foliomuestra,
                                                reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_unidadmedida,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible,                                            
                                                REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo, "<", "˂"), ">", "˃"), "&", "Ց") AS reportehieloevaluacionparametros_metodo,
                                                REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց") AS reportehieloevaluacionparametros_obtenida,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                                RIGHT JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                reportehieloarea.reportehieloarea_instalacion ASC,
                                                reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion DESC,
                                                reportehieloevaluacionparametros.catparametrohielocaracteristica_id ASC');


                $numero_registro = 0; $dato['analisis_resultados'] = NULL;
                foreach ($evaluacion as $key => $value) 
                {
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }


                    // TABLA ANALISIS DE LOS RESULTADOS
                    //==========================================


                    $dato['analisis_resultados'] .= '<tr>
                                                        <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_tipo.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_caracteristica.'</td>
                                                        <td>'.$value->reportehieloevaluacion_ubicacion.' / '.$value->reportehieloevaluacion_suministro.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_obtenida.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_concentracionpermisible.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_resultado.'</td>
                                                    </tr>';
                }


                //==========================================


                $evaluacion_datos = DB::select('SELECT
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportehieloarea.reportehieloarea_instalacion,
                                                    reportehieloarea.reportehieloarea_nombre,
                                                    reportehieloarea.reportehieloarea_numorden,
                                                    reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                    reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                    reportehieloevaluacion.reportehieloevaluacion_tipouso,
                                                    reportehieloevaluacion.reportehieloevaluacion_descripcionuso,
                                                    reportehieloevaluacion.reportehieloevaluacion_condiciones,
                                                    reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                                FROM
                                                    reportehieloevaluacion
                                                    INNER JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                                WHERE
                                                    reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                    AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                ORDER BY
                                                    reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                    reportehieloarea.reportehieloarea_numorden ASC');


                $dato['manejo_hielo'] = NULL; $dato['dispensadores_hielo'] = NULL;
                foreach ($evaluacion_datos as $key => $value)
                {
                    $dato['manejo_hielo'] .= '<tr>
                                                <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                <td>'.$value->reportehieloarea_instalacion.'</td>
                                                <td>'.$value->reportehieloarea_nombre.'</td>
                                                <td>'.$value->reportehieloevaluacion_tipouso.'</td>
                                                <td>'.$value->reportehieloevaluacion_descripcionuso.'</td>
                                            </tr>';


                    $dato['dispensadores_hielo'] .= '<tr>
                                                        <td>'.$value->reportehieloevaluacion_punto.'</td>
                                                        <td>'.$value->reportehieloarea_instalacion.'</td>
                                                        <td>'.$value->reportehieloarea_nombre.'</td>
                                                        <td>'.$value->reportehieloevaluacion_ubicacion.'</td>
                                                        <td>'.$value->reportehieloevaluacion_suministro.'</td>
                                                        <td>'.$value->reportehieloevaluacion_condiciones.'</td>
                                                    </tr>';
                }


                if (count($evaluacion_datos) > 0)
                {
                    $dato['dispensadores_hielo'] .= '<tr>
                                                        <td><b>'.count($evaluacion_datos).'</b></td>
                                                        <td>Total</td>
                                                        <td>columna 3</td>
                                                        <td>columna 4</td>
                                                        <td>columna 5</td>
                                                        <td>columna 6</td>
                                                    </tr>';
                }


                //==========================================


                $metodo_evaluacion = DB::select('SELECT
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportehieloarea.reportehieloarea_instalacion,
                                                    reportehieloarea.reportehieloarea_nombre,
                                                    reportehieloarea.reportehieloarea_numorden,
                                                    -- reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible 
                                                FROM
                                                    reportehieloevaluacionparametros
                                                    LEFT JOIN reportehieloevaluacion ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                                    LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                                WHERE
                                                    reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                    AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo = "Fisicoquímico"
                                                GROUP BY
                                                    reportehieloevaluacion.proyecto_id,
                                                    reportehieloevaluacion.registro_id,
                                                    reportehieloarea.reportehieloarea_instalacion,
                                                    reportehieloarea.reportehieloarea_nombre,
                                                    reportehieloarea.reportehieloarea_numorden,
                                                    -- reportehieloevaluacion.reportehieloevaluacion_punto,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo,
                                                    catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible
                                                ORDER BY
                                                    reportehieloarea.reportehieloarea_numorden ASC,
                                                    reportehieloevaluacionparametros.catparametrohielocaracteristica_id ASC');


                $dato['metodo_evaluacion'] = NULL;
                foreach ($metodo_evaluacion as $key => $value)
                {
                    $dato['metodo_evaluacion'] .= '<tr>
                                                        <td>'.$value->reportehieloarea_instalacion.'</td>
                                                        <td>'.$value->reportehieloarea_nombre.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_caracteristica.'</td>
                                                        <td>'.$value->reportehieloevaluacionparametros_metodo.'</td>
                                                        <td>'.$value->catparametrohielocaracteristica_concentracionpermisible.'</td>
                                                    </tr>';
                }
            }


            //==========================================


            // respuesta
            $dato['data'] = $evaluacion;
            $dato["total"] = count($evaluacion);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total"] = 0;
            $dato['analisis_resultados'] = NULL;
            $dato['metodo_evaluacion'] = NULL;
            $dato['manejo_hielo'] = NULL;
            $dato['dispensadores_hielo'] = NULL;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reportehieloevaluacion_id
     * @param  $reportehieloevaluacion_tipo
     * @param  int $proveedor_id
     * @return \Illuminate\Http\Response
     */
    public function reportehieloevaluacionparametros($reportehieloevaluacion_id, $reportehieloevaluacion_tipo, $proveedor_id)
    {
        try
        {
            $where_condicion = '';
            if ($reportehieloevaluacion_tipo != "Fisicoquímico_Microbiológico")
            {
                 $where_condicion = 'AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%'.$reportehieloevaluacion_tipo.'%"';
            }


            //=======================================================


            $parametros = DB::select('SELECT
                                            catparametrohielocaracteristica.id,
                                            catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                            catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                            catparametrohielocaracteristica.catparametrohielocaracteristica_unidadmedida,
                                            catparametrohielocaracteristica.catparametrohielocaracteristica_concentracionpermisible,
                                            (
                                                SELECT
                                                    -- reportehielometodomuestreocatalogo.id,
                                                    -- reportehielometodomuestreocatalogo.catparametrohielocaracteristica_Id,
                                                    -- reportehielometodomuestreocatalogo.proveedor_id,
                                                    reportehielometodomuestreocatalogo.metodomuestreo 
                                                FROM
                                                    reportehielometodomuestreocatalogo
                                                WHERE
                                                    reportehielometodomuestreocatalogo.catparametrohielocaracteristica_Id = catparametrohielocaracteristica.id
                                                    AND reportehielometodomuestreocatalogo.proveedor_id = '.$proveedor_id.' 
                                                LIMIT 1
                                            ) AS metodo_proveedor,
                                            (
                                                SELECT
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_metodo
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = '.$reportehieloevaluacion_id.' 
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                LIMIT 1
                                            ) AS metodo,
                                            (
                                                SELECT
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = '.$reportehieloevaluacion_id.' 
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                LIMIT 1
                                            ) AS obtenida,
                                            (
                                                SELECT
                                                    reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = '.$reportehieloevaluacion_id.' 
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                LIMIT 1
                                            ) AS resultado
                                        FROM
                                            catparametrohielocaracteristica
                                        WHERE
                                            catparametrohielocaracteristica.catparametrohielocaracteristica_activo = 1
                                            -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%Fisicoquímico%" 
                                            '.$where_condicion.' 
                                        ORDER BY
                                            catparametrohielocaracteristica.id ASC');


            $dato['parametros'] = '';
            foreach ($parametros as $key => $value)
            {
                $dentro_selected = '';
                $fuera_selected = '';
                $noaplica_selected = '';
                $background = '#FFFFFF';
                $color = '#888';


                if ($value->resultado)
                {
                    if ($value->resultado == "Dentro de norma")
                    {
                        $dentro_selected = 'selected';
                        $background = '#00FF00';
                        $color = '#000000';
                    }
                    else if ($value->resultado == "Fuera de norma")
                    {
                        $fuera_selected = 'selected';
                        $background = '#FF0000';
                        $color = '#FFFFFF';
                    }
                    else
                    {
                        $noaplica_selected = 'selected';
                        $background = '#888888';
                        $color = '#FFFFFF';
                    }
                }


                $metodo = $value->metodo_proveedor;
                if ($value->metodo)
                {
                    $metodo = $value->metodo;
                }


                $dato['parametros'] .= '<tr>
                                            <td>
                                                <label>'.$value->catparametrohielocaracteristica_caracteristica.'</label>
                                                <input type="hidden" class="form-control" name="catparametrohielocaracteristica_id[]" value="'.$value->id.'">
                                                <select class="custom-select form-control select_alcances" style="background: '.$background.'; color: '.$color.';" name="reportehieloevaluacionparametros_resultado[]" onchange="select_background(this);" required>
                                                    <option value="">Seleccione resultado</option>
                                                    <option value="Dentro de norma" '.$dentro_selected.' style="background: #FFF; color: #888;">Dentro de norma</option>
                                                    <option value="Fuera de norma" '.$fuera_selected.' style="background: #FFF; color: #888;">Fuera de norma</option>
                                                    <option value="No aplicable" '.$noaplica_selected.' style="background: #FFF; color: #888;">No aplicable</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="reportehieloevaluacionparametros_metodo[]" value="'.$metodo.'" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="reportehieloevaluacionparametros_obtenida[]" value="'.$value->obtenida.'" required>
                                            </td>
                                        </tr>';
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['parametros'] = '<tr><td colspan="3">Error al consultar los parámetros</td></tr>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $reportehieloevaluacion_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportehieloevaluacioncategorias($proyecto_id, $reporteregistro_id, $reportehieloevaluacion_id, $areas_poe)
    {
        try
        {
            if (($areas_poe+0) == 1)
            {
                $categorias = DB::select('SELECT
                                                reportecategoria.id,
                                                reportecategoria.proyecto_id,
                                                reportecategoria.recsensorialcategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre AS reportehielocategoria_nombre,
                                                reportecategoria.reportecategoria_total AS reportehielocategoria_total,
                                                IFNULL((
                                                    SELECT
                                                        reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "") AS nombre,
                                                IFNULL((
                                                    SELECT
                                                        reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "") AS ficha,
                                                IFNULL((
                                                    SELECT
                                                        IF(IFNULL(reportehieloevaluacioncategorias.reportehielocategoria_id, "") = "", "", "checked")
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "") AS checked
                                            FROM
                                                reportecategoria
                                            WHERE
                                                reportecategoria.proyecto_id = "'.$proyecto_id.'" 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');
            }
            else
            {
                $categorias = DB::select('SELECT
                                                reportehielocategoria.id,
                                                reportehielocategoria.proyecto_id,
                                                reportehielocategoria.registro_id,
                                                reportehielocategoria.recsensorialcategoria_id,
                                                reportehielocategoria.reportehielocategoria_nombre,
                                                reportehielocategoria.reportehielocategoria_total,
                                                IFNULL((
                                                    SELECT
                                                        reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                                    LIMIT 1
                                                ), "") AS nombre,
                                                IFNULL((
                                                    SELECT
                                                        reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                                    LIMIT 1
                                                ), "") AS ficha,
                                                IFNULL((
                                                    SELECT
                                                        IF(IFNULL(reportehieloevaluacioncategorias.reportehielocategoria_id, "") = "", "", "checked")
                                                    FROM
                                                        reportehieloevaluacioncategorias
                                                    WHERE
                                                        reportehieloevaluacioncategorias.reportehieloevaluacion_id = "'.$reportehieloevaluacion_id.'" 
                                                        AND reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                                    LIMIT 1
                                                ), "") AS checked
                                            FROM
                                                reportehielocategoria
                                            WHERE
                                                reportehielocategoria.proyecto_id = "'.$proyecto_id.'" 
                                                AND reportehielocategoria.registro_id = "'.$reporteregistro_id.'" 
                                            ORDER BY
                                                reportehielocategoria.reportehielocategoria_nombre ASC');
            }


            $numero_registro = 0; $dato['categorias'] = '';
            foreach ($categorias as $key => $value)
            {
                $numero_registro += 1;                


                $required_readonly = 'readonly';
                if ($value->checked)
                {
                    $required_readonly = 'required';
                }


                $dato['categorias'] .= '<tr>
                                            <td>
                                                <div class="switch" style="float: left;">
                                                    <label>
                                                        <input type="checkbox" name="reportehielocategoria_id[]" value="'.$value->id.'" '.$value->checked.' onchange="activa_hieloevaluacioncategoria(this, '.$numero_registro.');">
                                                        <span class="lever switch-col-light-blue"></span>
                                                    </label>
                                                </div>
                                                <label class="demo-switch-title" style="float: left;">'.$value->reportehielocategoria_nombre.'</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control hieloevaluacioncategoria_'.$numero_registro.'" name="reportehieloevaluacioncategorias_nombre_'.$value->id.'" value="'.$value->nombre.'" '.$required_readonly.'>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control hieloevaluacioncategoria_'.$numero_registro.'" name="reportehieloevaluacioncategorias_ficha_'.$value->id.'" value="'.$value->ficha.'" '.$required_readonly.'>
                                            </td>
                                        </tr>';
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['categorias'] = '<tr><td colspan="3">Error al consultar las categorías</td></tr>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reportehieloevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reportehieloevaluacioneliminar($reportehieloevaluacion_id)
    {
        try
        {
            $puntoevaluacion = reportehieloevaluacionModel::where('id', $reportehieloevaluacion_id)->delete();

            $puntoevaluacion_parametros = reportehieloevaluacionparametrosModel::where('reportehieloevaluacion_id', $reportehieloevaluacion_id)->delete();

            $puntoevaluacion_categorias = reportehieloevaluacioncategoriasModel::where('reportehieloevaluacion_id', $reportehieloevaluacion_id)->delete();


            // respuesta
            $dato["msj"] = 'Punto de evaluación eliminado correctamente';
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
     * @param  int $reporteregistro_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportehielomatriztabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


            if (($proyecto->catregion_id+0) == 1 || ($proyecto->catregion_id+0) == 2)
            {
                if (($areas_poe+0) == 1)
                {
                    $matriz = DB::select('SELECT
                                            reportehieloevaluacion.id,
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
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
                                            reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                            reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                            reportearea.reportearea_orden AS reportehieloarea_numorden,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportecategoria.reportecategoria_nombre AS reportehielocategoria_nombre,
                                            reportehieloevaluacion.reportehieloevaluacion_totalpersonas,
                                            reportehieloevaluacion.reportehieloevaluacion_geo,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 4 -- color
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_color,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 5 -- olor
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_olor,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- turbiedad
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_turbiedad,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                LIMIT 1
                                            ), "-") AS microbiologico_totales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                LIMIT 1
                                            ), "-") AS microbiologico_fecales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                LIMIT 1
                                            ), "-") AS microbiologico_aerobios 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                            RIGHT OUTER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN reportecategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                        WHERE
                                            reportehieloevaluacion.proyecto_id =  '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND (reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Fisicoquímico_Microbiológico%" OR reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion = "%Fisicoquímico%" OR reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion = "%Microbiológico%")
                                        ORDER BY
                                            reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC');
                }
                else
                {
                    $matriz = DB::select('SELECT
                                            reportehieloevaluacion.id,
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
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
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloarea.reportehieloarea_nombre,
                                            reportehieloarea.reportehieloarea_numorden,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportehielocategoria.reportehielocategoria_nombre,
                                            reportehieloevaluacion.reportehieloevaluacion_totalpersonas,
                                            reportehieloevaluacion.reportehieloevaluacion_geo,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 6 -- color
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_color,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- olor
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_olor,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 9 -- turbiedad
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_turbiedad,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                LIMIT 1
                                            ), "-") AS microbiologico_totales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                LIMIT 1
                                            ), "-") AS microbiologico_fecales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                LIMIT 1
                                            ), "-") AS microbiologico_aerobios 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                            RIGHT OUTER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN reportehielocategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                        WHERE
                                            reportehieloevaluacion.proyecto_id =  '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND (reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Fisicoquímico_Microbiológico%" OR reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion = "%Fisicoquímico%" OR reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion = "%Microbiológico%")
                                        ORDER BY
                                            reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                            reportehielocategoria.reportehielocategoria_nombre ASC');
                }
            }
            else
            {
                if (($areas_poe+0) == 1)
                {
                    $matriz = DB::select('SELECT
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.id,
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
                                                reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                                reportehieloevaluacioncategorias.reportehielocategoria_id,
                                                reportecategoria.reportecategoria_nombre AS reportehielocategoria_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 6 -- color
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_color,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- olor
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_olor,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 9 -- turbiedad
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_turbiedad,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                    LIMIT 1
                                                ), "-") AS microbiologico_totales,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                    LIMIT 1
                                                ), "-") AS microbiologico_fecales,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                    LIMIT 1
                                                ), "-") AS microbiologico_aerobios 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                                INNER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN reportecategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                                INNER JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%xxxxxxxx%" -- Fisicoquímico, Microbiológico
                                                AND reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma"
                                                -- AND (catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%COLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%OLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            GROUP BY
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                                reportehieloevaluacioncategorias.reportehielocategoria_id,
                                                reportecategoria.reportecategoria_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                            ORDER BY
                                                reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');
                }
                else
                {
                    $matriz = DB::select('SELECT
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloevaluacion.id,
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
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                            reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportehielocategoria.reportehielocategoria_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 6 -- color
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_color,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- olor
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_olor,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 9 -- turbiedad
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_turbiedad,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                LIMIT 1
                                            ), "-") AS microbiologico_totales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                LIMIT 1
                                            ), "-") AS microbiologico_fecales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                LIMIT 1
                                            ), "-") AS microbiologico_aerobios 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                            INNER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN reportehielocategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                            INNER JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%xxxxxxxx%" -- Fisicoquímico, Microbiológico
                                            AND reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma"
                                            -- AND (catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%COLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%OLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            
                                        GROUP BY
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloevaluacion.id,
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                            reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportehielocategoria.reportehielocategoria_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                        ORDER BY
                                            reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                            reportehielocategoria.reportehielocategoria_nombre ASC');
                }

    
                //--------------------------


                if (count($matriz) == 0)
                {
                    if (($areas_poe+0) == 1)
                    {
                        $matriz = DB::select('SELECT
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.id,
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
                                                reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                                reportehieloevaluacioncategorias.reportehielocategoria_id,
                                                reportecategoria.reportecategoria_nombre AS reportehielocategoria_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 6 -- color
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_color,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- olor
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_olor,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 9 -- turbiedad
                                                    LIMIT 1
                                                ), "-") AS fisicoquimico_turbiedad,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                    LIMIT 1
                                                ), "-") AS microbiologico_totales,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                    LIMIT 1
                                                ), "-") AS microbiologico_fecales,
                                                IFNULL((
                                                    SELECT
                                                        REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                    WHERE
                                                        reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                    LIMIT 1
                                                ), "-") AS microbiologico_aerobios 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                                INNER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN reportecategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportecategoria.id
                                                INNER JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%xxxxxxxx%" -- Fisicoquímico, Microbiológico
                                                -- AND reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma"
                                                -- AND (catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%COLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%OLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            GROUP BY
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                reportehieloevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reportehieloevaluacion.reportehieloevaluacion_punto,
                                                -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                                -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                                reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                                reportehieloevaluacioncategorias.reportehielocategoria_id,
                                                reportecategoria.reportecategoria_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                                reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                            ORDER BY
                                                reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');
                    }
                    else
                    {
                        $matriz = DB::select('SELECT
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloevaluacion.id,
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
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                            reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportehielocategoria.reportehielocategoria_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 6 -- color
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_color,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 7 -- olor
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_olor,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 9 -- turbiedad
                                                LIMIT 1
                                            ), "-") AS fisicoquimico_turbiedad,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 1 -- totales
                                                LIMIT 1
                                            ), "-") AS microbiologico_totales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 2 -- fecales
                                                LIMIT 1
                                            ), "-") AS microbiologico_fecales,
                                            IFNULL((
                                                SELECT
                                                    REPLACE(REPLACE(REPLACE(reportehieloevaluacionparametros.reportehieloevaluacionparametros_obtenida, "<", "˂"), ">", "˃"), "&", "Ց")
                                                FROM
                                                    reportehieloevaluacionparametros
                                                WHERE
                                                    reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                    AND reportehieloevaluacionparametros.catparametrohielocaracteristica_id = 3 -- aerobios
                                                LIMIT 1
                                            ), "-") AS microbiologico_aerobios 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN proyecto ON reportehieloevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                            INNER JOIN reportehieloevaluacioncategorias ON reportehieloevaluacioncategorias.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN reportehielocategoria ON reportehieloevaluacioncategorias.reportehielocategoria_id = reportehielocategoria.id
                                            INNER JOIN reportehieloevaluacionparametros ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                            LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id 
                                        WHERE
                                            reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%xxxxxxxx%" -- Fisicoquímico, Microbiológico
                                            -- AND reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma"
                                            -- AND (catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%COLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%OLOR%" OR catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                        GROUP BY
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloevaluacion.id,
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloevaluacion_punto,
                                            -- reportehieloevaluacionparametros.catparametrohielocaracteristica_id,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_tipo,
                                            -- catparametrohielocaracteristica.catparametrohielocaracteristica_caracteristica,
                                            reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado,
                                            reportehieloevaluacioncategorias.reportehielocategoria_id,
                                            reportehielocategoria.reportehielocategoria_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_nombre,
                                            reportehieloevaluacioncategorias.reportehieloevaluacioncategorias_ficha 
                                        ORDER BY
                                            reportehieloevaluacion.reportehieloevaluacion_punto ASC,
                                            reportehielocategoria.reportehielocategoria_nombre ASC');
                    }
                }
            }
            // dd($matriz);


            // respuesta
            $dato["data"] = $matriz;
            $dato["total"] = count($matriz);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["data"] = 0;
            $dato["total"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  $reporte_tipo
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reportehielodashboard($proyecto_id, $reporteregistro_id, $reporte_tipo, $areas_poe)
    {
        try
        {
            // $reporte_tipo = 'Fisicoquímico';
            // $reporte_tipo = 'Microbiológico';


            //=====================================
            // TOTAL INSTALACIONES


            $where_condicion = '';

            if ($reporte_tipo != 'Fisicoquímico_Microbiológico')
            {
                $where_condicion = 'AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%'.$reporte_tipo.'%"';
            }


            //--------------------------------------


            if (($areas_poe+0) == 1)
            {
                $instalaciones = DB::select('SELECT
                                                -- reportehieloevaluacion.proyecto_id,
                                                -- reportehieloevaluacion.registro_id,
                                                -- reportehieloevaluacion.reportehieloarea_id,
                                                -- reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                                reportearea.reportearea_instalacion AS reportehieloarea_instalacion
                                                -- reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                -- AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Microbiológico%"
                                                '.$where_condicion.' 
                                            GROUP BY
                                                -- reportehieloevaluacion.proyecto_id,
                                                -- reportehieloevaluacion.registro_id,
                                                -- reportehieloevaluacion.reportehieloarea_id,
                                                -- reportearea.reportearea_nombre,
                                                reportearea.reportearea_instalacion
                                                -- reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion');
            }
            else
            {


                $instalaciones = DB::select('SELECT
                                                -- reportehieloevaluacion.proyecto_id,
                                                -- reportehieloevaluacion.registro_id,
                                                -- reportehieloevaluacion.reportehieloarea_id,
                                                -- reportehieloarea.reportehieloarea_nombre,
                                                reportehieloarea.reportehieloarea_instalacion
                                                -- reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                            FROM
                                                reportehieloevaluacion
                                                LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                -- AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Microbiológico%"
                                                '.$where_condicion.' 
                                            GROUP BY
                                                -- reportehieloevaluacion.proyecto_id,
                                                -- reportehieloevaluacion.registro_id,
                                                -- reportehieloevaluacion.reportehieloarea_id,
                                                -- reportehieloarea.reportehieloarea_nombre,
                                                reportehieloarea.reportehieloarea_instalacion
                                                -- reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion');
            }


            //=====================================
            // AREAS EVALUADAS


            $where_condicion = '';

            if ($reporte_tipo != 'Fisicoquímico_Microbiológico')
            {
                $where_condicion = 'AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%'.$reporte_tipo.'%"';
            }


            //--------------------------------------


            if (($areas_poe+0) == 1)
            {
                $areas = DB::select('SELECT
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportearea.reportearea_instalacion AS reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloarea_id,
                                            reportearea.reportearea_nombre AS reportehieloarea_nombre,
                                            reportearea.reportearea_orden AS reportehieloarea_numorden,
                                            COUNT(reportehieloevaluacion.reportehieloevaluacion_punto) AS total_puntosevaluados,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id 
                                        WHERE
                                            reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Fisicoquímico%"
                                            '.$where_condicion.' 
                                        GROUP BY
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportearea.reportearea_instalacion,
                                            reportehieloevaluacion.reportehieloarea_id,
                                            reportearea.reportearea_nombre,
                                            reportearea.reportearea_orden,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion  
                                        ORDER BY
                                            MIN(reportehieloevaluacion.reportehieloevaluacion_punto) ASC,
                                            reportearea.reportearea_orden ASC');
            }
            else
            {
                $areas = DB::select('SELECT
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloarea_id,
                                            reportehieloarea.reportehieloarea_nombre,
                                            reportehieloarea.reportehieloarea_numorden,
                                            COUNT(reportehieloevaluacion.reportehieloevaluacion_punto) AS total_puntosevaluados,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion 
                                        FROM
                                            reportehieloevaluacion
                                            LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id 
                                        WHERE
                                            reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            -- AND reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion LIKE "%Fisicoquímico%"
                                            '.$where_condicion.' 
                                        GROUP BY
                                            reportehieloevaluacion.proyecto_id,
                                            reportehieloevaluacion.registro_id,
                                            reportehieloarea.reportehieloarea_instalacion,
                                            reportehieloevaluacion.reportehieloarea_id,
                                            reportehieloarea.reportehieloarea_nombre,
                                            reportehieloarea.reportehieloarea_numorden,
                                            reportehieloevaluacion.reportehieloevaluacion_tipoevaluacion  
                                        ORDER BY
                                            MIN(reportehieloevaluacion.reportehieloevaluacion_punto) ASC,
                                            reportehieloarea.reportehieloarea_numorden ASC');
            }


            $dato["dashboard_areas"] = ''; $instalacion = 'XXXXX'; $dato["dashboard_puntos_total"] = 0;
            if (count($areas) > 0)
            {
                foreach ($areas as $key => $value)
                {
                    if ($instalacion != $value->reportehieloarea_instalacion && count($instalaciones) > 1)
                    {
                        $dato["dashboard_areas"] .= '<br><span style="font-weight: 550; color: #000;">'.$value->reportehieloarea_instalacion.'</span><hr style="margin: 4px 40px;">';
                        $instalacion = $value->reportehieloarea_instalacion;
                    }

                    
                    $dato["dashboard_areas"] .= '<span style="font-size:0.8vw!important;">● '.$value->reportehieloarea_nombre.'</span> ('.$value->total_puntosevaluados.' puntos)<br>';
                    
                    $dato["dashboard_puntos_total"] += ($value->total_puntosevaluados + 0);
                }
            }
            else
            {
                $dato["dashboard_areas"] = 'No se encontraron áreas evaluadas.';
            }


            //=====================================
            // EVALUACION, CUMPLIMIENTO NORMATIVO


            $where_condicion = '';

            if ($reporte_tipo != 'Fisicoquímico_Microbiológico')
            {
                $where_condicion = 'AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%'.$reporte_tipo.'%"';
            }


            //--------------------------------------


            if (($areas_poe+0) == 1)
            {
                $cumplimiento = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.instalacion,
                                                TABLA.area_id,
                                                TABLA.area_orden,
                                                TABLA.area_nombre,
                                                TABLA.punto,
                                                TABLA.ubicacion,
                                                TABLA.suministro,
                                                TABLA.tipo,
                                                TABLA.dentro_norma,
                                                TABLA.fuera_norma,
                                                IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) AS cumplimiento,
                                                (
                                                    CASE
                                                        WHEN IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) >= 90 THEN "#8ee66b"
                                                        WHEN IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) >= 50 THEN "#ffb22b"
                                                        ELSE "#fc4b6c"
                                                    END
                                                ) AS cumplimiento_color
                                            FROM
                                                (
                                                    SELECT
                                                        reportehieloevaluacion.proyecto_id,
                                                        reportehieloevaluacion.registro_id,
                                                        reportearea.reportearea_instalacion AS instalacion,
                                                        reportehieloevaluacion.reportehieloarea_id AS area_id,
                                                        reportearea.reportearea_orden AS area_orden,
                                                        reportearea.reportearea_nombre AS area_nombre,
                                                        reportehieloevaluacion.reportehieloevaluacion_punto AS punto,
                                                        reportehieloevaluacion.reportehieloevaluacion_ubicacion AS ubicacion,
                                                        reportehieloevaluacion.reportehieloevaluacion_suministro AS suministro,
                                                        catparametrohielocaracteristica.catparametrohielocaracteristica_tipo AS tipo, 
                                                        SUM(IF(reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Dentro de norma" OR reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "No aplicable", 1, 0)) AS dentro_norma,
                                                        SUM(IF(reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma", 1, 0)) AS fuera_norma
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                        LEFT JOIN reportehieloevaluacion ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        LEFT JOIN reportearea ON reportehieloevaluacion.reportehieloarea_id = reportearea.id 
                                                        LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                    WHERE
                                                        reportehieloevaluacion.proyecto_id = '.$proyecto_id.'  
                                                        AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%Microbiológico%" 
                                                        '.$where_condicion.' 
                                                    GROUP BY
                                                        reportehieloevaluacion.proyecto_id,
                                                        reportehieloevaluacion.registro_id,
                                                        reportearea.reportearea_instalacion,
                                                        reportehieloevaluacion.reportehieloarea_id,
                                                        reportearea.reportearea_orden,
                                                        reportearea.reportearea_nombre,
                                                        reportehieloevaluacion.reportehieloevaluacion_punto,
                                                        reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                        reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                        catparametrohielocaracteristica.catparametrohielocaracteristica_tipo
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.punto ASC,
                                                TABLA.area_orden ASC');
            }
            else
            {
                $cumplimiento = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.instalacion,
                                                TABLA.area_id,
                                                TABLA.area_orden,
                                                TABLA.area_nombre,
                                                TABLA.punto,
                                                TABLA.ubicacion,
                                                TABLA.suministro,
                                                TABLA.tipo,
                                                TABLA.dentro_norma,
                                                TABLA.fuera_norma,
                                                IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) AS cumplimiento,
                                                (
                                                    CASE
                                                        WHEN IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) >= 90 THEN "#8ee66b"
                                                        WHEN IFNULL(ROUND((ROUND((TABLA.dentro_norma / IF(TABLA.tipo = "Fisicoquímico", 15, 5)), 2) * 100), 0), 0) >= 50 THEN "#ffb22b"
                                                        ELSE "#fc4b6c"
                                                    END
                                                ) AS cumplimiento_color
                                            FROM
                                                (
                                                    SELECT
                                                        reportehieloevaluacion.proyecto_id,
                                                        reportehieloevaluacion.registro_id,
                                                        reportehieloarea.reportehieloarea_instalacion AS instalacion,
                                                        reportehieloevaluacion.reportehieloarea_id AS area_id,
                                                        reportehieloarea.reportehieloarea_numorden AS area_orden,
                                                        reportehieloarea.reportehieloarea_nombre AS area_nombre,
                                                        reportehieloevaluacion.reportehieloevaluacion_punto AS punto,
                                                        reportehieloevaluacion.reportehieloevaluacion_ubicacion AS ubicacion,
                                                        reportehieloevaluacion.reportehieloevaluacion_suministro AS suministro,
                                                        catparametrohielocaracteristica.catparametrohielocaracteristica_tipo AS tipo, 
                                                        SUM(IF(reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Dentro de norma" OR reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "No aplicable", 1, 0)) AS dentro_norma,
                                                        SUM(IF(reportehieloevaluacionparametros.reportehieloevaluacionparametros_resultado = "Fuera de norma", 1, 0)) AS fuera_norma
                                                    FROM
                                                        reportehieloevaluacionparametros
                                                        LEFT JOIN reportehieloevaluacion ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                        LEFT JOIN reportehieloarea ON reportehieloevaluacion.reportehieloarea_id = reportehieloarea.id 
                                                        LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                                    WHERE
                                                        reportehieloevaluacion.proyecto_id = '.$proyecto_id.'  
                                                        AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        -- AND catparametrohielocaracteristica.catparametrohielocaracteristica_tipo LIKE "%Microbiológico%" 
                                                        '.$where_condicion.' 
                                                    GROUP BY
                                                        reportehieloevaluacion.proyecto_id,
                                                        reportehieloevaluacion.registro_id,
                                                        reportehieloarea.reportehieloarea_instalacion,
                                                        reportehieloevaluacion.reportehieloarea_id,
                                                        reportehieloarea.reportehieloarea_numorden,
                                                        reportehieloarea.reportehieloarea_nombre,
                                                        reportehieloevaluacion.reportehieloevaluacion_punto,
                                                        reportehieloevaluacion.reportehieloevaluacion_ubicacion,
                                                        reportehieloevaluacion.reportehieloevaluacion_suministro,
                                                        catparametrohielocaracteristica.catparametrohielocaracteristica_tipo
                                                ) AS TABLA
                                            ORDER BY
                                                TABLA.tipo ASC,
                                                TABLA.punto ASC,
                                                TABLA.area_orden ASC');
            }


            $col = 'col-12';
            if ((count($cumplimiento) + count($instalaciones)) > 7)
            {
                $col = 'col-6';
            }


            $dato["dashboard_cumplimiento"] = ''; $instalacion = 'XXXXX';
            if (count($cumplimiento) > 0)
            {
                foreach ($cumplimiento as $key => $value) 
                {
                    if ($instalacion != $value->instalacion && count($instalaciones) > 1)
                    {
                        $dato["dashboard_cumplimiento"] .= '<div class="col-12" style="text-align: center;">
                                                                <span style="font-weight: 550; color: #000;">'.$value->instalacion.'</span>
                                                            </div>';

                        $instalacion = $value->instalacion;
                    }
                    

                    $tipo = '';
                    if ($reporte_tipo == 'Fisicoquímico_Microbiológico')
                    {
                        $tipo = $value->tipo;
                    }


                    $dato["dashboard_cumplimiento"] .= '<div class="'.$col.'" style="display: inline-block; text-align: left;">
                                                            <h6 class="m-t-30" style="margin: 0px; font-size:0.7vw;">Punto '.$value->punto.' '.$tipo.' ('.$value->suministro.' / '.$value->ubicacion.') <span class="pull-right">'.$value->cumplimiento.'%</span></h6>
                                                            <div class="progress" style="margin-bottom: 8px;">
                                                                <div class="progress-bar" role="progressbar" style="width: '.$value->cumplimiento.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>';
                }
            }
            else
            {
                $dato["dashboard_cumplimiento"] = 'No se encontrarón puntos de evaluación.';
            }


            //=====================================
            // PARAMETROS EVALUADOS


            $dato["dashboard_parametros"] = '';


            if ($reporte_tipo == 'Microbiológico')
            {
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Coliformes totales</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Coliformes fecales</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Mesófilos aerobios</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Fierro</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Cloro residual libre</span>';
            }
            else if ($reporte_tipo == 'Fisicoquímico')
            {
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Color</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Olor</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Sabor</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Turbiedad</span>';
            }
            else
            {
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Coliformes totales</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Coliformes fecales</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Mesófilos aerobios</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Fierro</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Cloro residual libre</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Color</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Olor</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Sabor</span><br>';
                $dato["dashboard_parametros"] .= '<span style="font-size:0.8vw!important;">● Turbiedad</span>';
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
                                                reporterecomendaciones.proyecto_id = '.$proyecto_id.'  
                                                AND reporterecomendaciones.registro_id = '.$reporteregistro_id.' 
                                                AND reporterecomendaciones.agente_nombre LIKE "%Hielo%"');


            $dato['dashboard_recomendaciones'] = 0;
            if (count($recomendaciones) > 0)
            {
                $dato['dashboard_recomendaciones'] = $recomendaciones[0]->totalrecomendaciones;
            }
            $dato['dashboard_recomendaciones'] = '<br>'.$dato['dashboard_recomendaciones'];


            //=====================================
            // TITULO DASBOARD


            $dato["dashboard_titulo"] = 'Fisicoquímico y/o Microbiológico';

            if ($reporte_tipo != 'Fisicoquímico_Microbiológico')
            {
                $dato["dashboard_titulo"] = $reporte_tipo;
            }

            
            //=====================================


            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["dashboard_titulo"] = 'Fisicoquímico y/o Microbiológico';
            $dato["dashboard_areas"] = 'Error al consultar las áreas evaluadas';
            $dato["dashboard_cumplimiento"] = 'Error al consultar los puntos de evaluación';
            $dato["dashboard_puntos_total"] = 0;
            $dato["dashboard_parametros"] = 'Error al consultar los párametros';
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

    /*
    public function reportehielodashboardgraficas(Request $request)
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
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/dashboard/evaluacion_'.$request->informe_tipo.'.jpg'; // GRAFICA


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
    public function reportehielorecomendacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try
        {
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
                                                                        reporterecomendaciones.proyecto_id = '.$proyecto_id.'
                                                                        AND reporterecomendaciones.registro_id = '.$reporteregistro_id.'
                                                                        AND reporterecomendaciones.reporterecomendacionescatalogo_id = reporterecomendacionescatalogo.id
                                                                    LIMIT 1 
                                                            ), NULL) AS recomendaciones_descripcion
                                                        FROM
                                                            reporterecomendacionescatalogo
                                                        WHERE
                                                            reporterecomendacionescatalogo.agente_nombre = "'.$agente_nombre.'"
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
                                                    reporterecomendaciones.proyecto_id = '.$proyecto_id.'
                                                    AND reporterecomendaciones.agente_nombre = "'.$agente_nombre.'"
                                                    AND reporterecomendaciones.registro_id = '.$reporteregistro_id.'
                                                    AND reporterecomendaciones.reporterecomendacionescatalogo_id = 0
                                                ORDER BY
                                                    reporterecomendaciones.id ASC
                                            )
                                        ) AS TABLA');


            $numero_registro = 0; $total = 0;
            foreach ($tabla as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if (($value->id + 0) > 0)
                {
                    $required_readonly = 'readonly';
                    if ($value->checked)
                    {
                        $required_readonly = 'required';
                    }

                    $value->checkbox = '<div class="switch">
                                            <label>
                                                <input type="checkbox" class="recomendacion_checkbox" name="recomendacion_checkbox[]" value="'.$value->id.'" '.$value->checked.' onclick="activa_recomendacion(this);">
                                                <span class="lever switch-col-light-blue"></span>
                                            </label>
                                        </div>';

                    $value->descripcion = '<input type="hidden" class="form-control" name="recomendacion_tipo_'.$value->id.'" value="'.$value->recomendaciones_tipo.'" required>
                                            <label>'.$value->recomendaciones_tipo.'</label>
                                            <textarea  class="form-control" rows="5" id="recomendacion_descripcion_'.$value->id.'" name="recomendacion_descripcion_'.$value->id.'" '.$required_readonly.'>'.$this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion).'</textarea>';
                }
                else
                {
                    $value->checkbox = '<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>
                                        <button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-2x"></i></button>';

                    $preventiva = ""; $correctiva = "";
                    if ($value->recomendaciones_tipo == "Preventiva")
                    {
                        $preventiva = "selected";
                    }
                    else
                    {
                        $correctiva = "selected";
                    }



                    if (($value->catalogo_id+0) == 1)
                    {
                        $fisicoquimico = 'selected';
                        $microbiologico = '';
                    }
                    else
                    {
                        $fisicoquimico = '';
                        $microbiologico = 'selected';
                    }


                    $value->descripcion = '<div class="row">
                                                <div class="col-6">
                                                    <label>Tipo recomendación</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>
                                                        <option value=""></option>
                                                        <option value="Preventiva" '.$preventiva.'>Preventiva</option>
                                                        <option value="Correctiva" '.$correctiva.'>Correctiva</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label>Tipo informe</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_tipoinforme[]" required>
                                                        <option value=""></option>
                                                        <option value="1" '.$fisicoquimico.'>Fisicoquímico</option>
                                                        <option value="2" '.$microbiologico.'>Microbiológico</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <br>
                                                    <label>Descripción</label>
                                                    <textarea  class="form-control" rows="5" name="recomendacionadicional_descripcion[]" required>'.$this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->recomendaciones_descripcion).'</textarea>
                                                </div>
                                            </div>';
                }

                if ($value->checked)
                {
                    $total += 1;
                }
            }


            // respuesta
            $dato['data'] = $tabla;
            $dato['total'] = $total;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato['total'] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reportehielomaterialutilizado($proyecto_id, $reporteregistro_id)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 10)
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


            $material = reportehielomaterialModel::where('proyecto_id', $proyecto_id)
                                                ->where('registro_id', $reporteregistro_id)
                                                ->orderBy('id', 'ASC')
                                                ->get();


            $numero_registro = 0;
            foreach ($material as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;
                
                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                if ($edicion == 1)
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                }
                else
                {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $material;
            $dato["total"] = count($material);
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
     * @param  int $materialutilizado_id
     * @return \Illuminate\Http\Response
     */
    public function reportehielomaterialutilizadoeliminar($materialutilizado_id)
    {
        try
        {
            $materialutilizado = reportehielomaterialModel::where('id', $materialutilizado_id)->delete();

            // respuesta
            $dato["msj"] = 'Material utilizado eliminado correctamente';
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
    public function reportehieloresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reportehieloModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1)
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reportehielo_responsable1documento);
            }
            else
            {
                return Storage::download($reporte->reportehielo_responsable1documento);
            }
        }
        else
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reportehielo_responsable2documento);
            }
            else
            {
                return Storage::download($reporte->reportehielo_responsable2documento);
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
    public function reportehieloplanostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                AND reporteplanoscarpetas.registro_id = '.$reporteregistro_id.' 
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


                $value->tipo_evaluacion = $value->agente_nombre;


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
     * @param int $proyecto_id
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
    */
    public function reportehieloanexosresultadostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                AND reporteanexos.registro_id = '.$reporteregistro_id.'
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
         * @param int $reporteregistro_id
         * @param $agente_nombre
         * @return \Illuminate\Http\Response
     */
    public function reportehieloanexosacreditacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try
        {
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
                                                        AND reporteanexos.registro_id = '.$reporteregistro_id.' 
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
                                                        proyectoproveedores.proyecto_id = '.$proyecto_id.' 
                                                        AND proyectoproveedores.catprueba_id = 9
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
            foreach ($acreditaciones as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="hidden" class="form-control" name="anexoacreditacion_nombre_'.$value->id.'" value="'.$value->acreditacion_Entidad.' '.$value->acreditacion_Numero.'">
                                            <input type="hidden" class="form-control" name="anexoacreditacion_archivo_'.$value->id.'" value="'.$value->acreditacion_SoportePDF.'">
                                            <input type="checkbox" class="anexoacreditacion_checkbox" name="anexoacreditacion_checkbox[]" value="'.$value->id.'" '.$value->checked.'>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                $value->tipo = '<span class="'.$value->vigencia_color.'">'.$value->acreditacion_Tipo.'</span>';
                $value->entidad = '<span class="'.$value->vigencia_color.'">'.$value->acreditacion_Entidad.'</span>';
                $value->numero = '<span class="'.$value->vigencia_color.'">'.$value->acreditacion_Numero.'</span>';
                $value->area = '<span class="'.$value->vigencia_color.'">'.$value->vigencia_color.'</span>';
                $value->vigencia = '<span class="'.$value->vigencia_color.'">'.$value->vigencia_texto.'</span>';
                $value->certificado = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar certificado"><i class="fa fa-file-pdf-o fa-2x"></i></button>';

                // VERIFICAR SI HAY ACREDITACIONES SELECCIONADOS
                if ($value->checked)
                {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $acreditaciones;
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
    public function reportehielorevisionestabla($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 10
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
    public function reportehielorevisionconcluir($reporte_id)
    {
        try
        {
            // $reporte  = reporteaguaModel::findOrFail($reporte_id);
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
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $reporteregistro_id
     * @param  int $revision_id
     * @param  int $ultimarevision_id
     * @return \Illuminate\Http\Response
     */
    public function reportehielorevisionparametros($proyecto_id, $reporteregistro_id, $revision_id, $ultimarevision_id)
    {
        try
        {
            if(($revision_id+0) == ($ultimarevision_id+0))
            {
                $parametros = DB::select('SELECT
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_tipo 
                                            FROM
                                                reportehieloevaluacionparametros
                                                LEFT JOIN reportehieloevaluacion ON reportehieloevaluacionparametros.reportehieloevaluacion_id = reportehieloevaluacion.id
                                                LEFT JOIN catparametrohielocaracteristica ON reportehieloevaluacionparametros.catparametrohielocaracteristica_id = catparametrohielocaracteristica.id
                                            WHERE
                                                reportehieloevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reportehieloevaluacion.registro_id = '.$reporteregistro_id.' 
                                            GROUP BY
                                                reportehieloevaluacion.proyecto_id,
                                                reportehieloevaluacion.registro_id,
                                                catparametrohielocaracteristica.catparametrohielocaracteristica_tipo');
            }
            else
            {
                $parametros = DB::select('SELECT
                                                reporterevisiones.proyecto_id,
                                                reporterevisiones.agente_id,
                                                reporterevisiones.agente_nombre,
                                                reporterevisiones.id,
                                                reporterevisiones.reporterevisiones_revision,
                                                reporterevisiones.reporterevisiones_concluido,
                                                reporterevisiones.reporterevisiones_cancelado,
                                                reporterevisionesarchivo.reporterevisionesarchivo_tipo AS catparametrohielocaracteristica_tipo,
                                                reporterevisionesarchivo.reporterevisionesarchivo_archivo 
                                            FROM
                                                reporterevisiones
                                                LEFT JOIN reporterevisionesarchivo ON reporterevisiones.id = reporterevisionesarchivo.reporterevisiones_id
                                            WHERE
                                                reporterevisiones.id = '.$revision_id.' 
                                            ORDER BY
                                                reporterevisionesarchivo.reporterevisionesarchivo_tipo ASC');
            }


            $dato['parametros'] = array();
            foreach ($parametros as $key => $value)
            {
                $dato['parametros'][] = $value->catparametrohielocaracteristica_tipo;
            }


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['parametros'] = NULL;
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

    /*
    public function reportehielorevisionnueva(Request $request)
    {
        try
        {
            // dd($request->all());


            // OBTENER ULTIMA REVISION
            // -------------------------------------------------


            $revision = DB::select('SELECT
                                        reportehielo.id,
                                        reportehielo.proyecto_id,
                                        reportehielo.reportehielo_revision 
                                    FROM
                                        reportehielo 
                                    WHERE
                                        reportehielo.proyecto_id = '.$request->proyecto_id.' 
                                    ORDER BY
                                        reportehielo.reportehielo_revision DESC
                                    LIMIT 1');


            // CLONAR REGISTRO REPORTE
            // -------------------------------------------------


            $revisionfinal  = reportehieloModel::findOrFail($revision[0]->id);

            DB::statement('ALTER TABLE reportehielo AUTO_INCREMENT = 1;');

            // $revisionnueva = $revisionfinal->replicate();
            $revisionnueva = $revisionfinal->replicate()->fill([
                  'reportehielo_revision' => ($revision[0]->reportehielo_revision + 1)
                , 'reportehielo_concluido' => 0
                , 'reportehielo_concluidonombre' => NULL
                , 'reportehielo_concluidofecha' => NULL
                , 'reportehielo_cancelado' => 0
                , 'reportehielo_canceladonombre' => NULL
                , 'reportehielo_canceladofecha' => NULL
                , 'reportehielo_canceladoobservacion' => NULL
            ]);

            $revisionnueva->save();


            // CLONAR REGISTROS TABLA ANEXOS
            // -------------------------------------------------


            $anexos_historial = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_nombre', 'LIKE', '%'.$request->agente_nombre.'%')
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
            

            // CLONAR REGISTROS TABLA MATERIAL UTILIZADO
            // -------------------------------------------------


            $material_utlizado = reportehielomaterialModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();

            DB::statement('ALTER TABLE reportehielomaterial AUTO_INCREMENT = 1;');
            foreach ($material_utlizado as $key => $value)
            {                
                $material = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $material->save();
            }


            // CLONAR REGISTROS TABLA PLANOS CARPETAS
            // -------------------------------------------------


            $planoscarpetas_historial = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                                                                    ->where('registro_id', $revision[0]->id)
                                                                    ->where('agente_nombre', 'LIKE', '%'.$request->agente_nombre.'%')
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
                                                                    ->where('registro_id', $revision[0]->id)
                                                                    ->where('agente_nombre', 'LIKE', '%'.$request->agente_nombre.'%')
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


            $categorias_historial = reportehielocategoriaModel::where('proyecto_id', $request->proyecto_id)
                                                            ->where('registro_id', $revision[0]->id)
                                                            ->get();


            $categorias_nuevosid = array();
            DB::statement('ALTER TABLE reportehielocategoria AUTO_INCREMENT = 1;');
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

            
            $areas_historial = reportehieloareaModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('registro_id', $revision[0]->id)
                                                    ->get();

            $areas_nuevosid = array();
            DB::statement('ALTER TABLE reportehieloarea AUTO_INCREMENT = 1;');
            foreach ($areas_historial as $key => $value)
            {                
                $area = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $area->save();

                $areas_nuevosid['id_'.$value->id] = $area->id;
            }
            // dd($areas_nuevosid);


            // CLONAR REGISTROS TABLA EVALUACION
            // -------------------------------------------------


            $evaluacion = reportehieloevaluacionModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('registro_id', $revision[0]->id)
                                                    ->get();


            DB::statement('ALTER TABLE reportehieloevaluacion AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE reportehieloevaluacioncategorias AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE reportehieloevaluacionparametros AUTO_INCREMENT = 1;');

            foreach ($evaluacion as $key => $value)
            {
                $punto = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                    , 'reportehieloarea_id' => $areas_nuevosid['id_'.$value->reportehieloarea_id]
                ]);

                $punto->save();

                
                // CLONAR REGISTROS TABLA EVALUACION PARAMETROS
                $evaluacion_parametros = reportehieloevaluacionparametrosModel::where('reportehieloevaluacion_id', $value->id)->get();
                foreach ($evaluacion_parametros as $key2 => $value2)
                {
                    $parametro = $value2->replicate()->fill([
                        'reportehieloevaluacion_id' => $punto->id
                    ]);

                    $parametro->save();
                }


                // CLONAR REGISTROS TABLA EVALUACION CATEGORIAS
                $evaluacion_categorias = reportehieloevaluacioncategoriasModel::where('reportehieloevaluacion_id', $value->id)->get();
                foreach ($evaluacion_categorias as $key2 => $value2)
                {
                    $categoria = $value2->replicate()->fill([
                        'reportehieloevaluacion_id' => $punto->id
                    ]);

                    $categoria->save();
                }
            }
            // dd($evaluacion);


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
                    $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/dashboard/evaluacion_'.$request->dashboard_parametros[$i].'.jpg'; // GRAFICA

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
                    'reportehielo_ubicacionfoto' => $carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                    'reportehielo_ubicacionfoto' => NULL
                ]);
            }


            if (Storage::exists($carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'))
            {
                $revisionnueva->update([
                      'reportehielo_responsable1documento' => $carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'
                    , 'reportehielo_responsable2documento' => $carpetaarchivos_destino.'/responsables informe/responsable2_doc.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                      'reportehielo_responsable1documento' => NULL
                    , 'reportehielo_responsable2documento' => NULL
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
                $reporte = reportehieloModel::findOrFail($request->reporteregistro_id);
                $dato["reporteregistro_id"] = $reporte->id;
                $reporte->update([
                      'reportehielo_instalacion' => $request->reporte_instalacion
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


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion+0) != 21) // Valida disponibilidad de esta version (21 CANCELACION REVISION)
                {
                    // respuesta
                    $dato["msj"] = 'Informe de '.$request->agente_nombre.' NO disponible para edición';
                    return response()->json($dato);
                }
            }
            else
            {
                DB::statement('ALTER TABLE reportehielo AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id)
                {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reportehieloModel::create([
                      'proyecto_id' => $request->proyecto_id
                    , 'agente_id' => $request->agente_id
                    , 'agente_nombre' => $request->agente_nombre
                    , 'catactivo_id' => $request->catactivo_id
                    , 'reportehielo_revision' => 0
                    , 'reportehielo_instalacion' => $request->reporte_instalacion
                    , 'reportehielo_catregion_activo' => 1
                    , 'reportehielo_catsubdireccion_activo' => 1
                    , 'reportehielo_catgerencia_activo' => 1
                    , 'reportehielo_catactivo_activo' => 1
                    , 'reportehielo_concluido' => 0
                    , 'reportehielo_cancelado' => 0
                ]);


                //--------------------------------------


                // ASIGNAR CATEGORIAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportehielocategoria
                                SET 
                                    registro_id = '.$reporte->id.'
                                WHERE 
                                    proyecto_id = '.$request->proyecto_id.'
                                    AND IFNULL(registro_id, "") = "";');


                // ASIGNAR AREAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reportehieloarea
                                SET 
                                    registro_id = '.$reporte->id.'
                                WHERE 
                                    proyecto_id = '.$request->proyecto_id.'
                                    AND IFNULL(registro_id, "") = "";');
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
                      'reportehielo_catregion_activo' => $catregion_activo
                    , 'reportehielo_catsubdireccion_activo' => $catsubdireccion_activo
                    , 'reportehielo_catgerencia_activo' => $catgerencia_activo
                    , 'reportehielo_catactivo_activo' => $catactivo_activo
                    , 'reportehielo_instalacion' => $request->reporte_instalacion
                    , 'reportehielo_fecha' => $request->reporte_fecha
                    , 'reporte_mes' => $request->reporte_mes

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
                    'reportehielo_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion)
                    , 'reportehielo_introduccion2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion2)
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
                    'reportehielo_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion+0) == 4)
            {
                $reporte->update([
                    'reportehielo_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                    , 'reportehielo_objetivoespecifico2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion+0) == 5)
            {
                $reporte->update([
                    'reportehielo_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                    , 'reportehielo_metodologia_4_12' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_12)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion+0) == 6)
            {
                $reporte->update([
                    'reportehielo_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_2)
                    , 'reportehielo_metodologia_4_22' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_22)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.3
            if (($request->opcion+0) == 7)
            {
                $reporte->update([
                    'reportehielo_metodologia_4_3' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_3)
                    , 'reportehielo_metodologia_4_32' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_32)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion+0) == 8)
            {
                $reporte->update([
                    'reportehielo_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
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
                        'reportehielo_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion+0) == 9)
            {
                $reporte->update([
                    'reportehielo_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO ELABORACION
            if (($request->opcion+0) == 10)
            {
                $reporte->update([
                    'reportehielo_procesoelaboracion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoelaboracion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion+0) == 11)
            {
                if (($request->reportecategoria_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reportehielocategoria AUTO_INCREMENT = 1;');

                    $request['recsensorialcategoria_id'] = 0;
                    $request['registro_id'] = $reporte->id;
                    $categoria = reportehielocategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $request['registro_id'] = $reporte->id;
                    $categoria = reportehielocategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS
            if (($request->opcion+0) == 12)
            {
                if (($request->areas_poe+0) == 1)
                {
                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
                else
                {
                    if (($request->reportearea_id+0) == 0)
                    {
                        DB::statement('ALTER TABLE reportehieloarea AUTO_INCREMENT = 1;');

                        $request['registro_id'] = $reporte->id;
                        $request['recsensorialarea_id'] = 0;
                        $area = reportehieloareaModel::create($request->all());

                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    }
                    else
                    {
                        $request['registro_id'] = $reporte->id;
                        $area = reportehieloareaModel::findOrFail($request->reportearea_id);
                        $area->update($request->all());

                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // PUNTO DE EVALUACION
            if (($request->opcion+0) == 13)
            {
                if (($request->puntomedicion_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reportehieloevaluacion AUTO_INCREMENT = 1;');


                    $request['registro_id'] = $reporte->id;                    
                    $punto = reportehieloevaluacionModel::create($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $request['registro_id'] = $reporte->id;
                    $punto = reportehieloevaluacionModel::findOrFail($request->puntomedicion_id);
                    $punto->update($request->all());


                    $eliminar_parametros = reportehieloevaluacionparametrosModel::where('reportehieloevaluacion_id', $request->puntomedicion_id)->delete();
                    $eliminar_categorias = reportehieloevaluacioncategoriasModel::where('reportehieloevaluacion_id', $request->puntomedicion_id)->delete();

                    
                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }


                if ($request->catparametrohielocaracteristica_id)
                {
                    foreach ($request->catparametrohielocaracteristica_id as $key => $value) 
                    {
                        $parametro = reportehieloevaluacionparametrosModel::create([
                              'reportehieloevaluacion_id' => $punto->id
                            , 'catparametrohielocaracteristica_id' => $value
                            , 'reportehieloevaluacionparametros_metodo' => str_replace('<', '˂', str_replace('>', '˃', str_replace('&', 'Ց', $request['reportehieloevaluacionparametros_metodo'][$key])))
                            , 'reportehieloevaluacionparametros_obtenida' => str_replace('<', '˂', str_replace('>', '˃', str_replace('&', 'Ց', $request['reportehieloevaluacionparametros_obtenida'][$key])))
                            , 'reportehieloevaluacionparametros_resultado' => $request['reportehieloevaluacionparametros_resultado'][$key]
                        ]);
                    }
                }


                if ($request->reportehielocategoria_id)
                {
                    foreach ($request->reportehielocategoria_id as $key => $value) 
                    {
                        $categoria = reportehieloevaluacioncategoriasModel::create([
                              'reportehieloevaluacion_id' => $punto->id
                            , 'reportehielocategoria_id' => $value
                            , 'reportehieloevaluacioncategorias_nombre' => $request['reportehieloevaluacioncategorias_nombre_'.$value]
                            , 'reportehieloevaluacioncategorias_ficha' => $request['reportehieloevaluacioncategorias_ficha_'.$value]
                        ]);
                    }
                }
            }


            // CONCLUSION
            if (($request->opcion+0) == 14)
            {
                $reporte->update([
                      'reportehielo_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
                    , 'reportehielo_conclusion2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion+0) == 15)
            {
                if ($request->recomendacion_checkbox)
                {
                    $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                                                                            ->where('catactivo_id', $request->catactivo_id)
                                                                            ->where('agente_nombre', $request->agente_nombre)
                                                                            ->where('registro_id', $reporte->id)
                                                                            ->delete();

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacion_checkbox as $key => $value)
                    {
                        $recomendacion = reporterecomendacionesModel::create([
                              'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'proyecto_id' => $request->proyecto_id
                            , 'registro_id' => $reporte->id
                            , 'catactivo_id' => $request->catactivo_id
                            , 'reporterecomendacionescatalogo_id' => $value
                            , 'reporterecomendaciones_tipo' => $request['recomendacion_tipo_'.$value]
                            , 'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request['recomendacion_descripcion_'.$value])
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                if ($request->recomendacionadicional_checkbox)
                {
                    if (!$request->recomendacion_checkbox)
                    {
                        $eliminar_recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                                                                                ->where('catactivo_id', $request->catactivo_id)
                                                                                ->where('agente_nombre', $request->agente_nombre)
                                                                                ->where('registro_id', $reporte->id)
                                                                                ->delete();
                    }

                    DB::statement('ALTER TABLE reporterecomendaciones AUTO_INCREMENT = 1;');

                    foreach ($request->recomendacionadicional_checkbox as $key => $value)
                    {
                        $recomendacion = reporterecomendacionesModel::create([
                              'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'proyecto_id' => $request->proyecto_id
                            , 'registro_id' => $reporte->id
                            , 'catactivo_id' => $request->catactivo_id
                            , 'reporterecomendacionescatalogo_id' => 0
                            , 'reporterecomendaciones_tipo' => $request->recomendacionadicional_tipo[$key]
                            , 'reporterecomendaciones_descripcion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->recomendacionadicional_descripcion[$key])
                            , 'catalogo_id' => $request->recomendacionadicional_tipoinforme[$key]
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
            }


            // MATERIAL UTILIZADO
            if (($request->opcion+0) == 16)
            {
                if (($request->materialutilizado_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reportehielomaterial AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $material = reportehielomaterialModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $request['registro_id'] = $reporte->id;
                    $material = reportehielomaterialModel::findOrFail($request->materialutilizado_id);
                    $material->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion+0) == 17)
            {
                $reporte->update([
                      'reportehielo_responsable1' => $request->reporte_responsable1
                    , 'reportehielo_responsable1cargo' => $request->reporte_responsable1cargo
                    , 'reportehielo_responsable2' => $request->reporte_responsable2
                    , 'reportehielo_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial)
                {
                    $nuevo_destino = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/'.$request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/'.$nuevo_destino));

                    $reporte->update([
                          'reportehielo_responsable1documento' => $nuevo_destino.'responsable1_doc.jpg'
                        , 'reportehielo_responsable2documento' => $nuevo_destino.'responsable2_doc.jpg'
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
                        'reportehielo_responsable1documento' => $destinoPath
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
                        'reportehielo_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion+0) == 18)
            {
                $eliminar_carpetasplanos = reporteplanoscarpetasModel::where('proyecto_id', $request->proyecto_id)
                                                                        ->where('agente_nombre', $request->agente_nombre)
                                                                        ->where('registro_id', $reporte->id)
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


            // INFORMES RESULTADOS
            if (($request->opcion+0) == 19)
            {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_nombre', $request->agente_nombre)
                                                    ->where('registro_id', $reporte->id)
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


            // ANEXOS 7 STPS y 8 EMA
            if (($request->opcion+0) == 20)
            {
                $eliminar_anexos = reporteanexosModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('agente_nombre', $request->agente_nombre)
                                                    ->where('registro_id', $reporte->id)
                                                    ->where('reporteanexos_tipo', 2) // ANEXOS TIPO STPS Y EMA
                                                    ->delete();

                if ($request->anexoacreditacion_checkbox)
                {
                    DB::statement('ALTER TABLE reporteanexos AUTO_INCREMENT = 1;');

                    $dato["total"] = 0;
                    foreach ($request->anexoacreditacion_checkbox as $key => $value)
                    {
                        $anexo = reporteanexosModel::create([
                              'proyecto_id' => $request->proyecto_id
                            , 'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'registro_id' => $reporte->id
                            , 'reporteanexos_tipo' => 2  // ANEXOS TIPO STPS Y EMA
                            , 'reporteanexos_anexonombre' => ($key+1).'.- '.str_replace(['\\', '/', ':', '*', '"', '?', '<', '>', '|'], '-', $request['anexoacreditacion_nombre_'.$value])
                            , 'reporteanexos_rutaanexo' => $request['anexoacreditacion_archivo_'.$value]
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
            if (($request->opcion+0) == 21)
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
