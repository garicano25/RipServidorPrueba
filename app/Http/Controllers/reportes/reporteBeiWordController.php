<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;;

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

// Catalogos
use App\modelos\recsensorial\catregionModel;
use App\modelos\recsensorial\catsubdireccionModel;
use App\modelos\recsensorial\catgerenciaModel;
use App\modelos\recsensorial\catactivoModel;

//Revisiones
use App\modelos\reportes\reporterevisionesModel;
use App\modelos\reportes\reporterevisionesarchivoModel;

// Tablas datos del reconocimiento
use App\modelos\recsensorial\recsensorialcategoriaModel;
use App\modelos\recsensorial\recsensorialareaModel;

// Tablas estrucura del reporte
use App\modelos\reportes\reportebeicatalogoModel;
use App\modelos\reportes\reportebeiModel;

use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reportebeipuntosModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reportebeicategoriaModel;
use App\modelos\reportes\reportebeiareaModel;
use App\modelos\reportes\reportebeiareacategoriaModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;
class reporteBeiWordController extends Controller
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
      


        $parrafos = explode('\n\n', $texto);
        $texto_nuevo = '';


        foreach ($parrafos as $key => $parrafo) {
            if (($key + 0) < (count($parrafos) - 1)) {
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


    public function reportebeiword(Request $request){
        try {


            // ====================================================
            //Estilos de las tablas
            $encabezado_celda = array('bgColor' => '1A5276', 'valign' => 'center'); //'bgColor' => '1A5276'
            $encabezado_texto = array('color' => 'FFFFFF', 'size' => 11, 'bold' => false, 'name' => 'Arial');
            $combinar_fila_encabezado = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '1A5276');
            $combinar_fila = array('vMerge' => 'restart', 'valign' => 'center');
            $varias_columnas = array('gridSpan' => 2, 'valign' => 'center');
            $continua_fila = array('vMerge' => 'continue', 'valign' => 'center');
            $celda = array('valign' => 'center');
            $centrado = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $izquierda = array('align' => 'left', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $justificado = array('align' => 'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $texto = array('color' => '000000', 'size' => 11, 'bold' => false, 'name' => 'Arial');
            $textonegrita = array('color' => '000000', 'size' => 11, 'bold' => true, 'name' => 'Arial');
            $textototal = array('color' => 'FFFFFF', 'size' => 11, 'bold' => false, 'name' => 'Arial');






            $proyecto_id = $request->proyecto_id;
            $reportebei_id = $request->reportebei_id;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL, "es_MX");

            ################ DATOS GENERALES ######################
            $agente_id = 22;
            $agente_nombre = "BEI";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
            $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reportebeiModel::where('proyecto_id', $proyecto_id)->get();
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

            if ($reportebei_id > 0) {
                $reportebei  = reportebeiModel::findOrFail($reportebei_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe guardar los datos para poder descargarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================

            $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_beis.docx')); //Ruta carpeta storage
            


            ################ PORTADA EXTERNA ####################
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 22) // Iluminacion
                ->orderBy('updated_at', 'DESC')
                ->get();

            if (count($titulo_partida) > 0) {

                //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $clienteInfo[0]->NUMERO_CONTRATO);

                $plantillaword->setValue('PARTIDA', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion));
            } else {

                $plantillaword->setValue('PARTIDA', "");
                $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
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


            $fecha = $agente[0]->reportebei_mes . ' del ' . $agente[0]->reportebei_fecha;
            $plantillaword->setValue('lugar_fecha_portada', $recsensorial->recsensorial_direccion . ' ' . $fecha);
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


            // PORTADA
            //================================================================================

            $NIVEL_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . "<w:br />";
            $NIVEL_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . "<w:br />";
            $NIVEL_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . "<w:br />";
            $NIVEL_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . "<w:br />";
            $NIVEL_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . "<w:br />";
            $NIVEL_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6 . "<w:br />";
            $plantillaword->setValue(
                'ESTRUCTURA',
                $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6
            );

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

            $plantillaword->setValue(
                'ENCABEZADO',
                $NIVEL1 . $NIVEL2 . $NIVEL3 . $NIVEL4 . $NIVEL5
            );
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



            //-----------------------------------------
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

            $introduccionTexto = $agente[0]->reportebei_introduccion;
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
            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                $where_definiciones = 'AND reportedefiniciones.catactivo_id = ' . $proyecto->catactivo_id;
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

            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================

            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_objetivoespecifico));

            // METODOLOGIA
            //================================================================================

            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_metodologia_4_2));


            // UBICACION
            //================================================================================

            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_ubicacioninstalacion));

            if ($reportebei->reportebei_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reportebei->reportebei_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reportebei->reportebei_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }

            // PROCESO INSTALACION
            //================================================================================
            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reportebei->reportebei_procesoinstalacion));



            // TABLA 5.3 Población ocupacionalmente expuesta
            //================================================================================
            // Crear tabla
            $table = null;
            $No = 1;
            $total = 0;
            $table = new Table(array('name' => 'Arial', 'width' => 13500, 'borderSize' => 10, 'borderColor' => '000000', 'cellMargin' => 0, 'spaceAfter' => 0, 'unit' => TblWidth::TWIP));

            $sql = DB::select('SELECT
                                        reportearea.proyecto_id,
                                        reportearea.id,
                                        reportearea.aplica_bei,
                                        reportearea.reportearea_instalacion,
                                        reportearea.reportearea_nombre,
                                        reportearea.reportearea_orden,
                                        reportearea.reportebeiarea_porcientooperacion,
                                        IF( IFNULL( reportearea.reportebeiarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reportebeiarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                        reportearea.reportearea_descripcion,
                                        reporteareacategoria.reportecategoria_id,
                                        reportecategoria.reportecategoria_orden,
                                        reportecategoria.reportecategoria_nombre,
                                        IFNULL((
                                            SELECT
                                                IF(reportebeiareacategoria.reportebeicategoria_id, "checked", "") AS checked
                                            FROM
                                                reportebeiareacategoria
                                            WHERE
                                                reportebeiareacategoria.reportebeiarea_id = reportearea.id
                                                AND reportebeiareacategoria.reportebeicategoria_id = reporteareacategoria.reportecategoria_id
                                                AND reportebeiareacategoria.reportebeiareacategoria_poe = ?
                                            LIMIT 1
                                        ), "") AS checked,
                                        reportecategoria.reportecategoria_horas,
                                        reporteareacategoria.reporteareacategoria_total,
                                        reporteareacategoria.reporteareacategoria_geh,
                                        reporteareacategoria.reporteareacategoria_actividades

                                    FROM
                                        reportearea
                                        LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
                                        LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                    WHERE
                                        reportearea.proyecto_id = ?
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC,
                                        reportecategoria.reportecategoria_orden ASC,
                                        reportecategoria.reportecategoria_nombre ASC', [$reportebei_id, $proyecto_id]);


            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(5000, $encabezado_celda)->addTextRun($centrado)->addText('No', $encabezado_texto);
            $table->addCell(5000, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell(6000, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
            $table->addCell(1500, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad principal de la instalación', $encabezado_texto);

            // registros tabla
            $area = 'xxx';
            
            foreach ($sql as $key => $value) {

                if ($area != $value->reportearea_nombre) {
                    $table->addRow(); //fila
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reportearea_orden, $texto);
                    $table->addCell(null, $combinar_fila)->addTextRun($centrado)->addText($value->reportearea_nombre, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_total, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_actividades, $texto);

                    $area = $value->reportearea_nombre;
                } else {
                    $table->addRow(); //fila
                    $table->addCell(null, $continua_fila);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reportecategoria_nombre, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_total, $texto);
                    $table->addCell(null, $celda)->addTextRun($centrado)->addText($value->reporteareacategoria_actividades, $texto);
                }
            }

            $plantillaword->setComplexBlock('TABLA_5_3', $table);


            // TABLA 5.4 Equipo de Protección Personal (EPP)
            //================================================================================


            $sql = DB::select('SELECT
                                    reporteruidoepp.id,
                                    reporteruidoepp.proyecto_id,
                                    reporteruidoepp.registro_id,
                                    reporteruidoepp.reporteruidoepp_partecuerpo,
                                    reporteruidoepp.reporteruidoepp_equipo 
                                FROM
                                    reporteruidoepp
                                WHERE
                                    reporteruidoepp.proyecto_id = ?
                                    AND reporteruidoepp.registro_id = ? 
                                ORDER BY
                                    reporteruidoepp.id ASC', [$proyecto_id, $reportebei_id]);

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $ancho_col_1 = 4000;
            $ancho_col_2 = 5500;
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Región anatómica', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('EPP', $encabezado_texto);

            foreach ($sql as $key => $value) {
                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($value->reporteruidoepp_partecuerpo,
                    $texto
                );
                $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($value->reporteruidoepp_equipo, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_4', $table);
            

            

        } catch (Exception $e) {
            // respuesta
            $dato["msj"] = 'Error ' . $e->getMessage();
            return response()->json($dato);
        }
    }


    public function reportebeiworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'BEI';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $zip_nombre = 'Informe de BEI - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';


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