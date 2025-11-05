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


//Revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;


// Tablas estrucura del reporte
use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteanexosModel;

use App\modelos\reportes\reportealimentoscatalogoModel;
use App\modelos\reportes\reportealimentosModel;
use App\modelos\reportes\reportecategoriaModel;
use App\modelos\reportes\reporteareaModel;
use App\modelos\recsensorial\catConclusionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteAlimentosPuntosSuperficiesInertesModel;
use App\modelos\reportes\reporteAlimentosPuntosSuperficiesVivasModel;
use App\modelos\reportes\reporteAlimentosPuntosAlimentosModel;


use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;



class reportealimentosWordController extends Controller
{
    

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
        }


        $texto = str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $texto);
        $texto = str_replace("INSTALACION_DIRECCION", $proyecto->proyecto_clientedireccionservicio, $texto);
        $texto = str_replace("INSTALACION_CODIGOPOSTAL", "C.P. " . $recsensorial->recsensorial_codigopostal, $texto);
        $texto = str_replace("INSTALACION_COORDENADAS", $recsensorial->recsensorial_coordenadas, $texto);
        $texto = str_replace("REPORTE_FECHA_LARGA", $reportefecha[2] . " de " . $meses[($reportefecha[1] + 0)] . " del año " . $reportefecha[0], $texto);
        // $texto = str_replace("\n\n", "<w:br/><w:br/>", $texto);
        // $texto = str_replace("\n", "<w:br/>", $texto);

        // return $texto;


        $parrafos = explode('\n\n', $texto);
        $texto_nuevo = '';


        foreach ($parrafos as $key => $parrafo) {
            if (($key + 0) < (count($parrafos) - 1)) {
                $text = explode("\n", $parrafo);

                foreach ($text as $key2 => $parrafo2) {
                    if (($key2 + 0) < (count($text) - 1)) {
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

                        // SALTO DE PAGINA (<w:br/></w:t></w:r><w:r ><w:br w:type="page"/></w:r><w:r><w:t><w:br/>)

                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>' . htmlspecialchars($parrafo2) . '</w:t>
                                        </w:p>';
                    } else {
                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>' . htmlspecialchars($parrafo2) . '</w:t>
                                        </w:p><w:br/>';
                    }
                }
            } else {
                $text = explode("\n", $parrafo);

                foreach ($text as $key2 => $parrafo2) {
                    if (($key2 + 0) < (count($text) - 1)) {
                        $texto_nuevo .= '<w:p>
                                            <w:pPr>
                                                <w:jc w:val="both"/>
                                                <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                            </w:pPr>
                                            <w:t>' . htmlspecialchars($parrafo2) . '</w:t>
                                        </w:p>';
                    } else {
                        $texto_nuevo .= '<w:t>' . htmlspecialchars($parrafo2) . '</w:t>';
                    }
                }
            }
        }


        return $texto_nuevo;
    }



    public function introduccion($proyecto, $texto)
    {
        if (!$proyecto) {
            return $texto;
        }


        $proyecto = $proyecto;

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


  
    public function reportealimentosword(Request $request)
    {
        try {
         


            $proyecto_id = $request->proyecto_id;
            $reportealimentos_id = $request->reportealimentos_id;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL, "es_MX");

            ################ DATOS GENERALES ######################
            $agente_id = 11;
            $agente_nombre = "Alimentos";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
            $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reportealimentosModel::where('proyecto_id', $proyecto_id)->get();
            if ($proyecto->requiereContrato == 1) {

                $contratoId = $proyecto->contrato_id;

                $clienteInfo = DB::table('contratos_clientes as cc')
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
                $clienteInfo = clienteModel::where('id', $proyecto->cliente_id)->get();
            }


            ########### VALIDACION DEL RECONOCIMIENTO #################

            if ($reportealimentos_id > 0) {
                $reportealimentos  = reportealimentosModel::findOrFail($reportealimentos_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe guardar los datos para poder descargarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================
            $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_alimentos.docx')); //Ruta carpeta 

            //================================= Limpiamos el texto =================================================
            function sanitizeText($text)
            {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }

            ################ PORTADA EXTERNA ####################
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 11) // Alimentos
                ->orderBy('updated_at', 'DESC')
                ->get();

            if (count($titulo_partida) > 0) {

                //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $clienteInfo[0]->NUMERO_CONTRATO);

                $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
            } else {

                $plantillaword->setValue('PARTIDA', "");
                $plantillaword->setValue('proyecto_portada', 'No existe una partida para este informe en el contrato.');
            }

            $plantillaword->setValue('folio_portada', $proyecto->proyecto_folio);
            $plantillaword->setValue('razon_social_portada', $cliente->cliente_RazonSocial);

            // PARTE INTALACION PORTADA
            $OPCION_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . " | ";
            $OPCION_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . " | ";
            $OPCION_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . " | ";
            $OPCION_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . " | ";
            $OPCION_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . " | ";
            $OPCION_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6;

            $plantillaword->setValue('instalación_portada', $OPCION_PORTADA1 . $OPCION_PORTADA2 . $OPCION_PORTADA3 . $OPCION_PORTADA4 . $OPCION_PORTADA5 . $OPCION_PORTADA6);


            $fecha = $agente[0]->reportealimentos_mes . ' del ' . $agente[0]->reportealimentos_fecha;
            $plantillaword->setValue('lugar_fecha_portada', $recsensorial->recsensorial_direccion . ' ' . $fecha);
            $plantillaword->setValue('PORTADA_FECHA', $fecha);


            //IMAGEN DE LA PORTADA
            if ($recursos[0]->RUTA_IMAGEN_PORTADA) {
                if (file_exists(storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA))) {

                    $plantillaword->setImageValue('foto_portada', array('path' => storage_path('app/' . $recursos[0]->RUTA_IMAGEN_PORTADA), 'width' => 969, 'height' => 689, 'ratio' => true, 'borderColor' => '000000'));
                } else {

                    $plantillaword->setValue('foto_portada', 'LA IMAGEN NO HA SIDO ENCONTRADA');
                }
            } else {

                $plantillaword->setValue('foto_portada', 'LA IMAGEN DE LA PORTADA NO HA SIDO CARGADA');
            }


            // PORTADA
            //================================================================================

            $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
            $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
            $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
            $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
            $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
            $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
            $plantillaword->setValue('ESTRUCTURA',$NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);

            if (
                $proyecto->requiereContrato == 1
            ) {

                $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                $plantillaword->setValue('CONTRATO', $clienteInfo[0]->NUMERO_CONTRATO);
                $plantillaword->setValue('DESCRIPCION_CONTRATO', $clienteInfo[0]->DESCRIPCION_CONTRATO);

                $plantillaword->setValue('PIE_PAGINA', $clienteInfo[0]->CONTRATO_PLANTILLA_PIEPAGINA);
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

            $plantillaword->setValue('ENCABEZADO',$NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
            $plantillaword->setValue('INSTALACION_NOMBRE', $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5);
            $plantillaword->setValue('INSTALACION_NOMBRE_TEXTO', $proyecto->proyecto_clienteinstalacion);


            //LOGOS DE AS EMPRESAS DE INFORME
            if ($proyecto->requiereContrato == 1) {

                if ($clienteInfo[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
                    if (file_exists(storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO))) {

                        $plantillaword->setImageValue('LOGO_IZQUIERDO', array('path' => storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                        $plantillaword->setImageValue('LOGO_IZQUIERDO_PORTADA', array('path' => storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGOIZQUIERDO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('LOGO_IZQUIERDO', 'IMAGEN NO ENCONTRADA');
                        $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'IMAGEN NO ENCONTRADA');
                    }
                } else {
                    $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN IMAGEN');
                }


                if ($clienteInfo[0]->CONTRATO_PLANTILLA_LOGODERECHO) {
                    if (file_exists(storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGODERECHO))) {

                        $plantillaword->setImageValue('LOGO_DERECHO', array('path' => storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));

                        $plantillaword->setImageValue('LOGO_DERECHO_PORTADA', array('path' => storage_path('app/' . $clienteInfo[0]->CONTRATO_PLANTILLA_LOGODERECHO), 'width' => 120, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    } else {

                        $plantillaword->setValue('LOGO_DERECHO', 'IMAGEN NO ENCONTRATA');
                        $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'IMAGEN NO ENCONTRATA');
                    }
                } else {
                    $plantillaword->setValue('LOGO_DERECHO', 'SIN IMAGEN');
                    $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN IMAGEN');
                }
            } else {

                $plantillaword->setValue('LOGO_DERECHO', 'SIN CONTRATO');
                $plantillaword->setValue('LOGO_IZQUIERDO', 'SIN CONTRATO');

                $plantillaword->setValue('LOGO_DERECHO_PORTADA', 'SIN CONTRATO');
                $plantillaword->setValue('LOGO_IZQUIERDO_PORTADA', 'SIN CONTRATO');
            }

            //--------------------------------------------------------------------------------------------------------------------------

            ##### REVISIONES ###################
            $cancelado_texto = '';
            if ($revision->reporterevisiones_cancelado == 1) {
                $cancelado_texto = '<w:br/>INFORME REVISIÓN ' . $revision->reporterevisiones_revision . ' CANCELADA';
            }

            if (($revision->reporterevisiones_revision + 0) > 0) {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
            } else {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
            }



            ##### INTRODUCCION ###################

            $introduccionTexto = $agente[0]->reportealimentos_introduccion;
            $introduccionTextoModificado = $this->introduccion($proyecto, $introduccionTexto);

            // Asigna el texto modificado a la plantilla
            $plantillaword->setValue('INTRODUCCION', $introduccionTextoModificado);

            if (($revision->reporterevisiones_revision + 0) > 0) {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
            } else {
                $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
            }


            // DEFINICIONES
            //================================================================================


            $definiciones_simbolo = ["¹", "²", "³", "⁴", "⁵", "⁶", "⁷", "⁸", "⁹", "*", "●", "♦", "~", "°", "¨", "#"];
            $definiciones_fuentes;


            $where_definiciones = '';
            if (($recsensorial->recsensorial_tipocliente + 0) == 1) 
            {
                $where_definiciones = 'AND reportedefiniciones.catactivo_id = ' . $proyecto->catactivo_id;
            }


            $sql = collect(DB::select('SELECT
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
                                                        reportedefinicionescatalogo.agente_nombre LIKE "' . $agente_nombre . '"
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
                                                        reportedefiniciones.agente_nombre LIKE "' . $agente_nombre . '"
                                                        ' . $where_definiciones . ' 
                                                    ORDER BY
                                                        reportedefiniciones.agente_nombre ASC
                                                )
                                            ) AS TABLA
                                        GROUP BY
                                            TABLA.fuente
                                        ORDER BY
                                            TABLA.fuente ASC'));


            $definicionesfuentes = '';
            if (count($sql) > 1) {
                foreach ($sql as $key => $value) {
                    $definiciones_fuentes[] = array(
                        'fuente_descripcion' => $value->fuente,
                        'fuente_simbolo' => ' ' . $definiciones_simbolo[$key] . '*'
                    );


                    if (($key + 0) < (count($sql) - 1)) {
                        $definicionesfuentes .= 'Fuentes ' . $definiciones_simbolo[$key] . '*: ' . $value->fuente . '<w:br/>';
                    } else {
                        $definicionesfuentes .= 'Fuentes ' . $definiciones_simbolo[$key] . '*: ' . $value->fuente;
                    }
                }
            } else {
                $definiciones_fuentes[] = array(
                    'fuente_descripcion' => $sql[0]->fuente,
                    'fuente_simbolo' => ''
                );

                $definicionesfuentes = 'Fuentes: ' . $sql[0]->fuente;
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
                                                        reportedefinicionescatalogo.agente_nombre LIKE "' . $agente_nombre . '"
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
                                                        reportedefiniciones.agente_nombre LIKE "' . $agente_nombre . '"
                                                        ' . $where_definiciones . ' 
                                                    ORDER BY
                                                        reportedefiniciones.agente_nombre ASC
                                                )
                                            ) AS TABLA
                                        ORDER BY
                                            -- TABLA.catactivo_id ASC,
                                            TABLA.concepto ASC'));


            $definiciones = '';
            foreach ($sql as $key => $value) {
                foreach ($definiciones_fuentes as $key2 => $dato) {
                    if ($value->fuente == $dato['fuente_descripcion']) {
                        if (($key + 0) < (count($sql) - 1)) {

                            $definiciones .= '<w:p>
                                                <w:pPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                </w:pPr>
                                                <w:rPr>
                                                    <w:b w:val="true"/>
                                                </w:rPr>
                                                <w:t>
                                                    ' . $value->concepto . '
                                                </w:t>
                                                <w:rPr>
                                                    <w:b w:val="false"/>
                                                </w:rPr>
                                                <w:t>
                                                    ' . htmlspecialchars($value->descripcion) . '' . $dato['fuente_simbolo'] . '
                                                </w:t>
                                            </w:p><w:br/>';
                        } else {

                            $definiciones .= '<w:p>
                                                <w:pPr>
                                                    <w:jc w:val="both"/>
                                                    <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                </w:pPr>
                                                <w:rPr>
                                                    <w:b w:val="true"/>
                                                </w:rPr>
                                                <w:t>
                                                    ' . $value->concepto . '
                                                </w:t>
                                                <w:rPr>
                                                    <w:b w:val="false"/>
                                                </w:rPr>
                                                <w:t>
                                                    ' . htmlspecialchars($value->descripcion) . '' . $dato['fuente_simbolo'] . '
                                                </w:t>
                                            </w:p>';
                        }
                    }
                }
            }

            $plantillaword->setValue('DEFINICIONES', $definiciones);


            // OBJETIVO GENERAL
            //================================================================================


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_objetivoespecifico));


            // METODOLOGIA
            //================================================================================


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_metodologia_4_2));
            $plantillaword->setValue('METODOLOGIA_5_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_metodologia_5_1));
            $plantillaword->setValue('METODOLOGIA_5_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_metodologia_5_2));


            // UBICACION
            //================================================================================

            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_ubicacioninstalacion));

            // Imagen FOTO
            if ($reportealimentos->reportealimentos_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reportealimentos->reportealimentos_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reportealimentos->reportealimentos_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            // PROCESO INSTALACION
            //================================================================================

            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_procesoinstalacion));
            


            // TIPOGRAFIAS PARA LAS TABLAS
            //================================================================================
            $fuente = 'Poppins';
            $font_size = 10;

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



            // TABLA  8.1.- Resultados de calidad e inocuidad de alimentos
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            $sql8_1 = DB::select('CALL sp_obtener_puntos_alimentos_8_1_b(?)', [$proyecto_id]);


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de medición', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Método de análisis', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores expuestos', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);
    

            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_1 as $key => $value) {

                if ($punto != $value->PUNTO) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->AREA), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->FECHA_MEDICION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->TRABAJADORES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma"){
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    }else{
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;

                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                }
            }

            $plantillaword->setComplexBlock('TABLA_8_1', $table);


            // TABLA  8.1.1.- Resultados de calidad e inocuidad de alimentos
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            $sql8_1_1 = DB::select('CALL sp_obtener_puntos_alimentos_8_1_1_b(?)', [$proyecto_id]);


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de medición', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Unidades', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Método de análisis', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores expuestos', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
          


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_1_1 as $key => $value) {

                if ($punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->AREA), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->FECHA_MEDICION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->TRABAJADORES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);


                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);

                   
                }
            }

            $plantillaword->setComplexBlock('TABLA_8_1_1', $table);


            // TABLA  8.2.- Resultados, evalución de superficies vivas
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            $sql8_2 = DB::select('CALL sp_obtener_puntos_alimentos_8_2_b(?)', [$proyecto_id]);


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de medición', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Unidades', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Método de análisis', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores expuestos', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_2 as $key => $value) {

                if ($punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->AREA), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->FECHA_MEDICION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->TRABAJADORES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }
                }
            }

            $plantillaword->setComplexBlock('TABLA_8_2', $table);



            // TABLA  8.3.- Resultados, evalución de superficies inertes
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            $sql8_3 = DB::select('CALL sp_obtener_puntos_alimentos_8_3_b(?)', [$proyecto_id]);


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(3000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Fecha de medición', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(2000, $encabezado_celda)->addTextRun($centrado)->addText('Unidades', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Método de análisis', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('No. de trabajadores expuestos', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_3 as $key => $value) {

                if (
                    $punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->AREA), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->FECHA_MEDICION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->TRABAJADORES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->UNIDADES), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->METODO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }
                }
            }

            $plantillaword->setComplexBlock('TABLA_8_3', $table);


            // 9.1.- Calidad e inocuidad de alimentos
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_1 as $key => $value) {

                if ($punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }
                }
            }

            $plantillaword->setComplexBlock('TABLA_9_1', $table);



            // TABLA  8.1.1.- Resultados de calidad e inocuidad de alimentos
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(3500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);



            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_1_1 as $key => $value) {

                if (
                    $punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);


                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                }
            }

            $plantillaword->setComplexBlock('TABLA_9_1_1', $table);



            // TABLA  9.2.- Superficies vivas
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_2 as $key => $value) {

                if (
                    $punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center', 'color' => '000000'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center', 'color' => 'FFFFFF'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }
                }
            }

            $plantillaword->setComplexBlock('TABLA_9_2', $table);



            // TABLA  9.3.- Superficies inertes
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Poppins', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Punto', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Parametro', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Ubicación', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración obtenida', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Concentración permisible', $encabezado_texto);
            $table->addCell(2500, $encabezado_celda)->addTextRun($centrado)->addText('Cumplimiento normativo', $encabezado_texto);


            // registros tabla
            $punto = 'xxx';

            foreach ($sql8_3 as $key => $value) {

                if (
                    $punto != $value->PUNTO
                ) {
                    $table->addRow(); //fila
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->PUNTO), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText(sanitizeText($value->UBICACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }

                    $punto = $value->PUNTO;
                } else {

                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->PARAMETRO), $texto);
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION), $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText(sanitizeText($value->CONCENTRACION_PERMISIBLE), $texto);

                    if ($value->CUMPLIMIENTO_NORMATIVO == "Dentro de norma") {
                        $table->addCell(null, array('bgColor' => '#00FF00', 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $texto);
                    } else {
                        $table->addCell(null, array('bgColor' => "#FF0000", 'valign' => 'center'))->addTextRun($centrado)->addText($value->CUMPLIMIENTO_NORMATIVO, $textototal);
                    }
                }
            }

            $plantillaword->setComplexBlock('TABLA_9_3', $table);



            // CONCLUSION
            //================================================================================
            $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportealimentos->reportealimentos_conclusion));


            // RECOMENDACIONES
            //================================================================================


            $sql = collect(DB::select('SELECT
                                            TABLA.id,
                                            TABLA.agente_id,
                                            TABLA.agente_nombre,
                                            TABLA.recomendaciones_tipo,
                                            TABLA.recomendaciones_descripcion,
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
                                                                            reporterecomendaciones.proyecto_id = ' . $proyecto_id . ' 
                                                                            AND reporterecomendaciones.registro_id = ' . $reportealimentos_id . ' 
                                                                            AND reporterecomendaciones.reporterecomendacionescatalogo_id = reporterecomendacionescatalogo.id
                                                                        LIMIT 1 
                                                                ), NULL) AS recomendaciones_descripcion
                                                            FROM
                                                                reporterecomendacionescatalogo
                                                            WHERE
                                                                reporterecomendacionescatalogo.agente_nombre = "' . $agente_nombre . '"
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
                                                        "checked" AS checked
                                                    FROM
                                                        reporterecomendaciones
                                                    WHERE
                                                        reporterecomendaciones.proyecto_id = ' . $proyecto_id . '
                                                        AND reporterecomendaciones.agente_nombre = "' . $agente_nombre . '" 
                                                        AND reporterecomendaciones.registro_id = ' . $reportealimentos_id . ' 
                                                        AND reporterecomendaciones.reporterecomendacionescatalogo_id = 0
                                                    ORDER BY
                                                        reporterecomendaciones.id ASC
                                                )
                                            ) AS TABLA
                                        WHERE
                                            TABLA.checked = "checked"
                                        ORDER BY
                                            TABLA.recomendaciones_tipo DESC'));


            $recomendacion = '';
            foreach ($sql as $key => $value) {
                if (($key + 0) < (count($sql) - 1)) {
                    $recomendacion .= $value->recomendaciones_descripcion . '\n\n';
                } else {
                    $recomendacion .= $value->recomendaciones_descripcion;
                }
            }


            $plantillaword->setValue('RECOMENDACIONES', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $recomendacion));


            // RESPONSABLES
            //================================================================================


            // RESPONSABLE 1, FOTO DOCUMENTO
            if ($reportealimentos->reportealimentos_responsable1documento) {
                if (file_exists(storage_path('app/' . $reportealimentos->reportealimentos_responsable1documento))) {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reportealimentos->reportealimentos_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE1', $reportealimentos->reportealimentos_responsable1 . "<w:br/>" . $reportealimentos->reportealimentos_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reportealimentos->reportealimentos_responsable2documento) {
                if (file_exists(storage_path('app/' . $reportealimentos->reportealimentos_responsable2documento))) {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reportealimentos->reportealimentos_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE2', $reportealimentos->reportealimentos_responsable2 . "<w:br/>" . $reportealimentos->reportealimentos_responsable2cargo);


            // TABLA ANEXO 4, Memoria fotográfica  - CREAR VARIABLES
            //================================================================================


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

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
                                    proyectoevidenciafoto.proyecto_id = ' . $proyecto_id . '
                                    AND proyectoevidenciafoto.agente_nombre = "' . $agente_nombre . '"
                                ORDER BY
                                    proyectoevidenciafoto.proyectoevidenciafoto_nopunto ASC');

            $ancho_col_1 = 4750;
            $ancho_col_2 = 4750;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $table->addRow(400, array('tblHeader' => true));
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => '000000', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1,))->addTextRun($centrado)->addText('Memoria fotográfica', array('color' => '000000', 'size' => 12, 'bold' => true, 'name' => $fuente));
            $table->addRow(400, array('tblHeader' => true));
            $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Evaluación de iluminación', $encabezado_texto);


            for ($i = 0; $i < count($fotos); $i += 4) {
                $foto1 = '';
                $descripcion1 = '';
                if ($i < count($fotos)) {
                    $foto1 = '${PUNTO_' . $i . '_FOTO}';
                    $descripcion1 = '${PUNTO_' . $i . '_DESCRIPCION}';
                }

                $foto2 = '';
                $descripcion2 = '';
                if (($i + 1) < count($fotos)) {
                    $foto2 = '${PUNTO_' . ($i + 1) . '_FOTO}';
                    $descripcion2 = '${PUNTO_' . ($i + 1) . '_DESCRIPCION}';
                }

                $foto3 = '';
                $descripcion3 = '';
                if (($i + 2) < count($fotos)) {
                    $foto3 = '${PUNTO_' . ($i + 2) . '_FOTO}';
                    $descripcion3 = '${PUNTO_' . ($i + 2) . '_DESCRIPCION}';
                }

                $foto4 = '';
                $descripcion4 = '';
                if (($i + 3) < count($fotos)) {
                    $foto4 = '${PUNTO_' . ($i + 3) . '_FOTO}';
                    $descripcion4 = '${PUNTO_' . ($i + 3) . '_DESCRIPCION}';
                }


                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($foto1, $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($foto2, $texto);
                $table->addRow(1000); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion1, $texto);
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion2, $texto);

                if (($i + 2) < count($fotos)) {
                    $table->addRow(); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($foto3, $texto);
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($foto4, $texto);
                    $table->addRow(1000); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion3, $texto);
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion4, $texto);
                }
            }


            if (count($fotos) > 0) {
                $plantillaword->setComplexBlock('TABLA_MEMORIA_FOTOGRAFICA', $table);
            } else {
                $plantillaword->setValue('TABLA_MEMORIA_FOTOGRAFICA', 'NO HAY MEMORIA FOTOGRÁFICA QUE MOSTRAR.');
            }



            // ANEXO 5, Planos de ubicación de luminarias y puntos de evaluación por área - CREAR VARIABLES
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
                                                reporteplanoscarpetas.proyecto_id = ' . $proyecto_id . '
                                                AND reporteplanoscarpetas.agente_nombre = "' . $agente_nombre . '" 
                                                AND reporteplanoscarpetas.registro_id = ' . $reportealimentos_id);


            $planoscarpetasvPoppinses = '';
            $planocontador = 0;
            $plano_archivo = array();
            if (count($planoscarpetas) > 0) {
                foreach ($planoscarpetas as $key => $carpeta) {
                    $planos = DB::select('SELECT
                                                proyectoevidenciaplano.proyecto_id,
                                                proyectoevidenciaplano.agente_id,
                                                proyectoevidenciaplano.agente_nombre,
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta,
                                                proyectoevidenciaplano.proyectoevidenciaplano_archivo 
                                            FROM
                                                proyectoevidenciaplano 
                                            WHERE
                                                proyectoevidenciaplano.proyecto_id = ' . $carpeta->proyecto_id . ' 
                                                AND proyectoevidenciaplano.agente_nombre = "' . $carpeta->agente_nombre . '" 
                                                AND proyectoevidenciaplano.proyectoevidenciaplano_carpeta = "' . $carpeta->reporteplanoscarpetas_nombre . '" 
                                            ORDER BY
                                                proyectoevidenciaplano.proyectoevidenciaplano_carpeta ASC');

                    foreach ($planos as $key => $plano) {
                        $planoscarpetasvPoppinses .= '${PLANO_' . $planocontador . '_FOTO}';

                        $plano_archivo[] = $plano->proyectoevidenciaplano_archivo;

                        $planocontador += 1;
                    }
                }
            } else {
                $plano_archivo = array();
                $planoscarpetasvPoppinses = 'NO HAY PLANOS QUE MOSTRAR.';
            }


            $plantillaword->setValue('PLANOS', $planoscarpetasvPoppinses);


           

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CREAR WORD TEMPORAL


            // GUARDAR
            Storage::makeDirectory('reportes/informes'); //crear directorio
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_alimentos_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

            // sleep(1);

            // ABRIR NUEVA PLANTILLA
            $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_alimentos_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            // TABLA ANEXO 1, Memoria fotográfica - AGREGAR FOTOS
            //================================================================================


            for ($i = 0; $i < count($fotos); $i += 4) {
                if ($i < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . $i . '_FOTO', array('path' => storage_path('app/' . $fotos[$i]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . $i . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_' . $i . '_DESCRIPCION', "Punto " . $fotos[$i]->proyectoevidenciafoto_nopunto . " " . $fotos[$i]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . $i . '_DESCRIPCION', $fotos[$i]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 1) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 1) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 1)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 1) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_' . ($i + 1) . '_DESCRIPCION', "Punto " . $fotos[($i + 1)]->proyectoevidenciafoto_nopunto . " " . $fotos[($i + 1)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 1) . '_DESCRIPCION', $fotos[($i + 1)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 2) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 2) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 2)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 2) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_' . ($i + 2) . '_DESCRIPCION', "Punto " . $fotos[($i + 2)]->proyectoevidenciafoto_nopunto . " " . $fotos[($i + 2)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 2) . '_DESCRIPCION', $fotos[($i + 2)]->proyectoevidenciafoto_descripcion);
                }


                if (($i + 3) < count($fotos)) {
                    if (Storage::exists($fotos[$i]->proyectoevidenciafoto_archivo)) {
                        $plantillaword->setImageValue('PUNTO_' . ($i + 3) . '_FOTO', array('path' => storage_path('app/' . $fotos[($i + 3)]->proyectoevidenciafoto_archivo), 'height' => 284, 'width' => 284, 'ratio' => false, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('PUNTO_' . ($i + 3) . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
                    }

                    // $plantillaword->setValue('PUNTO_' . ($i + 3) . '_DESCRIPCION', "Punto " . $fotos[($i + 3)]->proyectoevidenciafoto_nopunto . " " . $fotos[($i + 3)]->proyectoevidenciafoto_descripcion);
                    $plantillaword->setValue('PUNTO_' . ($i + 3) . '_DESCRIPCION', $fotos[($i + 3)]->proyectoevidenciafoto_descripcion);
                }
            }


            // ANEXO 2, Planos de ubicación de luminarias y puntos de evaluación por área - AGREGAR FOTOS
            //================================================================================


            for ($i = 0; $i < count($plano_archivo); $i++) {
                if (Storage::exists($plano_archivo[$i])) {
                    $plantillaword->setImageValue('PLANO_' . $i . '_FOTO', array('path' => storage_path('app/' . $plano_archivo[$i]), 'height' => 690, 'width' => 588, 'ratio' => false, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('PLANO_' . $i . '_FOTO', 'NO SE ENCONTRÓ LA FOTO<w:br/>');
                }
            }


            // ARCHIVO ZIP ANEXOS
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
                                                    reporteanexos.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteanexos.registro_id = ' . $reportealimentos_id . '
                                                    AND reporteanexos.agente_nombre = "' . $agente_nombre . '"
                                            )
                                        ) AS ANEXO');



            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL


            // GUARDAR
            $informe_nombre = 'Informe de Alimentos - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';
            $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word


            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_alimentos_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                Storage::delete('reportes/informes/Informe_alimentos_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
            }


            


            //--------------------------------------------------------------------------------


            if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
            {
                //================================================================================
                // CREAR .ZIP INFORME

                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');
                // Zip File Name
                $zip_nombre = 'Informe de Alimentos - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
                // Create ZipArchive Obj
                $zip = new ZipArchive;

                if ($zip->open($zip_ruta . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE) {
                    // Add File in ZipArchive
                    $zip->addFile(storage_path('app/reportes/informes/' . $informe_nombre), $informe_nombre); //Word


                    foreach ($anexos_lista as $key => $file) {
                        if (Storage::exists($file->archivo)) {
                            $extencion = explode(".", $file->archivo);
                            $zip->addFile(storage_path('app/' . $file->archivo), ($key + 1) . '.- ' . $file->nombre . '.' . $extencion[1]); // Pdf Anexos
                        }
                    }


                    // Close ZipArchive     
                    $zip->close();
                }


                //----------------------------------


                // ELIMINAR INFORME word (PORQUE YA ESTÁ EN EL ZIP)
                if (Storage::exists('reportes/informes/' . $informe_nombre)) {
                    Storage::delete('reportes/informes/' . $informe_nombre);
                }


                $dato["msj"] = 'Informe creado correctamente';
            } else // Crear informes historial y guardar en base de datos
            {
                //================================================================================
                // CREAR .ZIP INFORME


                // Define Dir Folder
                $zip_ruta_servidor = 'reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reportealimentos_id . '/revisiones/' . $request->ultimarevision_id;
                Storage::makeDirectory($zip_ruta_servidor); //crear directorio
                $zip_ruta_completa = storage_path('app/reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reportealimentos_id . '/revisiones/' . $request->ultimarevision_id);
                // Zip File Name
                $zip_nombre = 'Informe de Alimentos - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
                // Create ZipArchive Obj
                $zip = new ZipArchive;

                if ($zip->open($zip_ruta_completa . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE) {
                    // Add File in ZipArchive
                    $zip->addFile(storage_path('app/reportes/informes/' . $informe_nombre), $informe_nombre); //Word


                    foreach ($anexos_lista as $key => $file) {
                        if (Storage::exists($file->archivo)) {
                            $extencion = explode(".", $file->archivo);
                            $zip->addFile(storage_path('app/' . $file->archivo), ($key + 1) . '.- ' . $file->nombre . '.' . $extencion[1]); // Pdf Anexos
                        }
                    }


                    $zip->close(); // Close ZipArchive
                }


                //----------------------------------


                // ELIMINAR INFORME word (PORQUE YA ESTÁ EN EL ZIP)
                if (Storage::exists('reportes/informes/' . $informe_nombre)) {
                    Storage::delete('reportes/informes/' . $informe_nombre);
                }


                //================================================================================
                // GUARDAR RUTA EN BASE DE DATOS


                $archivo = reporterevisionesarchivoModel::create([
                    'reporterevisiones_id' => $request->ultimarevision_id,
                    'reporterevisionesarchivo_tipo' => 0,
                    'reporterevisionesarchivo_archivo' => $zip_ruta_servidor . '/' . $zip_nombre
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
                                                reporterevisiones.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporterevisiones.agente_id = 11 -- Alimentos 
                                            ORDER BY
                                                reporterevisiones.reporterevisiones_revision DESC');


                // CREAR NUEVA REVISION
                // -------------------------------------------------


                DB::statement('ALTER TABLE reporterevisiones AUTO_INCREMENT = 1;');
                $revision = reporterevisionesModel::create([
                    'proyecto_id' => $request->proyecto_id,
                    'agente_id' => $request->agente_id,
                    'agente_nombre' => $request->agente_nombre,
                    'reporterevisiones_revision' => ($revisiones[0]->reporterevisiones_revision + 1),
                    'reporterevisiones_concluido' => 0,
                    'reporterevisiones_concluidonombre' => NULL,
                    'reporterevisiones_concluidofecha' => NULL,
                    'reporterevisiones_cancelado' => 0,
                    'reporterevisiones_canceladonombre' => NULL,
                    'reporterevisiones_canceladofecha' => NULL,
                    'reporterevisiones_canceladoobservacion' => NULL
                ]);


                $dato["msj"] = 'Nueva revisión creada correctamente';
            }


            //--------------------------------------------------------------------------------


            return response()->json($dato);
        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


   
    public function reportealimentosworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Alimentos';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $zip_nombre = 'Informe de Alimentos - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';


            if (Storage::exists('reportes/informes/' . $zip_nombre)) {
                return response()->download(storage_path('app/reportes/informes/' . $zip_nombre), $zip_nombre, array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(true);
            } else {
                return '<h3>No se encontró el informe de ' . $agente_nombre . ', intentelo de nuevo</h3>';
            }
        } else {
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
                                                reporterevisiones.id = ' . $revision_id);


            if (count($archivo_historial) > 0) {
                if (Storage::exists($archivo_historial[0]->reporterevisionesarchivo_archivo)) {
                    return response()->download(storage_path('app/' . $archivo_historial[0]->reporterevisionesarchivo_archivo), "", array('Content-Type' => 'application/octet-stream'))->deleteFileAfterSend(false);
                } else {
                    return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . ' 1</h3>';
                }
            } else {
                return '<h3>No se encontró el archivo historial del informe de ' . $agente_nombre . ' 2</h3>';
            }
        }
    }
}
