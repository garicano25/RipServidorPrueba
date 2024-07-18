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
        $this->middleware('roles:Superusuario,Administrador,Coordinador,Operativo HI,Almacén,Compras');
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

        if (($proyecto->recsensorial->recsensorial_tipocliente + 0) == 1 && ($proyecto->recsensorial_id == NULL || $proyecto->catregion_id == NULL || $proyecto->catsubdireccion_id == NULL || $proyecto->catgerencia_id == NULL || $proyecto->catactivo_id == NULL || $proyecto->proyecto_clienteinstalacion == NULL || $proyecto->proyecto_fechaentrega == NULL)) {
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


            if (($total_categorias[0]->TOTAL + 0) == 0) {
                $recsensorial_categorias = recsensorialcategoriaModel::where('recsensorial_id', $proyecto->recsensorial_id)
                    ->orderBy('recsensorialcategoria_nombrecategoria', 'ASC')
                    ->get();


                DB::statement('ALTER TABLE reportecategoria AUTO_INCREMENT = 1;');


                foreach ($recsensorial_categorias as $key => $value) {
                    $categoria = reportecategoriaModel::create([
                        'proyecto_id' => $proyecto_id, 'recsensorialcategoria_id' => $value->id, 'reportecategoria_nombre' => $value->recsensorialcategoria_nombrecategoria
                    ]);
                }
            }


            // COPIAR AREAS DEL RECONOCIMIENTO SENSORIAL
            //===================================================


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


                foreach ($recsensorial_areas as $key => $value) {
                    $area = reporteareaModel::create([
                        'proyecto_id' => $proyecto_id, 'recsensorialarea_id' => $value->id, 'reportearea_nombre' => $value->recsensorialarea_nombre, 'reportearea_instalacion' => $proyecto->proyecto_clienteinstalacion
                    ]);
                }
            }


            //===================================================


            // $recsensorial = recsensorialModel::with(['catcontrato', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);

            // Catalogos
            $catregion = catregionModel::get();
            $catsubdireccion = catsubdireccionModel::orderBy('catsubdireccion_nombre', 'ASC')->get();
            $catgerencia = catgerenciaModel::orderBy('catgerencia_nombre', 'ASC')->get();
            $catactivo = catactivoModel::orderBy('catactivo_nombre', 'ASC')->get();

            // Vista
            return view('reportes.parametros.reportepoe', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'));
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
			r.recsensorial_folioquimico as RecSensorialFolioQuimico
        FROM serviciosProyecto sp 
        LEFT JOIN proyecto p ON sp.PROYECTO_ID = p.id
        LEFT JOIN recsensorial r ON r.id  = p.recsensorial_id
        WHERE sp.HI_INFORME = 1
        ");

            $proyectoID = null;
            foreach ($proyectos as $proyecto) {
                $proyectoID = $proyecto->ProyectoID;
                $reconocimientoQuimico = $proyecto->RecSensorialFolioQuimico ? '[' . $proyecto->RecSensorialFolioQuimico . ']' : '[No tiene folio de reconocimiento químico]';
                $reconocimientoFisico = $proyecto->RecSensorialFolioFisico ? '[' . $proyecto->RecSensorialFolioFisico . ']' : '[No tiene folio de reconocimiento físico]';


                $opciones_select .= '<option value="' . $proyectoID . '">Folio proyecto [' .
                    $proyecto->ProyectoFolio . '], Reconocimiento ' .
                    $reconocimientoQuimico . ', ' .
                    $reconocimientoFisico . '</option>';
            }

            $dato['opciones'] = $opciones_select;
            $dato['proyecto_id'] = $proyectoID;
            $dato["msj"] = 'Datos consultados correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['opciones'] = $opciones_select;
            return response()->json($dato);
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
                                                    WHEN catprueba_id = 5 THEN "" -- "Radiaciones ionizantes"
                                                    WHEN catprueba_id = 6 THEN "" -- "Radiaciones no ionizantes"
                                                    WHEN catprueba_id = 7 THEN "" -- "Presiones ambientales anormales"
                                                    WHEN catprueba_id = 8 THEN "Ventilación y calidad del aire"
                                                    WHEN catprueba_id = 9 THEN "Agua"
                                                    WHEN catprueba_id = 10 THEN "Hielo"
                                                    WHEN catprueba_id = 11 THEN "" -- "Alimentos"
                                                    WHEN catprueba_id = 12 THEN "" -- "Superficies"
                                                    WHEN catprueba_id = 13 THEN "" -- "Riesgos ergonómicos"
                                                    WHEN catprueba_id = 14 THEN "" -- "Factores psicosociales"
                                                    WHEN catprueba_id = 15 THEN "Químicos"
                                                    WHEN catprueba_id = 16 THEN "Infraestructura para servicios al personal"
                                                    WHEN catprueba_id = 17 THEN "" -- "Mapa de riesgos"
                                                    ELSE ""
                                                END
                                            ) AS agente_nombre
                                        FROM
                                            proyectoproveedores
                                        WHERE
                                            proyectoproveedores.proyecto_id = ? 
                                            AND proyectoproveedores.proyectoproveedores_tipoadicional < 2
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

            $opciones_menu = '<option value="">Seleccione</option>';
            $opciones_menu .= '<option value="0">POE PROYECTO</option>';

            if (!auth()->user()->hasRoles(['CoordinadorPsicosocial', 'CoordinadorErgonómico'])) {
                foreach ($sql as $key => $value) {
                    $opciones_menu .= '<option value="' . $value->agente_id . '">' . $value->agente_nombre . '</option>';
                }
            }

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
    public function reportecategoriatabla($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::findOrFail($proyecto_id);


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

                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if (($proyecto->proyecto_concluido + 0) == 0 && auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
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
    public function reporteareacategorias($proyecto_id, $reportearea_id)
    {
        try {
            $areacategorias = DB::select('SELECT
                                                reportecategoria.proyecto_id,
                                                reportecategoria.id,
                                                reportecategoria.reportecategoria_nombre,
                                                reportecategoria.reportecategoria_orden,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteareacategoria.reportecategoria_id, "checked", "")
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = ' . $reportearea_id . ' 
                                                        AND reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ), "") AS checked,
                                                (
                                                    SELECT
                                                        reporteareacategoria.reporteareacategoria_total
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = ' . $reportearea_id . ' 
                                                        AND reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ) AS total,
                                                (
                                                    SELECT
                                                        reporteareacategoria.reporteareacategoria_geh
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = ' . $reportearea_id . ' 
                                                        AND reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ) AS geh,
                                                (
                                                    SELECT
                                                        reporteareacategoria.reporteareacategoria_actividades 
                                                    FROM
                                                        reporteareacategoria
                                                    WHERE
                                                        reporteareacategoria.reportearea_id = ' . $reportearea_id . ' 
                                                        AND reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                    LIMIT 1
                                                ) AS actividades
                                            FROM
                                                reportecategoria
                                            WHERE
                                                reportecategoria.proyecto_id = ' . $proyecto_id . ' 
                                            ORDER BY
                                                reportecategoria.reportecategoria_orden ASC,
                                                reportecategoria.reportecategoria_nombre ASC');


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

                // $areacategorias_lista .= '<tr>
                //                             <td with="">
                //                                 <div class="switch" style="border: 0px #000 solid;">
                //                                     <label>
                //                                         <input type="checkbox" name="checkbox_reportecategoria_id[]" value="'.$value->id.'" '.$value->checked.' onchange="activa_areacategoria(this, '.$numero_registro.');"/>
                //                                         <span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>
                //                                     </label>
                //                                 </div>
                //                             </td>
                //                             <td with="240">
                //                                 '.$value->reportecategoria_nombre.'
                //                             </td>
                //                             <td with="">
                //                                 <input type="number" min="1" class="form-control areacategoria_'.$numero_registro.'" name="reporteareacategoria_total_'.$value->id.'" value="'.$value->total.'" '.$readonly_required.'>
                //                             </td>
                //                             <td with="">
                //                                 <input type="number" min="1" class="form-control areacategoria_'.$numero_registro.'" name="reporteareacategoria_geh_'.$value->id.'" value="'.$value->geh.'" '.$readonly_required.'>
                //                             </td>
                //                             <td with="">
                //                                 <textarea rows="2" class="form-control areacategoria_'.$numero_registro.'" name="reporteareacategoria_actividades_'.$value->id.'" '.$readonly_required.'>'.$value->actividades.'</textarea>
                //                             </td>
                //                         </tr>';


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


            //==========================================


            // $areas = reporteareaModel::where('proyecto_id', $proyecto_id)
            //                         ->orderBy('reportearea_nombre', 'ASC')
            //                         ->get();


            $areas = DB::select('SELECT
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


                $value->boton_editar = '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>';


                if (($proyecto->proyecto_concluido + 0) == 0 && auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador'])) {
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
                } else {
                    $value->boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
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
                                'reportearea_id' => $area->id, 'reportecategoria_id' => $value["reportecategoria_id"], 'reporteareacategoria_total' => $value["reporteareacategoria_total"], 'reporteareacategoria_geh' => $value["reporteareacategoria_geh"], 'reporteareacategoria_actividades' => $value["reporteareacategoria_actividades"]
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
                                'reportearea_id' => $area->id, 'reportecategoria_id' => $value["reportecategoria_id"], 'reporteareacategoria_total' => $value["reporteareacategoria_total"], 'reporteareacategoria_geh' => $value["reporteareacategoria_geh"], 'reporteareacategoria_actividades' => $value["reporteareacategoria_actividades"]
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
}
