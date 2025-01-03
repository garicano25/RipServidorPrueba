<?php

namespace App\Http\Controllers\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


// Plugins
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Shared\Html;
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
use App\modelos\reconocimientopsico\reconocimientopsicoModel;
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
use App\modelos\reportes\reportenom0353catalogoModel;
use App\modelos\reportes\reportenom0353Model;

use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;

class reportenom0353wordController extends Controller

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
    
            if (($recsensorial->tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                $texto = str_replace('SUBDIRECCION_NOMBRE', $proyecto->catsubdireccion->catsubdireccion_nombre, $texto);
                $texto = str_replace('GERENCIA_NOMBRE', $proyecto->catgerencia->catgerencia_nombre, $texto);
                $texto = str_replace('ACTIVO_NOMBRE', $proyecto->catactivo->catactivo_nombre, $texto);
            } else {
                $texto = str_replace('SUBDIRECCION_NOMBRE', '', $texto);
                $texto = str_replace('GERENCIA_NOMBRE', '', $texto);
                $texto = str_replace('ACTIVO_NOMBRE', '', $texto);
    
                $texto = str_replace('PEMEX Exploración y Producción', $recsensorial->empresa, $texto);
            }
    
            $texto = str_replace("INSTALACION_NOMBRE", $proyecto->proyecto_clienteinstalacion, $texto);
            $texto = str_replace("INSTALACION_DIRECCION", $proyecto->proyecto_clientedireccionservicio, $texto);
            $texto = str_replace("INSTALACION_CODIGOPOSTAL", "C.P. " . $recsensorial->codigopostal, $texto);
            $texto = str_replace("INSTALACION_COORDENADAS", $recsensorial->coordenadas, $texto);
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
    
    
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function reportenom0353word(Request $request)
        {
            try {
                // dd($request->all());
    
                $phpWord = new PhpWord();
                $proyecto_id = $request->proyecto_id;
                $reporteregistro_id = $request->reporteregistro_id;
                $areas_poe = $request->areas_poe;
    
    
                //Zona horaria local
                date_default_timezone_set('America/Mexico_City');
                setlocale(LC_ALL, "es_MX");
    
                ############## DATOS GENERALES #########
                $agente_id = 353;
                $agente_nombre = "NOM035";
                $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
                $recsensorial = reconocimientopsicoModel::findOrFail($proyecto->reconocimiento_psico_id);
                $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
                $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);
    
    
                ############# INFORMACION DE LAS PORTADAS #########
                $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
                $agente = reportenom0353Model::where('proyecto_id', $proyecto_id)->get();
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
    
                ######## VALIDACION DEL INFORME ###############
                if ($reporteregistro_id > 0) {
                    $reporte  = reportenom0353Model::findOrFail($reporteregistro_id);
                    $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
                } else {
                    return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe llenar los datos para poder generarlo.</h3>';
                }
    
    
                // LEER PLANTILLA WORD
                //================================================================================
    
    
                if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
                {
                    $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_nom0353.docx')); //Ruta carpeta storage
                } else {
                    $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_nom0353.docx')); //Ruta carpeta storage
                }
    
                function sanitizeText($text) {
                
                    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
                }
                // PORTADA EXTERNA
                $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                    ->where('clientepartidas_tipo', 2) // Informe de resultados
                    ->where('catprueba_id', 1) // ruido
                    ->orderBy('updated_at', 'DESC')
                    ->get();
    
    
                if (count($titulo_partida) > 0) {
    
                    //Para el valor que lleva proyecto se utilizo: descripcion de la partida, Numero del contrato y la descripcion del contrato
                    $plantillaword->setValue('proyecto_portada', str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion) . ' - Contrato: ' . $clienteInfo[0]->NUMERO_CONTRATO);
    
                    $plantillaword->setValue(
                        'PARTIDA',
                        str_replace("\n", "<w:br/>", $titulo_partida[0]->clientepartidas_descripcion)
                    );
                } else {
    
                    $plantillaword->setValue(
                        'PARTIDA',
                        ""
                    );
                    $plantillaword->setValue('proyecto_portada', 'El proyecto no esta vinculado a ningun contrato.');
                }
    
                $plantillaword->setValue(
                    'folio_portada',
                    $proyecto->proyecto_folio
                );
                $plantillaword->setValue('razon_social_portada', $cliente->cliente_RazonSocial);
    
                // PARTE INTALACION PORTADA
                $OPCION_PORTADA1 = is_null($recursos[0]->OPCION_PORTADA1) ? "" : $recursos[0]->OPCION_PORTADA1 . " | ";
                $OPCION_PORTADA2 = is_null($recursos[0]->OPCION_PORTADA2) ? "" : $recursos[0]->OPCION_PORTADA2 . " | ";
                $OPCION_PORTADA3 = is_null($recursos[0]->OPCION_PORTADA3) ? "" : $recursos[0]->OPCION_PORTADA3 . " | ";
                $OPCION_PORTADA4 = is_null($recursos[0]->OPCION_PORTADA4) ? "" : $recursos[0]->OPCION_PORTADA4 . " | ";
                $OPCION_PORTADA5 = is_null($recursos[0]->OPCION_PORTADA5) ? "" : $recursos[0]->OPCION_PORTADA5 . " | ";
                $OPCION_PORTADA6 = is_null($recursos[0]->OPCION_PORTADA6) ? "" : $recursos[0]->OPCION_PORTADA6;
                $plantillaword->setValue('instalación_portada', $OPCION_PORTADA1 . $OPCION_PORTADA2 . $OPCION_PORTADA3 . $OPCION_PORTADA4 . $OPCION_PORTADA5 . $OPCION_PORTADA6);
    
                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reportenom0353_fecha;
                $plantillaword->setValue('lugar_fecha_portada', $recsensorial->direccion . ' ' . $fecha);
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
                $plantillaword->setValue('ESTRUCTURA', $NIVEL_PORTADA1 . $NIVEL_PORTADA2 . $NIVEL_PORTADA3 . $NIVEL_PORTADA4 . $NIVEL_PORTADA5 . $NIVEL_PORTADA6);
    
                if (
                    $proyecto->requiereContrato == 1
                ) {
    
                    $plantillaword->setValue('TITULO_CONTRATO', "Contrato:");
                    $plantillaword->setValue('CONTRATO', $clienteInfo[0]->NUMERO_CONTRATO);
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', $clienteInfo[0]->DESCRIPCION_CONTRATO);
    
                    $plantillaword->setValue(
                        'PIE_PAGINA',
                        $clienteInfo[0]->CONTRATO_PLANTILLA_PIEPAGINA
                    );
                    $plantillaword->setValue('INFORME_REVISION', "");
                } else {
    
                    $plantillaword->setValue(
                        'CONTRATO',
                        ""
                    );
                    $plantillaword->setValue('DESCRIPCION_CONTRATO', "");
                    $plantillaword->setValue('TITULO_CONTRATO', "");
    
                    $plantillaword->setValue(
                        'PIE_PAGINA',
                        ""
                    );
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
    
                $introduccionTexto = $agente[0]->reportenom0353_introduccion;
                $introduccionTextoModificado = $this->introduccion($proyecto, $introduccionTexto);
    
                // Asigna el texto modificado a la plantilla
                $plantillaword->setValue('INTRODUCCION', sanitizeText($introduccionTextoModificado));
    
                if (($revision->reporterevisiones_revision + 0) > 0) {
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre . ' Rev-' . $revision->reporterevisiones_revision);
                } else {
                    $plantillaword->setValue('INFORME_REVISION', $proyecto->proyecto_folio . ' - Informe de ' . $agente_nombre);
                }
    
    
    
                // DEFINICIONES
                //================================================================================
    
    
                $definiciones_simbolo = ["¹", "²", "³", "⁴", "⁵", "⁶", "⁷", "⁸", "⁹", "*", "●", "♦", "~", "°", "¨", "#"];
                $definiciones_fuentes ;
    
    
                $where_definiciones = '';
                if (($recsensorial->tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
                {
                    $where_definiciones = 'AND psicocat.ACTIVO = 1';
                }
    
    
                $sql = collect(DB::select('
                                                        SELECT
                                                            psicocat_definiciones.ID_DEFINICION_INFORME,
                                                            0 catactivo_id,
                                                            psicocat_definiciones.CONCEPTO AS concepto,
                                                            psicocat_definiciones.DESCRIPCION AS descripcion,
                                                            psicocat_definiciones.FUENTE AS fuente
                                                        FROM
                                                            psicocat_definiciones
                                                        WHERE
                                                            psicocat_definiciones.ACTIVO = 1
                                                        ORDER BY
                                                            psicocat_definiciones.CONCEPTO ASC'));
    
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
    
                $definiciones = '';
                foreach ($sql as $key => $value) {
                    foreach ($definiciones_fuentes as $key2 => $dato) {
                        if ($value->fuente == $dato['fuente_descripcion']) {
                            $conceptoSanitizado = sanitizeText($value->concepto); // Sanitize only concept text
                            $descripcionSanitizada = sanitizeText($value->descripcion); // Sanitize only description text
                            
                            // Construir el contenido de Word sin escapar las etiquetas de formato
                            if (($key + 0) < (count($sql) - 1)) {
                                $definiciones .= '<w:p>
                                                    <w:pPr>
                                                        <w:jc w:val="both"/>
                                                        <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="exactly" w:beforeAutospacing="0" w:afterAutospacing="0"/>
                                                    </w:pPr>
                                                    <w:rPr>
                                                        <w:b w:val="true"/>
                                                    </w:rPr>
                                                    <w:t>' . $conceptoSanitizado . '</w:t>
                                                    <w:rPr>
                                                        <w:b w:val="false"/>
                                                    </w:rPr>
                                                    <w:t>' . $descripcionSanitizada . '' . $dato['fuente_simbolo'] . '</w:t>
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
                                                    <w:t>' . $conceptoSanitizado . '</w:t>
                                                    <w:rPr>
                                                        <w:b w:val="false"/>
                                                    </w:rPr>
                                                    <w:t>' . $descripcionSanitizada . '' . $dato['fuente_simbolo'] . '</w:t>
                                                </w:p>';
                            }
                        }
                    }
                }
                
                // Insertar el contenido procesado en la plantilla
                $plantillaword->setValue('DEFINICIONES', $definiciones);
    
    
                // OBJETIVO GENERAL
                //================================================================================
    
                $objetivoGeneral = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportenom0353_objetivogeneral);
                $plantillaword->setValue('OBJETIVO_GENERAL', $objetivoGeneral);
                
                // OBJETIVOS ESPECIFICOS
                $objetivosEspecificos = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportenom0353_objetivoespecifico);
                $plantillaword->setValue('OBJETIVOS_ESPECIFICOS',$objetivosEspecificos);
                
                // METODOLOGIA
                $metodologia = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportenom0353_metodologiainstrumentos);
                $plantillaword->setValue('METODOLOGIA_4_1', $metodologia);
                
                // UBICACION
                $ubicacionTexto = $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportenom0353_ubicacioninstalacion);
                $plantillaword->setValue('UBICACION_TEXTO', $ubicacionTexto);
    
                // Imagen FOTO
                if ($reporte->reportenom0353_ubicacionfoto) {
                    if (file_exists(storage_path('app/' . $reporte->reportenom0353_ubicacionfoto))) {
                        $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporte->reportenom0353_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                // PROCESO INSTALACION
                //================================================================================
                $plantillaword->setValue('PROCESO_INSTALACION',   sanitizeText($reporte->reportenom0353_procesoinstalacion));
                // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", $reporte->reporteruido_procesoinstalacion)));
    
    
                // TABLA 5.3 Descripción de los procesos que generen ruido
                //================================================================================
    
    
               // if (($recsensorial->tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
                // {
                //     $fuente = 'Arial';
                //     $font_size = 10;
                // } else {
                //     $fuente = 'Arial';
                //     $font_size = 10;
                // }
    
    
                // $bgColor_encabezado = '#0C3F64'; //#1A5276
                // $encabezado_celda = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
                // $encabezado_texto = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
                // $combinar_fila_encabezado = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado);
                // $combinar_fila = array('vMerge' => 'restart', 'valign' => 'center');
                // $continua_fila = array('vMerge' => 'continue', 'valign' => 'center');
                // $celda = array('valign' => 'center');
                // $centrado = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
                // $izquierda = array('align' => 'left', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
                // $justificado = array('align' => 'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
                // $texto = array('color' => '000000', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
                // $textonegrita = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
                // $textototal = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
    
    
                // $areas_poe = 1;
    
    
                //if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                // {
                //     $sql = DB::select('SELECT
                //                             reportearea.proyecto_id,
                //                             reportearea.id,
                //                             reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                //                             reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                //                             reportearea.reportearea_orden AS reporteruidoarea_numorden,
                //                             reportearea.reportearea_porcientooperacion,
                //                             reportearea.reporteruidoarea_porcientooperacion,
                //                             reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                //                             reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                //                             reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                //                             reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                //                             reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                //                             reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                //                             reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                //                             reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                //                             reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                //                             reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                //                             reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                //                             reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                //                             reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10 
                //                         FROM
                //                             reportearea
                //                         WHERE
                //                             reportearea.proyecto_id = ' . $proyecto_id . ' 
                //                             AND reportearea.reporteruidoarea_porcientooperacion > 0
                //                         ORDER BY
                //                             reportearea.reportearea_orden ASC,
                //                             reportearea.reportearea_nombre ASC');
                // } else {
                //     $sql = DB::select('SELECT
                //                             reporteruidoarea.proyecto_id,
                //                             reporteruidoarea.registro_id,
                //                             reporteruidoarea.reporteruidoarea_instalacion,
                //                             reporteruidoarea.reporteruidoarea_nombre,
                //                             reporteruidoarea.reporteruidoarea_numorden,
                //                             reporteruidoarea.reporteruidoarea_proceso 
                //                         FROM
                //                             reporteruidoarea
                //                         WHERE
                //                             reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                //                             AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                //                             AND reporteruidoarea.reporteruidoarea_porcientooperacion > 0
                //                         ORDER BY
                //                             reporteruidoarea.reporteruidoarea_numorden ASC,
                //                             reporteruidoarea.reporteruidoarea_nombre ASC');
                // }
    
    
                // Crear tabla
                // $table = null;
                // $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
    
                // Columnas
                //$ancho_col_1 = 700;
                // $ancho_col_2 = 1500;
                //$ancho_col_3 = 3000;
                //$ancho_col_4 = 6300;
    
    
                
                // $numero_fila = 0;
                // $instalacion = 'XXXXX';
                // foreach ($sql as $key => $value) {
                //     if ($instalacion != $value->reporteruidoarea_instalacion) {
                        // encabezado tabla
                    //     $table->addRow(200, array('tblHeader' => true));
                    //     $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    //     $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
                    //     $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Proceso', $encabezado_texto);
    
    
                    //     $table->addRow(); //fila
                    //     $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $encabezado_texto); // combina columna
    
    
                    //     $instalacion = $value->reporteruidoarea_instalacion;
                    //     $numero_fila = 0;
                    // }
    
    
                    // $numero_fila += 1;
                    // $table->addRow(); //fila
                    // $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);
    
                    // if($instalacion != $value->reporteruidoarea_instalacion)
                    // {
                    //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $texto);
                    //     $instalacion = $value->reporteruidoarea_instalacion;
                    // }
                    // else
                    // {
                    //     $table->addCell($ancho_col_2, $continua_fila);
                    // }
    
                //     $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteruidoarea_nombre, $texto);
                //     $table->addCell($ancho_col_4, $celda)->addTextRun($justificado)->addText($value->reporteruidoarea_proceso, $texto);
                // }
    
    
                // $plantillaword->setComplexBlock('TABLA_5_3', $table);
    
    
                // TABLA 5.4 Población ocupacionalmente expuesta
                //================================================================================
    
    
                // $areas_poe = 1;
    
    
                // if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                // {
                //     $sql = DB::select('SELECT
                //                             reportearea.proyecto_id,
                //                             reportearea.id,
                //                             REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reporteruidoarea_instalacion,
                //                             reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                //                             reportearea.reportearea_orden AS reporteruidoarea_numorden,
                //                             reportearea.reportearea_porcientooperacion,
                //                             reportearea.reporteruidoarea_porcientooperacion,
                //                             IF( IFNULL( reportearea.reporteruidoarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteruidoarea_porcientooperacion, " %" ), NULL ) AS reporteruidoarea_porcientooperacion_texto,
                //                             reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                //                             reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                //                             reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                //                             reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                //                             reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                //                             reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                //                             reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                //                             reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                //                             reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                //                             reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                //                             reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                //                             reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                //                             reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10,
                //                             reporteareacategoria.reportecategoria_id AS reporteruidocategoria_id,
                //                             reportecategoria.reportecategoria_orden AS reporteruidocategoria_orden,
                //                             reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                //                             IFNULL((
                //                                 SELECT
                //                                     IF(reporteruidoareacategoria.reporteruidocategoria_id, "activo", "") AS checked
                //                                 FROM
                //                                     reporteruidoareacategoria
                //                                 WHERE
                //                                     reporteruidoareacategoria.reporteruidoarea_id = reportearea.id
                //                                     AND reporteruidoareacategoria.reporteruidocategoria_id = reporteareacategoria.reportecategoria_id
                //                                     AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
                //                                 LIMIT 1
                //                             ), "") AS activo,
                //                             reporteareacategoria.reporteareacategoria_total AS reporteruidocategoria_total,
                //                             reporteareacategoria.reporteareacategoria_geh AS reporteruidoareacategoria_geh,
                //                             reporteareacategoria.reporteareacategoria_actividades AS reporteruidoareacategoria_actividades,
                //                             (
                //                                 SELECT
                //                                     reporteruido.reporteruido_actividadprincipal 
                //                                 FROM
                //                                     reporteruido
                //                                 WHERE
                //                                     reporteruido.id = ' . $reporteregistro_id . ' 
                //                                 LIMIT 1
                //                             ) AS actividad_principal_instalacion 
                //                         FROM
                //                             reportearea
                //                             LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
                //                             LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                //                         WHERE
                //                             reportearea.proyecto_id = ' . $proyecto_id . ' 
                //                         ORDER BY
                //                             reportearea.reportearea_orden ASC,
                //                             reportearea.reportearea_nombre ASC,
                //                             reportecategoria.reportecategoria_orden ASC,
                //                             reportecategoria.reportecategoria_nombre ASC');
                // } else {
                //     $sql = DB::select('SELECT
                //                             reporteruidoarea.proyecto_id,
                //                             reporteruidoarea.registro_id,
                //                             reporteruidoareacategoria.reporteruidoareacategoria_poe,
                //                             reporteruidoareacategoria.id,
                //                             REPLACE(reporteruidoarea.reporteruidoarea_instalacion, "\"", "") AS reporteruidoarea_instalacion,
                //                             reporteruidoareacategoria.reporteruidoarea_id,
                //                             reporteruidoarea.reporteruidoarea_nombre,
                //                             reporteruidoarea.reporteruidoarea_numorden,
                //                             reporteruidoareacategoria.reporteruidocategoria_id,
                //                             reporteruidocategoria.reporteruidocategoria_nombre,
                //                             reporteruidocategoria.reporteruidocategoria_total,
                //                             (
                //                                 SELECT
                //                                     reporteruido.reporteruido_actividadprincipal 
                //                                 FROM
                //                                     reporteruido
                //                                 WHERE
                //                                     reporteruido.id = reporteruidoarea.registro_id
                //                                 LIMIT 1
                //                             ) AS actividad_principal_instalacion
                //                         FROM
                //                             reporteruidoareacategoria
                //                             LEFT JOIN reporteruidoarea ON reporteruidoareacategoria.reporteruidoarea_id = reporteruidoarea.id
                //                             LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id
                //                         WHERE
                //                             reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                //                             AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                //                             AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0
                //                         ORDER BY
                //                             reporteruidoarea.reporteruidoarea_numorden ASC,
                //                             reporteruidocategoria.reporteruidocategoria_nombre ASC');
                // }
    
    
                // //Columnas
                // $ancho_col_1 = 500;
                // // $ancho_col_2 = 1500;
                // $ancho_col_3 = 2000;
                // $ancho_col_4 = 3500;
                // $ancho_col_5 = 1000;
                // $ancho_col_6 = 2500;
    
    
                // // Crear tabla
                // $table = null;
                // $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

                // DESCRIPCION DEL METODO
                //================================================================================
    
    
                $plantillaword->setValue('DESCRIPCION_METODO',   $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reportenom0353_metodoevaluacion));

                // CONCLUSION
                //================================================================================
    
                $conclusionesJson = json_decode($reporte->reportenom0353_conclusiones, true);

                // Asignar valores desde el JSON a variables específicas en el Word
                if ($conclusionesJson) {
                    $plantillaword->setValue('CONCLUSION_1', sanitizeText($conclusionesJson['acontecimientos_traumaticos']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_2', sanitizeText($conclusionesJson['ambiente_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_3', sanitizeText($conclusionesJson['condiciones_ambiente']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_4', sanitizeText($conclusionesJson['factores_actividad']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_5', sanitizeText($conclusionesJson['carga_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_6', sanitizeText($conclusionesJson['falta_control']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_7', sanitizeText($conclusionesJson['organizacion_tiempo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_8', sanitizeText($conclusionesJson['jornada_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_9', sanitizeText($conclusionesJson['interferencia_trabajo_familia']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_10', sanitizeText($conclusionesJson['liderazgo_relaciones']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_11', sanitizeText($conclusionesJson['liderazgo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_12', sanitizeText($conclusionesJson['relaciones_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_13', sanitizeText($conclusionesJson['violencia']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_14', sanitizeText($conclusionesJson['entorno_organizacional']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_15', sanitizeText($conclusionesJson['reconocimiento_desempeno']) ?? 'N/A');
                    $plantillaword->setValue('CONCLUSION_16', sanitizeText($conclusionesJson['insuficiente_pertenencia']) ?? 'N/A');
                } else {
                    // En caso de que no exista JSON válido, asignar valores por defecto
                    for ($i = 1; $i <= 16; $i++) {
                        $plantillaword->setValue("CONCLUSION_$i", 'N/A');
                    }
                }

                // RECOMENDACIONES
                //================================================================================

                $recomendacionesJson = json_decode($reporte->reportenom0353_recomendaciones, true);

                // Asignar valores desde el JSON a variables específicas en el Word
                if ($recomendacionesJson) {
                    $plantillaword->setValue('RECOMENDACION_1', sanitizeText($recomendacionesJson['acontecimientos_traumaticos']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_2', sanitizeText($recomendacionesJson['ambiente_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_3', sanitizeText($recomendacionesJson['condiciones_ambiente']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_4', sanitizeText($recomendacionesJson['factores_actividad']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_5', sanitizeText($recomendacionesJson['carga_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_6', sanitizeText($recomendacionesJson['falta_control']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_7', sanitizeText($recomendacionesJson['organizacion_tiempo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_8', sanitizeText($recomendacionesJson['jornada_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_9', sanitizeText($recomendacionesJson['interferencia_trabajo_familia']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_10', sanitizeText($recomendacionesJson['liderazgo_relaciones']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_11', sanitizeText($recomendacionesJson['liderazgo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_12', sanitizeText($recomendacionesJson['relaciones_trabajo']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_13', sanitizeText($recomendacionesJson['violencia']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_14', sanitizeText($recomendacionesJson['entorno_organizacional']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_15', sanitizeText($recomendacionesJson['reconocimiento_desempeno']) ?? 'N/A');
                    $plantillaword->setValue('RECOMENDACION_16', sanitizeText($recomendacionesJson['insuficiente_pertenencia']) ?? 'N/A');
                } else {
                    // En caso de que no exista JSON válido, asignar valores por defecto
                    for ($i = 1; $i <= 16; $i++) {
                        $plantillaword->setValue("RECOMENDACION_$i", 'N/A');
                    }
                }


                if ($request->ambienteChart) {
                    $imagen_recibida = explode(',', $request->ambienteChart);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/ambientechart_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.png';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('GRAFICA_2', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 1000, 'height' => 700, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('GRAFICA_2', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('GRAFICA_2', 'NO SE ENVIÓ IMAGEN QUE MOSTRAR.');
                }

                if ($request->factoresChart) {
                    $imagen_recibida = explode(',', $request->factoresChart);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/factoresChart_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.png';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('GRAFICA_3', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 1000, 'height' => 800, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('GRAFICA_3', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('GRAFICA_3', 'NO SE ENVIÓ IMAGEN QUE MOSTRAR.');
                }

                if ($request->organizacionChart) {
                    $imagen_recibida = explode(',', $request->organizacionChart);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/organizacionChart_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.png';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('GRAFICA_4', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 1000, 'height' => 900, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('GRAFICA_4', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('GRAFICA_4', 'NO SE ENVIÓ IMAGEN QUE MOSTRAR.');
                }

                if ($request->liderazgoChart) {
                    $imagen_recibida = explode(',', $request->liderazgoChart);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/liderazgoChart_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.png';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('GRAFICA_5', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 1000, 'height' => 600, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('GRAFICA_5', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('GRAFICA_5', 'NO SE ENVIÓ IMAGEN QUE MOSTRAR.');
                }

                if ($request->entornoChart) {
                    $imagen_recibida = explode(',', $request->entornoChart);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/entornoChart_' . $proyecto->proyecto_folio . '.png';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('GRAFICA_6', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 1000, 'height' => 750, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('GRAFICA_6', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('GRAFICA_6', 'NO SE ENVIÓ IMAGEN QUE MOSTRAR.');
                }
    
    
                // DASHBOARD
                //================================================================================

                // if ($request->grafica_dashboard) {
                //     $imagen_recibida = explode(',', $request->grafica_dashboard);
                //     $imagen_base64 = base64_decode($imagen_recibida[1]);
                //     $imagen_temporal_ruta = 'reportes/informes/dashboard_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.jpg';
                //     Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                //     if (Storage::exists($imagen_temporal_ruta)) {
                //         $plantillaword->setImageValue('DASHBOARD_GRAFICA1', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 120, 'height' => 120, 'ratio' => true, 'borderColor' => '000000'));
                //         Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                //     } else {
                //         $plantillaword->setValue('DASHBOARD_GRAFICA1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                //     }
                // } else {
                //     $plantillaword->setValue('DASHBOARD_GRAFICA1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                // }
    
    
                //---------------------
    
    
                // RESPONSABLES
                //================================================================================
    
    
                // RESPONSABLE 1, FOTO DOCUMENTO
                if ($reporte->reportenom0353_responsable1documento) {
                    if (file_exists(storage_path('app/' . $reporte->reportenom0353_responsable1documento))) {
                        $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportenom0353_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                $plantillaword->setValue('REPONSABLE1', $reporte->reportenom0353_responsable1 . "<w:br/>" . $reporte->reportenom0353_responsable1cargo);
    
    
                // RESPONSABLE 2, FOTO DOCUMENTO
                if ($reporte->reportenom0353_responsable2doc) {
                    if (file_exists(storage_path('app/' . $reporte->reportenom0353_responsable2doc))) {
                        $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reportenom0353_responsable2doc), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                $plantillaword->setValue('REPONSABLE2', $reporte->reportenom0353_responsable2 . "<w:br/>" . $reporte->reportenom0353_responsable2cargo);
    
    
    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // CREAR WORD TEMPORAL
    
    
                // GUARDAR
                Storage::makeDirectory('reportes/informes'); //crear directorio
                $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_nom035_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL
    
                // sleep(1);
    
                // ABRIR NUEVA PLANTILLA
                $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_nom035_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL
    
    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // GUARDAR Y DESCARGAR INFORME FINAL
    
    
                // GUARDAR
                $informe_nombre = 'Informe de NOM035 - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';
                $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word
    
    
                // ELIMINAR TEMPORAL
                if (Storage::exists('reportes/informes/Informe_nom035_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                    Storage::delete('reportes/informes/Informe_nom035_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
                }
    
    
                /*
    
                //================================================================================
                // CREAR .ZIP
    
    
                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');
                
                // Zip File Name
                $zip_nombre = 'Informe - 1.1 Evaluación de Ruido en Puesto de Trabajo o Evaluación Personal (Dosimetrías) - Proyecto '.$proyecto->proyecto_folio.' + Anexos.zip';
                
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
                // $plantillaword->saveAs(storage_path('app/reportes/informes/'.$informe_nombre)); //GUARDAR Y CREAR archivo word TEMPORAL
                // return response()->download(storage_path('app/reportes/informes/'.$informe_nombre))->deleteFileAfterSend(true);
    
                */
    
    
                //--------------------------------------------------------------------------------
    
    
                if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
                {
                    //================================================================================
                    // CREAR .ZIP INFORME
    
                    // Define Dir Folder
                    $zip_ruta = storage_path('app/reportes/informes');
                    // Zip File Name
                    $zip_nombre = 'Informe de NOM035 - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
                    // Create ZipArchive Obj
                    $zip = new ZipArchive;
    
                    if ($zip->open($zip_ruta . '/' . $zip_nombre, ZipArchive::CREATE) === TRUE) {
                        // Add File in ZipArchive
                        $zip->addFile(storage_path('app/reportes/informes/' . $informe_nombre), $informe_nombre); //Word
    
    
                        // foreach ($anexos_lista as $key => $file) {
                        //     if (Storage::exists($file->archivo)) {
                        //         $extencion = explode(".", $file->archivo);
                        //         $zip->addFile(storage_path('app/' . $file->archivo), ($key + 1) . '.- ' . $file->nombre . '.' . $extencion[1]); 
                        //     }
                        // }
    
    
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
                    $zip_ruta_servidor = 'reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteregistro_id . '/revisiones/' . $request->ultimarevision_id;
                    Storage::makeDirectory($zip_ruta_servidor); //crear directorio
                    $zip_ruta_completa = storage_path('app/reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteregistro_id . '/revisiones/' . $request->ultimarevision_id);
                    // Zip File Name
                    $zip_nombre = 'Informe de NOM035 - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
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
    
    
                    // $headers = array('Content-Type' => 'application/octet-stream'); // Set Header
                    // $zip_rutacompleta = $zip_ruta_completa.'/'.$zip_nombre;
                    // if(file_exists($zip_rutacompleta))
                    // {
                    // return response()->download($zip_rutacompleta, $zip_nombre, $headers)->deleteFileAfterSend(true); // DESCARGAR ZIP
                    // }
    
    
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
                                                    AND reporterevisiones.agente_id = 353
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
    
       
    
        /**
         * Display the specified resource.
         *
         * @param  int $proyecto_id
         * @param  int $revision_id
         * @param  int $ultima_revision
         * @return \Illuminate\Http\Response
         */
        public function reportenom0353worddescargar($proyecto_id, $revision_id, $ultima_revision)
        {
            $agente_nombre = 'NOM0353';
    
    
            if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
            {
                $proyecto = proyectoModel::findOrFail($proyecto_id);
    
    
                $zip_nombre = 'Informe de NOM035 - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
    
    
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

