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
use App\modelos\reportes\reporteairecatalogoModel;
use App\modelos\reportes\reporteaireModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteairecategoriaModel;
use App\modelos\reportes\reporteaireareaModel;
use App\modelos\reportes\reporteaireareacategoriaModel;
//----------------------------------------------------------
use App\modelos\recsensorial\catparametrocalidadairecaracteristicaModel;
use App\modelos\reportes\reporteaireevaluacionModel;
use App\modelos\reportes\reporteaireevaluacioncategoriasModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\reportes\reportenotasModel;

use App\modelos\reportes\recursosPortadasInformesModel;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');


class reporteaireController extends Controller
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
    public function reporteairevista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente+0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL))
        {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Ventilación y Calidad del Aire primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        }
        else
        {
            // CREAR REVISION SI NO EXISTE
            //===================================================


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 8) // Aire
                                                ->orderBy('reporterevisiones_revision', 'DESC')
                                                ->get();

            // ================ DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR =========================

            if(count($revision) == 0)
            {
                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');

                $revision = reporterevisionesModel::create([
                      'proyecto_id' => $proyecto_id
                    , 'agente_id' => 8
                    , 'agente_nombre' => 'Ventilación y calidad del aire'
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
                                            reporteairecategoria.proyecto_id, 
                                            reporteairecategoria.registro_id, 
                                            reporteairecategoria.id, 
                                            reporteairecategoria.reporteairecategoria_nombre, 
                                            reporteairecategoria.reporteairecategoria_total
                                        FROM
                                            reporteairecategoria
                                        WHERE
                                            reporteairecategoria.proyecto_id = '.$proyecto_id.' 
                                        ORDER BY
                                            reporteairecategoria.reporteairecategoria_nombre ASC');


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
                                    reporteairearea.proyecto_id, 
                                    reporteairearea.registro_id, 
                                    reporteairearea.id, 
                                    reporteairearea.reporteairearea_instalacion, 
                                    reporteairearea.reporteairearea_nombre, 
                                    reporteairearea.reporteairearea_numorden, 
                                    reporteairearea.reporteairearea_porcientooperacion
                                FROM
                                    reporteairearea
                                WHERE
                                    reporteairearea.proyecto_id = '.$proyecto_id.' 
                                ORDER BY
                                    reporteairearea.reporteairearea_numorden ASC,
                                    reporteairearea.reporteairearea_nombre ASC');


            if (count($areas) > 0)
            {
                $areas_poe = 0; // NO TIENE POE GENERAL
            }
            else
            {
                $areas_poe = 1; // TIENE POE GENERAL
            }


            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            /*
            $total_categorias = DB::select('SELECT
                                                COUNT(reporteairecategoria.id) AS TOTAL
                                            FROM
                                                reporteairecategoria
                                            WHERE
                                                reporteairecategoria.proyecto_id = '.$proyecto_id);

            if (($total_categorias[0]->TOTAL + 0) == 0)
            {
                $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                                                                        ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                                                                        ->get();


                DB::statement('ALTER TABLE reporteairecategoria AUTO_INCREMENT = 1;');

                
                foreach ($recsensorial_categorias as $key => $value)
                {
                    $categoria = reporteairecategoriaModel::create([
                          'proyecto_id' => $proyecto_id
                        , 'recsensorialcategoria_id' => $value->id
                        , 'reporteairecategoria_nombre' => $value->recsensorialcategoria_nombrecategoria
                    ]);
                }
            }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


            $total_areas = DB::select('SELECT
                                            COUNT(reporteairearea.id) AS TOTAL
                                        FROM
                                            reporteairearea
                                        WHERE
                                            reporteairearea.proyecto_id = '.$proyecto_id);

            if (($total_areas[0]->TOTAL + 0) == 0)
            {
                $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                                                            ->orderBy('recsensorialarea_nombre', 'ASC')
                                                            ->get();


                DB::statement('ALTER TABLE reporteairearea AUTO_INCREMENT = 1;');

                
                foreach ($recsensorial_areas as $key => $value)
                {
                    $area = reporteaireareaModel::create([
                          'proyecto_id' => $proyecto_id
                        , 'recsensorialarea_id' => $value->id
                        , 'reporteairearea_nombre' => $value->recsensorialarea_nombre
                        , 'reporteairearea_instalacion' => $proyecto->proyecto_clienteinstalacion
                    ]);
                }
            }
            */


            //-------------------------------------


            // $categorias_poe = 1; // TIENE POE GENERAL
            // $areas_poe = 1; // TIENE POE GENERAL


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
                                            AND proyectoproveedores.catprueba_id = 8
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            //DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
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
            return view('reportes.parametros.reporteaire', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'proveedor_id', 'categorias_poe', 'areas_poe'));
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
    public function reporteairedatosgenerales($proyecto_id, $agente_id, $agente_nombre)
    {
        try
        {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
                
            $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $proyectofecha = explode("-", $proyecto->proyecto_fechaentrega);

            $reportecatalogo = reporteairecatalogoModel::limit(1)->get();
            $reporte  = reporteaireModel::where('proyecto_id', $proyecto_id)
                                        ->orderBy('reporteaire_revision', 'DESC')
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
                    $reporte = reporteaireModel::where('catactivo_id', $proyecto->catactivo_id)
                                                ->orderBy('proyecto_id', 'DESC')
                                                ->orderBy('reporteaire_revision', 'DESC')
                                                ->limit(1)
                                                ->get();
                }
                else
                {
                    $reporte = DB::select('SELECT
                                                recsensorial.recsensorial_tipocliente,
                                                recsensorial.cliente_id,
                                                reporteaire.id,
                                                reporteaire.proyecto_id,
                                                reporteaire.agente_id,
                                                reporteaire.agente_nombre,
                                                reporteaire.catactivo_id,
                                                reporteaire.reporteaire_revision,
                                                reporteaire.reporteaire_fecha,
                                                reporteaire.reporte_mes,

                                                reporteaire.reporteaire_instalacion,
                                                reporteaire.reporteaire_catregion_activo,
                                                reporteaire.reporteaire_catsubdireccion_activo,
                                                reporteaire.reporteaire_catgerencia_activo,
                                                reporteaire.reporteaire_catactivo_activo,
                                                reporteaire.reporteaire_introduccion,
                                                reporteaire.reporteaire_objetivogeneral,
                                                reporteaire.reporteaire_objetivoespecifico,
                                                reporteaire.reporteaire_metodologia_4_1,
                                                reporteaire.reporteaire_metodologia_4_2,
                                                reporteaire.reporteaire_ubicacioninstalacion,
                                                reporteaire.reporteaire_ubicacionfoto,
                                                reporteaire.reporteaire_procesoinstalacion,
                                                reporteaire.reporteaire_actividadprincipal,
                                                reporteaire.reporteaire_conclusion,
                                                reporteaire.reporteaire_responsable1,
                                                reporteaire.reporteaire_responsable1cargo,
                                                reporteaire.reporteaire_responsable1documento,
                                                reporteaire.reporteaire_responsable2,
                                                reporteaire.reporteaire_responsable2cargo,
                                                reporteaire.reporteaire_responsable2documento,
                                                reporteaire.reporteaire_concluido,
                                                reporteaire.reporteaire_concluidonombre,
                                                reporteaire.reporteaire_concluidofecha,
                                                reporteaire.reporteaire_cancelado,
                                                reporteaire.reporteaire_canceladonombre,
                                                reporteaire.reporteaire_canceladofecha,
                                                reporteaire.reporteaire_canceladoobservacion,
                                                reporteaire.created_at,
                                                reporteaire.updated_at 
                                            FROM
                                                recsensorial
                                                LEFT JOIN proyecto ON recsensorial.id = proyecto.recsensorial_id
                                                LEFT JOIN reporteaire ON proyecto.id = reporteaire.proyecto_id 
                                            WHERE
                                                recsensorial.cliente_id = '.$recsensorial->cliente_id.'  
                                                AND reporteaire.reporteaire_instalacion <> "" 
                                            ORDER BY
                                                reporteaire.updated_at DESC');
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
                                                ->where('agente_id', 8) //Aire
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


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_fecha != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $reportefecha = $reporte->reporteaire_fecha;
                $dato['reporte_portada_guardado'] = 1;

                $dato['reporte_portada'] = array(
                                                  'reporte_catregion_activo' => $reporte->reporteaire_catregion_activo
                                                , 'catregion_id' => $proyecto->catregion_id
                                                , 'reporte_catsubdireccion_activo' => $reporte->reporteaire_catsubdireccion_activo
                                                , 'catsubdireccion_id' => $proyecto->catsubdireccion_id
                                                , 'reporte_catgerencia_activo' => $reporte->reporteaire_catgerencia_activo
                                                , 'catgerencia_id' => $proyecto->catgerencia_id
                                                , 'reporte_catactivo_activo' => $reporte->reporteaire_catactivo_activo
                                                , 'catactivo_id' => $proyecto->catactivo_id
                                                , 'reporte_instalacion' => $proyecto->proyecto_clienteinstalacion
                                                , 'reporte_fecha' => $reportefecha
                                                ,'reporte_mes' => $reporte->reporte_mes

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


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_introduccion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_introduccion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_introduccion_guardado'] = 0;
                }

                $introduccion = $reporte->reporteaire_introduccion;
            }
            else
            {
                $dato['reporte_introduccion_guardado'] = 0;
                $introduccion = $reportecatalogo[0]->reporteairecatalogo_introduccion;
            }

            $dato['reporte_introduccion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $introduccion);


            // OBJETIVO GENERAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_objetivogeneral != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivogeneral_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivogeneral_guardado'] = 0;
                }

                $objetivogeneral = $reporte->reporteaire_objetivogeneral;
            }
            else
            {
                $dato['reporte_objetivogeneral_guardado'] = 0;
                $objetivogeneral = $reportecatalogo[0]->reporteairecatalogo_objetivogeneral;
            }

            $dato['reporte_objetivogeneral'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivogeneral);


            // OBJETIVOS ESPECIFICOS
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_objetivoespecifico != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_objetivoespecifico_guardado'] = 0;
                }

                $objetivoespecifico = $reporte->reporteaire_objetivoespecifico;
            }
            else
            {
                $dato['reporte_objetivoespecifico_guardado'] = 0;
                $objetivoespecifico = $reportecatalogo[0]->reporteairecatalogo_objetivoespecifico;
            }

            $dato['reporte_objetivoespecifico'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $objetivoespecifico);


            // METODOLOGIA PUNTO 4.1
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_metodologia_4_1 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_1_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_1_guardado'] = 0;
                }

                $metodologia_4_1 = $reporte->reporteaire_metodologia_4_1;
            }
            else
            {
                $dato['reporte_metodologia_4_1_guardado'] = 0;
                $metodologia_4_1 = $reportecatalogo[0]->reporteairecatalogo_metodologia_4_1;
            }

            $dato['reporte_metodologia_4_1'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_1);


            // METODOLOGIA PUNTO 4.2
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_metodologia_4_2 != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_metodologia_4_2_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_metodologia_4_2_guardado'] = 0;
                }

                $metodologia_4_2 = $reporte->reporteaire_metodologia_4_2;
            }
            else
            {
                $dato['reporte_metodologia_4_2_guardado'] = 0;
                $metodologia_4_2 = $reportecatalogo[0]->reporteairecatalogo_metodologia_4_2;
            }

            $dato['reporte_metodologia_4_2'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $metodologia_4_2);


            // UBICACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_ubicacioninstalacion != NULL)
            {
                if ($reporte->proyecto_id == $proyecto_id)
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 1;
                }
                else
                {
                    $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                }

                $ubicacion = $reporte->reporteaire_ubicacioninstalacion;
            }
            else
            {
                $dato['reporte_ubicacioninstalacion_guardado'] = 0;
                $ubicacion = $reportecatalogo[0]->reporteairecatalogo_ubicacioninstalacion;
            }


            $ubicacionfoto = NULL;
            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_ubicacionfoto != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $ubicacionfoto = $reporte->reporteaire_ubicacionfoto;
            }

            $dato['reporte_ubicacioninstalacion'] = array(
                                                          'ubicacion' => $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $ubicacion)
                                                        , 'ubicacionfoto' => $ubicacionfoto
                                                    );


            // PROCESO INSTALACION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_procesoinstalacion != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $dato['reporte_procesoinstalacion_guardado'] = 1;
                $procesoinstalacion = $reporte->reporteaire_procesoinstalacion;
            }
            else
            {
                $dato['reporte_procesoinstalacion_guardado'] = 0;
                $procesoinstalacion = $recsensorial->recsensorial_descripcionproceso;
            }

            $dato['reporte_procesoinstalacion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $procesoinstalacion);


            // ACTIVIDAD PRINCIPAL
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_actividadprincipal != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $actividadprincipal = $reporte->reporteaire_actividadprincipal;
            }
            else
            {
                $actividadprincipal = $recsensorial->recsensorial_actividadprincipal;
            }

            $dato['reporte_actividadprincipal'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $actividadprincipal);


            // CONCLUSION
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_conclusion != NULL && $reporte->proyecto_id == $proyecto_id)
            {
                $dato['reporte_conclusion_guardado'] = 1;
                $conclusion = $reporte->reporteaire_conclusion;
            }
            else
            {
                $dato['reporte_conclusion_guardado'] = 0;
                $conclusion = $reportecatalogo[0]->reporteairecatalogo_conclusion;
            }

            $dato['reporte_conclusion'] = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $conclusion);


            // RESPONSABLES DEL INFORME
            //===================================================


            if ($dato['reporteregistro_id'] >= 0 && $reporte->reporteaire_responsable1 != NULL)
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
                                                              'responsable1' => $reporte->reporteaire_responsable1
                                                            , 'responsable1cargo' => $reporte->reporteaire_responsable1cargo
                                                            , 'responsable1documento' => $reporte->reporteaire_responsable1documento
                                                            , 'responsable2' => $reporte->reporteaire_responsable2
                                                            , 'responsable2cargo' => $reporte->reporteaire_responsable2cargo
                                                            , 'responsable2documento' => $reporte->reporteaire_responsable2documento
                                                            , 'proyecto_id' => $reporte->proyecto_id
                                                            , 'registro_id' => $reporte->id
                                                        );
            }
            else
            {
                $dato['reporte_responsablesinforme_guardado'] = 0;


                $reportehistorial = reporteaireModel::where('reporteaire_responsable1', '!=', '')
                                                    ->orderBy('updated_at', 'DESC')
                                                    ->limit(1)
                                                    ->get();

                if (count($reportehistorial) > 0 && $reportehistorial[0]->reporteaire_responsable1 != NULL)
                {
                    $dato['reporte_responsablesinforme'] = array(
                                                                  'responsable1' => $reportehistorial[0]->reporteaire_responsable1
                                                                , 'responsable1cargo' => $reportehistorial[0]->reporteaire_responsable1cargo
                                                                , 'responsable1documento' => $reportehistorial[0]->reporteaire_responsable1documento
                                                                , 'responsable2' => $reportehistorial[0]->reporteaire_responsable2
                                                                , 'responsable2cargo' => $reportehistorial[0]->reporteaire_responsable2cargo
                                                                , 'responsable2documento' => $reportehistorial[0]->reporteaire_responsable2documento
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
                                                    proyectoevidenciafoto.proyecto_id = '.$proyecto_id.'
                                                    AND proyectoevidenciafoto.agente_nombre = "'.$agente_nombre.'"
                                                GROUP BY
                                                    proyectoevidenciafoto.proyecto_id,
                                                    proyectoevidenciafoto.agente_nombre
                                                LIMIT 1');

            if (count($memoriafotografica) > 0)
            {
                $dato['reporte_memoriafotografica_guardado'] = $memoriafotografica[0]->total;
            }
            else
            {                
                $dato['reporte_memoriafotografica_guardado'] = 0;
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
    public function reporteairetabladefiniciones($proyecto_id, $agente_nombre, $reporteregistro_id)
    {
        try
        {
            // $reporte = reporteaireModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteaire_concluido == 1 || $reporte[0]->reporteaire_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 8)
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
    public function reporteairedefinicioneliminar($definicion_id)
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
    public function reporteairemapaubicacion($reporteregistro_id, $archivo_opcion)
    {
        $reporte  = reporteaireModel::findOrFail($reporteregistro_id);

        if ($archivo_opcion == 0)
        {
            return Storage::response($reporte->reporteaire_ubicacionfoto);
        }
        else
        {
            return Storage::download($reporte->reporteaire_ubicacionfoto);
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
    public function reporteairecategorias($proyecto_id, $reporteregistro_id, $areas_poe)
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


                    $value->reporteairecategoria_nombre = $value->reportecategoria_nombre;
                    $value->reporteairecategoria_total = $value->reportecategoria_total;


                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                }


                 $total_singuardar = 1;
            }
            else
            {
                // $reporte = reporteaireModel::where('id', $reporteregistro_id)->get();

                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reporteaire_concluido == 1 || $reporte[0]->reporteaire_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                    ->where('agente_id', 8)
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


                $categorias = reporteairecategoriaModel::where('proyecto_id', $proyecto_id)
                                                        ->where('registro_id', $reporteregistro_id)
                                                        ->orderBy('reporteairecategoria_nombre', 'ASC')
                                                        ->get();

                if (count($categorias) == 0)
                {
                    $categorias = reporteairecategoriaModel::where('proyecto_id', $proyecto_id)
                                                            ->orderBy('reporteairecategoria_nombre', 'ASC')
                                                            ->get();
                }


                $numero_registro = 0;
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


                    if (!$value->reporteairecategoria_total)
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
    public function reporteairecategoriaeliminar($categoria_id)
    {
        try
        {
            $categoria = reporteairecategoriaModel::where('id', $categoria_id)->delete();

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
    public function reporteaireareas($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $numero_registro = 0; $numero_registro2 = 0; $total_singuardar = 0; $instalacion = 'XXX'; $area = 'XXX'; $area2 = 'XXX'; $selectareasoption = '<option value=""></option>'; $tabla_5_4 = ''; $tabla_5_5 = ''; $tabla_6_1 = '';


            if (($areas_poe+0) == 1)
            {
                $areas = DB::select('SELECT
                                         reportearea.proyecto_id,
                                         reportearea.id,
                                         reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                         reportearea.reportearea_nombre AS reporteairearea_nombre,
                                         reportearea.reportearea_orden AS reporteairearea_numorden,
                                         reportearea.reportearea_porcientooperacion,
                                         reportearea.reporteairearea_porcientooperacion,
                                         reportearea.reportearea_ventilacionsistema AS reporteairearea_ventilacionsistema,
                                         reportearea.reportearea_ventilacioncaracteristica AS reporteairearea_ventilacioncaracteristica,
                                         reportearea.reportearea_ventilacioncantidad AS reporteairearea_ventilacioncantidad,
                                         reporteareacategoria.reportecategoria_id AS reporteairecategoria_id,
                                         reportecategoria.reportecategoria_orden AS reporteairecategoria_orden,
                                         reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                         IFNULL((
                                             SELECT
                                                 IF(reporteaireareacategoria.reporteairecategoria_id, "activo", "") AS checked
                                             FROM
                                                 reporteaireareacategoria
                                             WHERE
                                                 reporteaireareacategoria.reporteairearea_id = reportearea.id
                                                 AND reporteaireareacategoria.reporteairecategoria_id = reporteareacategoria.reportecategoria_id
                                                 AND reporteaireareacategoria.reporteaireareacategoria_poe = '.$reporteregistro_id.' 
                                             LIMIT 1
                                         ), "") AS activo,
                                         reporteareacategoria.reporteareacategoria_total AS reporteaireareacategoria_total,
                                         reporteareacategoria.reporteareacategoria_geh AS reporteaireareacategoria_geh,
                                         reporteareacategoria.reporteareacategoria_actividades AS reporteaireareacategoria_actividades  
                                     FROM
                                         reportearea
                                         LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
                                         LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                     WHERE
                                         reportearea.proyecto_id = '.$proyecto_id.' 
                                     ORDER BY
                                         reportearea.reportearea_orden ASC,
                                         reportearea.reportearea_nombre ASC,
                                         reportecategoria.reportecategoria_orden ASC,
                                         reportecategoria.reportecategoria_nombre ASC');


                foreach ($areas as $key => $value) 
                {
                    if ($area != $value->reporteairearea_nombre)
                    {
                        $area = $value->reporteairearea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reporteairearea_porcientooperacion > 0)
                        {
                            $numero_registro2 += 1;

                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>'.$numero_registro2.'</td>
                                                <td>'.$value->reporteairearea_instalacion.'</td>
                                                <td>'.$value->reporteairearea_nombre.'</td>
                                                <td>'.$value->reporteairearea_porcientooperacion.' %</td>
                                            </tr>';
                        }
                    }
                    else
                    {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }


                    if ($value->activo)
                    {
                        $value->reportecategoria_nombre_texto = '<span class="text-danger">'.$value->reporteairecategoria_nombre.'</span>';
                    }
                    else
                    {
                        $value->reportecategoria_nombre_texto = $value->reporteairecategoria_nombre;
                    }


                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';


                    if ($value->reporteairearea_ventilacionsistema === NULL)
                    {
                        $total_singuardar += 1;
                    }


                    if ($value->reporteairearea_porcientooperacion > 0)
                    {
                        if ($value->activo)
                        {
                            //TABLA 5.4.- Actividades del personal expuesto
                            //==================================================


                            $tabla_5_4 .= '<tr>
                                                <td>'.$numero_registro2.'</td>
                                                <td>'.$value->reporteairearea_instalacion.'</td>
                                                <td>'.$value->reporteairearea_nombre.'</td>
                                                <td>'.$value->reporteairecategoria_nombre.'</td>
                                                <td class="justificado">'.$value->reporteaireareacategoria_actividades.'</td>
                                            </tr>';
                        }


                        //TABLA 5.5.- Identificación de las áreas
                        //==================================================


                        $tabla_5_5 .= '<tr>
                                            <td>'.$numero_registro2.'</td>
                                            <td>'.$value->reporteairearea_instalacion.'</td>
                                            <td>'.$value->reporteairearea_nombre.'</td>
                                            <td>'.$value->reporteairearea_ventilacionsistema.'</td>
                                            <td>'.$value->reporteairearea_ventilacioncaracteristica.'</td>
                                            <td>'.$value->reporteairearea_ventilacioncantidad.'</td>
                                        </tr>';


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reporteairearea_instalacion && ($key + 0) == 0)
                        {
                            $instalacion = $value->reporteairearea_instalacion;
                            $selectareasoption .= '<optgroup label="'.$instalacion.'">';
                        }
                        
                        if ($instalacion != $value->reporteairearea_instalacion && ($key + 0) > 0)
                        {
                            $instalacion = $value->reporteairearea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="'.$instalacion.'">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reporteairearea_nombre)
                        {
                            $area2 = $value->reporteairearea_nombre;
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
                // $reporte = reporteaireModel::where('id', $reporteregistro_id)->get();

                // $edicion = 1;
                // if (count($reporte) > 0)
                // {
                //     if($reporte[0]->reporteaire_concluido == 1 || $reporte[0]->reporteaire_cancelado == 1)
                //     {
                //         $edicion = 0;
                //     }
                // }


                $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                        ->where('agente_id', 8)
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


                $registros = DB::select('SELECT
                                            COUNT(reporteairearea.id) AS total
                                        FROM
                                            reporteairearea
                                        WHERE
                                            reporteairearea.proyecto_id = '.$proyecto_id.'
                                            AND reporteairearea.registro_id = '.$reporteregistro_id);


                $where_condicion = '';
                if (($registros[0]->total + 0) > 0)
                {
                    $where_condicion = 'AND reporteairearea.registro_id = '.$reporteregistro_id;
                }


                //==========================================


                $areas = DB::select('SELECT
                                        reporteairearea.id,
                                        reporteairearea.proyecto_id,
                                        reporteairearea.registro_id,
                                        reporteairearea.recsensorialarea_id,
                                        reporteairearea.reporteairearea_instalacion,
                                        reporteairearea.reporteairearea_nombre,
                                        reporteairearea.reporteairearea_numorden,
                                        reporteairearea.reporteairearea_porcientooperacion,
                                        reporteairearea.reporteairearea_ventilacionsistema,
                                        reporteairearea.reporteairearea_ventilacioncaracteristica,
                                        reporteairearea.reporteairearea_ventilacioncantidad,
                                        reporteaireareacategoria.reporteairecategoria_id,
                                        reporteairecategoria.reporteairecategoria_nombre,
                                        reporteaireareacategoria.reporteaireareacategoria_total, 
                                        reporteaireareacategoria.reporteaireareacategoria_actividades 
                                    FROM
                                        reporteairearea
                                        LEFT OUTER JOIN reporteaireareacategoria ON reporteairearea.id = reporteaireareacategoria.reporteairearea_id
                                        LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id 
                                    WHERE
                                        reporteairearea.proyecto_id = '.$proyecto_id.' 
                                        '.$where_condicion.' 
                                    ORDER BY
                                        reporteairearea.reporteairearea_numorden ASC,
                                        reporteairearea.reporteairearea_instalacion ASC,
                                        reporteairearea.reporteairearea_nombre ASC');

                
                foreach ($areas as $key => $value) 
                {
                    if ($area != $value->reporteairearea_nombre)
                    {
                        $area = $value->reporteairearea_nombre;
                        $value->area_nombre = $area;


                        $numero_registro += 1;
                        $value->numero_registro = $numero_registro;


                        if ($value->reporteairearea_porcientooperacion > 0)
                        {
                            $numero_registro2 += 1;

                            //TABLA 6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)
                            //==================================================

                            $tabla_6_1 .= '<tr>
                                                <td>'.$numero_registro2.'</td>
                                                <td>'.$value->reporteairearea_instalacion.'</td>
                                                <td>'.$value->reporteairearea_nombre.'</td>
                                                <td>'.$value->reporteairearea_porcientooperacion.' %</td>
                                            </tr>';
                        }
                    }
                    else
                    {
                        $value->area_nombre = $area;
                        $value->numero_registro = $numero_registro;
                    }

                    $value->reportecategoria_nombre_texto = $value->reporteairecategoria_nombre;
                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';

                    if ($edicion == 1)
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                    }
                    else
                    {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
                    }

                    if ($value->reporteairearea_porcientooperacion === NULL)
                    {
                        $total_singuardar += 1;
                    }


                    if ($value->reporteairearea_porcientooperacion > 0)
                    {
                        //TABLA 5.4.- Actividades del personal expuesto
                        //==================================================


                        $tabla_5_4 .= '<tr>
                                            <td>'.$numero_registro2.'</td>
                                            <td>'.$value->reporteairearea_instalacion.'</td>
                                            <td>'.$value->reporteairearea_nombre.'</td>
                                            <td>'.$value->reporteairecategoria_nombre.'</td>
                                            <td class="justificado">'.$value->reporteaireareacategoria_actividades.'</td>
                                        </tr>';


                        //TABLA 5.5.- Identificación de las áreas
                        //==================================================


                        $tabla_5_5 .= '<tr>
                                            <td>'.$numero_registro2.'</td>
                                            <td>'.$value->reporteairearea_instalacion.'</td>
                                            <td>'.$value->reporteairearea_nombre.'</td>
                                            <td>'.$value->reporteairearea_ventilacionsistema.'</td>
                                            <td>'.$value->reporteairearea_ventilacioncaracteristica.'</td>
                                            <td>'.$value->reporteairearea_ventilacioncantidad.'</td>
                                        </tr>';


                        // SELECT OPCIONES DE AREAS POR INSTALACION
                        //==================================================


                        if ($instalacion != $value->reporteairearea_instalacion && ($key + 0) == 0)
                        {
                            $instalacion = $value->reporteairearea_instalacion;
                            $selectareasoption .= '<optgroup label="'.$instalacion.'">';
                        }
                        
                        if ($instalacion != $value->reporteairearea_instalacion && ($key + 0) > 0)
                        {
                            $instalacion = $value->reporteairearea_instalacion;
                            $selectareasoption .= '</optgroup><optgroup label="'.$instalacion.'">';
                            $area2 = 'XXXXX';
                        }


                        if ($area2 != $value->reporteairearea_nombre)
                        {
                            $area2 = $value->reporteairearea_nombre;
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
            $dato["tabla_5_4"] = $tabla_5_4;
            $dato["tabla_5_5"] = $tabla_5_5;
            $dato["tabla_6_1"] = $tabla_6_1;
            $dato["selectareasoption"] = $selectareasoption;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = 0;
            $dato["total_singuardar"] = $total_singuardar;
            $dato["tabla_5_4"] = '<tr><td colspan="5">Error al consultar los datos</td></tr>';
            $dato["tabla_5_5"] = '<tr><td colspan="6">Error al consultar los datos</td></tr>';
            $dato["tabla_6_1"] = '<tr><td colspan="4">Error al consultar los datos</td></tr>';
            $dato["selectareasoption"] = '<option value="">Error al consultar áreas</option>';
            $dato["msj"] = 'Error '.$e->getMessage();
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
    public function reporteaireareacategorias($proyecto_id, $reporteregistro_id, $area_id, $areas_poe)
    {
        try
        {
            if (($areas_poe+0) == 1)
            {
                $areacategorias = DB::select('SELECT
                                                    reportecategoria.proyecto_id,
                                                    reporteareacategoria.reportearea_id,
                                                    reportecategoria.id,
                                                    reportecategoria.reportecategoria_orden,
                                                    reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteaireareacategoria.reporteairecategoria_id, "checked", "") AS checked
                                                        FROM
                                                            reporteaireareacategoria
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = reporteareacategoria.reportearea_id
                                                            AND reporteaireareacategoria.reporteairecategoria_id = reportecategoria.id
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe = '.$reporteregistro_id.' 
                                                        LIMIT 1
                                                    ), "") AS checked,
                                                    reporteareacategoria.reporteareacategoria_total AS categoria_total,
                                                    reporteareacategoria.reporteareacategoria_geh AS reporteaireareacategoria_geh,
                                                    reporteareacategoria.reporteareacategoria_actividades AS categoria_actividades
                                                FROM
                                                    reporteareacategoria
                                                    INNER JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                                WHERE
                                                    reportecategoria.proyecto_id = '.$proyecto_id.' 
                                                    AND reporteareacategoria.reportearea_id = '.$area_id.' 
                                                ORDER BY
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC');


                $numero_registro = 0;
                $areacategorias_lista = '';


                foreach ($areacategorias as $key => $value) 
                {
                    $numero_registro += 1;


                    $areacategorias_lista .= '<tr>
                                                <td with="60">
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_categoria_id[]" value="'.$value->id.'" '.$value->checked.'/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td with="240">
                                                    '.$value->reporteairecategoria_nombre.'
                                                </td>
                                                <td with="120">
                                                    <input type="number" min="1" class="form-control areacategoria_'.$numero_registro.'" name="areacategoria_total_'.$value->id.'" value="'.$value->categoria_total.'" readonly>
                                                </td>
                                                <td with="">
                                                    <textarea rows="2" class="form-control areacategoria_'.$numero_registro.'" name="areacategoria_actividades_'.$value->id.'" readonly>'.$value->categoria_actividades.'</textarea>
                                                </td>
                                            </tr>';
                }
            }
            else
            {
                $areacategorias = DB::select('SELECT
                                                    reporteairecategoria.id,
                                                    reporteairecategoria.proyecto_id,
                                                    reporteairecategoria.recsensorialcategoria_id,
                                                    reporteairecategoria.reporteairecategoria_nombre,
                                                    reporteairecategoria.reporteairecategoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteaireareacategoria.id, "checked", "") AS checked
                                                        FROM
                                                            reporteaireareacategoria
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = '.$area_id.'
                                                            AND reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                                    ), "") AS checked,
                                                    IFNULL((
                                                        SELECT
                                                            reporteaireareacategoria.reporteaireareacategoria_total
                                                        FROM
                                                            reporteaireareacategoria
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = '.$area_id.'
                                                            AND reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                                    ), "") AS categoria_total,
                                                    IFNULL((
                                                        SELECT
                                                            reporteaireareacategoria.reporteaireareacategoria_actividades
                                                        FROM
                                                            reporteaireareacategoria
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = '.$area_id.'
                                                            AND reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                                    ), "") AS categoria_actividades
                                                FROM
                                                    reporteairecategoria
                                                WHERE
                                                    reporteairecategoria.proyecto_id = '.$proyecto_id.'
                                                    AND reporteairecategoria.registro_id = '.$reporteregistro_id.'
                                                ORDER BY
                                                    reporteairecategoria.reporteairecategoria_nombre ASC');


                $numero_registro = 0;
                $areacategorias_lista = '';
                $readonly_required = '';


                foreach ($areacategorias as $key => $value) 
                {
                    $numero_registro += 1;

                    if ($value->checked){
                        $readonly_required = 'required';
                    }
                    else{
                        $readonly_required = 'readonly';
                    }

                    $areacategorias_lista .= '<tr>
                                                <td with="60">
                                                    <div class="switch" style="border: 0px #000 solid;">
                                                        <label>
                                                            <input type="checkbox" name="checkbox_categoria_id[]" value="'.$value->id.'" '.$value->checked.' onchange="activa_areacategoria(this, '.$numero_registro.');"/>
                                                            <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td with="240">
                                                    '.$value->reporteairecategoria_nombre.'
                                                </td>
                                                <td with="120">
                                                    <input type="number" min="1" class="form-control areacategoria_'.$numero_registro.'" name="areacategoria_total_'.$value->id.'" value="'.$value->categoria_total.'" '.$readonly_required.'>
                                                </td>
                                                <td with="">
                                                    <textarea rows="2" class="form-control areacategoria_'.$numero_registro.'" name="areacategoria_actividades_'.$value->id.'" '.$readonly_required.'>'.$value->categoria_actividades.'</textarea>
                                                </td>
                                            </tr>';
                }
            }


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;            
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['areacategorias'] = '<tr><td colspan="4">Error al cargar las categorías</td></tr>';
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
    public function reporteaireareaeliminar($area_id)
    {
        try
        {
            $area = reporteaireareaModel::where('id', $area_id)->delete();
            $areacategorias = reporteaireareacategoriaModel::where('reporteairearea_id', $area_id)
                                                            ->where('reporteaireareacategoria_poe', 0)
                                                            ->delete();

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
    public function reporteaireevaluaciontabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            // $reporte = reporteaireModel::where('id', $reporteregistro_id)->get();

            // $edicion = 1;
            // if (count($reporte) > 0)
            // {
            //     if($reporte[0]->reporteaire_concluido == 1 || $reporte[0]->reporteaire_cancelado == 1)
            //     {
            //         $edicion = 0;
            //     }
            // }


            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                            ->where('agente_id', 8)
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


            $numero_registro = 0; $dato['tabla_reporte_7_1'] = NULL; $dato['tabla_reporte_7_2'] = NULL; $dato['tabla_reporte_7_3'] = NULL; $dato['tabla_reporte_7_4'] = NULL; $dato['tabla_reporte_7_5'] = NULL; $dato['tabla_reporte_7_6'] = NULL;


            if (($areas_poe+0) == 1)
            {
                $evaluacion = DB::select('SELECT
                                                reporteaireevaluacion.id,
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.reporteairearea_id,
                                                reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                reportearea.reportearea_orden AS reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                (
                                                    SELECT
                                                        -- TABLA.proyecto_id,
                                                        -- TABLA.registro_id,
                                                        -- TABLA.reporteairearea_id,
                                                        COUNT(TABLA.reporteaireevaluacion_punto)
                                                    FROM
                                                        reporteaireevaluacion AS TABLA
                                                    WHERE
                                                        TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                        AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                        AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                ) AS total_puntosarea,
                                                (
                                                    SELECT
                                                        -- TABLA.proyecto_id,
                                                        -- TABLA.registro_id,
                                                        -- TABLA.reporteairearea_id,
                                                        COUNT(TABLA.reporteaireevaluacion_punto)
                                                    FROM
                                                        reporteaireevaluacion AS TABLA
                                                    WHERE
                                                        TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                        AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                        AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                        AND TABLA.reporteaireevaluacion_ubicacion = reporteaireevaluacion.reporteaireevaluacion_ubicacion
                                                ) AS total_puntosubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                (
                                                    -- IF((REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,"") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS ct_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS ctma_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS hongos_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS levaduras_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, "Dentro de norma", "Fuera de norma")
                                                ) AS temperatura_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                (
                                                    -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, "Dentro de norma", "Fuera de norma")
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), "Dentro de norma", "Fuera de norma")
                                                ) AS velocidad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, "Dentro de norma", "Fuera de norma")
                                                ) AS humedad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS co_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co2,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS co2_resultado 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                reportearea.reportearea_orden ASC');
            }
            else
            {
                $evaluacion = DB::select('SELECT
                                                reporteaireevaluacion.id,
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.reporteairearea_id,
                                                reporteairearea.reporteairearea_instalacion,
                                                reporteairearea.reporteairearea_nombre,
                                                reporteairearea.reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                (
                                                    SELECT
                                                        -- TABLA.proyecto_id,
                                                        -- TABLA.registro_id,
                                                        -- TABLA.reporteairearea_id,
                                                        COUNT(TABLA.reporteaireevaluacion_punto)
                                                    FROM
                                                        reporteaireevaluacion AS TABLA
                                                    WHERE
                                                        TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                        AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                        AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                ) AS total_puntosarea,
                                                (
                                                    SELECT
                                                        -- TABLA.proyecto_id,
                                                        -- TABLA.registro_id,
                                                        -- TABLA.reporteairearea_id,
                                                        COUNT(TABLA.reporteaireevaluacion_punto)
                                                    FROM
                                                        reporteaireevaluacion AS TABLA
                                                    WHERE
                                                        TABLA.proyecto_id = reporteaireevaluacion.proyecto_id 
                                                        AND TABLA.registro_id = reporteaireevaluacion.registro_id 
                                                        AND TABLA.reporteairearea_id = reporteaireevaluacion.reporteairearea_id
                                                        AND TABLA.reporteaireevaluacion_ubicacion = reporteaireevaluacion.reporteaireevaluacion_ubicacion
                                                ) AS total_puntosubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                (
                                                    -- IF((REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,"") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS ct_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS ctma_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS hongos_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS levaduras_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, "Dentro de norma", "Fuera de norma")
                                                ) AS temperatura_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                (
                                                    -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, "Dentro de norma", "Fuera de norma")
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), "Dentro de norma", "Fuera de norma")
                                                ) AS velocidad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, "Dentro de norma", "Fuera de norma")
                                                ) AS humedad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS co_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co2,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                            , "Dentro de norma"
                                                            , "Fuera de norma"
                                                        )
                                                        , "Fuera de norma"
                                                    )
                                                ) AS co2_resultado 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                reporteairearea.reporteairearea_numorden ASC');
            }


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


                // TABLA RESULTADOS BIOAEROSOLES
                //==========================================


                $dato['tabla_reporte_7_1'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->total_puntosarea.'</td>
                                                    <td>Cuenta de microorganismos coliformes totales en placa (CT)</td>
                                                    <td>NOM-113-SSA-1994</td>
                                                    <td>UFC/mtra</td>
                                                    <td>500</td>
                                                    <td>'.$value->reporteaireevaluacion_ct.'</td>
                                                    <td>'.$value->ct_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->total_puntosarea.'</td>
                                                    <td>Cuenta Total de Mesofílicos Aerobios (CTMA)</td>
                                                    <td>NOM-092-SSA1-1994</td>
                                                    <td>UFC/mtra</td>
                                                    <td>500</td>
                                                    <td>'.$value->reporteaireevaluacion_ctma.'</td>
                                                    <td>'.$value->ctma_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->total_puntosarea.'</td>
                                                    <td>Hongos</td>
                                                    <td>NOM-111-SSA1-1994</td>
                                                    <td>UFC/mtra</td>
                                                    <td>500</td>
                                                    <td>'.$value->reporteaireevaluacion_hongos.'</td>
                                                    <td>'.$value->hongos_resultado.'</td>
                                                </tr>
                                                <tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->total_puntosarea.'</td>
                                                    <td>Levaduras</td>
                                                    <td>NOM-111-SSA1-1994</td>
                                                    <td>UFC/mtra</td>
                                                    <td>500</td>
                                                    <td>'.$value->reporteaireevaluacion_levaduras.'</td>
                                                    <td>'.$value->levaduras_resultado.'</td>
                                                </tr>';


                // TABLA RESULTADOS TEMPERATURA
                //==========================================


                $dato['tabla_reporte_7_2'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_ubicacion.'</td>
                                                    <td>'.$value->total_puntosubicacion.'</td>
                                                    <td>22-24.5</td>
                                                    <td>'.$value->reporteaireevaluacion_temperatura.'</td>
                                                    <td>'.$value->temperatura_resultado.'</td>
                                                </tr>';


                // TABLA RESULTADOS VELOCIDAD
                //==========================================


                $dato['tabla_reporte_7_3'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_ubicacion.'</td>
                                                    <td>'.$value->total_puntosubicacion.'</td>
                                                    <td>'.$value->reporteaireevaluacion_velocidadlimite.'</td>
                                                    <td>'.$value->reporteaireevaluacion_velocidad.'</td>
                                                    <td>'.$value->velocidad_resultado.'</td>
                                                </tr>';


                // TABLA RESULTADOS HUMEDAD RELATIVA
                //==========================================


                $dato['tabla_reporte_7_4'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_ubicacion.'</td>
                                                    <td>'.$value->total_puntosubicacion.'</td>
                                                    <td>20-60</td>
                                                    <td>'.$value->reporteaireevaluacion_humedad.'</td>
                                                    <td>'.$value->humedad_resultado.'</td>
                                                </tr>';


                // TABLA RESULTADOS Monóxido de Carbono (CO)
                //==========================================


                $dato['tabla_reporte_7_5'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_ubicacion.'</td>
                                                    <td>'.$value->total_puntosubicacion.'</td>
                                                    <td>25</td>
                                                    <td>'.$value->reporteaireevaluacion_co.'</td>
                                                    <td>'.$value->co_resultado.'</td>
                                                </tr>';


                // TABLA RESULTADOS Dióxido  de Carbono (CO2)
                //==========================================


                $dato['tabla_reporte_7_6'] .= '<tr>
                                                    <td>'.$value->reporteaireevaluacion_punto.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteairearea_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_ubicacion.'</td>
                                                    <td>'.$value->total_puntosubicacion.'</td>
                                                    <td>5000</td>
                                                    <td>'.$value->reporteaireevaluacion_co2.'</td>
                                                    <td>'.$value->co2_resultado.'</td>
                                                </tr>';
            }


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
            $dato['tabla_reporte_7_1'] = NULL;
            $dato['tabla_reporte_7_2'] = NULL;
            $dato['tabla_reporte_7_3'] = NULL;
            $dato['tabla_reporte_7_4'] = NULL;
            $dato['tabla_reporte_7_5'] = NULL;
            $dato['tabla_reporte_7_6'] = NULL;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reporteaireevaluacion_id
     * @param  int $reporteairearea_id
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteaireevaluacioncategorias($reporteaireevaluacion_id, $reporteairearea_id, $areas_poe)
    {
        try
        {
            if (($areas_poe+0) == 1)
            {
                $evaluacionareacategorias = DB::select('SELECT
                                                            reporteaireareacategoria.id,
                                                            reporteaireareacategoria.reporteairearea_id,
                                                            reporteaireareacategoria.reporteairecategoria_id,
                                                            reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS nombre,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS ficha,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS geo
                                                        FROM
                                                            reporteaireareacategoria
                                                            LEFT JOIN reportecategoria ON reporteaireareacategoria.reporteairecategoria_id = reportecategoria.id 
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = '.$reporteairearea_id.' 
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe > 0 
                                                            AND reportecategoria.reportecategoria_nombre != "" 
                                                        ORDER BY
                                                            reportecategoria.reportecategoria_orden ASC,
                                                            reportecategoria.reportecategoria_nombre ASC');
            }
            else
            {
                $evaluacionareacategorias = DB::select('SELECT
                                                            reporteaireareacategoria.id,
                                                            reporteaireareacategoria.reporteairearea_id,
                                                            reporteaireareacategoria.reporteairecategoria_id,
                                                            reporteairecategoria.reporteairecategoria_nombre,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS nombre,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS ficha,
                                                            (
                                                                SELECT
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacion_id,
                                                                    -- reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo 
                                                                FROM
                                                                    reporteaireevaluacioncategorias
                                                                WHERE
                                                                    reporteaireevaluacioncategorias.reporteaireevaluacion_id = '.$reporteaireevaluacion_id.' 
                                                                    AND reporteaireevaluacioncategorias.reporteairecategoria_id = reporteaireareacategoria.reporteairecategoria_id
                                                                LIMIT 1
                                                            ) AS geo
                                                        FROM
                                                            reporteaireareacategoria
                                                            LEFT JOIN reporteairecategoria ON reporteaireareacategoria.reporteairecategoria_id = reporteairecategoria.id 
                                                        WHERE
                                                            reporteaireareacategoria.reporteairearea_id = '.$reporteairearea_id.'
                                                            AND reporteaireareacategoria.reporteaireareacategoria_poe = 0 
                                                            AND reporteairecategoria.reporteairecategoria_nombre != "" 
                                                        ORDER BY
                                                            reporteairecategoria.reporteairecategoria_nombre ASC');
            }


            $numero_registro = 0;
            $areacategorias_lista = '';


            foreach ($evaluacionareacategorias as $key => $value) 
            {
                $numero_registro += 1;


                $areacategorias_lista .= '<tr>
                                                <td width="400">
                                                    <input type="hidden" class="form-control" name="reporteairecategoria_id[]" value="'.$value->reporteairecategoria_id.'" required>
                                                    <b style="color: #000000;">'.$value->reporteairecategoria_nombre.'</b>
                                                </td>
                                                <td width="300">
                                                    <input type="text" class="form-control" name="reporteaireevaluacioncategorias_nombre[]" value="'.$value->nombre.'" required>
                                                </td>
                                                <td width="120">
                                                    <input type="text" maxlength="30" class="form-control" name="reporteaireevaluacioncategorias_ficha[]"  value="'.$value->ficha.'" required>
                                                </td>
                                                <td width="100">
                                                    <input type="number" min="0" maxlength="4" class="form-control" name="reporteaireevaluacioncategorias_geo[]"  value="'.$value->geo.'" required>
                                                </td>
                                            </tr>';
            }


            // respuesta
            $dato['categorias'] = $areacategorias_lista;            
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['categorias'] = '<tr><td colspan="4">Error al cargar las categorías</td></tr>';
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reporteaireevaluacion_id
     * @return \Illuminate\Http\Response
     */
    public function reporteaireevaluacioneliminar($reporteaireevaluacion_id)
    {
        try
        {
            $puntoevaluacion = reporteaireevaluacionModel::where('id', $reporteaireevaluacion_id)->delete();
            $puntoevaluacion_categorias = reporteaireevaluacioncategoriasModel::where('reporteaireevaluacion_id', $reporteaireevaluacion_id)->delete();


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
    public function reporteairematriztabla($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            $numero_registro = 0; 


            $proyecto = proyectoModel::findOrFail($proyecto_id);


            if (($proyecto->catregion_id+0) == 1 || ($proyecto->catregion_id+0) == 2)
            {
                if (($areas_poe+0) == 1)
                {
                    $matriz = DB::select('SELECT
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.id,
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
                                                reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                reportearea.reportearea_orden AS reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                reportecategoria.reportecategoria_total AS reporteairecategoria_total,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                -- reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                -- reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                -- reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- reporteaireevaluacion.reporteaireevaluacion_co2 
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.'  
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                AND reportecategoria.reportecategoria_nombre != "" 
                                            ORDER BY
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                reportearea.reportearea_orden ASC');
                }
                else
                {
                    $matriz = DB::select('SELECT
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.id,
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
                                                reporteairearea.reporteairearea_instalacion,
                                                reporteairearea.reporteairearea_nombre,
                                                reporteairearea.reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                reporteairecategoria.reporteairecategoria_nombre,
                                                reporteairecategoria.reporteairecategoria_total,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_geo,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                -- reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                -- reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                -- reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                -- reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- reporteaireevaluacion.reporteaireevaluacion_co2 
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.'  
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                AND reporteairecategoria.reporteairecategoria_nombre != "" 
                                            ORDER BY
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                reporteairearea.reporteairearea_numorden ASC');
                }


                $dato["data"] = $matriz;
                $dato["total"] = count($matriz);
            }
            else
            {
                if (($areas_poe+0) == 1)
                {
                    $categorias = DB::select('SELECT
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                    -- reportearea.reportearea_nombre,
                                                    -- reportearea.reportearea_orden,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_punto
                                                FROM
                                                    reporteaireevaluacion
                                                    LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                    INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                    LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                                WHERE
                                                    reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                    AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    AND reportecategoria.reportecategoria_nombre != "" 
                                                GROUP BY
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reportearea.reportearea_instalacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reportecategoria.reportecategoria_nombre
                                                ORDER BY
                                                    reportearea.reportearea_instalacion ASC,
                                                    reportecategoria.reportecategoria_orden ASC,
                                                    reportecategoria.reportecategoria_nombre ASC');

                    
                    $dato["matriz"] = '';
                    foreach ($categorias as $key => $categoria)
                    {
                        $matriz = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.id,
                                                TABLA.catregion_nombre,
                                                TABLA.catsubdireccion_nombre,
                                                TABLA.catgerencia_nombre,
                                                TABLA.catactivo_nombre,
                                                TABLA.gerencia_activo,
                                                TABLA.reporteairearea_instalacion,
                                                TABLA.reporteairearea_nombre,
                                                TABLA.reporteairearea_numorden,
                                                TABLA.reporteaireevaluacion_ubicacion,
                                                TABLA.reporteairecategoria_id,
                                                TABLA.reporteairecategoria_nombre,
                                                TABLA.reporteaireevaluacioncategorias_nombre,
                                                TABLA.reporteaireevaluacioncategorias_ficha,
                                                TABLA.reporteaireevaluacion_punto,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2, 
                                                -- TABLA.ct_resultado,
                                                -- TABLA.ctma_resultado,
                                                -- TABLA.hongos_resultado,
                                                -- TABLA.levaduras_resultado,
                                                -- TABLA.temperatura_resultado,
                                                -- TABLA.velocidad_resultado,
                                                -- TABLA.humedad_resultado,
                                                -- TABLA.co_resultado,
                                                -- TABLA.co2_resultado,
                                                (
                                                    ROUND(ROUND((
                                                            TABLA.ct_resultado + 
                                                            TABLA.ctma_resultado + 
                                                            TABLA.hongos_resultado + 
                                                            TABLA.levaduras_resultado + 
                                                            TABLA.temperatura_resultado + 
                                                            TABLA.velocidad_resultado + 
                                                            TABLA.humedad_resultado + 
                                                            TABLA.co_resultado + 
                                                            TABLA.co2_resultado
                                                    ) / 9, 3) * 100, 1)
                                                ) AS cumplimiento 
                                            FROM
                                                (
                                                    SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteaireevaluacion.id,
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
                                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                        reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                        reportearea.reportearea_orden AS reporteairearea_numorden,
                                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                        reportecategoria.reportecategoria_nombre AS reporteairecategoria_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                                        reporteaireevaluacion.reporteaireevaluacion_ct,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ct_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ctma_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS hongos_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS levaduras_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                        ) AS temperatura_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                        (
                                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                        ) AS velocidad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                        ) AS humedad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co2,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co2_resultado 
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                        LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                        LEFT JOIN reportecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reportecategoria.id 
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = '.$categoria->proyecto_id.' 
                                                        AND reporteaireevaluacion.registro_id = '.$categoria->registro_id.' 
                                                        AND reporteaireevaluacioncategorias.reporteairecategoria_id = '.$categoria->reporteairecategoria_id.'
                                                    ORDER BY
                                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                        reportecategoria.reportecategoria_orden ASC,
                                                        reportecategoria.reportecategoria_nombre ASC
                                                ) AS TABLA
                                            ORDER BY
                                                cumplimiento ASC
                                            LIMIT 1');


                        foreach ($matriz as $key2 => $value)
                        {
                            $numero_registro += 1;


                            $dato["matriz"] .= '<tr>
                                                    <td>'.$numero_registro.'</td>
                                                    <td>'.$value->catsubdireccion_nombre.'</td>
                                                    <td>'.$value->gerencia_activo.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteaireevaluacioncategorias_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacioncategorias_ficha.'</td>
                                                    <td>'.$value->reporteairecategoria_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_temperatura.'</td>
                                                    <td>'.$value->reporteaireevaluacion_velocidad.'</td>
                                                    <td>'.$value->reporteaireevaluacion_humedad.'</td>
                                                    <td>'.$value->reporteaireevaluacion_co.'</td>
                                                    <td>'.$value->reporteaireevaluacion_co2.'</td>
                                                    <td><b>CT:</b> '.$value->reporteaireevaluacion_ct.'<br><b>CTMA:</b> '.$value->reporteaireevaluacion_ctma.'<br><b>Hongos:</b> '.$value->reporteaireevaluacion_hongos.'<br><b>Levaduras:</b> '.$value->reporteaireevaluacion_levaduras.'</td>
                                                </tr>';
                        }
                    }
                }
                else
                {
                    $categorias = DB::select('SELECT
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    -- reporteairearea.reporteairearea_nombre,
                                                    -- reporteairearea.reporteairearea_numorden,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reporteairecategoria.reporteairecategoria_nombre
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                    -- reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                    -- reporteaireevaluacion.reporteaireevaluacion_punto
                                                FROM
                                                    reporteaireevaluacion
                                                    LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                    LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                    LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                    LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                    LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                    LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                    INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                    LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                                WHERE
                                                    reporteaireevaluacion.proyecto_id = '.$proyecto_id.'  
                                                    AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    AND reporteairecategoria.reporteairecategoria_nombre != "" 
                                                GROUP BY
                                                    reporteaireevaluacion.proyecto_id,
                                                    reporteaireevaluacion.registro_id,
                                                    reporteairearea.reporteairearea_instalacion,
                                                    reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                    reporteairecategoria.reporteairecategoria_nombre
                                                ORDER BY
                                                    reporteairearea.reporteairearea_instalacion ASC,
                                                    reporteairecategoria.reporteairecategoria_nombre ASC');

                    
                    $dato["matriz"] = '';
                    foreach ($categorias as $key => $categoria)
                    {
                        $matriz = DB::select('SELECT
                                                TABLA.proyecto_id,
                                                TABLA.registro_id,
                                                TABLA.id,
                                                TABLA.catregion_nombre,
                                                TABLA.catsubdireccion_nombre,
                                                TABLA.catgerencia_nombre,
                                                TABLA.catactivo_nombre,
                                                TABLA.gerencia_activo,
                                                TABLA.reporteairearea_instalacion,
                                                TABLA.reporteairearea_nombre,
                                                TABLA.reporteairearea_numorden,
                                                TABLA.reporteaireevaluacion_ubicacion,
                                                TABLA.reporteairecategoria_id,
                                                TABLA.reporteairecategoria_nombre,
                                                TABLA.reporteaireevaluacioncategorias_nombre,
                                                TABLA.reporteaireevaluacioncategorias_ficha,
                                                TABLA.reporteaireevaluacion_punto,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_temperatura, "<", "˂"), ">", "˃") AS reporteaireevaluacion_temperatura,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_velocidad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_velocidad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_humedad, "<", "˂"), ">", "˃") AS reporteaireevaluacion_humedad,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                REPLACE(REPLACE(TABLA.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2, 
                                                -- TABLA.ct_resultado,
                                                -- TABLA.ctma_resultado,
                                                -- TABLA.hongos_resultado,
                                                -- TABLA.levaduras_resultado,
                                                -- TABLA.temperatura_resultado,
                                                -- TABLA.velocidad_resultado,
                                                -- TABLA.humedad_resultado,
                                                -- TABLA.co_resultado,
                                                -- TABLA.co2_resultado,
                                                (
                                                    ROUND(ROUND((
                                                            TABLA.ct_resultado + 
                                                            TABLA.ctma_resultado + 
                                                            TABLA.hongos_resultado + 
                                                            TABLA.levaduras_resultado + 
                                                            TABLA.temperatura_resultado + 
                                                            TABLA.velocidad_resultado + 
                                                            TABLA.humedad_resultado + 
                                                            TABLA.co_resultado + 
                                                            TABLA.co2_resultado
                                                    ) / 9, 3) * 100, 1)
                                                ) AS cumplimiento 
                                            FROM
                                                (
                                                    SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteaireevaluacion.id,
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
                                                        reporteairearea.reporteairearea_instalacion,
                                                        reporteairearea.reporteairearea_nombre,
                                                        reporteairearea.reporteairearea_numorden,
                                                        reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                        reporteaireevaluacioncategorias.reporteairecategoria_id,
                                                        reporteairecategoria.reporteairecategoria_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_nombre,
                                                        reporteaireevaluacioncategorias.reporteaireevaluacioncategorias_ficha,
                                                        reporteaireevaluacion.reporteaireevaluacion_punto,
                                                        reporteaireevaluacion.reporteaireevaluacion_ct,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ct_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS ctma_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS hongos_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS levaduras_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                        ) AS temperatura_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                        reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                        (
                                                            -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                        ) AS velocidad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                        (
                                                            IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                        ) AS humedad_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co_resultado,
                                                        reporteaireevaluacion.reporteaireevaluacion_co2,
                                                        (
                                                            -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000, 1, 0)
                                                            IF(
                                                                CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                                , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                , 0
                                                            )
                                                        ) AS co2_resultado 
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN proyecto ON reporteaireevaluacion.proyecto_id = proyecto.id
                                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                        LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                        INNER JOIN reporteaireevaluacioncategorias ON reporteaireevaluacion.id = reporteaireevaluacioncategorias.reporteaireevaluacion_id
                                                        LEFT JOIN reporteairecategoria ON reporteaireevaluacioncategorias.reporteairecategoria_id = reporteairecategoria.id 
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = '.$categoria->proyecto_id.' 
                                                        AND reporteaireevaluacion.registro_id = '.$categoria->registro_id.' 
                                                        AND reporteaireevaluacioncategorias.reporteairecategoria_id = '.$categoria->reporteairecategoria_id.'
                                                    ORDER BY
                                                        reporteaireevaluacion.reporteaireevaluacion_punto ASC,
                                                        reporteairecategoria.reporteairecategoria_nombre ASC
                                                ) AS TABLA
                                            ORDER BY
                                                cumplimiento ASC
                                            LIMIT 1');


                        foreach ($matriz as $key2 => $value)
                        {
                            $numero_registro += 1;


                            $dato["matriz"] .= '<tr>
                                                    <td>'.$numero_registro.'</td>
                                                    <td>'.$value->catsubdireccion_nombre.'</td>
                                                    <td>'.$value->gerencia_activo.'</td>
                                                    <td>'.$value->reporteairearea_instalacion.'</td>
                                                    <td>'.$value->reporteaireevaluacioncategorias_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacioncategorias_ficha.'</td>
                                                    <td>'.$value->reporteairecategoria_nombre.'</td>
                                                    <td>'.$value->reporteaireevaluacion_temperatura.'</td>
                                                    <td>'.$value->reporteaireevaluacion_velocidad.'</td>
                                                    <td>'.$value->reporteaireevaluacion_humedad.'</td>
                                                    <td>'.$value->reporteaireevaluacion_co.'</td>
                                                    <td>'.$value->reporteaireevaluacion_co2.'</td>
                                                    <td><b>CT:</b> '.$value->reporteaireevaluacion_ct.'<br><b>CTMA:</b> '.$value->reporteaireevaluacion_ctma.'<br><b>Hongos:</b> '.$value->reporteaireevaluacion_hongos.'<br><b>Levaduras:</b> '.$value->reporteaireevaluacion_levaduras.'</td>
                                                </tr>';
                        }
                    }
                }


                $dato["total"] = $numero_registro;
            }
            // dd($dato["matriz"]);


            // respuesta
            // $dato["data"] = $matriz;
            // $dato["total"] = count($matriz);
            
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
     * @param  int $areas_poe
     * @return \Illuminate\Http\Response
     */
    public function reporteairedashboard($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try
        {
            //=====================================
            // CUMPLIMIENTO NORMATIVO POR PARAMETRO


            if (($areas_poe+0) == 1)
            {
                $cumplimiento = DB::select('SELECT
                                                reporteaireevaluacion.id,
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.reporteairearea_id,
                                                reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                reportearea.reportearea_orden AS reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS ct_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS ctma_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS hongos_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS levaduras_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                ) AS temperatura_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                (
                                                    -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                ) AS velocidad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                ) AS humedad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , 1000), "<" ,""), " ", "") + 0) <= 25, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS co_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co2,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , 6000), "<" ,""), " ", "") + 0) <= 5000, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS co2_resultado 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reportearea.reportearea_orden ASC,
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC');
            }
            else
            {
                $cumplimiento = DB::select('SELECT
                                                reporteaireevaluacion.id,
                                                reporteaireevaluacion.proyecto_id,
                                                reporteaireevaluacion.registro_id,
                                                reporteaireevaluacion.reporteairearea_id,
                                                reporteairearea.reporteairearea_instalacion,
                                                reporteairearea.reporteairearea_nombre,
                                                reporteairearea.reporteairearea_numorden,
                                                reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                reporteaireevaluacion.reporteaireevaluacion_punto,
                                                reporteaireevaluacion.reporteaireevaluacion_ct,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ct,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS ct_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, "<", "˂"), ">", "˃") AS reporteaireevaluacion_ctma,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS ctma_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, "<", "˂"), ">", "˃") AS reporteaireevaluacion_hongos,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS hongos_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, "<", "˂"), ">", "˃") AS reporteaireevaluacion_levaduras,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , 1000), "<" ,""), " ", "") + 0) <= 500, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS levaduras_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                ) AS temperatura_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                (
                                                    -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                ) AS velocidad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                (
                                                    IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                ) AS humedad_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , 1000), "<" ,""), " ", "") + 0) <= 25, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS co_resultado,
                                                reporteaireevaluacion.reporteaireevaluacion_co2,
                                                -- REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, "<", "˂"), ">", "˃") AS reporteaireevaluacion_co2,
                                                (
                                                    -- IF((REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , 6000), "<" ,""), " ", "") + 0) <= 5000, "Dentro de norma", "Fuera de norma")
                                                    IF(
                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) >= 0
                                                        , IF(
                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                            , 1
                                                            , 0
                                                        )
                                                        , 0
                                                    )
                                                ) AS co2_resultado 
                                            FROM
                                                reporteaireevaluacion
                                                LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                            WHERE
                                                reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                            ORDER BY
                                                reporteairearea.reporteairearea_numorden ASC,
                                                reporteaireevaluacion.reporteaireevaluacion_punto ASC');
            }


            $dato["dashboard_parametros"] = ''; $bioaerosoles = 0; $bioaerosoles_color = ''; $temperatura = 0; $temperatura_color = ''; $velocidad = 0; $velocidad_color = ''; $humedad = 0; $humedad_color = ''; $monoxido = 0; $monoxido_color = ''; $dioxido = 0; $dioxido_color = '';
            if (count($cumplimiento) > 0)
            {
                foreach ($cumplimiento as $key => $value) 
                {
                    $temperatura += ($value->temperatura_resultado+0);
                    $velocidad += ($value->velocidad_resultado+0);
                    $humedad += ($value->humedad_resultado+0);
                    $monoxido += ($value->co_resultado+0);
                    $dioxido += ($value->co2_resultado+0);
                    $bioaerosoles += (($value->ct_resultado+0) + ($value->ctma_resultado+0) + ($value->hongos_resultado+0) + ($value->levaduras_resultado+0));
                }


                //--------------------


                $temperatura = round((round(($temperatura / count($cumplimiento)), 3) * 100), 1);


                if ($temperatura >= 90)
                {
                    $temperatura_color = '#8ee66b';
                }
                else if ($temperatura >= 50)
                {
                    $temperatura_color = '#ffb22b';
                }
                else
                {
                    $temperatura_color = '#fc4b6c';
                }


                //--------------------


                $velocidad = round((round(($velocidad / count($cumplimiento)), 3) * 100), 1);


                if ($velocidad >= 90)
                {
                    $velocidad_color = '#8ee66b';
                }
                else if ($velocidad >= 50)
                {
                    $velocidad_color = '#ffb22b';
                }
                else
                {
                    $velocidad_color = '#fc4b6c';
                }


                //--------------------


                $humedad = round((round(($humedad / count($cumplimiento)), 3) * 100), 1);


                if ($humedad >= 90)
                {
                    $humedad_color = '#8ee66b';
                }
                else if ($humedad >= 50)
                {
                    $humedad_color = '#ffb22b';
                }
                else
                {
                    $humedad_color = '#fc4b6c';
                }


                //--------------------


                $monoxido = round((round(($monoxido / count($cumplimiento)), 3) * 100), 1);


                if ($monoxido >= 90)
                {
                    $monoxido_color = '#8ee66b';
                }
                else if ($monoxido >= 50)
                {
                    $monoxido_color = '#ffb22b';
                }
                else
                {
                    $monoxido_color = '#fc4b6c';
                }


                //--------------------


                $dioxido = round((round(($dioxido / count($cumplimiento)), 3) * 100), 1);


                if ($dioxido >= 90)
                {
                    $dioxido_color = '#8ee66b';
                }
                else if ($dioxido >= 50)
                {
                    $dioxido_color = '#ffb22b';
                }
                else
                {
                    $dioxido_color = '#fc4b6c';
                }


                //--------------------


                $bioaerosoles = round((round(($bioaerosoles / (4 * count($cumplimiento))), 3) * 100), 1);


                if ($bioaerosoles >= 90)
                {
                    $bioaerosoles_color = '#8ee66b';
                }
                else if ($bioaerosoles >= 50)
                {
                    $bioaerosoles_color = '#ffb22b';
                }
                else
                {
                    $bioaerosoles_color = '#fc4b6c';
                }


                //--------------------


                $dato["dashboard_parametros"] .= '<div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Temperatura del aire <span class="pull-right">'.$temperatura.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$temperatura.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Velocidad del aire <span class="pull-right">'.$velocidad.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$velocidad.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Humedad relativa <span class="pull-right">'.$humedad.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$humedad.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Monóxido de carbono (CO) <span class="pull-right">'.$monoxido.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$monoxido.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Dióxido de carbono (CO<sub>2</sub>) <span class="pull-right">'.$dioxido.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$dioxido.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display: inline-block; text-align: left;">
                                                        <h6 class="m-t-30" style="margin: 0px; font-size:0.8vw;">Bioaerosoles (CT, CTMA, Hongos, Levaduras) <span class="pull-right">'.$bioaerosoles.'%</span></h6>
                                                        <div class="progress" style="margin-bottom: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: '.$bioaerosoles.'%; height: 10px; background: #8ee66b;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>';


                $dato["dashboard_puntos"] = (count($cumplimiento)+0);
            }
            else
            {
                $dato["dashboard_puntos"] = 0;
                $dato["dashboard_parametros"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">No se encontrarón parámetros evaluados.</span>';
            }


            //=====================================
            // AREAS CRITICAS POR PARAMETRO


            if (($areas_poe+0) == 1)
            {
                $total_instalaciones = DB::select('SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reportearea.reportearea_instalacion AS reporteairearea_instalacion
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    GROUP BY
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reportearea.reportearea_instalacion');
            }
            else
            {
                $total_instalaciones = DB::select('SELECT
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteairearea.reporteairearea_instalacion
                                                    FROM
                                                        reporteaireevaluacion
                                                        LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                    WHERE
                                                        reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                    GROUP BY
                                                        reporteaireevaluacion.proyecto_id,
                                                        reporteaireevaluacion.registro_id,
                                                        reporteairearea.reporteairearea_instalacion');
            }


            //-----------------------------------


            if (($areas_poe+0) == 1)
            {
                $areas_criticas = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.reporteairearea_instalacion,
                                                    TABLA.reporteairearea_id,
                                                    TABLA.reporteairearea_nombre,
                                                    COUNT(TABLA.reporteaireevaluacion_punto) AS puntos,
                                                    SUM(TABLA.temperatura_resultado) AS t_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.temperatura_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS t_resultado,
                                                    SUM(TABLA.velocidad_resultado) AS v_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.velocidad_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS v_resultado,
                                                    SUM(TABLA.humedad_resultado) AS h_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.humedad_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS h_resultado,
                                                    SUM(TABLA.co_resultado) AS co_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.co_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS co_resultado,
                                                    SUM(TABLA.co2_resultado) AS co2_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.co2_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS co2_resultado,
                                                    SUM(TABLA.bioaerosoles_resultado) AS bio_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.bioaerosoles_resultado) / (COUNT(TABLA.reporteaireevaluacion_punto) * 4)), 3) * 100), 0) AS bio_resultado
                                                FROM
                                                    (
                                                        SELECT
                                                            reporteaireevaluacion.proyecto_id,
                                                            reporteaireevaluacion.registro_id,
                                                            reporteaireevaluacion.id,
                                                            reportearea.reportearea_instalacion AS reporteairearea_instalacion,
                                                            reporteaireevaluacion.reporteairearea_id,
                                                            reportearea.reportearea_nombre AS reporteairearea_nombre,
                                                            reportearea.reportearea_orden AS reporteairearea_numorden,
                                                            reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                            reporteaireevaluacion.reporteaireevaluacion_punto,
                                                            reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                            (
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                            ) AS temperatura_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                            reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                            (
                                                                -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                            ) AS velocidad_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                            (
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                            ) AS humedad_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_co,
                                                            (
                                                                IF(
                                                                    CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                    , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                    , 0
                                                                )
                                                            ) AS co_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_co2,
                                                            (
                                                                IF(
                                                                    CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                    , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                    , 0
                                                                )
                                                            ) AS co2_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_ct,
                                                            reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                            reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                            reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                            (
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                            ) AS bioaerosoles_resultado
                                                        FROM
                                                            reporteaireevaluacion
                                                            LEFT JOIN reportearea ON reporteaireevaluacion.reporteairearea_id = reportearea.id
                                                        WHERE
                                                            reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                            AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        ORDER BY
                                                            reportearea.reportearea_orden ASC,
                                                            reporteaireevaluacion.reporteaireevaluacion_punto ASC
                                                    ) AS TABLA
                                                GROUP BY
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.reporteairearea_instalacion,
                                                    TABLA.reporteairearea_id,
                                                    TABLA.reporteairearea_nombre');
            }
            else
            {
                $areas_criticas = DB::select('SELECT
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.reporteairearea_instalacion,
                                                    TABLA.reporteairearea_id,
                                                    TABLA.reporteairearea_nombre,
                                                    COUNT(TABLA.reporteaireevaluacion_punto) AS puntos,
                                                    SUM(TABLA.temperatura_resultado) AS t_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.temperatura_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS t_resultado,
                                                    SUM(TABLA.velocidad_resultado) AS v_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.velocidad_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS v_resultado,
                                                    SUM(TABLA.humedad_resultado) AS h_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.humedad_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS h_resultado,
                                                    SUM(TABLA.co_resultado) AS co_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.co_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS co_resultado,
                                                    SUM(TABLA.co2_resultado) AS co2_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.co2_resultado) / COUNT(TABLA.reporteaireevaluacion_punto)), 3) * 100), 0) AS co2_resultado,
                                                    SUM(TABLA.bioaerosoles_resultado) AS bio_cumplimiento, 
                                                    ROUND((ROUND((SUM(TABLA.bioaerosoles_resultado) / (COUNT(TABLA.reporteaireevaluacion_punto) * 4)), 3) * 100), 0) AS bio_resultado
                                                FROM
                                                    (
                                                        SELECT
                                                            reporteaireevaluacion.proyecto_id,
                                                            reporteaireevaluacion.registro_id,
                                                            reporteaireevaluacion.id,
                                                            reporteairearea.reporteairearea_instalacion,
                                                            reporteaireevaluacion.reporteairearea_id,
                                                            reporteairearea.reporteairearea_nombre,
                                                            reporteairearea.reporteairearea_numorden,
                                                            reporteaireevaluacion.reporteaireevaluacion_ubicacion,
                                                            reporteaireevaluacion.reporteaireevaluacion_punto,
                                                            reporteaireevaluacion.reporteaireevaluacion_temperatura,
                                                            (
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) >= 22 AND (reporteaireevaluacion.reporteaireevaluacion_temperatura + 0) <= 24.5, 1, 0)
                                                            ) AS temperatura_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_velocidad,
                                                            reporteaireevaluacion.reporteaireevaluacion_velocidadlimite,
                                                            (
                                                                -- IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) >= 0.15 AND (reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= 0.25, 1, 0)
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_velocidad + 0) <= (reporteaireevaluacion.reporteaireevaluacion_velocidadlimite + 0), 1, 0)
                                                            ) AS velocidad_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_humedad,
                                                            (
                                                                IF((reporteaireevaluacion.reporteaireevaluacion_humedad + 0) >= 20 AND (reporteaireevaluacion.reporteaireevaluacion_humedad + 0) <= 60, 1, 0)
                                                            ) AS humedad_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_co,
                                                            (
                                                                IF(
                                                                    CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                    , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co, ">" , ""), "<" ,""), " ", "") + 0) <= 25
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                    , 0
                                                                )
                                                            ) AS co_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_co2,
                                                            (
                                                                IF(
                                                                    CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                    , IF(
                                                                        (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_co2, ">" , ""), "<" ,""), " ", "") + 0) <= 5000
                                                                        , 1
                                                                        , 0
                                                                    )
                                                                    , 0
                                                                )
                                                            ) AS co2_resultado,
                                                            reporteaireevaluacion.reporteaireevaluacion_ct,
                                                            reporteaireevaluacion.reporteaireevaluacion_ctma,
                                                            reporteaireevaluacion.reporteaireevaluacion_hongos,
                                                            reporteaireevaluacion.reporteaireevaluacion_levaduras,
                                                            (
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ct, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_ctma, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_hongos, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                                +
                                                                (
                                                                    IF(
                                                                        CONVERT(REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", ""), UNSIGNED INTEGER) > 0
                                                                        , IF(
                                                                            (REPLACE(REPLACE(REPLACE(reporteaireevaluacion.reporteaireevaluacion_levaduras, ">" , ""), "<" ,""), " ", "") + 0) <= 500
                                                                            , 1
                                                                            , 0
                                                                        )
                                                                        , 0
                                                                    )
                                                                )
                                                            ) AS bioaerosoles_resultado
                                                        FROM
                                                            reporteaireevaluacion
                                                            LEFT JOIN reporteairearea ON reporteaireevaluacion.reporteairearea_id = reporteairearea.id
                                                        WHERE
                                                            reporteaireevaluacion.proyecto_id = '.$proyecto_id.' 
                                                            AND reporteaireevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        ORDER BY
                                                            reporteairearea.reporteairearea_numorden ASC,
                                                            reporteaireevaluacion.reporteaireevaluacion_punto ASC
                                                    ) AS TABLA
                                                GROUP BY
                                                    TABLA.proyecto_id,
                                                    TABLA.registro_id,
                                                    TABLA.reporteairearea_instalacion,
                                                    TABLA.reporteairearea_id,
                                                    TABLA.reporteairearea_nombre');
            }


            $dato["dashboard_temperatura"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_velocidad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_humedad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_co"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_co2"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_bioaerosoles"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';


            if (count($areas_criticas) > 0)
            {
                $dato["dashboard_temperatura"] = NULL;
                $dato["dashboard_velocidad"] = NULL;
                $dato["dashboard_humedad"] = NULL;
                $dato["dashboard_co"] = NULL;
                $dato["dashboard_co2"] = NULL;
                $dato["dashboard_bioaerosoles"] = NULL;

                $instalacion1 = 'XXXXX';
                $instalacion2 = 'XXXXX';
                $instalacion3 = 'XXXXX';
                $instalacion4 = 'XXXXX';
                $instalacion5 = 'XXXXX';
                $instalacion6 = 'XXXXX';

                foreach ($areas_criticas as $key => $value)
                {
                    if (($value->t_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion1 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_temperatura"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion1 = $value->reporteairearea_instalacion;
                        }
                        
                        $dato["dashboard_temperatura"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->t_resultado.'%</b>)<br>';
                    }


                    if (($value->v_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion2 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_velocidad"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion2 = $value->reporteairearea_instalacion;
                        }

                        $dato["dashboard_velocidad"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->v_resultado.'%</b>)<br>';
                    }


                    if (($value->h_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion3 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_humedad"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion3 = $value->reporteairearea_instalacion;
                        }

                        $dato["dashboard_humedad"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->h_resultado.'%</b>)<br>';
                    }


                    if (($value->co_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion4 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_co"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion4 = $value->reporteairearea_instalacion;
                        }

                        $dato["dashboard_co"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->co_resultado.'%</b>)<br>';
                    }


                    if (($value->co2_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion5 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_co2"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion5 = $value->reporteairearea_instalacion;
                        }

                        $dato["dashboard_co2"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->co2_resultado.'%</b>)<br>';
                    }


                    if (($value->bio_resultado+0) < 100)
                    {
                        if (count($total_instalaciones) > 1 && $instalacion6 != $value->reporteairearea_instalacion)
                        {
                            $dato["dashboard_bioaerosoles"] .= '<b style="font-size: 0.6vw; color: #0BACDB;">'.$value->reporteairearea_instalacion.'</b><br>';
                            $instalacion6 = $value->reporteairearea_instalacion;
                        }

                        $dato["dashboard_bioaerosoles"] .= '● '.$value->reporteairearea_nombre.' ('.$value->puntos.' puntos - <b>'.$value->bio_resultado.'%</b>)<br>';
                    }
                }


                if (!$dato["dashboard_temperatura"])
                {
                    $dato["dashboard_temperatura"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }


                if (!$dato["dashboard_velocidad"])
                {
                    $dato["dashboard_velocidad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }


                if (!$dato["dashboard_humedad"])
                {
                    $dato["dashboard_humedad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }


                if (!$dato["dashboard_co"])
                {
                    $dato["dashboard_co"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }


                if (!$dato["dashboard_co2"])
                {
                    $dato["dashboard_co2"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }


                if (!$dato["dashboard_bioaerosoles"])
                {
                    $dato["dashboard_bioaerosoles"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
                }
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
                                                AND reporterecomendaciones.agente_nombre LIKE "%Aire%"');


            $dato['dashboard_recomendaciones'] = 0;
            if (count($recomendaciones) > 0)
            {
                $dato['dashboard_recomendaciones'] = $recomendaciones[0]->totalrecomendaciones;
            }

            
            //=====================================


            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["dashboard_parametros"] = 'Error al consultar los parámetros evaluados.';
            $dato["dashboard_puntos"] = 0;
            $dato["dashboard_temperatura"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_velocidad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_humedad"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_co"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_co2"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato["dashboard_bioaerosoles"] = '<span style="font-size: 1.1vw; font-weight: 600; color: #000000;">0</span>';
            $dato['dashboard_recomendaciones'] = 0;
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
    public function reporteairedashboardgraficas(Request $request)
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
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$request->reporteregistro_id.'/dashboard/dashboard_grafica.jpg'; // GRAFICA


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
    public function reporteairerecomendacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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


                    $value->descripcion = '<div class="row">
                                                <div class="col-12">
                                                    <label>Tipo recomendación</label>
                                                    <select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>
                                                        <option value=""></option>
                                                        <option value="Preventiva" '.$preventiva.'>Preventiva</option>
                                                        <option value="Correctiva" '.$correctiva.'>Correctiva</option>
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
     * @param int $reporteregistro_id
     * @param int $responsabledoc_tipo
     * @param int $responsabledoc_opcion
     * @return \Illuminate\Http\Response
    */
    public function reporteaireresponsabledocumento($reporteregistro_id, $responsabledoc_tipo, $responsabledoc_opcion)
    {
        $reporte = reporteaireModel::findOrFail($reporteregistro_id);

        if ($responsabledoc_tipo == 1)
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reporteaire_responsable1documento);
            }
            else
            {
                return Storage::download($reporte->reporteaire_responsable1documento);
            }
        }
        else
        {
            if ($responsabledoc_opcion == 0)
            {
                return Storage::response($reporte->reporteaire_responsable2documento);
            }
            else
            {
                return Storage::download($reporte->reporteaire_responsable2documento);
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
    public function reporteaireplanostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
    public function reporteaireequipoutilizadotabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try
        {
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
                                            AND proyectoproveedores.catprueba_id = 8 -- Aire ------------------------------
                                        ORDER BY
                                            proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                            proyectoproveedores.catprueba_id ASC
                                        LIMIT 1');

            $where_condicion = '';
            if (count($proveedor) > 0)
            {
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
                                        equipo.equipo_CertificadoPDF,
                                        equipo.equipo_cartaPDF,
                                        IFNULL((
                                            SELECT
                                                IF(IFNULL(reporteequiposutilizados.equipo_id, ""), "checked" , "")
                                            FROM
                                                reporteequiposutilizados
                                            WHERE
                                                reporteequiposutilizados.proyecto_id = proyectoequiposactual.proyecto_id
                                                AND reporteequiposutilizados.registro_id = '.$reporteregistro_id.' 
                                                AND reporteequiposutilizados.agente_nombre = "'.$agente_nombre.'"
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
                                                AND reporteequiposutilizados.registro_id = '.$reporteregistro_id.' 
                                                AND reporteequiposutilizados.agente_nombre = "'.$agente_nombre.'"
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
                                                AND reporteequiposutilizados.registro_id = '.$reporteregistro_id.' 
                                                AND reporteequiposutilizados.agente_nombre = "'.$agente_nombre.'"
                                                AND reporteequiposutilizados.equipo_id = proyectoequiposactual.equipo_id
                                            LIMIT 1
                                        ), NULL) AS id
                                    FROM
                                        proyectoequiposactual
                                        LEFT JOIN proveedor ON proyectoequiposactual.proveedor_id = proveedor.id
                                        LEFT JOIN equipo ON proyectoequiposactual.equipo_id = equipo.id
                                    WHERE
                                        proyectoequiposactual.proyecto_id = '.$proyecto_id.' 
                                        '.$where_condicion.' 
                                    ORDER BY
                                        equipo.equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie');


            $total_activos = 0;
            $numero_registro = 0;
            foreach ($equipos as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;


                $value->checkbox = '<div class="switch">
                                        <label>
                                            <input type="checkbox" class="equipoutilizado_checkbox" name="equipoutilizado_checkbox[]" value="'.$value->equipo_id.'" '.$value->checked.' onchange="activa_checkboxcarta(this, '.$value->equipo_id.');";>
                                            <span class="lever switch-col-light-blue"></span>
                                        </label>
                                    </div>';


                $value->equipo = '<span class="'.$value->vigencia_color.'">'.$value->equipo_Descripcion.'</span><br><small class="'.$value->vigencia_color.'">'.$value->proveedor_NombreComercial.'</small>';
                

                $value->marca_modelo_serie = '<span class="'.$value->vigencia_color.'">'.$value->equipo_Marca.'<br>'.$value->equipo_Modelo.'<br>'.$value->equipo_Serie.'</span>';


                $value->vigencia = '<span class="'.$value->vigencia_color.'">'.$value->vigencia_texto.'</span>';


                if ($value->equipo_CertificadoPDF)
                {
                    $value->certificado = '<button type="button" class="btn btn-info waves-effect btn-circle" data-toggle="tooltip" title="Mostrar certificado"><i class="fa fa-file-pdf-o fa-2x"></i></button>';
                }
                else
                {
                    $value->certificado = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="N/A certificado"><i class="fa fa-ban fa-2x"></i></button>';
                }


                //---------------------------


                if ($value->equipo_cartaPDF)
                {
                    $checkedcarta_disabled = 'disabled';
                    if ($value->checked)
                    {
                        $checkedcarta_disabled = '';
                    }


                    $checked_carta = '';
                    if ($value->cartacalibracion)
                    {
                        $checked_carta = 'checked';
                    }


                    $value->checkbox_carta = '<div class="switch">
                                                    <label>
                                                        <input type="checkbox" id="equipoutilizado_checkboxcarta_'.$value->equipo_id.'" name="equipoutilizado_checkboxcarta_'.$value->equipo_id.'" value="'.$value->equipo_id.'" '.$checkedcarta_disabled.' '.$checked_carta.'/>
                                                        <span class="lever switch-col-light-green"></span>
                                                    </label>
                                                </div>';


                    $value->carta = '<button type="button" class="btn btn-success waves-effect btn-circle" data-toggle="tooltip" title="Mostrar carta">
                                            <i class="fa fa-file-pdf-o fa-2x"></i>
                                        </button>';
                }
                else
                {
                    $value->checkbox_carta = 'N/A';
                    $value->carta = 'N/A';
                }


                // VERIFICAR SI HAY EQUIPOS SELECCIONADOS
                if ($value->checked)
                {
                    $total_activos += 1;
                }
            }

            // respuesta
            $dato['data'] = $equipos;
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
    public function reporteaireanexosresultadostabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                            LIMIT 1
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
    public function reporteaireanexosacreditacionestabla($proyecto_id, $reporteregistro_id, $agente_nombre)
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
                                                        AND proyectoproveedores.catprueba_id = 8 
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
     * @param int $proyecto_id
     * @param int $reporteregistro_id
     * @param $agente_nombre
     * @return \Illuminate\Http\Response
    */
    public function reporteairenotasstpstabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 8)
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


            $notas_stps = DB::select('SELECT
                                        reportenotas.id,
                                        reportenotas.proyecto_id,
                                        reportenotas.registro_id,
                                        reportenotas.agente_id,
                                        reportenotas.agente_nombre,
                                        reportenotas.reportenotas_tipo,
                                        reportenotas.reportenotas_descripcion 
                                    FROM
                                        reportenotas
                                    WHERE
                                        reportenotas.proyecto_id = '.$proyecto_id.' 
                                        AND reportenotas.registro_id = '.$reporteregistro_id.' 
                                        AND reportenotas.agente_id = 8
                                        AND reportenotas.reportenotas_tipo = 1
                                    ORDER BY
                                        reportenotas.id ASC');

            
            $numero_registro = 0;
            foreach ($notas_stps as $key => $value) 
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


            //==========================================


            // respuesta
            $dato["data"] = $notas_stps;
            $dato["total"] = count($notas_stps);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = NULL;
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
    public function reporteairenotasematabla($proyecto_id, $reporteregistro_id, $agente_nombre)
    {
        try
        {
            $revision = reporterevisionesModel::where('proyecto_id', $proyecto_id)
                                                ->where('agente_id', 8)
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


            $notas_ema = DB::select('SELECT
                                        reportenotas.id,
                                        reportenotas.proyecto_id,
                                        reportenotas.registro_id,
                                        reportenotas.agente_id,
                                        reportenotas.agente_nombre,
                                        reportenotas.reportenotas_tipo,
                                        reportenotas.reportenotas_descripcion 
                                    FROM
                                        reportenotas
                                    WHERE
                                        reportenotas.proyecto_id = '.$proyecto_id.' 
                                        AND reportenotas.registro_id = '.$reporteregistro_id.' 
                                        AND reportenotas.agente_id = 8
                                        AND reportenotas.reportenotas_tipo = 2
                                    ORDER BY
                                        reportenotas.id ASC');

            
            $numero_registro = 0;
            foreach ($notas_ema as $key => $value) 
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


            //==========================================


            // respuesta
            $dato["data"] = $notas_ema;
            $dato["total"] = count($notas_ema);
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato['data'] = NULL;
            $dato["total"] = 0;
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $reportenotas_id
     * @return \Illuminate\Http\Response
     */
    public function reporteairenotaseliminar($reportenotas_id)
    {
        try
        {
            $nota = reportenotasModel::where('id', $reportenotas_id)->delete();

            // respuesta
            $dato["msj"] = 'Nota aclaratoria eliminada correctamente';
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
     * @return \Illuminate\Http\Response
     */
    public function reporteairerevisionestabla($proyecto_id)
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
                                            AND reporterevisiones.agente_id = 8
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
    public function reporteairerevisionconcluir($reporte_id)
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

    /*
    public function reporteairerevisionnueva(Request $request)
    {
        try
        {
            // dd($request->all());


            // OBTENER ULTIMA REVISION
            // -------------------------------------------------


            $revision = DB::select('SELECT
                                        reporteaire.id,
                                        reporteaire.proyecto_id,
                                        reporteaire.reporteaire_revision 
                                    FROM
                                        reporteaire 
                                    WHERE
                                        reporteaire.proyecto_id = '.$request->proyecto_id.' 
                                    ORDER BY
                                        reporteaire.reporteaire_revision DESC
                                    LIMIT 1');


            // CLONAR REGISTRO REPORTE
            // -------------------------------------------------


            $revisionfinal  = reporteaireModel::findOrFail($revision[0]->id);

            DB::statement('ALTER TABLE reporteaire AUTO_INCREMENT = 1;');

            // $revisionnueva = $revisionfinal->replicate();
            $revisionnueva = $revisionfinal->replicate()->fill([
                  'reporteaire_revision' => ($revision[0]->reporteaire_revision + 1)
                , 'reporteaire_concluido' => 0
                , 'reporteaire_concluidonombre' => NULL
                , 'reporteaire_concluidofecha' => NULL
                , 'reporteaire_cancelado' => 0
                , 'reporteaire_canceladonombre' => NULL
                , 'reporteaire_canceladofecha' => NULL
                , 'reporteaire_canceladoobservacion' => NULL
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
            

            // CLONAR REGISTROS TABLA EQUIPOS UTILIZADOS
            // -------------------------------------------------


            $equipos_historial = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('agente_nombre', 'LIKE', '%'.$request->agente_nombre.'%')
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


            // CLONAR REGISTROS TABLA NOTAS ACLARATORIAS (STPS y EMA)
            // -------------------------------------------------


            $notas_historial = reportenotasModel::where('proyecto_id', $request->proyecto_id)
                                                ->where('registro_id', $revision[0]->id)
                                                ->where('agente_nombre', 'LIKE', '%'.$request->agente_nombre.'%')
                                                ->get();

            DB::statement('ALTER TABLE reportenotas AUTO_INCREMENT = 1;');
            foreach ($notas_historial as $key => $value)
            {                
                $nota = $value->replicate()->fill([
                    'registro_id' => $revisionnueva->id
                ]);

                $nota->save();
            }


            // CLONAR REGISTROS TABLA CATEGORÍAS
            // -------------------------------------------------


            $categorias_historial = reporteairecategoriaModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('registro_id', $revision[0]->id)
                                                                ->get();


            $categorias_nuevosid = array();
            DB::statement('ALTER TABLE reporteairecategoria AUTO_INCREMENT = 1;');
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

            
            $areas_historial = reporteaireareaModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('registro_id', $revision[0]->id)
                                                    ->get();

            $areas_nuevosid = array();
            DB::statement('ALTER TABLE reporteairearea AUTO_INCREMENT = 1;');
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
                                                reporteairearea.proyecto_id,
                                                reporteairearea.registro_id,
                                                reporteaireareacategoria.id,
                                                reporteaireareacategoria.reporteairearea_id,
                                                reporteaireareacategoria.reporteairecategoria_id,
                                                reporteaireareacategoria.reporteaireareacategoria_total,
                                                reporteaireareacategoria.reporteaireareacategoria_actividades 
                                            FROM
                                                reporteaireareacategoria
                                                LEFT JOIN reporteairearea ON reporteaireareacategoria.reporteairearea_id = reporteairearea.id
                                            WHERE
                                                reporteairearea.proyecto_id = '.$request->proyecto_id.' 
                                                AND reporteairearea.registro_id = '.$revision[0]->id);

            DB::statement('ALTER TABLE reporteaireareacategoria AUTO_INCREMENT = 1;');
            foreach ($areacategorias as $key => $value)
            {
                $registro = reporteaireareacategoriaModel::create([
                      'reporteairearea_id' => $areas_nuevosid['id_'.$value->reporteairearea_id]
                    , 'reporteairecategoria_id' => $categorias_nuevosid['id_'.$value->reporteairecategoria_id]
                    , 'reporteaireareacategoria_total' => $value->reporteaireareacategoria_total
                    , 'reporteaireareacategoria_actividades' => $value->reporteaireareacategoria_actividades
                ]);
            }
            // dd($areacategorias);


            // CLONAR REGISTROS TABLA EVALUACION
            // -------------------------------------------------


            $evaluacion = reporteaireevaluacionModel::where('proyecto_id', $request->proyecto_id)
                                                    ->where('registro_id', $revision[0]->id)
                                                    ->get();


            DB::statement('ALTER TABLE reporteaireevaluacion AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE reporteaireevaluacioncategorias AUTO_INCREMENT = 1;');

            foreach ($evaluacion as $key => $value)
            {
                $punto = $value->replicate()->fill([
                      'registro_id' => $revisionnueva->id
                    , 'reporteairearea_id' => $areas_nuevosid['id_'.$value->reporteairearea_id]
                ]);

                $punto->save();


                // CLONAR REGISTROS TABLA EVALUACION CATEGORIAS
                $evaluacion_categorias = reporteaireevaluacioncategoriasModel::where('reporteaireevaluacion_id', $value->id)->get();
                foreach ($evaluacion_categorias as $key2 => $value2)
                {
                    $categoria = $value2->replicate()->fill([
                        'reporteaireevaluacion_id' => $punto->id
                        , 'reporteairecategoria_id' => $categorias_nuevosid['id_'.$value2->reporteairecategoria_id]
                    ]);

                    $categoria->save();
                }
            }
            // dd($evaluacion);


            // GUARDAR GRAFICAS DASHBOARD
            // -------------------------------------------------


            if ($request->dashboard_graficas)
            {
                // Codificar imagen recibida como tipo base64
                $imagen_recibida = explode(',', $request->dashboard_graficas); //Archivo foto tipo base64
                $imagen_nueva = base64_decode($imagen_recibida[1]);


                // Ruta destino archivo
                $destinoPath = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$revision[0]->id.'/dashboard/dashboard_grafica.jpg'; // GRAFICA


                // Guardar Foto
                Storage::put($destinoPath, $imagen_nueva); // Guardar en storage
                // file_put_contents(public_path('/imagen.jpg'), $imagen_nueva); // Guardar en public
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
                    'reporteaire_ubicacionfoto' => $carpetaarchivos_destino.'/ubicacionfoto/ubicacionfoto.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                    'reporteaire_ubicacionfoto' => NULL
                ]);
            }


            if (Storage::exists($carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'))
            {
                $revisionnueva->update([
                      'reporteaire_responsable1documento' => $carpetaarchivos_destino.'/responsables informe/responsable1_doc.jpg'
                    , 'reporteaire_responsable2documento' => $carpetaarchivos_destino.'/responsables informe/responsable2_doc.jpg'
                ]);
            }
            else
            {
                $revisionnueva->update([
                      'reporteaire_responsable1documento' => NULL
                    , 'reporteaire_responsable2documento' => NULL
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
                $reporte = reporteaireModel::findOrFail($request->reporteregistro_id);

                $dato["reporteregistro_id"] = $reporte->id;

                $reporte->update([
                      'reporteaire_instalacion' => $request->reporte_instalacion
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


                if (($revision->reporterevisiones_concluido == 1 || $revision->reporterevisiones_cancelado == 1) && ($request->opcion+0) != 20) // Valida disponibilidad de esta version (20 CANCELACION REVISION)
                {
                    // respuesta
                    $dato["msj"] = 'Informe de '.$request->agente_nombre.' NO disponible para edición';
                    return response()->json($dato);
                }
            }
            else
            {
                DB::statement('ALTER TABLE reporteaire AUTO_INCREMENT = 1;');

                if (!$request->catactivo_id)
                {
                    $request['catactivo_id'] = 0; // es es modo cliente y viene en null se pone en cero
                }

                $reporte = reporteaireModel::create([
                      'proyecto_id' => $request->proyecto_id
                    , 'agente_id' => $request->agente_id
                    , 'agente_nombre' => $request->agente_nombre
                    , 'catactivo_id' => $request->catactivo_id
                    , 'reporteaire_revision' => 0
                    , 'reporteaire_instalacion' => $request->reporte_instalacion
                    , 'reporteaire_catregion_activo' => 1
                    , 'reporteaire_catsubdireccion_activo' => 1
                    , 'reporteaire_catgerencia_activo' => 1
                    , 'reporteaire_catactivo_activo' => 1
                    , 'reporteaire_concluido' => 0
                    , 'reporteaire_cancelado' => 0
                ]);


                //--------------------------------------


                // ASIGNAR CATEGORIAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reporteairecategoria
                                SET 
                                    registro_id = '.$reporte->id.'
                                WHERE 
                                    proyecto_id = '.$request->proyecto_id.'
                                    AND IFNULL(registro_id, "") = "";');

                // ASIGNAR AREAS AL REGISTRO ACTUAL
                DB::statement('UPDATE 
                                    reporteairearea
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
                      'reporteaire_catregion_activo' => $catregion_activo
                    , 'reporteaire_catsubdireccion_activo' => $catsubdireccion_activo
                    , 'reporteaire_catgerencia_activo' => $catgerencia_activo
                    , 'reporteaire_catactivo_activo' => $catactivo_activo
                    , 'reporteaire_instalacion' => $request->reporte_instalacion
                    , 'reporteaire_fecha' => $request->reporte_fecha
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
                    'reporteaire_introduccion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_introduccion)
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
                    'reporteaire_objetivogeneral' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivogeneral)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // OBJETIVOS  ESPECIFICOS
            if (($request->opcion+0) == 4)
            {
                $reporte->update([
                    'reporteaire_objetivoespecifico' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_objetivoespecifico)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.1
            if (($request->opcion+0) == 5)
            {
                $reporte->update([
                    'reporteaire_metodologia_4_1' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_1)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // METODOLOGIA PUNTO 4.2
            if (($request->opcion+0) == 6)
            {
                $reporte->update([
                    'reporteaire_metodologia_4_2' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_metodologia_4_2)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // UBICACION
            if (($request->opcion+0) == 7)
            {
                $reporte->update([
                    'reporteaire_ubicacioninstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_ubicacioninstalacion)
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
                        'reporteaire_ubicacionfoto' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PROCESO INSTALACION
            if (($request->opcion+0) == 8)
            {
                $reporte->update([
                    'reporteaire_procesoinstalacion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_procesoinstalacion)
                    , 'reporteaire_actividadprincipal' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_actividadprincipal)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // CATEGORIAS
            if (($request->opcion+0) == 9)
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


            // AREAS
            if (($request->opcion+0) == 10)
            {
                // dd($request->all());


                if (($request->areas_poe+0) == 1)
                {
                    $request['reportearea_ventilacionsistema'] = $request->reporteairearea_ventilacionsistema;
                    $request['reportearea_ventilacioncaracteristica'] = $request->reporteairearea_ventilacioncaracteristica;
                    $request['reportearea_ventilacioncantidad'] = $request->reporteairearea_ventilacioncantidad;


                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reporteaireareacategoriaModel::where('reporteairearea_id', $request->reportearea_id)
                                                                        ->where('reporteaireareacategoria_poe', $request->reporteregistro_id)
                                                                        ->delete();


                    if ($request->checkbox_categoria_id)
                    {
                        DB::statement('ALTER TABLE reporteaireareacategoria AUTO_INCREMENT = 1;');

                        foreach ($request->checkbox_categoria_id as $key => $value) 
                        {
                            $areacategoria = reporteaireareacategoriaModel::create([
                                  'reporteairearea_id' => $area->id
                                , 'reporteairecategoria_id' => $value
                                , 'reporteaireareacategoria_poe' => $request->reporteregistro_id
                                , 'reporteaireareacategoria_total' => $request['areacategoria_total_'.$value]
                                , 'reporteaireareacategoria_actividades' => $request['areacategoria_actividades_'.$value]
                            ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
                else
                {
                    if (($request->reportearea_id+0) == 0)
                    {
                        DB::statement('ALTER TABLE reporteairearea AUTO_INCREMENT = 1;');


                        $request['registro_id'] = $reporte->id;
                        $request['recsensorialarea_id'] = 0;
                        $area = reporteaireareaModel::create($request->all());


                        if ($request->checkbox_categoria_id)
                        {
                            DB::statement('ALTER TABLE reporteaireareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) 
                            {
                                $areacategoria = reporteaireareacategoriaModel::create([
                                      'reporteairearea_id' => $area->id
                                    , 'reporteairecategoria_id' => $value
                                    , 'reporteaireareacategoria_poe' => 0
                                    , 'reporteaireareacategoria_total' => $request['areacategoria_total_'.$value]
                                    , 'reporteaireareacategoria_actividades' => $request['areacategoria_actividades_'.$value]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos guardados correctamente';
                    }
                    else
                    {
                        $request['registro_id'] = $reporte->id;
                        $area = reporteaireareaModel::findOrFail($request->reportearea_id);
                        $area->update($request->all());


                        $eliminar_categorias = reporteaireareacategoriaModel::where('reporteairearea_id', $request->reportearea_id)
                                                                            ->where('reporteaireareacategoria_poe', 0)
                                                                            ->delete();


                        if ($request->checkbox_categoria_id)
                        {
                            DB::statement('ALTER TABLE reporteaireareacategoria AUTO_INCREMENT = 1;');

                            foreach ($request->checkbox_categoria_id as $key => $value) 
                            {
                                $areacategoria = reporteaireareacategoriaModel::create([
                                      'reporteairearea_id' => $area->id
                                    , 'reporteairecategoria_id' => $value
                                    , 'reporteaireareacategoria_poe' => 0
                                    , 'reporteaireareacategoria_total' => $request['areacategoria_total_'.$value]
                                    , 'reporteaireareacategoria_actividades' => $request['areacategoria_actividades_'.$value]
                                ]);
                            }
                        }


                        // Mensaje
                        $dato["msj"] = 'Datos modificados correctamente';
                    }
                }
            }


            // PUNTO DE EVALUACION
            if (($request->opcion+0) == 11)
            {
                // dd($request->all());
                if (($request->reporteaireevaluacion_id+0) == 0)
                {
                    DB::statement('ALTER TABLE reporteaireevaluacion AUTO_INCREMENT = 1;');


                    $request['registro_id'] = $reporte->id;
                    $punto = reporteaireevaluacionModel::create($request->all());


                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else
                {
                    $request['registro_id'] = $reporte->id;
                    $punto = reporteaireevaluacionModel::findOrFail($request->reporteaireevaluacion_id);
                    $punto->update($request->all());


                    $eliminar_categorias = reporteaireevaluacioncategoriasModel::where('reporteaireevaluacion_id', $request->reporteaireevaluacion_id)->delete();

                    
                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }


                if ($request->reporteairecategoria_id)
                {
                    foreach ($request->reporteairecategoria_id as $key => $value) 
                    {
                        $categoria = reporteaireevaluacioncategoriasModel::create([
                              'reporteaireevaluacion_id' => $punto->id
                            , 'reporteairecategoria_id' => $value
                            , 'reporteaireevaluacioncategorias_nombre' => $request['reporteaireevaluacioncategorias_nombre'][$key]
                            , 'reporteaireevaluacioncategorias_ficha' => $request['reporteaireevaluacioncategorias_ficha'][$key]
                            , 'reporteaireevaluacioncategorias_geo' => $request['reporteaireevaluacioncategorias_geo'][$key]
                        ]);
                    }
                }
            }


            // CONCLUSION
            if (($request->opcion+0) == 12)
            {
                $reporte->update([
                    'reporteaire_conclusion' => $this->datosproyectolimpiartexto($proyecto, $recsensorial, $request->reporte_conclusion)
                ]);

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // RECOMENDACIONES
            if (($request->opcion+0) == 13)
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
                        ]);
                    }

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }


                // total recomendaciones
                $recomendaciones = reporterecomendacionesModel::where('proyecto_id', $request->proyecto_id)
                                                                ->where('registro_id', $reporte->id)
                                                                ->where('agente_nombre', $request->agente_nombre)
                                                                ->get();


                $dato["dashboard_recomendaciones"] = count($recomendaciones);
            }


            // RESPONSABLES DEL INFORME
            if (($request->opcion+0) == 14)
            {
                $reporte->update([
                      'reporteaire_responsable1' => $request->reporte_responsable1
                    , 'reporteaire_responsable1cargo' => $request->reporte_responsable1cargo
                    , 'reporteaire_responsable2' => $request->reporte_responsable2
                    , 'reporteaire_responsable2cargo' => $request->reporte_responsable2cargo
                ]);


                if ($request->responsablesinforme_carpetadocumentoshistorial)
                {
                    $nuevo_destino = 'reportes/proyecto/'.$request->proyecto_id.'/'.$request->agente_nombre.'/'.$reporte->id.'/responsables informe/';
                    Storage::makeDirectory($nuevo_destino); //crear directorio

                    File::copyDirectory(storage_path('app/'.$request->responsablesinforme_carpetadocumentoshistorial), storage_path('app/'.$nuevo_destino));

                    $reporte->update([
                          'reporteaire_responsable1documento' => $nuevo_destino.'responsable1_doc.jpg'
                        , 'reporteaire_responsable2documento' => $nuevo_destino.'responsable2_doc.jpg'
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
                        'reporteaire_responsable1documento' => $destinoPath
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
                        'reporteaire_responsable2documento' => $destinoPath
                    ]);
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // PLANOS
            if (($request->opcion+0) == 15)
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


            // EQUIPO UTILIZADO
            if (($request->opcion+0) == 16)
            {
                // dd($request->all());

                if ($request->equipoutilizado_checkbox)
                {
                    $eliminar_equiposutilizados = reporteequiposutilizadosModel::where('proyecto_id', $request->proyecto_id)
                                                                                ->where('agente_nombre', $request->agente_nombre)
                                                                                ->where('registro_id', $request->reporteregistro_id)
                                                                                ->delete();


                    DB::statement('ALTER TABLE reporteequiposutilizados AUTO_INCREMENT = 1;');


                    foreach ($request->equipoutilizado_checkbox as $key => $value)
                    {
                        if ($request['equipoutilizado_checkboxcarta_'.$value])
                        {
                            $request->reporteequiposutilizados_cartacalibracion = 1;
                        }
                        else
                        {
                            $request->reporteequiposutilizados_cartacalibracion = null;
                        }


                        $equipoutilizado = reporteequiposutilizadosModel::create([
                              'proyecto_id' => $request->proyecto_id
                            , 'agente_id' => $request->agente_id
                            , 'agente_nombre' => $request->agente_nombre
                            , 'registro_id' => $reporte->id
                            , 'equipo_id' => $value
                            , 'reporteequiposutilizados_cartacalibracion' => $request->reporteequiposutilizados_cartacalibracion
                        ]);
                    }
                }

                // Mensaje
                $dato["msj"] = 'Datos guardados correctamente';
            }


            // INFORMES RESULTADOS
            if (($request->opcion+0) == 17)
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
            if (($request->opcion+0) == 18)
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


            // NOTAS ACLARATORIAS (STPS y EMA)
            if (($request->opcion+0) == 19)
            {
                if (($request->reportenotas_id+0) == 0) //NUEVO
                {
                    DB::statement('ALTER TABLE reportenotas AUTO_INCREMENT = 1;');

                    $request['registro_id'] = $reporte->id;
                    $nota = reportenotasModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                }
                else //EDITAR
                {
                    $nota = reportenotasModel::findOrFail($request->reportenotas_id);
                    $nota->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // REVISION INFORME, CANCELACION
            if (($request->opcion+0) == 20)
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
