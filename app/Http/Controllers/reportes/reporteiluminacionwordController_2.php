<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Plugins
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Chart;
// use PhpOffice\PhpWord\Shared\Converter;

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWord\TemplateProcessor;

use Illuminate\Support\Facades\Storage;
use DB;
use ZipArchive;

///////////////////MODELOS//////////////////

// Generales
use App\modelos\proyecto\proyectoModel;
use App\modelos\recsensorial\recsensorialModel;

// // Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

// //Revisiones
use App\modelos\reportes\reporterevisionesModel;
// use App\modelos\reportes\reporterevisionesarchivoModel;

// // Tablas datos del reconocimiento
// use App\modelos\recsensorial\recsensorialcategoriaModel;
// use App\modelos\recsensorial\recsensorialareaModel;

// // Tablas estrucura del reporte
// use App\modelos\reportes\reporteiluminacioncatalogoModel;
use App\modelos\reportes\reporteiluminacionModel;

// use App\modelos\reportes\reportedefinicionesModel;
// use App\modelos\reportes\reporteiluminacionpuntosModel;
// use App\modelos\reportes\reporterecomendacionesModel;
// use App\modelos\reportes\reporteequiposutilizadosModel;
// use App\modelos\reportes\reporteiluminacioncategoriaModel;
// use App\modelos\reportes\reporteiluminacionareaModel;
// use App\modelos\reportes\reporteiluminacionareacategoriaModel;
// use App\modelos\reportes\reporteanexosModel;


