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
use App\modelos\clientes\clienteModel;


// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

//Revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;

// Modelos estructura reporte
use App\modelos\reportes\reporteaguacatalogoModel;
use App\modelos\reportes\reporteaguaModel;
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteaguacategoriaModel;
use App\modelos\reportes\reporteaguaareaModel;
//----------------------------------------------------------
use App\modelos\recsensorial\catparametroaguacaracteristicaModel;
use App\modelos\reportes\reporteaguaevaluacionModel;
use App\modelos\reportes\reporteaguaevaluacionparametrosModel;
use App\modelos\reportes\reporteaguaevaluacioncategoriasModel;
use App\modelos\reportes\reporteaguamaterialModel;
//----------------------------------------------------------
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;


class reporteaguawordController extends Controller
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

        $texto = str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace("INSTALACION_DIRECCION", $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace("INSTALACION_CODIGOPOSTAL", "C.P. ".$recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace("INSTALACION_COORDENADAS", $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace("REPORTE_FECHA_LARGA", $reportefecha[2]." de ".$meses[($reportefecha[1]+0)]." del año ".$reportefecha[0], $texto);
        // $texto = str_replace("\n\n", "<w:br/><w:br/>", $texto);
        // $texto = str_replace("\n", "<w:br/>", $texto);

        // return $texto;


        $parrafos = explode('\n\n', $texto);
        $texto_nuevo = '';


        foreach($parrafos as $key => $parrafo)
        {
            if (($key+0) < (count($parrafos) -1))
            {
                $text = explode("\n", $parrafo);

                foreach($text as $key2 => $parrafo2)
                {
                    if (($key2+0) < (count($text) -1))
                    {
                        // $formato = '<w:rPr>
                        //                 <!-- <w:u w:val="single"/>  -->
                        //                 <!-- <w:u w:val="none"/>  -->
                        //                 <!-- <w:b w:val="false"/>  -->
                        //                 <!-- <w:i w:val="false"/>  -->
                        //                 <!-- <w:caps w:val="false"/>  -->
                        //                 <!-- <w:sz w:val="60"/>  -->
                        //                 <!-- <w:color w:val="ff0000"/> -->
                        //                 <w:t xml:space="preserve">'.htmlspecialchars($parrafo2).'</w:t>
                        //             </w:rPr>';

                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>'.htmlspecialchars($parrafo2).'</w:t>
                                        </w:p>';
                    }
                    else
                    {
                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>'.htmlspecialchars($parrafo2).'</w:t>
                                        </w:p><w:br/>';
                    }
                }
            }
            else
            {
                $text = explode("\n", $parrafo);

                foreach($text as $key2 => $parrafo2)
                {
                    if (($key2+0) < (count($text) -1))
                    {
                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>'.htmlspecialchars($parrafo2).'</w:t>
                                        </w:p>';
                    }
                    else
                    {
                        $texto_nuevo .= '<w:t>'.htmlspecialchars($parrafo2).'</w:t>';
                    }
                }
            }
        }


        return $texto_nuevo;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function reporteaguaword(Request $request)
    {
        try
        {
            // dd($request->all());


            $proyecto_id = $request->proyecto_id;
            $reporteregistro_id = $request->reporteregistro_id;
            $informe_tipo = $request->dashboard_parametro;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL,"es_MX");


            $agente_id = 9;
            $agente_nombre = "Agua";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);


            if ($reporteregistro_id > 0)
            {
                $reporte  = reporteaguaModel::findOrFail($reporteregistro_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            }
            else
            {
                return '<h3>Aun no se ha guardado nada para este informe de '.$agente_nombre.', primero debe llenar los datos para poder generarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            if (($recsensorial->cliente_id+0) != 2) // cliente_id [2 = senegas]
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_agua.docx'));//Ruta carpeta storage
            }
            else
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_aguacliente.docx'));//Ruta carpeta storage
            }


            // PORTADA
            //================================================================================


            // LOGOS
            //-----------------------------------------


            $plantillaword->setValue('ENCABEZADO', str_replace('\n', '<w:br/>', $cliente->cliente_plantillaencabezado));


            if ($cliente->cliente_plantillalogoizquierdo)
            {
                if (file_exists(storage_path('app/'.$cliente->cliente_plantillalogoizquierdo)))
                {
                    $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/'.$cliente->cliente_plantillalogoizquierdo), 'width' => 160, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    
                    $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/'.$cliente->cliente_plantillalogoizquierdo), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                }
            }
            else
            {
                $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
            }


            if ($cliente->cliente_plantillalogoderecho)
            {
                if (file_exists(storage_path('app/'.$cliente->cliente_plantillalogoderecho)))
                {
                    $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/'.$cliente->cliente_plantillalogoderecho), 'width' => 160, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    
                    $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/'.$cliente->cliente_plantillalogoderecho), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                }
            }
            else
            {
                $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
            }


            if ($recsensorial->recsensorial_fotoinstalacion)
            {
                if (file_exists(storage_path('app/'.$recsensorial->recsensorial_fotoinstalacion)))
                {
                    $plantillaword->setImageValue('INSTALACION_FOTO', array('path' => storage_path('app/'.$recsensorial->recsensorial_fotoinstalacion), 'height' => 280, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('INSTALACION_FOTO', 'SIN IMAGEN');
                }
            }
            else
            {
                $plantillaword->setValue('INSTALACION_FOTO', 'SIN IMAGEN');
            }


            //-----------------------------------------



            $cancelado_texto = '';
            if ($revision->reporterevisiones_cancelado == 1)
            {
                $cancelado_texto = '<w:br/>INFORME REVISIÓN '.$revision->reporterevisiones_revision.' CANCELADA';
            }


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
            {
                if ($reporte->reporteagua_catsubdireccion_activo == 1)
                {
                    $plantillaword->setValue('SUBDIRECCION', $proyecto->catsubdireccion->catsubdireccion_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('SUBDIRECCION', '');
                }


                if ($reporte->reporteagua_catgerencia_activo == 1)
                {
                    $plantillaword->setValue('GERENCIA', $proyecto->catgerencia->catgerencia_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('GERENCIA', '');
                }


                if ($reporte->reporteagua_catactivo_activo == 1)
                {
                    $plantillaword->setValue('ACTIVO', $proyecto->catactivo->catactivo_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('ACTIVO', '');
                }


                if ($reporte->reporteagua_catregion_activo == 1)
                {
                    $plantillaword->setValue('REGION', 'Región '.$proyecto->catregion->catregion_nombre.'<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('REGION', '');
                }
            }
            else
            {
                $plantillaword->setValue('SUBDIRECCION', '');
                $plantillaword->setValue('GERENCIA', '');
                $plantillaword->setValue('ACTIVO', '');
                $plantillaword->setValue('REGION', '');
            }


            if ($reporte->reporteagua_instalacion)
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $reporte->reporteagua_instalacion.$cancelado_texto);
            }
            else
            {
                $plantillaword->setValue('INSTALACION_NOMBRE', $proyecto->proyecto_clienteinstalacion.$cancelado_texto);
            }

            //-----------------------------------------
            // TITULO DEL INFORME (PARTIDA)

            $evaluacion = reporteaguaevaluacionModel::where('proyecto_id', $proyecto_id)
                                                    ->where('registro_id', $reporteregistro_id)
                                                    ->where('reporteaguaevaluacion_tipoevaluacion', 'LIKE', '%'.$informe_tipo.'%')
                                                    ->get();

            $tipoinstacion = '';
            switch (true)
            {
                case (count($evaluacion) > 150 && ($recsensorial->cliente_id+0) != 2): // cliente_id [2 = senegas]
                    $tipoinstacion = 'instalación extra grande';
                    break;
                case (count($evaluacion) > 80):
                    $tipoinstacion = 'instalación grande';
                    break;
                case (count($evaluacion) > 40):
                    $tipoinstacion = 'instalación mediana';
                    break;
                case (count($evaluacion) > 20):
                    $tipoinstacion = 'instalación chica';
                    break;
                default:
                    $tipoinstacion = 'instalación extra chica';
                    break;
            }

            $condiciontipo = '';

            if ($informe_tipo == 'Fisicoquímico')
            {
                $condiciontipo = 'Olor';
            }
            else
            {
                $condiciontipo = 'Coliformes';
            }


            $partidainforme = DB::select('SELECT
                                                clientepartidas.cliente_id, 
                                                clientepartidas.clientepartidas_tipo, 
                                                clientepartidas.catprueba_id, 
                                                clientepartidas.clientepartidas_nombre, 
                                                clientepartidas.clientepartidas_descripcion
                                            FROM
                                                clientepartidas
                                            WHERE
                                                clientepartidas.cliente_id = '.$recsensorial->cliente_id.'
                                                AND clientepartidas.clientepartidas_tipo = 2 -- 1 = reconocimiento, 2 = informes
                                                AND clientepartidas.catprueba_id = '.$agente_id.'
                                                AND clientepartidas.clientepartidas_nombre LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                                AND clientepartidas.clientepartidas_descripcion LIKE "%'.$condiciontipo.'%"
                                                AND clientepartidas.clientepartidas_descripcion LIKE "%'.$tipoinstacion.'%"
                                            ORDER BY
                                                updated_at DESC
                                            LIMIT 1');


            if (count($partidainforme) == 0)
            {
                $partidainforme = DB::select('SELECT
                                                clientepartidas.cliente_id, 
                                                clientepartidas.clientepartidas_tipo, 
                                                clientepartidas.catprueba_id, 
                                                clientepartidas.clientepartidas_nombre, 
                                                clientepartidas.clientepartidas_descripcion
                                            FROM
                                                clientepartidas
                                            WHERE
                                                clientepartidas.cliente_id = '.$recsensorial->cliente_id.'
                                                AND clientepartidas.clientepartidas_tipo = 2 -- 1 = reconocimiento, 2 = informes
                                                AND clientepartidas.catprueba_id = '.$agente_id.'
                                                AND clientepartidas.clientepartidas_nombre LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                                AND clientepartidas.clientepartidas_descripcion LIKE "%'.$condiciontipo.'%"
                                                -- AND clientepartidas.clientepartidas_descripcion LIKE "%'.$tipoinstacion.'%"
                                            ORDER BY
                                                updated_at DESC
                                            LIMIT 1');
            }


            if (count($partidainforme) > 0)
            {
                $plantillaword->setValue('TITULO_INFORME', $partidainforme[0]->clientepartidas_descripcion);
                $plantillaword->setValue('PARTIDA', $partidainforme[0]->clientepartidas_descripcion);
            }
            else
            {
                $plantillaword->setValue('TITULO_INFORME', '<w:rPr><w:color w:val="ff0000"/><w:t>FALTA AGREGAR TÍTULO (PARTIDA) DEL INFORME DE AGUA EN EL MÓDULO CLIENTES, CONSULTE CON EL ADMINISTRADOR</w:t></w:rPr>');
                $plantillaword->setValue('PARTIDA', '<w:rPr><w:color w:val="ff0000"/><w:t>FALTA AGREGAR TÍTULO (PARTIDA) DEL INFORME DE AGUA EN EL MÓDULO CLIENTES, CONSULTE CON EL ADMINISTRADOR</w:t></w:rPr>');
            }


            //-----------------------------------------

            $plantillaword->setValue('CONTRATO', $cliente->cliente_numerocontrato);
            $plantillaword->setValue('PORTADA_FECHA', $reporte->reporteagua_fecha);
            $plantillaword->setValue('DESCRIPCION_CONTRATO', $cliente->cliente_descripcioncontrato);
            $plantillaword->setValue('EMPRESA_RESPONSABLE', $cliente->cliente_plantillaempresaresponsable);
            $plantillaword->setValue('PIE_PAGINA', str_replace("\r\n", "<w:br/>", str_replace("\n\n", "<w:br/>", $cliente->cliente_plantillapiepagina)));


            if (($revision->reporterevisiones_revision+0) > 0)
            {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' ('.$informe_tipo.') Rev-'.$revision->reporterevisiones_revision);
            }
            else
            {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio.' - Informe de '.$agente_nombre.' ('.$informe_tipo.')');
            }


            // INTRODUCCION
            //================================================================================

            
            if ($informe_tipo == 'Fisicoquímico')
            {
                $plantillaword->setValue('INTRODUCCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_introduccion));
            }
            else
            {
                $plantillaword->setValue('INTRODUCCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_introduccion2));
            }


            // DEFINICIONES
            //================================================================================


            $definiciones_simbolo = ["¹", "²", "³", "⁴", "⁵", "⁶", "⁷", "⁸", "⁹", "*", "●", "♦", "~", "°", "¨", "#"];
            $definiciones_fuentes;
            

            $where_definiciones = '';
            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
            {
                $where_definiciones = 'AND reportedefiniciones.catactivo_id = '.$proyecto->catactivo_id;
            }

            
            $sql = collect(DB::select('SELECT
                                            -- TABLA.id,
                                            -- TABLA.agente_id,
                                            -- TABLA.agente_nombre,
                                            -- TABLA.catactivo_id,
                                            -- TABLA.concepto,
                                            -- CONCAT(": ", TABLA.descripcion) AS descripcion,
                                            TABLA.fuente
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        reportedefinicionescatalogo.id,
                                                        reportedefinicionescatalogo.agente_id,
                                                        reportedefinicionescatalogo.agente_nombre,
                                                        0 catactivo_id,
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
                                        GROUP BY
                                            TABLA.fuente
                                        ORDER BY
                                            TABLA.fuente ASC'));


            $definicionesfuentes = '';
            if (count($sql) > 1)
            {
                foreach ($sql as $key => $value)
                {
                    $definiciones_fuentes[] = array(
                                                  'fuente_descripcion' => $value->fuente
                                                , 'fuente_simbolo' => ' '.$definiciones_simbolo[$key].'*'
                                            );

                    
                    if (($key+0) < (count($sql) -1))
                    {
                        $definicionesfuentes .= 'Fuentes '.$definiciones_simbolo[$key].'*: '.$value->fuente.'<w:br/>';
                    }
                    else
                    {
                        $definicionesfuentes .= 'Fuentes '.$definiciones_simbolo[$key].'*: '.$value->fuente;
                    }
                }
            }
            else
            {
                $definiciones_fuentes[] = array(
                                                  'fuente_descripcion' => $sql[0]->fuente
                                                , 'fuente_simbolo' => ''
                                            );


                $definicionesfuentes = 'Fuentes: '.$sql[0]->fuente;
            }


            $plantillaword->setValue('DEFINICIONES_FUENTES', $definicionesfuentes);


            $sql = collect(DB::select('SELECT
                                            TABLA.id,
                                            TABLA.agente_id,
                                            TABLA.agente_nombre,
                                            TABLA.catactivo_id,
                                            TABLA.concepto,
                                            CONCAT(": ", TABLA.descripcion) AS descripcion,
                                            TABLA.fuente
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        reportedefinicionescatalogo.id,
                                                        reportedefinicionescatalogo.agente_id,
                                                        reportedefinicionescatalogo.agente_nombre,
                                                        0 catactivo_id,
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


            $definiciones = '';
            foreach ($sql as $key => $value)
            {
                foreach ($definiciones_fuentes as $key2 => $dato)
                {
                    if ($value->fuente == $dato['fuente_descripcion'])
                    {
                        if (($key+0) < (count($sql) -1))
                        {
                            $definiciones .= '<w:p>
                                                <w:pPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                </w:pPr>
                                                <w:rPr>
                                                    <w:b w:val="true"/>
                                                </w:rPr>
                                                <w:t>
                                                    '.$value->concepto.'
                                                </w:t>
                                                <w:rPr>
                                                    <w:b w:val="false"/>
                                                </w:rPr>
                                                <w:t>
                                                    '.htmlspecialchars($value->descripcion).''.$dato['fuente_simbolo'].'
                                                </w:t>
                                            </w:p><w:br/>';
                        }
                        else
                        {
                            $definiciones .= '<w:p>
                                                <w:pPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                </w:pPr>
                                                <w:rPr>
                                                    <w:b w:val="true"/>
                                                </w:rPr>
                                                <w:t>
                                                    '.$value->concepto.'
                                                </w:t>
                                                <w:rPr>
                                                    <w:b w:val="false"/>
                                                </w:rPr>
                                                <w:t>
                                                    '.htmlspecialchars($value->descripcion).''.$dato['fuente_simbolo'].'
                                                </w:t>
                                            </w:p>';
                        }
                    }
                }
            }


            $plantillaword->setValue('DEFINICIONES', $definiciones);


            // OBJETIVO GENERAL
            //================================================================================


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            if ($informe_tipo == 'Fisicoquímico')
            {
                $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_objetivoespecifico));
            }
            else
            {
                $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_objetivoespecifico2));
            }


            // METODOLOGIA
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Poppins';
                $font_size = 10;
            }
            else
            {
                $fuente = 'Montserrat';
                $font_size = 10;
            }


            $bgColor_encabezado = '#0C3F64'; //#1A5276
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


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_metodologia_4_2));


            if ($informe_tipo == 'Fisicoquímico')
            {
                $plantillaword->setValue('METODOLOGIA_4_3', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_metodologia_4_3));


                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                $ancho_col_1 = 4750;
                $ancho_col_2 = 4750;

                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Color', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('20 unidades de color verdadero en la escala de platino-cobalto.', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Olor y Sabor', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('Agradable (se aceptarán aquellos que sean tolerables para la mayoría de los consumidores, siempre que no sean resultados de condiciones objetables desde el punto de vista biológico o químico).', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Turbiedad', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('5 unidades de turbiedad nefelométricas (UTN) o su equivalente en otro método.', $texto);


                $plantillaword->setValue('TITULO_TABLA_4_3_1_1', 'Tabla 2<w:br/>Límites Permisibles de Características Físicas y Organolépticas');
                $plantillaword->setComplexBlock('TABLA_4_3_1_1', $table);
                $plantillaword->setValue('PIE_TABLA_4_3_1_1', 'Tabla obtenida de la NOM-127-SSA1-1994.');


                //------------------------------------------------

                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                $ancho_col_1 = 4750;
                $ancho_col_2 = 4750;

                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible mg/L', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Aluminio', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,20', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Arsénico (Nota 2)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,05', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Bario', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,70', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cadmio', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,005', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cianuros (como CN-)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,07', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cloro residual libre', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,2-1,50', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cloruros (como Cl-)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('250,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cobre', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('2,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Cromo total', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,05', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Dureza total (como CaCO3)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('500,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Fenoles o compuestos fenólicos', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,3', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Fierro', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,30', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Fluoruros (como F-)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('1,50', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Hidrocarburos aromáticos en microgramos/l:', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('-', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Benceno', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('10,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Etilbenceno', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('300,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Tolueno', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('700,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Xileno (tres isómeros)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('500,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Manganeso', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,15', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Mercurio', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,001', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Nitratos (como N)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('10,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Nitritos (como N)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('1,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Nitrógeno amoniacal (como N)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,50', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('pH (potencial de hidrógeno) en unidades de pH', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('6,5-8,5', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Plaguicidas en microgramos/l:', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('-', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Aldrín y dieldrín (separados o combinados)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,03', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Clordano (total de isómeros)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,20', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('DDT (total de isómeros)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('1,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Gamma-HCH (lindano)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('2,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Hexaclorobenceno', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('1,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Heptacloro y epóxido de heptacloro', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,03', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Metoxicloro', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('20,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('2,4 – D', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('30,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Plomo', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,01', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Sodio', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('200,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Sólidos disueltos totales', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('1000,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Sulfatos (como SO4=)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('400,00', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Sustancias activas al azul de metileno (SAAM)', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,50', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Trihalometanos totales', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,20', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Yodo residual libre', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('0,2-0,5', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Zinc', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('5,00', $texto);


                $plantillaword->setValue('TITULO_TABLA_4_3_1_2', 'Tabla 3<w:br/>Límites permisibles de características químicas');
                $plantillaword->setComplexBlock('TABLA_4_3_1_2', $table);
                $plantillaword->setValue('PIE_TABLA_4_3_1_2', 'Tabla obtenida de la NOM-127-SSA1-1994.');


                $plantillaword->setValue('METODOLOGIA_4_3_1_PARRAFO1', '');
                $plantillaword->setValue('METODOLOGIA_4_3_1_PARRAFO2', '');
            }
            else
            {
                $plantillaword->setValue('METODOLOGIA_4_3', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_metodologia_4_32));


                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                $ancho_col_1 = 4750;
                $ancho_col_2 = 4750;

                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Organismos coliformes totales', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('Ausencia o no detectables', $texto);
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Organismos coliformes fecales', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('Ausencia o no detectables', $texto);


                $plantillaword->setValue('TITULO_TABLA_4_3_1_1', 'Tabla 1<w:br/>Límites permisibles de calidad del agua');
                $plantillaword->setComplexBlock('TABLA_4_3_1_1', $table);
                $plantillaword->setValue('PIE_TABLA_4_3_1_1', 'Tabla obtenida de la NOM-127-SSA1-1994.');


                //------------------------------------------------


                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                $ancho_col_1 = 4750;
                $ancho_col_2 = 4750;

                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Característica', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible', $encabezado_texto);

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText('Mesofílicos aerobios', $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText('100 UFC/100 mL', $texto);


                $plantillaword->setValue('TITULO_TABLA_4_3_1_2', '');
                $plantillaword->setComplexBlock('TABLA_4_3_1_2', $table);
                $plantillaword->setValue('PIE_TABLA_4_3_1_2', 'Tabla obtenida de la NOM-041-SSA1-1993');

                $plantillaword->setValue('METODOLOGIA_4_3_1_PARRAFO1', '<w:br/>El contenido de organismos resultante del examen de una muestra simple de agua, debe ajustarse a lo establecido en las siguientes tablas.<w:br/>');
                $plantillaword->setValue('METODOLOGIA_4_3_1_PARRAFO2', '<w:br/>Los resultados de los exámenes bacteriológicos se deben reportar en unidades de NMP/100 ml (número más probable por 100 ml), si se utiliza la técnica del número más probable o UFC/100 ml (unidades formadoras de colonias por 100 ml), si se utiliza la técnica de filtración por membrana.');
            }


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_ubicacioninstalacion));


            // Imagen FOTO
            if ($reporte->reporteagua_ubicacionfoto)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteagua_ubicacionfoto)))
                {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/'.$reporte->reporteagua_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            }
            else
            {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }
                

            // PROCESO INSTALACION
            //================================================================================


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $reporte->reporteagua_procesoinstalacion))));


            // PROCESO ELABORACION
            //================================================================================


            $plantillaword->setValue('PROCESO_ELABORACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_procesoelaboracion));


            // TABLA 5.5 MANEJO DEL AGUA EN LA INSTALACION
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reportearea.reportearea_nombre AS reporteaguaarea_nombre,
                                        reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipouso,
                                        reporteaguaevaluacion.reporteaguaevaluacion_descripcionuso 
                                    FROM
                                        reporteaguaevaluacion
                                        LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipouso,
                                        reporteaguaevaluacion.reporteaguaevaluacion_descripcionuso 
                                    FROM
                                        reporteaguaevaluacion
                                        LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC');
            }


            // Columnas
            $ancho_col_1 = 1000;
            $ancho_col_2 = 3000;
            $ancho_col_3 = 2500;
            $ancho_col_4 = 3000;


            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // Encabezado
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de medición', $encabezado_texto);
            // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Tipo<w:br/>Para uso / consumo humano', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Descripción', $encabezado_texto);


            $area = 'XXXXX'; $instalacion = 'XXXXX'; $descripcion = 'XXXXX';
            foreach ($sql as $key => $value)
            {
                if($instalacion != $value->reporteaguaarea_instalacion)
                {
                    // Encabezado
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de medición', $encabezado_texto);
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Tipo<w:br/>Para uso / consumo humano', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Descripción', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reporteaguaarea_instalacion;
                }


                $table->addRow(); //fila

                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_punto, $texto);


                if($area != $value->reporteaguaarea_nombre)
                {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_nombre, $texto);
                    $area = $value->reporteaguaarea_nombre;
                }
                else
                {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_tipouso, $texto);


                if($descripcion != $value->reporteaguaevaluacion_descripcionuso)
                {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_descripcionuso, $texto);
                    $descripcion = $value->reporteaguaevaluacion_descripcionuso;
                }
                else
                {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_5_5', $table);


            // TABLA 6.1 Condiciones de operación durante la evaluación (representado en porcentaje)
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                        reportearea.reportearea_orden AS reporteaguaarea_numorden,
                                        reportearea.reportearea_nombre AS reporteaguaarea_nombre,
                                        reportearea.reporteaguaarea_porcientooperacion 
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = '.$proyecto_id.' 
                                        AND reportearea.reporteaguaarea_porcientooperacion > 0
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaarea.proyecto_id,
                                        reporteaguaarea.registro_id,
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaarea.reporteaguaarea_numorden,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaarea.reporteaguaarea_porcientooperacion 
                                    FROM
                                        reporteaguaarea
                                    WHERE
                                        reporteaguaarea.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaarea.registro_id = '.$reporteregistro_id.' 
                                        AND reporteaguaarea.reporteaguaarea_porcientooperacion > 0
                                    ORDER BY
                                        reporteaguaarea.reporteaguaarea_numorden ASC,
                                        reporteaguaarea.reporteaguaarea_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 5500;
            $ancho_col_4 = 3000;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // Encabezado
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


            $instalacion = 'xxxx'; $total = 'XXXX';
            foreach ($sql as $key => $value)
            {
                if($instalacion != $value->reporteaguaarea_instalacion)
                {
                    // Encabezado
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $encabezado_texto); // combina columna


                    // $instalacion = $value->reporteaguaarea_instalacion;
                    $numero_fila = 0;
                }


                $table->addRow(); //fila


                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText(($key+1), $texto);


                // if($instalacion != $value->reporteaguaarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $texto);
                //     $instalacion = $value->reporteaguaarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteaguaarea_nombre, $texto);


                if($instalacion != $value->reporteaguaarea_instalacion || $total != $value->reporteaguaarea_porcientooperacion)
                {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_porcientooperacion.'%', $texto);
                    

                    $instalacion = $value->reporteaguaarea_instalacion;
                    $total = $value->reporteaguaarea_porcientooperacion;
                }
                else
                {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_6_1', $table);


            // TABLA 6.2 METODO DE EVALUACION
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                        reportearea.reportearea_nombre AS reporteaguaarea_nombre,
                                        reportearea.reportearea_orden,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo, "&", "Ց") AS reporteaguaevaluacionparametros_metodo,
                                        REPLACE(REPLACE(catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible, "<", "˂"), ">", "˃") AS catparametroaguacaracteristica_concentracionpermisible
                                        -- , reporteaguaevaluacion.reporteaguaevaluacion_punto 
                                    FROM
                                        reporteaguaevaluacionparametros
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%"
                                    GROUP BY
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reportearea.reportearea_instalacion,
                                        reportearea.reportearea_nombre,
                                        reportearea.reportearea_orden,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reporteaguaevaluacion.reporteaguaarea_id ASC,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo, "&", "Ց") AS reporteaguaevaluacionparametros_metodo,
                                        REPLACE(REPLACE(catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible, "<", "˂"), ">", "˃") AS catparametroaguacaracteristica_concentracionpermisible
                                        -- , reporteaguaevaluacion.reporteaguaevaluacion_punto 
                                    FROM
                                        reporteaguaevaluacionparametros
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%"
                                    GROUP BY
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.reporteaguaarea_id,
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaarea_id ASC,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC');
            }


            // Columnas
            // $ancho_col_1 = 2500;
            $ancho_col_2 = 4000;
            $ancho_col_3 = 3000;
            $ancho_col_4 = 3000;
            $ancho_col_5 = 3000;


            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // Encabezado
            // $table->addRow(200, array('tblHeader' => true));
            // // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Método', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible', $encabezado_texto);


            $instalacion = 'xxxx'; $area = 'xxxx'; $parametro = 'XXXX'; $limite = 'XXXX';
            foreach ($sql as $key => $value)
            {
                if($instalacion != $value->reporteaguaarea_instalacion)
                {
                    // Encabezado
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Método', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Límite permisible', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reporteaguaarea_instalacion;
                }


                $table->addRow(); //fila


                // if($instalacion != $value->reporteaguaarea_instalacion)
                // {
                //     $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $texto);
                //     $instalacion = $value->reporteaguaarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_1, $continua_fila);
                // }


                if($area != $value->reporteaguaarea_nombre)
                {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_nombre, $texto);
                    $area = $value->reporteaguaarea_nombre;
                }
                else
                {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if($parametro != $value->catparametroaguacaracteristica_caracteristica)
                {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_caracteristica, $texto);
                    $parametro = $value->catparametroaguacaracteristica_caracteristica;
                }
                else
                {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_metodo, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_concentracionpermisible, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_6_2', $table);


            if ($informe_tipo == "Fisicoquímico")
            {
                $plantillaword->setValue('METODO_EVALUACION', 'En la siguiente tabla se indican las áreas con sus respectivos parámetros, métodos y sus límites permisibles de acuerdo con la NOM-127-SSA1-1994:');
            }
            else
            {
                $plantillaword->setValue('METODO_EVALUACION', 'En la siguiente tabla se indican las áreas con sus respectivos parámetros, métodos y sus límites permisibles:');
            }


            // TABLA 7.1 Resultados
            //================================================================================


            if ($informe_tipo == "Fisicoquímico")
            {
                $plantillaword->setValue('RESULTADOS_PARRAFO', 'A continuación, se presentan de manera detallada los resultados obtenidos en la evaluación de la calidad del agua respecto a los parámetros fisicoquímicos por punto de muestreo:');
            }
            else
            {
                $plantillaword->setValue('RESULTADOS_PARRAFO', 'A continuación, se presentan de manera detallada los resultados obtenidos en la evaluación de la calidad del agua respecto a los parámetros microbiológicos por punto de muestreo:');
            }


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_foliomuestra,
                                        reportearea.reportearea_nombre AS reporteaguaarea_nombre,
                                        reporteaguaevaluacion.reporteaguaevaluacion_ubicacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_suministro,
                                        reporteaguaevaluacion.reporteaguaevaluacion_fecha,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_unidadmedida,
                                        REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo, "&", "Ց") AS reporteaguaevaluacionparametros_metodo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_totalpersonas,
                                        REPLACE(REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_obtenida, "<", "˂"), ">", "˃") AS reporteaguaevaluacionparametros_obtenida,
                                        REPLACE(REPLACE(catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible, "<", "˂"), ">", "˃") AS catparametroaguacaracteristica_concentracionpermisible,
                                        reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado 
                                    FROM
                                        reporteaguaevaluacionparametros
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%"
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_foliomuestra,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaevaluacion.reporteaguaevaluacion_ubicacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_suministro,
                                        reporteaguaevaluacion.reporteaguaevaluacion_fecha,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                        catparametroaguacaracteristica.catparametroaguacaracteristica_unidadmedida,
                                        REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_metodo, "&", "Ց") AS reporteaguaevaluacionparametros_metodo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_totalpersonas,
                                        REPLACE(REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_obtenida, "<", "˂"), ">", "˃") AS reporteaguaevaluacionparametros_obtenida,
                                        REPLACE(REPLACE(catparametroaguacaracteristica.catparametroaguacaracteristica_concentracionpermisible, "<", "˂"), ">", "˃") AS catparametroaguacaracteristica_concentracionpermisible,
                                        reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado 
                                    FROM
                                        reporteaguaevaluacionparametros
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%"
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC');
            }


            // Columnas
            $ancho_col_1 = 1500;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 1000;
            $ancho_col_4 = 2500;
            $ancho_col_5 = 1500;
            $ancho_col_6 = 1000;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1500;
            $ancho_col_9 = 1500;

            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $punto = 'XXXXX'; $ubicacion = 'XXXXX'; $trabajadores = 'XXXXX'; $area = 'XXXXX';
            foreach ($sql as $key => $value)
            {
                if($punto != $value->reporteaguaevaluacion_punto)
                {
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($centrado)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Tabla de resultados de agua muestra con número de folio '.$value->reporteaguaevaluacion_foliomuestra.'</w:t></w:r><w:r><w:t>', $texto);
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Punto de medición</w:t></w:r><w:r><w:t>: '.$value->reporteaguaevaluacion_punto, $texto);
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Área</w:t></w:r><w:r><w:t>: '.$value->reporteaguaarea_nombre, $texto);
                    $table->addRow(); //fila
                    $table->addCell(13000, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '#FFFFFF'))->addTextRun($izquierda)->addText('</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>Ubicación</w:t></w:r><w:r><w:t>: '.$value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion, $texto);
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de<w:br/>medición', $encabezado_texto);
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Unidades', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Método de<w:br/>análisis', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('No. de<w:br/>trabajadores<w:br/>expuestos', $encabezado_texto);
                    $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Concentración<w:br/>obtenida', $encabezado_texto);
                    $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Concentración<w:br/>permisible', $encabezado_texto);
                    $table->addCell($ancho_col_9, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);

                    $punto = $value->reporteaguaevaluacion_punto;
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_fecha, $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_caracteristica, $texto);
                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_unidadmedida, $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_metodo, $texto);

                
                if($ubicacion != $value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion)
                {
                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion;
                }
                else
                {
                    $table->addCell($ancho_col_5, $continua_fila);
                }


                if($trabajadores != $value->reporteaguaevaluacion_totalpersonas)
                {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_totalpersonas, $texto);
                    $trabajadores = $value->reporteaguaevaluacion_totalpersonas;
                }
                else
                {
                    $table->addCell($ancho_col_6, $continua_fila);
                }


                $table->addCell($ancho_col_7, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_obtenida, $texto);
                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_concentracionpermisible, $texto);


                $texto_color = "#000000";
                if ($value->reporteaguaevaluacionparametros_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_resultado, array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
                else if ($value->reporteaguaevaluacionparametros_resultado == "Fuera de norma") //Rojo
                {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_resultado, array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
                else
                {
                    $text_color = "#FFFFFF";
                    $bgColor = "#888888";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText('No<w:br/>aplicable', array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
            }


            if ($informe_tipo == "Fisicoquímico")
            {
                $plantillaword->setValue('TABLA_7_1_NOTA', '<w:br/>Nota: **No se observó olor, no se observó sabor con base en STANDARD METHODS-2150-2012, B y STANDARD METHODS-2160-2012, B respectivamente. Por lo que se considera agradable para la comparación contra la NOM-127-SSA1-1994.<w:br/>');
            }
            else
            {
                $plantillaword->setValue('TABLA_7_1_NOTA', '');
            }


            $plantillaword->setComplexBlock('TABLA_7_1', $table);


            // TABLA 7.2 Analisis de los Resultados
            //================================================================================


            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // encabezado tabla
            $ancho_col_1 = 1500;
            $ancho_col_2 = 3500;
            $ancho_col_3 = 2000;
            $ancho_col_4 = 2000;
            $ancho_col_5 = 2000;
            $ancho_col_6 = 2000;


            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de<w:br/>medición', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Parámetro', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Concentración<w:br/>obtenida', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Concentración<w:br/>permisible', $encabezado_texto);
            $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento<w:br/>normativo', $encabezado_texto);


            $punto = 'XXXXX'; $ubicacion = 'XXXXX';
            foreach ($sql as $key => $value)
            {
                $table->addRow(); //fila

                if($punto != $value->reporteaguaevaluacion_punto)
                {
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_punto, $texto);
                    $punto = $value->reporteaguaevaluacion_punto;
                }
                else
                {
                    $table->addCell($ancho_col_1, $continua_fila);
                }

               
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_caracteristica, $texto);
                
                
                if($ubicacion != $value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion)
                {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion, $texto);
                    $ubicacion = $value->reporteaguaevaluacion_suministro.' / '.$value->reporteaguaevaluacion_ubicacion;
                }
                else
                {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_obtenida, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->catparametroaguacaracteristica_concentracionpermisible, $texto);


                $texto_color = "#000000";
                if ($value->reporteaguaevaluacionparametros_resultado == "Dentro de norma") //Verde
                {
                    $text_color = "#000000";
                    $bgColor = "#00FF00";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_resultado, array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
                else if ($value->reporteaguaevaluacionparametros_resultado == "Fuera de norma") //Rojo
                {
                    $text_color = "#FFFFFF";
                    $bgColor = "#FF0000";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText($value->reporteaguaevaluacionparametros_resultado, array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
                else
                {
                    $text_color = "#FFFFFF";
                    $bgColor = "#888888";

                    $table->addCell($ancho_col_9, array('bgColor' => $bgColor))->addTextRun($centrado)->addText('No aplicable', array('color' => $text_color, 'size' => 10, 'bold' => true, 'name' => $fuente));
                }
            }


            if ($informe_tipo == "Fisicoquímico")
            {
                $plantillaword->setValue('TABLA_7_2_NOTA', '<w:br/>Nota: **No se observó olor, no se observó sabor con base en STANDARD METHODS-2150-2012, B y STANDARD METHODS-2160-2012, B respectivamente. Por lo que se considera agradable para la comparación contra la NOM-127-SSA1-1994.<w:br/>');
            }
            else
            {
                $plantillaword->setValue('TABLA_7_2_NOTA', '');
            }


            $plantillaword->setComplexBlock('TABLA_7_2', $table);


            // TABLA 7.3 Recipientes y dispensadores de agua evaluados
            //================================================================================


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                        reportearea.reportearea_nombre reporteaguaarea_nombre,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_ubicacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_suministro,
                                        reporteaguaevaluacion.reporteaguaevaluacion_condiciones,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion 
                                    FROM
                                        reporteaguaevaluacion
                                        LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.'  
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion LIKE "%'.$informe_tipo.'%" 
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_ubicacion,
                                        reporteaguaevaluacion.reporteaguaevaluacion_suministro,
                                        reporteaguaevaluacion.reporteaguaevaluacion_condiciones,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion 
                                    FROM
                                        reporteaguaevaluacion
                                        LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.'  
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                        AND reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion LIKE "%'.$informe_tipo.'%" 
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC');
            }
            

            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 3000;
            $ancho_col_4 = 2500;
            $ancho_col_5 = 2500;
            $ancho_col_6 = 4000;


            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            // // Enabezado
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de<w:br/>medición', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Punto de localización', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Equipo de suministro', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de las<w:br/>condiciones del medio', $encabezado_texto);


            $instalacion = 'XXXXX'; $area = 'XXXXX';
            foreach ($sql as $key => $value)
            {
                if($instalacion != $value->reporteaguaarea_instalacion)
                {
                    // Enabezado
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Punto de<w:br/>medición', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Punto de localización', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Equipo de suministro', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de las<w:br/>condiciones del medio', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $encabezado_texto); // combina columna


                    $instalacion = $value->reporteaguaarea_instalacion;
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_punto, $texto);


                // if($instalacion != $value->reporteaguaarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_instalacion, $texto);
                //     $instalacion = $value->reporteaguaarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if($area != $value->reporteaguaarea_nombre)
                {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteaguaarea_nombre, $texto);
                    $area = $value->reporteaguaarea_nombre;
                }
                else
                {
                    $table->addCell($ancho_col_3, $continua_fila);
                }

                
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_ubicacion, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_suministro, $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reporteaguaevaluacion_condiciones, $texto);
            }


            $table->addRow(); //fila
            $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText(count($sql), $textonegrita);
            $table->addCell(12000, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos de medición', $textototal); // combina columna


            $plantillaword->setComplexBlock('TABLA_7_3', $table);


            // TABLA 7.2 Matriz de exposición laboral
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Poppins';
                $font_size = 6;
            }
            else
            {
                $fuente = 'Montserrat';
                $font_size = 6;
            }


            $bgColor_encabezado = '#0C3F64'; //#1A5276
            $encabezado_celda3 = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_celda3_celeste = array('bgColor' => '0BACDB', 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_texto3 = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $combinar_fila_encabezado3 = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado);
            $combinar_fila_encabezado3_celeste = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0BACDB');
            $combinar_fila3 = array('vMerge' => 'restart', 'valign' => 'center');
            $continua_fila3 = array('vMerge' => 'continue', 'valign' => 'center');
            $celda3 = array('valign' => 'center');
            $centrado3 = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $izquierda3 = array('align' => 'left', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $justificado3 = array('align' => 'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $texto3 = array('color' => '000000', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $textonegrita3 = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
            $textototal3 = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);


            // $areas_poe = 0;


            if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.id,
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
                                        reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                        reportearea.reportearea_nombre AS reporteaguaarea_nombre,
                                        reportearea.reportearea_orden,
                                        reportecategoria.reportecategoria_nombre AS reporteaguacategoria_nombre,
                                        reportecategoria.reportecategoria_orden,
                                        reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                        reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha,
                                        reporteaguaevaluacion.reporteaguaevaluacion_geo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_totalpersonas,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion 
                                    FROM
                                        reporteaguaevaluacioncategorias
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                        LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                        LEFT JOIN reportecategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reportecategoria.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.'  
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.'  
                                        AND reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion LIKE "%'.$informe_tipo.'%" -- Fisicoquímico_Microbiológico
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC,
                                        reportecategoria.reportecategoria_orden ASC,
                                        reportecategoria.reportecategoria_nombre ASC');
            }
            else
            {
                $sql = DB::select('SELECT
                                        reporteaguaevaluacion.proyecto_id,
                                        reporteaguaevaluacion.registro_id,
                                        reporteaguaevaluacion.id,
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
                                        reporteaguaarea.reporteaguaarea_instalacion,
                                        reporteaguaarea.reporteaguaarea_nombre,
                                        reporteaguacategoria.reporteaguacategoria_nombre,
                                        reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                        reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha,
                                        reporteaguaevaluacion.reporteaguaevaluacion_geo,
                                        reporteaguaevaluacion.reporteaguaevaluacion_totalpersonas,
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                        reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion 
                                    FROM
                                        reporteaguaevaluacioncategorias
                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                        LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                        LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                        LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                        LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                        LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                        LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                        LEFT JOIN reporteaguacategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reporteaguacategoria.id 
                                    WHERE
                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.'  
                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.'  
                                        AND reporteaguaevaluacion.reporteaguaevaluacion_tipoevaluacion LIKE "%'.$informe_tipo.'%" -- Fisicoquímico_Microbiológico
                                    ORDER BY
                                        reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                        reporteaguaarea.reporteaguaarea_instalacion ASC,
                                        reporteaguaarea.reporteaguaarea_nombre ASC,
                                        reporteaguacategoria.reporteaguacategoria_nombre ASC');
            }


            if (($proyecto->catregion_id + 0) == 1) //REGION NORTE
            {
                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 400;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 1500;
                $ancho_col_6 = 1500;
                $ancho_col_7 = 500;
                $ancho_col_8 = 1500;
                $ancho_col_9 = 500;
                $ancho_col_10 = 500;
                $ancho_col_11 = 800;
                $ancho_col_12 = 800;
                $ancho_col_13 = 800;
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);                
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes biológicos', $textonegrita3);
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Agua', $encabezado_texto3);
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Área de<w:br/>referencia<w:br/>en atlas<w:br/>de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Número de<w:br/>personas', $encabezado_texto3);
                $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Grupo de<w:br/>exposición<w:br/>homogénea', $encabezado_texto3);
                
                if ($informe_tipo == "Fisicoquímico")
                {
                    $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Color<w:br/>(20 Pt/Co)', $encabezado_texto3);
                    $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Olor<w:br/>(Agradable)', $encabezado_texto3);
                    $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Turbiedad<w:br/>(5 UTN)', $encabezado_texto3);
                }
                else
                {
                    $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>totales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_12, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>fecales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_13, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Mesofílicos<w:br/>aerobios<w:br/>(100 UFC/mL)', $encabezado_texto3);
                }


                $numero_registro = 0; $punto = 'XXXX'; $subdir = 'XXXX'; $activo = 'XXXX'; $instalacion = 'XXXX'; $area = 'XXXX';
                foreach ($sql as $key => $value)
                {
                    $table->addRow(); //fila


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $numero_registro += 1;

                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                        // $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_1, $continua_fila3);
                    }


                    if($subdir != $value->catsubdireccion_nombre)
                    {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdir = $value->catsubdireccion_nombre;
                    }
                    else
                    {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if($activo != $value->gerencia_activo)
                    {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $activo = $value->gerencia_activo;
                    }
                    else
                    {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if($instalacion != $value->reporteaguaarea_instalacion)
                    {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaarea_instalacion, $texto3);
                        $instalacion = $value->reporteaguaarea_instalacion;
                    }
                    else
                    {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if($area != $value->reporteaguaarea_nombre)
                    {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaarea_nombre, $texto3);
                        $area = $value->reporteaguaarea_nombre;
                    }
                    else
                    {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteaguacategoria_nombre, $texto3);


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacion_totalpersonas, $texto3);
                        $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacion_geo, $texto3);


                        $where_condicion = '';
                        if ($informe_tipo == "Fisicoquímico")
                        {
                            $where_condicion = ' AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%") ';
                        }


                        $resultado = DB::select('SELECT
                                                        reporteaguaevaluacion.proyecto_id,
                                                        reporteaguaevaluacion.registro_id,
                                                        reporteaguaevaluacionparametros.reporteaguaevaluacion_id,
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                                        REPLACE(REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_obtenida, "<", "˂"), ">", "˃") AS reporteaguaevaluacionparametros_obtenida 
                                                    FROM
                                                        reporteaguaevaluacionparametros
                                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                                    WHERE
                                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        AND reporteaguaevaluacionparametros.reporteaguaevaluacion_id = '.$value->id.' 
                                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Microbiológico, Fisicoquímico 
                                                        -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                                        '.$where_condicion.'
                                                    ORDER BY
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC
                                                    LIMIT 3');


                        if (count($resultado) > 0)
                        {
                            $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[0]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_12, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[1]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_13, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[2]->reporteaguaevaluacionparametros_obtenida, $texto3);
                        }
                        else
                        {
                            $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_12, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_13, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                        }


                        $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_9, $continua_fila3);
                        $table->addCell($ancho_col_10, $continua_fila3);

                        $table->addCell($ancho_col_11, $continua_fila3);
                        $table->addCell($ancho_col_12, $continua_fila3);
                        $table->addCell($ancho_col_13, $continua_fila3);
                    }
                }
            }
            else if (($proyecto->catregion_id + 0) == 2) //SUR
            {
                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 400;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 1500;
                $ancho_col_6 = 1500;
                $ancho_col_7 = 800;
                $ancho_col_8 = 1500;
                $ancho_col_9 = 1000;
                $ancho_col_10 = 1000;
                $ancho_col_11 = 1000;
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes biológicos', $textonegrita3);
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(NULL, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Agua', $encabezado_texto3);
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Gerencia<w:br/>o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Área de referencia<w:br/>en atlas de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);

                if ($informe_tipo == "Fisicoquímico")
                {
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Color<w:br/>(20 Pt/Co)', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Olor<w:br/>(Agradable)', $encabezado_texto3);
                    $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Turbiedad<w:br/>(5 UTN)', $encabezado_texto3);
                }
                else
                {
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>totales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>fecales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_11, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Mesofílicos<w:br/>aerobios<w:br/>(100 UFC/mL)', $encabezado_texto3);
                }

                
                $numero_registro = 0; $punto = 'XXXX'; $subdir = 'XXXX'; $activo = 'XXXX'; $instalacion = 'XXXX'; $area = 'XXXX';
                foreach ($sql as $key => $value)
                {
                    $table->addRow(); //fila


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $numero_registro += 1;

                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                        // $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_1, $continua_fila3);
                    }


                    if($subdir != $value->catsubdireccion_nombre)
                    {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdir = $value->catsubdireccion_nombre;
                    }
                    else
                    {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if($activo != $value->gerencia_activo)
                    {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $activo = $value->gerencia_activo;
                    }
                    else
                    {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if($instalacion != $value->reporteaguaarea_instalacion)
                    {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaarea_instalacion, $texto3);
                        $instalacion = $value->reporteaguaarea_instalacion;
                    }
                    else
                    {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if($area != $value->reporteaguaarea_nombre)
                    {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaarea_nombre, $texto3);
                        $area = $value->reporteaguaarea_nombre;
                    }
                    else
                    {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }


                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteaguacategoria_nombre, $texto3);


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $where_condicion = '';
                        if ($informe_tipo == "Fisicoquímico")
                        {
                            $where_condicion = ' AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%") ';
                        }


                        $resultado = DB::select('SELECT
                                                        reporteaguaevaluacion.proyecto_id,
                                                        reporteaguaevaluacion.registro_id,
                                                        reporteaguaevaluacionparametros.reporteaguaevaluacion_id,
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                                        REPLACE(REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_obtenida, "<", "˂"), ">", "˃") AS reporteaguaevaluacionparametros_obtenida 
                                                    FROM
                                                        reporteaguaevaluacionparametros
                                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                                    WHERE
                                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        AND reporteaguaevaluacionparametros.reporteaguaevaluacion_id = '.$value->id.' 
                                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Microbiológico, Fisicoquímico 
                                                        -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                                        '.$where_condicion.'
                                                    ORDER BY
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC
                                                    LIMIT 3');


                        if (count($resultado) > 0)
                        {
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[0]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[1]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[2]->reporteaguaevaluacionparametros_obtenida, $texto3);
                        }
                        else
                        {
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_11, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                        }


                        $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_9, $continua_fila3);
                        $table->addCell($ancho_col_10, $continua_fila3);
                        $table->addCell($ancho_col_11, $continua_fila3);
                    }
                }
            }
            else //MARINA
            {
                // Crear tabla
                $table = null;            
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // encabezado tabla
                $ancho_col_1 = 400;
                $ancho_col_2 = 1800;
                $ancho_col_3 = 1800;
                $ancho_col_4 = 1800;
                $ancho_col_5 = 1800;
                $ancho_col_6 = 800;
                $ancho_col_7 = 1800;
                $ancho_col_8 = 1000;
                $ancho_col_9 = 1000;
                $ancho_col_10 = 1000;
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection'=>\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Área física', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Plantilla laboral', $encabezado_texto3);
                $table->addCell(NULL, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Agentes biológicos<w:br/>Agua', $encabezado_texto3);
                
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Subdirección o<w:br/>corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coordinación', $encabezado_texto3);
                $table->addCell($ancho_col_4, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_6, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_7, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);

                if ($informe_tipo == "Fisicoquímico")
                {
                    $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Color<w:br/>(20 Pt/Co)', $encabezado_texto3);
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Olor<w:br/>(Agradable)', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Turbiedad<w:br/>(5 UTN)', $encabezado_texto3);

                    $where_condicion = 'AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")';
                }
                else
                {
                    $table->addCell($ancho_col_8, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>totales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_9, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Coliformes<w:br/>fecales<w:br/>(ND)', $encabezado_texto3);
                    $table->addCell($ancho_col_10, $encabezado_celda3_celeste)->addTextRun($centrado3)->addText('Mesofílicos<w:br/>aerobios<w:br/>(100 UFC/mL)', $encabezado_texto3);

                    $where_condicion = '';
                }


                if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
                {
                    $sql = DB::select('SELECT
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
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
                                            reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reportecategoria.reportecategoria_nombre AS reporteaguacategoria_nombre,
                                            reportecategoria.reportecategoria_orden,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        FROM
                                            reporteaguaevaluacion
                                            LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                            INNER JOIN reporteaguaevaluacioncategorias ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN reportecategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reportecategoria.id
                                            INNER JOIN reporteaguaevaluacionparametros ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                            AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                            AND reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado = "Fuera de norma"
                                            -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            '.$where_condicion.' 
                                        GROUP BY
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
                                            reportearea.reportearea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reportecategoria.reportecategoria_nombre,
                                            reportecategoria.reportecategoria_orden,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        ORDER BY
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre ASC');
                }
                else
                {
                    $sql = DB::select('SELECT
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
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
                                            reporteaguaarea.reporteaguaarea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reporteaguacategoria.reporteaguacategoria_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        FROM
                                            reporteaguaevaluacion
                                            LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                            INNER JOIN reporteaguaevaluacioncategorias ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN reporteaguacategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reporteaguacategoria.id
                                            INNER JOIN reporteaguaevaluacionparametros ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                            AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                            AND reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado = "Fuera de norma"
                                            -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            '.$where_condicion.' 
                                        GROUP BY
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
                                            reporteaguaarea.reporteaguaarea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reporteaguacategoria.reporteaguacategoria_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        ORDER BY
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                            reporteaguacategoria.reporteaguacategoria_nombre ASC');
                }


                if (count($sql) == 0)
                {
                    if (($areas_poe+0) == 1) // TIENE TABLA POE GENERAL
                    {
                        $sql = DB::select('SELECT
                                                reporteaguaevaluacion.proyecto_id,
                                                reporteaguaevaluacion.registro_id,
                                                reporteaguaevaluacion.id,
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
                                                reportearea.reportearea_instalacion AS reporteaguaarea_instalacion,
                                                reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                                -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                                -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                                reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                                reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                                reportecategoria.reportecategoria_nombre AS reporteaguacategoria_nombre,
                                                reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                                reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                            FROM
                                                reporteaguaevaluacion
                                                LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reporteaguaevaluacion.reporteaguaarea_id = reportearea.id
                                                INNER JOIN reporteaguaevaluacioncategorias ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                                LEFT JOIN reportecategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reportecategoria.id
                                                INNER JOIN reporteaguaevaluacionparametros ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                                LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                            WHERE
                                                reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                                AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                                AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                                -- AND reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado = "Fuera de norma"
                                                -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                                '.$where_condicion.' 
                                            GROUP BY
                                                reporteaguaevaluacion.proyecto_id,
                                                reporteaguaevaluacion.registro_id,
                                                reporteaguaevaluacion.id,
                                                reportearea.reportearea_instalacion,
                                                reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                                -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                                -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                                reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                                reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                                reportecategoria.reportecategoria_nombre,
                                                reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                                reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                            ORDER BY
                                                reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                                reportecategoria.reportecategoria_nombre ASC');
                    }
                    else
                    {
                        $sql = DB::select('SELECT
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
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
                                            reporteaguaarea.reporteaguaarea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reporteaguacategoria.reporteaguacategoria_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        FROM
                                            reporteaguaevaluacion
                                            LEFT JOIN proyecto ON reporteaguaevaluacion.proyecto_id = proyecto.id
                                            LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                            LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                            LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                            LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                            LEFT JOIN reporteaguaarea ON reporteaguaevaluacion.reporteaguaarea_id = reporteaguaarea.id
                                            INNER JOIN reporteaguaevaluacioncategorias ON reporteaguaevaluacioncategorias.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN reporteaguacategoria ON reporteaguaevaluacioncategorias.reporteaguacategoria_id = reporteaguacategoria.id
                                            INNER JOIN reporteaguaevaluacionparametros ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                            LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id 
                                        WHERE
                                            reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                            AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                            AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Fisicoquímico, Microbiológico
                                            -- AND reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado = "Fuera de norma"
                                            -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                            '.$where_condicion.' 
                                        GROUP BY
                                            reporteaguaevaluacion.proyecto_id,
                                            reporteaguaevaluacion.registro_id,
                                            reporteaguaevaluacion.id,
                                            reporteaguaarea.reporteaguaarea_instalacion,
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto,
                                            -- reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                            -- catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                            reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_resultado,
                                            reporteaguaevaluacioncategorias.reporteaguacategoria_id,
                                            reporteaguacategoria.reporteaguacategoria_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_nombre,
                                            reporteaguaevaluacioncategorias.reporteaguaevaluacioncategorias_ficha 
                                        ORDER BY
                                            reporteaguaevaluacion.reporteaguaevaluacion_punto ASC,
                                            reporteaguacategoria.reporteaguacategoria_nombre ASC');
                    }
                }


                $numero_registro = 0; $punto = 'XXXX'; $subdir = 'XXXX'; $activo = 'XXXX'; $instalacion = 'XXXX';
                foreach ($sql as $key => $value)
                {
                    $table->addRow(); //fila


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $numero_registro += 1;

                        $table->addCell($ancho_col_1, $combinar_fila3)->addTextRun($centrado3)->addText($numero_registro, $texto3);
                        // $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_1, $continua_fila3);
                    }


                    if($subdir != $value->catsubdireccion_nombre)
                    {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdir = $value->catsubdireccion_nombre;
                    }
                    else
                    {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if($activo != $value->gerencia_activo)
                    {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $activo = $value->gerencia_activo;
                    }
                    else
                    {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if($instalacion != $value->reporteaguaarea_instalacion)
                    {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteaguaarea_instalacion, $texto3);
                        $instalacion = $value->reporteaguaarea_instalacion;
                    }
                    else
                    {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    $table->addCell($ancho_col_5, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_nombre, $texto3);
                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteaguaevaluacioncategorias_ficha, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteaguacategoria_nombre, $texto3);


                    if($punto != $value->reporteaguaevaluacion_punto)
                    {
                        $resultado = DB::select('SELECT
                                                        reporteaguaevaluacion.proyecto_id,
                                                        reporteaguaevaluacion.registro_id,
                                                        reporteaguaevaluacionparametros.reporteaguaevaluacion_id,
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_tipo,
                                                        catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica,
                                                        REPLACE(REPLACE(reporteaguaevaluacionparametros.reporteaguaevaluacionparametros_obtenida, "<", "˂"), ">", "˃") AS reporteaguaevaluacionparametros_obtenida 
                                                    FROM
                                                        reporteaguaevaluacionparametros
                                                        LEFT JOIN reporteaguaevaluacion ON reporteaguaevaluacionparametros.reporteaguaevaluacion_id = reporteaguaevaluacion.id
                                                        LEFT JOIN catparametroaguacaracteristica ON reporteaguaevaluacionparametros.catparametroaguacaracteristica_id = catparametroaguacaracteristica.id
                                                    WHERE
                                                        reporteaguaevaluacion.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteaguaevaluacion.registro_id = '.$reporteregistro_id.' 
                                                        AND reporteaguaevaluacionparametros.reporteaguaevaluacion_id = '.$value->id.' 
                                                        AND catparametroaguacaracteristica.catparametroaguacaracteristica_tipo LIKE "%'.$informe_tipo.'%" -- Microbiológico, Fisicoquímico 
                                                        -- AND (catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%COLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%OLOR%" OR catparametroaguacaracteristica.catparametroaguacaracteristica_caracteristica LIKE "%TURBIEDAD%")
                                                        '.$where_condicion.'
                                                    ORDER BY
                                                        reporteaguaevaluacionparametros.catparametroaguacaracteristica_id ASC
                                                    LIMIT 3');


                        if (count($resultado) > 0)
                        {
                            $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[0]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[1]->reporteaguaevaluacionparametros_obtenida, $texto3);
                            $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText($resultado[2]->reporteaguaevaluacionparametros_obtenida, $texto3);
                        }
                        else
                        {
                            $table->addCell($ancho_col_8, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_9, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                            $table->addCell($ancho_col_10, $combinar_fila3)->addTextRun($centrado3)->addText('-', $texto3);
                        }


                        $punto = $value->reporteaguaevaluacion_punto;
                    }
                    else
                    {
                        $table->addCell($ancho_col_8, $continua_fila3);
                        $table->addCell($ancho_col_9, $continua_fila3);
                        $table->addCell($ancho_col_10, $continua_fila3);
                    }
                }
            }


            if ($informe_tipo == "Fisicoquímico")
            {
                $plantillaword->setValue('TABLA_7_4_NOTA', '<w:br/>Nota: **No se observó olor, no se observó sabor con base en STANDARD METHODS-2150-2012, B y STANDARD METHODS-2160-2012, B respectivamente. Por lo que se considera agradable para la comparación contra la NOM-127-SSA1-1994.<w:br/>');
            }
            else
            {
                $plantillaword->setValue('TABLA_7_4_NOTA', '');
            }


            if (($recsensorial->recsensorial_tipocliente+0) == 1) // 1 = pemex, 0 = cliente
            {
                if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1)
                {
                    $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>Se anexa en formato digital.<w:br/>');
                }
                else
                {
                    $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>A continuación, se muestra un concentrado de los valores obtenidos en los diversos parámetros evaluados para determinar la calidad del agua respecto a los agentes biológicos:<w:br/>');
                }
            }
            else
            {
                $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>A continuación, se muestra un concentrado de los valores obtenidos en los diversos parámetros evaluados para determinar la calidad del agua respecto a los agentes biológicos:<w:br/>');
            }


            $plantillaword->setComplexBlock('TABLA_7_4', $table);


            // CONCLUSION
            //================================================================================


            if ($informe_tipo == 'Fisicoquímico')
            {
                $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_conclusion));
            }
            else
            {
                $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteagua_conclusion2));
            }


            // DASHBOARD
            //================================================================================


            // $dashboard_ruta = storage_path('app/reportes/proyecto/'.$proyecto_id.'/'.$agente_nombre.'/'.$reporteregistro_id.'/dashboard/evaluacion_'.$informe_tipo.'.jpg');


            // if (file_exists($dashboard_ruta))
            // {
            //     $plantillaword->setImageValue('DASHBOARD', array('path' => $dashboard_ruta, 'height' => 515, 'width' => 860, 'ratio' => false, 'borderColor' => '000000'));
            // }
            // else
            // {
            //     $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
            // }


            //-----------------------------------------


            if ($request->grafica_dashboard)
            {
                $imagen_base64 = base64_decode(str_replace("data:image/jpeg;base64,", "", $request->grafica_dashboard));
                $imagen_temporal_ruta = 'reportes/informes/dashboard_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'.jpg';
                Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage


                if (Storage::exists($imagen_temporal_ruta))
                {
                    $plantillaword->setImageValue('DASHBOARD', array('path' => storage_path('app/'.$imagen_temporal_ruta), 'height' => 500, 'width' => 860, 'ratio' => false, 'borderColor' => '000000'));


                    Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                }
                else
                {
                    $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
                }
            }
            else
            {
                $plantillaword->setValue('DASHBOARD', 'NO SE ENCONTRÓ DASHBOARD PARA ESTE INFORME.');
            }


            // RECOMENDACIONES
            //================================================================================


            if ($informe_tipo == 'Fisicoquímico')
            {
                $tipo = 1;
            }
            else
            {
                $tipo = 2;
            }


            $sql = DB::select('SELECT
                                    reporterecomendaciones.proyecto_id,
                                    reporterecomendaciones.registro_id,
                                    reporterecomendaciones.agente_nombre,
                                    reporterecomendaciones.reporterecomendacionescatalogo_id,
                                    reporterecomendaciones.reporterecomendaciones_tipo,
                                    reporterecomendaciones.reporterecomendaciones_descripcion,
                                    IFNULL(reporterecomendaciones.catalogo_id, 0) AS catalogo_id
                                FROM
                                    reporterecomendaciones
                                WHERE
                                    reporterecomendaciones.proyecto_id = '.$proyecto_id.' 
                                    AND reporterecomendaciones.registro_id = '.$reporteregistro_id.' 
                                    AND reporterecomendaciones.agente_nombre = "'.$agente_nombre.'" 
                                    AND (IFNULL(reporterecomendaciones.catalogo_id, 0) = 0 OR IFNULL(reporterecomendaciones.catalogo_id, 0) = '.$tipo.')
                                ORDER BY
                                    reporterecomendaciones.reporterecomendaciones_tipo ASC');


            $recomendacion = '';
            foreach ($sql as $key => $value)
            {
                if (($key+0) < (count($sql) -1))
                {
                    $recomendacion .= $value->reporterecomendaciones_descripcion.'\n\n';
                }
                else
                {
                    $recomendacion .= $value->reporterecomendaciones_descripcion;
                }
            }


            $plantillaword->setValue('RECOMENDACIONES', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $recomendacion));


            // TABLA 10 EQUIPO Y MATERIAL UTILIZADO
            //================================================================================


            $sql = DB::select('SELECT
                                    reporteaguamaterial.id,
                                    reporteaguamaterial.proyecto_id,
                                    reporteaguamaterial.registro_id,
                                    reporteaguamaterial.reporteaguamaterial_nombre 
                                FROM
                                    reporteaguamaterial
                                WHERE
                                    reporteaguamaterial.proyecto_id = '.$proyecto_id.' 
                                    AND reporteaguamaterial.registro_id = '.$reporteregistro_id.' 
                                ORDER BY
                                    reporteaguamaterial.id ASC');


            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 9500;

            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Equipo y material utilizado para evaluar agua', $encabezado_texto);


            foreach ($sql as $key => $value)
            {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteaguamaterial_nombre, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_10', $table);


            // RESPONSABLES
            //================================================================================


            // RESPONSABLE 1, FOTO DOCUMENTO
            if ($reporte->reporteagua_responsable1documento)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteagua_responsable1documento)))
                {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/'.$reporte->reporteagua_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            }
            else
            {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }
                

            $plantillaword->setValue('REPONSABLE1', $reporte->reporteagua_responsable1."<w:br/>".$reporte->reporteagua_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporte->reporteagua_responsable2documento)
            {
                if (file_exists(storage_path('app/'.$reporte->reporteagua_responsable2documento)))
                {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/'.$reporte->reporteagua_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            }
            else
            {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }
                

            $plantillaword->setValue('REPONSABLE2', $reporte->reporteagua_responsable2."<w:br/>".$reporte->reporteagua_responsable2cargo);


            // TABLA ANEXO 1, Memoria fotográfica  - CREAR VARIABLES
            //================================================================================


            $fotos = DB::select('SELECT
                                    proyectoevidenciafoto.id,
                                    proyectoevidenciafoto.proyecto_id,
                                    proyectoevidenciafoto.proveedor_id,
                                    proyectoevidenciafoto.agente_id,
                                    proyectoevidenciafoto.agente_nombre,
                                    proyectoevidenciafoto.proyectoevidenciafoto_carpeta,
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto,
                                    proyectoevidenciafoto.proyectoevidenciafoto_descripcion,
                                    proyectoevidenciafoto.proyectoevidenciafoto_archivo
                                FROM
                                    proyectoevidenciafoto
                                WHERE
                                    proyectoevidenciafoto.proyecto_id = '.$proyecto_id.'
                                    AND proyectoevidenciafoto.agente_nombre LIKE "%'.$agente_nombre.'%" 
                                    AND proyectoevidenciafoto.agente_nombre LIKE "%'.$informe_tipo.'%" 
                                ORDER BY
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto ASC');


            $ancho_col_1 = 4750;
            $ancho_col_2 = 4750;

            // Crear tabla
            $table = null;            
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $table->addRow(400, array('tblHeader' => true));
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' =>'ffffff', 'borderTopSize' => 1, 'borderRightColor' =>'ffffff', 'borderRightSize' => 1, 'borderBottomColor' =>'000000', 'borderBottomSize' => 1, 'borderLeftColor' =>'ffffff', 'borderLeftSize' => 1,))->addTextRun($centrado)->addText('Memoria fotográfica', array('color' => '000000', 'size' => 12, 'bold' => true, 'name' => $fuente));
            $table->addRow(400, array('tblHeader' => true));
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Evaluación de '.$agente_nombre, $encabezado_texto);


            if (count($fotos) > 0)
            {
                for ($i = 0; $i < count($fotos); $i += 4)
                {
                    $foto1 = ''; $descripcion1 = '';
                    if ($i < count($fotos))
                    {
                        $foto1 = '${PUNTO_'.$i.'_FOTO}';
                        $descripcion1 = '${PUNTO_'.$i.'_DESCRIPCION}';
                    }

                    $foto2 = ''; $descripcion2 = '';
                    if (($i+1) < count($fotos))
                    {
                        $foto2 = '${PUNTO_'.($i+1).'_FOTO}';
                        $descripcion2 = '${PUNTO_'.($i+1).'_DESCRIPCION}';
                    }

                    $foto3 = ''; $descripcion3 = '';
                    if (($i+2) < count($fotos))
                    {
                        $foto3 = '${PUNTO_'.($i+2).'_FOTO}';
                        $descripcion3 = '${PUNTO_'.($i+2).'_DESCRIPCION}';
                    }

                    $foto4 = ''; $descripcion4 = '';
                    if (($i+3) < count($fotos))
                    {
                        $foto4 = '${PUNTO_'.($i+3).'_FOTO}';
                        $descripcion4 = '${PUNTO_'.($i+3).'_DESCRIPCION}';
                    }

                    $table->addRow(); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($foto1, $texto);
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($foto2, $texto);
                    $table->addRow(1000); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion1, $texto);
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion2, $texto);
                    
                    if (($i+2) < count($fotos))
                    {
                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($foto3, $texto);
                        $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($foto4, $texto);
                        $table->addRow(1000); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion3, $texto);
                        $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion4, $texto);
                    }
                }            


                $plantillaword->setComplexBlock('TABLA_MEMORIA_FOTOGRAFICA', $table);
            }
            else
            {
                $plantillaword->setValue('TABLA_MEMORIA_FOTOGRAFICA', 'NO SE ENCONTRARON FOTOS QUE MOSTRAR.');
            }


            // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - CREAR VARIABLES
            //================================================================================


            $planoscarpetas = DB::select('SELECT
                                                reporteplanoscarpetas.id,
                                                reporteplanoscarpetas.proyecto_id,
                                                reporteplanoscarpetas.agente_id,
                                                reporteplanoscarpetas.agente_nombre,
                                                reporteplanoscarpetas.reporteplanoscarpetas_nombre
                                            FROM
                                                reporteplanoscarpetas
                                            WHERE
                                                reporteplanoscarpetas.proyecto_id = '.$proyecto_id.' 
                                                AND reporteplanoscarpetas.registro_id = '.$reporteregistro_id.' 
                                                AND reporteplanoscarpetas.agente_nombre LIKE "%'.$agente_nombre.'%"');


            $planoscarpetasvPoppinses = ''; $planocontador = 0; $plano_archivo = array();
            if (count($planoscarpetas) > 0)
            {
                foreach ($planoscarpetas as $key => $carpeta)
                {
                    $planos = DB::select('SELECT
                                                proyectoevidenciaplano.proyecto_id,
                                                proyectoevidenciaplano.agente_id,
                                                proyectoevidenciaplano.agente_nombre,
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo 
                                            FROM
                                                proyectoevidenciaplano 
                                            WHERE
                                                proyectoevidenciaplano.proyecto_id = '.$carpeta->proyecto_id.' 
                                                AND proyectoevidenciaplano.agente_nombre LIKE "%'.$carpeta->agente_nombre.' ('.$informe_tipo.')%" 
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = "'.$carpeta->reporteplanoscarpetas_nombre.'" 
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo ASC');

                    foreach ($planos as $key => $plano)
                    {
                        $planoscarpetasvPoppinses .= '${PLANO_'.$planocontador.'_FOTO}';

                        $plano_archivo[] = $plano->proyectoevidenciaplano_archivo;

                        $planocontador += 1;
                    }
                }
            }
            else
            {
                $plano_archivo = array();
                $planoscarpetasvPoppinses = 'NO SE ENCONTRARÓN PLANOS QUE MOSTRAR.';
            }
                

            $plantillaword->setValue('PLANOS', $planoscarpetasvPoppinses);


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CREAR WORD TEMPORAL


            // GUARDAR
            Storage::makeDirectory('reportes/informes'); //crear directorio
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

            // sleep(1);

            // ABRIR NUEVA PLANTILLA
            $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx'));//Abrir plantilla TEMPORAL


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            // TABLA ANEXO 1, Memoria fotográfica - AGREGAR FOTOS
            //================================================================================


            for ($i = 0; $i < count($fotos); $i += 4)
            {
                if ($i < count($fotos))
                {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo))
                    {
                        $plantillaword->setImageValue('PUNTO_'.$i.'_FOTO', array('path' => storage_path('app/'.$fotos[$i]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    }
                    else
                    {
                        $plantillaword->setValue('PUNTO_'.$i.'_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_'.$i.'_DESCRIPCION', "Punto ".$fotos[$i]->proyectoevidenciafoto_nopunto." ".$fotos[$i]->proyectoevidenciafoto_descripcion);
                }


                if (($i+1) < count($fotos))
                {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo))
                    {
                        $plantillaword->setImageValue('PUNTO_'.($i+1).'_FOTO', array('path' => storage_path('app/'.$fotos[($i+1)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    }
                    else
                    {
                        $plantillaword->setValue('PUNTO_'.($i+1).'_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_'.($i+1).'_DESCRIPCION', "Punto ".$fotos[($i+1)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+1)]->proyectoevidenciafoto_descripcion);
                }


                if (($i+2) < count($fotos))
                {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo))
                    {
                        $plantillaword->setImageValue('PUNTO_'.($i+2).'_FOTO', array('path' => storage_path('app/'.$fotos[($i+2)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    }
                    else
                    {
                        $plantillaword->setValue('PUNTO_'.($i+2).'_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_'.($i+2).'_DESCRIPCION', "Punto ".$fotos[($i+2)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+2)]->proyectoevidenciafoto_descripcion);
                }


                if (($i+3) < count($fotos))
                {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo))
                    {
                        $plantillaword->setImageValue('PUNTO_'.($i+3).'_FOTO', array('path' => storage_path('app/'.$fotos[($i+3)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    }
                    else
                    {
                        $plantillaword->setValue('PUNTO_'.($i+3).'_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    $plantillaword->setValue('PUNTO_'.($i+3).'_DESCRIPCION', "Punto ".$fotos[($i+3)]->proyectoevidenciafoto_nopunto." ".$fotos[($i+3)]->proyectoevidenciafoto_descripcion);
                }
            }


            // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - AGREGAR FOTOS
            //================================================================================


            for ($i = 0; $i < count($plano_archivo); $i ++)
            {
                if (Storage::exists($plano_archivo[$i]))
                {
                    $plantillaword->setImageValue('PLANO_'.$i.'_FOTO', array('path' => storage_path('app/'.$plano_archivo[$i]), 'height' => 690, 'width' => 588, 'ratio' => false, 'borderColor' => '000000'));
                }
                else
                {
                    $plantillaword->setValue('PLANO_'.$i.'_FOTO', 'NO SE ENCONTRÓ EL PLANO');
                }
            }


            // ARCHIVO PDF's ANEXOS
            //================================================================================


            $anexos_lista = DB::select('SELECT
                                            REPLACE(ANEXO.nombre, "/", "-") AS nombre,
                                            ANEXO.archivo
                                        FROM
                                            (
                                                (
                                                    SELECT
                                                        reporteanexos.reporteanexos_anexonombre AS nombre,
                                                        reporteanexos.reporteanexos_rutaanexo AS archivo 
                                                    FROM
                                                        reporteanexos
                                                    WHERE
                                                        reporteanexos.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteanexos.registro_id = '.$reporteregistro_id.' 
                                                        AND reporteanexos.agente_nombre = "'.$agente_nombre.'" 
                                                        AND reporteanexos.reporteanexos_tipo = 1
                                                        AND reporteanexos.reporteanexos_rutaanexo LIKE "%'.$informe_tipo.'%"
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        reporteanexos.reporteanexos_anexonombre AS nombre,
                                                        reporteanexos.reporteanexos_rutaanexo AS archivo 
                                                    FROM
                                                        reporteanexos
                                                    WHERE
                                                        reporteanexos.proyecto_id = '.$proyecto_id.' 
                                                        AND reporteanexos.registro_id = '.$reporteregistro_id.' 
                                                        AND reporteanexos.agente_nombre = "'.$agente_nombre.'" 
                                                        AND reporteanexos.reporteanexos_tipo = 2
                                                )
                                            ) AS ANEXO');


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL

            
            $informe_nombre = 'Informe de '.$agente_nombre.' '.$informe_tipo.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.').docx';


            // GUARDAR WORD FINAL
            $plantillaword->saveAs(storage_path('app/reportes/informes/'.$informe_nombre)); //crear archivo word

            
            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx'))
            {
                Storage::delete('reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx');
            }



            /*
            //================================================================================
            // CREAR .ZIP


            // Define Dir Folder
            $zip_ruta = storage_path('app/reportes/informes');
            
            // Zip File Name
            $zip_nombre = 'Informe de '.$agente_nombre.' ('.$informe_tipo.') - '.$proyecto->proyecto_folio.' + Anexos.zip';
            
            // Create ZipArchive Obj
            $zip = new ZipArchive;

            if ($zip->open($zip_ruta . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE)
            {
                // Add File in ZipArchive
                $zip->addFile(storage_path('app/reportes/informes/'.$informe_nombre), $informe_nombre); //Word

                foreach($anexos_lista as $file)
                {
                    if (Storage::exists($file->archivo))
                    {
                        $extencion = explode(".", $file->archivo);
                        $zip->addFile(storage_path('app/'.$file->archivo), $file->nombre.'.'.$extencion[1]); // Pdf Anexos
                    }
                }

                // Close ZipArchive     
                $zip->close();
            }
            
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );

            $zip_rutacompleta = $zip_ruta.'/'.$zip_nombre;


            //================================================================================


            // ELIMINAR INFORME word (PORQUE YA ESTÁ EN EL ZIP)
            if (Storage::exists('reportes/informes/'.$informe_nombre))
            {
                Storage::delete('reportes/informes/'.$informe_nombre);
            }


            // DESCARGAR ZIP
            // if(file_exists($zip_rutacompleta))
            // {
                return response()->download($zip_rutacompleta, $zip_nombre, $headers)->deleteFileAfterSend(true);
            // }


            // DESCARGAR PLANTILLA WORD
            // $word_ruta = storage_path('app/reportes/informes/Informe_de_'.$agente_nombre.'_proyecto_'.$proyecto->proyecto_folio.'.docx');
            // $word_ruta = storage_path('app/reportes/informes/Informe_'.$agente_nombre.'_'.$proyecto->proyecto_folio.'_TEMPORAL.docx');
            // $plantillaword->saveAs($word_ruta); //GUARDAR Y CREAR archivo word TEMPORAL
            // return response()->download($word_ruta)->deleteFileAfterSend(true);
            */


            // return response()->download(storage_path('app/reportes/informes/'.$informe_nombre))->deleteFileAfterSend(true);


            //--------------------------------------------------------------------------------


            if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
            {
                //================================================================================
                // CREAR .ZIP INFORME

                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');

                // Zip File Name
                $zip_nombre = 'Informe de '.$agente_nombre.' '.$informe_tipo.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';

                // Create ZipArchive Obj
                $zip = new ZipArchive;

                if ($zip->open($zip_ruta . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE)
                {
                    // Add File in ZipArchive
                    $zip->addFile(storage_path('app/reportes/informes/'.$informe_nombre), $informe_nombre); //Word


                    foreach($anexos_lista as $key => $file)
                    {
                        if (Storage::exists($file->archivo))
                        {
                            $extencion = explode(".", $file->archivo);
                            $zip->addFile(storage_path('app/'.$file->archivo), ($key+1).'.- '.$file->nombre.'.'.$extencion[1]); // Pdf Anexos
                        }
                    }


                    // Close ZipArchive     
                    $zip->close();
                }


                //----------------------------------


                // ELIMINAR INFORME word (PORQUE YA ESTÁ EN EL ZIP)
                if (Storage::exists('reportes/informes/'.$informe_nombre))
                {
                    Storage::delete('reportes/informes/'.$informe_nombre);
                }


                $dato["msj"] = 'Informe creado correctamente';
            }
            else // Crear informes historial y guardar en base de datos
            {
                //================================================================================
                // CREAR .ZIP INFORME


                // Define Dir Folder
                $zip_ruta_servidor = 'reportes/proyecto/'.$proyecto_id.'/'.$agente_nombre.'/'.$reporteregistro_id.'/revisiones/'.$request->ultimarevision_id;
                Storage::makeDirectory($zip_ruta_servidor); //crear directorio
                $zip_ruta_completa = storage_path('app/reportes/proyecto/'.$proyecto_id.'/'.$agente_nombre.'/'.$reporteregistro_id.'/revisiones/'.$request->ultimarevision_id);
                
                // Zip File Name
                $zip_nombre = 'Informe de '.$agente_nombre.' '.$informe_tipo.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';

                // Create ZipArchive Obj
                $zip = new ZipArchive;

                if ($zip->open($zip_ruta_completa . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE)
                {
                    // Add File in ZipArchive
                    $zip->addFile(storage_path('app/reportes/informes/'.$informe_nombre), $informe_nombre); //Word


                    foreach($anexos_lista as $key => $file)
                    {
                        if (Storage::exists($file->archivo))
                        {
                            $extencion = explode(".", $file->archivo);
                            $zip->addFile(storage_path('app/'.$file->archivo), ($key+1).'.- '.$file->nombre.'.'.$extencion[1]); // Pdf Anexos
                        }
                    }


                    $zip->close(); // Close ZipArchive
                }
                

                // $headers = array('Content-Type' => 'application/octet-stream'); // Set Header
                // $zip_rutacompleta = $zip_ruta_completa.'/'.$zip_nombre;
                // if(file_exists($zip_rutacompleta))
                // {
                    // return response()->download($zip_rutacompleta, $zip_nombre, $headers)->deleteFileAfterSend(true); // DESCARGAR ZIP
                // }


                //----------------------------------


                // ELIMINAR INFORME word (PORQUE YA ESTÁ EN EL ZIP)
                if (Storage::exists('reportes/informes/'.$informe_nombre))
                {
                    Storage::delete('reportes/informes/'.$informe_nombre);
                }


                //================================================================================
                // GUARDAR RUTA EN BASE DE DATOS


                $archivo = reporterevisionesarchivoModel::create([
                      'reporterevisiones_id' => $request->ultimarevision_id
                    , 'reporterevisionesarchivo_tipo' => $informe_tipo
                    , 'reporterevisionesarchivo_archivo' => $zip_ruta_servidor.'/'.$zip_nombre
                ]);


                //================================================================================
                // OBTENER TODAS LAS REVISIONES DE ESTE PROYECTO


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
                                                AND reporterevisiones.agente_id = 9 -- Agua
                                            ORDER BY
                                                reporterevisiones.reporterevisiones_revision DESC');


                // CREAR NUEVA REVISION
                // -------------------------------------------------


                if (($revisiones[0]->id + 0) == ($request->ultimarevision_id + 0))
                {
                    DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');


                    $revision = reporterevisionesModel::create([
                          'proyecto_id' => $request->proyecto_id
                        , 'agente_id' => $request->agente_id
                        , 'agente_nombre' => $request->agente_nombre
                        , 'reporterevisiones_revision' => ($revisiones[0]->reporterevisiones_revision + 1)
                        , 'reporterevisiones_concluido' => 0
                        , 'reporterevisiones_concluidonombre' => NULL
                        , 'reporterevisiones_concluidofecha' => NULL
                        , 'reporterevisiones_cancelado' => 0
                        , 'reporterevisiones_canceladonombre' => NULL
                        , 'reporterevisiones_canceladofecha' => NULL
                        , 'reporterevisiones_canceladoobservacion' => NULL
                    ]);
                }


                $dato["msj"] = 'Nueva revisión creada correctamente';
            }


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


    /**
     * Display the specified resource.
     *
     * @param  int $proyecto_id
     * @param  int $revision_id
     * @param  int $informe_tipo
     * @param  int $ultima_revision
     * @return \Illuminate\Http\Response
    */
    public function reporteaguaworddescargar($proyecto_id, $revision_id, $informe_tipo, $ultima_revision)
    {
        $agente_nombre = 'Agua';

        
        if (($revision_id+0) == ($ultima_revision+0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $zip_nombre = 'Informe de '.$agente_nombre.' '.$informe_tipo.' - '.$proyecto->proyecto_folio.' ('.$proyecto->proyecto_clienteinstalacion.') + Anexos.zip';


            if (Storage::exists('reportes/informes/'.$zip_nombre))
            {
                return response()->download(storage_path('app/reportes/informes/'.$zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
            }
            else
            {
                return '<h3>No se encontró el informe de '.$agente_nombre.' tipo '.$informe_tipo.', intentelo de nuevo</h3>';
            }
        }
        else
        {
            $archivo_historial = DB::select('SELECT
                                                reporterevisiones.proyecto_id,
                                                reporterevisiones.agente_id,
                                                reporterevisiones.agente_nombre,
                                                reporterevisiones.id,
                                                reporterevisiones.reporterevisiones_revision,
                                                reporterevisiones.reporterevisiones_concluido,
                                                reporterevisiones.reporterevisiones_cancelado,
                                                reporterevisionesarchivo.reporterevisionesarchivo_tipo,
                                                reporterevisionesarchivo.reporterevisionesarchivo_archivo 
                                            FROM
                                                reporterevisiones
                                                LEFT JOIN reporterevisionesarchivo ON reporterevisiones.id = reporterevisionesarchivo.reporterevisiones_id
                                            WHERE
                                                reporterevisiones.id = '.$revision_id.' 
                                                AND reporterevisionesarchivo.reporterevisionesarchivo_tipo = "'.$informe_tipo.'"');

            
            if (count($archivo_historial) > 0)
            {
                if (Storage::exists($archivo_historial[0]->reporterevisionesarchivo_archivo))
                {
                    return response()->download(storage_path('app/'.$archivo_historial[0]->reporterevisionesarchivo_archivo), "", array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(false);
                }
                else
                {
                    return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.' - tipo '.$informe_tipo.'</h3>';
                }
            }
            else
            {
                return '<h3>No se encontró el archivo historial del informe de '.$agente_nombre.' - tipo '.$informe_tipo.'</h3>';
            }
        }
    }
}