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




use DOMDocument;
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

use App\modelos\reportes\matrizlaboral;
use App\modelos\reportes\departamentosmeldraft;



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

/// EXCEL 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use PhpOffice\PhpSpreadsheet\Cell\DataType;




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



    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function reportemeldraftvista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);





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
        return view('reportes.parametros.reportemeldraft', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'estatus'));
    }



    public function tablameldraft($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            if (empty($reporteregistro_id) || $reporteregistro_id == 0) {
                $registro = DB::table('reportequimicosgrupos')
                    ->where('proyecto_id', $proyecto_id)
                    ->select('registro_id')
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($registro) {
                    $reporteregistro_id = $registro->registro_id;
                } else {
                    return response()->json([
                        'data' => [],
                        'total' => 0,
                        'msj' => 'No se encontró registro_id para el proyecto especificado en reportequimicosgrupos'
                    ]);
                }
            }

            $departamento = DB::table('departamentos_meldraft')
                ->where('proyecto_id', $proyecto_id)
                ->value('DEPARTAMENTO_MEL');

            if (!$departamento) {
                $departamento = "No tiene departamento guardado";
            }

            if (($areas_poe + 0) == 1) {
                $puntos = DB::select('
                    SELECT
                        rq.id,
                        rq.proyecto_id,
                        rq.registro_id,
                        IF(cr.catregion_nombre = "N/A", "", cr.catregion_nombre) AS catregion_nombre,
                        IF(cs.catsubdireccion_nombre = "N/A", "", cs.catsubdireccion_nombre) AS catsubdireccion_nombre,
                        IF(cg.catgerencia_nombre = "N/A", "", cg.catgerencia_nombre) AS catgerencia_nombre,
                        IF(ca.catactivo_nombre = "N/A", "", ca.catactivo_nombre) AS catactivo_nombre,
                        (
                            CASE
                                WHEN IF(ca.catactivo_nombre = "N/A", "", ca.catactivo_nombre) != "" THEN ca.catactivo_nombre
                                ELSE cg.catgerencia_nombre
                            END
                        ) AS gerencia_activo,
                        ra.reportearea_instalacion AS reportequimicosarea_instalacion,
                        ra.reportearea_nombre AS reportequimicosarea_nombre,
                        rc.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                        rq.reportequimicosevaluacion_nombre,
                        rq.reportequimicosevaluacion_anios,
                        rq.reportequimicosevaluacion_antiguedadgeneral,
                        rq.reportequimicosevaluacion_antiguedadcategoria,
                        rq.reportequimicosevaluacion_horariotrabajo,
                        rq.reportequimicosevaluacion_ficha,
                        rq.reportequimicosevaluacion_geo,
                        rq.reportequimicosevaluacion_total,
                        rq.reportequimicosevaluacion_punto,
                        rp.reportequimicosevaluacionparametro_parametro AS tipo,
                        rp.reportequimicosevaluacionparametro_valorlimite AS referencia_vle,
                        rp.reportequimicosevaluacionparametro_unidad AS unidad_vle,
                        rp.reportequimicosevaluacionparametro_concentracion AS resultado_concentracion
                    FROM reportequimicosevaluacion rq
                    LEFT JOIN proyecto p ON rq.proyecto_id = p.id
                    LEFT JOIN catregion cr ON p.catregion_id = cr.id
                    LEFT JOIN catsubdireccion cs ON p.catsubdireccion_id = cs.id
                    LEFT JOIN catgerencia cg ON p.catgerencia_id = cg.id
                    LEFT JOIN catactivo ca ON p.catactivo_id = ca.id
                    LEFT JOIN reportearea ra ON rq.reportequimicosarea_id = ra.id
                    LEFT JOIN reportecategoria rc ON rq.reportequimicoscategoria_id = rc.id
                    LEFT JOIN reportequimicosevaluacionparametro rp ON rp.reportequimicosevaluacion_id = rq.id
                    WHERE rq.proyecto_id = ' . $proyecto_id . '
                    AND rq.registro_id = ' . $reporteregistro_id . '
                    ORDER BY rq.reportequimicosevaluacion_punto ASC
                ');
            } else {
                $puntos = DB::select('
                    SELECT
                        rq.id,
                        rq.proyecto_id,
                        rq.registro_id,
                        IF(cr.catregion_nombre = "N/A", "", cr.catregion_nombre) AS catregion_nombre,
                        IF(cs.catsubdireccion_nombre = "N/A", "", cs.catsubdireccion_nombre) AS catsubdireccion_nombre,
                        IF(cg.catgerencia_nombre = "N/A", "", cg.catgerencia_nombre) AS catgerencia_nombre,
                        IF(ca.catactivo_nombre = "N/A", "", ca.catactivo_nombre) AS catactivo_nombre,
                        (
                            CASE
                                WHEN IF(ca.catactivo_nombre = "N/A", "", ca.catactivo_nombre) != "" THEN ca.catactivo_nombre
                                ELSE cg.catgerencia_nombre
                            END
                        ) AS gerencia_activo,
                        ra.reportequimicosarea_instalacion,
                        ra.reportequimicosarea_nombre,
                        rc.reportequimicoscategoria_nombre,
                        rq.reportequimicosevaluacion_nombre,
                        rq.reportequimicosevaluacion_anios,
                        rq.reportequimicosevaluacion_antiguedadgeneral,
                        rq.reportequimicosevaluacion_antiguedadcategoria,
                        rq.reportequimicosevaluacion_horariotrabajo,
                        rq.reportequimicosevaluacion_ficha,
                        rq.reportequimicosevaluacion_geo,
                        rq.reportequimicosevaluacion_total,
                        rq.reportequimicosevaluacion_punto,
                        rp.reportequimicosevaluacionparametro_parametro AS tipo,
                        rp.reportequimicosevaluacionparametro_valorlimite AS referencia_vle,
                        rp.reportequimicosevaluacionparametro_unidad AS unidad_vle,
                        rp.reportequimicosevaluacionparametro_concentracion AS resultado_concentracion
                    FROM reportequimicosevaluacion rq
                    LEFT JOIN proyecto p ON rq.proyecto_id = p.id
                    LEFT JOIN catregion cr ON p.catregion_id = cr.id
                    LEFT JOIN catsubdireccion cs ON p.catsubdireccion_id = cs.id
                    LEFT JOIN catgerencia cg ON p.catgerencia_id = cg.id
                    LEFT JOIN catactivo ca ON p.catactivo_id = ca.id
                    LEFT JOIN reportequimicosarea ra ON rq.reportequimicosarea_id = ra.id
                    LEFT JOIN reportequimicoscategoria rc ON rq.reportequimicoscategoria_id = rc.id
                    LEFT JOIN reportequimicosevaluacionparametro rp ON rp.reportequimicosevaluacion_id = rq.id
                    WHERE rq.proyecto_id = ' . $proyecto_id . '
                    AND rq.registro_id = ' . $reporteregistro_id . '
                    ORDER BY rq.reportequimicosevaluacion_punto ASC
                ');
            }

            $numero_registro = 0;
            foreach ($puntos as $value) {
                $numero_registro++;
                $value->numero_registro = $numero_registro;
                $value->DEPARTAMENTO_MEL = $departamento;

                // Evaluar cumplimiento
                $value->cumplimiento = $this->evaluarCumplimiento(
                    $value->resultado_concentracion,
                    $value->referencia_vle
                );
            }

            return response()->json([
                'data' => $puntos,
                'total' => count($puntos),
                'msj' => 'Datos consultados correctamente'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'total' => 0,
                'msj' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    private function evaluarCumplimiento($concentracion, $valorLimite)
    {
        try {
            if ($concentracion === null || $valorLimite === null) {
                return "-";
            }

            $concStr = trim($concentracion);
            $limStr = trim($valorLimite);

            $concVal = floatval(str_replace(['<', '>', '='], '', $concStr));
            $limVal = floatval(str_replace(['<', '>', '='], '', $limStr));

            if ($limVal == 0) return "No aplica";
            if (str_contains($concStr, '<')) return "Cumple";
            if (str_contains($concStr, '>')) return "No cumple";

            return ($concVal <= $limVal) ? "Cumple" : "No cumple";
        } catch (\Throwable $th) {
            return "Indeterminado";
        }
    }

    public function guardarmeldraft(Request $request)
    {
        try {
            $request->validate([
                'proyecto_id' => 'required|integer',
                'DEPARTAMENTO_MEL' => 'required|string|max:255',
            ]);

            departamentosmeldraft::where('proyecto_id', $request->proyecto_id)->delete();

            $registro = departamentosmeldraft::create([
                'proyecto_id' => $request->proyecto_id,
                'DEPARTAMENTO_MEL' => $request->DEPARTAMENTO_MEL,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departamento actualizado correctamente.',
                'data' => $registro
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarmeldraft($proyecto_id)
    {
        $existe = DB::table('departamentos_meldraft')->where('proyecto_id', $proyecto_id)->exists();

        if ($existe) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No hay registros para generar la MEL.'
        ], 404);
    }

    public function exportarMeldraft($proyecto_id)
    {
        try {
            $registro = DB::table('reportequimicosgrupos')
                ->where('proyecto_id', $proyecto_id)
                ->select('registro_id')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$registro) {
                return response()->json(['success' => false, 'message' => 'No se encontró registro_id para este proyecto.']);
            }

            $reporteregistro_id = $registro->registro_id;

            $departamento = DB::table('departamentos_meldraft')
                ->where('proyecto_id', $proyecto_id)
                ->value('DEPARTAMENTO_MEL') ?? "No tiene departamento guardado";

            $puntos = DB::select("
            SELECT
                rq.id,
                rq.proyecto_id,
                rq.registro_id,
                ra.reportearea_instalacion AS reportequimicosarea_instalacion,
                ra.reportearea_nombre AS reportequimicosarea_nombre,
                rc.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                rq.reportequimicosevaluacion_nombre,
                rq.reportequimicosevaluacion_ficha,
                rq.reportequimicosevaluacion_anios,
                rq.reportequimicosevaluacion_antiguedadgeneral,
                rq.reportequimicosevaluacion_antiguedadcategoria,
                rq.reportequimicosevaluacion_horariotrabajo,
                rp.reportequimicosevaluacionparametro_parametro AS tipo,
                rp.reportequimicosevaluacionparametro_valorlimite AS referencia_vle,
                rp.reportequimicosevaluacionparametro_unidad AS unidad_vle,
                rp.reportequimicosevaluacionparametro_concentracion AS resultado_concentracion
            FROM reportequimicosevaluacion rq
            LEFT JOIN reportearea ra ON rq.reportequimicosarea_id = ra.id
            LEFT JOIN reportecategoria rc ON rq.reportequimicoscategoria_id = rc.id
            LEFT JOIN reportequimicosevaluacionparametro rp ON rp.reportequimicosevaluacion_id = rq.id
            WHERE rq.proyecto_id = $proyecto_id
            AND rq.registro_id = $reporteregistro_id
            ORDER BY rq.reportequimicosevaluacion_punto ASC
        ");

            if (empty($puntos)) {
                return response()->json(['success' => false, 'message' => 'No hay datos para exportar.']);
            }

            $templatePath = storage_path('app/plantillas_reportes/proyecto_infomes/plantillla_meldraft.xlsx');
            if (!file_exists($templatePath)) {
                return response()->json(['success' => false, 'message' => 'No se encontró la plantilla Excel.']);
            }

            $reader = IOFactory::createReader('Xlsx');
            $reader->setIncludeCharts(true);
            $reader->setReadDataOnly(false);
            $spreadsheet = $reader->load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            $blueHeader = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'C5D9F1']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];

            $purpleHeader = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E4DFEC']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];

            $sheet->getStyle('B3:K5')->applyFromArray($blueHeader);
            $sheet->getStyle('L3:O5')->applyFromArray($purpleHeader);

            $borderStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ];

            $filaInicio = 6;
            $fila = $filaInicio;



            foreach ($puntos as $value) {
                $cumplimiento = $this->evaluarCumplimiento(
                    $value->resultado_concentracion,
                    $value->referencia_vle
                );

                $sheet->setCellValueExplicit("C{$fila}", $value->reportequimicosarea_instalacion ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("D{$fila}", $value->reportequimicosarea_nombre ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("E{$fila}", $value->reportequimicosevaluacion_nombre ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("F{$fila}", $value->reportequimicosevaluacion_ficha ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("G{$fila}", $value->reportequimicoscategoria_nombre ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("H{$fila}", $value->reportequimicosevaluacion_anios ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("I{$fila}", $value->reportequimicosevaluacion_antiguedadgeneral ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("J{$fila}", $value->reportequimicosevaluacion_antiguedadcategoria ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("K{$fila}", $value->reportequimicosevaluacion_horariotrabajo ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("L{$fila}", $value->tipo ?? '', DataType::TYPE_STRING);

                $valorVLE = trim(($value->referencia_vle ?? '') . ' ' . ($value->unidad_vle ?? ''));
                $sheet->setCellValueExplicit("M{$fila}", $valorVLE, DataType::TYPE_STRING);

                $sheet->setCellValueExplicit("N{$fila}", $value->resultado_concentracion ?? '', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit("O{$fila}", $cumplimiento ?? '', DataType::TYPE_STRING);

                $sheet->getStyle("B{$fila}:O{$fila}")->applyFromArray($borderStyle);

                $sheet->getRowDimension($fila)->setRowHeight(42.90);

                $fila++;
            }



            $ultimaFila = $fila - 1;
            if ($ultimaFila >= $filaInicio) {
                $sheet->mergeCells("B{$filaInicio}:B{$ultimaFila}");
                $sheet->setCellValue("B{$filaInicio}", $departamento);
                $sheet->getStyle("B{$filaInicio}:B{$ultimaFila}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("B{$filaInicio}:B{$ultimaFila}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$filaInicio}:B{$ultimaFila}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setPreCalculateFormulas(false);

            $fileName = 'Matriz_de_Exposición_Laboral_' . date('Ymd_His') . '.xlsx';
            $filePath = storage_path('app/plantillas_reportes/proyecto_infomes/' . $fileName);
            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el Excel: ' . $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }
    }

    
    
    //////////////// MATIRZ DE RECOMENDACIONES ////////////////

    public function reportematrizrecovista($proyecto_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);


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
        return view('reportes.parametros.reportematrizreco', compact('proyecto', 'recsensorial', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo', 'estatus'));
    }





    // public function matrizrecomendaciones($proyecto_id, $reporteregistro_id, $areas_poe)
    // {
    //     try {
    //         $numero_registro = 0;
    //         $data = [];

    //         // 🔹 Si no viene un registro_id válido, obtener el más reciente
    //         if (empty($reporteregistro_id) || $reporteregistro_id == 0) {
    //             $registro = DB::table('reportequimicosgrupos')
    //                 ->where('proyecto_id', $proyecto_id)
    //                 ->select('registro_id')
    //                 ->orderBy('created_at', 'desc')
    //                 ->first();

    //             if ($registro) {
    //                 $reporteregistro_id = $registro->registro_id;
    //             } else {
    //                 return response()->json([
    //                     'data' => [],
    //                     'success' => false,
    //                     'mensaje' => 'No se encontró registro_id para el proyecto especificado en reportequimicosgrupos.'
    //                 ]);
    //             }
    //         }

    //         // 🔹 Obtener el nombre del departamento
    //         $departamento = DB::table('departamentos_meldraft')
    //             ->where('proyecto_id', $proyecto_id)
    //             ->value('DEPARTAMENTO_MEL') ?? 'No asignado';

    //         // 🔹 Consulta idéntica a la que usa la tabla 5.5 (ajustada para solo devolver campos necesarios)
    //         $areas = DB::select("
    //         SELECT
    //             reportearea.proyecto_id,
    //             reportearea.id,
    //             reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
    //             reportearea.reportearea_nombre AS reportequimicosarea_nombre,
    //             reportearea.reportearea_porcientooperacion,
    //             reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
    //             reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
    //             reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
    //             reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades,
    //             IFNULL((
    //                 SELECT
    //                     IF(reportequimicosareacategoria.reportequimicoscategoria_id, 'activo', '') AS checked
    //                 FROM reportequimicosareacategoria
    //                 WHERE reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
    //                   AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
    //                   AND reportequimicosareacategoria.reportequimicosareacategoria_poe = $reporteregistro_id
    //                 LIMIT 1
    //             ), '') AS activo
    //         FROM reportearea
    //         LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
    //         LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
    //         WHERE reportearea.proyecto_id = $proyecto_id
    //         ORDER BY
    //             reportearea.reportearea_instalacion ASC,
    //             reportearea.reportearea_nombre ASC,
    //             reportecategoria.reportecategoria_orden ASC,
    //             reportecategoria.reportecategoria_nombre ASC
    //     ");

    //         // 🔹 Recorrer resultados como en la tabla 5.5
    //         foreach ($areas as $value) {
    //             // Solo incluir las áreas activas y con porcentaje > 0
    //             if (($value->reportearea_porcientooperacion ?? 0) > 0 && $value->activo) {
    //                 $numero_registro++;

    //                 $data[] = [
    //                     'numero_registro' => $numero_registro,
    //                     'DEPARTAMENTO_MEL' => $departamento,
    //                     'reportequimicosarea_instalacion' => $value->reportequimicosarea_instalacion ?? '-',
    //                     'reportequimicosarea_nombre' => $value->reportequimicosarea_nombre ?? '-',
    //                     'reportequimicoscategoria_nombre' => $value->reportequimicoscategoria_nombre ?? '-',
    //                     'nombre_agente' => 'Químico',
    //                     'recomendaciones' => '-' // (Placeholder, se llenará después)
    //                 ];
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $data,
    //             'mensaje' => 'Datos de matriz de recomendaciones cargados correctamente.'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'mensaje' => 'Error al consultar matriz de recomendaciones: ' . $e->getMessage(),
    //             'linea' => $e->getLine(),
    //             'data' => []
    //         ]);
    //     }
    // }


    public function matrizrecomendaciones($proyecto_id, $reporteregistro_id, $areas_poe)
    {
        try {
            $numero_registro = 0;
            $data = [];
            $idAgente = 15; // Químico

            // 🔹 Si no viene un registro_id válido, obtener el más reciente
            if (empty($reporteregistro_id) || $reporteregistro_id == 0) {
                $registro = DB::table('reportequimicosgrupos')
                    ->where('proyecto_id', $proyecto_id)
                    ->select('registro_id')
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($registro) {
                    $reporteregistro_id = $registro->registro_id;
                } else {
                    return response()->json([
                        'data' => [],
                        'success' => false,
                        'mensaje' => 'No se encontró registro_id para el proyecto especificado en reportequimicosgrupos.'
                    ]);
                }
            }

            // 🔹 Obtener el departamento MEL
            $departamento = DB::table('departamentos_meldraft')
                ->where('proyecto_id', $proyecto_id)
                ->value('DEPARTAMENTO_MEL') ?? 'No asignado';

            // 🔹 Obtener las recomendaciones del agente químico
            $recomendaciones = DB::table('reporterecomendaciones')
                ->where('proyecto_id', $proyecto_id)
                ->where('agente_id', $idAgente)
                ->get();

            // 🔹 Consulta base igual a la tabla 5.5
            $areas = DB::select("
            SELECT
                reportearea.proyecto_id,
                reportearea.id,
                reportearea.reportearea_instalacion AS reportequimicosarea_instalacion,
                reportearea.reportearea_nombre AS reportequimicosarea_nombre,
                reportearea.reportearea_porcientooperacion,
                reporteareacategoria.reportecategoria_id AS reportequimicoscategoria_id,
                reportecategoria.reportecategoria_orden AS reportequimicoscategoria_orden,
                reportecategoria.reportecategoria_nombre AS reportequimicoscategoria_nombre,
                reporteareacategoria.reporteareacategoria_actividades AS reportequimicosareacategoria_actividades,
                IFNULL((
                    SELECT
                        IF(reportequimicosareacategoria.reportequimicoscategoria_id, 'activo', '') AS checked
                    FROM reportequimicosareacategoria
                    WHERE reportequimicosareacategoria.reportequimicosarea_id = reportearea.id
                      AND reportequimicosareacategoria.reportequimicoscategoria_id = reporteareacategoria.reportecategoria_id
                      AND reportequimicosareacategoria.reportequimicosareacategoria_poe = $reporteregistro_id
                    LIMIT 1
                ), '') AS activo
            FROM reportearea
            LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
            LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
            WHERE reportearea.proyecto_id = $proyecto_id
            ORDER BY
                reportearea.reportearea_instalacion ASC,
                reportearea.reportearea_nombre ASC,
                reportecategoria.reportecategoria_orden ASC,
                reportecategoria.reportecategoria_nombre ASC
        ");

            // 🔹 Recorremos áreas y armamos el DataTable
            foreach ($areas as $value) {
                if (($value->reportearea_porcientooperacion ?? 0) > 0 && $value->activo) {
                    $numero_registro++;

                    // 🔸 Armar bloque HTML con switch + textarea para recomendaciones
                    $bloque_recomendaciones = '';
                    foreach ($recomendaciones as $rec) {
                        $bloque_recomendaciones .= '
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input switch-recomendacion" type="checkbox" id="rec_' . $numero_registro . '_' . $rec->id . '" data-id="' . $rec->id . '">
                            <label class="form-check-label" for="rec_' . $numero_registro . '_' . $rec->id . '">' . htmlspecialchars($rec->reporterecomendaciones_descripcion) . '</label>
                        </div>
                    ';
                    }

                    // Opcionalmente podrías agregar un textarea al final
                    $bloque_recomendaciones .= '
                    <textarea class="form-control mt-2" rows="2" placeholder="Observaciones adicionales..."></textarea>
                ';

                    $data[] = [
                        'numero_registro' => $numero_registro,
                        'DEPARTAMENTO_MEL' => $departamento,
                        'reportequimicosarea_instalacion' => $value->reportequimicosarea_instalacion ?? '-',
                        'reportequimicosarea_nombre' => $value->reportequimicosarea_nombre ?? '-',
                        'reportequimicoscategoria_nombre' => $value->reportequimicoscategoria_nombre ?? '-',
                        'nombre_agente' => 'Químico',
                        'recomendaciones' => $bloque_recomendaciones
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'mensaje' => 'Datos de matriz de recomendaciones cargados correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al consultar matriz de recomendaciones: ' . $e->getMessage(),
                'linea' => $e->getLine(),
                'data' => []
            ]);
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
            foreach ($sql as $key => $value){
                $opciones_menu .= '<option value="'.$value->agente_id.'">'.$value->agente_nombre.'</option>';
            }


            // // QUITAR DESPUES DE SUBIR AL SERVIDOR
            // $opciones_menu .= '<option value="1">Ruido</option>';
            // $opciones_menu .= '<option value="2">Vibración</option>';
            // $opciones_menu .= '<option value="3">Temperatura</option>';
            // $opciones_menu .= '<option value="4">Iluminación</option>';
            // $opciones_menu .= '<option value="8">Ventilación y calidad del aire</option>';
            // $opciones_menu .= '<option value="9">Agua</option>';
            // $opciones_menu .= '<option value="10">Hielo</option>';
            // $opciones_menu .= '<option value="15">Químicos</option>';
            // $opciones_menu .= '<option value="16">Infraestructura para servicios al personal</option>';
            // $opciones_menu .= '<option value="22">BEI</option>';
            // $opciones_menu .= '<option value="23">Mapa con matriz</option>';



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



    public function reportematrizlabtablageneral($proyecto_id)
    {
        try {
            $proyecto = proyectoModel::with('recsensorial')->findOrFail($proyecto_id);
            $recsensorial = $proyecto->recsensorial;

            $matrizGuardada = matrizlaboral::where('proyecto_id', $proyecto_id)->get()->keyBy('fila_id');


            if (!$recsensorial) {
                throw new \Exception("No se encontró información de reconocimiento sensorial para este proyecto.");
            }

            $areas = recsensorialareaModel::with([
                'recsensorialareapruebas' => function ($query) {
                    // Solo incluye pruebas cuya catprueba no sea 17
                    $query->whereHas('catprueba', function ($q) {
                        $q->where('id', '!=', 17);
                    });
                },
                'recsensorialareapruebas.catprueba',
                'recsensorialareacategorias.categorias'
            ])
                ->where('recsensorial_id', $recsensorial->id)
                ->whereHas('recsensorialareapruebas.catprueba', function ($q) {
                    // Filtra las áreas para que solo se incluyan si al menos una prueba tiene catprueba diferente de 17
                    $q->where('id', '!=', 17);
                })
                ->orderBy('id', 'asc')
                ->get();



            $filas = [];
            $contadorArea = 1;
            $idsValidos = [1, 2, 3, 4, 8, 15];

            foreach ($areas as $area) {
                $agentes = $area->recsensorialareapruebas->map(function ($prueba) {
                    return [
                        'id' => $prueba->catprueba->id ?? '',
                        'nombre' => $prueba->catprueba->catPrueba_Nombre ?? '',
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

                $indiceQuimico = 0;


                for ($i = 0; $i < $maxFilas; $i++) {
                    $categoria = $categorias[$i];
                    $mostrarSelect = !empty($agentes[$i]['nombre']);
                    $fila_id = $contadorArea . '_' . $i;
                    $idAgente = $agentes[$i]['id'];

                    $agenteTexto = $agentes[$i]['nombre'];


                    $valorLMPNMP = 'N/A';
                    $cumplimiento = '';
                    $medidas = 'N/A';

                    if (!empty($idAgente)) {
                        $reporteAreaIds = DB::table('reportearea')
                            ->where('recsensorialarea_id', $area->id)
                            ->pluck('id');


                        if ($idAgente == 4) {
                            $puntosIluminacion = DB::table('reporteiluminacionpuntos')
                                ->whereIn('reporteiluminacionpuntos_area_id', $reporteAreaIds)
                                ->where('proyecto_id', $proyecto_id);

                            if ($puntosIluminacion->exists()) {
                                $registros = $puntosIluminacion->get([
                                    'id',
                                    'reporteiluminacionpuntos_lux',
                                    'reporteiluminacionpuntos_luxmed1',
                                    'reporteiluminacionpuntos_luxmed2',
                                    'reporteiluminacionpuntos_luxmed3'
                                ]);

                                $valoresComparacion = [];

                                foreach ($registros as $registro) {
                                    // valores medidos de este registro (ignorar null y ceros)
                                    $valores = array_filter([
                                        $registro->reporteiluminacionpuntos_luxmed1,
                                        $registro->reporteiluminacionpuntos_luxmed2,
                                        $registro->reporteiluminacionpuntos_luxmed3
                                    ], fn($v) => !is_null($v) && $v != 0);

                                    if (!empty($valores)) {
                                        // tomar el MENOR de los med1,2,3 válidos
                                        $minMedicion = min($valores);

                                        $valoresComparacion[] = [
                                            'registro_id' => $registro->id ?? null,
                                            'minMedicion' => $minMedicion,
                                            'limite'      => $registro->reporteiluminacionpuntos_lux
                                        ];
                                    }
                                }

                                if (!empty($valoresComparacion)) {
                                    // Tomamos el MÁS BAJO de los mínimos
                                    $seleccionado = collect($valoresComparacion)->sortBy('minMedicion')->first();

                                    $minMedicion = $seleccionado['minMedicion'];
                                    $limite      = $seleccionado['limite'];

                                    $valorLMPNMP  = $minMedicion . ' /' . $limite;
                                    $cumplimiento = ($minMedicion >= $limite) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                                } else {
                                    $valorLMPNMP  = 'No tiene registro';
                                    $cumplimiento = 'FUERA DE NORMA';
                                }
                            } else {
                                $valorLMPNMP  = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        } elseif ($idAgente == 1) {
                            $puntosRuido = DB::table('reporteruidopuntoner')
                                ->whereIn('reporteruidoarea_id', $reporteAreaIds)
                                ->where('proyecto_id', $proyecto_id);

                            if ($puntosRuido->exists()) {
                                $registroMax = $puntosRuido->orderByDesc('reporteruidopuntoner_ner')->first();
                                if ($registroMax) {
                                    $ner = $registroMax->reporteruidopuntoner_ner;
                                    $lmpe = $registroMax->reporteruidopuntoner_lmpe;
                                    $valorLMPNMP = $ner . ' /' . $lmpe;
                                    $cumplimiento = ($ner <= $lmpe) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                                }
                            } else {
                                $valorLMPNMP = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        } elseif ($idAgente == 3) {
                            $registrosTemp = DB::table('reportetemperaturaevaluacion')
                                ->whereIn('reportearea_id', $reporteAreaIds)
                                ->where('proyecto_id', $proyecto_id)
                                ->get();

                            if ($registrosTemp->count() > 0) {
                                $maxValor = null;
                                $lmpeValor = null;

                                foreach ($registrosTemp as $registro) {
                                    $valores = [
                                        $registro->reportetemperaturaevaluacion_I,
                                        $registro->reportetemperaturaevaluacion_II,
                                        $registro->reportetemperaturaevaluacion_III,
                                    ];
                                    $valorMaxLocal = max($valores);
                                    if (is_null($maxValor) || $valorMaxLocal > $maxValor) {
                                        $maxValor = $valorMaxLocal;
                                        $lmpeValor = $registro->reportetemperaturaevaluacion_LMPE;
                                    }
                                }

                                if (!is_null($maxValor) && !is_null($lmpeValor)) {
                                    $valorLMPNMP = $maxValor . ' /' . $lmpeValor;
                                    $cumplimiento = ($maxValor <= $lmpeValor) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                                }
                            } else {
                                $valorLMPNMP = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        } elseif ($idAgente == 8) {
                            $reporteAreaIds = DB::table('reportearea')
                                ->where('recsensorialarea_id', $area->id)
                                ->pluck('id');

                            $evaluaciones = DB::table('reporteaireevaluacion')
                                ->where('proyecto_id', $proyecto_id)
                                ->whereIn('reporteairearea_id', $reporteAreaIds)
                                ->get();

                            if ($evaluaciones->count() > 0) {
                                $fuera = false;
                                $maxValor = 0;

                                foreach ($evaluaciones as $eval) {
                                    $valores = [
                                        preg_replace('/[^\d.]/', '', $eval->reporteaireevaluacion_ct),
                                        preg_replace('/[^\d.]/', '', $eval->reporteaireevaluacion_ctma),
                                        preg_replace('/[^\d.]/', '', $eval->reporteaireevaluacion_hongos),
                                        preg_replace('/[^\d.]/', '', $eval->reporteaireevaluacion_levaduras),
                                    ];

                                    foreach ($valores as $valor) {
                                        $num = is_numeric($valor) ? $valor : 0;
                                        $maxValor = max($maxValor, $num);
                                        if ($num > 500) {
                                            $fuera = true;
                                        }
                                    }
                                }

                                $valorLMPNMP = $maxValor . ' /500';
                                $cumplimiento = $fuera ? 'FUERA DE NORMA' : 'DENTRO DE NORMA';
                            } else {
                                $valorLMPNMP = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        } elseif ($idAgente == 2) {
                            $reporteAreaIds = DB::table('reportearea')
                                ->where('recsensorialarea_id', $area->id)
                                ->pluck('id');

                            $registros = DB::table('reportevibracionevaluacion')
                                ->join('reportevibracionevaluaciondatos', 'reportevibracionevaluacion.id', '=', 'reportevibracionevaluaciondatos.reportevibracionevaluacion_id')
                                ->whereIn('reportevibracionevaluacion.reportearea_id', $reporteAreaIds)
                                ->select(
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az1',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az2',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_az3',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax1',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax2',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ax3',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay1',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay2',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_ay3',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_azlimite',
                                    'reportevibracionevaluaciondatos.reportevibracionevaluaciondatos_axylimite'
                                )
                                ->get();

                            if ($registros->count() > 0) {
                                $maxValor = null;
                                $limiteComparacion = null;
                                $valorSeleccionado = '';
                                $limiteSeleccionado = '';

                                foreach ($registros as $r) {
                                    $valores = [
                                        $r->reportevibracionevaluaciondatos_az1,
                                        $r->reportevibracionevaluaciondatos_az2,
                                        $r->reportevibracionevaluaciondatos_az3,
                                        $r->reportevibracionevaluaciondatos_ax1,
                                        $r->reportevibracionevaluaciondatos_ax2,
                                        $r->reportevibracionevaluaciondatos_ax3,
                                        $r->reportevibracionevaluaciondatos_ay1,
                                        $r->reportevibracionevaluaciondatos_ay2,
                                        $r->reportevibracionevaluaciondatos_ay3,
                                    ];

                                    foreach ($valores as $idx => $valor) {
                                        if (is_numeric($valor)) {
                                            if (is_null($maxValor) || $valor > $maxValor) {
                                                $maxValor = $valor;
                                                $valorSeleccionado = $valor;
                                                $limiteComparacion = $idx <= 2
                                                    ? $r->reportevibracionevaluaciondatos_azlimite
                                                    : $r->reportevibracionevaluaciondatos_axylimite;
                                                $limiteSeleccionado = $limiteComparacion;
                                            }
                                        }
                                    }
                                }

                                if (!is_null($valorSeleccionado) && !is_null($limiteSeleccionado)) {
                                    $valorLMPNMP = $valorSeleccionado . ' /' . $limiteSeleccionado;
                                    $cumplimiento = $valorSeleccionado <= $limiteSeleccionado ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                                }
                            } else {
                                $valorLMPNMP = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        }

                        // Agente 15 (químicos)
                       
                        elseif ($idAgente == 15) {
                            $reporteAreaIds = DB::table('reportearea')
                                ->where('recsensorialarea_id', $area->id)
                                ->pluck('id');

                            $evaluaciones = DB::table('reportequimicosevaluacion')
                                ->where('proyecto_id', $proyecto_id)
                                ->whereIn('reportequimicosarea_id', $reporteAreaIds)
                                ->pluck('id');

                            if ($evaluaciones->isNotEmpty()) {
                                $parametros = DB::table('reportequimicosevaluacionparametro')
                                    ->whereIn('reportequimicosevaluacion_id', $evaluaciones)
                                    ->get()
                                    ->values();

                                if (isset($parametros[$indiceQuimico])) {
                                    $p = $parametros[$indiceQuimico];

                                    $concentracionOriginal = $p->reportequimicosevaluacionparametro_concentracion ?? '';
                                    $limiteOriginal = $p->reportequimicosevaluacionparametro_valorlimite ?? '';

                                    $concentracionNum = preg_replace('/[^\d.]/', '', $concentracionOriginal);
                                    $limiteNum = preg_replace('/[^\d.]/', '', $limiteOriginal);

                                    $concentracionNum = is_numeric($concentracionNum) ? (float)$concentracionNum : null;
                                    $limiteNum = is_numeric($limiteNum) ? (float)$limiteNum : null;

                                    if (!is_null($concentracionNum) && !is_null($limiteNum)) {
                                        // mostramos con el símbolo original, pero comparamos con el valor limpio
                                        $valorLMPNMP = $concentracionOriginal . ' /' . $limiteOriginal;
                                        $cumplimiento = ($concentracionNum <= $limiteNum) ? 'DENTRO DE NORMA' : 'FUERA DE NORMA';
                                    } else {
                                        $valorLMPNMP = 'No tiene registro';
                                        $cumplimiento = 'FUERA DE NORMA';
                                    }
                                } else {
                                    $valorLMPNMP = 'No tiene registro';
                                    $cumplimiento = 'FUERA DE NORMA';
                                }

                                $indiceQuimico++;
                            } else {
                                $valorLMPNMP = 'No tiene registro';
                                $cumplimiento = 'FUERA DE NORMA';
                            }
                        } elseif (in_array($idAgente, [13, 14])) {
                            $valorLMPNMP = 'N/A';
                            $cumplimiento = 'DETERMINAR';
                            $medidas = 'N/A';
                        } 
                        elseif ($idAgente === '' || !in_array($idAgente, $idsValidos)) {
                            $valorLMPNMP = 'N/A';
                            $cumplimiento = 'FUERA DE NORMA';
                            $medidas = 'N/A';
                        }
                    }

                    $registroGuardado = $matrizGuardada[$fila_id] ?? null;

                    $indicePeligroGuardado = $registroGuardado->indice_peligro ?? '';
                    $indiceExposicionGuardado = $registroGuardado->indice_exposicion ?? '';
                    $riesgoGuardado = $registroGuardado->riesgo ?? '';



                    $medidasGuardadas = collect(json_decode($registroGuardado->medidas_json ?? '[]', true));

                    $medidas_array = [];

                    if (!empty($idAgente)) {
                        $recomendaciones = DB::table('reporterecomendaciones')
                            ->where('proyecto_id', $proyecto_id)
                            ->where('agente_id', $idAgente)
                            ->get();

                        foreach ($recomendaciones as $value) {
                            $descripcionOriginal = $value->reporterecomendaciones_descripcion ?? '';
                            $descripcionTexto = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $descripcionOriginal);

                            $yaGuardada = $medidasGuardadas->firstWhere('descripcion', $descripcionTexto);
                            $seleccionado = $yaGuardada['seleccionado'] ?? false;

                            $medidas_array[] = [
                                'descripcion' => $descripcionTexto,
                                'seleccionado' => $seleccionado
                            ];
                        }
                    }


                    $filas[] = [
                        'numero_registro' => $fila_id,
                        'numero_visible' => $contadorArea,
                        'recsensorialarea_nombre' => $i === 0 ? $area->recsensorialarea_nombre . ' (ID: ' . $area->id . ')' : '',
                        'agente' => $agenteTexto,
                        'categoria' => $categoria['nombre'],
                        'recsensorialarea_numerotrabajadores' => $categoria['total'],
                        'recsensorialarea_tiempoexposicion' => $categoria['tiempoexpo'],
                        'recsensorialarea_indicepeligro' => $indicePeligroGuardado,
                        'recsensorialarea_indiceexposicion' => $indiceExposicionGuardado,
                        'recsensorialarea_riesgo' => $riesgoGuardado,
                        'recsensorialarea_lmpnmp' => $valorLMPNMP,
                        'recsensorialarea_cumplimiento' => ($cumplimiento ?: ''),
                        'recsensorialarea_medidas_array' => $medidas_array,
                        'rowspan' => $i === 0 ? $maxFilas : 0,
                        'mostrar_select' => $mostrarSelect,
                    ];
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


    public function guardarMatrizLaboral(Request $request)
    {
        $proyecto_id = $request->proyecto_id;
        $filas = $request->filas;

        if (!$filas || !is_array($filas)) {
            return response()->json(['success' => false, 'message' => 'No se proporcionaron datos válidos.']);
        }

        matrizlaboral::where('proyecto_id', $proyecto_id)->delete();

        foreach ($filas as $fila) {
            $medidas = [];

            if (isset($fila['recsensorialarea_medidas']) && is_array($fila['recsensorialarea_medidas'])) {
                foreach ($fila['recsensorialarea_medidas'] as $medida) {
                    $medidas[] = [
                        'descripcion' => $medida['descripcion'] ?? '',
                        'seleccionado' => filter_var($medida['seleccionado'], FILTER_VALIDATE_BOOLEAN),
                    ];
                }
            }

            matrizlaboral::create([
                'proyecto_id' => $proyecto_id,
                'area_id' => $fila['area_id'] ?? 0,
                'fila_id' => $fila['numero_registro'],
                'agente' => $fila['agente'],
                'categoria' => $fila['categoria'],
                'numero_trabajadores' => $fila['recsensorialarea_numerotrabajadores'] ?? '',
                'tiempo_exposicion' => $fila['recsensorialarea_tiempoexposicion'] ?? '',
                'indice_peligro' => $fila['recsensorialarea_indicepeligro'] ?? '',
                'indice_exposicion' => $fila['recsensorialarea_indiceexposicion'] ?? '',
                'riesgo' => $fila['recsensorialarea_riesgo'] ?? '',
                'valor_lmpnmp' => $fila['recsensorialarea_lmpnmp'] ?? '',
                'cumplimiento' => $fila['recsensorialarea_cumplimiento'] ?? '',
                'medidas_json' => json_encode($medidas, JSON_UNESCAPED_UNICODE),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Matriz guardada correctamente.']);
    }

    public function verificarmatrizlab($proyecto_id)
    {
        $existe = DB::table('matriz_laboral')->where('proyecto_id', $proyecto_id)->exists();

        if ($existe) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No hay registros para este proyecto en la matriz laboral.'
        ], 404);
    }

    

    public function reporteexcelmatrizlab($proyecto_id)
    {
        $registros = DB::table('matriz_laboral')
            ->where('proyecto_id', $proyecto_id)
            ->orderBy('fila_id')
            ->get();

    
        $areasNombres = DB::table('recsensorialarea')->pluck('recsensorialarea_nombre', 'id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Matriz Laboral');

        $encabezados = [
            'Área de Trabajo Evaluada',
            'Tipo de agente/Factor de Riesgo',
            'No. de Trabajadores',
            'Categorías',
            'Tiempo de Exposición (minutos)',
            'Índice de Peligro (IP)',
            'Índice de Exposición (IE)',
            'Riesgo / Prioridad de atención',
            'Nivel Máximo LMP-NMP',
            'Cumplimiento Normativo',
            'Medidas de Control',
        ];

        $sheet->fromArray($encabezados, null, 'A1');
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');

        $sheet->getColumnDimension('A')->setWidth(29);
        $sheet->getColumnDimension('B')->setWidth(21);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(22);
        $sheet->getColumnDimension('J')->setWidth(22);
        $sheet->getColumnDimension('K')->setWidth(85);

        $combinaciones = [
            '1A' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '1B' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '1C' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '1D' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '1E' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '2A' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '2B' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '2C' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '2D' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '2E' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '3A' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '3B' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '3C' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '3D' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
            '3E' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
            '4A' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '4B' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '4C' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '4D' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
            '4E' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
            '5A' => ['texto' => 'Bajo riesgo / Vigilancia para mejora continua', 'color' => 'FFFFFF'],
            '5B' => ['texto' => 'Riesgo medio / Tercera prioridad', 'color' => '00B050'],
            '5C' => ['texto' => 'Alto riesgo / Segunda prioridad', 'color' => 'FFFF00'],
            '5D' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
            '5E' => ['texto' => 'Muy alto / Primera prioridad', 'color' => 'FF0000'],
        ];

        $registros = $registros->values();
        $areaActual = null;
        foreach ($registros as $registro) {
            if ($registro->area_id != 0) {
                $areaActual = $registro->area_id;
            }
            $registro->area_id_real = $areaActual;
        }

        $filaExcel = 2;
        $areas = $registros->groupBy('area_id_real');

        foreach ($areas as $area_id => $filasArea) {
            $areaNombre = $areasNombres[$area_id] ?? 'Área no encontrada';

            $agentes = collect($filasArea);
            $categorias = collect($filasArea)
                ->unique(function ($item) {
                    return $item->categoria . '|' . $item->numero_trabajadores . '|' . $item->tiempo_exposicion;
                })
                ->map(function ($item) {
                    return [
                        'categoria' => $item->categoria,
                        'numero_trabajadores' => $item->numero_trabajadores,
                        'tiempo_exposicion' => $item->tiempo_exposicion
                    ];
                })->values();

            $numAgentes = $agentes->count();
            $numCategorias = $categorias->count();

            $sheet->mergeCells("A{$filaExcel}:A" . ($filaExcel + $numAgentes - 1));
            $sheet->setCellValue("A{$filaExcel}", $areaNombre);
            $sheet->getStyle("A{$filaExcel}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A{$filaExcel}")->getAlignment()->setWrapText(true);

            $reparto = floor($numAgentes / $numCategorias);
            $resto = $numAgentes % $numCategorias;
            $bloques = [];
            for ($i = 0; $i < $numCategorias; $i++) {
                $bloques[] = $reparto + ($i < $resto ? 1 : 0);
            }

            $inicioBloque = $filaExcel;
            foreach ($categorias as $index => $categoria) {
                $rango = $bloques[$index];
                $finBloque = $inicioBloque + $rango - 1;

                $sheet->mergeCells("C{$inicioBloque}:C{$finBloque}");
                $sheet->setCellValue("C{$inicioBloque}", $categoria['numero_trabajadores']);

                $sheet->mergeCells("D{$inicioBloque}:D{$finBloque}");
                $sheet->setCellValue("D{$inicioBloque}", $categoria['categoria']);

                $sheet->mergeCells("E{$inicioBloque}:E{$finBloque}");
                $sheet->setCellValue("E{$inicioBloque}", $categoria['tiempo_exposicion']);

                $inicioBloque = $finBloque + 1;
            }

            foreach ($agentes as $registro) {
                $medidasTexto = '';
                $medidas = json_decode($registro->medidas_json, true);
                if (is_array($medidas)) {
                    foreach ($medidas as $medida) {
                        if (!empty($medida['seleccionado'])) {
                            $medidasTexto .= "- {$medida['descripcion']}\n";
                        }
                    }
                }

                $sheet->setCellValue("B{$filaExcel}", $registro->agente);
                $sheet->setCellValue("F{$filaExcel}", $registro->indice_peligro);
                $sheet->setCellValue("G{$filaExcel}", $registro->indice_exposicion);

                $clave = "{$registro->indice_peligro}{$registro->indice_exposicion}";
                if (isset($combinaciones[$clave])) {
                    $color = $combinaciones[$clave]['color'];
                    $texto = $combinaciones[$clave]['texto'];

                    $sheet->setCellValue("H{$filaExcel}", $texto);
                    $sheet->getStyle("H{$filaExcel}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($color);
                } else {
                    $sheet->setCellValue("H{$filaExcel}", $registro->riesgo);
                }

                $sheet->setCellValue("I{$filaExcel}", $registro->valor_lmpnmp);
                $sheet->setCellValue("J{$filaExcel}", $registro->cumplimiento);
                $sheet->setCellValue("K{$filaExcel}", $medidasTexto);
                $sheet->getStyle("K{$filaExcel}")->getAlignment()->setWrapText(true);

                $filaExcel++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Matriz_Laboral_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return Response::download($temp_file, $fileName)->deleteFileAfterSend(true);
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
                    $readonly_required = '';
                } else {
                    $readonly_required = '';
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

    public function obtenermeldraft($ID)
    {
        try {
            $opciones_select = '<option value="">&nbsp;</option>';
            $html  = '<option value="">&nbsp;</option>';

            $departamento_guardado = DB::table('departamentos_meldraft')
                ->where('proyecto_id', $ID)
                ->value('DEPARTAMENTO_MEL');

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

            $niveles = DB::select('
                SELECT 
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
                    $opciones_select .= '<option value="" disabled>Proyecto vinculado sin Estructura organizacional para mostrar</option>';
                } else {
                    $texto = $value->ETIQUETA . ' : ' . $value->OPCION;
                    if ($value->NIVEL != 0) {
                        $texto .= ' [Nivel ' . $value->NIVEL . ']';
                    }

                    $selected = ($departamento_guardado && $departamento_guardado == $value->OPCION) ? 'selected' : '';

                    $opciones_select .= '<option value="' . $value->OPCION . '" ' . $selected . '>' . $texto . '</option>';
                }
            }

            // 🔹 Armar respuesta
            $dato = [
                "opciones" => $opciones_select,
                "departamento_guardado" => $departamento_guardado ?? null,
                "data" => $info ?: 'No se encontraron datos'
            ];

            return response()->json($dato);
        } catch (Exception $e) {
            return response()->json([
                "msj" => 'Error ' . $e->getMessage()
            ], 500);
        }
    }
}