class reporteiluminacionwordController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,Externo');
    }


    public function datosproyectoreemplazartexto($proyecto, $recsensorial, $texto)
    {
        $meses = ["Vacio", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        $reportefecha = explode("-", $proyecto->proyecto_fechaentrega);

        $texto = str_replace("SUBDIRECCION_NOMBRE", $proyecto->catsubdireccion->catsubdireccion_nombre, $texto);
        $texto = str_replace("GERENCIA_NOMBRE", $proyecto->catgerencia->catgerencia_nombre, $texto);
        $texto = str_replace("ACTIVO_NOMBRE", $proyecto->catactivo->catactivo_nombre, $texto);
        $texto = str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace("INSTALACION_DIRECCION", $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace("INSTALACION_CODIGOPOSTAL", "C.P. ".$recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace("INSTALACION_COORDENADAS", $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace("REPORTE_FECHA_LARGA", $reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], $texto);
        $texto = str_replace("\n\n", "<w:br/><w:br/>", $texto);
        $texto = str_replace("\n", "<w:br/>", $texto);

        return $texto;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function reporteiluminacionword(Request $request)
    {
        try
        {
            // dd($request->all());


            $proyecto_id = $request->proyecto_id;
            $reporteiluminacion_id = $request->reporteiluminacion_id;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            // date_default_timezone_set('America/Mexico_City');


            $agente_id = 4;
            $agente_nombre = "Iluminación";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            // $reporteiluminacion = reporteiluminacionModel::where('proyecto_id', $proyecto_id)->limit(1)->get();


            if ($reporteiluminacion_id > 0)
            {
                $reporteiluminacion  = reporteiluminacionModel::findOrFail($reporteiluminacion_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            }
            else
            {
                return '<h3>Aun no se ha guardado nada para este informe de '.$agente_nombre.', primero debe guardar los datos para poder descargarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_iluminacion.docx'));//Ruta carpeta storage


            // PORTADA
            //================================================================================


            $cancelado_texto = '';
            if ($revision->reporterevisiones_cancelado == 1)
            {
                $cancelado_texto = '<w:br/>INFORME REVISIÓN '.$revision->reporterevisiones_revision.' CANCELADA';
            }


            if ($reporteiluminacion->reporteiluminacion_catsubdireccion_activo == 1)
            {
                $plantillaword->setValue('SUBDIRECCION', $proyecto->catsubdireccion->catsubdireccion_nombre.'<w:br/>');
            }
            else
            {
                $plantillaword->setValue('SUBDIRECCION', '');
            }


            if ($reporteiluminacion->reporteiluminacion_catgerencia_activo == 1)
            {
                $plantillaword->setValue('GERENCIA', $proyecto->catgerencia->catgerencia_nombre.'<w:br/>');
            }
            else
            {
                $plantillaword->setValue('GERENCIA', '');
            }


            if ($reporteiluminacion->reporteiluminacion_catactivo_activo == 1)
            {
                $plantillaword->setValue('ACTIVO', $proyecto->catactivo->catactivo_nombre.'<w:br/>');
            }
            else
            {
                $plantillaword->setValue('ACTIVO', '');
            }


            if ($reporteiluminacion->reporteiluminacion_catregion_activo == 1)
            {
                $plantillaword->setValue('REGION', 'Región '.$proyecto->catregion->catregion_nombre.'<w:br/>');
            }
            else
            {
                $plantillaword->setValue('REGION', '');
            }


            if ($reporteiluminacion->reporteiluminacion_instalacion)
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $reporteiluminacion->reporteiluminacion_instalacion.$cancelado_texto);
            }
            else
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion.$cancelado_texto);
            }
            
            $plantillaword->setValue('CONTRATO', $recsensorial->cliente->cliente_numerocontrato);
            $plantillaword->setValue('PORTADA_FECHA', $reporteiluminacion->reporteiluminacion_fecha);

            
            if (($revision->reporterevisiones_revision+0) > 0)
            {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' Rev-'.$revision->reporterevisiones_revision);
            }
            else
            {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre);
            }


            // INTRODUCCION
            //================================================================================

            
            // dd($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_introduccion));
            // dd(str_replace("\n\n", "<w:br/><w:br/>", $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_introduccion)));

            // $plantillaword->setValue('INTRODUCCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_introduccion));
            $plantillaword->setValue('INTRODUCCION', $this->formateartexto($reporteiluminacion->reporteiluminacion_introduccion));


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CREAR WORD TEMPORAL


            // GUARDAR
            Storage::makeDirectory('reportes/informes'); //crear directorio
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_de_iluminacion_proyecto_'.$proyecto->proyecto_folio.'_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL
            $dato["msj"] = 'Informe creado correctamente';

            
            //--------------------------------------------------------------------------------


            return response()->json($dato);
        }
        catch(Exception $e)
        {
            // respuesta
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    public function formateartexto($texto)
    {
        $parrafos = explode("\n\n", $texto);
        $texto_nuevo = '';
        
        foreach($parrafos as $parrafo)
        {
            $texto_nuevo .= '<w:p>
                                <w:pPr>
                                    <w:jc w:val="both"/>
                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                </w:pPr>
                                <w:t>'.htmlspecialchars($parrafo).'</w:t>
                            </w:p><w:br/>';


            // $texto_nuevo .= $parrafo.'<w:br/><w:br/>';
        }

        return $texto_nuevo;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $revision_id
     * @param  int $ultima_revision
     * @return \Illuminate\Http\Response
    */
    public function reporteiluminacionworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Iluminación.docx';
        $proyecto = proyectoModel::findOrFail($proyecto_id);


        return response()->download(storage_path('app/reportes/informes/Informe_de_iluminacion_proyecto_'.$proyecto->proyecto_folio.'_TEMPORAL.docx'), $agente_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);


        // if (($revision_id+0) == ($ultima_revision+0)) //Descargar y eliminar .ZIP de la carpeta temporal
        // {
        //     $proyecto = proyectoModel::findOrFail($proyecto_id);


        //     $zip_nombre = 'Informe - 1.3 Evaluación de Iluminación en Zonas y Puestos de Trabajo - Proyecto '.$proyecto->proyecto_folio.' + Anexos.zip';


        //     if (Storage::exists('reportes/informes/'.$zip_nombre))
        //     {
        //         return response()->download(storage_path('app/reportes/informes/'.$zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
        //     }
        //     else
        //     {
        //         return '<h3>No se encontró el informe de '.$agente_nombre.', intentelo de nuevo</h3>';
        //     }
        // }
        // else
        // {
        //     $archivo_historial = DB::select('SELECT
        //                                         reporterevisiones.proyecto_id,
        //                                         reporterevisiones.agente_id,
        //                                         reporterevisiones.agente_nombre,
        //                                         reporterevisiones.id,
        //                                         reporterevisiones.reporterevisiones_revision,
        //                                         reporterevisiones.reporterevisiones_concluido,
        //                                         reporterevisiones.reporterevisiones_cancelado,
        //                                         reporterevisionesarchivo.reporterevisionesarchivo_tipo,
        //                                         reporterevisionesarchivo.reporterevisionesarchivo_archivo 
        //                                     FROM
        //                                         reporterevisiones
        //                                         LEFT JOIN reporterevisionesarchivo ON reporterevisiones.id = reporterevisionesarchivo.reporterevisiones_id
        //                                     WHERE
        //                                         reporterevisiones.id = '.$revision_id);


        //     if (count($archivo_historial) > 0)
        //     {
        //         if (Storage::exists($archivo_historial[0]->reporterevisionesarchivo_archivo))
        //         {
        //             return response()->download(storage_path('app/'.$archivo_historial[0]->reporterevisionesarchivo_archivo), "", array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(false);
        //         }
        //         else
        //         {
        //             return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.' 1</h3>';
        //         }
        //     }
        //     else
        //     {
        //         return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.' 2</h3>';
        //     }
        // }
    }
}