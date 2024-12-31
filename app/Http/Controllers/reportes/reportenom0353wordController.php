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
use App\modelos\reportes\reporteruidocatalogoModel;
use App\modelos\reportes\reporteruidoModel;

use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteruidocategoriaModel;
use App\modelos\reportes\reporteruidoareaModel;
use App\modelos\reportes\reporteruidoareacategoriaModel;
use App\modelos\reportes\reporteruidoequipoauditivoModel;
use App\modelos\reportes\reporteruidoequipoauditivoatenuacionModel;
use App\modelos\reportes\reporteruidoequipoauditivocategoriasModel;
use App\modelos\reportes\reporteruidoeppModel;
use App\modelos\reportes\reporteruidoareaevaluacionModel;
use App\modelos\reportes\reporteruidonivelsonoroModel;
use App\modelos\reportes\reporteruidopuntonerModel;
use App\modelos\reportes\reporteruidopuntonercategoriasModel;
use App\modelos\reportes\reporteruidopuntonerfrecuenciasModel;
use App\modelos\reportes\reporteruidodosisnerModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteplanoscarpetasModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
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
                $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
                $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
                $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);
    
    
                ############# INFORMACION DE LAS PORTADAS #########
                $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
                $agente = reporteruidoModel::where('proyecto_id', $proyecto_id)->get();
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
                    $reporte  = reporteruidoModel::findOrFail($reporteregistro_id);
                    $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
                } else {
                    return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe llenar los datos para poder generarlo.</h3>';
                }
    
    
                // LEER PLANTILLA WORD
                //================================================================================
    
    
                if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
                {
                    $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_ruido_sin_MEL.docx')); //Ruta carpeta storage
                } else {
                    $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_ruido_sin_MEL.docx')); //Ruta carpeta storage
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
    
                $fecha = $agente[0]->reporte_mes . ' del ' . $agente[0]->reporteruido_fecha;
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
    
                $introduccionTexto = $agente[0]->reporteruido_introduccion;
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
                                // $definiciones .= '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>'.$value->concepto.'</w:t></w:r><w:r><w:t>'.$value->descripcion.''.$dato['fuente_simbolo'].'<w:br/><w:br/>';
    
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
                                // $definiciones .= '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>'.$value->concepto.'</w:t></w:r><w:r><w:t>'.$value->descripcion.''.$dato['fuente_simbolo'].'<w:br/>';
    
    
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
    
    
                $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_objetivogeneral));
    
    
                // OBJETIVOS ESPECIFICOS
                //================================================================================
    
    
                $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_objetivoespecifico));
    
    
                // METODOLOGIA
                //================================================================================
    
    
                $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_metodologia_4_1));
    
    
                // UBICACION
                //================================================================================
    
    
                $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_ubicacioninstalacion));
    
    
                // Imagen FOTO
                if ($reporte->reporteruido_ubicacionfoto) {
                    if (file_exists(storage_path('app/' . $reporte->reporteruido_ubicacionfoto))) {
                        $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporte->reporteruido_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                // PROCESO INSTALACION
                //================================================================================
    
    
                $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_procesoinstalacion));
                // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n\n", "<w:br/><w:br/>", str_replace("\n\n", "<w:br/><w:br/>", $reporte->reporteruido_procesoinstalacion)));
    
    
                // TABLA 5.3 Descripción de los procesos que generen ruido
                //================================================================================
    
    
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
                {
                    $fuente = 'Arial';
                    $font_size = 10;
                } else {
                    $fuente = 'Arial';
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
    
    
                // $areas_poe = 1;
    
    
                if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                {
                    $sql = DB::select('SELECT
                                            reportearea.proyecto_id,
                                            reportearea.id,
                                            reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                            reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                            reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                            reportearea.reportearea_porcientooperacion,
                                            reportearea.reporteruidoarea_porcientooperacion,
                                            reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                                            reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                                            reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                                            reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                                            reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                                            reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                                            reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                                            reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                                            reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                                            reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                                            reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                                            reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                                            reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10 
                                        FROM
                                            reportearea
                                        WHERE
                                            reportearea.proyecto_id = ' . $proyecto_id . ' 
                                            AND reportearea.reporteruidoarea_porcientooperacion > 0
                                        ORDER BY
                                            reportearea.reportearea_orden ASC,
                                            reportearea.reportearea_nombre ASC');
                } else {
                    $sql = DB::select('SELECT
                                            reporteruidoarea.proyecto_id,
                                            reporteruidoarea.registro_id,
                                            reporteruidoarea.reporteruidoarea_instalacion,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidoarea.reporteruidoarea_numorden,
                                            reporteruidoarea.reporteruidoarea_proceso 
                                        FROM
                                            reporteruidoarea
                                        WHERE
                                            reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                                            AND reporteruidoarea.reporteruidoarea_porcientooperacion > 0
                                        ORDER BY
                                            reporteruidoarea.reporteruidoarea_numorden ASC,
                                            reporteruidoarea.reporteruidoarea_nombre ASC');
                }
    
    
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
    
                // Columnas
                $ancho_col_1 = 700;
                // $ancho_col_2 = 1500;
                $ancho_col_3 = 3000;
                $ancho_col_4 = 6300;
    
    
                // // encabezado tabla
                // $table->addRow(200, array('tblHeader' => true));
                // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
                // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Proceso', $encabezado_texto);
    
    
                $numero_fila = 0;
                $instalacion = 'XXXXX';
                foreach ($sql as $key => $value) {
                    if ($instalacion != $value->reporteruidoarea_instalacion) {
                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                        $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Áreas de trabajo', $encabezado_texto);
                        $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Proceso', $encabezado_texto);
    
    
                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $encabezado_texto); // combina columna
    
    
                        $instalacion = $value->reporteruidoarea_instalacion;
                        $numero_fila = 0;
                    }
    
    
                    $numero_fila += 1;
                    $table->addRow(); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);
    
                    // if($instalacion != $value->reporteruidoarea_instalacion)
                    // {
                    //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $texto);
                    //     $instalacion = $value->reporteruidoarea_instalacion;
                    // }
                    // else
                    // {
                    //     $table->addCell($ancho_col_2, $continua_fila);
                    // }
    
                    $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteruidoarea_nombre, $texto);
                    $table->addCell($ancho_col_4, $celda)->addTextRun($justificado)->addText($value->reporteruidoarea_proceso, $texto);
                }
    
    
                $plantillaword->setComplexBlock('TABLA_5_3', $table);
    
    
                // TABLA 5.4 Población ocupacionalmente expuesta
                //================================================================================
    
    
                // $areas_poe = 1;
    
    
                if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                {
                    $sql = DB::select('SELECT
                                            reportearea.proyecto_id,
                                            reportearea.id,
                                            REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reporteruidoarea_instalacion,
                                            reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                            reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                            reportearea.reportearea_porcientooperacion,
                                            reportearea.reporteruidoarea_porcientooperacion,
                                            IF( IFNULL( reportearea.reporteruidoarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteruidoarea_porcientooperacion, " %" ), NULL ) AS reporteruidoarea_porcientooperacion_texto,
                                            reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                                            reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                                            reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                                            reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                                            reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                                            reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                                            reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                                            reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                                            reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                                            reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                                            reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                                            reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                                            reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10,
                                            reporteareacategoria.reportecategoria_id AS reporteruidocategoria_id,
                                            reportecategoria.reportecategoria_orden AS reporteruidocategoria_orden,
                                            reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                            IFNULL((
                                                SELECT
                                                    IF(reporteruidoareacategoria.reporteruidocategoria_id, "activo", "") AS checked
                                                FROM
                                                    reporteruidoareacategoria
                                                WHERE
                                                    reporteruidoareacategoria.reporteruidoarea_id = reportearea.id
                                                    AND reporteruidoareacategoria.reporteruidocategoria_id = reporteareacategoria.reportecategoria_id
                                                    AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
                                                LIMIT 1
                                            ), "") AS activo,
                                            reporteareacategoria.reporteareacategoria_total AS reporteruidocategoria_total,
                                            reporteareacategoria.reporteareacategoria_geh AS reporteruidoareacategoria_geh,
                                            reporteareacategoria.reporteareacategoria_actividades AS reporteruidoareacategoria_actividades,
                                            (
                                                SELECT
                                                    reporteruido.reporteruido_actividadprincipal 
                                                FROM
                                                    reporteruido
                                                WHERE
                                                    reporteruido.id = ' . $reporteregistro_id . ' 
                                                LIMIT 1
                                            ) AS actividad_principal_instalacion 
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
                } else {
                    $sql = DB::select('SELECT
                                            reporteruidoarea.proyecto_id,
                                            reporteruidoarea.registro_id,
                                            reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                            reporteruidoareacategoria.id,
                                            REPLACE(reporteruidoarea.reporteruidoarea_instalacion, "\"", "") AS reporteruidoarea_instalacion,
                                            reporteruidoareacategoria.reporteruidoarea_id,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidoarea.reporteruidoarea_numorden,
                                            reporteruidoareacategoria.reporteruidocategoria_id,
                                            reporteruidocategoria.reporteruidocategoria_nombre,
                                            reporteruidocategoria.reporteruidocategoria_total,
                                            (
                                                SELECT
                                                    reporteruido.reporteruido_actividadprincipal 
                                                FROM
                                                    reporteruido
                                                WHERE
                                                    reporteruido.id = reporteruidoarea.registro_id
                                                LIMIT 1
                                            ) AS actividad_principal_instalacion
                                        FROM
                                            reporteruidoareacategoria
                                            LEFT JOIN reporteruidoarea ON reporteruidoareacategoria.reporteruidoarea_id = reporteruidoarea.id
                                            LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id
                                        WHERE
                                            reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0
                                        ORDER BY
                                            reporteruidoarea.reporteruidoarea_numorden ASC,
                                            reporteruidocategoria.reporteruidocategoria_nombre ASC');
                }
    
    
                //Columnas
                $ancho_col_1 = 500;
                // $ancho_col_2 = 1500;
                $ancho_col_3 = 2000;
                $ancho_col_4 = 3500;
                $ancho_col_5 = 1000;
                $ancho_col_6 = 2500;
    
    
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
    
                // // encabezado tabla
                // $table->addRow(200, array('tblHeader' => true));
                // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);
    
    
                $numero_fila = 0;
                $instalacion = 'XXXXX';
                $area = 'xxxx';
                foreach ($sql as $key => $value) {
                    if ($instalacion != $value->reporteruidoarea_instalacion) {
                        if (($key + 0) != 0) {
                            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                            {
                                $total = DB::select('SELECT
                                                        IFNULL(SUM( TABLA.reportecategoria_total ), 0) AS total 
                                                    FROM
                                                        (
                                                            SELECT
                                                                reportearea.proyecto_id,
                                                                reportearea.reportearea_instalacion,
                                                                reporteareacategoria.reportecategoria_id,
                                                                reportecategoria.reportecategoria_nombre,
                                                                reportecategoria.reportecategoria_total 
                                                            FROM
                                                                reporteareacategoria
                                                                LEFT JOIN reportearea ON reporteareacategoria.reportearea_id = reportearea.id
                                                                LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                            WHERE
                                                                reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                                AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                            GROUP BY
                                                                reportearea.proyecto_id,
                                                                reportearea.reportearea_instalacion,
                                                                reporteareacategoria.reportecategoria_id,
                                                                reportecategoria.reportecategoria_nombre,
                                                                reportecategoria.reportecategoria_total
                                                        ) AS TABLA');
                            } else {
                                $total = DB::select('SELECT
                                                            IFNULL(SUM( TABLA.reporteruidocategoria_total ), 0) AS total 
                                                        FROM
                                                            (
                                                                SELECT
                                                                    reporteruidocategoria.proyecto_id,
                                                                    reporteruidocategoria.registro_id,
                                                                    reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                                                    reporteruidoarea.reporteruidoarea_instalacion,
                                                                    reporteruidocategoria.reporteruidocategoria_nombre,
                                                                    reporteruidocategoria.reporteruidocategoria_total 
                                                                FROM
                                                                    reporteruidoareacategoria
                                                                    LEFT JOIN reporteruidoarea ON reporteruidoareacategoria.reporteruidoarea_id = reporteruidoarea.id
                                                                    LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id 
                                                                WHERE
                                                                    reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                                                    AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                                                                    AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0
                                                                    AND REPLACE(reporteruidoarea.reporteruidoarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                                GROUP BY
                                                                    reporteruidocategoria.proyecto_id,
                                                                    reporteruidocategoria.registro_id,
                                                                    reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                                                    reporteruidoarea.reporteruidoarea_instalacion,
                                                                    reporteruidocategoria.reporteruidocategoria_nombre,
                                                                    reporteruidocategoria.reporteruidocategoria_total 
                                                            ) AS TABLA');
                            }
    
    
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
                        $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de<w:br/>personal', $encabezado_texto);
                        $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);
    
                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $encabezado_texto); // combina columna
    
    
                        // $instalacion = $value->reporteruidoarea_instalacion;
                        $numero_fila = 0;
                    }
    
    
                    $table->addRow(); //fila
    
    
                    if ($area != $value->reporteruidoarea_nombre) {
                        $numero_fila += 1;
                        $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                    } else {
                        $table->addCell($ancho_col_1, $continua_fila);
                    }
    
                    // if($instalacion != $value->reporteruidoarea_instalacion)
                    // {
                    //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $texto);
                    //     $instalacion = $value->reporteruidoarea_instalacion;
                    // }
                    // else
                    // {
                    //     $table->addCell($ancho_col_2, $continua_fila);
                    // }
    
                    if ($area != $value->reporteruidoarea_nombre) {
                        $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_nombre, $texto);
                        $area = $value->reporteruidoarea_nombre;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila);
                    }
    
                    $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteruidocategoria_nombre, $texto);
                    $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteruidocategoria_total, $texto);
    
    
                    if ($instalacion != $value->reporteruidoarea_instalacion) {
                        $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->actividad_principal_instalacion), $texto);
    
                        $instalacion = $value->reporteruidoarea_instalacion;
                    } else {
                        $table->addCell($ancho_col_6, $continua_fila);
                    }
                }
    
    
                if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                {
                    $total = DB::select('SELECT
                                            IFNULL(SUM( TABLA.reportecategoria_total ), 0) AS total 
                                        FROM
                                            (
                                                SELECT
                                                    reportearea.proyecto_id,
                                                    reportearea.reportearea_instalacion,
                                                    reporteareacategoria.reportecategoria_id,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportecategoria.reportecategoria_total 
                                                FROM
                                                    reporteareacategoria
                                                    LEFT JOIN reportearea ON reporteareacategoria.reportearea_id = reportearea.id
                                                    LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id
                                                WHERE
                                                    reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                    AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                GROUP BY
                                                    reportearea.proyecto_id,
                                                    reportearea.reportearea_instalacion,
                                                    reporteareacategoria.reportecategoria_id,
                                                    reportecategoria.reportecategoria_nombre,
                                                    reportecategoria.reportecategoria_total
                                            ) AS TABLA');
                } else {
                    $total = DB::select('SELECT
                                                IFNULL(SUM( TABLA.reporteruidocategoria_total ), 0) AS total 
                                            FROM
                                                (
                                                    SELECT
                                                        reporteruidocategoria.proyecto_id,
                                                        reporteruidocategoria.registro_id,
                                                        reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                                        reporteruidoarea.reporteruidoarea_instalacion,
                                                        reporteruidocategoria.reporteruidocategoria_nombre,
                                                        reporteruidocategoria.reporteruidocategoria_total 
                                                    FROM
                                                        reporteruidoareacategoria
                                                        LEFT JOIN reporteruidoarea ON reporteruidoareacategoria.reporteruidoarea_id = reporteruidoarea.id
                                                        LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id 
                                                    WHERE
                                                        reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                                        AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                                                        AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0
                                                        AND REPLACE(reporteruidoarea.reporteruidoarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                    GROUP BY
                                                        reporteruidocategoria.proyecto_id,
                                                        reporteruidocategoria.registro_id,
                                                        reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                                        reporteruidoarea.reporteruidoarea_instalacion,
                                                        reporteruidocategoria.reporteruidocategoria_nombre,
                                                        reporteruidocategoria.reporteruidocategoria_total 
                                                ) AS TABLA');
                }
    
    
                $table->addRow(); //fila
                $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
                $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
                $table->addCell($ancho_col_5, $continua_fila);
    
                $table->addRow(); //fila
                $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);
    
    
                $plantillaword->setComplexBlock('TABLA_5_4', $table);
    
    
                // TABLA 5.5 Actividades del personal expuesto
                //================================================================================
    
    
                // $areas_poe = 1;
    
    
                if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                {
                    $sql = DB::select('SELECT
                                            TABLA.proyecto_id,
                                            TABLA.id,
                                            TABLA.reporteruidoarea_instalacion,
                                            TABLA.reporteruidoarea_nombre,
                                            TABLA.reporteruidoarea_numorden,
                                            TABLA.reporteruidoarea_porcientooperacion,
                                            TABLA.reporteruidoarea_porcientooperacion_texto,
                                            TABLA.reporteruidoarea_proceso,
                                            TABLA.reporteruidoarea_tiporuido,
                                            TABLA.reporteruidoarea_evaluacion,
                                            TABLA.reporteruidoarea_LNI_1,
                                            TABLA.reporteruidoarea_LNI_2,
                                            TABLA.reporteruidoarea_LNI_3,
                                            TABLA.reporteruidoarea_LNI_4,
                                            TABLA.reporteruidoarea_LNI_5,
                                            TABLA.reporteruidoarea_LNI_6,
                                            TABLA.reporteruidoarea_LNI_7,
                                            TABLA.reporteruidoarea_LNI_8,
                                            TABLA.reporteruidoarea_LNI_9,
                                            TABLA.reporteruidoarea_LNI_10,
                                            TABLA.reporteruidocategoria_id,
                                            TABLA.reporteruidocategoria_orden,
                                            TABLA.reporteruidocategoria_nombre,
                                            TABLA.activo,
                                            TABLA.reporteruidocategoria_total,
                                            TABLA.reporteruidoareacategoria_geh,
                                            TABLA.reporteruidoareacategoria_actividades,
                                            TABLA.actividad_principal_instalacion
                                        FROM
                                            (
                                                SELECT
                                                    reportearea.proyecto_id,
                                                    reportearea.id,
                                                    reportearea.reportearea_instalacion AS reporteruidoarea_instalacion,
                                                    reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                                    reportearea.reportearea_orden AS reporteruidoarea_numorden,
                                                    reportearea.reportearea_porcientooperacion,
                                                    reportearea.reporteruidoarea_porcientooperacion,
                                                    IF( IFNULL( reportearea.reporteruidoarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteruidoarea_porcientooperacion, " %" ), NULL ) AS reporteruidoarea_porcientooperacion_texto,
                                                    reportearea.reportearea_proceso AS reporteruidoarea_proceso,
                                                    reportearea.reportearea_tiporuido AS reporteruidoarea_tiporuido,
                                                    reportearea.reportearea_evaluacion AS reporteruidoarea_evaluacion,
                                                    reportearea.reportearea_LNI_1 AS reporteruidoarea_LNI_1,
                                                    reportearea.reportearea_LNI_2 AS reporteruidoarea_LNI_2,
                                                    reportearea.reportearea_LNI_3 AS reporteruidoarea_LNI_3,
                                                    reportearea.reportearea_LNI_4 AS reporteruidoarea_LNI_4,
                                                    reportearea.reportearea_LNI_5 AS reporteruidoarea_LNI_5,
                                                    reportearea.reportearea_LNI_6 AS reporteruidoarea_LNI_6,
                                                    reportearea.reportearea_LNI_7 AS reporteruidoarea_LNI_7,
                                                    reportearea.reportearea_LNI_8 AS reporteruidoarea_LNI_8,
                                                    reportearea.reportearea_LNI_9 AS reporteruidoarea_LNI_9,
                                                    reportearea.reportearea_LNI_10 AS reporteruidoarea_LNI_10,
                                                    reporteareacategoria.reportecategoria_id AS reporteruidocategoria_id,
                                                    reportecategoria.reportecategoria_orden AS reporteruidocategoria_orden,
                                                    reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                                                    IFNULL((
                                                        SELECT
                                                            IF(reporteruidoareacategoria.reporteruidocategoria_id, "activo", "") AS checked
                                                        FROM
                                                            reporteruidoareacategoria
                                                        WHERE
                                                            reporteruidoareacategoria.reporteruidoarea_id = reportearea.id
                                                            AND reporteruidoareacategoria.reporteruidocategoria_id = reporteareacategoria.reportecategoria_id
                                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = ' . $reporteregistro_id . ' 
                                                        LIMIT 1
                                                    ), "") AS activo,
                                                    reporteareacategoria.reporteareacategoria_total AS reporteruidocategoria_total,
                                                    reporteareacategoria.reporteareacategoria_geh AS reporteruidoareacategoria_geh,
                                                    reporteareacategoria.reporteareacategoria_actividades AS reporteruidoareacategoria_actividades,
                                                    (
                                                        SELECT
                                                            reporteruido.reporteruido_actividadprincipal 
                                                        FROM
                                                            reporteruido
                                                        WHERE
                                                            reporteruido.id = ' . $reporteregistro_id . ' 
                                                        LIMIT 1
                                                    ) AS actividad_principal_instalacion 
                                                FROM
                                                    reportearea
                                                    LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id
                                                    LEFT JOIN reportecategoria ON reporteareacategoria.reportecategoria_id = reportecategoria.id 
                                                WHERE
                                                    reportearea.proyecto_id = ' . $proyecto_id . ' 
                                                -- ORDER BY
                                                    -- reportearea.reportearea_orden ASC,
                                                    -- reportearea.reportearea_nombre ASC,
                                                    -- reportecategoria.reportecategoria_orden ASC,
                                                    -- reportecategoria.reportecategoria_nombre ASC
                                            ) AS TABLA
                                        WHERE
                                            TABLA.reporteruidoarea_porcientooperacion > 0
                                            AND TABLA.activo != ""
                                        ORDER BY
                                            TABLA.reporteruidoarea_numorden ASC,
                                            TABLA.reporteruidoarea_nombre ASC,
                                            TABLA.reporteruidocategoria_orden ASC,
                                            TABLA.reporteruidocategoria_nombre ASC');
                } else {
                    $sql = DB::select('SELECT
                                            reporteruidoarea.proyecto_id,
                                            reporteruidoarea.registro_id,
                                            reporteruidoareacategoria.reporteruidoareacategoria_poe,
                                            reporteruidoareacategoria.id,
                                            reporteruidoarea.reporteruidoarea_instalacion,
                                            reporteruidoareacategoria.reporteruidoarea_id,
                                            reporteruidoarea.reporteruidoarea_nombre,
                                            reporteruidoarea.reporteruidoarea_numorden,
                                            reporteruidoareacategoria.reporteruidocategoria_id,
                                            reporteruidocategoria.reporteruidocategoria_nombre,
                                            reporteruidoareacategoria.reporteruidoareacategoria_actividades
                                        FROM
                                            reporteruidoareacategoria
                                            LEFT JOIN reporteruidoarea ON reporteruidoareacategoria.reporteruidoarea_id = reporteruidoarea.id
                                            LEFT JOIN reporteruidocategoria ON reporteruidoareacategoria.reporteruidocategoria_id = reporteruidocategoria.id
                                        WHERE
                                            reporteruidoarea.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidoarea.registro_id = ' . $reporteregistro_id . ' 
                                            AND reporteruidoareacategoria.reporteruidoareacategoria_poe = 0 
                                            AND reporteruidoarea.reporteruidoarea_porcientooperacion > 0
                                        ORDER BY
                                            reporteruidoarea.reporteruidoarea_numorden ASC,
                                            reporteruidoarea.reporteruidoarea_instalacion ASC,
                                            reporteruidocategoria.reporteruidocategoria_nombre ASC');
                }
    
    
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
    
                // Columnas
                $ancho_col_1 = 500;
                // $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 3000;
                $ancho_col_5 = 4500;
    
    
                // // encabezado tabla
                // $table->addRow(200, array('tblHeader' => true));
                // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
    
                $numero_fila = 0;
                $instalacion = 'xxxx';
                $area = 'xxxx';
                $actividad = 'xxxx';
                foreach ($sql as $key => $value) {
                    if ($instalacion != $value->reporteruidoarea_instalacion) {
                        // encabezado tabla
                        $table->addRow(200, array('tblHeader' => true));
                        $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                        $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                        $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                        $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
    
                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $encabezado_texto); // combina columna
    
    
                        $instalacion = $value->reporteruidoarea_instalacion;
                        $numero_fila = 0;
                    }
    
    
                    $table->addRow(); //fila
    
    
                    if ($area != $value->reporteruidoarea_nombre) {
                        $numero_fila += 1;
                        $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila);
                    } else {
                        $table->addCell($ancho_col_1, $continua_fila);
                    }
    
    
                    // if($instalacion != $value->reporteruidoarea_instalacion)
                    // {
                    //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_instalacion, $texto);
                    //     $instalacion = $value->reporteruidoarea_instalacion;
                    // }
                    // else
                    // {
                    //     $table->addCell($ancho_col_2, $continua_fila);
                    // }
    
    
                    if ($area != $value->reporteruidoarea_nombre) {
                        $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoarea_nombre, $texto);
                        $area = $value->reporteruidoarea_nombre;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila);
                    }
    
    
                    $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteruidocategoria_nombre, $texto);
                    $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteruidoareacategoria_actividades, $texto);
                }
    
    
                $plantillaword->setComplexBlock('TABLA_5_5', $table);
    
    
                // TABLA 5.6 Equipo de Protección Personal Auditiva (EPPA)
                //================================================================================
    
    
                // $areas_poe = 1;
    
    
                if (($areas_poe + 0) == 1) {
                    $sql = DB::select('SELECT
                                            reporteruidoequipoauditivo.proyecto_id,
                                            reportecategoria.reportecategoria_orden,
                                            reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre, 
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR,
                                            reporteruidoequipoauditivocategorias.reporteruidocategoria_id
                                        FROM
                                            reporteruidoequipoauditivo
                                            LEFT JOIN reporteruidoequipoauditivocategorias ON reporteruidoequipoauditivo.id = reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id
                                            RIGHT JOIN reportecategoria ON reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reportecategoria.id
                                        WHERE
                                            reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                        ORDER BY
                                            reportecategoria.reportecategoria_orden ASC,
                                            reportecategoria.reportecategoria_nombre ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');
                } else {
                    $sql = DB::select('SELECT
                                            reporteruidoequipoauditivo.proyecto_id,
                                            reporteruidocategoria.reporteruidocategoria_nombre, 
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR,
                                            reporteruidoequipoauditivocategorias.reporteruidocategoria_id
                                        FROM
                                            reporteruidoequipoauditivo
                                            LEFT JOIN reporteruidoequipoauditivocategorias ON reporteruidoequipoauditivo.id = reporteruidoequipoauditivocategorias.reporteruidoequipoauditivo_id
                                            RIGHT JOIN reporteruidocategoria ON reporteruidoequipoauditivocategorias.reporteruidocategoria_id = reporteruidocategoria.id
                                        WHERE
                                            reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidocategoria.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidocategoria.reporteruidocategoria_nombre ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');
                }
    
    
                // Columnas
                $ancho_col_1 = 3500;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 1500;
    
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Tipo', $encabezado_texto);
                $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Marca', $encabezado_texto);
                $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Modelo', $encabezado_texto);
                $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('NRR', $encabezado_texto);
    
                $numero_fila = 0;
                $categoria = 'xxxx';
                $tipo = 'xxxx';
                $marca = 'xxxx';
                $modelo = 'xxxx';
                $nrr = 'xxxx';
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila
    
                    if ($categoria != $value->reporteruidocategoria_nombre) {
                        $numero_fila += 1;
                        $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidocategoria_nombre, $texto);
                        $categoria = $value->reporteruidocategoria_nombre;
                    } else {
                        $table->addCell($ancho_col_1, $continua_fila);
                    }
    
    
                    if ($tipo != $value->reporteruidoequipoauditivo_tipo) {
                        $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoequipoauditivo_tipo, $texto);
                        $tipo = $value->reporteruidoequipoauditivo_tipo;
                    } else {
                        $table->addCell($ancho_col_2, $continua_fila);
                    }
    
    
                    if ($marca != $value->reporteruidoequipoauditivo_marca) {
                        $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoequipoauditivo_marca, $texto);
                        $marca = $value->reporteruidoequipoauditivo_marca;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila);
                    }
    
    
                    if ($modelo != $value->reporteruidoequipoauditivo_modelo) {
                        $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoequipoauditivo_modelo, $texto);
                        $modelo = $value->reporteruidoequipoauditivo_modelo;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila);
                    }
    
    
                    if ($nrr != $value->reporteruidoequipoauditivo_NRR) {
                        $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($centrado)->addText($value->reporteruidoequipoauditivo_NRR, $texto);
                        $nrr = $value->reporteruidoequipoauditivo_NRR;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila);
                    }
                }
    
    
                $plantillaword->setComplexBlock('TABLA_5_6', $table);

    
    
                if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
                {
                    if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1) {
                        $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>Se anexa en formato digital.<w:br/>');
                    } else {
                        $plantillaword->setValue('MATRIZ_TEXTO', '');
                    }
                } else {
                    $plantillaword->setValue('MATRIZ_TEXTO', '');
                }
    
    
                $plantillaword->setComplexBlock('TABLA_7_7', $table);
    
    
                // CONCLUSION
                //================================================================================
    
    
                $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporte->reporteruido_conclusion));
    
    
                // DASHBOARD
                //================================================================================
    
    
                // $areas_poe = 0;
    
    
                if (($areas_poe + 0) == 1) {
                    //=====================================
                    // AREAS CRITICAS
    
    
                    $areas = DB::select('CALL sp_areas_criticas_ruido_b(?,?, ?)', [$proyecto_id, $reporteregistro_id, 1]);
    
    
                    $dashboard_areas = '';
                    if (count($areas) > 0) {
                        $plantillaword->setValue('DASHBOARD_TITULOAREAS', 'Áreas críticas evaluadas<w:br/>en medición ambiental');
    
                        foreach ($areas as $key => $value) {
                            $dashboard_areas .= '● ' . $value->reporteruidoarea_nombre . ' ' . $value->puntoscriticos . '.<w:br/>';
                        }
    
                        $plantillaword->setValue('DASHBOARD_AREAS', $dashboard_areas);
                    } else {
                        $plantillaword->setValue('DASHBOARD_TITULOAREAS', 'Áreas evaluadas<w:br/>en medición ambiental');
    
    
                        // AREAS EVALUADAS
                        $areas = DB::select('SELECT
                                                    reporteruidopuntoner.proyecto_id,
                                                    reporteruidopuntoner.registro_id,
                                                    reportearea.reportearea_nombre AS reporteruidoarea_nombre,
                                                    reportearea.reportearea_orden AS reporteruidoarea_numorden
                                                FROM
                                                    reporteruidopuntoner
                                                    LEFT JOIN reportearea ON reporteruidopuntoner.reporteruidoarea_id = reportearea.id
                                                WHERE
                                                    reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                                GROUP BY
                                                    reporteruidopuntoner.proyecto_id,
                                                    reporteruidopuntoner.registro_id,
                                                    reportearea.reportearea_nombre,
                                                    reportearea.reportearea_orden
                                                ORDER BY
                                                    reportearea.reportearea_orden ASC');
    
                        if (count($areas) > 0) {
                            foreach ($areas as $key => $value) {
                                $dashboard_areas .= '● ' . $value->reporteruidoarea_nombre . '<w:br/>';
                            }
    
                            $plantillaword->setValue('DASHBOARD_AREAS', $dashboard_areas);
                        } else {
                            $plantillaword->setValue('DASHBOARD_AREAS', 'No se encontraron áreas evaluadas.');
                        }
                    }
    
    
                    //=====================================
                    // CATEGORIAS CRITICAS
    
                    $categorias = DB::select('CALL sp_categorias_criticas_ruido_b(?,?,?)', [$proyecto_id, $reporteregistro_id, 1]);
    
                    // $categorias = DB::select('SELECT
                    //                                 TABLA.proyecto,
                    //                                 TABLA.registro,
                    //                                 TABLA.categoria,
                    //                                 TABLA.reporteruidocategoria_nombre,
                    //                                 TABLA.puntominimo,
                    //                                 TABLA.puntoscriticos
                    //                             FROM
                    //                                 (
                    //                                     SELECT
                    //                                         reporteruidodosisner.proyecto_id AS proyecto,
                    //                                         reporteruidodosisner.registro_id AS registro,
                    //                                         reporteruidodosisner.reporteruidocategoria_id AS categoria,
                    //                                         reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre,
                    //                                         MIN(reporteruidodosisner.reporteruidodosisner_punto) AS puntominimo,
                    //                                         (
                    //                                             SELECT
                    //                                                 REPLACE(GROUP_CONCAT(CONCAT("Punto ", reporteruidodosisner_punto, " (", reporteruidodosisner_ner, " dB)")), ",", ", ")
                    //                                             FROM
                    //                                                 reporteruidodosisner
                    //                                             WHERE
                    //                                                 reporteruidodosisner.proyecto_id = proyecto 
                    //                                                 AND reporteruidodosisner.registro_id = registro
                    //                                                 AND reporteruidodosisner.reporteruidocategoria_id = categoria
                    //                                                 AND reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe
                    //                                             ORDER BY
                    //                                                 reporteruidodosisner.reporteruidodosisner_punto ASC
                    //                                         ) AS puntoscriticos
                    //                                     FROM
                    //                                         reporteruidodosisner
                    //                                         LEFT JOIN reportecategoria ON reporteruidodosisner.reporteruidocategoria_id = reportecategoria.id 
                    //                                     WHERE
                    //                                         reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                    //                                         AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                    //                                         AND reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe
                    //                                     GROUP BY
                    //                                         reporteruidodosisner.proyecto_id,
                    //                                         reporteruidodosisner.registro_id,
                    //                                         reporteruidodosisner.reporteruidocategoria_id,
                    //                                         reportecategoria.reportecategoria_nombre
                    //                                 ) AS TABLA
                    //                             ORDER BY
                    //                                 TABLA.puntominimo ASC');
    
    
                    $dashboard_categorias = '';
                    if (count($categorias) > 0) {
                        $plantillaword->setValue('DASHBOARD_TITULOCATEGORIAS', 'Categorías críticas evaluadas<w:br/>en medición personal');
    
                        foreach ($categorias as $key => $value) {
                            $dashboard_categorias .= '● ' . $value->reporteruidocategoria_nombre . ' ' . $value->puntoscriticos . '.<w:br/>';
                        }
    
                        $plantillaword->setValue('DASHBOARD_CATEGORIAS', $dashboard_categorias);
                    } else {
                        $plantillaword->setValue('DASHBOARD_TITULOCATEGORIAS', 'Categorías evaluadas<w:br/>en medición personal');
    
    
                        // CATEGORIAS EVALUADAS
                        $categorias = DB::select('SELECT
                                                        reporteruidodosisner.proyecto_id,
                                                        reporteruidodosisner.registro_id,
                                                        reporteruidodosisner.reporteruidocategoria_id,
                                                        reportecategoria.reportecategoria_nombre AS reporteruidocategoria_nombre 
                                                    FROM
                                                        reporteruidodosisner
                                                        INNER JOIN reportecategoria ON reporteruidodosisner.reporteruidocategoria_id = reportecategoria.id
                                                    WHERE
                                                        reporteruidodosisner.proyecto_id = ' . $proyecto_id . '  
                                                        AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                                    GROUP BY
                                                        reporteruidodosisner.proyecto_id,
                                                        reporteruidodosisner.registro_id,
                                                        reporteruidodosisner.reporteruidocategoria_id,
                                                        reportecategoria.reportecategoria_nombre
                                                    ORDER BY
                                                        reportecategoria.reportecategoria_orden ASC,
                                                        reportecategoria.reportecategoria_nombre ASC');
    
    
                        if (count($categorias) > 0) {
                            foreach ($categorias as $key => $value) {
                                $dashboard_categorias .= '● ' . $value->reporteruidocategoria_nombre . '<w:br/>';
                            }
    
                            $plantillaword->setValue('DASHBOARD_CATEGORIAS', $dashboard_categorias);
                        } else {
                            $plantillaword->setValue('DASHBOARD_CATEGORIAS', 'No se encontraron categorías evaluadas.');
                        }
                    }
                } else {
                    //=====================================
                    // AREAS CRITICAS
    
    
                    $areas = DB::select('CALL sp_areas_criticas_ruido_b(?,?,?)', [$proyecto_id, $reporteregistro_id, 2]);
    
                    $dashboard_areas = '';
                    if (count($areas) > 0) {
                        $plantillaword->setValue('DASHBOARD_TITULOAREAS', 'Áreas críticas evaluadas<w:br/>en medición ambiental');
    
                        foreach ($areas as $key => $value) {
                            $dashboard_areas .= '● ' . $value->reporteruidoarea_nombre . ' ' . $value->puntoscriticos . '.<w:br/>';
                        }
    
                        $plantillaword->setValue('DASHBOARD_AREAS', $dashboard_areas);
                    } else {
                        $plantillaword->setValue('DASHBOARD_TITULOAREAS', 'Áreas evaluadas<w:br/>en medición ambiental');
    
    
                        // AREAS EVALUADAS
                        $areas = DB::select('SELECT
                                                    reporteruidopuntoner.proyecto_id,
                                                    reporteruidopuntoner.registro_id,
                                                    reporteruidoarea.reporteruidoarea_nombre,
                                                    reporteruidoarea.reporteruidoarea_numorden 
                                                FROM
                                                    reporteruidopuntoner
                                                    LEFT JOIN reporteruidoarea ON reporteruidopuntoner.reporteruidoarea_id = reporteruidoarea.id
                                                WHERE
                                                    reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                    AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                                GROUP BY
                                                    reporteruidopuntoner.proyecto_id,
                                                    reporteruidopuntoner.registro_id,
                                                    reporteruidoarea.reporteruidoarea_nombre,
                                                    reporteruidoarea.reporteruidoarea_numorden
                                                ORDER BY
                                                    reporteruidoarea.reporteruidoarea_numorden ASC');
    
    
                        if (count($areas) > 0) {
                            foreach ($areas as $key => $value) {
                                $dashboard_areas .= '● ' . $value->reporteruidoarea_nombre . '<w:br/>';
                            }
    
                            $plantillaword->setValue('DASHBOARD_AREAS', $dashboard_areas);
                        } else {
                            $plantillaword->setValue('DASHBOARD_AREAS', 'No se encontraron áreas evaluadas.');
                        }
                    }
    
    
                    //=====================================
                    // CATEGORIAS CRITICAS
    
                    $categorias = DB::select('CALL sp_categorias_criticas_ruido_b(?,?,?)', [$proyecto_id, $reporteregistro_id, 2]);
    
                    // $categorias = DB::select('SELECT
                    //                                 TABLA.proyecto,
                    //                                 TABLA.registro,
                    //                                 TABLA.categoria,
                    //                                 TABLA.reporteruidocategoria_nombre,
                    //                                 TABLA.puntominimo,
                    //                                 TABLA.puntoscriticos
                    //                             FROM
                    //                                 (
                    //                                     SELECT
                    //                                         reporteruidodosisner.proyecto_id AS proyecto,
                    //                                         reporteruidodosisner.registro_id AS registro,
                    //                                         reporteruidodosisner.reporteruidocategoria_id AS categoria,
                    //                                         reporteruidocategoria.reporteruidocategoria_nombre,
                    //                                         MIN(reporteruidodosisner.reporteruidodosisner_punto) AS puntominimo,
                    //                                         (
                    //                                             SELECT
                    //                                                 REPLACE(GROUP_CONCAT(CONCAT("Punto ", reporteruidodosisner_punto, " (", reporteruidodosisner_ner, " dB)")), ",", ", ")
                    //                                             FROM
                    //                                                 reporteruidodosisner
                    //                                             WHERE
                    //                                                 reporteruidodosisner.proyecto_id = proyecto 
                    //                                                 AND reporteruidodosisner.registro_id = registro
                    //                                                 AND reporteruidodosisner.reporteruidocategoria_id = categoria
                    //                                                 AND reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe
                    //                                             ORDER BY
                    //                                                 reporteruidodosisner.reporteruidodosisner_punto ASC
                    //                                         ) AS puntoscriticos
                    //                                     FROM
                    //                                         reporteruidodosisner
                    //                                         LEFT JOIN reporteruidocategoria ON reporteruidodosisner.reporteruidocategoria_id = reporteruidocategoria.id 
                    //                                     WHERE
                    //                                         reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                    //                                         AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                    //                                         AND reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe
                    //                                     GROUP BY
                    //                                         reporteruidodosisner.proyecto_id,
                    //                                         reporteruidodosisner.registro_id,
                    //                                         reporteruidodosisner.reporteruidocategoria_id,
                    //                                         reporteruidocategoria.reporteruidocategoria_nombre
                    //                                 ) AS TABLA
                    //                             ORDER BY
                    //                                 TABLA.puntominimo ASC');
    
    
                    $dashboard_categorias = '';
                    if (count($categorias) > 0) {
                        $plantillaword->setValue('DASHBOARD_TITULOCATEGORIAS', 'Categorías críticas evaluadas<w:br/>en medición personal');
    
                        foreach ($categorias as $key => $value) {
                            $dashboard_categorias .= '● ' . $value->reporteruidocategoria_nombre . ' ' . $value->puntoscriticos . '.<w:br/>';
                        }
    
                        $plantillaword->setValue('DASHBOARD_CATEGORIAS', $dashboard_categorias);
                    } else {
                        $plantillaword->setValue('DASHBOARD_TITULOCATEGORIAS', 'Categorías evaluadas<w:br/>en medición personal');
    
    
                        // CATEGORIAS EVALUADAS
                        $categorias = DB::select('SELECT
                                                        reporteruidodosisner.proyecto_id,
                                                        reporteruidodosisner.registro_id,
                                                        reporteruidodosisner.reporteruidocategoria_id,
                                                        reporteruidocategoria.reporteruidocategoria_nombre 
                                                    FROM
                                                        reporteruidodosisner
                                                        INNER JOIN reporteruidocategoria ON reporteruidodosisner.reporteruidocategoria_id = reporteruidocategoria.id
                                                    WHERE
                                                        reporteruidodosisner.proyecto_id = ' . $proyecto_id . '  
                                                        AND reporteruidodosisner.registro_id = ' . $reporteregistro_id . ' 
                                                    GROUP BY
                                                        reporteruidodosisner.proyecto_id,
                                                        reporteruidodosisner.registro_id,
                                                        reporteruidodosisner.reporteruidocategoria_id,
                                                        reporteruidocategoria.reporteruidocategoria_nombre
                                                    ORDER BY
                                                        reporteruidocategoria.reporteruidocategoria_nombre ASC');
    
    
                        if (count($categorias) > 0) {
                            foreach ($categorias as $key => $value) {
                                $dashboard_categorias .= '● ' . $value->reporteruidocategoria_nombre . '<w:br/>';
                            }
    
                            $plantillaword->setValue('DASHBOARD_CATEGORIAS', $dashboard_categorias);
                        } else {
                            $plantillaword->setValue('DASHBOARD_CATEGORIAS', 'No se encontraron categorías evaluadas.');
                        }
                    }
                }
    
    
                //---------------------
    
    
                // EQUIPOS AUDITIVOS
                $equipos = DB::select('SELECT
                                            reporteruidoequipoauditivo.proyecto_id,
                                            reporteruidoequipoauditivo.registro_id,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR 
                                        FROM
                                            reporteruidoequipoauditivo
                                        WHERE
                                            reporteruidoequipoauditivo.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteruidoequipoauditivo.registro_id = ' . $reporteregistro_id . ' 
                                        ORDER BY
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_tipo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_marca ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_modelo ASC,
                                            reporteruidoequipoauditivo.reporteruidoequipoauditivo_NRR ASC');
    
    
                $dashboard_equipos = '';
                if (count($equipos) > 0) {
                    foreach ($equipos as $key => $value) {
                        $dashboard_equipos .= '● ' . $value->reporteruidoequipoauditivo_tipo . ' Marca: ' . $value->reporteruidoequipoauditivo_marca . ', Modelo: ' . $value->reporteruidoequipoauditivo_modelo . ', NRR: ' . $value->reporteruidoequipoauditivo_NRR . ' dB.<w:br/>';
                    }
    
                    $plantillaword->setValue('DASHBOARD_EQUIPOS', $dashboard_equipos);
                } else {
                    $plantillaword->setValue('DASHBOARD_EQUIPOS', 'No se encontraron equipos auditivos.');
                }
    
    
                //---------------------
    
    
                // SONOMETRIAS RESULTADOS
                $sonometrias = DB::select('SELECT
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id,
                                                COUNT(reporteruidopuntoner.reporteruidopuntoner_punto) AS totalsonometrias,
                                             SUM(CASE
                                                    WHEN reporteruidopuntoner.reporteruidopuntoner_ner < 85 THEN 1
                                                    ELSE 0
                                                END) AS dentronorma,
                                            SUM(CASE
                                                    WHEN reporteruidopuntoner.reporteruidopuntoner_ner BETWEEN 85 AND 90 THEN 1
                                                    ELSE 0
                                                END) AS niveldeaccion,
                                            SUM(CASE
                                                    WHEN reporteruidopuntoner.reporteruidopuntoner_ner > 90 THEN 1
                                                    ELSE 0
                                                END) AS fueranorma
                                            FROM
                                                reporteruidopuntoner
                                            WHERE
                                                reporteruidopuntoner.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteruidopuntoner.registro_id = ' . $reporteregistro_id . ' 
                                            GROUP BY
                                                reporteruidopuntoner.proyecto_id,
                                                reporteruidopuntoner.registro_id');
    
    
                $dashboard_total_evaluacion = '';
                if (count($sonometrias) > 0) {
                    $dashboard_total_evaluacion = $sonometrias[0]->totalsonometrias . ' puntos<w:br/>Sonometría<w:br/><w:br/>';
                    $plantillaword->setValue('TOTAL_DENTRO', $sonometrias[0]->dentronorma);
                    $plantillaword->setValue('TOTAL_NIVEL', $sonometrias[0]->niveldeaccion);
                    $plantillaword->setValue('TOTAL_FUERA', $sonometrias[0]->fueranorma);
                } else {
                    $dashboard_total_evaluacion = '0 puntos<w:br/>Sonometría<w:br/><w:br/>';
                    $plantillaword->setValue('TOTAL_DENTRO', '0');
                    $plantillaword->setValue('TOTAL_NIVEL', '0');
                    $plantillaword->setValue('TOTAL_FUERA', '0');
                }
    
    
                //---------------------
    
    
                // DOSIMETRIAS RESULTADOS
                $dosimetria = DB::select('SELECT
                                                reporteruidodosisner.proyecto_id,
                                                reporteruidodosisner.registro_id,
                                                COUNT(reporteruidodosisner.reporteruidodosisner_punto) AS totaldosimetrias,
                                                SUM(IF(reporteruidodosisner.reporteruidodosisner_ner <= reporteruidodosisner.reporteruidodosisner_lmpe, 1, 0)) AS dentronorma,
                                                SUM(IF(reporteruidodosisner.reporteruidodosisner_ner > reporteruidodosisner.reporteruidodosisner_lmpe, 1, 0)) AS fueranorma
                                            FROM
                                                reporteruidodosisner
                                            WHERE
                                                reporteruidodosisner.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteruidodosisner.registro_id= ' . $reporteregistro_id . ' 
                                            GROUP BY
                                                reporteruidodosisner.proyecto_id,
                                                reporteruidodosisner.registro_id');
    
    
                if (count($dosimetria) > 0) {
                    $dashboard_total_evaluacion .= $dosimetria[0]->totaldosimetrias . ' puntos<w:br/>Dosimetría';
                } else {
                    $dashboard_total_evaluacion .= '0 puntos<w:br/>Dosimetría';
                }
    
    
                $plantillaword->setValue('DASHBOARD_TOTALEVALUACION', $dashboard_total_evaluacion);
    
    
                //---------------------
    
    
                // $grafica1_Path = 'reportes/proyecto/'.$reporte->proyecto_id.'/'.$reporte->agente_nombre.'/'.$reporte->id.'/graficas/grafica_1.jpg';
                // if (file_exists(storage_path('app/'.$grafica1_Path)))
                // {
                //     $plantillaword->setImageValue('DASHBOARD_GRAFICA1', array('path' => storage_path('app/'.$grafica1_Path), 'width' => 160, 'height' => 160, 'ratio' => true, 'borderColor' => '000000'));
                // }
                // else
                // {
                //     $plantillaword->setValue('DASHBOARD_GRAFICA1', 'NO HAY GRÁFICA QUE MOSTRAR.');
                // }
    
    
    
    
                if ($request->grafica_dashboard) {
                    $imagen_recibida = explode(',', $request->grafica_dashboard);
                    $imagen_base64 = base64_decode($imagen_recibida[1]);
                    $imagen_temporal_ruta = 'reportes/informes/dashboard_' . $agente_nombre . '_' . $proyecto->proyecto_folio . '.jpg';
                    Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage
    
    
                    if (Storage::exists($imagen_temporal_ruta)) {
                        $plantillaword->setImageValue('DASHBOARD_GRAFICA1', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 120, 'height' => 120, 'ratio' => true, 'borderColor' => '000000'));
                        Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                    } else {
                        $plantillaword->setValue('DASHBOARD_GRAFICA1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                    }
                } else {
                    $plantillaword->setValue('DASHBOARD_GRAFICA1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                }
    
    
                //---------------------
    
    
                // RECOMENDACIONES
                $recomendaciones = DB::select('SELECT
                                                    -- reporterecomendaciones.proyecto_id,
                                                    -- reporterecomendaciones.registro_id,
                                                    -- reporterecomendaciones.agente_id,
                                                    -- reporterecomendaciones.agente_nombre,
                                                    -- reporterecomendaciones.catactivo_id,
                                                    -- reporterecomendaciones.reporterecomendacionescatalogo_id,
                                                    -- reporterecomendaciones.reporterecomendaciones_tipo,
                                                    -- reporterecomendaciones.reporterecomendaciones_descripcion,
                                                    COUNT(reporterecomendaciones.id) AS totalrecomendaciones
                                                FROM
                                                    reporterecomendaciones 
                                                WHERE
                                                    reporterecomendaciones.proyecto_id = ' . $proyecto_id . '  
                                                    AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
                                                    AND reporterecomendaciones.agente_nombre = "' . $agente_nombre . '"');
    
    
                if (count($recomendaciones) > 0) {
                    $plantillaword->setValue('DASHBOARD_RECOM', $recomendaciones[0]->totalrecomendaciones);
                } else {
                    $plantillaword->setValue('DASHBOARD_RECOM', '0');
                }
    
    
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
                                                                                AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
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
                                                            AND reporterecomendaciones.registro_id = ' . $reporteregistro_id . ' 
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
                if ($reporte->reporteruido_responsable1documento) {
                    if (file_exists(storage_path('app/' . $reporte->reporteruido_responsable1documento))) {
                        $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reporteruido_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                $plantillaword->setValue('REPONSABLE1', $reporte->reporteruido_responsable1 . "<w:br/>" . $reporte->reporteruido_responsable1cargo);
    
    
                // RESPONSABLE 2, FOTO DOCUMENTO
                if ($reporte->reporteruido_responsable2documento) {
                    if (file_exists(storage_path('app/' . $reporte->reporteruido_responsable2documento))) {
                        $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporte->reporteruido_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                    } else {
                        $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                    }
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
    
    
                $plantillaword->setValue('REPONSABLE2', $reporte->reporteruido_responsable2 . "<w:br/>" . $reporte->reporteruido_responsable2cargo);
    
    
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
                $table->addCell(($ancho_col_1 + $ancho_col_2), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Evaluación de ruido', $encabezado_texto);
    
    
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
                    $table->addRow(1300); //fila
                    $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion1, $texto);
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion2, $texto);
    
                    if (($i + 2) < count($fotos)) {
                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($foto3, $texto);
                        $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($foto4, $texto);
                        $table->addRow(1300); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($descripcion3, $texto);
                        $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText($descripcion4, $texto);
                    }
                }
    
    
                $plantillaword->setComplexBlock('TABLA_MEMORIA_FOTOGRAFICA', $table);
    
    
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
                                                    reporteplanoscarpetas.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteplanoscarpetas.agente_nombre = "' . $agente_nombre . '" 
                                                    AND reporteplanoscarpetas.registro_id = ' . $reporteregistro_id);
    
    
                $planoscarpetasvariales = '';
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
                            $planoscarpetasvariales .= '${PLANO_' . $planocontador . '_FOTO}';
    
                            $plano_archivo[] = $plano->proyectoevidenciaplano_archivo;
    
                            $planocontador += 1;
                        }
                    }
                } else {
                    $plano_archivo = array();
                    $planoscarpetasvariales = 'NO HAY PLANOS QUE MOSTRAR.';
                }
    
    
                $plantillaword->setValue('PLANOS', $planoscarpetasvariales);
    
    
                // TABLA ANEXO 5, EQUIPO UTILIZADO PARA LA MEDICION
                //================================================================================
    
    
                // Crear tabla
                $table = null;
                $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
    
    
                $sql = DB::select('SELECT
                                        reporteequiposutilizados.proyecto_id,
                                        reporteequiposutilizados.registro_id,
                                        reporteequiposutilizados.id,
                                        reporteequiposutilizados.agente_id,
                                        reporteequiposutilizados.agente_nombre,
                                        reporteequiposutilizados.equipo_id,
                                        reporteequiposutilizados.reporteequiposutilizados_cartacalibracion,
                                        REPLACE(REPLACE(REPLACE(equipo.equipo_Descripcion, "<", "˂"), ">", "˃"), "&", "Ꞩ") AS equipo_Descripcion,
                                        equipo.equipo_Marca,
                                        equipo.equipo_Modelo,
                                        equipo.equipo_Serie,
                                        IFNULL(equipo.equipo_FechaCalibracion, "N/A") AS equipo_FechaCalibracion,
                                        IFNULL(equipo.equipo_VigenciaCalibracion, "N/A") AS equipo_VigenciaCalibracion,
                                        IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) AS vigencia_dias,
                                        IF(equipo.equipo_VigenciaCalibracion, CONCAT(equipo.equipo_VigenciaCalibracion, " (", (DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1)," d)"), "N/A") AS vigencia_texto,
                                        (
                                            CASE
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) = 0 THEN ""
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) >= 90 THEN ""
                                                WHEN IFNULL(DATEDIFF(equipo.equipo_VigenciaCalibracion, CURDATE()) + 1, 0) >= 30 THEN "text-warning"
                                                ELSE "text-danger"
                                            END
                                        ) AS vigencia_color
                                    FROM
                                        reporteequiposutilizados
                                        LEFT JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                    WHERE
                                        reporteequiposutilizados.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . ' 
                                        AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                    ORDER BY
                                        equipo.equipo_Descripcion ASC,
                                        equipo.equipo_Marca ASC,
                                        equipo.equipo_Modelo ASC,
                                        equipo.equipo_Serie ASC');
    
    
                // encabezado tabla
                $table->addRow(200, array('tblHeader' => true));
                $ancho_col_1 = 1800;
                $ancho_col_2 = 1800;
                $ancho_col_3 = 1800;
                $ancho_col_4 = 1800;
                $ancho_col_5 = 1800;
                $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('Equipo', $encabezado_texto);
                $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Marca', $encabezado_texto);
                $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Modelo', $encabezado_texto);
                $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('No. de serie', $encabezado_texto);
                $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Vigencia de<w:br/>calibración', $encabezado_texto);
    
    
                $numero_fila = 0;
                $total_cartas = 0;
                foreach ($sql as $key => $value) {
                    $table->addRow(); //fila
    
                    if ($value->reporteequiposutilizados_cartacalibracion) {
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText(htmlspecialchars('* ' . $value->equipo_Descripcion), $texto);
                        $total_cartas += 1;
                    } else {
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText(htmlspecialchars($value->equipo_Descripcion), $texto);
                    }
    
                    $table->addCell($ancho_col_2, $celda)->addTextRun($centrado)->addText(htmlspecialchars($value->equipo_Marca), $texto);
                    $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText(htmlspecialchars($value->equipo_Modelo), $texto);
                    $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText(htmlspecialchars($value->equipo_Serie), $texto);
                    $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText(htmlspecialchars($value->equipo_VigenciaCalibracion), $texto);
                }
    
    
                $plantillaword->setComplexBlock('EQUIPO_UTILIZADO', $table);
    
    
                if ($total_cartas > 0) {
                    $plantillaword->setValue('EQUIPO_UTILIZADO_NOTA', '<w:br/>
                                                                        <w:rPr>
                                                                            <w:b w:val="true"/>
                                                                            <w:t xml:space="preserve">Nota *: </w:t>
                                                                        </w:rPr>
                                                                        <w:rPr>
                                                                            <w:b w:val="false"/>
                                                                            <w:t xml:space="preserve">La calibración tiene una extensión en el tiempo de vigencia avalada mediante una carta emitida por el laboratorio acreditado misma que se encuentra disponible para consulta en el anexo 5.</w:t>
                                                                        </w:rPr>');
                } else {
                    $plantillaword->setValue('EQUIPO_UTILIZADO_NOTA', '');
                }
    
    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // CREAR WORD TEMPORAL
    
    
                // GUARDAR
                Storage::makeDirectory('reportes/informes'); //crear directorio
                $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_ruido_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL
    
                // sleep(1);
    
                // ABRIR NUEVA PLANTILLA
                $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_ruido_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL
    
    
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
                        $plantillaword->setValue('PLANO_' . $i . '_FOTO', 'NO SE ENCONTRÓ LA FOTO');
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
                                                        CONCAT("Certificado equipo ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                                        equipos_documentos.RUTA_DOCUMENTO AS archivo
                                                    FROM
                                                        reporteequiposutilizados
                                                    INNER JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                                    INNER JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
                                                    WHERE
                                                        reporteequiposutilizados.proyecto_id = ' . $proyecto_id . '
                                                        AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . '
                                                        AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                        AND equipos_documentos.DOCUMENTO_TIPO = 4
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        CONCAT("Carta vigencia - ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                                        equipos_documentos.RUTA_DOCUMENTO AS archivo
                                                    FROM
                                                        reporteequiposutilizados
                                                    INNER JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                                    INNER JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
                                                    WHERE
                                                        reporteequiposutilizados.proyecto_id = ' . $proyecto_id . '
                                                        AND reporteequiposutilizados.registro_id = ' . $reporteregistro_id . '
                                                        AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                        #AND reporteequiposutilizados.reporteequiposutilizados_cartacalibracion = 1
                                                        AND equipos_documentos.DOCUMENTO_TIPO = 5
                                                )
                                                UNION ALL
                                                (
                                                    SELECT
                                                        reporteanexos.reporteanexos_anexonombre AS nombre,
                                                        reporteanexos.reporteanexos_rutaanexo AS archivo 
                                                    FROM
                                                        reporteanexos
                                                    WHERE
                                                        reporteanexos.proyecto_id = ' . $proyecto_id . '
                                                        AND reporteanexos.registro_id = ' . $reporteregistro_id . '
                                                        AND reporteanexos.agente_nombre = "' . $agente_nombre . '"
                                                )
                                            ) AS ANEXO');
    
    
    
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // GUARDAR Y DESCARGAR INFORME FINAL
    
    
                // GUARDAR
                $informe_nombre = 'Informe de ruido - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';
                $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word
    
    
                // ELIMINAR TEMPORAL
                if (Storage::exists('reportes/informes/Informe_ruido_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                    Storage::delete('reportes/informes/Informe_ruido_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
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
                    $zip_nombre = 'Informe de ruido - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
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
                    $zip_ruta_servidor = 'reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteregistro_id . '/revisiones/' . $request->ultimarevision_id;
                    Storage::makeDirectory($zip_ruta_servidor); //crear directorio
                    $zip_ruta_completa = storage_path('app/reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteregistro_id . '/revisiones/' . $request->ultimarevision_id);
                    // Zip File Name
                    $zip_nombre = 'Informe de ruido - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
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
                                                    AND reporterevisiones.agente_id = 1 -- Ruido
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
            $agente_nombre = 'Ruido';
    
    
            if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
            {
                $proyecto = proyectoModel::findOrFail($proyecto_id);
    
    
                $zip_nombre = 'Informe de ruido - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
    
    
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

