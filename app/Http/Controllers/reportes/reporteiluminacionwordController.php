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
use App\modelos\reportes\reporteiluminacioncatalogoModel;
use App\modelos\reportes\reporteiluminacionModel;

use App\modelos\reportes\reportedefinicionesModel;
use App\modelos\reportes\reporteiluminacionpuntosModel;
use App\modelos\reportes\reporterecomendacionesModel;
use App\modelos\reportes\reporteequiposutilizadosModel;
use App\modelos\reportes\reporteiluminacioncategoriaModel;
use App\modelos\reportes\reporteiluminacionareaModel;
use App\modelos\reportes\reporteiluminacionareacategoriaModel;
use App\modelos\reportes\reporteanexosModel;
use App\modelos\clientes\clientepartidasModel;
use App\modelos\clientes\clientecontratoModel;
use App\modelos\reportes\recursosPortadasInformesModel;



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
    public function reporteiluminacionword(Request $request)
    {
        try {
            // dd($request->all());


            $proyecto_id = $request->proyecto_id;
            $reporteiluminacion_id = $request->reporteiluminacion_id;
            $areas_poe = $request->areas_poe;


            //Zona horaria local
            date_default_timezone_set('America/Mexico_City');
            setlocale(LC_ALL, "es_MX");

            ################ DATOS GENERALES ######################
            $agente_id = 4;
            $agente_nombre = "Iluminación";
            $proyecto = proyectoModel::with(['catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto_id);
            $recsensorial = recsensorialModel::with(['cliente', 'catregion', 'catsubdireccion', 'catgerencia', 'catactivo'])->findOrFail($proyecto->recsensorial_id);
            $cliente = clienteModel::findOrFail($recsensorial->cliente_id);
            $contrato = clientecontratoModel::findOrFail($proyecto->contrato_id);

            ############# INFORMACION DE LAS PORTADAS #########
            $recursos = recursosPortadasInformesModel::where('PROYECTO_ID', $proyecto_id)->where('AGENTE_ID', $agente_id)->get();
            $agente = reporteiluminacionModel::where('proyecto_id', $proyecto_id)->get();
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

            if ($reporteiluminacion_id > 0) {
                $reporteiluminacion  = reporteiluminacionModel::findOrFail($reporteiluminacion_id);
                $revision = reporterevisionesModel::findOrFail($request->ultimarevision_id);
            } else {
                return '<h3>Aun no se ha guardado nada para este informe de ' . $agente_nombre . ', primero debe guardar los datos para poder descargarlo.</h3>';
            }


            // LEER PLANTILLA WORD
            //================================================================================


            if (($recsensorial->cliente_id + 0) != 2) // cliente_id [2 = senegas]
            {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_iluminacion_sin_MEL.docx')); //Ruta carpeta storage
            } else {
                $plantillaword = new TemplateProcessor(storage_path('app/plantillas_reportes/proyecto_infomes/Plantilla_informe_iluminacion_sin_MEL.docx')); //Ruta carpeta storage
            }


            ################ PORTADA EXTERNA ####################
            $titulo_partida = clientepartidasModel::where('CONTRATO_ID', $recsensorial->contrato_id)
                ->where('clientepartidas_tipo', 2) // Informe de resultados
                ->where('catprueba_id', 4) // Iluminacion
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


            $fecha = $agente[0]->reporteiluminacion_mes . ' del ' . $agente[0]->reporteiluminacion_fecha;
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

            $introduccionTexto = $agente[0]->reporteiluminacion_introduccion;
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


            $plantillaword->setValue('OBJETIVO_GENERAL', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_objetivogeneral));


            // OBJETIVOS ESPECIFICOS
            //================================================================================


            $plantillaword->setValue('OBJETIVOS_ESPECIFICOS', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_objetivoespecifico));


            // METODOLOGIA
            //================================================================================


            $plantillaword->setValue('METODOLOGIA_4_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_1));
            $plantillaword->setValue('METODOLOGIA_4_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_2));
            $plantillaword->setValue('METODOLOGIA_4_2_1', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_2_1));
            $plantillaword->setValue('METODOLOGIA_4_2_2', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_2_2));
            $plantillaword->setValue('METODOLOGIA_4_2_3', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_2_3));
            $plantillaword->setValue('METODOLOGIA_4_2_4', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_metodologia_4_2_4));


            // UBICACION
            //================================================================================


            $plantillaword->setValue('UBICACION_TEXTO', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_ubicacioninstalacion));

            // Imagen FOTO
            if ($reporteiluminacion->reporteiluminacion_ubicacionfoto) {
                if (file_exists(storage_path('app/' . $reporteiluminacion->reporteiluminacion_ubicacionfoto))) {
                    $plantillaword->setImageValue('UBICACION_FOTO', array('path' => storage_path('app/' . $reporteiluminacion->reporteiluminacion_ubicacionfoto), 'width' => 580, 'height' => 400, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('UBICACION_FOTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            // PROCESO INSTALACION
            //================================================================================


            $plantillaword->setValue('PROCESO_INSTALACION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_procesoinstalacion));
            // $plantillaword->setValue('PROCESO_INSTALACION', str_replace("\n", "<w:br/>", str_replace("\n\n", "<w:br/><w:br/>", str_replace(['\\', '/', ':', '*', '&', '?', '<', '>', '|'], '-', $reporteiluminacion->reporteiluminacion_procesoinstalacion))));


            // TABLA 5.3 Población ocupacionalmente expuesta
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


            /*

            $ancho_col_1 = 500;
            $ancho_col_2 = 1500;
            $ancho_col_3 = 1500;
            $ancho_col_4 = 2000;
            $ancho_col_5 = 1000;
            $ancho_col_6 = 3000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => 'Arial', 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));
            
            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
            $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad<w:br/>principal de la instalación', $encabezado_texto);

            $numero_fila = 0;
            $instalacion = 'xxxx'; $area = 'xxxx'; $area2 = 'xxxx';
            foreach ($sql as $key => $value) 
            {
                $table->addRow(); //fila

                if($area != $value->reporteiluminacionarea_nombre)
                {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                    $area = $value->reporteiluminacionarea_nombre;
                }
                else
                {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                if($instalacion != $value->reporteiluminacionarea_instalacion)
                {
                    $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }
                else
                {
                    $table->addCell($ancho_col_2, $continua_fila);
                }


                if($area2 != $value->reporteiluminacionarea_nombre)
                {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);
                    $area2 = $value->reporteiluminacionarea_nombre;
                }
                else
                {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteiluminacioncategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionareacategoria_total, $texto);


                if ($key == 0)
                {
                    $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->actividad_principal_instalacion), $texto);
                }
                else
                {
                    $table->addCell($ancho_col_6, $continua_fila);
                }
            }

            $sql = DB::select('SELECT
                                    IFNULL(SUM(reporteiluminacioncategoria_total), 0) AS total
                                FROM
                                 (
                                    SELECT
                                        --  reporteiluminacioncategoria.proyecto_id,
                                        --  reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                        --  reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_total 
                                    FROM
                                        reporteiluminacionareacategoria
                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                    WHERE
                                        reporteiluminacioncategoria.proyecto_id = '.$proyecto_id.' 
                                        AND reporteiluminacioncategoria.registro_id = '.$reporteiluminacion_id.'
                                    GROUP BY
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_total
                                 ) AS TABLA');


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($sql[0]->total, $textonegrita);
            $table->addCell($ancho_col_5, $continua_fila);

            */


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportearea_instalacion,
                                        TABLA.reportearea_nombre,
                                        TABLA.reportearea_orden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportearea_puntos_ic,
                                        TABLA.reportearea_puntos_pt,
                                        TABLA.reportearea_sistemailuminacion,
                                        TABLA.reportearea_luznatural,
                                        TABLA.reportearea_iluminacionlocalizada,
                                        TABLA.reportearea_colorsuperficie,
                                        TABLA.reportearea_tiposuperficie,
                                        TABLA.reportearea_largo,
                                        TABLA.reportearea_ancho,
                                        TABLA.reportearea_alto,
                                        TABLA.reportecategoria_id,
                                        TABLA.reportecategoria_orden,
                                        TABLA.reportecategoria_nombre,
                                        TABLA.checked,
                                        TABLA.reportecategoria_horas,
                                        TABLA.reporteareacategoria_total,
                                        TABLA.reporteareacategoria_geh,
                                        TABLA.reporteareacategoria_actividades,
                                        TABLA.reporteareacategoria_tareavisual,
                                        IFNULL((
                                            SELECT
                                                reporteiluminacion.reporteiluminacion_actividadprincipal 
                                            FROM
                                                reporteiluminacion
                                            WHERE
                                                reporteiluminacion.proyecto_id = TABLA.proyecto_id
                                                AND reporteiluminacion.id = ' . $reporteiluminacion_id . ' 
                                            LIMIT 1
                                        ), "") AS actividad_principal_instalacion
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                REPLACE(reportearea.reportearea_instalacion, "\"", "") AS reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                -- reportearea.reportearea_porcientooperacion,
                                                reportearea.reporteiluminacionarea_porcientooperacion AS reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reporteiluminacionarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteiluminacionarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportearea_puntos_ic,
                                                reportearea.reportearea_puntos_pt,
                                                reportearea.reportearea_sistemailuminacion,
                                                reportearea.reportearea_luznatural,
                                                reportearea.reportearea_iluminacionlocalizada,
                                                reportearea.reportearea_colorsuperficie,
                                                reportearea.reportearea_tiposuperficie,
                                                reportearea.reportearea_largo,
                                                reportearea.reportearea_ancho,
                                                reportearea.reportearea_alto,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reportecategoria.reportecategoria_horas,
                                                reporteareacategoria.reporteareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades,
                                                -- reporteareacategoria.reporteareacategoria_tareavisual,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS reporteareacategoria_tareavisual
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
                                    -- WHERE
                                        -- TABLA.checked != ""
                                    ORDER BY
                                        TABLA.reportearea_orden ASC,
                                        TABLA.reportearea_nombre ASC,
                                        TABLA.reportecategoria_orden ASC,
                                        TABLA.reportecategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.registro_id,
                                        REPLACE(reporteiluminacionarea.reporteiluminacionarea_instalacion, "\"", "") AS reportearea_instalacion,
                                        reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre AS reportearea_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre AS reportecategoria_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_total AS reporteareacategoria_total,
                                        IFNULL((
                                            SELECT
                                                reporteiluminacion.reporteiluminacion_actividadprincipal 
                                            FROM
                                                reporteiluminacion
                                            WHERE
                                                reporteiluminacion.proyecto_id = reporteiluminacionarea.proyecto_id
                                                AND reporteiluminacion.id = reporteiluminacionarea.registro_id
                                            LIMIT 1
                                        ), "") AS actividad_principal_instalacion
                                    FROM
                                        reporteiluminacionareacategoria
                                        LEFT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id
                                        LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . '
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . '
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');
            }


            $ancho_col_1 = 500;
            $ancho_col_2 = 1200;
            $ancho_col_3 = 3500;
            $ancho_col_4 = 1300;
            $ancho_col_5 = 3000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reportearea_instalacion) {
                    if (($key + 0) != 0) {
                        if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
                        {
                            $total = DB::select('SELECT
                                                    IFNULL(SUM(TABLA.reportecategoria_total), 0) AS total
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
                                                            AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                        GROUP BY
                                                            reporteareacategoria.reportecategoria_id,
                                                            reportecategoria.reportecategoria_nombre,
                                                            reportecategoria.reportecategoria_total
                                                    ) AS TABLA');
                        } else {
                            $total = DB::select('SELECT
                                                    IFNULL(SUM( TABLA.reporteiluminacioncategoria_total ), 0) AS total 
                                                FROM
                                                    (
                                                        SELECT
                                                            reporteiluminacioncategoria.proyecto_id,
                                                            reporteiluminacioncategoria.registro_id,
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                            reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                            reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                            reporteiluminacioncategoria.reporteiluminacioncategoria_total 
                                                        FROM
                                                            reporteiluminacionareacategoria
                                                            LEFT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id
                                                            LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id 
                                                        WHERE
                                                            reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                                            AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                                            AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0 
                                                            AND REPLACE(reporteiluminacionarea.reporteiluminacionarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                                        GROUP BY
                                                            reporteiluminacioncategoria.proyecto_id,
                                                            reporteiluminacioncategoria.registro_id,
                                                            reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                            reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                            reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                            reporteiluminacioncategoria.reporteiluminacioncategoria_total
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
                    $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Cantidad de personal', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de la actividad principal de la instalación', $encabezado_texto);

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
                    $table->addCell($ancho_col_5, $combinar_fila)->addTextRun($justificado)->addText($this->datosproyectoreemplazartexto($proyecto, $recsensorial, $value->actividad_principal_instalacion), $texto);

                    $instalacion = $value->reportearea_instalacion;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila);
                }
            }


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $total = DB::select('SELECT
                                        IFNULL(SUM(TABLA.reportecategoria_total), 0) AS total
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
                                                AND REPLACE(reportearea.reportearea_instalacion, "\"", "") = "' . $instalacion . '" 
                                            GROUP BY
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_nombre,
                                                reportecategoria.reportecategoria_total
                                        ) AS TABLA');
            } else {
                $total = DB::select('SELECT
                                        IFNULL(SUM( TABLA.reporteiluminacioncategoria_total ), 0) AS total 
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacioncategoria.proyecto_id,
                                                reporteiluminacioncategoria.registro_id,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_total 
                                            FROM
                                                reporteiluminacionareacategoria
                                                LEFT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id
                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id 
                                            WHERE
                                                reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                                AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0 
                                                AND REPLACE(reporteiluminacionarea.reporteiluminacionarea_instalacion, "\"", "") = "' . $instalacion . '" 
                                            GROUP BY
                                                reporteiluminacioncategoria.proyecto_id,
                                                reporteiluminacioncategoria.registro_id,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_total
                                        ) AS TABLA');
            }


            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de personal', $textototal); // combina columna
            $table->addCell(null, $celda)->addTextRun($centrado)->addText($total[0]->total, $textonegrita);
            $table->addCell($ancho_col_5, $continua_fila);

            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('Nota: Las categorías repetidas en más de un área son consideradas como puesto móvil de trabajo.', $texto);


            $plantillaword->setComplexBlock('TABLA_5_3', $table);


            // TABLA 5.4 Actividades del personal expuesto
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                        TABLA.reportearea_nombre AS reporteiluminacionarea_nombre,
                                        TABLA.reportearea_orden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportearea_puntos_ic AS reporteiluminacionarea_puntos_ic,
                                        TABLA.indicearea,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 25
                                                WHEN (TABLA.indicearea >= 2) THEN 16
                                                WHEN (TABLA.indicearea >= 1) THEN 9
                                                WHEN (TABLA.indicearea > 0 ) THEN 4
                                                ELSE "N/A"
                                            END
                                        ) AS zonasminimas,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 30
                                                WHEN (TABLA.indicearea >= 2) THEN 20
                                                WHEN (TABLA.indicearea >= 1) THEN 12
                                                WHEN (TABLA.indicearea > 0 ) THEN 6
                                                ELSE "N/A"
                                            END
                                        ) AS zonasmaximas,
                                        TABLA.reportearea_puntos_pt AS reporteiluminacionarea_puntos_pt,
                                        TABLA.reportearea_sistemailuminacion,
                                        TABLA.reportearea_luznatural AS reporteiluminacionarea_luznatural,
                                        TABLA.reportearea_iluminacionlocalizada AS reporteiluminacionarea_iluminacionlocalizada,
                                        TABLA.reportearea_colorsuperficie,
                                        TABLA.reportearea_tiposuperficie,
                                        TABLA.reportearea_largo,
                                        TABLA.reportearea_ancho,
                                        TABLA.reportearea_alto,
                                        TABLA.reportecategoria_id,
                                        TABLA.reportecategoria_orden,

                                        TABLA.reportearea_tipoiluminacion AS reporteiluminacionarea_tipoiluminacion,
                                        TABLA.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                        TABLA.checked,
                                        TABLA.reportecategoria_horas AS reporteiluminacioncategoria_horas,
                                        TABLA.reporteareacategoria_total,
                                        TABLA.reporteareacategoria_geh,
                                        TABLA.reporteareacategoria_actividades AS reporteiluminacionareacategoria_actividades,
                                        TABLA.reporteareacategoria_tareavisual AS reporteiluminacionareacategoria_tareavisual
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                -- reportearea.reportearea_porcientooperacion,
                                                reportearea.reporteiluminacionarea_porcientooperacion AS reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reporteiluminacionarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteiluminacionarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportearea_puntos_ic,
                                                IFNULL((
                                                    ROUND(((reportearea_largo*reportearea_ancho) / (reportearea_alto * (reportearea_largo + reportearea_ancho))), 1)
                                                ), "N/A") AS indicearea, 
                                                reportearea.reportearea_puntos_pt,
                                                reportearea.reportearea_sistemailuminacion,
                                                reportearea.reportearea_luznatural,
                                                reportearea.reportearea_iluminacionlocalizada,
                                                reportearea.reportearea_colorsuperficie,
                                                reportearea.reportearea_tiposuperficie,
                                                reportearea.reportearea_largo,
                                                reportearea.reportearea_ancho,
                                                reportearea.reportearea_alto,
                                                reportearea.reportearea_tipoiluminacion,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reportecategoria.reportecategoria_horas,
                                                reporteareacategoria.reporteareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades,
                                                -- reporteareacategoria.reporteareacategoria_tareavisual,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS reporteareacategoria_tareavisual
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
                                        TABLA.checked != ""
                                        AND TABLA.reportearea_porcientooperacion > 0
                                    ORDER BY
                                        TABLA.reportearea_orden ASC,
                                        TABLA.reportearea_nombre ASC,
                                        TABLA.reportecategoria_orden ASC,
                                        TABLA.reportecategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                        reporteiluminacionareacategoria.reporteiluminacionarea_id,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacioncategoria_id,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades,
                                        reporteiluminacionarea.reporteiluminacionarea_luznatural,
                                        reporteiluminacionarea.reporteiluminacionarea_iluminacionlocalizada,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_horas 
                                        reporteiluminacioncategoria.reporteiluminacionarea_tipoiluminacion
                                    FROM
                                        reporteiluminacionareacategoria
                                        RIGHT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id
                                        RIGHT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id 
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                        AND reporteiluminacionarea.reporteiluminacionarea_porcientooperacion > 0 
                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 2000;
            $ancho_col_4 = 3000;
            $ancho_col_5 = 4500;
            $ancho_col_6 = 1000;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de las actividades<w:br/>que desarrolla', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Influencia de luz natural', $encabezado_texto);
            // $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Iluminación localizada', $encabezado_texto);
            // $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de jornada', $encabezado_texto);

            $numero_fila = 0;
            $instalacion = 'xxxx';
            $area = 'xxxx';
            $area2 = 'xxxx';
            $area3 = 'xxxx';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteiluminacionarea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Descripción de las actividades<w:br/>que desarrolla', $encabezado_texto);
                    // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Influencia de luz natural', $encabezado_texto);
                    $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de iluminación', $encabezado_texto);
                    $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('Tiempo de jornada', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $encabezado_texto); // combina columna

                    $numero_fila = 0;
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $numero_fila += 1;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($numero_fila, $texto);
                    $area = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteiluminacionarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                //     $instalacion = $value->reporteiluminacionarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area2 != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);
                    $area2 = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }

                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteiluminacioncategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteiluminacionareacategoria_actividades, $texto);

                if ($area3 != $value->reporteiluminacionarea_nombre) {
                    // $table->addCell($ancho_col_6, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_luznatural, $texto);
                    $table->addCell($ancho_col_7, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_tipoiluminacion, $texto);
                    $area3 = $value->reporteiluminacionarea_nombre;
                } else {
                    // $table->addCell($ancho_col_6, $continua_fila);
                    $table->addCell($ancho_col_7, $continua_fila);
                }

                $table->addCell($ancho_col_8, $celda)->addTextRun($centrado)->addText($value->reporteiluminacioncategoria_horas, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_4', $table);


            // TABLA 5.5 Descripción del área iluminada y su sistema de iluminación
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id, 
                                        reportearea.id, 
                                        reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion, 
                                        reportearea.reportearea_nombre AS reporteiluminacionarea_nombre, 
                                        reportearea.reportearea_orden, 
                                        -- reportearea.reportearea_porcientooperacion, 
                                        reportearea.reporteiluminacionarea_porcientooperacion AS reportearea_porcientooperacion,
                                        reportearea.reportearea_puntos_ic, 
                                        reportearea.reportearea_puntos_pt, 
                                        reportearea.reportearea_sistemailuminacion AS reporteiluminacionarea_sistemailuminacion, 
                                        reportearea.reportearea_luznatural AS reporteiluminacionarea_luznatural, 
                                        reportearea.reportearea_iluminacionlocalizada, 
                                        reportearea.reportearea_colorsuperficie AS reporteiluminacionarea_colorsuperficie, 
                                        reportearea.reportearea_tiposuperficie AS reporteiluminacionarea_tiposuperficie, 
                                         reportearea.reportearea_colorpiso AS reporteiluminacionarea_colorpiso   , 
                                        reportearea.reportearea_superficiepiso  AS  reporteiluminacionarea_superficiepiso, 
                                        reportearea.reportearea_tipoiluminacion AS reporteiluminacionarea_tipoiluminacion,
                                        reportearea.reportearea_largo, 
                                        reportearea.reportearea_ancho, 
                                        reportearea.reportearea_alto
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportearea.reporteiluminacionarea_porcientooperacion > 0
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                        reporteiluminacionarea.reporteiluminacionarea_colorsuperficie,
                                        reporteiluminacionarea.reporteiluminacionarea_tiposuperficie,
                                        reporteiluminacionarea.reporteiluminacionarea_luznatural,
                                        reporteiluminacionarea.reporteiluminacionarea_sistemailuminacion, 
                                        reporteiluminacionarea.reporteiluminacionarea_colorpiso,
                                        reporteiluminacionarea.reporteiluminacionarea_superficiepiso,
                                        reporteiluminacionarea.reporteiluminacionarea_tipoiluminacion
                                    FROM
                                        reporteiluminacionarea
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                        AND reporteiluminacionarea.reporteiluminacionarea_porcientooperacion > 0 
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 2000;
            $ancho_col_3 = 3000;
            $ancho_col_4 = 2000;
            $ancho_col_5 = 2000;
            $ancho_col_6 = 1000;
            $ancho_col_7 = 4500;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // Encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
            // $table->addCell(4000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Descripción del área iluminada', $encabezado_texto);
            // $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Influencia de luz natural', $encabezado_texto);
            // $table->addCell($ancho_col_7, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Sistema de iluminación', $encabezado_texto);
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $continua_fila);
            // $table->addCell($ancho_col_2, $continua_fila);
            // $table->addCell($ancho_col_3, $continua_fila);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Color de superficie', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de superficie', $encabezado_texto);
            // $table->addCell($ancho_col_6, $continua_fila);
            // $table->addCell($ancho_col_7, $continua_fila);

            $numero_fila = 0;
            $instalacion = 'XXXXX';
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteiluminacionarea_instalacion) {
                    // Encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    // $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('instalación', $encabezado_texto);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Áreas', $encabezado_texto);
                    $table->addCell(4000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Descripción del área iluminada', $encabezado_texto);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Tipo de iluminación', $encabezado_texto);
                    $table->addCell($ancho_col_7, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Sistema de iluminación', $encabezado_texto);
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila);
                    // $table->addCell($ancho_col_2, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Color de superficie', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Tipo de superficie', $encabezado_texto);
                    $table->addCell($ancho_col_6, $continua_fila);
                    $table->addCell($ancho_col_7, $continua_fila);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $encabezado_texto); // combina columna

                    $numero_fila = 0;
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }


                $numero_fila += 1;

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);

                // if($instalacion != $value->reporteiluminacionarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                //     $instalacion = $value->reporteiluminacionarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }



                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);
                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionarea_colorpiso, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionarea_superficiepiso, $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionarea_tipoiluminacion, $texto);
                $table->addCell($ancho_col_7, $celda)->addTextRun($justificado)->addText($value->reporteiluminacionarea_sistemailuminacion, $texto);
            }


            $plantillaword->setComplexBlock('TABLA_5_5', $table);


            // TABLA 6.1 Condiciones de operación durante la evaluación (representado en porcentaje)
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        reportearea.proyecto_id, 
                                        reportearea.id, 
                                        reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion, 
                                        reportearea.reportearea_nombre AS reporteiluminacionarea_nombre, 
                                        reportearea.reportearea_orden, 
                                        reportearea.reporteiluminacionarea_porcientooperacion,
                                        reportearea.reportearea_puntos_ic, 
                                        reportearea.reportearea_puntos_pt, 
                                        reportearea.reportearea_sistemailuminacion AS reporteiluminacionarea_sistemailuminacion, 
                                        reportearea.reportearea_luznatural AS reporteiluminacionarea_luznatural, 
                                        reportearea.reportearea_iluminacionlocalizada, 
                                        reportearea.reportearea_colorsuperficie AS reporteiluminacionarea_colorsuperficie, 
                                        reportearea.reportearea_tiposuperficie AS reporteiluminacionarea_tiposuperficie, 
                                        reportearea.reportearea_largo, 
                                        reportearea.reportearea_ancho, 
                                        reportearea.reportearea_alto
                                    FROM
                                        reportearea
                                    WHERE
                                        reportearea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reportearea.reporteiluminacionarea_porcientooperacion > 0
                                    ORDER BY
                                        reportearea.reportearea_orden ASC,
                                        reportearea.reportearea_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                        reporteiluminacionarea.reporteiluminacionarea_porcientooperacion 
                                    FROM
                                        reporteiluminacionarea 
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                        AND reporteiluminacionarea.reporteiluminacionarea_porcientooperacion > 0 
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 500;
            // $ancho_col_2 = 3000;
            $ancho_col_3 = 6000;
            $ancho_col_4 = 3000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);

            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $total = 0;
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteiluminacionarea_instalacion) {
                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No.', $encabezado_texto);
                    // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área de trabajo', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Porcentaje de operación', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $encabezado_texto); // combina columna

                    $numero_fila = 0;
                    $total = 0;
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }

                $numero_fila += 1;

                $table->addRow(); //fila
                $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($numero_fila, $texto);


                // if($instalacion != $value->reporteiluminacionarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                //     $instalacion = $value->reporteiluminacionarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                $table->addCell($ancho_col_3, $celda)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);


                if ($total != $value->reporteiluminacionarea_porcientooperacion) {
                    $table->addCell($ancho_col_4, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_porcientooperacion . '%', $texto);
                    $total = $value->reporteiluminacionarea_porcientooperacion;
                } else {
                    $table->addCell($ancho_col_4, $continua_fila);
                }
            }


            $plantillaword->setComplexBlock('TABLA_6_1', $table);


            // CRITERIO DE SELECCION
            //================================================================================


            $plantillaword->setValue('CRITERIO_SELECCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_criterioseleccion));


            // TABLA 6.2.1 Índice de área
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                        TABLA.reportearea_nombre AS reporteiluminacionarea_nombre,
                                        TABLA.reportearea_orden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportearea_puntos_ic AS reporteiluminacionarea_puntos_ic,
                                        TABLA.indicearea,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 25
                                                WHEN (TABLA.indicearea >= 2) THEN 16
                                                WHEN (TABLA.indicearea >= 1) THEN 9
                                                WHEN (TABLA.indicearea > 0 ) THEN 4
                                                ELSE "N/A"
                                            END
                                        ) AS zonasminimas,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 30
                                                WHEN (TABLA.indicearea >= 2) THEN 20
                                                WHEN (TABLA.indicearea >= 1) THEN 12
                                                WHEN (TABLA.indicearea > 0 ) THEN 6
                                                ELSE "N/A"
                                            END
                                        ) AS zonasmaximas,
                                        TABLA.reportearea_puntos_pt AS reporteiluminacionarea_puntos_pt,
                                        TABLA.reportearea_sistemailuminacion,
                                        TABLA.reportearea_luznatural AS reporteiluminacionarea_luznatural,
                                        TABLA.reportearea_iluminacionlocalizada AS reporteiluminacionarea_iluminacionlocalizada,
                                        TABLA.reportearea_colorsuperficie,
                                        TABLA.reportearea_tiposuperficie,
                                        TABLA.reportearea_largo,
                                        TABLA.reportearea_ancho,
                                        TABLA.reportearea_alto,
                                        TABLA.reportecategoria_id,
                                        TABLA.reportecategoria_orden,
                                        TABLA.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                        TABLA.checked,
                                        TABLA.reportecategoria_horas AS reporteiluminacioncategoria_horas,
                                        TABLA.reporteareacategoria_total,
                                        TABLA.reporteareacategoria_geh,
                                        TABLA.reporteareacategoria_actividades AS reporteiluminacionareacategoria_actividades,
                                        TABLA.reporteareacategoria_tareavisual AS reporteiluminacionareacategoria_tareavisual
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                -- reportearea.reportearea_porcientooperacion,
                                                reportearea.reporteiluminacionarea_porcientooperacion AS reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reporteiluminacionarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteiluminacionarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportearea_puntos_ic,
                                                IFNULL((
                                                    ROUND(((reportearea_largo*reportearea_ancho) / (reportearea_alto * (reportearea_largo + reportearea_ancho))), 1)
                                                ), "N/A") AS indicearea, 
                                                reportearea.reportearea_puntos_pt,
                                                reportearea.reportearea_sistemailuminacion,
                                                reportearea.reportearea_luznatural,
                                                reportearea.reportearea_iluminacionlocalizada,
                                                reportearea.reportearea_colorsuperficie,
                                                reportearea.reportearea_tiposuperficie,
                                                reportearea.reportearea_largo,
                                                reportearea.reportearea_ancho,
                                                reportearea.reportearea_alto,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reportecategoria.reportecategoria_horas,
                                                reporteareacategoria.reporteareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades,
                                                -- reporteareacategoria.reporteareacategoria_tareavisual,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS reporteareacategoria_tareavisual
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
                                        TABLA.checked != ""
                                        AND TABLA.reportearea_porcientooperacion > 0
                                        AND TABLA.reportearea_puntos_ic > 0
                                    ORDER BY
                                        TABLA.reportearea_orden ASC,
                                        TABLA.reportearea_nombre ASC,
                                        TABLA.reportecategoria_orden ASC,
                                        TABLA.reportecategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.reporteiluminacionarea_puntos_ic,
                                        TABLA.reporteiluminacionarea_nombre,
                                        TABLA.reporteiluminacionarea_instalacion,
                                        TABLA.reporteiluminacioncategoria_nombre,
                                        TABLA.reporteiluminacionareacategoria_actividades,
                                        TABLA.indicearea,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 25
                                                WHEN (TABLA.indicearea >= 2) THEN 16
                                                WHEN (TABLA.indicearea >= 1) THEN 9
                                                WHEN (TABLA.indicearea > 0 ) THEN 4
                                                ELSE "N/A"
                                            END
                                        ) AS zonasminimas,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 30
                                                WHEN (TABLA.indicearea >= 2) THEN 20
                                                WHEN (TABLA.indicearea >= 1) THEN 12
                                                WHEN (TABLA.indicearea > 0 ) THEN 6
                                                ELSE "N/A"
                                            END
                                        ) AS zonasmaximas
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionarea.proyecto_id,
                                                reporteiluminacionarea.reporteiluminacionarea_puntos_ic,
                                                reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades,
                                                IFNULL((
                                                    ROUND(((reporteiluminacionarea_largo*reporteiluminacionarea_ancho) / (reporteiluminacionarea_alto * (reporteiluminacionarea_largo + reporteiluminacionarea_ancho))), 1)
                                                ), "N/A") AS indicearea 
                                            FROM
                                                reporteiluminacionareacategoria
                                                RIGHT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                                RIGHT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id 
                                            WHERE
                                                reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                                AND reporteiluminacionarea.reporteiluminacionarea_porcientooperacion > 0 
                                                AND reporteiluminacionarea.reporteiluminacionarea_puntos_ic  > 0
                                                AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0
                                            ORDER BY
                                                reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                                reporteiluminacionarea.reporteiluminacionarea_nombre ASC,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC
                                        ) AS TABLA');
            }


            // encabezado tabla
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 2000;
            $ancho_col_4 = 3000;
            $ancho_col_5 = 4000;
            $ancho_col_6 = 1000;
            $ancho_col_7 = 1000;
            $ancho_col_8 = 1000;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No. de puntos evaluados', $encabezado_texto);
            // $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
            // $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('IC', $encabezado_texto);
            // $table->addCell(($ancho_col_7 + $ancho_col_8), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Número de zonas a evaluar', $encabezado_texto);
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $continua_fila);
            // $table->addCell($ancho_col_2, $continua_fila);
            // $table->addCell($ancho_col_3, $continua_fila);
            // $table->addCell($ancho_col_4, $continua_fila);
            // $table->addCell($ancho_col_5, $continua_fila);
            // $table->addCell($ancho_col_6, $continua_fila);            
            // $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('N.M.Z.E', $encabezado_texto);
            // $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('N.Z.C.P.L', $encabezado_texto);


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxxx';
            $total = 0;
            $total_general = 0;
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteiluminacionarea_instalacion) {
                    if (($key + 0) != 0) {
                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($total, $textonegrita);
                        $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal);
                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('N/P: No Proporcionado<w:br/>IC: Índice de Área<w:br/>N.M.Z.E: Número Mínimo de Zonas a Evaluar<w:br/>N.Z.C.P.L: Número de Zonas a Considerar Por la Limitación', $texto);
                    }

                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $combinar_fila_encabezado)->addTextRun($centrado)->addText('No. de puntos evaluados', $encabezado_texto);
                    // $table->addCell($ancho_col_2, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                    $table->addCell($ancho_col_3, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $combinar_fila_encabezado)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
                    $table->addCell($ancho_col_6, $combinar_fila_encabezado)->addTextRun($centrado)->addText('IC', $encabezado_texto);
                    $table->addCell(($ancho_col_7 + $ancho_col_8), array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado)->addText('Número de zonas<w:br/>a evaluar', $encabezado_texto);
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $continua_fila);
                    // $table->addCell($ancho_col_2, $continua_fila);
                    $table->addCell($ancho_col_3, $continua_fila);
                    $table->addCell($ancho_col_4, $continua_fila);
                    $table->addCell($ancho_col_5, $continua_fila);
                    $table->addCell($ancho_col_6, $continua_fila);
                    $table->addCell($ancho_col_7, $encabezado_celda)->addTextRun($centrado)->addText('N.M.Z.E', $encabezado_texto);
                    $table->addCell($ancho_col_8, $encabezado_celda)->addTextRun($centrado)->addText('N.Z.C.P.L', $encabezado_texto);


                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $encabezado_texto); // combina columna

                    $numero_fila = 0;
                    $total = 0;
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $numero_fila += 1;
                    $total += $value->reporteiluminacionarea_puntos_ic;
                    $total_general += $value->reporteiluminacionarea_puntos_ic;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_puntos_ic, $texto);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteiluminacionarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                //     $instalacion = $value->reporteiluminacionarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteiluminacioncategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteiluminacionareacategoria_actividades, $texto);


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_7, $combinar_fila)->addTextRun($centrado)->addText($value->indicearea, $texto);
                    $table->addCell($ancho_col_7, $combinar_fila)->addTextRun($centrado)->addText($value->zonasminimas, $texto);
                    $table->addCell($ancho_col_8, $combinar_fila)->addTextRun($centrado)->addText($value->zonasmaximas, $texto);
                    $area = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_7, $continua_fila);
                    $table->addCell($ancho_col_7, $continua_fila);
                    $table->addCell($ancho_col_8, $continua_fila);
                }
            }


            $table->addRow(); //fila
            $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($total, $textonegrita);
            $table->addCell(null, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal);
            $table->addRow(); //fila
            $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('N/P: No Proporcionado<w:br/>IC: Índice de Área<w:br/>N.M.Z.E: Número Mínimo de Zonas a Evaluar<w:br/>N.Z.C.P.L: Número de Zonas a Considerar Por la Limitación', $texto);


            if ($total_general > 0) {
                $plantillaword->setValue('INDICEAREA_DESCRIPCION', 'El método utilizado para esta evaluación se basa en dividir el área de trabajo en zonas del mismo tamaño, en consideración a lo establecido en la tabla “relación entre el índice de área y el número de zonas de medición” NOM-025-STPS-2008. Con el objetivo de describir el entorno ambiental de la iluminación de una forma confiable.<w:br/>');

                $plantillaword->setComplexBlock('TABLA_6_2_1', $table);

                // $plantillaword->setValue('INDICEAREA_ABREVIACION', '</w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>N/P</w:t></w:r><w:r><w:t>     : No Proporcionado<w:br/></w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>IC</w:t></w:r><w:r><w:t>      : Índice de Área<w:br/></w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>N.M.Z.E</w:t></w:r><w:r><w:t>     : Número Mínimo de Zonas a Evaluar<w:br/></w:t></w:r><w:r><w:rPr><w:b/></w:rPr><w:t>N.Z.C.P.L</w:t></w:r><w:r><w:t>    : Número de Zonas a Considerar Por la Limitación');

                $plantillaword->setValue('INDICEAREA_ABREVIACION', '');
            } else {
                $plantillaword->setValue('INDICEAREA_DESCRIPCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, 'Para el presente estudio de iluminación realizado en INSTALACION_NOMBRE se empleó el criterio de selección: Puesto de Trabajo, por lo que no se incluye información en relación al método de índice de área dentro de este apartado.'));

                $plantillaword->setValue('TABLA_6_2_1', '');

                $plantillaword->setValue('INDICEAREA_ABREVIACION', '');
            }


            // TABLA 6.2.2 Puesto de trabajo
            //================================================================================


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        TABLA.proyecto_id,
                                        TABLA.id,
                                        TABLA.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                        TABLA.reportearea_nombre AS reporteiluminacionarea_nombre,
                                        TABLA.reportearea_orden,
                                        TABLA.reportearea_porcientooperacion,
                                        TABLA.reportearea_porcientooperacion_texto,
                                        TABLA.reportearea_puntos_ic AS reporteiluminacionarea_puntos_ic,
                                        TABLA.indicearea,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 25
                                                WHEN (TABLA.indicearea >= 2) THEN 16
                                                WHEN (TABLA.indicearea >= 1) THEN 9
                                                WHEN (TABLA.indicearea > 0 ) THEN 4
                                                ELSE "N/A"
                                            END
                                        ) AS zonasminimas,
                                        (
                                            CASE
                                                WHEN (TABLA.indicearea >= 3) THEN 30
                                                WHEN (TABLA.indicearea >= 2) THEN 20
                                                WHEN (TABLA.indicearea >= 1) THEN 12
                                                WHEN (TABLA.indicearea > 0 ) THEN 6
                                                ELSE "N/A"
                                            END
                                        ) AS zonasmaximas,
                                        TABLA.reportearea_puntos_pt AS reporteiluminacionarea_puntos_pt,
                                        TABLA.reportearea_sistemailuminacion,
                                        TABLA.reportearea_luznatural AS reporteiluminacionarea_luznatural,
                                        TABLA.reportearea_iluminacionlocalizada AS reporteiluminacionarea_iluminacionlocalizada,
                                        TABLA.reportearea_colorsuperficie,
                                        TABLA.reportearea_tiposuperficie,
                                        TABLA.reportearea_largo,
                                        TABLA.reportearea_ancho,
                                        TABLA.reportearea_alto,
                                        TABLA.reportecategoria_id,
                                        TABLA.reportecategoria_orden,
                                        TABLA.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                        TABLA.checked,
                                        TABLA.reportecategoria_horas AS reporteiluminacioncategoria_horas,
                                        TABLA.reporteareacategoria_total,
                                        TABLA.reporteareacategoria_geh,
                                        TABLA.reporteareacategoria_actividades AS reporteiluminacionareacategoria_actividades,
                                        TABLA.reporteareacategoria_tareavisual AS reporteiluminacionareacategoria_tareavisual
                                    FROM
                                        (
                                            SELECT
                                                reportearea.proyecto_id,
                                                reportearea.id,
                                                reportearea.reportearea_instalacion,
                                                reportearea.reportearea_nombre,
                                                reportearea.reportearea_orden,
                                                -- reportearea.reportearea_porcientooperacion,
                                                reportearea.reporteiluminacionarea_porcientooperacion AS reportearea_porcientooperacion,
                                                IF( IFNULL( reportearea.reporteiluminacionarea_porcientooperacion, "" ) != "", CONCAT( reportearea.reporteiluminacionarea_porcientooperacion, " %" ), NULL ) AS reportearea_porcientooperacion_texto,
                                                reportearea.reportearea_puntos_ic,
                                                IFNULL((
                                                    ROUND(((reportearea_largo*reportearea_ancho) / (reportearea_alto * (reportearea_largo + reportearea_ancho))), 1)
                                                ), "N/A") AS indicearea, 
                                                reportearea.reportearea_puntos_pt,
                                                reportearea.reportearea_sistemailuminacion,
                                                reportearea.reportearea_luznatural,
                                                reportearea.reportearea_iluminacionlocalizada,
                                                reportearea.reportearea_colorsuperficie,
                                                reportearea.reportearea_tiposuperficie,
                                                reportearea.reportearea_largo,
                                                reportearea.reportearea_ancho,
                                                reportearea.reportearea_alto,
                                                reporteareacategoria.reportecategoria_id,
                                                reportecategoria.reportecategoria_orden,
                                                reportecategoria.reportecategoria_nombre,
                                                IFNULL((
                                                    SELECT
                                                        IF(reporteiluminacionareacategoria.reporteiluminacioncategoria_id, "checked", "") AS checked
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS checked,
                                                reportecategoria.reportecategoria_horas,
                                                reporteareacategoria.reporteareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh,
                                                reporteareacategoria.reporteareacategoria_actividades,
                                                -- reporteareacategoria.reporteareacategoria_tareavisual,
                                                IFNULL((
                                                    SELECT
                                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                                    FROM
                                                        reporteiluminacionareacategoria
                                                    WHERE
                                                        reporteiluminacionareacategoria.reporteiluminacionarea_id = reportearea.id
                                                        AND reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteareacategoria.reportecategoria_id
                                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = ' . $reporteiluminacion_id . ' 
                                                    LIMIT 1
                                                ), "") AS reporteareacategoria_tareavisual
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
                                        TABLA.checked != ""
                                        AND TABLA.reportearea_porcientooperacion > 0
                                        AND TABLA.reportearea_puntos_pt > 0
                                    ORDER BY
                                        TABLA.reportearea_orden ASC,
                                        TABLA.reportearea_nombre ASC,
                                        TABLA.reportecategoria_orden ASC,
                                        TABLA.reportecategoria_nombre ASC');
            } else {
                $sql = DB::select('SELECT
                                        reporteiluminacionarea.proyecto_id,
                                        reporteiluminacionarea.reporteiluminacionarea_puntos_pt,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre,
                                        reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_actividades,
                                        reporteiluminacionareacategoria.reporteiluminacionareacategoria_tareavisual
                                    FROM
                                        reporteiluminacionareacategoria
                                        RIGHT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id
                                        RIGHT JOIN reporteiluminacionarea ON reporteiluminacionareacategoria.reporteiluminacionarea_id = reporteiluminacionarea.id 
                                    WHERE
                                        reporteiluminacionarea.proyecto_id = ' . $proyecto_id . ' 
                                        AND reporteiluminacionarea.registro_id = ' . $reporteiluminacion_id . ' 
                                        AND reporteiluminacionarea.reporteiluminacionarea_porcientooperacion > 0 
                                        AND reporteiluminacionarea.reporteiluminacionarea_puntos_pt  > 0
                                        AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0 
                                    ORDER BY
                                        reporteiluminacionarea.reporteiluminacionarea_numorden ASC,
                                        reporteiluminacionarea.reporteiluminacionarea_nombre ASC,
                                        reporteiluminacioncategoria.reporteiluminacioncategoria_nombre ASC');
            }


            // Columnas
            $ancho_col_1 = 1000;
            // $ancho_col_2 = 1500;
            $ancho_col_3 = 2000;
            $ancho_col_4 = 3000;
            $ancho_col_5 = 3500;
            $ancho_col_6 = 3500;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'unit' => TblWidth::TWIP));

            // // encabezado tabla
            // $table->addRow(200, array('tblHeader' => true));
            // $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No. de puntos evaluados', $encabezado_texto);
            // // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
            // $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
            // $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
            // $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
            // $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Tarea visual', $encabezado_texto);


            $numero_fila = 0;
            $instalacion = 'XXXXX';
            $area = 'xxxxx';
            $total = 0;
            $total_general = 0;
            foreach ($sql as $key => $value) {
                if ($instalacion != $value->reporteiluminacionarea_instalacion) {
                    if (($key + 0) != 0) {
                        $table->addRow(); //fila
                        $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($total, $textonegrita);
                        $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal);
                        $table->addRow(); //fila
                        $table->addCell(null, array('gridSpan' => 7, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($izquierda)->addText('', $texto);
                    }

                    // encabezado tabla
                    $table->addRow(200, array('tblHeader' => true));
                    $table->addCell($ancho_col_1, $encabezado_celda)->addTextRun($centrado)->addText('No. de puntos evaluados', $encabezado_texto);
                    // $table->addCell($ancho_col_2, $encabezado_celda)->addTextRun($centrado)->addText('Instalación', $encabezado_texto);
                    $table->addCell($ancho_col_3, $encabezado_celda)->addTextRun($centrado)->addText('Área', $encabezado_texto);
                    $table->addCell($ancho_col_4, $encabezado_celda)->addTextRun($centrado)->addText('Categoría', $encabezado_texto);
                    $table->addCell($ancho_col_5, $encabezado_celda)->addTextRun($centrado)->addText('Actividades', $encabezado_texto);
                    $table->addCell($ancho_col_6, $encabezado_celda)->addTextRun($centrado)->addText('Tarea visual', $encabezado_texto);

                    $table->addRow(); //fila
                    $table->addCell(null, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $encabezado_texto); // combina columna

                    $numero_fila = 0;
                    $total = 0;
                    $instalacion = $value->reporteiluminacionarea_instalacion;
                }


                $table->addRow(); //fila


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $numero_fila += 1;
                    $total += $value->reporteiluminacionarea_puntos_pt;
                    $total_general += $value->reporteiluminacionarea_puntos_pt;
                    $table->addCell($ancho_col_1, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_puntos_pt, $texto);
                } else {
                    $table->addCell($ancho_col_1, $continua_fila);
                }


                // if($instalacion != $value->reporteiluminacionarea_instalacion)
                // {
                //     $table->addCell($ancho_col_2, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_instalacion, $texto);
                //     $instalacion = $value->reporteiluminacionarea_instalacion;
                // }
                // else
                // {
                //     $table->addCell($ancho_col_2, $continua_fila);
                // }


                if ($area != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_3, $combinar_fila)->addTextRun($centrado)->addText($value->reporteiluminacionarea_nombre, $texto);
                    $area = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_3, $continua_fila);
                }


                $table->addCell($ancho_col_4, $celda)->addTextRun($centrado)->addText($value->reporteiluminacioncategoria_nombre, $texto);
                $table->addCell($ancho_col_5, $celda)->addTextRun($justificado)->addText($value->reporteiluminacionareacategoria_actividades, $texto);
                $table->addCell($ancho_col_6, $celda)->addTextRun($justificado)->addText($value->reporteiluminacionareacategoria_tareavisual, $texto);
            }


            $table->addRow(); //fila
            $table->addCell($ancho_col_1, $celda)->addTextRun($centrado)->addText($total, $textonegrita);
            $table->addCell(null, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado)->addText('Total de puntos evaluados', $textototal); // combina columna


            if ($total_general > 0) {
                $plantillaword->setValue('PUESTOTRABAJO_DESCRIPCION', 'El método de evaluación definido por puesto de trabajo se basa en realizar al menos una medición en cada plano de trabajo donde el trabajador realice sus actividades laborales. A continuación, se presentan:');

                $plantillaword->setComplexBlock('TABLA_6_2_2', $table);
            } else {
                $plantillaword->setValue('PUESTOTRABAJO_DESCRIPCION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, 'Para el presente estudio de iluminación realizado en INSTALACION_NOMBRE se empleó el criterio de selección: Índice de área, por lo que no se incluye información en relación al método de Puesto de Trabajo dentro de este apartado.'));

                $plantillaword->setValue('TABLA_6_2_2', '');
            }


            // TABLA 7.1 Resultados del nivel de iluminación
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Arial';
                $font_size = 8;
            } else {
                $fuente = 'Arial';
                $font_size = 8;
            }

            $bgColor_encabezado = '#0C3F64'; //#1A5276
            $encabezado_celda2 = array('bgColor' => $bgColor_encabezado, 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_celda2_celeste = array('bgColor' => '0BACDB', 'valign' => 'center', 'cellMargin' => 100);
            $encabezado_texto2 = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $combinar_fila_encabezado2 = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => $bgColor_encabezado);
            $combinar_fila_encabezado2_celeste = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0BACDB');
            $combinar_fila2 = array('vMerge' => 'restart', 'valign' => 'center');
            $continua_fila2 = array('vMerge' => 'continue', 'valign' => 'center');
            $celda2 = array('valign' => 'center');
            $centrado2 = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $izquierda2 = array('align' => 'left', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $justificado2 = array('align' => 'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.15);
            $texto2 = array('color' => '000000', 'size' => $font_size, 'bold' => false, 'name' => $fuente);
            $textonegrita2 = array('color' => '000000', 'size' => $font_size, 'bold' => true, 'name' => $fuente);
            $textototal2 = array('color' => 'FFFFFF', 'size' => $font_size, 'bold' => false, 'name' => $fuente);


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacionpuntos_categoria_id,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_fechaeval,
                                        reporteiluminacionpuntos_horario1,
                                        reporteiluminacionpuntos_horario2,
                                        reporteiluminacionpuntos_horario3,
                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,        
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,

                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#FF0000", "#000000"), "#000000") AS luxmed1_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#FF0000", "#000000"), "#000000") AS luxmed2_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#FF0000", "#000000"), "#000000") AS luxmed3_color,
                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#00FF00", "#FF0000"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#FF0000", "#000000"), "#000000") AS frpmed1_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#FF0000", "#000000"), "#000000") AS frpmed2_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#FF0000", "#000000"), "#000000") AS frpmed3_color,
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frp_resultado_color, 
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#FF0000", "#000000"), "#000000") AS frptmed1_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#FF0000", "#000000"), "#000000") AS frptmed2_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#FF0000", "#000000"), "#000000") AS frptmed3_color,
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frpt_resultado_color,

                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                            END
                                        ) AS fr_resultado,
                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#00FF00", "#FF0000")
                                            END
                                        ) AS fr_resultado_color
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                )  AS total_frpt
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            } else {
                $sql = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacionpuntos_categoria_id,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_fechaeval,
                                        IFNULL(reporteiluminacionpuntos_horario1, "-") AS reporteiluminacionpuntos_horario1,
                                        IFNULL(reporteiluminacionpuntos_horario2, "-") AS reporteiluminacionpuntos_horario2,
                                        IFNULL(reporteiluminacionpuntos_horario3, "-") AS reporteiluminacionpuntos_horario3,
                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,


                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00F00", "#000000"), "#000000") AS luxmed1_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00F00", "#000000"), "#000000") AS luxmed2_color,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00F00", "#000000"), "#000000") AS luxmed3_color,
                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#00FF00", "#FF0000"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00F00", "#000000"), "#000000") AS frpmed1_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00F00", "#000000"), "#000000") AS frpmed2_color,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00F00", "#000000"), "#000000") AS frpmed3_color,
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                        IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frp_resultado_color, 
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00F00", "#000000"), "#000000") AS frptmed1_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00F00", "#000000"), "#000000") AS frptmed2_color,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00F00", "#000000"), "#000000") AS frptmed3_color,
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                        IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#00FF00", "#FF0000")), "#7F8C8D") AS frpt_resultado_color,

                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                            END
                                        ) AS fr_resultado,
                                        (
                                            CASE
                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#00FF00", "#FF0000")
                                            END
                                        ) AS fr_resultado_color
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                )  AS total_frpt
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            }


            DB::statement('SET lc_time_names = "es_ES";');
            $fecha_puntos = DB::select('SELECT
                                            reporteiluminacionpuntos.proyecto_id,
                                            reporteiluminacionpuntos.registro_id,
                                            MIN(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval) AS fecha_i,
                                            MAX(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval) AS fecha_f,
                                            DATE_FORMAT(MIN(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval), "%d de %M del %Y") AS fecha_i_texto,
                                            DATE_FORMAT(MAX(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval), "%d de %M del %Y") AS fecha_f_texto,
                                            (
                                                CASE
                                                    WHEN MIN(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval) = MAX(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval)
                                                            THEN DATE_FORMAT(MIN(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval), "%d de %M del %Y")
                                                    ELSE CONCAT(DATE_FORMAT(MIN(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval), "%d de %M del %Y"), " al ",DATE_FORMAT(MAX(reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval), "%d de %M del %Y"))
                                                END
                                            ) AS fecha_completa
                                        FROM
                                            reporteiluminacionpuntos
                                        WHERE
                                            reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                            AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                        GROUP BY
                                            reporteiluminacionpuntos.proyecto_id,
                                            reporteiluminacionpuntos.registro_id');


            $fecha_evaluacion = '';
            if (count($fecha_puntos) > 0) {
                $fecha_evaluacion = $fecha_puntos[0]->fecha_completa;
            }


            // COLUMNAS
            $ancho_col_1 = 700;
            $ancho_col_2 = 700;
            $ancho_col_3 = 700;
            $ancho_col_4 = 700;
            $ancho_col_5 = 1800;
            $ancho_col_6 = 1000;
            $ancho_col_7 = 500;
            $ancho_col_8 = 2000;
            $ancho_col_9 = 700;
            $ancho_col_10 = 900;
            $ancho_col_11 = 900;
            $ancho_col_12 = 900;
            $ancho_col_13 = 1200;

            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'cellPadding' => 10, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(13000, array('gridSpan' => 13, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado2)->addText('Tabla de resultados de niveles de iluminación', $encabezado_texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(2800, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado2)->addText('Fecha de evaluación:', $encabezado_texto2);
            $table->addCell(10200, array('gridSpan' => 9, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($izquierda2)->addText($fecha_evaluacion, $texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $combinar_fila_encabezado2)->addTextRun($centrado2)->addText('No. Medición', $encabezado_texto2);
            $table->addCell(2100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Hora de medición', $encabezado_texto2);
            $table->addCell($ancho_col_5, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Ubicación', $encabezado_texto2);
            $table->addCell($ancho_col_6, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Concepto', $encabezado_texto2);
            $table->addCell($ancho_col_7, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('No. de POE', $encabezado_texto2);
            $table->addCell($ancho_col_8, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Categoría', $encabezado_texto2);
            $table->addCell($ancho_col_9, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('NIMR (Lux)', $encabezado_texto2);
            $table->addCell(2700, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Resultados de las mediciones<w:br/>con factor de corrección', $encabezado_texto2);
            $table->addCell($ancho_col_13, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Cumplimiento normativo', $encabezado_texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila2);
            $table->addCell(2100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Periodo', $encabezado_texto2);
            $table->addCell($ancho_col_5, $continua_fila2);
            $table->addCell($ancho_col_6, $continua_fila2);
            $table->addCell($ancho_col_7, $continua_fila2);
            $table->addCell($ancho_col_8, $continua_fila2);
            $table->addCell($ancho_col_9, $continua_fila2);
            $table->addCell($ancho_col_10, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('Periodo I', $encabezado_texto2);
            $table->addCell($ancho_col_11, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('Periodo II', $encabezado_texto2);
            $table->addCell($ancho_col_12, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('Periodo III', $encabezado_texto2);
            $table->addCell($ancho_col_13, $continua_fila2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila2);
            $table->addCell($ancho_col_2, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('I', $encabezado_texto2);
            $table->addCell($ancho_col_3, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('II', $encabezado_texto2);
            $table->addCell($ancho_col_4, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('III', $encabezado_texto2);
            $table->addCell($ancho_col_5, $continua_fila2);
            $table->addCell($ancho_col_6, $continua_fila2);
            $table->addCell($ancho_col_7, $continua_fila2);
            $table->addCell($ancho_col_8, $continua_fila2);
            $table->addCell($ancho_col_9, $continua_fila2);
            $table->addCell($ancho_col_10, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('NI (Lux)', $encabezado_texto2);
            $table->addCell($ancho_col_11, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('NI (Lux)', $encabezado_texto2);
            $table->addCell($ancho_col_12, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('NI (Lux)', $encabezado_texto2);
            $table->addCell($ancho_col_13, $continua_fila2);


            $numero_fila = 0;
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                $numero_fila += 1;
                $table->addRow(); //fila

                $table->addCell($ancho_col_1, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_nopunto, $texto2);
                $table->addCell($ancho_col_2, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario1, $texto2);
                $table->addCell($ancho_col_3, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario2, $texto2);
                $table->addCell($ancho_col_4, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario3, $texto2);

                if ($area != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_5, $combinar_fila2)->addTextRun($centrado2)->addText($value->reporteiluminacionarea_nombre, $texto2);
                    $area = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila2);
                }

                $table->addCell($ancho_col_6, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_concepto, $texto2);
                $table->addCell($ancho_col_7, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_nopoe, $texto2);
                $table->addCell($ancho_col_8, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacioncategoria_nombre, $texto2);
                $table->addCell($ancho_col_9, $celda2)->addTextRun($centrado2)->addText($value->lux, $texto2);
                $table->addCell($ancho_col_10, $celda2)->addTextRun($centrado2)->addText($value->luxmed1, array('color' => $value->luxmed1_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_11, $celda2)->addTextRun($centrado2)->addText($value->luxmed2, array('color' => $value->luxmed2_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_12, $celda2)->addTextRun($centrado2)->addText($value->luxmed3, array('color' => $value->luxmed3_color, 'size' => 8, 'bold' => false, 'name' => $fuente));

                if ($value->lux_resultado == "Fuera de norma") {
                    $table->addCell($ancho_col_13, array('valign' => 'center', 'bgColor' => $value->lux_resultado_color))->addTextRun($centrado2)->addText($value->lux_resultado, array('color' => 'FFFFFF', 'size' => 8, 'bold' => true, 'name' => $fuente));
                } else {
                    $table->addCell($ancho_col_13, array('valign' => 'center', 'bgColor' => $value->lux_resultado_color))->addTextRun($centrado2)->addText($value->lux_resultado, array('color' => '000000', 'size' => 8, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_1', $table);


            // TABLA 7.2 Resultados del nivel de reflexión
            //================================================================================


            // Columnas
            $ancho_col_1 = 700;
            $ancho_col_2 = 700;
            $ancho_col_3 = 700;
            $ancho_col_4 = 700;
            $ancho_col_5 = 2200;
            $ancho_col_6 = 500;
            $ancho_col_7 = 2000;
            $ancho_col_8 = 500;
            $ancho_col_9 = 500;
            $ancho_col_10 = 500;
            $ancho_col_11 = 500;
            $ancho_col_12 = 500;
            $ancho_col_13 = 500;
            $ancho_col_14 = 500;
            $ancho_col_15 = 500;
            $ancho_col_16 = 1500;


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'cellPadding' => 10, 'unit' => TblWidth::TWIP));

            // encabezado tabla
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(13000, array('gridSpan' => 16, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado2)->addText('Tabla de resultados del nivel de reflexión', $encabezado_texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell(2800, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado2)->addText('Fecha de evaluación:', $encabezado_texto2);
            $table->addCell(10200, array('gridSpan' => 12, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($izquierda2)->addText($fecha_evaluacion, $texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $combinar_fila_encabezado2)->addTextRun($centrado2)->addText('No. Medición', $encabezado_texto2);
            $table->addCell(2100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Hora de medición', $encabezado_texto2);
            $table->addCell($ancho_col_5, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Ubicación', $encabezado_texto2);
            $table->addCell($ancho_col_6, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('No. de POE', $encabezado_texto2);
            $table->addCell($ancho_col_7, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Categoría', $encabezado_texto2);
            $table->addCell(1000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB', 'vMerge' => 'restart'))->addTextRun($centrado2)->addText('NMPR, Kf<w:br/>(%)', $encabezado_texto2);
            $table->addCell(3000, array('gridSpan' => 6, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Resultados de las mediciones<w:br/>con factor de corrección', $encabezado_texto2);
            $table->addCell($ancho_col_16, $combinar_fila_encabezado2_celeste)->addTextRun($centrado2)->addText('Cumplimiento normativo', $encabezado_texto2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila2);
            $table->addCell(2100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Periodo', $encabezado_texto2);
            $table->addCell($ancho_col_5, $continua_fila2);
            $table->addCell($ancho_col_6, $continua_fila2);
            $table->addCell($ancho_col_7, $continua_fila2);
            $table->addCell(1000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB', 'vMerge' => 'continue'));
            $table->addCell(1000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Periodo I', $encabezado_texto2);
            $table->addCell(1000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Periodo II', $encabezado_texto2);
            $table->addCell(1000, array('gridSpan' => 2, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado2)->addText('Periodo III', $encabezado_texto2);
            $table->addCell($ancho_col_16, $continua_fila2);
            $table->addRow(200, array('tblHeader' => true));
            $table->addCell($ancho_col_1, $continua_fila2);
            $table->addCell($ancho_col_2, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('I', $encabezado_texto2);
            $table->addCell($ancho_col_3, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('II', $encabezado_texto2);
            $table->addCell($ancho_col_4, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('III', $encabezado_texto2);
            $table->addCell($ancho_col_5, $continua_fila2);
            $table->addCell($ancho_col_6, $continua_fila2);
            $table->addCell($ancho_col_7, $continua_fila2);
            $table->addCell($ancho_col_8, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('(P)', $encabezado_texto2);
            $table->addCell($ancho_col_9, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('(PT)', $encabezado_texto2);
            $table->addCell($ancho_col_10, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(P)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_11, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(PT)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_12, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(P)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_13, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(PT)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_14, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(P)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_15, $encabezado_celda2_celeste)->addTextRun($centrado2)->addText('FR<w:br/>(PT)<w:br/>(%)', $encabezado_texto2);
            $table->addCell($ancho_col_16, $continua_fila2);


            $numero_fila = 0;
            $area = 'xxxx';
            foreach ($sql as $key => $value) {
                $numero_fila += 1;
                $table->addRow(); //fila

                $table->addCell($ancho_col_1, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_nopunto, $texto2);
                $table->addCell($ancho_col_2, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario1, $texto2);
                $table->addCell($ancho_col_3, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario2, $texto2);
                $table->addCell($ancho_col_4, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_horario3, $texto2);

                if ($area != $value->reporteiluminacionarea_nombre) {
                    $table->addCell($ancho_col_5, $combinar_fila2)->addTextRun($centrado2)->addText($value->reporteiluminacionarea_nombre, $texto2);
                    $area = $value->reporteiluminacionarea_nombre;
                } else {
                    $table->addCell($ancho_col_5, $continua_fila2);
                }

                $table->addCell($ancho_col_6, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacionpuntos_nopoe, $texto2);
                $table->addCell($ancho_col_7, $celda2)->addTextRun($centrado2)->addText($value->reporteiluminacioncategoria_nombre, $texto2);
                $table->addCell($ancho_col_8, $celda2)->addTextRun($centrado2)->addText($value->frp, $texto2);
                $table->addCell($ancho_col_9, $celda2)->addTextRun($centrado2)->addText($value->frpt, $texto2);
                $table->addCell($ancho_col_10, $celda2)->addTextRun($centrado2)->addText($value->frpmed1, array('color' => $value->frpmed1_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_11, $celda2)->addTextRun($centrado2)->addText($value->frptmed1, array('color' => $value->frptmed1_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_12, $celda2)->addTextRun($centrado2)->addText($value->frpmed2, array('color' => $value->frpmed2_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_13, $celda2)->addTextRun($centrado2)->addText($value->frptmed2, array('color' => $value->frptmed2_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_14, $celda2)->addTextRun($centrado2)->addText($value->frpmed3, array('color' => $value->frpmed3_color, 'size' => 8, 'bold' => false, 'name' => $fuente));
                $table->addCell($ancho_col_15, $celda2)->addTextRun($centrado2)->addText($value->frptmed3, array('color' => $value->frptmed3_color, 'size' => 8, 'bold' => false, 'name' => $fuente));

                if ($value->fr_resultado == "Fuera de norma") {
                    $table->addCell($ancho_col_16, array('valign' => 'center', 'bgColor' => $value->fr_resultado_color))->addTextRun($centrado2)->addText($value->fr_resultado, array('color' => 'FFFFFF', 'size' => 8, 'bold' => true, 'name' => $fuente));
                } else {
                    $table->addCell($ancho_col_16, array('valign' => 'center', 'bgColor' => $value->fr_resultado_color))->addTextRun($centrado2)->addText($value->fr_resultado, array('color' => '000000', 'size' => 8, 'bold' => true, 'name' => $fuente));
                }
            }


            $plantillaword->setComplexBlock('TABLA_7_2', $table);


            // TABLA 7.3 Matriz de exposición laboral
            //================================================================================


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = Pemex, 0 = Cliente
            {
                $fuente = 'Arial';
                $font_size = 6;
            } else {
                $fuente = 'Arial';
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


            if (($areas_poe + 0) == 1) // TIENE TABLA POE GENERAL
            {
                $sql = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                        IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                        IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                        IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                        (
                                            CASE
                                                WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) = "" THEN catactivo_nombre
                                                ELSE catgerencia_nombre
                                            END
                                        ) AS gerencia_activo,
                                        proyecto_clienteinstalacion,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionareacategoria_total,
                                        reporteiluminacionareacategoria_geo,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_fechaeval,
                                        reporteiluminacionpuntos_horario1,
                                        reporteiluminacionpuntos_horario2,
                                        reporteiluminacionpuntos_horario3,
                                        
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux, 
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("< ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,
                                        
                                        (
                                            CASE
                                                WHEN reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed2 = 0, 1000000, reporteiluminacionpuntos_luxmed2)
                                                    AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                THEN reporteiluminacionpuntos_luxmed1
                                                WHEN
                                                    reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed1 = 0, 1000000, reporteiluminacionpuntos_luxmed1) 
                                                    AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                THEN reporteiluminacionpuntos_luxmed2
                                                ELSE reporteiluminacionpuntos_luxmed3
                                            END
                                        ) AS lux_resultado_critico,
                                        IF(lux = 1, IF(total_lux = 3, "Si cumple", "No cumple"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,       
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                catregion.catregion_nombre,
                                                catsubdireccion.catsubdireccion_nombre,
                                                catgerencia.catgerencia_nombre,
                                                catactivo.catactivo_nombre,
                                                proyecto.proyecto_clienteinstalacion,
                                                reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteareacategoria.reporteareacategoria_total AS reporteiluminacionareacategoria_total,
                                                reporteareacategoria.reporteareacategoria_geh AS reporteiluminacionareacategoria_geo,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt 
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN proyecto ON reporteiluminacionpuntos.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                                LEFT JOIN reporteareacategoria ON reportearea.id = reporteareacategoria.reportearea_id 
                                                AND reportecategoria.id = reporteareacategoria.reportecategoria_id 
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . '
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            } else {
                $sql = DB::select('SELECT
                                        id,
                                        proyecto_id,
                                        IF(catregion_nombre = "N/A", "", catregion_nombre) AS catregion_nombre,
                                        IF(catsubdireccion_nombre = "N/A", "", catsubdireccion_nombre) AS catsubdireccion_nombre,
                                        IF(catgerencia_nombre = "N/A", "", catgerencia_nombre) AS catgerencia_nombre,
                                        IF(catactivo_nombre = "N/A", "", catactivo_nombre) AS catactivo_nombre,
                                        (
                                            CASE
                                                WHEN IF(catactivo_nombre = "N/A", "", catactivo_nombre) = "" THEN catactivo_nombre
                                                ELSE catgerencia_nombre
                                            END
                                        ) AS gerencia_activo,
                                        proyecto_clienteinstalacion,
                                        reporteiluminacionarea_instalacion,
                                        reporteiluminacionpuntos_area_id,
                                        reporteiluminacionarea_nombre,
                                        reporteiluminacioncategoria_nombre,
                                        reporteiluminacionpuntos_nombre,
                                        reporteiluminacionpuntos_ficha,
                                        reporteiluminacionareacategoria_total,
                                        reporteiluminacionareacategoria_geo,
                                        reporteiluminacionpuntos_nopoe,
                                        reporteiluminacionpuntos_nopunto,
                                        reporteiluminacionpuntos_concepto,
                                        reporteiluminacionpuntos_fechaeval,
                                        reporteiluminacionpuntos_horario1,
                                        reporteiluminacionpuntos_horario2,
                                        reporteiluminacionpuntos_horario3,
                                        
                                        IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux, 
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), IF(reporteiluminacionpuntos_luxmed1menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1))), "N/A") AS luxmed1,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), IF(reporteiluminacionpuntos_luxmed2menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2))), "N/A") AS luxmed2,
                                        IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), IF(reporteiluminacionpuntos_luxmed3menor = 1, CONCAT("˂ ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3))), "N/A") AS luxmed3,
                                        (
                                            CASE
                                                WHEN reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed2 = 0, 1000000, reporteiluminacionpuntos_luxmed2)
                                                    AND reporteiluminacionpuntos_luxmed1 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                THEN reporteiluminacionpuntos_luxmed1
                                                WHEN
                                                    reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed1 = 0, 1000000, reporteiluminacionpuntos_luxmed1) 
                                                    AND reporteiluminacionpuntos_luxmed2 < IF(reporteiluminacionpuntos_luxmed3 = 0, 1000000, reporteiluminacionpuntos_luxmed3) 
                                                THEN reporteiluminacionpuntos_luxmed2
                                                ELSE reporteiluminacionpuntos_luxmed3
                                            END
                                        ) AS lux_resultado_critico,
                                        IF(lux = 1, IF(total_lux = 3, "Si cumple", "No cumple"), "N/A") AS lux_resultado,
                                        IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                        
                                        IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                        IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,       
                                        
                                        IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                        IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3
                                    FROM
                                        (
                                            SELECT
                                                reporteiluminacionpuntos.id,
                                                reporteiluminacionpuntos.proyecto_id,
                                                catregion.catregion_nombre,
                                                catsubdireccion.catsubdireccion_nombre,
                                                catgerencia.catgerencia_nombre,
                                                catactivo.catactivo_nombre,
                                                proyecto.proyecto_clienteinstalacion,
                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_total,
                                                reporteiluminacionareacategoria.reporteiluminacionareacategoria_geo,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS lux,
                                                (
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                    +
                                                    (
                                                        CASE
                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                            ELSE 0
                                                        END
                                                    )
                                                ) AS total_lux,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3menor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frp,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3,
                                                (
                                                    CASE
                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                        ELSE 1
                                                    END
                                                ) AS frpt   
                                            FROM
                                                reporteiluminacionpuntos
                                                LEFT JOIN proyecto ON reporteiluminacionpuntos.proyecto_id = proyecto.id
                                                LEFT JOIN catregion ON proyecto.catregion_id = catregion.id
                                                LEFT JOIN catsubdireccion ON proyecto.catsubdireccion_id = catsubdireccion.id
                                                LEFT JOIN catgerencia ON proyecto.catgerencia_id = catgerencia.id
                                                LEFT JOIN catactivo ON proyecto.catactivo_id = catactivo.id
                                                LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                LEFT JOIN reporteiluminacionareacategoria ON reporteiluminacionarea.id = reporteiluminacionareacategoria.reporteiluminacionarea_id 
                                                AND reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacionareacategoria.reporteiluminacioncategoria_id
                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionareacategoria.reporteiluminacioncategoria_id = reporteiluminacioncategoria.id 
                                            WHERE
                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . ' 
                                                -- AND reporteiluminacionareacategoria.reporteiluminacionareacategoria_poe = 0 
                                            ORDER BY
                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                        ) AS TABLA');
            }


            if (($recsensorial->recsensorial_tipocliente + 0) == 1) // 1 = pemex, 0 = cliente
            {
                if (str_contains($proyecto->catsubdireccion->catsubdireccion_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1 || str_contains($proyecto->catgerencia->catgerencia_nombre, ['Perforación', 'perforación', 'Perforacion', 'perforacion']) == 1) {
                    $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>Se anexa en formato digital.<w:br/>');
                } else {
                    $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico iluminación, así como información del área física y de la plantilla laboral de la instalación en cuestión.<w:br/>');
                }
            } else {
                $plantillaword->setValue('MATRIZ_TEXTO', '<w:br/>La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico iluminación, así como información del área física y de la plantilla laboral de la instalación en cuestión.<w:br/>');
            }


            // Crear tabla
            $table = null;
            $table = new Table(array('name' => $fuente, 'borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 40, 'cellPadding' => 10, 'unit' => TblWidth::TWIP));


            if ($proyecto->catregion_id == 1) //REGION NORTE
            {
                // encabezado tabla
                $ancho_col_1 = 400;
                $ancho_col_2 = 1100;
                $ancho_col_3 = 1100;
                $ancho_col_4 = 1100;
                $ancho_col_5 = 1250;
                $ancho_col_6 = 1400;
                $ancho_col_7 = 800;
                $ancho_col_8 = 1200;
                $ancho_col_9 = 500;
                $ancho_col_10 = 500;
                $ancho_col_11 = 500;
                $ancho_col_12 = 350;
                $ancho_col_13 = 350;
                $ancho_col_14 = 350;
                $ancho_col_15 = 350;
                $ancho_col_16 = 350;
                $ancho_col_17 = 350;
                $ancho_col_18 = 350;
                $ancho_col_19 = 350;
                $ancho_col_20 = 350;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(5000, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(4400, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                $table->addCell(3200, array('gridSpan' => 10, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(5000, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(4400, array('gridSpan' => 5, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                $table->addCell(3200, array('gridSpan' => 10, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Iluminación', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de referencia en atlas de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Número de personas en el área', $encabezado_texto3);
                $table->addCell($ancho_col_10, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Grupo de exposición homogénea', $encabezado_texto3);
                $table->addCell($ancho_col_11, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Niveles mínimos de iluminación (lux)', $encabezado_texto3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Nivel de iluminación (E2) (lux)', $encabezado_texto3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Niveles máximos permisibles de reflexión plano de trabajo (50%)', $encabezado_texto3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Niveles máximos permisibles de reflexión paredes (60%)', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $continua_fila3);
                $table->addCell($ancho_col_3, $continua_fila3);
                $table->addCell($ancho_col_4, $continua_fila3);
                $table->addCell($ancho_col_5, $continua_fila3);
                $table->addCell($ancho_col_6, $continua_fila3);
                $table->addCell($ancho_col_7, $continua_fila3);
                $table->addCell($ancho_col_8, $continua_fila3);
                $table->addCell($ancho_col_9, $continua_fila3);
                $table->addCell($ancho_col_10, $continua_fila3);
                $table->addCell($ancho_col_11, $continua_fila3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Periodo', $encabezado_texto3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Periodo', $encabezado_texto3);
                $table->addCell(900, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0BACDB'))->addTextRun($centrado3)->addText('Periodo', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $continua_fila3);
                $table->addCell($ancho_col_3, $continua_fila3);
                $table->addCell($ancho_col_4, $continua_fila3);
                $table->addCell($ancho_col_5, $continua_fila3);
                $table->addCell($ancho_col_6, $continua_fila3);
                $table->addCell($ancho_col_7, $continua_fila3);
                $table->addCell($ancho_col_8, $continua_fila3);
                $table->addCell($ancho_col_9, $continua_fila3);
                $table->addCell($ancho_col_10, $continua_fila3);
                $table->addCell($ancho_col_11, $continua_fila3);
                $table->addCell($ancho_col_12, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('I', $encabezado_texto2);
                $table->addCell($ancho_col_13, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('II', $encabezado_texto2);
                $table->addCell($ancho_col_14, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('III', $encabezado_texto2);
                $table->addCell($ancho_col_15, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('I', $encabezado_texto2);
                $table->addCell($ancho_col_16, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('II', $encabezado_texto2);
                $table->addCell($ancho_col_17, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('III', $encabezado_texto2);
                $table->addCell($ancho_col_18, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('I', $encabezado_texto2);
                $table->addCell($ancho_col_19, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('II', $encabezado_texto2);
                $table->addCell($ancho_col_20, $encabezado_celda2_celeste)->addTextRun($centrado3)->addText('III', $encabezado_texto2);

                $numero_fila = 0;
                $subdireccion = 'xxxx';
                $gerencia_activo = 'xxxx';
                $instalacion = 'xxxx';
                $area = 'xxxx';

                foreach ($sql as $key => $value) {
                    $numero_fila += 1;
                    $table->addRow(); //fila

                    $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($numero_fila, $texto3);
                    // $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_nopunto, $texto3);

                    if ($subdireccion != $value->catsubdireccion_nombre) {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdireccion = $value->catsubdireccion_nombre;
                    } else {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if ($gerencia_activo != $value->gerencia_activo) {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $gerencia_activo = $value->gerencia_activo;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if ($instalacion != $value->proyecto_clienteinstalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->proyecto_clienteinstalacion, $texto3);
                        $instalacion = $value->proyecto_clienteinstalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->reporteiluminacionarea_nombre) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteiluminacionarea_nombre, $texto3);
                        $area = $value->reporteiluminacionarea_nombre;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }

                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacioncategoria_nombre, $texto3);
                    $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionareacategoria_total, $texto3);
                    $table->addCell($ancho_col_10, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionareacategoria_geo, $texto3);
                    $table->addCell($ancho_col_11, $celda3)->addTextRun($centrado3)->addText($value->lux, $texto3);

                    $table->addCell($ancho_col_12, $celda3)->addTextRun($centrado3)->addText($value->luxmed1, $texto3);
                    $table->addCell($ancho_col_13, $celda3)->addTextRun($centrado3)->addText($value->luxmed2, $texto3);
                    $table->addCell($ancho_col_14, $celda3)->addTextRun($centrado3)->addText($value->luxmed3, $texto3);
                    $table->addCell($ancho_col_15, $celda3)->addTextRun($centrado3)->addText($value->frptmed1, $texto3);
                    $table->addCell($ancho_col_16, $celda3)->addTextRun($centrado3)->addText($value->frptmed2, $texto3);
                    $table->addCell($ancho_col_17, $celda3)->addTextRun($centrado3)->addText($value->frptmed3, $texto3);
                    $table->addCell($ancho_col_18, $celda3)->addTextRun($centrado3)->addText($value->frpmed1, $texto3);
                    $table->addCell($ancho_col_19, $celda3)->addTextRun($centrado3)->addText($value->frpmed2, $texto3);
                    $table->addCell($ancho_col_20, $celda3)->addTextRun($centrado3)->addText($value->frpmed3, $texto3);
                }


                $table->addRow(); //fila
                $table->addCell(null, array('gridSpan' => 20, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($justificado)->addText('NR: No Registrado (Durante la medición no se encontró el personal que realiza actividades)', $texto);
            } else {
                // encabezado tabla
                $ancho_col_1 = 400;
                $ancho_col_2 = 1500;
                $ancho_col_3 = 1500;
                $ancho_col_4 = 1500;
                $ancho_col_5 = 2300;
                $ancho_col_6 = 1800;
                $ancho_col_7 = 800;
                $ancho_col_8 = 1500;
                $ancho_col_9 = 1700;

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '0C3F64', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addTextRun($centrado3)->addText('Contador', $encabezado_texto3);
                $table->addCell(6800, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Área física', $textonegrita3);
                $table->addCell(4100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Plantilla laboral, unidad de implantación', $textonegrita3);
                $table->addCell(1700, array('valign' => 'center', 'bgColor' => 'FFFFFF'))->addTextRun($centrado3)->addText('Agentes físicos', $textonegrita3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell(6800, array('gridSpan' => 4, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Localización', $encabezado_texto3);
                $table->addCell(4100, array('gridSpan' => 3, 'valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Datos demográficos', $encabezado_texto3);
                $table->addCell(1700, array('valign' => 'center', 'bgColor' => '0C3F64'))->addTextRun($centrado3)->addText('Iluminación', $encabezado_texto3);

                $table->addRow(200, array('tblHeader' => true));
                $table->addCell($ancho_col_1, $continua_fila3);
                $table->addCell($ancho_col_2, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Subdirección o corporativo', $encabezado_texto3);
                $table->addCell($ancho_col_3, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Gerencia o activo', $encabezado_texto3);
                $table->addCell($ancho_col_4, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Instalación', $encabezado_texto3);
                $table->addCell($ancho_col_5, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Área de referencia en atlas de riesgo', $encabezado_texto3);
                $table->addCell($ancho_col_6, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Nombre', $encabezado_texto3);
                $table->addCell($ancho_col_7, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Ficha', $encabezado_texto3);
                $table->addCell($ancho_col_8, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Categoría', $encabezado_texto3);
                $table->addCell($ancho_col_9, $combinar_fila_encabezado3_celeste)->addTextRun($centrado3)->addText('Iluminación<w:br/>Lux (E 2c) / NMI', $encabezado_texto3);

                $numero_fila = 0;
                $subdireccion = 'xxxx';
                $gerencia_activo = 'xxxx';
                $instalacion = 'xxxx';
                $area = 'xxxx';

                foreach ($sql as $key => $value) {
                    $numero_fila += 1;
                    $table->addRow(); //fila

                    $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($numero_fila, $texto3);
                    // $table->addCell($ancho_col_1, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_nopunto, $texto3);

                    if ($subdireccion != $value->catsubdireccion_nombre) {
                        $table->addCell($ancho_col_2, $combinar_fila3)->addTextRun($centrado3)->addText($value->catsubdireccion_nombre, $texto3);
                        $subdireccion = $value->catsubdireccion_nombre;
                    } else {
                        $table->addCell($ancho_col_2, $continua_fila3);
                    }


                    if ($gerencia_activo != $value->gerencia_activo) {
                        $table->addCell($ancho_col_3, $combinar_fila3)->addTextRun($centrado3)->addText($value->gerencia_activo, $texto3);
                        $gerencia_activo = $value->gerencia_activo;
                    } else {
                        $table->addCell($ancho_col_3, $continua_fila3);
                    }


                    if ($instalacion != $value->proyecto_clienteinstalacion) {
                        $table->addCell($ancho_col_4, $combinar_fila3)->addTextRun($centrado3)->addText($value->proyecto_clienteinstalacion, $texto3);
                        $instalacion = $value->proyecto_clienteinstalacion;
                    } else {
                        $table->addCell($ancho_col_4, $continua_fila3);
                    }


                    if ($area != $value->reporteiluminacionarea_nombre) {
                        $table->addCell($ancho_col_5, $combinar_fila3)->addTextRun($centrado3)->addText($value->reporteiluminacionarea_nombre, $texto3);
                        $area = $value->reporteiluminacionarea_nombre;
                    } else {
                        $table->addCell($ancho_col_5, $continua_fila3);
                    }

                    $table->addCell($ancho_col_6, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_nombre, $texto3);
                    $table->addCell($ancho_col_7, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacionpuntos_ficha, $texto3);
                    $table->addCell($ancho_col_8, $celda3)->addTextRun($centrado3)->addText($value->reporteiluminacioncategoria_nombre, $texto3);
                    $table->addCell($ancho_col_9, $celda3)->addTextRun($centrado3)->addText($value->lux_resultado_critico . ' / ' . $value->lux . ' / ' . $value->lux_resultado, $texto3);
                }


                $table->addRow(); //fila
                $table->addCell(null, array('gridSpan' => 9, 'valign' => 'center', 'borderTopColor' => 'ffffff', 'borderTopSize' => 1, 'borderRightColor' => 'ffffff', 'borderRightSize' => 1, 'borderBottomColor' => 'ffffff', 'borderBottomSize' => 1, 'borderLeftColor' => 'ffffff', 'borderLeftSize' => 1))->addTextRun($justificado)->addText('NR: No Registrado (Durante la medición no se encontró el personal que realiza actividades)', $texto);
            }


            $plantillaword->setComplexBlock('TABLA_7_3', $table);


            // CONCLUSION
            //================================================================================


            $plantillaword->setValue('CONCLUSION', $this->datosproyectoreemplazartexto($proyecto, $recsensorial, $reporteiluminacion->reporteiluminacion_conclusion));


            // DASHBOARD
            //================================================================================


            if (($areas_poe + 0) == 1) {
                $categorias = DB::select('SELECT
                                                proyecto_id,
                                                reporteiluminacioncategoria_nombre
                                            FROM
                                                (
                                                    SELECT
                                                        id,
                                                        proyecto_id,
                                                        reporteiluminacionarea_instalacion,
                                                        -- reporteiluminacionpuntos_area_id,
                                                        reporteiluminacionarea_nombre,
                                                        -- reporteiluminacionpuntos_categoria_id,
                                                        reporteiluminacioncategoria_nombre,
                                                        -- reporteiluminacionpuntos_nombre,
                                                        -- reporteiluminacionpuntos_ficha,
                                                        -- reporteiluminacionpuntos_nopunto,
                                                        -- reporteiluminacionpuntos_concepto,
                                                        -- reporteiluminacionpuntos_nopoe,
                                                        -- reporteiluminacionpuntos_fechaeval,
                                                        -- reporteiluminacionpuntos_horario1,
                                                        -- reporteiluminacionpuntos_horario2,
                                                        -- reporteiluminacionpuntos_horario3,
                                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                                        -- IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                        -- IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                        
                                                        -- IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                        -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                        -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                        
                                                        -- IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                        -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                        -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                        (
                                                            CASE
                                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                            END
                                                        ) AS fr_resultado
                                                        -- ,(
                                                        --     CASE
                                                        --         WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                        --         ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                        --     END
                                                        -- ) AS fr_resultado_color
                                                    FROM
                                                        (
                                                            SELECT
                                                                reporteiluminacionpuntos.id,
                                                                reporteiluminacionpuntos.proyecto_id,
                                                                reportearea.reportearea_instalacion AS reporteiluminacionarea_instalacion,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                                reportearea.reportearea_nombre AS reporteiluminacionarea_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                                reportecategoria.reportecategoria_nombre AS reporteiluminacioncategoria_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS lux,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                ) AS total_lux,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS frp,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                ) AS total_frp,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS frpt,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                )  AS total_frpt
                                                            FROM
                                                                reporteiluminacionpuntos
                                                                LEFT JOIN reportearea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reportearea.id
                                                                LEFT JOIN reportecategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reportecategoria.id
                                                            WHERE
                                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                            ORDER BY
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                        ) AS TABLA
                                                ) AS RESULTADO
                                            WHERE
                                                lux_resultado = "Fuera de norma" OR fr_resultado = "Fuera de norma"
                                            GROUP BY
                                                proyecto_id,
                                                reporteiluminacioncategoria_nombre');
            } else {
                $categorias = DB::select('SELECT
                                                proyecto_id,
                                                reporteiluminacioncategoria_nombre
                                                
                                            FROM
                                                (
                                                    SELECT
                                                        id,
                                                        proyecto_id,
                                                        reporteiluminacionarea_instalacion,
                                                        -- reporteiluminacionpuntos_area_id,
                                                        reporteiluminacionarea_nombre,
                                                        -- reporteiluminacionpuntos_categoria_id,
                                                        reporteiluminacioncategoria_nombre,
                                                        -- reporteiluminacionpuntos_nombre,
                                                        -- reporteiluminacionpuntos_ficha,
                                                        -- reporteiluminacionpuntos_nopunto,
                                                        -- reporteiluminacionpuntos_concepto,
                                                        -- reporteiluminacionpuntos_nopoe,
                                                        -- reporteiluminacionpuntos_fechaeval,
                                                        -- reporteiluminacionpuntos_horario1,
                                                        -- reporteiluminacionpuntos_horario2,
                                                        -- reporteiluminacionpuntos_horario3,
                                                        --  reporteiluminacionpuntos_luxmed1mayor,
                                                        --  reporteiluminacionpuntos_luxmed2mayor,
                                                        --  reporteiluminacionpuntos_luxmed3mayor,
                                                        -- IF(lux = 1, reporteiluminacionpuntos_lux, "N/A") AS lux,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed1mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed1), reporteiluminacionpuntos_luxmed1)), "N/A") AS luxmed1,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed2mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed2), reporteiluminacionpuntos_luxmed2)), "N/A") AS luxmed2,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 = 0, "N/A", IF(reporteiluminacionpuntos_luxmed3mayor = 1, CONCAT("> ", reporteiluminacionpuntos_luxmed3), reporteiluminacionpuntos_luxmed3)), "N/A") AS luxmed3,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed1 < 0 OR (reporteiluminacionpuntos_luxmed1 > 0 AND reporteiluminacionpuntos_luxmed1 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed1_color,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed2 < 0 OR (reporteiluminacionpuntos_luxmed2 > 0 AND reporteiluminacionpuntos_luxmed2 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed2_color,
                                                        -- IF(lux = 1, IF(reporteiluminacionpuntos_luxmed3 < 0 OR (reporteiluminacionpuntos_luxmed3 > 0 AND reporteiluminacionpuntos_luxmed3 < reporteiluminacionpuntos_lux), "#F00", "inherit"), "inherit") AS luxmed3_color,
                                                        IF(lux = 1, IF(total_lux = 3, "Dentro de norma", "Fuera de norma"), "N/A") AS lux_resultado,
                                                        -- IF(lux = 1, IF(total_lux = 3, "#27AE60", "#C0392B"), "#7F8C8D") AS lux_resultado_color,
                                                        
                                                        -- IF(frp = 1, reporteiluminacionpuntos_frp, "N/A") AS frp,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 = 0, "N/A", reporteiluminacionpuntos_frpmed1), "N/A") AS frpmed1,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 = 0, "N/A", reporteiluminacionpuntos_frpmed2), "N/A") AS frpmed2,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 = 0, "N/A", reporteiluminacionpuntos_frpmed3), "N/A") AS frpmed3,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed1 < 0 OR (reporteiluminacionpuntos_frpmed1 > 0 AND reporteiluminacionpuntos_frpmed1 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed1_color,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed2 < 0 OR (reporteiluminacionpuntos_frpmed2 > 0 AND reporteiluminacionpuntos_frpmed2 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed2_color,
                                                        -- IF(frp = 1, IF(reporteiluminacionpuntos_frpmed3 < 0 OR (reporteiluminacionpuntos_frpmed3 > 0 AND reporteiluminacionpuntos_frpmed3 > reporteiluminacionpuntos_frp), "#F00", "inherit"), "inherit") AS frpmed3_color,
                                                        -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "N/A", IF(total_frp = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frp_resultado, 
                                                        -- IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, "#7F8C8D", IF(total_frp = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frp_resultado_color, 
                                                        
                                                        -- IF(frpt = 1, reporteiluminacionpuntos_frpt, "N/A") AS frpt,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 = 0, "N/A", reporteiluminacionpuntos_frptmed1), "N/A") AS frptmed1,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 = 0, "N/A", reporteiluminacionpuntos_frptmed2), "N/A") AS frptmed2,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 = 0, "N/A", reporteiluminacionpuntos_frptmed3), "N/A") AS frptmed3,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed1 < 0 OR (reporteiluminacionpuntos_frptmed1 > 0 AND reporteiluminacionpuntos_frptmed1 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed1_color,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed2 < 0 OR (reporteiluminacionpuntos_frptmed2 > 0 AND reporteiluminacionpuntos_frptmed2 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed2_color,
                                                        -- IF(frpt = 1, IF(reporteiluminacionpuntos_frptmed3 < 0 OR (reporteiluminacionpuntos_frptmed3 > 0 AND reporteiluminacionpuntos_frptmed3 > reporteiluminacionpuntos_frpt), "#F00", "inherit"), "inherit") AS frptmed3_color,
                                                        -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "N/A", IF(total_frpt = 3, "Dentro de norma", "Fuera de norma")), "N/A") AS frpt_resultado, 
                                                        -- IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, "#7F8C8D", IF(total_frpt = 3, "#27AE60", "#C0392B")), "#7F8C8D") AS frpt_resultado_color,

                                                        (
                                                            CASE
                                                                WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "N/A"
                                                                ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "Dentro de norma", "Fuera de norma")
                                                            END
                                                        ) AS fr_resultado
                                                        -- ,(
                                                        --     CASE
                                                        --         WHEN (reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3 + reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0 THEN "#7F8C8D"
                                                        --         ELSE IF((IF(frp = 1, IF((reporteiluminacionpuntos_frpmed1 + reporteiluminacionpuntos_frpmed2 + reporteiluminacionpuntos_frpmed3) = 0, 1, IF(total_frp = 3, 1, 0)), 1) + IF(frpt = 1, IF((reporteiluminacionpuntos_frptmed1 + reporteiluminacionpuntos_frptmed2 + reporteiluminacionpuntos_frptmed1) = 0, 1, IF(total_frpt = 3, 1, 0)), 1)) = 2, "#27AE60", "#C0392B")
                                                        --     END
                                                        -- ) AS fr_resultado_color
                                                    FROM
                                                        (
                                                            SELECT
                                                                reporteiluminacionpuntos.id,
                                                                reporteiluminacionpuntos.proyecto_id,
                                                                reporteiluminacionarea.reporteiluminacionarea_instalacion,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_area_id,
                                                                reporteiluminacionarea.reporteiluminacionarea_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id,
                                                                reporteiluminacioncategoria.reporteiluminacioncategoria_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nombre,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_ficha,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_concepto,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopoe,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_fechaeval,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_horario3,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_lux,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3,
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_lux < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_lux = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS lux,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed1 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed2 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_luxmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_luxmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_luxmed3 >= reporteiluminacionpuntos_lux THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                ) AS total_lux,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed1mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed2mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_luxmed3mayor,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frp,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpmed3,
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_frp < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_frp = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS frp,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed1 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed2 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frpmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frpmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frpmed3 <= reporteiluminacionpuntos_frp THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                ) AS total_frp,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frpt,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed1,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed2,
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_frptmed3, 
                                                                (
                                                                    CASE
                                                                        WHEN reporteiluminacionpuntos_frpt < 0 THEN 0
                                                                        WHEN reporteiluminacionpuntos_frpt = 0 THEN 0
                                                                        ELSE 1
                                                                    END
                                                                ) AS frpt,
                                                                (
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed1 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed1 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed1 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed2 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed2 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed2 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                    +
                                                                    (
                                                                        CASE
                                                                            WHEN reporteiluminacionpuntos_frptmed3 < 0 THEN 0
                                                                            WHEN reporteiluminacionpuntos_frptmed3 = 0 THEN 1
                                                                            WHEN reporteiluminacionpuntos_frptmed3 <= reporteiluminacionpuntos_frpt THEN 1
                                                                            ELSE 0
                                                                        END
                                                                    )
                                                                )  AS total_frpt
                                                            FROM
                                                                reporteiluminacionpuntos
                                                                LEFT JOIN reporteiluminacionarea ON reporteiluminacionpuntos.reporteiluminacionpuntos_area_id = reporteiluminacionarea.id
                                                                LEFT JOIN reporteiluminacioncategoria ON reporteiluminacionpuntos.reporteiluminacionpuntos_categoria_id = reporteiluminacioncategoria.id
                                                            WHERE
                                                                reporteiluminacionpuntos.proyecto_id = ' . $proyecto_id . ' 
                                                                AND reporteiluminacionpuntos.registro_id = ' . $reporteiluminacion_id . '
                                                            ORDER BY
                                                                reporteiluminacionpuntos.reporteiluminacionpuntos_nopunto ASC
                                                        ) AS TABLA
                                                ) AS RESULTADO
                                            WHERE
                                                lux_resultado = "Fuera de norma" OR fr_resultado = "Fuera de norma"
                                            GROUP BY
                                                proyecto_id,
                                                reporteiluminacioncategoria_nombre');
            }


            $categorias_criticas = '';
            foreach ($categorias as $key => $value) {
                $categorias_criticas .= '● ' . $value->reporteiluminacioncategoria_nombre . '<w:br/>';
            }


            // dd($categorias_criticas);
            $plantillaword->setValue('CATEGORIAS', $categorias_criticas);


            //------------------------------------------


            if (($areas_poe + 0) == 1) {
                $resultados = DB::select('CALL sp_puntos_iluminacion_inform_b(?,?,?)', [1, $proyecto_id, $reporteiluminacion_id]);
            } else {
                $resultados = DB::select('CALL sp_puntos_iluminacion_inform_b(?,?,?)', [2, $proyecto_id, $reporteiluminacion_id]);
            }


            if (count($resultados) > 0) {
                $plantillaword->setValue('total_puntos', $resultados[0]->total_iluminacion);
                $plantillaword->setValue('total_recome', $resultados[0]->recomendaciones_total);
                $plantillaword->setValue('tot1_norma', $resultados[0]->total_iluminacion_dentronorma);
                $plantillaword->setValue('tot1_fuera', $resultados[0]->total_iluminacion_fueranorma);
                $plantillaword->setValue('tot2_norma', $resultados[0]->total_reflexion_dentronorma);
                $plantillaword->setValue('tot2_fuera', $resultados[0]->total_reflexion_fueranorma);
                $plantillaword->setValue('NIVEL_ILUMINACION', $resultados[0]->nivel_iluminacion);
            } else {
                $plantillaword->setValue('total_puntos', "0");
                $plantillaword->setValue('total_recome', "0");
                $plantillaword->setValue('tot1_norma', "0");
                $plantillaword->setValue('tot1_fuera', "0");
                $plantillaword->setValue('tot2_norma', "0");
                $plantillaword->setValue('tot2_fuera', "0");
                $plantillaword->setValue('NIVEL_ILUMINACION', "");
            }


            //------------------------------------------


            if ($request->grafica1) {
                $imagen_recibida = explode(',', $request->grafica1);
                $imagen_base64 = base64_decode($imagen_recibida[1]);
                $imagen_temporal_ruta = 'reportes/informes/dashboard_' . $agente_nombre . '1_' . $proyecto->proyecto_folio . '.jpg';
                Storage::put($imagen_temporal_ruta, $imagen_base64); // Guardar en storage


                if (Storage::exists($imagen_temporal_ruta)) {
                    $plantillaword->setImageValue('GRAFICA_1', array('path' => storage_path('app/' . $imagen_temporal_ruta), 'width' => 480, 'height' => 205, 'ratio' => false, 'borderColor' => '000000'));
                    Storage::delete($imagen_temporal_ruta); // Eliminar imagen temporal
                } else {
                    $plantillaword->setValue('GRAFICA_1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                }
            } else {
                $plantillaword->setValue('GRAFICA_1', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
            }


            if ($request->grafica_iluminacion) {
                $imagen_recibida = explode(',', $request->grafica_iluminacion);
                $imagen_base64 = base64_decode($imagen_recibida[1]);
                $imagen_temporal_ruta2 = 'reportes/informes/dashboard_' . $agente_nombre . '2_' . $proyecto->proyecto_folio . '.jpg';
                Storage::put($imagen_temporal_ruta2, $imagen_base64); // Guardar en storage


                if (Storage::exists($imagen_temporal_ruta2)) {
                    $plantillaword->setImageValue('GRAFICA_2', array('path' => storage_path('app/' . $imagen_temporal_ruta2), 'width' => 150, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    Storage::delete($imagen_temporal_ruta2); // Eliminar imagen temporal
                } else {
                    $plantillaword->setValue('GRAFICA_2', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                }
            } else {
                $plantillaword->setValue('GRAFICA_2', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
            }


            if ($request->grafica_reflexion) {
                $imagen_recibida = explode(',', $request->grafica_reflexion);
                $imagen_base64 = base64_decode($imagen_recibida[1]);
                $imagen_temporal_ruta3 = 'reportes/informes/dashboard_' . $agente_nombre . '3_' . $proyecto->proyecto_folio . '.jpg';
                Storage::put($imagen_temporal_ruta3, $imagen_base64); // Guardar en storage


                if (Storage::exists($imagen_temporal_ruta3)) {
                    $plantillaword->setImageValue('GRAFICA_3', array('path' => storage_path('app/' . $imagen_temporal_ruta3), 'width' => 150, 'height' => 150, 'ratio' => true, 'borderColor' => '000000'));
                    Storage::delete($imagen_temporal_ruta3); // Eliminar imagen temporal
                } else {
                    $plantillaword->setValue('GRAFICA_3', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
                }
            } else {
                $plantillaword->setValue('GRAFICA_2', 'NO SE ENCONTRÓ IMAGEN QUE MOSTRAR.');
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
                                                                            AND reporterecomendaciones.registro_id = ' . $reporteiluminacion_id . ' 
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
                                                        AND reporterecomendaciones.registro_id = ' . $reporteiluminacion_id . ' 
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
            if ($reporteiluminacion->reporteiluminacion_responsable1documento) {
                if (file_exists(storage_path('app/' . $reporteiluminacion->reporteiluminacion_responsable1documento))) {
                    $plantillaword->setImageValue('REPONSABLE1_DOCUMENTO', array('path' => storage_path('app/' . $reporteiluminacion->reporteiluminacion_responsable1documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE1_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE1', $reporteiluminacion->reporteiluminacion_responsable1 . "<w:br/>" . $reporteiluminacion->reporteiluminacion_responsable1cargo);


            // RESPONSABLE 2, FOTO DOCUMENTO
            if ($reporteiluminacion->reporteiluminacion_responsable2documento) {
                if (file_exists(storage_path('app/' . $reporteiluminacion->reporteiluminacion_responsable2documento))) {
                    $plantillaword->setImageValue('REPONSABLE2_DOCUMENTO', array('path' => storage_path('app/' . $reporteiluminacion->reporteiluminacion_responsable2documento), 'height' => 300, 'width' => 580, 'ratio' => true, 'borderColor' => '000000'));
                } else {
                    $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
                }
            } else {
                $plantillaword->setValue('REPONSABLE2_DOCUMENTO', 'FALTA CARGAR IMAGEN DESDE EL SISTEMA.');
            }


            $plantillaword->setValue('REPONSABLE2', $reporteiluminacion->reporteiluminacion_responsable2 . "<w:br/>" . $reporteiluminacion->reporteiluminacion_responsable2cargo);


            // TABLA ANEXO 1, Memoria fotográfica  - CREAR VARIABLES
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
                                                AND reporteplanoscarpetas.registro_id = ' . $reporteiluminacion_id);


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


            // TABLA ANEXO 3, EQUIPO UTILIZADO PARA LA MEDICION
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
                                    -- equipo.equipo_CertificadoPDF 
                                FROM
                                    reporteequiposutilizados
                                    LEFT JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                WHERE
                                    reporteequiposutilizados.proyecto_id = ' . $proyecto_id . ' 
                                    AND reporteequiposutilizados.registro_id = ' . $reporteiluminacion_id . ' 
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
            $plantillaword->saveAs(storage_path('app/reportes/informes/Informe_iluminacion_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //GUARDAR Y CREAR archivo word TEMPORAL

            // sleep(1);

            // ABRIR NUEVA PLANTILLA
            $plantillaword = new TemplateProcessor(storage_path('app/reportes/informes/Informe_iluminacion_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')); //Abrir plantilla TEMPORAL


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
                                                    CONCAT("Certificado de calibración - ", equipo.equipo_Descripcion, " (", equipo.equipo_Serie, ")") AS nombre,
                                                    equipos_documentos.RUTA_DOCUMENTO AS archivo
                                                FROM
                                                    reporteequiposutilizados
                                                INNER JOIN equipo ON reporteequiposutilizados.equipo_id = equipo.id
                                                INNER JOIN equipos_documentos ON equipos_documentos.EQUIPO_ID = equipo.id
                                                WHERE
                                                    reporteequiposutilizados.proyecto_id = ' . $proyecto_id . '
                                                    AND reporteequiposutilizados.registro_id = ' . $reporteiluminacion_id . '
                                                    AND reporteequiposutilizados.agente_nombre = "' . $agente_nombre . '"
                                                    AND equipos_documentos.DOCUMENTO_TIPO = 4
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
                                                    AND reporteanexos.registro_id = ' . $reporteiluminacion_id . '
                                                    AND reporteanexos.agente_nombre = "' . $agente_nombre . '"
                                            )
                                        ) AS ANEXO');



            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // GUARDAR Y DESCARGAR INFORME FINAL


            // GUARDAR
            $informe_nombre = 'Informe de Iluminación - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ').docx';
            $plantillaword->saveAs(storage_path('app/reportes/informes/' . $informe_nombre)); //crear archivo word


            // ELIMINAR TEMPORAL
            if (Storage::exists('reportes/informes/Informe_iluminacion_' . $proyecto->proyecto_folio . '_TEMPORAL.docx')) {
                Storage::delete('reportes/informes/Informe_iluminacion_' . $proyecto->proyecto_folio . '_TEMPORAL.docx');
            }


            /*

            //================================================================================
            // ZIP


            // Define Dir Folder
            $zip_ruta = storage_path('app/reportes/informes');
            
            // Zip File Name
            $zip_nombre = 'Informe de '.$agente_nombre.' '.$proyecto->proyecto_folio.' + Anexos.zip';
            
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


            // ELIMINAR word
            if (Storage::exists('reportes/informes/'.$informe_nombre))
            {
                Storage::delete('reportes/informes/'.$informe_nombre);
            }


            // DESCARGAR ZIP
            // if(file_exists($zip_rutacompleta))
            // {
                return response()->download($zip_rutacompleta, $zip_nombre, $headers)->deleteFileAfterSend(true);
            // }


            // DESCARGAR WORD
            // return response()->download(storage_path('app/reportes/informes/'.$informe_nombre))->deleteFileAfterSend(true);
            // return response()->json($dato);

            */


            //--------------------------------------------------------------------------------


            if (($request->crear_revision + 0) == 0) // Crear informe y guardar en carpeta temporal para descarga
            {
                //================================================================================
                // CREAR .ZIP INFORME

                // Define Dir Folder
                $zip_ruta = storage_path('app/reportes/informes');
                // Zip File Name
                $zip_nombre = 'Informe de Iluminación - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
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
                $zip_ruta_servidor = 'reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteiluminacion_id . '/revisiones/' . $request->ultimarevision_id;
                Storage::makeDirectory($zip_ruta_servidor); //crear directorio
                $zip_ruta_completa = storage_path('app/reportes/proyecto/' . $proyecto_id . '/' . $agente_nombre . '/' . $reporteiluminacion_id . '/revisiones/' . $request->ultimarevision_id);
                // Zip File Name
                $zip_nombre = 'Informe de Iluminación - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';
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
                                                AND reporterevisiones.agente_id = 4 -- Iluminación 
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
    public function reporteiluminacionworddescargar($proyecto_id, $revision_id, $ultima_revision)
    {
        $agente_nombre = 'Iluminación';


        if (($revision_id + 0) == ($ultima_revision + 0)) //Descargar y eliminar .ZIP de la carpeta temporal
        {
            $proyecto = proyectoModel::findOrFail($proyecto_id);

            $zip_nombre = 'Informe de Iluminación - ' . $proyecto->proyecto_folio . ' (' . $proyecto->proyecto_clienteinstalacion . ') + Anexos.zip';


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
