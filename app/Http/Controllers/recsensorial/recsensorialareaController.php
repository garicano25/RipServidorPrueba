<?php

namespace App\Http\Controllers\recsensorial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\recsensorial\recsensorialModel;
use App\modelos\recsensorial\recsensorialareaModel;
use App\modelos\recsensorial\recsensorialpruebasModel;
use App\modelos\recsensorial\recsensorialareapruebasModel;
use App\modelos\recsensorial\recsensorialareacategoriasModel;
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\clientes\clienteModel;
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

use Carbon\Carbon;
use DateTime;

//ZONA DE HORARIO
date_default_timezone_set('America/Mexico_City');
date_default_timezone_set('UTC');

class recsensorialareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Externo');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialareatabla($recsensorial_id)
    {
        try {
            // Reconocimiento
            $recsensorial = recsensorialModel::findOrFail($recsensorial_id);

            $tabla = recsensorialareaModel::with(['recsensorialareapruebas', 'recsensorialareapruebas.catprueba', 'recsensorialareacategorias', 'recsensorialareacategorias.categorias'])
                ->where('recsensorial_id', $recsensorial_id)
                ->orderBy('id', 'asc')
                ->get();

            // FORMATEAR FILAS
            $numero_registro = 0;
            foreach ($tabla  as $key => $value) {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                //=======================================

                // obtener las pruebas
                $value['agentes'] = $value->recsensorialareapruebas->pluck('catprueba.catPrueba_Nombre');

                // formatear pruebas
                $cadena = "";
                foreach ($value['agentes']  as $key => $agentes) {
                    $cadena .= "<li>" . $agentes . "</li>";
                }
                $value['agentes'] = $cadena;

                //=======================================

                // obtener categorias
                $value['categorias'] = $value->recsensorialareacategorias->pluck('categorias.recsensorialcategoria_nombrecategoria');

                // formatear pruebas
                $cadena = "";
                foreach ($value['categorias']  as $key => $categorias) {
                    $cadena .= "<li>" . $categorias . "</li>";
                }
                $value['categorias'] = $cadena;

                //=======================================

                // Botones
                if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'Operativo HI'])  && ($recsensorial->recsensorial_bloqueado + 0) == 0) {
                    $value->accion_activa = 1;
                    $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>';
                } else {
                    $value->accion_activa = 0;
                    $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                }
            }

            // respuesta
            $dato['data'] = $tabla;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $area_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialareaparametros($recsensorial_id, $area_id)
    {
        try {
            $listaparametros = DB::select('SELECT
                                                recsensorialpruebas.recsensorial_id,
                                                recsensorialpruebas.catprueba_id,
                                                cat_prueba.catPrueba_Nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(recsensorialareapruebas.catprueba_id, "checked", "") 
                                                    FROM
                                                        recsensorialareapruebas 
                                                    WHERE
                                                        recsensorialareapruebas.recsensorialarea_id = ' . $area_id . ' AND recsensorialareapruebas.catprueba_id = recsensorialpruebas.catprueba_id
                                                    LIMIT 1
                                                ), "") AS checked
                                            FROM
                                                recsensorialpruebas
                                                LEFT JOIN cat_prueba ON recsensorialpruebas.catprueba_id = cat_prueba.id 
                                            WHERE
                                                recsensorialpruebas.recsensorial_id = ' . $recsensorial_id . '
                                            ORDER BY
                                                recsensorialpruebas.catprueba_id ASC');

            // respuesta
            $dato['parametros'] = $listaparametros;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialareacategorias($recsensorial_id)
    {
        try {
            $categorias = DB::select('SELECT
                                            recsensorialcategoria.recsensorial_id,
                                            recsensorialcategoria.id,
                                            -- CONCAT( recsensorialcategoria.recsensorialcategoria_nombrecategoria, " (", recsensorialcategoria.recsensorialcategoria_funcioncategoria, ")" ) AS categoria_nombre 
                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria AS categoria_nombre 
                                        FROM
                                            recsensorialcategoria 
                                        WHERE
                                            recsensorialcategoria.recsensorial_id = ' . $recsensorial_id . ' 
                                        ORDER BY
                                            recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');
            // respuesta
            $dato['categorias'] = $categorias;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
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
    public function recsensorialareacategoriaselegidas($area_id)
    {
        try {
            $categoriaselegidas = DB::select('SELECT
                                                    recsensorialareacategorias.recsensorialarea_id,
                                                    recsensorialareacategorias.recsensorialcategoria_id,
                                                    recsensorialareacategorias.recsensorialareacategorias_actividad,
                                                    recsensorialareacategorias.recsensorialareacategorias_geh,
                                                    recsensorialareacategorias.recsensorialareacategorias_total,
                                                    recsensorialareacategorias.recsensorialareacategorias_tiempoexpo,
                                                    recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo, 
                                                    recsensorialareacategorias.tiempoexpo_quimico, 
                                                    recsensorialareacategorias.frecuenciaexpo_quimico   

                                                FROM
                                                    recsensorialareacategorias
                                                WHERE
                                                    recsensorialareacategorias.recsensorialarea_id = ' . $area_id);

            // respuesta
            $dato['categoriaselegidas'] = $categoriaselegidas;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $recsensorial_id
     * @param  int  $id_seleccionado
     * @return \Illuminate\Http\Response
     */
    public function recsensorialconsultaareas($recsensorial_id, $id_seleccionado, $quimicas)
    {
        try {
            $opciones = '<option value=""></option>';

            if ($quimicas == 0) {

                $tabla = recsensorialareaModel::where('recsensorial_id', $recsensorial_id)->get();

                // colocar numero de registro
                foreach ($tabla  as $key => $value) {
                    if ($id_seleccionado == $value['id']) {
                        $opciones .= '<option value="' . $value['id'] . '" selected>' . $value['recsensorialarea_nombre'] . '</option>';
                    } else {
                        $opciones .= '<option value="' . $value['id'] . '">' . $value['recsensorialarea_nombre'] . '</option>';
                    }
                }
            } else {

                $tabla = DB::select('SELECT a.*
                            FROM recsensorialarea a
                            LEFT JOIN recsensorialareapruebas p ON p.recsensorialarea_id = a.id
                            WHERE recsensorial_id = ? AND p.catprueba_id = 15', [$recsensorial_id]);

                // colocar numero de registro
                foreach ($tabla as $key => $value) {
                    if ($id_seleccionado == $value->id) {
                        $opciones .= '<option value="' . $value->id . '" selected>' . $value->recsensorialarea_nombre . '</option>';
                    } else {
                        $opciones .= '<option value="' . $value->id . '">' . $value->recsensorialarea_nombre . '</option>';
                    }
                }
            }

            // respuesta
            $dato['opciones'] = $opciones;
            $dato["msj"] = 'Informacion consultada correctamente';
            return response()->json($dato);
        } catch (Exception $e) {
            $dato['opciones'] = 0;
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
    public function recsensorialareaeliminar($area_id)
    {
        try {
            // $listaparametros = recsensorialpruebasModel::all();
            $area = recsensorialareaModel::where('id', $area_id)->delete();
            $area = recsensorialareapruebasModel::where('recsensorialarea_id', $area_id)->delete();
            $area = recsensorialareacategoriasModel::where('recsensorialarea_id', $area_id)->delete();

            // respuesta
            $dato['eliminado'] = $area;
            $dato["msj"] = 'Información eliminada correctamente';
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
            if ($request['area_id'] == 0) //nuevo
            {
                // AUTO_INCREMENT
                DB::statement('ALTER TABLE recsensorialarea AUTO_INCREMENT=1');

                // guardar
                $area = recsensorialareaModel::create($request->all());
                $area->recsensorialareapruebassincronizacion()->sync($request->parametro);
                // $area->recsensorialareacategoriassincronizacion()->sync($request->categoria);
                $opcionesSeleccionadas = $request->input('recsensorialarea_generacioncontaminante');

                // guardar categorias
                foreach ($request->categoria as $key => $value) {
                    $sustancia = recsensorialareacategoriasModel::create([
                        'recsensorialarea_id' => $area->id,
                        'recsensorialcategoria_id' => $request->categoria[$key],
                        'recsensorialareacategorias_actividad' => $request->actividad[$key],
                        'recsensorialareacategorias_geh' => $request->geh[$key],
                        'recsensorialareacategorias_total' => $request->total[$key],
                        'recsensorialareacategorias_tiempoexpo' => $request->tiempo[$key],
                        'recsensorialareacategorias_frecuenciaexpo' => $request->frecuencia[$key],

                        'tiempoexpo_quimico' => $request->tiempo_quimicos[$key],
                        'frecuenciaexpo_quimico' => $request->frecuencia_quimicos[$key],

                    ]);
                }

                // mensaje
                $dato["msj"] = 'Informacion guardada correctamente';
                $dato["opciones_seleccionadas"] = $opcionesSeleccionadas;
            } else //editar
            {
                // modificar
                $area = recsensorialareaModel::findOrFail($request['area_id']);
                $area->update($request->all());
                $area->recsensorialareapruebassincronizacion()->sync($request->parametro);
                // $area->recsensorialareacategoriassincronizacion()->sync($request->categoria);

                $areascategoriaseliminar = recsensorialareacategoriasModel::where('recsensorialarea_id', $request['area_id'])->delete(); //eliminar todas las categorias del area

                $opcionesSeleccionadas = $request->input('recsensorialarea_generacioncontaminante');

                // guardar categorias
                foreach ($request->categoria as $key => $value) {
                    $sustancia = recsensorialareacategoriasModel::create([
                        'recsensorialarea_id' => $area->id,
                        'recsensorialcategoria_id' => $request->categoria[$key],
                        'recsensorialareacategorias_actividad' => $request->actividad[$key],
                        'recsensorialareacategorias_geh' => $request->geh[$key],
                        'recsensorialareacategorias_total' => $request->total[$key],
                        'recsensorialareacategorias_tiempoexpo' => $request->tiempo[$key],
                        'recsensorialareacategorias_frecuenciaexpo' => $request->frecuencia[$key],

                        'tiempoexpo_quimico' => $request->tiempo_quimicos[$key],
                        'frecuenciaexpo_quimico' => $request->frecuencia_quimicos[$key],


                    ]);
                }

                // mensaje
                $dato["msj"] = 'Informacion modificada correctamente';
                $dato["opciones_seleccionadas"] = $opcionesSeleccionadas;
            }

            // respuesta
            $dato['area'] = $area;
            return response()->json($dato);
        } catch (Exception $e) {
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param int $recsensorial_id
     * @return \Illuminate\Http\Response
     */
    public function recsensorialpoeword($recsensorial_id)
    {
        try {
            // $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($recsensorial_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);


            // LEER PLANTILLA WORD
            //================================================================================


            $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/reconocimiento_sensorial/Plantilla_tabla_poe.docx')); //Ruta carpeta storage


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


            // ENCABEZADO PLANTILLA
            //================================================================================


            $plantillaword->setValue('INSTALACION_NOMBRE', 'Población ocupacionalmente expuesta');
            // $plantillaword->setValue('FECHA_CREACION', date_format(date_create($recsensorial->recsensorial_fechainicio), 'F Y')); // date('Y-m-d H:i:s')
            setlocale(LC_ALL, "es_MX");
            $plantillaword->setValue('FECHA_CREACION', ucfirst(strftime("%B %Y", strtotime(date("d-m-Y", strtotime($recsensorial->recsensorial_fechainicio)))))); //ucfirst = primera letra mayuscula


            // PIE DE PAGINA PLANTILLA
            //================================================================================

            $folio = '';
            switch (1) {
                case ($recsensorial->recsensorial_alcancefisico > 0 && $recsensorial->recsensorial_alcancequimico > 0):
                    $folio = 'FOLIOS: ' . $recsensorial->recsensorial_foliofisico . ' y ' . $recsensorial->recsensorial_folioquimico;
                    break;
                case ($recsensorial->recsensorial_alcancefisico > 0):
                    $folio = 'FOLIO: ' . $recsensorial->recsensorial_foliofisico;
                    break;
                default:
                    $folio = 'FOLIO: ' . $recsensorial->recsensorial_folioquimico;
                    break;
            }

            $plantillaword->setValue('PIE_PAGINA', str_replace("\r\n", "<w:br/>", str_replace("\n\n", "<w:br/>", $cliente->cliente_plantillapiepagina . '<w:br/>' . $folio)));


            // TABLA'S POE
            //================================================================================


            $font_size = 9;
            $bgColor_encabezado = '#0C3F64';
            $fuente = 'Montserrat';
            $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
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

            // COLUMNAS
            $col_1 = 500;
            $col_2 = 2500;
            $col_3 = 2500;
            $col_4 = 2500;
            $col_5 = 1000;
            $col_6 = 1000;
            $col_7 = 1000;

            // CREAR TABLA
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // ENCABEZADO DE TABLA
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(null, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Instalación', $encabezado_texto); // combina columna
            $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($justificado)->addText($recsensorial->recsensorial_instalacion, $textonegrita); // combina columna

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            $table->addCell($col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Áreas de<w:br/>trabajo', $encabezado_texto);
            $table->addCell($col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Categoría<w:br/>expuesta', $encabezado_texto);
            $table->addCell($col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Actividad', $encabezado_texto);
            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => $bgColor_encabezado))->addTextRun($centrado)->addText('Tiempo de exposición', $encabezado_texto); // combina columna

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($col_1, $continua_fila);
            $table->addCell($col_2, $continua_fila);
            $table->addCell($col_3, $continua_fila);
            $table->addCell($col_4, $continua_fila);
            $table->addCell($col_5, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo', $encabezado_texto);
            $table->addCell($col_6, $encabezado_celda)->addTextRun($centrado)->addText('Frecuencia', $encabezado_texto);
            $table->addCell($col_7, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo<w:br/>total', $encabezado_texto);


            $sql = DB::select('SELECT
                                    recsensorialarea.recsensorial_id,
                                    recsensorialareacategorias.recsensorialarea_id,
                                    recsensorialarea.recsensorialarea_nombre,
                                    recsensorialareacategorias.recsensorialcategoria_id,
                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria,
                                    recsensorialareacategorias.recsensorialareacategorias_actividad,
                                    recsensorialareacategorias.recsensorialareacategorias_tiempoexpo,
                                    recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo,
                                    (recsensorialareacategorias.recsensorialareacategorias_tiempoexpo * recsensorialareacategorias.recsensorialareacategorias_frecuenciaexpo) AS total_expo
                                FROM
                                    recsensorialareacategorias
                                    LEFT JOIN recsensorialarea ON recsensorialareacategorias.recsensorialarea_id = recsensorialarea.id
                                    LEFT JOIN recsensorialcategoria ON recsensorialareacategorias.recsensorialcategoria_id = recsensorialcategoria.id 
                                WHERE
                                    recsensorialarea.recsensorial_id = ' . $recsensorial_id . ' 
                                ORDER BY
                                    recsensorialarea.recsensorialarea_nombre ASC,
                                    recsensorialcategoria.recsensorialcategoria_nombrecategoria ASC');

            $numero_fila = 0;
            $area = 'xxxx';


            foreach ($sql as $key => $value) {
                $table->addRow(); //fila

                if ($area != $value->recsensorialarea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);

                    $table->addCell($col_2, $combinar_fila)->addTextRun($centrado)->addText($value->recsensorialarea_nombre, $texto);
                    $area = $value->recsensorialarea_nombre;
                } else {
                    $table->addCell($col_1, $continua_fila);
                    $table->addCell($col_2, $continua_fila);
                }

                $table->addCell($col_3, $celda)->addTextRun($centrado)->addText($value->recsensorialcategoria_nombrecategoria, $texto);
                $table->addCell($col_4, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_actividad, $texto);
                $table->addCell($col_5, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_tiempoexpo, $texto);
                $table->addCell($col_6, $celda)->addTextRun($centrado)->addText($value->recsensorialareacategorias_frecuenciaexpo, $texto);
                $table->addCell($col_7, $celda)->addTextRun($centrado)->addText($value->total_expo, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_POE', $table);


            // GUARDAR CAMBIOS Y DESCARGAR .DOCX
            //================================================================================


            $word_ruta = storage_path('app/reportes/recsensorial/Tabla POE Reconocmiento (' . $recsensorial->recsensorial_instalacion . ').docx');
            $plantillaword->saveAs($word_ruta); //GUARDAR Y CREAR archivo word TEMPORAL
            return response()->download($word_ruta)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }
}
