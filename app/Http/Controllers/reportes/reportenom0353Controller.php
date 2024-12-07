<?php


namespace App\Http\Controllers\reportes;

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;

//Tablas revisiones
use App\modelos\reportes\reporterevisionesModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
use App\modelos\recsensorial\catConclusionesModel;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;

class reportenom0353Controller extends Controller
{
   
      //
      public function __construct()
      {
          $this->middleware('auth');
          // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,ApoyoTecnico,Reportes,Externo');
          $this->middleware('roles:Superusuario,Administrador,Coordinador,Psicólogo');
      }
       /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportenom035vista3($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if ($proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño del reporte de Ruido primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {
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
            $catConclusiones = catConclusionesModel::where('ACTIVO', 1)->get();


            // Vista
            return view('reportes.psico.reportenom035guia3', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'categorias_poe', 'areas_poe', 'catConclusiones'));
        }
    }

      /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $agente_id
     * @param  $agente_nombre
     * @return \Illuminate\Http\Response
     */
    public function reportepsico3datosgenerales($proyecto_id)
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


            // respuesta
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

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


                                    #Verificamos si existe registro con ese ID en la tabla de reporteruidopuntonerfrecuencias para  no duplicar los datos
                                    $total = reporteruidopuntonerfrecuenciasModel::where('reporteruidopuntoner_id', $punto->id)->count();

                                    if ($total == 0) {

                                        $frecuencias_bandasoctava = array('31.5', '63', '125', '250', '500', '1K', '2K', '4K', '8K');
                                        foreach ($frecuencias_bandasoctava as $key => $value) {
                                            $frecuencia = reporteruidopuntonerfrecuenciasModel::create([
                                                'reporteruidopuntoner_id' => $punto->id,
                                                'reporteruidopuntonerfrecuencias_orden' => ($key + 1),
                                                'reporteruidopuntonerfrecuencias_frecuencia' => $value, 
                                                'reporteruidopuntonerfrecuencias_nivel' => NULL
                                            ]);
                                        }

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
                                        'reporteruidodosisner_nombre' => is_null($rowData['F']) ? 'NA' : $rowData['F'],
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
                    'reporteruido_introduccion' => $request->reporte_introduccion,
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
                        // DB::statement('ALTER TABLE reporteruidoequipoauditivoatenuacion AUTO_INCREMENT = 1;');

                        foreach ($request->reporteruidoequipoauditivoatenuacion_bandaNRR as $key => $value) {
                            $atenuacion = reporteruidoequipoauditivoatenuacionModel::create([
                                'reporteruidoequipoauditivo_id' => $equipoauditivo->id,
                                'reporteruidoequipoauditivoatenuacion_bandaNRR' => $value,
                                'reporteruidoequipoauditivoatenuacion_bandaatenuacion' => $request['reporteruidoequipoauditivoatenuacion_bandaatenuacion'][$key]
                            ]);
                        }
                    }

                    if ($request->equipoauditivo_categoria) {
                        // DB::statement('ALTER TABLE reporteruidoequipoauditivocategorias AUTO_INCREMENT = 1;');

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
                    'reporteruidopuntoner_NRE' => $request->reporteruidobandaoctava_NRE,
                    'reporteruidobandaoctava_equipo' => $request->reporteruidobandaoctava_equipo
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
