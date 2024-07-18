<?php

namespace App\Http\Controllers\programa;

use App\modelos\programa\Programa;
use App\modelos\programa\Actividad;
use App\modelos\proyecto\proyectoModel;
use App\modelos\proyecto\proyectoordentrabajoModel;
use App\modelos\proyecto\proyectoordentrabajodatosModel;


use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;


use App\Http\Requests\ExcelRequest;
use Carbon\Carbon;
use DateTime;
use Storage;
use PHPExcel_Worksheet_Drawing;
use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class programaController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $programa = Programa::findOrFail($id);
        $proyecto = proyectoModel::findOrFail($programa->proyecto_id);
        $ruta = 'programa/'.$proyecto->proyecto_folio.'/'.$programa->actividad_id.'_'.$programa->programa_Actividad;
        if($request->file('Archivos'))
        {
            $extension = $request->file('Archivos')->getClientOriginalExtension();
            $request['programa_Evidencia'] = $request->file('Archivos')->storeAs($ruta, 'documento_'.$id.'.'.$extension);
        }
        $programa->update($request->all());
        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function programatrabajoexcel($proyecto_id,$proyectoordentrabajo_id)
    {
        //
        $proyecto = proyectoModel::findOrFail($proyecto_id);
        
       
        $reader = IOFactory::createReader('Xlsx');   
        $spreadsheet = $reader->load(public_path('template/Programa.xlsx'));
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->getStyle('A10')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('002060');
        $spreadsheet->getActiveSheet()->getStyle('A10')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $spreadsheet->getActiveSheet()->getStyle('A17')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $spreadsheet->getActiveSheet()->getStyle('A43')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $spreadsheet->getActiveSheet()->getStyle('A50')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $spreadsheet->getActiveSheet()->getStyle('A61')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(11);


        $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
        $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
        $conditional1->setText('Atrazado');
        $conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $conditional1->getStyle()->getFont()->setBold(true);

        $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
        $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
        $conditional2->setText('Atendido');
        $conditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
        $conditional2->getStyle()->getFont()->setBold(true);

        $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT);
        $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT);
        $conditional3->setText('Proceso');
        $conditional3->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $conditional3->getStyle()->getFont()->setBold(true);

        $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('I12')->getConditionalStyles();
        $conditionalStyles[] = $conditional1;
        $conditionalStyles[] = $conditional2;
        $conditionalStyles[] = $conditional3;
        $spreadsheet->getActiveSheet()->getStyle('I12:I15')->setConditionalStyles($conditionalStyles);
        $spreadsheet->getActiveSheet()->getStyle('I45:I49')->setConditionalStyles($conditionalStyles);
        $spreadsheet->getActiveSheet()->getStyle('I52:I55')->setConditionalStyles($conditionalStyles);
        $spreadsheet->getActiveSheet()->getStyle('I65:I200')->setConditionalStyles($conditionalStyles);

        // $spreadsheet->getActiveSheet()
        // ->duplicateStyle(
        //     $spreadsheet->getActiveSheet()->getStyle('I12'),
        //     'I13:I15'
        // );
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,2,$proyecto->proyecto_clienteinstalacion);
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(8,3,$proyecto->proyecto_folio);
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2,7,$proyecto->proyecto_ordenservicio);
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,7,$proyecto->proyecto_clienteinstalacion);
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2,8,$proyecto->proyecto_fechainicio);
        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,8,$proyecto->proyecto_fechaentrega);
        

        $fechahoy = new DateTime();
        $fechahoy = $fechahoy->format('Y-m-d');
        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        $filas = 12;
        $fondo = 1;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,($key+1).") ".$value->programa_Actividad);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->programa_FinPrograma);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$filas,$value->programa_FinReal);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$filas,$value->programa_Responsable);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                if ($value->programa_Estatus == 1) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atendido');
                }
                else
                {
                    if ($fechahoy > $value->programa_FinPrograma) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atrazado');
                    }
                    else
                    {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                    }
                }
                if ($fondo == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 0;
                }
                else {
                    // $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 1;
                }
                
                $filas++;
        }
        // $programa = Programa::with(['proyectoordentrabajodatos'])->where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Muestreo']])->get();
        $programa = proyectoordentrabajodatosModel::with(['proveedor','proyectoordentrabajo.proyecto'])->where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        $filas = 19;
        $fondo = 1;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,($key+1).") ".$value->proyectoordentrabajodatos_agentenombre);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$filas,$value->proyectoordentrabajodatos_agentepuntos);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->proveedor->proveedor_NombreComercial);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$filas,"");
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,$proyecto->proyecto_fechaentrega);
                if ($fondo == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 0;
                }
                else {
                    // $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 1;
                }
                $filas++;
        }
        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        $filas = 45;
        $fondo = 1;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,($key+1).") ".$value->programa_Actividad);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->programa_FinPrograma);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$filas,$value->programa_FinReal);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$filas,$value->programa_Responsable);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                if ($value->programa_Estatus == 1) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atendido');
                }
                else
                {
                    if ($fechahoy > $value->programa_FinPrograma) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atrazado');
                    }
                    else
                    {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                    }
                }
                if ($fondo == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 0;
                }
                else {
                    // $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 1;
                }
                
                $filas++;
        }
        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Finalizacion']])->get();
        $filas = 52;
        $fondo = 1;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,($key+1).") ".$value->programa_Actividad);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->programa_FinPrograma);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$filas,$value->programa_FinReal);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$filas,$value->programa_Responsable);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                if ($value->programa_Estatus == 1) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atendido');
                }
                else
                {
                    if ($fechahoy > $value->programa_FinPrograma) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atrazado');
                    }
                    else
                    {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                    }
                }
                if ($fondo == 1) {
                    $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 0;
                }
                else {
                    // $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                    $fondo = 1;
                }
                
                $filas++;
        }

        // $programa = Programa::with(['proyectoordentrabajodatos'])->where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Muestreo']])->get();
        $filas = 63;
        $fondo = 1;
        
        $ordentrabajo = proyectoordentrabajodatosModel::with(['proveedor','proyectoordentrabajo.proyecto','programa'])->where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        foreach ($ordentrabajo as $actg => $orden) {
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,($actg+1).") ".$orden->proyectoordentrabajodatos_agentenombre);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$filas,$orden->proyectoordentrabajodatos_agentepuntos." Punto(s)");
            $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('88BD23');
            $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFont()->setBold(true);
            $filas++;
            foreach ($orden->programa as $key => $value) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1,$filas,"     ".($key+1).") ".$value->programa_Actividad);
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->programa_FinPrograma);
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6,$filas,$value->programa_FinReal);
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(7,$filas,$value->programa_Responsable);
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                    if ($value->programa_Estatus == 1) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atendido');
                    }
                    else
                    {
                        if ($fechahoy > $value->programa_FinPrograma) {
                            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Atrazado');
                        }
                        else
                        {
                            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9,$filas,'Proceso');
                        }
                    }
                    if ($fondo == 1) {
                        $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                        $fondo = 0;
                    }
                    else {
                        // $spreadsheet->getActiveSheet()->getStyle('A'.$filas.':I'.$filas)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
                        $fondo = 1;
                    }
                    $filas++;
            }
        }
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $spreadsheet->getActiveSheet()->getStyle('A61'.':I'.$filas)->applyFromArray($styleArray);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         // $writer->setIncludeCharts(true);
        $writer->save(storage_path('app/programa/ProgramaReal_'.$proyecto->proyecto_folio.'.xlsx'));

        // dd($evaluacion);
        // dd(Storage::url('public/Template/'.$NombreDocumento.'.xlsx'));
        return Storage::response('programa/ProgramaReal_'.$proyecto->proyecto_folio.'.xlsx');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $proyecto_id
     * @param  int  $proyecto_id
     * @return \Illuminate\Http\Response
     */
    public function programatrabajoexcelcliente($proyecto_id,$proyectoordentrabajo_id)
    {
        $proyecto = proyectoModel::findOrFail($proyecto_id);
        $reader = IOFactory::createReader('Xlsx');   
        $spreadsheet = $reader->load(public_path('template/Programacliente.xlsx'));
        $spreadsheet->setActiveSheetIndex(0);
        $programa = proyectoordentrabajodatosModel::with(['proveedor','proyectoordentrabajo.proyecto'])->where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        $filas = 12;
        $filaFinal = 0;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2,$filas,$value->proyectoordentrabajodatos_agentenombre);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3,$filas,$value->proyectoordentrabajodatos_agentepuntos);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(4,$filas,"");
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(5,$filas,$value->proveedor->proveedor_NombreComercial);
                $filas++;
                $filaFinal = $filas;
        }
        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Gestion']])->get();
        $columna = 6;
        $ultimacolumna = 0;
        foreach ($programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,12,$value->programa_Actividad);
                $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,12,($columna+$value->programa_DuracionPrograma-1),$filaFinal-1);
                $columna = $columna + $value->programa_DuracionPrograma;
                $ultimacolumna = $columna;
        }
        $agentes = proyectoordentrabajodatosModel::with(['proveedor','proyectoordentrabajo.proyecto','programa'])->where('proyectoordentrabajo_id',$proyectoordentrabajo_id)->get();
        $filas = 12;
        $columnaMuestreo = 0;
        foreach ($agentes as $key => $agente) {
            $columna = $ultimacolumna;
            foreach ($agente->programa as $key => $value) {
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,$filas,$value->programa_Actividad);
                $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,$filas,($columna+$value->programa_DuracionPrograma-1),$filas);
                $columna = $columna + $value->programa_DuracionPrograma;
                $columnaMuestreo = $columna;
            }
            $filas++;
        }

        $programa = Programa::where([['proyecto_id',$proyecto_id],['proyectoordentrabajo_id',$proyectoordentrabajo_id],['programa_Clasificacion','Resultados']])->get();
        $columna = $columnaMuestreo;
        $columnaResultados = 0;
        foreach ($programa as $key => $value) {
                if ($value->actividad_id == 9) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,12,$value->programa_Actividad);
                    $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,12,($columna+$value->programa_DuracionPrograma-1),$filaFinal-1);
                }
                elseif ($value->actividad_id == 10) {

                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,$filaFinal,$value->programa_Actividad);
                    $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,$filaFinal,($columna+$value->programa_DuracionPrograma-1),$filaFinal);
                }
                elseif ($value->actividad_id == 11) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,$filaFinal,$value->programa_Actividad);
                    $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,$filaFinal,($columna+$value->programa_DuracionPrograma-1),$filaFinal+1);
                }
                elseif ($value->actividad_id == 12) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($columna,$filaFinal+1,$value->programa_Actividad);
                    $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow($columna,$filaFinal+1,($columna+$value->programa_DuracionPrograma-1),$filaFinal+1);
                }
                
                $columna = $columna + $value->programa_DuracionPrograma;
                $columnaResultados = $columna;
        }



        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         // $writer->setIncludeCharts(true);
        $writer->save(storage_path('app/programa/ProgramaRealCliente_'.$proyecto->proyecto_folio.'.xlsx'));

        // dd($evaluacion);
        // dd(Storage::url('public/Template/'.$NombreDocumento.'.xlsx'));
        return Storage::response('programa/ProgramaRealCliente_'.$proyecto->proyecto_folio.'.xlsx');
    }
}
