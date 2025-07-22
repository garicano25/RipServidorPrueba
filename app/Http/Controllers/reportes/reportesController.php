<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Image;
use Illuminate\Support\Facades\Auth;

// Plugins
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Chart;
// use PhpOffice\PhpWord\Shared\Converter;

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWord\TemplateProcessor;

//----------------------------------------------------------

// Modelos
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\clientes\clienteModel;

// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;
//----------------------------------------------------------
use App\modelos\reportes\reportecategoriaModel;
use App\modelos\reportes\reporteareaModel;
use App\modelos\reportes\reporteareacategoriaModel;
//----------------------------------------------------------
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\proyecto\estatusReportesInformeModel;


// RECURSOS DE LOS INFORMES
use App\modelos\reportes\recursosPortadasInformesModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\reportes\reporteaireModel;
use App\modelos\reportes\reporteiluminacionModel;
use App\modelos\reportes\reporteruidoModel;
use App\modelos\reportes\reportetemperaturaModel;
use App\modelos\reportes\reportevibracionModel;
use App\modelos\reportes\reporteaguaModel;
use App\modelos\reportes\reportehieloModel;
use App\modelos\reportes\reportequimicosModel;
use App\modelos\reportes\reporteserviciopersonalModel;



//ZONA DE HORARIO
date_default_timezone_set('America/Mexico_City');
// date_default_timezone_set('UTC');
// setlocale(LC_ALL,"es_MX");


class reportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras,Psicólogo,Ergónomo');

         $this->middleware('asignacionUser:POE')->only('store');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportepoevista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño de la tabla POE primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {

            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================
            $total_categorias = DB::select('SELECT
                                                COUNT(reportecategoria.id) AS TOTAL
                                            FROM
                                                reportecategoria
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id);


            //INSERTAMOS LA CATEGORIAS POR PRIMERA VEZ
            if (($total_categorias[0]->TOTAL + 0) == 0) {

                $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                    ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                    ->get();


                DB::statement('ALTER TABLE reportecategoria AUTO_INCREMENT = 1;');

                $num_orden = 1;
                foreach ($recsensorial_categorias as $key => $value) {
                    $categoria = reportecategoriaModel::create([
                        'proyecto_id' => $proyecto_id,
                        'recsensorialcategoria_id' => $value->id,
                        'reportecategoria_nombre' => $value->recsensorialcategoria_nombrecategoria,
                        'reportecategoria_orden' => $num_orden,
                        'reportecategoria_horas' => $value->sumaHorasJornada
                    ]);

                    $num_orden++;
                }
            } else { // VALIDAMOS AQUELLAS CATEGORIAS QUE NO ESTAN AGREGADAS Y LAS AGREGAMOS

                $inserciones = DB::select('CALL sp_insertar_categoriasFaltantes_g(?,?)', [$proyecto->recsensorial_id, $proyecto_id]);
            }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //==================================================
            $total_areas = DB::select('SELECT
                                            COUNT(reportearea.id) AS TOTAL
                                        FROM
                                            reportearea
                                        WHERE
                                            reportearea.proyecto_id = ' . $proyecto_id);

            if (($total_areas[0]->TOTAL + 0) == 0) {
                $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                    ->orderBy('recsensorialarea_nombre', 'ASC')
                    ->get();


                DB::statement('ALTER TABLE reportearea AUTO_INCREMENT = 1;');

                $num_orden = 1;
                foreach ($recsensorial_areas as $key => $value) {
                    $area = reporteareaModel::create([
                        'proyecto_id' => $proyecto_id,
                        'recsensorialarea_id' => $value->id,
                        'reportearea_nombre' => $value->recsensorialarea_nombre,
                        'reportearea_instalacion' => $proyecto->proyecto_clienteinstalacion,
                        'reportearea_orden' => $num_orden,
                        'reportearea_proceso' => $value->RECSENSORIALAREA_PROCESO
                    ]);

                    $num_orden++;
                }
            } else {

                $inserciones = DB::select('CALL sp_insertar_areasFaltantes_g(?,?, ?)', [$proyecto->recsensorial_id, $proyecto_id, $proyecto->proyecto_clienteinstalacion]);
            }


            //===================================================


            // $recsensorial = recsensorialModel::with(['catcontrato', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $proyecto_id)->get();


            // Vista
            return view('reportes.parametros.reportepoe', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'estatus'));
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportematrizlaboralvista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
         
        } else {

           


            //===================================================


            // $recsensorial = recsensorialModel::with(['catcontrato', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $proyecto_id)->get();


            // Vista
            return view('reportes.parametros.reportematirzlab', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'estatus'));
        }
    }



      /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportepoevistapsico($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);

        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
            return '<div style="text-align: center;">
                        <p style="font-size: 24px;">Datos incompletos</p>
                        <b style="font-size: 18px;">Para ingresar al diseño de la tabla POE primero debe completar todos los campos vacíos de la sección de datos generales del proyecto.</b>
                    </div>';
        } else {

            // COPIAR CATEGORIAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================
            $total_categorias = DB::select('SELECT
                                                COUNT(reportecategoria.id) AS TOTAL
                                            FROM
                                                reportecategoria
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id);


            //INSERTAMOS LA CATEGORIAS POR PRIMERA VEZ
            if (($total_categorias[0]->TOTAL + 0) == 0) {

                $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                    ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                    ->get();


                DB::statement('ALTER TABLE reportecategoria AUTO_INCREMENT = 1;');

                $num_orden = 1;
                foreach ($recsensorial_categorias as $key => $value) {
                    $categoria = reportecategoriaModel::create([
                        'proyecto_id' => $proyecto_id,
                        'recsensorialcategoria_id' => $value->id,
                        'reportecategoria_nombre' => $value->recsensorialcategoria_nombrecategoria,
                        'reportecategoria_orden' => $num_orden,
                        'reportecategoria_horas' => $value->sumaHorasJornada
                    ]);

                    $num_orden++;
                }
            } else { // VALIDAMOS AQUELLAS CATEGORIAS QUE NO ESTAN AGREGADAS Y LAS AGREGAMOS

                $inserciones = DB::select('CALL sp_insertar_categoriasFaltantes_g(?,?)', [$proyecto->recsensorial_id, $proyecto_id]);
            }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //==================================================
            $total_areas = DB::select('SELECT
                                            COUNT(reportearea.id) AS TOTAL
                                        FROM
                                            reportearea
                                        WHERE
                                            reportearea.proyecto_id = ' . $proyecto_id);

            if (($total_areas[0]->TOTAL + 0) == 0) {
                $recsensorial_areas = recsensorialareaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                    ->orderBy('recsensorialarea_nombre', 'ASC')
                    ->get();


                DB::statement('ALTER TABLE reportearea AUTO_INCREMENT = 1;');

                $num_orden = 1;
                foreach ($recsensorial_areas as $key => $value) {
                    $area = reporteareaModel::create([
                        'proyecto_id' => $proyecto_id,
                        'recsensorialarea_id' => $value->id,
                        'reportearea_nombre' => $value->recsensorialarea_nombre,
                        'reportearea_instalacion' => $proyecto->proyecto_clienteinstalacion,
                        'reportearea_orden' => $num_orden,
                        'reportearea_proceso' => $value->RECSENSORIALAREA_PROCESO
                    ]);

                    $num_orden++;
                }
            } else {

                $inserciones = DB::select('CALL sp_insertar_areasFaltantes_g(?,?, ?)', [$proyecto->recsensorial_id, $proyecto_id, $proyecto->proyecto_clienteinstalacion]);
            }


            //===================================================


            // $recsensorial = recsensorialModel::with(['catcontrato', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();
            $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $proyecto_id)->get();


            // Vista
            return view('reportes.parametros.reportepoe', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'estatus'));
        }
    }


    public function validacionAsignacionUserProyecto($id)
    {
        try {
            if (auth()->user()->hasRoles(['Administrador','Superusuario'])) {

                $next = 1;
            }else{

                $user = auth()->user()->id;

                $permiso = DB::select("SELECT COUNT(u.ID_PROYECTO_USUARIO) AS PERMISO
                                FROM proyectoUsuarios u
                                WHERE u.SERVICIO_HI = 1 
                                AND u.ACTIVO = 1
                                AND u.PROYECTO_ID = ?
                                AND u.USUARIO_ID = ?", [$id, $user]);

                $next = $permiso[0]->PERMISO;
            }


            $dato['permisos'] = $next;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = "No encontradas";
            return response()->json($dato);
        }
    }


    public function servicioHI()
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';

            $proyectos = DB::select("
            SELECT p.proyecto_folio as ProyectoFolio,
                p.id AS ProyectoID,
                r.recsensorial_foliofisico as RecSensorialFolioFisico,
                r.recsensorial_folioquimico as RecSensorialFolioQuimico,
                p.proyecto_clienteinstalacion as ProyectoClienteInstalacion
            FROM serviciosProyecto sp 
            LEFT JOIN proyecto p ON sp.PROYECTO_ID = p.id
            LEFT JOIN recsensorial r ON r.id  = p.recsensorial_id
            WHERE sp.HI_INFORME = 1
            ");

            $proyectoID = null;
            foreach ($proyectos as $proyecto) {
                if ($proyecto->RecSensorialFolioFisico || $proyecto->RecSensorialFolioQuimico) { // Verifica que al menos uno de los folios exista
                    $proyectoID = $proyecto->ProyectoID;
                    $reconocimientoQuimico = $proyecto->RecSensorialFolioQuimico ? '[' . $proyecto->RecSensorialFolioQuimico . ']' : '[No tiene folio de reconocimiento químico]';
                    $reconocimientoFisico = $proyecto->RecSensorialFolioFisico ? '[' . $proyecto->RecSensorialFolioFisico . ']' : '[No tiene folio de reconocimiento físico]';
                    $instalacion = $proyecto->ProyectoClienteInstalacion ? 'Instalación: ' . $proyecto->ProyectoClienteInstalacion : '[No tiene instalación]';

                    $opciones_select .= '<option value="' . $proyectoID . '">Folio proyecto [' .
                        $proyecto->ProyectoFolio . '], Reconocimiento ' .
                        $reconocimientoQuimico . ', ' .
                        $reconocimientoFisico . ', ' .
                        $instalacion . '</option>';
                }
            }

            $dato['opciones'] = $opciones_select;
            $dato['proyecto_id'] = $proyectoID;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato, 500);
        }
    }

    public function servicioPsico()
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';

            $proyectos = DB::select("
            SELECT p.proyecto_folio as ProyectoFolio,
                p.id AS ProyectoID,
                p.proyecto_clienteinstalacion as ProyectoClienteInstalacion
            FROM serviciosProyecto sp 
            LEFT JOIN proyecto p ON sp.PROYECTO_ID = p.id
            LEFT JOIN reconocimientopsico r ON r.id  = p.reconocimiento_psico_id
            WHERE sp.PSICO_INFORME = 1
            ");

            $proyectoID = null;
            foreach ($proyectos as $proyecto) {
               
                    $proyectoID = $proyecto->ProyectoID;
                    $instalacion = $proyecto->ProyectoClienteInstalacion ? 'Instalación: ' . $proyecto->ProyectoClienteInstalacion : '[No tiene instalación]';

                    $opciones_select .= '<option value="' . $proyectoID . '">Folio proyecto [' .
                        $proyecto->ProyectoFolio . '], Reconocimiento ' .
                        $instalacion . '</option>';
                
            }

            $dato['opciones'] = $opciones_select;
            $dato['proyecto_id'] = $proyectoID;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato, 500);
        }
    }




    public function estatusProyecto($PROYECTO_ID)
    {
        // try {


        $proyecto = estatusReportesInformeModel::where('PROYECTO_ID', $PROYECTO_ID)->first();


        //VALIDAMOS SI EXISTE INFORMACION DEL PROYECTO
        if ($proyecto) {

            $dato['nuevo'] = 0;
            $dato['info'] = $proyecto;
        } else {

            $dato['nuevo'] = 1;
            $dato['info'] = '';
        }

        $dato["msj"] = 'Datos consultados correctamente';
        return response()->json($dato);

        // } catch (Exception  $e) {
        //     $dato["msj"] = 'Error ' . $e->getMessage();
        //     return response()->json($dato, 500);
        // }
    }


    public function finalizarPOE($PROYECTO_ID, $opcion, $nuevo)
    {
        try {


            if ($nuevo == 1) {

                $estatus = estatusReportesInformeModel::create([
                    'PROYECTO_ID' => $PROYECTO_ID,
                    'POE_FINALIZADO' => 1
                ]);
            } else {

                $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $PROYECTO_ID)->update(['POE_FINALIZADO' => $opcion]);
            }


            $dato['estatus'] = $opcion;
            $dato["msj"] = 'POE finalizado correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato, 500);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteslistaparametros($proyecto_id)
    {
        try {

            $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.agente_id,
                                        TABLA.agente_nombre
                                    FROM
                                        (
                                            SELECT
                                                proyectoproveedores.proyecto_id,
                                                proyectoproveedores.proveedor_id,
                                                proyectoproveedores.proyectoproveedores_tipoadicional,
                                                proyectoproveedores.catprueba_id AS agente_id,
                                                proyectoproveedores.proyectoproveedores_agente,
                                                (
                                                    CASE
                                                        WHEN catprueba_id = 1 THEN "Ruido"
                                                        WHEN catprueba_id = 2 THEN "Vibración"
                                                        WHEN catprueba_id = 3 THEN "Temperatura"
                                                        WHEN catprueba_id = 4 THEN "Iluminación"
                                                        WHEN catprueba_id = 5 THEN "" -- Radiaciones ionizantes
                                                        WHEN catprueba_id = 6 THEN "" -- Radiaciones no ionizantes
                                                        WHEN catprueba_id = 7 THEN "" -- Presiones ambientales anormales
                                                        WHEN catprueba_id = 8 THEN "Ventilación y calidad del aire"
                                                        WHEN catprueba_id = 9 THEN "Agua"
                                                        WHEN catprueba_id = 10 THEN "Hielo"
                                                        WHEN catprueba_id = 11 THEN "Alimentos"
                                                        WHEN catprueba_id = 12 THEN "" -- Superficies
                                                        WHEN catprueba_id = 13 THEN "" -- Riesgos ergonómicos
                                                        WHEN catprueba_id = 14 THEN "" -- Factores psicosociales
                                                        WHEN catprueba_id = 15 THEN "Químicos"
                                                        WHEN catprueba_id = 16 THEN "Infraestructura para servicios al personal"
                                                        WHEN catprueba_id = 17 THEN "Mapa de riesgos"
                                                        WHEN catprueba_id = 22 THEN "BEI"
                                                        WHEN catprueba_id = 23 THEN "Mapa con matriz"
                                                        WHEN catprueba_id = 24 THEN "Mapa sin matriz"
                                                        WHEN catprueba_id = 25 THEN "Instalación mapa"
                                                        ELSE ""
                                                    END
                                                ) AS agente_nombre
                                            FROM
                                                proyectoproveedores
                                            WHERE
                                                proyectoproveedores.proyecto_id = ?
                                                AND (
                                                    proyectoproveedores.proyectoproveedores_tipoadicional < 2
                                                    OR proyectoproveedores.catprueba_id = 23
                                                )
                                            ORDER BY
                                                proyectoproveedores.proyectoproveedores_tipoadicional ASC,
                                                proyectoproveedores.catprueba_id ASC
                                        ) AS TABLA
                                    WHERE
                                        IFNULL(TABLA.agente_nombre, "") != ""
                                    GROUP BY
                                        TABLA.proyecto_id,
                                        TABLA.agente_id,
                                        TABLA.agente_nombre
                                    ORDER BY
                                        TABLA.agente_nombre ASC', [$proyecto_id]);






            // $sql = DB::select('SELECT
            //                         TABLA.proyecto_id,
            //                         TABLA.agente_id,
            //                         TABLA.agente_nombre
            //                     FROM
            //                         (
            //                             SELECT
            //                                 proyectoproveedores.proyecto_id,
            //                                 proyectoproveedores.proveedor_id,
            //                                 proyectoproveedores.proyectoproveedores_tipoadicional,
            //                                 proyectoproveedores.catprueba_id AS agente_id,
            //                                 proyectoproveedores.proyectoproveedores_agente,
            //                                 (
            //                                     CASE
            //                                         WHEN catprueba_id = 1 THEN "Ruido"
            //                                         WHEN catprueba_id = 2 THEN "Vibración"
            //                                         WHEN catprueba_id = 3 THEN "Temperatura"
            //                                         WHEN catprueba_id = 4 THEN "Iluminación"
            //                                         WHEN catprueba_id = 5 THEN "" -- "Radiaciones ionizantes"
            //                                         WHEN catprueba_id = 6 THEN "" -- "Radiaciones no ionizantes"
            //                                         WHEN catprueba_id = 7 THEN "" -- "Presiones ambientales anormales"
            //                                         WHEN catprueba_id = 8 THEN "Ventilación y calidad del aire"
            //                                         WHEN catprueba_id = 9 THEN "Agua"
            //                                         WHEN catprueba_id = 10 THEN "Hielo"
            //                                         WHEN catprueba_id = 11 THEN "Alimentos" -- "Alimentos"
            //                                         WHEN catprueba_id = 12 THEN "" -- "Superficies"
            //                                         WHEN catprueba_id = 13 THEN "" -- "Riesgos ergonómicos"
            //                                         WHEN catprueba_id = 14 THEN "" -- "Factores psicosociales"
            //                                         WHEN catprueba_id = 15 THEN "Químicos"
            //                                         WHEN catprueba_id = 16 THEN "Infraestructura para servicios al personal"
            //                                         WHEN catprueba_id = 17 THEN "Mapa de riesgos" -- "Mapa de riesgos"
            //                                         WHEN catprueba_id = 22 THEN "BEI" -- "Beis"
            //                                         WHEN catprueba_id = 23 THEN "Mapa con matriz" -- "Beis"
            //                                         WHEN catprueba_id = 24 THEN "Mapa sin matriz" -- "Beis"
            //                                         WHEN catprueba_id = 25 THEN "Instalación mapa" -- "Beis"


            //                                         ELSE ""
            //                                     END
            //                                 ) AS agente_nombre
            //                             FROM
            //                                 proyectoproveedores
            //                             WHERE
            //                                 proyectoproveedores.proyecto_id = ? 
            //                                 AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
            //                             ORDER BY
            //                                 proyectoproveedores.proyectoproveedores_tipoadicional ASC,
            //                                 proyectoproveedores.catprueba_id ASC
            //                         ) AS TABLA
            //                     WHERE
            //                         IFNULL(TABLA.agente_nombre, "") != ""
            //                     GROUP BY
            //                         TABLA.proyecto_id,
            //                         TABLA.agente_id,
            //                         TABLA.agente_nombre
            //                     ORDER BY
            //                         TABLA.agente_nombre ASC', [$proyecto_id]);


            $opciones_menu = '<option value="">Seleccione</option>';
            // $opciones_menu .= '<option value="0">POE PROYECTO</option>';  -> EL POE ESTARA APARTE EN EL SELECT SOLO ESTARAN LOS REPORTES DE LOS AGENTES

            // DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            // foreach ($sql as $key => $value){
            //     $opciones_menu .= '<option value="'.$value->agente_id.'">'.$value->agente_nombre.'</option>';
            // }


            // // QUITAR DESPUES DE SUBIR AL SERVIDOR
            $opciones_menu .= '<option value="1">Ruido</option>';
            $opciones_menu .= '<option value="2">Vibración</option>';
            $opciones_menu .= '<option value="3">Temperatura</option>';
            $opciones_menu .= '<option value="4">Iluminación</option>';
            $opciones_menu .= '<option value="8">Ventilación y calidad del aire</option>';
            $opciones_menu .= '<option value="9">Agua</option>';
            $opciones_menu .= '<option value="10">Hielo</option>';
            $opciones_menu .= '<option value="15">Químicos</option>';
            $opciones_menu .= '<option value="16">Infraestructura para servicios al personal</option>';
            $opciones_menu .= '<option value="22">BEI</option>';
            $opciones_menu .= '<option value="23">Mapa con matriz</option>';



            $dato['opciones_menu'] = $opciones_menu;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones_menu'] = '<option value="">Error al consultar los parametros</option>';
            return response()->json($dato);
        }
    }

    

    /**
     * Display a listing of the resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reporteslistaparametrosPsico($proyecto_id)
    {
        try {
            $sql = DB::select('SELECT 
                                p.id as proyecto_id,
                                 CASE 
                                    WHEN n.RECPSICO_GUIAII = 1 THEN 1
                                    WHEN n.RECPSICO_GUIAIII = 1 THEN 2
                                    ELSE NULL 
                                END AS paquete_id,
                                "NOM-035" AS paquete_nombre
                                FROM reconocimientopsico r
                                LEFT JOIN recopsiconormativa n ON r.id = n.RECPSICO_ID
                                LEFT JOIN proyecto p ON p.reconocimiento_psico_id = r.id
                                WHERE p.id = ? AND n.RECPSICO_GUIAI = 1', [$proyecto_id]);

            $opciones_menu = '<option value="">Seleccione</option>';
            // $opciones_menu .= '<option value="0">POE PROYECTO</option>';  -> EL POE ESTARA APARTE EN EL SELECT SOLO ESTARAN LOS REPORTES DE LOS AGENTES

            //DESCOMENTAR DESPUES DE SUBIR AL SERVIDOR
            foreach ($sql as $key => $value){
                $opciones_menu .= '<option value="'.$value->paquete_id.'">'.$value->paquete_nombre.'</option>';
            }


            // QUITAR DESPUES DE SUBIR AL SERVIDOR
            // $opciones_menu .= '<option value="1">Ruido</option>';
            // $opciones_menu .= '<option value="2">Vibración</option>';
            // $opciones_menu .= '<option value="3">Temperatura</option>';
            // $opciones_menu .= '<option value="4">Iluminación</option>';
            // $opciones_menu .= '<option value="8">Ventilación y calidad del aire</option>';
            // $opciones_menu .= '<option value="9">Agua</option>';
            // $opciones_menu .= '<option value="10">Hielo</option>';
            // $opciones_menu .= '<option value="15">Químicos</option>';
            // $opciones_menu .= '<option value="16">Infraestructura para servicios al personal</option>';


            $dato['opciones_menu'] = $opciones_menu;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones_menu'] = '<option value="">Error al consultar los parametros</option>';
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */



    // public function reportematrizlabtablageneral($proyecto_id)
    // {
    //     try {
    //         $proyecto = proyectoModel::with('recsensorial')->findOrFail($proyecto_id);
    //         $recsensorial = $proyecto->recsensorial;

    //         if (!$recsensorial) {
    //             throw new \Exception("No se encontró información de reconocimiento sensorial para este proyecto.");
    //         }

    //         $areas = recsensorialareaModel::with([
    //             'recsensorialareapruebas.catprueba',
    //             'recsensorialareacategorias.categorias'
    //         ])
    //             ->where('recsensorial_id', $recsensorial->id)
    //             ->orderBy('id', 'asc')
    //             ->get();

    //         $filas = [];
    //         $contadorArea = 1;

    //         // $idsValidos = [1, 2, 3, 4, 8, 9, 10, 15, 16, 22];
    //         $idsValidos = [1, 2, 3, 4, 8, 15, 16, 22];


    //         foreach ($areas as $area) {
    //             $agentes = $area->recsensorialareapruebas->map(function ($prueba) {
    //                 $catprueba = $prueba->catprueba;
    //                 return [
    //                     'id' => $catprueba->id ?? '',
    //                     'nombre' => $catprueba->catPrueba_Nombre ?? '',
    //                 ];
    //             })->toArray();

    //             $categorias = $area->recsensorialareacategorias->map(function ($cat) {
    //                 return [
    //                     'nombre' => $cat->categorias->recsensorialcategoria_nombrecategoria ?? '',
    //                     'total' => $cat->recsensorialareacategorias_total ?? '',
    //                     'tiempoexpo' => $cat->recsensorialareacategorias_tiempoexpo ?? '',
    //                 ];
    //             })->toArray();

    //             $maxFilas = max(count($agentes), count($categorias), 1);
    //             $agentes = array_pad($agentes, $maxFilas, ['id' => '', 'nombre' => '']);
    //             $categorias = array_pad($categorias, $maxFilas, ['nombre' => '', 'total' => '', 'tiempoexpo' => '']);

    //             for ($i = 0; $i < $maxFilas; $i++) {
    //                 $categoria = $categorias[$i];
    //                 $mostrarSelect = !empty($agentes[$i]['nombre']);
    //                 $fila_id = $contadorArea . '_' . $i;

    //                 $agenteTexto = '';
    //                 if (!empty($agentes[$i]['nombre'])) {
    //                     $agenteTexto = $agentes[$i]['nombre'];
    //                     if (!empty($agentes[$i]['id'])) {
    //                         $agenteTexto .= ' (ID: ' . $agentes[$i]['id'] . ')';
    //                     }
    //                 }

    //                 // Determinar valor de LMP/NMP por agente
    //                 $idAgente = $agentes[$i]['id'];
    //                 $valorLMPNMP = '';
    //                 if ($idAgente !== '' && !in_array($idAgente, $idsValidos)) {
    //                     $valorLMPNMP = 'N/A';
    //                 }

    //                 $fila = [
    //                     'numero_registro' => $fila_id,
    //                     'numero_visible' => $contadorArea,
    //                     'recsensorialarea_nombre' => $i === 0 ? $area->recsensorialarea_nombre : '',
    //                     'agente' => $agenteTexto,
    //                     'categoria' => $categoria['nombre'],
    //                     'recsensorialarea_numerotrabajadores' => $categoria['total'],
    //                     'recsensorialarea_tiempoexposicion' => $categoria['tiempoexpo'],
    //                     'recsensorialarea_indicepeligro' => $mostrarSelect ? '' : '-',
    //                     'recsensorialarea_indiceexposicion' => $mostrarSelect ? '' : '-',
    //                     'recsensorialarea_riesgo' => '',
    //                     'recsensorialarea_lmpnmp' => $valorLMPNMP,
    //                     'recsensorialarea_cumplimiento' => $i === 0 ? $area->recsensorialarea_cumplimiento : '',
    //                     'recsensorialarea_medidas' => $i === 0 ? $area->recsensorialarea_medidas : '',
    //                     'rowspan' => $i === 0 ? $maxFilas : 0,
    //                     'mostrar_select' => $mostrarSelect,
    //                 ];

    //                 $filas[] = $fila;
    //             }

    //             $contadorArea++;
    //         }

    //         return response()->json([
    //             'data' => $filas,
    //             'msj' => 'Datos cargados correctamente'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'data' => [],
    //             'msj' => 'Error: ' . $e->getMessage()
    //         ]);
    //     }
    // }




    // public function reportematrizlabtablageneral($proyecto_id)
    // {
    //     try {
    //         $proyecto = proyectoModel::with('recsensorial')->findOrFail($proyecto_id);
    //         $recsensorial = $proyecto->recsensorial;

    //         if (!$recsensorial) {
    //             throw new \Exception("No se encontró información de reconocimiento sensorial para este proyecto.");
    //         }

    //         $areas = recsensorialareaModel::with([
    //             'recsensorialareapruebas.catprueba',
    //             'recsensorialareacategorias.categorias'
    //         ])
    //             ->where('recsensorial_id', $recsensorial->id)
    //             ->orderBy('id', 'asc')
    //             ->get();

    //         $filas = [];
    //         $contadorArea = 1;

    //         $idsValidos = [1, 2, 3, 4, 8, 15, 16, 22];

    //         foreach ($areas as $area) {
    //             $agentes = $area->recsensorialareapruebas->map(function ($prueba) {
    //                 $catprueba = $prueba->catprueba;
    //                 return [
    //                     'id' => $catprueba->id ?? '',
    //                     'nombre' => $catprueba->catPrueba_Nombre ?? '',
    //                 ];
    //             })->toArray();

    //             $categorias = $area->recsensorialareacategorias->map(function ($cat) {
    //                 return [
    //                     'nombre' => $cat->categorias->recsensorialcategoria_nombrecategoria ?? '',
    //                     'total' => $cat->recsensorialareacategorias_total ?? '',
    //                     'tiempoexpo' => $cat->recsensorialareacategorias_tiempoexpo ?? '',
    //                 ];
    //             })->toArray();

    //             $maxFilas = max(count($agentes), count($categorias), 1);
    //             $agentes = array_pad($agentes, $maxFilas, ['id' => '', 'nombre' => '']);
    //             $categorias = array_pad($categorias, $maxFilas, ['nombre' => '', 'total' => '', 'tiempoexpo' => '']);

    //             for ($i = 0; $i < $maxFilas; $i++) {
    //                 $categoria = $categorias[$i];
    //                 $mostrarSelect = !empty($agentes[$i]['nombre']);
    //                 $fila_id = $contadorArea . '_' . $i;

    //                 $agenteTexto = '';
    //                 if (!empty($agentes[$i]['nombre'])) {
    //                     $agenteTexto = $agentes[$i]['nombre'];
    //                     if (!empty($agentes[$i]['id'])) {
    //                         $agenteTexto .= ' (ID: ' . $agentes[$i]['id'] . ')';
    //                     }
    //                 }

    //                 $idAgente = $agentes[$i]['id'];
    //                 $valorLMPNMP = '';
    //                 $cumplimiento = '';

    //                 if ($idAgente == 4) {
    //                     $existenRegistros = DB::table('reporteiluminacionpuntos')
    //                         ->where('proyecto_id', $proyecto_id)
    //                         ->exists();

    //                     if ($existenRegistros) {
    //                         $valorMaxLux = DB::table('reporteiluminacionpuntos')
    //                             ->where('proyecto_id', $proyecto_id)
    //                             ->max('reporteiluminacionpuntos_lux');

    //                         $totalLuxNorma = DB::table('reporteiluminacionpuntos')
    //                             ->selectRaw('
    //                             SUM(
    //                                 CASE WHEN reporteiluminacionpuntos_luxmed1 <= reporteiluminacionpuntos_lux THEN 1 ELSE 0 END +
    //                                 CASE WHEN reporteiluminacionpuntos_luxmed2 <= reporteiluminacionpuntos_lux THEN 1 ELSE 0 END +
    //                                 CASE WHEN reporteiluminacionpuntos_luxmed3 <= reporteiluminacionpuntos_lux THEN 1 ELSE 0 END
    //                             ) as total_cumplen,
    //                             COUNT(*) * 3 as total_medidas
    //                         ')
    //                             ->where('proyecto_id', $proyecto_id)
    //                             ->first();

    //                         if ($totalLuxNorma && $totalLuxNorma->total_medidas > 0) {
    //                             $dentroNorma = ($totalLuxNorma->total_cumplen == $totalLuxNorma->total_medidas);
    //                             $valorLMPNMP = number_format($valorMaxLux, 2) . ' /200';
    //                             $cumplimiento = $dentroNorma ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
    //                         }
    //                     }
    //                 } elseif ($idAgente !== '' && !in_array($idAgente, $idsValidos)) {
    //                     $valorLMPNMP = 'N/A';
    //                 }

    //                 $fila = [
    //                     'numero_registro' => $fila_id,
    //                     'numero_visible' => $contadorArea,
    //                     'recsensorialarea_nombre' => $i === 0 ? $area->recsensorialarea_nombre : '',
    //                     'agente' => $agenteTexto,
    //                     'categoria' => $categoria['nombre'],
    //                     'recsensorialarea_numerotrabajadores' => $categoria['total'],
    //                     'recsensorialarea_tiempoexposicion' => $categoria['tiempoexpo'],
    //                     'recsensorialarea_indicepeligro' => $mostrarSelect ? '' : '-',
    //                     'recsensorialarea_indiceexposicion' => $mostrarSelect ? '' : '-',
    //                     'recsensorialarea_riesgo' => '',
    //                     'recsensorialarea_lmpnmp' => $valorLMPNMP,
    //                     'recsensorialarea_cumplimiento' => $i === 0 ? ($cumplimiento ?: $area->recsensorialarea_cumplimiento) : '',
    //                     'recsensorialarea_medidas' => $i === 0 ? $area->recsensorialarea_medidas : '',
    //                     'rowspan' => $i === 0 ? $maxFilas : 0,
    //                     'mostrar_select' => $mostrarSelect,
    //                 ];

    //                 $filas[] = $fila;
    //             }

    //             $contadorArea++;
    //         }

    //         return response()->json([
    //             'data' => $filas,
    //             'msj' => 'Datos cargados correctamente'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'data' => [],
    //             'msj' => 'Error: ' . $e->getMessage()
    //         ]);
    //     }
    // }


    public function reportematrizlabtablageneral($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::with('recsensorial')->findOrFail($proyecto_id);
            $recsensorial = $proyecto->recsensorial;

            if (!$recsensorial) {
                throw new \Exception("No se encontró información de reconocimiento sensorial para este proyecto.");
            }

            $areas = recsensorialareaModel::with([
                'recsensorialareapruebas.catprueba',
                'recsensorialareacategorias.categorias'
            ])
                ->where('recsensorial_id', $recsensorial->id)
                ->orderBy('id', 'asc')
                ->get();

            $filas = [];
            $contadorArea = 1;
            $idsValidos = [1, 2, 3, 4, 8, 15, 16, 22];

            foreach ($areas as $area) {
                $agentes = $area->recsensorialareapruebas->map(function ($prueba) {
                    $catprueba = $prueba->catprueba;
                    return [
                        'id' => $catprueba->id ?? '',
                        'nombre' => $catprueba->catPrueba_Nombre ?? '',
                    ];
                })->toArray();

                $categorias = $area->recsensorialareacategorias->map(function ($cat) {
                    return [
                        'nombre' => $cat->categorias->recsensorialcategoria_nombrecategoria ?? '',
                        'total' => $cat->recsensorialareacategorias_total ?? '',
                        'tiempoexpo' => $cat->recsensorialareacategorias_tiempoexpo ?? '',
                    ];
                })->toArray();

                $maxFilas = max(count($agentes), count($categorias), 1);
                $agentes = array_pad($agentes, $maxFilas, ['id' => '', 'nombre' => '']);
                $categorias = array_pad($categorias, $maxFilas, ['nombre' => '', 'total' => '', 'tiempoexpo' => '']);

                for ($i = 0; $i < $maxFilas; $i++) {
                    $categoria = $categorias[$i];
                    $mostrarSelect = !empty($agentes[$i]['nombre']);
                    $fila_id = $contadorArea . '_' . $i;

                    $agenteTexto = '';
                    if (!empty($agentes[$i]['nombre'])) {
                        $agenteTexto = $agentes[$i]['nombre'];
                        if (!empty($agentes[$i]['id'])) {
                            $agenteTexto .= ' (ID: ' . $agentes[$i]['id'] . ')';
                        }
                    }

                    $idAgente = $agentes[$i]['id'];
                    $valorLMPNMP = '';
                    $cumplimiento = '';
                    $medidas = 'N/A';

                    $areaId = $area->id;

                    // Obtener los IDs de 'reportearea' que están vinculados a esta área
                    $reporteAreaIds = DB::table('reportearea')
                        ->where('recsensorialarea_id', $areaId)
                        ->pluck('id');

                    // Lógica para agente ID 4: iluminación
                    if ($idAgente == 4) {
                        $puntosIluminacion = DB::table('reporteiluminacionpuntos')
                            ->whereIn('reporteiluminacionpuntos_area_id', $reporteAreaIds)
                            ->where('proyecto_id', $proyecto_id);

                        if ($puntosIluminacion->exists()) {
                            $valorMaxLux = $puntosIluminacion->max('reporteiluminacionpuntos_lux');
                            $valorLMPNMP = number_format($valorMaxLux) . ' /200';

                            $cumplimiento = ($valorMaxLux >= 200) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                        } else {
                            $valorLMPNMP = 'No tiene registro';
                        }
                    } elseif ($idAgente == 1) {
                        $puntosRuido = DB::table('reporteruidopuntoner')
                            ->whereIn('reporteruidoarea_id', $reporteAreaIds)
                            ->where('proyecto_id', $proyecto_id);

                        if ($puntosRuido->exists()) {
                            $registroMax = $puntosRuido
                                ->orderByDesc('reporteruidopuntoner_ner')
                                ->first();

                            if ($registroMax) {
                                $ner = $registroMax->reporteruidopuntoner_ner;
                                $lmpe = $registroMax->reporteruidopuntoner_lmpe;
                                $valorLMPNMP = number_format($ner, 2);

                                $cumplimiento = ($ner <= $lmpe) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                            }
                        } else {
                            $valorLMPNMP = 'No tiene registro';
                        }
                    }
                    // Resto de agentes no válidos
                    elseif ($idAgente !== '' && !in_array($idAgente, $idsValidos)) {
                        $valorLMPNMP = 'N/A';
                    }


                    // Recomendaciones
                    if (in_array($idAgente, $idsValidos)) {
                        $recomendaciones = DB::table('reporterecomendaciones')
                            ->where('proyecto_id', $proyecto_id)
                            ->where('agente_id', $idAgente)
                            ->get();

                        if ($recomendaciones->count() > 0) {
                            $htmlRecomendaciones = '';
                            foreach ($recomendaciones as $value) {
                                $descripcionTexto = $value->reporterecomendaciones_descripcion ?? '';

                                $checkbox = '<div class="switch">
                                <label>
                                    <input type="checkbox" class="recomendacion_checkbox" name="recomendacion_checkbox[]" value="' . $value->id . '" onclick="activa_recomendacion(this);">
                                    <span class="lever switch-col-light-blue"></span>
                                </label>
                            </div>';

                                $textarea = '<textarea class="form-control" rows="5" id="recomendacion_descripcion_' . $value->id . '" name="recomendacion_descripcion_' . $value->id . '">' . $descripcionTexto . '</textarea>';

                                $htmlRecomendaciones .= '<div class="recomendacion-bloque mb-2">' . $checkbox . $textarea . '</div>';
                            }

                            $medidas = $htmlRecomendaciones;
                        }
                    }

                    // Construcción de fila
                    $fila = [
                        'numero_registro' => $fila_id,
                        'numero_visible' => $contadorArea,
                        'recsensorialarea_nombre' => $i === 0 ? $area->recsensorialarea_nombre . ' (ID: ' . $area->id . ')' : '',
                        'agente' => $agenteTexto,
                        'categoria' => $categoria['nombre'],
                        'recsensorialarea_numerotrabajadores' => $categoria['total'],
                        'recsensorialarea_tiempoexposicion' => $categoria['tiempoexpo'],
                        'recsensorialarea_indicepeligro' => $mostrarSelect ? '' : '-',
                        'recsensorialarea_indiceexposicion' => $mostrarSelect ? '' : '-',
                        'recsensorialarea_riesgo' => '',
                        'recsensorialarea_lmpnmp' => $valorLMPNMP,
                        'recsensorialarea_cumplimiento' => $i === 0 ? ($cumplimiento ?: $area->recsensorialarea_cumplimiento) : '',
                        'recsensorialarea_medidas' => $medidas,
                        'rowspan' => $i === 0 ? $maxFilas : 0,
                        'mostrar_select' => $mostrarSelect,
                    ];

                    $filas[] = $fila;
                }

                $contadorArea++;
            }

            return response()->json([
                'data' => $filas,
                'msj' => 'Datos cargados correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'msj' => 'Error: ' . $e->getMessage()
            ]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportecategoriatabla($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $proyecto_id)->first();


            //VALIDAMOS SI EXISTE INFORMACION DEL PROYECTO
            if ($estatus) {
                $bloqueado = $estatus->POE_FINALIZADO;
            } else {
                $bloqueado = 0;
            }


            //==========================================


            $categorias = reportecategoriaModel::where('proyecto_id', $proyecto_id)
                ->orderBy('reportecategoria_orden', 'ASC')
                ->orderBy('reportecategoria_nombre', 'ASC')
                ->get();


            $numero_registro = 0;
            $total_singuardar = 0;
            foreach ($categorias as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                if ($bloqueado == 0) {

                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar"><i class="fa fa-pencil"></i></button>';
                } else {

                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                }


                if ($bloqueado == 0) {

                    if (($proyecto->proyecto_concluido + 0) == 0 && auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                    }
                } else {

                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                }


                if (!$value->reporteairecategoria_total) {
                    $total_singuardar += 1;
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
     * @param  int  $reportecategoria_id
     * @return \Illuminate\Http\Response
     */
    public function reportecategoriaeliminar($reportecategoria_id)
    {
        try {
            $categoria = reportecategoriaModel::where('id', $reportecategoria_id)->delete();

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
     * @param  int $reportearea_id
     * @return \Illuminate\Http\Response
     */
    public function reporteareacategorias($proyecto_id, $reportearea_id, $recsensorialarea_id)
    {
        try {
            $areacategorias = DB::select('CALL sp_obtenerCategoriasArea_POE_b(?,?,?)', [$proyecto_id, $reportearea_id, $recsensorialarea_id]);


            $numero_registro = 0;
            $areacategorias_lista = '';
            $readonly_required = '';

            foreach ($areacategorias as $key => $value) {
                $numero_registro += 1;

                if ($value->checked) {
                    $readonly_required = 'readonly';
                } else {
                    $readonly_required = 'readonly';
                }


                $areacategorias_lista .= '<tr>
                                            <td with="">
                                                <div class="switch" style="border: 0px #000 solid;">
                                                    <label>
                                                        <input type="checkbox" name="checkbox_reportecategoria_id[]" value="' . $value->id . '" ' . $value->checked . ' onchange="activa_areacategoria(this, ' . $numero_registro . ');"/>
                                                        <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td with="240">
                                                ' . $value->reportecategoria_nombre . '
                                            </td>
                                            <td with="">
                                                <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_total[]" value="' . $value->total . '" ' . $readonly_required . '>
                                            </td>
                                            <td with="">
                                                <input type="number" min="1" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_geh[]" value="' . $value->geh . '" ' . $readonly_required . '>
                                            </td>
                                            <td with="">
                                                <textarea rows="2" class="form-control areacategoria_' . $numero_registro . '" name="reporteareacategoria_actividades[]" ' . $readonly_required . '>' . $value->actividades . '</textarea>
                                            </td>
                                        </tr>';
            }


            // respuesta
            $dato['areacategorias'] = $areacategorias_lista;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['areacategorias'] = '<tr><td colspan="5">Error al cargar las categorías</td></tr>';
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
    public function reporteareatabla($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);
            $estatus = estatusReportesInformeModel::where('PROYECTO_ID', $proyecto_id)->first();


            //VALIDAMOS SI EXISTE INFORMACION DEL PROYECTO
            if ($estatus) {
                $bloqueado = $estatus->POE_FINALIZADO;
            } else {
                $bloqueado = 0;
            }

            //==========================================


            // $areas = reporteareaModel::where('proyecto_id', $proyecto_id)
            //                         ->orderBy('reportearea_nombre', 'ASC')
            //                         ->get();


            $areas = DB::select('SELECT
                                    reportearea.proyecto_id,
                                    reportearea.id,
                                    reportearea.recsensorialarea_id,
                                    reportearea.reportearea_instalacion,
                                    reportearea.reportearea_nombre,
                                    reportearea.reportearea_orden,
                                    reportearea.reportearea_porcientooperacion,
                                    IF(IFNULL(reportearea.reportearea_porcientooperacion, "") != "", CONCAT(reportearea.reportearea_porcientooperacion, " %"), null) AS reportearea_porcientooperacion_texto,
                                    reporteareacategoria.reportecategoria_id,
                                    reportecategoria.reportecategoria_nombre,
                                    reportecategoria.reportecategoria_orden,
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


            $numero_registro = 0;
            $area = 'XXXXX';
            $id = 0;
            foreach ($areas as $key => $value) {
                if ($value->reportearea_nombre != $area || $value->id != $id) {
                    $numero_registro += 1;
                    $value->numero_registro = $numero_registro;

                    $area = $value->reportearea_nombre;
                    $id = $value->id;
                } else {
                    $value->numero_registro = $numero_registro;
                }

                if ($bloqueado == 0) {

                    $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle editar" ><i class="fa fa-pencil"></i></button>';
                } else {
                    $value->boton_editar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                }

                if ($bloqueado == 0) {

                    if (($proyecto->proyecto_concluido + 0) == 0 && auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                    } else {
                        $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                    }
                } else {

                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $areas;
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
     * @param  int  $reportearea_id
     * @return \Illuminate\Http\Response
     */
    public function reporteareaeliminar($reportearea_id)
    {
        try {
            $area = reporteareaModel::where('id', $reportearea_id)->delete();
            $eliminar_categorias = reporteareacategoriaModel::where('reportearea_id', $reportearea_id)->delete();


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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());


            // CATEGORIAS
            if (($request->opcion + 0) == 1) {
                if (($request->reportecategoria_id + 0) == 0) {
                    DB::statement('ALTER TABLE reportecategoria AUTO_INCREMENT = 1;');


                    $request['recsensorialcategoria_id'] = 0;
                    $categoria = reportecategoriaModel::create($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $categoria = reportecategoriaModel::findOrFail($request->reportecategoria_id);
                    $categoria->update($request->all());

                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // AREAS
            if (($request->opcion + 0) == 2) {
                if (($request->reportearea_id + 0) == 0) {
                    $request['recsensorialarea_id'] = 0;
                    $request['reporteiluminacionarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteruidoarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reportequimicosarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteairearea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteaguaarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reportehieloarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reportevibracionarea_porcientooperacion'] = $request->reportearea_porcientooperacion;


                    DB::statement('ALTER TABLE reportearea AUTO_INCREMENT = 1;');
                    $area = reporteareaModel::create($request->all());


                    if ($request->checkbox_reportecategoria_id) {
                        DB::statement('ALTER TABLE reporteareacategoria AUTO_INCREMENT = 1;');

                        foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                            $areacategoria = reporteareacategoriaModel::create([
                                'reportearea_id' => $area->id,
                                'reportecategoria_id' => $value["reportecategoria_id"],
                                'reporteareacategoria_total' => $value["reporteareacategoria_total"],
                                'reporteareacategoria_geh' => $value["reporteareacategoria_geh"],
                                'reporteareacategoria_actividades' => $value["reporteareacategoria_actividades"]
                            ]);


                            // $areacategoria = reporteareacategoriaModel::create([
                            //       'reportearea_id' => $area->id
                            //     , 'reportecategoria_id' => $value
                            //     , 'reporteareacategoria_total' => $request['reporteareacategoria_total_'.$value]
                            //     , 'reporteareacategoria_geh' => $request['reporteareacategoria_geh_'.$value]
                            //     , 'reporteareacategoria_actividades' => $request['reporteareacategoria_actividades_'.$value]
                            // ]);
                        }
                    }


                    // Mensaje de salida
                    $dato["msj"] = 'Datos guardados correctamente';
                } else {
                    $request['reporteiluminacionarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteruidoarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reportequimicosarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteairearea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reporteaguaarea_porcientooperacion'] = $request->reportearea_porcientooperacion;
                    $request['reportehieloarea_porcientooperacion'] = $request->reportearea_porcientooperacion;


                    $area = reporteareaModel::findOrFail($request->reportearea_id);
                    $area->update($request->all());


                    $eliminar_categorias = reporteareacategoriaModel::where('reportearea_id', $request->reportearea_id)->delete();
                    DB::statement('ALTER TABLE reporteareacategoria AUTO_INCREMENT = 1;');


                    if ($request->checkbox_reportecategoria_id) {
                        foreach ($request->checkbox_reportecategoria_id as $key => $value) {
                            $areacategoria = reporteareacategoriaModel::create([
                                'reportearea_id' => $area->id,
                                'reportecategoria_id' => $value["reportecategoria_id"],
                                'reporteareacategoria_total' => $value["reporteareacategoria_total"],
                                'reporteareacategoria_geh' => $value["reporteareacategoria_geh"],
                                'reporteareacategoria_actividades' => $value["reporteareacategoria_actividades"]
                            ]);

                            // $areacategoria = reporteareacategoriaModel::create([
                            //       'reportearea_id' => $area->id
                            //     , 'reportecategoria_id' => $value
                            //     , 'reporteareacategoria_total' => $request['reporteareacategoria_total_'.$value]
                            //     , 'reporteareacategoria_geh' => $request['reporteareacategoria_geh_'.$value]
                            //     , 'reporteareacategoria_actividades' => $request['reporteareacategoria_actividades_'.$value]
                            // ]);
                        }
                    }


                    // Mensaje
                    $dato["msj"] = 'Datos modificados correctamente';
                }
            }


            // respuesta
            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportepoeword($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);


            // LEER PLANTILLA WORD
            //================================================================================


            $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_tabla_poe.docx')); //Ruta carpeta storage


            // LOGOS
            //================================================================================


            if ($cliente->cliente_plantillalogoizquierdo) {
                if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoizquierdo))) {
                    $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoizquierdo), 'height' => 39, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                }
            } else {
                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
            }


            if ($cliente->cliente_plantillalogoderecho) {
                if (file_exists(storage_path('app/' . $cliente->cliente_plantillalogoderecho))) {
                    $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente->cliente_plantillalogoderecho), 'height' => 39, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                }
            } else {
                $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
            }


            // INSTALACION
            //================================================================================


            $plantillaword->setValue('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion . '<w:br/>' . $proyecto->proyecto_folio);
            $plantillaword->setValue('INSTALACION', $proyecto->proyecto_clienteinstalacion);

            setlocale(LC_ALL, "es_MX");
            $plantillaword->setValue('FECHA_CREACION', ucfirst(strftime("%B %Y", strtotime(date("d-m-Y", strtotime($recsensorial->recsensorial_fechainicio)))))); //ucfirst = primera letra mayuscula


            // TABLA'S POE
            //================================================================================


            // FORMATO
            $font_size = 10;
            $bgColor_encabezado = '#0C3F64'; //#1A5276
            $fuente = 'Montserrat';
            $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $combinar_fila_encabezado = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado);
            $combinar_fila = array('vMerge' => 'restart', 'valign' => 'center');
            $continua_fila = array('vMerge' => 'continue', 'valign' => 'center');
            $celda = array('valign' => 'center');
            $centrado = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $izquierda = array('align' => 'left', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $justificado = array('align' => 'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $texto = array('color' => '000000', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $textonegrita = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
            $textototal = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);


            $sql = DB::select('SELECT
                                    reportearea.proyecto_id,
                                    reportearea.id,
                                    reportearea.reportearea_instalacion,
                                    reportearea.reportearea_nombre,
                                    reportearea.reportearea_orden,
                                    reportearea.reportearea_porcientooperacion,
                                    IF(IFNULL(reportearea.reportearea_porcientooperacion, "") != "", CONCAT(reportearea.reportearea_porcientooperacion, " %"), null) AS reportearea_porcientooperacion_texto,
                                    reporteareacategoria.reportecategoria_id,
                                    reportecategoria.reportecategoria_nombre,
                                    reportecategoria.reportecategoria_orden,
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

            $ancho_col_1 = 500;
            $ancho_col_2 = 1200;
            $ancho_col_3 = 3500;
            $ancho_col_4 = 1300;
            $ancho_col_5 = 3000;

            // Crear tabla
            $table = null;
            $width_table = 9940;
            $table = new Table(array('name' => $fuente, 'width' => $width_table, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    if (($key + 0) != 0) {
                        $total = DB::select('SELECT
                                                SUM(TABLA.reportecategoria_total) AS total
                                            FROM
                                                (
                                                    SELECT
                                                        -- reportearea.proyecto_id,
                                                        -- reportearea.reportearea_instalacion,
                                                        -- reportearea.reportearea_nombre,
                                                        -- reportearea.reportearea_orden,
                                                        reporteareacategoria.reportecategoria_id,
                                                        reportecategoria.reportecategoria_nombre,
                                                        -- reporteareacategoria.reporteareacategoria_total,
                                                        reportecategoria.reportecategoria_total 
                                                    FROM
                                                        reporteareacategoria
                                                        LEFT JOIN reportearea ON reporteareacategoria.reportearea_id = reportearea.id
                                                        LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                    WHERE
                                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reportearea.reportearea_instalacion = "' . $instalacion . '"
                                                    GROUP BY
                                                        reporteareacategoria.reportecategoria_id,
                                                        reportecategoria.reportecategoria_nombre,
                                                        reportecategoria.reportecategoria_total
                                                ) AS TABLA');


                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
                        $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
                        $table->addCell($ancho_col_5, $continua_fila);

                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);
                    }

                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reportearea_instalacion, $encabezado_texto); // combina columna

                    // $instalacion = $value->reportearea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                if ($area != $value->reportearea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                if ($area != $value->reportearea_nombre) {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $area = $value->reportearea_nombre;
                } else {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_total, $texto);


                if ($instalacion != $value->reportearea_instalacion) {
                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($justificado)->addText($recsensorial->recsensorial_actividadprincipal, $texto);

                    $instalacion = $value->reportearea_instalacion;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila);
                }
            }

            $total = DB::select('SELECT
                                    SUM(TABLA.reportecategoria_total) AS total
                                FROM
                                    (
                                        SELECT
                                            -- reportearea.proyecto_id,
                                            -- reportearea.reportearea_instalacion,
                                            -- reportearea.reportearea_nombre,
                                            -- reportearea.reportearea_orden,
                                            reporteareacategoria.reportecategoria_id,
                                            reportecategoria.reportecategoria_nombre,
                                            -- reporteareacategoria.reporteareacategoria_total,
                                            reportecategoria.reportecategoria_total 
                                        FROM
                                            reporteareacategoria
                                            LEFT JOIN reportearea ON reporteareacategoria.reportearea_id = reportearea.id
                                            LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                        WHERE
                                            reportearea.proyecto_id = ' . $proyecto_id . ' 
                                            AND reportearea.reportearea_instalacion = "' . $instalacion . '"
                                        GROUP BY
                                            reporteareacategoria.reportecategoria_id,
                                            reportecategoria.reportecategoria_nombre,
                                            reportecategoria.reportecategoria_total
                                    ) AS TABLA');


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
            $table->addCell($ancho_col_5, $continua_fila);

            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);


            $plantillaword->setComplexBlock('TABLA_POE', $table);


            // GUARDAR CAMBIOS Y DESCARGAR .DOCX
            //================================================================================


            $word_ruta = storage_path('app/reportes/informes/Tabla_POE_proyecto_' . $proyecto->proyecto_folio . '.docx');
            $plantillaword->saveAs($word_ruta); //GUARDAR Y CREAR archivo word TEMPORAL
            return response()->download($word_ruta)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }

    /// OBTENEMOS LOS DATOS DEL INFORME
    public function obtenerDatosInformesProyecto($ID)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $html  = '<option value="">&nbsp;</option>';
            $info = DB::select('SELECT ID_RECURSO_INFORME,
                                         PROYECTO_ID,
                                         AGENTE_ID,
                                         NORMA_ID,
                                         RUTA_IMAGEN_PORTADA,
                                         OPCION_PORTADA1,
                                         OPCION_PORTADA2,
                                         OPCION_PORTADA3,
                                         OPCION_PORTADA4,
                                         OPCION_PORTADA5,
                                         OPCION_PORTADA6,                                        
                                         NIVEL1,
                                         NIVEL2,
                                         NIVEL3,
                                         NIVEL4,
                                         NIVEL5
                                 FROM recursosPortadasInformes
                                 WHERE PROYECTO_ID = ?', [$ID]);

            $niveles = DB::select('   SELECT 
                                            "Instalación" ETIQUETA,
                                            proyecto_clienteinstalacion OPCION,
                                            0 NIVEL
                                        FROM proyecto
                                        WHERE id = ?
                                        UNION
                                        SELECT
                                            IFNULL(ce.NOMBRE_ETIQUETA, "NO") AS ETIQUETA,
                                            IFNULL(co.NOMBRE_OPCIONES , "NO") AS OPCION, 
                                            IFNULL(ep.NIVEL, 0) NIVEL
                                        FROM proyecto rs
                                        LEFT JOIN proyecto p ON rs.proyecto_folio = p.proyecto_folio
                                        LEFT JOIN estructuraProyectos ep ON p.id = ep.PROYECTO_ID
                                        LEFT JOIN cat_etiquetas ce ON ep.ETIQUETA_ID = ce.ID_ETIQUETA
                                        LEFT JOIN catetiquetas_opciones co ON ep.OPCION_ID = co.ID_OPCIONES_ETIQUETAS
                                        WHERE rs.id = ?
                                        UNION
                                        SELECT 
                                            "Folio" ETIQUETA,
                                            proyecto_folio OPCION,
                                            0 NIVEL
                                        FROM proyecto
                                        WHERE id = ?
                                        UNION
                                        SELECT
                                            "Razón social" ETIQUETA,
                                            proyecto_clienterazonsocial OPCION,
                                            0 NIVEL
                                        FROM proyecto
                                        WHERE id = ?
                                        UNION
                                        SELECT 
                                            "Nombre comercial" ETIQUETA,
                                            c.cliente_NombreComercial OPCION,
                                            0 NIVEL
                                        FROM cliente c
                                        LEFT JOIN proyecto r ON r.cliente_id = c.id
                                        WHERE r.id = ?
                                     ORDER BY NIVEL', [$ID, $ID, $ID, $ID, $ID]);



            foreach ($niveles as $key => $value) {

                if ($value->ETIQUETA == 'NO') {

                    $opciones_select .= '<option value="" disabled> Proyecto vinculado sin Estructura organizacional para mostrar</option>';
                } else {

                    if ($value->NIVEL == 0) {

                        $opciones_select .= '<option value="' . $value->OPCION . '"  >' . $value->ETIQUETA . ' : ' . $value->OPCION  . ' </option>';
                    } else {

                        $opciones_select .= '<option value="' . $value->OPCION . '"  >' . $value->ETIQUETA . ' : ' . $value->OPCION . ' [ Nivel' . $value->NIVEL . ']' . ' </option>';
                    }
                }
            }


            foreach ($niveles as $key => $value) {
                if ($value->ETIQUETA == 'Instalación' || $value->NIVEL != 0) {

                    $html .= '<option value="' . $value->OPCION . '">' . $value->ETIQUETA . " : " . $value->OPCION;
                    if ($value->NIVEL != 0) {

                        $html .= ' [ Nivel ' . $value->NIVEL . ']';
                    }
                    $html .= '</option>';
                }
            }

            $dato["opciones"] = $opciones_select;
            $dato["checks"] = $html;



            if ($info) {

                $dato["data"] = $info;
                return response()->json($dato);
            } else {

                $dato["data"] = 'No se encontraron datos';
                return response()->json($dato);
            }
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato, 500); // Se puede usar el código de estado 500 para indicar un error del servidor
        }
    }


    /// OBTENEMOS LOS DATOS DE LAS PORTADAS PARA TODOS LOS INFORMES
    public function portadaInfo($PROYECTO, $AGENTE)
    {
        try {

            //Obtenemos
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $PROYECTO)->where('AGENTE_ID', $AGENTE)->get();

            //Enviamos
            $dato["data"] = $recursos;
            return response()->json($dato);
        } catch (Exception $e) {

            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato, 500); // Se puede usar el código de estado 500 para indicar un error del servidor
        }
    }

    public function logoPortada($ID_RECUROS)
    {

        $logo = recursosPortadasInformesModel::findOrFail($ID_RECUROS);
        return Storage::response($logo->RUTA_IMAGEN_PORTADA);
    }




    public function descargarPortadaInformes($proyecto_id, $tipo)
    {

        function introduccion($proyecto, $texto)
        {
            if (count($proyecto) == 0) {
                return $texto;
            }

            $proyecto = $proyecto[0];

            $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);
            $meses = [
                1 => 'enero',
                2 => 'febrero',
                3 => 'marzo',
                4 => 'abril',
                5 => 'mayo',
                6 => 'junio',
                7 => 'julio',
                8 => 'agosto',
                9 => 'septiembre',
                10 => 'octubre',
                11 => 'noviembre',
                12 => 'diciembre'
            ];

            $texto = str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $texto);
            $texto = str_replace("REPORTE_FECHA_LARGA", $reportefecha[2] . " de " . $meses[(int)$reportefecha[1]] . " del año " . $reportefecha[0], $texto);

            return $texto;
        }

        // ================== DATOS GENERALES =================
        $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $tipo)->get();
        $proyecto = proyectoModel::where('id', $proyecto_id)->get();
        $reconocimiento = recsensorialModel::where('id', $proyecto[0]->recsensorial_id)->get();


        if ($proyecto[0]->requiereContrato == 1) {

            $contratoId = $proyecto[0]->contrato_id;

            $cliente = DB::table('contratos_clientes as cc')
                ->leftJoin('cliente as c', 'c.id', '=', 'cc.CLIENTE_ID')
                ->where('cc.ID_CONTRATO', $contratoId)
                ->select(
                    'cc.NUMERO_CONTRATO',
                    'cc.DESCRIPCION_CONTRATO',
                    'cc.CONTRATO_PLANTILLA_LOGODERECHO',
                    'cc.CONTRATO_PLANTILLA_LOGOIZQUIERDO',
                    'cc.CONTRATO_PLANTILLA_PIEPAGINA',
                    'c.cliente_RazonSocial'
                )
                ->get();
        } else {
            $cliente = clienteModel::where('id', $proyecto[0]->cliente_id)->get();
        }



        switch ($tipo) {
            case 1: //RUIDO

                $agente = reporteruidoModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_ruido.docx')); //Ruta carpeta storage


                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 1) // ruido
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteruido_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA intERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reporteruido_introduccion);


                $introduccionTexto = $agente[0]->reporteruido_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);


                //=========== CREAR Y DESCARGAR EL INFORME
                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Ruido.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Ruido.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }

                break;



            case 2: // VIBRACIÓN

                $agente = reportevibracionModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_vibracion.docx')); //Ruta carpeta storage


                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 2) // Vibracion
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportevibracion_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====


                $introduccionTexto = $agente[0]->reportevibracion_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);



                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reportevibracion_introduccion);


                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Vibracion.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Vibracion.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }
                break;


            case 3: // TEMPERATURA

                $agente = reportetemperaturaModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_temperatura.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 3) // Temperatura
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportetemperatura_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reportetemperatura_introduccion);

                $introduccionTexto = $agente[0]->reportetemperatura_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);



                Storage::makeDirectory('reportes/portadas'); //crear directorio
                $plantillaword->saveAs(storage_path('app/reportes/portadas/Temperatura.docx')); //crear archivo word

                return response()->download(storage_path('app/reportes/portadas/Temperatura.docx'))->deleteFileAfterSend(true);
                break;



            case 4: // ILUMINACIÓN
                $agente = reporteiluminacionModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_iluminacion.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 4) // Iluminacion
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporteiluminacion_mes . ' del ' . $agente[0]->reporteiluminacion_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reporteiluminacion_introduccion);

                $introduccionTexto = $agente[0]->reporteiluminacion_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);



                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Iluminacion.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Iluminacion.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }

                break;

            case 8: // VENTILACIÓN Y CALIDAD DEL AIRE

                $agente = reporteaireModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_aire.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 8) // Aire
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO

                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }


                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteaire_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if (
                    $proyecto[0]->requiereContrato == 1
                ) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if (
                    $proyecto[0]->requiereContrato == 1
                ) {
                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reporteaire_introduccion);

                $introduccionTexto = $agente[0]->reporteaire_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);


                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Aire.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Aire.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }
                break;


            case 9: // AGUA

                $agente = reporteaguaModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_agua.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 9) // Agua
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (
                    count($titulo_partida) > 0
                ) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteagua_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reporteagua_introduccion);

                $introduccionTexto = $agente[0]->reporteagua_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);



                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Agua.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Agua.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }
                break;



            case 10: // HIELO
                $agente = reportehieloModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_hielo.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 10) // ruido
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportehielo_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reportehielo_introduccion);


                $introduccionTexto = $agente[0]->reportehielo_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);


                try {
                    Storage::makeDirectory('reportes/ejemplo'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/ejemplo/Ejemplo.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/ejemplo/Ejemplo.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }
                break;


            case 15: // QUÍMICOS

                $agente = reportequimicosModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_quimicos.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 15) // ruido
                    ->orderBy('updated_at', 'DESC')
                    ->get();

                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue('instalación_portada', $reconocimiento[0]->recsensorial_instalacion);

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportequimicos_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $fecha);
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reportequimicos_introduccion);


                $introduccionTexto = $agente[0]->reportequimicos_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);


                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Quimico.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Quimico.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }

                break;

            case 16: // INFRAESTRUCTURA PARA SERVICIOS AL PERSONAL
                $agente = reporteserviciopersonalModel::where('proyecto_id', $proyecto_id)->get();
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/portadas/Plantilla_informe_serviciopersonal.docx')); //Ruta carpeta storage

                // ====== PORTADA EXTERIROR

                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $reconocimiento[0]->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 16) // ruido
                    ->orderBy('updated_at', 'DESC')
                    ->get();


                //PARTE DEL PROYECTO
                if (count($titulo_partida) > 0) {

                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $cliente[0]->NUMERO_CONTRATO);

                    $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
                } else {

                    $plantillaword->setValue('PARTIDA', "");
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }

                $plantillaword->setValue('folio_portada', $proyecto[0]->proyecto_folio);
                $plantillaword->setValue('razon_social_portada', $cliente[0]->cliente_RazonSocial);
                $plantillaword->setValue(
                    'instalación_portada',
                    $reconocimiento[0]->recsensorial_instalacion
                );

                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteserviciopersonal_fecha;
                $plantillaword->setValue(
                    'lugar_fecha_portada',
                    $fecha
                );
                $plantillaword->setValue('PORTADA_FECHA', $fecha);



                //IMAGEN DE LA PORTADA
                if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                    if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                        $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 650, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                    }
                } else {

                    $plantillaword->setValue(
                        'foto_portada',
                        'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA'
                    );
                }


                // ============ PORTADA INTERIOR
                $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
                $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
                $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
                $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
                $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
                $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

                if ($proyecto[0]->requiereContrato == 1) {

                    $plantillaword->setValue(
                        'TITULO_CONTRATO',
                        "Contrato:"
                    );
                    $plantillaword->setValue('CONTRATO', $cliente[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente[0]->DESCRIPCION_CONTRATO);

                    $plantillaword->setValue('PIE_PAGINA', $cliente[0]->CONTRATO_PLANTILLA_PIEPAGINA);
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {

                    $plantillaword->setValue('CONTRATO', "");
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue(
                        'TITULO_CONTRATO',
                        ""
                    );

                    $plantillaword->setValue('PIE_PAGINA', "");
                    $plantillaword->setValue('INFORME_REVISION', "");
                }


                //============= ENCABEZADOS TITULOS
                $NIVEL1 = is_null($recursos[0]->NIVEL1) ? "" : $recursos[0]->NIVEL1 . "<w:br />";
                $NIVEL2 = is_null($recursos[0]->NIVEL2) ? "" : $recursos[0]->NIVEL2 . "<w:br />";
                $NIVEL3 = is_null($recursos[0]->NIVEL3) ? "" : $recursos[0]->NIVEL3 . "<w:br />";
                $NIVEL4 = is_null($recursos[0]->NIVEL4) ? "" : $recursos[0]->NIVEL4 . "<w:br />";
                $NIVEL5 = is_null($recursos[0]->NIVEL5) ? "" : $recursos[0]->NIVEL5;

                $plantillaword->setValue('ENCABEZADO', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
                $plantillaword->setValue(
                    'INSTALACION_NOMBRE',
                    $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5
                );

                //LOGOS DE AS EMPRESAS DE INFORME
                if ($proyecto[0]->requiereContrato == 1) {

                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                            $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    }


                    if ($cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                        if (file_exists(storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                            $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                            $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $cliente[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                        } else {

                            $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                            $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                        }
                    } else {
                        $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    }
                } else {

                    $plantillaword->setValue(
                        'LOGO_DERECHO',
                        'SIN IMAGEN'
                    );
                    $plantillaword->setValue(
                        'LOGO_IZQUIERDO',
                        'SIN IMAGEN'
                    );

                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }

                // ====== INTRODUCCION =====
                // $plantillaword->setValue('INTRODUCCION', $agente[0]->reporteserviciopersonal_introduccion); 


                $introduccionTexto = $agente[0]->reporteserviciopersonal_introduccion;
                $introduccionTextoModificado = introduccion($proyecto, $introduccionTexto);

                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);


                try {
                    Storage::makeDirectory('reportes/portadas'); //crear directorio
                    $plantillaword->saveAs(storage_path('app/reportes/portadas/Servicios.docx')); //crear archivo word

                    return response()->download(storage_path('app/reportes/portadas/Servicios.docx'))->deleteFileAfterSend(true);
                } catch (Exception $e) {
                    $dato["msj"] = 'Error al crear reporte: ' . $e->getMessage();
                    return response()->json($dato);
                }
                break;
            default:
                # code...
                break;
        }
    }
}
